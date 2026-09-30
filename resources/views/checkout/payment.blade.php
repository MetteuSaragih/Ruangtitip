@extends('layouts.ruang-titip')
@section('title', 'Ringkasan Pembayaran')

@php function rp($n){ return 'Rp'.number_format($n,0,',','.'); } @endphp

@section('content')
@php
    $back = ($shipping['method'] ?? 'pickup') === 'biteship' ? route('checkout.courier') : route('checkout.shipping');
@endphp
<main class="wrap">
  <a class="back-link" href="{{ $back }}">
    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5M11 18l-6-6 6-6"/></svg>
    Kembali
  </a>

  <div class="layout">
    <div>
      <ol class="stepper" aria-label="Langkah checkout">
        <li class="done"><button type="button"><span class="bar"></span><span class="lbl"><span class="num"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg></span><span>Pengambilan</span></span></button></li>
        <li class="done {{ ($shipping['method'] ?? 'pickup') === 'pickup' ? 'skip' : '' }}"><button type="button"><span class="bar"></span><span class="lbl"><span class="num"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg></span><span>Alamat</span></span></button></li>
        <li class="done {{ ($shipping['method'] ?? 'pickup') === 'pickup' ? 'skip' : '' }}"><button type="button"><span class="bar"></span><span class="lbl"><span class="num"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg></span><span>Kurir</span></span></button></li>
        <li class="now"><button type="button"><span class="bar"></span><span class="lbl"><span class="num">4</span><span>Bayar</span></span></button></li>
      </ol>

      <div class="step-head">
        <h1>Cek lagi, lalu bayar</h1>
        <p>Pastikan pesanan dan pengirimannya sudah benar.</p>
      </div>

      @if (session('error'))
        <p class="err" style="margin-top:12px">{{ session('error') }}</p>
      @endif

      <div class="panel">
        <h2>{{ ($shipping['method'] ?? 'pickup') === 'pickup' ? 'Ambil di Gudang RuangTitip' : 'Dikirim ke ' . ($shipping['address']['full'] ?? '-') }}</h2>
        <p class="hint" style="margin-top:6px">
          @if (($shipping['method'] ?? 'pickup') === 'pickup')
            Datang ke gudang RuangTitip dan tunjukkan kode pesanan yang muncul setelah bayar.
          @else
            {{ $shipping['courier_name'] ?? '' }}
          @endif
        </p>
      </div>

      <div class="panel">
        <h2>Rincian tagihan</h2>
        <div class="lines">
          @foreach ($cart as $item)
            <div class="line"><span>{{ $item['name'] }} &times;{{ $item['qty'] }}</span><span>{{ rp($item['subtotal']) }}</span></div>
          @endforeach
          <div class="line"><span>Biaya Layanan dan Platform</span><span>{{ rp($serviceFee) }}</span></div>
          <div class="line"><span>Biaya Pengiriman</span><span>{{ $shippingCost === 0 ? 'Gratis' : rp($shippingCost) }}</span></div>
        </div>
        <div class="total"><span style="font-weight:700">Total bayar</span><strong>{{ rp($total) }}</strong></div>
      </div>

      <form id="payForm">
        @csrf

        @if (($shipping['method'] ?? 'pickup') === 'biteship')
          <div class="panel">
            <x-pickup-schedule theme="light" :old-date="old('pickup_date')" :old-time="old('pickup_time')" />
          </div>
        @endif

        <div class="panel">
          <h2>Metode pembayaran</h2>
          <p class="hint">Pembayaran diproses aman lewat Tripay.</p>
          <input type="hidden" name="payment_method">
          <div style="margin-top:16px">
            <x-tripay-channel-picker theme="light" button-id="payBtn" />
          </div>
        </div>

        <div class="actions">
          <a href="{{ $back }}" class="btn btn-outline">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
            Kembali
          </a>
          <button type="button" id="payBtn" disabled class="btn btn-primary" onclick="processPayment()">Bayar {{ rp($total) }}</button>
        </div>
        <p class="err" id="payErr"></p>
      </form>
    </div>

    @include('checkout._summary', ['cart' => $cart, 'shipping' => $shipping])
  </div>
</main>

@push('scripts')
<script>
function processPayment() {
    const selectedPayment = document.querySelector('input[name="payment_method"]').value;
    if (!selectedPayment) return;

    const btn = document.getElementById('payBtn');
    const originalLabel = btn.innerHTML;
    btn.innerHTML = 'Memproses...';
    btn.disabled = true;

    const pickupDate = document.querySelector('input[name="pickup_date"]');
    const pickupTime = document.querySelector('input[name="pickup_time"]');

    fetch('{{ route("checkout.process") }}', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        body: JSON.stringify({
            payment_method: selectedPayment,
            pickup_date: pickupDate ? pickupDate.value : null,
            pickup_time: pickupTime ? pickupTime.value : null,
        })
    })
    .then(r => r.json())
    .then(data => {
        if (data.redirect) {
            window.location.href = data.redirect;
        } else {
            document.getElementById('payErr').textContent = data.message || 'Gagal memproses pembayaran.';
            btn.innerHTML = originalLabel;
            btn.disabled = false;
        }
    })
    .catch(() => {
        document.getElementById('payErr').textContent = 'Gagal memproses pembayaran.';
        btn.innerHTML = originalLabel;
        btn.disabled = false;
    });
}
</script>
@endpush
@endsection
