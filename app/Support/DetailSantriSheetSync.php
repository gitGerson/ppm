<?php

namespace App\Support;

use App\Models\DetailSantri;
use Illuminate\Support\Facades\Log;
use Revolution\Google\Sheets\Facades\Sheets;

/**
 * Two-way sync between the detail_santris table and a Google Sheet.
 *
 * The sheet's first column holds the DetailSantri primary key, which is what
 * pairs a sheet row with a model. Rows without an ID are ignored: creating a
 * santri needs a user_id that only registration can supply.
 */
class DetailSantriSheetSync
{
    /**
     * Set while a sheet-originated change is being written to the database, so
     * the observer does not turn around and push that same change back.
     */
    private static bool $applyingRemoteChange = false;

    public static function isApplyingRemoteChange(): bool
    {
        return self::$applyingRemoteChange;
    }

    /**
     * @template TReturn
     *
     * @param  callable(): TReturn  $callback
     * @return TReturn
     */
    public static function withoutPush(callable $callback): mixed
    {
        self::$applyingRemoteChange = true;

        try {
            return $callback();
        } finally {
            self::$applyingRemoteChange = false;
        }
    }

    public static function isEnabled(): bool
    {
        return (bool) config('santri_sheet.enabled')
            && filled(config('santri_sheet.spreadsheet_id'));
    }

    /**
     * Write one santri's current state into its sheet row, appending if the row
     * is not there yet.
     */
    public function pushOne(DetailSantri $santri): void
    {
        if (! self::isEnabled()) {
            return;
        }

        $this->ensureSheetExists();

        $located = $this->locateRow($santri->getKey());

        if ($located === null) {
            $this->writeRow($santri, null);

            return;
        }

        $sheetHash = SantriSheetSchema::hashSheetRow($located['row']);

        // The sheet moved since our last sync, so this is not a plain push —
        // writing blind here would silently erase whoever edited the sheet.
        if ($santri->sheet_hash !== null && $sheetHash !== $santri->sheet_hash) {
            Log::warning('Konflik sinkronisasi Google Sheet DetailSantri saat push', [
                'detail_santri_id' => $santri->getKey(),
                'sheet_row' => $located['number'],
                'winner' => config('santri_sheet.conflict_winner'),
            ]);

            if (config('santri_sheet.conflict_winner') === 'sheet') {
                $this->applySheetRow($santri, $located['row'], $located['number'], $sheetHash);

                return;
            }
        }

        $this->writeRow($santri, $located['number']);
    }

    /**
     * Unconditional write of a santri into a sheet row. The caller owns the
     * decision that this side wins; this only carries it out.
     */
    private function writeRow(DetailSantri $santri, ?int $rowNumber): void
    {
        $row = SantriSheetSchema::toSheetRow($santri);

        if ($rowNumber === null) {
            $this->sheet()->append([$row], valueInputOption: 'USER_ENTERED');
        } else {
            $this->sheet()
                ->range($this->rowRange($rowNumber))
                ->update([$row], valueInputOption: 'USER_ENTERED');
        }

        $this->markSynced($santri, SantriSheetSchema::hashModel($santri));
    }

    /**
     * Rewrite the whole sheet from the database. Also the delete path: removing
     * a row mid-sheet is not something the values API can do, so the full
     * rewrite closes the gap instead.
     *
     * @return int number of santri rows written
     */
    public function pushAll(): int
    {
        if (! self::isEnabled()) {
            return 0;
        }

        $this->ensureSheetExists();

        $rows = [];
        $hashes = [];

        DetailSantri::query()
            ->orderBy('id')
            ->each(function (DetailSantri $santri) use (&$rows, &$hashes): void {
                $rows[] = SantriSheetSchema::toSheetRow($santri);
                $hashes[$santri->getKey()] = SantriSheetSchema::hashModel($santri);
            });

        $this->sheet()->clear();
        $this->sheet()->append(
            array_merge([SantriSheetSchema::headers()], $rows),
            valueInputOption: 'USER_ENTERED'
        );

        DetailSantri::query()->each(function (DetailSantri $santri) use ($hashes): void {
            if (isset($hashes[$santri->getKey()])) {
                $this->markSynced($santri, $hashes[$santri->getKey()]);
            }
        });

        return count($rows);
    }

    /**
     * Read the sheet and settle every row against the database.
     *
     * @return array{applied: int, pushed: int, conflicts: int, skipped: array<int, string>}
     */
    public function pull(): array
    {
        if (! self::isEnabled()) {
            return ['applied' => 0, 'pushed' => 0, 'conflicts' => 0, 'skipped' => []];
        }

        $this->ensureSheetExists();

        $values = $this->sheet()->all();
        array_shift($values); // header

        $applied = 0;
        $pushed = 0;
        $conflicts = 0;
        $skipped = [];

        foreach ($values as $offset => $row) {
            $rowNumber = $offset + 2; // 1-indexed sheet, plus the header row
            $id = isset($row[0]) ? trim(ltrim((string) $row[0], "'")) : '';

            if ($id === '' || ! ctype_digit($id)) {
                if (filled(array_filter($row))) {
                    $skipped[] = "Baris {$rowNumber}: tanpa ID, diabaikan";
                }

                continue;
            }

            $santri = DetailSantri::query()->find((int) $id);

            if ($santri === null) {
                $skipped[] = "Baris {$rowNumber}: santri ID {$id} tidak ditemukan";

                continue;
            }

            $outcome = $this->settleRow($santri, $row, $rowNumber);

            if (str_starts_with($outcome, 'conflict_')) {
                $conflicts++;
            }

            if (str_ends_with($outcome, 'applied')) {
                $applied++;
            } elseif (str_ends_with($outcome, 'pushed')) {
                $pushed++;
            }
        }

        return ['applied' => $applied, 'pushed' => $pushed, 'conflicts' => $conflicts, 'skipped' => $skipped];
    }

    /**
     * Decide which side of a single row wins and act on it.
     *
     * @param  array<int, string|null>  $row
     * @return 'applied'|'pushed'|'conflict_applied'|'conflict_pushed'|'unchanged'
     */
    private function settleRow(DetailSantri $santri, array $row, int $rowNumber): string
    {
        $sheetHash = SantriSheetSchema::hashSheetRow($row);

        $sheetChanged = $sheetHash !== $santri->sheet_hash;
        $databaseChanged = $santri->sheet_synced_at === null
            || $santri->updated_at?->greaterThan($santri->sheet_synced_at);

        if (! $sheetChanged && ! $databaseChanged) {
            return 'unchanged';
        }

        if ($sheetChanged && $databaseChanged) {
            Log::warning('Konflik sinkronisasi Google Sheet DetailSantri', [
                'detail_santri_id' => $santri->getKey(),
                'sheet_row' => $rowNumber,
                'winner' => config('santri_sheet.conflict_winner'),
            ]);

            if (config('santri_sheet.conflict_winner') === 'database') {
                $this->writeRow($santri, $rowNumber);

                return 'conflict_pushed';
            }

            $this->applySheetRow($santri, $row, $rowNumber, $sheetHash);

            return 'conflict_applied';
        }

        if ($sheetChanged) {
            $this->applySheetRow($santri, $row, $rowNumber, $sheetHash);

            return 'applied';
        }

        // The winner is already decided here, so write directly rather than
        // going through pushOne and paying for a second conflict check.
        $this->writeRow($santri, $rowNumber);

        return 'pushed';
    }

    /**
     * @param  array<int, string|null>  $row
     */
    private function applySheetRow(DetailSantri $santri, array $row, int $rowNumber, string $sheetHash): void
    {
        ['attributes' => $attributes, 'errors' => $errors] = SantriSheetSchema::fromSheetRow($row);

        if ($errors !== []) {
            Log::warning('Nilai tidak valid pada baris Google Sheet DetailSantri', [
                'detail_santri_id' => $santri->getKey(),
                'sheet_row' => $rowNumber,
                'errors' => $errors,
            ]);
        }

        self::withoutPush(function () use ($santri, $attributes): void {
            $santri->fill($attributes)->save();
        });

        // Rehash from the saved model: a rejected cell means the stored value
        // still differs from the sheet, and hashing the model keeps the next
        // run from re-reading that same bad cell as a fresh edit.
        $this->markSynced($santri, $errors === [] ? $sheetHash : SantriSheetSchema::hashModel($santri));

        if ($errors !== []) {
            // Put the corrected values back so the sheet stops showing the typo.
            // writeRow, not pushOne: the sheet is known-stale, and re-entering
            // the conflict check here would recurse.
            $this->writeRow($santri, $rowNumber);
        }
    }

    /**
     * Record the sync point without firing model events or moving updated_at —
     * bumping it would make the row look database-edited on the next run.
     */
    private function markSynced(DetailSantri $santri, string $hash): void
    {
        $timestamps = $santri->timestamps;
        $santri->timestamps = false;

        // forceFill, not update: these columns are deliberately not fillable so
        // no form can ever forge a sync state.
        $santri->forceFill([
            'sheet_hash' => $hash,
            'sheet_synced_at' => $santri->updated_at ?? now(),
        ])->saveQuietly();

        $santri->timestamps = $timestamps;
    }

    private function rowRange(int $rowNumber): string
    {
        return "A{$rowNumber}:".SantriSheetSchema::lastColumnLetter().$rowNumber;
    }

    /**
     * Find the sheet row holding the given santri ID.
     *
     * Returns the row contents alongside its number, in one read, because the
     * caller needs to know whether that row changed before overwriting it.
     *
     * @return array{number: int, row: array<int, string>}|null
     */
    private function locateRow(int $id): ?array
    {
        $values = $this->sheet()->all();

        foreach ($values as $offset => $row) {
            if ($offset === 0) {
                continue; // header
            }

            $cell = isset($row[0]) ? trim(ltrim((string) $row[0], "'")) : '';

            if ($cell !== '' && ctype_digit($cell) && (int) $cell === $id) {
                return ['number' => $offset + 1, 'row' => $row];
            }
        }

        return null;
    }

    private function ensureSheetExists(): void
    {
        $name = (string) config('santri_sheet.sheet_name');
        $spreadsheet = Sheets::spreadsheet((string) config('santri_sheet.spreadsheet_id'));

        if (in_array($name, $spreadsheet->sheetList(), true)) {
            return;
        }

        $spreadsheet->addSheet($name);
        $spreadsheet->sheet($name)->range('')->append(
            [SantriSheetSchema::headers()],
            valueInputOption: 'USER_ENTERED'
        );
    }

    /**
     * The Sheets client is a scoped singleton and never clears its own range,
     * so a range set by an earlier call would silently narrow the next one.
     * Every access starts from a blank range on purpose.
     */
    private function sheet(): mixed
    {
        return Sheets::spreadsheet((string) config('santri_sheet.spreadsheet_id'))
            ->sheet((string) config('santri_sheet.sheet_name'))
            ->range('');
    }
}
