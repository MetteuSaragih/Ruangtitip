@extends('layouts.rt')
@section('title', 'Ruang Titip')

@php
    if (! function_exists('rp')) {
        function rp($n) { return 'Rp' . number_format($n, 0, ',', '.'); }
    }
    $back = $s['logistic'] === 'instant' ? route('ruang-titip.kurir')
          : ($s['logistic'] === 'rutip' ? route('ruang-titip.alamat') : route('ruang-titip.logistik'));
    $logLabel = match ($s['logistic']) {
        'self' => 'Antar sendiri',
        'rutip' => 'Packing + Anjem RuTip',
        'instant' => $courier ? trim($courier->name . ' ' . ($courier->service ?? '')) : 'Kurir instan',
        default => '-',
    };
    $dStart = \Illuminate\Support\Carbon::parse($s['date_start']);
    $dEnd = \Illuminate\Support\Carbon::parse($s['date_end']);
@endphp

@section('content')
@include('dashboard.ruang-titip._icons')
<div class="ttp">
<div class="stack">
    <nav class="crumbs" aria-label="Breadcrumb"><a href="{{ route('ruang-titip.index') }}">Ruang Titip</a><span aria-hidden="true">/</span>@if ($storage)<a href="{{ route('ruang-titip.detail', $storage) }}">{{ $storage->name }}</a>@else<span>Pesan</span>@endif<span aria-hidden="true">/</span><span aria-current="page">Pesan</span></nav>
    <div class="ph">
        <div>
            <h2 class="pt" tabindex="-1">Ringkasan pesanan</h2>
            <p class="ps">Periksa semua data sebelum pesanan dibuat.</p>
        </div>
    </div>

    @include('dashboard.ruang-titip._progress', ['step' => 4])

    <div class="two">
        <form method="POST" action="{{ route('ruang-titip.place') }}" class="stack">
            @csrf
            @if ($errors->any())
                <div class="alert a-err"><svg class="ic" aria-hidden="true"><use href="#i-warn"/></svg><div><b>{{ $errors->first() }}</b></div></div>
            @endif

            <section class="card">
                <div class="card-h"><h3>Gudang, periode, dan pengiriman</h3><a class="more-link" href="{{ route('ruang-titip.detail-item') }}">Ubah</a></div>
                <div class="rows">
                    <div><span>Gudang</span><b>{{ $storage->name ?? '-' }}<small>{{ $storage->location ?? '' }}</small></b></div>
                    <div><span>Periode</span><b>{{ $dStart->translatedFormat('l, j F Y') }} sampai {{ $dEnd->translatedFormat('l, j F Y') }}<small>{{ $calc['days'] }} hari</small></b></div>
                    <div><span>Pengiriman</span><b>{{ $logLabel }}</b></div>
                </div>
            </section>

            <section class="card">
                <div class="card-h"><h3>Barang dan rincian biaya</h3><a class="more-link" href="{{ route('ruang-titip.detail-item') }}">Ubah</a></div>
                <div class="tbl-wrap">
                    <table class="tbl">
                        <thead><tr><th>Rincian</th><th>Subtotal</th></tr></thead>
                        <tbody>
                            @foreach (($s['items'] ?? []) as $code => $qty)
                                @if ((int) $qty > 0)
                                    @php $unitPrice = \App\Support\StorageSizes::price((string) $code); @endphp
                                    <tr><td><b>{{ (int) $qty }} &times; {{ \App\Support\StorageSizes::label((string) $code) }}</b><small>{{ (int) $qty }} &times; {{ rp($unitPrice) }} &times; {{ $calc['days'] }} hari</small></td><td><b>{{ rp((int) $qty * $unitPrice * $calc['days']) }}</b></td></tr>
                                @endif
                            @endforeach
                            @if ($s['logistic'] === 'rutip')
                                <tr><td><b>Antar-jemput RuTip</b><small>{{ $calc['km'] }} km dari alamatmu ke gudang</small></td><td><b>{{ rp($calc['kmCost']) }}</b></td></tr>
                                <tr><td><b>Jemput per barang</b><small>{{ $calc['totalItems'] }} &times; {{ rp($calc['totalItems'] > 0 ? $calc['pickupBoxCost'] / $calc['totalItems'] : 0) }}</small></td><td><b>{{ rp($calc['pickupBoxCost']) }}</b></td></tr>
                                <tr><td><b>Jasa packing</b><small>{{ $calc['totalItems'] }} &times; {{ rp($calc['totalItems'] > 0 ? $calc['packingCost'] / $calc['totalItems'] : 0) }}</small></td><td><b>{{ rp($calc['packingCost']) }}</b></td></tr>
                            @elseif ($s['logistic'] === 'instant' && $courier)
                                <tr><td><b>Pengiriman</b><small>{{ $courier->name }} {{ $courier->service }}</small></td><td><b>{{ rp($calc['courierCost']) }}</b></td></tr>
                            @endif
                            <tr><td><b>Biaya layanan platform</b></td><td><b>{{ rp($calc['platform_fee']) }}</b></td></tr>
                            <tr class="tot"><td>Total bayar</td><td>{{ rp($calc['total']) }}</td></tr>
                        </tbody>
                    </table>
                </div>
            </section>

            @if ($s['logistic'] !== 'self')
                <section class="card">
                    <h3>Waktu penjemputan</h3>
                    <x-pickup-slot-picker
                        :old-date="old('pickup_date')"
                        :old-start="old('pickup_time')"
                        :old-end="old('pickup_time_end')" />
                </section>
            @endif

            <section class="card">
                <h3>Persetujuan</h3>
                <label class="pick">
                    <input type="checkbox" name="agree" value="1" required>
                    <span>Saya menyetujui <a href="{{ route('legal.terms') }}" target="_blank" rel="noopener">Syarat dan Ketentuan RuangTitip</a>, termasuk ketentuan blind box: isi kemasan tidak dibuka, petugas hanya memeriksa jumlah, kategori, ukuran, kemasan, dan kondisi luar, dan saya bertanggung jawab atas isi barang serta memastikan tidak ada barang yang dilarang. Saya juga menyetujui <a href="{{ route('legal.privacy') }}" target="_blank" rel="noopener">Kebijakan Privasi</a>.</span>
                </label>
            </section>

            <section class="card">
                <h3>Metode pembayaran</h3>
                @include('partials.payment-method-qris')
            </section>

            <div class="nav-btns">
                <a href="{{ $back }}" class="btn btn-ghost">Kembali</a>
                <button type="submit" id="placeBtn" class="btn btn-sun">Buat pesanan dan bayar {{ rp($calc['total']) }}</button>
            </div>
        </form>

        <aside class="aside">
            <section class="card">
                <h3>Pembayaran</h3>
                <div class="rows">
                    <div class="tot"><span>Total bayar</span><b>{{ rp($calc['total']) }}</b></div>
                </div>
                <p class="help mt">Setelah pesanan dibuat, kamu membayar lewat QRIS dan mengunggah bukti pembayaran. Admin memeriksa buktinya sebelum pesanan dilanjutkan.</p>
            </section>
        </aside>
    </div>
</div>
</div>
@endsection
