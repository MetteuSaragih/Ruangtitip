@extends('layouts.dashboard')

@section('title', 'Toko Preloved')

@section('content')
<style>
    .preloved-wrap { color: #fff; }

    .filter-pill {
        display: inline-flex;
        align-items: center;
        padding: 6px 16px;
        border-radius: 999px;
        font-size: 13px;
        font-weight: 500;
        cursor: pointer;
        border: 1.5px solid rgba(255,255,255,0.15);
        background: transparent;
        color: rgba(255,255,255,0.55);
        transition: all .2s;
        text-decoration: none;
    }
    .filter-pill.active, .filter-pill:hover {
        background: #7c3aed;
        border-color: #7c3aed;
        color: #fff;
    }

    .product-card {
        background: rgba(255,255,255,0.04);
        border: 1px solid rgba(255,255,255,0.08);
        border-radius: 16px;
        overflow: hidden;
        transition: transform .2s, box-shadow .2s;
        position: relative;
    }
    .product-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 40px rgba(124,58,237,0.18);
    }
    .product-img-wrap {
        width: 100%;
        aspect-ratio: 1/1;
        background: rgba(255,255,255,0.05);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 64px;
        position: relative;
        overflow: hidden;
    }
    .product-img-wrap img {
        width: 100%; height: 100%; object-fit: cover;
    }
    .badge-discount {
        position: absolute;
        top: 10px; left: 10px;
        background: #ef4444;
        color: #fff;
        font-size: 11px;
        font-weight: 700;
        padding: 3px 8px;
        border-radius: 6px;
        z-index: 2;
    }
    .badge-wishlist {
        position: absolute;
        top: 10px; right: 10px;
        width: 32px; height: 32px;
        border-radius: 50%;
        background: rgba(0,0,0,0.35);
        display: flex; align-items: center; justify-content: center;
        cursor: pointer;
        border: none;
        color: rgba(255,255,255,0.6);
        transition: all .2s;
        z-index: 2;
    }
    .badge-wishlist:hover { background: rgba(239,68,68,0.3); color: #ef4444; }

    .badge-condition {
        display: inline-flex;
        align-items: center;
        padding: 2px 8px;
        border-radius: 6px;
        font-size: 11px;
        font-weight: 600;
        margin-bottom: 6px;
    }
    .cond-mulus  { background: rgba(16,185,129,0.2); color: #34d399; }
    .cond-baik   { background: rgba(59,130,246,0.2); color: #60a5fa; }
    .cond-pernah { background: rgba(245,158,11,0.2); color: #fbbf24; }

    .price-original {
        font-size: 12px;
        color: rgba(255,255,255,0.3);
        text-decoration: line-through;
        margin-bottom: 0;
        line-height: 1.4;
    }
    .price-current {
        font-size: 17px;
        font-weight: 700;
        color: #a78bfa;
        margin-bottom: 10px;
        line-height: 1.3;
    }
    .btn-beli {
        width: 100%;
        padding: 9px 0;
        border-radius: 10px;
        background: linear-gradient(135deg, #7c3aed, #6366f1);
        color: #fff !important;
        font-size: 13px;
        font-weight: 600;
        border: none;
        cursor: pointer;
        text-align: center;
        display: block;
        text-decoration: none;
        transition: opacity .2s;
    }
    .btn-beli:hover { opacity: .85; }

    .banner-jual {
        background: linear-gradient(135deg, rgba(109,40,217,0.35) 0%, rgba(99,102,241,0.2) 100%);
        border: 1px solid rgba(139,92,246,0.3);
        border-radius: 16px;
        padding: 18px 24px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        margin-bottom: 24px;
    }
    .banner-left { display: flex; align-items: center; gap: 14px; }
    .banner-icon {
        width: 40px; height: 40px;
        background: rgba(139,92,246,0.25);
        border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        font-size: 18px; flex-shrink: 0;
    }
    .banner-eyebrow {
        font-size: 10px; font-weight: 700; letter-spacing: .08em;
        color: #a78bfa; text-transform: uppercase; margin-bottom: 3px;
    }
    .banner-desc { font-size: 14px; color: rgba(255,255,255,0.85); }
    .banner-desc a { color: #a78bfa; font-weight: 600; text-decoration: none; }
    .btn-pelajari {
        display: inline-flex; align-items: center; gap: 6px;
        padding: 9px 20px;
        background: #7c3aed;
        color: #fff !important; font-size: 13px; font-weight: 600;
        border-radius: 10px; text-decoration: none;
        white-space: nowrap; flex-shrink: 0;
        transition: opacity .2s;
    }
    .btn-pelajari:hover { opacity: .85; }

</style>

<div class="preloved-wrap">

    {{-- Page Header --}}
    <div class="mb-5">
        <h1 class="text-2xl font-extrabold font-display text-white mb-1">Toko Preloved</h1>
        <p style="color:rgba(255,255,255,0.4);font-size:14px;">Barang bekas berkualitas dari sesama mahasiswa</p>
    </div>

    {{-- Banner --}}
    <div class="banner-jual">
        <div class="banner-left">
            <div class="banner-icon">✨</div>
            <div>
                <div class="banner-eyebrow">Jual Barang Bekasmu</div>
                <div class="banner-desc">
                    Barang kos menumpuk atau mau lulus? <a href="#">Jadi cuan di RuTip!</a>
                </div>
            </div>
        </div>
        <a href="#" class="btn-pelajari">
            Pelajari
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
        </a>
    </div>

    {{-- Filter Pills --}}
    <div class="flex items-center gap-2 flex-wrap mb-6">
        @php
            $filterOptions = [
                'semua'           => 'Semua',
                '95_mulus'        => '95%+ Mulus',
                '85_baik'         => '85%+ Baik',
                '75_pernah_pakai' => 'Pernah Pakai',
            ];
        @endphp
        @foreach ($filterOptions as $key => $label)
            <a href="{{ route('preloved.index', ['kondisi' => $key]) }}"
               class="filter-pill {{ ($condition ?? 'semua') === $key ? 'active' : '' }}">
                {{ $label }}
            </a>
        @endforeach
    </div>

    {{-- Product Grid --}}
    @if ($products->count())
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            @foreach ($products as $product)
                @php
                    $disc      = $product['discount_percent'] ?? 0;
                    $origPrice = $product['original_price'] ?? 0;
                    $price     = $product['price'] ?? 0;
                    $cond      = $product['condition'] ?? '';

                    // hitung diskon dari harga kalau discount_percent = 0
                    if (!$disc && $origPrice && $origPrice > $price) {
                        $disc = round((1 - $price / $origPrice) * 100);
                    }

                    $condClass = match(true) {
                        str_contains($cond, 'mulus') => 'cond-mulus',
                        str_contains($cond, 'baik')  => 'cond-baik',
                        default                       => 'cond-pernah',
                    };
                @endphp

                @php
                    $images = !empty($product['images']) ? $product['images'] : (!empty($product['image']) ? [$product['image']] : []);
                @endphp
                <div class="product-card">
                    <div class="product-img-wrap rt-carousel">
                        @if ($disc)
                            <div class="badge-discount">🏷 -{{ $disc }}%</div>
                        @endif
                        <button class="badge-wishlist">
                            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/></svg>
                        </button>
                        @forelse ($images as $i => $img)
                            <img src="{{ Storage::url($img) }}" alt="{{ $product['name'] }}" class="rt-slide {{ $i === 0 ? 'active' : '' }}" />
                        @empty
                            <x-lucide-image class="w-10 h-10" style="color:#a78bfa;" />
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

                    <div style="padding:12px;">
                        <div class="badge-condition {{ $condClass }}">
                            {{ $product['condition_label'] ?? $product['condition'] }}
                        </div>
                        <p class="text-sm font-semibold text-white mb-0.5 leading-snug">{{ $product['name'] }}</p>
                        <p style="font-size:12px;color:rgba(255,255,255,0.35);margin-bottom:4px;">Stok: {{ $product['stock'] }}</p>

                        @if ($origPrice && $origPrice > $price)
                            <p class="price-original">Rp {{ number_format($origPrice, 0, ',', '.') }}</p>
                        @endif
                        <p class="price-current">Rp {{ number_format($price, 0, ',', '.') }}</p>

                        <a href="{{ route('preloved.show', $product['id']) }}" class="btn-beli">Beli</a>
                    </div>
                </div>
            @endforeach
        </div>

    @else
        <div class="text-center py-20" style="color:rgba(255,255,255,0.3);">
            <div style="font-size:48px;margin-bottom:12px;">📦</div>
            <p class="font-semibold text-white">Belum ada produk</p>
            <p style="font-size:13px;margin-top:4px;">Coba pilih kategori lain</p>
        </div>
    @endif
</div>
@endsection
