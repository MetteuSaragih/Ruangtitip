<?php

namespace App\Console\Commands;

use App\Models\Order;
use App\Models\PackingOrder;
use App\Models\TitipanOrder;
use App\Services\BiteshipOrderBooker;
use Illuminate\Console\Command;

/**
 * Dijalankan terjadwal tiap 5 menit (lihat routes/console.php). Dua tugas:
 *
 *  1. Kurir INSTAN (gojek/grab/lalamove): pesanan yang lunas dan sudah
 *     dijadwalkan tapi belum dipesan ke Biteship — dipesan begitu waktu
 *     sekarang sudah masuk jendela ~45 menit sebelum awal slot, dengan
 *     delivery_type "now" (bukan scheduled), supaya driver dicari saat
 *     dekat waktunya, bukan jauh-jauh hari.
 *  2. Penyapu percobaan ulang: pesanan APA SAJA (reguler atau instan) yang
 *     gagal dipesan sebelumnya (biteship_order_id masih kosong, belum
 *     di-flag perlu tindakan admin) dicoba lagi di sini.
 */
class BookPendingBiteshipShipments extends Command
{
    protected $signature = 'biteship:book-pending';

    protected $description = 'Pesan order Biteship untuk pesanan yang sudah lunas: kurir instan menjelang slot, dan percobaan ulang yang gagal sebelumnya.';

    public function handle(BiteshipOrderBooker $booker): int
    {
        $minutesBefore = (int) config('pickup_slots.instant_booking_minutes_before', 45);
        $booked = 0;
        $retried = 0;

        foreach ([TitipanOrder::class, PackingOrder::class, Order::class] as $modelClass) {
            $candidates = $modelClass::query()
                ->where('payment_status', 'PAID')
                ->whereNull('biteship_order_id')
                ->where('needs_admin_attention', false)
                ->get()
                ->filter(fn ($order) => $booker->isEligible($order));

            foreach ($candidates as $order) {
                $group = $booker->courierGroup($booker->courierCodeOf($order));

                if ($group === 'regular') {
                    // Harusnya sudah dipesan langsung oleh listener saat lunas;
                    // kalau masih di sini berarti percobaan sebelumnya gagal — ulangi.
                    if ($booker->attempt($order)) {
                        $retried++;
                    }

                    continue;
                }

                if ($group === 'instant') {
                    $pickupAt = $booker->pickupDateTime($order);
                    if (! $pickupAt) {
                        continue;
                    }

                    $windowOpensAt = $pickupAt->copy()->subMinutes($minutesBefore);
                    if (now()->lt($windowOpensAt)) {
                        continue; // belum waktunya
                    }

                    if ($booker->attempt($order, forceNow: true)) {
                        $booked++;
                    }
                }
            }
        }

        $this->info("Selesai. Kurir instan dipesan: {$booked}. Percobaan ulang berhasil: {$retried}.");

        return self::SUCCESS;
    }
}
