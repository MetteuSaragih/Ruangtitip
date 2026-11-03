@extends('layouts.rt')

@section('title', 'Beranda')

@php
    if (! function_exists('rupiah')) {
        function rupiah($n) { return 'Rp' . number_format($n, 0, ',', '.'); }
    }

    $firstName = $user && $user->name ? explode(' ', trim($user->name))[0] : 'Penitip';
    $hour = (int) now()->format('G');
    $greeting = $hour < 11 ? 'Selamat pagi' : ($hour < 15 ? 'Selamat siang' : ($hour < 19 ? 'Selamat sore' : 'Selamat malam'));

    // Ringkasan titipan aktif
    $daysLeft = 0;
    $totalDays = 1;
    $ringOffset = 345.4;
    if ($activeOrder) {
        $daysLeft = max(0, (int) now()->startOfDay()->diffInDays($activeOrder->date_end->copy()->startOfDay(), false));
        $totalDays = max(1, (int) abs($activeOrder->date_start->copy()->startOfDay()->diffInDays($activeOrder->date_end->copy()->startOfDay())) + 1);
        $ringOffset = round(345.4 * (1 - min(1, $daysLeft / $totalDays)), 1);
    }

    // Kelengkapan profil (nama + nomor WhatsApp)
    $profileDone = (int) ! empty($user->name) + (int) ! empty($user->phone);
    $profileDismissed = session('profile_banner_dismissed');

    $actions = [
        ['ruang-titip.index', 'n-ware', 'Titip barang', 'Pilih ruang dan jadwal jemput'],
        ['pesanan.index', 'n-receipt', 'Pesanan saya', 'Bayar, pantau, dan ambil barang'],
        ['packing.index', 'n-tape', 'Beli perlengkapan', 'Kardus, lakban, bubble wrap'],
        ['preloved.cara-jual', 'n-tag', 'Jual barang', 'Lewat Toko Preloved'],
    ];
@endphp

@section('content')
<div class="bd">

    {{-- ===== Banner lengkapi profil ===== --}}
    @if ($profileIncomplete && ! $profileDismissed)
        <section class="bd-profile c12" id="notice" aria-label="Pengingat profil">
            <div class="bd-ring">
                <svg viewBox="0 0 52 52" aria-hidden="true">
                    <circle class="t" cx="26" cy="26" r="21"/>
                    <circle class="p" cx="26" cy="26" r="21" stroke-dasharray="131.9" stroke-dashoffset="{{ round(131.9 * (1 - $profileDone / 2), 1) }}"/>
                </svg>
                <span>{{ $profileDone }}/2</span>
            </div>
            <div>
                <b>Lengkapi profil kamu dulu, yuk</b>
                <p>Isi nama dan nomor WhatsApp supaya tim Ruru bisa menghubungimu soal pesanan.</p>
            </div>
            <div class="acts">
                <a class="btn btn-ink sm" href="{{ route('profile.index') }}">Lengkapi sekarang</a>
                <button class="later" type="button" id="skip-notice">Nanti saja</button>
            </div>
        </section>
    @endif

    {{-- ===== Hero ===== --}}
    <section class="rt-card bd-hero c8" aria-label="Sapaan">
        <div class="disc" aria-hidden="true"></div>
        <div class="bd-hero-copy">
            <span class="hi">{{ $greeting }}, {{ $firstName }}!</span>
            @if ($activeOrder)
                <h2>Barangmu aman di <span>{{ $activeOrder->storage?->name ?? 'gudang kami' }}.</span></h2>
                <p>Masa titip {{ $activeOrder->code() }} berakhir {{ $activeOrder->date_end->locale('id')->translatedFormat('j F Y') }}. Mau titip lagi atau cek status barangmu? Semua bisa dari sini.</p>
                <div class="acts">
                    <a class="btn btn-sun" href="{{ route('pesanan.detail', $activeOrder) }}">Lihat pesanan</a>
                    <a class="btn btn-line" href="{{ route('ruang-titip.index') }}">Titip barang lagi</a>
                </div>
            @else
                <h2>Titip barangmu, <span>simpan uangmu.</span></h2>
                <p>Mau pulang kampung, magang, atau pindah kos? Titipkan barangmu di gudang RuangTitip dan nggak perlu bayar kos kosong.</p>
                <div class="acts">
                    <a class="btn btn-sun" href="{{ route('ruang-titip.index') }}">Titip barang sekarang
                        <svg class="ic" aria-hidden="true"><use href="#i-arrow"/></svg></a>
                    <a class="btn btn-line" href="{{ route('pesanan.index') }}">Lihat pesanan</a>
                </div>
            @endif
        </div>
        <div class="bd-say" aria-hidden="true">{{ $activeOrder ? 'Aku jagain, tenang aja!' : 'Barangmu mau dititip kapan?' }}</div>
        <img class="ruru" src="{{ asset('assets/ruru/ruru-halo.webp') }}" alt="" width="800" height="800">
    </section>

    {{-- ===== Ringkasan titipan ===== --}}
    <section class="rt-card bd-summary c4" aria-label="Ringkasan titipan">
        @if ($activeOrder)
            <div class="rt-card-h"><div><h2>Ringkasan titipan</h2><p class="sub">{{ $activeOrder->code() }}, {{ $activeOrder->storage?->name }}</p></div></div>
            <div class="bd-bigring" role="img" aria-label="Sisa {{ $daysLeft }} dari {{ $totalDays }} hari">
                <svg viewBox="0 0 132 132" aria-hidden="true">
                    <circle class="t" cx="66" cy="66" r="55"/>
                    <circle class="p" cx="66" cy="66" r="55" stroke-dashoffset="{{ $ringOffset }}"/>
                </svg>
                <div><strong>{{ $daysLeft }}</strong><small>hari lagi</small></div>
            </div>
            <ul class="bd-kv">
                <li><span>Jumlah barang</span><b>{{ $activeOrder->totalItems() }} barang</b></li>
                <li><span>Periode</span><b>{{ $activeOrder->date_start->locale('id')->translatedFormat('j M') }} sampai {{ $activeOrder->date_end->locale('id')->translatedFormat('j M Y') }}</b></li>
                <li><span>Total biaya</span><b>{{ rupiah($activeOrder->total) }}</b></li>
            </ul>
            <a class="btn btn-ink" href="{{ route('pesanan.detail', $activeOrder) }}">Lihat detail pesanan</a>
        @else
            <div class="rt-card-h"><div><h2>Ringkasan titipan</h2><p class="sub">Belum ada barang di gudang</p></div></div>
            <div class="bd-empty-mini">
                <svg viewBox="0 0 44 40" aria-hidden="true"><use href="#box"/></svg>
                <b>Rak masih kosong</b>
                <p>Sisa hari titip dan biayamu akan muncul di sini.</p>
            </div>
        @endif
    </section>

    {{-- ===== Perlu tindakan ===== --}}
    @if ($pendingOrders->isNotEmpty())
        <section class="rt-card bd-todo c12" aria-labelledby="todoT">
            <header class="bd-todo-h">
                <span class="bd-todo-n" aria-hidden="true">{{ $pendingOrders->count() }}</span>
                <div><h2 id="todoT">Perlu tindakanmu</h2><p class="sub">Selesaikan dari atas, yang paling mendesak duluan.</p></div>
                <a class="more-link" href="{{ route('pesanan.index') }}">Semua pesanan <svg class="ic" width="16" height="16" aria-hidden="true"><use href="#i-arrow"/></svg></a>
            </header>
            <ol class="bd-todo-list">
                @foreach ($pendingOrders as $po)
                    @php $pay = $po->status === 'menunggu_pembayaran'; @endphp
                    <li class="{{ $pay ? 't-warn' : 't-info' }}">
                        <div class="bd-t-main"><span class="bd-t-st">{{ $po->statusMeta()['label'] }}</span><b>{{ $po->code() }}</b></div>
                        <span class="bd-t-what">{{ $po->storage?->name }}, {{ $po->totalItems() }} barang</span>
                        <a class="btn {{ $pay ? 'btn-sun' : 'btn-ghost' }} sm" href="{{ route('pesanan.detail', $po) }}">{{ $pay ? 'Bayar ' . rupiah($po->total) : 'Lihat detail' }}</a>
                    </li>
                @endforeach
            </ol>
        </section>
    @endif

    {{-- ===== Aksi cepat ===== --}}
    <nav class="rt-card bd-actions c12" aria-label="Aksi cepat">
        @foreach ($actions as [$route, $icon, $title, $desc])
            <a class="bd-act" href="{{ route($route) }}">
                <span class="ico"><svg aria-hidden="true"><use href="#{{ $icon }}"/></svg></span>
                <span><b>{{ $title }}</b><small>{{ $desc }}</small></span>
            </a>
        @endforeach
    </nav>

    {{-- ===== Barang di gudang ===== --}}
    <section class="rt-card c8" aria-labelledby="gTitle">
        <div class="rt-card-h">
            <div><h2 id="gTitle">Barang di gudang</h2><p class="sub">{{ $warehouseOrders->isNotEmpty() ? 'Titipan yang sedang tersimpan, urut dari yang paling cepat berakhir.' : 'Status dan lokasi titipanmu akan tampil di sini.' }}</p></div>
        </div>
        @if ($warehouseOrders->isNotEmpty())
            <div class="bd-inv" role="table" aria-label="Titipan di gudang">
                <div class="bd-inv-h" role="row"><span role="columnheader">Pesanan</span><span role="columnheader">Gudang</span><span role="columnheader">Sampai</span><span role="columnheader">Status</span><span></span></div>
                @foreach ($warehouseOrders as $wo)
                    <a class="bd-inv-r" role="row" href="{{ route('pesanan.detail', $wo) }}">
                        <span role="cell" class="bd-inv-code"><svg viewBox="0 0 44 40" aria-hidden="true"><use href="#box"/></svg><span><b>{{ $wo->code() }}</b><small>{{ $wo->totalItems() }} barang</small></span></span>
                        <span role="cell"><b>{{ $wo->storage?->name }}</b><small>{{ $wo->storage?->address }}</small></span>
                        <span role="cell">{{ $wo->date_end->locale('id')->translatedFormat('j M Y') }}</span>
                        <span role="cell" class="bd-inv-st">Disimpan</span>
                        <span class="bd-inv-go" aria-hidden="true"><svg class="ic"><use href="#i-arrow"/></svg></span>
                    </a>
                @endforeach
            </div>
        @else
            <div class="bd-empty">
                <svg viewBox="0 0 44 40" aria-hidden="true"><use href="#box"/></svg>
                <div>
                    <h3>Belum ada barang di gudang</h3>
                    <p>Setelah barangmu diterima petugas dan masuk gudang, titipanmu muncul di sini lengkap dengan statusnya.</p>
                    <a class="btn btn-ink sm" href="{{ route('ruang-titip.index') }}">Buat pesanan</a>
                </div>
            </div>
        @endif
    </section>

    {{-- ===== Aktivitas terbaru ===== --}}
    <section class="rt-card c4" aria-labelledby="aTitle">
        <div class="rt-card-h"><h2 id="aTitle">Aktivitas terbaru</h2><a class="more-link" href="{{ route('pesanan.index') }}">Semua</a></div>
        @if ($notifications->isNotEmpty())
            <ul class="bd-feed">
                @foreach ($notifications as $n)
                    <li>
                        <span class="dot" aria-hidden="true"><svg class="ic"><use href="#n-bell"/></svg></span>
                        <div>
                            <a href="{{ $n['link'] }}"><b>{{ $n['text'] }}</b></a>
                            @if ($n['at'])<span>{{ $n['at']->locale('id')->diffForHumans() }}</span>@endif
                        </div>
                    </li>
                @endforeach
            </ul>
        @else
            <p class="bd-note">Belum ada aktivitas. Kabar soal pembayaran, penjemputan, dan status barang akan muncul di sini.</p>
        @endif
    </section>

    {{-- ===== Ruang Titip ===== --}}
    <section class="rt-card bd-shop" aria-labelledby="sec-gudang">
        <div class="rt-card-h">
            <div><h2 id="sec-gudang">Ruang Titip</h2><p class="sub">Gudang penyimpanan terdekat dari kosmu.</p></div>
            <a class="more-link" href="{{ route('ruang-titip.index') }}">Lihat semua <svg class="ic" width="16" height="16" aria-hidden="true"><use href="#i-arrow"/></svg></a>
        </div>
        @if ($storages->isNotEmpty())
            <div class="bd-shop-grid three">
                @foreach ($storages as $s)
                    <a class="bd-prod" href="{{ route('ruang-titip.detail', $s) }}">
                        <div class="thumb">
                            @if ($s->primary_photo)
                                <img src="{{ asset('storage/' . $s->primary_photo) }}" alt="{{ $s->name }}">
                            @else
                                <svg aria-hidden="true"><use href="#n-ware"/></svg>
                            @endif
                        </div>
                        <div class="body">
                            <strong>{{ $s->name }}</strong>
                            <span class="meta">{{ $s->address }}</span>
                            <div class="bd-bar"><span style="width:{{ $s->capacity_pct }}%"></span></div>
                            <p class="price">{{ rupiah($s->min_price) }} <span class="meta">/ hari</span></p>
                        </div>
                    </a>
                @endforeach
            </div>
        @else
            <div class="bd-note">Ruru lagi siapin gudangnya. Kami kabari lewat notifikasi begitu sudah buka.</div>
        @endif
    </section>

    {{-- ===== Toko Packing ===== --}}
    <section class="rt-card bd-shop" aria-labelledby="sec-packing">
        <div class="rt-card-h">
            <div><h2 id="sec-packing">Toko Packing</h2><p class="sub">Kardus, lakban, dan bubble wrap untuk titipanmu.</p></div>
            <a class="more-link" href="{{ route('packing.index') }}">Lihat semua <svg class="ic" width="16" height="16" aria-hidden="true"><use href="#i-arrow"/></svg></a>
        </div>
        @if ($packing->isNotEmpty())
            <div class="bd-shop-grid">
                @foreach ($packing as $p)
                    <a class="bd-prod" href="{{ route('packing.show', $p) }}">
                        <div class="thumb">
                            @if ($p->primary_image)
                                <img src="{{ asset('storage/' . $p->primary_image) }}" alt="{{ $p->name }}">
                            @else
                                <svg aria-hidden="true"><use href="#n-tape"/></svg>
                            @endif
                        </div>
                        <div class="body">
                            <strong>{{ $p->name }}</strong>
                            <p class="price">{{ rupiah($p->price) }}</p>
                            <span class="meta">Stok: {{ $p->stock }}</span>
                        </div>
                    </a>
                @endforeach
            </div>
        @else
            <div class="bd-note">Produk packing segera hadir. Sementara itu, tim kami tetap bisa bawa perlengkapan saat menjemput barangmu.</div>
        @endif
    </section>

    {{-- ===== Toko Preloved ===== --}}
    <section class="rt-card bd-shop" aria-labelledby="sec-preloved">
        <div class="rt-card-h">
            <div><h2 id="sec-preloved">Toko Preloved</h2><p class="sub">Barang bekas mahasiswa, masih layak pakai.</p></div>
            <a class="more-link" href="{{ route('preloved.index') }}">Lihat semua <svg class="ic" width="16" height="16" aria-hidden="true"><use href="#i-arrow"/></svg></a>
        </div>
        <div class="bd-promo">
            <div class="txt">
                <strong>Punya barang nganggur? Jual di sini.</strong>
                <span>Ajukan barangmu, cek bareng petugas, dan terima pembayaran saat serah terima.</span>
            </div>
            <a class="btn btn-sun sm" href="{{ route('preloved.cara-jual') }}">Pelajari</a>
        </div>
        @if ($preloved->isNotEmpty())
            <div class="bd-shop-grid">
                @foreach ($preloved as $p)
                    <a class="bd-prod" href="{{ route('preloved.show', $p->id) }}">
                        <div class="thumb">
                            @if ($p->primary_photo)
                                <img src="{{ asset('storage/' . $p->primary_photo) }}" alt="{{ $p->name }}">
                            @else
                                <svg aria-hidden="true"><use href="#n-tag"/></svg>
                            @endif
                        </div>
                        <div class="body">
                            <span class="cond">{{ $p->condition }}%</span>
                            <strong>{{ $p->name }}</strong>
                            <p class="price">{{ rupiah($p->price) }}</p>
                        </div>
                    </a>
                @endforeach
            </div>
        @else
            <div class="bd-note">Belum ada barang preloved. Jadi yang pertama pasang barang, biar lebih cepat dilihat pembeli.</div>
        @endif
    </section>

    {{-- ===== Testimoni ===== --}}
    <section class="c12" aria-labelledby="sec-testi">
        <div class="rt-card-h"><div><h2 id="sec-testi">Kata mereka</h2><p class="sub">Cerita dari penitip RuangTitip.</p></div></div>
        <div class="bd-voices">
            @foreach ($testimonials as $t)
                <figure class="rt-card bd-voice">
                    <blockquote>&ldquo;{{ $t['text'] }}&rdquo;</blockquote>
                    <figcaption>
                        <span class="av" aria-hidden="true">{{ $t['avatar'] }}</span>
                        <span><b>{{ $t['name'] }}</b><span>{{ $t['major'] }}</span></span>
                    </figcaption>
                </figure>
            @endforeach
        </div>
    </section>
</div>
@endsection

@push('scripts')
<script>
(function () {
    var skip = document.getElementById('skip-notice');
    if (!skip) return;
    skip.addEventListener('click', function () {
        var box = document.getElementById('notice');
        if (box) box.remove();
        fetch(@json(route('profile.dismiss-banner')), {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            }
        });
    });
})();
</script>
@endpush
