@extends('layouts.dashboard')

@section('title', 'Ringkasan Pembayaran')

@php
    if (! function_exists('rupiah')) {
        function rupiah($n) { return 'Rp ' . number_format($n, 0, ',', '.'); }
    }
@endphp

@section('content')
<div class="py-6 pb-24 flex flex-col items-center max-w-xl mx-auto">

    <div class="w-full mb-2">
        <h1 class="text-xl font-extrabold text-white font-display">Ringkasan Pembayaran</h1>
        <p class="text-sm mt-1" style="color:rgba(255,255,255,0.45);">Tinjau dan selesaikan pembayaran</p>
    </div>

    @if (session('error'))
        <div class="w-full rounded-2xl px-4 py-3 mb-4 text-sm font-medium" style="background:rgba(239,68,68,0.15);border:1px solid rgba(239,68,68,0.3);color:#f87171;">
            {{ session('error') }}
        </div>
    @endif

    {{-- Ringkasan Harga --}}
    <div class="w-full rounded-2xl p-5 mb-4" style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.09);">
        <p class="text-xs font-bold text-white mb-3">Total Harga Produk</p>
        @foreach ($items as $item)
            <div class="flex justify-between items-center py-1.5">
                <span class="text-sm" style="color:rgba(255,255,255,0.55);">{{ $item['name'] }} × {{ $item['qty'] }}</span>
                <span class="text-sm font-semibold text-white">{{ rupiah($item['price'] * $item['qty']) }}</span>
            </div>
        @endforeach

        <div class="mt-3 pt-3" style="border-top:1px solid rgba(255,255,255,0.08);">
            <div class="flex justify-between items-center py-1">
                <span class="text-sm" style="color:rgba(255,255,255,0.45);">Subtotal Produk</span>
                <span class="text-sm font-medium text-white">{{ rupiah($subtotal) }}</span>
            </div>
            @if ($shipping > 0)
            <div class="flex justify-between items-center py-1">
                <span class="text-sm" style="color:rgba(255,255,255,0.45);">Biaya Pengiriman ({{ $courier['name'] ?? '' }})</span>
                <span class="text-sm font-medium text-white">{{ rupiah($shipping) }}</span>
            </div>
            @endif
        </div>

        <div class="flex justify-between items-center mt-3 pt-3" style="border-top:1px solid rgba(255,255,255,0.12);">
            <span class="text-sm font-bold text-white">Total Keseluruhan</span>
            <span class="text-lg font-extrabold font-display" style="color:#a78bfa;">{{ rupiah($total) }}</span>
        </div>
    </div>

    {{-- Info: pilih metode di popup Midtrans --}}
    <div class="w-full rounded-2xl px-4 py-3 mb-6 flex items-center gap-3" style="background:rgba(124,58,237,0.1);border:1px solid rgba(124,58,237,0.25);">
        <span class="text-xl">💳</span>
        <p class="text-xs leading-relaxed" style="color:rgba(255,255,255,0.6);">
            Pilih metode pembayaran — QRIS, Virtual Account, atau E-Wallet —
            langsung di halaman pembayaran aman Midtrans.
        </p>
    </div>

    {{-- Total + Tombol Bayar --}}
    <div class="w-full rounded-2xl px-5 py-4 flex items-center justify-between"
         style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.09);">
        <div>
            <p class="text-xs" style="color:rgba(255,255,255,0.4);">Total Pembayaran</p>
            <p class="text-xl font-extrabold font-display" style="color:#a78bfa;">{{ rupiah($total) }}</p>
        </div>

        {{-- form tanpa radio button: langsung submit ke pay() --}}
        <form method="POST" action="{{ route('packing.pay') }}">
            @csrf
            {{-- nilai ini tidak dipakai lagi di controller, tapi field required wajib ada --}}
            <input type="hidden" name="payment_method" value="snap">
            <button type="submit"
                    class="flex items-center gap-2 px-6 py-3 rounded-2xl font-bold text-sm text-white transition-all hover:scale-[1.02] active:scale-[0.98]"
                    style="background:linear-gradient(135deg,#7c3aed,#6366f1);box-shadow:0 6px 20px rgba(124,58,237,0.4);">
                Pilih Metode Pembayaran →
            </button>
        </form>
    </div>

</div>
@endsection