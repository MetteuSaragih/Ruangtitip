@extends('layouts.ruang-titip')
@section('title', 'Checkout')

@php function rp($n){ return 'Rp'.number_format($n,0,',','.'); } @endphp

@section('content')
@php
    $back = $s['logistic'] === 'instant' ? route('ruang-titip.kurir')
          : ($s['logistic'] === 'rutip' ? route('ruang-titip.alamat') : route('ruang-titip.logistik'));
@endphp
<main class="wrap">
  <a class="back-link" href="{{ $back }}">
    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5M11 18l-6-6 6-6"/></svg>
    Kembali
  </a>

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

      <form method="POST" action="{{ route('ruang-titip.place') }}">
        @csrf

        @if ($s['logistic'] !== 'self')
          <div class="panel">
            <h2>Waktu penjemputan</h2>
            <x-pickup-slot-picker
              :old-date="old('pickup_date')"
              :old-start="old('pickup_time')"
              :old-end="old('pickup_time_end')" />
          </div>
        @endif

        <div class="panel">
          <label class="agree">
            <input type="checkbox" name="agree" value="1">
            <span>Saya menyetujui <a href="{{ route('legal.terms') }}" target="_blank">Syarat &amp; Ketentuan</a> Garansi Batas Tetap dan <a href="{{ route('legal.privacy') }}" target="_blank">Kebijakan Privasi</a> RuangTitip.</span>
          </label>
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
