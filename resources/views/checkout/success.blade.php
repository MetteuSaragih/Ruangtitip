@extends('layouts.ruang-titip')
@section('title', 'Status Pembayaran')

@php
    function rp($n){ return 'Rp'.number_format($n,0,',','.'); }
    $isPaid = $order->payment_status === 'PAID';
    $isPending = in_array($order->payment_status, ['UNPAID', 'pending']);
    $isFailed = in_array($order->payment_status, ['EXPIRED', 'FAILED', 'REFUND']);
@endphp

@section('content')
<<<<<<< HEAD
<main class="wrap" style="max-width:520px;margin:0 auto;padding-top:56px;padding-bottom:64px;display:flex;flex-direction:column;align-items:center;text-align:center">
  @if ($isPaid)
    <div style="width:80px;height:80px;border-radius:50%;display:grid;place-items:center;margin-bottom:24px;background:var(--depot-light);border:2px solid var(--depot)">
      <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="var(--depot)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
=======
<div class="py-6 pb-24 flex flex-col items-center text-center max-w-xl mx-auto">

    {{-- Ikon status --}}
    @if ($isPaid)
        <div class="w-24 h-24 rounded-full flex items-center justify-center mb-6 mt-4"
             style="background:rgba(52,211,153,0.15);border:3px solid rgba(52,211,153,0.4);box-shadow:0 0 40px rgba(52,211,153,0.2);">
            <x-lucide-check class="w-12 h-12" style="color:#34d399;" />
        </div>
        <h1 class="text-2xl font-extrabold text-white font-display mb-2">Pembayaran Berhasil!</h1>
        <p class="text-sm mb-2" style="color:rgba(255,255,255,0.5);">Pesananmu telah dikonfirmasi</p>
    @elseif ($isFailed)
        <div class="w-24 h-24 rounded-full flex items-center justify-center mb-6 mt-4"
             style="background:rgba(239,68,68,0.15);border:3px solid rgba(239,68,68,0.4);box-shadow:0 0 40px rgba(239,68,68,0.2);">
            <x-lucide-x class="w-12 h-12" style="color:#f87171;" />
        </div>
        <h1 class="text-2xl font-extrabold text-white font-display mb-2">Pembayaran Gagal</h1>
        <p class="text-sm mb-2" style="color:rgba(255,255,255,0.5);">Pembayaran dibatalkan atau kedaluwarsa</p>
    @else
        <div class="w-24 h-24 rounded-full flex items-center justify-center mb-6 mt-4"
             style="background:rgba(251,191,36,0.15);border:3px solid rgba(251,191,36,0.4);box-shadow:0 0 40px rgba(251,191,36,0.2);">
            <x-lucide-clock class="w-12 h-12" style="color:#fbbf24;" />
        </div>
        <h1 class="text-2xl font-extrabold text-white font-display mb-2">Menunggu Pembayaran</h1>
        <p class="text-sm mb-2" style="color:rgba(255,255,255,0.5);">Selesaikan pembayaranmu untuk melanjutkan</p>
    @endif

    <p class="text-xs font-mono mb-8" style="color:rgba(167,139,250,0.7);">#{{ $order->order_number }}</p>

    {{-- Tombol lanjut bayar - redirect ke halaman checkout Tripay --}}
    @if ($isPending && $order->tripay_checkout_url)
        <a href="{{ $order->tripay_checkout_url }}"
           class="w-full py-4 rounded-2xl font-bold text-sm text-white flex items-center justify-center gap-2 transition-all hover:scale-[1.02] mb-4"
           style="background:linear-gradient(135deg,#7c3aed,#6366f1);box-shadow:0 6px 20px rgba(124,58,237,0.4);">
            <x-lucide-credit-card class="w-4 h-4" /> Lanjutkan Pembayaran
        </a>
        @if ($order->tripay_pay_code)
            <p class="text-xs mb-6" style="color:rgba(255,255,255,0.45);">Kode pembayaran: <span class="font-mono font-bold text-white">{{ $order->tripay_pay_code }}</span></p>
        @endif
    @endif

    {{-- Banner WhatsApp tracking (hanya saat sudah lunas) --}}
    @if ($isPaid)
        <div class="w-full rounded-2xl p-5 mb-6 text-left"
             style="background:linear-gradient(135deg,rgba(37,211,102,0.15),rgba(18,140,78,0.1));border:1px solid rgba(37,211,102,0.3);">
            <div class="flex items-start gap-3">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0 text-xl" style="background:rgba(37,211,102,0.2);">💬</div>
                <div class="flex-1">
                    <p class="text-sm font-bold text-white mb-1">Notifikasi Tracking via WhatsApp</p>
                    <p class="text-xs leading-relaxed" style="color:rgba(255,255,255,0.6);">
                        Pesanan Anda sedang diproses. Status pelacakan dan resi pengiriman instan dari Biteship akan dikirimkan otomatis melalui <span class="font-semibold text-white">WhatsApp Anda</span>.
                    </p>
                    @if (($order->shipping_method ?? 'pickup') === 'pickup')
                        <div class="mt-2 flex items-center gap-1.5">
                            <x-lucide-store class="w-3.5 h-3.5 shrink-0" style="color:#34d399;" />
                            <p class="text-xs font-semibold" style="color:#34d399;">Siapkan pesananmu untuk dijemput di Gudang RUTIP</p>
                        </div>
                    @else
                        <div class="mt-2 flex items-center gap-1.5">
                            <x-lucide-package-check class="w-3.5 h-3.5 shrink-0" style="color:#34d399;" />
                            <p class="text-xs font-semibold" style="color:#34d399;">{{ $order->biteship_tracking_id ? 'Lacak pesananmu: ' . $order->biteship_tracking_id : 'Pesananmu sedang dibooking ke kurir Biteship' }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif

    {{-- Ringkasan pesanan --}}
    <div class="w-full rounded-2xl p-4 mb-8 text-left" style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.09);">
        <p class="text-xs font-bold text-white mb-3">Ringkasan Pesanan</p>
        @foreach ($order->items as $i => $item)
            @php $itemType = $item['type'] ?? 'preloved'; @endphp
            <div class="flex items-center gap-3 py-1.5"
                 style="{{ $i < count($order->items) - 1 ? 'border-bottom:1px solid rgba(255,255,255,0.05);' : '' }}">
                <div class="w-9 h-9 rounded-lg flex items-center justify-center overflow-hidden shrink-0" style="background:rgba(255,255,255,0.06);">
                    @if (!empty($item['image']))
                        @if ($itemType === 'packing')
                            <img src="{{ asset('storage/'.$item['image']) }}" alt="{{ $item['name'] }}" class="w-full h-full object-cover">
                        @else
                            <img src="{{ Storage::url($item['image']) }}" alt="{{ $item['name'] }}" class="w-full h-full object-cover">
                        @endif
                    @else
                        <x-lucide-image class="w-4 h-4" style="color:#a78bfa;" />
                    @endif
                </div>
                <span class="flex-1 text-xs" style="color:rgba(255,255,255,0.55);">{{ $item['name'] }} × {{ $item['qty'] }}</span>
                <span class="text-xs font-semibold text-white shrink-0">{{ rupiah($item['price'] * $item['qty']) }}</span>
            </div>
        @endforeach
        <div class="flex justify-between items-center py-1.5" style="border-bottom:1px solid rgba(255,255,255,0.05);">
            <span class="text-xs" style="color:rgba(255,255,255,0.55);">Biaya Layanan dan Platform</span>
            <span class="text-xs font-semibold text-white">{{ rupiah($order->service_fee) }}</span>
        </div>
        @if ($order->shipping_cost > 0)
            <div class="flex justify-between items-center py-1.5">
                <span class="text-xs" style="color:rgba(255,255,255,0.55);">Biaya Pengiriman</span>
                <span class="text-xs font-semibold text-white">{{ rupiah($order->shipping_cost) }}</span>
            </div>
        @endif
        <div class="flex justify-between items-center pt-2 mt-1" style="border-top:1px solid rgba(255,255,255,0.1);">
            <span class="text-xs font-bold text-white">Total {{ $isPaid ? 'Dibayar' : 'Tagihan' }}</span>
            <span class="text-sm font-extrabold font-display" style="color:#a78bfa;">{{ rupiah($order->total) }}</span>
        </div>
>>>>>>> hostinger/main
    </div>
    <h1 style="font-size:28px;margin-bottom:8px">Pembayaran Berhasil!</h1>
    <p style="color:var(--muted);margin-bottom:8px">Pesananmu telah dikonfirmasi.</p>
  @elseif ($isFailed)
    <div style="width:80px;height:80px;border-radius:50%;display:grid;place-items:center;margin-bottom:24px;background:var(--tape-soft);border:2px solid var(--tape)">
      <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="var(--tape-dark)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
    </div>
    <h1 style="font-size:28px;margin-bottom:8px">Pembayaran Gagal</h1>
    <p style="color:var(--muted);margin-bottom:8px">Pembayaran dibatalkan atau kedaluwarsa.</p>
  @else
    <div style="width:80px;height:80px;border-radius:50%;display:grid;place-items:center;margin-bottom:24px;background:var(--sand);border:2px solid var(--ink)">
      <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="var(--ink)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 7v5l3 2"/></svg>
    </div>
    <h1 style="font-size:28px;margin-bottom:8px">Menunggu Pembayaran</h1>
    <p style="color:var(--muted);margin-bottom:8px">Selesaikan pembayaranmu untuk melanjutkan.</p>
  @endif

  <p style="font-family:monospace;font-size:13px;color:var(--muted);margin-bottom:24px">#{{ $order->order_number }}</p>

  @if ($isPending && $order->tripay_checkout_url)
    <a href="{{ $order->tripay_checkout_url }}" class="btn btn-primary" style="width:100%;margin-bottom:12px">Lanjutkan Pembayaran</a>
    @if ($order->tripay_pay_code)
      <p style="font-size:13px;color:var(--muted);margin-bottom:24px">Kode pembayaran: <strong style="font-family:monospace;color:var(--ink)">{{ $order->tripay_pay_code }}</strong></p>
    @endif
  @endif

  @if ($isPaid)
    <div class="panel" style="width:100%;text-align:left;margin-top:0;background:var(--depot-light);border-color:var(--depot)">
      <p style="font-weight:700;margin-bottom:6px">Notifikasi tracking via WhatsApp</p>
      <p style="font-size:14px;color:var(--body)">
        Pesananmu sedang diproses. Status pelacakan dan resi pengiriman akan dikirimkan otomatis lewat WhatsApp kamu.
      </p>
      @if (($order->shipping_method ?? 'pickup') === 'pickup')
        <p style="font-size:13px;font-weight:700;color:var(--depot);margin-top:10px">Siapkan pesananmu untuk dijemput di gudang RuangTitip.</p>
      @else
        <p style="font-size:13px;font-weight:700;color:var(--depot);margin-top:10px">{{ $order->biteship_tracking_id ? 'Lacak pesananmu: ' . $order->biteship_tracking_id : 'Pesananmu sedang dibooking ke kurir Biteship.' }}</p>
      @endif
    </div>
  @endif

  <div class="panel" style="width:100%;text-align:left">
    <h2 style="margin-bottom:12px">Ringkasan Pesanan</h2>
    <div class="lines">
      @foreach ($order->items as $item)
        @php $itemType = $item['type'] ?? 'preloved'; @endphp
        <div class="line"><span>{{ $item['name'] }} &times;{{ $item['qty'] }}</span><span>{{ rp($item['price'] * $item['qty']) }}</span></div>
      @endforeach
      <div class="line"><span>Biaya Layanan dan Platform</span><span>{{ rp($order->service_fee) }}</span></div>
      @if ($order->shipping_cost > 0)
        <div class="line"><span>Biaya Pengiriman</span><span>{{ rp($order->shipping_cost) }}</span></div>
      @endif
    </div>
    <div class="total"><span style="font-weight:700">Total {{ $isPaid ? 'Dibayar' : 'Tagihan' }}</span><strong>{{ rp($order->total) }}</strong></div>
  </div>

  <a href="{{ $isPaid ? route('preloved.index') : route('preloved.cart.index') }}" class="btn btn-primary" style="width:100%;margin-top:20px">{{ $isPaid ? 'Belanja Lagi' : 'Kembali ke Keranjang' }}</a>
</main>
@endsection
