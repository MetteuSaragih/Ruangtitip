<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Midtrans Credentials
    |--------------------------------------------------------------------------
    | Ambil dari Dashboard Midtrans → Settings → Access Keys.
    | Untuk testing gunakan key SANDBOX, untuk live gunakan key PRODUCTION.
    */

    'server_key' => env('MIDTRANS_SERVER_KEY'),
'client_key' => env('MIDTRANS_CLIENT_KEY'),

    // true = Production (live), false = Sandbox (testing)
    'is_production' => env('MIDTRANS_IS_PRODUCTION', false),

    // Sanitasi & 3D Secure — biarkan default seperti rekomendasi Midtrans
    'is_sanitized' => env('MIDTRANS_IS_SANITIZED', true),
    'is_3ds'       => env('MIDTRANS_IS_3DS', true),
];
