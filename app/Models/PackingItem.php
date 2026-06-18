<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PackingItem extends Model
{
    protected $fillable = [
        'name', 'category', 'unit', 'price', 'stock', 'low_threshold'
    ];
}