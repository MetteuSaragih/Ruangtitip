<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'original_price',
        'discount_percent',
        'condition',
        'condition_label',
        'stock',
        'seller_name',
        'seller_rating',
        'image',
        'images',
        'category',
        'weight',
        'is_active',
    ];

    protected $casts = [
        'images' => 'array',
        'is_active' => 'boolean',
        'price' => 'integer',
        'original_price' => 'integer',
    ];

    public function getFormattedPriceAttribute()
    {
        return 'Rp ' . number_format($this->price, 0, ',', '.');
    }

    public function getFormattedOriginalPriceAttribute()
    {
        return 'Rp ' . number_format($this->original_price, 0, ',', '.');
    }

    public function getDiscountBadgeAttribute()
    {
        if ($this->discount_percent > 0) {
            return '-' . $this->discount_percent . '%';
        }
        return null;
    }

    public function getConditionColorAttribute()
    {
        return match($this->condition) {
            '90_mulus', '95_mulus' => '#22c55e',
            '85_baik' => '#3b82f6',
            '75_pernah_pakai' => '#f59e0b',
            default => '#6b7280',
        };
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByCondition($query, $condition)
    {
        if ($condition === 'semua') return $query;

        return match($condition) {
            '95_mulus' => $query->whereIn('condition', ['95_mulus', '90_mulus']),
            '85_baik' => $query->where('condition', '85_baik'),
            '75_pernah_pakai' => $query->where('condition', '75_pernah_pakai'),
            default => $query,
        };
    }
}
