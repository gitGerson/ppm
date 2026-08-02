<?php

namespace App\Console\Commands;

use App\Support\DetailSantriSheetSync;
use Illuminate\Console\Command;

class SyncSantriSheet extends Command
{
    protected $signature = 'santri:sheet-sync
                            {--full : Rewrite the whole sheet from the database after pulling}';

    protected $description = 'Sinkronisasi dua arah data santri dengan Google Sheet';

    public function handle(DetailSantriSheetSync $sync): int
    {
        if (! DetailSantriSheetSync::isEnabled()) {
            $this->components->warn('Sinkronisasi Google Sheet nonaktif. Set SANTRI_SHEET_SYNC_ENABLED dan SANTRI_SHEET_ID.');

            return self::SUCCESS;
        }

        // Pull first even on a full run: a rewrite would otherwise discard any
        // edit an admin made in the sheet since the last sync.
        $result = $sync->pull();

        $this->components->info(sprintf(
            'Tarik selesai: %d baris masuk ke database, %d baris didorong ke sheet, %d konflik.',
            $result['applied'],
            $result['pushed'],
            $result['conflicts'],
        ));

        foreach ($result['skipped'] as $message) {
            $this->components->warn($message);
        }

        if ($this->option('full')) {
            $written = $sync->pushAll();
            $this->components->info("Sheet ditulis ulang: {$written} baris.");
        }

        return self::SUCCESS;
    }
}
