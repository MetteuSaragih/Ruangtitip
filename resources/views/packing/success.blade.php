@extends('layouts.rt')

@section('title', 'Status Pembayaran')

@php
    if (! function_exists('rupiah')) {
        function rupiah($n) { return 'Rp' . number_format($n, 0, ',', '.'); }
    }
    $isPaid = $order->payment_status === 'PAID';
    $isPending = in_array($order->payment_status, ['UNPAID', 'pending']);
    $isFailed = in_array($order->payment_status, ['EXPIRED', 'FAILED', 'REFUND']);
    $tone = $isPaid ? 'ok' : ($isFailed ? 'err' : 'warn');
    $icon = $isPaid ? 'i-check' : ($isFailed ? 'i-x' : 'i-clock');
@endphp

@section('content')
<div class="wrap" style="max-width:640px">
    <section class="rt-card rt-state" aria-live="polite">
        <span class="badge b-{{ $tone }}" style="width:78px;height:78px;border-radius:50%;justify-content:center;padding:0" aria-hidden="true">
            <svg width="38" height="38"><use href="#{{ $icon }}"/></svg>
        </span>
        <h3>
            @if ($isPaid) Pembayaran berhasil!
            @elseif ($isFailed) Pembayaran gagal
            @else Menunggu pembayaran
            @endif
        </h3>
        <p>
            @if ($isPaid) Pesananmu sudah dikonfirmasi.
            @elseif ($isFailed) Pembayaran dibatalkan atau kedaluwarsa.
            @else Selesaikan pembayaranmu untuk melanjutkan.
            @endif
        </p>
        <p class="rt-help">No. pesanan <b style="color:var(--text)">#{{ $order->order_code }}</b></p>

        @if ($isPending && $order->tripay_checkout_url)
            <div class="acts">
                <a class="btn btn-sun" href="{{ $order->tripay_checkout_url }}">Lanjutkan pembayaran</a>
            </div>
            @if ($order->tripay_pay_code)
                <p class="rt-help">Kode pembayaran: <b style="color:var(--text);font-family:var(--display);letter-spacing:.04em">{{ $order->tripay_pay_code }}</b></p>
            @endif
        @endif
    </section>

    @if ($isPaid)
        <div class="alert a-info" style="margin-top:16px">
            <svg class="ic" aria-hidden="true"><use href="#n-chat"/></svg>
            <div>
                <b>Pelacakan lewat WhatsApp</b>
                <p>Status dan resi pengiriman dari Biteship dikirim otomatis ke WhatsApp-mu.
                    @if ($order->logistic === 'pickup') Siapkan diri untuk mengambil pesanan di gudang RuangTitip. @endif
                </p>
            </div>
        </div>
    @endif

    <section class="rt-card" style="margin-top:16px">
        <div class="rt-card-h"><h3>Ringkasan pesanan</h3></div>
        <ul class="rt-rows" style="list-style:none;margin:0;padding:0">
            @foreach ($order->items as $item)
                <li style="display:flex;justify-content:space-between;gap:16px;padding:10px 0;border-top:1px dashed var(--line);font-size:14px">
                    <span style="color:var(--muted)">{{ $item['name'] }} &times;{{ $item['qty'] }}</span>
                    <b>{{ rupiah($item['price'] * $item['qty']) }}</b>
                </li>
            @endforeach
        </ul>
        <div class="total"><span style="font-weight:700">Total {{ $isPaid ? 'dibayar' : 'tagihan' }}</span><strong>{{ rupiah($order->total) }}</strong></div>
    </section>

    <div class="actions">
        <a class="btn {{ $isPaid ? 'btn-sun' : 'btn-outline' }}" href="{{ route('packing.index') }}">{{ $isPaid ? 'Belanja lagi' : 'Kembali ke toko' }}</a>
        <a class="btn btn-outline" href="{{ route('pesanan.index') }}">Pesanan saya</a>
    </div>
</div>
@endsection
