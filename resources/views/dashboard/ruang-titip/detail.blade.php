@extends('layouts.ruang-titip')
@section('title', $storage->name)

@php function rp($n){ return 'Rp'.number_format($n,0,',','.'); } @endphp

@push('styles')
<style>
.detail-wrap{max-width:720px;margin:0 auto}
.detail-photo{position:relative;aspect-ratio:16/9;background:var(--sand);border:1.5px solid var(--ink);border-radius:18px;overflow:hidden;display:flex;align-items:center;justify-content:center;margin-top:20px}
.detail-photo img{position:absolute;inset:0;width:100%;height:100%;object-fit:cover}
.detail-grid{display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-top:16px}
.detail-box{background:var(--cream);border:1px solid var(--line-strong);border-radius:14px;padding:14px 16px}
.detail-box small{display:block;font-size:12px;color:var(--muted);margin-bottom:2px}
.detail-box strong{font-size:15px}
</style>
@endpush

@section('content')
<main class="wrap detail-wrap">
  <a class="back-link" href="{{ route('ruang-titip.index') }}" style="margin-top:20px">
    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5M11 18l-6-6 6-6"/></svg>
    Kembali
  </a>

  @php
    $images = !empty($storage->photos) ? $storage->photos : ($storage->primary_photo ? [$storage->primary_photo] : []);
  @endphp
  <div class="detail-photo">
    @forelse ($images as $img)
      <img src="{{ asset('storage/'.$img) }}" alt="{{ $storage->name }}">
      @break
    @empty
      <span style="display:flex;flex-direction:column;align-items:center;gap:8px;color:var(--muted);font-size:13px">
        <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#6B6557" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M3 10 12 4l9 6v10H3z"/><path d="M8 20v-6h8v6M8 17h8"/></svg>
        [Foto gudang]
      </span>
    @endforelse
  </div>

  <div class="panel">
    <div style="display:flex;justify-content:space-between;align-items:flex-start;gap:12px;margin-bottom:6px">
      <h1 style="font-size:24px;font-weight:800;letter-spacing:-.5px">{{ $storage->name }}</h1>
      <span style="font-size:13px;font-weight:700;padding:5px 12px;border-radius:999px;background:var(--depot-light);color:#1F4535;border:1px solid var(--depot);white-space:nowrap;">Aktif</span>
    </div>
    <p class="loc" style="margin-bottom:16px">
      <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 21s-7-6.2-7-11.5a7 7 0 0 1 14 0C19 14.8 12 21 12 21z"/><circle cx="12" cy="9.5" r="2.5"/></svg>
      {{ $storage->address }}
    </p>

    <div class="detail-grid">
      <div class="detail-box"><small>Mulai dari</small><strong>{{ rp($storage->min_price) }} /bulan</strong></div>
      <div class="detail-box"><small>Lokasi</small><strong>{{ $storage->location ?: '-' }}</strong></div>
    </div>

    <div class="detail-box" style="margin-top:12px">
      <div class="cap-row" style="margin-bottom:6px"><span>Kapasitas terisi</span><b>{{ $storage->capacity_pct }}%</b></div>
      <div class="bar"><i class="{{ $storage->capacity_pct >= 80 ? 'warn' : '' }}" style="width:{{ $storage->capacity_pct }}%"></i></div>
    </div>

    @if (count($storage->facilities ?? []))
      <ul class="feat" aria-label="Fasilitas" style="margin-top:14px">
        @foreach ($storage->facilities as $tag)<li>{{ $tag }}</li>@endforeach
      </ul>
    @endif
  </div>

  <a href="{{ route('ruang-titip.detail-item') }}" class="btn btn-primary" style="width:100%;margin-top:20px;margin-bottom:40px">
    Pesan Sekarang
    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
  </a>
</main>
@endsection
