@extends('layouts.rt')

@section('title', 'Status Pembayaran')

@php
    if (! function_exists('rupiah')) {
        function rupiah($n) { return 'Rp' . number_format($n, 0, ',', '.'); }
    }
    $isPaid = \App\Services\ManualPayment::state($order->payment_status) === 'paid';
    $rows = [];
    foreach ($order->items ?? [] as $item) {
        $rows[] = [($item['name'] ?? 'Barang') . ' ×' . ($item['qty'] ?? 1), rupiah(($item['price'] ?? 0) * ($item['qty'] ?? 1))];
    }
    if ($order->shipping_cost > 0) {
        $rows[] = ['Ongkir', rupiah($order->shipping_cost)];
    }
    if ($order->platform_fee > 0) {
        $rows[] = ['Biaya platform', rupiah($order->platform_fee)];
    }
    $rows[] = ['Metode', 'QRIS'];
    $paidText = $order->logistic === 'pickup'
        ? 'Pesananmu sudah dikonfirmasi. Siapkan diri untuk mengambil pesanan di gudang RuangTitip.'
        : 'Pesananmu sudah dikonfirmasi. Status dan resi pengiriman dikirim ke WhatsApp-mu.';
@endphp

@section('content')
<div class="mp">
<div class="stack">
    @include('partials.mp-head', [
        'crumbs' => [['Toko Packing', route('packing.index')], ['Pembayaran', null]],
        'title' => 'Pembayaran',
    ])

    @include('partials.payment-panel', [
        'order' => $order,
        'paidText' => $paidText,
        'rows' => $rows,
        'actions' => [
            [$isPaid ? 'Belanja lagi' : 'Kembali ke toko', route('packing.index'), $isPaid ? 'btn-sun' : 'btn-ghost'],
            ['Pesanan saya', route('pesanan.index'), 'btn-ghost'],
        ],
    ])
</div>
</div>
@endsection
