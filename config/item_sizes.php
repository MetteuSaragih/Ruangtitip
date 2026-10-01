<?php

/*
|--------------------------------------------------------------------------
| Ukuran & berat barang Ruang Titip
|--------------------------------------------------------------------------
| Satu tempat untuk berat (gram) dan dimensi (cm) tiap jenis/ukuran barang
| penitipan, dipakai untuk menghitung ongkir Biteship (bukan untuk harga
| sewa — harga sewa tetap di StorageRoom->pricing per gudang).
|
| Kunci array (ks, km, kl, ... ) harus sama persis dengan id yang dipakai
| di pricing gudang (lihat App\Http\Controllers\Admin\RuangTitipController
| $defaultPricing).
*/

return [
    'kardus' => [
        'ks' => ['label' => 'Kardus S', 'length' => 30, 'width' => 25, 'height' => 20, 'weight' => 5000],
        'km' => ['label' => 'Kardus M', 'length' => 40, 'width' => 30, 'height' => 30, 'weight' => 8000],
        'kl' => ['label' => 'Kardus L', 'length' => 50, 'width' => 40, 'height' => 40, 'weight' => 12000],
        'kxl' => ['label' => 'Kardus XL', 'length' => 60, 'width' => 50, 'height' => 50, 'weight' => 15000],
    ],

    'koper' => [
        'kpc' => ['label' => 'Koper Cabin', 'length' => 55, 'width' => 40, 'height' => 20, 'weight' => 5000],
        'kpm' => ['label' => 'Koper Medium', 'length' => 65, 'width' => 45, 'height' => 28, 'weight' => 8000],
        'kpl' => ['label' => 'Koper Large', 'length' => 75, 'width' => 50, 'height' => 30, 'weight' => 10000],
        'kpxl' => ['label' => 'Koper XL', 'length' => 81, 'width' => 55, 'height' => 33, 'weight' => 12000],
    ],

    // Barang custom non-standar. Dimensi dipakai angka representatif tengah
    // rentang yang diizinkan (30x30x30 - 100x100x100 cm); berat diisi sendiri
    // oleh pelanggan saat memilih jenis ini (dibatasi weight_min/weight_max),
    // dengan weight_default dipakai kalau pelanggan tidak mengisi.
    'dimensi_lain' => [
        'label' => 'Dimensi Lain',
        'length' => 60, 'width' => 60, 'height' => 60,
        'weight_default' => 10000,
        'weight_min' => 1000,
        'weight_max' => 20000,
    ],

    // Fallback kalau suatu kode ukuran tidak ditemukan di atas (jaga-jaga).
    'fallback' => ['length' => 40, 'width' => 30, 'height' => 30, 'weight' => 5000],

    // Batas maksimum kurir motor (GoSend/GrabBike) — dipakai untuk
    // menyembunyikan opsi kurir motor dan hanya menampilkan Lalamove kalau
    // kiriman melebihi batas ini.
    'motor_limit' => [
        'weight' => 20000, // gram
        'length' => 70, 'width' => 50, 'height' => 50, // cm
    ],
];
