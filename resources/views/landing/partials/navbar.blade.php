<header class="nav">
  <div class="wrap nav-in">
    <a class="brand" href="{{ url('/') }}" aria-label="RuangTitip, ke beranda">
      <img src="{{ asset('assets/logo-ruangtitip.svg') }}" alt="RuangTitip" width="220" height="56">
    </a>
    <nav aria-label="Menu utama">
      <ul class="nav-links">
        <li><a href="#cara">Cara kerja</a></li>
        <li><a href="#layanan">Layanan</a></li>
        <li><a href="#testimoni">Cerita penitip</a></li>
        <li><a href="#faq">FAQ</a></li>
      </ul>
    </nav>
    <div class="nav-cta">
      <a class="btn btn-dark" href="{{ route('login') }}">Masuk</a>
      <button class="menu-btn" type="button" aria-label="Buka menu" aria-expanded="false" aria-controls="mobile-menu">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><path d="M4 7h16M4 12h16M4 17h16"/></svg>
      </button>
    </div>
  </div>
  <div class="wrap mobile-menu" id="mobile-menu">
    <ul>
      <li><a href="#cara">Cara kerja</a></li>
      <li><a href="#layanan">Layanan</a></li>
      <li><a href="#testimoni">Cerita penitip</a></li>
      <li><a href="#faq">FAQ</a></li>
      <li><a href="{{ route('login') }}">Masuk</a></li>
    </ul>
  </div>
</header>
