@extends('layouts.ruang-titip')
@section('title', 'Checkout')

@php function rp($n){ return 'Rp'.number_format($n,0,',','.'); } @endphp

@section('content')
<<<<<<< HEAD
@php
    $back = $s['logistic'] === 'instant' ? route('ruang-titip.kurir')
          : ($s['logistic'] === 'rutip' ? route('ruang-titip.alamat') : route('ruang-titip.logistik'));
@endphp
<main class="wrap">
  <a class="back-link" href="{{ $back }}">
    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5M11 18l-6-6 6-6"/></svg>
    Kembali
  </a>
=======
<div class="max-w-xl mx-auto pt-6 pb-8">
    @php
        $back = $s['logistic'] === 'instant' ? route('ruang-titip.kurir')
              : ($s['logistic'] === 'rutip' ? route('ruang-titip.alamat') : route('ruang-titip.logistik'));
    @endphp
    @include('dashboard.ruang-titip._progress', ['step' => 4])
    <h1 class="text-xl font-extrabold text-white font-display mb-1">Checkout</h1>
    <p class="text-xs mb-6" style="color:rgba(255,255,255,0.4);">Langkah 4 dari 4 - Review &amp; selesaikan pesanan</p>
>>>>>>> hostinger/main

  <div class="layout">
    <div>
      @include('dashboard.ruang-titip._progress', ['step' => 4])

      <div class="step-head">
        <h1>Cek lagi, lalu bayar</h1>
        <p>Pastikan semua sudah benar sebelum lanjut.</p>
      </div>

      @if ($errors->any())
        <p class="err" style="margin-top:12px">{{ $errors->first() }}</p>
      @endif

      <div class="panel">
        <h2>Rincian tagihan</h2>
        <div class="lines">
          <div class="line"><span>Biaya Penitipan ({{ $calc['totalItems'] }} item &middot; {{ $calc['months'] }} bln)</span><span>{{ rp($calc['itemSubtotal']) }}</span></div>
          @if ($s['logistic'] === 'rutip')
            <div class="line"><span>Antar-Jemput RuTip ({{ $calc['km'] }} km)</span><span>{{ rp($calc['kmCost']) }}</span></div>
            <div class="line"><span>Jasa Packing ({{ $calc['totalItems'] }} &times; Rp15.000)</span><span>{{ rp($calc['packingCost']) }}</span></div>
          @elseif ($s['logistic'] === 'instant' && $courier)
            <div class="line"><span>Pengiriman ({{ $courier->name }} {{ $courier->service }})</span><span>{{ rp($calc['courierCost']) }}</span></div>
          @endif
          <div class="line"><span>Biaya Layanan Platform</span><span>{{ rp($calc['platform_fee']) }}</span></div>
        </div>
        <div class="total"><span style="font-weight:700">Total bayar</span><strong>{{ rp($calc['total']) }}</strong></div>
        @if ($s['logistic'] === 'rutip')
          <p class="note">Estimasi jarak {{ $calc['km'] }} km dari alamatmu ke gudang.</p>
        @endif
      </div>

<<<<<<< HEAD
      <form method="POST" action="{{ route('ruang-titip.place') }}">
=======
    {{-- Rincian --}}
    <div class="rounded-2xl p-5 mb-4" style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.09);">
        <h3 class="text-sm font-bold text-white mb-4">Rincian Total Tagihan</h3>
        <div class="space-y-3 mb-4">
            <div class="flex items-start justify-between gap-2">
                <span class="text-xs leading-relaxed" style="color:rgba(255,255,255,0.5);">Biaya Penitipan ({{ $calc['totalItems'] }} item · {{ $calc['months'] }} bln)</span>
                <span class="text-xs font-semibold text-white shrink-0">{{ rp($calc['itemSubtotal']) }}</span>
            </div>

            @if ($s['logistic'] === 'rutip')
                {{-- Rincian Anjem RuTip: jarak + per-kardus jemput + packing --}}
                <div class="flex items-start justify-between gap-2">
                    <span class="text-xs leading-relaxed" style="color:rgba(255,255,255,0.5);">Ongkos Jemput ({{ $calc['km'] }} km × Rp4.000)</span>
                    <span class="text-xs font-semibold text-white shrink-0">{{ rp($calc['kmCost']) }}</span>
                </div>
                <div class="flex items-start justify-between gap-2">
                    <span class="text-xs leading-relaxed" style="color:rgba(255,255,255,0.5);">Penjemputan Kardus ({{ $calc['totalItems'] }} × Rp2.000)</span>
                    <span class="text-xs font-semibold text-white shrink-0">{{ rp($calc['pickupBoxCost']) }}</span>
                </div>
                <div class="flex items-start justify-between gap-2">
                    <span class="text-xs leading-relaxed" style="color:rgba(255,255,255,0.5);">Jasa Packing ({{ $calc['totalItems'] }} × Rp3.000)</span>
                    <span class="text-xs font-semibold text-white shrink-0">{{ rp($calc['packingCost']) }}</span>
                </div>
                <div class="flex items-center gap-2 px-3 py-2 rounded-lg text-[10px]" style="background:rgba(251,191,36,0.08);border:1px solid rgba(251,191,36,0.2);color:#fbbf24;">
                    <x-lucide-info class="w-3 h-3 shrink-0" /> Estimasi jarak {{ $calc['km'] }} km dari alamatmu ke gudang.
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
>>>>>>> hostinger/main
        @csrf

        @if ($s['logistic'] !== 'self')
          <div class="panel">
            <x-pickup-schedule theme="light" :old-date="old('pickup_date')" :old-time="old('pickup_time')" />
          </div>
        @endif

<<<<<<< HEAD
        <div class="panel">
          <label class="agree">
            <input type="checkbox" name="agree" value="1">
            <span>Saya menyetujui <a href="{{ route('legal.terms') }}" target="_blank">Syarat &amp; Ketentuan</a> Garansi Batas Tetap dan <a href="{{ route('legal.privacy') }}" target="_blank">Kebijakan Privasi</a> RuangTitip.</span>
          </label>
=======
        <label class="flex items-start gap-3 px-4 py-3.5 rounded-xl mb-4 cursor-pointer" style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.09);">
            <input type="checkbox" name="agree" value="1" class="mt-0.5 accent-violet-500 w-4 h-4 shrink-0">
            <span class="text-xs leading-relaxed" style="color:rgba(255,255,255,0.55);">
                Saya menyetujui <a href="{{ route('legal.terms') }}" target="_blank" class="font-semibold underline" style="color:#a78bfa;">Syarat &amp; Ketentuan</a> Garansi Batas Tetap dan <a href="{{ route('legal.privacy') }}" target="_blank" class="font-semibold underline" style="color:#a78bfa;">Kebijakan Privasi</a> RUTIP.
            </span>
        </label>

        <div class="rounded-2xl p-5 mb-6" style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.09);">
            <h3 class="text-sm font-bold text-white mb-4">Pilih Metode Pembayaran</h3>
            <input type="hidden" name="payment_method">
            <x-tripay-channel-picker button-id="placeBtn" />
>>>>>>> hostinger/main
        </div>

        <div class="panel">
          <h2>Metode pembayaran</h2>
          <p class="hint">Pembayaran diproses aman lewat Tripay.</p>
          <input type="hidden" name="payment_method">
          <div style="margin-top:16px">
            <x-tripay-channel-picker theme="light" button-id="placeBtn" />
          </div>
        </div>

        <div class="actions">
          <a href="{{ $back }}" class="btn btn-outline">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
            Kembali
          </a>
          <button type="submit" id="placeBtn" disabled class="btn btn-primary">Bayar {{ rp($calc['total']) }}</button>
        </div>
      </form>
    </div>

    @include('dashboard.ruang-titip._summary', ['storage' => $storage, 's' => $s, 'calc' => $calc, 'courier' => $courier])
  </div>
</main>
@endsection
