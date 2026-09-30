@extends('layouts.dashboard')

@section('title', 'Ringkasan Pembayaran')

@php
    if (! function_exists('rupiah')) {
        function rupiah($n) { return 'Rp ' . number_format($n, 0, ',', '.'); }
    }
@endphp

@section('content')
<div class="py-6 pb-24 flex flex-col items-center max-w-xl mx-auto">

    <x-checkout-progress :labels="['Opsi Logistik', 'Alamat', 'Kurir', 'Pembayaran']" :step="4" />
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
            <div class="flex items-center gap-3 py-1.5">
                <div class="w-10 h-10 rounded-lg flex items-center justify-center overflow-hidden shrink-0" style="background:rgba(255,255,255,0.06);">
                    @if (!empty($item['image']))
                        <img src="{{ asset('storage/'.$item['image']) }}" alt="{{ $item['name'] }}" class="w-full h-full object-cover">
                    @else
                        <x-lucide-package class="w-4 h-4" style="color:#a78bfa;" />
                    @endif
                </div>
                <span class="flex-1 text-sm" style="color:rgba(255,255,255,0.55);">{{ $item['name'] }} × {{ $item['qty'] }}</span>
                <span class="text-sm font-semibold text-white shrink-0">{{ rupiah($item['price'] * $item['qty']) }}</span>
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
            <div class="flex justify-between items-center py-1">
                <span class="text-sm" style="color:rgba(255,255,255,0.45);">Biaya Layanan Platform</span>
                <span class="text-sm font-medium text-white">{{ rupiah($platformFee) }}</span>
            </div>
        </div>

        <div class="flex justify-between items-center mt-3 pt-3" style="border-top:1px solid rgba(255,255,255,0.12);">
            <span class="text-sm font-bold text-white">Total Keseluruhan</span>
            <span class="text-lg font-extrabold font-display" style="color:#a78bfa;">{{ rupiah($total) }}</span>
        </div>
    </div>

    {{-- Total + Tombol Bayar --}}
    <form method="POST" action="{{ route('packing.pay') }}" class="w-full">
        @csrf

        @if ($logistic === 'biteship')
            <div class="w-full rounded-2xl p-5 mb-6" style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.09);">
                <x-pickup-schedule :old-date="old('pickup_date')" :old-time="old('pickup_time')" />
            </div>
        @endif

        {{-- Pilih metode pembayaran (Tripay) --}}
        <div class="w-full rounded-2xl p-5 mb-6" style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.09);">
            <p class="text-xs font-bold text-white mb-3">Pilih Metode Pembayaran</p>
            <input type="hidden" name="payment_method">
            <x-tripay-channel-picker button-id="payBtn" />
        </div>

        <div class="flex gap-3">
            <a href="{{ $logistic === 'biteship' ? route('packing.courier') : route('packing.logistics') }}"
               class="flex items-center justify-center gap-1.5 py-3.5 px-4 rounded-xl font-semibold text-sm hover:bg-white/5 shrink-0"
               style="border:1.5px solid rgba(255,255,255,0.18);color:rgba(255,255,255,0.65);">
                <x-lucide-chevron-left class="w-4 h-4" /> Kembali
            </a>
            <div class="flex-1 rounded-2xl px-5 py-3.5 flex items-center justify-between"
                 style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.09);">
                <div>
                    <p class="text-[10px]" style="color:rgba(255,255,255,0.4);">Total Pembayaran</p>
                    <p class="text-base font-extrabold font-display" style="color:#a78bfa;">{{ rupiah($total) }}</p>
                </div>
                <button type="submit" id="payBtn" disabled
                        onclick="this.disabled=true;this.innerHTML='<svg class=\'w-4 h-4 animate-spin\' fill=\'none\' viewBox=\'0 0 24 24\'><circle class=\'opacity-25\' cx=\'12\' cy=\'12\' r=\'10\' stroke=\'currentColor\' stroke-width=\'4\'></circle><path class=\'opacity-75\' fill=\'currentColor\' d=\'M4 12a8 8 0 018-8v8z\'></path></svg> Memproses...';this.closest('form').submit();"
                        class="flex items-center gap-2 px-5 py-2.5 rounded-xl font-bold text-sm text-white transition-all hover:scale-[1.02] active:scale-[0.98] disabled:opacity-40"
                        style="background:linear-gradient(135deg,#7c3aed,#6366f1);box-shadow:0 6px 20px rgba(124,58,237,0.4);">
                    Bayar →
                </button>
            </div>
        </div>
    </form>

</div>
@endsection