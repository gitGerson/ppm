<?php

use App\Models\DetailSantri;
use App\Support\SantriSheetSchema;
use Illuminate\Support\Defer\DeferredCallbackCollection;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Queue;

beforeEach(function () {
    config()->set('santri_sheet.api_enabled', true);
    config()->set('santri_sheet.api_token', str_repeat('a', 64));
    config()->set('santri_sheet.requests_per_minute', 120);
    $this->withToken(str_repeat('a', 64));
    Http::preventStrayRequests();
});

function sheetUpdatePayload(DetailSantri $santri, array $changes): array
{
    return ['base_revision' => SantriSheetSchema::revision($santri), 'changes' => $changes];
}

it('fails closed without valid integration authentication', function (bool $enabled, ?string $configuredToken, string $sentToken, int $status) {
    config()->set('santri_sheet.api_enabled', $enabled);
    config()->set('santri_sheet.api_token', $configuredToken);
    $this->withToken($sentToken)->getJson('/api/v1/sheet-sync/santris')->assertStatus($status);
})->with([
    'disabled' => [false, str_repeat('a', 64), str_repeat('a', 64), 404],
    'unconfigured' => [true, null, '', 401],
    'weak token' => [true, 'short', 'short', 401],
    'missing token' => [true, str_repeat('a', 64), '', 401],
    'wrong token' => [true, str_repeat('a', 64), str_repeat('b', 64), 401],
]);

it('exports paginated records and only approved values', function () {
    $santris = DetailSantri::factory()->count(3)->create(['NIK' => '0012345678901234', 'no_hp' => '08123456789']);
    $first = $this->getJson('/api/v1/sheet-sync/santris?per_page=2')
        ->assertOk()->assertJsonCount(2, 'data')
        ->assertJsonPath('data.0.id', $santris[0]->id)
        ->assertJsonPath('data.0.values.NIK', '0012345678901234')
        ->assertJsonPath('data.0.values.no_hp', '08123456789')
        ->assertJsonMissingPath('data.0.values.image_ktp_path')
        ->assertJsonMissingPath('data.0.values.sheet_hash')
        ->assertJsonMissingPath('data.0.values.pendaftaran_notified_at');
    expect($first->headers->get('Cache-Control'))->toContain('no-store');
    $this->getJson('/api/v1/sheet-sync/santris?per_page=2&cursor='.urlencode($first->json('next_cursor')))
        ->assertOk()->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.id', $santris[2]->id)
        ->assertJsonPath('next_cursor', null);
});

it('rejects invalid pagination', function (string $query) {
    $this->getJson('/api/v1/sheet-sync/santris?'.$query)->assertUnprocessable();
})->with(['per_page=101', 'per_page=0', 'cursor=garbage', 'cursor[]=1']);

it('updates allowed fields and returns the saved revision', function () {
    $santri = DetailSantri::factory()->create();
    $response = $this->patchJson('/api/v1/sheet-sync/santris/'.$santri->id, sheetUpdatePayload($santri, [
        'nama_lengkap' => 'Ahmad Fauzi',
        'NIK' => '0012345678901234',
        'is_mondok' => true,
        'tanggal_lahir' => '2005-01-02',
        'penghasilan_ayah' => 5000000,
    ]))->assertOk()->assertJsonPath('data.values.nama_lengkap', 'Ahmad Fauzi')
        ->assertJsonPath('data.values.NIK', '0012345678901234')
        ->assertJsonPath('data.values.is_mondok', true);
    expect($response->json('data.revision'))->toBe(SantriSheetSchema::revision($santri->refresh()));
    Http::assertNothingSent();
});

it('rejects invalid values atomically', function (string $field, mixed $value) {
    $santri = DetailSantri::factory()->create(['nama_lengkap' => 'Original']);
    $payload = sheetUpdatePayload($santri, ['nama_lengkap' => 'Changed', $field => $value]);
    $this->patchJson('/api/v1/sheet-sync/santris/'.$santri->id, $payload)
        ->assertUnprocessable()->assertJsonValidationErrors('changes.'.$field);
    expect($santri->refresh()->nama_lengkap)->toBe('Original');
})->with([
    'required name' => ['nama_lengkap', null],
    'long name' => ['nama_lengkap', str_repeat('a', 256)],
    'NIK length' => ['NIK', str_repeat('1', 17)],
    'numeric identifier' => ['NIK', 123],
    'email' => ['email', 'invalid'],
    'enum' => ['jenis_kelamin', 'Lakilaki'],
    'date rollover' => ['tanggal_lahir', '2005-02-30'],
    'year lower bound' => ['tahun_masuk_ppm', 1900],
    'year upper bound' => ['tahun_masuk_ppm', 2156],
    'negative integer' => ['anak_ke', -1],
    'tiny integer overflow' => ['jumlah_saudara', 256],
    'small integer overflow' => ['tinggi_badan', 65536],
    'income overflow' => ['penghasilan_ayah', 4294967296],
    'fraction' => ['berat_badan', 2.5],
    'invalid boolean' => ['is_mondok', 'perhaps'],
]);

it('rejects ownership and unknown column writes despite globally unguarded models', function (string $field) {
    $santri = DetailSantri::factory()->create();
    $this->patchJson('/api/v1/sheet-sync/santris/'.$santri->id, sheetUpdatePayload($santri, [$field => '1']))
        ->assertUnprocessable()->assertJsonValidationErrors('changes');
})->with(['id', 'user_id', 'sheet_hash', 'image_ktp_path', 'password', 'updated_at']);

it('allows nullable cells to be cleared', function () {
    $santri = DetailSantri::factory()->create(['NIK' => '123']);
    $this->patchJson('/api/v1/sheet-sync/santris/'.$santri->id, sheetUpdatePayload($santri, ['NIK' => null]))
        ->assertOk()->assertJsonPath('data.values.NIK', null);
});

it('detects a concurrent database change even within the same second', function () {
    $santri = DetailSantri::factory()->create(['nama_lengkap' => 'Initial']);
    $payload = sheetUpdatePayload($santri, ['nama_lengkap' => 'Sheet']);
    $santri->update(['nama_lengkap' => 'Database']);
    $this->patchJson('/api/v1/sheet-sync/santris/'.$santri->id, $payload)
        ->assertConflict()->assertJsonPath('data.values.nama_lengkap', 'Database');
    expect($santri->refresh()->nama_lengkap)->toBe('Database');
});

it('acknowledges a lost-response retry without firing another update', function () {
    $santri = DetailSantri::factory()->create(['nama_lengkap' => 'Initial']);
    $payload = sheetUpdatePayload($santri, ['nama_lengkap' => 'Sheet']);
    $this->patchJson('/api/v1/sheet-sync/santris/'.$santri->id, $payload)->assertOk();
    Event::fake(['eloquent.updated: '.DetailSantri::class]);
    $this->patchJson('/api/v1/sheet-sync/santris/'.$santri->id, $payload)->assertOk();
    Event::assertNotDispatched('eloquent.updated: '.DetailSantri::class);
});

it('returns not found for deleted records and has no create or delete API', function () {
    $santri = DetailSantri::factory()->create();
    $payload = sheetUpdatePayload($santri, ['nama_lengkap' => 'Sheet']);
    $santri->delete();
    $this->patchJson('/api/v1/sheet-sync/santris/'.$santri->id, $payload)->assertNotFound();
    $this->postJson('/api/v1/sheet-sync/santris', $payload)->assertStatus(405);
    $this->deleteJson('/api/v1/sheet-sync/santris/'.$santri->id)->assertStatus(405);
});

it('throttles the integration across requests', function () {
    config()->set('santri_sheet.requests_per_minute', 1);
    $this->getJson('/api/v1/sheet-sync/santris')->assertOk();
    $this->getJson('/api/v1/sheet-sync/santris')->assertTooManyRequests();
});

it('does not queue or defer outbound sync for application changes', function () {
    Queue::fake();
    $santri = DetailSantri::factory()->create();
    $santri->update(['nama_lengkap' => 'Updated']);
    $santri->delete();
    Queue::assertNothingPushed();
    expect(app(DeferredCallbackCollection::class))->toHaveCount(0);
    Http::assertNothingSent();
});
