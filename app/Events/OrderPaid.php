<?php

namespace App\Events;

use App\Models\Order;
use App\Models\PackingOrder;
use App\Models\TitipanOrder;
use Illuminate\Foundation\Events\Dispatchable;

/**
 * Dipicu sekali, tepat saat sebuah pesanan (Ruang Titip, Packing, atau
 * Preloved) pertama kali berubah status pembayaran jadi lunas. Sumbernya
 * sekarang callback Tripay; kalau nanti ditambah gateway lain (mis.
 * Midtrans), webhook-nya cukup fire event yang sama tanpa menyentuh kode
 * pemesanan Biteship sama sekali.
 */
class OrderPaid
{
    use Dispatchable;

    public function __construct(public readonly TitipanOrder|PackingOrder|Order $order)
    {
    }
}
