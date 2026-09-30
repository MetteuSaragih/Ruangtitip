@extends('layouts.ruang-titip')
@section('title', $product['name'] . ' · Toko Preloved')

@php function rp($n){ return 'Rp'.number_format($n,0,',','.'); } @endphp

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
