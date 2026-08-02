<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Fonnte WhatsApp gateway
    |--------------------------------------------------------------------------
    |
    | Disabled by default so local and test environments never send a real
    | WhatsApp message. Turn it on per-environment once the device token from
    | the Fonnte dashboard is in place.
    |
    */

    'enabled' => env('FONNTE_ENABLED', false),

    'token' => env('FONNTE_TOKEN'),

    'endpoint' => env('FONNTE_ENDPOINT', 'https://api.fonnte.com/send'),

    /*
    |--------------------------------------------------------------------------
    | Country code
    |--------------------------------------------------------------------------
    |
    | Used both to expand a locally written number ("0812...") into its
    | international form and as the fallback Fonnte itself applies.
    |
    */

    'country_code' => env('FONNTE_COUNTRY_CODE', '62'),

    'timeout' => (int) env('FONNTE_TIMEOUT', 15),

];
