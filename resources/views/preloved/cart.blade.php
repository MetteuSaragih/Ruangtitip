@extends('layouts.rt')
@section('title', 'Keranjang')

@php
    if (! function_exists('rp')) {
        function rp($n) { return 'Rp' . number_format($n, 0, ',', '.'); }
    }
@endphp

@section('content')
<div class="mp">
<div class="stack">
    @include('partials.mp-head', [
        'crumbs' => [['Toko Preloved', route('preloved.index')], ['Keranjang', null]],
        'title' => 'Keranjang',
        'sub' => 'Produk packing dan preloved yang kamu pilih.',
        'acts' => '<a class="btn btn-ghost" href="' . e(route('preloved.index')) . '">Belanja lagi</a>',
    ])

    @if ($errors->any())
        <div class="alert a-err"><svg class="ic" aria-hidden="true"><use href="#i-warn"/></svg><div><b>{{ $errors->first() }}</b></div></div>
    @endif

    @if (empty($cart))
        <section class="card state"><svg viewBox="0 0 44 40" width="90" height="82" aria-hidden="true"><use href="#box"/></svg><h3>Keranjangmu masih kosong</h3><p>Pilih barang preloved dulu, ya.</p><div class="acts"><a class="btn btn-sun" href="{{ route('preloved.index') }}">Lihat katalog</a></div></section>
    @else
        <div class="two">
            <div class="stack">
                <form method="POST" action="{{ route('preloved.cart.checkout') }}" id="cartForm" class="stack">
                    @csrf
                    <section class="card">
                        <div class="card-h">
                            <label class="pick" style="padding:8px 14px"><input type="checkbox" id="selectAll" checked onchange="toggleSelectAll(this.checked)"><span>Pilih semua</span></label>
                            <span class="help" id="totalItemCount">{{ count($cart) }} produk</span>
                        </div>
                        <div class="olist" id="cartItems">
                            @foreach ($cart as $key => $item)
                                @php $type = $item['type'] ?? 'preloved'; @endphp
                                <article class="oc cart-item" data-key="{{ $key }}" style="grid-template-columns:auto 64px minmax(0,1fr) auto">
                                    <input type="checkbox" name="selected[]" value="{{ $key }}" class="item-checkbox" checked onchange="recalcSummary()" style="width:20px;height:20px;accent-color:var(--teal)" aria-label="Pilih {{ $item['name'] }}">
                                    <div class="pic" style="--tone:var(--alt)">
                                        @if (! empty($item['image']))
                                            @if ($type === 'packing')
                                                <img src="{{ asset('storage/'.$item['image']) }}" alt="{{ $item['name'] }}" style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover;border-radius:14px">
                                            @else
                                                <img src="{{ \Illuminate\Support\Facades\Storage::url($item['image']) }}" alt="{{ $item['name'] }}" style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover;border-radius:14px">
                                            @endif
                                        @else
                                            <svg viewBox="0 0 24 24" aria-hidden="true"><use href="#n-tag"/></svg>
                                        @endif
                                    </div>
                                    <div>
                                        <div class="top"><span class="badge {{ $type === 'packing' ? 'b-info' : 'b-ok' }}"><i></i>{{ $type === 'packing' ? 'Toko Packing' : 'Preloved' }}</span></div>
                                        <p><b>{{ $item['name'] }}</b></p>
                                        <p class="meta">
                                            @if ($type === 'packing')Satuan: {{ $item['unit'] ?? 'pcs' }}. @elseif (! empty($item['condition_label'])){{ $item['condition_label'] }}. @endif
                                            <span data-price="{{ $item['price'] }}">{{ rp($item['price']) }}</span>
                                        </p>
                                        <div style="display:flex;align-items:center;gap:8px;margin-top:8px">
                                            <button type="button" class="btn btn-ghost sm" aria-label="Kurangi {{ $item['name'] }}" onclick="cartChangeQty('{{ $key }}', -1)">&minus;</button>
                                            <output class="qty-num" style="min-width:24px;text-align:center;font-weight:800">{{ $item['qty'] }}</output>
                                            <button type="button" class="btn btn-ghost sm" aria-label="Tambah {{ $item['name'] }}" onclick="cartChangeQty('{{ $key }}', 1)">+</button>
                                        </div>
                                    </div>
                                    <div class="oc-acts"><button type="button" class="btn btn-ghost sm" aria-label="Hapus {{ $item['name'] }}" onclick="cartRemove('{{ $key }}')">Hapus</button></div>
                                </article>
                            @endforeach
                        </div>
                    </section>
                </form>
            </div>

            <aside class="aside" aria-label="Ringkasan keranjang">
                <section class="card">
                    <h3>Ringkasan</h3>
                    <div class="rows">
                        <div><span>Produk dipilih</span><b id="summarySelectedCount">{{ count($cart) }}</b></div>
                        <div class="tot"><span>Total</span><b id="summaryTotal">{{ rp(array_sum(array_column($cart, 'subtotal'))) }}</b></div>
                    </div>
                    <button type="submit" form="cartForm" id="btnCheckout" class="btn btn-sun wide mt">Checkout</button>
                </section>
            </aside>
        </div>
    @endif
</div>
</div>
@endsection

@push('scripts')
<script>
    const updateUrl = '{{ route('preloved.cart.update') }}';
    const removeUrl = '{{ route('preloved.cart.remove') }}';
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

    function recalcSummary() {
        let selectedCount = 0, total = 0;
        document.querySelectorAll('.cart-item').forEach(row => {
            const checked = row.querySelector('.item-checkbox').checked;
            row.style.opacity = checked ? '' : '.55';
            if (checked) {
                const qty = parseInt(row.querySelector('.qty-num').textContent, 10);
                const price = parseInt(row.querySelector('[data-price]').dataset.price, 10);
                selectedCount += 1;
                total += qty * price;
            }
        });

        const cnt = document.getElementById('summarySelectedCount');
        if (!cnt) return;
        cnt.textContent = selectedCount;
        document.getElementById('summaryTotal').textContent = 'Rp' + total.toLocaleString('id-ID');
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
        const row = document.querySelector('.cart-item[data-key="' + key + '"]');
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
            document.querySelector('.cart-item[data-key="' + key + '"]')?.remove();
            const remaining = document.querySelectorAll('.cart-item').length;
            document.getElementById('totalItemCount').textContent = remaining + ' produk';
            if (remaining === 0) { window.location.reload(); return; }
            recalcSummary();
        });
    }

    recalcSummary();
</script>
@endpush
