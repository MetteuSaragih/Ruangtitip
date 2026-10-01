<?php

namespace App\Listeners;

use App\Events\OrderPaid;
use App\Services\BiteshipOrderBooker;

/**
 * Begitu pesanan lunas: kurir REGULER langsung dipesan sekarang (delivery
 * terjadwal sesuai slot). Kurir INSTAN (gojek/grab/lalamove) sengaja TIDAK
 * dipesan di sini — baru dipesan menjelang slot lewat command terjadwal
 * (lihat App\Console\Commands\BookPendingBiteshipShipments), supaya driver
 * tidak nunggu lama dan tidak hangus sebelum waktunya.
 */
class BookShipmentOnOrderPaid
{
    public function __construct(private BiteshipOrderBooker $booker)
    {
    }

    public function handle(OrderPaid $event): void
    {
        $order = $event->order;

        if ($this->booker->courierGroup($this->booker->courierCodeOf($order)) !== 'regular') {
            return;
        }

        $this->booker->attempt($order);
    }
}
