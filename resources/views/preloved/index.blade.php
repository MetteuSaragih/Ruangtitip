<<<<<<< HEAD
@extends('layouts.ruang-titip')
=======
﻿@extends('layouts.dashboard')

>>>>>>> hostinger/main
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
<<<<<<< HEAD
<main class="wrap">
  <div class="page-head">
    <div>
      <h1>Toko Preloved</h1>
      <p>Barang bekas layak pakai dari sesama mahasiswa. Semua barang disimpan di gudang RuangTitip.</p>
=======
<div class="pt-6 pb-10">

    {{-- Heading --}}
    <div class="mb-5">
        <h1 class="text-xl font-extrabold text-white font-display">Toko Preloved</h1>
        <p class="text-xs mt-0.5" style="color:rgba(255,255,255,0.4);">Barang bekas berkualitas dari sesama mahasiswa</p>
>>>>>>> hostinger/main
    </div>
  </div>

<<<<<<< HEAD
  <aside class="sell">
    <img src="{{ asset('assets/ruru.webp') }}" alt="" width="88" height="103">
    <div class="txt">
      <small>Jual barang bekasmu</small>
      <strong>Mau lulus atau pindah kos? Barangmu bisa jadi uang.</strong>
      <p>Foto &amp; pasang barang, titip di gudang, dapat uang saat terjual.</p>
=======
    {{-- Banner jual barang --}}
    <div class="flex items-center justify-between gap-4 rounded-2xl px-5 py-4 mb-6 flex-wrap"
         style="background:linear-gradient(135deg,rgba(109,40,217,0.35),rgba(99,102,241,0.2));border:1px solid rgba(139,92,246,0.3);">
        <div class="flex items-center gap-3.5">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0" style="background:rgba(139,92,246,0.25);">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#a78bfa" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/>
                </svg>
            </div>
            <div>
                <p class="text-[10px] font-bold uppercase tracking-wider mb-0.5" style="color:#a78bfa;">Jual Barang Bekasmu</p>
                <p class="text-sm" style="color:rgba(255,255,255,0.85);">Barang kos menumpuk atau mau lulus? <a href="{{ route('preloved.cara-jual') }}" class="font-semibold" style="color:#a78bfa;">Jadi cuan di RuTip!</a></p>
            </div>
        </div>
        <a href="{{ route('preloved.cara-jual') }}" class="shrink-0 flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-semibold transition-all hover:opacity-90" style="background:#7c3aed;color:white;">
            Pelajari <x-lucide-arrow-right class="w-3.5 h-3.5" />
        </a>
>>>>>>> hostinger/main
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

<<<<<<< HEAD
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
=======
    {{-- Grid produk --}}
    @if ($products->isEmpty())
        <div class="rounded-2xl py-16 flex flex-col items-center text-center" style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.09);">
            <div class="relative inline-flex mx-auto mb-5">
                <div class="absolute inset-0 rounded-3xl blur-xl opacity-25" style="background:linear-gradient(135deg,#059669,#34d399);"></div>
                <div class="relative w-20 h-20 rounded-3xl flex items-center justify-center" style="background:linear-gradient(135deg,rgba(5,150,105,0.2),rgba(52,211,153,0.1));border:1px solid rgba(5,150,105,0.35);">
                    <svg class="w-9 h-9" fill="none" stroke="#34d399" stroke-width="1.5" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20.59 13.41l-7.17 7.17a2 2 0 01-2.83 0L2 12V2h10l8.59 8.59a2 2 0 010 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/>
                    </svg>
                </div>
            </div>
            <p class="text-sm font-bold text-white mb-1">Belum ada produk</p>
            <p class="text-xs" style="color:rgba(255,255,255,0.4);">Produk preloved akan segera tersedia</p>
        </div>
    @else
        {{-- Skeleton loading --}}
        <div id="rt-skeleton-grid" class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
            @for ($si = 0; $si < 8; $si++)
            <div class="rounded-2xl overflow-hidden flex flex-col" style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.07);">
                <div class="rt-skeleton" style="aspect-ratio:1/1;"></div>
                <div class="p-3 flex flex-col gap-2">
                    <div class="rt-skeleton h-3 rounded w-3/4"></div>
                    <div class="rt-skeleton h-3 rounded w-1/2"></div>
                    <div class="rt-skeleton h-8 rounded-xl mt-1"></div>
                </div>
            </div>
            @endfor
        </div>

        <div id="rt-product-grid" class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4 hidden">
            @foreach ($products as $product)
                @php
                    $disc = $product['discount_percent'] ?? 0;
                    $origPrice = $product['original_price'] ?? 0;
                    $price = $product['price'] ?? 0;
                    if (! $disc && $origPrice && $origPrice > $price) {
                        $disc = round((1 - $price / $origPrice) * 100);
                    }
                    $cond = $product['condition'] ?? '';
                    $condStyle = match(true) {
                        str_contains($cond, 'mulus') => 'background:rgba(16,185,129,0.2);color:#34d399;',
                        str_contains($cond, 'baik')  => 'background:rgba(59,130,246,0.2);color:#60a5fa;',
                        default                       => 'background:rgba(245,158,11,0.2);color:#fbbf24;',
                    };
                    $images = !empty($product['images']) ? $product['images'] : (!empty($product['image']) ? [$product['image']] : []);
                @endphp
                <div class="rt-tilt rounded-2xl overflow-hidden flex flex-col"
                     style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.09);">
                    {{-- Gambar (rasio 1:1) --}}
                    <div class="relative rt-carousel" style="aspect-ratio:1/1;background:rgba(124,58,237,0.08);">
                        <a href="{{ route('preloved.show', $product['id']) }}" class="block h-full w-full flex items-center justify-center relative">
                            @forelse ($images as $i => $img)
                                <img src="{{ Storage::url($img) }}" alt="{{ $product['name'] }}"
                                     class="rt-slide {{ $i === 0 ? 'active' : '' }}"
                                     style="object-position:center center;">
                            @empty
                                <x-lucide-image class="w-12 h-12" style="color:#a78bfa;" />
                            @endforelse
                        </a>
                        @if ($disc)
                            <span class="absolute top-2 left-2 px-2 py-0.5 rounded-full text-[10px] font-bold"
                                  style="background:#ef4444;color:white;">-{{ $disc }}%</span>
                        @endif
                        @if (count($images) > 1)
                            <button type="button" class="rt-carousel-btn rt-prev" onclick="event.preventDefault();event.stopPropagation();rtCarouselNav(this,-1)">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="m15 18-6-6 6-6"/></svg>
                            </button>
                            <button type="button" class="rt-carousel-btn rt-next" onclick="event.preventDefault();event.stopPropagation();rtCarouselNav(this,1)">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="m9 18 6-6-6-6"/></svg>
                            </button>
                            <div class="rt-carousel-dots">
                                @foreach ($images as $i => $img)
                                    <span class="{{ $i === 0 ? 'active' : '' }}"></span>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <div class="p-3 flex flex-col flex-1">
                        <span class="inline-flex items-center px-1.5 py-0.5 rounded-md text-[9px] font-bold mb-1.5" style="{{ $condStyle }}">
                            {{ $product['condition_label'] ?? $cond }}
                        </span>
                        <a href="{{ route('preloved.show', $product['id']) }}"
                           class="block text-xs font-bold text-white leading-snug mb-1 hover:text-violet-300 transition-colors line-clamp-2">{{ $product['name'] }}</a>
                        <div class="flex items-center gap-1.5 mb-1">
                            @if ($origPrice && $origPrice > $price)
                                <span class="text-[10px] line-through" style="color:rgba(255,255,255,0.28);">{{ rupiah($origPrice) }}</span>
                            @endif
                            <span class="text-sm font-extrabold" style="color:#a78bfa;">{{ rupiah($price) }}</span>
                        </div>
                        <p class="text-[10px] mb-3" style="color:rgba(255,255,255,0.3);">Stok: {{ $product['stock'] }}</p>

                        {{-- Tombol aksi --}}
                        <div class="mt-auto flex gap-2">
                            <button type="button"
                                    data-quick-cart="{{ $product['id'] }}" data-type="preloved"
                                    onclick="rtQuickCart(this)"
                                    class="w-9 h-9 rounded-xl flex items-center justify-center shrink-0 transition-all hover:scale-105"
                                    style="background:rgba(124,58,237,0.12);border:1.5px solid rgba(124,58,237,0.35);color:#c4b5fd;"
                                    title="Tambah ke Keranjang">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><circle cx="8" cy="21" r="1"/><circle cx="19" cy="21" r="1"/><path d="M2.05 2.05h2l2.66 12.42a2 2 0 002 1.58h9.78a2 2 0 001.95-1.57L23 6H6"/></svg>
                            </button>
                            <a href="{{ route('preloved.show', $product['id']) }}"
                               class="flex-1 text-center py-2.5 rounded-xl text-[10px] font-bold text-white transition-all hover:opacity-90 hover:scale-[1.02] flex items-center justify-center"
                               style="background:linear-gradient(135deg,#7c3aed,#6366f1);box-shadow:0 3px 12px rgba(124,58,237,0.35);">
                                Beli Sekarang
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

<script>
/* Show real content after skeleton delay */
(function () {
    const sk = document.getElementById('rt-skeleton-grid');
    const pg = document.getElementById('rt-product-grid');
    if (!sk || !pg) return;
    setTimeout(function () {
        sk.style.transition = 'opacity .3s';
        sk.style.opacity = '0';
        setTimeout(function () { sk.remove(); pg.classList.remove('hidden'); }, 300);
    }, 450);
})();

const rtCartUrl = '{{ route("preloved.cart.add") }}';
const rtCsrf   = document.querySelector('meta[name="csrf-token"]')?.content ?? '';

function rtQuickCart(btn) {
    const pid  = btn.dataset.quickCart;
    const type = btn.dataset.type;
    btn.disabled = true;
    btn.innerHTML = '<svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path></svg>';

    fetch(rtCartUrl, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': rtCsrf },
        body: JSON.stringify({ type, product_id: pid, qty: 1 }),
    })
    .then(r => r.json())
    .then(data => {
        if (data.success || data.message) {
            btn.innerHTML = '<svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>';
            btn.style.background = 'rgba(52,211,153,0.15)';
            btn.style.borderColor = 'rgba(52,211,153,0.4)';
            btn.style.color = '#34d399';
            if (typeof rtToast === 'function') rtToast('success', 'Produk ditambahkan ke keranjang!');
        } else {
            if (typeof rtToast === 'function') rtToast('error', data.error || 'Gagal menambahkan ke keranjang.');
        }
        setTimeout(() => {
            btn.disabled = false;
            btn.innerHTML = '<svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><circle cx="8" cy="21" r="1"/><circle cx="19" cy="21" r="1"/><path d="M2.05 2.05h2l2.66 12.42a2 2 0 002 1.58h9.78a2 2 0 001.95-1.57L23 6H6"/></svg>';
            btn.style.background = 'rgba(124,58,237,0.12)';
            btn.style.borderColor = 'rgba(124,58,237,0.35)';
            btn.style.color = '#c4b5fd';
        }, 1800);
    })
    .catch(() => { btn.disabled = false; });
}
</script>
    @endif
</div>
>>>>>>> hostinger/main
@endsection
