@extends('layouts.rt')
@section('title', 'Keranjang')

@php
    if (! function_exists('rp')) {
        function rp($n) { return 'Rp' . number_format($n, 0, ',', '.'); }
    }
    $isPickup = ($shipping['method'] ?? 'pickup') === 'pickup';
    $back = $isPickup ? route('checkout.shipping') : route('checkout.courier');
@endphp

@section('content')
<div class="mp">
<div class="stack">
    @include('partials.mp-head', [
        'crumbs' => [['Toko Preloved', route('preloved.index')], ['Keranjang', route('preloved.cart.index')], ['Checkout', null]],
        'title' => 'Ringkasan pesanan',
        'sub' => 'Pastikan pesanan dan pengirimannya sudah benar, lalu bayar.',
    ])
    @include('checkout._wiz', ['step' => 4])

    <div class="two">
        <div class="stack">
            @if (session('error'))
                <div class="alert a-err"><svg class="ic" aria-hidden="true"><use href="#i-warn"/></svg><div><b>{{ session('error') }}</b></div></div>
            @endif

            <section class="card">
                <div class="card-h"><h3>Barang dan penerimaan</h3><a class="more-link" href="{{ route('checkout.shipping') }}">Ubah</a></div>
                <div class="rows">
                    @foreach ($cart as $item)
                        <div><span>Barang</span><b>{{ $item['name'] }}<small>Jumlah {{ $item['qty'] }}</small></b></div>
                    @endforeach
                    <div><span>Penerimaan</span><b>{{ $isPickup ? 'Jemput sendiri ke gudang' : 'Dikirim RuangTitip' }}</b></div>
                    <div>
                        <span>{{ $isPickup ? 'Lokasi' : 'Tujuan' }}</span>
                        <b>@if ($isPickup)Gudang Ruru, Lowokwaru, Malang<small>Datang ke gudang dan tunjukkan kode pesanan yang muncul setelah bayar.</small>@else{{ $shipping['address']['full'] ?? '-' }}@if (! empty($shipping['courier_name']))<small>{{ $shipping['courier_name'] }}</small>@endif @endif</b>
                    </div>
                </div>
            </section>

            <form id="payForm" class="stack">
                @csrf

                @if (! $isPickup)
                    <section class="card">
                        <x-pickup-schedule theme="light" :old-date="old('pickup_date')" :old-time="old('pickup_time')" />
                    </section>
                @endif

                <section class="card stack" style="gap:12px">
                    <div><h3>Metode pembayaran</h3><p class="help">Pembayaran diproses aman lewat Tripay.</p></div>
                    <input type="hidden" name="payment_method">
                    <x-tripay-channel-picker theme="light" button-id="payBtn" />
                </section>

                <div class="nav-btns">
                    <a href="{{ $back }}" class="btn btn-ghost">Kembali</a>
                    <button type="button" id="payBtn" disabled class="btn btn-sun" onclick="processPayment()">Bayar {{ rp($total) }}</button>
                </div>
                <p class="msg" id="payErr"></p>
            </form>
        </div>

        <aside class="aside">
            <section class="card">
                <h3>Total pembayaran</h3>
                <div class="rows">
                    @foreach ($cart as $item)
                        <div><span>{{ $item['name'] }} &times;{{ $item['qty'] }}</span><b>{{ rp($item['subtotal']) }}</b></div>
                    @endforeach
                    <div><span>Biaya layanan</span><b>{{ rp($serviceFee) }}</b></div>
                    <div><span>Ongkir</span><b>{{ $shippingCost === 0 ? 'Rp0' : rp($shippingCost) }}</b></div>
                    <div class="tot"><span>Total</span><b>{{ rp($total) }}</b></div>
                </div>
                <p class="help mt">Dibayar penuh lewat Tripay. Status pesanan bisa dicek di Pesanan Saya setelah bayar.</p>
            </section>
        </aside>
    </div>
</div>
</div>
@endsection

@push('scripts')
<script>
function processPayment() {
    const selectedPayment = document.querySelector('input[name="payment_method"]').value;
    if (!selectedPayment) return;

    const btn = document.getElementById('payBtn');
    const originalLabel = btn.innerHTML;
    btn.innerHTML = '<span class="spin"></span>Memproses...';
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
