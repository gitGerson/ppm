<?php

use App\Models\DetailSantri;
use App\Models\User;

test('pendaftaran rejects values outside unsigned tiny integer range', function (string $field) {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post(route('pendaftaran.store'), [
        'nama_lengkap' => 'Santri Baru',
        $field => 256,
    ]);

    $response->assertInvalid([$field]);

    expect(DetailSantri::query()->whereBelongsTo($user)->exists())->toBeFalse();
})->with([
    'anak ke' => 'anak_ke',
    'jumlah saudara' => 'jumlah_saudara',
]);
