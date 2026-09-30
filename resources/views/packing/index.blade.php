<<<<<<< HEAD
@extends('layouts.ruang-titip')
=======
﻿@extends('layouts.dashboard')

>>>>>>> hostinger/main
@section('title', 'Toko Packing')

@php function rp($n){ return 'Rp'.number_format($n,0,',','.'); } @endphp

@push('styles')
<style>
.page-head{padding:40px 0 24px;display:grid;grid-template-columns:1fr auto;gap:24px;align-items:end}
.page-head h1{font-size:clamp(34px,4vw,48px);font-weight:800;letter-spacing:-1.4px}
.page-head p{color:var(--body);margin-top:6px;max-width:560px}
.tabs{display:flex;gap:8px;flex-wrap:wrap;margin-bottom:24px}
.tabs a{display:inline-flex;min-height:42px;align-items:center;padding:0 18px;border-radius:999px;border:1.5px solid var(--line-strong);background:var(--paper);font-weight:700;font-size:15px;text-decoration:none;color:var(--ink)}
.tabs a:hover{border-color:var(--ink)}
.tabs a.on{background:var(--ink);border-color:var(--ink);color:var(--cream)}

.prod-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:18px}
.prod{display:flex;flex-direction:column;background:var(--paper);border:1px solid var(--line-strong);border-radius:16px;overflow:hidden;transition:border-color .15s,transform .15s}
.prod:hover{border-color:var(--ink);transform:translateY(-2px)}
.prod .thumb{position:relative;display:grid;place-items:center;aspect-ratio:4/3;background:var(--sand);text-decoration:none;overflow:hidden}
.prod .thumb img{position:absolute;inset:0;width:100%;height:100%;object-fit:cover}
.prod .thumb .disc{position:absolute;top:10px;left:10px;font-size:11px;font-weight:700;padding:4px 9px;border-radius:999px;background:var(--tape);color:#fff}
.prod .body{padding:14px 16px 16px;display:flex;flex-direction:column;gap:2px;flex-grow:1}
.prod h2{font-family:var(--font-body);font-size:16px;font-weight:700}
.prod h2 a{color:var(--ink);text-decoration:none}
.prod h2 a:hover{color:var(--tape-dark)}
.prod .stock{font-size:13px;color:var(--muted)}
.prod .row{margin-top:auto;padding-top:12px;display:flex;justify-content:space-between;align-items:center;gap:8px;min-height:52px}
.prod .price{font-family:var(--font-display);font-size:20px;font-weight:800;color:var(--tape-dark)}
.prod .price s{display:block;font-family:var(--font-body);font-size:12px;font-weight:500;color:var(--muted)}
.prod .add{min-height:40px;padding:0 14px;border-radius:999px;border:1.5px solid var(--ink);background:var(--paper);font-weight:700;font-size:14px;cursor:pointer;display:flex;align-items:center;gap:6px;color:var(--ink)}
.prod .add:hover{background:var(--tape);color:#fff;border-color:var(--tape)}
.prod .add:disabled{border-color:var(--line-strong);color:var(--muted);background:var(--cream);cursor:not-allowed}

.cartbar{position:fixed;left:50%;bottom:20px;transform:translate(-50%,140%);z-index:35;width:min(720px,calc(100% - 32px));display:flex;align-items:center;gap:16px;padding:12px 12px 12px 20px;background:var(--ink);color:var(--cream);border-radius:999px;transition:transform .25s ease}
.cartbar.show{transform:translate(-50%,0)}
.cartbar .info{flex-grow:1;line-height:1.3}
.cartbar .info small{display:block;color:#C9C2B3;font-size:13px}
.cartbar .info strong{font-family:var(--font-display);font-size:20px}
.cartbar .btn{background:var(--tape-light);color:var(--ink);border-color:var(--tape-light);box-shadow:none}
.cartbar .btn:hover{background:#F0B98F;color:var(--ink)}
body.has-cart-items{padding-bottom:100px}

.help{margin-bottom:28px}
.tabs{margin-top:0}

@media (max-width:1080px){.prod-grid{grid-template-columns:repeat(3,1fr)}}
@media (max-width:760px){
  .prod-grid{grid-template-columns:repeat(2,1fr);gap:12px}
  .page-head{grid-template-columns:1fr}
  .help{flex-direction:column;align-items:flex-start}
  .prod .row{flex-direction:column;align-items:stretch}
  .prod .add{justify-content:center}
}
</style>
@endpush

@section('content')
<<<<<<< HEAD
<main class="wrap">
  <div class="page-head">
    <div>
      <h1>Toko Packing</h1>
      <p>Kardus, lakban, dan pelindung barang. Ambil sendiri di gudang atau dikirim ke kosmu.</p>
=======
<div class="pt-6 pb-10">

    {{-- Heading --}}
    <div class="mb-6">
        <h1 class="text-xl font-extrabold text-white font-display">Toko Packing</h1>
        <p class="text-xs mt-0.5" style="color:rgba(255,255,255,0.4);">Material packing berkualitas untuk barangmu</p>
>>>>>>> hostinger/main
    </div>
  </div>

  <aside class="help">
    <img src="{{ asset('assets/ruru.webp') }}" alt="" width="64" height="75">
    <div>
      <strong>Baru pertama kali packing?</strong>
      <p>Mulai dari kardus dan lakban, tim kami bantu hitung kebutuhanmu lewat WhatsApp kalau bingung.</p>
    </div>
    <a class="btn btn-outline btn-sm" href="https://wa.me/6285121091134" target="_blank" rel="noopener">Tanya dulu</a>
  </aside>

<<<<<<< HEAD
  <div class="tabs" role="group" aria-label="Filter kategori">
    @foreach ($categories as $cat)
      <a href="{{ route('packing.index', $cat === 'Semua' ? [] : ['kategori' => $cat]) }}" class="{{ $cat === $category ? 'on' : '' }}" @if($cat === $category) aria-current="true" @endif>{{ $cat }}</a>
    @endforeach
  </div>

  @if ($products->isEmpty())
    <div class="wh" style="cursor:default;padding:48px;text-align:center;">
      <p style="font-weight:700;font-size:18px;margin-bottom:6px">Belum ada produk</p>
      <p style="color:var(--muted)">Produk packing akan segera tersedia di kategori ini.</p>
    </div>
  @else
    <div class="prod-grid" id="grid">
      @foreach ($products as $p)
        @php $qty = (int) (session('cart', [])['packing_'.$p->id]['qty'] ?? 0); @endphp
        <article class="prod" data-id="{{ $p->id }}" data-stock="{{ $p->stock }}" data-qty="{{ $qty }}" data-price="{{ $p->price }}">
          <a class="thumb" href="{{ route('packing.show', $p) }}" aria-label="Detail {{ $p->name }}">
            @if ($p->discount)<span class="disc">-{{ $p->discount }}%</span>@endif
            @if ($p->primary_image)
              <img src="{{ asset('storage/'.$p->primary_image) }}" alt="{{ $p->name }}">
            @else
              <svg width="44" height="44" viewBox="0 0 24 24" fill="none" stroke="var(--tape-dark)" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M21 8 12 3 3 8v8l9 5 9-5V8z"/><path d="M3 8l9 5 9-5"/><path d="M12 13v8"/></svg>
            @endif
          </a>
          <div class="body">
            <h2><a href="{{ route('packing.show', $p) }}">{{ $p->name }}</a></h2>
            <span class="stock">Stok: {{ $p->stock }}</span>
            <div class="row">
              <span class="price">
                {{ rp($p->price) }}
                @if ($p->discount)<s>{{ rp($p->original_price) }}</s>@endif
              </span>
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
</main>

<div class="cartbar" id="cartbar" aria-live="polite">
  <div class="info"><small id="cb-count">0 barang di keranjang</small><strong id="cb-total">Rp0</strong></div>
  <a class="btn" href="{{ route('preloved.cart.index') }}">Lanjut bayar</a>
=======
    {{-- Grid produk --}}
    @if ($products->isEmpty())
        <div class="rounded-2xl py-16 flex flex-col items-center text-center" style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.09);">
            <div class="relative inline-flex mx-auto mb-5">
                <div class="absolute inset-0 rounded-3xl blur-xl opacity-25" style="background:linear-gradient(135deg,#f97316,#fb923c);"></div>
                <div class="relative w-20 h-20 rounded-3xl flex items-center justify-center" style="background:linear-gradient(135deg,rgba(249,115,22,0.2),rgba(251,146,60,0.1));border:1px solid rgba(249,115,22,0.35);">
                    <svg class="w-9 h-9" fill="none" stroke="#fb923c" stroke-width="1.5" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 8L12 3 3 8v8l9 5 9-5V8z"/><path d="M3 8l9 5 9-5"/><path d="M12 13v8"/>
                    </svg>
                </div>
            </div>
            <p class="text-sm font-bold text-white mb-1">Belum ada produk</p>
            <p class="text-xs" style="color:rgba(255,255,255,0.4);">Produk packing akan segera tersedia</p>
        </div>
    @else
        {{-- Skeleton loading cards (hidden by JS after load) --}}
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
            @foreach ($products as $p)
                @php
                    $images = !empty($p->images) ? $p->images : ($p->primary_image ? [$p->primary_image] : []);
                @endphp
                <div class="rt-tilt rounded-2xl overflow-hidden flex flex-col"
                     style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.09);">
                    {{-- Gambar (rasio 1:1) --}}
                    <div class="relative rt-carousel" style="aspect-ratio:1/1;background:rgba(124,58,237,0.08);">
                        <a href="{{ route('packing.show', $p) }}" class="block h-full w-full flex items-center justify-center relative">
                            @forelse ($images as $i => $img)
                                <img src="{{ asset('storage/'.$img) }}" alt="{{ $p->name }}"
                                     class="rt-slide {{ $i === 0 ? 'active' : '' }}"
                                     style="object-position:center center;">
                            @empty
                                <x-lucide-package class="w-12 h-12" style="color:#a78bfa;" />
                            @endforelse
                        </a>
                        @if ($p->discount)
                            <span class="absolute top-2 left-2 px-2 py-0.5 rounded-full text-[10px] font-bold"
                                  style="background:#ef4444;color:white;">-{{ $p->discount }}%</span>
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

                    {{-- Info --}}
                    <div class="p-3 flex flex-col flex-1">
                        <a href="{{ route('packing.show', $p) }}"
                           class="block text-xs font-bold text-white leading-snug mb-1 hover:text-violet-300 transition-colors line-clamp-2">{{ $p->name }}</a>
                        <div class="flex items-center gap-1.5 mb-1">
                            @if ($p->discount)
                                <span class="text-[10px] line-through" style="color:rgba(255,255,255,0.28);">{{ rupiah($p->original_price) }}</span>
                            @endif
                            <span class="text-sm font-extrabold" style="color:#a78bfa;">{{ rupiah($p->price) }}</span>
                        </div>
                        <p class="text-[10px] mb-3" style="color:rgba(255,255,255,0.3);">Stok: {{ $p->stock }}</p>

                        {{-- Tombol aksi --}}
                        <div class="mt-auto flex gap-2">
                            <button type="button"
                                    data-quick-cart="{{ $p->id }}" data-type="packing"
                                    onclick="rtQuickCart(this)"
                                    class="w-9 h-9 rounded-xl flex items-center justify-center shrink-0 transition-all hover:scale-105"
                                    style="background:rgba(124,58,237,0.12);border:1.5px solid rgba(124,58,237,0.35);color:#c4b5fd;"
                                    title="Tambah ke Keranjang">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><circle cx="8" cy="21" r="1"/><circle cx="19" cy="21" r="1"/><path d="M2.05 2.05h2l2.66 12.42a2 2 0 002 1.58h9.78a2 2 0 001.95-1.57L23 6H6"/></svg>
                            </button>
                            <a href="{{ route('packing.show', $p) }}"
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
/* Show real content after short skeleton delay */
(function () {
    const sk = document.getElementById('rt-skeleton-grid');
    const pg = document.getElementById('rt-product-grid');
    if (!sk || !pg) return;
    setTimeout(function () {
        sk.style.transition = 'opacity .3s';
        sk.style.opacity = '0';
        setTimeout(function () {
            sk.remove();
            pg.classList.remove('hidden');
        }, 300);
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
>>>>>>> hostinger/main
</div>

@push('scripts')
<script>
(function () {
    const csrf = document.querySelector('meta[name="csrf-token"]').content;
    const addUrl = '{{ route('preloved.cart.add') }}';
    const updateUrl = '{{ route('preloved.cart.update') }}';

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
        const bar = document.getElementById('cartbar');
        bar.classList.toggle('show', count > 0);
        document.body.classList.toggle('has-cart-items', count > 0);
        document.getElementById('cb-count').textContent = count + ' barang di keranjang';
        document.getElementById('cb-total').textContent = 'Rp' + total.toLocaleString('id-ID');
    }

    function sync(id, qty) {
        if (qty === 0) {
            fetch('{{ route('preloved.cart.remove') }}', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf },
                body: JSON.stringify({ product_id: 'packing_' + id }),
            }).then(() => { delete cartTotals[id]; refreshBar(); });
            return;
        }
        const isNew = !(id in cartTotals);
        const req = isNew
            ? fetch(addUrl, { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf }, body: JSON.stringify({ type: 'packing', product_id: id, qty }) })
            : fetch(updateUrl, { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf }, body: JSON.stringify({ product_id: 'packing_' + id, qty }) });
        req.then(r => r.json()).then(data => {
            if (data.success === false) return;
            refreshBar();
        });
    }

    document.querySelectorAll('.prod').forEach(function (card) {
        const id = card.dataset.id;
        const qty = parseInt(card.dataset.qty, 10) || 0;
        const price = parseInt(card.dataset.price, 10) || 0;
        if (qty > 0) cartTotals[id] = { qty, price };
        wire(card);
    });
    refreshBar();
})();
</script>
@endpush
@endsection
