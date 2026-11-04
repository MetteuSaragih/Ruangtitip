<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

/** Pengaturan aplikasi sederhana (kunci => nilai JSON), mis. harga per ukuran. */
class AppSetting extends Model
{
    protected $fillable = ['key', 'value'];

    protected $casts = ['value' => 'array'];

    /** Baca pengaturan. Aman bila tabel belum dimigrasi: mengembalikan $default. */
    public static function read(string $key, mixed $default = null): mixed
    {
        try {
            if (! Schema::hasTable('app_settings')) {
                return $default;
            }

            $row = static::where('key', $key)->first();

            return $row ? ($row->value ?? $default) : $default;
        } catch (\Throwable $e) {
            return $default;
        }
    }

    public static function write(string $key, mixed $value): void
    {
        static::updateOrCreate(['key' => $key], ['value' => $value]);
    }
}
