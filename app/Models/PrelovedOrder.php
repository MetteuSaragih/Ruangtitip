<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PrelovedOrder extends Model
{
    protected $fillable = [
        'order_code', 'user_id', 'preloved_product_id', 'quantity',
        'product_subtotal', 'service_fee', 'shipping_fee', 'total_amount',
        'delivery_method', 'courier_code', 'courier_name', 'delivery_address',
        'payment_method', 'payment_gateway', 'midtrans_order_id',
        'midtrans_transaction_id', 'midtrans_token', 'midtrans_redirect_url',
        'payment_status', 'order_status', 'biteship_order_id',
        'tracking_number', 'paid_at',
    ];

    protected $casts = [
        'product_subtotal' => 'decimal:2',
        'service_fee' => 'decimal:2',
        'shipping_fee' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'paid_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(PrelovedProduct::class, 'preloved_product_id');
    }

    public static function generateOrderCode(): string
    {
        return 'TP-' . date('Y') . '-' . rand(10000, 99999);
    }

    public function getFormattedTotalAttribute(): string
    {
        return 'Rp ' . number_format($this->total_amount, 0, ',', '.');
    }
}
