@extends('layouts.ruang-titip')
@section('title', 'Status Pembayaran')

@php
    function rp($n){ return 'Rp'.number_format($n,0,',','.'); }
    $isPaid = $order->payment_status === 'PAID';
    $isPending = in_array($order->payment_status, ['UNPAID', 'pending']);
    $isFailed = in_array($order->payment_status, ['EXPIRED', 'FAILED', 'REFUND']);
@endphp

@section('content')
<main class="wrap" style="max-width:520px;margin:0 auto;padding-top:56px;padding-bottom:64px;display:flex;flex-direction:column;align-items:center;text-align:center">
  @if ($isPaid)
    <div style="width:80px;height:80px;border-radius:50%;display:grid;place-items:center;margin-bottom:24px;background:var(--depot-light);border:2px solid var(--depot)">
      <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="var(--depot)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
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
