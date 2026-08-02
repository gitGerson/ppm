<?php

namespace App\Jobs;

use App\Models\DetailSantri;
use App\Support\Fonnte;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\Middleware\WithoutOverlapping;
use Illuminate\Support\Facades\Log;
use RuntimeException;

/**
 * WhatsApp confirmation sent once, after a santri's first completed
 * pendaftaran submit. `pendaftaran_notified_at` records that it went out, so a
 * later edit of the same form does not send it a second time.
 */
class SendPendaftaranWhatsapp implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;

    public int $backoff = 60;

    public function __construct(public int $detailSantriId) {}

    /**
     * @return array<int, object>
     */
    public function middleware(): array
    {
        return [(new WithoutOverlapping('pendaftaran-wa-'.$this->detailSantriId))->expireAfter(120)];
    }

    public function handle(Fonnte $fonnte): void
    {
        $santri = DetailSantri::query()->find($this->detailSantriId);

        if ($santri === null || $santri->pendaftaran_notified_at !== null) {
            return;
        }

        $target = Fonnte::normalizeTarget($santri->no_hp);

        // Nothing to retry against: the number itself is the problem.
        if ($target === null) {
            Log::warning('Notifikasi pendaftaran dilewati, nomor HP tidak dapat dihubungi', [
                'detail_santri_id' => $santri->getKey(),
                'no_hp' => $santri->no_hp,
            ]);

            return;
        }

        if (! $fonnte->send($target, self::message())) {
            throw new RuntimeException('Gagal mengirim notifikasi pendaftaran ke '.$target);
        }

        // Quietly: bookkeeping, not a change the Google Sheet needs to hear about.
        $santri->forceFill(['pendaftaran_notified_at' => now()])->saveQuietly();
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
