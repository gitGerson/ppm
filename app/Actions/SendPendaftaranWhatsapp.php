<?php

namespace App\Actions;

use App\Models\DetailSantri;
use App\Support\Fonnte;
use Illuminate\Support\Facades\Log;

/**
 * WhatsApp confirmation sent once, after a santri's first completed
 * pendaftaran submit. `pendaftaran_notified_at` records that it went out, so a
 * later edit of the same form does not send it a second time.
 *
 * Runs inline, inside the request that saved the form: there is no queue worker
 * on this deployment. A failed send is therefore logged and swallowed rather
 * than thrown -- the santri's data is already saved, so the submit must still
 * succeed. The timestamp stays null, which lets a later edit try again.
 */
class SendPendaftaranWhatsapp
{
    public function __construct(private Fonnte $fonnte) {}

    /**
     * @return bool whether a message actually went out
     */
    public function send(int $detailSantriId): bool
    {
        $santri = DetailSantri::query()->find($detailSantriId);

        if ($santri === null || $santri->pendaftaran_notified_at !== null) {
            return false;
        }

        $target = Fonnte::normalizeTarget($santri->no_hp);

        if ($target === null) {
            Log::warning('Notifikasi pendaftaran dilewati, nomor HP tidak dapat dihubungi', [
                'detail_santri_id' => $santri->getKey(),
                'no_hp' => $santri->no_hp,
            ]);

            return false;
        }

        if (! $this->fonnte->send($target, self::message())) {
            // Fonnte::send() already logged the reason.
            Log::warning('Notifikasi pendaftaran gagal dikirim', [
                'detail_santri_id' => $santri->getKey(),
                'target' => $target,
            ]);

            return false;
        }

        // Quietly: bookkeeping, not a change the Google Sheet needs to hear about.
        $santri->forceFill(['pendaftaran_notified_at' => now()])->saveQuietly();

        return true;
    }

    /**
     * Public and static so `fonnte:test --pendaftaran` can preview or send the
     * exact text a santri receives.
     */
    public static function message(): string
    {
        return <<<'TEXT'
        Alhamdulillahi Jaza Kumullohu Khoiro, pendaftaran Anda telah berhasil!

        Selanjutnya, mohon segera melanjutkan dengan:
        1. Mengisi pemesanan materi
        2. Mengunduh surat pernyataan

        Silakan akses keduanya melalui website.

        👉 Harap selalu memeriksa halaman pengumuman untuk informasi lanjutan dan jadwal penting.
        TEXT;
    }
}
