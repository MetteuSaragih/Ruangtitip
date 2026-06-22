@extends('layouts.dashboard')

@section('title', 'Status Pembayaran')

@php
    if (! function_exists('rupiah')) {
        function rupiah($n) { return 'Rp ' . number_format($n, 0, ',', '.'); }
    }
    $isPaid    = $order->payment_status === 'PAID';
    $isPending = in_array($order->payment_status, ['UNPAID', 'pending']);
    $isFailed  = in_array($order->payment_status, ['EXPIRED', 'FAILED', 'REFUND']);
@endphp

@section('content')
<div class="py-6 pb-24 flex flex-col items-center text-center max-w-xl mx-auto">

    {{-- Ikon status --}}
    @if ($isPaid)
        <div class="w-24 h-24 rounded-full flex items-center justify-center mb-6 mt-4"
             style="background:rgba(52,211,153,0.15);border:3px solid rgba(52,211,153,0.4);box-shadow:0 0 40px rgba(52,211,153,0.2);">
            <x-lucide-check class="w-12 h-12" style="color:#34d399;" />
        </div>
        <h1 class="text-2xl font-extrabold text-white font-display mb-2">Pembayaran Berhasil!</h1>
        <p class="text-sm mb-2" style="color:rgba(255,255,255,0.5);">Pesananmu telah dikonfirmasi</p>
    @elseif ($isFailed)
        <div class="w-24 h-24 rounded-full flex items-center justify-center mb-6 mt-4"
             style="background:rgba(239,68,68,0.15);border:3px solid rgba(239,68,68,0.4);box-shadow:0 0 40px rgba(239,68,68,0.2);">
            <x-lucide-x class="w-12 h-12" style="color:#f87171;" />
        </div>
        <h1 class="text-2xl font-extrabold text-white font-display mb-2">Pembayaran Gagal</h1>
        <p class="text-sm mb-2" style="color:rgba(255,255,255,0.5);">Pembayaran dibatalkan atau kedaluwarsa</p>
    @else
        <div class="w-24 h-24 rounded-full flex items-center justify-center mb-6 mt-4"
             style="background:rgba(251,191,36,0.15);border:3px solid rgba(251,191,36,0.4);box-shadow:0 0 40px rgba(251,191,36,0.2);">
            <x-lucide-clock class="w-12 h-12" style="color:#fbbf24;" />
        </div>
        <h1 class="text-2xl font-extrabold text-white font-display mb-2">Menunggu Pembayaran</h1>
        <p class="text-sm mb-2" style="color:rgba(255,255,255,0.5);">Selesaikan pembayaranmu untuk melanjutkan</p>
    @endif

    <p class="text-xs font-mono mb-8" style="color:rgba(167,139,250,0.7);">#{{ $order->order_number }}</p>

    {{-- Tombol lanjut bayar — redirect ke halaman checkout Tripay --}}
    @if ($isPending && $order->tripay_checkout_url)
        <a href="{{ $order->tripay_checkout_url }}"
           class="w-full py-4 rounded-2xl font-bold text-sm text-white flex items-center justify-center gap-2 transition-all hover:scale-[1.02] mb-4"
           style="background:linear-gradient(135deg,#7c3aed,#6366f1);box-shadow:0 6px 20px rgba(124,58,237,0.4);">
            <x-lucide-credit-card class="w-4 h-4" /> Lanjutkan Pembayaran
        </a>
        @if ($order->tripay_pay_code)
            <p class="text-xs mb-6" style="color:rgba(255,255,255,0.45);">Kode pembayaran: <span class="font-mono font-bold text-white">{{ $order->tripay_pay_code }}</span></p>
        @endif
    @endif

    {{-- Banner WhatsApp tracking (hanya saat sudah lunas) --}}
    @if ($isPaid)
        <div class="w-full rounded-2xl p-5 mb-6 text-left"
             style="background:linear-gradient(135deg,rgba(37,211,102,0.15),rgba(18,140,78,0.1));border:1px solid rgba(37,211,102,0.3);">
            <div class="flex items-start gap-3">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0 text-xl" style="background:rgba(37,211,102,0.2);">💬</div>
                <div class="flex-1">
                    <p class="text-sm font-bold text-white mb-1">Notifikasi Tracking via WhatsApp</p>
                    <p class="text-xs leading-relaxed" style="color:rgba(255,255,255,0.6);">
                        Pesanan Anda sedang diproses. Status pelacakan dan resi pengiriman instan dari Biteship akan dikirimkan otomatis melalui <span class="font-semibold text-white">WhatsApp Anda</span>.
                    </p>
                    @if (($order->shipping_method ?? 'pickup') === 'pickup')
                        <div class="mt-2 flex items-center gap-1.5">
                            <x-lucide-store class="w-3.5 h-3.5 shrink-0" style="color:#34d399;" />
                            <p class="text-xs font-semibold" style="color:#34d399;">Siapkan pesananmu untuk dijemput di Gudang RUTIP</p>
                        </div>
                    @else
                        <div class="mt-2 flex items-center gap-1.5">
                            <x-lucide-package-check class="w-3.5 h-3.5 shrink-0" style="color:#34d399;" />
                            <p class="text-xs font-semibold" style="color:#34d399;">{{ $order->biteship_tracking_id ? 'Lacak pesananmu: ' . $order->biteship_tracking_id : 'Pesananmu sedang dibooking ke kurir Biteship' }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif

    {{-- Ringkasan pesanan --}}
    <div class="w-full rounded-2xl p-4 mb-8 text-left" style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.09);">
        <p class="text-xs font-bold text-white mb-3">Ringkasan Pesanan</p>
        @foreach ($order->items as $i => $item)
            @php $itemType = $item['type'] ?? 'preloved'; @endphp
            <div class="flex items-center gap-3 py-1.5"
                 style="{{ $i < count($order->items) - 1 ? 'border-bottom:1px solid rgba(255,255,255,0.05);' : '' }}">
                <div class="w-9 h-9 rounded-lg flex items-center justify-center overflow-hidden shrink-0" style="background:rgba(255,255,255,0.06);">
                    @if (!empty($item['image']))
                        @if ($itemType === 'packing')
                            <img src="{{ asset('storage/'.$item['image']) }}" alt="{{ $item['name'] }}" class="w-full h-full object-cover">
                        @else
                            <img src="{{ Storage::url($item['image']) }}" alt="{{ $item['name'] }}" class="w-full h-full object-cover">
                        @endif
                    @else
                        <x-lucide-image class="w-4 h-4" style="color:#a78bfa;" />
                    @endif
                </div>
                <span class="flex-1 text-xs" style="color:rgba(255,255,255,0.55);">{{ $item['name'] }} × {{ $item['qty'] }}</span>
                <span class="text-xs font-semibold text-white shrink-0">{{ rupiah($item['price'] * $item['qty']) }}</span>
            </div>
        @endforeach
        <div class="flex justify-between items-center py-1.5" style="border-bottom:1px solid rgba(255,255,255,0.05);">
            <span class="text-xs" style="color:rgba(255,255,255,0.55);">Biaya Layanan dan Platform</span>
            <span class="text-xs font-semibold text-white">{{ rupiah($order->service_fee) }}</span>
        </div>
        @if ($order->shipping_cost > 0)
            <div class="flex justify-between items-center py-1.5">
                <span class="text-xs" style="color:rgba(255,255,255,0.55);">Biaya Pengiriman</span>
                <span class="text-xs font-semibold text-white">{{ rupiah($order->shipping_cost) }}</span>
            </div>
        @endif
        <div class="flex justify-between items-center pt-2 mt-1" style="border-top:1px solid rgba(255,255,255,0.1);">
            <span class="text-xs font-bold text-white">Total {{ $isPaid ? 'Dibayar' : 'Tagihan' }}</span>
            <span class="text-sm font-extrabold font-display" style="color:#a78bfa;">{{ rupiah($order->total) }}</span>
        </div>
    </div>

    {{-- Aksi --}}
    <a href="{{ route('preloved.index') }}"
       class="w-full py-4 rounded-2xl font-bold text-sm flex items-center justify-center gap-2 transition-all hover:scale-[1.02]"
       style="{{ $isPaid
            ? 'background:linear-gradient(135deg,#7c3aed,#6366f1);color:white;box-shadow:0 6px 20px rgba(124,58,237,0.4);'
            : 'border:1.5px solid rgba(124,58,237,0.4);color:#a78bfa;' }}">
        <x-lucide-package class="w-4 h-4" /> {{ $isPaid ? 'Belanja Lagi' : 'Kembali ke Toko' }}
    </a>
</div>
@endsection
