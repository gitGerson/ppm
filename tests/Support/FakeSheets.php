<?php

namespace Tests\Support;

/**
 * In-memory stand-in for the Google Sheets client.
 *
 * Mirrors the fluent surface DetailSantriSheetSync uses, including the part
 * that bites in production: the range is sticky until something resets it.
 */
class FakeSheets
{
    /** @var array<string, array<int, array<int, string>>> */
    public array $tabs = [];

    /** @var array<int, array{method: string, sheet: string|null, range: string|null}> */
    public array $calls = [];

    private ?string $sheet = null;

    private ?string $range = null;

    /**
     * @param  array<string, array<int, array<int, string>>>  $tabs
     */
    public function __construct(array $tabs = [])
    {
        $this->tabs = $tabs;
    }

    public function spreadsheet(string $spreadsheetId): self
    {
        return $this;
    }

    public function sheet(string $sheet): self
    {
        $this->sheet = $sheet;

        return $this;
    }

    public function range(string $range): self
    {
        $this->range = $range === '' ? null : $range;

        return $this;
    }

    /**
     * @return array<int, string>
     */
    public function sheetList(): array
    {
        return array_values(array_keys($this->tabs));
    }

    public function addSheet(string $sheetTitle): void
    {
        $this->tabs[$sheetTitle] ??= [];
    }

    /**
     * @return array<int, array<int, string>>
     */
    public function all(): array
    {
        $this->record('all');

        $rows = $this->tabs[$this->sheet] ?? [];

        if ($this->range === null) {
            return $rows;
        }

        // Only the ID-column probe needs range-aware reads.
        if ($this->range === 'A2:A') {
            return array_map(
                fn (array $row): array => [$row[0] ?? ''],
                array_slice($rows, 1)
            );
        }

        return $rows;
    }

    /**
     * @param  array<int, array<int, string>>  $values
     */
    public function append(array $values, string $valueInputOption = 'RAW', string $insertDataOption = 'OVERWRITE'): void
    {
        $this->record('append');

        $this->tabs[$this->sheet] ??= [];

        foreach ($values as $row) {
            $this->tabs[$this->sheet][] = $row;
        }
    }

    /**
     * @param  array<int, array<int, string>>  $value
     */
    public function update(array $value, string $valueInputOption = 'RAW'): void
    {
        $this->record('update');

        if ($this->range === null || ! preg_match('/^A(\d+):/', $this->range, $matches)) {
            return;
        }

        $this->tabs[$this->sheet][(int) $matches[1] - 1] = $value[0];
    }

    public function clear(): void
    {
        $this->record('clear');

        if ($this->range === null) {
            $this->tabs[$this->sheet] = [];
        }
    }

    /**
     * @return array<int, array<int, string>>
     */
    public function rows(string $sheet): array
    {
        return $this->tabs[$sheet] ?? [];
    }

    private function record(string $method): void
    {
        $this->calls[] = ['method' => $method, 'sheet' => $this->sheet, 'range' => $this->range];
    }
}
