@extends('layouts.admin')

@section('title', 'Tagihan & pembayaran')

@php
    $rp = fn ($n) => 'Rp ' . number_format((int) $n, 0, ',', '.');
    $tabs = ['semua' => 'Semua', 'periksa' => 'Perlu diperiksa', 'menunggu' => 'Menunggu bukti', 'berhasil' => 'Berhasil', 'gagal' => 'Gagal'];
    $link = fn (array $over) => route('admin.tagihan.index', array_filter(
        array_merge(['tab' => $tab, 'sumber' => $source, 'q' => $q], $over),
        fn ($v) => $v !== null && $v !== '' && $v !== 'semua'
    ));
    $csvQuery = array_filter(['tab' => $tab, 'sumber' => $source, 'q' => $q], fn ($v) => $v !== null && $v !== '' && $v !== 'semua');
@endphp

@section('content')
<div class="ph">
  <div>
    <h1>Tagihan &amp; pembayaran</h1>
    <p>Gabungan pembayaran Titip, Preloved, dan Packing lewat QRIS. Periksa bukti dari pelanggan, lalu tandai berhasil atau gagal.</p>
  </div>
  <div class="acts">
    <a class="btn btn-ghost" href="{{ route('admin.tagihan.csv', $csvQuery) }}">Unduh CSV</a>
  </div>
</div>

@if ($kpi['periksa_n'] > 0 && $tab !== 'periksa')
  <div class="al info" style="margin-bottom:16px">
    <svg class="ic" aria-hidden="true"><use href="#i-clock"/></svg>
    <div><b>{{ $kpi['periksa_n'] }} bukti pembayaran menunggu diperiksa</b><p>Total {{ $rp($kpi['periksa']) }}. Pelanggan menunggu konfirmasimu.</p></div>
    <div class="acts"><a class="btn btn-primary sm" href="{{ $link(['tab' => 'periksa', 'page' => null]) }}">Periksa sekarang</a></div>
  </div>
@endif

<div class="g4" style="margin-bottom:16px">
  <div class="kpi"><small>Perlu diperiksa</small><b class="num">{{ $rp($kpi['periksa']) }}</b><span>{{ $kpi['periksa_n'] }} bukti menunggu</span></div>
  <div class="kpi"><small>Menunggu bukti</small><b class="num">{{ $rp($kpi['menunggu']) }}</b><span>{{ $kpi['menunggu_n'] }} tagihan belum dibayar</span></div>
  <div class="kpi"><small>Berhasil bulan ini</small><b class="num">{{ $rp($kpi['berhasil']) }}</b><span>Sejak {{ now()->startOfMonth()->format('d/m/Y') }}</span></div>
  <div class="kpi"><small>Gagal</small><b class="num">{{ $kpi['gagal'] }}</b><span>Dari 200 tagihan terbaru per sumber</span></div>
</div>

<div class="bar">
  <div class="tabs">
    @foreach ($tabs as $key => $label)
      <a href="{{ $link(['tab' => $key, 'page' => null]) }}" @if ($tab === $key) aria-current="page" @endif>{{ $label }} <i>{{ $counts[$key] }}</i></a>
    @endforeach
  </div>
  <div class="tabs">
    <a href="{{ $link(['sumber' => 'semua', 'page' => null]) }}" @if ($source === 'semua') aria-current="page" @endif>Semua sumber</a>
    @foreach ($types as $key => $label)
      <a href="{{ $link(['sumber' => $key, 'page' => null]) }}" @if ($source === $key) aria-current="page" @endif>{{ $label }}</a>
    @endforeach
  </div>
  <form class="search" method="GET" action="{{ route('admin.tagihan.index') }}" role="search">
    <svg class="ic" aria-hidden="true"><use href="#n-search"/></svg>
    @if ($tab !== 'semua') <input type="hidden" name="tab" value="{{ $tab }}"> @endif
    @if ($source !== 'semua') <input type="hidden" name="sumber" value="{{ $source }}"> @endif
    <input type="search" name="q" value="{{ $q }}" placeholder="Cari kode, nama, atau email" aria-label="Cari tagihan">
  </form>
</div>

<div class="card flush">
  @if ($rows->count() === 0)
    <div class="empty"><b>Tidak ada tagihan</b>Ubah filter atau kata kunci pencarian.</div>
  @else
    <div class="tw">
      <table class="tbl">
        <thead>
          <tr>
            <th>Kode</th><th>Jenis</th><th>Pelanggan</th><th>Metode</th><th class="r">Jumlah</th><th>Status</th><th>Dibuat</th><th>Dibayar</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($rows as $r)
            <tr>
              <td><a href="{{ route('admin.tagihan.show', [$r['type'], $r['id']]) }}"><b class="mono">{{ $r['code'] }}</b></a></td>
              <td><span class="tag info">{{ $r['type_label'] }}</span></td>
              <td><b>{{ $r['customer'] }}</b>@if ($r['email'])<small>{{ $r['email'] }}</small>@endif</td>
              <td>{{ $r['method'] }}</td>
              <td class="r">{{ $rp($r['amount']) }}</td>
              <td><span class="st {{ $r['tone'] }}">{{ $r['label'] }}</span></td>
              <td class="num">{{ $r['created'] ? $r['created']->copy()->timezone('Asia/Jakarta')->format('d/m/Y H:i') : '-' }}</td>
              <td class="num">{{ $r['paid'] ? $r['paid']->copy()->timezone('Asia/Jakarta')->format('d/m/Y H:i') : '-' }}</td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
    <div class="pager">
      <span>{{ $rows->total() }} tagihan, halaman {{ $rows->currentPage() }} dari {{ $rows->lastPage() }}</span>
      <nav aria-label="Halaman">
        @if ($rows->previousPageUrl())
          <a href="{{ $rows->previousPageUrl() }}">Sebelumnya</a>
        @else
          <span class="pg off">Sebelumnya</span>
        @endif
        @if ($rows->nextPageUrl())
          <a href="{{ $rows->nextPageUrl() }}">Berikutnya</a>
        @else
          <span class="pg off">Berikutnya</span>
        @endif
      </nav>
    </div>
  @endif
</div>
@endsection
