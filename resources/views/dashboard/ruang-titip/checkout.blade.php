@extends('layouts.rt')
@section('title', 'Ruang Titip')

@php
    if (! function_exists('rp')) {
        function rp($n) { return 'Rp' . number_format($n, 0, ',', '.'); }
    }
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
            <p class="ps">Pilih kapan kamu mengantar barang ke gudang, lalu periksa data sebelum pesanan dibuat.</p>
        </div>
    </div>

    @include('dashboard.ruang-titip._progress', ['step' => 2])

    <div class="two">
        <form method="POST" action="{{ route('ruang-titip.place') }}" class="stack" id="placeForm">
            @csrf
            @if ($errors->any())
                <div class="alert a-err" role="alert"><svg class="ic" aria-hidden="true"><use href="#i-warn"/></svg><div><b>{{ $errors->first() }}</b></div></div>
            @endif

            <section class="card">
                <div class="card-h"><h3>Gudang dan periode</h3><a class="more-link" href="{{ route('ruang-titip.detail-item') }}">Ubah</a></div>
                <div class="rows">
                    <div><span>Gudang</span><b>{{ $storage->name ?? '-' }}<small>{{ $storage->location ?? '' }}</small></b></div>
                    <div><span>Periode</span><b>{{ $dStart->translatedFormat('l, j F Y') }} sampai {{ $dEnd->translatedFormat('l, j F Y') }}<small>{{ $calc['days'] }} hari</small></b></div>
                    <div><span>Pengantaran</span><b>Antar sendiri ke gudang</b></div>
                </div>
            </section>

            <section class="card">
                <div class="card-h"><h3>Barang dan rincian biaya</h3><a class="more-link" href="{{ route('ruang-titip.detail-item') }}">Ubah</a></div>
                <div class="tbl-wrap">
                    <table class="tbl">
                        <thead><tr><th>Rincian</th><th>Subtotal</th></tr></thead>
                        <tbody>
                            @foreach ($calc['quote'] as $row)
                                <tr>
                                    <td>
                                        <b>{{ $row['qty'] }} &times; {{ $row['label'] }}</b>
                                        <small>
                                            @foreach ($row['lines'] as $ln){{ $ln['qty'] }} &times; paket {{ $ln['label'] }} ({{ rp($ln['price']) }})@if (! $loop->last), @endif @endforeach
                                            per barang, {{ $calc['days'] }} hari
                                        </small>
                                    </td>
                                    <td><b>{{ rp($row['subtotal']) }}</b></td>
                                </tr>
                            @endforeach
                            <tr><td><b>Biaya layanan platform</b></td><td><b>{{ rp($calc['platform_fee']) }}</b></td></tr>
                            <tr class="tot"><td>Total bayar</td><td>{{ rp($calc['total']) }}</td></tr>
                        </tbody>
                    </table>
                </div>
                <p class="help mt">Harga memakai gabungan paket termurah untuk lama titipmu.</p>
            </section>

            <section class="card">
                <h3>Jadwal antar barang</h3>
                <p class="help" style="margin-bottom:12px">Pilih kapan kamu datang mengantar barang ke {{ $storage->name ?? 'gudang' }}. Jam yang bisa dipilih mengikuti jam operasional gudang.</p>
                <x-dropoff-picker :drop="$drop" :default-date="$dStart->toDateString()" :old-date="old('pickup_date')" :old-time="old('pickup_time')" />
                <p class="help mt">Masa titip dihitung dari tanggal mulai di pesananmu, walau barang datang terlambat.</p>
            </section>

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
                <a href="{{ route('ruang-titip.detail-item') }}" class="btn btn-ghost">Kembali</a>
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
