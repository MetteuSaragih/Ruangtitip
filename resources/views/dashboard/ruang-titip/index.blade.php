@extends('layouts.rt')

@section('title', 'Ruang Titip')

@php
    if (! function_exists('rp')) {
        function rp($n) { return 'Rp' . number_format($n, 0, ',', '.'); }
    }
    $tones = ['var(--color-aqua)', 'var(--color-bg)', 'var(--color-divider)'];
    $wa = 'https://wa.me/6285121091134';
    $startDefault = now()->addDay()->toDateString();
@endphp

@section('content')
@include('dashboard.ruang-titip._icons')
<div class="ttp">
<div class="stack">
    <nav class="crumbs" aria-label="Breadcrumb"><span aria-current="page">Ruang Titip</span></nav>
    <div class="ph">
        <div>
            <h2 class="pt" tabindex="-1">Pilih ruang untuk barangmu</h2>
            <p class="ps">Pilih gudang terdekat, lalu atur barang dan jadwal titipmu. Ketersediaan mengikuti periode rencanamu.</p>
        </div>
    </div>

    <section class="card plan">
        <h3>Cek ketersediaan</h3>
        <div class="field">
            <label for="pStart">Mulai titip</label>
            <input class="input" type="date" id="pStart" value="{{ $startDefault }}" min="{{ $startDefault }}" max="{{ now()->addDays(365)->toDateString() }}">
        </div>
        <div class="field">
            <label for="pDur">Lama titip</label>
            <select class="input" id="pDur">
                @foreach ([[14, '2 minggu'], [30, '1 bulan'], [60, '2 bulan'], [90, '3 bulan'], [180, '6 bulan']] as [$d, $l])
                    <option value="{{ $d }}" @selected($d === 30)>{{ $l }} ({{ $d }} hari)</option>
                @endforeach
            </select>
        </div>
        <p>Selesai <b id="pEnd"></b></p>
        <div class="full" id="pPeak" hidden>
            <div class="alert a-warn"><svg class="ic" aria-hidden="true"><use href="#i-info"/></svg><div><b>Musim libur semester</b><p>Periode ini biasanya ramai, ruang lebih cepat penuh.</p></div></div>
        </div>
    </section>

    @if ($storages->isNotEmpty())
        <div class="rooms">
            @foreach ($storages as $i => $s)
                @php
                    $status = $s->capacity_pct >= 100 ? 'full' : ($s->capacity_pct >= 85 ? 'few' : 'open');
                    $label = ['open' => 'Tersedia', 'few' => 'Hampir penuh', 'full' => 'Penuh di periode ini'][$status];
                    $tone = ['open' => 'ok', 'few' => 'warn', 'full' => 'err'][$status];
                    $rem = max(0, (int) $s->capacity_total - (int) $s->capacity_used);
                @endphp
                <article class="card room">
                    <div class="art" style="--tone:{{ $tones[$i % 3] }}">
                        @if ($s->primary_photo)
                            <img src="{{ asset('storage/' . $s->primary_photo) }}" alt="Foto {{ $s->name }}">
                        @else
                            <svg viewBox="0 0 260 150" role="img" aria-label="Ilustrasi {{ $s->name }}"><use href="#sc"/></svg>
                        @endif
                        <span class="badge b-{{ $tone }}"><i></i>{{ $label }}</span>
                    </div>
                    <div class="room-body">
                        <div>
                            <h3>{{ $s->name }}@if ($s->title)<small class="where"> &middot; {{ $s->title }}</small>@endif</h3>
                            <p class="where">{{ $s->location ?: $s->address }}</p>
                        </div>
                        <p class="desc">{{ $s->description ?: $s->address }}</p>
                        <div class="chips" aria-label="Ukuran yang diterima">
                            <span class="help">Ukuran:</span>
                            @foreach ($s->accepted_sizes as $zc)<span class="chip sm">{{ $zc }}</span>@endforeach
                        </div>
                        <div class="cap">
                            <div class="cap-top"><span>Sisa kapasitas</span><b>{{ $rem > 0 ? '±' . $rem . ' setara ukuran S' : 'Habis' }}</b></div>
                            <div class="bar is-{{ $status === 'full' ? 'err' : ($status === 'few' ? 'warn' : 'ok') }}" aria-hidden="true"><i style="width:{{ min(100, $s->capacity_pct) }}%"></i></div>
                            <small>Terisi {{ $s->capacity_pct }}%</small>
                        </div>
                        <div class="room-foot">
                            <p class="price"><small>Mulai</small><b>{{ rp($s->min_price) }}</b><small>per barang per hari</small></p>
                            <a class="btn btn-ink" href="{{ route('ruang-titip.detail', $s) }}">Lihat Detail</a>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>
    @else
        <section class="card state">
            <svg viewBox="0 0 44 40" width="90" height="82" aria-hidden="true"><use href="#box"/></svg>
            <h3>Belum ada ruang yang dibuka</h3>
            <p>Ruang penyimpanan sedang disiapkan. Cek lagi nanti atau tanya admin.</p>
            <div class="acts"><a class="btn btn-ghost" href="{{ $wa }}" target="_blank" rel="noopener">Tanya admin</a></div>
        </section>
    @endif
</div>
</div>
@endsection

@push('scripts')
<script>
(function () {
    var st = document.getElementById('pStart'), du = document.getElementById('pDur'), en = document.getElementById('pEnd'), pk = document.getElementById('pPeak');
    function upd() {
        if (!st.value) return;
        var d = new Date(st.value + 'T00:00:00'), n = parseInt(du.value, 10), e = new Date(d.getTime());
        e.setDate(e.getDate() + n - 1);
        en.textContent = e.toLocaleDateString('id-ID', { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' });
        var peak = [0, 5, 6, 11], hit = false, c = new Date(d.getFullYear(), d.getMonth(), 1);
        while (c <= e) { if (peak.indexOf(c.getMonth()) !== -1) { hit = true; break; } c.setMonth(c.getMonth() + 1); }
        pk.hidden = !hit;
    }
    st.addEventListener('change', upd); du.addEventListener('change', upd); upd();
})();
</script>
@endpush
