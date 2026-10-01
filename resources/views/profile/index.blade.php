@extends('layouts.ruang-titip')
@section('title', 'Profil & Bantuan')

@push('styles')
<style>
.shell{display:grid;grid-template-columns:260px minmax(0,1fr);gap:32px;margin-top:40px;align-items:start;padding-bottom:64px}
.btn-sm{min-height:40px;padding:0 16px;font-size:14px}
.pill-ink{background:var(--ink);color:var(--cream);border:1px solid var(--ink)}

/* Menu samping */
.side{position:sticky;top:96px;background:var(--paper);border:1.5px solid var(--ink);border-radius:18px;overflow:hidden}
.side .me{display:flex;align-items:center;gap:12px;padding:18px;background:var(--sand);border-bottom:1.5px solid var(--ink)}
.side .me .av{width:48px;height:48px;border-radius:50%;background:var(--tape);color:#fff;border:1.5px solid var(--ink);display:grid;place-items:center;font-weight:800;font-family:var(--font-display);font-size:18px;overflow:hidden;flex-shrink:0}
.side .me .av img{width:100%;height:100%;object-fit:cover}
.side .me strong{display:block;line-height:1.25}
.side .me small{color:var(--depot);font-weight:700;font-size:13px}
.side nav{display:flex;flex-direction:column;padding:8px}
.side nav button,.side nav a{display:flex;align-items:center;gap:12px;min-height:48px;padding:0 14px;border-radius:12px;border:0;background:none;font-weight:600;font-size:15px;color:var(--ink);text-decoration:none;cursor:pointer;text-align:left;width:100%}
.side nav button:hover,.side nav a:hover{background:var(--cream)}
.side nav button[aria-selected="true"]{background:var(--ink);color:var(--cream)}
.side nav button[aria-selected="true"] svg{stroke:var(--cream)}
.side nav hr{border:0;border-top:1px solid var(--line);margin:6px 4px}
.side nav .out{color:var(--danger)}
.side nav .out svg{stroke:var(--danger)}

.pane[hidden]{display:none}
.pane-head h1{font-size:clamp(30px,3.4vw,40px);font-weight:800;letter-spacing:-1px}
.pane-head p{color:var(--body);margin-top:4px}
.card{background:var(--paper);border:1px solid var(--line-strong);border-radius:18px;padding:24px;margin-top:20px}
.card h2{font-size:20px;font-weight:700}
.card .sub{font-size:14px;color:var(--muted);margin-top:2px}
.card-head{display:flex;justify-content:space-between;align-items:center;gap:12px}

/* Foto */
.photo{display:flex;align-items:center;gap:20px}
.photo .big{width:96px;height:96px;border-radius:50%;background:var(--tape);color:#fff;border:2px solid var(--ink);box-shadow:4px 4px 0 var(--ink);display:grid;place-items:center;font-family:var(--font-display);font-weight:800;font-size:38px;overflow:hidden;flex-shrink:0}
.photo .big img{width:100%;height:100%;object-fit:cover}
.photo .btns{display:flex;gap:8px;flex-wrap:wrap;margin-top:8px}
.photo small{display:block;color:var(--muted);font-size:13px}

/* Input */
.field{margin-top:18px}
.input-ic{position:relative}
.input-ic svg{position:absolute;left:14px;top:50%;transform:translateY(-50%);color:var(--muted);pointer-events:none}
.input-ic input{padding-left:44px}
.field input[readonly]{background:var(--line);color:var(--muted);cursor:not-allowed}
.lock{position:absolute;right:12px;top:50%;transform:translateY(-50%);font-size:12px;font-weight:700;padding:3px 10px;border-radius:999px;background:var(--paper);border:1px solid var(--line-strong);color:var(--muted)}
.phone{display:flex}
.phone span{display:flex;align-items:center;padding:0 14px;border:1.5px solid var(--line-strong);border-right:0;border-radius:12px 0 0 12px;background:var(--sand);font-weight:700}
.phone input{border-radius:0 12px 12px 0!important}
.field .err-msg{font-size:13px;font-weight:600;color:var(--danger);display:none;margin-top:6px}
.field.invalid .err-msg{display:block}
.field.invalid input{border-color:var(--danger)}

/* Alamat */
.addr{display:flex;flex-direction:column;gap:12px;margin-top:16px}
.addr-item{display:flex;gap:14px;align-items:flex-start;padding:16px;border:1.5px solid var(--line-strong);border-radius:14px;background:var(--paper)}
.addr-item.main{border-color:var(--ink);background:var(--cream)}
.addr-item .ic{width:40px;height:40px;border-radius:12px;background:var(--sand);border:1.5px solid var(--ink);display:grid;place-items:center;flex-shrink:0}
.addr-item .t{flex-grow:1;min-width:0}
.addr-item strong{font-size:16px}
.addr-item p{color:var(--body);font-size:15px}
.addr-item .row{display:flex;gap:6px;margin-top:10px;flex-wrap:wrap}
.link-btn{min-height:36px;padding:0 12px;border-radius:999px;border:1.5px solid var(--line-strong);background:var(--paper);font-weight:600;font-size:13px;cursor:pointer}
.link-btn:hover{border-color:var(--ink)}
.link-btn.danger{color:var(--danger)}
.link-btn.danger:hover{border-color:var(--danger)}
.new-form{margin-top:16px;padding:18px;border:1.5px dashed var(--line-strong);border-radius:14px;background:var(--cream)}
.new-form[hidden]{display:none}
.new-form .acts{display:flex;gap:8px;justify-content:flex-end;margin-top:16px}
.empty-addr{padding:20px;text-align:center;color:var(--muted);border:1.5px dashed var(--line-strong);border-radius:14px}

/* Simpan */
.savebar{position:sticky;bottom:16px;z-index:20;display:flex;justify-content:space-between;align-items:center;gap:16px;margin-top:20px;padding:14px 14px 14px 20px;background:var(--ink);color:var(--cream);border-radius:999px;transition:opacity .2s,transform .2s}
.savebar.idle{opacity:0;transform:translateY(12px);pointer-events:none}
.savebar .btn{box-shadow:none;border-color:var(--tape-light);background:var(--tape-light);color:var(--ink)}
.savebar .btn:hover{background:#F0B98F;color:var(--ink)}
.savebar .btn-ghost{background:none;border:0;color:#D6D0C3;font-weight:600;cursor:pointer;min-height:44px;padding:0 12px}

/* Bantuan */
.help-search{position:relative;margin-top:20px;display:block}
.help-search svg{position:absolute;left:16px;top:50%;transform:translateY(-50%);color:var(--muted)}
.help-search input{width:100%;height:52px;border-radius:999px;border:1.5px solid var(--line-strong);background:var(--paper);padding:0 18px 0 46px;font-size:16px}
.help-search input:focus{outline:none;border-color:var(--ink)}
.faq{margin-top:12px}
.faq details{border-bottom:1px solid var(--line)}
.faq details:first-child{border-top:1.5px solid var(--ink)}
.faq summary{list-style:none;cursor:pointer;display:flex;justify-content:space-between;align-items:center;gap:16px;padding:18px 0;font-weight:700;font-size:17px}
.faq summary::-webkit-details-marker{display:none}
.faq summary .pm{width:28px;height:28px;border-radius:50%;border:1.5px solid var(--ink);display:grid;place-items:center;flex-shrink:0;font-weight:700;transition:transform .2s}
.faq details[open] summary .pm{transform:rotate(45deg);background:var(--ink);color:var(--cream)}
.faq details p{color:var(--body);padding:0 44px 18px 0}
.no-faq{padding:20px 0;color:var(--muted)}
.cs{display:flex;gap:20px;align-items:center;margin-top:24px;padding:24px;background:var(--sand);border:1.5px solid var(--ink);border-radius:18px}
.cs img{width:92px;height:auto;flex-shrink:0}
.cs .t{flex-grow:1}
.cs strong{display:block;font-family:var(--font-display);font-size:22px}
.cs p{color:var(--body);font-size:15px;margin-top:2px}
.cs .acts{display:flex;gap:8px;flex-wrap:wrap;margin-top:14px}
.btn-wa{background:var(--depot);color:#fff;border-color:var(--ink);box-shadow:3px 3px 0 var(--ink)}
.btn-wa:hover{background:#244A38;color:#fff}
.ver{margin-top:24px;text-align:center;font-size:13px;color:var(--muted)}

.p-toast{position:fixed;left:50%;bottom:24px;transform:translate(-50%,150%);z-index:60;display:flex;align-items:center;gap:10px;padding:14px 20px;background:var(--depot);color:#fff;border:1.5px solid var(--ink);border-radius:999px;font-weight:700;transition:transform .25s}
.p-toast.show{transform:translate(-50%,0)}

@media (max-width:900px){
  .shell{grid-template-columns:1fr;margin-top:24px}
  .side{position:static}
  .side nav{flex-direction:row;overflow-x:auto}
  .side nav hr{display:none}
  .side nav button,.side nav a{white-space:nowrap;width:auto}
  .cs{flex-direction:column;align-items:flex-start}
}
@media (max-width:560px){.photo{flex-direction:column;align-items:flex-start}.card{padding:18px}}
</style>
@endpush

@section('content')
<main class="wrap">
  <div class="shell">
    <aside class="side">
      <div class="me">
        <span class="av" data-avatar>
          @if ($user->avatar)
            <img src="{{ \Illuminate\Support\Facades\Storage::url($user->avatar) }}" alt="">
          @else
            {{ $user->initials }}
          @endif
        </span>
        <div><strong data-name>{{ $user->name ?: 'Tanpa nama' }}</strong><small>Penitip aktif</small></div>
      </div>
      <nav role="tablist" aria-label="Menu akun">
        <button type="button" role="tab" id="t-profil" aria-selected="{{ $currentTab !== 'bantuan' ? 'true' : 'false' }}" aria-controls="p-profil" data-pane="profil">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="4"/><path d="M4 21a8 8 0 0 1 16 0"/></svg>Profil saya</button>
        <a href="{{ route('pesanan.index') }}"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M9 4h6l1 2h3v15H5V6h3z"/><path d="M9 12h6M9 16h4"/></svg>Pesanan saya</a>
        <button type="button" role="tab" id="t-bantuan" aria-selected="{{ $currentTab === 'bantuan' ? 'true' : 'false' }}" aria-controls="p-bantuan" data-pane="bantuan">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M9.5 9a2.5 2.5 0 1 1 3.5 2.3c-.7.3-1 .9-1 1.7M12 17h.01"/></svg>Bantuan</button>
        <hr>
        <form method="POST" action="{{ route('logout') }}">
          @csrf
          <button type="submit" class="out">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M15 4h4v16h-4M10 17l5-5-5-5M15 12H3"/></svg>Keluar
          </button>
        </form>
      </nav>
    </aside>

    <div>
      <!-- ================= PROFIL ================= -->
      <section class="pane" id="p-profil" role="tabpanel" aria-labelledby="t-profil" @if($currentTab === 'bantuan') hidden @endif>
        <div class="pane-head">
          <h1>Profil saya</h1>
          <p>Data ini dipakai tim Ruru untuk menghubungimu soal pesanan.</p>
        </div>

        @if (session('error'))
          <p class="err" style="margin-top:16px">{{ session('error') }}</p>
        @endif

        <div class="card">
          <div class="photo">
            <span class="big" id="big-av" data-avatar>
              @if ($user->avatar)
                <img src="{{ \Illuminate\Support\Facades\Storage::url($user->avatar) }}" alt="">
              @else
                {{ $user->initials }}
              @endif
            </span>
            <div>
              <h2>Foto profil</h2>
              <small>JPG, JPEG, PNG, atau WEBP, maksimal 3 MB.</small>
              <div class="btns">
                <label class="btn btn-outline btn-sm" style="cursor:pointer">Ganti foto<input type="file" id="photo" accept="image/png,image/jpeg,image/webp" style="position:absolute;left:-9999px"></label>
              </div>
              <p class="err-msg" id="photo-err" style="display:block;font-size:13px;color:var(--danger);font-weight:600;margin-top:6px;min-height:1em"></p>
            </div>
          </div>
        </div>

        <div>
          <div class="card">
            <h2>Data diri</h2>
            <div class="field">
              <label for="name">Nama lengkap</label>
              <div class="input-ic">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"><circle cx="12" cy="8" r="4"/><path d="M4 21a8 8 0 0 1 16 0"/></svg>
                <input id="name" name="name" autocomplete="name" value="{{ $user->name }}">
              </div>
              <p class="err-msg">Nama nggak boleh kosong.</p>
            </div>
            <div class="field">
              <label for="email">Email</label>
              <div class="input-ic">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="m3 7 9 6 9-6"/></svg>
                <input id="email" type="email" value="{{ $user->email }}" readonly aria-describedby="email-note">
                <span class="lock">Terkunci</span>
              </div>
              <small id="email-note">Email dipakai untuk masuk, jadi nggak bisa diubah. Butuh ganti? Hubungi bantuan.</small>
            </div>
            <div class="field">
              <label for="wa">Nomor WhatsApp</label>
              <div class="phone"><span>+62</span><input id="wa" name="whatsapp" type="tel" inputmode="tel" autocomplete="tel-national" placeholder="812xxxxxxxx" value="{{ ltrim($user->phone ?? '', '0') }}"></div>
              <p class="err-msg">Nomor belum valid. Contoh: 81234567890</p>
            </div>
          </div>
        </div>

        <div class="card">
          <div class="card-head">
            <div><h2>Alamat</h2><p class="sub">Alamat utama otomatis dipilih saat checkout.</p></div>
            <button type="button" class="btn btn-outline btn-sm" id="add-addr">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"><path d="M12 5v14M5 12h14"/></svg>Tambah
            </button>
          </div>
          <div class="addr" id="addr-list"></div>

          <div class="new-form" id="new-form" hidden>
            <h2 style="font-size:17px">Alamat baru</h2>
            <div class="field" style="margin-top:12px"><label for="n-label">Label <span style="font-weight:400;color:var(--muted)">(opsional)</span></label><input id="n-label" placeholder="Kos / Rumah / Kontrakan"></div>
            <div class="field"><label for="n-full">Alamat lengkap</label><textarea id="n-full" placeholder="Nama jalan, nomor rumah/kamar, nama kos, kecamatan"></textarea></div>
            <p class="err" id="n-err" role="alert" style="margin-top:0"></p>
            <div class="acts">
              <button type="button" class="link-btn" id="n-cancel">Batal</button>
              <button type="button" class="btn btn-primary btn-sm" id="n-save">Simpan alamat</button>
            </div>
          </div>
        </div>

        <div class="savebar idle" id="savebar" aria-live="polite">
          <span>Ada perubahan yang belum disimpan</span>
          <span style="display:flex;gap:4px">
            <button type="button" class="btn-ghost" id="discard">Batalkan</button>
            <button type="button" class="btn btn-sm" id="save">Simpan perubahan</button>
          </span>
        </div>
      </section>

      <!-- ================= BANTUAN ================= -->
      <section class="pane" id="p-bantuan" role="tabpanel" aria-labelledby="t-bantuan" @if($currentTab !== 'bantuan') hidden @endif>
        <div class="pane-head">
          <h1>Bantuan</h1>
          <p>Cari jawabannya di sini dulu. Kalau belum ketemu, tim Ruru siap bantu.</p>
        </div>

        <label class="help-search">
          <span class="sr" style="position:absolute;left:-9999px">Cari pertanyaan</span>
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5"/></svg>
          <input type="search" id="faq-q" placeholder="Contoh: jemput, bayar, perpanjang">
        </label>

        <div class="faq" id="faq">
          <details><summary>Barang apa saja yang bisa dititipkan?<span class="pm" aria-hidden="true">+</span></summary>
            <p>Kardus, koper, buku, kipas angin, rak, dan elektronik kecil. Barang yang tidak diterima: makanan mudah busuk, bahan mudah terbakar, dan barang berharga tanpa asuransi tambahan.</p></details>
          <details><summary>Bagaimana jika barang saya rusak atau hilang?<span class="pm" aria-hidden="true">+</span></summary>
            <p>Setiap barang difoto saat diterima sebagai bukti kondisi awal. Klaim ganti rugi mengikuti batas yang tercantum di Syarat &amp; Ketentuan.</p></details>
          <details><summary>Berapa lama minimal penitipan?<span class="pm" aria-hidden="true">+</span></summary>
            <p>Harga dihitung per bulan. Titip kurang dari 30 hari tetap dihitung 1 bulan.</p></details>
          <details><summary>Apakah ada layanan jemput ke kos?<span class="pm" aria-hidden="true">+</span></summary>
            <p>Ada, untuk area Malang. Pilih "Dijemput + dipacking tim Ruru" saat memesan di Ruang Titip.</p></details>
          <details><summary>Bagaimana cara membayar?<span class="pm" aria-hidden="true">+</span></summary>
            <p>Lewat Virtual Account bank, e-wallet dan QRIS, atau minimarket. Pilih metodenya di langkah terakhir pemesanan.</p></details>
          <details><summary>Bisakah saya memperpanjang durasi penitipan?<span class="pm" aria-hidden="true">+</span></summary>
            <p>Bisa. Buka <a href="{{ route('pesanan.index') }}">Pesanan Saya</a>, cari titipanmu, lalu hubungi tim Ruru untuk perpanjangan.</p></details>
        </div>
        <p class="no-faq" id="no-faq" hidden>Belum ada jawaban untuk itu. Tanya langsung ke tim Ruru di bawah, ya.</p>

        <div class="cs">
          <img src="{{ asset('assets/ruru/ruru-tunjuk.webp') }}" alt="" width="92" height="86">
          <div class="t">
            <strong>Masih bingung? Tanya Ruru aja.</strong>
            <p>Tim RuangTitip membalas lewat WhatsApp setiap hari, 08.00–21.00 WIB.</p>
            <div class="acts">
              <a class="btn btn-wa btn-sm" href="https://wa.me/6285121091134?text=Halo%20RuangTitip%2C%20saya%20butuh%20bantuan" target="_blank" rel="noopener">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12a8.5 8.5 0 0 1-12.6 7.4L3 21l1.6-5.2A8.5 8.5 0 1 1 21 12z"/></svg>
                Chat via WhatsApp</a>
              <a class="btn btn-outline btn-sm" href="mailto:ruangtitipmu@gmail.com">Kirim email</a>
            </div>
          </div>
        </div>
        <p class="ver">RuangTitip v1.0.0 &middot; <a href="#">Syarat &amp; Ketentuan</a> &middot; <a href="#">Kebijakan Privasi</a></p>
      </section>
    </div>
  </div>
</main>

<div class="p-toast" id="p-toast" role="status" aria-live="polite">
  <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
  <span id="p-toast-msg">Perubahan tersimpan</span>
</div>

@push('scripts')
<script>
(function () {
  var $ = function (x) { return document.getElementById(x); };

  /* ---------- Tab ---------- */
  function openPane(name) {
    document.querySelectorAll('[data-pane]').forEach(function (b) { b.setAttribute('aria-selected', b.dataset.pane === name ? 'true' : 'false'); });
    $('p-profil').hidden = name !== 'profil';
    $('p-bantuan').hidden = name !== 'bantuan';
    var url = new URL(window.location.href);
    url.searchParams.set('tab', name);
    history.replaceState(null, '', url);
  }
  document.querySelectorAll('[data-pane]').forEach(function (b) { b.addEventListener('click', function () { openPane(b.dataset.pane); }); });

  /* ---------- Foto ---------- */
  var avatarUrl = '{{ route('profile.avatar') }}';
  var csrf = document.querySelector('meta[name="csrf-token"]').content;
  $('photo').addEventListener('change', function () {
    var f = this.files[0], err = $('photo-err');
    err.textContent = '';
    if (!f) return;
    if (!/image\/(png|jpe?g|webp)/.test(f.type)) { err.textContent = 'Formatnya harus JPG, PNG, atau WEBP.'; return; }
    if (f.size > 3 * 1024 * 1024) { err.textContent = 'Ukuran foto lebih dari 3 MB.'; return; }

    var form = new FormData();
    form.append('avatar', f);
    form.append('_token', csrf);
    err.textContent = 'Mengunggah...';
    fetch(avatarUrl, { method: 'POST', body: form })
      .then(function (r) { return r.json(); })
      .then(function (data) {
        if (data.success) {
          var html = '<img src="' + data.avatar_url + '?t=' + Date.now() + '" alt="">';
          document.querySelectorAll('[data-avatar]').forEach(function (el) { el.innerHTML = html; });
          err.textContent = '';
          showToast('Foto profil diperbarui');
        } else {
          err.textContent = 'Gagal mengunggah foto.';
        }
      })
      .catch(function () { err.textContent = 'Gagal mengunggah foto.'; });
  });

  /* ---------- Data diri + savebar ---------- */
  var saved = { name: {{ Illuminate\Support\Js::from($user->name ?? '') }}, wa: {{ Illuminate\Support\Js::from(ltrim($user->phone ?? '', '0')) }} };
  var draft = { name: saved.name, wa: saved.wa };
  var redirectAfter = {{ Illuminate\Support\Js::from($redirectAfter ?? null) }};

  function isDirty() { return draft.name !== saved.name || draft.wa !== saved.wa; }
  function changed() { $('savebar').classList.toggle('idle', !isDirty()); }
  $('name').addEventListener('input', function () { draft.name = this.value; document.querySelectorAll('[data-name]').forEach(function (el) { el.textContent = draft.name || 'Tanpa nama'; }); changed(); });
  $('wa').addEventListener('input', function () { draft.wa = this.value.replace(/\D/g, ''); changed(); });

  function validate() {
    var ok = true;
    var nf = $('name').closest('.field'), wf = $('wa').closest('.field');
    var nameEmpty = !draft.name.trim();
    nf.classList.toggle('invalid', nameEmpty); if (nameEmpty) ok = false;
    var badWa = draft.wa && !/^8\d{8,12}$/.test(draft.wa);
    wf.classList.toggle('invalid', !!badWa); if (badWa) ok = false;
    return ok;
  }

  var toastTimer;
  function showToast(msg) {
    $('p-toast-msg').textContent = msg;
    $('p-toast').classList.add('show');
    clearTimeout(toastTimer);
    toastTimer = setTimeout(function () { $('p-toast').classList.remove('show'); }, 2500);
  }

  $('save').addEventListener('click', function () {
    if (!validate()) return;
    var btn = this;
    btn.disabled = true;
    fetch('{{ route('profile.update') }}', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
      body: JSON.stringify({ name: draft.name, whatsapp: draft.wa }),
    })
      .then(function (r) { return r.json(); })
      .then(function (data) {
        btn.disabled = false;
        if (data.success) {
          saved = { name: draft.name, wa: draft.wa };
          changed();
          showToast(data.message || 'Perubahan tersimpan');
          if (redirectAfter && draft.name.trim() && draft.wa.trim()) {
            window.location.href = redirectAfter;
          }
        }
      })
      .catch(function () { btn.disabled = false; });
  });
  $('discard').addEventListener('click', function () {
    draft = { name: saved.name, wa: saved.wa };
    $('name').value = draft.name;
    $('wa').value = draft.wa;
    document.querySelectorAll('[data-name]').forEach(function (el) { el.textContent = draft.name || 'Tanpa nama'; });
    document.querySelectorAll('.field.invalid').forEach(function (f) { f.classList.remove('invalid'); });
    changed();
  });
  window.addEventListener('beforeunload', function (e) { if (isDirty()) { e.preventDefault(); e.returnValue = ''; } });

  /* ---------- Alamat ---------- */
  var addresses = {{ Illuminate\Support\Js::from($addresses ?? []) }};
  function drawAddr() {
    var box = $('addr-list'); box.innerHTML = '';
    if (!addresses.length) { box.innerHTML = '<p class="empty-addr">Belum ada alamat. Tambahkan alamat kos biar checkout lebih cepat.</p>'; return; }
    addresses.forEach(function (a) {
      var el = document.createElement('div');
      el.className = 'addr-item' + (a.isPrimary ? ' main' : '');
      el.innerHTML = '<span class="ic" aria-hidden="true"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#1C1B18" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 21s-7-6.2-7-11.5a7 7 0 0 1 14 0C19 14.8 12 21 12 21z"/><circle cx="12" cy="9.5" r="2.5"/></svg></span>' +
        '<div class="t"><strong></strong>' + (a.isPrimary ? ' <span class="pill pill-ink">Utama</span>' : '') + '<p></p>' +
        '<div class="row">' + (a.isPrimary ? '' : '<button type="button" class="link-btn" data-act="main">Jadikan utama</button>') +
        '<button type="button" class="link-btn danger" data-act="del">Hapus</button></div></div>';
      el.querySelector('strong').textContent = a.label;
      el.querySelector('p').textContent = a.address;
      el.querySelectorAll('[data-act]').forEach(function (b) {
        b.addEventListener('click', function () {
          if (b.dataset.act === 'main') {
            fetch('{{ url('/profil/alamat') }}/' + a.id + '/primary', { method: 'POST', headers: { 'X-CSRF-TOKEN': csrf } })
              .then(function () { addresses.forEach(function (x) { x.isPrimary = x.id === a.id; }); drawAddr(); });
          } else {
            if (!confirm('Hapus alamat "' + a.label + '"?')) return;
            fetch('{{ url('/profil/alamat') }}/' + a.id, { method: 'DELETE', headers: { 'X-CSRF-TOKEN': csrf } })
              .then(function (r) { return r.json(); })
              .then(function (data) {
                if (data.success) {
                  var wasPrimary = a.isPrimary;
                  addresses = addresses.filter(function (x) { return x.id !== a.id; });
                  if (wasPrimary && addresses.length) addresses[addresses.length - 1].isPrimary = true;
                  drawAddr();
                }
              });
          }
        });
      });
      box.appendChild(el);
    });
  }
  function closeForm() { $('new-form').hidden = true; $('add-addr').hidden = false; $('n-label').value = ''; $('n-full').value = ''; $('n-err').textContent = ''; }
  $('add-addr').addEventListener('click', function () { $('new-form').hidden = false; this.hidden = true; $('n-label').focus(); });
  $('n-cancel').addEventListener('click', closeForm);
  $('n-save').addEventListener('click', function () {
    if (!$('n-full').value.trim()) { $('n-err').textContent = 'Isi alamat lengkap.'; return; }
    fetch('{{ route('profile.address.store') }}', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf },
      body: JSON.stringify({ label: $('n-label').value.trim(), address: $('n-full').value.trim() }),
    })
      .then(function (r) { return r.json(); })
      .then(function (data) {
        if (data.success) {
          addresses.push({ id: data.id, label: data.label, address: data.address, isPrimary: data.isPrimary });
          closeForm(); drawAddr();
        } else {
          $('n-err').textContent = 'Gagal menyimpan alamat.';
        }
      });
  });

  /* ---------- Cari FAQ ---------- */
  $('faq-q').addEventListener('input', function () {
    var q = this.value.trim().toLowerCase(), shown = 0;
    document.querySelectorAll('#faq details').forEach(function (d) {
      var ok = d.textContent.toLowerCase().indexOf(q) !== -1;
      d.hidden = !ok; if (ok) shown++;
      if (q && ok) d.open = true;
    });
    $('no-faq').hidden = shown > 0;
  });

  drawAddr();
})();
</script>
@endpush
@endsection
