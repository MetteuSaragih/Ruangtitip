@extends('layouts.dashboard')
@section('title', 'Ruang Titip')

@php function rp($n){ return 'Rp '.number_format($n,0,',','.'); } @endphp

@section('content')
<div class="pt-6 pb-28">
    <div class="mb-6">
        <h1 class="text-xl font-extrabold text-white font-display">Ruang Titip</h1>
        <p class="text-xs mt-1" style="color:rgba(255,255,255,0.4);">Pilih gudang untuk menitipkan barangmu</p>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
        @foreach ($storages as $s)
            <div class="rounded-2xl overflow-hidden transition-all hover:-translate-y-0.5" style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.09);">
                <div class="h-36 flex items-center justify-center relative" style="background:linear-gradient(135deg,{{ $s->color }}20,{{ $s->color }}08);">
                    <span class="text-6xl">{{ $s->emoji }}</span>
                    <div class="absolute top-3 right-3 flex items-center gap-1 px-2 py-1 rounded-full text-[10px] font-bold" style="background:rgba(0,0,0,0.35);color:#fbbf24;">
                        <x-lucide-star class="w-3 h-3" /> {{ $s->rating }}
                    </div>
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
                            <span class="text-[10px] font-bold" style="color:{{ $s->filled >= 80 ? '#7c3aed' : '#34d399' }};">{{ $s->filled }}%</span>
                        </div>
                        <div class="h-1.5 rounded-full overflow-hidden" style="background:rgba(255,255,255,0.07);">
                            <div class="h-full rounded-full" style="width:{{ $s->filled }}%;background:linear-gradient(90deg,{{ $s->color }},{{ $s->color }}99);"></div>
                        </div>
                    </div>
                    <div class="flex flex-wrap gap-1.5 mb-4">
                        @foreach (($s->tags ?? []) as $tag)
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-medium" style="background:rgba(255,255,255,0.07);color:rgba(255,255,255,0.5);">{{ $tag }}</span>
                        @endforeach
                    </div>
                    <div class="flex items-center justify-between pt-3" style="border-top:1px solid rgba(255,255,255,0.07);">
                        <div>
                            <p class="text-sm font-bold" style="color:{{ $s->color }};">{{ rp($s->price) }}</p>
                            <p class="text-[10px]" style="color:rgba(255,255,255,0.3);">per kardus/bulan</p>
                        </div>
                        <a href="{{ route('ruang-titip.detail', $s) }}" class="px-4 py-2 rounded-xl text-xs font-bold transition-all hover:scale-105" style="border:1.5px solid {{ $s->color }};color:{{ $s->color }};background:{{ $s->color }}10;">Lihat</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
