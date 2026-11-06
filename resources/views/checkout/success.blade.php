@extends('layouts.rt')
@section('title', 'Keranjang')

@php
    if (! function_exists('rp')) {
        function rp($n) { return 'Rp' . number_format($n, 0, ',', '.'); }
    }
    $paidText = ($order->shipping_method ?? 'pickup') === 'pickup'
        ? 'Barang sudah jadi milikmu. Siapkan pesananmu untuk diambil di Gudang Ruru, Lowokwaru, Malang.'
        : ($order->biteship_tracking_id ? 'Barang sudah jadi milikmu. Lacak pesananmu dengan nomor ' . $order->biteship_tracking_id . '.' : 'Barang sudah jadi milikmu. Petugas akan mengemas dan mengirimnya lewat Biteship.');

    $rows = [];
    foreach ($order->items ?? [] as $item) {
        $rows[] = [($item['name'] ?? 'Barang') . ' ×' . ($item['qty'] ?? 1), rp(($item['price'] ?? 0) * ($item['qty'] ?? 1))];
    }
    $rows[] = ['Biaya layanan', rp($order->service_fee)];
    if ($order->shipping_cost > 0) {
        $rows[] = ['Ongkir', rp($order->shipping_cost)];
    }
    $rows[] = ['Metode', 'QRIS'];

    $isPaid = \App\Services\ManualPayment::state($order->payment_status) === 'paid';
    $actions = $isPaid
        ? [['Lihat pembelian saya', route('purchases.index'), 'btn-sun'], ['Belanja lagi', route('preloved.index'), 'btn-ghost']]
        : [['Lihat pembelian saya', route('purchases.index'), 'btn-ghost'], ['Kembali ke toko', route('preloved.index'), 'btn-ghost']];
@endphp

@section('content')
<div class="mp">
<div class="stack">
    @include('partials.mp-head', [
        'crumbs' => [['Toko Preloved', route('preloved.index')], ['Pembayaran', null]],
        'title' => 'Pembayaran',
    ])

    @include('partials.payment-panel', ['order' => $order, 'paidText' => $paidText, 'rows' => $rows, 'actions' => $actions])
</div>
</div>
@endsection
