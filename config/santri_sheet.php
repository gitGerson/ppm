<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Two-way Google Sheets sync for DetailSantri
    |--------------------------------------------------------------------------
    |
    | Disabled by default so local and test environments never reach out to
    | Google. Turn it on per-environment once the service account JSON is in
    | place and the spreadsheet has been shared with that account's email.
    |
    */

    'enabled' => env('SANTRI_SHEET_SYNC_ENABLED', false),

    'spreadsheet_id' => env('SANTRI_SHEET_ID'),

    'sheet_name' => env('SANTRI_SHEET_NAME', 'DetailSantri'),

    /*
    |--------------------------------------------------------------------------
    | Conflict policy
    |--------------------------------------------------------------------------
    |
    | What to do when a row changed on both sides between two syncs. "database"
    | keeps the app's value and overwrites the sheet; "sheet" does the reverse.
    | Either way the conflict is logged.
    |
    */

    'conflict_winner' => env('SANTRI_SHEET_CONFLICT_WINNER', 'database'),

];
