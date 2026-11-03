@extends('layouts.rt')

@section('title', 'Ringkasan Pembayaran')

@php
    if (! function_exists('rupiah')) {
        function rupiah($n) { return 'Rp' . number_format($n, 0, ',', '.'); }
    }
@endphp

@section('content')
<div class="wrap">
    <a class="back-link" href="{{ $logistic === 'biteship' ? route('packing.courier') : route('packing.logistics') }}">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M19 12H5M11 18l-6-6 6-6"/></svg>
        Kembali
    </a>

    <form method="POST" action="{{ route('packing.pay') }}" class="layout" id="payForm">
        @csrf
        <div>
            <x-checkout-progress :labels="['Opsi logistik', 'Alamat', 'Kurir', 'Pembayaran']" :step="4" />

            <div class="step-head">
                <h1>Ringkasan pembayaran</h1>
                <p>Tinjau pesananmu, lalu selesaikan pembayaran.</p>
            </div>

            @if (session('error'))
                <p class="err">{{ session('error') }}</p>
            @endif
            @if ($errors->any())
                <p class="err">{{ $errors->first() }}</p>
            @endif

            @if ($logistic === 'biteship')
                <div class="panel">
                    <x-pickup-schedule theme="light" :old-date="old('pickup_date')" :old-time="old('pickup_time')" />
                </div>
            @endif

            <div class="panel">
                <h2>Metode pembayaran</h2>
                <p class="hint" style="margin-bottom:14px">Pembayaran diproses oleh Tripay.</p>
                <input type="hidden" name="payment_method">
                <x-tripay-channel-picker theme="light" button-id="payBtn" />
            </div>

            <div class="actions">
                <a href="{{ $logistic === 'biteship' ? route('packing.courier') : route('packing.logistics') }}" class="btn btn-outline">Kembali</a>
                <button type="submit" id="payBtn" disabled class="btn btn-primary">Bayar {{ rupiah($total) }}</button>
            </div>
        </div>

        <aside class="summary" aria-label="Rincian pembayaran">
            <div class="sec"><h3 style="margin:0">Pesananmu</h3></div>
            <div class="sec">
                <ul class="items">
                    @foreach ($items as $item)
                        <li><span>{{ $item['name'] }} &times;{{ $item['qty'] }}</span><span>{{ rupiah($item['price'] * $item['qty']) }}</span></li>
                    @endforeach
                </ul>
            </div>
            <div class="foot">
                <div class="lines">
                    <div class="line"><span>Subtotal produk</span><span>{{ rupiah($subtotal) }}</span></div>
                    @if ($shipping > 0)
                        <div class="line"><span>Ongkir{{ ! empty($courier['name']) ? ' (' . $courier['name'] . ')' : '' }}</span><span>{{ rupiah($shipping) }}</span></div>
                    @endif
                    <div class="line"><span>Biaya layanan</span><span>{{ rupiah($platformFee) }}</span></div>
                </div>
                <div class="total"><span style="font-weight:700">Total</span><strong>{{ rupiah($total) }}</strong></div>
            </div>
        </aside>
    </form>
</div>
@endsection

@push('scripts')
<script>
document.getElementById('payForm').addEventListener('submit', function () {
    var b = document.getElementById('payBtn');
    // Tunda sebentar supaya nilai form terkirim sebelum tombol dinonaktifkan.
    setTimeout(function () { b.disabled = true; b.textContent = 'Memproses...'; }, 0);
});
</script>
@endpush
