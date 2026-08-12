<?php

use App\Jobs\RewriteSantriSheet;
use App\Jobs\SyncDetailSantriToSheet;
use App\Models\DetailSantri;
use App\Support\DetailSantriSheetSync;
use App\Support\SantriSheetSchema;
use Illuminate\Support\Facades\Queue;
use Revolution\Google\Sheets\Facades\Sheets;
use Tests\Support\FakeSheets;

const SHEET = 'DetailSantri';

beforeEach(function () {
    config()->set('santri_sheet.enabled', true);
    config()->set('santri_sheet.spreadsheet_id', 'test-spreadsheet');
    config()->set('santri_sheet.sheet_name', SHEET);
    config()->set('santri_sheet.conflict_winner', 'database');

    $this->fake = new FakeSheets([SHEET => [SantriSheetSchema::headers()]]);
    Sheets::swap($this->fake);
});

/**
 * Column offset of a database column within the sheet row.
 */
function columnIndex(string $dbColumn): int
{
    return (int) array_search($dbColumn, SantriSheetSchema::dbColumns(), true);
}

function sheetRowFor(FakeSheets $fake, DetailSantri $santri): ?array
{
    foreach ($fake->rows(SHEET) as $row) {
        if (isset($row[0]) && (int) ltrim((string) $row[0], "'") === $santri->getKey()) {
            return $row;
        }
    }

    return null;
}

it('queues a push when a santri is created', function () {
    Queue::fake();

    $santri = DetailSantri::factory()->create();

    Queue::assertPushed(
        SyncDetailSantriToSheet::class,
        fn (SyncDetailSantriToSheet $job): bool => $job->detailSantriId === $santri->getKey()
    );
});

it('does not queue anything while the sync is disabled', function () {
    config()->set('santri_sheet.enabled', false);
    Queue::fake();

    DetailSantri::factory()->create();

    Queue::assertNothingPushed();
});

it('queues a full rewrite on delete so the row does not leave a gap', function () {
    $santri = DetailSantri::factory()->create();

    Queue::fake();
    $santri->delete();

    Queue::assertPushed(RewriteSantriSheet::class);
});

it('appends a new santri row and records the sync point', function () {
    $santri = DetailSantri::factory()->create(['nama_lengkap' => 'Ahmad Fauzi']);

    app(DetailSantriSheetSync::class)->pushOne($santri);

    $row = sheetRowFor($this->fake, $santri);

    expect($row)->not->toBeNull()
        ->and($row[columnIndex('nama_lengkap')])->toBe('Ahmad Fauzi');

    $santri->refresh();
    expect($santri->sheet_hash)->not->toBeNull()
        ->and($santri->sheet_synced_at)->not->toBeNull();
});

it('updates the existing row instead of appending a duplicate', function () {
    $santri = DetailSantri::factory()->create(['nama_lengkap' => 'Ahmad Fauzi']);
    $sync = app(DetailSantriSheetSync::class);

    $sync->pushOne($santri);
    $santri->update(['nama_lengkap' => 'Ahmad Fauzi Rahman']);
    $sync->pushOne($santri);

    // header + exactly one santri row
    expect($this->fake->rows(SHEET))->toHaveCount(2)
        ->and(sheetRowFor($this->fake, $santri)[columnIndex('nama_lengkap')])
        ->toBe('Ahmad Fauzi Rahman');
});

it('writes identifier columns as text so leading zeros survive', function () {
    $santri = DetailSantri::factory()->create(['no_hp' => '081234567890']);

    app(DetailSantriSheetSync::class)->pushOne($santri);

    expect(sheetRowFor($this->fake, $santri)[columnIndex('no_hp')])
        ->toBe("'081234567890");
});

it('applies an edit made in the sheet back to the database', function () {
    $santri = DetailSantri::factory()->create(['nama_lengkap' => 'Ahmad Fauzi']);
    $sync = app(DetailSantriSheetSync::class);
    $sync->pushOne($santri);

    $rowNumber = 1;
    $this->fake->tabs[SHEET][$rowNumber][columnIndex('nama_lengkap')] = 'Ahmad Fauzi Rahman';

    $result = $sync->pull();

    expect($result['applied'])->toBe(1)
        ->and($santri->refresh()->nama_lengkap)->toBe('Ahmad Fauzi Rahman');
});

it('does not echo a sheet-originated change back to the sheet', function () {
    $santri = DetailSantri::factory()->create(['nama_lengkap' => 'Ahmad Fauzi']);
    $sync = app(DetailSantriSheetSync::class);
    $sync->pushOne($santri);

    $this->fake->tabs[SHEET][1][columnIndex('nama_lengkap')] = 'Ahmad Fauzi Rahman';

    Queue::fake();
    $sync->pull();

    Queue::assertNotPushed(SyncDetailSantriToSheet::class);
});

it('leaves a row alone when neither side changed', function () {
    $santri = DetailSantri::factory()->create();
    $sync = app(DetailSantriSheetSync::class);
    $sync->pushOne($santri);

    $result = $sync->pull();

    expect($result['applied'])->toBe(0)
        ->and($result['pushed'])->toBe(0)
        ->and($result['conflicts'])->toBe(0);
});

it('lets the database win when both sides changed', function () {
    $santri = DetailSantri::factory()->create(['nama_lengkap' => 'Ahmad Fauzi']);
    $sync = app(DetailSantriSheetSync::class);
    $sync->pushOne($santri);

    // Both sides move before the next sync. The push is left on the queue so
    // the conflict is still outstanding when the scheduled pull runs.
    $this->fake->tabs[SHEET][1][columnIndex('nama_lengkap')] = 'Dari Sheet';
    $this->travel(1)->minutes();
    Queue::fake();
    $santri->update(['nama_lengkap' => 'Dari Database']);

    $result = $sync->pull();

    expect($result['conflicts'])->toBe(1)
        ->and($santri->refresh()->nama_lengkap)->toBe('Dari Database')
        ->and(sheetRowFor($this->fake, $santri)[columnIndex('nama_lengkap')])->toBe('Dari Database');
});

it('lets the sheet win when configured that way', function () {
    config()->set('santri_sheet.conflict_winner', 'sheet');

    $santri = DetailSantri::factory()->create(['nama_lengkap' => 'Ahmad Fauzi']);
    $sync = app(DetailSantriSheetSync::class);
    $sync->pushOne($santri);

    $this->fake->tabs[SHEET][1][columnIndex('nama_lengkap')] = 'Dari Sheet';
    $this->travel(1)->minutes();
    Queue::fake();
    $santri->update(['nama_lengkap' => 'Dari Database']);

    $result = $sync->pull();

    expect($result['conflicts'])->toBe(1)
        ->and($santri->refresh()->nama_lengkap)->toBe('Dari Sheet');
});

it('does not let a queued push silently overwrite a sheet edit', function () {
    $santri = DetailSantri::factory()->create(['nama_lengkap' => 'Ahmad Fauzi']);
    $sync = app(DetailSantriSheetSync::class);
    $sync->pushOne($santri);

    // Sheet edited, then the app saves and the push job finally runs.
    $this->fake->tabs[SHEET][1][columnIndex('nama_lengkap')] = 'Dari Sheet';
    config()->set('santri_sheet.conflict_winner', 'sheet');
    $santri->forceFill(['nama_lengkap' => 'Dari Database'])->saveQuietly();

    $sync->pushOne($santri);

    expect($santri->refresh()->nama_lengkap)->toBe('Dari Sheet');
});

it('overwrites a sheet edit on push when the database is the configured winner', function () {
    $santri = DetailSantri::factory()->create(['nama_lengkap' => 'Ahmad Fauzi']);
    $sync = app(DetailSantriSheetSync::class);
    $sync->pushOne($santri);

    $this->fake->tabs[SHEET][1][columnIndex('nama_lengkap')] = 'Dari Sheet';
    $santri->forceFill(['nama_lengkap' => 'Dari Database'])->saveQuietly();

    $sync->pushOne($santri);

    expect(sheetRowFor($this->fake, $santri)[columnIndex('nama_lengkap')])->toBe('Dari Database')
        ->and($santri->refresh()->nama_lengkap)->toBe('Dari Database');
});

it('rejects an invalid value without wiping the stored one', function () {
    $santri = DetailSantri::factory()->create([
        'jenis_kelamin' => 'Laki-laki',
        'nama_lengkap' => 'Ahmad Fauzi',
    ]);
    $sync = app(DetailSantriSheetSync::class);
    $sync->pushOne($santri);

    // A typo in the enum column, alongside a perfectly good name edit.
    $this->fake->tabs[SHEET][1][columnIndex('jenis_kelamin')] = 'Lakilaki';
    $this->fake->tabs[SHEET][1][columnIndex('nama_lengkap')] = 'Ahmad Fauzi Rahman';

    $sync->pull();
    $santri->refresh();

    expect($santri->jenis_kelamin)->toBe('Laki-laki')
        ->and($santri->nama_lengkap)->toBe('Ahmad Fauzi Rahman')
        // the corrected value is written back over the typo
        ->and(sheetRowFor($this->fake, $santri)[columnIndex('jenis_kelamin')])->toBe('Laki-laki');
});

it('rejects unsigned tiny integer values outside the database range', function (string $field) {
    $santri = DetailSantri::factory()->create([$field => 2]);
    $sync = app(DetailSantriSheetSync::class);
    $sync->pushOne($santri);

    $this->fake->tabs[SHEET][1][columnIndex($field)] = '256';

    $sync->pull();
    $santri->refresh();

    expect($santri->getAttribute($field))->toBe(2)
        ->and(sheetRowFor($this->fake, $santri)[columnIndex($field)])->toBe('2');
})->with([
    'anak ke' => 'anak_ke',
    'jumlah saudara' => 'jumlah_saudara',
]);

it('does not re-apply a rejected cell as a fresh edit on the next run', function () {
    $santri = DetailSantri::factory()->create(['jenis_kelamin' => 'Laki-laki']);
    $sync = app(DetailSantriSheetSync::class);
    $sync->pushOne($santri);

    $this->fake->tabs[SHEET][1][columnIndex('jenis_kelamin')] = 'Lakilaki';
    $sync->pull();

    $result = $sync->pull();

    expect($result['applied'])->toBe(0)
        ->and($result['conflicts'])->toBe(0);
});

it('parses indonesian formatted numbers and boolean words', function () {
    $santri = DetailSantri::factory()->create([
        'penghasilan_ayah' => 1_000_000,
        'is_mondok' => false,
    ]);
    $sync = app(DetailSantriSheetSync::class);
    $sync->pushOne($santri);

    $this->fake->tabs[SHEET][1][columnIndex('penghasilan_ayah')] = 'Rp 5.000.000';
    $this->fake->tabs[SHEET][1][columnIndex('is_mondok')] = 'Ya';

    $sync->pull();
    $santri->refresh();

    expect($santri->penghasilan_ayah)->toBe(5000000)
        ->and((bool) $santri->is_mondok)->toBeTrue();
});

it('skips sheet rows that have no id', function () {
    DetailSantri::factory()->create();
    $sync = app(DetailSantriSheetSync::class);
    $sync->pushOne(DetailSantri::query()->first());

    $manualRow = array_fill(0, count(SantriSheetSchema::dbColumns()), '');
    $manualRow[columnIndex('nama_lengkap')] = 'Ditulis Manual';
    $this->fake->tabs[SHEET][] = $manualRow;

    $result = $sync->pull();

    expect($result['skipped'])->toHaveCount(1)
        ->and($result['skipped'][0])->toContain('tanpa ID')
        ->and(DetailSantri::query()->count())->toBe(1);
});

it('reports sheet rows pointing at a deleted santri', function () {
    $sync = app(DetailSantriSheetSync::class);

    $orphanRow = array_fill(0, count(SantriSheetSchema::dbColumns()), '');
    $orphanRow[0] = '9999';
    $this->fake->tabs[SHEET][] = $orphanRow;

    $result = $sync->pull();

    expect($result['skipped'][0])->toContain('9999');
});

it('rewrites the whole sheet from the database', function () {
    DetailSantri::factory()->count(3)->create();
    $this->fake->tabs[SHEET][] = ['baris', 'sampah'];

    $written = app(DetailSantriSheetSync::class)->pushAll();

    expect($written)->toBe(3)
        ->and($this->fake->rows(SHEET))->toHaveCount(4)
        ->and($this->fake->rows(SHEET)[0])->toBe(SantriSheetSchema::headers());
});

it('does nothing at all when the sync is disabled', function () {
    config()->set('santri_sheet.enabled', false);

    $santri = DetailSantri::factory()->create();
    $sync = app(DetailSantriSheetSync::class);

    $sync->pushOne($santri);

    expect($this->fake->calls)->toBeEmpty()
        ->and($sync->pull()['applied'])->toBe(0);
});
