@extends('layouts.rt')

@section('title', $product->name)

@php
    if (! function_exists('rp')) {
        function rp($n) { return 'Rp' . number_format($n, 0, ',', '.'); }
    }
@endphp

@push('styles')
<style>
.pk-main { position: relative; aspect-ratio: 1; max-height: 520px; background: var(--alt); border-radius: var(--r); overflow: hidden; display: grid; place-items: center; }
.pk-main img { position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover; }
.pk-main > svg { width: 96px; height: 96px; color: var(--muted); }
.pk-qty { display: flex; align-items: center; justify-content: space-between; gap: 12px; margin-bottom: 14px; }
.pk-btns { display: grid; gap: 10px; margin-top: 16px; }
.pk-rel { display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 12px; }
.pk-rel a { display: flex; align-items: center; gap: 12px; padding: 12px; border: 1px solid var(--line); border-radius: 16px; text-decoration: none; color: var(--text); background: var(--surface); }
.pk-rel a:hover { border-color: var(--bright); }
.pk-rel .th { width: 52px; height: 52px; border-radius: 12px; background: var(--alt); display: grid; place-items: center; overflow: hidden; flex: none; }
.pk-rel .th img { width: 100%; height: 100%; object-fit: cover; }
.pk-rel strong { display: block; font-size: 14px; line-height: 1.3; }
.pk-rel small { color: var(--tape-dark); font-weight: 700; }
.p-toast { position: fixed; left: 50%; bottom: 24px; transform: translate(-50%, 150%); z-index: 60; display: flex; align-items: center; gap: 12px; padding: 12px 12px 12px 18px; background: var(--tip-bg); color: var(--tip-ink); border-radius: 14px; font-weight: 600; font-size: 14px; transition: transform .25s; }
.p-toast.show { transform: translate(-50%, 0); }
.p-toast a { color: var(--tip-ink); font-weight: 800; }
@media (max-width: 960px) { .p-toast { bottom: 96px; } }
</style>
@endpush

@section('content')
<div class="pg">
    <nav class="crumbs" aria-label="Breadcrumb"><a href="{{ route('packing.index') }}">Toko Packing</a><span aria-hidden="true">/</span><span aria-current="page">{{ $product->name }}</span></nav>

    <div class="pg-head">
        <div>
            <h2>{{ $product->name }}</h2>
            <p>{{ $product->category }}</p>
        </div>
        <span class="badge {{ $product->stock ? 'b-ok' : 'b-mute' }}"><i></i>{{ $product->stock ? 'Stok ' . $product->stock : 'Stok habis' }}</span>
    </div>

    <div class="pg-two">
        <div class="pg">
            <div class="pk-main">
                @if ($product->primary_image)
                    <img src="{{ asset('storage/' . $product->primary_image) }}" alt="{{ $product->name }}">
                @else
                    <svg aria-hidden="true"><use href="#n-tape"/></svg>
                @endif
            </div>

            @if ($product->description)
                <section class="rt-card">
                    <div class="rt-card-h"><h3>Tentang produk</h3></div>
                    <p style="color:var(--muted)">{{ $product->description }}</p>
                </section>
            @endif

            <div class="alert a-info">
                <svg class="ic" aria-hidden="true"><use href="#i-info"/></svg>
                <div><b>Titip barang juga?</b><p>Pesan kardus di sini, lalu pilih "Packing + Anjem RuTip" di Ruang Titip. Tim Ruru membawa kardusnya sekalian.</p></div>
            </div>

            @if ($related->isNotEmpty())
                <section aria-labelledby="rel-title">
                    <div class="rt-card-h"><h3 id="rel-title">Biasanya dibeli bareng</h3></div>
                    <div class="pk-rel">
                        @foreach ($related as $r)
                            <a href="{{ route('packing.show', $r) }}">
                                <span class="th">
                                    @if ($r->primary_image)
                                        <img src="{{ asset('storage/' . $r->primary_image) }}" alt="{{ $r->name }}">
                                    @else
                                        <svg width="26" height="26" aria-hidden="true"><use href="#n-tape"/></svg>
                                    @endif
                                </span>
                                <span><strong>{{ $r->name }}</strong><small>{{ rp($r->price) }}</small></span>
                            </a>
                        @endforeach
                    </div>
                </section>
            @endif
        </div>

        <aside class="pg-aside">
            <form id="buyForm" class="rt-card" data-price="{{ $product->price }}" data-max="{{ $product->stock }}" data-id="{{ $product->id }}">
                <p class="rt-help">Harga</p>
                <p class="pc-price" style="font-size:30px;margin:2px 0 16px">{{ rp($product->price) }}@if ($product->discount)<s>{{ rp($product->original_price) }}</s>@endif</p>

                <div class="pk-qty">
                    <b>Jumlah</b>
                    <div class="qty">
                        <button type="button" id="qtyMinus" aria-label="Kurangi jumlah">&minus;</button>
                        <output id="q" aria-live="polite">1</output>
                        <button type="button" id="qtyPlus" aria-label="Tambah jumlah">+</button>
                    </div>
                </div>
                <div class="rt-rows"><div class="tot"><span>Subtotal</span><b id="sub">{{ rp($product->price) }}</b></div></div>
                <div class="pk-btns">
                    <button type="button" class="btn btn-sun wide" id="buy" {{ $product->stock ? '' : 'disabled' }}>{{ $product->stock ? 'Beli sekarang' : 'Stok habis' }}</button>
                    <button type="button" class="btn btn-ghost wide" id="add" {{ $product->stock ? '' : 'disabled' }}><svg class="ic" aria-hidden="true"><use href="#n-cart"/></svg>Masukkan keranjang</button>
                </div>
            </form>
        </aside>
    </div>
</div>

<div class="p-toast" id="toast" role="status" aria-live="polite"><span id="toast-msg">Masuk keranjang</span><a href="{{ route('preloved.cart.index') }}">Lihat keranjang</a></div>
@endsection

@push('scripts')
<script>
(function () {
    const form = document.getElementById('buyForm');
    const price = parseInt(form.dataset.price, 10);
    const max = parseInt(form.dataset.max, 10);
    const productId = form.dataset.id;
    const qOut = document.getElementById('q');
    const sub = document.getElementById('sub');
    const minus = document.getElementById('qtyMinus');
    const plus = document.getElementById('qtyPlus');
    const csrf = document.querySelector('meta[name="csrf-token"]').content;
    const cartUrl = @json(route('preloved.cart.add'));
    let qty = max ? 1 : 0;

    function render() {
        qOut.textContent = qty;
        sub.textContent = 'Rp' + (price * qty).toLocaleString('id-ID');
        minus.disabled = qty <= 1;
        plus.disabled = qty >= max;
    }
    minus.addEventListener('click', () => { qty = Math.max(1, qty - 1); render(); });
    plus.addEventListener('click', () => { qty = Math.min(max, qty + 1); render(); });
    render();

    function showToast(msg) {
        document.getElementById('toast-msg').textContent = msg;
        const el = document.getElementById('toast');
        el.classList.add('show');
        clearTimeout(el._t);
        el._t = setTimeout(() => el.classList.remove('show'), 3500);
    }

    function addToCart(buyNow) {
        return fetch(cartUrl, {
            method: 'POST',
            headers: { 'Accept': 'application/json', 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf },
            body: JSON.stringify({ type: 'packing', product_id: productId, qty, buy_now: buyNow })
        }).then(r => r.json());
    }

    document.getElementById('add').addEventListener('click', function () {
        addToCart(false).then(data => {
            showToast(data.success ? 'Produk ditambahkan ke keranjang' : (data.message || 'Gagal menambahkan.'));
        }).catch(() => showToast('Terjadi kesalahan.'));
    });

    document.getElementById('buy').addEventListener('click', function () {
        addToCart(true).then(data => {
            if (data.redirect) { window.location.href = data.redirect; }
            else { showToast(data.message || 'Gagal memproses.'); }
        }).catch(() => showToast('Terjadi kesalahan.'));
    });
})();
</script>
@endpush
