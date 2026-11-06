<?php

namespace App\Services;

use App\Mail\AdminAlertMail;
use App\Models\Order;
use App\Models\PackingOrder;
use App\Models\PrelovedSubmission;
use App\Models\TitipanOrder;
use App\Models\TitipanRequest;
use App\Models\User;
use App\Support\StorageSizes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

/**
 * Email pemberitahuan ke SEMUA admin terdaftar setiap ada pesanan, pembayaran, atau permintaan masuk.
 *
 * Aman dipanggil di mana saja: tidak pernah melempar galat dan tidak menunda respons ke pelanggan
 * (email dikirim setelah respons selesai). Gagal kirim hanya dicatat di log.
 */
class AdminNotifier
{
    /* ───────────── Penerima dan pengiriman ───────────── */

    /** Email semua admin aktif ditambah alamat ekstra di .env, tanpa duplikat. */
    public static function recipients(?int $exceptUserId = null): array
    {
        $emails = User::where('role', 'admin')
            ->where('is_active', true)
            ->when($exceptUserId, fn ($q) => $q->where('id', '!=', $exceptUserId))
            ->pluck('email')
            ->all();

        $emails = array_merge($emails, (array) config('notify.admin_email.extra', []));

        $clean = [];
        foreach ($emails as $email) {
            $email = strtolower(trim((string) $email));
            if ($email !== '' && filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $clean[$email] = $email;
            }
        }

        return array_values($clean);
    }

    /**
     * @param  array<int, array{0:string,1:string}>  $rows
     */
    public static function send(string $subject, string $headline, array $rows, ?string $url, string $kind = 'info', ?string $note = null, ?int $exceptUserId = null): void
    {
        try {
            if (! config('notify.admin_email.enabled')) {
                return;
            }

            dispatch(function () use ($subject, $headline, $rows, $url, $kind, $note, $exceptUserId) {
                foreach (self::recipients($exceptUserId) as $email) {
                    try {
                        Mail::to($email)->send(new AdminAlertMail($subject, $headline, $rows, $url, $kind, $note));
                    } catch (Throwable $e) {
                        // Satu penerima gagal tidak boleh menghentikan penerima lain.
                        Log::warning('Email admin gagal dikirim ke ' . $email . ': ' . $e->getMessage());
                    }
                }
            })->afterResponse();
        } catch (Throwable $e) {
            report($e);
        }
    }

    /** Jalankan pembuat email; galat apa pun hanya dicatat. */
    private static function guard(callable $fn): void
    {
        try {
            $fn();
        } catch (Throwable $e) {
            report($e);
        }
    }

    /* ───────────── Pesanan baru ───────────── */

    public static function newTitip(TitipanOrder $order): void
    {
        self::guard(function () use ($order) {
            $order->loadMissing(['user', 'storage']);
            $drop = $order->pickup_date
                ? $order->pickup_date->translatedFormat('j M Y') . ($order->pickup_time ? ', pukul ' . str_replace(':', '.', substr((string) $order->pickup_time, 0, 5)) . ' WIB' : '')
                : '-';

            self::send(
                'Pesanan titip baru ' . $order->code(),
                'Pesanan titip baru masuk',
                [
                    ['Pesanan', $order->code()],
                    ['Pelanggan', self::who($order->user)],
                    ['Ruang', $order->storage->name ?? '-'],
                    ['Barang', self::titipItems($order)],
                    ['Periode', self::period($order)],
                    ['Jadwal antar', $drop],
                    ['Total', self::rp($order->total)],
                    ['Status bayar', ManualPayment::label($order->payment_status)],
                ],
                route('admin.titip.show', $order),
                'order',
                'Pelanggan akan membayar lewat QRIS lalu mengunggah bukti. Kamu akan menerima email lagi saat bukti masuk.'
            );
        });
    }

    public static function newPreloved(Order $order): void
    {
        self::guard(function () use ($order) {
            self::send(
                'Pesanan preloved baru ' . $order->order_number,
                'Pesanan preloved baru masuk',
                [
                    ['Pesanan', (string) $order->order_number],
                    ['Pelanggan', self::contact($order->customer_name, $order->customer_email, $order->customer_phone)],
                    ['Barang', self::orderItems($order)],
                    ['Pengambilan', 'Jemput sendiri di gudang'],
                    ['Total', self::rp($order->total)],
                    ['Status bayar', ManualPayment::label($order->payment_status)],
                ],
                route('admin.tagihan.show', ['preloved', $order->id]),
                'order',
                'Pelanggan akan membayar lewat QRIS lalu mengunggah bukti.'
            );
        });
    }

    public static function newPacking(PackingOrder $order): void
    {
        self::guard(function () use ($order) {
            $order->loadMissing('user');

            self::send(
                'Pesanan packing baru ' . $order->order_code,
                'Pesanan Toko Packing baru masuk',
                [
                    ['Pesanan', (string) $order->order_code],
                    ['Pelanggan', self::who($order->user)],
                    ['Barang', self::orderItems($order)],
                    ['Total', self::rp($order->total)],
                    ['Status bayar', ManualPayment::label($order->payment_status)],
                ],
                route('admin.tagihan.show', ['packing', $order->id]),
                'order'
            );
        });
    }

    /* ───────────── Pembayaran ───────────── */

    /** Bukti pembayaran masuk (atau dikirim ulang): admin perlu memeriksa. */
    public static function proofUploaded(Model $order): void
    {
        self::guard(function () use ($order) {
            $type = ManualPayment::typeOf($order);
            $attempt = (int) ($order->payment_attempts ?? 1);
            $resubmit = $attempt > 1;

            self::send(
                ($resubmit ? 'Bukti bayar dikirim ulang ' : 'Bukti bayar masuk ') . ManualPayment::codeOf($order),
                $resubmit ? 'Bukti pembayaran dikirim ulang, perlu diperiksa' : 'Bukti pembayaran masuk, perlu diperiksa',
                [
                    ['Pesanan', ManualPayment::codeOf($order)],
                    ['Jenis', ['titip' => 'Ruang Titip', 'preloved' => 'Toko Preloved', 'packing' => 'Toko Packing'][$type] ?? $type],
                    ['Pelanggan', self::customerOf($order)],
                    ['Nominal', self::rp($order->total)],
                    ['Unggahan ke', (string) $attempt],
                ],
                route('admin.tagihan.show', [$type, $order->getKey()]),
                'action',
                'Cocokkan dengan mutasi QRIS, lalu klik "Pembayaran berhasil" atau "Pembayaran gagal". Pelanggan sedang menunggu.'
            );
        });
    }

    public static function paymentConfirmed(Model $order, ?User $by): void
    {
        self::guard(function () use ($order, $by) {
            $type = ManualPayment::typeOf($order);
            self::send(
                'Pembayaran dikonfirmasi ' . ManualPayment::codeOf($order),
                'Pembayaran dikonfirmasi',
                [
                    ['Pesanan', ManualPayment::codeOf($order)],
                    ['Pelanggan', self::customerOf($order)],
                    ['Nominal', self::rp($order->total)],
                    ['Dikonfirmasi oleh', $by->name ?? 'Admin'],
                ],
                route('admin.tagihan.show', [$type, $order->getKey()]),
                'info',
                ! empty($order->needs_admin_attention) ? 'Perhatian: ada barang di pesanan ini yang sudah terjual lewat pesanan lain. Mohon dicek.' : null,
                $by?->id
            );
        });
    }

    public static function paymentRejected(Model $order, ?User $by, string $reason): void
    {
        self::guard(function () use ($order, $by, $reason) {
            $type = ManualPayment::typeOf($order);
            self::send(
                'Pembayaran ditandai gagal ' . ManualPayment::codeOf($order),
                'Pembayaran ditandai gagal',
                [
                    ['Pesanan', ManualPayment::codeOf($order)],
                    ['Pelanggan', self::customerOf($order)],
                    ['Nominal', self::rp($order->total)],
                    ['Alasan', $reason],
                    ['Oleh', $by->name ?? 'Admin'],
                ],
                route('admin.tagihan.show', [$type, $order->getKey()]),
                'info',
                'Pelanggan diminta mengunggah ulang bukti pembayaran.',
                $by?->id
            );
        });
    }

    /* ───────────── Permintaan atas titipan ───────────── */

    public static function newRequest(TitipanRequest $req): void
    {
        self::guard(function () use ($req) {
            $req->loadMissing(['order', 'user']);
            $payload = (array) $req->payload;
            $detail = match ($req->type) {
                'perpanjang' => 'Tambah ' . ($payload['days'] ?? '?') . ' hari, berakhir baru ' . (isset($payload['new_date_end']) ? \Illuminate\Support\Carbon::parse($payload['new_date_end'])->translatedFormat('j M Y') : '-'),
                default => $req->typeLabel(),
            };

            $rows = [
                ['Jenis', $req->typeLabel()],
                ['Pesanan', $req->order ? $req->order->code() : '-'],
                ['Pelanggan', self::who($req->user)],
                ['Detail', $detail],
            ];
            if (trim((string) $req->catatan) !== '') {
                $rows[] = ['Catatan', (string) $req->catatan];
            }

            self::send(
                'Permintaan ' . mb_strtolower($req->typeLabel()) . ' ' . ($req->order ? $req->order->code() : ''),
                'Permintaan baru dari pelanggan: ' . mb_strtolower($req->typeLabel()),
                $rows,
                route('admin.permintaan.index', ['type' => $req->type]),
                'action',
                'Konfirmasi permintaan ini dan hubungi pelanggan bila perlu.'
            );
        });
    }

    /* ───────────── Jual barang preloved ───────────── */

    public static function sellSubmitted(PrelovedSubmission $s): void
    {
        self::guard(function () use ($s) {
            $s->loadMissing('user');
            self::send(
                'Pengajuan jual baru ' . $s->code,
                'Pengajuan jual barang baru masuk',
                [
                    ['Kode', (string) $s->code],
                    ['Pelanggan', self::who($s->user)],
                    ['Barang', (string) $s->name],
                    ['Kategori', (string) $s->category],
                    ['Asal barang', $s->source === 'titip' ? 'Barang titipan ' . $s->titip_ref : 'Dari luar'],
                ],
                route('admin.jual.show', $s),
                'action',
                'Barang menunggu seleksi admin.'
            );
        });
    }

    public static function sellScheduled(PrelovedSubmission $s): void
    {
        self::guard(function () use ($s) {
            $s->loadMissing('user');
            $slot = \App\Http\Controllers\PrelovedSellController::SLOTS[$s->schedule_slot] ?? (string) $s->schedule_slot;
            self::send(
                'Jadwal antar jual ' . $s->code,
                'Pelanggan memilih jadwal antar barang jual',
                [
                    ['Kode', (string) $s->code],
                    ['Pelanggan', self::who($s->user)],
                    ['Barang', (string) $s->name],
                    ['Jadwal', \Illuminate\Support\Carbon::parse($s->schedule_date)->translatedFormat('l, j F Y') . ', ' . $slot . ' WIB'],
                ],
                route('admin.jual.show', $s),
                'info'
            );
        });
    }

    public static function sellOfferAnswered(PrelovedSubmission $s, bool $accepted): void
    {
        self::guard(function () use ($s, $accepted) {
            $s->loadMissing('user');
            self::send(
                ($accepted ? 'Penawaran diterima ' : 'Penawaran ditolak ') . $s->code,
                $accepted ? 'Pelanggan menerima penawaran harga beli' : 'Pelanggan menolak penawaran harga beli',
                [
                    ['Kode', (string) $s->code],
                    ['Pelanggan', self::who($s->user)],
                    ['Barang', (string) $s->name],
                    ['Penawaran', self::rp($s->offer)],
                ],
                route('admin.jual.show', $s),
                $accepted ? 'action' : 'info',
                $accepted ? 'Lanjutkan proses pembelian barang ini.' : null
            );
        });
    }

    /* ───────────── Format ───────────── */

    private static function rp($n): string
    {
        return 'Rp' . number_format((int) $n, 0, ',', '.');
    }

    private static function who(?User $u): string
    {
        return $u ? self::contact($u->name, $u->email, $u->phone ?? null) : '-';
    }

    private static function contact(?string $name, ?string $email, ?string $phone): string
    {
        $parts = array_filter([trim((string) $name), trim((string) $email), trim((string) $phone)]);

        return $parts ? implode("\n", $parts) : '-';
    }

    /** Nama dan kontak pelanggan untuk pesanan jenis apa pun. */
    private static function customerOf(Model $order): string
    {
        if ($order instanceof Order) {
            return self::contact($order->customer_name, $order->customer_email, $order->customer_phone);
        }
        $order->loadMissing('user');

        return self::who($order->user);
    }

    private static function titipItems(TitipanOrder $order): string
    {
        $lines = [];
        foreach ((array) $order->items as $code => $qty) {
            if ((int) $qty > 0) {
                $lines[] = (int) $qty . ' × ' . StorageSizes::label((string) $code);
            }
        }

        return $lines ? implode("\n", $lines) : '-';
    }

    private static function orderItems(Model $order): string
    {
        $lines = [];
        foreach ((array) ($order->items ?? []) as $item) {
            $lines[] = ((int) ($item['qty'] ?? 1)) . ' × ' . ($item['name'] ?? 'Barang');
        }

        return $lines ? implode("\n", $lines) : '-';
    }

    private static function period(TitipanOrder $order): string
    {
        if (! $order->date_start || ! $order->date_end) {
            return '-';
        }
        $days = $order->date_start->diffInDays($order->date_end) + 1;

        return $order->date_start->translatedFormat('j M Y') . ' sampai ' . $order->date_end->translatedFormat('j M Y') . ' (' . $days . ' hari)';
    }
}
