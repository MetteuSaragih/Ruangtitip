@extends('layouts.rt')
@section('title', 'Keranjang')

@php
    if (! function_exists('rp')) {
        function rp($n) { return 'Rp' . number_format($n, 0, ',', '.'); }
    }
    $isPaid = $order->payment_status === 'PAID';
    $isPending = in_array($order->payment_status, ['UNPAID', 'pending']);
    $isFailed = in_array($order->payment_status, ['EXPIRED', 'FAILED', 'REFUND']);
    if ($isPaid) {
        [$tone, $icon, $ttl] = ['ok', 'i-check', 'Pembayaran berhasil'];
        $txt = ($order->shipping_method ?? 'pickup') === 'pickup'
            ? 'Barang sudah jadi milikmu. Siapkan pesananmu untuk diambil di Gudang Ruru, Lowokwaru, Malang.'
            : ($order->biteship_tracking_id ? 'Barang sudah jadi milikmu. Lacak pesananmu dengan nomor ' . $order->biteship_tracking_id . '.' : 'Barang sudah jadi milikmu. Petugas akan mengemas dan mengirimnya lewat Biteship.');
    } elseif ($isFailed) {
        [$tone, $icon, $ttl] = ['err', 'i-x', 'Pembayaran gagal'];
        $txt = 'Pembayaran dibatalkan atau kedaluwarsa.';
    } else {
        [$tone, $icon, $ttl] = ['warn', 'i-clock', 'Menunggu pembayaran'];
        $txt = 'Selesaikan pembayaranmu untuk melanjutkan. Status berubah otomatis setelah Tripay mengonfirmasi.';
    }
@endphp

@section('content')
<div class="mp">
<div class="stack">
    @include('partials.mp-head', [
        'crumbs' => [['Toko Preloved', route('preloved.index')], ['Hasil pembayaran', null]],
        'title' => 'Hasil pembayaran',
    ])

    <section class="card state" aria-live="polite">
        <div class="r-ic r-{{ $tone }}"><svg class="ic" aria-hidden="true"><use href="#{{ $icon }}"/></svg></div>
        <h3>{{ $ttl }}</h3>
        <p>{{ $txt }}</p>
        <div class="rows" style="width:100%;max-width:440px;text-align:left">
            <div><span>Referensi</span><b>{{ $order->order_number }}</b></div>
            @foreach ($order->items as $item)
                <div><span>{{ $item['name'] }} &times;{{ $item['qty'] }}</span><b>{{ rp($item['price'] * $item['qty']) }}</b></div>
            @endforeach
            <div><span>Biaya layanan</span><b>{{ rp($order->service_fee) }}</b></div>
            @if ($order->shipping_cost > 0)
                <div><span>Ongkir</span><b>{{ rp($order->shipping_cost) }}</b></div>
            @endif
            <div class="tot"><span>{{ $isPaid ? 'Total dibayar' : 'Total tagihan' }}</span><b>{{ rp($order->total) }}</b></div>
            @if ($isPending && $order->tripay_pay_code)
                <div><span>Kode pembayaran</span><b>{{ $order->tripay_pay_code }}</b></div>
            @endif
        </div>
        <div class="acts">
            @if ($isPending && $order->tripay_checkout_url)
                <a href="{{ $order->tripay_checkout_url }}" class="btn btn-sun">Lanjutkan pembayaran</a>
            @endif
            @if ($isPaid)
                <a href="{{ route('purchases.index') }}" class="btn btn-sun">Lihat pembelian saya</a>
                <a href="{{ route('preloved.index') }}" class="btn btn-ghost">Belanja lagi</a>
            @else
                <a href="{{ route('preloved.cart.index') }}" class="btn btn-ghost">Kembali ke keranjang</a>
            @endif
        </div>
    </section>
</div>
</div>
@endsection
