@extends('layouts.dashboard')

@section('title', 'Toko Preloved')

@php
    if (! function_exists('rupiah')) {
        function rupiah($n) { return 'Rp ' . number_format($n, 0, ',', '.'); }
    }
@endphp

@section('content')
<div class="pt-2 pb-10">

    {{-- Heading --}}
    <div class="mb-5">
        <h1 class="text-xl font-extrabold text-white font-display">Toko Preloved</h1>
        <p class="text-xs mt-0.5" style="color:rgba(255,255,255,0.4);">Barang bekas berkualitas dari sesama mahasiswa</p>
    </div>

    {{-- Banner jual barang --}}
    <div class="flex items-center justify-between gap-4 rounded-2xl px-5 py-4 mb-6 flex-wrap"
         style="background:linear-gradient(135deg,rgba(109,40,217,0.35),rgba(99,102,241,0.2));border:1px solid rgba(139,92,246,0.3);">
        <div class="flex items-center gap-3.5">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center text-lg shrink-0" style="background:rgba(139,92,246,0.25);">✨</div>
            <div>
                <p class="text-[10px] font-bold uppercase tracking-wider mb-0.5" style="color:#a78bfa;">Jual Barang Bekasmu</p>
                <p class="text-sm" style="color:rgba(255,255,255,0.85);">Barang kos menumpuk atau mau lulus? <a href="{{ route('preloved.cara-jual') }}" class="font-semibold" style="color:#a78bfa;">Jadi cuan di RuTip!</a></p>
            </div>
        </div>
        <a href="{{ route('preloved.cara-jual') }}" class="shrink-0 flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-semibold transition-all hover:opacity-90" style="background:#7c3aed;color:white;">
            Pelajari <x-lucide-arrow-right class="w-3.5 h-3.5" />
        </a>
    </div>

    {{-- Filter kondisi --}}
    @php
        $filterOptions = [
            'semua'           => 'Semua',
            '95_mulus'        => '95%+ Mulus',
            '85_baik'         => '85%+ Baik',
            '75_pernah_pakai' => 'Pernah Pakai',
        ];
    @endphp
    <div class="flex gap-2 overflow-x-auto pb-2 mb-5 no-scrollbar">
        @foreach ($filterOptions as $key => $label)
            @php $active = ($condition ?? 'semua') === $key; @endphp
            <a href="{{ route('preloved.index', ['kondisi' => $key]) }}"
               class="shrink-0 px-3 py-1.5 rounded-full text-xs font-semibold transition-all"
               style="{{ $active
                    ? 'background:linear-gradient(135deg,#7c3aed,#6366f1);color:#fff;'
                    : 'background:rgba(255,255,255,0.06);color:rgba(255,255,255,0.5);border:1px solid rgba(255,255,255,0.1);' }}">
                {{ $label }}
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
            @foreach ($products as $product)
                @php
                    $disc = $product['discount_percent'] ?? 0;
                    $origPrice = $product['original_price'] ?? 0;
                    $price = $product['price'] ?? 0;
                    if (! $disc && $origPrice && $origPrice > $price) {
                        $disc = round((1 - $price / $origPrice) * 100);
                    }
                    $cond = $product['condition'] ?? '';
                    $condStyle = match(true) {
                        str_contains($cond, 'mulus') => 'background:rgba(16,185,129,0.2);color:#34d399;',
                        str_contains($cond, 'baik')  => 'background:rgba(59,130,246,0.2);color:#60a5fa;',
                        default                       => 'background:rgba(245,158,11,0.2);color:#fbbf24;',
                    };
                    $images = !empty($product['images']) ? $product['images'] : (!empty($product['image']) ? [$product['image']] : []);
                @endphp
                <div class="rounded-2xl overflow-hidden transition-all hover:-translate-y-0.5"
                     style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.09);">
                    {{-- Gambar --}}
                    <div class="aspect-square relative rt-carousel" style="background:rgba(124,58,237,0.08);">
                        <a href="{{ route('preloved.show', $product['id']) }}" class="block h-full w-full flex items-center justify-center relative">
                            @forelse ($images as $i => $img)
                                <img src="{{ Storage::url($img) }}" alt="{{ $product['name'] }}" class="rt-slide {{ $i === 0 ? 'active' : '' }}">
                            @empty
                                <x-lucide-image class="w-12 h-12" style="color:#a78bfa;" />
                            @endforelse
                        </a>
                        @if ($disc)
                            <span class="absolute top-2 left-2 px-2 py-0.5 rounded-full text-[10px] font-bold flex items-center gap-1"
                                  style="background:#ef4444;color:white;">
                                <x-lucide-tag class="w-2.5 h-2.5" /> -{{ $disc }}%
                            </span>
                        @endif
                        <button type="button" class="absolute top-2 right-2 w-7 h-7 rounded-full flex items-center justify-center transition-all" style="background:rgba(0,0,0,0.4);color:rgba(255,255,255,0.6);" aria-label="Wishlist">
                            <x-lucide-heart class="w-3.5 h-3.5" />
                        </button>
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

                    <div class="p-3">
                        <span class="inline-flex items-center px-1.5 py-0.5 rounded-md text-[9px] font-bold mb-1.5" style="{{ $condStyle }}">
                            {{ $product['condition_label'] ?? $cond }}
                        </span>
                        <a href="{{ route('preloved.show', $product['id']) }}" class="block text-xs font-bold text-white leading-snug mb-0.5 hover:text-violet-300 transition-colors">{{ $product['name'] }}</a>
                        <p class="text-[10px] mb-1.5" style="color:rgba(255,255,255,0.35);">Stok: {{ $product['stock'] }}</p>
                        <div class="flex items-center gap-1 mb-3">
                            @if ($origPrice && $origPrice > $price)
                                <span class="text-[10px] line-through" style="color:rgba(255,255,255,0.3);">{{ rupiah($origPrice) }}</span>
                            @endif
                            <span class="text-sm font-bold" style="color:#a78bfa;">{{ rupiah($price) }}</span>
                        </div>
                        <a href="{{ route('preloved.show', $product['id']) }}"
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
