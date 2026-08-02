<?php

use App\Jobs\SendPendaftaranWhatsapp;
use App\Models\DetailSantri;
use App\Models\User;
use App\Support\Fonnte;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Queue;

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

test('first pendaftaran submit queues the whatsapp confirmation', function () {
    Queue::fake();
    $user = User::factory()->create();

    submitPendaftaran($user)
        ->assertRedirect(route('pendaftaran'))
        ->assertSessionHas('status');

    $santri = DetailSantri::query()->where('user_id', $user->id)->firstOrFail();

    Queue::assertPushed(
        SendPendaftaranWhatsapp::class,
        fn (SendPendaftaranWhatsapp $job): bool => $job->detailSantriId === $santri->getKey()
    );
});

test('a later edit does not send the confirmation again', function () {
    $user = User::factory()->create();
    submitPendaftaran($user);

    DetailSantri::query()
        ->where('user_id', $user->id)
        ->update(['pendaftaran_notified_at' => now()]);

    Queue::fake();
    submitPendaftaran($user, ['nama_panggilan' => 'Santri']);

    Queue::assertNotPushed(SendPendaftaranWhatsapp::class);
});

test('nothing is queued while fonnte is disabled', function () {
    config()->set('fonnte.enabled', false);
    Queue::fake();

    submitPendaftaran(User::factory()->create());

    Queue::assertNotPushed(SendPendaftaranWhatsapp::class);
});

test('the job sends the message and marks the santri as notified', function () {
    Http::fake([
        'api.fonnte.com/*' => Http::response(['status' => true, 'id' => ['1']]),
    ]);

    $santri = DetailSantri::factory()->create(['no_hp' => '081234567890']);

    (new SendPendaftaranWhatsapp($santri->getKey()))->handle(new Fonnte);

    Http::assertSent(function ($request): bool {
        return $request->url() === 'https://api.fonnte.com/send'
            && $request->hasHeader('Authorization', 'test-token')
            && $request['target'] === '6281234567890'
            && $request['countryCode'] === '62'
            && str_contains($request['message'], 'pendaftaran Anda telah berhasil');
    });

    expect($santri->refresh()->pendaftaran_notified_at)->not->toBeNull();
});

test('the job fails so it can retry when fonnte rejects the message', function () {
    Http::fake([
        'api.fonnte.com/*' => Http::response(['status' => false, 'reason' => 'token invalid']),
    ]);

    $santri = DetailSantri::factory()->create(['no_hp' => '081234567890']);

    expect(fn () => (new SendPendaftaranWhatsapp($santri->getKey()))->handle(new Fonnte))
        ->toThrow(RuntimeException::class);

    expect($santri->refresh()->pendaftaran_notified_at)->toBeNull();
});

test('an unusable phone number is skipped instead of retried forever', function () {
    Http::fake();

    $santri = DetailSantri::factory()->create(['no_hp' => '-']);

    (new SendPendaftaranWhatsapp($santri->getKey()))->handle(new Fonnte);

    Http::assertNothingSent();
    expect($santri->refresh()->pendaftaran_notified_at)->toBeNull();
});

test('an already notified santri is not messaged twice', function () {
    Http::fake();

    $santri = DetailSantri::factory()->create([
        'no_hp' => '081234567890',
        'pendaftaran_notified_at' => now(),
    ]);

    (new SendPendaftaranWhatsapp($santri->getKey()))->handle(new Fonnte);

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
