<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LandingSurveyResponse extends Model
{
    use HasFactory;

    protected $fillable = [
        'minat',
        'layanan',
        'harga',
        'user_id',
        'ip_address',
    ];

    protected $casts = [
        'layanan' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
