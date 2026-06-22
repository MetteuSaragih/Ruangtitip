@once
    <style>
        .rutip-footer {
            margin-top: 64px;
            border-top: 1px solid rgba(255,255,255,0.08);
            padding: 48px 0 32px;
            color: #fff;
        }
        .rutip-footer-inner {
            width: 100%;
            max-width: 80rem;
            margin: 0 auto;
            padding: 0 1rem;
        }
        @media (min-width: 1024px) {
            .rutip-footer-inner { padding: 0 1.5rem; }
        }
        .footer-brand-desc {
            font-size: 13px;
            color: rgba(255,255,255,0.4);
            line-height: 1.7;
            margin-top: 10px;
            max-width: 220px;
        }
        .footer-social {
            display: flex;
            gap: 10px;
            margin-top: 16px;
        }
        .footer-social a {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            background: rgba(255,255,255,0.07);
            display: flex;
            align-items: center;
            justify-content: center;
            color: rgba(255,255,255,0.45);
            text-decoration: none;
            transition: background .2s, color .2s;
        }
        .footer-social a:hover {
            background: rgba(139,92,246,0.3);
            color: #a78bfa;
        }
        .footer-col h4 {
            font-size: 11px;
            font-weight: 700;
            letter-spacing: .08em;
            text-transform: uppercase;
            color: rgba(255,255,255,0.5);
            margin-bottom: 14px;
        }
        .footer-col a,
        .footer-col button {
            display: block;
            font-size: 13px;
            color: rgba(255,255,255,0.45);
            text-decoration: none;
            margin-bottom: 9px;
            transition: color .2s;
        }
        .footer-col a:hover,
        .footer-col button:hover {
            color: #a78bfa;
        }
        .footer-contact-item {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
            color: rgba(255,255,255,0.45);
            margin-bottom: 9px;
        }
        .btn-wa {
            display: inline-flex !important;
            align-items: center;
            gap: 8px;
            padding: 9px 18px;
            border-radius: 10px;
            background: #25d366;
            color: #fff !important;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            margin-top: 6px;
            transition: opacity .2s;
        }
        .btn-wa:hover { opacity: .85; }
        .footer-bottom {
            border-top: 1px solid rgba(255,255,255,0.06);
            margin-top: 40px;
            padding-top: 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 10px;
        }
        .footer-bottom span {
            font-size: 12px;
            color: rgba(255,255,255,0.25);
        }
        .footer-bottom a {
            font-size: 12px;
            color: rgba(255,255,255,0.35);
            text-decoration: none;
        }
        .footer-bottom a:hover { color: #a78bfa; }
    </style>
@endonce

@php
    $homeHref = auth()->check() ? route('dashboard') : route('home');
@endphp

<footer class="rutip-footer">
    <div class="rutip-footer-inner">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-10">
            <div>
                <a href="{{ $homeHref }}" class="flex items-center">
                    <img src="{{ asset('images/logo-rutip-putih.png') }}" alt="RUTIP" class="h-12 w-auto">
                </a>
                <p class="footer-brand-desc">Solusi titip barang terpercaya untuk mahasiswa Universitas Brawijaya. Aman, hemat, dan mudah.</p>
                <div class="footer-social">
                    <a href="#" aria-label="Instagram">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                    </a>
                    <a href="#" aria-label="X">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-4.714-6.231-5.401 6.231H2.744l7.73-8.835L1.254 2.25H8.08l4.261 5.636zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                    </a>
                    <a href="#" aria-label="TikTok">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 24 24"><path d="M19.59 6.69a4.83 4.83 0 0 1-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 0 1-2.88 2.5 2.89 2.89 0 0 1-2.89-2.89 2.89 2.89 0 0 1 2.89-2.89c.28 0 .54.04.79.1V9.01a6.33 6.33 0 0 0-.79-.05 6.34 6.34 0 0 0-6.34 6.34 6.34 6.34 0 0 0 6.34 6.34 6.34 6.34 0 0 0 6.33-6.34V8.69a8.18 8.18 0 0 0 4.79 1.53V6.77a4.85 4.85 0 0 1-1.02-.08z"/></svg>
                    </a>
                </div>
            </div>

            <div class="footer-col">
                <h4>Layanan</h4>
                <a href="{{ route('ruang-titip.index') }}">Titip Barang</a>
                <a href="{{ route('ruang-titip.index') }}">Jemput &amp; Antar</a>
                <a href="{{ route('packing.index') }}">Toko Packing</a>
                <a href="{{ route('preloved.index') }}">Toko Preloved</a>
                <a href="{{ route('pesanan.index') }}">Perpanjang Titipan</a>
            </div>

            <div class="footer-col">
                <h4>Akun</h4>
                @auth
                    <a href="{{ route('profile.index') }}">Profil Saya</a>
                    <a href="{{ route('pesanan.index') }}">Pesanan Saya</a>
                    <a href="{{ route('profile.index') }}">Pengaturan</a>
                    <a href="{{ route('pesanan.index') }}">Riwayat Transaksi</a>
                    <form method="POST" action="{{ route('logout') }}" class="logout-form">
                        @csrf
                        <button type="submit" style="background:none;border:none;padding:0;cursor:pointer;text-align:left;">
                            Keluar
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}">Masuk</a>
                    <a href="{{ route('login') }}">Daftar Akun</a>
                    <a href="{{ route('home') }}#faq">Bantuan</a>
                @endauth
            </div>

            <div class="footer-col">
                <h4>Kontak</h4>
                <div class="footer-contact-item">
                    <x-lucide-mail class="w-3.5 h-3.5" />
                    rutip@ub.ac.id
                </div>
                <div class="footer-contact-item">
                    <x-lucide-phone class="w-3.5 h-3.5" />
                    +62 812-3456-7890
                </div>
                <div class="footer-contact-item">
                    <x-lucide-map-pin class="w-3.5 h-3.5" />
                    Malang, Jawa Timur
                </div>
                <a href="https://wa.me/6281234567890" target="_blank" rel="noopener noreferrer" class="btn-wa">
                    <x-lucide-message-circle class="w-4 h-4" />
                    Chat via WhatsApp
                </a>
            </div>
        </div>

        <div class="footer-bottom">
            <span>&copy; 2026 RUTIP &middot; Malang, Indonesia &middot; All rights reserved.</span>
            <div style="display:flex;gap:20px;flex-wrap:wrap;">
                <a href="#">Syarat &amp; Ketentuan</a>
                <a href="#">Kebijakan Privasi</a>
            </div>
        </div>
    </div>
</footer>
