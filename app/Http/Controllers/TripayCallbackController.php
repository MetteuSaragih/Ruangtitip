<?php

namespace App\Http\Controllers;

use App\Events\OrderPaid;
use App\Models\Order;
use App\Models\PackingOrder;
use App\Models\PrelovedItem;
use App\Models\TitipanOrder;
use App\Services\TripayService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TripayCallbackController extends Controller
{
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
     * dan menembakkan event OrderPaid — tidak langsung memesan Biteship di
     * sini. Pemesanan Biteship ada di App\Listeners\BookShipmentOnOrderPaid
     * (kurir reguler) dan App\Console\Commands\BookPendingBiteshipShipments
     * (kurir instan + percobaan ulang), lewat App\Services\BiteshipOrderBooker.
     * Kalau nanti ditambah gateway lain (mis. Midtrans), webhook-nya cukup
     * fire event OrderPaid yang sama tanpa menyentuh kode Biteship sama sekali.
     */
    public function handle(Request $request, TripayService $tripay)
    {
        $rawBody = $request->getContent();
        $signature = $request->header('X-Callback-Signature');

        if (! $tripay->verifyCallbackSignature($rawBody, $signature)) {
            Log::warning('Tripay callback signature tidak valid', ['body' => $rawBody]);

            return response()->json(['success' => false, 'message' => 'Invalid signature'], 403);
        }

        $payload = json_decode($rawBody, true) ?? [];
        $merchantRef = $payload['merchant_ref'] ?? null;
        $status = $payload['status'] ?? null; // PAID|UNPAID|EXPIRED|FAILED|REFUND

        if (! $merchantRef || ! $status) {
            return response()->json(['success' => false, 'message' => 'Payload tidak lengkap'], 422);
        }

        Log::info("Tripay callback {$merchantRef}: {$status}");

        if (str_starts_with($merchantRef, 'TP-')) {
            $order = PackingOrder::where('order_code', $merchantRef)->first();
            if ($order) {
                $this->applyPackingStatus($order, $status);
            }
        } elseif (str_starts_with($merchantRef, 'RT-')) {
            $order = Order::where('order_number', $merchantRef)->first();
            if ($order) {
                $this->applyOrderStatus($order, $status);
            }
        } elseif (str_starts_with($merchantRef, 'RTP-')) {
            $id = (int) ltrim(substr($merchantRef, 4), '0');
            $order = TitipanOrder::find($id);
            if ($order) {
                $this->applyTitipanStatus($order, $status);
            }
        }

        return response()->json(['success' => true]);
    }

    private function applyPackingStatus(PackingOrder $order, string $status): void
    {
        // Idempoten: kalau sudah PAID sebelumnya, jangan proses ulang efek
        // sampingnya (event OrderPaid dkk) walau Tripay kirim callback dobel.
        $alreadyPaid = $order->payment_status === 'PAID';

        $order->payment_status = $status;
        if ($status === 'PAID') {
            $order->status = 'diproses';
        }
        $order->save();

        if ($status === 'PAID' && ! $alreadyPaid) {
            event(new OrderPaid($order));
        }
    }

    private function applyOrderStatus(Order $order, string $status): void
    {
        $alreadyPaid = $order->payment_status === 'PAID';

        $order->payment_status = $status;
        if ($status === 'PAID') {
            $order->status = Order::STATUS_PAID;
        }
        $order->save();

        if ($status === 'PAID' && ! $alreadyPaid) {
            $this->markPrelovedItemsSold($order);
            event(new OrderPaid($order));
        }
    }

    private function markPrelovedItemsSold(Order $order): void
    {
        $prelovedIds = collect($order->items)
            ->filter(fn ($i) => ($i['type'] ?? 'preloved') === 'preloved')
            ->pluck('id')
            ->filter()
            ->all();

        if (! empty($prelovedIds)) {
            PrelovedItem::whereIn('id', $prelovedIds)->update(['status' => 'Terjual']);
        }
    }

    private function applyTitipanStatus(TitipanOrder $order, string $status): void
    {
        $alreadyPaid = $order->payment_status === 'PAID';

        $order->payment_status = $status;
        if ($status === 'PAID') {
            $order->status = 'penjadwalan_penjemputan';
        }
        $order->save();

        if ($status === 'PAID' && ! $alreadyPaid) {
            event(new OrderPaid($order));
        }
    }
}
