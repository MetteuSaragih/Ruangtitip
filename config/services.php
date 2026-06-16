<?php

/*
|--------------------------------------------------------------------------
| Third Party Services — Rutip
|--------------------------------------------------------------------------
|
| Tambahkan konfigurasi Google OAuth di sini.
| Nilai-nilai ini diambil dari file .env agar tidak hardcoded di kode.
|
| Cara mendapatkan credentials:
| 1. Buka console.cloud.google.com
| 2. Buat project baru → Buka "APIs & Services" → "Credentials"
| 3. Create OAuth Client ID → Application type: Web application
| 4. Authorized redirect URIs: http://localhost:8000/auth/google/callback
| 5. Salin Client ID dan Client Secret ke file .env
|
*/

return [

    'mailgun' => [
        'domain'   => env('MAILGUN_DOMAIN'),
        'secret'   => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
        'scheme'   => 'https',
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key'    => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    /*
    |------------------------------------------------------------------
    | Google OAuth (Socialite)
    |------------------------------------------------------------------
    */
    'google' => [
        'client_id'     => env('GOOGLE_CLIENT_ID'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET'),
        'redirect'      => env('GOOGLE_REDIRECT_URL', 'http://localhost:8000/auth/google/callback'),
    ],

];
