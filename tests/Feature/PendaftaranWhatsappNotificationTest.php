<?php

use App\Actions\SendPendaftaranWhatsapp;
use App\Models\DetailSantri;
use App\Models\User;
use App\Support\Fonnte;
use Illuminate\Support\Facades\Http;

beforeEach(function () {
    config()->set('fonnte.enabled', true);
    config()->set('fonnte.token', 'test-token');
    config()->set('fonnte.endpoint', 'https://api.fonnte.com/send');
    config()->set('fonnte.country_code', '62');
});

function submitPendaftaran(User $user, array $overrides = [])
{
    return test()->actingAs($user)->post(route('pendaftaran.store'), array_merge([
        'nama_lengkap' => 'Santri Baru',
        'no_hp' => '081234567890',
    ], $overrides));
}

function fakeFonnteOk(): void
{
    Http::fake([
        'api.fonnte.com/*' => Http::response(['status' => true, 'id' => ['1']]),
    ]);
}

test('first pendaftaran submit sends the whatsapp confirmation inline', function () {
    fakeFonnteOk();
    $user = User::factory()->create();

    submitPendaftaran($user)
        ->assertRedirect(route('pendaftaran'))
        ->assertSessionHas('status');

    Http::assertSent(fn ($request): bool => $request['target'] === '6281234567890');

    $santri = DetailSantri::query()->where('user_id', $user->id)->firstOrFail();
    expect($santri->pendaftaran_notified_at)->not->toBeNull();
});

test('a later edit does not send the confirmation again', function () {
    fakeFonnteOk();
    $user = User::factory()->create();
    submitPendaftaran($user);

    Http::fake();
    submitPendaftaran($user, ['nama_panggilan' => 'Santri']);

    Http::assertNothingSent();
});

test('nothing is sent while fonnte is disabled', function () {
    config()->set('fonnte.enabled', false);
    Http::fake();

    submitPendaftaran(User::factory()->create());

    Http::assertNothingSent();
});

test('a failed send still lets the submit succeed and leaves the santri unnotified', function () {
    Http::fake([
        'api.fonnte.com/*' => Http::response(['status' => false, 'reason' => 'token invalid']),
    ]);
    $user = User::factory()->create();

    submitPendaftaran($user)
        ->assertRedirect(route('pendaftaran'))
        ->assertSessionHas('status');

    $santri = DetailSantri::query()->where('user_id', $user->id)->firstOrFail();
    expect($santri->pendaftaran_notified_at)->toBeNull();
});

test('the action sends the message and marks the santri as notified', function () {
    fakeFonnteOk();

    $santri = DetailSantri::factory()->create(['no_hp' => '081234567890']);

    expect(app(SendPendaftaranWhatsapp::class)->send($santri->getKey()))->toBeTrue();

    Http::assertSent(function ($request): bool {
        return $request->url() === 'https://api.fonnte.com/send'
            && $request->hasHeader('Authorization', 'test-token')
            && $request['target'] === '6281234567890'
            && $request['countryCode'] === '62'
            && str_contains($request['message'], 'pendaftaran Anda telah berhasil');
    });

    expect($santri->refresh()->pendaftaran_notified_at)->not->toBeNull();
});

test('a rejected message is reported without throwing', function () {
    Http::fake([
        'api.fonnte.com/*' => Http::response(['status' => false, 'reason' => 'token invalid']),
    ]);

    $santri = DetailSantri::factory()->create(['no_hp' => '081234567890']);

    expect(app(SendPendaftaranWhatsapp::class)->send($santri->getKey()))->toBeFalse();
    expect($santri->refresh()->pendaftaran_notified_at)->toBeNull();
});

test('an unusable phone number is skipped', function () {
    Http::fake();

    $santri = DetailSantri::factory()->create(['no_hp' => '-']);

    expect(app(SendPendaftaranWhatsapp::class)->send($santri->getKey()))->toBeFalse();

    Http::assertNothingSent();
    expect($santri->refresh()->pendaftaran_notified_at)->toBeNull();
});

test('an already notified santri is not messaged twice', function () {
    Http::fake();

    $santri = DetailSantri::factory()->create([
        'no_hp' => '081234567890',
        'pendaftaran_notified_at' => now(),
    ]);

    expect(app(SendPendaftaranWhatsapp::class)->send($santri->getKey()))->toBeFalse();

    Http::assertNothingSent();
});

test('phone numbers are normalised to their international form', function (?string $input, ?string $expected) {
    expect(Fonnte::normalizeTarget($input))->toBe($expected);
})->with([
    ['081234567890', '6281234567890'],
    ['0812-3456-7890', '6281234567890'],
    ['+62 812 3456 7890', '6281234567890'],
    ['6281234567890', '6281234567890'],
    ['81234567890', '6281234567890'],
    ['0812', null],
    ['', null],
    [null, null],
]);
