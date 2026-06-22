@extends('layouts.dashboard')

@section('title', 'Keranjang')

@php
    if (! function_exists('rupiah')) {
        function rupiah($n) { return 'Rp ' . number_format($n, 0, ',', '.'); }
    }
@endphp

@section('content')
<div class="pt-2 pb-10 max-w-xl mx-auto">

    <div class="mb-5">
        <h1 class="text-xl font-extrabold text-white font-display">Keranjang</h1>
        <p class="text-xs mt-0.5" style="color:rgba(255,255,255,0.4);">Produk packing dan preloved yang kamu pilih</p>
    </div>

    @if ($errors->any())
        <div class="rounded-xl px-4 py-2.5 mb-4 text-sm" style="background:rgba(239,68,68,0.1);border:1px solid rgba(239,68,68,0.3);color:#fca5a5;">
            {{ $errors->first() }}
        </div>
    @endif

    @if (empty($cart))
        <div class="rounded-2xl p-10 text-center" style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.09);">
            <p class="text-3xl mb-2">🛒</p>
            <p class="text-sm font-semibold text-white">Keranjangmu masih kosong</p>
            <p class="text-xs mt-1 mb-5" style="color:rgba(255,255,255,0.4);">Yuk, jelajahi produk packing dan preloved pilihan</p>
            <div class="flex items-center justify-center gap-3">
                <a href="{{ route('preloved.index') }}" class="px-5 py-2.5 rounded-xl text-xs font-bold text-white transition-all hover:opacity-90" style="background:linear-gradient(135deg,#7c3aed,#6366f1);">Toko Preloved</a>
                <a href="{{ route('packing.index') }}" class="px-5 py-2.5 rounded-xl text-xs font-bold transition-all hover:bg-violet-900/20" style="border:1.5px solid rgba(124,58,237,0.4);color:#a78bfa;">Toko Packing</a>
            </div>
        </div>
    @else
        <form method="POST" action="{{ route('preloved.cart.checkout') }}" id="cartForm">
            @csrf

            <label class="flex items-center justify-between px-4 py-3 rounded-2xl mb-3 cursor-pointer" style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.08);">
                <span class="flex items-center gap-2.5 text-xs font-bold text-white">
                    <input type="checkbox" id="selectAll" class="w-[18px] h-[18px] accent-violet-500" checked onchange="toggleSelectAll(this.checked)">
                    Pilih Semua <span id="totalItemCount" style="color:rgba(255,255,255,0.4);font-weight:500;">({{ count($cart) }} produk)</span>
                </span>
            </label>

            <div id="cartItems" class="space-y-3 mb-4">
                @foreach ($cart as $key => $item)
                    @php $type = $item['type'] ?? 'preloved'; @endphp
                    <div class="cart-item flex items-start gap-3 p-3.5 rounded-2xl transition-all" data-key="{{ $key }}"
                         style="background:rgba(124,58,237,0.06);border:1.5px solid rgba(124,58,237,0.3);">
                        <div class="pt-7 shrink-0">
                            <input type="checkbox" name="selected[]" value="{{ $key }}" class="item-checkbox w-[18px] h-[18px] accent-violet-500" checked onchange="recalcSummary()">
                        </div>
                        <div class="w-[72px] h-[72px] rounded-xl flex items-center justify-center overflow-hidden shrink-0" style="background:rgba(255,255,255,0.05);">
                            @if (!empty($item['image']))
                                @if ($type === 'packing')
                                    <img src="{{ asset('storage/'.$item['image']) }}" alt="{{ $item['name'] }}" class="w-full h-full object-cover">
                                @else
                                    <img src="{{ Storage::url($item['image']) }}" alt="{{ $item['name'] }}" class="w-full h-full object-cover">
                                @endif
                            @else
                                <x-lucide-image class="w-6 h-6" style="color:#a78bfa;" />
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <span class="inline-flex items-center px-1.5 py-0.5 rounded-md text-[9px] font-bold mb-1"
                                  style="{{ $type === 'packing' ? 'background:rgba(56,189,248,0.15);color:#38bdf8;' : 'background:rgba(124,58,237,0.15);color:#a78bfa;' }}">
                                {{ $type === 'packing' ? 'Toko Packing' : 'Preloved' }}
                            </span>
                            <p class="text-sm font-bold text-white leading-snug">{{ $item['name'] }}</p>
                            @if ($type === 'packing')
                                <p class="text-[11px] mb-1" style="color:rgba(255,255,255,0.4);">Satuan: {{ $item['unit'] ?? 'pcs' }}</p>
                            @elseif (!empty($item['condition_label']))
                                <p class="text-[11px] mb-1" style="color:rgba(255,255,255,0.4);">{{ $item['condition_label'] }}</p>
                            @endif
                            <p class="text-sm font-bold mb-2" style="color:#a78bfa;" data-price="{{ $item['price'] }}">{{ rupiah($item['price']) }}</p>
                            <div class="flex items-center gap-2.5">
                                <button type="button" class="w-7 h-7 rounded-lg flex items-center justify-center text-sm" style="background:rgba(255,255,255,0.07);color:rgba(255,255,255,0.7);border:1px solid rgba(255,255,255,0.1);" onclick="cartChangeQty('{{ $key }}', -1)">−</button>
                                <span class="qty-num text-xs font-bold text-white min-w-[18px] text-center">{{ $item['qty'] }}</span>
                                <button type="button" class="w-7 h-7 rounded-lg flex items-center justify-center text-sm" style="background:rgba(124,58,237,0.2);color:#a78bfa;border:1px solid rgba(124,58,237,0.35);" onclick="cartChangeQty('{{ $key }}', 1)">+</button>
                            </div>
                        </div>
                        <button type="button" class="shrink-0 p-1.5 rounded-lg transition-colors hover:bg-red-500/10" style="color:rgba(255,255,255,0.35);" onclick="cartRemove('{{ $key }}')" aria-label="Hapus">
                            <x-lucide-trash-2 class="w-4 h-4" />
                        </button>
                    </div>
                @endforeach
            </div>

            <div class="rounded-2xl p-5" style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.09);">
                <div class="flex justify-between items-center py-1">
                    <span class="text-sm" style="color:rgba(255,255,255,0.45);">Produk dipilih</span>
                    <span class="text-sm font-semibold text-white" id="summarySelectedCount">{{ count($cart) }}</span>
                </div>
                <div class="flex justify-between items-center mt-2 pt-3" style="border-top:1px solid rgba(255,255,255,0.1);">
                    <span class="text-sm font-bold text-white">Total</span>
                    <span class="text-lg font-extrabold font-display" id="summaryTotal" style="color:#a78bfa;">{{ rupiah(array_sum(array_column($cart, 'subtotal'))) }}</span>
                </div>
                <button type="submit" id="btnCheckout"
                        class="w-full mt-4 py-3.5 rounded-2xl font-bold text-sm text-white transition-all hover:scale-[1.02] disabled:opacity-40"
                        style="background:linear-gradient(135deg,#7c3aed,#6366f1);box-shadow:0 6px 20px rgba(124,58,237,0.4);">
                    Checkout
                </button>
            </div>
        </form>
    @endif
</div>

<script>
    const updateUrl = '{{ route("preloved.cart.update") }}';
    const removeUrl = '{{ route("preloved.cart.remove") }}';
    const csrfToken = '{{ csrf_token() }}';

    function recalcSummary() {
        let selectedCount = 0, total = 0;
        document.querySelectorAll('.cart-item').forEach(row => {
            const checkbox = row.querySelector('.item-checkbox');
            const checked = checkbox.checked;
            row.style.background = checked ? 'rgba(124,58,237,0.06)' : 'rgba(255,255,255,0.04)';
            row.style.borderColor = checked ? 'rgba(124,58,237,0.3)' : 'rgba(255,255,255,0.08)';
            if (checked) {
                const qty = parseInt(row.querySelector('.qty-num').textContent, 10);
                const price = parseInt(row.querySelector('[data-price]').dataset.price, 10);
                selectedCount += 1;
                total += qty * price;
            }
        });

        document.getElementById('summarySelectedCount').textContent = selectedCount;
        document.getElementById('summaryTotal').textContent = 'Rp ' + total.toLocaleString('id-ID');
        document.getElementById('btnCheckout').disabled = selectedCount === 0;

        const allCheckboxes = document.querySelectorAll('.item-checkbox');
        const allChecked = allCheckboxes.length > 0 && [...allCheckboxes].every(c => c.checked);
        const selectAll = document.getElementById('selectAll');
        if (selectAll) selectAll.checked = allChecked;
    }

    function toggleSelectAll(checked) {
        document.querySelectorAll('.item-checkbox').forEach(c => { c.checked = checked; });
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
