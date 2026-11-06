@extends('layouts.rt')
@section('title', 'Keranjang')

@php
    if (! function_exists('rp')) {
        function rp($n) { return 'Rp' . number_format($n, 0, ',', '.'); }
    }
    $back = route('preloved.cart.index');
@endphp

@section('content')
<div class="mp">
<div class="stack">
    @include('partials.mp-head', [
        'crumbs' => [['Toko Preloved', route('preloved.index')], ['Keranjang', route('preloved.cart.index')], ['Checkout', null]],
        'title' => 'Ringkasan pesanan',
        'sub' => 'Pastikan pesananmu sudah benar, lalu bayar. Barang diambil sendiri di gudang.',
    ])

    <div class="two">
        <div class="stack">
            @if (session('error'))
                <div class="alert a-err"><svg class="ic" aria-hidden="true"><use href="#i-warn"/></svg><div><b>{{ session('error') }}</b></div></div>
            @endif

            <section class="card">
                <div class="card-h"><h3>Barang dan penerimaan</h3><a class="more-link" href="{{ route('preloved.cart.index') }}">Ubah</a></div>
                <div class="rows">
                    @foreach ($cart as $item)
                        <div><span>Barang</span><b>{{ $item['name'] }}<small>Jumlah {{ $item['qty'] }}</small></b></div>
                    @endforeach
                    <div><span>Penerimaan</span><b>Jemput sendiri ke gudang</b></div>
                    <div><span>Lokasi</span><b>Gudang Ruru, Lowokwaru, Malang<small>Datang ke gudang dan tunjukkan kode pesanan yang muncul setelah bayar.</small></b></div>
                </div>
            </section>

            <form id="payForm" class="stack">
                @csrf

                <section class="card stack">
                    <h3>Metode pembayaran</h3>
                    @include('partials.payment-method-qris')
                </section>

                <div class="nav-btns">
                    <a href="{{ $back }}" class="btn btn-ghost">Kembali</a>
                    <button type="button" id="payBtn" class="btn btn-sun" onclick="processPayment()">Buat pesanan dan bayar {{ rp($total) }}</button>
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
                    <div class="tot"><span>Total</span><b>{{ rp($total) }}</b></div>
                </div>
                <p class="help mt">Dibayar lewat QRIS. Setelah pesanan dibuat, unggah bukti pembayaran supaya admin bisa memeriksanya.</p>
            </section>
        </aside>
    </div>
</div>
</div>
@endsection

@push('scripts')
<script>
function processPayment() {
    const btn = document.getElementById('payBtn');
    const originalLabel = btn.innerHTML;
    btn.innerHTML = '<span class="spin"></span>Memproses...';
    btn.disabled = true;

    fetch('{{ route("checkout.process") }}', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        body: JSON.stringify({})
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
