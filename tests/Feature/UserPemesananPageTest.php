<?php

use App\Models\Pemesanan;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

test('guest cannot access pemesanan page', function () {
    $this->get(route('pemesanan.create'))
        ->assertRedirect(route('login'));
});

test('authenticated user can view pemesanan form', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('pemesanan.create'))
        ->assertOk()
        ->assertSee('Form Pemesanan Materi')
        ->assertSee('Al-Quran Makna')
        ->assertSee('Unggah Bukti Pembayaran');
});

test('authenticated user can submit pemesanan', function () {
    Storage::fake('public');

    $user = User::factory()->create();

    $this->actingAs($user)
        ->post(route('pemesanan.store'), [
            'nama' => 'Santri Pemesan',
            'nama_kos' => 'Kos Basecamp Putri',
            'address' => 'Sumampir',
            'materials' => ['al_quran_makna', 'seragam_ppm'],
            'seragam_ppm_size' => 'L',
            'baju_asad_size' => 'M',
            'bukti_pembayaran' => UploadedFile::fake()->image('bukti.png'),
        ])
        ->assertRedirect(route('pemesanan.create'))
        ->assertSessionHas('status');

    $pemesanan = Pemesanan::query()
        ->where('nama', 'Santri Pemesan')
        ->with('detailPemesanans.item')
        ->first();

    expect($pemesanan)->not->toBeNull()
        ->and($pemesanan->user_id)->toBe($user->id)
        ->and($pemesanan->nama_kos)->toBe('Kos Basecamp Putri')
        ->and($pemesanan->total_amount)->toBe(235000)
        ->and($pemesanan->detailPemesanans)->toHaveCount(2);

    Storage::disk('public')->assertExists($pemesanan->bukti_pembayaran_path);
});

test('pemesanan requires at least one material and proof upload', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->from(route('pemesanan.create'))
        ->post(route('pemesanan.store'), [
            'nama' => 'Santri Pemesan',
            'nama_kos' => 'Kos Basecamp Putri',
            'address' => 'Sumampir',
            'materials' => [],
        ])
        ->assertRedirect(route('pemesanan.create'))
        ->assertSessionHasErrors(['materials', 'bukti_pembayaran']);
});
