@extends('layouts.rt')
@section('title', 'Ruang Titip')

@php
    if (! function_exists('rp')) {
        function rp($n) { return 'Rp' . number_format($n, 0, ',', '.'); }
    }
    $paidText = 'Pesanan ' . $order->code() . ' dan jadwal antar terkonfirmasi. Antar barang yang sudah dikemas ke ' . ($order->storage->name ?? 'gudang RuangTitip') . '.';
@endphp

@section('content')
@include('dashboard.ruang-titip._icons')
<div class="ttp">
<div class="stack">
    <nav class="crumbs" aria-label="Breadcrumb"><a href="{{ route('ruang-titip.index') }}">Ruang Titip</a><span aria-hidden="true">/</span><span aria-current="page">Pembayaran</span></nav>
    <div class="ph"><div><h2 class="pt" tabindex="-1">Pembayaran</h2></div></div>

    @include('partials.payment-panel', [
        'order' => $order,
        'paidText' => $paidText,
        'rows' => [
            ['Tagihan', 'Tagihan pertama'],
            ['Ruang', $order->storage->name ?? 'Gudang RuangTitip'],
            ['Metode', 'QRIS'],
        ],
        'actions' => [
            ['Lihat Detail Pesanan', route('pesanan.detail', $order), 'btn-ink'],
            ['Ke Pesanan Saya', route('pesanan.index'), 'btn-ghost'],
        ],
    ])
</div>
</div>
@endsection
