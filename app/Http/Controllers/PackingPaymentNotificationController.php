<?php

namespace App\Http\Controllers;

use App\Models\PackingOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Midtrans\Config as MidtransConfig;
use Midtrans\Notification;

class PackingPaymentNotificationController extends Controller
{
    /**
     * Endpoint webhook yang dipanggil SERVER Midtrans setiap status
     * pembayaran berubah. Ini adalah SUMBER KEBENARAN status pembayaran,
     * bukan callback dari browser (yang bisa dimanipulasi user).
     *
     * Daftarkan URL ini di Dashboard Midtrans:
     *   Settings → Configuration → Payment Notification URL
     *   contoh: https://domainmu.com/midtrans/notification
     *
     * Route HARUS bebas dari middleware 'auth' dan dikecualikan dari CSRF.
     */
    public function handle(Request $request)
    {
        MidtransConfig::$serverKey    = config('midtrans.server_key');
        MidtransConfig::$isProduction = config('midtrans.is_production');

        try {
            $notif = new Notification();
        } catch (\Throwable $e) {
            Log::error('Midtrans notification gagal diparse: ' . $e->getMessage());
            return response()->json(['message' => 'Invalid notification'], 400);
        }

        $orderCode   = $notif->order_id;
        $statusCode  = $notif->status_code;
        $grossAmount = $notif->gross_amount;

        // Verifikasi signature (wajib — mencegah notifikasi palsu)
        $signatureOk = hash('sha512', $orderCode . $statusCode . $grossAmount . config('midtrans.server_key'))
            === $notif->signature_key;

        if (! $signatureOk) {
            Log::warning("Midtrans signature tidak valid untuk order {$orderCode}");
            return response()->json(['message' => 'Invalid signature'], 403);
        }

        $order = PackingOrder::where('order_code', $orderCode)->first();
        if (! $order) {
            return response()->json(['message' => 'Order tidak ditemukan'], 404);
        }

        $trxStatus = $notif->transaction_status;   // capture|settlement|pending|deny|cancel|expire|failure
        $fraud     = $notif->fraud_status ?? null;  // accept|challenge|deny

        $this->applyStatus($order, $trxStatus, $fraud);

        Log::info("Midtrans notif order {$orderCode}: {$trxStatus} (fraud: {$fraud})");

        return response()->json(['message' => 'OK']);
    }

    private function applyStatus(PackingOrder $order, string $trxStatus, ?string $fraud): void
    {
        $paid = false;

        switch ($trxStatus) {
            case 'capture':
                // Kartu kredit: cek fraud
                $paid = $fraud !== 'challenge';
                $order->payment_status = $fraud === 'challenge' ? 'challenge' : 'settlement';
                break;
            case 'settlement':
                $paid = true;
                $order->payment_status = 'settlement';
                break;
            case 'pending':
                $order->payment_status = 'pending';
                break;
            case 'deny':
            case 'cancel':
            case 'expire':
            case 'failure':
                $order->payment_status = $trxStatus;
                break;
            default:
                $order->payment_status = $trxStatus;
        }

        // status pesanan (fulfillment) ikut berubah saat lunas
        if ($paid) {
            $order->status = 'diproses';   // mulai diproses gudang
        }

        $order->save();
    }
}
