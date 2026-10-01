<?php

/*
|--------------------------------------------------------------------------
| Slot jam penjemputan/pengantaran Ruang Titip
|--------------------------------------------------------------------------
| Satu tempat untuk daftar slot jam yang bisa dipilih pelanggan, dan aturan
| waktunya (minimal berapa jam dari sekarang, maksimal berapa hari ke depan).
*/

return [
    'slots' => [
        ['start' => '08:00', 'end' => '10:00'],
        ['start' => '10:00', 'end' => '12:00'],
        ['start' => '12:00', 'end' => '14:00'],
        ['start' => '14:00', 'end' => '16:00'],
        ['start' => '16:00', 'end' => '18:00'],
    ],

    // Slot paling cepat yang boleh dipilih: minimal sekian jam dari sekarang.
    'min_hours_ahead' => 2,

    // Slot paling jauh yang boleh dipilih: maksimal sekian hari ke depan.
    'max_days_ahead' => 14,

    // Untuk kurir instan (gojek/grab/lalamove): order Biteship baru dibuat
    // kira-kira sekian menit sebelum awal slot (lewat command terjadwal).
    'instant_booking_minutes_before' => 45,

    // Berapa kali percobaan ulang sebelum pesanan ditandai "perlu tindakan admin".
    'max_booking_attempts' => 5,
];
