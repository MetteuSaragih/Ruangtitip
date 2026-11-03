<!doctype html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="robots" content="noindex">
<title>Halaman tidak ditemukan &middot; RuangTitip</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,700;12..96,800&family=Figtree:wght@400;500;600;700&display=swap">
<style>
:root{
  --ink:#E6F5F3;--deep:#0C2B2A;--cream:#0A2423;--paper:#11332F;--sand:#163C38;--kraft:#C9A27A;
  --tape:#FFC93C;--tape-dark:#FFD86B;--tape-light:#FFC93C;--tape-soft:#1E3B37;
  --depot:#2DD4BF;--depot-light:#173F2A;
  --body:#A5C6C2;--muted:#A5C6C2;--line:#24504B;--line-strong:#24504B;
  --font-display:'Bricolage Grotesque','Figtree',system-ui,sans-serif;
  --font-body:'Figtree',system-ui,sans-serif;
}
*,*::before,*::after{box-sizing:border-box}
html{overflow-x:hidden}
body{margin:0;min-height:100vh;font-family:var(--font-body);background:var(--cream);color:var(--ink);font-size:16px;line-height:1.6;-webkit-font-smoothing:antialiased;display:flex;flex-direction:column}
img,svg{display:block;max-width:100%}
a{color:var(--tape-dark)}
h1{font-family:var(--font-display);margin:0;line-height:1.05}
p{margin:0}
button{font:inherit;color:inherit}
:focus-visible{outline:3px solid var(--tape);outline-offset:3px}

.page{flex:1;width:100%;max-width:1080px;margin:0 auto;padding:28px 24px 40px;display:flex;flex-direction:column}
.top .logo{display:inline-block}
.top .logo svg{height:40px;width:auto}

main{flex:1;display:grid;grid-template-columns:1.05fr 1fr;gap:48px;align-items:center;padding:40px 0}

/* Rak dengan kardus 4-0-4 */
.scene{position:relative;padding:0 8px}
.shelf{position:relative;display:flex;align-items:flex-end;justify-content:center;gap:12px;padding:0 12px;min-height:320px}
.shelf::after{content:"";position:absolute;left:0;right:0;bottom:-16px;height:16px;background:var(--kraft);border:2px solid var(--ink);border-radius:4px}
.box{position:relative;display:grid;place-items:center;width:clamp(70px,9vw,104px);aspect-ratio:1/.92;background:var(--kraft);border:2px solid var(--ink);border-radius:8px;font-family:var(--font-display);font-weight:800;font-size:clamp(46px,6vw,68px);line-height:1;color:var(--ink);box-shadow:none}
.box::before{content:"";position:absolute;top:-2px;left:50%;width:18px;height:22px;transform:translateX(-50%);background:var(--tape);border:2px solid var(--ink);border-top:0}
.box span{position:relative;top:6px}
.box.b1{transform:rotate(-4deg)}
.box.b2{background:var(--paper);border-style:dashed;box-shadow:none;color:var(--tape)}
.box.b2::before{display:none}
.box.b3{transform:rotate(3deg);margin-bottom:0}
.ruru{position:relative;flex-shrink:0;margin-left:4px;margin-bottom:-2px;width:clamp(120px,15vw,160px);transform-origin:50% 100%;animation:look 4s ease-in-out infinite;cursor:pointer;border:0;padding:0;background:none}
.ruru img{width:100%}
@keyframes look{0%,100%{transform:rotate(0)}30%{transform:rotate(-5deg)}60%{transform:rotate(4deg)}}
.bubble{position:absolute;right:clamp(130px,15vw,170px);top:20px;max-width:220px;padding:12px 16px;background:var(--paper);border:2px solid var(--ink);border-radius:16px;box-shadow:none;font-weight:600;font-size:15px;line-height:1.4}
.bubble::after{content:"";position:absolute;right:-10px;top:24px;width:16px;height:16px;background:var(--paper);border-right:2px solid var(--ink);border-bottom:2px solid var(--ink);transform:rotate(-45deg)}
.hint{margin-top:34px;text-align:center;font-size:13px;color:var(--muted)}

.code{display:inline-flex;align-items:center;gap:8px;padding:6px 14px;border-radius:999px;background:var(--tape-soft);border:1.5px solid var(--tape);color:var(--tape-dark);font-weight:700;font-size:14px}
h1{font-size:clamp(38px,4.6vw,58px);font-weight:800;letter-spacing:-2px;margin-top:16px}
h1 em{font-style:normal;color:var(--tape)}
.lead{font-size:18px;color:var(--body);margin-top:14px;max-width:470px}

.info{margin-top:22px;background:var(--paper);border:1.5px solid var(--line-strong);border-radius:16px;padding:4px 18px;max-width:470px}
.info div{display:flex;justify-content:space-between;gap:16px;padding:11px 0;font-size:14px}
.info div+div{border-top:1px solid var(--line)}
.info dt{color:var(--muted)}
.info dd{margin:0;font-weight:700;text-align:right;overflow-wrap:anywhere;font-family:ui-monospace,Menlo,monospace;font-size:13px}
.info dl{margin:0}

.actions{display:flex;gap:10px;flex-wrap:wrap;margin-top:24px}
.btn{display:inline-flex;align-items:center;justify-content:center;gap:8px;min-height:50px;padding:0 22px;border-radius:999px;font-weight:700;font-size:15px;text-decoration:none;border:2px solid var(--ink);cursor:pointer;white-space:nowrap;transition:transform .12s}
.btn-primary{background:var(--tape);color:var(--deep);box-shadow:none}
.btn-primary:hover{background:var(--tape-dark);color:var(--deep)}
.btn-primary:active{transform:translate(2px,2px);box-shadow:none}
.btn-outline{background:var(--paper);color:var(--ink)}
.btn-outline:hover{background:var(--sand);color:var(--ink)}
.help{margin-top:18px;font-size:14px;color:var(--muted)}
.help a{font-weight:700;text-underline-offset:3px}

.foot{text-align:center;font-size:14px;color:var(--muted)}

@media (max-width:860px){
  main{grid-template-columns:1fr;gap:28px;padding:24px 0}
  .scene{order:0;padding-top:88px}
  .shelf{min-height:0;justify-content:flex-start;padding-left:4px}
  .bubble{top:-88px;right:auto;left:0;max-width:280px}
  .bubble::after{right:auto;left:auto;right:40%;top:auto;bottom:-10px;transform:rotate(45deg)}
  .hint{text-align:left}
}
@media (max-width:420px){
  .shelf{gap:8px}
  .box{width:62px;font-size:40px}
  .ruru{width:104px}
}
@media (prefers-reduced-motion:reduce){*{animation:none!important;transition:none!important}}
</style>
</head>
<body>
<div class="page">
  <header class="top">
    <a class="logo" href="{{ url('/') }}" aria-label="RuangTitip, ke beranda"><img src="{{ asset('assets/logo-ruangtitip-putih.svg') }}" alt="RuangTitip" width="160" height="40" style="height:40px;width:auto"></a>
  </header>

  <main>
    <div class="scene">
      <div class="shelf">
        <div class="box b1" aria-hidden="true"><span>4</span></div>
        <div class="box b2" aria-hidden="true"><span>0</span></div>
        <div class="box b3" aria-hidden="true"><span>4</span></div>
        <p class="bubble" id="bubble" aria-live="polite">Hmm, rak ini kosong. Aku udah cari ke mana-mana&hellip;</p>
        <button class="ruru" type="button" id="ruru" aria-label="Tanya Ruru"><img src="{{ asset('assets/ruru/ruru-mencari.webp') }}" alt=""></button>
      </div>
      <p class="hint">Klik Ruru kalau mau ngobrol sebentar.</p>
    </div>

    <div>
      <span class="code">Error 404 &middot; Halaman tidak ditemukan</span>
      <h1>Rak ini <em>kosong.</em></h1>
      <p class="lead">Halaman yang kamu cari mungkin sudah dipindah, dihapus, atau alamatnya salah ketik.</p>

      <div class="info">
        <dl>
          <div><dt>Alamat</dt><dd id="url">&ndash;</dd></div>
          <div><dt>Waktu</dt><dd id="time">&ndash;</dd></div>
        </dl>
      </div>

      <div class="actions">
        <a class="btn btn-primary" href="{{ url('/') }}">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M3 11 12 3l9 8"/><path d="M5 10v10h14V10"/></svg>
          Ke beranda
        </a>
        <button class="btn btn-outline" type="button" id="back">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5"/><path d="m12 19-7-7 7-7"/></svg>
          Halaman sebelumnya
        </button>
      </div>
      <p class="help">Yakin alamatnya benar? <a id="wa" href="https://wa.me/6285121091134" target="_blank" rel="noopener">Kabari kami via WhatsApp</a></p>
    </div>
  </main>

  <p class="foot">&copy; {{ date('Y') }} RuangTitip &middot; <a href="mailto:ruangtitipmu@gmail.com">ruangtitipmu@gmail.com</a></p>
</div>

<script>
(function () {
  var $ = function (x) { return document.getElementById(x); };
  var path = location.pathname + location.search;
  $('url').textContent = path.length > 60 ? path.slice(0, 57) + '…' : path;
  var now = new Date();
  var tz = -now.getTimezoneOffset() / 60, zone = tz === 7 ? 'WIB' : tz === 8 ? 'WITA' : tz === 9 ? 'WIT' : 'GMT' + (tz >= 0 ? '+' : '') + tz;
  $('time').textContent = now.toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' }) + ', ' +
    now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' }).replace('.', ':') + ' ' + zone;
  $('wa').href = 'https://wa.me/6285121091134?text=' + encodeURIComponent('Halo RuangTitip, halaman ' + location.href + ' tidak ditemukan (404).');

  $('back').addEventListener('click', function () {
    if (history.length > 1 && document.referrer) history.back(); else location.href = '/';
  });

  var lines = [
    'Hmm, rak ini kosong. Aku udah cari ke mana-mana…',
    'Sudah kucek di gudang A sampai Z. Nihil!',
    'Mungkin halamannya ikut pulang kampung?',
    'Coba lewat beranda, jalannya lebih jelas.',
    'Kardus nomor 0 ini memang dari dulu kosong, sih.',
    'Tenang, barang titipanmu aman kok. Cuma halamannya yang hilang.'
  ];
  var i = 0;
  $('ruru').addEventListener('click', function () {
    i = (i + 1) % lines.length;
    $('bubble').textContent = lines[i];
    var r = this; r.style.animation = 'none'; r.offsetWidth;
    r.animate([{ transform: 'translateY(0)' }, { transform: 'translateY(-14px)' }, { transform: 'translateY(0)' }], { duration: 320, easing: 'ease-out' });
    setTimeout(function () { r.style.animation = ''; }, 330);
  });
})();
</script>
</body>
</html>
