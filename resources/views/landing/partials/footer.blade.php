<footer>
    <div class="wrap f-cta">
        <div>
            <h2>Liburan makin dekat.<span>Rak gudang nggak nambah.</span></h2>
            <p>Amankan slotmu sekarang. Bayar setengah dulu, sisanya nanti waktu kamu ambil.</p>
        </div>
        <div class="f-cta-act">
            <a class="btn btn-sun" href="{{ route('login') }}">Titip barang sekarang</a>
            <a class="btn btn-line" href="https://wa.me/6285121091134" target="_blank" rel="noopener">Tanya lewat WhatsApp</a>
        </div>
    </div>
    <div class="road" aria-hidden="true">
        <svg viewBox="0 0 1440 110" preserveAspectRatio="xMidYMax slice">
            <rect x="0" y="86" width="1440" height="24" fill="#0A1F1E"/>
            <path d="M0 98 H1440" stroke="#FFC93C" stroke-width="3" stroke-dasharray="30 30" opacity=".6"/>
            <g class="ride"><use href="#rider" transform="translate(0 4) scale(.62)"/></g>
        </svg>
    </div>
    <div class="wrap">
        <div class="f-main">
            <div class="f-brand">
                <a class="logo" href="#top" style="color:#fff"><img class="logo-img" src="{{ asset('assets/logo/logo-horizontal-light.png') }}" alt="RuangTitip" width="104" height="42"></a>
                <p>Titip barangmu, simpan uangmu. Penitipan barang untuk mahasiswa di Malang sejak 2026.</p>
            </div>
            <div class="f-col">
                <h3>Layanan</h3>
                <ul>
                    <li><a href="#cara-kerja">Penitipan barang</a></li>
                    <li><a href="#cara-kerja">Kirim Barang Saya</a></li>
                    <li><a href="#preloved">Jual barang</a></li>
                    <li><a href="#preloved">Beli preloved</a></li>
                </ul>
            </div>
            <div class="f-col">
                <h3>Bantuan</h3>
                <ul>
                    <li><a href="#harga">Harga</a></li>
                    <li><a href="#faq">FAQ</a></li>
                    <li><a href="{{ route('login') }}">Cek status barang</a></li>
                    <li><a href="{{ route('legal.terms') }}">Syarat &amp; Ketentuan</a></li>
                </ul>
            </div>
            <div class="f-col">
                <h3>Hubungi kami</h3>
                <div class="contact">
                    <a href="https://wa.me/6285121091134" target="_blank" rel="noopener"><svg aria-hidden="true"><use href="#st-chat"/></svg><span><small>WhatsApp</small>+62 851-2109-1134</span></a>
                    <a href="mailto:ruangtitipmu@gmail.com"><svg aria-hidden="true"><use href="#st-mail"/></svg><span><small>Email</small>ruangtitipmu@gmail.com</span></a>
                    <div><svg aria-hidden="true"><use href="#st-pin"/></svg><span><small>Lokasi</small>Malang, Jawa Timur</span></div>
                </div>
            </div>
        </div>
        <div class="f-bottom">
            <span>&copy; 2026 RuangTitip</span>
            <nav aria-label="Legal"><a href="{{ route('legal.terms') }}">Syarat &amp; Ketentuan</a><a href="{{ route('legal.privacy') }}">Kebijakan Privasi</a></nav>
        </div>
    </div>
</footer>
