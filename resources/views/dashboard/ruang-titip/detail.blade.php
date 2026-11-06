@extends('layouts.rt')

@section('title', 'Ruang Titip')

@php
    if (! function_exists('rp')) {
        function rp($n) { return 'Rp' . number_format($n, 0, ',', '.'); }
    }
    $images = ! empty($storage->photos) ? $storage->photos : ($storage->primary_photo ? [$storage->primary_photo] : []);
    $pct = $storage->capacity_pct;
    $full = $pct >= 100;
    $status = $full ? 'err' : ($pct >= 85 ? 'warn' : 'ok');
    $statusLabel = $full ? 'Penuh di periode ini' : ($pct >= 85 ? 'Hampir penuh' : 'Tersedia');
    $rem = max(0, (int) $storage->capacity_total - (int) $storage->capacity_used);
    $sizeRows = $storage->size_rows;
    $rules = array_values(array_filter((array) ($storage->rules ?? []), fn ($x) => is_string($x) && trim($x) !== ''));
    $aboutText = trim((string) $storage->long_description) !== '' ? $storage->long_description : $storage->description;
    $aturan = [
        'Barang diantar sendiri ke gudang dalam keadaan sudah dikemas (kardus, koper, atau plastik wrap).',
        'Isi kemasan tidak dibuka (blind box). Petugas hanya memeriksa jumlah, kategori, ukuran, kemasan, dan kondisi luar.',
        'Setiap barang diberi kode, segel, dan foto kondisi awal.',
        'Masa titip dihitung dari tanggal mulai di pesanan, walau barang datang terlambat.',
        'Barang hanya bisa dikeluarkan setelah tagihannya lunas.',
    ];
    $larangan = [
        'Makanan atau bahan yang mudah basi',
        'Barang mudah terbakar atau meledak (tabung gas, bensin)',
        'Hewan atau tanaman hidup',
        'Uang tunai, perhiasan, dan surat berharga',
        'Narkotika, senjata, dan barang ilegal',
        'Barang bocor, berbau, atau kemasannya rusak',
    ];
@endphp

@section('content')
@include('dashboard.ruang-titip._icons')
<div class="ttp">
<div class="stack">
    <nav class="crumbs" aria-label="Breadcrumb"><a href="{{ route('ruang-titip.index') }}">Ruang Titip</a><span aria-hidden="true">/</span><span aria-current="page">{{ $storage->name }}</span></nav>
    <div class="ph">
        <div>
            <h2 class="pt" tabindex="-1">{{ $storage->name }}</h2>
            <p class="ps">@if ($storage->title){{ $storage->title }} &middot; @endif{{ $storage->location ?: $storage->address }}</p>
        </div>
        <div class="ph-acts"><span class="badge b-{{ $status }}"><i></i>{{ $statusLabel }}</span></div>
    </div>

    <div class="two">
        <div class="stack">
            <figure class="gal" style="--tone:var(--color-aqua)">
                @if (! empty($images))
                    <img src="{{ asset('storage/' . $images[0]) }}" alt="Foto {{ $storage->name }}" style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover">
                @else
                    <svg viewBox="0 0 260 150" role="img" aria-label="Ilustrasi {{ $storage->name }}"><use href="#sc"/></svg>
                @endif
            </figure>

            <section class="card">
                <h3>Tentang ruang ini</h3>
                @if ($storage->title)<h4 class="sub">{{ $storage->title }}</h4>@endif
                <p class="desc">{{ $aboutText ?: 'Gudang penitipan barang dengan pengawasan petugas dan pencatatan kondisi di setiap barang.' }}</p>
                @if (count($storage->facilities ?? []))
                    <h4 class="sub">Fasilitas</h4>
                    <ul class="bul">
                        @foreach ($storage->facilities as $tag)<li>{{ $tag }}</li>@endforeach
                    </ul>
                @endif
            </section>

            <section class="card">
                <h3>Ukuran barang</h3>
                <div class="tbl-wrap">
                    <table class="tbl">
                        <thead><tr><th>Ukuran</th><th>Sisi terpanjang</th><th>P + L + T</th><th>Berat</th></tr></thead>
                        <tbody>
                            @foreach ($sizeRows as $row)
                                <tr>
                                    <td><b>{{ $row['code'] }}</b><small>{{ $row['unit'] }} setara ukuran S</small></td>
                                    <td>maks. {{ $row['side'] }} cm</td>
                                    <td>maks. {{ $row['sum'] }} cm</td>
                                    <td>maks. {{ $row['kg'] }} kg</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <p class="help mt">P + L + T = panjang + lebar + tinggi barang yang sudah dikemas.</p>
            </section>

            <section class="card">
                <h3>Paket harga per barang</h3>
                <div class="tbl-wrap">
                    <table class="tbl">
                        <thead>
                            <tr>
                                <th>Ukuran</th>
                                @foreach (($sizeRows[0]['tiers'] ?? []) as $t)<th>{{ $t['label'] }}</th>@endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($sizeRows as $row)
                                <tr>
                                    <td><b>{{ $row['code'] }}</b></td>
                                    @foreach ($row['tiers'] as $t)<td>{{ rp($t['price']) }}</td>@endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <p class="help mt">Harga per barang. Paket 7 hari bayar 6 hari. Paket 3, 6, dan 12 bulan sudah hemat dan dihitung total untuk seluruh periode. Untuk lama titip bebas, sistem memilih gabungan paket yang paling murah. Harga sama di semua ruang.</p>
            </section>

            @if (count($rules))
                <section class="card">
                    <h3>Aturan khusus ruang ini</h3>
                    <ul class="bul">
                        @foreach ($rules as $x)<li>{{ $x }}</li>@endforeach
                    </ul>
                </section>
            @endif

            <section class="card">
                <h3>Aturan penitipan</h3>
                <ul class="bul">
                    @foreach ($aturan as $x)<li>{{ $x }}</li>@endforeach
                </ul>
                <h4 class="sub">Barang yang tidak diterima</h4>
                <ul class="bul">
                    @foreach ($larangan as $x)<li>{{ $x }}</li>@endforeach
                </ul>
            </section>

            <section class="card">
                <h3>Lokasi dan jam operasional</h3>
                <div class="rows">
                    <div><span>Alamat</span><b>{{ $storage->address }}</b></div>
                    <div><span>Senin sampai Jumat</span><b>{{ $storage->hours['weekday'] }}</b></div>
                    <div><span>Sabtu</span><b>{{ $storage->hours['saturday'] }}</b></div>
                    <div><span>Minggu</span><b>{{ $storage->hours['sunday'] }}</b></div>
                </div>
            </section>
        </div>

        <aside class="aside">
            <section class="card stack" style="gap:14px">
                <p class="price"><small>Mulai</small><b>{{ rp($storage->min_price) }}</b><small>per barang per hari</small></p>
                <div class="cap">
                    <div class="cap-top"><span>Sisa kapasitas</span><b>{{ $rem > 0 ? '±' . $rem . ' setara ukuran S' : 'Habis' }}</b></div>
                    <div class="bar is-{{ $status }}" aria-hidden="true"><i style="width:{{ min(100, $pct) }}%"></i></div>
                    <small>Terisi {{ $pct }}%</small>
                </div>
                <p class="help">Periode dan jadwal bisa diubah di langkah berikutnya.</p>
                @if ($full)
                    <button class="btn btn-ghost wide" disabled>Penuh di periode ini</button>
                    <p class="help center">Ubah periode di halaman Ruang Titip atau pilih ruang lain.</p>
                @else
                    <a href="{{ route('ruang-titip.detail-item') }}" class="btn btn-sun wide">Pesan Ruang</a>
                @endif
            </section>
        </aside>
    </div>

    <div class="mcta">
        <p class="price"><small>Mulai</small><b>{{ rp($storage->min_price) }}</b></p>
        @if ($full)
            <button class="btn btn-ghost" disabled>Penuh</button>
        @else
            <a class="btn btn-sun" href="{{ route('ruang-titip.detail-item') }}">Pesan Ruang</a>
        @endif
    </div>
</div>
</div>
@endsection
