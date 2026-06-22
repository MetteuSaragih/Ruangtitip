@extends('layouts.dashboard')

@section('title', $product->name)

@php
    if (! function_exists('rupiah')) {
        function rupiah($n) { return 'Rp ' . number_format($n, 0, ',', '.'); }
    }
@endphp

@section('content')
<div class="py-4 pb-32 max-w-xl mx-auto">

    {{-- Kembali --}}
    <a href="{{ route('packing.index') }}"
       class="flex items-center gap-1.5 text-sm mb-4 transition-colors hover:text-violet-300"
       style="color:rgba(255,255,255,0.4);">
        <x-lucide-chevron-left class="w-4 h-4" /> Kembali
    </a>

    {{-- Galeri --}}
    @php
        $images = !empty($product->images) ? $product->images : ($product->primary_image ? [$product->primary_image] : []);
    @endphp
    <div class="relative rounded-2xl overflow-hidden mb-5 flex items-center justify-center rt-carousel"
         style="height:220px;background:rgba(124,58,237,0.1);">
        @forelse ($images as $i => $img)
            <img src="{{ asset('storage/'.$img) }}" alt="{{ $product->name }}" class="rt-slide {{ $i === 0 ? 'active' : '' }}">
        @empty
            <x-lucide-package class="w-20 h-20" style="color:#a78bfa;" />
        @endforelse
        @if ($product->discount)
            <div class="absolute top-3 left-3 flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold"
                 style="background:#ef4444;color:white;z-index:2;">
                <x-lucide-tag class="w-3 h-3" /> Promo {{ $product->discount }}%
            </div>
        @endif
        @if (count($images) > 1)
            <button type="button" class="rt-carousel-btn rt-prev" onclick="event.preventDefault();event.stopPropagation();rtCarouselNav(this,-1)">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="m15 18-6-6 6-6"/></svg>
            </button>
            <button type="button" class="rt-carousel-btn rt-next" onclick="event.preventDefault();event.stopPropagation();rtCarouselNav(this,1)">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="m9 18 6-6-6-6"/></svg>
            </button>
            <div class="rt-carousel-dots">
                @foreach ($images as $i => $img)
                    <span class="{{ $i === 0 ? 'active' : '' }}"></span>
                @endforeach
            </div>
        @endif
    </div>

    {{-- Info --}}
    <div class="flex items-start justify-between gap-2 mb-2">
        <h1 class="text-xl font-extrabold text-white font-display leading-tight">{{ $product->name }}</h1>
        <span class="shrink-0 px-2 py-0.5 rounded-full text-[10px] font-semibold"
              style="background:rgba(52,211,153,0.15);color:#34d399;">Stok: {{ $product->stock }}</span>
    </div>

    <div class="flex items-center gap-2 mb-4">
        @if ($product->discount)
            <span class="text-sm line-through" style="color:rgba(255,255,255,0.35);">{{ rupiah($product->original_price) }}</span>
        @endif
        <span class="text-2xl font-extrabold font-display" style="color:#a78bfa;">{{ rupiah($product->price) }}</span>
        @if ($product->discount)
            <span class="px-2 py-0.5 rounded-full text-xs font-bold" style="background:rgba(239,68,68,0.15);color:#f87171;">-{{ $product->discount }}%</span>
        @endif
    </div>

    {{-- Rating statis --}}
    <div class="flex items-center gap-1 mb-4">
        @for ($i = 0; $i < 5; $i++)
            <x-lucide-star class="w-4 h-4" style="color:#fbbf24;fill:#fbbf24;" />
        @endfor
        <span class="text-xs ml-1" style="color:rgba(255,255,255,0.4);">(128 ulasan)</span>
    </div>

    {{-- Deskripsi --}}
    <div class="rounded-2xl p-4 mb-5" style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.08);">
        <p class="text-xs font-bold text-white mb-2">Deskripsi Produk</p>
        <p class="text-sm leading-relaxed" style="color:rgba(255,255,255,0.58);">{{ $product->description }}</p>
    </div>

    {{-- Form qty + beli --}}
    <form method="POST" action="{{ route('packing.buy', $product) }}"
          id="buyForm"
          data-price="{{ $product->price }}"
          data-max="{{ $product->stock }}">
        @csrf
        <input type="hidden" name="qty" id="qtyInput" value="1">

        <div class="rounded-2xl p-4" style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.08);">
            <div class="flex items-center justify-between">
                <p class="text-xs font-bold text-white">Jumlah</p>
                <div class="flex items-center gap-3">
                    <button type="button" id="qtyMinus"
                            class="w-9 h-9 rounded-xl flex items-center justify-center transition-all"
                            style="background:rgba(255,255,255,0.07);color:rgba(255,255,255,0.7);">
                        <x-lucide-minus class="w-4 h-4" />
                    </button>
                    <span class="w-8 text-center text-lg font-extrabold text-white font-display" id="qtyDisplay">1</span>
                    <button type="button" id="qtyPlus"
                            class="w-9 h-9 rounded-xl flex items-center justify-center transition-all active:scale-90"
                            style="background:rgba(124,58,237,0.2);color:#a78bfa;border:1px solid rgba(124,58,237,0.35);">
                        <x-lucide-plus class="w-4 h-4" />
                    </button>
                </div>
            </div>
            <div class="flex items-center justify-between mt-3 pt-3" style="border-top:1px solid rgba(255,255,255,0.07);">
                <span class="text-xs" style="color:rgba(255,255,255,0.45);">Subtotal</span>
                <span class="text-base font-extrabold font-display" style="color:#a78bfa;" id="subtotalDisplay">{{ rupiah($product->price) }}</span>
            </div>
        </div>

        {{-- Tombol aksi (sticky bawah, berhenti sebelum mencapai footer) --}}
        <div class="sticky bottom-16 lg:bottom-0 z-30"
             style="background:linear-gradient(to top,#080313 65%,transparent);">
            <div class="px-4 pb-3 pt-2">
                <div class="flex gap-3">
                    <button type="button" id="btnKeranjang" onclick="addToCart()"
                            class="flex-1 py-3.5 rounded-2xl font-semibold text-sm flex items-center justify-center gap-2 transition-all hover:bg-violet-900/30"
                            style="border:1.5px solid rgba(124,58,237,0.4);color:#a78bfa;">
                        <x-lucide-shopping-cart class="w-4 h-4" /> + Keranjang
                    </button>
                    <button type="submit"
                            class="flex-1 py-3.5 rounded-2xl font-bold text-sm text-white flex items-center justify-center gap-2 transition-all hover:scale-[1.02]"
                            style="background:linear-gradient(135deg,#7c3aed,#6366f1);box-shadow:0 6px 20px rgba(124,58,237,0.4);">
                        Beli Sekarang <x-lucide-arrow-right class="w-4 h-4" />
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

{{-- Toast Notification --}}
<div class="toast-notif" id="toastNotif">
    <span id="toastIcon">✅</span>
    <span id="toastMsg">Berhasil!</span>
</div>

<style>
    .toast-notif {
        position: fixed;
        bottom: 160px; right: 24px;
        background: #1e1e35;
        border: 1px solid rgba(139,92,246,0.4);
        border-radius: 12px;
        padding: 12px 18px;
        display: flex; align-items: center; gap: 10px;
        font-size: 14px; font-weight: 500; color: #fff;
        z-index: 999;
        transform: translateY(80px);
        opacity: 0;
        transition: all .3s cubic-bezier(.34,1.56,.64,1);
        box-shadow: 0 8px 32px rgba(0,0,0,0.4);
    }
    .toast-notif.show { transform: translateY(0); opacity: 1; }
    @media (min-width: 1024px) {
        .toast-notif { bottom: 96px; }
    }
</style>

<script>
(function () {
    const form = document.getElementById('buyForm');
    if (!form) return;
    const price    = parseInt(form.dataset.price, 10);
    const max      = parseInt(form.dataset.max, 10);
    const input    = document.getElementById('qtyInput');
    const display  = document.getElementById('qtyDisplay');
    const subtotal = document.getElementById('subtotalDisplay');
    const minus    = document.getElementById('qtyMinus');
    const plus     = document.getElementById('qtyPlus');
    let qty = 1;

    const render = () => {
        input.value = qty;
        display.textContent = qty;
        subtotal.textContent = 'Rp ' + (price * qty).toLocaleString('id-ID');
        minus.style.opacity = qty === 1 ? '0.3' : '1';
        plus.style.opacity  = qty >= max ? '0.3' : '1';
    };
    minus.addEventListener('click', () => { qty = Math.max(1, qty - 1); render(); });
    plus.addEventListener('click',  () => { qty = Math.min(max, qty + 1); render(); });
    render();

    window.getPackingQty = () => qty;
})();

const csrfToken = '{{ csrf_token() }}';
const cartUrl   = '{{ route("preloved.cart.add") }}';
const productId = {{ $product->id }};

function showToast(msg, icon = '✅') {
    document.getElementById('toastMsg').textContent  = msg;
    document.getElementById('toastIcon').textContent = icon;
    const el = document.getElementById('toastNotif');
    el.classList.add('show');
    setTimeout(() => el.classList.remove('show'), 3000);
}

function addToCart() {
    const btn = document.getElementById('btnKeranjang');
    btn.disabled = true;
    btn.innerHTML = 'Menambahkan...';

    fetch(cartUrl, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify({ type: 'packing', product_id: productId, qty: window.getPackingQty() })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            showToast('Produk ditambahkan ke keranjang!', '✅');
        } else {
            showToast(data.message ?? 'Gagal menambahkan.', '❌');
        }
    })
    .catch(() => showToast('Terjadi kesalahan.', '❌'))
    .finally(() => {
        btn.disabled = false;
        btn.innerHTML = `
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><circle cx="8" cy="21" r="1"/><circle cx="19" cy="21" r="1"/><path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"/></svg>
            + Keranjang`;
    });
}
</script>
@endsection
