@extends('layouts.ruang-titip')
@section('title', 'Toko Preloved')

@php function rp($n){ return 'Rp'.number_format($n,0,',','.'); } @endphp

@push('styles')
<style>
.sell{display:flex;align-items:center;gap:20px;padding:24px 28px;background:var(--ink);color:var(--cream);border-radius:20px;margin-bottom:28px;position:relative;overflow:hidden}
.sell img{width:88px;height:auto;flex-shrink:0;position:relative}
.sell .txt{flex-grow:1;position:relative}
.sell small{font-size:13px;font-weight:700;letter-spacing:.6px;text-transform:uppercase;color:var(--tape-light)}
.sell strong{display:block;font-family:var(--font-display);font-size:24px;line-height:1.2;margin-top:4px}
.sell p{color:#C9C2B3;font-size:15px;margin-top:4px}
.sell .btn{background:var(--tape-light);color:var(--ink);border-color:var(--tape-light);box-shadow:none;position:relative}
.sell .btn:hover{background:#F0B98F;color:var(--ink)}

.chips{display:flex;gap:8px;flex-wrap:wrap;margin-bottom:16px}
.chips span.lbl{font-size:13px;font-weight:700;color:var(--muted);align-self:center;margin-right:2px}
.chips a{display:inline-flex;min-height:40px;align-items:center;padding:0 16px;border-radius:999px;border:1.5px solid var(--line-strong);background:var(--paper);font-weight:600;font-size:14px;text-decoration:none;color:var(--ink)}
.chips a:hover{border-color:var(--ink)}
.chips a.on{background:var(--ink);border-color:var(--ink);color:var(--cream)}
.chip-row{margin-bottom:12px}
.chip-row:last-of-type{margin-bottom:24px}

.item-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:18px}
.item{position:relative;display:flex;flex-direction:column;background:var(--paper);border:1px solid var(--line-strong);border-radius:16px;overflow:hidden;transition:border-color .15s,transform .15s}
.item:hover{border-color:var(--ink);transform:translateY(-2px)}
.item .thumb{position:relative;display:grid;place-items:center;aspect-ratio:1;background:var(--sand);text-decoration:none;overflow:hidden}
.item .thumb img{position:absolute;inset:0;width:100%;height:100%;object-fit:cover}
.item .thumb .cond{position:absolute;left:10px;bottom:10px;font-size:11px;font-weight:700;padding:4px 9px;border-radius:999px;background:var(--depot-light);color:#1F4535}
.fav{position:absolute;top:10px;right:10px;z-index:2;width:36px;height:36px;border-radius:50%;background:var(--paper);border:1.5px solid var(--ink);display:grid;place-items:center;cursor:pointer;padding:0}
.fav[aria-pressed="true"] svg{fill:var(--tape);stroke:var(--tape)}
.item .body{padding:14px 16px 16px;display:flex;flex-direction:column;gap:4px;flex-grow:1}
.item h2{font-family:var(--font-body);font-size:15px;font-weight:700;line-height:1.35}
.item h2 a{color:var(--ink);text-decoration:none}
.item h2 a:hover{color:var(--tape-dark)}
.item .price{font-family:var(--font-display);font-size:19px;font-weight:800;color:var(--tape-dark)}
.item .meta{font-size:13px;color:var(--muted);display:flex;align-items:center;gap:6px;margin-top:auto;padding-top:8px}

.empty-cat{padding:48px 24px;text-align:center;background:var(--paper);border:1.5px dashed var(--line-strong);border-radius:18px}
.empty-cat img{width:90px;margin:0 auto 12px}
.empty-cat p{color:var(--body)}

@media (max-width:1080px){.item-grid{grid-template-columns:repeat(3,1fr)}}
@media (max-width:760px){
  .item-grid{grid-template-columns:repeat(2,1fr);gap:12px}
  .sell{flex-direction:column;align-items:flex-start}
}
</style>
@endpush

@section('content')
<main class="wrap">
  <div class="page-head">
    <div>
      <h1>Toko Preloved</h1>
      <p>Barang bekas layak pakai dari sesama mahasiswa. Semua barang disimpan di gudang RuangTitip.</p>
    </div>
  </div>

  <aside class="sell">
    <img src="{{ asset('assets/ruru.webp') }}" alt="" width="88" height="103">
    <div class="txt">
      <small>Jual barang bekasmu</small>
      <strong>Mau lulus atau pindah kos? Barangmu bisa jadi uang.</strong>
      <p>Foto &amp; pasang barang, titip di gudang, dapat uang saat terjual.</p>
    </div>
    <a class="btn" href="{{ route('preloved.cara-jual') }}">Jual barang</a>
  </aside>

  <div class="filters" role="search">
    <label class="search">
      <span class="sr" style="position:absolute;left:-9999px">Cari barang</span>
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5"/></svg>
      <input type="search" id="q" placeholder="Cari kipas, rak, rice cooker…">
    </label>
    <label><span class="sr" style="position:absolute;left:-9999px">Urutkan</span>
      <select id="sort">
        <option value="new">Terbaru</option>
        <option value="cheap">Harga termurah</option>
        <option value="exp">Harga termahal</option>
        <option value="cond">Kondisi terbaik</option>
      </select>
    </label>
  </div>

  <div class="chips chip-row">
    <span class="lbl">Kategori</span>
    @foreach ($categories as $cat)
      <a href="{{ route('preloved.index', array_filter(['kategori' => $cat === 'Semua' ? null : $cat, 'kondisi' => $condition !== 'semua' ? $condition : null])) }}" class="{{ $cat === $category ? 'on' : '' }}">{{ $cat }}</a>
    @endforeach
  </div>
  <div class="chips chip-row">
    <span class="lbl">Kondisi</span>
    @php $condOptions = ['semua' => 'Semua', '95_mulus' => 'Seperti baru', '85_baik' => 'Baik ke atas']; @endphp
    @foreach ($condOptions as $key => $label)
      <a href="{{ route('preloved.index', array_filter(['kategori' => $category !== 'Semua' ? $category : null, 'kondisi' => $key !== 'semua' ? $key : null])) }}" class="{{ $key === $condition ? 'on' : '' }}">{{ $label }}</a>
    @endforeach
  </div>

  @if ($products->isEmpty())
    <div class="empty-cat">
      <img src="{{ asset('assets/ruru.webp') }}" alt="" width="90" height="105">
      <p>Ruru belum nemu barang yang cocok. Coba ubah filter atau kata kunci.</p>
    </div>
  @else
    <div class="item-grid" id="grid">
      @foreach ($products as $p)
        <article class="item" data-name="{{ strtolower($p['name']) }}" data-price="{{ $p['price'] }}" data-cond="{{ $p['condition_percent'] }}">
          <button type="button" class="fav" aria-label="Simpan {{ $p['name'] }}" aria-pressed="false" onclick="toggleFav(this)">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#1C1B18" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.8 5.6a5 5 0 0 0-7.1 0L12 7.3l-1.7-1.7a5 5 0 1 0-7.1 7.1L12 21.5l8.8-8.8a5 5 0 0 0 0-7.1z"/></svg>
          </button>
          <a class="thumb" href="{{ route('preloved.show', $p['id']) }}" aria-label="Detail {{ $p['name'] }}">
            @if ($p['image'])
              <img src="{{ \Illuminate\Support\Facades\Storage::url($p['image']) }}" alt="{{ $p['name'] }}">
            @else
              <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="var(--tape-dark)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/></svg>
            @endif
            <span class="cond">{{ $p['condition_label'] }}</span>
          </a>
          <div class="body">
            <h2><a href="{{ route('preloved.show', $p['id']) }}">{{ $p['name'] }}</a></h2>
            <span class="price">{{ rp($p['price']) }}</span>
            <span class="meta">
              <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 21s-7-6.2-7-11.5a7 7 0 0 1 14 0C19 14.8 12 21 12 21z"/><circle cx="12" cy="9.5" r="2.5"/></svg>
              Gudang RuangTitip
            </span>
          </div>
        </article>
      @endforeach
    </div>
    <p id="no-result" style="display:none;padding:40px 0;text-align:center;color:var(--muted)">Barang nggak ditemukan. Coba kata kunci lain.</p>
  @endif
</main>

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

function toggleFav(btn) {
    var on = btn.getAttribute('aria-pressed') === 'true';
    btn.setAttribute('aria-pressed', on ? 'false' : 'true');
}
</script>
@endpush
@endsection
