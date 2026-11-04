@extends('layouts.admin')

@section('title', $title)

@push('styles')
<style>
    .rq-note { white-space: pre-line; max-width: 46ch; }
    .rq-links { display: flex; gap: 12px; flex-wrap: wrap; font-size: 13px; }
</style>
@endpush

@section('content')
@php
    $tabDefs = ['aktif' => 'Aktif', 'selesai' => 'Selesai', 'ditolak' => 'Ditolak'];
    $stTone = ['menunggu' => 'warn', 'diproses' => 'info', 'selesai' => 'ok', 'ditolak' => 'err'];
    $fmtDate = fn ($d) => $d ? \Illuminate\Support\Carbon::parse($d)->translatedFormat('d M Y') : '-';
@endphp

<div class="ph">
  <div><h1>{{ $title }}</h1><p>{{ $subtitle }}</p></div>
</div>

@if (! $ready)
  <div class="al warn">
    <svg class="ic" aria-hidden="true"><use href="#i-warn"/></svg>
    <div><b>Tabel permintaan belum tersedia</b><p>Jalankan <span class="mono">php artisan migrate</span> terlebih dulu.</p></div>
  </div>
@else
  <div class="bar">
    <nav class="tabs" aria-label="Status permintaan">
      @foreach ($tabDefs as $key => $label)
        <a href="{{ route('admin.permintaan.index', ['type' => $type, 'tab' => $key]) }}" @if ($tab === $key) aria-current="page" @endif>{{ $label }} <i>{{ $counts[$key] }}</i></a>
      @endforeach
    </nav>
  </div>

  <section class="card flush">
    @if ($requests->isEmpty())
      <div class="empty"><b>Tidak ada permintaan</b>Belum ada permintaan pada tab ini.</div>
    @else
      <div class="tw">
        <table class="tbl">
          <thead>
            <tr>
              <th>Pesanan</th>
              <th>Pelanggan</th>
              <th>Permintaan</th>
              <th>Status</th>
              <th class="acts">Aksi</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($requests as $req)
              @php
                  $order = $req->order;
                  $cust = $req->user;
                  $p = $req->payload ?? [];
                  $wa = \App\Support\Phone::wa($cust?->phone);
                  $next = $transitions[$req->status] ?? [];
              @endphp
              <tr>
                <td>
                  @if ($order && Route::has('admin.titip.show'))
                    <b><a href="{{ route('admin.titip.show', $order) }}">{{ $order->code() }}</a></b>
                  @else
                    <b>{{ $order?->code() ?? '-' }}</b>
                  @endif
                  <small class="num">{{ $req->created_at?->translatedFormat('d M Y, H:i') }}</small>
                </td>
                <td>
                  <b>{{ $cust?->name ?? '-' }}</b>
                  <small>{{ $cust?->phone ?: ($cust?->email ?? '') }}</small>
                </td>
                <td>
                  @if ($req->type === 'perpanjang')
                    <b>+{{ (int) ($p['months'] ?? 0) }} bulan</b>
                    <small>sampai {{ $fmtDate($p['new_date_end'] ?? null) }}</small>
                  @elseif ($req->type === 'kirim')
                    <b>{{ $p['label'] ?? 'Alamat' }}</b>
                    <small>{{ $p['address'] ?? '-' }}</small>
                  @else
                    <b>{{ $fmtDate($p['date'] ?? null) }} &middot; {{ ($p['method'] ?? '') === 'diwakilkan' ? 'Diwakilkan' : 'Diambil sendiri' }}</b>
                    @if (! empty($p['rep_name']))<small>Perwakilan: {{ $p['rep_name'] }}</small>@endif
                  @endif
                  @if ($req->catatan)<small class="rq-note">{{ $req->catatan }}</small>@endif
                </td>
                <td><span class="st {{ $stTone[$req->status] ?? 'mute' }}">{{ \App\Models\TitipanRequest::STATUS_LABELS[$req->status] ?? $req->status }}</span></td>
                <td class="acts">
                  <div class="rq-links" style="justify-content:flex-end">
                    @if ($wa)<a href="{{ $wa }}" target="_blank" rel="noopener">WhatsApp</a>@endif
                    @if ($next)
                      <button type="button" class="btn btn-primary sm" data-open="rq{{ $req->id }}">Tindak lanjuti</button>
                    @endif
                  </div>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>

      <div class="pager">
        <span>{{ $requests->total() }} permintaan &middot; halaman {{ $requests->currentPage() }} dari {{ $requests->lastPage() }}</span>
        <nav aria-label="Halaman">
          @if ($requests->previousPageUrl())<a href="{{ $requests->previousPageUrl() }}">Sebelumnya</a>@else<span class="pg off">Sebelumnya</span>@endif
          @if ($requests->nextPageUrl())<a href="{{ $requests->nextPageUrl() }}">Berikutnya</a>@else<span class="pg off">Berikutnya</span>@endif
        </nav>
      </div>
    @endif
  </section>

  @foreach ($requests as $req)
    @php $next = $transitions[$req->status] ?? []; @endphp
    @if ($next)
      <dialog class="dlg" id="rq{{ $req->id }}">
        <form method="POST" action="{{ route('admin.permintaan.update', $req) }}" data-confirm="Simpan perubahan status permintaan ini?">
          @csrf
          <div class="dh"><h3>{{ $req->order?->code() }} &middot; {{ $req->typeLabel() }}</h3><button type="button" class="btn btn-text" data-close>Tutup</button></div>
          <div class="db">
            <div class="field">
              <label for="rqs{{ $req->id }}">Status baru</label>
              <select class="input" id="rqs{{ $req->id }}" name="status" required>
                @foreach ($next as $s)
                  <option value="{{ $s }}">{{ \App\Models\TitipanRequest::STATUS_LABELS[$s] }}</option>
                @endforeach
              </select>
            </div>
            <div class="field">
              <label for="rqc{{ $req->id }}">Catatan untuk pelanggan</label>
              <textarea class="input" id="rqc{{ $req->id }}" name="catatan" maxlength="500"></textarea>
              <span class="help">Wajib diisi bila permintaan ditolak.</span>
            </div>
            @if ($req->type === 'perpanjang')
              <p class="help">Saat diselesaikan, tanggal akhir titip pesanan diperbarui ke {{ $fmtDate($req->payload['new_date_end'] ?? null) }}.</p>
            @endif
          </div>
          <div class="df">
            <button type="button" class="btn btn-ghost" data-close>Batal</button>
            <button type="submit" class="btn btn-primary">Simpan</button>
          </div>
        </form>
      </dialog>
    @endif
  @endforeach
@endif
@endsection
