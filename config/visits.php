<?php

return [
    /*
    | Statistik kunjungan anonim untuk dashboard admin (pengunjung login vs hanya landing page).
    | Matikan dengan VISIT_TRACKING=false di .env.
    */
    'enabled' => filter_var(env('VISIT_TRACKING', true), FILTER_VALIDATE_BOOLEAN),

    // Pengunjung dianggap "sedang aktif" bila terakhir terlihat dalam sekian menit.
    'active_minutes' => 5,

    // Nama cookie anonim (berisi kode acak 32 karakter).
    'cookie' => 'rt_vid',
];
