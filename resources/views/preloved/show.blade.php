@extends('layouts.dashboard')

@section('title', $product['name'] . ' — Toko Preloved')

@section('content')
<style>
    .show-wrap { max-width: 520px; margin: 0 auto; color: #fff; }

    /* Image card */
    .img-card {
        background: rgba(255,255,255,0.04);
        border: 1px solid rgba(255,255,255,0.08);
        border-radius: 20px;
        overflow: hidden;
        position: relative;
        margin-bottom: 20px;
    }
    .img-area {
        width: 100%;
        aspect-ratio: 1/1;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 96px;
        background: rgba(255,255,255,0.03);
        position: relative;
    }
    .img-area img { width: 100%; height: 100%; object-fit: cover; }

    .badge-disc {
        position: absolute;
        top: 14px; left: 14px;
        background: #ef4444;
        color: #fff;
        font-size: 12px; font-weight: 700;
        padding: 4px 10px;
        border-radius: 8px;
        display: flex; align-items: center; gap: 5px;
        z-index: 2;
    }
    .btn-wish {
        position: absolute;
        top: 14px; right: 14px;
        width: 36px; height: 36px;
        border-radius: 50%;
        background: rgba(0,0,0,0.4);
        border: none;
        display: flex; align-items: center; justify-content: center;
        color: rgba(255,255,255,0.6);
        cursor: pointer;
        transition: all .2s;
        z-index: 2;
    }
    .btn-wish:hover { background: rgba(239,68,68,0.3); color: #ef4444; }

    /* Dots */
    .img-dots {
        display: flex; align-items: center; justify-content: center;
        gap: 6px; padding: 12px 0;
    }
    .img-dot {
        height: 4px; border-radius: 2px;
        background: rgba(255,255,255,0.2);
        transition: all .2s;
    }
    .img-dot.active { width: 20px; background: #fff; }
    .img-dot:not(.active) { width: 6px; }

    /* Info section */
    .badge-cond {
        display: inline-flex; align-items: center;
        padding: 3px 10px; border-radius: 7px;
        font-size: 12px; font-weight: 600;
    }
    .badge-stok {
        display: inline-flex; align-items: center;
        padding: 3px 10px; border-radius: 7px;
        font-size: 12px; font-weight: 600;
        background: rgba(99,102,241,0.2); color: #818cf8;
    }
    .cond-mulus { background: rgba(16,185,129,0.2); color: #34d399; }
    .cond-baik  { background: rgba(59,130,246,0.2); color: #60a5fa; }
    .cond-pernah{ background: rgba(245,158,11,0.2); color: #fbbf24; }

    .product-title {
        font-size: 26px; font-weight: 800;
        color: #fff; margin: 10px 0 8px;
    }
    .price-row { display: flex; align-items: center; gap: 10px; margin-bottom: 14px; flex-wrap: wrap; }
    .price-orig { font-size: 14px; color: rgba(255,255,255,0.3); text-decoration: line-through; }
    .price-now  { font-size: 28px; font-weight: 800; color: #a78bfa; }
    .badge-pct  {
        font-size: 11px; font-weight: 700;
        background: rgba(239,68,68,0.2); color: #f87171;
        padding: 3px 8px; border-radius: 6px;
    }

    /* Seller */
    .seller-row { display: flex; align-items: center; gap: 8px; margin-bottom: 16px; }
    .seller-avatar {
        width: 28px; height: 28px; border-radius: 50%;
        background: linear-gradient(135deg,#7c3aed,#6366f1);
        display: flex; align-items: center; justify-content: center;
        font-size: 11px; font-weight: 700; color: #fff;
        flex-shrink: 0;
    }
    .seller-name { font-size: 13px; color: rgba(255,255,255,0.7); }
    .seller-rating { display: flex; align-items: center; gap: 3px; font-size: 13px; font-weight: 600; color: #fbbf24; }

    /* Desc card */
    .desc-card {
        background: rgba(255,255,255,0.04);
        border: 1px solid rgba(255,255,255,0.08);
        border-radius: 14px;
        padding: 16px;
        margin-bottom: 12px;
    }
    .desc-card h4 { font-size: 13px; font-weight: 700; color: #fff; margin-bottom: 8px; }
    .desc-card p  { font-size: 13px; color: rgba(255,255,255,0.55); line-height: 1.65; margin: 0; }

    /* Qty + subtotal card */
    .qty-card {
        background: rgba(255,255,255,0.04);
        border: 1px solid rgba(255,255,255,0.08);
        border-radius: 14px;
        overflow: hidden;
        margin-bottom: 24px;
    }
    .qty-row {
        display: flex; align-items: center; justify-content: space-between;
        padding: 14px 16px;
        border-bottom: 1px solid rgba(255,255,255,0.06);
    }
    .qty-label { font-size: 14px; font-weight: 600; color: #fff; }
    .qty-controls { display: flex; align-items: center; gap: 12px; }
    .qty-btn {
        width: 32px; height: 32px; border-radius: 50%;
        border: none; cursor: pointer;
        display: flex; align-items: center; justify-content: center;
        font-size: 16px; font-weight: 700;
        transition: all .2s;
    }
    .qty-btn-minus {
        background: rgba(255,255,255,0.08);
        color: rgba(255,255,255,0.6);
    }
    .qty-btn-minus:hover:not(:disabled) { background: rgba(255,255,255,0.14); }
    .qty-btn-minus:disabled { opacity: .35; cursor: not-allowed; }
    .qty-btn-plus {
        background: #7c3aed;
        color: #fff;
    }
    .qty-btn-plus:hover:not(:disabled) { background: #6d28d9; }
    .qty-btn-plus:disabled { opacity: .35; cursor: not-allowed; }
    .qty-num { font-size: 16px; font-weight: 700; color: #fff; min-width: 24px; text-align: center; }

    .subtotal-row {
        display: flex; align-items: center; justify-content: space-between;
        padding: 14px 16px;
    }
    .subtotal-label { font-size: 13px; color: rgba(255,255,255,0.4); }
    .subtotal-val   { font-size: 16px; font-weight: 700; color: #a78bfa; }

    /* CTA buttons */
    .cta-row { display: flex; gap: 12px; }
    .btn-keranjang {
        flex: 1;
        display: flex; align-items: center; justify-content: center; gap: 8px;
        padding: 14px;
        border-radius: 14px;
        border: 1.5px solid rgba(139,92,246,0.5);
        background: transparent;
        color: #a78bfa;
        font-size: 14px; font-weight: 600;
        cursor: pointer; transition: all .2s;
        text-decoration: none;
    }
    .btn-keranjang:hover { background: rgba(139,92,246,0.1); color: #a78bfa; }
    .btn-beli-sekarang {
        flex: 1;
        display: flex; align-items: center; justify-content: center; gap: 8px;
        padding: 14px;
        border-radius: 14px;
        border: none;
        background: linear-gradient(135deg, #7c3aed, #6366f1);
        color: #fff;
        font-size: 14px; font-weight: 700;
        cursor: pointer; transition: opacity .2s;
        text-decoration: none;
    }
    .btn-beli-sekarang:hover { opacity: .88; }
    .btn-beli-sekarang:disabled { opacity: .5; cursor: not-allowed; }

    /* Toast */
    .toast-notif {
        position: fixed;
        bottom: 24px; right: 24px;
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
</style>

<div class="show-wrap">

    {{-- Back --}}
    <a href="{{ route('preloved.index') }}"
       class="inline-flex items-center gap-2 mb-5 text-sm"
       style="color:rgba(255,255,255,0.45); text-decoration:none; display:flex; align-items:center; gap:6px; margin-bottom:20px;">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="m15 18-6-6 6-6"/></svg>
        Kembali ke Toko Preloved
    </a>

    @php
        $cond      = $product['condition'] ?? '';
        $condLabel = $product['condition_label'] ?? $cond;
        $condClass = match(true) {
            str_contains($cond, 'mulus') => 'cond-mulus',
            str_contains($cond, 'baik')  => 'cond-baik',
            default                       => 'cond-pernah',
        };
        $disc      = $product['discount_percent'] ?? 0;
        $origPrice = $product['original_price'] ?? 0;
        $price     = $product['price'] ?? 0;
        if (!$disc && $origPrice && $origPrice > $price) {
            $disc = round((1 - $price / $origPrice) * 100);
        }
        $sellerInitial = strtoupper(substr($product['seller_name'] ?? 'A', 0, 1));
        $stock = $product['stock'] ?? 1;
    @endphp

    {{-- Image Card --}}
    <div class="img-card">
        <div class="img-area">
            @if ($disc)
                <div class="badge-disc">🏷 -{{ $disc }}%</div>
            @endif
            <button class="btn-wish" id="btnWish" onclick="toggleWishlist()">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/></svg>
            </button>
            @if (!empty($product['image']))
                <img src="{{ Storage::url($product['image']) }}" alt="{{ $product['name'] }}" />
            @else
                {{ $product['emoji'] ?? '📦' }}
            @endif
        </div>
        <div class="img-dots">
            <div class="img-dot active"></div>
            <div class="img-dot"></div>
            <div class="img-dot"></div>
        </div>
    </div>

    {{-- Badges --}}
    <div style="display:flex; align-items:center; gap:8px; margin-bottom:8px;">
        <span class="badge-cond {{ $condClass }}">{{ $condLabel }}</span>
        <span class="badge-stok">Stok: {{ $stock }}</span>
    </div>

    {{-- Title --}}
    <h1 class="product-title">{{ $product['name'] }}</h1>

    {{-- Price --}}
    <div class="price-row">
        @if ($origPrice && $origPrice > $price)
            <span class="price-orig">Rp {{ number_format($origPrice, 0, ',', '.') }}</span>
        @endif
        <span class="price-now">Rp {{ number_format($price, 0, ',', '.') }}</span>
        @if ($disc)
            <span class="badge-pct">-{{ $disc }}%</span>
        @endif
    </div>

    {{-- Seller --}}
    <div class="seller-row">
        <div class="seller-avatar">{{ $sellerInitial }}</div>
        <span class="seller-name">{{ $product['seller_name'] ?? '-' }}</span>
        <span class="seller-rating">⭐ {{ $product['seller_rating'] ?? '4.5' }}</span>
    </div>

    {{-- Description --}}
    <div class="desc-card">
        <h4>Deskripsi &amp; Kondisi Barang</h4>
        <p>{{ $product['description'] ?? '-' }}</p>
    </div>

    {{-- Qty + Subtotal --}}
    <div class="qty-card">
        <div class="qty-row">
            <span class="qty-label">Jumlah</span>
            <div class="qty-controls">
                <button class="qty-btn qty-btn-minus" id="btnMinus" onclick="changeQty(-1)" disabled>−</button>
                <span class="qty-num" id="qtyNum">1</span>
                <button class="qty-btn qty-btn-plus" id="btnPlus" onclick="changeQty(1)" {{ $stock <= 1 ? 'disabled' : '' }}>+</button>
            </div>
        </div>
        <div class="subtotal-row">
            <span class="subtotal-label">Subtotal</span>
            <span class="subtotal-val" id="subtotalVal">Rp {{ number_format($price, 0, ',', '.') }}</span>
        </div>
    </div>

    {{-- CTA --}}
    <div class="cta-row">
        <button class="btn-keranjang" id="btnKeranjang" onclick="addToCart()">
            <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><circle cx="8" cy="21" r="1"/><circle cx="19" cy="21" r="1"/><path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"/></svg>
            + Keranjang
        </button>

        <button class="btn-beli-sekarang" id="btnBeli" onclick="beliSekarang()">
            Beli Sekarang
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
        </button>
    </div>

</div>

{{-- Toast Notification --}}
<div class="toast-notif" id="toastNotif">
    <span id="toastIcon">✅</span>
    <span id="toastMsg">Berhasil!</span>
</div>

<script>
    const harga      = {{ $price }};
    const stok       = {{ $stock }};
    const productId  = {{ $product['id'] }};
    const csrfToken  = '{{ csrf_token() }}';
    const cartUrl    = '{{ route("preloved.cart.add") }}';
    let qty = 1;

    // ── Qty controls ──────────────────────────────────────────────
    function changeQty(delta) {
        qty = Math.max(1, Math.min(stok, qty + delta));

        document.getElementById('qtyNum').textContent = qty;
        document.getElementById('subtotalVal').textContent =
            'Rp ' + (harga * qty).toLocaleString('id-ID');

        document.getElementById('btnMinus').disabled = qty <= 1;
        document.getElementById('btnPlus').disabled  = qty >= stok;
    }

    // ── Toast helper ──────────────────────────────────────────────
    function showToast(msg, icon = '✅') {
        document.getElementById('toastMsg').textContent  = msg;
        document.getElementById('toastIcon').textContent = icon;
        const el = document.getElementById('toastNotif');
        el.classList.add('show');
        setTimeout(() => el.classList.remove('show'), 3000);
    }

    // ── Tambah ke keranjang ───────────────────────────────────────
    function addToCart() {
        const btn = document.getElementById('btnKeranjang');
        btn.disabled = true;
        btn.textContent = 'Menambahkan...';

        fetch(cartUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({ product_id: productId, qty: qty })
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
                <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><circle cx="8" cy="21" r="1"/><circle cx="19" cy="21" r="1"/><path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"/></svg>
                + Keranjang`;
        });
    }

    // ── Beli Sekarang ─────────────────────────────────────────────
    function beliSekarang() {
        const btn = document.getElementById('btnBeli');
        btn.disabled = true;
        btn.textContent = 'Memproses...';

        fetch(cartUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({ product_id: productId, qty: qty, buy_now: true })
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

    // ── Wishlist ──────────────────────────────────────────────────
    let wishlisted = false;
    function toggleWishlist() {
        wishlisted = !wishlisted;
        const btn = document.getElementById('btnWish');
        if (wishlisted) {
            btn.style.background = 'rgba(239,68,68,0.3)';
            btn.style.color = '#ef4444';
            btn.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#ef4444" stroke="#ef4444" stroke-width="2" viewBox="0 0 24 24"><path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/></svg>`;
            showToast('Ditambahkan ke wishlist!', '❤️');
        } else {
            btn.style.background = 'rgba(0,0,0,0.4)';
            btn.style.color = 'rgba(255,255,255,0.6)';
            btn.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/></svg>`;
            showToast('Dihapus dari wishlist.', '🤍');
        }
    }
</script>
@endsection