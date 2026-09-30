@extends('layouts.ruang-titip')
@section('title', $product->name)

@php function rp($n){ return 'Rp'.number_format($n,0,',','.'); } @endphp

@push('styles')
<style>
.detail{display:grid;grid-template-columns:1fr 1fr;gap:40px;margin-top:20px;align-items:start}
.media{position:sticky;top:96px}
.media .main{position:relative;aspect-ratio:1;background:var(--sand);border:1.5px solid var(--ink);border-radius:20px;display:grid;place-items:center;overflow:hidden}
.media .main img{width:100%;height:100%;object-fit:cover}
.p-info .cat{font-size:14px;font-weight:700;color:var(--depot)}
.p-info h1{font-size:clamp(28px,3vw,38px);font-weight:800;letter-spacing:-1.1px;margin-top:6px}
.p-info .meta{display:flex;align-items:center;gap:10px;margin-top:10px;flex-wrap:wrap}
.p-info .meta span{font-size:14px;font-weight:700;padding:4px 10px;border-radius:999px;background:var(--depot-light);color:#1F4535}
.p-info .price{font-family:var(--font-display);font-size:36px;font-weight:800;color:var(--tape-dark);margin-top:16px;line-height:1;display:flex;align-items:baseline;gap:10px}
.p-info .price s{font-family:var(--font-body);font-size:16px;font-weight:500;color:var(--muted)}
.p-info .desc{margin-top:20px;color:var(--body);font-size:16px;line-height:1.6}
.buy{margin-top:24px;padding:20px;background:var(--paper);border:1.5px solid var(--ink);border-radius:18px}
.buy .top{display:flex;justify-content:space-between;align-items:center;gap:16px}
.buy .top label{font-weight:700}
.buy .sub{display:flex;justify-content:space-between;align-items:baseline;margin-top:16px;padding-top:16px;border-top:1.5px dashed var(--line-strong)}
.buy .sub strong{font-family:var(--font-display);font-size:24px;color:var(--tape-dark)}
.buy .btns{display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-top:16px}
.pickup-note{display:flex;gap:10px;align-items:flex-start;margin-top:16px;font-size:14px;color:var(--body)}
.pickup-note svg{flex-shrink:0;margin-top:2px}
.related{margin-top:64px}
.related h2{font-size:24px;font-weight:800;letter-spacing:-.5px;margin-bottom:16px}
.rel-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:16px}
.rel{display:flex;gap:12px;align-items:center;padding:14px;background:var(--paper);border:1px solid var(--line-strong);border-radius:14px;color:var(--ink);text-decoration:none}
.rel:hover{border-color:var(--ink)}
.rel .th{width:52px;height:52px;border-radius:10px;background:var(--sand);display:grid;place-items:center;flex-shrink:0;overflow:hidden}
.rel .th img{width:100%;height:100%;object-fit:cover}
.rel strong{display:block;font-size:15px;line-height:1.3}
.rel span{font-size:14px;font-weight:700;color:var(--tape-dark)}
.toast{position:fixed;left:50%;bottom:24px;transform:translate(-50%,150%);z-index:50;display:flex;align-items:center;gap:12px;padding:12px 12px 12px 18px;background:var(--ink);color:var(--cream);border-radius:999px;font-weight:600;transition:transform .25s}
.toast.show{transform:translate(-50%,0)}
.toast a{color:var(--tape-light);font-weight:700;padding:8px 10px}
@media (max-width:900px){
  .detail{grid-template-columns:1fr}
  .media{position:static}
  .rel-grid{grid-template-columns:1fr 1fr}
}
@media (max-width:520px){.buy .btns{grid-template-columns:1fr}.rel-grid{grid-template-columns:1fr}}
</style>
@endpush

@section('content')
<main class="wrap">
  <a class="back-link" href="{{ route('packing.index') }}">
    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5M11 18l-6-6 6-6"/></svg>
    Kembali ke Toko Packing
  </a>

  <div class="detail">
    <div class="media">
      <div class="main">
        @if ($product->primary_image)
          <img src="{{ asset('storage/'.$product->primary_image) }}" alt="{{ $product->name }}">
        @else
          <svg width="120" height="120" viewBox="0 0 24 24" fill="none" stroke="var(--tape-dark)" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round"><path d="M21 8 12 3 3 8v8l9 5 9-5V8z"/><path d="M3 8l9 5 9-5"/><path d="M12 13v8"/></svg>
        @endif
      </div>
    </div>

    <div class="p-info">
      <span class="cat">{{ $product->category }}</span>
      <h1>{{ $product->name }}</h1>
      <div class="meta"><span>Stok: {{ $product->stock }}</span></div>
      <p class="price">
        {{ rp($product->price) }}
        @if ($product->discount)<s>{{ rp($product->original_price) }}</s>@endif
      </p>
      <p class="desc">{{ $product->description }}</p>

      <form id="buyForm" data-price="{{ $product->price }}" data-max="{{ $product->stock }}" data-id="{{ $product->id }}">
        <div class="buy">
          <div class="top">
            <label>Jumlah</label>
            <div class="qty">
              <button type="button" id="qtyMinus" aria-label="Kurangi jumlah">&minus;</button>
              <output id="q" aria-live="polite">1</output>
              <button type="button" id="qtyPlus" aria-label="Tambah jumlah">+</button>
            </div>
          </div>
          <div class="sub"><span>Subtotal</span><strong id="sub">{{ rp($product->price) }}</strong></div>
          <div class="btns">
            <button type="button" class="btn btn-outline" id="add">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="20" r="1.4"/><circle cx="18" cy="20" r="1.4"/><path d="M3 4h2l2.4 11.2a1.5 1.5 0 0 0 1.5 1.2h8.6a1.5 1.5 0 0 0 1.5-1.2L21 8H6.2"/></svg>
              Masukkan keranjang
            </button>
            <button type="button" class="btn btn-primary" id="buy" {{ $product->stock ? '' : 'disabled' }}>{{ $product->stock ? 'Beli sekarang' : 'Stok habis' }}</button>
          </div>
        </div>
      </form>

      <p class="pickup-note">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--depot)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
        Titip barang juga? Pesan kardus di sini, lalu pilih "Packing + Anjem RuTip" di Ruang Titip. Tim Ruru bawa kardusnya sekalian.
      </p>
    </div>
  </div>

  @if ($related->isNotEmpty())
  <section class="related" aria-labelledby="rel-title">
    <h2 id="rel-title">Biasanya dibeli bareng</h2>
    <div class="rel-grid">
      @foreach ($related as $r)
        <a class="rel" href="{{ route('packing.show', $r) }}">
          <span class="th">
            @if ($r->primary_image)
              <img src="{{ asset('storage/'.$r->primary_image) }}" alt="{{ $r->name }}">
            @else
              <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="var(--tape-dark)" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M21 8 12 3 3 8v8l9 5 9-5V8z"/><path d="M3 8l9 5 9-5"/><path d="M12 13v8"/></svg>
            @endif
          </span>
          <span><strong>{{ $r->name }}</strong><span>{{ rp($r->price) }}</span></span>
        </a>
      @endforeach
    </div>
  </section>
  @endif
</main>

<div class="toast" id="toast" role="status" aria-live="polite"><span id="toast-msg">Masuk keranjang</span><a href="{{ route('preloved.cart.index') }}">Lihat keranjang</a></div>

@push('scripts')
<script>
(function () {
    const form = document.getElementById('buyForm');
    const price = parseInt(form.dataset.price, 10);
    const max = parseInt(form.dataset.max, 10);
    const productId = form.dataset.id;
    const qOut = document.getElementById('q');
    const sub = document.getElementById('sub');
    const minus = document.getElementById('qtyMinus');
    const plus = document.getElementById('qtyPlus');
    const csrf = document.querySelector('meta[name="csrf-token"]').content;
    const cartUrl = '{{ route('preloved.cart.add') }}';
    let qty = max ? 1 : 0;

    function render() {
        qOut.textContent = qty;
        sub.textContent = 'Rp' + (price * qty).toLocaleString('id-ID');
        minus.disabled = qty <= 1;
        plus.disabled = qty >= max;
    }
    minus.addEventListener('click', () => { qty = Math.max(1, qty - 1); render(); });
    plus.addEventListener('click', () => { qty = Math.min(max, qty + 1); render(); });
    render();

    function showToast(msg) {
        document.getElementById('toast-msg').textContent = msg;
        const el = document.getElementById('toast');
        el.classList.add('show');
        clearTimeout(el._t);
        el._t = setTimeout(() => el.classList.remove('show'), 3500);
    }

    function addToCart(buyNow) {
        return fetch(cartUrl, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf },
            body: JSON.stringify({ type: 'packing', product_id: productId, qty, buy_now: buyNow }),
        }).then(r => r.json());
    }

    document.getElementById('add').addEventListener('click', function () {
        addToCart(false).then(data => {
            showToast(data.success ? 'Produk ditambahkan ke keranjang' : (data.message || 'Gagal menambahkan.'));
        }).catch(() => showToast('Terjadi kesalahan.'));
    });

    document.getElementById('buy').addEventListener('click', function () {
        addToCart(true).then(data => {
            if (data.redirect) { window.location.href = data.redirect; }
            else { showToast(data.message || 'Gagal memproses.'); }
        }).catch(() => showToast('Terjadi kesalahan.'));
    });
})();
</script>
@endpush
@endsection
