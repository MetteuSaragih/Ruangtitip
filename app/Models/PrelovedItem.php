<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PrelovedItem extends Model
{
    protected $fillable = [
        'name', 'category', 'condition', 'price', 'status', 'photo', 'photos', 'seller',
    ];

    protected $casts = [
        'photos' => 'array',
    ];

    public function getPrimaryPhotoAttribute(): ?string
    {
        return collect($this->photos ?? [])->first() ?: $this->photo;
    }

    public function orders()
    {
        return $this->hasMany(PrelovedOrder::class);
    }
}
