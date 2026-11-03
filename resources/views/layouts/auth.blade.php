<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="color-scheme" content="dark">
    <title>@yield('title', 'Masuk') &middot; RuangTitip</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('assets/favicon.svg') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wdth,wght@12..96,75..100,400..800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap">
    @vite(['resources/css/rt-auth.css'])
    @stack('styles')
</head>
<body class="au">
@include('partials.rt-icons')
@include('landing.partials.symbols')

<div class="shell">
    {{-- ===== Panel kiri ===== --}}
    <aside class="side">
        <a class="logo" href="{{ route('home') }}" aria-label="RuangTitip, ke beranda"><svg aria-hidden="true"><use href="#logo"/></svg><span>ruang<em>titip</em></span></a>

        <div class="side-copy">
            <span class="kicker"><b>Malang</b>Penitipan barang khusus mahasiswa</span>
            <h1>Satu akun untuk<span>semua titipanmu.</span></h1>
            <p class="lede">Pesan ruang, bayar tagihan, pantau barang di rak, sampai jual barang preloved. Semuanya dari sini.</p>
            <ul class="perks">
                @foreach (['Disegel, difoto, dan dikode saat masuk gudang', 'Status dan sisa tagihan bisa dicek kapan saja', 'Tanpa kata sandi, cukup kode dari email'] as $perk)
                    <li><span><svg aria-hidden="true"><use href="#i-check"/></svg></span>{{ $perk }}</li>
                @endforeach
            </ul>
        </div>

        @include('landing.partials.scene', ['say' => trim($__env->yieldContent('bubble-title', 'Aku jagain!'))])
    </aside>

    {{-- ===== Panel kanan ===== --}}
    <main class="main">
        <div class="top">
            <a class="back" href="{{ route('home') }}">
                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M20 12H5M11 5.5L4.5 12l6.5 6.5" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"/></svg>
                Ke beranda
            </a>
        </div>

        <div class="stage">
            <div class="card">
                @yield('content')
            </div>
        </div>

        <footer class="foot">
            <span>Butuh bantuan? <a href="https://wa.me/6285121091134" target="_blank" rel="noopener">Chat tim kami</a></span>
            <span>&copy; 2026 RuangTitip</span>
        </footer>
    </main>
</div>

@stack('scripts')
</body>
</html>
