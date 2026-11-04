@extends('layouts.admin')

@section('title', 'Pesanan titip')

@php
    $rp = fn ($n) => 'Rp ' . number_format((int) $n, 0, ',', '.');
    $tone = ['menunggu_pembayaran' => 'warn', 'penjadwalan_penjemputan' => 'info', 'dalam_gudang' => 'ok', 'proses_pengembalian' => 'info', 'selesai' => 'mute'];
@endphp

@section('content')
<div class="ph">
    <div>
        <h1>Pesanan titip</h1>
        <p>Semua pesanan penitipan barang, dari pembayaran sampai barang kembali ke pelanggan.</p>
    </div>
</div>

<div class="bar">
    <div class="tabs" role="navigation" aria-label="Filter status">
        @foreach ($tabs as $key => $label)
            <a href="{{ route('admin.titip.index', array_filter(['tab' => $key === 'semua' ? null : $key, 'q' => $q !== '' ? $q : null])) }}" @if ($tab === $key) aria-current="page" @endif>
                {{ $label }} <i>{{ $counts[$key] ?? 0 }}</i>
            </a>
        @endforeach
    </div>
    <form class="search" method="GET" action="{{ route('admin.titip.index') }}" role="search">
        @if ($tab !== 'semua')<input type="hidden" name="tab" value="{{ $tab }}">@endif
        <svg class="ic" aria-hidden="true"><use href="#n-search"/></svg>
        <input type="search" name="q" value="{{ $q }}" placeholder="Cari kode, nama, atau email" aria-label="Cari pesanan">
    </form>
</div>

<section class="card flush">
    @if ($orders->isEmpty())
        <div class="empty"><b>Tidak ada pesanan</b>Coba ganti filter atau kata kunci pencarian.</div>
    @else
        <div class="tw">
            <table class="tbl">
                <thead>
                    <tr>
                        <th>Pesanan</th><th>Pelanggan</th><th>Ruang</th><th>Periode</th><th>Status</th><th class="r">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($orders as $o)
                        @php $href = route('admin.titip.show', $o); @endphp
                        <tr class="link" onclick="if(!event.target.closest('a')) location.href='{{ $href }}'">
                            <td>
                                <a href="{{ $href }}"><b class="mono">{{ $o->code() }}</b></a>
                                <small>{{ optional($o->created_at)->format('d M Y, H:i') }}</small>
                                @if ($o->needs_admin_attention)<span class="tag err">Perlu tindakan</span>@endif
                            </td>
                            <td>{{ $o->user?->name ?? 'Pelanggan' }}<small>{{ $o->user?->email ?? '-' }}</small></td>
                            <td>{{ $o->storage?->name ?? '-' }}<small class="num">{{ $o->totalItems() }} item</small></td>
                            <td class="num">
                                {{ optional($o->date_start)->format('d M Y') ?? '-' }}
                                <small>s/d {{ optional($o->date_end)->format('d M Y') ?? '-' }}</small>
                            </td>
                            <td><span class="st {{ $tone[$o->status] ?? 'info' }}">{{ $o->statusMeta()['label'] }}</span></td>
                            <td class="r">{{ $rp($o->total) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="pager">
            <span>Menampilkan {{ $orders->firstItem() }}-{{ $orders->lastItem() }} dari {{ $orders->total() }} pesanan</span>
            @if ($orders->hasPages())
                <nav aria-label="Halaman">
                    @if ($orders->onFirstPage())
                        <span class="pg off">Sebelumnya</span>
                    @else
                        <a href="{{ $orders->previousPageUrl() }}" rel="prev">Sebelumnya</a>
                    @endif
                    <span class="pg on">{{ $orders->currentPage() }} / {{ $orders->lastPage() }}</span>
                    @if ($orders->hasMorePages())
                        <a href="{{ $orders->nextPageUrl() }}" rel="next">Berikutnya</a>
                    @else
                        <span class="pg off">Berikutnya</span>
                    @endif
                </nav>
            @endif
        </div>
    @endif
</section>
@endsection
