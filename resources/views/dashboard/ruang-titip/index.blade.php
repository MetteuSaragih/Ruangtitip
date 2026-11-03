@extends('layouts.rt')

@section('title', 'Ruang Titip')

@php
    if (! function_exists('rp')) {
        function rp($n) { return 'Rp' . number_format($n, 0, ',', '.'); }
    }
@endphp

@section('content')
<div class="pg">
    <div class="pg-head">
        <div>
            <h2>Pilih ruang untuk barangmu</h2>
            <p>Pilih gudang terdekat, lalu atur barang dan jadwal titipmu. Isi kemasan tidak dibuka petugas.</p>
        </div>
        <div class="pg-acts">
            <span class="badge b-mute"><i></i>1 Pilih gudang</span>
            <span class="badge b-mute"><i></i>2 Isi barang dan tanggal</span>
            <span class="badge b-mute"><i></i>3 Bayar</span>
        </div>
    </div>

    @if ($storages->isNotEmpty())
        <div class="pg-toolbar" role="search">
            <label class="pg-search">
                <span class="sr">Cari gudang</span>
                <svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5"/></svg>
                <input type="search" id="q" placeholder="Cari nama gudang atau kecamatan">
            </label>
            <label><span class="sr">Urutkan</span>
                <select class="pg-select" id="sort">
                    <option value="near">Terbaru</option>
                    <option value="cheap">Harga termurah</option>
                    <option value="space">Paling lega</option>
                </select>
            </label>
        </div>

        <div class="rm-grid" id="grid">
            @foreach ($storages as $s)
                @php
                    $status = $s->capacity_pct >= 100 ? 'full' : ($s->capacity_pct >= 80 ? 'few' : 'open');
                    $label = ['open' => 'Tersedia', 'few' => 'Hampir penuh', 'full' => 'Penuh'][$status];
                    $tone = ['open' => 'ok', 'few' => 'warn', 'full' => 'mute'][$status];
                @endphp
                <a class="rt-card rm" href="{{ route('ruang-titip.detail', $s) }}"
                   data-name="{{ strtolower($s->name . ' ' . $s->location) }}" data-price="{{ $s->min_price }}" data-fill="{{ $s->capacity_pct }}">
                    <div class="rm-art">
                        <span class="badge b-{{ $tone }}"><i></i>{{ $label }}</span>
                        @if ($s->primary_photo)
                            <img src="{{ asset('storage/' . $s->primary_photo) }}" alt="{{ $s->name }}">
                        @else
                            <svg aria-hidden="true"><use href="#n-ware"/></svg>
                        @endif
                    </div>
                    <div class="rm-body">
                        <div>
                            <h3>{{ $s->name }}</h3>
                            <p class="rm-where"><svg aria-hidden="true"><use href="#i-pin"/></svg>{{ $s->address }}</p>
                        </div>
                        <div class="rm-cap">
                            <div class="top"><span>Kapasitas terisi</span><b>{{ $s->capacity_pct }}%</b></div>
                            <div class="bar" role="img" aria-label="Kapasitas terisi {{ $s->capacity_pct }} persen"><i class="{{ $s->capacity_pct >= 80 ? 'warn' : '' }}" style="width:{{ $s->capacity_pct }}%"></i></div>
                        </div>
                        @if (count($s->facilities ?? []))
                            <ul class="rm-tags" aria-label="Fasilitas">
                                @foreach ($s->facilities as $tag)<li>{{ $tag }}</li>@endforeach
                            </ul>
                        @endif
                        <div class="rm-foot">
                            <p class="rm-price"><small>Mulai dari</small><b>{{ rp($s->min_price) }}</b><span> /bulan</span></p>
                            @if ($status === 'full')
                                <span class="btn btn-ghost" aria-disabled="true">Penuh</span>
                            @else
                                <span class="btn btn-ink">Pilih gudang</span>
                            @endif
                        </div>
                    </div>
                </a>
            @endforeach
        </div>

        <p id="no-result" class="rt-help" style="display:none;padding:40px 0;text-align:center">Gudang tidak ditemukan. Coba kata kunci lain.</p>
    @else
        <section class="rt-card rt-state">
            <svg viewBox="0 0 44 40" width="90" height="82" aria-hidden="true"><use href="#box"/></svg>
            <h3>Belum ada gudang tersedia</h3>
            <p>Ruru lagi siapin gudangnya. Kami kabari lewat notifikasi begitu sudah buka.</p>
        </section>
    @endif

    <section class="rt-card" style="display:flex;align-items:center;gap:20px;flex-wrap:wrap">
        <img src="{{ asset('assets/ruru/ruru-tunjuk.webp') }}" alt="" width="84" height="79" style="width:84px;height:auto">
        <div style="flex:1;min-width:220px">
            <h3 style="font-size:18px">Bingung pilih ukuran kardus?</h3>
            <p class="rt-help" style="margin-top:4px">Chat tim Ruru, kirim foto barangmu, nanti kami bantu hitung butuh berapa kardus.</p>
        </div>
        <a class="btn btn-ghost" href="https://wa.me/6285121091134" target="_blank" rel="noopener">Tanya via WhatsApp</a>
    </section>
</div>
@endsection

@push('scripts')
<script>
(function () {
    var grid = document.getElementById('grid'), q = document.getElementById('q'), sort = document.getElementById('sort'), none = document.getElementById('no-result');
    if (!grid || !q) return;
    function apply() {
        var term = q.value.trim().toLowerCase(), cards = [].slice.call(grid.children), shown = 0;
        cards.forEach(function (c) { var ok = c.dataset.name.indexOf(term) !== -1; c.style.display = ok ? '' : 'none'; if (ok) shown++; });
        none.style.display = shown ? 'none' : 'block';
        var s = sort.value;
        if (s !== 'near') {
            cards.sort(function (a, b) { return s === 'cheap' ? a.dataset.price - b.dataset.price : a.dataset.fill - b.dataset.fill; })
                 .forEach(function (c) { grid.appendChild(c); });
        }
    }
    q.addEventListener('input', apply);
    sort.addEventListener('change', apply);
})();
</script>
@endpush
