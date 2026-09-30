<!doctype html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="robots" content="noindex">
<title>Sedang perbaikan &middot; RuangTitip</title>
<link rel="icon" type="image/svg+xml" href="{{ asset('assets/favicon.svg') }}">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,700;12..96,800&family=Figtree:wght@400;500;600;700&display=swap">
<style>
:root{
  --ink:#1C1B18;--cream:#F5F1E8;--paper:#FFFDF8;--sand:#E8DFCD;--kraft:#C9A27A;
  --tape:#B4531D;--tape-dark:#9A4415;--tape-light:#E8A677;--tape-soft:#F6E3D3;
  --depot:#2E5A45;--depot-light:#EAF1EC;--danger:#A3321A;
  --body:#4F4A40;--muted:#5C574D;--line:#DDD5C4;--line-strong:#CFC6B3;
  --font-display:'Bricolage Grotesque','Figtree',system-ui,sans-serif;
  --font-body:'Figtree',system-ui,sans-serif;
}
*,*::before,*::after{box-sizing:border-box}
html,body{min-height:100%}
html{overflow-x:hidden}
body{position:relative}
body{margin:0;font-family:var(--font-body);background:var(--cream);color:var(--ink);font-size:16px;line-height:1.6;-webkit-font-smoothing:antialiased;overflow-x:hidden}
img{display:block;max-width:100%}
a{color:var(--tape-dark)}
h1,h2,h3{font-family:var(--font-display);margin:0;line-height:1.05}
p{margin:0}
button,input{font:inherit;color:inherit}
:focus-visible{outline:3px solid var(--tape);outline-offset:3px}
.sr{position:absolute;left:-9999px}

/* Pita lakban "under construction" */
.tape-band{position:absolute;left:-5%;width:110%;height:44px;background:repeating-linear-gradient(-45deg,var(--tape-light) 0 28px,var(--tape) 28px 56px);border-block:2px solid var(--ink);z-index:0;opacity:.9}
.tape-band.t1{top:92px;transform:rotate(-3deg)}
.tape-band.t2{bottom:34px;transform:rotate(1.5deg)}
.tape-band span{display:block;width:max-content;animation:slide 40s linear infinite}
@keyframes slide{to{transform:translateX(-50%)}}

.page{position:relative;z-index:1;max-width:1140px;margin:0 auto;padding:28px 24px 150px}
.top{display:flex;justify-content:space-between;align-items:center;gap:16px}
.top img{height:40px;width:auto}
.top a.btn{min-height:40px;font-size:14px;padding:0 16px}

.grid{display:grid;grid-template-columns:1fr 1.05fr;gap:40px;align-items:center;margin-top:96px}

/* ===== Ruru ===== */
.stage{position:relative;display:flex;justify-content:center;align-items:flex-end;min-height:520px}
.stage .disc{position:absolute;bottom:40px;width:min(440px,92%);aspect-ratio:1;border-radius:50%;background:var(--sand);border:2px solid var(--ink)}
.stage .shadow{position:absolute;bottom:34px;width:260px;height:26px;border-radius:50%;background:var(--ink);opacity:.14;transition:transform .2s}
.ruru-btn{position:relative;border:0;background:none;padding:0;cursor:pointer;margin-bottom:52px;touch-action:manipulation}
.ruru-btn img{width:min(320px,70vw);height:auto;transform-origin:50% 90%;transition:transform .25s cubic-bezier(.3,1.6,.5,1);animation:bob 3.4s ease-in-out infinite}
.ruru-btn:hover img{animation-play-state:paused}
.ruru-btn.poke img{animation:poke .5s cubic-bezier(.3,1.6,.5,1)}
@keyframes bob{0%,100%{translate:0 0}50%{translate:0 -10px}}
@keyframes poke{0%{transform:scale(1,1)}30%{transform:scale(1.08,.9)}60%{transform:scale(.95,1.06) translateY(-14px)}100%{transform:scale(1,1)}}
.say{position:absolute;top:10px;left:0;max-width:280px;background:var(--paper);border:2px solid var(--ink);border-radius:20px;box-shadow:5px 5px 0 var(--ink);padding:14px 18px;font-family:var(--font-display);font-weight:800;font-size:20px;line-height:1.2;transition:transform .2s}
.say::after{content:"";position:absolute;right:34px;bottom:-11px;width:18px;height:18px;background:var(--paper);border-right:2px solid var(--ink);border-bottom:2px solid var(--ink);transform:rotate(45deg)}
.say.pop{animation:pop .3s ease}
@keyframes pop{50%{transform:scale(1.06)}}
.hint{position:absolute;bottom:0;left:50%;transform:translateX(-50%);font-size:13px;font-weight:600;color:var(--muted);white-space:nowrap}
.tools{position:absolute;inset:0;pointer-events:none}
.tools i{position:absolute;display:grid;place-items:center;width:52px;height:52px;border-radius:14px;background:var(--paper);border:2px solid var(--ink);animation:float 5s ease-in-out infinite}
.tools i:nth-child(1){left:6%;top:38%;animation-delay:-1s}
.tools i:nth-child(2){right:4%;top:28%;animation-delay:-2.5s}
.tools i:nth-child(3){right:10%;bottom:28%;animation-delay:-3.5s}
@keyframes float{0%,100%{transform:translateY(0) rotate(-6deg)}50%{transform:translateY(-12px) rotate(6deg)}}

/* ===== Konten ===== */
.badge{display:inline-flex;align-items:center;gap:8px;padding:7px 14px;border-radius:999px;background:var(--tape-soft);border:1.5px solid var(--tape);color:var(--tape-dark);font-weight:700;font-size:14px}
.badge .dot{width:9px;height:9px;border-radius:50%;background:var(--tape);animation:blink 1.4s ease-in-out infinite}
@keyframes blink{50%{opacity:.25}}
h1{font-size:clamp(42px,5.4vw,68px);font-weight:800;letter-spacing:-2px;margin-top:18px}
h1 em{font-style:normal;color:var(--tape)}
.lead{font-size:18px;color:var(--body);margin-top:14px;max-width:520px}

.card{margin-top:26px;background:var(--paper);border:2px solid var(--ink);border-radius:20px;box-shadow:6px 6px 0 var(--ink);overflow:hidden}
.eta{display:flex;justify-content:space-between;align-items:center;gap:16px;padding:18px 22px;background:var(--sand);border-bottom:2px solid var(--ink);flex-wrap:wrap}
.eta small{display:block;font-size:13px;font-weight:700;color:var(--muted);letter-spacing:.4px;text-transform:uppercase}
.eta strong{font-family:var(--font-display);font-size:18px}
.clock{display:flex;gap:6px}
.clock div{min-width:62px;text-align:center;padding:6px 8px;background:var(--ink);color:var(--cream);border-radius:10px}
.clock b{display:block;font-family:var(--font-display);font-size:26px;line-height:1.1;font-variant-numeric:tabular-nums}
.clock span{font-size:11px;color:#C9C2B3;font-weight:600}
.steps{list-style:none;margin:0;padding:18px 22px;display:flex;flex-direction:column;gap:12px}
.steps li{display:flex;align-items:center;gap:12px;font-weight:600}
.steps .ic{width:28px;height:28px;border-radius:50%;display:grid;place-items:center;flex-shrink:0;border:2px solid var(--line-strong);background:var(--paper)}
.steps li.done .ic{background:var(--depot);border-color:var(--depot)}
.steps li.now .ic{border-color:var(--tape);border-top-color:transparent;animation:spin 1s linear infinite}
.steps li.todo{color:var(--muted)}
.steps li small{margin-left:auto;font-size:13px;font-weight:600;color:var(--muted)}
.steps li.now small{color:var(--tape-dark)}
@keyframes spin{to{transform:rotate(360deg)}}
.upd{padding:12px 22px;border-top:1px solid var(--line);font-size:13px;color:var(--muted)}

/* Kabari saya */
.notify{margin-top:18px}
.notify label{font-weight:700;font-size:15px}
.notify .row{display:flex;gap:8px;margin-top:8px}
.notify input{flex:1;min-width:0;height:52px;border-radius:999px;border:2px solid var(--line-strong);background:var(--paper);padding:0 20px;font-size:16px}
.notify input:focus{outline:none;border-color:var(--ink)}
.btn{display:inline-flex;align-items:center;justify-content:center;gap:8px;min-height:52px;padding:0 22px;border-radius:999px;font-weight:700;font-size:15px;text-decoration:none;border:2px solid var(--ink);cursor:pointer;white-space:nowrap;transition:transform .12s}
.btn-primary{background:var(--tape);color:#fff;box-shadow:3px 3px 0 var(--ink)}
.btn-primary:hover{background:var(--tape-dark);color:#fff}
.btn-primary:active{transform:translate(2px,2px);box-shadow:1px 1px 0 var(--ink)}
.btn-outline{background:var(--paper);color:var(--ink)}
.btn-outline:hover{background:var(--sand);color:var(--ink)}
.msg{font-size:14px;font-weight:600;margin-top:8px;min-height:1.4em}
.msg.ok{color:var(--depot)}.msg.bad{color:var(--danger)}
.links{display:flex;gap:10px;flex-wrap:wrap;margin-top:18px}
.btn-wa{background:var(--depot);color:#fff;box-shadow:3px 3px 0 var(--ink)}
.btn-wa:hover{background:#244A38;color:#fff}

/* ===== Mini game ===== */
.game{margin-top:72px;background:var(--paper);border:2px solid var(--ink);border-radius:24px;box-shadow:6px 6px 0 var(--ink);overflow:hidden}
.game-head{display:flex;justify-content:space-between;align-items:center;gap:16px;padding:18px 24px;border-bottom:2px solid var(--ink);flex-wrap:wrap}
.game-head h2{font-size:26px;letter-spacing:-.5px}
.game-head p{color:var(--body);font-size:15px}
.score{display:flex;gap:10px}
.score div{padding:8px 14px;border-radius:12px;background:var(--cream);border:1.5px solid var(--line-strong);text-align:center;min-width:84px}
.score small{display:block;font-size:12px;font-weight:700;color:var(--muted)}
.score b{font-family:var(--font-display);font-size:24px;line-height:1.1}
.arena{position:relative;height:420px;background:linear-gradient(var(--cream) 0 0) ,var(--cream);overflow:hidden;cursor:pointer;touch-action:manipulation;user-select:none}
.arena::before{content:"";position:absolute;inset:0;background:repeating-linear-gradient(0deg,transparent 0 39px,var(--line) 39px 40px);opacity:.6}
.floor{position:absolute;z-index:2;left:0;right:0;bottom:0;height:24px;background:var(--kraft);border-top:2px solid var(--ink)}
#tower{position:absolute;left:0;right:0;bottom:24px;transition:transform .3s ease}
.box{position:absolute;height:40px;background:var(--kraft);border:2px solid var(--ink);border-radius:4px}
.box::before{content:"";position:absolute;left:50%;top:-2px;bottom:-2px;width:14px;transform:translateX(-50%);background:var(--tape);opacity:.85}
.box.cut{background:var(--tape-light);transition:transform .7s ease-in,opacity .7s;opacity:.9}
.box.cut::before{display:none}
.box.perfect{animation:flash .4s}
@keyframes flash{50%{background:var(--depot-light)}}
.overlay{position:absolute;inset:0;display:flex;flex-direction:column;align-items:center;justify-content:center;gap:12px;text-align:center;background:rgba(245,241,232,.9);z-index:5;padding:24px}
.overlay[hidden]{display:none}
.overlay strong{font-family:var(--font-display);font-size:30px}
.overlay p{color:var(--body);max-width:360px}
.float-txt{position:absolute;z-index:4;font-family:var(--font-display);font-weight:800;font-size:22px;color:var(--depot);pointer-events:none;animation:up .9s ease forwards}
@keyframes up{to{transform:translateY(-40px);opacity:0}}

.foot{margin-top:40px;text-align:center;font-size:14px;color:var(--muted)}

@media (max-width:900px){
  .grid{grid-template-columns:1fr;margin-top:72px;gap:8px}
  .stage{min-height:440px;order:-1}
  .say{font-size:17px;max-width:220px}
  .tools{display:none}
  .tape-band.t1{top:78px}
}
@media (max-width:520px){
  .notify .row{flex-direction:column}
  .clock div{min-width:54px}
  .arena{height:360px}
  .top a.btn span{display:none}
}
@media (prefers-reduced-motion:reduce){
  *{animation:none!important;transition:none!important}
}
</style>
</head>
<body>
<div class="tape-band t1" aria-hidden="true"></div>
<div class="tape-band t2" aria-hidden="true"></div>

<div class="page">
  <header class="top">
    <img src="{{ asset('assets/logo-ruangtitip.svg') }}" alt="RuangTitip" width="200" height="50">
    <a class="btn btn-outline" href="https://instagram.com/ruangtitip" target="_blank" rel="noopener">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.5" cy="6.5" r=".8" fill="currentColor"/></svg>
      <span>Update di Instagram</span>
    </a>
  </header>

  <main class="grid">
    <!-- ===== Ruru ===== -->
    <div class="stage">
      <div class="disc" aria-hidden="true"></div>
      <div class="shadow" id="shadow" aria-hidden="true"></div>
      <div class="tools" aria-hidden="true">
        <i><svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="#1C1B18" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.7 6.3a4 4 0 0 0-5.4 5.4L3 18l3 3 6.3-6.3a4 4 0 0 0 5.4-5.4l-2.5 2.5-2.4-.6-.6-2.4z"/></svg></i>
        <i><svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="#1C1B18" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"/><path d="M12 2v3M12 19v3M4.2 4.2l2.1 2.1M17.7 17.7l2.1 2.1M2 12h3M19 12h3M4.2 19.8l2.1-2.1M17.7 6.3l2.1-2.1"/></svg></i>
        <i><svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="#1C1B18" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 8 12 3 3 8v8l9 5 9-5z"/><path d="m3 8 9 5 9-5M12 13v8"/></svg></i>
      </div>
      <button class="ruru-btn" id="ruru" type="button" aria-label="Colek Ruru">
        <img src="{{ asset('assets/ruru.webp') }}" alt="" width="769" height="900">
      </button>
      <p class="say" id="say" aria-live="polite">Lagi benerin rak gudang dulu, ya!</p>
      <p class="hint">Colek Ruru, deh</p>
    </div>

    <!-- ===== Info ===== -->
    <div>
      <span class="badge"><span class="dot" aria-hidden="true"></span>Sedang perbaikan terjadwal</span>
      <h1>Ruru lagi <em>beres-beres</em> gudang.</h1>
      <p class="lead">RuangTitip sedang diperbarui supaya lebih cepat dan nyaman dipakai. Barang titipanmu tetap aman di gudang, perbaikan ini cuma di website.</p>

      <section class="card" aria-label="Status perbaikan">
        <div class="eta">
          <div><small>Perkiraan selesai</small><strong id="eta-text">&ndash;</strong></div>
          <div class="clock" id="clock" aria-live="off">
            <div><b id="c-h">00</b><span>jam</span></div>
            <div><b id="c-m">00</b><span>menit</span></div>
            <div><b id="c-s">00</b><span>detik</span></div>
          </div>
        </div>
        <ol class="steps" id="steps"></ol>
        <p class="upd" id="upd"></p>
      </section>

      <form class="notify" id="notify" novalidate>
        <label for="email">Kabari saya kalau sudah online</label>
        <div class="row">
          <input type="email" id="email" name="email" placeholder="email@kamu.com" autocomplete="email" required>
          <button class="btn btn-primary" type="submit">Kabari saya</button>
        </div>
        <p class="msg" id="msg" role="status"></p>
      </form>

      <div class="links">
        <a class="btn btn-wa" href="https://wa.me/6285121091134?text=Halo%20RuangTitip%2C%20saya%20mau%20tanya%20soal%20titipan%20saya" target="_blank" rel="noopener">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12a8.5 8.5 0 0 1-12.6 7.4L3 21l1.6-5.2A8.5 8.5 0 1 1 21 12z"/></svg>
          Urusan titipan mendesak? Chat WA
        </a>
        <button class="btn btn-outline" type="button" id="reload">Coba muat ulang</button>
      </div>
    </div>
  </main>

  <!-- ===== Mini game ===== -->
  <section class="game" aria-labelledby="game-title">
    <div class="game-head">
      <div>
        <h2 id="game-title">Sambil nunggu, bantu Ruru susun kardus</h2>
        <p>Klik, tap, atau tekan spasi untuk menjatuhkan kardus. Makin pas, makin tinggi.</p>
      </div>
      <div class="score">
        <div><small>Skor</small><b id="score">0</b></div>
        <div><small>Rekor</small><b id="best">0</b></div>
      </div>
    </div>
    <div class="arena" id="arena" role="button" tabindex="0" aria-label="Area permainan. Tekan spasi atau Enter untuk menjatuhkan kardus.">
      <div class="floor" aria-hidden="true"></div>
      <div id="tower" aria-hidden="true"></div>
      <div class="overlay" id="overlay">
        <strong id="ov-title">Siap susun kardus?</strong>
        <p id="ov-text">Kardus bergerak kiri-kanan. Jatuhkan tepat di atas tumpukan. Bagian yang meleset akan terpotong.</p>
        <button class="btn btn-primary" type="button" id="start">Mulai main</button>
      </div>
    </div>
  </section>

  <p class="foot">&copy; {{ date('Y') }} RuangTitip &middot; Malang &middot; <a href="mailto:ruangtitipmu@gmail.com">ruangtitipmu@gmail.com</a></p>
</div>

<script>
/* =====================================================
   PENGATURAN MAINTENANCE — ubah bagian ini saja
   ===================================================== */
var MAINTENANCE = {
  selesai: new Date(Date.now() + 2 * 3600e3 + 15 * 60e3), // ganti: new Date('2026-10-01T21:00:00+07:00')
  diperbarui: new Date(),                                   // ganti: kapan status terakhir diubah
  langkah: [
    { nama: 'Cadangkan data',          status: 'done' },
    { nama: 'Perbarui sistem pembayaran', status: 'done' },
    { nama: 'Pasang fitur baru',       status: 'now'  },
    { nama: 'Uji coba akhir',          status: 'todo' }
  ]
};
</script>
<script>
(function () {
  var $ = function (x) { return document.getElementById(x); };

  /* ---------- Status & hitung mundur ---------- */
  var ICON_OK = '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="3.2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>';
  var label = { done: 'Selesai', now: 'Sedang dikerjakan', todo: 'Berikutnya' };
  MAINTENANCE.langkah.forEach(function (s) {
    var li = document.createElement('li');
    li.className = s.status;
    li.innerHTML = '<span class="ic" aria-hidden="true">' + (s.status === 'done' ? ICON_OK : '') + '</span><span></span><small>' + label[s.status] + '</small>';
    li.children[1].textContent = s.nama;
    $('steps').appendChild(li);
  });
  var fmt = { weekday: 'long', hour: '2-digit', minute: '2-digit' };
  $('eta-text').textContent = MAINTENANCE.selesai.toLocaleString('id-ID', fmt).replace('.', ':') + ' WIB';
  $('upd').textContent = 'Status terakhir diperbarui ' + MAINTENANCE.diperbarui.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' }).replace('.', ':') + ' WIB';
  var pad = function (n) { return String(n).padStart(2, '0'); };
  function tick() {
    var left = Math.max(0, MAINTENANCE.selesai - Date.now());
    var h = Math.floor(left / 3600e3), m = Math.floor(left % 3600e3 / 60e3), s = Math.floor(left % 60e3 / 1e3);
    $('c-h').textContent = pad(h); $('c-m').textContent = pad(m); $('c-s').textContent = pad(s);
    if (!left) { $('eta-text').textContent = 'Sebentar lagi, lagi finishing'; clearInterval(timer); }
  }
  var timer = setInterval(tick, 1000); tick();

  /* ---------- Ruru interaktif ---------- */
  var lines = [
    'Lagi benerin rak gudang dulu, ya!',
    'Eh, geli! Aku lagi kerja nih.',
    'Barangmu aman kok, aku jagain.',
    'Lakbannya habis, tunggu bentar…',
    'Sambil nunggu, main susun kardus yuk!',
    'Nanti balik lagi dengan fitur baru.',
    'Kamu udah minum air belum?',
    'Kardusku masih kosong, siap diisi barangmu.'
  ];
  var li = 0, pokes = 0;
  $('ruru').addEventListener('click', function () {
    var b = this; pokes++;
    b.classList.remove('poke'); void b.offsetWidth; b.classList.add('poke');
    li = (li + 1) % lines.length;
    var text = pokes === 10 ? 'Sepuluh colekan! Kamu pasti lagi bosan, ya?' : lines[li];
    $('say').textContent = text;
    $('say').classList.remove('pop'); void $('say').offsetWidth; $('say').classList.add('pop');
  });
  // Ruru sedikit miring mengikuti kursor
  var img = document.querySelector('.ruru-btn img');
  if (window.matchMedia('(hover:hover)').matches && !window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
    document.addEventListener('mousemove', function (e) {
      var r = img.getBoundingClientRect(), dx = (e.clientX - (r.left + r.width / 2)) / window.innerWidth;
      img.style.transform = 'rotate(' + (dx * 14).toFixed(2) + 'deg)';
    });
  }

  /* ---------- Kabari saya ---------- */
  $('notify').addEventListener('submit', function (e) {
    e.preventDefault();
    var v = $('email').value.trim(), m = $('msg');
    if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v)) { m.className = 'msg bad'; m.textContent = 'Email-nya belum valid, coba cek lagi.'; return; }
    m.className = 'msg ok'; m.textContent = 'Siap! Ruru bakal kabari ' + v + ' begitu website online lagi.';
    this.reset();
  });
  $('reload').addEventListener('click', function () { location.reload(); });

  /* ---------- Mini game: susun kardus ---------- */
  var arena = $('arena'), tower = $('tower');
  var BOX_H = 40, W, stack, cur, dir, speed, running = false, raf, score = 0, best = 0;
  try { best = +localStorage.getItem('rt_stack_best') || 0; } catch (e) {}
  $('best').textContent = best;

  function mk(x, y, w, cls) {
    var d = document.createElement('div');
    d.className = 'box' + (cls ? ' ' + cls : '');
    d.style.left = x + 'px'; d.style.bottom = y + 'px'; d.style.width = w + 'px';
    tower.appendChild(d); return d;
  }
  function start() {
    W = arena.clientWidth; tower.innerHTML = ''; tower.style.transform = 'translateY(0)';
    var w0 = Math.min(260, W * 0.5);
    stack = [{ x: (W - w0) / 2, w: w0 }];
    mk(stack[0].x, 0, w0);
    score = 0; $('score').textContent = 0; speed = 3; running = true;
    $('overlay').hidden = true; spawn(); loop(); arena.focus();
  }
  function spawn() {
    var top = stack[stack.length - 1];
    dir = Math.random() < .5 ? 1 : -1;
    cur = { x: dir > 0 ? 0 : W - top.w, w: top.w, y: stack.length * BOX_H };
    cur.el = mk(cur.x, cur.y, cur.w);
  }
  function loop() {
    if (!running) return;
    cur.x += dir * speed;
    if (cur.x > W - cur.w) { cur.x = W - cur.w; dir = -1; } else if (cur.x < 0) { cur.x = 0; dir = 1; }
    cur.el.style.left = cur.x + 'px';
    raf = requestAnimationFrame(loop);
  }
  function drop() {
    if (!running) return;
    var top = stack[stack.length - 1];
    var l = Math.max(cur.x, top.x), r = Math.min(cur.x + cur.w, top.x + top.w), ov = r - l;
    if (ov <= 0) { cur.el.classList.add('cut'); cur.el.style.transform = 'translateY(300px) rotate(' + (dir * 30) + 'deg)'; return over(); }
    var perfect = Math.abs(cur.x - top.x) < 6;
    if (perfect) { l = top.x; ov = top.w; }
    // potongan yang meleset
    if (!perfect && ov < cur.w) {
      var cx = cur.x < top.x ? cur.x : r, cw = cur.w - ov;
      var piece = mk(cx, cur.y, cw, 'cut');
      requestAnimationFrame(function () { piece.style.transform = 'translateY(260px) rotate(' + (cur.x < top.x ? -25 : 25) + 'deg)'; piece.style.opacity = 0; });
      setTimeout(function () { piece.remove(); }, 800);
    }
    cur.el.style.left = l + 'px'; cur.el.style.width = ov + 'px';
    if (perfect) { cur.el.classList.add('perfect'); floatTxt('Pas banget!', l + ov / 2); }
    stack.push({ x: l, w: ov });
    score++; $('score').textContent = score;
    speed = Math.min(9, 3 + score * 0.25);
    var h = stack.length * BOX_H, max = arena.clientHeight - 140;
    if (h > max) tower.style.transform = 'translateY(' + (h - max) + 'px)';
    spawn();
  }
  function floatTxt(t, x) {
    var s = document.createElement('span'); s.className = 'float-txt'; s.textContent = t;
    s.style.left = Math.max(10, x - 60) + 'px'; s.style.top = '40%';
    arena.appendChild(s); setTimeout(function () { s.remove(); }, 900);
  }
  function over() {
    running = false; cancelAnimationFrame(raf);
    var rec = score > best;
    if (rec) { best = score; $('best').textContent = best; try { localStorage.setItem('rt_stack_best', best); } catch (e) {} }
    setTimeout(function () {
      $('ov-title').textContent = rec && score > 0 ? 'Rekor baru: ' + score + ' kardus!' : score + ' kardus tersusun';
      $('ov-text').textContent = score >= 15 ? 'Wah, kamu cocok jadi tim gudang Ruru.' : score >= 7 ? 'Lumayan! Coba lagi, pasti bisa lebih tinggi.' : 'Kardusnya jatuh. Tenang, barang titipan asli nggak begini kok.';
      $('start').textContent = 'Main lagi';
      $('overlay').hidden = false; $('start').focus();
    }, 500);
  }
  $('start').addEventListener('click', function (e) { e.stopPropagation(); start(); });
  arena.addEventListener('pointerdown', function (e) { if (running) { e.preventDefault(); drop(); } });
  arena.addEventListener('keydown', function (e) {
    if (e.key === ' ' || e.key === 'Enter') { e.preventDefault(); running ? drop() : start(); }
  });
})();
</script>
</body>
</html>
