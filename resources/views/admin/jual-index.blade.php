@extends('layouts.admin')

@section('title', 'Pengajuan Jual')

@php
$tabs = ['perlu' => 'Perlu tindakan', 'berjalan' => 'Berjalan', 'selesai' => 'Selesai', 'semua' => 'Semua'];
@endphp

@section('content')
<div class="ph">
  <div>
    <h1>Pengajuan Jual</h1>
    <p>Barang yang diajukan pelanggan untuk dibeli Ruang Titip. Perlu tindakan: seleksi, pemeriksaan barang, dan pembayaran ke penjual.</p>
  </div>
</div>

@if ($missing)
  <div class="card">
    <div class="empty">
      <b>Fitur pengajuan belum aktif</b>
      Tabel pengajuan belum ada di database. Jalankan <span class="mono">php artisan migrate</span> lalu muat ulang halaman ini.
    </div>
  </div>
@else
  <div class="bar">
    <div class="tabs">
      @foreach ($tabs as $k => $label)
        <a href="{{ route('admin.jual.index', array_filter(['tab' => $k === 'perlu' ? null : $k, 'q' => $q ?: null])) }}" @if ($tab === $k) aria-current="page" @endif>{{ $label }} <i>{{ $counts[$k] ?? 0 }}</i></a>
      @endforeach
    </div>
    <form method="GET" action="{{ route('admin.jual.index') }}" class="search" role="search">
      @if ($tab !== 'perlu')<input type="hidden" name="tab" value="{{ $tab }}">@endif
      <svg class="ic" aria-hidden="true"><use href="#n-search"/></svg>
      <input type="search" name="q" value="{{ $q }}" placeholder="Cari kode, barang, penjual" aria-label="Cari pengajuan">
    </form>
  </div>

  <div class="card flush">
    @if ($rows->isEmpty())
      <div class="empty"><b>Tidak ada pengajuan</b>Pengajuan dengan filter ini akan muncul di sini.</div>
    @else
      <div class="tw">
        <table class="tbl">
          <thead>
            <tr><th>Pengajuan</th><th>Penjual</th><th>Kategori</th><th class="r">Penawaran</th><th>Status</th><th class="acts">Aksi</th></tr>
          </thead>
          <tbody>
            @foreach ($rows as $row)
              @php
                $meta = \App\Models\PrelovedSubmission::STATUS[$row->status] ?? [$row->status, 'mute'];
                $hot = in_array($row->status, \App\Http\Controllers\Admin\PrelovedSubmissionController::GROUPS['perlu'], true);
                $thumb = collect($row->photos ?? [])->first();
              @endphp
              <tr class="link">
                <td>
                  <div class="row-flex">
                    <span class="thumb">
                      @if ($thumb)
                        <img src="{{ \Illuminate\Support\Facades\Storage::url($thumb) }}" alt="">
                      @else
                        <svg class="ic" aria-hidden="true"><use href="#n-coin"/></svg>
                      @endif
                    </span>
                    <div>
                      <b><a href="{{ route('admin.jual.show', $row->code) }}">{{ $row->name }}</a></b>
                      <small><span class="mono">{{ $row->code }}</span> &middot; {{ optional($row->created_at)->format('d M Y') }}</small>
                    </div>
                  </div>
                </td>
                <td>{{ optional($row->user)->name ?? '-' }}<small>{{ $row->source === 'titip' ? 'Dari titipan' : 'Barang luar' }}</small></td>
                <td>{{ $row->category }}</td>
                <td class="r num">{{ $row->offer ? 'Rp ' . number_format($row->offer, 0, ',', '.') : '-' }}</td>
                <td><span class="st {{ $meta[1] }}">{{ $meta[0] }}</span></td>
                <td class="acts">
                  <a class="btn {{ $hot ? 'btn-primary' : 'btn-ghost' }} sm" href="{{ route('admin.jual.show', $row->code) }}">{{ $hot ? 'Tindak lanjuti' : 'Lihat' }}</a>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
      @include('admin.preloved-pager', ['p' => $rows])
    @endif
  </div>
@endif
@endsection
