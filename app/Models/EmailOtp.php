<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class EmailOtp extends Model
{
    protected $fillable = [
        'email',
        'code',
        'attempts',
        'expires_at',
        'used_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'used_at'    => 'datetime',
        'attempts'   => 'integer',
    ];

    /**
     * Apakah OTP ini masih bisa dipakai?
     * Valid jika: belum dipakai, belum kedaluwarsa, percobaan < 5.
     */
    public function isUsable(): bool
    {
        return is_null($this->used_at)
            && $this->expires_at->isFuture()
            && $this->attempts < 5;
    }

    /**
     * Hash kode OTP (kode asli tidak pernah disimpan di database).
     */
    public static function hashCode(string $code): string
    {
        return hash_hmac('sha256', $code, (string) config('app.key'));
    }

    public function matches(string $code): bool
    {
        return hash_equals((string) $this->code, static::hashCode($code));
    }

    /**
     * Buat OTP baru untuk sebuah email.
     * Menghapus OTP lama email tsb agar hanya ada satu kode aktif.
     */
    public static function generateFor(string $email): self
    {
        $email = strtolower(trim($email));

        // Hapus kode lama yang belum terpakai
        static::where('email', $email)->whereNull('used_at')->delete();

        $plain = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        $otp = static::create([
            'email'      => $email,
            'code'       => static::hashCode($plain),
            'attempts'   => 0,
            'expires_at' => Carbon::now()->addMinutes(10),
        ]);

        // Kode asli hanya ada di memori untuk dikirim lewat email.
        $otp->plain_code = $plain;

        return $otp;
    }
}
