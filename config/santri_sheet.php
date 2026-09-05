<?php

return [
    'api_enabled' => env('SANTRI_SHEET_API_ENABLED', false),

    // This integration can read and update all DetailSantri records, using only the schema allowlist.
    'api_token' => env('SANTRI_SHEET_API_TOKEN'),

    'requests_per_minute' => (int) env('SANTRI_SHEET_REQUESTS_PER_MINUTE', 120),
];
