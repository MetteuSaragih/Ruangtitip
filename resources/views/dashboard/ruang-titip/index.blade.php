@extends('layouts.dashboard')
@section('title', 'Ruang Titip')

@php function rp($n){ return 'Rp '.number_format($n,0,',','.'); } @endphp

@section('content')
<div class="pt-6 pb-28">
    <div class="mb-6">
        <h1 class="text-xl font-extrabold text-white font-display">Ruang Titip</h1>
        <p class="text-xs mt-1" style="color:rgba(255,255,255,0.4);">Pilih gudang untuk menitipkan barangmu</p>
    </div>

    @if ($storages->isEmpty())
        <div class="rounded-2xl py-16 flex flex-col items-center text-center" style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.09);">
            <div class="relative inline-flex mx-auto mb-5">
                <div class="absolute inset-0 rounded-3xl blur-xl opacity-25" style="background:linear-gradient(135deg,#7c3aed,#a78bfa);"></div>
                <div class="relative w-20 h-20 rounded-3xl flex items-center justify-center" style="background:linear-gradient(135deg,rgba(124,58,237,0.2),rgba(167,139,250,0.1));border:1px solid rgba(124,58,237,0.35);">
                    <svg class="w-9 h-9" fill="none" stroke="#a78bfa" stroke-width="1.5" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 9.5L12 4l9 5.5V20H3V9.5z"/><path d="M9 20v-5h6v5"/><line x1="12" y1="4" x2="12" y2="9"/>
                    </svg>
                </div>
            </div>
            <p class="text-sm font-bold text-white mb-1">Belum ada gudang tersedia</p>
            <p class="text-xs" style="color:rgba(255,255,255,0.4);">Gudang akan segera hadir di kotamu</p>
        </div>
    @else
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3" style="gap:24px;">
        @foreach ($storages as $s)
            @php
                $images = !empty($s->photos) ? $s->photos : ($s->primary_photo ? [$s->primary_photo] : []);
            @endphp
            <div class="rounded-2xl overflow-hidden transition-all hover:-translate-y-1"
                 style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.09);box-shadow:0 2px 12px rgba(0,0,0,0.2);">
                <div class="relative rt-carousel" style="height:160px;background:rgba(124,58,237,0.12);overflow:hidden;display:flex;align-items:center;justify-content:center;">
                    @forelse ($images as $i => $img)
                        <img src="{{ asset('storage/'.$img) }}" alt="{{ $s->name }}" class="rt-slide {{ $i === 0 ? 'active' : '' }}">
                    @empty
                        <x-lucide-warehouse class="w-12 h-12" style="color:#a78bfa;" />
                    @endforelse
                    @if (count($images) > 1)
                        <button type="button" class="rt-carousel-btn rt-prev" onclick="event.preventDefault();event.stopPropagation();rtCarouselNav(this,-1)">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="m15 18-6-6 6-6"/></svg>
                        </button>
                        <button type="button" class="rt-carousel-btn rt-next" onclick="event.preventDefault();event.stopPropagation();rtCarouselNav(this,1)">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="m9 18 6-6-6-6"/></svg>
                        </button>
                        <div class="rt-carousel-dots">
                            @foreach ($images as $i => $img)
                                <span class="{{ $i === 0 ? 'active' : '' }}"></span>
                            @endforeach
                        </div>
                    @endif
                </div>
                <div class="p-5">
                    <h3 class="text-sm font-bold text-white leading-snug mb-1.5">{{ $s->name }}</h3>
                    <div class="flex items-center gap-1.5 mb-3">
                        <x-lucide-map-pin class="w-3 h-3 shrink-0" style="color:rgba(255,255,255,0.3);" />
                        <p class="text-[11px] truncate" style="color:rgba(255,255,255,0.45);">{{ $s->address }}</p>
                    </div>
                    <div class="mb-3">
                        <div class="flex justify-between mb-1.5">
                            <span class="text-[10px]" style="color:rgba(255,255,255,0.4);">Kapasitas terisi</span>
                            <span class="text-[10px] font-bold" style="color:{{ $s->capacity_pct >= 80 ? '#7c3aed' : '#34d399' }};">{{ $s->capacity_pct }}%</span>
                        </div>
                        <div class="h-1.5 rounded-full overflow-hidden" style="background:rgba(255,255,255,0.07);">
                            <div class="h-full rounded-full" style="width:{{ $s->capacity_pct }}%;background:linear-gradient(90deg,#7c3aed,#7c3aed99);"></div>
                        </div>
                    </div>
                    <div class="flex flex-wrap gap-1.5 mb-5">
                        @foreach (($s->facilities ?? []) as $tag)
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-medium" style="background:rgba(255,255,255,0.07);color:rgba(255,255,255,0.5);">{{ $tag }}</span>
                        @endforeach
                    </div>
                    <div class="flex items-center justify-between pt-4" style="border-top:1px solid rgba(255,255,255,0.07);">
                        <div>
                            <p class="text-base font-extrabold" style="color:#a78bfa;">{{ rp($s->min_price) }}</p>
                            <p class="text-[10px]" style="color:rgba(255,255,255,0.3);">mulai dari / bulan</p>
                        </div>
                        <a href="{{ route('ruang-titip.detail', $s) }}"
                           class="px-5 py-2.5 rounded-xl text-xs font-bold text-white transition-all hover:opacity-90 hover:scale-105"
                           style="background:linear-gradient(135deg,#7c3aed,#6366f1);box-shadow:0 3px 12px rgba(124,58,237,0.35);">Lihat</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    @endif
</div>
@endsection
