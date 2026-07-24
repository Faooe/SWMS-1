<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) Configuration
    |--------------------------------------------------------------------------
    |
    | Path yang boleh diakses lintas origin. "api/*" mencakup semua endpoint
    | /api/v1/... yang dipakai Flutter (mobile & web) dan "sanctum/csrf-cookie"
    | untuk kebutuhan SPA berbasis cookie (kalau nanti dipakai).
    |
    */

    'paths' => ['api/*', 'sanctum/csrf-cookie'],

    'allowed_methods' => ['*'],

    /*
    | Untuk app mobile Flutter native (Android/iOS), origin biasanya tidak
    | relevan (bukan browser). Tapi untuk testing pakai `flutter run -d chrome`
    | atau kalau nanti ada web dashboard terpisah, origin browser perlu
    | diizinkan di sini. '*' aman dipakai karena API ini pakai token Bearer
    | (Sanctum Personal Access Token), bukan cookie -- jadi tidak butuh
    | 'supports_credentials' => true.
    */
    'allowed_origins' => ['*'],

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => false,

];