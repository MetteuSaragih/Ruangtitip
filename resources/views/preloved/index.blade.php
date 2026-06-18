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

    /* Footer */
    .preloved-footer {
        margin-top: 64px;
        border-top: 1px solid rgba(255,255,255,0.08);
        padding-top: 48px;
        padding-bottom: 32px;
    }
    .footer-brand-desc {
        font-size: 13px; color: rgba(255,255,255,0.4);
        line-height: 1.7; margin-top: 10px; max-width: 220px;
    }
    .footer-social { display: flex; gap: 10px; margin-top: 16px; }
    .footer-social a {
        width: 32px; height: 32px; border-radius: 8px;
        background: rgba(255,255,255,0.07);
        display: flex; align-items: center; justify-content: center;
        color: rgba(255,255,255,0.45);
        text-decoration: none; transition: background .2s;
    }
    .footer-social a:hover { background: rgba(139,92,246,0.3); color: #a78bfa; }
    .footer-col h4 {
        font-size: 11px; font-weight: 700; letter-spacing: .08em;
        text-transform: uppercase; color: rgba(255,255,255,0.5); margin-bottom: 14px;
    }
    .footer-col a {
        display: block; font-size: 13px;
        color: rgba(255,255,255,0.45); text-decoration: none;
        margin-bottom: 9px; transition: color .2s;
    }
    .footer-col a:hover { color: #a78bfa; }
    .footer-contact-item {
        display: flex; align-items: center; gap: 8px;
        font-size: 13px; color: rgba(255,255,255,0.45); margin-bottom: 9px;
    }
    .btn-wa {
        display: inline-flex; align-items: center; gap: 8px;
        padding: 9px 18px; border-radius: 10px;
        background: #25d366; color: #fff !important;
        font-size: 13px; font-weight: 600;
        text-decoration: none; margin-top: 6px; transition: opacity .2s;
    }
    .btn-wa:hover { opacity: .85; }
    .footer-bottom {
        border-top: 1px solid rgba(255,255,255,0.06);
        margin-top: 40px; padding-top: 20px;
        display: flex; align-items: center; justify-content: space-between;
        flex-wrap: wrap; gap: 10px;
    }
    .footer-bottom span { font-size: 12px; color: rgba(255,255,255,0.25); }
    .footer-bottom a { font-size: 12px; color: rgba(255,255,255,0.35); text-decoration: none; }
    .footer-bottom a:hover { color: #a78bfa; }
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

                <div class="product-card">
                    <div class="product-img-wrap">
                        @if ($disc)
                            <div class="badge-discount">🏷 -{{ $disc }}%</div>
                        @endif
                        <button class="badge-wishlist">
                            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/></svg>
                        </button>
                        @if (!empty($product['image']))
                            <img src="{{ Storage::url($product['image']) }}" alt="{{ $product['name'] }}" />
                        @else
                            <x-lucide-image class="w-10 h-10" style="color:#a78bfa;" />
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

    {{-- FOOTER --}}
    <div class="preloved-footer">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-10">

            {{-- Brand --}}
            <div>
                <a href="{{ route('dashboard') }}" class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center" style="background:linear-gradient(135deg,#7c3aed,#6366f1);">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="m7.5 4.27 9 5.15"/><path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"/><path d="m3.3 7 8.7 5 8.7-5"/><path d="M12 22V12"/></svg>
                    </div>
                    <span class="text-xl font-extrabold text-white font-display">RUTIP</span>
                </a>
                <p class="footer-brand-desc">Solusi titip barang terpercaya untuk mahasiswa Universitas Brawijaya. Aman, hemat, dan mudah.</p>
                <div class="footer-social">
                    <a href="#">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                    </a>
                    <a href="#">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-4.714-6.231-5.401 6.231H2.744l7.73-8.835L1.254 2.25H8.08l4.261 5.636zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                    </a>
                    <a href="#">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 24 24"><path d="M19.59 6.69a4.83 4.83 0 01-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 01-2.88 2.5 2.89 2.89 0 01-2.89-2.89 2.89 2.89 0 012.89-2.89c.28 0 .54.04.79.1V9.01a6.33 6.33 0 00-.79-.05 6.34 6.34 0 00-6.34 6.34 6.34 6.34 0 006.34 6.34 6.34 6.34 0 006.33-6.34V8.69a8.18 8.18 0 004.79 1.53V6.77a4.85 4.85 0 01-1.02-.08z"/></svg>
                    </a>
                </div>
            </div>

            {{-- Layanan --}}
            <div class="footer-col">
                <h4>Layanan</h4>
                <a href="#">Titip Barang</a>
                <a href="#">Jemput &amp; Antar</a>
                <a href="#">Toko Packing</a>
                <a href="{{ route('preloved.index') }}">Toko Preloved</a>
                <a href="#">Perpanjang Titipan</a>
            </div>

            {{-- Akun --}}
            <div class="footer-col">
                <h4>Akun</h4>
                <a href="#">Profil Saya</a>
                <a href="#">Pesanan Saya</a>
                <a href="#">Pengaturan</a>
                <a href="#">Riwayat Transaksi</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" style="background:none;border:none;padding:0;font-size:13px;color:rgba(255,255,255,0.45);cursor:pointer;margin-bottom:9px;display:block;">
                        Keluar
                    </button>
                </form>
            </div>

            {{-- Kontak --}}
            <div class="footer-col">
                <h4>Kontak</h4>
                <div class="footer-contact-item">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>
                    rutip@ub.ac.id
                </div>
                <div class="footer-contact-item">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12a19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 3.77 1h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 8.91a16 16 0 0 0 6 6l.91-.91a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                    +62 812-3456-7890
                </div>
                <div class="footer-contact-item">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                    Malang, Jawa Timur
                </div>
                <a href="https://wa.me/6281234567890" target="_blank" class="btn-wa">
                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M7.9 20A9 9 0 1 0 4 16.1L2 22Z"/></svg>
                    Chat via WhatsApp
                </a>
            </div>

        </div>

        <div class="footer-bottom">
            <span>© 2026 RUTIP · Malang, Indonesia · All rights reserved.</span>
            <div style="display:flex;gap:20px;">
                <a href="#">Syarat &amp; Ketentuan</a>
                <a href="#">Kebijakan Privasi</a>
            </div>
        </div>
    </div>

</div>
@endsection
