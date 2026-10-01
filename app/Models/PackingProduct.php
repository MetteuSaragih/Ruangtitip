<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PackingProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'category', 'price', 'stock',
        'unit', 'low_threshold', 'discount', 'emoji', 'description', 'images', 'is_active',
        'weight', 'length', 'width', 'height',
    ];

    protected $casts = [
        'price'     => 'integer',
        'stock'     => 'integer',
        'low_threshold' => 'integer',
        'discount'  => 'integer',
        'images'    => 'array',
        'is_active' => 'boolean',
        'weight'    => 'integer',
        'length'    => 'integer',
        'width'     => 'integer',
        'height'    => 'integer',
    ];

    public function getPrimaryImageAttribute(): ?string
    {
        return collect($this->images ?? [])->first();
    }

    /**
     * Harga sebelum diskon (untuk ditampilkan dicoret), null jika tanpa diskon.
     */
    public function getOriginalPriceAttribute(): ?int
    {
        if (! $this->discount) {
            return null;
        }

        return (int) round($this->price / (1 - $this->discount / 100));
    }

    /** Scope produk aktif saja. */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
