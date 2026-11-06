<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TitipanOrder extends Model
{
    protected $fillable = [
        'user_id','storage_id','item_type','items','date_start','date_end','pickup_time','pickup_time_end','pickup_date',
        'logistic','address','note','courier_code','packing','payment_method',
        'item_subtotal','courier_cost','packing_cost','platform_fee','total','status','proof_photo',
        'address_area_id','address_postal_code','courier_service_code','courier_company',
        'biteship_order_id','biteship_tracking_id','biteship_status','biteship_attempts',
        'biteship_last_error','needs_admin_attention',
        'payment_status','tripay_reference','tripay_checkout_url','tripay_pay_code','tripay_payment_method',
    ];

    protected $casts = [
        'items' => 'array',
        'date_start' => 'date',
        'date_end' => 'date',
        'pickup_date' => 'date',
        'payment_proof_at' => 'datetime',
        'payment_reviewed_at' => 'datetime',
        'paid_at' => 'datetime',
        'biteship_attempts' => 'integer',
        'needs_admin_attention' => 'boolean',
    ];

    public function storage(): BelongsTo { return $this->belongsTo(StorageRoom::class, 'storage_id'); }
    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function requests(): \Illuminate\Database\Eloquent\Relations\HasMany { return $this->hasMany(TitipanRequest::class, 'titipan_order_id'); }

    /* ─── Status fase pesanan (urut sesuai dokumen) ─── */
    public const FLOW = [
        'menunggu_pembayaran'     => ['label' => 'Menunggu Pembayaran', 'color' => '#fbbf24', 'icon' => 'clock'],
        'penjadwalan_penjemputan' => ['label' => 'Penjadwalan Penjemputan', 'color' => '#6366f1', 'icon' => 'calendar-clock'],
        'dalam_gudang'            => ['label' => 'Dalam Gudang RUTIP', 'color' => '#7c3aed', 'icon' => 'warehouse'],
        'proses_pengembalian'     => ['label' => 'Proses Pengembalian', 'color' => '#06b6d4', 'icon' => 'truck'],
        'selesai'                 => ['label' => 'Selesai', 'color' => '#34d399', 'icon' => 'check-circle'],
    ];

    public function isDone(): bool
    {
        return $this->status === 'selesai';
    }

    public function statusMeta(): array
    {
        return self::FLOW[$this->status] ?? ['label' => ucfirst(str_replace('_', ' ', $this->status)), 'color' => '#94a3b8', 'icon' => 'circle'];
    }

    /**
     * Label dan nada badge sesuai desain terbaru: [label, tone, tahap 0..6 pada stepper 6 langkah].
     */
    public function displayStatus(): array
    {
        return match ($this->status) {
            'menunggu_pembayaran'     => match (\App\Services\ManualPayment::state($this->payment_status)) {
                'review'   => ['Pembayaran Diperiksa', 'info', 1],
                'rejected' => ['Pembayaran Gagal', 'err', 1],
                default    => ['Menunggu Pembayaran', 'warn', 1],
            },
            'penjadwalan_penjemputan' => ['Menunggu Pengantaran', 'info', 2],
            'dalam_gudang'            => ['Dalam Penyimpanan', 'ok', 4],
            'proses_pengembalian'     => ['Menunggu Pengambilan', 'info', 4],
            'selesai'                 => ['Selesai', 'mute', 6],
            default                   => [ucfirst(str_replace('_', ' ', (string) $this->status)), 'info', 1],
        };
    }

    /** Indeks fase 0..4 untuk progress bar */
    public function statusStep(): int
    {
        $keys = array_keys(self::FLOW);
        $idx = array_search($this->status, $keys, true);
        return $idx === false ? 0 : $idx;
    }

    /** Nomor pesanan rapi: RTP-0001 */
    public function code(): string
    {
        return 'RTP-' . str_pad((string) $this->id, 4, '0', STR_PAD_LEFT);
    }

    /** Total kuantitas item */
    public function totalItems(): int
    {
        return array_sum(array_map('intval', $this->items ?? []));
    }
}
