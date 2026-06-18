<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
class StorageRoom extends Model
{
    protected $fillable = [
        'name', 'location', 'address', 'description',
        'pricing', 'capacity_total', 'capacity_used',
        'facilities', 'photo', 'photos', 'active',
    ];

    protected $casts = [
        'pricing'    => 'array',
        'facilities' => 'array',
        'photos'     => 'array',
        'active'     => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    public function getCapacityPctAttribute(): int
    {
        if ($this->capacity_total <= 0) return 0;
        return (int) round(($this->capacity_used / $this->capacity_total) * 100);
    }

    public function getMinPriceAttribute(): int
    {
        return collect($this->pricing['kardus'] ?? [])->min('price') ?? 0;
    }

    public function getMaxPriceAttribute(): int
    {
        return collect($this->pricing['koper'] ?? [])->max('price') ?? 0;
    }

    public function getPrimaryPhotoAttribute(): ?string
    {
        return collect($this->photos ?? [])->first() ?: $this->photo;
    }
}
