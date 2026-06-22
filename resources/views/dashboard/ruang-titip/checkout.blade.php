@extends('layouts.dashboard')
@section('title', 'Checkout')

@php function rp($n){ return 'Rp '.number_format($n,0,',','.'); } @endphp

@section('content')
<div class="max-w-xl mx-auto pt-6 pb-8">
    @php
        $back = $s['logistic'] === 'instant' ? route('ruang-titip.kurir')
              : ($s['logistic'] === 'rutip' ? route('ruang-titip.alamat') : route('ruang-titip.logistik'));
    @endphp
    @include('dashboard.ruang-titip._progress', ['step' => 4])
    <h1 class="text-xl font-extrabold text-white font-display mb-1">Checkout</h1>
    <p class="text-xs mb-6" style="color:rgba(255,255,255,0.4);">Langkah 4 dari 4 — Review &amp; selesaikan pesanan</p>

    @if ($errors->any())
        <div class="rounded-xl px-4 py-2.5 mb-4 text-sm" style="background:rgba(239,68,68,0.1);border:1px solid rgba(239,68,68,0.3);color:#fca5a5;">{{ $errors->first() }}</div>
    @endif

    {{-- Gudang terpilih --}}
    @if ($storage)
        <div class="flex items-center gap-3 p-4 rounded-2xl mb-4" style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.09);">
            <div class="w-14 h-14 rounded-xl flex items-center justify-center overflow-hidden shrink-0" style="background:rgba(124,58,237,0.1);">
                @if ($storage->primary_photo)
                    <img src="{{ asset('storage/'.$storage->primary_photo) }}" alt="{{ $storage->name }}" class="w-full h-full object-cover">
                @else
                    <x-lucide-warehouse class="w-6 h-6" style="color:#a78bfa;" />
                @endif
            </div>
            <div class="min-w-0">
                <p class="text-sm font-bold text-white truncate">{{ $storage->name }}</p>
                <p class="text-[11px] truncate" style="color:rgba(255,255,255,0.45);">{{ $storage->address }}</p>
            </div>
        </div>
    @endif

    {{-- Rincian --}}
    <div class="rounded-2xl p-5 mb-4" style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.09);">
        <h3 class="text-sm font-bold text-white mb-4">Rincian Total Tagihan</h3>
        <div class="space-y-3 mb-4">
            <div class="flex items-start justify-between gap-2">
                <span class="text-xs leading-relaxed" style="color:rgba(255,255,255,0.5);">Biaya Penitipan ({{ $calc['totalItems'] }} item · {{ $calc['months'] }} bln)</span>
                <span class="text-xs font-semibold text-white shrink-0">{{ rp($calc['itemSubtotal']) }}</span>
            </div>

            @if ($s['logistic'] === 'rutip')
                {{-- Rincian Anjem RuTip: jarak + packing --}}
                <div class="flex items-start justify-between gap-2">
                    <span class="text-xs leading-relaxed" style="color:rgba(255,255,255,0.5);">Antar-Jemput RuTip ({{ $calc['km'] }} km)</span>
                    <span class="text-xs font-semibold text-white shrink-0">{{ rp($calc['kmCost']) }}</span>
                </div>
                <div class="flex items-start justify-between gap-2">
                    <span class="text-xs leading-relaxed" style="color:rgba(255,255,255,0.5);">Jasa Packing ({{ $calc['totalItems'] }} × Rp15.000)</span>
                    <span class="text-xs font-semibold text-white shrink-0">{{ rp($calc['packingCost']) }}</span>
                </div>
                <div class="flex items-center gap-2 px-3 py-2 rounded-lg text-[10px]" style="background:rgba(251,191,36,0.08);border:1px solid rgba(251,191,36,0.2);color:#fbbf24;">
                    <x-lucide-info class="w-3 h-3 shrink-0" /> Jarak {{ $calc['km'] }} km masih dummy — nanti dihitung otomatis dari alamatmu ke gudang.
                </div>
            @elseif ($s['logistic'] === 'instant' && $courier)
                <div class="flex items-start justify-between gap-2">
                    <span class="text-xs leading-relaxed" style="color:rgba(255,255,255,0.5);">Pengiriman ({{ $courier->name }} {{ $courier->service }})</span>
                    <span class="text-xs font-semibold text-white shrink-0">{{ rp($calc['courierCost']) }}</span>
                </div>
            @endif

            <div class="flex items-start justify-between gap-2">
                <span class="text-xs leading-relaxed" style="color:rgba(255,255,255,0.5);">Biaya Layanan Platform</span>
                <span class="text-xs font-semibold text-white shrink-0">{{ rp($calc['platform_fee']) }}</span>
            </div>
        </div>
        <div class="flex items-center justify-between pt-3" style="border-top:1px solid rgba(255,255,255,0.1);">
            <span class="text-sm font-bold text-white">Total</span>
            <span class="text-xl font-extrabold font-display" style="color:#a78bfa;">{{ rp($calc['total']) }}</span>
        </div>
    </div>

    <form method="POST" action="{{ route('ruang-titip.place') }}">
        @csrf

        @if ($s['logistic'] !== 'self')
            <div class="rounded-2xl p-5 mb-4" style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.09);">
                <x-pickup-schedule :old-date="old('pickup_date')" :old-time="old('pickup_time')" />
            </div>
        @endif

        <label class="flex items-start gap-3 px-4 py-3.5 rounded-xl mb-4 cursor-pointer" style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.09);">
            <input type="checkbox" name="agree" value="1" class="mt-0.5 accent-violet-500 w-4 h-4 shrink-0">
            <span class="text-xs leading-relaxed" style="color:rgba(255,255,255,0.55);">
                Saya menyetujui <span class="font-semibold underline" style="color:#a78bfa;">Syarat &amp; Ketentuan</span> Garansi Batas Tetap dan <span class="font-semibold underline" style="color:#a78bfa;">Kebijakan Privasi</span> RUTIP.
            </span>
        </label>

        <div class="rounded-2xl p-5 mb-6" style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.09);">
            <h3 class="text-sm font-bold text-white mb-4">Pilih Metode Pembayaran</h3>
            <input type="hidden" name="payment_method">
            <x-tripay-channel-picker button-id="placeBtn" />
        </div>

        <div class="flex gap-3">
            <a href="{{ $back }}" class="flex items-center justify-center gap-1.5 py-4 px-4 rounded-2xl font-semibold text-sm hover:bg-white/5 shrink-0" style="border:1.5px solid rgba(255,255,255,0.18);color:rgba(255,255,255,0.65);">
                <x-lucide-chevron-left class="w-4 h-4" /> Kembali
            </a>
            <button type="submit" id="placeBtn" disabled class="flex-1 py-4 rounded-2xl font-bold text-base text-white flex items-center justify-center gap-2 transition-all hover:scale-[1.02] disabled:opacity-40" style="background:linear-gradient(135deg,#7c3aed,#6366f1);box-shadow:0 8px 24px rgba(124,58,237,0.5);">
                Bayar {{ rp($calc['total']) }}
            </button>
        </div>
    </form>
</div>
@endsection
