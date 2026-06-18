@extends('layouts.dashboard')
@section('title', 'Pesanan Berhasil')

@php function rp($n){ return 'Rp '.number_format($n,0,',','.'); } @endphp

@section('content')
<div class="max-w-xl mx-auto pt-16 pb-8 flex flex-col items-center text-center">
    <div class="w-20 h-20 rounded-full flex items-center justify-center mb-6" style="background:rgba(52,211,153,0.15);border:2px solid rgba(52,211,153,0.4);">
        <x-lucide-check-circle class="w-10 h-10" style="color:#34d399;" />
    </div>
    <h2 class="text-2xl font-extrabold text-white font-display mb-2">Pesanan Dibuat!</h2>
    <p class="text-sm mb-1" style="color:rgba(255,255,255,0.45);">Nomor pesanan #RTP-{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}</p>
    <p class="text-sm mb-6" style="color:rgba(255,255,255,0.45);">Status: <span style="color:#fbbf24;">Menunggu pembayaran</span></p>

    <div class="w-full rounded-2xl p-5 mb-6 text-left" style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.09);">
        <div class="flex items-center justify-between mb-2">
            <span class="text-xs" style="color:rgba(255,255,255,0.5);">Total Tagihan</span>
            <span class="text-lg font-extrabold font-display" style="color:#a78bfa;">{{ rp($order->total) }}</span>
        </div>
        <p class="text-[11px]" style="color:rgba(255,255,255,0.35);">Metode: {{ strtoupper($order->payment_method) }} · {{ $order->storage->name ?? '' }}</p>
    </div>

    <p class="text-[11px] mb-6" style="color:rgba(255,255,255,0.3);">
        Catatan: pembayaran belum diproses karena integrasi payment gateway belum aktif.
    </p>

    <a href="{{ route('dashboard') }}" class="w-full py-3.5 rounded-xl font-bold text-sm text-white transition-all hover:scale-[1.02]" style="background:linear-gradient(135deg,#7c3aed,#6366f1);">Kembali ke Dashboard</a>
</div>
@endsection
