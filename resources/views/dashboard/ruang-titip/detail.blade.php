@extends('layouts.dashboard')
@section('title', $storage->name)

@php function rp($n){ return 'Rp '.number_format($n,0,',','.'); } @endphp

@section('content')
<div class="max-w-xl mx-auto pt-6 pb-28">
    <a href="{{ route('ruang-titip.index') }}" class="flex items-center gap-1.5 text-sm mb-6 hover:text-violet-300 transition-colors" style="color:rgba(255,255,255,0.4);">
        <x-lucide-chevron-left class="w-4 h-4" /> Kembali
    </a>

    @php
        $images = !empty($storage->photos) ? $storage->photos : ($storage->primary_photo ? [$storage->primary_photo] : []);
    @endphp
    <div class="relative rounded-2xl overflow-hidden mb-5 flex items-center justify-center rt-carousel" style="background:rgba(124,58,237,0.1);height:220px;">
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
                <p class="text-[10px] mb-0.5" style="color:rgba(255,255,255,0.35);">Rating</p>
                <p class="text-xs font-semibold text-white">{{ $storage->location ?: '-' }}</p>
            </div>
        </div>
        {{-- Kapasitas terisi sebagai progress bar --}}
        <div class="rounded-xl p-3" style="background:rgba(255,255,255,0.04);">
            <div class="flex justify-between mb-1.5">
                <span class="text-[10px]" style="color:rgba(255,255,255,0.4);">Kapasitas terisi</span>
                <span class="text-[10px] font-bold" style="color:{{ $storage->capacity_pct >= 80 ? '#7c3aed' : '#34d399' }};">{{ $storage->capacity_pct }}%</span>
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
    </div>

    <div class="mb-4 hidden">
        <h3 class="text-sm font-bold text-white mb-3">Ulasan Gudang</h3>
        <div class="space-y-3">
            @foreach ([] as $r)
                <div class="rounded-2xl p-4" style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.07);">
                    <div class="flex items-center justify-between mb-2">
                        <div class="flex items-center gap-2">
                            <div class="w-7 h-7 rounded-full flex items-center justify-center text-[10px] font-bold text-white" style="background:{{ $r->color }};">{{ $r->avatar }}</div>
                            <span class="text-xs font-semibold text-white">{{ $r->reviewer_name }}</span>
                        </div>
                        <div class="flex gap-0.5">
                            @for ($j = 0; $j < $r->rating; $j++)<x-lucide-star class="w-3 h-3" style="color:#fbbf24;" />@endfor
                        </div>
                    </div>
                    <p class="text-xs leading-relaxed" style="color:rgba(255,255,255,0.55);">{{ $r->text }}</p>
                </div>
            @endforeach
        </div>
    </div>

    <a href="{{ route('ruang-titip.detail-item') }}" class="block text-center w-full py-4 rounded-2xl font-bold text-sm text-white transition-all hover:scale-[1.02]" style="background:linear-gradient(135deg,#7c3aed,#6366f1);box-shadow:0 8px 24px rgba(124,58,237,0.45);">
        Pesan Sekarang →
    </a>
</div>
@endsection
