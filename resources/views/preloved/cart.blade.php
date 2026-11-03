@extends('layouts.rt')
@section('title', 'Keranjang')

@php
    if (! function_exists('rp')) {
        function rp($n) { return 'Rp' . number_format($n, 0, ',', '.'); }
    }
@endphp

@push('styles')
<style>
.select-all { display: flex; justify-content: space-between; align-items: center; padding: 12px 4px; font-weight: 700; font-size: 14.5px; }
.select-all label { display: flex; align-items: center; gap: 10px; cursor: pointer; }
.select-all input, .cart-item > input { width: 20px; height: 20px; flex: none; }
#cartItems { display: grid; gap: 12px; }
.cart-item { display: grid; grid-template-columns: auto 72px minmax(0, 1fr) auto; gap: 14px; align-items: start; padding: 16px; background: var(--surface); border: 1px solid var(--line); border-radius: 20px; }
.cart-item.off { opacity: .55; }
.cart-item .th { width: 72px; height: 72px; border-radius: 16px; background: var(--alt); display: grid; place-items: center; overflow: hidden; }
.cart-item .th img { width: 100%; height: 100%; object-fit: cover; }
.cart-item .th svg { color: var(--muted); }
.cart-item .tag { display: inline-block; font-size: 11px; font-weight: 700; padding: 2px 9px; border-radius: 999px; background: var(--ok-bg); color: var(--ok-ink); margin-bottom: 6px; }
.cart-item .tag.packing { background: var(--info-bg); color: var(--info-ink); }
.cart-item strong { display: block; font-size: 15.5px; line-height: 1.3; }
.cart-item .sub { font-size: 13px; color: var(--muted); }
.cart-item .price { font-family: var(--display); font-weight: 700; color: var(--tape-dark); margin: 4px 0 8px; }
.cart-item .rm { width: 40px; height: 40px; border: 0; background: none; border-radius: 12px; color: var(--muted); display: grid; place-items: center; }
.cart-item .rm:hover { background: var(--alt); color: var(--err-ink); }
.empty-cart { display: grid; justify-items: center; text-align: center; gap: 10px; padding: 48px 20px; background: var(--surface); border: 1px solid var(--line); border-radius: var(--r); }
.empty-cart img { width: 110px; height: auto; }
.empty-cart p { color: var(--muted); }
@media (max-width: 560px) { .cart-item { grid-template-columns: auto 56px minmax(0, 1fr); } .cart-item .rm { grid-column: 3; justify-self: end; } .cart-item .th { width: 56px; height: 56px; } }
</style>
@endpush

@section('content')
<div class="wrap">
  @if ($errors->any())
    <p class="err" style="margin-top:20px">{{ $errors->first() }}</p>
  @endif

  @if (empty($cart))
    <section class="empty-cart">
      <img src="{{ asset('assets/ruru/ruru-kosong.webp') }}" alt="" width="110" height="119">
      <h1>Keranjangmu masih kosong</h1>
      <p>Pilih kardus, lakban, atau barang preloved dulu, ya.</p>
      <div style="display:flex;gap:10px;justify-content:center;flex-wrap:wrap">
        <a class="btn btn-primary" href="{{ route('packing.index') }}">Belanja perlengkapan</a>
        <a class="btn btn-outline" href="{{ route('preloved.index') }}">Lihat preloved</a>
      </div>
    </section>
  @else
    <div class="layout">
      <div>
        <div class="step-head" style="margin-bottom:16px">
          <h1>Keranjang</h1>
          <p>Produk packing dan preloved yang kamu pilih.</p>
        </div>

        <form method="POST" action="{{ route('preloved.cart.checkout') }}" id="cartForm">
          @csrf
          <div class="select-all">
            <label>
              <input type="checkbox" id="selectAll" checked onchange="toggleSelectAll(this.checked)">
              Pilih Semua
            </label>
            <span id="totalItemCount">{{ count($cart) }} produk</span>
          </div>

          <div id="cartItems">
            @foreach ($cart as $key => $item)
              @php $type = $item['type'] ?? 'preloved'; @endphp
              <div class="cart-item" data-key="{{ $key }}">
                <input type="checkbox" name="selected[]" value="{{ $key }}" class="item-checkbox" checked onchange="recalcSummary()">
                <span class="th">
                  @if (!empty($item['image']))
                    @if ($type === 'packing')
                      <img src="{{ asset('storage/'.$item['image']) }}" alt="{{ $item['name'] }}">
                    @else
                      <img src="{{ \Illuminate\Support\Facades\Storage::url($item['image']) }}" alt="{{ $item['name'] }}">
                    @endif
                  @else
                    <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M21 8 12 3 3 8v8l9 5 9-5V8z"/><path d="M3 8l9 5 9-5"/><path d="M12 13v8"/></svg>
                  @endif
                </span>
                <div class="t">
                  <span class="tag {{ $type === 'packing' ? 'packing' : '' }}">{{ $type === 'packing' ? 'Toko Packing' : 'Preloved' }}</span>
                  <strong>{{ $item['name'] }}</strong>
                  @if ($type === 'packing')
                    <p class="sub">Satuan: {{ $item['unit'] ?? 'pcs' }}</p>
                  @elseif (!empty($item['condition_label']))
                    <p class="sub">{{ $item['condition_label'] }}</p>
                  @endif
                  <p class="price" data-price="{{ $item['price'] }}">{{ rp($item['price']) }}</p>
                  <div class="qty">
                    <button type="button" aria-label="Kurangi {{ $item['name'] }}" onclick="cartChangeQty('{{ $key }}', -1)">&minus;</button>
                    <output class="qty-num">{{ $item['qty'] }}</output>
                    <button type="button" aria-label="Tambah {{ $item['name'] }}" onclick="cartChangeQty('{{ $key }}', 1)">+</button>
                  </div>
                </div>
                <button type="button" class="rm" aria-label="Hapus {{ $item['name'] }}" onclick="cartRemove('{{ $key }}')">
                  <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2m3 0-1 14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2L4 6"/></svg>
                </button>
              </div>
            @endforeach
          </div>
        </form>
      </div>

      <aside class="summary" aria-label="Ringkasan keranjang">
        <div class="sec">
          <h3>Ringkasan</h3>
          <div style="display:flex;justify-content:space-between;font-size:15px">
            <span style="color:var(--body)">Produk dipilih</span>
            <strong id="summarySelectedCount">{{ count($cart) }}</strong>
          </div>
        </div>
        <div class="foot">
          <div class="total"><span style="font-weight:700">Total</span><strong id="summaryTotal">{{ rp(array_sum(array_column($cart, 'subtotal'))) }}</strong></div>
          <button type="submit" form="cartForm" id="btnCheckout" class="btn btn-primary" style="width:100%;margin-top:16px">Checkout</button>
          <div class="ruru-tip">
            <img src="{{ asset('assets/ruru/ruru-wajah-happy.webp') }}" alt="">
            <p>Tip dari Ruru: mau titip barang juga? Kardus bisa dibawakan tim saat jemput.</p>
          </div>
        </div>
      </aside>
    </div>
  @endif
</div>

@push('scripts')
<script>
    const updateUrl = '{{ route('preloved.cart.update') }}';
    const removeUrl = '{{ route('preloved.cart.remove') }}';
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

    function recalcSummary() {
        let selectedCount = 0, total = 0;
        document.querySelectorAll('.cart-item').forEach(row => {
            const checked = row.querySelector('.item-checkbox').checked;
            row.classList.toggle('off', !checked);
            if (checked) {
                const qty = parseInt(row.querySelector('.qty-num').textContent, 10);
                const price = parseInt(row.querySelector('[data-price]').dataset.price, 10);
                selectedCount += 1;
                total += qty * price;
            }
        });

        document.getElementById('summarySelectedCount').textContent = selectedCount;
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
@endsection
