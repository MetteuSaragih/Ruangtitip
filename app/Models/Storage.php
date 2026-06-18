<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Storage extends Model
{
    protected $fillable = ['name','address','rating','reviews_count','filled','price','tags','emoji','color','is_active'];

    protected $casts = ['tags' => 'array', 'rating' => 'float', 'is_active' => 'boolean'];

    public function reviews(): HasMany
    {
        return $this->hasMany(StorageReview::class);
    }
}
