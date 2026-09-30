@extends('layouts.ruang-titip')
@section('title', 'Detail Pesanan')

@php
<<<<<<< HEAD
function rp($n){ return 'Rp'.number_format($n,0,',','.'); }
$meta = $order->statusMeta();
$step = $order->statusStep();
$flow = \App\Models\TitipanOrder::FLOW;
$logisticLabel = [
    'sendiri' => 'Antar sendiri',
    'self'    => 'Antar sendiri',
    'anjem'   => 'Packing + Anjem RuTip',
    'rutip'   => 'Packing + Anjem RuTip',
    'kurir'   => 'Kurir instan',
    'instant' => 'Kurir instan',
][$order->logistic] ?? '-';
$statusClass = match ($order->status) {
    'menunggu_pembayaran' => 's-pay',
    'dalam_gudang' => 's-store',
    'selesai' => 's-done',
    default => 's-run',
};
=======
    function rp_d($n){ return 'Rp '.number_format($n,0,',','.'); }
    $meta = $order->statusMeta();
    $step = $order->statusStep();
    $flow = \App\Models\TitipanOrder::FLOW;
    $logisticLabel = [
        'self'    => 'Antar Sendiri',
        'rutip'   => 'Packing + Anjem RuTip',
        'instant' => 'Kurir Biteship',
    ][$order->logistic] ?? '-';
>>>>>>> hostinger/main
@endphp

@push('styles')
<style>
.detail-page{max-width:620px;margin:0 auto}
.d-head{display:flex;align-items:flex-start;justify-content:space-between;gap:12px;margin:24px 0}
.d-head .who{display:flex;align-items:center;gap:12px;min-width:0}
.d-head .ic{width:44px;height:44px;border-radius:12px;border:1.5px solid var(--ink);background:var(--depot-light);display:grid;place-items:center;flex-shrink:0;overflow:hidden}
.d-head .ic img{width:100%;height:100%;object-fit:cover}
.d-head h1{font-size:22px;font-weight:800}
.d-head small{display:block;color:var(--muted);font-size:13px;margin-top:2px}
.status{font-size:13px;font-weight:700;padding:5px 12px;border-radius:999px;border:1px solid;white-space:nowrap;flex-shrink:0}
.s-pay{background:var(--tape-soft);border-color:var(--tape);color:var(--tape-dark)}
.s-run{background:var(--cream);border-color:var(--ink);color:var(--ink)}
.s-store{background:var(--depot-light);border-color:var(--depot);color:#1F4535}
.s-done{background:var(--line);border-color:var(--line-strong);color:var(--muted)}

.v-track{list-style:none;margin:0;padding:0}
.v-track li{display:flex;gap:12px}
.v-track .dot{display:flex;flex-direction:column;align-items:center;flex-shrink:0}
.v-track .dot span{width:28px;height:28px;border-radius:50%;display:grid;place-items:center;background:var(--line);flex-shrink:0}
.v-track li.done .dot span{background:var(--depot);color:#fff}
.v-track li.now .dot span{background:var(--tape);color:#fff}
.v-track .bar{width:2px;flex-grow:1;min-height:24px;background:var(--line);margin:2px 0}
.v-track li.done .bar{background:var(--depot)}
.v-track .txt{padding-bottom:22px}
.v-track strong{display:block;font-size:14px;color:var(--muted)}
.v-track li.done strong,.v-track li.now strong{color:var(--ink)}
.v-track .now-tag{font-size:12px;color:var(--tape-dark);margin-top:2px}

.kv{display:flex;flex-direction:column;gap:10px;font-size:14px}
.kv .row{display:flex;justify-content:space-between;gap:12px}
.kv .row span:first-child{color:var(--muted)}
.kv .row span:last-child{font-weight:600;text-align:right}
.pay-due{padding:14px 16px;border-radius:12px;background:var(--tape-soft);color:var(--tape-dark);font-weight:600;font-size:14px}
</style>
@endpush

@section('content')
<main class="wrap">
  <div class="detail-page">
    <a class="back-link" href="{{ route('pesanan.index') }}">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5M11 18l-6-6 6-6"/></svg>
      Kembali ke Pesanan
    </a>

    <div class="d-head">
      <div class="who">
        <span class="ic">
          @if ($order->storage && $order->storage->primary_photo)
            <img src="{{ asset('storage/'.$order->storage->primary_photo) }}" alt="{{ $order->storage->name }}">
          @else
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#1C1B18" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 10 12 4l9 6v10H3z"/><path d="M8 20v-6h8v6"/></svg>
          @endif
        </span>
        <div>
          <h1>#{{ $order->code() }}</h1>
          <small>Dibuat {{ $order->created_at->format('d M Y, H:i') }}</small>
        </div>
      </div>
      <span class="status {{ $statusClass }}">{{ $meta['label'] }}</span>
    </div>

    <div class="panel">
      <h2>Status pesanan</h2>
      <ol class="v-track" style="margin-top:16px">
        @foreach ($flow as $key => $f)
          @php $i = array_search($key, array_keys($flow), true); $isLast = $i === count($flow) - 1; @endphp
          <li class="{{ $i < $step ? 'done' : ($i === $step ? 'now' : '') }}">
            <span class="dot">
              <span>
                @if ($i < $step)
                  <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                @endif
              </span>
              @if (!$isLast)<span class="bar"></span>@endif
            </span>
            <span class="txt">
              <strong>{{ $f['label'] }}</strong>
              @if ($i === $step)<span class="now-tag">Tahap saat ini</span>@endif
            </span>
          </li>
        @endforeach
      </ol>
    </div>

<<<<<<< HEAD
    <div class="panel">
      <h2>Detail penitipan</h2>
      <div class="kv" style="margin-top:14px">
        <div class="row"><span>Gudang</span><span>{{ $order->storage->name ?? '-' }}</span></div>
        <div class="row"><span>Jenis barang</span><span>{{ ucfirst($order->item_type) }}</span></div>
        <div class="row"><span>Jumlah item</span><span>{{ $order->totalItems() }} item</span></div>
        @if ($order->date_start && $order->date_end)
          <div class="row"><span>Periode</span><span>{{ $order->date_start->format('d M Y') }} – {{ $order->date_end->format('d M Y') }}</span></div>
=======
    {{-- Info penitipan --}}
    <div class="rounded-2xl p-5 mb-4" style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.09);">
        <p class="text-xs font-bold text-white mb-3">Detail Penitipan</p>
        <div class="space-y-2.5 text-xs">
            <div class="flex justify-between gap-2"><span style="color:rgba(255,255,255,0.45);">Gudang</span><span class="text-white font-medium text-right">{{ $order->storage->name ?? '-' }}</span></div>
            <div class="flex justify-between gap-2"><span style="color:rgba(255,255,255,0.45);">Jenis Barang</span><span class="text-white font-medium capitalize">{{ $order->item_type }}</span></div>
            <div class="flex justify-between gap-2"><span style="color:rgba(255,255,255,0.45);">Jumlah Item</span><span class="text-white font-medium">{{ $order->totalItems() }} item</span></div>
            @if ($order->date_start && $order->date_end)
                <div class="flex justify-between gap-2"><span style="color:rgba(255,255,255,0.45);">Periode</span><span class="text-white font-medium text-right">{{ $order->date_start->format('d M Y') }} – {{ $order->date_end->format('d M Y') }}</span></div>
            @endif
            <div class="flex justify-between gap-2"><span style="color:rgba(255,255,255,0.45);">Logistik</span><span class="text-white font-medium">{{ $logisticLabel }}</span></div>
            @if ($order->address)
                <div class="flex justify-between gap-2"><span style="color:rgba(255,255,255,0.45);">Alamat</span><span class="text-white font-medium text-right max-w-[60%]">{{ $order->address }}</span></div>
            @endif
        </div>
    </div>

    {{-- Rincian biaya --}}
    <div class="rounded-2xl p-5 mb-4" style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.09);">
        <p class="text-xs font-bold text-white mb-3">Rincian Biaya</p>
        <div class="space-y-2.5 text-xs mb-3">
            <div class="flex justify-between"><span style="color:rgba(255,255,255,0.45);">Biaya Penitipan</span><span class="text-white font-medium">{{ rp_d($order->item_subtotal) }}</span></div>
            @if ($order->courier_cost > 0)
                <div class="flex justify-between"><span style="color:rgba(255,255,255,0.45);">Biaya Logistik</span><span class="text-white font-medium">{{ rp_d($order->courier_cost) }}</span></div>
            @endif
            <div class="flex justify-between"><span style="color:rgba(255,255,255,0.45);">Biaya Layanan</span><span class="text-white font-medium">{{ rp_d($order->platform_fee) }}</span></div>
        </div>
        <div class="flex items-center justify-between pt-3" style="border-top:1px solid rgba(255,255,255,0.1);">
            <span class="text-sm font-bold text-white">Total</span>
            <span class="text-lg font-extrabold font-display" style="color:#a78bfa;">{{ rp_d($order->total) }}</span>
        </div>
    </div>

    {{-- Aksi sesuai status --}}
    @if ($order->status === 'menunggu_pembayaran')
        @if ($order->tripay_checkout_url)
            <a href="{{ $order->tripay_checkout_url }}"
               class="flex items-center justify-center gap-2 w-full py-3.5 rounded-xl text-sm font-bold text-white mb-4 transition-all hover:scale-[1.02]"
               style="background:linear-gradient(135deg,#7c3aed,#6366f1);">
                <x-lucide-credit-card class="w-4 h-4" /> Lanjutkan Pembayaran
            </a>
        @else
            <div class="flex items-center gap-2 px-3 py-2.5 rounded-xl mb-4 text-[11px]" style="background:rgba(251,191,36,0.08);border:1px solid rgba(251,191,36,0.2);color:#fbbf24;">
                <x-lucide-info class="w-3.5 h-3.5 shrink-0" /> Menunggu pembayaran.
            </div>
>>>>>>> hostinger/main
        @endif
        <div class="row"><span>Logistik</span><span>{{ $logisticLabel }}</span></div>
        @if ($order->address)
          <div class="row"><span>Alamat</span><span style="max-width:60%">{{ $order->address }}</span></div>
        @endif
      </div>
    </div>

    <div class="panel">
      <h2>Rincian biaya</h2>
      <div class="lines">
        <div class="line"><span>Biaya penitipan</span><span>{{ rp($order->item_subtotal) }}</span></div>
        @if ($order->courier_cost > 0)
          <div class="line"><span>Biaya logistik</span><span>{{ rp($order->courier_cost) }}</span></div>
        @endif
        <div class="line"><span>Biaya layanan</span><span>{{ rp($order->platform_fee) }}</span></div>
      </div>
      <div class="total"><span style="font-weight:700">Total</span><strong>{{ rp($order->total) }}</strong></div>
    </div>

    @if ($order->status === 'menunggu_pembayaran')
      @if ($order->tripay_checkout_url)
        <a href="{{ $order->tripay_checkout_url }}" class="btn btn-primary" style="width:100%;margin-bottom:12px">Lanjutkan pembayaran</a>
      @else
        <div class="pay-due" style="margin-bottom:16px">Menunggu pembayaran.</div>
      @endif
    @endif

<<<<<<< HEAD
    <a href="https://wa.me/6285121091134?text={{ urlencode('Halo RuangTitip, saya mau tanya pesanan '.$order->code()) }}" target="_blank" rel="noopener" class="btn btn-outline" style="width:100%;margin-bottom:40px;border-color:var(--depot);color:var(--depot)">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12a8.5 8.5 0 0 1-12.6 7.4L3 21l1.6-5.2A8.5 8.5 0 1 1 21 12z"/></svg>
      Hubungi admin via WhatsApp
=======
    {{-- Bantuan via WhatsApp (sesuai dokumen) --}}
    <a href="https://wa.me/6285121091134?text=Halo%20RUTIP,%20saya%20mau%20tanya%20pesanan%20{{ $order->code() }}"
       target="_blank" rel="noopener noreferrer"
       class="flex items-center justify-center gap-2 w-full py-3.5 rounded-xl text-sm font-bold transition-all hover:scale-[1.02]"
       style="border:1.5px solid rgba(37,211,102,0.4);color:#25d366;background:rgba(37,211,102,0.06);">
        <x-lucide-message-circle class="w-4 h-4" /> Hubungi Admin via WhatsApp
>>>>>>> hostinger/main
    </a>
  </div>
</main>
@endsection
