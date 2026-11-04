@extends('layouts.rt')

@section('title', 'Katalog')

@php
    if (! function_exists('rp')) {
        function rp($n) { return 'Rp' . number_format($n, 0, ',', '.'); }
    }
@endphp

@section('content')
<div class="mp">
<div class="stack">
    @include('partials.mp-head', [
        'crumbs' => [['Toko Preloved', null], ['Katalog', null]],
        'title' => 'Barang preloved pilihan',
        'sub' => 'Semua barang sudah diperiksa petugas dan disimpan di Gudang Ruru, Lowokwaru, Malang. Kondisi, kelengkapan, dan kekurangannya ditulis apa adanya.',
        'acts' => '<a class="btn btn-sun" href="' . e(route('preloved.cara-jual')) . '">Jual barangmu</a>',
    ])

    <section class="card stack" style="gap:14px">
        <div class="field"><label for="q">Cari barang</label><input class="input" id="q" type="search" value="" placeholder="Contoh: kipas, rak, rice cooker"></div>
        <div class="chips" role="group" aria-label="Kategori">
            @foreach ($categories as $cat)
                <a class="chip" aria-pressed="{{ $cat === $category ? 'true' : 'false' }}" href="{{ route('preloved.index', array_filter(['kategori' => $cat === 'Semua' ? null : $cat])) }}">{{ $cat }}</a>
            @endforeach
        </div>
    </section>

    @if ($products->isEmpty())
        <section class="card state"><svg viewBox="0 0 44 40" width="90" height="82" aria-hidden="true"><use href="#box"/></svg><h3>Belum ada barang yang cocok</h3><p>Coba kata kunci atau kategori lain. Stok baru muncul setelah barang selesai diperiksa admin.</p></section>
    @else
        <div class="grid" id="grid">
            @foreach ($products as $p)
                <a class="card prod" href="{{ route('preloved.show', $p['id']) }}" data-name="{{ strtolower($p['name']) }}">
                    <div class="pic" style="--tone:var(--alt)">
                        @if ($p['image'])
                            <img src="{{ \Illuminate\Support\Facades\Storage::url($p['image']) }}" alt="{{ $p['name'] }}" style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover">
                        @else
                            <svg viewBox="0 0 24 24" aria-hidden="true"><use href="#n-tag"/></svg>
                        @endif
                    </div>
                    <div class="prod-b"><span class="help">{{ $p['category_label'] }}, {{ $p['cond_text'] }}</span><h3>{{ $p['name'] }}</h3><span class="price">{{ rp($p['price']) }}</span></div>
                </a>
            @endforeach
        </div>
        <section class="card state" id="no-result" hidden><svg viewBox="0 0 44 40" width="90" height="82" aria-hidden="true"><use href="#box"/></svg><h3>Belum ada barang yang cocok</h3><p>Coba kata kunci atau kategori lain. Stok baru muncul setelah barang selesai diperiksa admin.</p></section>
    @endif
</div>
</div>
@endsection

@push('scripts')
<script>
(function () {
    var grid = document.getElementById('grid');
    if (!grid) return;
    var q = document.getElementById('q'), none = document.getElementById('no-result');
    q.addEventListener('input', function () {
        var term = q.value.trim().toLowerCase(), shown = 0;
        [].slice.call(grid.children).forEach(function (c) {
            var ok = c.dataset.name.indexOf(term) !== -1;
            c.style.display = ok ? '' : 'none';
            if (ok) shown++;
        });
        grid.style.display = shown ? '' : 'none';
        none.hidden = shown > 0;
    });
})();
</script>
@endpush
