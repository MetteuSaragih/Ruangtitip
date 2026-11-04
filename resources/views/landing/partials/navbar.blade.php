<header class="nav" id="nav">
    <div class="wrap nav-in">
        <a class="logo" href="#top" aria-label="RuangTitip, ke atas"><img class="logo-img" src="{{ asset('assets/logo/logo-horizontal-light.png') }}" alt="RuangTitip" width="104" height="42"></a>
        <nav aria-label="Utama">
            <ul class="nav-links">
                <li><a href="#keunggulan">Keunggulan</a></li>
                <li><a href="#cara-kerja">Cara kerja</a></li>
                <li><a href="#harga">Harga</a></li>
                <li><a href="#preloved">Preloved</a></li>
                <li><a href="#faq">FAQ</a></li>
            </ul>
        </nav>
        <div class="nav-cta">
            @auth
                <a class="btn btn-sun" href="{{ route('dashboard') }}">Ke dashboard</a>
            @else
                <a class="login" href="{{ route('login') }}">Masuk</a>
                <a class="btn btn-sun" href="{{ route('login') }}">Titip barang</a>
            @endauth
            <button class="menu-btn" id="menuBtn" type="button" aria-expanded="false" aria-controls="mMenu" aria-label="Buka menu"><span></span><span></span><span></span></button>
        </div>
    </div>
    <div class="m-menu" id="mMenu">
        <ul class="wrap">
            <li><a href="#keunggulan">Keunggulan</a></li>
            <li><a href="#cara-kerja">Cara kerja</a></li>
            <li><a href="#harga">Harga</a></li>
            <li><a href="#preloved">Preloved</a></li>
            <li><a href="#faq">FAQ</a></li>
            <li><a href="{{ auth()->check() ? route('dashboard') : route('login') }}">{{ auth()->check() ? 'Ke dashboard' : 'Masuk' }}</a></li>
        </ul>
    </div>
</header>
