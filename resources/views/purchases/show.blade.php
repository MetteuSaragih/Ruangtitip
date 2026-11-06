@extends('layouts.rt')

@section('title', 'Pembelian Saya')

@php
    if (! function_exists('rp')) {
        function rp($n) { return 'Rp' . number_format($n, 0, ',', '.'); }
    }
    $steps = $o['kirim'] ? ['Dipesan', 'Dibayar', 'Dikirim', 'Diterima', 'Selesai'] : ['Dipesan', 'Dibayar', 'Diserahkan', 'Masa komplain', 'Selesai'];
    $st = ['bayar' => 1, 'serah' => 2, 'kirim' => 2, 'selesai' => 5][$o['key']] ?? 1;
    $wa = 'https://wa.me/6285121091134?text=' . urlencode('Halo RuangTitip, saya mau tanya pesanan ' . $o['number']);
    $dibuat = $o['created']->locale('id')->translatedFormat('j M Y, H.i');
@endphp

@section('content')
<div class="mp">
<div class="stack">
    @include('partials.mp-head', [
        'crumbs' => [['Pembelian Saya', route('purchases.index')], [$o['number'], null]],
        'title' => 'Pesanan ' . $o['number'],
        'sub' => 'Dibuat ' . $dibuat . '.',
        'acts' => '<span class="badge b-' . $o['status_tone'] . '"><i></i>' . e($o['status_label']) . '</span>',
    ])

    @if ($o['key'] === 'bayar')
        @if ($o['pay_state'] === 'review')
            <div class="alert a-info"><svg class="ic" aria-hidden="true"><use href="#i-clock"/></svg><div><b>Pembayaran sedang diperiksa</b><p>Bukti pembayaran {{ rp($o['total']) }} sudah kami terima. Admin sedang memeriksanya. Barang tetap ditahan untukmu.</p><div class="acts"><a class="btn btn-ghost sm" href="{{ $o['pay_url'] }}">Lihat status pembayaran</a></div></div></div>
        @elseif ($o['pay_state'] === 'rejected')
            <div class="alert a-err"><svg class="ic" aria-hidden="true"><use href="#i-x"/></svg><div><b>Pembayaran gagal</b><p>{{ $o['pay_reason'] ?: 'Admin belum bisa memastikan pembayaranmu.' }} Foto ulang bukti pembayaranmu dan unggah lagi.</p><div class="acts"><a class="btn btn-primary sm" href="{{ $o['pay_url'] }}">Unggah ulang bukti</a></div></div></div>
        @else
            <div class="alert a-warn"><svg class="ic" aria-hidden="true"><use href="#i-clock"/></svg><div><b>Bayar {{ rp($o['total']) }} untuk melanjutkan</b><p>Bayar lewat QRIS lalu unggah bukti pembayaran. Kalau lewat batas waktu, pesanan batal dan barang tersedia lagi untuk pembeli lain.</p><div class="acts"><a class="btn btn-primary sm" href="{{ $o['pay_url'] }}">Bayar sekarang</a></div></div></div>
        @endif
    @elseif ($o['key'] === 'serah')
        @if ($o['kirim'])
            <div class="alert a-info"><svg class="ic" aria-hidden="true"><use href="#i-truck"/></svg><div><b>Barang sedang disiapkan</b><p>Petugas mengemas barang, memotret kondisi barang dan paket, lalu menyerahkannya ke kurir.</p></div></div>
        @else
            <div class="alert a-info"><svg class="ic" aria-hidden="true"><use href="#i-clock"/></svg><div><b>Ambil barang di gudang</b><p>Datang ke Gudang Ruru, Lowokwaru, Malang dan tunjukkan nomor pesanan {{ $o['number'] }}. Petugas memverifikasi identitasmu. Periksa barang dan kelengkapannya saat serah terima.</p></div></div>
        @endif
    @elseif ($o['key'] === 'kirim')
        <div class="alert a-info"><svg class="ic" aria-hidden="true"><use href="#i-truck"/></svg><div><b>Dalam pengiriman{{ $o['courier'] ? ' lewat ' . $o['courier'] : '' }}</b><p>@if ($o['tracking'])Nomor resi <b>{{ $o['tracking'] }}</b>. @endif Kalau ada kendala pengiriman, kami tindak lanjuti sebelum transaksi dianggap diterima.</p></div></div>
    @elseif ($o['key'] === 'selesai')
        <div class="alert a-ok"><svg class="ic" aria-hidden="true"><use href="#i-check"/></svg><div><b>Transaksi selesai</b><p>Barang sudah diterima. Kalau ada yang tidak sesuai katalog, hubungi admin.</p><div class="acts"><a class="btn btn-ghost sm" href="{{ $wa }}" target="_blank" rel="noopener">Hubungi admin</a></div></div></div>
    @else
        <div class="alert a-mute"><svg class="ic" aria-hidden="true"><use href="#i-clock"/></svg><div><b>Pesanan kedaluwarsa</b><p>Pembayaran tidak selesai dalam batas waktu. Barang dilepas kembali ke katalog.</p></div></div>
    @endif

    @if ($o['key'] !== 'exp')
        <section class="card">
            <ol class="track" style="--n:{{ count($steps) }}">
                @foreach ($steps as $i => $t)
                    <li class="{{ $i < $st ? 'done' : ($i === $st ? 'cur' : '') }}" @if ($i === $st) aria-current="step" @endif><span class="d">@if ($i < $st)<svg class="ic" aria-hidden="true"><use href="#i-check"/></svg>@endif</span>{{ $t }}</li>
                @endforeach
            </ol>
        </section>
    @endif

    <div class="two">
        <div class="stack">
            <section class="card">
                <h3>Barang</h3>
                @foreach ($o['items'] as $item)
                    <div class="oc" style="grid-template-columns:64px 1fr;margin-bottom:12px">
                        <div class="pic" style="--tone:var(--alt)">
                            @if (! empty($item['image']))
                                <img src="{{ asset('storage/'.$item['image']) }}" alt="" style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover;border-radius:14px">
                            @else
                                <svg viewBox="0 0 24 24" aria-hidden="true"><use href="#n-tag"/></svg>
                            @endif
                        </div>
                        <div>
                            <b>{{ $item['name'] }}</b>
                            <p class="meta">Jumlah {{ $item['qty'] }}@if (! empty($item['condition_label'])), kondisi {{ $item['condition_label'] }}@endif.</p>
                            @if (! empty($item['id']))<a class="more-link" href="{{ route('preloved.show', $item['id']) }}">Lihat deskripsi katalog</a>@endif
                        </div>
                    </div>
                @endforeach
            </section>
        </div>
        <aside class="aside">
            <section class="card">
                <h3>Rincian</h3>
                <div class="rows">
                    <div><span>Harga barang</span><b>{{ rp($order->subtotal) }}</b></div>
                    <div><span>Biaya layanan</span><b>{{ rp($order->service_fee) }}</b></div>
                    <div><span>Ongkir</span><b>{{ rp($order->shipping_cost) }}</b></div>
                    <div class="tot"><span>Total</span><b>{{ rp($o['total']) }}</b></div>
                </div>
            </section>
            <section class="card">
                <h3>Penerimaan</h3>
                <div class="rows">
                    <div><span>Cara</span><b>{{ $o['method'] }}</b></div>
                    @if ($o['kirim'])
                        <div><span>Tujuan</span><b>{{ $o['address'] ?? '-' }}</b></div>
                        @if ($o['courier'])<div><span>Layanan</span><b>{{ $o['courier'] }}</b></div>@endif
                    @else
                        <div><span>Lokasi</span><b>Gudang Ruru, Lowokwaru, Malang</b></div>
                    @endif
                </div>
            </section>
        </aside>
    </div>
</div>
</div>
@endsection
