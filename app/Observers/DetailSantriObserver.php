<?php

namespace App\Observers;

use App\Jobs\RewriteSantriSheet;
use App\Jobs\SyncDetailSantriToSheet;
use App\Models\DetailSantri;
use App\Support\DetailSantriSheetSync;

class DetailSantriObserver
{
    public function created(DetailSantri $santri): void
    {
        $this->queuePush($santri);
    }

    public function updated(DetailSantri $santri): void
    {
        $this->queuePush($santri);
    }

    public function deleted(DetailSantri $santri): void
    {
        if (! DetailSantriSheetSync::isEnabled() || DetailSantriSheetSync::isApplyingRemoteChange()) {
            return;
        }

        RewriteSantriSheet::dispatch();
    }

    private function queuePush(DetailSantri $santri): void
    {
        if (! DetailSantriSheetSync::isEnabled()) {
            return;
        }

        // The change came from the sheet; echoing it back would be a wasted
        // API call at best and a write race at worst.
        if (DetailSantriSheetSync::isApplyingRemoteChange()) {
            return;
        }

        SyncDetailSantriToSheet::dispatch($santri->getKey());
    }
}
