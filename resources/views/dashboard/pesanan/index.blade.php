@extends('layouts.ruang-titip')
@section('title', 'Pesanan Saya')

@php function rp($n){ return 'Rp'.number_format($n,0,',','.'); } @endphp

@push('styles')
<style>
.page{max-width:880px;margin:0 auto}
.btn-sm{min-height:40px;padding:0 16px;font-size:14px}

.overview{display:grid;grid-template-columns:repeat(3,1fr);gap:12px;margin-bottom:24px}
.overview div{padding:16px 18px;background:var(--paper);border:1px solid var(--line-strong);border-radius:14px}
.overview small{display:block;font-size:13px;color:var(--muted);font-weight:600}
.overview strong{font-family:var(--font-display);font-size:26px;font-weight:800;line-height:1.2}
.overview .warn strong{color:var(--tape-dark)}

.tabs{display:grid;grid-template-columns:1fr 1fr;gap:6px;padding:6px;background:var(--sand);border-radius:999px}
.tabs a{min-height:46px;border-radius:999px;font-weight:700;font-size:15px;display:flex;align-items:center;justify-content:center;gap:8px;text-decoration:none;color:var(--muted)}
.tabs a.on{background:var(--paper);box-shadow:0 0 0 1.5px var(--ink);color:var(--ink)}
.tabs .n{min-width:24px;height:24px;padding:0 7px;border-radius:999px;background:var(--ink);color:var(--cream);font-size:12px;display:grid;place-items:center}
.tabs a:not(.on) .n{background:var(--line-strong);color:var(--ink)}

.type-filters{display:flex;gap:8px;flex-wrap:wrap;margin:16px 0 20px}
.type-filters a{display:inline-flex;min-height:38px;align-items:center;padding:0 14px;border-radius:999px;border:1.5px solid var(--line-strong);background:var(--paper);font-weight:600;font-size:14px;text-decoration:none;color:var(--ink)}
.type-filters a:hover{border-color:var(--ink)}
.type-filters a.on{background:var(--ink);border-color:var(--ink);color:var(--cream)}

.list{display:flex;flex-direction:column;gap:16px}
.order{background:var(--paper);border:1px solid var(--line-strong);border-radius:18px;overflow:hidden;text-decoration:none;color:var(--ink);display:block}
.order.attn{border:1.5px solid var(--tape);box-shadow:4px 4px 0 var(--tape)}
.o-head{display:flex;align-items:center;gap:12px;padding:16px 20px;border-bottom:1px solid var(--line)}
.o-head .ic{width:40px;height:40px;border-radius:12px;border:1.5px solid var(--ink);display:grid;place-items:center;flex-shrink:0;overflow:hidden}
.o-head .ic img{width:100%;height:100%;object-fit:cover}
.o-head .who{flex-grow:1;line-height:1.3;min-width:0}
.o-head .who strong{font-size:15px;display:block;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
.o-head .who small{display:block;font-size:13px;color:var(--muted)}
.o-head code{font-family:ui-monospace,Menlo,monospace;font-size:13px}
.status{font-size:13px;font-weight:700;padding:5px 12px;border-radius:999px;border:1px solid;white-space:nowrap}
.s-pay{background:var(--tape-soft);border-color:var(--tape);color:var(--tape-dark)}
.s-run{background:var(--cream);border-color:var(--ink);color:var(--ink)}
.s-store{background:var(--depot-light);border-color:var(--depot);color:#1F4535}
.s-done{background:var(--line);border-color:var(--line-strong);color:var(--muted)}
.o-body{padding:18px 20px;display:flex;flex-direction:column;gap:16px}
.o-items{font-weight:600}
.o-items small{display:block;font-weight:500;color:var(--muted);font-size:14px}

.period{padding:14px 16px;border-radius:12px;background:var(--cream)}
.period .row{display:flex;justify-content:space-between;gap:12px;font-size:14px}
.period .row b{font-weight:700}
.period .bar{height:8px;border-radius:999px;background:var(--line);margin-top:10px;overflow:hidden}
.period .bar i{display:block;height:100%;border-radius:999px;background:var(--depot)}
.period.soon{background:var(--tape-soft)}
.period.soon .bar i{background:var(--tape)}

.track{display:grid;list-style:none;margin:0;padding:0;gap:4px}
.track li{display:flex;flex-direction:column;gap:6px;font-size:12px;font-weight:600;color:var(--muted);line-height:1.25}
.track li::before{content:"";height:5px;border-radius:999px;background:var(--line)}
.track li.done::before{background:var(--depot)}
.track li.now{color:var(--ink)}
.track li.now::before{background:var(--tape)}

.pay-due{display:flex;align-items:center;gap:12px;padding:14px 16px;border-radius:12px;background:var(--tape-soft);font-size:15px}
.pay-due svg{flex-shrink:0}
.pay-due b{font-family:ui-monospace,Menlo,monospace;font-size:16px;color:var(--tape-dark)}

.o-hist summary{list-style:none;cursor:pointer;display:inline-flex;align-items:center;gap:6px;font-weight:600;font-size:14px;color:var(--tape-dark)}
.o-hist summary::-webkit-details-marker{display:none}
.o-hist[open] summary svg{transform:rotate(180deg)}
.timeline{list-style:none;margin:14px 0 0;padding:0 0 0 18px;border-left:2px solid var(--line)}
.timeline li{position:relative;padding:0 0 14px 14px;font-size:14px}
.timeline li::before{content:"";position:absolute;left:-25px;top:5px;width:12px;height:12px;border-radius:50%;background:var(--paper);border:2px solid var(--depot)}
.timeline li:first-child::before{background:var(--depot)}
.timeline time{display:block;font-size:13px;color:var(--muted)}

.o-foot{display:flex;justify-content:space-between;align-items:center;gap:12px;flex-wrap:wrap;padding:14px 20px;border-top:1px solid var(--line);background:var(--cream)}
.o-foot .total small{display:block;font-size:13px;color:var(--muted)}
.o-foot .total strong{font-family:var(--font-display);font-size:20px}
.o-foot .acts{display:flex;gap:8px;flex-wrap:wrap}

.empty-orders{text-align:center;padding:48px 24px;background:var(--paper);border:1.5px dashed var(--line-strong);border-radius:20px}
.empty-orders img{width:110px;margin:0 auto 14px}
.empty-orders h2{font-size:26px;font-weight:800}
.empty-orders p{color:var(--body);margin:6px auto 22px;max-width:420px}
.empty-orders .go{display:grid;grid-template-columns:repeat(3,1fr);gap:10px;max-width:560px;margin:0 auto}
.empty-orders .go a{display:flex;flex-direction:column;align-items:center;gap:8px;padding:16px 10px;border:1.5px solid var(--line-strong);border-radius:14px;color:var(--ink);text-decoration:none;font-weight:700;font-size:14px}
.empty-orders .go a:hover{border-color:var(--ink);background:var(--cream)}
.empty-orders .go span{width:40px;height:40px;border-radius:12px;border:1.5px solid var(--ink);display:grid;place-items:center}

@media (max-width:640px){
  .overview{grid-template-columns:1fr 1fr}
  .overview div:last-child{grid-column:span 2}
  .o-head{flex-wrap:wrap}
  .o-head .status{order:3;margin-left:52px}
  .track li span{display:none}
  .track li.now span{display:inline}
  .empty-orders .go{grid-template-columns:1fr}
  .o-foot .acts{width:100%}
  .o-foot .acts .btn{flex:1}
}
</style>
@endpush

@section('content')
<main class="wrap">
  <div class="page">
    <div class="page-head" style="padding-bottom:20px">
      <div>
        <h1>Pesanan Saya</h1>
        <p>Pantau titipan, belanjaan, dan riwayat pesananmu di satu tempat.</p>
      </div>
    </div>

    <div class="overview" aria-label="Ringkasan titipan">
      <div><small>Titipan aktif</small><strong>{{ $overview['active'] }}</strong></div>
      <div class="{{ $overview['soonest_end_days'] !== null && $overview['soonest_end_days'] <= 7 ? 'warn' : '' }}">
        <small>Titipan terdekat berakhir</small>
        <strong>{{ $overview['soonest_end_days'] !== null ? $overview['soonest_end_days'].' hari lagi' : '–' }}</strong>
      </div>
      <div class="{{ $overview['pending_payment'] ? 'warn' : '' }}"><small>Menunggu pembayaran</small><strong>{{ $overview['pending_payment'] }}</strong></div>
    </div>

    <div class="tabs" role="tablist" aria-label="Status pesanan">
      <a href="{{ route('pesanan.index', ['tab' => 'berlangsung', 'jenis' => $type]) }}" class="{{ $tab === 'berlangsung' ? 'on' : '' }}" role="tab" aria-selected="{{ $tab === 'berlangsung' ? 'true' : 'false' }}">Sedang berlangsung <span class="n">{{ $countBerlangsung }}</span></a>
      <a href="{{ route('pesanan.index', ['tab' => 'selesai', 'jenis' => $type]) }}" class="{{ $tab === 'selesai' ? 'on' : '' }}" role="tab" aria-selected="{{ $tab === 'selesai' ? 'true' : 'false' }}">Selesai <span class="n">{{ $countSelesai }}</span></a>
    </div>

    <div class="type-filters" role="group" aria-label="Jenis pesanan">
      @php $types = ['semua' => 'Semua', 'titip' => 'Ruang Titip', 'packing' => 'Toko Packing', 'preloved' => 'Toko Preloved']; @endphp
      @foreach ($types as $key => $label)
        <a href="{{ route('pesanan.index', ['tab' => $tab, 'jenis' => $key]) }}" class="{{ $type === $key ? 'on' : '' }}">{{ $label }}</a>
      @endforeach
    </div>

    @php $hasAny = $orders->count() > 0 || $packingOrders->count() > 0 || $genericOrders->count() > 0; @endphp
    @if ($hasAny)
      <div class="list">
        @foreach ($orders as $order)
          @include('dashboard.pesanan._card', ['order' => $order])
        @endforeach
        @foreach ($genericOrders as $order)
          @include('dashboard.pesanan._card_order', ['order' => $order])
        @endforeach
        @foreach ($packingOrders as $order)
          @include('dashboard.pesanan._card_packing', ['order' => $order])
        @endforeach
      </div>
    @else
      <div class="empty-orders">
        <img src="{{ asset('assets/ruru.webp') }}" alt="" width="110" height="129">
        <h2>{{ $tab === 'selesai' ? 'Belum ada pesanan selesai' : 'Belum ada pesanan aktif' }}</h2>
        <p>{{ $tab === 'selesai' ? 'Pesanan yang sudah selesai akan tersimpan di sini sebagai riwayat.' : 'Ruru lagi nganggur nih. Mau titip barang, beli perlengkapan packing, atau cari barang preloved?' }}</p>
        @if ($tab !== 'selesai')
          <div class="go">
            <a href="{{ route('ruang-titip.index') }}">
              <span style="background:var(--depot-light)"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#1C1B18" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 10 12 4l9 6v10H3z"/><path d="M8 20v-6h8v6"/></svg></span>
              Titip barang
            </a>
            <a href="{{ route('packing.index') }}">
              <span style="background:var(--tape-soft)"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#1C1B18" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M21 8 12 3 3 8v8l9 5 9-5z"/><path d="m3 8 9 5 9-5M12 13v8"/></svg></span>
              Beli perlengkapan
            </a>
            <a href="{{ route('preloved.index') }}">
              <span style="background:var(--sand)"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#1C1B18" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M20.6 13.4 13.4 20.6a2 2 0 0 1-2.8 0L3 13V3h10l7.6 7.6a2 2 0 0 1 0 2.8z"/><circle cx="7.5" cy="7.5" r="1.5"/></svg></span>
              Cari preloved
            </a>
          </div>
        @endif
      </div>
    @endif
  </div>
</main>
@endsection
