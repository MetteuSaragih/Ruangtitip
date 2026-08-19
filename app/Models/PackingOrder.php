<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PackingOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_code', 'user_id', 'items', 'subtotal', 'shipping_cost', 'platform_fee',
        'total', 'logistic', 'courier', 'address', 'payment_method', 'status',
        'payment_status',
        'tripay_reference', 'tripay_checkout_url', 'tripay_pay_code', 'tripay_payment_method',
        'address_area_id', 'address_postal_code', 'courier_service_code',
        'courier_company', 'biteship_order_id', 'biteship_tracking_id',
        'pickup_date', 'pickup_time',
    ];

    protected $casts = [
        'items'         => 'array',
        'subtotal'      => 'integer',
        'shipping_cost' => 'integer',
        'platform_fee'  => 'integer',
        'total'         => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
