@extends('layouts.ruang-titip')
@section('title', $product['name'] . ' · Toko Preloved')

<<<<<<< HEAD
@php function rp($n){ return 'Rp'.number_format($n,0,',','.'); } @endphp
=======
@section('title', $product['name'] . ' - Toko Preloved')
>>>>>>> hostinger/main

@push('styles')
<style>
.detail{display:grid;grid-template-columns:1.05fr 1fr;gap:40px;margin-top:20px;align-items:start}
.gallery{position:sticky;top:96px}
.gallery .main{position:relative;aspect-ratio:1;background:var(--sand);border:1.5px solid var(--ink);border-radius:20px;display:grid;place-items:center;overflow:hidden}
.gallery .main img{position:absolute;inset:0;width:100%;height:100%;object-fit:cover}
.gallery .nav-btn{position:absolute;top:50%;transform:translateY(-50%);width:44px;height:44px;border-radius:50%;background:var(--paper);border:1.5px solid var(--ink);display:grid;place-items:center;cursor:pointer;padding:0}
.gallery .prev{left:14px}.gallery .next{right:14px}
.gallery .count{position:absolute;bottom:14px;right:14px;font-size:13px;font-weight:700;background:var(--ink);color:var(--cream);padding:4px 10px;border-radius:999px}
.fav{position:absolute;top:14px;right:14px;width:44px;height:44px;border-radius:50%;background:var(--paper);border:1.5px solid var(--ink);display:grid;place-items:center;cursor:pointer;padding:0}
.fav[aria-pressed="true"] svg{fill:var(--tape);stroke:var(--tape)}
.thumbs{display:flex;gap:10px;margin-top:12px}
.thumbs button{width:72px;height:72px;border-radius:12px;background:var(--sand);border:1.5px solid var(--line-strong);display:grid;place-items:center;cursor:pointer;padding:0;overflow:hidden}
.thumbs button img{width:100%;height:100%;object-fit:cover}
.thumbs button[aria-current="true"]{border-color:var(--ink);box-shadow:3px 3px 0 var(--ink)}

.p-info .badges{display:flex;gap:10px;align-items:center;flex-wrap:wrap}
.p-info .badges span{font-size:13px;font-weight:700;padding:4px 12px;border-radius:999px;background:var(--depot-light);color:#1F4535}
.p-info h1{font-size:clamp(28px,3.2vw,40px);font-weight:800;letter-spacing:-1.1px;margin-top:12px}
.p-info .price{font-family:var(--font-display);font-size:38px;font-weight:800;color:var(--tape-dark);margin-top:12px;line-height:1}
.box{margin-top:20px;padding:20px;background:var(--paper);border:1px solid var(--line-strong);border-radius:16px}
.box h2{font-family:var(--font-body);font-size:15px;font-weight:700}
.meter{display:flex;align-items:center;gap:12px;margin-top:10px}
.meter .bar{flex-grow:1;height:10px;border-radius:999px;background:var(--line);overflow:hidden}
.meter .bar i{display:block;height:100%;border-radius:999px;background:var(--depot)}
.meter b{font-family:var(--font-display);font-size:20px}
.box p{color:var(--body);margin-top:8px}
.specs{margin:12px 0 0;padding:0;list-style:none}
.specs li{display:flex;justify-content:space-between;gap:16px;padding:10px 0;border-top:1px solid var(--line);font-size:15px}
.specs li span:first-child{color:var(--muted)}
.seller{display:flex;align-items:center;gap:12px}
.seller .av{width:44px;height:44px;border-radius:50%;background:var(--sand);border:1.5px solid var(--ink);display:grid;place-items:center;font-weight:700;flex-shrink:0}
.seller div{flex-grow:1;line-height:1.3}
.seller small{color:var(--muted);font-size:13px}
.buy{margin-top:20px;padding:20px;background:var(--paper);border:1.5px solid var(--ink);border-radius:18px}
.buy .top{display:flex;justify-content:space-between;align-items:center;gap:16px;margin-bottom:16px}
.buy .top[hidden]{display:none}
.buy .btns{display:grid;grid-template-columns:1fr 1fr;gap:12px}
.buy .ask{display:flex;align-items:center;justify-content:center;gap:8px;margin-top:14px;font-weight:600;font-size:15px}
.toast{position:fixed;left:50%;bottom:24px;transform:translate(-50%,150%);z-index:50;display:flex;align-items:center;gap:12px;padding:12px 12px 12px 18px;background:var(--ink);color:var(--cream);border-radius:999px;font-weight:600;transition:transform .25s}
.toast.show{transform:translate(-50%,0)}
.toast a{color:var(--tape-light);font-weight:700;padding:8px 10px}
@media (max-width:900px){.detail{grid-template-columns:1fr}.gallery{position:static}.gallery .main{aspect-ratio:4/3}}
@media (max-width:520px){.buy .btns{grid-template-columns:1fr}}
</style>
@endpush

@section('content')
@php
  $images = !empty($product['images']) ? $product['images'] : (!empty($product['image']) ? [$product['image']] : []);
  $sellerInitial = strtoupper(substr($product['seller_name'] ?? 'A', 0, 1));
@endphp
<main class="wrap">
  <a class="back-link" href="{{ route('preloved.index') }}">
    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5M11 18l-6-6 6-6"/></svg>
    Kembali ke Toko Preloved
  </a>

<<<<<<< HEAD
  <div class="detail">
    <div class="gallery">
      <div class="main" id="main">
        @forelse ($images as $i => $img)
          <img src="{{ \Illuminate\Support\Facades\Storage::url($img) }}" alt="{{ $product['name'] }}" class="g-slide" style="{{ $i === 0 ? '' : 'display:none' }}">
        @empty
          <svg width="120" height="120" viewBox="0 0 24 24" fill="none" stroke="var(--tape-dark)" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/></svg>
        @endforelse
        <button type="button" class="fav" id="fav" aria-label="Simpan barang" aria-pressed="false">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#1C1B18" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.8 5.6a5 5 0 0 0-7.1 0L12 7.3l-1.7-1.7a5 5 0 1 0-7.1 7.1L12 21.5l8.8-8.8a5 5 0 0 0 0-7.1z"/></svg>
        </button>
        @if (count($images) > 1)
          <button type="button" class="nav-btn prev" id="g-prev" aria-label="Foto sebelumnya"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg></button>
          <button type="button" class="nav-btn next" id="g-next" aria-label="Foto berikutnya"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="m9 6 6 6-6 6"/></svg></button>
          <span class="count" id="g-count">1/{{ count($images) }}</span>
        @endif
      </div>
      @if (count($images) > 1)
        <div class="thumbs" id="thumbs">
          @foreach ($images as $i => $img)
            <button type="button" aria-current="{{ $i === 0 ? 'true' : 'false' }}" aria-label="Lihat foto {{ $i + 1 }}" data-i="{{ $i }}"><img src="{{ \Illuminate\Support\Facades\Storage::url($img) }}" alt=""></button>
          @endforeach
=======
    {{-- Kembali --}}
    <a href="{{ route('preloved.index') }}"
       class="flex items-center gap-1.5 text-sm mb-4 transition-colors hover:text-violet-300"
       style="color:rgba(255,255,255,0.4);">
        <x-lucide-chevron-left class="w-4 h-4" /> Kembali ke Toko Preloved
    </a>

    {{-- Galeri --}}
    @php $lbSrcsStr = json_encode(array_values(array_map(fn($img) => Storage::url($img), $images))); @endphp
    <div class="relative rounded-2xl mb-3 rt-carousel"
         style="aspect-ratio:1/1;background:rgba(124,58,237,0.1);cursor:zoom-in;overflow:hidden;">
        @forelse ($images as $i => $img)
            <img src="{{ Storage::url($img) }}" alt="{{ $product['name'] }}"
                 class="rt-slide {{ $i === 0 ? 'active' : '' }}"
                 onclick="rtLbOpen({{ $lbSrcsStr }}, {{ $i }})"
                 style="cursor:zoom-in;">
        @empty
            <div class="absolute inset-0 flex items-center justify-center">
                <x-lucide-image class="w-20 h-20" style="color:#a78bfa;" />
            </div>
        @endforelse
        @if ($disc)
            <div class="absolute top-3 left-3 flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold"
                 style="background:#ef4444;color:white;z-index:2;">
                -{{ $disc }}%
            </div>
        @endif
        <button type="button" id="btnWish" onclick="event.stopPropagation();toggleWishlist()"
                class="absolute top-3 right-3 w-9 h-9 rounded-full flex items-center justify-center transition-all"
                style="background:rgba(0,0,0,0.4);color:rgba(255,255,255,0.6);z-index:2;">
            <x-lucide-heart class="w-4 h-4" id="wishIcon" />
        </button>
        @if (count($images) > 1)
            <button type="button" class="rt-carousel-btn rt-prev" onclick="event.preventDefault();event.stopPropagation();rtCarouselNav(this,-1)">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="m15 18-6-6 6-6"/></svg>
            </button>
            <button type="button" class="rt-carousel-btn rt-next" onclick="event.preventDefault();event.stopPropagation();rtCarouselNav(this,1)">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="m9 18 6-6-6-6"/></svg>
            </button>
            <div class="rt-carousel-dots" style="bottom:10px;">
                @foreach ($images as $i => $img)
                    <span class="{{ $i === 0 ? 'active' : '' }}"></span>
                @endforeach
            </div>
        @endif
        {{-- zoom hint --}}
        <div class="absolute bottom-2 right-2 flex items-center gap-1 px-2 py-1 rounded-lg text-[9px] font-medium pointer-events-none"
             style="background:rgba(0,0,0,0.45);color:rgba(255,255,255,0.6);">
            <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/><line x1="11" y1="8" x2="11" y2="14"/><line x1="8" y1="11" x2="14" y2="11"/></svg>
            Klik untuk perbesar
        </div>
    </div>

    {{-- Thumbnail strip --}}
    @if (count($images) > 1)
    <div class="rt-thumb-strip mb-4" id="plThumbStrip">
        @foreach ($images as $i => $img)
            <button type="button" class="{{ $i === 0 ? 'active' : '' }}"
                    onclick="rtGalleryThumb(this, {{ $i }})">
                <img src="{{ Storage::url($img) }}" alt="">
            </button>
        @endforeach
    </div>
    <script>
    function rtGalleryThumb(btn, idx) {
        const carousel = btn.closest('.max-w-xl').querySelector('.rt-carousel');
        if (!carousel) return;
        const slides = carousel.querySelectorAll('.rt-slide');
        slides.forEach((s, i) => s.classList.toggle('active', i === idx));
        carousel.querySelectorAll('.rt-carousel-dots span').forEach((d, i) => d.classList.toggle('active', i === idx));
        btn.closest('#plThumbStrip').querySelectorAll('button').forEach((b, i) => b.classList.toggle('active', i === idx));
    }
    </script>
    @endif

    {{-- Badges --}}
    <div class="flex items-center gap-2 mb-2">
        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-semibold" style="{{ $condStyle }}">{{ $product['condition_label'] ?? $cond }}</span>
        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-semibold" style="background:rgba(99,102,241,0.2);color:#818cf8;">Stok: {{ $stock }}</span>
    </div>

    {{-- Judul --}}
    <h1 class="text-xl font-extrabold text-white font-display leading-tight mb-2">{{ $product['name'] }}</h1>

    {{-- Harga --}}
    <div class="flex items-center gap-2 mb-4 flex-wrap">
        @if ($origPrice && $origPrice > $price)
            <span class="text-sm line-through" style="color:rgba(255,255,255,0.35);">{{ rupiah($origPrice) }}</span>
        @endif
        <span class="text-2xl font-extrabold font-display" style="color:#a78bfa;">{{ rupiah($price) }}</span>
        @if ($disc)
            <span class="px-2 py-0.5 rounded-full text-xs font-bold" style="background:rgba(239,68,68,0.15);color:#f87171;">-{{ $disc }}%</span>
        @endif
    </div>

    {{-- Seller --}}
    <div class="flex items-center gap-2 mb-4">
        <div class="w-7 h-7 rounded-full flex items-center justify-center text-[11px] font-bold text-white shrink-0" style="background:linear-gradient(135deg,#7c3aed,#6366f1);">{{ $sellerInitial }}</div>
        <span class="text-sm" style="color:rgba(255,255,255,0.7);">{{ $product['seller_name'] ?? '-' }}</span>
        <span class="flex items-center gap-1 text-sm font-semibold" style="color:#fbbf24;">⭐ {{ $product['seller_rating'] ?? '4.5' }}</span>
    </div>

    {{-- Deskripsi --}}
    <div class="rounded-2xl p-4 mb-5" style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.08);">
        <p class="text-xs font-bold text-white mb-2">Deskripsi &amp; Kondisi Barang</p>
        <p class="text-sm leading-relaxed" style="color:rgba(255,255,255,0.58);">{{ $product['description'] ?? '-' }}</p>
    </div>

    {{-- Qty + subtotal --}}
    <div class="rounded-2xl p-4 mb-24" style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.08);">
        <div class="flex items-center justify-between">
            <p class="text-xs font-bold text-white">Jumlah</p>
            <div class="flex items-center gap-3">
                <button type="button" id="qtyMinus"
                        class="w-9 h-9 rounded-xl flex items-center justify-center transition-all"
                        style="background:rgba(255,255,255,0.07);color:rgba(255,255,255,0.7);">
                    <x-lucide-minus class="w-4 h-4" />
                </button>
                <span class="w-8 text-center text-lg font-extrabold text-white font-display" id="qtyDisplay">1</span>
                <button type="button" id="qtyPlus" {{ $stock <= 1 ? 'disabled' : '' }}
                        class="w-9 h-9 rounded-xl flex items-center justify-center transition-all active:scale-90"
                        style="background:rgba(124,58,237,0.2);color:#a78bfa;border:1px solid rgba(124,58,237,0.35);">
                    <x-lucide-plus class="w-4 h-4" />
                </button>
            </div>
        </div>
        <div class="flex items-center justify-between mt-3 pt-3" style="border-top:1px solid rgba(255,255,255,0.07);">
            <span class="text-xs" style="color:rgba(255,255,255,0.45);">Subtotal</span>
            <span class="text-base font-extrabold font-display" style="color:#a78bfa;" id="subtotalDisplay">{{ rupiah($price) }}</span>
>>>>>>> hostinger/main
        </div>
      @endif
    </div>

    <div class="p-info">
      <div class="badges"><span>{{ $product['condition_label'] }}</span><span>Stok: {{ $product['stock'] }}</span></div>
      <h1>{{ $product['name'] }}</h1>
      <p class="price">{{ rp($product['price']) }}</p>

      <div class="buy">
        <div class="top" id="qty-row" {{ $product['stock'] <= 1 ? 'hidden' : '' }}>
          <strong>Jumlah</strong>
          <div class="qty"><button type="button" id="minus" aria-label="Kurangi">&minus;</button><output id="q">1</output><button type="button" id="plus" aria-label="Tambah">+</button></div>
        </div>
        <div class="btns">
          <button type="button" class="btn btn-outline" id="add">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="20" r="1.4"/><circle cx="18" cy="20" r="1.4"/><path d="M3 4h2l2.4 11.2a1.5 1.5 0 0 0 1.5 1.2h8.6a1.5 1.5 0 0 0 1.5-1.2L21 8H6.2"/></svg>
            Masukkan keranjang
          </button>
          <button type="button" class="btn btn-primary" id="buy">Beli sekarang</button>
        </div>
        <a class="ask" href="https://wa.me/6285121091134?text={{ urlencode('Halo RuangTitip, saya mau tanya soal barang preloved: '.$product['name']) }}" target="_blank" rel="noopener">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12a8.5 8.5 0 0 1-12.6 7.4L3 21l1.6-5.2A8.5 8.5 0 1 1 21 12z"/></svg>
          Tanya soal barang ini
        </a>
      </div>

      <section class="box" aria-labelledby="h-cond">
        <h2 id="h-cond">Kondisi barang</h2>
        <div class="meter"><span class="bar"><i style="width:{{ $product['condition_percent'] }}%"></i></span><b>{{ $product['condition_percent'] }}%</b></div>
      </section>

      <section class="box" aria-labelledby="h-desc">
        <h2 id="h-desc">Tentang barang</h2>
        <p>{{ $product['description'] }}</p>
        <ul class="specs">
          <li><span>Kategori</span><span>{{ ucfirst($product['category']) }}</span></li>
          <li><span>Stok</span><span>{{ $product['stock'] }} barang</span></li>
          <li><span>Pengambilan</span><span>Ambil di gudang atau dikirim</span></li>
        </ul>
      </section>

      <section class="box seller" aria-label="Penjual">
        <span class="av">{{ $sellerInitial }}</span>
        <div><strong>{{ $product['seller_name'] }}</strong><br><small>Penjual · barangnya dititip di gudang RuangTitip</small></div>
      </section>
    </div>
  </div>
</main>

<div class="toast" id="toast" role="status" aria-live="polite"><span id="toast-msg"></span><a href="{{ route('preloved.cart.index') }}">Lihat keranjang</a></div>

@push('scripts')
<script>
(function () {
    var harga = {{ $product['price'] }};
    var stok = {{ $product['stock'] }};
    var productId = {{ $product['id'] }};
    var csrf = document.querySelector('meta[name="csrf-token"]').content;
    var cartUrl = '{{ route('preloved.cart.add') }}';
    var qty = 1;

    var qtyOut = document.getElementById('q');
    var minus = document.getElementById('minus');
    var plus = document.getElementById('plus');
    if (minus && plus) {
        function renderQty() {
            qtyOut.textContent = qty;
            minus.disabled = qty <= 1;
            plus.disabled = qty >= stok;
        }
        minus.addEventListener('click', function () { qty = Math.max(1, qty - 1); renderQty(); });
        plus.addEventListener('click', function () { qty = Math.min(stok, qty + 1); renderQty(); });
        renderQty();
    }

    function showToast(msg) {
        document.getElementById('toast-msg').textContent = msg;
        var el = document.getElementById('toast');
        el.classList.add('show');
        clearTimeout(el._t);
        el._t = setTimeout(function () { el.classList.remove('show'); }, 3500);
    }

    function addToCart(buyNow) {
        return fetch(cartUrl, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf },
            body: JSON.stringify({ type: 'preloved', product_id: productId, qty: qty, buy_now: buyNow }),
        }).then(function (r) { return r.json(); });
    }

    document.getElementById('add').addEventListener('click', function () {
        addToCart(false).then(function (data) {
            showToast(data.success ? 'Produk ditambahkan ke keranjang' : (data.message || 'Gagal menambahkan.'));
        }).catch(function () { showToast('Terjadi kesalahan.'); });
    });

    document.getElementById('buy').addEventListener('click', function () {
        addToCart(true).then(function (data) {
            if (data.redirect) { window.location.href = data.redirect; }
            else { showToast(data.message || 'Gagal memproses.'); }
        }).catch(function () { showToast('Terjadi kesalahan.'); });
    });

    // Galeri
    var slides = document.querySelectorAll('.g-slide');
    var thumbs = document.querySelectorAll('#thumbs button');
    var countEl = document.getElementById('g-count');
    var idx = 0;
    function draw() {
        slides.forEach(function (s, i) { s.style.display = i === idx ? '' : 'none'; });
        thumbs.forEach(function (b, i) { b.setAttribute('aria-current', i === idx ? 'true' : 'false'); });
        if (countEl) countEl.textContent = (idx + 1) + '/' + slides.length;
    }
    thumbs.forEach(function (b) { b.addEventListener('click', function () { idx = parseInt(b.dataset.i, 10); draw(); }); });
    var prevBtn = document.getElementById('g-prev'), nextBtn = document.getElementById('g-next');
    if (prevBtn) prevBtn.addEventListener('click', function () { idx = (idx + slides.length - 1) % slides.length; draw(); });
    if (nextBtn) nextBtn.addEventListener('click', function () { idx = (idx + 1) % slides.length; draw(); });

    document.getElementById('fav').addEventListener('click', function () {
        var on = this.getAttribute('aria-pressed') === 'true';
        this.setAttribute('aria-pressed', on ? 'false' : 'true');
    });
})();
</script>
@endpush
@endsection
