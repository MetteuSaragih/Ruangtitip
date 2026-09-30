<footer class="footer">
  <div class="wrap">
    <div class="cta-row">
      <h2>Bingung barangmu mau dititip ke mana?</h2>
      <a class="btn btn-light" href="#survei">Mulai titip barang</a>
    </div>
    <div class="foot-grid">
      <div class="foot-brand">
        <img src="{{ asset('assets/logo-ruangtitip-putih.svg') }}" alt="RuangTitip" width="220" height="56">
        <p>Titip barangmu, simpan uangmu.</p>
      </div>
      <div>
        <h3>Layanan</h3>
        <ul>
          <li><a href="#layanan">Penitipan</a></li>
          <li><a href="#layanan">Antar-jemput</a></li>
          <li><a href="#layanan">Packing</a></li>
        </ul>
      </div>
      <div>
        <h3>Akun</h3>
        <ul>
          <li><a href="{{ route('login') }}">Masuk</a></li>
          <li><a href="#faq">Bantuan</a></li>
        </ul>
      </div>
      <div>
        <h3>Kontak</h3>
        <ul>
          <li><a href="mailto:ruangtitipmu@gmail.com">ruangtitipmu@gmail.com</a></li>
          <li><a href="https://wa.me/6285121091134" target="_blank" rel="noopener">+62 851-2109-1134</a></li>
          <li>Malang, Jawa Timur</li>
        </ul>
      </div>
    </div>
    <div class="foot-bottom">
      <span>&copy; 2026 RuangTitip</span>
      <span><a href="{{ route('legal.terms') }}">Syarat &amp; Ketentuan</a> &middot; <a href="{{ route('legal.privacy') }}">Kebijakan Privasi</a></span>
    </div>
  </div>
</footer>
