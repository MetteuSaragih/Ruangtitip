@extends('layouts.admin')

@section('title', 'Pesanan Preloved')

@php
$tabs = [
    'semua' => 'Semua',
    'bayar' => 'Menunggu bayar',
    'diproses' => 'Diproses',
    'dikirim' => 'Dikirim',
    'selesai' => 'Selesai',
];
$deliveryLabel = ['pickup' => 'Ambil sendiri', 'biteship' => 'Ekspedisi'];
@endphp

@section('content')
<div class="ph">
  <div>
    <h1>Pesanan Preloved</h1>
    <p>Pesanan pelanggan yang memuat barang Toko Preloved, dari pembayaran sampai selesai.</p>
  </div>
  <div class="acts">
    <a class="btn btn-ghost" href="{{ route('admin.preloved') }}">Katalog</a>
  </div>
</div>

<div class="bar">
  <div class="tabs">
    @foreach ($tabs as $k => $label)
      <a href="{{ route('admin.preloved.orders', array_filter(['tab' => $k === 'semua' ? null : $k, 'q' => $q ?: null])) }}" @if ($tab === $k) aria-current="page" @endif>{{ $label }} <i>{{ $counts[$k] }}</i></a>
    @endforeach
  </div>
  <form method="GET" action="{{ route('admin.preloved.orders') }}" class="search" role="search">
    @if ($tab !== 'semua')<input type="hidden" name="tab" value="{{ $tab }}">@endif
    <svg class="ic" aria-hidden="true"><use href="#n-search"/></svg>
    <input type="search" name="q" value="{{ $q }}" placeholder="Cari nomor pesanan, pelanggan, barang" aria-label="Cari pesanan">
  </form>
</div>

<div class="card flush">
  @if ($orders->isEmpty())
    <div class="empty"><b>Tidak ada pesanan</b>Pesanan dengan filter ini akan muncul di sini.</div>
  @else
    <div class="tw">
      <table class="tbl">
        <thead>
          <tr>
            <th>Pesanan</th><th>Pelanggan</th><th>Barang</th><th class="r">Total</th><th>Pembayaran</th><th>Status</th><th class="acts">Aksi</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($orders as $order)
            @php
              $payTone = $order->is_paid ? 'ok' : (in_array(strtoupper((string) $order->payment_status), ['FAILED', 'EXPIRED'], true) ? 'err' : 'warn');
              $payLabel = $order->is_paid ? 'Lunas' : ($payTone === 'err' ? 'Gagal' : 'Menunggu');
              $stTone = ['paid' => 'warn', 'processing' => 'info', 'shipped' => 'info', 'delivered' => 'ok', 'cancelled' => 'err'][$order->status] ?? 'mute';
              $stLabel = $order->is_paid ? ($orderStatusLabels[$order->status] ?? $order->status) : ($order->status === 'cancelled' ? 'Dibatalkan' : 'Menunggu pembayaran');
              $next = $order->is_paid ? match ($order->status) {
                  'paid' => 'processing',
                  'processing' => $order->shipping_method === 'biteship' ? 'shipped' : 'delivered',
                  'shipped' => 'delivered',
                  default => null,
              } : null;
              $nextLabel = ['processing' => 'Proses pesanan', 'shipped' => 'Tandai dikirim', 'delivered' => 'Tandai selesai'][$next] ?? null;
              $dlgId = 'ord' . $order->id;
            @endphp
            <tr>
              <td>
                <b class="mono">{{ $order->order_number }}</b>
                <small>{{ optional($order->created_at)->format('d M Y') }}</small>
                @if ($order->needs_admin_attention)
                  <span class="tag err">Perlu tindakan</span>
                @endif
              </td>
              <td>{{ $order->customer_name }}<small>{{ $order->customer_phone }}</small></td>
              <td>{{ \Illuminate\Support\Str::limit($order->preloved_item_names ?: '-', 60) }}</td>
              <td class="r num">Rp {{ number_format($order->total ?: $order->preloved_subtotal, 0, ',', '.') }}</td>
              <td><span class="st {{ $payTone }}">{{ $payLabel }}</span></td>
              <td><span class="st {{ $stTone }}">{{ $stLabel }}</span></td>
              <td class="acts">
                <button class="btn btn-ghost sm" type="button" data-open="{{ $dlgId }}">Detail</button>
                @if ($next)
                  <form method="POST" action="{{ route('admin.preloved.advance-order', $order) }}" style="display:inline"
                        data-confirm="Ubah status pesanan {{ $order->order_number }} menjadi: {{ $nextLabel }}?" data-confirm-title="Lanjutkan status">
                    @csrf
                    <button class="btn btn-primary sm" type="submit">{{ $nextLabel }}</button>
                  </form>
                @endif
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
    @include('admin.preloved-pager', ['p' => $orders])
  @endif
</div>

@foreach ($orders as $order)
  @php $addr = is_array($order->shipping_address) ? ($order->shipping_address['full'] ?? null) : null; @endphp
  <dialog class="dlg" id="ord{{ $order->id }}">
    <div class="dh">
      <h3>Pesanan {{ $order->order_number }}</h3>
      <button class="x" type="button" data-close aria-label="Tutup"><svg class="ic" aria-hidden="true"><use href="#i-x"/></svg></button>
    </div>
    <div class="db">
      <div class="kv">
        <div><span>Pelanggan</span><b>{{ $order->customer_name }}<small>{{ $order->customer_email }} {{ $order->customer_phone }}</small></b></div>
        <div><span>Pengiriman</span><b>{{ $deliveryLabel[$order->shipping_method] ?? ($order->shipping_method ?: '-') }}@if ($order->courier_name)<small>{{ $order->courier_name }}</small>@endif</b></div>
        @if ($addr)<div><span>Alamat</span><b>{{ $addr }}</b></div>@endif
        @if ($order->biteship_tracking_id)<div><span>Nomor resi</span><b class="mono">{{ $order->biteship_tracking_id }}</b></div>@endif
        @if ($order->needs_admin_attention)<div><span>Catatan</span><b>{{ $order->biteship_last_error ?: 'Pemesanan kurir gagal' }}</b></div>@endif
      </div>
      <p class="sec-t">Barang</p>
      <div class="kv">
        @foreach (collect($order->items ?? []) as $it)
          <div><span>{{ $it['name'] ?? '-' }} x {{ $it['qty'] ?? 1 }}</span><b>Rp {{ number_format(($it['price'] ?? 0) * ($it['qty'] ?? 1), 0, ',', '.') }}</b></div>
        @endforeach
        <div class="tot"><span>Total</span><b>Rp {{ number_format($order->total ?: $order->preloved_subtotal, 0, ',', '.') }}</b></div>
      </div>
    </div>
    <div class="df"><button class="btn btn-ghost" type="button" data-close>Tutup</button></div>
  </dialog>
@endforeach
@endsection
