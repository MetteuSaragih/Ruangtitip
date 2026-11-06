<?php

return [
    /*
    | Pembayaran manual lewat QRIS: pelanggan membayar, mengunggah bukti, lalu admin memeriksa.
    | Gambar QRIS dibaca dari public/assets/payment/qris.(png|jpg|jpeg|webp), urut seperti itu.
    | Untuk memakai QRIS asli, cukup simpan gambarnya sebagai public/assets/payment/qris.png.
    */
    'qris' => [
        'merchant' => env('QRIS_MERCHANT_NAME', 'RUANGTITIP, LOGISTIK'),
        'directory' => 'assets/payment',
        'basename' => 'qris',
        'extensions' => ['png', 'jpg', 'jpeg', 'webp'],
    ],

    'proof' => [
        'disk' => 'local',
        'directory' => 'payment-proofs',
        'max_kb' => 8192,
        'mimes' => ['jpg', 'jpeg', 'png', 'webp'],
    ],

    /* Pesanan belum dibayar menahan barang preloved selama ini (jam), tidak berlaku saat bukti sedang diperiksa. */
    'hold_hours' => 24,
];
