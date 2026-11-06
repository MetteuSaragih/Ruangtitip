@extends('layouts.rt')

@php
    if (! function_exists('rupiah')) {
        function rupiah($n) { return 'Rp' . number_format($n, 0, ',', '.'); }
    }

    $firstName = $user && $user->name ? explode(' ', trim($user->name))[0] : 'Penitip';
    // Sapaan mengikuti jam WIB: pagi 04.00-10.59, siang 11.00-14.59, sore 15.00-17.59, selain itu malam.
    $hour = (int) now('Asia/Jakarta')->format('G');
    $greeting = ($hour >= 4 && $hour < 11 ? 'Selamat pagi' : ($hour >= 11 && $hour < 15 ? 'Selamat siang' : ($hour >= 15 && $hour < 18 ? 'Selamat sore' : 'Selamat malam'))) . ', ' . $firstName . '!';

    // Ringkasan titipan aktif
    $daysLeft = 0;
    $totalDays = 1;
    $ringOffset = 345.4;
    if ($activeOrder) {
        $daysLeft = max(0, (int) now()->startOfDay()->diffInDays($activeOrder->date_end->copy()->startOfDay(), false));
        $totalDays = max(1, (int) abs($activeOrder->date_start->copy()->startOfDay()->diffInDays($activeOrder->date_end->copy()->startOfDay())) + 1);
        $ringOffset = round(345.4 * (1 - min(1, $daysLeft / $totalDays)), 1);
    }

    $quick = [
        [route('ruang-titip.index'), 'n-ware', 'Titip barang', 'Pilih ruang, periode, dan barang'],
        [route('pesanan.index'), 'n-receipt', 'Pesanan saya', 'Bayar, perpanjang, kirim, ambil'],
        [route('preloved.index'), 'n-tag', 'Beli preloved', $prelovedCount . ' barang siap dibeli'],
        [route('preloved.cara-jual'), 'n-coin', 'Jual barang', 'Kami beli, dibayar penuh'],
    ];
@endphp

@section('title', $greeting)

@section('content')
<div class="bd">

    {{-- ===== Lengkapi profil ===== --}}
    @if ($profileNext)
        <section class="bd-profile c12" aria-label="Lengkapi profil">
            <div class="bd-profile-main">
                <div class="bd-segs" role="img" aria-label="Profil {{ $profileDone }} dari {{ count($profileSteps) }}">
                    @foreach ($profileSteps as $i => $step)<i class="{{ $i < $profileDone ? 'on' : '' }}"></i>@endforeach
                </div>
                <span class="bd-segs-l">Profil {{ $profileDone }} dari {{ count($profileSteps) }}</span>
            </div>
            <div class="bd-profile-t">
                <b>Lengkapi profilmu dulu, yuk</b>
                <p>Berikutnya: {{ $profileNext[0] }}. Tim Ruru menghubungimu lewat WhatsApp soal pesanan.</p>
            </div>
            <div class="acts">
                <a class="btn btn-brand" href="{{ $profileNext[2] }}">Lengkapi sekarang</a>
            </div>
        </section>
    @endif

    {{-- ===== Hero: satu-satunya CTA kuning di halaman ===== --}}
    <section class="rt-card bd-hero c8" aria-label="Sapaan">
        <div class="bd-hero-copy">
            @if ($activeOrder)
                <h2>Barangmu aman di <span>{{ $activeOrder->storage?->name ?? 'gudang kami' }}.</span></h2>
                <p>Masa titip {{ $activeOrder->code() }} berakhir {{ $activeOrder->date_end->locale('id')->translatedFormat('j F Y') }}. Mau tambah periode, minta dikirim, atau ambil sendiri? Semua bisa dari sini.</p>
                <div class="acts">
                    <a class="btn btn-sun" href="{{ route('pesanan.detail', $activeOrder) }}">Tambah periode</a>
                    <a class="btn btn-line" href="{{ route('pesanan.detail', $activeOrder) }}">Kirim ke alamatku</a>
                    <a class="btn btn-line" href="{{ route('pesanan.detail', $activeOrder) }}">Ambil di gudang</a>
                </div>
            @else
                <h2>Titip barangmu, <span>simpan uangmu.</span></h2>
                <p>Mau pulang kampung, magang, atau pindah kos? Pilih ruang, antar barangnya ke gudang, lalu kamar kos bisa kamu lepas.</p>
                <div class="acts">
                    <a class="btn btn-sun" href="{{ route('ruang-titip.index') }}">Titip barang sekarang</a>
                    @if ($hasOrders)
                        <a class="btn btn-line" href="{{ route('pesanan.index') }}">Lihat pesanan</a>
                    @endif
                </div>
            @endif
        </div>
        <div class="bd-say" aria-hidden="true">{{ $activeOrder ? 'Aku jagain, tenang aja!' : 'Barangmu mau dititip kapan?' }}</div>
        @include('dashboard.partials.ruru')
    </section>

    {{-- ===== Ringkasan titipan ===== --}}
    <section class="rt-card bd-summary c4" aria-label="Ringkasan titipan">
        @if ($activeOrder)
            <div class="rt-card-h"><div><h2>Ringkasan titipan</h2><p class="sub">Pesanan {{ $activeOrder->code() }}, {{ $activeOrder->storage?->name }}</p></div></div>
            <div class="bd-bigring" role="img" aria-label="Sisa {{ $daysLeft }} dari {{ $totalDays }} hari">
                <svg viewBox="0 0 132 132" aria-hidden="true">
                    <circle class="t" cx="66" cy="66" r="55"/>
                    <circle class="p" cx="66" cy="66" r="55" style="stroke-dashoffset:{{ $ringOffset }}"/>
                </svg>
                <div><strong>{{ $daysLeft }}</strong><small>hari lagi</small></div>
            </div>
            <ul class="bd-kv">
                <li><span>Barang disimpan</span><b>{{ $activeOrder->totalItems() }} barang</b></li>
                <li><span>Periode</span><b>{{ $activeOrder->date_start->locale('id')->translatedFormat('j M') }} sampai {{ $activeOrder->date_end->locale('id')->translatedFormat('j M Y') }}</b></li>
                <li><span>Sisa tagihan</span><b>{{ rupiah(0) }}</b></li>
            </ul>
            <a class="btn btn-primary" href="{{ route('pesanan.detail', $activeOrder) }}">Lihat detail pesanan</a>
        @else
            <div class="rt-card-h"><div><h2>Ringkasan titipan</h2><p class="sub">Belum ada barang di gudang</p></div></div>
            <ul class="bd-kv">
                <li><span>Barang disimpan</span><b class="nil">—</b></li>
                <li><span>Periode</span><b class="nil">—</b></li>
                <li><span>Sisa tagihan</span><b class="nil">—</b></li>
            </ul>
            <p class="bd-note">Sisa hari titip dan tagihanmu akan muncul di sini setelah barangmu masuk gudang.</p>
        @endif
    </section>

    {{-- ===== Perlu tindakanmu ===== --}}
    @if ($needs->isNotEmpty())
        <section class="rt-card bd-todo c12" aria-labelledby="todoT">
            <header class="bd-todo-h">
                <span class="bd-todo-n" aria-hidden="true">{{ $needs->count() }}</span>
                <div><h2 id="todoT">Perlu tindakanmu</h2><p class="sub">Selesaikan dari atas, yang paling mendesak duluan.</p></div>
                <a class="more-link" href="{{ route('pesanan.index') }}">Semua pesanan <svg class="ic" width="16" height="16" aria-hidden="true"><use href="#i-arrow"/></svg></a>
            </header>
            <ol class="bd-todo-list">
                @foreach ($needs->take(4) as $n)
                    <li class="t-{{ $n['tone'] }}">
                        <div class="bd-t-main"><span class="bd-t-st">{{ $n['label'] }}</span><b>{{ $n['code'] }}</b></div>
                        <span class="bd-t-what">{{ $n['what'] }}</span>
                        <a class="btn {{ $n['pay'] ? 'btn-primary' : 'btn-ghost' }} sm" href="{{ $n['href'] }}">{{ $n['cta'] }}</a>
                    </li>
                @endforeach
            </ol>
            @if ($needs->count() > 4)
                <p class="bd-todo-more">dan {{ $needs->count() - 4 }} lainnya di <a href="{{ route('pesanan.index') }}">Pesanan Saya</a> dan <a href="{{ route('preloved.index') }}">Toko Preloved</a>.</p>
            @endif
        </section>
    @endif

    {{-- ===== Aksi cepat ===== --}}
    <nav class="bd-actions c12" aria-label="Aksi cepat">
        @foreach ($quick as [$href, $icon, $title, $desc])
            <a class="bd-act" href="{{ $href }}">
                <span class="ico" aria-hidden="true"><svg><use href="#{{ $icon }}"/></svg></span>
                <span class="tx"><b>{{ $title }}</b><small>{{ $desc }}</small></span>
                <svg class="chev" aria-hidden="true"><use href="#i-chev"/></svg>
            </a>
        @endforeach
    </nav>

    {{-- ===== Barang di gudang ===== --}}
    <section class="rt-card c8" aria-labelledby="gTitle">
        <div class="rt-card-h">
            <div>
                <h2 id="gTitle">Barang di gudang</h2>
                <p class="sub">{{ $warehouseOrders->isNotEmpty() ? $warehouseOrders->sum(fn ($o) => $o->totalItems()) . ' barang tersimpan, status dan lokasinya.' : 'Status, lokasi, dan kondisi tiap barang.' }}</p>
            </div>
        </div>
        <div class="bd-inv" role="table" aria-label="Barang di gudang">
            <div class="bd-inv-h" role="row"><span role="columnheader">Kode</span><span role="columnheader">Lokasi</span><span role="columnheader">Sampai</span><span role="columnheader">Status</span><span></span></div>
            @forelse ($warehouseOrders as $wo)
                <a class="bd-inv-r" role="row" href="{{ route('pesanan.detail', $wo) }}">
                    <span role="cell" class="bd-inv-code"><svg viewBox="0 0 44 40" aria-hidden="true"><use href="#box"/></svg><span><b>{{ $wo->code() }}</b><small>{{ $wo->totalItems() }} barang</small></span></span>
                    <span role="cell"><b>{{ $wo->storage?->name }}</b><small>{{ $wo->storage?->address }}</small></span>
                    <span role="cell">{{ $wo->date_end->locale('id')->translatedFormat('j M Y') }}</span>
                    <span role="cell" class="bd-inv-st">Disimpan</span>
                    <span class="bd-inv-go" aria-hidden="true"><svg class="ic"><use href="#i-arrow"/></svg></span>
                </a>
            @empty
                <div class="bd-inv-empty">
                    <span class="ico" aria-hidden="true"><svg><use href="#n-ware"/></svg></span>
                    <h3>Belum ada barang di gudang</h3>
                    <p>Setelah barangmu diterima petugas, setiap barang muncul di sini lengkap dengan kode, lokasi, dan status penyimpanannya.</p>
                    <a class="btn btn-primary" href="{{ route('ruang-titip.index') }}">Buat pesanan</a>
                </div>
            @endforelse
        </div>
        @if ($warehouseOrders->isNotEmpty())
            <div class="bd-list-foot">
                <span>Isi kemasan tidak dibuka. Yang tercatat hanya kondisi luar.</span>
                <a class="more-link" href="{{ route('pesanan.index') }}">Semua pesanan <svg class="ic" width="16" height="16" aria-hidden="true"><use href="#i-arrow"/></svg></a>
            </div>
        @endif
    </section>

    {{-- ===== Aktivitas terbaru ===== --}}
    <section class="rt-card c4" aria-labelledby="aTitle">
        <div class="rt-card-h"><div><h2 id="aTitle">Aktivitas terbaru</h2></div><a class="more-link" href="{{ route('notifikasi.index') }}">Semua</a></div>
        @if ($notifications->isNotEmpty())
            <ul class="bd-feed">
                @foreach ($notifications as $n)
                    <li>
                        <span class="dot {{ \App\Support\UserNotifications::isUnread($n) ? 'd-sun' : 'd-mute' }}" aria-hidden="true"><svg class="ic"><use href="#n-bell"/></svg></span>
                        <div>
                            <a href="{{ $n['link'] }}"><b>{{ $n['text'] }}</b></a>
                            @if ($n['at'])<span>{{ $n['at']->locale('id')->diffForHumans() }}</span>@endif
                        </div>
                    </li>
                @endforeach
            </ul>
        @else
            <div class="bd-tl-empty"><i aria-hidden="true"></i><p>Belum ada aktivitas. Kabar soal pembayaran, pemeriksaan barang, dan jadwal akan muncul di sini.</p></div>
        @endif
    </section>

    {{-- ===== Toko Preloved ===== --}}
    <section class="rt-card bd-pre c8" aria-labelledby="pTitle">
        <span class="tag">Toko Preloved</span>
        <h3 id="pTitle">Barang nganggur? Ubah jadi uang.</h3>
        <p>Ajukan barangmu, cek kondisinya bareng petugas, dan terima pembayaran penuh saat serah terima.</p>
        <div class="acts">
            <a class="btn btn-primary" href="{{ route('preloved.cara-jual') }}">Ajukan barang</a>
            <a class="btn btn-plain" href="{{ route('preloved.index') }}">Lihat katalog</a>
        </div>
        @if ($preloved->isNotEmpty())
            <ul class="bd-mini-shop">
                @foreach ($preloved as $p)
                    <li>
                        <a href="{{ route('preloved.show', $p->id) }}">
                            <span class="ms-pic">
                                @if ($p->primary_photo)
                                    <img src="{{ asset('storage/' . $p->primary_photo) }}" alt="">
                                @else
                                    <svg aria-hidden="true"><use href="#n-tag"/></svg>
                                @endif
                            </span>
                            <span class="ms-t"><b>{{ $p->name }}</b><small>{{ rupiah($p->price) }}</small></span>
                        </a>
                    </li>
                @endforeach
            </ul>
        @else
            <div class="bd-pre-foot"><i></i><span>Katalog masih kosong. Barang pertama akan tampil di sini.</span></div>
        @endif
    </section>

    {{-- ===== Toko Packing (ditunda) ===== --}}
    <section class="rt-card bd-pack c4" aria-labelledby="kTitle">
        <div>
            <span class="soon">Segera hadir</span>
            <h3 id="kTitle">Toko Packing</h3>
            <p>Kardus, lakban, dan bubble wrap untuk titipanmu, dipesan sekalian dengan titipan.</p>
        </div>
        <ul class="bd-goods" aria-label="Produk yang akan tersedia"><li>Kardus S, M, L</li><li>Lakban</li><li>Bubble wrap</li><li>Plastik wrap</li></ul>
        <button class="btn btn-ghost" type="button" id="notifyPack" aria-pressed="false">Kabari saya saat buka</button>
    </section>
</div>
@endsection

@push('scripts')
<script>
(function () {
    var btn = document.getElementById('notifyPack');
    if (!btn) return;
    function draw(on) {
        btn.setAttribute('aria-pressed', String(on));
        btn.textContent = on ? 'Kamu akan dikabari' : 'Kabari saya saat buka';
    }
    try { draw(localStorage.getItem('rt-notify') === '1'); } catch (e) {}
    btn.addEventListener('click', function () {
        var on = btn.getAttribute('aria-pressed') !== 'true';
        try { localStorage.setItem('rt-notify', on ? '1' : '0'); } catch (e) {}
        draw(on);
    });
})();
</script>
@endpush
