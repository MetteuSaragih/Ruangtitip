@extends('layouts.rt')

@section('title', $product['name'])

@php
    if (! function_exists('rp')) {
        function rp($n) { return 'Rp' . number_format($n, 0, ',', '.'); }
    }
    $images = ! empty($product['images']) ? $product['images'] : (! empty($product['image']) ? [$product['image']] : []);
    $sellerInitial = strtoupper(substr($product['seller_name'] ?? 'A', 0, 1));
@endphp

@push('styles')
<style>
.gal-main { position: relative; aspect-ratio: 4 / 3; background: var(--alt); border-radius: var(--r); overflow: hidden; display: grid; place-items: center; }
.gal-main .g-slide { position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover; }
.gal-main > svg { width: 96px; height: 96px; color: var(--muted); }
.gal-btn { position: absolute; top: 50%; transform: translateY(-50%); width: 40px; height: 40px; border-radius: 50%; border: 0; background: rgba(12,43,42,.78); color: #fff; display: grid; place-items: center; }
.gal-btn.prev { left: 12px; } .gal-btn.next { right: 12px; }
.gal-count { position: absolute; bottom: 14px; right: 14px; font-size: 13px; font-weight: 700; background: var(--tip-bg); color: var(--tip-ink); padding: 4px 10px; border-radius: 999px; }
.gal-fav { position: absolute; top: 14px; right: 14px; width: 42px; height: 42px; border-radius: 50%; border: 0; background: rgba(12,43,42,.78); color: #fff; display: grid; place-items: center; }
.gal-fav[aria-pressed="true"] { background: var(--sun); color: var(--deep); }
.gal-thumbs { display: flex; gap: 8px; flex-wrap: wrap; margin-top: 10px; }
.gal-thumbs button { width: 72px; height: 72px; border-radius: 14px; border: 2px solid transparent; padding: 0; overflow: hidden; background: var(--alt); }
.gal-thumbs button[aria-current="true"] { border-color: var(--sun); }
.gal-thumbs img { width: 100%; height: 100%; object-fit: cover; }
.buy-qty { display: flex; align-items: center; justify-content: space-between; gap: 12px; margin-bottom: 14px; }
.buy-btns { display: grid; gap: 10px; }
.meter { display: flex; align-items: center; gap: 12px; }
.meter .bar { flex: 1; margin: 0; }
.seller { display: flex; align-items: center; gap: 14px; }
.p-toast { position: fixed; left: 50%; bottom: 24px; transform: translate(-50%, 150%); z-index: 60; display: flex; align-items: center; gap: 12px; padding: 12px 12px 12px 18px; background: var(--tip-bg); color: var(--tip-ink); border-radius: 14px; font-weight: 600; font-size: 14px; transition: transform .25s; }
.p-toast.show { transform: translate(-50%, 0); }
.p-toast a { color: var(--tip-ink); font-weight: 800; }
@media (max-width: 960px) { .p-toast { bottom: 96px; } }
</style>
@endpush

@section('content')
<div class="pg">
    <nav class="crumbs" aria-label="Breadcrumb"><a href="{{ route('preloved.index') }}">Toko Preloved</a><span aria-hidden="true">/</span><span aria-current="page">{{ $product['name'] }}</span></nav>

    <div class="pg-head">
        <div>
            <h2>{{ $product['name'] }}</h2>
            <p>{{ ucfirst($product['category']) }}. Disimpan di gudang RuangTitip.</p>
        </div>
        <div class="pg-acts">
            <span class="badge b-ok"><i></i>{{ $product['condition_label'] }}</span>
            <span class="badge b-mute"><i></i>Stok {{ $product['stock'] }}</span>
        </div>
    </div>

    <div class="pg-two">
        <div class="pg">
            <div>
                <div class="gal-main" id="main">
                    @forelse ($images as $i => $img)
                        <img src="{{ \Illuminate\Support\Facades\Storage::url($img) }}" alt="{{ $product['name'] }}" class="g-slide" style="{{ $i === 0 ? '' : 'display:none' }}">
                    @empty
                        <svg aria-hidden="true"><use href="#n-tag"/></svg>
                    @endforelse
                    <button type="button" class="gal-fav" id="fav" aria-label="Simpan barang" aria-pressed="false">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20.8 5.6a5 5 0 0 0-7.1 0L12 7.3l-1.7-1.7a5 5 0 1 0-7.1 7.1L12 21.5l8.8-8.8a5 5 0 0 0 0-7.1z"/></svg>
                    </button>
                    @if (count($images) > 1)
                        <button type="button" class="gal-btn prev" id="g-prev" aria-label="Foto sebelumnya"><svg width="18" height="18" aria-hidden="true"><use href="#i-back"/></svg></button>
                        <button type="button" class="gal-btn next" id="g-next" aria-label="Foto berikutnya"><svg width="18" height="18" aria-hidden="true"><use href="#i-chev"/></svg></button>
                        <span class="gal-count" id="g-count">1/{{ count($images) }}</span>
                    @endif
                </div>
                @if (count($images) > 1)
                    <div class="gal-thumbs" id="thumbs">
                        @foreach ($images as $i => $img)
                            <button type="button" aria-current="{{ $i === 0 ? 'true' : 'false' }}" aria-label="Lihat foto {{ $i + 1 }}" data-i="{{ $i }}"><img src="{{ \Illuminate\Support\Facades\Storage::url($img) }}" alt=""></button>
                        @endforeach
                    </div>
                @endif
            </div>

            <section class="rt-card" aria-labelledby="h-cond">
                <div class="rt-card-h"><h3 id="h-cond">Kondisi barang</h3></div>
                <div class="meter"><span class="bar"><i style="width:{{ $product['condition_percent'] }}%"></i></span><b>{{ $product['condition_percent'] }}%</b></div>
            </section>

            <section class="rt-card" aria-labelledby="h-desc">
                <div class="rt-card-h"><h3 id="h-desc">Tentang barang</h3></div>
                <p style="color:var(--muted)">{{ $product['description'] }}</p>
                <div class="rt-rows" style="margin-top:14px">
                    <div><span>Kategori</span><b>{{ ucfirst($product['category']) }}</b></div>
                    <div><span>Stok</span><b>{{ $product['stock'] }} barang</b></div>
                    <div><span>Pengambilan</span><b>Ambil di gudang atau dikirim</b></div>
                </div>
            </section>

            <section class="rt-card seller" aria-label="Penjual">
                <span class="av">{{ $sellerInitial }}</span>
                <div><b>{{ $product['seller_name'] }}</b><br><small class="rt-help">Penjual. Barangnya dititip di gudang RuangTitip.</small></div>
            </section>
        </div>

        <aside class="pg-aside">
            <section class="rt-card">
                <p class="rt-help">Harga</p>
                <p style="font-family:var(--display);font-size:32px;font-weight:700;line-height:1.1;margin:2px 0 16px">{{ rp($product['price']) }}</p>

                <div class="buy-qty" id="qty-row" {{ $product['stock'] <= 1 ? 'hidden' : '' }}>
                    <b>Jumlah</b>
                    <div class="qty"><button type="button" id="minus" aria-label="Kurangi">&minus;</button><output id="q">1</output><button type="button" id="plus" aria-label="Tambah">+</button></div>
                </div>
                <div class="buy-btns">
                    <button type="button" class="btn btn-sun wide" id="buy">Beli sekarang</button>
                    <button type="button" class="btn btn-ghost wide" id="add"><svg class="ic" aria-hidden="true"><use href="#n-cart"/></svg>Masukkan keranjang</button>
                    <a class="btn btn-link" style="justify-content:center" href="https://wa.me/6285121091134?text={{ urlencode('Halo RuangTitip, saya mau tanya soal barang preloved: ' . $product['name']) }}" target="_blank" rel="noopener">Tanya soal barang ini</a>
                </div>
            </section>
        </aside>
    </div>
</div>

<div class="p-toast" id="toast" role="status" aria-live="polite"><span id="toast-msg"></span><a href="{{ route('preloved.cart.index') }}">Lihat keranjang</a></div>
@endsection

@push('scripts')
<script>
(function () {
    var stok = {{ $product['stock'] }};
    var productId = {{ $product['id'] }};
    var csrf = document.querySelector('meta[name="csrf-token"]').content;
    var cartUrl = @json(route('preloved.cart.add'));
    var qty = 1;

    var qtyOut = document.getElementById('q');
    var minus = document.getElementById('minus');
    var plus = document.getElementById('plus');
    function renderQty() {
        qtyOut.textContent = qty;
        minus.disabled = qty <= 1;
        plus.disabled = qty >= stok;
    }
    minus.addEventListener('click', function () { qty = Math.max(1, qty - 1); renderQty(); });
    plus.addEventListener('click', function () { qty = Math.min(stok, qty + 1); renderQty(); });
    renderQty();

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
            body: JSON.stringify({ type: 'preloved', product_id: productId, qty: qty, buy_now: buyNow })
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
