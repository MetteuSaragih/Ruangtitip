<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PackingOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_code', 'user_id', 'items', 'subtotal', 'shipping_cost',
        'total', 'logistic', 'courier', 'address', 'payment_method', 'status',
        'payment_status', 'snap_token',
    ];

    protected $casts = [
        'items'         => 'array',
        'subtotal'      => 'integer',
        'shipping_cost' => 'integer',
        'total'         => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
