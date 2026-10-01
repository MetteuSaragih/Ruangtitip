<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'customer_name',
        'customer_email',
        'customer_phone',
        'items',
        'subtotal',
        'service_fee',
        'shipping_cost',
        'total',
        'shipping_method',
        'shipping_address',
        'courier_code',
        'courier_name',
        'courier_service_code',
        'pickup_date',
        'pickup_time',
        'payment_method',
        'payment_status',
        'tripay_reference',
        'tripay_checkout_url',
        'tripay_pay_code',
        'tripay_payment_method',
        'biteship_order_id',
        'biteship_tracking_id',
        'biteship_status',
        'biteship_attempts',
        'biteship_last_error',
        'needs_admin_attention',
        'status',
    ];

    protected $casts = [
        'items' => 'array',
        'shipping_address' => 'array',
        'biteship_attempts' => 'integer',
        'needs_admin_attention' => 'boolean',
    ];

    const STATUS_PENDING = 'pending';
    const STATUS_PAID = 'paid';
    const STATUS_PROCESSING = 'processing';
    const STATUS_SHIPPED = 'shipped';
    const STATUS_DELIVERED = 'delivered';
    const STATUS_CANCELLED = 'cancelled';

    const PAYMENT_PENDING = 'pending';
    const PAYMENT_SUCCESS = 'success';
    const PAYMENT_FAILED = 'failed';
    const PAYMENT_EXPIRED = 'expired';
}
