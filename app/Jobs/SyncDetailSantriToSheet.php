<?php

namespace App\Jobs;

use App\Models\DetailSantri;
use App\Support\DetailSantriSheetSync;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\Middleware\WithoutOverlapping;

class SyncDetailSantriToSheet implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;

    public int $backoff = 30;

    public function __construct(public int $detailSantriId) {}

    /**
     * One writer per santri row, so two quick edits cannot interleave and leave
     * the sheet showing the older of the two.
     *
     * @return array<int, object>
     */
    public function middleware(): array
    {
        return [(new WithoutOverlapping((string) $this->detailSantriId))->expireAfter(120)];
    }

    public function handle(DetailSantriSheetSync $sync): void
    {
        $santri = DetailSantri::query()->find($this->detailSantriId);

        if ($santri === null) {
            return;
        }

        $sync->pushOne($santri);
    }
}
