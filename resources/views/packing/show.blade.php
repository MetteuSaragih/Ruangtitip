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

<<<<<<< HEAD
  <div class="detail">
    <div class="media">
      <div class="main">
        @if ($product->primary_image)
          <img src="{{ asset('storage/'.$product->primary_image) }}" alt="{{ $product->name }}">
        @else
          <svg width="120" height="120" viewBox="0 0 24 24" fill="none" stroke="var(--tape-dark)" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round"><path d="M21 8 12 3 3 8v8l9 5 9-5V8z"/><path d="M3 8l9 5 9-5"/><path d="M12 13v8"/></svg>
=======
    {{-- Kembali --}}
    <a href="{{ route('packing.index') }}"
       class="flex items-center gap-1.5 text-sm mb-4 transition-colors hover:text-violet-300"
       style="color:rgba(255,255,255,0.4);">
        <x-lucide-chevron-left class="w-4 h-4" /> Kembali
    </a>

    {{-- Galeri --}}
    @php
        $images = !empty($product->images) ? $product->images : ($product->primary_image ? [$product->primary_image] : []);
        $lbSrcsStr = json_encode(array_values(array_map(fn($img) => asset('storage/'.$img), $images)));
    @endphp
    <div class="relative rounded-2xl mb-3 rt-carousel"
         style="aspect-ratio:1/1;background:rgba(124,58,237,0.1);overflow:hidden;">
        @forelse ($images as $i => $img)
            <img src="{{ asset('storage/'.$img) }}" alt="{{ $product->name }}"
                 class="rt-slide {{ $i === 0 ? 'active' : '' }}"
                 onclick="rtLbOpen({{ $lbSrcsStr }}, {{ $i }})"
                 style="cursor:zoom-in;">
        @empty
            <div class="absolute inset-0 flex items-center justify-center">
                <x-lucide-package class="w-20 h-20" style="color:#a78bfa;" />
            </div>
        @endforelse
        @if ($product->discount)
            <div class="absolute top-3 left-3 px-2.5 py-1 rounded-full text-xs font-bold"
                 style="background:#ef4444;color:white;z-index:2;">
                -{{ $product->discount }}%
            </div>
>>>>>>> hostinger/main
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
<<<<<<< HEAD
            <button type="button" class="btn btn-primary" id="buy" {{ $product->stock ? '' : 'disabled' }}>{{ $product->stock ? 'Beli sekarang' : 'Stok habis' }}</button>
          </div>
=======
            <button type="button" class="rt-carousel-btn rt-next" onclick="event.preventDefault();event.stopPropagation();rtCarouselNav(this,1)">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="m9 18 6-6-6-6"/></svg>
            </button>
            <div class="rt-carousel-dots" style="bottom:10px;">
                @foreach ($images as $i => $img)
                    <span class="{{ $i === 0 ? 'active' : '' }}"></span>
                @endforeach
            </div>
        @endif
        <div class="absolute bottom-2 right-2 flex items-center gap-1 px-2 py-1 rounded-lg text-[9px] font-medium pointer-events-none"
             style="background:rgba(0,0,0,0.45);color:rgba(255,255,255,0.6);">
            <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/><line x1="11" y1="8" x2="11" y2="14"/><line x1="8" y1="11" x2="14" y2="11"/></svg>
            Klik untuk perbesar
        </div>
    </div>

    {{-- Thumbnail strip --}}
    @if (count($images) > 1)
    <div class="rt-thumb-strip mb-4" id="pkThumbStrip">
        @foreach ($images as $i => $img)
            <button type="button" class="{{ $i === 0 ? 'active' : '' }}"
                    onclick="rtGalleryThumbPk(this, {{ $i }})">
                <img src="{{ asset('storage/'.$img) }}" alt="">
            </button>
        @endforeach
    </div>
    <script>
    function rtGalleryThumbPk(btn, idx) {
        const carousel = btn.closest('.max-w-xl').querySelector('.rt-carousel');
        if (!carousel) return;
        const slides = carousel.querySelectorAll('.rt-slide');
        slides.forEach((s, i) => s.classList.toggle('active', i === idx));
        carousel.querySelectorAll('.rt-carousel-dots span').forEach((d, i) => d.classList.toggle('active', i === idx));
        btn.closest('#pkThumbStrip').querySelectorAll('button').forEach((b, i) => b.classList.toggle('active', i === idx));
    }
    </script>
    @endif

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
>>>>>>> hostinger/main
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
