@extends('layouts.dashboard')

@section('title', 'Keranjang')

@section('content')
<style>
    .cart-wrap { color: #fff; }

    .cart-select-all {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 12px 14px;
        background: rgba(255,255,255,0.04);
        border: 1px solid rgba(255,255,255,0.08);
        border-radius: 12px;
        margin-bottom: 12px;
    }
    .cart-select-all label { display: flex; align-items: center; gap: 10px; cursor: pointer; font-size: 13px; font-weight: 600; color: #fff; }
    .cart-checkbox {
        width: 19px; height: 19px;
        accent-color: #7c3aed;
        cursor: pointer;
        flex-shrink: 0;
    }

    .cart-item {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        padding: 14px;
        background: rgba(255,255,255,0.04);
        border: 1px solid rgba(255,255,255,0.08);
        border-radius: 14px;
        margin-bottom: 12px;
        transition: border-color .2s, background .2s;
    }
    .cart-item.is-selected { border-color: rgba(124,58,237,0.4); background: rgba(124,58,237,0.06); }
    .cart-item-check { padding-top: 28px; flex-shrink: 0; }

    .cart-item-img {
        width: 76px; height: 76px;
        border-radius: 10px;
        background: rgba(255,255,255,0.05);
        display: flex; align-items: center; justify-content: center;
        overflow: hidden;
        flex-shrink: 0;
    }
    .cart-item-img img { width: 100%; height: 100%; object-fit: cover; }

    .cart-item-info { flex: 1; min-width: 0; }
    .cart-item-type {
        display: inline-flex; align-items: center;
        font-size: 10px; font-weight: 700;
        padding: 2px 8px; border-radius: 6px;
        margin-bottom: 4px;
    }
    .type-packing { background: rgba(56,189,248,0.15); color: #38bdf8; }
    .type-preloved { background: rgba(124,58,237,0.15); color: #a78bfa; }
    .cart-item-name { font-size: 14px; font-weight: 600; color: #fff; margin-bottom: 2px; }
    .cart-item-cond { font-size: 11px; color: rgba(255,255,255,0.4); margin-bottom: 6px; }
    .cart-item-price { font-size: 14px; font-weight: 700; color: #a78bfa; }

    .qty-controls {
        display: flex; align-items: center; gap: 10px;
        margin-top: 8px;
    }
    .qty-btn {
        width: 26px; height: 26px;
        border-radius: 8px;
        border: 1px solid rgba(255,255,255,0.15);
        background: rgba(255,255,255,0.05);
        color: #fff;
        font-size: 15px;
        display: flex; align-items: center; justify-content: center;
        cursor: pointer;
    }
    .qty-btn:disabled { opacity: .35; cursor: not-allowed; }
    .qty-num { font-size: 13px; font-weight: 600; min-width: 18px; text-align: center; }

    .btn-remove {
        background: none; border: none;
        color: rgba(255,255,255,0.35);
        cursor: pointer;
        padding: 4px;
        align-self: flex-start;
        margin-top: 4px;
        transition: color .2s;
    }
    .btn-remove:hover { color: #ef4444; }

    .cart-summary {
        background: rgba(255,255,255,0.04);
        border: 1px solid rgba(255,255,255,0.08);
        border-radius: 16px;
        padding: 18px;
        margin-top: 20px;
        position: sticky;
        bottom: 16px;
    }
    .summary-row { display: flex; justify-content: space-between; font-size: 13px; color: rgba(255,255,255,0.55); margin-bottom: 8px; }
    .summary-total { display: flex; justify-content: space-between; font-size: 16px; font-weight: 700; color: #fff; padding-top: 10px; border-top: 1px solid rgba(255,255,255,0.08); }

    .btn-checkout {
        display: block;
        width: 100%;
        text-align: center;
        margin-top: 16px;
        padding: 12px 0;
        border-radius: 12px;
        background: linear-gradient(135deg, #7c3aed, #6366f1);
        color: #fff !important;
        font-size: 14px;
        font-weight: 700;
        text-decoration: none;
        border: none;
        cursor: pointer;
        transition: opacity .2s;
    }
    .btn-checkout:hover { opacity: .85; }
    .btn-checkout:disabled { opacity: .4; cursor: not-allowed; }
</style>

<div class="cart-wrap">
    <div class="mb-5">
        <h1 class="text-2xl font-extrabold font-display text-white mb-1">Keranjang</h1>
        <p style="color:rgba(255,255,255,0.4);font-size:14px;">Produk packing dan preloved yang kamu pilih</p>
    </div>

    @if ($errors->any())
        <div class="rounded-xl px-4 py-2.5 mb-4 text-sm" style="background:rgba(239,68,68,0.1);border:1px solid rgba(239,68,68,0.3);color:#fca5a5;">
            {{ $errors->first() }}
        </div>
    @endif

    @if (empty($cart))
        <div class="text-center py-20" style="color:rgba(255,255,255,0.3);">
            <div style="font-size:48px;margin-bottom:12px;">🛒</div>
            <p class="font-semibold text-white">Keranjangmu masih kosong</p>
            <p style="font-size:13px;margin-top:4px;">Yuk, jelajahi produk packing dan preloved pilihan</p>
            <div class="flex items-center justify-center gap-3 mt-4">
                <a href="{{ route('preloved.index') }}" class="btn-checkout" style="display:inline-block;width:auto;padding:10px 24px;">Toko Preloved</a>
                <a href="{{ route('packing.index') }}" class="btn-checkout" style="display:inline-block;width:auto;padding:10px 24px;background:transparent;border:1.5px solid rgba(124,58,237,0.5);color:#a78bfa !important;">Toko Packing</a>
            </div>
        </div>
    @else
        <form method="POST" action="{{ route('preloved.cart.checkout') }}" id="cartForm">
            @csrf

            <div class="cart-select-all">
                <label>
                    <input type="checkbox" id="selectAll" class="cart-checkbox" checked onchange="toggleSelectAll(this.checked)">
                    <span>Pilih Semua <span id="totalItemCount">({{ count($cart) }} produk)</span></span>
                </label>
            </div>

            <div id="cartItems">
                @foreach ($cart as $key => $item)
                    @php $type = $item['type'] ?? 'preloved'; @endphp
                    <div class="cart-item is-selected" data-key="{{ $key }}">
                        <div class="cart-item-check">
                            <input type="checkbox" name="selected[]" value="{{ $key }}" class="cart-checkbox item-checkbox" checked onchange="onItemCheckChange(this)">
                        </div>
                        <div class="cart-item-img">
                            @if (!empty($item['image']))
                                @if ($type === 'packing')
                                    <img src="{{ asset('storage/'.$item['image']) }}" alt="{{ $item['name'] }}">
                                @else
                                    <img src="{{ Storage::url($item['image']) }}" alt="{{ $item['name'] }}">
                                @endif
                            @else
                                <x-lucide-image class="w-7 h-7" style="color:#a78bfa;" />
                            @endif
                        </div>
                        <div class="cart-item-info">
                            <span class="cart-item-type {{ $type === 'packing' ? 'type-packing' : 'type-preloved' }}">
                                {{ $type === 'packing' ? 'Toko Packing' : 'Preloved' }}
                            </span>
                            <p class="cart-item-name">{{ $item['name'] }}</p>
                            @if ($type === 'packing')
                                <p class="cart-item-cond">Satuan: {{ $item['unit'] ?? 'pcs' }}</p>
                            @elseif (!empty($item['condition_label']))
                                <p class="cart-item-cond">{{ $item['condition_label'] }}</p>
                            @endif
                            <p class="cart-item-price" data-price="{{ $item['price'] }}">Rp {{ number_format($item['price'], 0, ',', '.') }}</p>
                            <div class="qty-controls">
                                <button type="button" class="qty-btn btn-qty-minus" onclick="cartChangeQty('{{ $key }}', -1)">−</button>
                                <span class="qty-num">{{ $item['qty'] }}</span>
                                <button type="button" class="qty-btn btn-qty-plus" onclick="cartChangeQty('{{ $key }}', 1)">+</button>
                            </div>
                        </div>
                        <button type="button" class="btn-remove" onclick="cartRemove('{{ $key }}')" aria-label="Hapus">
                            <x-lucide-trash-2 class="w-4 h-4" />
                        </button>
                    </div>
                @endforeach
            </div>

            <div class="cart-summary">
                <div class="summary-row">
                    <span>Produk dipilih</span>
                    <span id="summarySelectedCount">{{ count($cart) }}</span>
                </div>
                <div class="summary-total">
                    <span>Total</span>
                    <span id="summaryTotal">Rp {{ number_format(array_sum(array_column($cart, 'subtotal')), 0, ',', '.') }}</span>
                </div>
                <button type="submit" class="btn-checkout" id="btnCheckout">Checkout</button>
            </div>
        </form>
    @endif
</div>

<script>
    const csrfToken = '{{ csrf_token() }}';
    const updateUrl = '{{ route("preloved.cart.update") }}';
    const removeUrl = '{{ route("preloved.cart.remove") }}';

    function recalcSummary() {
        let selectedCount = 0, total = 0;
        document.querySelectorAll('.cart-item').forEach(row => {
            const checkbox = row.querySelector('.item-checkbox');
            const checked = checkbox.checked;
            row.classList.toggle('is-selected', checked);
            if (checked) {
                const qty = parseInt(row.querySelector('.qty-num').textContent, 10);
                const price = parseInt(row.querySelector('.cart-item-price').dataset.price, 10);
                selectedCount += 1;
                total += qty * price;
            }
        });

        document.getElementById('summarySelectedCount').textContent = selectedCount;
        document.getElementById('summaryTotal').textContent = 'Rp ' + total.toLocaleString('id-ID');
        document.getElementById('btnCheckout').disabled = selectedCount === 0;

        const allCheckboxes = document.querySelectorAll('.item-checkbox');
        const allChecked = allCheckboxes.length > 0 && [...allCheckboxes].every(c => c.checked);
        document.getElementById('selectAll').checked = allChecked;
    }

    function toggleSelectAll(checked) {
        document.querySelectorAll('.item-checkbox').forEach(c => { c.checked = checked; });
        recalcSummary();
    }

    function onItemCheckChange() {
        recalcSummary();
    }

    function cartChangeQty(key, delta) {
        const row = document.querySelector(`.cart-item[data-key="${key}"]`);
        const qtyEl = row.querySelector('.qty-num');
        const newQty = Math.max(1, parseInt(qtyEl.textContent, 10) + delta);
        qtyEl.textContent = newQty;
        recalcSummary();

        fetch(updateUrl, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
            body: JSON.stringify({ product_id: key, qty: newQty }),
        });
    }

    function cartRemove(key) {
        fetch(removeUrl, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
            body: JSON.stringify({ product_id: key }),
        })
        .then(r => r.json())
        .then(() => {
            document.querySelector(`.cart-item[data-key="${key}"]`)?.remove();
            const remaining = document.querySelectorAll('.cart-item').length;
            document.getElementById('totalItemCount').textContent = `(${remaining} produk)`;
            if (remaining === 0) {
                window.location.reload();
                return;
            }
            recalcSummary();
        });
    }

    recalcSummary();
</script>
@endsection
