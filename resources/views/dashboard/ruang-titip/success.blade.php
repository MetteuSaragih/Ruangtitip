@extends('layouts.rt')
@section('title', 'Ruang Titip')

@php
    if (! function_exists('rp')) {
        function rp($n) { return 'Rp' . number_format($n, 0, ',', '.'); }
    }
    $isPaid = $order->payment_status === 'PAID';
    $isPending = in_array($order->payment_status, ['UNPAID', 'pending']);
    $isFailed = in_array($order->payment_status, ['EXPIRED', 'FAILED', 'REFUND']);
    $wa = 'https://wa.me/6285121091134';
    if ($isPaid) {
        [$tone, $icon, $title] = ['ok', 'i-check', 'Pembayaran terverifikasi'];
        $text = 'Pesanan ' . $order->code() . ' dan jadwal antar terkonfirmasi. Antar barang yang sudah dikemas ke ' . ($order->storage->name ?? 'gudang RuangTitip') . '.';
    } elseif ($isFailed) {
        [$tone, $icon, $title] = ['err', 'i-x', 'Pembayaran gagal'];
        $text = 'Transaksi tidak berhasil atau tagihan sudah kedaluwarsa. Hubungi admin bila kamu sudah membayar.';
    } else {
        [$tone, $icon, $title] = ['warn', 'i-clock', 'Menunggu pembayaran'];
        $text = 'Belum ada konfirmasi pembayaran ' . rp($order->total) . ' dari Tripay.';
    }
    $method = $order->tripay_payment_method ?: $order->payment_method;
@endphp

@section('content')
@include('dashboard.ruang-titip._icons')
<div class="ttp">
<div class="stack">
    <nav class="crumbs" aria-label="Breadcrumb"><a href="{{ route('ruang-titip.index') }}">Ruang Titip</a><span aria-hidden="true">/</span><span aria-current="page">Hasil pembayaran</span></nav>
    <div class="ph"><div><h2 class="pt" tabindex="-1">Hasil pembayaran</h2></div></div>

    <section class="card state" aria-live="polite">
        <div class="r-ic r-{{ $tone }}"><svg class="ic" aria-hidden="true"><use href="#{{ $icon }}"/></svg></div>
        <h3>{{ $title }}</h3>
        <p>{{ $text }}</p>
        <div class="rows" style="width:100%;max-width:460px;text-align:left">
            <div><span>No. pesanan</span><b>{{ $order->code() }}</b></div>
            <div><span>Tagihan</span><b>Tagihan pertama</b></div>
            <div><span>Nominal</span><b>{{ rp($order->total) }}</b></div>
            @if ($method)<div><span>Metode</span><b>{{ strtoupper($method) }}</b></div>@endif
            @if ($isPending && $order->tripay_pay_code)<div><span>Kode pembayaran</span><b>{{ $order->tripay_pay_code }}</b></div>@endif
        </div>
        <div class="acts">
            @if ($isPending && $order->tripay_checkout_url)
                <a class="btn btn-sun" href="{{ $order->tripay_checkout_url }}">Lanjutkan pembayaran</a>
            @elseif ($isFailed)
                <a class="btn btn-sun" href="{{ $wa }}" target="_blank" rel="noopener">Hubungi admin</a>
            @endif
            <a class="btn btn-ink" href="{{ route('pesanan.detail', $order) }}">Lihat Detail Pesanan</a>
            <a class="btn btn-ghost" href="{{ route('pesanan.index') }}">Ke Pesanan Saya</a>
        </div>
    </section>
</div>
</div>
@endsection
