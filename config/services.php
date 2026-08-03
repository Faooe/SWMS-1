<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'cron' => [
        'secret' => env('CRON_SECRET'),
    ],

    'midtrans' => [

        'server_key' => env('MIDTRANS_SERVER_KEY'),

        'client_key' => env('MIDTRANS_CLIENT_KEY'),

        /*
        |----------------------------------------------------------------
        | Sandbox vs Production
        |----------------------------------------------------------------
        |
        | Selalu sandbox (false) sesuai permintaan, sampai nanti sengaja
        | diubah manual pas benar-benar go-live.
        |
        */

        'is_production' => env('MIDTRANS_IS_PRODUCTION', false),

    ],

    /*
    |--------------------------------------------------------------------------
    | Firebase (SSO / "Login dengan Google")
    |--------------------------------------------------------------------------
    |
    | credentials_base64 = isi file Service Account JSON dari Firebase
    | Console (Project Settings > Service Accounts > Generate new private
    | key), di-base64-encode dulu sebelum ditaruh di .env. Base64 dipakai
    | karena env var (terutama di Vercel) gak aman buat nyimpen JSON
    | multi-baris apa adanya -- private key di dalam JSON itu ada
    | newline literal yang gampang rusak kalau di-paste langsung.
    |
    | web_api_key/auth_domain dipakai di FRONTEND (Firebase JS SDK di
    | resources/views/auth/login.blade.php) -- ini beda dari
    | credentials_base64 yang cuma dipakai backend. web_api_key BUKAN
    | rahasia (memang didesain publik oleh Firebase, aman muncul di HTML),
    | tapi credentials_base64 WAJIB rahasia (jangan pernah dikirim ke
    | frontend).
    |
    */

    'firebase' => [

        'project_id' => env('FIREBASE_PROJECT_ID'),

        'credentials_base64' => env('FIREBASE_CREDENTIALS_BASE64'),

        'web_api_key' => env('FIREBASE_WEB_API_KEY'),

        'auth_domain' => env('FIREBASE_AUTH_DOMAIN'),

    ],

];
