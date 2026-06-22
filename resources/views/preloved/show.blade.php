@extends('layouts.dashboard')

@section('title', $product['name'] . ' — Toko Preloved')

@php
    if (! function_exists('rupiah')) {
        function rupiah($n) { return 'Rp ' . number_format($n, 0, ',', '.'); }
    }
    $cond = $product['condition'] ?? '';
    $condStyle = match(true) {
        str_contains($cond, 'mulus') => 'background:rgba(16,185,129,0.2);color:#34d399;',
        str_contains($cond, 'baik')  => 'background:rgba(59,130,246,0.2);color:#60a5fa;',
        default                       => 'background:rgba(245,158,11,0.2);color:#fbbf24;',
    };
    $disc = $product['discount_percent'] ?? 0;
    $origPrice = $product['original_price'] ?? 0;
    $price = $product['price'] ?? 0;
    if (! $disc && $origPrice && $origPrice > $price) {
        $disc = round((1 - $price / $origPrice) * 100);
    }
    $sellerInitial = strtoupper(substr($product['seller_name'] ?? 'A', 0, 1));
    $stock = $product['stock'] ?? 1;
    $images = !empty($product['images']) ? $product['images'] : (!empty($product['image']) ? [$product['image']] : []);
@endphp

@section('content')
<div class="py-4 pb-32 max-w-xl mx-auto">

    {{-- Kembali --}}
    <a href="{{ route('preloved.index') }}"
       class="flex items-center gap-1.5 text-sm mb-4 transition-colors hover:text-violet-300"
       style="color:rgba(255,255,255,0.4);">
        <x-lucide-chevron-left class="w-4 h-4" /> Kembali ke Toko Preloved
    </a>

    {{-- Galeri --}}
    <div class="relative rounded-2xl overflow-hidden mb-5 flex items-center justify-center rt-carousel"
         style="height:220px;background:rgba(124,58,237,0.1);">
        @forelse ($images as $i => $img)
            <img src="{{ Storage::url($img) }}" alt="{{ $product['name'] }}" class="rt-slide {{ $i === 0 ? 'active' : '' }}">
        @empty
            <x-lucide-image class="w-20 h-20" style="color:#a78bfa;" />
        @endforelse
        @if ($disc)
            <div class="absolute top-3 left-3 flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold"
                 style="background:#ef4444;color:white;z-index:2;">
                <x-lucide-tag class="w-3 h-3" /> -{{ $disc }}%
            </div>
        @endif
        <button type="button" id="btnWish" onclick="toggleWishlist()"
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
            <div class="rt-carousel-dots">
                @foreach ($images as $i => $img)
                    <span class="{{ $i === 0 ? 'active' : '' }}"></span>
                @endforeach
            </div>
        @endif
    </div>

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
                <button type="button" id="btnBeli" onclick="beliSekarang()"
                        class="flex-1 py-3.5 rounded-2xl font-bold text-sm text-white flex items-center justify-center gap-2 transition-all hover:scale-[1.02]"
                        style="background:linear-gradient(135deg,#7c3aed,#6366f1);box-shadow:0 6px 20px rgba(124,58,237,0.4);">
                    Beli Sekarang <x-lucide-arrow-right class="w-4 h-4" />
                </button>
            </div>
        </div>
    </div>
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
    const harga      = {{ $price }};
    const stok       = {{ $stock }};
    const productId  = {{ $product['id'] }};
    const csrfToken  = '{{ csrf_token() }}';
    const cartUrl    = '{{ route("preloved.cart.add") }}';
    let qty = 1;

    function renderQty() {
        document.getElementById('qtyDisplay').textContent = qty;
        document.getElementById('subtotalDisplay').textContent = 'Rp ' + (harga * qty).toLocaleString('id-ID');
        document.getElementById('qtyMinus').style.opacity = qty === 1 ? '0.3' : '1';
        document.getElementById('qtyPlus').style.opacity  = qty >= stok ? '0.3' : '1';
    }
    document.getElementById('qtyMinus').addEventListener('click', () => { qty = Math.max(1, qty - 1); renderQty(); });
    document.getElementById('qtyPlus').addEventListener('click',  () => { qty = Math.min(stok, qty + 1); renderQty(); });
    renderQty();

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
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
            body: JSON.stringify({ type: 'preloved', product_id: productId, qty: qty })
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

    function beliSekarang() {
        const btn = document.getElementById('btnBeli');
        btn.disabled = true;
        btn.innerHTML = 'Memproses...';

        fetch(cartUrl, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
            body: JSON.stringify({ type: 'preloved', product_id: productId, qty: qty, buy_now: true })
        })
        .then(r => r.json())
        .then(data => {
            if (data.success && data.redirect) {
                window.location.href = data.redirect;
            } else {
                showToast(data.message ?? 'Gagal memproses.', '❌');
                btn.disabled = false;
                btn.innerHTML = 'Beli Sekarang →';
            }
        })
        .catch(() => {
            showToast('Terjadi kesalahan.', '❌');
            btn.disabled = false;
            btn.innerHTML = 'Beli Sekarang →';
        });
    }

    let wishlisted = false;
    function toggleWishlist() {
        wishlisted = !wishlisted;
        const btn = document.getElementById('btnWish');
        btn.style.background = wishlisted ? 'rgba(239,68,68,0.3)' : 'rgba(0,0,0,0.4)';
        btn.style.color = wishlisted ? '#ef4444' : 'rgba(255,255,255,0.6)';
        document.getElementById('wishIcon').setAttribute('fill', wishlisted ? '#ef4444' : 'none');
        showToast(wishlisted ? 'Ditambahkan ke wishlist!' : 'Dihapus dari wishlist.', wishlisted ? '❤️' : '🤍');
    }
</script>
@endsection
