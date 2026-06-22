@extends('layouts.dashboard')
@section('title', 'Pesanan Dibuat')

@php
    function rp($n){ return 'Rp '.number_format($n,0,',','.'); }
    $isPaid = $order->payment_status === 'PAID';
    $isPending = in_array($order->payment_status, ['UNPAID', 'pending']);
    $isFailed = in_array($order->payment_status, ['EXPIRED', 'FAILED', 'REFUND']);
@endphp

@section('content')
<div class="max-w-xl mx-auto pt-16 pb-8 flex flex-col items-center text-center">
    @if ($isPaid)
        <div class="w-20 h-20 rounded-full flex items-center justify-center mb-6" style="background:rgba(52,211,153,0.15);border:2px solid rgba(52,211,153,0.4);">
            <x-lucide-check-circle class="w-10 h-10" style="color:#34d399;" />
        </div>
        <h2 class="text-2xl font-extrabold text-white font-display mb-2">Pembayaran Berhasil!</h2>
    @elseif ($isFailed)
        <div class="w-20 h-20 rounded-full flex items-center justify-center mb-6" style="background:rgba(239,68,68,0.15);border:2px solid rgba(239,68,68,0.4);">
            <x-lucide-x-circle class="w-10 h-10" style="color:#f87171;" />
        </div>
        <h2 class="text-2xl font-extrabold text-white font-display mb-2">Pembayaran Gagal</h2>
    @else
        <div class="w-20 h-20 rounded-full flex items-center justify-center mb-6" style="background:rgba(251,191,36,0.15);border:2px solid rgba(251,191,36,0.4);">
            <x-lucide-clock class="w-10 h-10" style="color:#fbbf24;" />
        </div>
        <h2 class="text-2xl font-extrabold text-white font-display mb-2">Pesanan Dibuat!</h2>
    @endif

    <p class="text-sm mb-1" style="color:rgba(255,255,255,0.45);">Nomor pesanan #{{ $order->code() }}</p>
    <p class="text-sm mb-6" style="color:rgba(255,255,255,0.45);">
        Status: <span style="color:{{ $isPaid ? '#34d399' : ($isFailed ? '#f87171' : '#fbbf24') }};">{{ $isPaid ? 'Lunas' : ($isFailed ? 'Gagal/Kedaluwarsa' : 'Menunggu Pembayaran') }}</span>
    </p>

    @if ($isPending && $order->tripay_checkout_url)
        <a href="{{ $order->tripay_checkout_url }}"
           class="w-full py-3.5 rounded-xl font-bold text-sm text-white transition-all hover:scale-[1.02] mb-3"
           style="background:linear-gradient(135deg,#7c3aed,#6366f1);">
            Lanjutkan Pembayaran
        </a>
        @if ($order->tripay_pay_code)
            <p class="text-xs mb-6" style="color:rgba(255,255,255,0.45);">Kode pembayaran: <span class="font-mono font-bold text-white">{{ $order->tripay_pay_code }}</span></p>
        @endif
    @endif

    <div class="w-full rounded-2xl p-5 mb-6 text-left" style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.09);">
        @if ($order->storage)
            <div class="flex items-center gap-3 pb-4 mb-4" style="border-bottom:1px solid rgba(255,255,255,0.08);">
                <div class="w-12 h-12 rounded-xl flex items-center justify-center overflow-hidden shrink-0" style="background:rgba(124,58,237,0.1);">
                    @if ($order->storage->primary_photo)
                        <img src="{{ asset('storage/'.$order->storage->primary_photo) }}" alt="{{ $order->storage->name }}" class="w-full h-full object-cover">
                    @else
                        <x-lucide-warehouse class="w-5 h-5" style="color:#a78bfa;" />
                    @endif
                </div>
                <p class="text-sm font-bold text-white truncate">{{ $order->storage->name }}</p>
            </div>
        @endif
        <div class="flex items-center justify-between mb-2">
            <span class="text-xs" style="color:rgba(255,255,255,0.5);">Total Tagihan</span>
            <span class="text-lg font-extrabold font-display" style="color:#a78bfa;">{{ rp($order->total) }}</span>
        </div>
        <p class="text-[11px]" style="color:rgba(255,255,255,0.35);">Metode: {{ strtoupper($order->tripay_payment_method ?? $order->payment_method) }}</p>
    </div>

    <a href="{{ route('dashboard') }}" class="w-full py-3.5 rounded-xl font-bold text-sm text-white transition-all hover:scale-[1.02]" style="background:linear-gradient(135deg,#7c3aed,#6366f1);">Kembali ke Dashboard</a>
</div>
@endsection
