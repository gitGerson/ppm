<?php

use Illuminate\Foundation\Console\ClosureCommand;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    /** @var ClosureCommand $this */
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Picks up edits made directly in the Google Sheet.
Schedule::command('santri:sheet-sync')
    ->everyFiveMinutes()
    ->withoutOverlapping();

// Nightly rewrite: closes gaps left by deletes and repairs any drift.
Schedule::command('santri:sheet-sync --full')
    ->dailyAt('02:00')
    ->withoutOverlapping();
