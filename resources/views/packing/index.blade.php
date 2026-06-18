@extends('layouts.dashboard')

@section('title', 'Toko Packing')

@php
    if (! function_exists('rupiah')) {
        function rupiah($n) { return 'Rp ' . number_format($n, 0, ',', '.'); }
    }
@endphp

@section('content')
<div class="pt-2 pb-10">

    {{-- Heading --}}
    <div class="mb-6">
        <h1 class="text-xl font-extrabold text-white font-display">Toko Packing</h1>
        <p class="text-xs mt-0.5" style="color:rgba(255,255,255,0.4);">Material packing berkualitas untuk barangmu</p>
    </div>

    {{-- Filter kategori --}}
    <div class="flex gap-2 overflow-x-auto pb-2 mb-5 no-scrollbar">
        @foreach ($categories as $cat)
            @php $active = $cat === $category; @endphp
            <a href="{{ route('packing.index', ['kategori' => $cat]) }}"
               class="shrink-0 px-3 py-1.5 rounded-full text-xs font-semibold transition-all"
               style="{{ $active
                    ? 'background:linear-gradient(135deg,#7c3aed,#6366f1);color:#fff;'
                    : 'background:rgba(255,255,255,0.06);color:rgba(255,255,255,0.5);border:1px solid rgba(255,255,255,0.1);' }}">
                {{ $cat }}
            </a>
        @endforeach
    </div>

    {{-- Grid produk --}}
    @if ($products->isEmpty())
        <div class="rounded-2xl p-10 text-center" style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.09);">
            <p class="text-3xl mb-2">📦</p>
            <p class="text-sm" style="color:rgba(255,255,255,0.5);">Belum ada produk di kategori ini.</p>
        </div>
    @else
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
            @foreach ($products as $p)
                <div class="rounded-2xl overflow-hidden transition-all hover:-translate-y-0.5"
                     style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.09);">
                    {{-- Gambar / emoji --}}
                    <a href="{{ route('packing.show', $p) }}" class="block h-28 flex items-center justify-center relative"
                       style="background:rgba(124,58,237,0.08);">
                        @if($p->primary_image)
                            <img src="{{ asset('storage/'.$p->primary_image) }}" alt="{{ $p->name }}" class="w-full h-full object-cover">
                        @else
                            <x-lucide-package class="w-12 h-12" style="color:#a78bfa;" />
                        @endif
                        @if ($p->discount)
                            <span class="absolute top-2 left-2 px-2 py-0.5 rounded-full text-[10px] font-bold flex items-center gap-1"
                                  style="background:#ef4444;color:white;">
                                <x-lucide-tag class="w-2.5 h-2.5" /> Promo {{ $p->discount }}%
                            </span>
                        @endif
                    </a>
                    <div class="p-3">
                        <a href="{{ route('packing.show', $p) }}" class="block text-xs font-bold text-white leading-snug mb-0.5 hover:text-violet-300 transition-colors">{{ $p->name }}</a>
                        <p class="text-[10px] mb-1.5" style="color:rgba(255,255,255,0.35);">Stok: {{ $p->stock }}</p>
                        <div class="flex items-center gap-1 mb-3">
                            @if ($p->discount)
                                <span class="text-[10px] line-through" style="color:rgba(255,255,255,0.3);">{{ rupiah($p->original_price) }}</span>
                            @endif
                            <span class="text-sm font-bold" style="color:#a78bfa;">{{ rupiah($p->price) }}</span>
                        </div>
                        <a href="{{ route('packing.show', $p) }}"
                           class="block w-full text-center py-2 rounded-xl text-[10px] font-bold transition-all hover:opacity-90"
                           style="background:linear-gradient(135deg,#7c3aed,#6366f1);color:white;">
                            Beli
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
