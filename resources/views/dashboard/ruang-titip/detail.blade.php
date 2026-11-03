@extends('layouts.rt')

@section('title', $storage->name)

@php
    if (! function_exists('rp')) {
        function rp($n) { return 'Rp' . number_format($n, 0, ',', '.'); }
    }
    $images = ! empty($storage->photos) ? $storage->photos : ($storage->primary_photo ? [$storage->primary_photo] : []);
    $full = $storage->capacity_pct >= 100;
@endphp

@section('content')
<div class="pg">
    <nav class="crumbs" aria-label="Breadcrumb"><a href="{{ route('ruang-titip.index') }}">Ruang Titip</a><span aria-hidden="true">/</span><span aria-current="page">{{ $storage->name }}</span></nav>

    <div class="pg-head">
        <div>
            <h2>{{ $storage->name }}</h2>
            <p>{{ $storage->address }}</p>
        </div>
        <span class="badge b-{{ $full ? 'mute' : 'ok' }}"><i></i>{{ $full ? 'Penuh' : 'Aktif' }}</span>
    </div>

    <div class="pg-two">
        <div class="pg">
            <figure class="pg-photo" style="margin:0">
                @if (! empty($images))
                    <img src="{{ asset('storage/' . $images[0]) }}" alt="{{ $storage->name }}">
                @else
                    <svg aria-hidden="true"><use href="#n-ware"/></svg>
                @endif
            </figure>

            <section class="rt-card">
                <div class="rt-card-h"><h3>Tentang gudang ini</h3></div>
                <div class="rt-rows">
                    <div><span>Lokasi</span><b>{{ $storage->location ?: '-' }}</b></div>
                    <div><span>Alamat</span><b>{{ $storage->address }}</b></div>
                    <div><span>Mulai dari</span><b>{{ rp($storage->min_price) }} /bulan</b></div>
                </div>
                @if (count($storage->facilities ?? []))
                    <h4 style="margin:18px 0 8px;font-family:var(--body);font-size:14px">Fasilitas</h4>
                    <ul class="rm-tags" aria-label="Fasilitas">
                        @foreach ($storage->facilities as $tag)<li>{{ $tag }}</li>@endforeach
                    </ul>
                @endif
            </section>

            <section class="rt-card">
                <div class="rt-card-h"><h3>Aturan penitipan</h3></div>
                <ul class="bul" style="margin:0;padding-left:20px;display:grid;gap:6px;font-size:14px">
                    <li>Barang diantar sudah dikemas (kardus, koper, atau plastik wrap).</li>
                    <li>Isi kemasan tidak dibuka. Petugas hanya memeriksa jumlah, ukuran, kemasan, dan kondisi luar.</li>
                    <li>Setiap barang diberi kode, segel, dan foto kondisi awal.</li>
                    <li>Barang hanya bisa dikeluarkan setelah tagihannya lunas.</li>
                </ul>
            </section>
        </div>

        <aside class="pg-aside">
            <section class="rt-card" style="display:grid;gap:14px">
                <p class="rm-price"><small>Mulai dari</small><b>{{ rp($storage->min_price) }}</b><span> /bulan</span></p>
                <div class="rm-cap">
                    <div class="top"><span>Kapasitas terisi</span><b>{{ $storage->capacity_pct }}%</b></div>
                    <div class="bar"><i class="{{ $storage->capacity_pct >= 80 ? 'warn' : '' }}" style="width:{{ $storage->capacity_pct }}%"></i></div>
                </div>
                @if ($full)
                    <button class="btn btn-ghost wide" disabled>Penuh saat ini</button>
                @else
                    <a href="{{ route('ruang-titip.detail-item') }}" class="btn btn-sun wide">Pesan sekarang
                        <svg class="ic" aria-hidden="true"><use href="#i-arrow"/></svg></a>
                @endif
            </section>
        </aside>
    </div>
</div>
@endsection
