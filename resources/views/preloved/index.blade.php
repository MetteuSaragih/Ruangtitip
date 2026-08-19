@extends('layouts.dashboard')

@section('title', 'Toko Preloved')

@php
    if (! function_exists('rupiah')) {
        function rupiah($n) { return 'Rp ' . number_format($n, 0, ',', '.'); }
    }
@endphp

@section('content')
<div class="pt-6 pb-10">

    {{-- Heading --}}
    <div class="mb-5">
        <h1 class="text-xl font-extrabold text-white font-display">Toko Preloved</h1>
        <p class="text-xs mt-0.5" style="color:rgba(255,255,255,0.4);">Barang bekas berkualitas dari sesama mahasiswa</p>
    </div>

    {{-- Banner jual barang --}}
    <div class="flex items-center justify-between gap-4 rounded-2xl px-5 py-4 mb-6 flex-wrap"
         style="background:linear-gradient(135deg,rgba(109,40,217,0.35),rgba(99,102,241,0.2));border:1px solid rgba(139,92,246,0.3);">
        <div class="flex items-center gap-3.5">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0" style="background:rgba(139,92,246,0.25);">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#a78bfa" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/>
                </svg>
            </div>
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
        <div class="rounded-2xl py-16 flex flex-col items-center text-center" style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.09);">
            <div class="relative inline-flex mx-auto mb-5">
                <div class="absolute inset-0 rounded-3xl blur-xl opacity-25" style="background:linear-gradient(135deg,#059669,#34d399);"></div>
                <div class="relative w-20 h-20 rounded-3xl flex items-center justify-center" style="background:linear-gradient(135deg,rgba(5,150,105,0.2),rgba(52,211,153,0.1));border:1px solid rgba(5,150,105,0.35);">
                    <svg class="w-9 h-9" fill="none" stroke="#34d399" stroke-width="1.5" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20.59 13.41l-7.17 7.17a2 2 0 01-2.83 0L2 12V2h10l8.59 8.59a2 2 0 010 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/>
                    </svg>
                </div>
            </div>
            <p class="text-sm font-bold text-white mb-1">Belum ada produk</p>
            <p class="text-xs" style="color:rgba(255,255,255,0.4);">Produk preloved akan segera tersedia</p>
        </div>
    @else
        {{-- Skeleton loading --}}
        <div id="rt-skeleton-grid" class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
            @for ($si = 0; $si < 8; $si++)
            <div class="rounded-2xl overflow-hidden flex flex-col" style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.07);">
                <div class="rt-skeleton" style="aspect-ratio:1/1;"></div>
                <div class="p-3 flex flex-col gap-2">
                    <div class="rt-skeleton h-3 rounded w-3/4"></div>
                    <div class="rt-skeleton h-3 rounded w-1/2"></div>
                    <div class="rt-skeleton h-8 rounded-xl mt-1"></div>
                </div>
            </div>
            @endfor
        </div>

        <div id="rt-product-grid" class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4 hidden">
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
                <div class="rt-tilt rounded-2xl overflow-hidden flex flex-col"
                     style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.09);">
                    {{-- Gambar (rasio 1:1) --}}
                    <div class="relative rt-carousel" style="aspect-ratio:1/1;background:rgba(124,58,237,0.08);">
                        <a href="{{ route('preloved.show', $product['id']) }}" class="block h-full w-full flex items-center justify-center relative">
                            @forelse ($images as $i => $img)
                                <img src="{{ Storage::url($img) }}" alt="{{ $product['name'] }}"
                                     class="rt-slide {{ $i === 0 ? 'active' : '' }}"
                                     style="object-position:center center;">
                            @empty
                                <x-lucide-image class="w-12 h-12" style="color:#a78bfa;" />
                            @endforelse
                        </a>
                        @if ($disc)
                            <span class="absolute top-2 left-2 px-2 py-0.5 rounded-full text-[10px] font-bold"
                                  style="background:#ef4444;color:white;">-{{ $disc }}%</span>
                        @endif
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

                    <div class="p-3 flex flex-col flex-1">
                        <span class="inline-flex items-center px-1.5 py-0.5 rounded-md text-[9px] font-bold mb-1.5" style="{{ $condStyle }}">
                            {{ $product['condition_label'] ?? $cond }}
                        </span>
                        <a href="{{ route('preloved.show', $product['id']) }}"
                           class="block text-xs font-bold text-white leading-snug mb-1 hover:text-violet-300 transition-colors line-clamp-2">{{ $product['name'] }}</a>
                        <div class="flex items-center gap-1.5 mb-1">
                            @if ($origPrice && $origPrice > $price)
                                <span class="text-[10px] line-through" style="color:rgba(255,255,255,0.28);">{{ rupiah($origPrice) }}</span>
                            @endif
                            <span class="text-sm font-extrabold" style="color:#a78bfa;">{{ rupiah($price) }}</span>
                        </div>
                        <p class="text-[10px] mb-3" style="color:rgba(255,255,255,0.3);">Stok: {{ $product['stock'] }}</p>

                        {{-- Tombol aksi --}}
                        <div class="mt-auto flex gap-2">
                            <button type="button"
                                    data-quick-cart="{{ $product['id'] }}" data-type="preloved"
                                    onclick="rtQuickCart(this)"
                                    class="w-9 h-9 rounded-xl flex items-center justify-center shrink-0 transition-all hover:scale-105"
                                    style="background:rgba(124,58,237,0.12);border:1.5px solid rgba(124,58,237,0.35);color:#c4b5fd;"
                                    title="Tambah ke Keranjang">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><circle cx="8" cy="21" r="1"/><circle cx="19" cy="21" r="1"/><path d="M2.05 2.05h2l2.66 12.42a2 2 0 002 1.58h9.78a2 2 0 001.95-1.57L23 6H6"/></svg>
                            </button>
                            <a href="{{ route('preloved.show', $product['id']) }}"
                               class="flex-1 text-center py-2.5 rounded-xl text-[10px] font-bold text-white transition-all hover:opacity-90 hover:scale-[1.02] flex items-center justify-center"
                               style="background:linear-gradient(135deg,#7c3aed,#6366f1);box-shadow:0 3px 12px rgba(124,58,237,0.35);">
                                Beli Sekarang
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

<script>
/* Show real content after skeleton delay */
(function () {
    const sk = document.getElementById('rt-skeleton-grid');
    const pg = document.getElementById('rt-product-grid');
    if (!sk || !pg) return;
    setTimeout(function () {
        sk.style.transition = 'opacity .3s';
        sk.style.opacity = '0';
        setTimeout(function () { sk.remove(); pg.classList.remove('hidden'); }, 300);
    }, 450);
})();

const rtCartUrl = '{{ route("preloved.cart.add") }}';
const rtCsrf   = document.querySelector('meta[name="csrf-token"]')?.content ?? '';

function rtQuickCart(btn) {
    const pid  = btn.dataset.quickCart;
    const type = btn.dataset.type;
    btn.disabled = true;
    btn.innerHTML = '<svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path></svg>';

    fetch(rtCartUrl, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': rtCsrf },
        body: JSON.stringify({ type, product_id: pid, qty: 1 }),
    })
    .then(r => r.json())
    .then(data => {
        if (data.success || data.message) {
            btn.innerHTML = '<svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>';
            btn.style.background = 'rgba(52,211,153,0.15)';
            btn.style.borderColor = 'rgba(52,211,153,0.4)';
            btn.style.color = '#34d399';
            if (typeof rtToast === 'function') rtToast('success', 'Produk ditambahkan ke keranjang!');
        } else {
            if (typeof rtToast === 'function') rtToast('error', data.error || 'Gagal menambahkan ke keranjang.');
        }
        setTimeout(() => {
            btn.disabled = false;
            btn.innerHTML = '<svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><circle cx="8" cy="21" r="1"/><circle cx="19" cy="21" r="1"/><path d="M2.05 2.05h2l2.66 12.42a2 2 0 002 1.58h9.78a2 2 0 001.95-1.57L23 6H6"/></svg>';
            btn.style.background = 'rgba(124,58,237,0.12)';
            btn.style.borderColor = 'rgba(124,58,237,0.35)';
            btn.style.color = '#c4b5fd';
        }, 1800);
    })
    .catch(() => { btn.disabled = false; });
}
</script>
    @endif
</div>
@endsection
