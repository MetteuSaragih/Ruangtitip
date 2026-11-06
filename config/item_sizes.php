<?php

/*
|--------------------------------------------------------------------------
| Ukuran barang Ruang Titip (S, M, L, XL, XXL)
|--------------------------------------------------------------------------
| Satu sumber kebenaran untuk halaman pengguna, form admin, hitungan biaya,
| kapasitas, dan ongkir Biteship. Harga di sini adalah HARGA AWAL; admin bisa
| mengubahnya di halaman "Ruang & kapasitas" (disimpan di tabel app_settings).
| Harga sama di semua ruang.
|
|  price   : tarif harian per barang (input admin)
|  monthly : tarif 1 bulan (30 hari) per barang (input admin)
|            Tarif 7 hari, 3, 6, dan 12 bulan DIHITUNG dari dua angka ini, lihat 'plans'.
|  unit    : jatah kapasitas ruang (setara ukuran S)
|  side    : sisi terpanjang maksimal (cm)
|  sum     : jumlah panjang + lebar + tinggi maksimal (cm)
|  kg      : berat maksimal per barang
|  dims    : dimensi kardus [panjang, lebar, tinggi] cm untuk ongkir (cadangan), kg * 1000 = gram
*/

return [
    'sizes' => [
        'S' => [
            'label' => 'Ukuran S', 'price' => 5000, 'monthly' => 80000, 'unit' => 1,
            'side' => 40, 'sum' => 100, 'kg' => 5, 'dims' => [40, 30, 30],
            'fits' => 'Helm, totebag, kotak sepatu, tumpukan buku kecil',
        ],
        'M' => [
            'label' => 'Ukuran M', 'price' => 7000, 'monthly' => 110000, 'unit' => 2,
            'side' => 50, 'sum' => 120, 'kg' => 10, 'dims' => [50, 35, 35],
            'fits' => 'Backpack, pakaian setengah rak, alat mandi, peralatan dapur kecil',
        ],
        'L' => [
            'label' => 'Ukuran L', 'price' => 9000, 'monthly' => 150000, 'unit' => 3,
            'side' => 60, 'sum' => 140, 'kg' => 15, 'dims' => [60, 40, 40],
            'fits' => 'Tas travel, bantal dan selimut, rice cooker dan panci',
        ],
        'XL' => [
            'label' => 'Ukuran XL', 'price' => 11000, 'monthly' => 200000, 'unit' => 5,
            'side' => 80, 'sum' => 180, 'kg' => 20, 'dims' => [80, 50, 50],
            'fits' => 'Kardus besar, kontainer plastik ±100 L, bedcover tebal',
        ],
        'XXL' => [
            'label' => 'Ukuran XXL', 'price' => 15000, 'monthly' => 300000, 'unit' => 8,
            'side' => 100, 'sum' => 220, 'kg' => 30, 'dims' => [100, 60, 60],
            'fits' => 'Kardus sangat besar, kontainer plastik ±150 L, kasur lipat, kipas dan dispenser',
        ],
    ],

    /*
    | Paket harga (dihitung dari tarif harian dan tarif 1 bulan tiap ukuran):
    |  - 7 hari   : bayar 6 hari (hari ke-7 gratis)
    |  - 1 bulan  : tarif bulanan, 30 hari
    |  - N bulan  : tarif bulanan x N, dipotong diskon (persen), 30 hari per bulan
    |  Hasil dibulatkan ke atas ke kelipatan 'rounding' rupiah.
    | Untuk lama titip bebas (mis. 40 hari), sistem memilih gabungan paket yang paling murah.
    */
    'plans' => [
        'rounding' => 1000,
        'week' => ['days' => 7, 'paid_days' => 6],
        'months' => [
            1 => ['days' => 30, 'discount' => 0],
            3 => ['days' => 90, 'discount' => 5],
            6 => ['days' => 180, 'discount' => 8],
            12 => ['days' => 360, 'discount' => 12],
        ],
        'max_days' => 730,
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
