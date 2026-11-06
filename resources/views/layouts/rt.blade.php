{{--
    Shell area pengguna RuangTitip: sidebar, topbar, dan tab bar mobile.
    View mengisi @section('content'), judul halaman lewat @section('title'),
    serta boleh memakai @push('styles') dan @push('scripts').
--}}
@php
    $rtUser = auth()->user();
    $rtNotifs = \App\Support\UserNotifications::for($rtUser);
    $rtUnread = \App\Support\UserNotifications::unreadCount($rtNotifs);
    $rtCart = count(session('cart', []));
    $rtActive = fn (...$patterns) => request()->routeIs(...$patterns) ? 'page' : null;
    $rtActionCount = \App\Support\UserNotifications::actionCount($rtUser);
    $rtMenu = [
        'Menu' => [
            ['Beranda', 'dashboard', 'n-home', $rtActive('dashboard'), null],
            ['Ruang Titip', 'ruang-titip.index', 'n-ware', $rtActive('ruang-titip.*'), null],
            ['Pesanan Saya', 'pesanan.index', 'n-receipt', $rtActive('pesanan.*'), $rtActionCount ?: null],
            ['Notifikasi', 'notifikasi.index', 'n-bell', $rtActive('notifikasi.*'), $rtUnread ?: null],
        ],
        'Toko Preloved' => [
            ['Katalog', 'preloved.index', 'n-tag', $rtActive('preloved.index', 'preloved.show'), null],
            ['Keranjang', 'preloved.cart.index', 'n-cart', $rtActive('preloved.cart.*', 'checkout.*'), $rtCart ?: null],
            ['Pembelian Saya', 'purchases.index', 'n-bag', $rtActive('purchases.*'), null],
            ['Jual Barang', 'preloved.cara-jual', 'n-coin', $rtActive('preloved.cara-jual', 'sell.*'), null],
        ],
    ];
    $rtOnProfile = request()->routeIs('profile.*', 'akun.*');
    $rtHelpTab = request()->routeIs('akun.bantuan');
    $rtProfileActive = $rtOnProfile && ! $rtHelpTab ? 'page' : null;
    $rtHelpActive = $rtHelpTab ? 'page' : null;
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="color-scheme" content="light">
    <title>@yield('title', 'RuangTitip') &middot; RuangTitip</title>
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('assets/logo/favicon-32.png') }}">
    <link rel="icon" type="image/png" sizes="48x48" href="{{ asset('assets/logo/favicon-48.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('assets/logo/apple-touch-icon.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap">
    <script>try{if(localStorage.getItem('rt-side')==='1')document.documentElement.classList.add('side-collapsed')}catch(e){}</script>
    @vite(['resources/css/rt.css', 'resources/js/rt-shell.js'])
    @stack('styles')
</head>
<body>
@include('partials.rt-icons')
@include('landing.partials.symbols')

<div class="rt-app">
    {{-- ===== Sidebar ===== --}}
    <aside class="rt-side" id="rtSide" aria-label="Navigasi utama">
        <div class="rt-side-head">
            <a class="rt-logo" href="{{ route('dashboard') }}" data-tip="Beranda">
                <img class="rt-logo-full" src="{{ asset('assets/logo/logo-horizontal.png') }}" alt="RuTip" width="119" height="46">
                <img class="rt-logo-mini" src="{{ asset('assets/logo/logo-mark.png') }}" alt="RuTip" width="40" height="37">
            </a>
            <button class="rt-side-toggle" id="rtSideToggle" type="button" aria-expanded="true" aria-label="Ciutkan menu" data-tip="Ciutkan menu" data-kbd="[" data-tip-always>
                <svg class="ic i-close" width="20" height="20" aria-hidden="true"><use href="#n-side-close"/></svg>
                <svg class="ic i-open" width="20" height="20" aria-hidden="true"><use href="#n-side-open"/></svg>
            </button>
            <button class="rt-drawer-close" id="rtDrawerClose" type="button" aria-label="Tutup menu">
                <svg class="ic" width="22" height="22" aria-hidden="true"><use href="#n-x"/></svg>
            </button>
        </div>

        @foreach ($rtMenu as $group => $links)
            <div class="rt-nav-group">
                <p class="rt-nav-label">{{ $group }}</p>
                <ul class="rt-nav">
                    @foreach ($links as [$label, $route, $icon, $current, $count])
                        <li>
                            <a href="{{ route($route) }}" data-tip="{{ $label }}" @if ($current) aria-current="page" @endif>
                                <svg class="ic" aria-hidden="true"><use href="#{{ $icon }}"/></svg>
                                <span class="lbl">{{ $label }}</span>
                                @if ($count)<span class="rt-cnt">{{ $count }}</span>@endif
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endforeach

        <div class="rt-nav-group">
            <p class="rt-nav-label">Akun</p>
            <ul class="rt-nav">
                <li>
                    <a href="{{ route('profile.index') }}" data-tip="Akun saya" @if ($rtProfileActive) aria-current="page" @endif>
                        <svg class="ic" aria-hidden="true"><use href="#n-user"/></svg><span class="lbl">Akun saya</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('akun.bantuan') }}" data-tip="Bantuan" @if ($rtHelpActive) aria-current="page" @endif>
                        <svg class="ic" aria-hidden="true"><use href="#n-chat"/></svg><span class="lbl">Bantuan</span>
                    </a>
                </li>
                <li>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" data-tip="Keluar">
                            <svg class="ic" aria-hidden="true"><use href="#n-logout"/></svg><span class="lbl">Keluar</span>
                        </button>
                    </form>
                </li>
            </ul>
        </div>

        <div class="rt-side-foot">
            <div class="rt-help-card">
                <b>Ada kendala? Ruru siap bantu.</b>
                <p>Balasan lewat WhatsApp di jam kerja.</p>
                <a href="https://wa.me/6285121091134" target="_blank" rel="noopener">Chat sekarang</a>
            </div>
            <a class="rt-help-mini" href="https://wa.me/6285121091134" target="_blank" rel="noopener" aria-label="Chat Ruru lewat WhatsApp" data-tip="Chat Ruru lewat WhatsApp">
                <svg class="ic" aria-hidden="true"><use href="#n-chat"/></svg>
            </a>
            @if ($rtUser)
                <a class="rt-me" href="{{ route('profile.index') }}" data-tip="{{ $rtUser->name }}">
                    <span class="av">
                        @if ($rtUser->avatar)<img src="{{ $rtUser->avatar_url }}" alt="">@else{{ $rtUser->initials }}@endif
                    </span>
                    <span class="who lbl"><b>{{ $rtUser->name }}</b><span>{{ $rtUser->email }}</span></span>
                </a>
            @endif
        </div>
    </aside>

    <div class="rt-scrim" id="rtScrim" hidden></div>

    {{-- ===== Konten ===== --}}
    <div class="rt-main">
        <header class="rt-top">
            <div class="rt-top-in">
                <button class="rt-icon-btn rt-burger" id="rtBurger" type="button" aria-label="Buka menu" aria-controls="rtSide" aria-expanded="false">
                    <svg class="ic" aria-hidden="true"><use href="#n-menu"/></svg>
                </button>
                <a class="rt-logo" href="{{ route('dashboard') }}" aria-label="RuTip, ke beranda"><img class="rt-logo-full" src="{{ asset('assets/logo/logo-horizontal.png') }}" alt="RuTip" width="104" height="40"></a>
                <div class="rt-crumb">
                    <small>{{ now()->locale('id')->translatedFormat('l, j F Y') }}</small>
                    <h1>@yield('title', 'RuangTitip')</h1>
                </div>
                <div class="rt-top-acts">
                    <a class="rt-icon-btn" href="{{ route('notifikasi.index') }}" aria-label="Notifikasi{{ $rtUnread ? ', ' . $rtUnread . ' baru' : '' }}">
                        <svg class="ic" aria-hidden="true"><use href="#n-bell"/></svg>
                        @if ($rtUnread)<span class="dot"></span>@endif
                    </a>
                    @if ($rtUser)
                        <a class="av" href="{{ route('profile.index') }}" aria-label="Akun saya">
                            @if ($rtUser->avatar)<img src="{{ $rtUser->avatar_url }}" alt="">@else{{ $rtUser->initials }}@endif
                        </a>
                    @endif
                </div>
            </div>
        </header>

        <main class="rt-content" id="rtView" tabindex="-1">
            @yield('content')
        </main>
    </div>
</div>

<div class="rt-tip" id="rtTip" role="tooltip" aria-hidden="true"></div>
{{-- ===== Konfirmasi login berhasil (dari flash OTP atau Google) ===== --}}
@if (session('welcome_name'))
    <dialog class="rt-dlg" id="rtWelcome" aria-labelledby="rtWelcomeT" data-autoshow>
        <div class="rt-dlg-ic" aria-hidden="true"><svg class="ic"><use href="#i-check"/></svg></div>
        <h3 id="rtWelcomeT">Berhasil masuk</h3>
        <p>{{ session('welcome_type') === 'new' ? 'Selamat datang' : 'Selamat datang kembali' }}, {{ session('welcome_name') }}! Akunmu sudah aktif di perangkat ini.</p>
        <form method="dialog" class="rt-dlg-acts"><button class="btn btn-primary" value="ok" autofocus>Lanjut ke beranda</button></form>
    </dialog>
@endif

{{-- ===== Konfirmasi keluar ===== --}}
<dialog class="rt-dlg" id="rtLogout" aria-labelledby="rtLogoutT">
    <div class="rt-dlg-ic out" aria-hidden="true"><svg class="ic"><use href="#i-out"/></svg></div>
    <h3 id="rtLogoutT">Keluar dari akun?</h3>
    <p>Kamu perlu masuk lagi dengan kode OTP atau akun Google untuk membuka titipan dan pesananmu.</p>
    <div class="rt-dlg-acts">
        <button class="btn btn-ghost" type="button" id="rtLogoutNo" autofocus>Batal</button>
        <button class="btn btn-primary" type="button" id="rtLogoutYes">Ya, keluar</button>
    </div>
</dialog>

<div class="rt-toast" id="rtToast" role="status" aria-live="polite" @if (session('success') || session('status') || session('error')) data-msg="{{ session('success') ?? session('status') ?? session('error') }}" @endif></div>

@stack('scripts')
</body>
</html>
