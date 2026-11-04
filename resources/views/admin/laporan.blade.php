@extends('layouts.admin')

@section('title', 'Laporan')

@php
    $rp = fn ($n) => 'Rp ' . number_format((int) $n, 0, ',', '.');
    $maxPx = 120;
@endphp

@section('content')
<div class="ph">
  <div>
    <h1>Laporan</h1>
    <p>Ringkasan {{ $days }} hari terakhir, sejak {{ $from->format('d/m/Y') }}.</p>
  </div>
  <div class="acts">
    <div class="tabs">
      @foreach ([7, 14, 30] as $p)
        <a href="{{ route('admin.laporan', ['periode' => $p]) }}" @if ($days === $p) aria-current="page" @endif>{{ $p }} hari</a>
      @endforeach
    </div>
    <a class="btn btn-ghost" href="{{ route('admin.laporan.csv', ['periode' => $days]) }}">Unduh CSV harian</a>
  </div>
</div>

<div class="g4" style="margin-bottom:16px">
  <div class="kpi"><small>Pendapatan Titip</small><b class="num">{{ $rp($kpi['titip']) }}</b></div>
  <div class="kpi"><small>Penjualan Preloved</small><b class="num">{{ $rp($kpi['preloved']) }}</b></div>
  <div class="kpi"><small>Packing</small><b class="num">{{ $rp($kpi['packing']) }}</b></div>
  <div class="kpi"><small>Total pendapatan</small><b class="num">{{ $rp($kpi['total']) }}</b></div>
</div>
<div class="g2" style="margin-bottom:16px">
  <div class="kpi"><small>Tagihan baru</small><b class="num">{{ $kpi['new'] }}</b><span>Dibuat dalam periode</span></div>
  <div class="kpi"><small>Gagal / kedaluwarsa</small><b class="num">{{ $kpi['fail'] }}</b><span>Dari tagihan yang dibuat dalam periode</span></div>
</div>

<div class="stack">
  <div class="card">
    <div class="card-h">
      <h3>Pendapatan harian</h3>
      <div class="legend"><span>Titip</span><span class="t">Preloved dan Packing</span></div>
    </div>
    <div class="chart" role="img" aria-label="Grafik batang pendapatan harian, Titip dan Preloved plus Packing">
      @foreach ($daily as $d)
        @php
            $other = $d['preloved'] + $d['packing'];
            $hT = $d['titip'] > 0 ? max(3, (int) round($d['titip'] / $maxDay * $maxPx)) : 0;
            $hO = $other > 0 ? max(3, (int) round($other / $maxDay * $maxPx)) : 0;
            $showLabel = $days <= 14 || $loop->index % 5 === 0;
        @endphp
        <div class="c" title="{{ $d['label'] }}: Titip {{ $rp($d['titip']) }}, Preloved {{ $rp($d['preloved']) }}, Packing {{ $rp($d['packing']) }}">
          <i class="t" style="height: {{ $hO }}px"></i>
          <i style="height: {{ $hT }}px"></i>
          <small>{{ $showLabel ? $d['label'] : "\u{00a0}" }}</small>
        </div>
      @endforeach
    </div>
    <p class="help" style="margin-top:10px">Hanya pembayaran berstatus berhasil. Tidak ada kolom tanggal bayar, jadi pendapatan dikelompokkan per tanggal terakhir pesanan diperbarui (saat callback Tripay mengubah status jadi lunas). Data dibatasi 5000 tagihan terbaru per sumber.</p>
  </div>

  <div class="g2">
    <div class="card flush">
      <div class="card-h"><h3>Per jenis tagihan</h3></div>
      <div class="tw">
        <table class="tbl">
          <thead><tr><th>Jenis</th><th class="r">Tagihan</th><th class="r">Berhasil</th><th class="r">Menunggu</th><th class="r">Gagal</th><th class="r">Pendapatan</th></tr></thead>
          <tbody>
            @foreach ($perType as $row)
              <tr>
                <td><b>{{ $row['label'] }}</b></td>
                <td class="r">{{ $row['count'] }}</td>
                <td class="r">{{ $row['ok'] }}</td>
                <td class="r">{{ $row['wait'] }}</td>
                <td class="r">{{ $row['fail'] }}</td>
                <td class="r">{{ $rp($row['revenue']) }}</td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>

    <div class="card flush">
      <div class="card-h"><h3>Okupansi per ruang</h3></div>
      @if ($rooms->isEmpty())
        <div class="empty"><b>Belum ada ruang</b>Tambahkan ruang titip untuk melihat okupansi.</div>
      @else
        <div class="tw">
          <table class="tbl">
            <thead><tr><th>Ruang</th><th class="r">Terpakai</th><th style="min-width:140px">Okupansi</th></tr></thead>
            <tbody>
              @foreach ($rooms as $room)
                @php
                    $pct = min(100, (int) $room->capacity_pct);
                    $cls = $pct >= 100 ? 'full' : ($pct >= 80 ? 'hi' : '');
                @endphp
                <tr>
                  <td><b>{{ $room->name }}</b>@if ($room->location)<small>{{ $room->location }}</small>@endif</td>
                  <td class="r">{{ (int) $room->capacity_used }} / {{ (int) $room->capacity_total }}</td>
                  <td>
                    <div class="meter {{ $cls }}" role="progressbar" aria-valuenow="{{ $pct }}" aria-valuemin="0" aria-valuemax="100"><i style="width: {{ $pct }}%"></i></div>
                    <small class="num">{{ $pct }}%</small>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      @endif
    </div>
  </div>
</div>
@endsection
