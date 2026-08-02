<?php

namespace App\Jobs;

use App\Support\DetailSantriSheetSync;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\Middleware\WithoutOverlapping;

/**
 * Full rewrite of the sheet. Used after a delete, since the values API cannot
 * remove a row in the middle without leaving a blank gap behind.
 */
class RewriteSantriSheet implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;

    public int $backoff = 60;

    /**
     * @return array<int, object>
     */
    public function middleware(): array
    {
        return [(new WithoutOverlapping('santri-sheet-rewrite'))->expireAfter(600)];
    }

    public function handle(DetailSantriSheetSync $sync): void
    {
        $sync->pushAll();
    }
}
