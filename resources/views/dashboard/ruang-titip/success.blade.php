@extends('layouts.ruang-titip')
@section('title', 'Pesanan Dibuat')

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
  @elseif ($isFailed)
    <div style="width:80px;height:80px;border-radius:50%;display:grid;place-items:center;margin-bottom:24px;background:var(--tape-soft);border:2px solid var(--tape)">
      <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="var(--tape-dark)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
    </div>
    <h1 style="font-size:28px;margin-bottom:8px">Pembayaran Gagal</h1>
  @else
    <div style="width:80px;height:80px;border-radius:50%;display:grid;place-items:center;margin-bottom:24px;background:var(--sand);border:2px solid var(--ink)">
      <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="var(--ink)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 7v5l3 2"/></svg>
    </div>
    <h1 style="font-size:28px;margin-bottom:8px">Pesanan Dibuat!</h1>
  @endif

  <p style="color:var(--muted);margin-bottom:4px">Nomor pesanan #{{ $order->code() }}</p>
  <p style="color:var(--muted);margin-bottom:24px">
    Status: <strong style="color:{{ $isPaid ? 'var(--depot)' : ($isFailed ? 'var(--tape-dark)' : 'var(--ink)') }}">{{ $isPaid ? 'Lunas' : ($isFailed ? 'Gagal/Kedaluwarsa' : 'Menunggu Pembayaran') }}</strong>
  </p>

  @if ($isPending && $order->tripay_checkout_url)
    <a href="{{ $order->tripay_checkout_url }}" class="btn btn-primary" style="width:100%;margin-bottom:12px">Lanjutkan Pembayaran</a>
    @if ($order->tripay_pay_code)
      <p style="font-size:13px;color:var(--muted);margin-bottom:24px">Kode pembayaran: <strong style="font-family:monospace;color:var(--ink)">{{ $order->tripay_pay_code }}</strong></p>
    @endif
  @endif

  <div class="panel" style="width:100%;text-align:left;margin-top:0">
    @if ($order->storage)
      <div style="display:flex;align-items:center;gap:12px;padding-bottom:16px;margin-bottom:16px;border-bottom:1px solid var(--line)">
        <span style="width:48px;height:48px;border-radius:12px;background:var(--sand);border:1px solid var(--line-strong);display:grid;place-items:center;flex-shrink:0;overflow:hidden">
          @if ($order->storage->primary_photo)
            <img src="{{ asset('storage/'.$order->storage->primary_photo) }}" alt="{{ $order->storage->name }}" style="width:100%;height:100%;object-fit:cover">
          @else
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="var(--tape-dark)" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 10 12 4l9 6v10H3z"/><path d="M8 20v-6h8v6"/></svg>
          @endif
        </span>
        <strong>{{ $order->storage->name }}</strong>
      </div>
    @endif
    <div style="display:flex;justify-content:space-between;margin-bottom:8px">
      <span style="font-size:14px;color:var(--muted)">Total Tagihan</span>
      <strong style="font-family:var(--font-display);font-size:20px;color:var(--tape-dark)">{{ rp($order->total) }}</strong>
    </div>
    <p style="font-size:13px;color:var(--muted)">Metode: {{ strtoupper($order->tripay_payment_method ?? $order->payment_method) }}</p>
  </div>

  <a href="{{ route('dashboard') }}" class="btn btn-primary" style="width:100%;margin-top:20px">Kembali ke Dashboard</a>
</main>
@endsection
