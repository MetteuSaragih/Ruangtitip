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
        <div class="rounded-2xl p-10 text-center" style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.09);">
            <x-lucide-warehouse class="w-10 h-10 mx-auto mb-3" style="color:rgba(167,139,250,0.5);" />
            <p class="text-sm" style="color:rgba(255,255,255,0.5);">Belum ada gudang yang tersedia saat ini.</p>
        </div>
    @else
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
        @foreach ($storages as $s)
            @php
                $images = !empty($s->photos) ? $s->photos : ($s->primary_photo ? [$s->primary_photo] : []);
            @endphp
            <div class="rounded-2xl overflow-hidden transition-all hover:-translate-y-0.5" style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.09);">
                <div class="h-36 flex items-center justify-center relative rt-carousel" style="background:rgba(124,58,237,0.12);">
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
                <div class="p-4">
                    <h3 class="text-sm font-bold text-white leading-snug mb-1">{{ $s->name }}</h3>
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
                    <div class="flex flex-wrap gap-1.5 mb-4">
                        @foreach (($s->facilities ?? []) as $tag)
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-medium" style="background:rgba(255,255,255,0.07);color:rgba(255,255,255,0.5);">{{ $tag }}</span>
                        @endforeach
                    </div>
                    <div class="flex items-center justify-between pt-3" style="border-top:1px solid rgba(255,255,255,0.07);">
                        <div>
                            <p class="text-sm font-bold" style="color:#7c3aed;">{{ rp($s->min_price) }}</p>
                            <p class="text-[10px]" style="color:rgba(255,255,255,0.3);">mulai dari / hari</p>
                        </div>
                        <a href="{{ route('ruang-titip.detail', $s) }}" class="px-4 py-2 rounded-xl text-xs font-bold transition-all hover:scale-105" style="border:1.5px solid #7c3aed;color:#a78bfa;background:#7c3aed10;">Lihat</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    @endif
</div>
@endsection
