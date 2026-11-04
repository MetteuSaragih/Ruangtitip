@extends('layouts.admin')

@section('title', 'Tagihan ' . $bill['code'])

@php
    $rp = fn ($n) => 'Rp ' . number_format((int) $n, 0, ',', '.');
    $orderUrl = null;
    $orderLabel = 'Buka pesanan';
    if ($bill['type'] === 'titip' && Route::has('admin.titip.show')) {
        $orderUrl = route('admin.titip.show', $bill['id']);
    } elseif ($bill['type'] === 'preloved' && Route::has('admin.preloved.orders')) {
        $orderUrl = route('admin.preloved.orders');
    } elseif ($bill['type'] === 'packing' && Route::has('admin.packing.index')) {
        $orderUrl = route('admin.packing.index');
    }
    $fmt = fn ($d) => $d ? $d->format('d/m/Y H:i') : '-';
@endphp

@section('content')
<div class="ph">
  <div>
    <h1 class="mono">{{ $bill['code'] }}</h1>
    <p><span class="tag info">{{ $bill['type_label'] }}</span> <span class="st {{ $bill['tone'] }}">{{ $bill['label'] }}</span></p>
  </div>
  <div class="acts">
    <a class="btn btn-ghost" href="{{ route('admin.tagihan.index') }}">Kembali ke daftar</a>
    @if ($orderUrl)
      <a class="btn btn-primary" href="{{ $orderUrl }}">{{ $orderLabel }}</a>
    @endif
  </div>
</div>

<div class="cols">
  <div class="stack">
    @if ($bill['group'] === 'wait')
      <div class="al warn">
        <svg class="ic" aria-hidden="true"><use href="#i-clock"/></svg>
        <div>
          <b>Menunggu pembayaran</b>
          <p>Status berubah otomatis saat Tripay mengirim callback. Tidak ada verifikasi manual.</p>
        </div>
        @if ($bill['checkout_url'])
          <div class="acts"><a class="btn btn-ghost sm" href="{{ $bill['checkout_url'] }}" target="_blank" rel="noopener">Buka halaman bayar Tripay</a></div>
        @endif
      </div>
    @elseif ($bill['group'] === 'fail')
      <div class="al err">
        <svg class="ic" aria-hidden="true"><use href="#i-warn"/></svg>
        <div><b>Pembayaran {{ strtolower($bill['label']) }}</b><p>Pelanggan perlu membuat pembayaran baru bila masih ingin melanjutkan.</p></div>
      </div>
    @else
      <div class="al ok">
        <svg class="ic" aria-hidden="true"><use href="#i-check"/></svg>
        <div><b>Pembayaran berhasil</b><p>Dikonfirmasi Tripay pada {{ $fmt($bill['paid']) }}.</p></div>
      </div>
    @endif

    <div class="card">
      <div class="card-h"><h3>Rincian biaya</h3></div>
      <div class="kv">
        @foreach ($bill['breakdown'] as $line)
          <div><span>{{ $line[0] }}</span><b>{{ $rp($line[1]) }}</b></div>
        @endforeach
        <div class="tot"><span>Total</span><b>{{ $rp($bill['amount']) }}</b></div>
      </div>
    </div>
  </div>

  <aside>
    <div class="card">
      <div class="card-h"><h3>Pelanggan</h3></div>
      <div class="kv">
        <div><span>Nama</span><b>{{ $bill['customer'] }}</b></div>
        <div><span>Email</span><b>{{ $bill['email'] ?: '-' }}</b></div>
      </div>
    </div>
    <div class="card">
      <div class="card-h"><h3>Pembayaran</h3></div>
      <div class="kv">
        <div><span>Metode</span><b>{{ $bill['method'] }}</b></div>
        <div><span>Status Tripay</span><b>{{ $bill['raw'] !== '' ? $bill['raw'] : '-' }}</b></div>
        <div><span>Referensi</span><b class="mono">{{ $bill['reference'] ?: '-' }}</b></div>
        <div><span>Kode bayar</span><b class="mono">{{ $bill['pay_code'] ?: '-' }}</b></div>
        <div><span>Dibuat</span><b>{{ $fmt($bill['created']) }}</b></div>
        <div><span>Dibayar</span><b>{{ $fmt($bill['paid']) }}</b></div>
      </div>
    </div>
  </aside>
</div>
@endsection
