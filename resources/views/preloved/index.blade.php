@extends('layouts.rt')

@section('title', 'Toko Preloved')

@php
    if (! function_exists('rp')) {
        function rp($n) { return 'Rp' . number_format($n, 0, ',', '.'); }
    }
    $condOptions = ['semua' => 'Semua', '95_mulus' => 'Seperti baru', '85_baik' => 'Baik ke atas'];
@endphp

@section('content')
<div class="pg">
    <div class="pg-head">
        <div>
            <h2>Barang preloved pilihan</h2>
            <p>Barang bekas layak pakai dari sesama mahasiswa. Semua barang disimpan di gudang RuangTitip dan kondisinya ditulis apa adanya.</p>
        </div>
        <div class="pg-acts"><a class="btn btn-sun" href="{{ route('preloved.cara-jual') }}">Jual barangmu</a></div>
    </div>

    <section class="rt-card" style="display:flex;align-items:center;gap:20px;flex-wrap:wrap;background:var(--deep);border-color:transparent;color:#fff">
        <img src="{{ asset('assets/ruru/ruru-label-harga.webp') }}" alt="" width="88" height="88" style="width:88px;height:auto">
        <div style="flex:1;min-width:220px">
            <h3 style="font-size:20px">Mau lulus atau pindah kos? Barangmu bisa jadi uang.</h3>
            <p style="color:rgba(255,255,255,.75);margin-top:4px;font-size:15px">Ajukan barang, cek bareng petugas, dan terima pembayaran saat serah terima.</p>
        </div>
        <a class="btn btn-sun" href="{{ route('preloved.cara-jual') }}">Pelajari</a>
    </section>

    <div class="pg-toolbar" role="search">
        <label class="pg-search">
            <span class="sr">Cari barang</span>
            <svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5"/></svg>
            <input type="search" id="q" placeholder="Cari kipas, rak, rice cooker">
        </label>
        <label><span class="sr">Urutkan</span>
            <select class="pg-select" id="sort">
                <option value="new">Terbaru</option>
                <option value="cheap">Harga termurah</option>
                <option value="exp">Harga termahal</option>
                <option value="cond">Kondisi terbaik</option>
            </select>
        </label>
    </div>

    <div class="pg-filters" aria-label="Kategori">
        @foreach ($categories as $cat)
            <a class="chip" aria-pressed="{{ $cat === $category ? 'true' : 'false' }}" href="{{ route('preloved.index', array_filter(['kategori' => $cat === 'Semua' ? null : $cat, 'kondisi' => $condition !== 'semua' ? $condition : null])) }}">{{ $cat }}</a>
        @endforeach
        <span class="sep" aria-hidden="true"></span>
        @foreach ($condOptions as $key => $label)
            <a class="chip" aria-pressed="{{ $key === $condition ? 'true' : 'false' }}" href="{{ route('preloved.index', array_filter(['kategori' => $category !== 'Semua' ? $category : null, 'kondisi' => $key !== 'semua' ? $key : null])) }}">{{ $label }}</a>
        @endforeach
    </div>

    @if ($products->isEmpty())
        <section class="rt-card pg-empty">
            <img src="{{ asset('assets/ruru/ruru-mencari.webp') }}" alt="" width="90" height="90">
            <h3>Belum ada barang yang cocok</h3>
            <p>Ruru belum nemu barang yang cocok. Coba ubah filter atau kata kunci.</p>
        </section>
    @else
        <div class="pc-grid" id="grid">
            @foreach ($products as $p)
                <article class="pc" data-name="{{ strtolower($p['name']) }}" data-price="{{ $p['price'] }}" data-cond="{{ $p['condition_percent'] }}">
                    <a class="pc-pic" href="{{ route('preloved.show', $p['id']) }}" aria-label="Detail {{ $p['name'] }}">
                        @if ($p['image'])
                            <img src="{{ \Illuminate\Support\Facades\Storage::url($p['image']) }}" alt="{{ $p['name'] }}">
                        @else
                            <svg aria-hidden="true"><use href="#n-tag"/></svg>
                        @endif
                        <span class="badge b-ok"><i></i>{{ $p['condition_label'] }}</span>
                    </a>
                    <div class="pc-body">
                        <h3><a href="{{ route('preloved.show', $p['id']) }}">{{ $p['name'] }}</a></h3>
                        <span class="pc-price">{{ rp($p['price']) }}</span>
                        <span class="meta">Gudang RuangTitip</span>
                    </div>
                </article>
            @endforeach
        </div>
        <p id="no-result" class="rt-help" style="display:none;padding:40px 0;text-align:center">Barang tidak ditemukan. Coba kata kunci lain.</p>
    @endif
</div>
@endsection

@push('scripts')
<script>
(function () {
    var grid = document.getElementById('grid');
    if (!grid) return;
    var q = document.getElementById('q'), sort = document.getElementById('sort'), none = document.getElementById('no-result');

    function apply() {
        var term = q.value.trim().toLowerCase();
        var cards = [].slice.call(grid.children);
        var shown = 0;
        cards.forEach(function (c) {
            var ok = c.dataset.name.indexOf(term) !== -1;
            c.style.display = ok ? '' : 'none';
            if (ok) shown++;
        });
        none.style.display = shown ? 'none' : 'block';

        var s = sort.value;
        if (s !== 'new') {
            cards.sort(function (a, b) {
                if (s === 'cheap') return a.dataset.price - b.dataset.price;
                if (s === 'exp') return b.dataset.price - a.dataset.price;
                return b.dataset.cond - a.dataset.cond;
            }).forEach(function (c) { grid.appendChild(c); });
        }
    }
    q.addEventListener('input', apply);
    sort.addEventListener('change', apply);
})();
</script>
@endpush
