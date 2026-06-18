<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PrelovedProduct extends Model
{
    protected $fillable = [
        'name', 'description', 'price', 'original_price', 'discount_percent',
        'condition', 'stock', 'image', 'emoji', 'seller_name', 'seller_rating', 'is_active'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'original_price' => 'decimal:2',
        'seller_rating' => 'decimal:1',
        'is_active' => 'boolean',
    ];

    public function orders(): HasMany
    {
        return $this->hasMany(PrelovedOrder::class);
    }

    public function getFormattedPriceAttribute(): string
    {
        return 'Rp ' . number_format($this->price, 0, ',', '.');
    }

    public function getFormattedOriginalPriceAttribute(): ?string
    {
        if ($this->original_price) {
            return 'Rp ' . number_format($this->original_price, 0, ',', '.');
        }
        return null;
    }

    public function getConditionColorAttribute(): string
    {
        return match ($this->condition) {
            '90%+ Mulus', '95% Mulus' => 'condition-mulus',
            '85%+ Baik' => 'condition-baik',
            '75% Pernah Pakai' => 'condition-pakai',
            default => 'condition-mulus',
        };
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFilterCondition($query, $condition)
    {
        if ($condition && $condition !== 'semua') {
            return $query->where('condition', 'like', $condition . '%');
        }
        return $query;
    }
}
