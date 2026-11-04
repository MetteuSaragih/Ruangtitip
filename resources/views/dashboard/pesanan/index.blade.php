@extends('layouts.rt')

@section('title', 'Pesanan Saya')

@php
    if (! function_exists('rp')) {
        function rp($n) { return 'Rp' . number_format($n, 0, ',', '.'); }
    }
    $tabs = [
        'semua' => ['Semua', $countSemua],
        'tindakan' => ['Perlu tindakan', $countTindakan],
        'aktif' => ['Aktif', $countBerlangsung],
        'selesai' => ['Selesai', $countSelesai],
        'exp' => ['Kedaluwarsa', $countExp],
    ];
    $hasAny = $orders->count() > 0 || $packingOrders->count() > 0 || $genericOrders->count() > 0;
@endphp

@section('content')
@include('dashboard.ruang-titip._icons')
<div class="ttp">
<div class="stack">
    <nav class="crumbs" aria-label="Breadcrumb"><span aria-current="page">Pesanan Saya</span></nav>
    <div class="ph">
        <div>
            <h2 class="pt" tabindex="-1">Pesanan Saya</h2>
            <p class="ps">Pantau status, pembayaran, dan barang di setiap pesanan.</p>
        </div>
        <div class="ph-acts"><a class="btn btn-sun" href="{{ route('ruang-titip.index') }}">Titip barang</a></div>
    </div>

    <div class="tabs" role="tablist">
        @foreach ($tabs as $key => [$label, $count])
            <a class="chip" role="tab" aria-selected="{{ $tab === $key ? 'true' : 'false' }}" href="{{ route('pesanan.index', ['tab' => $key]) }}">{{ $label }}<span class="c">{{ $count }}</span></a>
        @endforeach
    </div>

    @if ($hasAny)
        <div class="olist" role="tabpanel">
            @foreach ($orders as $order)
                @include('dashboard.pesanan._card', ['order' => $order])
            @endforeach
            @foreach ($genericOrders as $order)
                @include('dashboard.pesanan._card_order', ['order' => $order])
            @endforeach
            @foreach ($packingOrders as $order)
                @include('dashboard.pesanan._card_packing', ['order' => $order])
            @endforeach
        </div>
    @elseif ($countSemua === 0)
        <section class="card state">
            <svg viewBox="0 0 44 40" width="90" height="82" aria-hidden="true"><use href="#box"/></svg>
            <h3>Belum ada pesanan</h3>
            <p>Pesanan penitipan yang kamu buat akan muncul di sini.</p>
            <div class="acts"><a class="btn btn-primary" href="{{ route('ruang-titip.index') }}">Titip barang</a></div>
        </section>
    @else
        <section class="card state">
            <svg viewBox="0 0 44 40" width="90" height="82" aria-hidden="true"><use href="#box"/></svg>
            <h3>Tidak ada pesanan di sini</h3>
            <p>{{ $tab === 'tindakan' ? 'Tidak ada pesanan yang perlu kamu tindak lanjuti.' : 'Coba lihat tab lain.' }}</p>
        </section>
    @endif
</div>
</div>
@endsection
