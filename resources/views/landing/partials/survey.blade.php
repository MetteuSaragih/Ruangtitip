<section class="section" id="survei" style="padding-top:0">
  <div class="wrap">
    <div class="survey-card">
      <div class="survey-intro">
        <span class="eyebrow">Ruru mau tanya · 30 detik</span>
        <h2 class="h2-sm">Kamu bakal pakai RuangTitip nggak?</h2>
        <p class="lead" style="font-size:17px">Jawab jujur saja. Kami lagi menentukan layanan dan harga yang paling pas buat mahasiswa, dan jawabanmu ikut menentukan.</p>
        <small>Tanpa login, tanpa nomor HP.</small>
      </div>

      <div>
        <form id="survey-form" action="{{ route('survey.store') }}" method="POST" novalidate>
          @csrf
          <fieldset>
            <legend>1. Libur semester nanti, kamu tertarik pakai layanan titip barang?</legend>
            <div class="opt-list" data-group="minat" data-single>
              <button type="button" class="opt" data-value="tertarik" aria-pressed="false">Tertarik, pasti pakai <small>Ya</small></button>
              <button type="button" class="opt" data-value="mungkin" aria-pressed="false">Mungkin, tergantung harga <small>Mungkin</small></button>
              <button type="button" class="opt" data-value="tidak" aria-pressed="false">Belum butuh sekarang <small>Tidak</small></button>
            </div>
          </fieldset>

          <div class="step2" id="step2">
            <fieldset>
              <legend>2. Layanan apa yang paling kamu butuhkan? <span>(boleh lebih dari satu)</span></legend>
              <div class="chips" data-group="layanan">
                <button type="button" class="chip" data-value="titip-libur" aria-pressed="false">Titip saat libur</button>
                <button type="button" class="chip" data-value="antar-jemput" aria-pressed="false">Antar-jemput</button>
                <button type="button" class="chip" data-value="packing" aria-pressed="false">Bantu packing</button>
                <button type="button" class="chip" data-value="pindah-kos" aria-pressed="false">Titip saat pindah kos</button>
              </div>
            </fieldset>
            <fieldset>
              <legend>3. Harga per bulan yang masuk akal buatmu?</legend>
              <div class="chips" data-group="harga" data-single>
                <button type="button" class="chip" data-value="<50rb" aria-pressed="false">&lt; Rp50rb</button>
                <button type="button" class="chip" data-value="50-100rb" aria-pressed="false">Rp50&ndash;100rb</button>
                <button type="button" class="chip" data-value="100-150rb" aria-pressed="false">Rp100&ndash;150rb</button>
                <button type="button" class="chip" data-value=">150rb" aria-pressed="false">&gt; Rp150rb</button>
              </div>
            </fieldset>
          </div>

          <div class="send-row">
            <button type="submit" class="btn btn-dark btn-send" id="send-btn" disabled>Kirim jawaban</button>
            <small id="helper" aria-live="polite">Pilih satu jawaban dulu.</small>
          </div>
        </form>

        <div class="thanks" id="thanks" role="status" aria-live="polite">
          <img src="{{ asset('assets/ruru.webp') }}" alt="" width="110" height="129">
          <div>
            <h3 id="thanks-title">Makasih!</h3>
            <p id="thanks-body"></p>
            <div class="row">
              <a class="btn btn-dark" href="{{ route('login') }}">Titip Sekarang</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
