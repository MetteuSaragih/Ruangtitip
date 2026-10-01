@extends('layouts.ruang-titip')
@section('title', 'Toko Packing')

@php function rp($n){ return 'Rp'.number_format($n,0,',','.'); } @endphp

@push('styles')
<style>
.page-head{padding:40px 0 24px;display:grid;grid-template-columns:1fr auto;gap:24px;align-items:end}
.page-head h1{font-size:clamp(34px,4vw,48px);font-weight:800;letter-spacing:-1.4px}
.page-head p{color:var(--body);margin-top:6px;max-width:560px}
.tabs{display:flex;gap:8px;flex-wrap:wrap;margin-bottom:24px}
.tabs a{display:inline-flex;min-height:42px;align-items:center;padding:0 18px;border-radius:999px;border:1.5px solid var(--line-strong);background:var(--paper);font-weight:700;font-size:15px;text-decoration:none;color:var(--ink)}
.tabs a:hover{border-color:var(--ink)}
.tabs a.on{background:var(--ink);border-color:var(--ink);color:var(--cream)}

.prod-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:18px}
.prod{display:flex;flex-direction:column;background:var(--paper);border:1px solid var(--line-strong);border-radius:16px;overflow:hidden;transition:border-color .15s,transform .15s}
.prod:hover{border-color:var(--ink);transform:translateY(-2px)}
.prod .thumb{position:relative;display:grid;place-items:center;aspect-ratio:4/3;background:var(--sand);text-decoration:none;overflow:hidden}
.prod .thumb img{position:absolute;inset:0;width:100%;height:100%;object-fit:cover}
.prod .thumb .disc{position:absolute;top:10px;left:10px;font-size:11px;font-weight:700;padding:4px 9px;border-radius:999px;background:var(--tape);color:#fff}
.prod .body{padding:14px 16px 16px;display:flex;flex-direction:column;gap:2px;flex-grow:1}
.prod h2{font-family:var(--font-body);font-size:16px;font-weight:700}
.prod h2 a{color:var(--ink);text-decoration:none}
.prod h2 a:hover{color:var(--tape-dark)}
.prod .stock{font-size:13px;color:var(--muted)}
.prod .row{margin-top:auto;padding-top:12px;display:flex;justify-content:space-between;align-items:center;gap:8px;min-height:52px}
.prod .price{font-family:var(--font-display);font-size:20px;font-weight:800;color:var(--tape-dark)}
.prod .price s{display:block;font-family:var(--font-body);font-size:12px;font-weight:500;color:var(--muted)}
.prod .add{min-height:40px;padding:0 14px;border-radius:999px;border:1.5px solid var(--ink);background:var(--paper);font-weight:700;font-size:14px;cursor:pointer;display:flex;align-items:center;gap:6px;color:var(--ink)}
.prod .add:hover{background:var(--tape);color:#fff;border-color:var(--tape)}
.prod .add:disabled{border-color:var(--line-strong);color:var(--muted);background:var(--cream);cursor:not-allowed}

.cartbar{position:fixed;left:50%;bottom:20px;transform:translate(-50%,140%);z-index:35;width:min(720px,calc(100% - 32px));display:flex;align-items:center;gap:16px;padding:12px 12px 12px 20px;background:var(--ink);color:var(--cream);border-radius:999px;transition:transform .25s ease}
.cartbar.show{transform:translate(-50%,0)}
.cartbar .info{flex-grow:1;line-height:1.3}
.cartbar .info small{display:block;color:#C9C2B3;font-size:13px}
.cartbar .info strong{font-family:var(--font-display);font-size:20px}
.cartbar .btn{background:var(--tape-light);color:var(--ink);border-color:var(--tape-light);box-shadow:none}
.cartbar .btn:hover{background:#F0B98F;color:var(--ink)}
body.has-cart-items{padding-bottom:100px}

.help{margin-bottom:28px}
.tabs{margin-top:0}

@media (max-width:1080px){.prod-grid{grid-template-columns:repeat(3,1fr)}}
@media (max-width:760px){
  .prod-grid{grid-template-columns:repeat(2,1fr);gap:12px}
  .page-head{grid-template-columns:1fr}
  .help{flex-direction:column;align-items:flex-start}
  .prod .row{flex-direction:column;align-items:stretch}
  .prod .add{justify-content:center}
}
</style>
@endpush

@section('content')
<main class="wrap">
  <div class="page-head">
    <div>
      <h1>Toko Packing</h1>
      <p>Kardus, lakban, dan pelindung barang. Ambil sendiri di gudang atau dikirim ke kosmu.</p>
    </div>
  </div>

  <aside class="help">
    <img src="{{ asset('assets/ruru/ruru-troli.webp') }}" alt="" width="64" height="64">
    <div>
      <strong>Baru pertama kali packing?</strong>
      <p>Mulai dari kardus dan lakban, tim kami bantu hitung kebutuhanmu lewat WhatsApp kalau bingung.</p>
    </div>
    <a class="btn btn-outline btn-sm" href="https://wa.me/6285121091134" target="_blank" rel="noopener">Tanya dulu</a>
  </aside>

  <div class="tabs" role="group" aria-label="Filter kategori">
    @foreach ($categories as $cat)
      <a href="{{ route('packing.index', $cat === 'Semua' ? [] : ['kategori' => $cat]) }}" class="{{ $cat === $category ? 'on' : '' }}" @if($cat === $category) aria-current="true" @endif>{{ $cat }}</a>
    @endforeach
  </div>

  @if ($products->isEmpty())
    <div class="wh" style="cursor:default;padding:48px;text-align:center;">
      <p style="font-weight:700;font-size:18px;margin-bottom:6px">Belum ada produk</p>
      <p style="color:var(--muted)">Produk packing akan segera tersedia di kategori ini.</p>
    </div>
  @else
    <div class="prod-grid" id="grid">
      @foreach ($products as $p)
        @php $qty = (int) (session('cart', [])['packing_'.$p->id]['qty'] ?? 0); @endphp
        <article class="prod" data-id="{{ $p->id }}" data-stock="{{ $p->stock }}" data-qty="{{ $qty }}" data-price="{{ $p->price }}">
          <a class="thumb" href="{{ route('packing.show', $p) }}" aria-label="Detail {{ $p->name }}">
            @if ($p->discount)<span class="disc">-{{ $p->discount }}%</span>@endif
            @if ($p->primary_image)
              <img src="{{ asset('storage/'.$p->primary_image) }}" alt="{{ $p->name }}">
            @else
              <svg width="44" height="44" viewBox="0 0 24 24" fill="none" stroke="var(--tape-dark)" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M21 8 12 3 3 8v8l9 5 9-5V8z"/><path d="M3 8l9 5 9-5"/><path d="M12 13v8"/></svg>
            @endif
          </a>
          <div class="body">
            <h2><a href="{{ route('packing.show', $p) }}">{{ $p->name }}</a></h2>
            <span class="stock">Stok: {{ $p->stock }}</span>
            <div class="row">
              <span class="price">
                {{ rp($p->price) }}
                @if ($p->discount)<s>{{ rp($p->original_price) }}</s>@endif
              </span>
              @if (! $p->stock)
                <button class="add" type="button" disabled>Habis</button>
              @else
                <div class="cart-ctrl"></div>
              @endif
            </div>
          </div>
        </article>
      @endforeach
    </div>
  @endif
</main>

<div class="cartbar" id="cartbar" aria-live="polite">
  <div class="info"><small id="cb-count">0 barang di keranjang</small><strong id="cb-total">Rp0</strong></div>
  <a class="btn" href="{{ route('preloved.cart.index') }}">Lanjut bayar</a>
</div>

@push('scripts')
<script>
(function () {
    const csrf = document.querySelector('meta[name="csrf-token"]').content;
    const addUrl = '{{ route('preloved.cart.add') }}';
    const updateUrl = '{{ route('preloved.cart.update') }}';

    function qtyControl(qty, max) {
        return qty > 0
            ? '<div class="qty"><button type="button" data-d="-1" aria-label="Kurangi">&minus;</button><output>' + qty + '</output><button type="button" data-d="1" aria-label="Tambah"' + (qty >= max ? ' disabled' : '') + '>+</button></div>'
            : '<button class="add" type="button" data-d="1"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"><path d="M12 5v14M5 12h14"/></svg>Keranjang</button>';
    }

    function wire(card) {
        const ctrl = card.querySelector('.cart-ctrl');
        if (!ctrl) return;
        const id = card.dataset.id;
        let qty = parseInt(card.dataset.qty, 10) || 0;
        const max = parseInt(card.dataset.stock, 10) || 0;

        function draw() {
            ctrl.innerHTML = qtyControl(qty, max);
            ctrl.querySelectorAll('[data-d]').forEach(function (b) {
                b.addEventListener('click', function () {
                    const delta = parseInt(b.dataset.d, 10);
                    const newQty = Math.max(0, Math.min(max, qty + delta));
                    if (newQty === qty) return;
                    qty = newQty;
                    card.dataset.qty = qty;
                    draw();
                    sync(id, qty);
                });
            });
        }
        draw();
    }

    let cartTotals = {};
    function refreshBar() {
        const count = Object.values(cartTotals).reduce((a, v) => a + v.qty, 0);
        const total = Object.values(cartTotals).reduce((a, v) => a + v.qty * v.price, 0);
        const bar = document.getElementById('cartbar');
        bar.classList.toggle('show', count > 0);
        document.body.classList.toggle('has-cart-items', count > 0);
        document.getElementById('cb-count').textContent = count + ' barang di keranjang';
        document.getElementById('cb-total').textContent = 'Rp' + total.toLocaleString('id-ID');
    }

    function sync(id, qty) {
        if (qty === 0) {
            fetch('{{ route('preloved.cart.remove') }}', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf },
                body: JSON.stringify({ product_id: 'packing_' + id }),
            }).then(() => { delete cartTotals[id]; refreshBar(); });
            return;
        }
        const isNew = !(id in cartTotals);
        const req = isNew
            ? fetch(addUrl, { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf }, body: JSON.stringify({ type: 'packing', product_id: id, qty }) })
            : fetch(updateUrl, { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf }, body: JSON.stringify({ product_id: 'packing_' + id, qty }) });
        req.then(r => r.json()).then(data => {
            if (data.success === false) return;
            refreshBar();
        });
    }

    document.querySelectorAll('.prod').forEach(function (card) {
        const id = card.dataset.id;
        const qty = parseInt(card.dataset.qty, 10) || 0;
        const price = parseInt(card.dataset.price, 10) || 0;
        if (qty > 0) cartTotals[id] = { qty, price };
        wire(card);
    });
    refreshBar();
})();
</script>
@endpush
@endsection
