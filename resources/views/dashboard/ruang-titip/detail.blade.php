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

<<<<<<< HEAD
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
=======
    @php
        $images = !empty($storage->photos) ? $storage->photos : ($storage->primary_photo ? [$storage->primary_photo] : []);
    @endphp
    <div class="relative rounded-2xl mb-5 rt-carousel" style="background:rgba(124,58,237,0.1);height:220px;overflow:hidden;display:flex;align-items:center;justify-content:center;">
        @forelse ($images as $i => $img)
            <img src="{{ asset('storage/'.$img) }}" alt="{{ $storage->name }}" class="rt-slide {{ $i === 0 ? 'active' : '' }}">
        @empty
            <x-lucide-warehouse class="w-16 h-16" style="color:#a78bfa;" />
        @endforelse
        @if (count($images) > 1)
            <button type="button" class="rt-carousel-btn rt-prev" onclick="event.preventDefault();event.stopPropagation();rtCarouselNav(this,-1)">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="m15 18-6-6 6-6"/></svg>
            </button>
            <button type="button" class="rt-carousel-btn rt-next" onclick="event.preventDefault();event.stopPropagation();rtCarouselNav(this,1)">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="m9 18 6-6-6-6"/></svg>
            </button>
            <div class="rt-carousel-dots">
                @foreach ($images as $i => $img)
                    <span class="{{ $i === 0 ? 'active' : '' }}"></span>
                @endforeach
            </div>
        @endif
    </div>

    <div class="rounded-2xl p-5 mb-5" style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.09);">
        <div class="flex items-start justify-between mb-3">
            <h2 class="text-lg font-extrabold text-white font-display">{{ $storage->name }}</h2>
            <span class="flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold" style="background:rgba(52,211,153,0.12);color:#34d399;">Aktif</span>
        </div>
        <div class="flex items-center gap-1.5 mb-4">
            <x-lucide-map-pin class="w-3.5 h-3.5 shrink-0" style="color:rgba(255,255,255,0.3);" />
            <p class="text-xs" style="color:rgba(255,255,255,0.5);">{{ $storage->address }}</p>
        </div>

        {{-- Harga + Kapasitas (progress bar) --}}
        <div class="grid grid-cols-2 gap-3 mb-3">
            <div class="rounded-xl p-3" style="background:rgba(255,255,255,0.04);">
                <p class="text-[10px] mb-0.5" style="color:rgba(255,255,255,0.35);">Mulai dari</p>
                <p class="text-xs font-semibold text-white">{{ rp($storage->min_price) }}/hari</p>
            </div>
            <div class="rounded-xl p-3" style="background:rgba(255,255,255,0.04);">
                <p class="text-[10px] mb-0.5" style="color:rgba(255,255,255,0.35);">Lokasi</p>
                <p class="text-xs font-semibold text-white">{{ $storage->location ?: '-' }}</p>
            </div>
        </div>
        {{-- Kapasitas terisi sebagai progress bar --}}
        <div class="rounded-xl p-3" style="background:rgba(255,255,255,0.04);">
            <div class="flex justify-between mb-1.5">
                <span class="text-[10px]" style="color:rgba(255,255,255,0.4);">Kapasitas terisi</span>
                <span class="text-[10px] font-bold" style="color:{{ $storage->capacity_pct >= 80 ? '#fb923c' : '#34d399' }};">{{ $storage->capacity_pct }}%</span>
            </div>
            <div class="h-2 rounded-full overflow-hidden" style="background:rgba(255,255,255,0.07);">
                <div class="h-full rounded-full" style="width:{{ $storage->capacity_pct }}%;background:linear-gradient(90deg,#7c3aed,#7c3aed99);"></div>
            </div>
        </div>

        <div class="flex flex-wrap gap-1.5 mt-3">
            @foreach (($storage->facilities ?? []) as $tag)
                <span class="px-2 py-0.5 rounded-full text-[10px] font-medium" style="background:rgba(255,255,255,0.07);color:rgba(255,255,255,0.5);">{{ $tag }}</span>
            @endforeach
        </div>
>>>>>>> hostinger/main
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
