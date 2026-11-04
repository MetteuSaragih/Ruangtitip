<?php

namespace App\Support;

class Phone
{
    /** Tautan WhatsApp (wa.me) dari nomor lokal; null bila nomor kosong. */
    public static function wa(?string $phone): ?string
    {
        $digits = preg_replace('/\D+/', '', (string) $phone);
        if ($digits === '') {
            return null;
        }
        if (str_starts_with($digits, '0')) {
            $digits = '62' . substr($digits, 1);
        } elseif (str_starts_with($digits, '8')) {
            $digits = '62' . $digits;
        }

        return 'https://wa.me/' . $digits;
    }
}
