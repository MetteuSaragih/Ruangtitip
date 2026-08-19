<?php

return [
    'api_key' => env('BITESHIP_API_KEY'),
    'base_url' => env('BITESHIP_BASE_URL', 'https://api.biteship.com/v1'),
    'is_production' => env('BITESHIP_IS_PRODUCTION', false),

    // Gudang/toko RuTip — dipakai sebagai titik asal pengiriman untuk
    // Toko Preloved, Toko Packing, dan tujuan drop-off Ruang Titip.
    'warehouse' => [
        'name' => env('BITESHIP_WAREHOUSE_NAME', 'RuTip'),
        'phone' => env('BITESHIP_WAREHOUSE_PHONE'),
        'address' => env('BITESHIP_WAREHOUSE_ADDRESS'),
        'postal_code' => env('BITESHIP_WAREHOUSE_POSTAL_CODE'),
        'area_id' => env('BITESHIP_WAREHOUSE_AREA_ID'),
        // Opsional: isi manual kalau titik koordinat gudang sudah pasti.
        // Kalau kosong, dihitung otomatis dari 'address' di atas (geocoding, di-cache).
        'latitude' => env('BITESHIP_WAREHOUSE_LAT'),
        'longitude' => env('BITESHIP_WAREHOUSE_LNG'),
    ],

    'regular_couriers' => array_filter(explode(',', env('BITESHIP_REGULAR_COURIERS', 'jne,jnt,sicepat,anteraja,ninja,idexpress,pos,lion'))),
    'instant_couriers' => array_filter(explode(',', env('BITESHIP_INSTANT_COURIERS', 'gojek,grab,lalamove'))),

    // Dipakai di layar "kurir" agar tetap bisa booking lewat kurir reguler
    // selagi kurir instan (Gojek/Grab/Lalamove) belum diaktifkan di dashboard Biteship.
    'checkout_couriers' => array_filter(array_merge(
        array_filter(explode(',', env('BITESHIP_INSTANT_COURIERS', 'gojek,grab,lalamove'))),
        array_filter(explode(',', env('BITESHIP_REGULAR_COURIERS', 'jne,jnt,sicepat,anteraja,ninja,idexpress,pos,lion')))
    )),
];
