<?php

namespace App\Console\Commands;

use App\Jobs\SendPendaftaranWhatsapp;
use App\Models\DetailSantri;
use App\Support\Fonnte;
use Illuminate\Console\Command;

/**
 * Manual smoke test for the WhatsApp gateway: sends one message right now,
 * outside the queue, so a misconfigured token surfaces here instead of in a
 * failed job hours later.
 */
class FonnteTest extends Command
{
    protected $signature = 'fonnte:test
                            {target? : Nomor tujuan, misal 081234567890}
                            {--santri= : Ambil nomor dari DetailSantri dengan ID ini}
                            {--message= : Isi pesan khusus}
                            {--pendaftaran : Kirim teks konfirmasi pendaftaran yang sebenarnya}
                            {--dry-run : Tampilkan tujuan dan pesan tanpa mengirim}';

    protected $description = 'Kirim satu pesan uji coba lewat gateway WhatsApp Fonnte';

    public function handle(Fonnte $fonnte): int
    {
        $target = $this->resolveTarget();

        if ($target === null) {
            return self::FAILURE;
        }

        $message = $this->resolveMessage();

        $this->components->twoColumnDetail('Tujuan', $target);
        $this->components->twoColumnDetail('Endpoint', (string) config('fonnte.endpoint'));
        $this->newLine();
        $this->line($message);
        $this->newLine();

        if ($this->option('dry-run')) {
            $this->components->info('Dry run: pesan tidak dikirim.');

            return self::SUCCESS;
        }

        if (! Fonnte::isEnabled()) {
            $this->components->error('Fonnte nonaktif. Set FONNTE_ENABLED=true dan isi FONNTE_TOKEN.');

            return self::FAILURE;
        }

        if (! $fonnte->send($target, $message)) {
            $this->components->error('Pengiriman gagal. Alasan lengkap ada di log aplikasi.');

            return self::FAILURE;
        }

        $this->components->info('Pesan terkirim.');

        return self::SUCCESS;
    }

    /**
     * @return string|null normalised number, or null when the input is unusable
     */
    private function resolveTarget(): ?string
    {
        $santriId = $this->option('santri');
        $raw = $this->argument('target');

        if ($santriId !== null) {
            $santri = DetailSantri::query()->find($santriId);

            if ($santri === null) {
                $this->components->error("DetailSantri dengan ID {$santriId} tidak ditemukan.");

                return null;
            }

            $raw = $santri->no_hp;
            $this->components->twoColumnDetail('Santri', $santri->nama_lengkap ?? "ID {$santriId}");
        }

        if (blank($raw)) {
            $this->components->error('Nomor tujuan kosong. Berikan argumen target atau --santri=ID.');

            return null;
        }

        $target = Fonnte::normalizeTarget($raw);

        if ($target === null) {
            $this->components->error("Nomor \"{$raw}\" tidak dapat dihubungi.");
        }

        return $target;
    }

    private function resolveMessage(): string
    {
        if ($this->option('pendaftaran')) {
            return SendPendaftaranWhatsapp::message();
        }

        return (string) ($this->option('message')
            ?: 'Tes koneksi Fonnte dari '.config('app.name').' pada '.now()->format('d/m/Y H:i').'.');
    }
}
