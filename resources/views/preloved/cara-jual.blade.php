@extends('layouts.rt')

@section('title', 'Cara Menjual Barang')

@php
    $warehouseAddress = config('biteship.warehouse.address', 'Malang, Jawa Timur');
    $mapsUrl = 'https://maps.google.com/?q=' . rawurlencode($warehouseAddress);
    $steps = [
        ['n-ware', 'Datang ke gudang', 'Bawa barang bekas kos yang ingin kamu jual ke gudang RuangTitip selama jam operasional.', 'Cepat dan praktis'],
        ['n-receipt', 'Inspeksi dan sepakati harga', 'Petugas mengecek kondisi, kelengkapan, dan fungsi dasar barang bersamamu, lalu menawarkan harga beli.', 'Harga transparan'],
        ['n-tag', 'Serah terima dan tayang', 'Setelah setuju, barang diserahkan dan dibayar penuh. Kami foto lalu tayangkan di katalog preloved.', 'Langsung tayang'],
    ];
    $benefits = [
        ['Gratis listing', 'Tidak ada biaya untuk mendaftarkan barang.'],
        ['Dibayar penuh', 'Pembayaran harga beli diberikan saat serah terima.'],
        ['Transaksi aman', 'Seluruh proses dikelola langsung oleh RuangTitip.'],
    ];
@endphp

@push('styles')
<style>
.cj-hero { background: var(--teal); color: #fff; border: 0; text-align: center; padding: clamp(28px, 5vw, 56px) 24px; }
.cj-hero h2 { font-size: clamp(26px, 3.4vw, 40px); letter-spacing: -0.03em; }
.cj-hero h2 span { color: var(--sun); display: block; }
.cj-hero p { color: rgba(255,255,255,.86); max-width: 46ch; margin: 12px auto 0; }
.cj-steps { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 16px; margin-top: 20px; }
.cj-step .ico { width: 48px; height: 48px; border-radius: 14px; background: var(--alt); display: grid; place-items: center; color: var(--bright); --acc: var(--sun); margin-bottom: 14px; }
.cj-step .ico svg { width: 26px; height: 26px; }
.cj-step small { font-weight: 800; letter-spacing: .08em; color: var(--tape-dark); font-size: 12px; }
.cj-step h3 { font-size: 19px; margin: 6px 0; }
.cj-step p { color: var(--muted); font-size: 15px; }
.cj-benefits { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 12px; margin-top: 16px; }
.cj-benefits .rt-card { padding: 18px; }
.cj-benefits b { display: block; margin-bottom: 2px; }
.cj-benefits p { color: var(--muted); font-size: 14px; }
@media (max-width: 760px) { .cj-steps, .cj-benefits { grid-template-columns: 1fr; } }
</style>
@endpush

@section('content')
<div class="wrap">
    <a class="back-link" href="{{ route('preloved.index') }}">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M19 12H5M11 18l-6-6 6-6"/></svg>
        Kembali ke Toko Preloved
    </a>

    <section class="rt-card cj-hero" style="margin-top:16px">
        <h2>Cara menjual barang preloved<span>di RuangTitip</span></h2>
        <p>Hanya 3 langkah. Gratis listing, dan kamu dibayar penuh saat serah terima.</p>
    </section>

    <div class="cj-steps">
        @foreach ($steps as $i => [$icon, $title, $desc, $pill])
            <article class="rt-card cj-step">
                <span class="ico"><svg aria-hidden="true"><use href="#{{ $icon }}"/></svg></span>
                <small>LANGKAH {{ $i + 1 }}</small>
                <h3>{{ $title }}</h3>
                <p>{{ $desc }}</p>
                <span class="badge b-ok" style="margin-top:14px"><i></i>{{ $pill }}</span>
            </article>
        @endforeach
    </div>

    <div class="cj-benefits">
        @foreach ($benefits as [$title, $desc])
            <div class="rt-card"><b>{{ $title }}</b><p>{{ $desc }}</p></div>
        @endforeach
    </div>

    <div class="alert a-info" style="margin-top:16px">
        <svg class="ic" aria-hidden="true"><use href="#i-clock"/></svg>
        <div>
            <b>Jam operasional gudang</b>
            <p>Senin sampai Jumat 08.00 sampai 17.00 WIB, Sabtu 09.00 sampai 14.00 WIB. Minggu dan hari libur tutup.</p>
        </div>
    </div>

    <div class="actions" style="flex-wrap:wrap">
        <a class="btn btn-sun" href="{{ $mapsUrl }}" target="_blank" rel="noopener noreferrer">Lihat lokasi gudang</a>
        <a class="btn btn-outline" href="{{ route('preloved.index') }}">Lihat katalog preloved</a>
    </div>
</div>
@endsection
