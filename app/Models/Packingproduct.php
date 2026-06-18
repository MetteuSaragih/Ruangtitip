<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PackingProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'category', 'price', 'stock',
        'discount', 'emoji', 'description', 'is_active',
    ];

    protected $casts = [
        'price'     => 'integer',
        'stock'     => 'integer',
        'discount'  => 'integer',
        'is_active' => 'boolean',
    ];

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
