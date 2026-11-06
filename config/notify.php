<?php

return [
    /*
    | Email pemberitahuan ke admin untuk setiap pesanan, pembayaran, dan permintaan yang masuk.
    | Penerima: semua akun berperan admin yang aktif, ditambah alamat di ADMIN_NOTIFY_EXTRA (pisahkan koma).
    | Matikan sementara dengan ADMIN_EMAIL_NOTIFY=false di .env.
    */
    'admin_email' => [
        'enabled' => filter_var(env('ADMIN_EMAIL_NOTIFY', true), FILTER_VALIDATE_BOOLEAN),
        'extra' => array_values(array_filter(array_map('trim', explode(',', (string) env('ADMIN_NOTIFY_EXTRA', ''))))),
    ],
];
