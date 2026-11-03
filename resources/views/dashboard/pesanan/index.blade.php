@extends('layouts.rt')

@section('title', 'Pesanan Saya')

@php
    if (! function_exists('rp')) {
        function rp($n) { return 'Rp' . number_format($n, 0, ',', '.'); }
    }
    $types = ['semua' => 'Semua', 'titip' => 'Ruang Titip', 'packing' => 'Toko Packing', 'preloved' => 'Toko Preloved'];
    $hasAny = $orders->count() > 0 || $packingOrders->count() > 0 || $genericOrders->count() > 0;
    $soon = $overview['soonest_end_days'] !== null && $overview['soonest_end_days'] <= 7;
@endphp

@section('content')
<div class="pg">
    <div class="pg-head">
        <div>
            <h2>Pesanan Saya</h2>
            <p>Pantau titipan, belanjaan, dan riwayat pesananmu di satu tempat.</p>
        </div>
        <div class="pg-acts"><a class="btn btn-sun" href="{{ route('ruang-titip.index') }}">Titip barang</a></div>
    </div>

    <div class="pg-stats" aria-label="Ringkasan titipan">
        <div class="rt-card pg-stat"><small>Titipan aktif</small><b>{{ $overview['active'] }}</b></div>
        <div class="rt-card pg-stat {{ $soon ? 'warn' : '' }}"><small>Titipan terdekat berakhir</small><b>{{ $overview['soonest_end_days'] !== null ? $overview['soonest_end_days'] . ' hari lagi' : '-' }}</b></div>
        <div class="rt-card pg-stat {{ $overview['pending_payment'] ? 'warn' : '' }}"><small>Menunggu pembayaran</small><b>{{ $overview['pending_payment'] }}</b></div>
    </div>

    <div class="pg-filters" role="tablist" aria-label="Status pesanan">
        <a class="chip" role="tab" aria-selected="{{ $tab === 'berlangsung' ? 'true' : 'false' }}" href="{{ route('pesanan.index', ['tab' => 'berlangsung', 'jenis' => $type]) }}">Berlangsung <span class="c">{{ $countBerlangsung }}</span></a>
        <a class="chip" role="tab" aria-selected="{{ $tab === 'selesai' ? 'true' : 'false' }}" href="{{ route('pesanan.index', ['tab' => 'selesai', 'jenis' => $type]) }}">Selesai <span class="c">{{ $countSelesai }}</span></a>
        <span class="sep" aria-hidden="true"></span>
        @foreach ($types as $key => $label)
            <a class="chip" aria-pressed="{{ $type === $key ? 'true' : 'false' }}" href="{{ route('pesanan.index', ['tab' => $tab, 'jenis' => $key]) }}">{{ $label }}</a>
        @endforeach
    </div>

    @if ($hasAny)
        <div class="po-list">
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
    @else
        <section class="rt-card pg-empty">
            <img src="{{ asset('assets/ruru/ruru-kosong.webp') }}" alt="" width="110" height="119">
            <h3>{{ $tab === 'selesai' ? 'Belum ada pesanan selesai' : 'Belum ada pesanan aktif' }}</h3>
            <p>{{ $tab === 'selesai' ? 'Pesanan yang sudah selesai akan tersimpan di sini sebagai riwayat.' : 'Ruru lagi nganggur nih. Mau titip barang, beli perlengkapan packing, atau cari barang preloved?' }}</p>
            @if ($tab !== 'selesai')
                <div class="rt-state"><div class="acts" style="margin-top:0">
                    <a class="btn btn-sun" href="{{ route('ruang-titip.index') }}">Titip barang</a>
                    <a class="btn btn-ghost" href="{{ route('packing.index') }}">Beli perlengkapan</a>
                    <a class="btn btn-ghost" href="{{ route('preloved.index') }}">Cari preloved</a>
                </div></div>
            @endif
        </section>
    @endif
</div>
@endsection
