@extends('layouts.rt')

@section('title', 'Toko Packing')

@php
    if (! function_exists('rp')) {
        function rp($n) { return 'Rp' . number_format($n, 0, ',', '.'); }
    }
@endphp

@push('styles')
<style>
.pc .add { display: inline-flex; align-items: center; gap: 6px; min-height: 38px; padding: 0 14px; border-radius: 999px; border: 1.5px solid var(--line); background: var(--surface); font-weight: 700; font-size: 13.5px; }
.pc .add:hover:not(:disabled) { background: var(--sun); border-color: var(--sun); color: var(--deep); }
.pc .add:disabled { opacity: .45; cursor: not-allowed; }
.pc .stock { font-size: 13px; color: var(--muted); }
.pc .row { display: flex; align-items: center; justify-content: space-between; gap: 8px; margin-top: 8px; }
.cartbar { position: fixed; left: 50%; bottom: 20px; transform: translate(-50%, 140%); z-index: 35; width: min(720px, calc(100% - 32px)); display: flex; align-items: center; gap: 16px; padding: 12px 12px 12px 22px; background: var(--deep); color: #fff; border: 1px solid var(--line); border-radius: 999px; transition: transform .25s ease; }
.cartbar.show { transform: translate(-50%, 0); }
.cartbar .info { flex: 1; line-height: 1.3; }
.cartbar .info small { display: block; color: var(--muted); font-size: 13px; }
.cartbar .info strong { font-family: var(--display); font-size: 20px; }
.cartbar .btn { background: var(--sun); color: var(--deep); }
@media (max-width: 960px) { .cartbar { bottom: calc(16px + env(safe-area-inset-bottom)); } }
@media (max-width: 560px) { .cartbar { flex-wrap: wrap; gap: 10px; } .cartbar .info { flex: 1 1 100%; } .cartbar .btn { flex: 1 1 auto; } }
</style>
@endpush

@section('content')
<div class="pg">
    <div class="pg-head">
        <div>
            <h2>Perlengkapan packing</h2>
            <p>Kardus, lakban, dan pelindung barang. Ambil sendiri di gudang atau dikirim ke kosmu.</p>
        </div>
    </div>

    <section class="rt-card" style="display:flex;align-items:center;gap:20px;flex-wrap:wrap">
        <img src="{{ asset('assets/ruru/ruru-troli.webp') }}" alt="" width="64" height="64" style="width:64px;height:auto">
        <div style="flex:1;min-width:220px">
            <h3 style="font-size:18px">Baru pertama kali packing?</h3>
            <p class="rt-help" style="margin-top:4px">Mulai dari kardus dan lakban. Tim kami bantu hitung kebutuhanmu lewat WhatsApp kalau bingung.</p>
        </div>
        <a class="btn btn-ghost sm" href="https://wa.me/6285121091134" target="_blank" rel="noopener">Tanya dulu</a>
    </section>

    <div class="pg-filters" role="group" aria-label="Filter kategori">
        @foreach ($categories as $cat)
            <a class="chip" aria-pressed="{{ $cat === $category ? 'true' : 'false' }}" href="{{ route('packing.index', $cat === 'Semua' ? [] : ['kategori' => $cat]) }}">{{ $cat }}</a>
        @endforeach
    </div>

    @if ($products->isEmpty())
        <section class="rt-card pg-empty">
            <svg viewBox="0 0 44 40" width="90" height="82" aria-hidden="true"><use href="#box"/></svg>
            <h3>Belum ada produk</h3>
            <p>Produk packing akan segera tersedia di kategori ini.</p>
        </section>
    @else
        <div class="pc-grid" id="grid">
            @foreach ($products as $p)
                @php $qty = (int) (session('cart', [])['packing_' . $p->id]['qty'] ?? 0); @endphp
                <article class="pc prod" data-id="{{ $p->id }}" data-stock="{{ $p->stock }}" data-qty="{{ $qty }}" data-price="{{ $p->price }}">
                    <a class="pc-pic" href="{{ route('packing.show', $p) }}" aria-label="Detail {{ $p->name }}">
                        @if ($p->discount)<span class="badge b-warn">-{{ $p->discount }}%</span>@endif
                        @if ($p->primary_image)
                            <img src="{{ asset('storage/' . $p->primary_image) }}" alt="{{ $p->name }}">
                        @else
                            <svg aria-hidden="true"><use href="#n-tape"/></svg>
                        @endif
                    </a>
                    <div class="pc-body">
                        <h3><a href="{{ route('packing.show', $p) }}">{{ $p->name }}</a></h3>
                        <span class="stock">Stok: {{ $p->stock }}</span>
                        <div class="row">
                            <span class="pc-price">{{ rp($p->price) }}@if ($p->discount)<s>{{ rp($p->original_price) }}</s>@endif</span>
                            @if (! $p->stock)
                                <button class="add" type="button" disabled>Habis</button>
                            @else
                                <div class="cart-ctrl"></div>
                            @endif
                        </div>
                    </div>
                </article>
            @endforeach
        </div>
    @endif
</div>

<div class="cartbar" id="cartbar" aria-live="polite">
    <div class="info"><small id="cb-count">0 barang di keranjang</small><strong id="cb-total">Rp0</strong></div>
    <a class="btn" href="{{ route('preloved.cart.index') }}">Lanjut bayar</a>
</div>
@endsection

@push('scripts')
<script>
(function () {
    const csrf = document.querySelector('meta[name="csrf-token"]').content;
    const addUrl = @json(route('preloved.cart.add'));
    const updateUrl = @json(route('preloved.cart.update'));
    const removeUrl = @json(route('preloved.cart.remove'));

    function qtyControl(qty, max) {
        return qty > 0
            ? '<div class="qty"><button type="button" data-d="-1" aria-label="Kurangi">&minus;</button><output>' + qty + '</output><button type="button" data-d="1" aria-label="Tambah"' + (qty >= max ? ' disabled' : '') + '>+</button></div>'
            : '<button class="add" type="button" data-d="1"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"><path d="M12 5v14M5 12h14"/></svg>Keranjang</button>';
    }

    function wire(card) {
        const ctrl = card.querySelector('.cart-ctrl');
        if (!ctrl) return;
        const id = card.dataset.id;
        let qty = parseInt(card.dataset.qty, 10) || 0;
        const max = parseInt(card.dataset.stock, 10) || 0;

        function draw() {
            ctrl.innerHTML = qtyControl(qty, max);
            ctrl.querySelectorAll('[data-d]').forEach(function (b) {
                b.addEventListener('click', function () {
                    const delta = parseInt(b.dataset.d, 10);
                    const newQty = Math.max(0, Math.min(max, qty + delta));
                    if (newQty === qty) return;
                    qty = newQty;
                    card.dataset.qty = qty;
                    cartTotals[id] = { qty: qty, price: parseInt(card.dataset.price, 10) || 0 };
                    if (qty === 0) delete cartTotals[id];
                    draw();
                    sync(id, qty);
                });
            });
        }
        draw();
    }

    let cartTotals = {};
    function refreshBar() {
        const count = Object.values(cartTotals).reduce((a, v) => a + v.qty, 0);
        const total = Object.values(cartTotals).reduce((a, v) => a + v.qty * v.price, 0);
        document.getElementById('cartbar').classList.toggle('show', count > 0);
        document.getElementById('cb-count').textContent = count + ' barang di keranjang';
        document.getElementById('cb-total').textContent = 'Rp' + total.toLocaleString('id-ID');
    }

    let known = {};
    function sync(id, qty) {
        refreshBar();
        if (qty === 0) {
            delete known[id];
            fetch(removeUrl, {
                method: 'POST',
                headers: { 'Accept': 'application/json', 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf },
                body: JSON.stringify({ product_id: 'packing_' + id })
            });
            return;
        }
        const isNew = !(id in known);
        known[id] = true;
        const req = isNew
            ? fetch(addUrl, { method: 'POST', headers: { 'Accept': 'application/json', 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf }, body: JSON.stringify({ type: 'packing', product_id: id, qty: qty }) })
            : fetch(updateUrl, { method: 'POST', headers: { 'Accept': 'application/json', 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf }, body: JSON.stringify({ product_id: 'packing_' + id, qty: qty }) });
        req.then(r => r.json()).catch(function () {});
    }

    document.querySelectorAll('.prod').forEach(function (card) {
        const id = card.dataset.id;
        const qty = parseInt(card.dataset.qty, 10) || 0;
        const price = parseInt(card.dataset.price, 10) || 0;
        if (qty > 0) { cartTotals[id] = { qty: qty, price: price }; known[id] = true; }
        wire(card);
    });
    refreshBar();
})();
</script>
@endpush
