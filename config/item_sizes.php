<?php

/*
|--------------------------------------------------------------------------
| Ukuran barang Ruang Titip (S, M, L, XL)
|--------------------------------------------------------------------------
| Satu sumber kebenaran untuk halaman pengguna, form admin, hitungan biaya,
| kapasitas, dan ongkir Biteship. Harga di sini adalah HARGA AWAL; admin bisa
| mengubahnya di halaman "Ruang & kapasitas" (disimpan di tabel app_settings).
| Harga per barang per HARI, sama di semua ruang.
|
|  unit  : jatah kapasitas ruang (setara ukuran S)
|  dims  : dimensi kardus maksimal [panjang, lebar, tinggi] cm, dipakai ongkir
|  kg    : berat maksimal per barang, dipakai ongkir (gram = kg * 1000)
|  side  : sisi terpanjang maksimal (cm)
*/

return [
    'sizes' => [
        'S' => [
            'label' => 'Ukuran S', 'price' => 5000, 'unit' => 1, 'side' => 30, 'kg' => 5,
            'dims' => [30, 25, 25], 'box' => '30 × 25 × 25 cm', 'vol' => '±19 L',
            'koper' => '', 'fits' => 'Helm, totebag, kotak sepatu, tumpukan buku kecil',
        ],
        'M' => [
            'label' => 'Ukuran M', 'price' => 7000, 'unit' => 2, 'side' => 45, 'kg' => 10,
            'dims' => [45, 35, 30], 'box' => '45 × 35 × 30 cm', 'vol' => '±47 L',
            'koper' => '16–18 inci (mini)', 'fits' => 'Backpack, pakaian setengah rak, alat mandi, peralatan dapur kecil',
        ],
        'L' => [
            'label' => 'Ukuran L', 'price' => 9000, 'unit' => 3, 'side' => 60, 'kg' => 15,
            'dims' => [60, 40, 40], 'box' => '60 × 40 × 40 cm', 'vol' => '±96 L',
            'koper' => '20–22 inci (kabin)', 'fits' => 'Tas travel, bantal dan selimut, rice cooker dan panci',
        ],
        'XL' => [
            'label' => 'Ukuran XL', 'price' => 11000, 'unit' => 5, 'side' => 76, 'kg' => 20,
            'dims' => [75, 50, 45], 'box' => '75 × 50 × 45 cm', 'vol' => '±170 L',
            'koper' => '24–28 inci', 'fits' => 'Kardus besar, kontainer plastik ±100 L, bedcover tebal',
        ],
    ],

    // Kode ukuran lama (sebelum S/M/L/XL) yang mungkin masih ada di pesanan lama: dipetakan agar tetap terbaca.
    'legacy' => [
        'ks' => 'S', 'km' => 'M', 'kl' => 'L', 'kxl' => 'XL',
        'kpc' => 'L', 'kpm' => 'XL', 'kpl' => 'XL', 'kpxl' => 'XL',
        'dimensi_lain' => 'XL',
    ],

    // Fallback kalau suatu kode ukuran tidak dikenali (jaga-jaga).
    'fallback' => ['length' => 40, 'width' => 30, 'height' => 30, 'weight' => 5000],

    // Batas maksimum kurir motor (GoSend/GrabBike) — dipakai untuk
    // menyembunyikan opsi kurir motor dan hanya menampilkan Lalamove kalau
    // kiriman melebihi batas ini.
    'motor_limit' => [
        'weight' => 20000, // gram
        'length' => 70, 'width' => 50, 'height' => 50, // cm
    ],
];
