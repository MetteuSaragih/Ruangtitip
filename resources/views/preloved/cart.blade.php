<<<<<<< HEAD
@extends('layouts.ruang-titip')
=======
﻿@extends('layouts.dashboard')

>>>>>>> hostinger/main
@section('title', 'Keranjang')

@php function rp($n){ return 'Rp'.number_format($n,0,',','.'); } @endphp

@push('styles')
<style>
.empty-cart{margin:40px auto 0;max-width:520px;text-align:center;padding:40px 24px;background:var(--paper);border:1.5px dashed var(--line-strong);border-radius:20px}
.empty-cart img{width:110px;margin:0 auto 16px}
.empty-cart h1{font-size:28px;font-weight:800}
.empty-cart p{color:var(--body);margin:8px 0 20px}
.cart-item{display:flex;align-items:flex-start;gap:14px;padding:16px;border-radius:16px;background:var(--paper);border:1.5px solid var(--line-strong);margin-bottom:12px;transition:border-color .15s,background .15s}
.cart-item.off{background:var(--cream);border-color:var(--line)}
.cart-item input[type="checkbox"]{width:19px;height:19px;margin-top:6px;accent-color:var(--tape);flex-shrink:0}
.cart-item .th{width:68px;height:68px;border-radius:12px;background:var(--sand);display:grid;place-items:center;overflow:hidden;flex-shrink:0}
.cart-item .th img{width:100%;height:100%;object-fit:cover}
.cart-item .t{flex:1;min-width:0}
.cart-item .tag{display:inline-block;font-size:10px;font-weight:700;padding:2px 8px;border-radius:999px;background:var(--depot-light);color:#1F4535;margin-bottom:6px}
.cart-item .tag.packing{background:#E1F0FB;color:#1E5A85}
.cart-item strong{display:block;font-size:15px;line-height:1.3}
.cart-item .sub{font-size:12px;color:var(--muted);margin-top:2px}
.cart-item .price{font-weight:700;color:var(--tape-dark);margin-top:6px}
.cart-item .qty{margin-top:10px}
.cart-item .qty button{width:30px;height:30px;font-size:15px}
.cart-item .qty output{min-width:22px;font-size:14px}
.cart-item .rm{background:none;border:0;color:var(--muted);cursor:pointer;padding:4px;flex-shrink:0}
.cart-item .rm:hover{color:var(--tape)}
.select-all{display:flex;align-items:center;justify-content:space-between;padding:14px 16px;border-radius:14px;background:var(--sand);margin-bottom:16px;font-weight:700;font-size:14px}
.select-all label{display:flex;align-items:center;gap:10px;cursor:pointer}
.select-all input{width:19px;height:19px;accent-color:var(--tape)}
</style>
@endpush

@section('content')
<<<<<<< HEAD
<main class="wrap">
  @if ($errors->any())
    <p class="err" style="margin-top:20px">{{ $errors->first() }}</p>
  @endif

  @if (empty($cart))
    <section class="empty-cart">
      <img src="{{ asset('assets/ruru.webp') }}" alt="" width="110" height="129">
      <h1>Keranjangmu masih kosong</h1>
      <p>Pilih kardus, lakban, atau barang preloved dulu, ya.</p>
      <div style="display:flex;gap:10px;justify-content:center;flex-wrap:wrap">
        <a class="btn btn-primary" href="{{ route('packing.index') }}">Belanja perlengkapan</a>
        <a class="btn btn-outline" href="{{ route('preloved.index') }}">Lihat preloved</a>
      </div>
    </section>
  @else
    <div class="layout" style="margin-top:32px">
      <div>
        <div class="step-head" style="margin-bottom:16px">
          <h1>Keranjang</h1>
          <p>Produk packing dan preloved yang kamu pilih.</p>
=======
<div class="pt-6 pb-10 max-w-xl mx-auto">

    <div class="mb-5">
        <h1 class="text-xl font-extrabold text-white font-display">Keranjang</h1>
        <p class="text-xs mt-0.5" style="color:rgba(255,255,255,0.4);">Produk packing dan preloved yang kamu pilih</p>
    </div>

    @if ($errors->any())
        <div class="flex items-center gap-3 rounded-xl px-4 py-3 mb-4 text-sm" style="background:rgba(239,68,68,0.1);border:1px solid rgba(239,68,68,0.3);color:#f87171;">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            {{ $errors->first() }}
>>>>>>> hostinger/main
        </div>

<<<<<<< HEAD
=======
    @if (empty($cart))
        <div class="rounded-2xl py-16 flex flex-col items-center text-center" style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.09);">
            <div class="relative inline-flex mx-auto mb-5">
                <div class="absolute inset-0 rounded-3xl blur-xl opacity-25" style="background:linear-gradient(135deg,#0284c7,#38bdf8);"></div>
                <div class="relative w-20 h-20 rounded-3xl flex items-center justify-center" style="background:linear-gradient(135deg,rgba(2,132,199,0.2),rgba(56,189,248,0.1));border:1px solid rgba(2,132,199,0.35);">
                    <svg class="w-9 h-9" fill="none" stroke="#38bdf8" stroke-width="1.5" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 002 1.61h9.72a2 2 0 001.95-1.57L23 6H6"/>
                    </svg>
                </div>
            </div>
            <p class="text-sm font-bold text-white mb-1">Keranjangmu masih kosong</p>
            <p class="text-xs mt-1 mb-6" style="color:rgba(255,255,255,0.4);">Yuk, jelajahi produk packing dan preloved pilihan</p>
            <div class="flex items-center justify-center gap-3">
                <a href="{{ route('preloved.index') }}" class="px-5 py-2.5 rounded-xl text-xs font-bold text-white transition-all hover:opacity-90" style="background:linear-gradient(135deg,#7c3aed,#6366f1);">Toko Preloved</a>
                <a href="{{ route('packing.index') }}" class="px-5 py-2.5 rounded-xl text-xs font-bold transition-all hover:bg-violet-900/20" style="border:1.5px solid rgba(124,58,237,0.4);color:#a78bfa;">Toko Packing</a>
            </div>
        </div>
    @else
>>>>>>> hostinger/main
        <form method="POST" action="{{ route('preloved.cart.checkout') }}" id="cartForm">
          @csrf
          <div class="select-all">
            <label>
              <input type="checkbox" id="selectAll" checked onchange="toggleSelectAll(this.checked)">
              Pilih Semua
            </label>
            <span id="totalItemCount">{{ count($cart) }} produk</span>
          </div>

<<<<<<< HEAD
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
                    <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="var(--tape-dark)" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M21 8 12 3 3 8v8l9 5 9-5V8z"/><path d="M3 8l9 5 9-5"/><path d="M12 13v8"/></svg>
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
=======
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
                                <button type="button" class="w-7 h-7 rounded-lg flex items-center justify-center text-sm" style="background:rgba(255,255,255,0.07);color:rgba(255,255,255,0.7);border:1px solid rgba(255,255,255,0.1);" onclick="cartChangeQty(‘{{ $key }}’, -1)">&minus;</button>
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
                        onclick="if(!this.disabled){this.disabled=true;this.innerHTML='<svg class=\'w-4 h-4 animate-spin inline\' fill=\'none\' viewBox=\'0 0 24 24\'><circle class=\'opacity-25\' cx=\'12\' cy=\'12\' r=\'10\' stroke=\'currentColor\' stroke-width=\'4\'></circle><path class=\'opacity-75\' fill=\'currentColor\' d=\'M4 12a8 8 0 018-8v8z\'></path></svg> Memproses...';}"
                        class="w-full mt-4 py-3.5 rounded-2xl font-bold text-sm text-white transition-all hover:scale-[1.02] disabled:opacity-40 flex items-center justify-center gap-2"
                        style="background:linear-gradient(135deg,#7c3aed,#6366f1);box-shadow:0 6px 20px rgba(124,58,237,0.4);">
                    Checkout
>>>>>>> hostinger/main
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
            <img src="{{ asset('assets/ruru.webp') }}" alt="">
            <p>Tip dari Ruru: mau titip barang juga? Kardus bisa dibawakan tim saat jemput.</p>
          </div>
        </div>
      </aside>
    </div>
  @endif
</main>

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
