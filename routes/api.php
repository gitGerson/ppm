<?php

use App\Http\Controllers\Api\V1\SantriSheetSyncController;
use App\Http\Middleware\AuthenticateSheetSync;
use Illuminate\Support\Facades\Route;

Route::prefix('v1/sheet-sync')->name('sheet-sync.')
    ->middleware([AuthenticateSheetSync::class, 'throttle:sheet-sync'])
    ->group(function (): void {
        Route::get('santris', [SantriSheetSyncController::class, 'index'])->name('index');
        Route::patch('santris/{santri}', [SantriSheetSyncController::class, 'update'])
            ->whereNumber('santri')->name('update');
    });
