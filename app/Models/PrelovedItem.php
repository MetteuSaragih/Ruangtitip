<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PrelovedItem extends Model
{
    /** Kategori resmi: dipakai form admin, form "Jual barang" pengguna, dan filter katalog. */
    public const CATEGORIES = [
        'Elektronik', 'Perabot', 'Peralatan dapur', 'Koper & tas',
        'Buku', 'Dekorasi', 'Olahraga', 'Lainnya',
    ];

    /** Tingkat kondisi (persen disimpan di kolom condition) dan labelnya, sama dengan form "Jual barang". */
    public const CONDITIONS = [
        95 => 'Seperti baru',
        85 => 'Baik',
        75 => 'Cukup, ada bekas pakai',
    ];

    protected $fillable = [
        'name', 'category', 'condition', 'price', 'status', 'photo', 'photos', 'seller',
        'weight', 'length', 'width', 'height',
        'description', 'function_note', 'completeness', 'flaws', 'source_code',
    ];

    protected $casts = [
        'photos' => 'array',
        'weight' => 'integer',
        'length' => 'integer',
        'width' => 'integer',
        'height' => 'integer',
    ];

    /** Bulatkan persen kondisi lama (70 sampai 95) ke salah satu dari tiga tingkat. */
    public static function levelFor(int $condition): int
    {
        if ($condition >= 90) {
            return 95;
        }

        return $condition >= 80 ? 85 : 75;
    }

    public static function conditionText(int $condition): string
    {
        return self::CONDITIONS[self::levelFor($condition)];
    }

    public function getPrimaryPhotoAttribute(): ?string
    {
        return collect($this->photos ?? [])->first() ?: $this->photo;
    }

    public function orders()
    {
        return $this->hasMany(PrelovedOrder::class);
    }
}
