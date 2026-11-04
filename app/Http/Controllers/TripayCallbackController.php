<?php

namespace App\Http\Controllers;

use App\Events\OrderPaid;
use App\Models\Order;
use App\Models\PackingOrder;
use App\Models\PrelovedItem;
use App\Models\TitipanOrder;
use App\Services\TripayService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TripayCallbackController extends Controller
{
    private const STATUSES = ['PAID', 'UNPAID', 'EXPIRED', 'FAILED', 'REFUND'];

    /**
     * Webhook server-to-server dari Tripay setiap status transaksi berubah.
     * Ini SUMBER KEBENARAN status pembayaran, bukan redirect return_url di browser.
     *
     * Daftarkan URL ini di dashboard Tripay: Merchant > Pengaturan > Callback URL,
     * contoh: https://domainmu.com/tripay/callback
     *
     * Route HARUS bebas dari middleware 'auth' dan dikecualikan dari CSRF.
     *
     * Begitu status jadi PAID, controller ini CUMA menandai pesanan lunas
     * dan menembakkan event OrderPaid (sekali saja) — pemesanan Biteship ada di
     * App\Listeners\BookShipmentOnOrderPaid dan App\Console\Commands\BookPendingBiteshipShipments.
     */
    public function handle(Request $request, TripayService $tripay)
    {
        $rawBody = $request->getContent();
        $signature = $request->header('X-Callback-Signature');

        // Signature diperiksa PERTAMA, sebelum payload dibaca atau diproses.
        if (! $tripay->verifyCallbackSignature($rawBody, $signature)) {
            Log::warning('Tripay callback signature tidak valid', ['ip' => $request->ip()]);

            return response()->json(['success' => false, 'message' => 'Invalid signature'], 403);
        }

        // Tripay juga mengirim event lain; hanya "payment_status" yang relevan.
        $event = $request->header('X-Callback-Event');
        if ($event !== null && $event !== 'payment_status') {
            return response()->json(['success' => true]);
        }

        $payload = json_decode($rawBody, true);
        $payload = is_array($payload) ? $payload : [];
        $merchantRef = isset($payload['merchant_ref']) ? (string) $payload['merchant_ref'] : '';
        $status = isset($payload['status']) ? strtoupper((string) $payload['status']) : '';

        if ($merchantRef === '' || $status === '') {
            return response()->json(['success' => false, 'message' => 'Payload tidak lengkap'], 422);
        }

        if (! in_array($status, self::STATUSES, true)) {
            Log::warning("Tripay callback {$merchantRef}: status tidak dikenal {$status}");

            return response()->json(['success' => true]);
        }

        Log::info("Tripay callback {$merchantRef}: {$status}");

        try {
            $paidOrder = $this->apply($merchantRef, $status);
        } catch (\Throwable $e) {
            report($e);

            // 500 membuat Tripay mengirim ulang callback; aman karena pemrosesan idempoten.
            return response()->json(['success' => false, 'message' => 'Server error'], 500);
        }

        if ($paidOrder) {
            try {
                event(new OrderPaid($paidOrder));
            } catch (\Throwable $e) {
                // Pemesanan kurir gagal tidak boleh membatalkan konfirmasi bayar;
                // command biteship:book-pending akan mengulang.
                report($e);
            }
        }

        // Pesanan tidak ditemukan tetap dibalas sukses agar Tripay tidak mengulang tanpa henti.
        return response()->json(['success' => true]);
    }

    /**
     * Terapkan status di dalam transaksi dengan row lock supaya callback ganda/bersamaan
     * tidak menggandakan efek. Mengembalikan pesanan bila baru saja berubah menjadi PAID.
     */
    private function apply(string $merchantRef, string $status): ?Model
    {
        return DB::transaction(function () use ($merchantRef, $status) {
            $order = null;

            if (str_starts_with($merchantRef, 'TP-')) {
                $order = PackingOrder::where('order_code', $merchantRef)->lockForUpdate()->first();
            } elseif (str_starts_with($merchantRef, 'RT-')) {
                $order = Order::where('order_number', $merchantRef)->lockForUpdate()->first();
            } elseif (preg_match('/^RTP-(\d+)$/', $merchantRef, $m)) {
                $order = TitipanOrder::whereKey((int) $m[1])->lockForUpdate()->first();
            }

            if (! $order) {
                Log::warning("Tripay callback {$merchantRef}: pesanan tidak ditemukan");

                return null;
            }

            $current = strtoupper((string) $order->payment_status);

            // Aturan transisi: PAID tidak pernah turun (kecuali REFUND); status akhir
            // gagal/kedaluwarsa tidak kembali ke UNPAID; callback sama diabaikan.
            if ($status === $current) {
                return null;
            }
            if ($current === 'PAID' && $status !== 'REFUND') {
                return null;
            }
            if ($status === 'REFUND' && $current !== 'PAID') {
                return null;
            }
            if ($status === 'UNPAID' && $current !== '' && $current !== 'PENDING') {
                return null;
            }

            $order->payment_status = $status;

            if ($status === 'PAID') {
                $this->markPaid($order);
            }
            $order->save();

            return $status === 'PAID' ? $order : null;
        });
    }

    private function markPaid(Model $order): void
    {
        if ($order instanceof PackingOrder) {
            if (in_array($order->status, ['menunggu_pembayaran', null, ''], true)) {
                $order->status = 'diproses';
            }
        } elseif ($order instanceof Order) {
            if (in_array($order->status, [Order::STATUS_PENDING, null, ''], true)) {
                $order->status = Order::STATUS_PAID;
            }
            $this->markPrelovedItemsSold($order);
        } elseif ($order instanceof TitipanOrder) {
            if (in_array($order->status, ['menunggu_pembayaran', null, ''], true)) {
                $order->status = 'penjadwalan_penjemputan';
            }
        }
    }

    private function markPrelovedItemsSold(Order $order): void
    {
        $prelovedIds = collect($order->items ?? [])
            ->filter(fn ($i) => ($i['type'] ?? 'preloved') === 'preloved')
            ->pluck('id')
            ->filter()
            ->all();

        if (empty($prelovedIds)) {
            return;
        }

        // Barang yang sudah Terjual lewat pesanan lain: tandai supaya admin menindaklanjuti (refund/batalkan).
        if (PrelovedItem::whereIn('id', $prelovedIds)->where('status', 'Terjual')->exists()) {
            $order->needs_admin_attention = true;
            $order->biteship_last_error = 'Barang preloved di pesanan ini sudah terjual lewat pesanan lain.';
        }

        PrelovedItem::whereIn('id', $prelovedIds)->update(['status' => 'Terjual']);
    }
}
