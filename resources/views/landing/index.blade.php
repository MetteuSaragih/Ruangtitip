<!doctype html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>RuangTitip · Titip barang mahasiswa di Malang</title>
<meta name="description" content="Pulang kampung, magang, atau pindah kos? Titip barangmu di RuangTitip. Kami jemput dari kos, simpan di gudang aman, dan antar balik saat kamu kembali.">
<link rel="icon" type="image/svg+xml" href="{{ asset('assets/favicon.svg') }}">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,700;12..96,800&family=Figtree:wght@400;500;600;700&display=swap">
<style>
/* ================= Palet RuangTitip ================= */
:root{
  --ink:#1C1B18;
  --cream:#F5F1E8;
  --paper:#FFFDF8;
  --sand:#E8DFCD;
  --tape:#B4531D;
  --tape-dark:#9A4415;
  --tape-light:#E8A677;
  --depot:#2E5A45;
  --depot-light:#EAF1EC;
  --body:#4F4A40;
  --muted:#5C574D;
  --line:#DDD5C4;
  --line-strong:#CFC6B3;
  --font-display:'Bricolage Grotesque','Figtree',system-ui,sans-serif;
  --font-body:'Figtree',system-ui,sans-serif;
  --wrap:1200px;
  --gutter:24px;
}

/* ================= Dasar ================= */
*,*::before,*::after{box-sizing:border-box}
html{scroll-behavior:smooth}
body{margin:0;font-family:var(--font-body);background:var(--cream);color:var(--ink);font-size:17px;line-height:1.6;-webkit-font-smoothing:antialiased}
img{max-width:100%;display:block}
a{color:var(--tape-dark)}
a:hover{color:#7A3510}
h1,h2,h3{font-family:var(--font-display);margin:0;line-height:1.05}
p{margin:0}
button{font:inherit}
:focus-visible{outline:3px solid var(--tape);outline-offset:3px}
.wrap{max-width:calc(var(--wrap) + var(--gutter)*2);margin:0 auto;padding:0 var(--gutter)}
.section{padding:104px 0}
.eyebrow{font-size:14px;font-weight:700;color:var(--depot);letter-spacing:.3px}
.h2{font-size:clamp(36px,4.6vw,56px);letter-spacing:-1.6px;font-weight:800}
.h2-sm{font-size:clamp(32px,3.6vw,44px);letter-spacing:-1.2px;font-weight:800}
.lead{color:var(--body);font-size:18px}
.skip{position:absolute;left:-999px;top:8px;background:var(--ink);color:var(--cream);padding:10px 16px;border-radius:8px;z-index:100}
.skip:focus{left:8px}

/* Tombol */
.btn{display:inline-flex;align-items:center;justify-content:center;min-height:48px;padding:0 26px;border-radius:999px;font-weight:700;font-size:16px;text-decoration:none;border:0;cursor:pointer;transition:transform .15s ease,background .15s ease}
.btn:hover{transform:translateY(-1px)}
.btn-primary{background:var(--tape);color:#fff}
.btn-primary:hover{background:var(--tape-dark);color:#fff}
.btn-dark{background:var(--ink);color:var(--cream)}
.btn-dark:hover{background:#000;color:var(--cream)}
.btn-light{background:var(--tape-light);color:var(--ink)}
.btn-light:hover{background:#F0B98F;color:var(--ink)}
.link-under{color:var(--ink);font-weight:600;text-decoration:underline;text-underline-offset:4px;padding:12px 8px}
.link-under:hover{color:var(--tape)}

/* ================= Navbar ================= */
.nav{position:sticky;top:0;z-index:50;background:rgba(245,241,232,.94);backdrop-filter:saturate(1.2) blur(8px);border-bottom:1px solid var(--line)}
.nav-in{display:flex;align-items:center;justify-content:space-between;height:76px;gap:24px}
.brand img{height:40px;width:auto}
.nav-links{display:flex;gap:36px;list-style:none;margin:0;padding:0}
.nav-links a{color:var(--ink);text-decoration:none;font-weight:500;font-size:15px}
.nav-links a:hover{color:var(--tape)}
.nav-cta{display:flex;align-items:center;gap:8px}
.nav-cta .btn{min-height:44px;font-size:15px;padding:0 20px}
.menu-btn{display:none;width:44px;height:44px;border-radius:10px;border:1px solid var(--line-strong);background:var(--paper);align-items:center;justify-content:center;cursor:pointer}
.mobile-menu{display:none}

/* ================= Hero ================= */
.hero{padding:80px 0 96px}
.hero-grid{display:grid;grid-template-columns:1.05fr 1fr;gap:48px;align-items:center}
.hero-copy{display:flex;flex-direction:column;gap:26px}
.hero h1{font-size:clamp(44px,6vw,76px);letter-spacing:-2.4px;line-height:.98;font-weight:800}
.hero .lead{font-size:clamp(17px,1.6vw,20px);max-width:520px;line-height:1.55}
.hero-actions{display:flex;flex-wrap:wrap;gap:10px;align-items:center}
.hero-actions .btn{min-height:54px;font-size:17px;padding:0 28px}
.stats{display:flex;flex-wrap:wrap;gap:40px;padding-top:22px;border-top:1px solid var(--line);margin:0}
.stats div{display:flex;flex-direction:column}
.stats dt{order:2;font-size:14px;color:var(--muted)}
.stats dd{order:1;margin:0;font-family:var(--font-display);font-size:34px;font-weight:800;line-height:1.1}

.hero-art{position:relative;min-height:600px}
.hero-art .disc{position:absolute;right:0;bottom:28px;width:min(500px,100%);aspect-ratio:1;border-radius:50%;background:var(--sand)}
.hero-art .shadow{position:absolute;right:12%;bottom:24px;width:60%;height:32px;border-radius:50%;background:var(--ink);opacity:.12}
.hero-art .ruru{position:absolute;right:8%;bottom:42px;width:min(400px,78%);height:auto;animation:bob 4s ease-in-out infinite}
@keyframes bob{0%,100%{transform:translateY(0)}50%{transform:translateY(-8px)}}
.bubble{position:absolute;top:0;left:0;background:var(--paper);border:1.5px solid var(--ink);border-radius:20px;box-shadow:6px 6px 0 var(--ink);padding:18px 26px}
.bubble strong{display:block;font-family:var(--font-display);font-size:28px;font-weight:800;letter-spacing:-.5px;white-space:nowrap;transition:opacity .2s ease}
.bubble::after{content:"";position:absolute;right:48px;bottom:-11px;width:20px;height:20px;background:var(--paper);border-right:1.5px solid var(--ink);border-bottom:1.5px solid var(--ink);transform:rotate(45deg)}
.chip-ok{position:absolute;left:4%;bottom:110px;display:flex;align-items:center;gap:10px;padding:12px 18px;background:var(--paper);border:1px solid var(--line-strong);border-radius:999px;font-size:15px;font-weight:600}
.chip-ok .dot{width:26px;height:26px;border-radius:50%;background:var(--depot);display:grid;place-items:center;flex-shrink:0}

/* ================= Cara kerja ================= */
.head-row{display:flex;justify-content:space-between;align-items:flex-end;gap:48px;margin-bottom:56px}
.head-row .h2{max-width:620px}
.head-row .lead{max-width:420px}
.steps{display:grid;grid-template-columns:repeat(3,1fr);border-top:1.5px solid var(--ink);list-style:none;margin:0;padding:0}
.steps li{padding:32px 40px 8px;border-right:1px solid var(--line)}
.steps li:first-child{padding-left:0}
.steps li:last-child{border-right:0;padding-right:0}
.steps .num{font-family:var(--font-display);font-size:22px;font-weight:800;color:var(--tape)}
.steps h3{font-size:28px;font-weight:700;letter-spacing:-.5px;margin:12px 0}
.steps p{color:var(--body)}

/* ================= Layanan ================= */
.services{display:grid;grid-template-columns:4fr 8fr;gap:48px;padding-top:0}
.services-intro{display:flex;flex-direction:column;gap:20px}
.photo-ph{height:260px;border:1.5px dashed #B8AE98;border-radius:12px;display:grid;place-items:center;text-align:center;color:#6B6557;font-size:14px;padding:24px}
.svc{list-style:none;margin:0;padding:0;border-top:1.5px solid var(--ink)}
.svc li{display:grid;grid-template-columns:220px 1fr;gap:24px;padding:24px 0;border-bottom:1px solid var(--line)}
.svc h3{font-size:22px;font-weight:700}
.svc p{color:var(--body)}

/* ================= Survei ================= */
.survey-card{background:var(--paper);border:1.5px solid var(--ink);border-radius:16px;display:grid;grid-template-columns:5fr 7fr;gap:48px;padding:56px 64px}
.survey-intro{display:flex;flex-direction:column;gap:16px}
.survey-intro small{font-size:14px;color:#6B6557}
fieldset{border:0;margin:0 0 26px;padding:0}
legend{font-size:19px;font-weight:700;padding:0;margin-bottom:14px}
legend span{font-weight:500;color:#6B6557;font-size:15px}
.opt-list{display:flex;flex-direction:column;gap:10px}
.opt{display:flex;justify-content:space-between;align-items:center;width:100%;text-align:left;font-size:17px;font-weight:600;min-height:56px;padding:0 20px;border-radius:10px;border:1.5px solid var(--line-strong);background:var(--paper);color:var(--ink);cursor:pointer;transition:border-color .15s,background .15s}
.opt small{font-size:14px;font-weight:500;opacity:.85}
.opt:hover{border-color:var(--ink)}
.chips{display:flex;flex-wrap:wrap;gap:10px}
.chip{min-height:44px;padding:0 18px;border-radius:999px;font-size:15px;font-weight:600;border:1.5px solid var(--line-strong);background:var(--paper);color:var(--ink);cursor:pointer;transition:border-color .15s,background .15s}
.chip:hover{border-color:var(--ink)}
.opt[aria-pressed="true"],.chip[aria-pressed="true"]{background:var(--tape);border-color:var(--tape);color:#fff}
.step2{display:none}
.step2.show{display:block}
.send-row{display:flex;align-items:center;gap:20px;flex-wrap:wrap}
.btn-send{min-height:52px}
.btn-send:disabled{background:#9C9585;cursor:not-allowed;transform:none}
.send-row small{font-size:14px;color:#6B6557}
.thanks{display:none;gap:24px;align-items:center;padding:32px;background:var(--depot-light);border-radius:12px;border:1.5px solid var(--depot)}
.thanks.show{display:flex}
.thanks img{width:110px;height:auto;flex-shrink:0}
.thanks h3{font-size:28px;font-weight:800;color:#1F4535;margin-bottom:10px}
.thanks p{color:#2F3B34;margin-bottom:16px}
.thanks .row{display:flex;gap:12px;align-items:center;flex-wrap:wrap}
.btn-text{background:none;border:0;min-height:44px;font-weight:600;text-decoration:underline;text-underline-offset:4px;cursor:pointer;color:var(--ink)}

/* ================= Testimoni ================= */
.testi{display:grid;grid-template-columns:1fr 1fr;gap:32px;margin-top:48px}
.testi figure{margin:0;display:flex;flex-direction:column;justify-content:space-between;gap:24px;border-radius:16px}
.testi .main{background:var(--sand);padding:40px}
.testi .main blockquote{font-family:var(--font-display);font-size:clamp(24px,2.4vw,30px);font-weight:700;line-height:1.25;letter-spacing:-.5px}
.testi .side{display:flex;flex-direction:column;gap:32px}
.testi .side figure{border:1px solid var(--line-strong);padding:32px;flex-grow:1}
.testi blockquote{margin:0;font-size:19px;line-height:1.55}
.testi figcaption{font-size:15px;font-weight:700}

/* ================= FAQ ================= */
.faq{display:grid;grid-template-columns:4fr 8fr;gap:48px}
.faq-intro{display:flex;flex-direction:column;gap:16px}
.faq-list{border-top:1.5px solid var(--ink)}
.faq-list details{border-bottom:1px solid var(--line)}
.faq-list summary{list-style:none;cursor:pointer;display:flex;justify-content:space-between;align-items:center;gap:16px;padding:22px 0;font-family:var(--font-display);font-size:21px;font-weight:700}
.faq-list summary::-webkit-details-marker{display:none}
.faq-list summary .ic{width:28px;height:28px;border-radius:50%;border:1.5px solid var(--ink);display:grid;place-items:center;flex-shrink:0;transition:transform .2s}
.faq-list details[open] summary .ic{transform:rotate(45deg);background:var(--ink);color:var(--cream)}
.faq-list details p{color:var(--body);padding:0 44px 22px 0}

/* ================= Tim ================= */
.team{display:grid;grid-template-columns:5fr 7fr;gap:48px;align-items:start}
.team-intro{display:flex;flex-direction:column;gap:16px}
.team-list{display:grid;grid-template-columns:repeat(3,1fr);gap:24px;list-style:none;margin:0;padding:0}
.team-list .ph{aspect-ratio:1;border-radius:12px;background:var(--sand);display:grid;place-items:center;font-size:13px;color:#6B6557;margin-bottom:12px;overflow:hidden}
.team-list .ph img{width:100%;height:100%;object-fit:cover}
.team-list strong{display:block;font-size:17px}
.team-list span{font-size:14px;color:var(--muted)}

/* ================= Footer ================= */
.footer{background:var(--ink);color:var(--cream);padding:88px 0 48px}
.cta-row{display:flex;justify-content:space-between;align-items:flex-end;gap:48px;margin-bottom:72px}
.cta-row h2{font-size:clamp(38px,5vw,64px);letter-spacing:-2px;font-weight:800;line-height:1;max-width:760px}
.cta-row .btn{min-height:58px;font-size:17px;padding:0 30px;white-space:nowrap}
.foot-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:32px;padding-top:40px;border-top:1px solid #3A3833;font-size:15px}
.foot-grid h3{font-family:var(--font-body);font-size:15px;font-weight:700;margin-bottom:12px}
.foot-grid ul{list-style:none;margin:0;padding:0;display:flex;flex-direction:column;gap:10px}
.foot-grid a{color:#D6D0C3;text-decoration:none}
.foot-grid a:hover{color:var(--tape-light)}
.foot-brand img{height:40px;width:auto;margin-bottom:14px}
.foot-brand p{color:#BDB6A7}
.foot-bottom{display:flex;justify-content:space-between;gap:16px;flex-wrap:wrap;margin-top:48px;font-size:13px;color:#A39C8D}
.foot-bottom a{color:#A39C8D}

/* ================= Responsif ================= */
@media (max-width:1024px){
  .section{padding:80px 0}
  .nav-links{display:none}
  .menu-btn{display:inline-flex}
  .mobile-menu.open{display:block;border-top:1px solid var(--line);padding:12px 0 20px}
  .mobile-menu ul{list-style:none;margin:0;padding:0}
  .mobile-menu a{display:block;padding:14px 0;color:var(--ink);text-decoration:none;font-weight:600;border-bottom:1px solid var(--line)}
  .hero-grid{grid-template-columns:1fr}
  .hero-art{min-height:520px;max-width:560px;width:100%;margin:0 auto}
  .services,.faq,.team{grid-template-columns:1fr}
  .head-row{flex-direction:column;align-items:flex-start;gap:16px}
  .steps{grid-template-columns:1fr}
  .steps li,.steps li:first-child,.steps li:last-child{padding:28px 0;border-right:0;border-bottom:1px solid var(--line)}
  .survey-card{grid-template-columns:1fr;gap:32px;padding:40px 32px}
  .testi{grid-template-columns:1fr}
  .cta-row{flex-direction:column;align-items:flex-start}
  .foot-grid{grid-template-columns:1fr 1fr}
}
@media (max-width:640px){
  body{font-size:16px}
  .section{padding:64px 0}
  .hero{padding:48px 0 64px}
  .nav-cta .btn{display:none}
  .brand img{height:34px}
  .hero-art{min-height:420px}
  .bubble strong{font-size:22px}
  .bubble{padding:14px 20px}
  .chip-ok{font-size:14px;bottom:80px}
  .stats{gap:24px}
  .svc li{grid-template-columns:1fr;gap:6px}
  .survey-card{padding:32px 20px}
  .thanks{flex-direction:column;align-items:flex-start}
  .testi .main{padding:28px}
  .testi .side figure{padding:24px}
  .team-list{grid-template-columns:1fr 1fr}
  .foot-grid{grid-template-columns:1fr}
}
@media (prefers-reduced-motion:reduce){
  html{scroll-behavior:auto}
  .hero-art .ruru{animation:none}
  .btn:hover{transform:none}
}
</style>
</head>
<body>
<a class="skip" href="#konten">Lewati ke konten</a>

@include('landing.partials.navbar')

<main id="konten">
    @include('landing.partials.hero')
    @include('landing.partials.how-it-works')
    @include('landing.partials.solution')
    @include('landing.partials.survey')
    @include('landing.partials.testimoni')
    @include('landing.partials.faq')
    @include('landing.partials.tentang-kami')
</main>

@include('landing.partials.footer')

<script>
(function () {
  /* ---------- Menu mobile ---------- */
  var menuBtn = document.querySelector('.menu-btn');
  var menu = document.getElementById('mobile-menu');
  menuBtn.addEventListener('click', function () {
    var open = menu.classList.toggle('open');
    menuBtn.setAttribute('aria-expanded', open ? 'true' : 'false');
    menuBtn.setAttribute('aria-label', open ? 'Tutup menu' : 'Buka menu');
  });
  menu.querySelectorAll('a').forEach(function (a) {
    a.addEventListener('click', function () {
      menu.classList.remove('open');
      menuBtn.setAttribute('aria-expanded', 'false');
    });
  });

  /* ---------- Bubble Ruru ---------- */
  var bubbleText = document.getElementById('ruru-bubble-text');
  if (bubbleText) {
    var bubbleMessages = ['Hii, aku Ruru!', 'Selamat datang di RuangTitip!'];
    var bubbleIdx = 0;
    setInterval(function () {
      bubbleIdx = (bubbleIdx + 1) % bubbleMessages.length;
      bubbleText.style.opacity = '0';
      setTimeout(function () {
        bubbleText.textContent = bubbleMessages[bubbleIdx];
        bubbleText.style.opacity = '1';
      }, 200);
    }, 3000);
  }

  /* ---------- Survei ---------- */
  var answers = { minat: null, layanan: [], harga: null };
  var form = document.getElementById('survey-form');
  var step2 = document.getElementById('step2');
  var sendBtn = document.getElementById('send-btn');
  var helper = document.getElementById('helper');
  var thanks = document.getElementById('thanks');

  document.querySelectorAll('[data-group]').forEach(function (group) {
    var key = group.getAttribute('data-group');
    var single = group.hasAttribute('data-single');
    group.querySelectorAll('button').forEach(function (btn) {
      btn.addEventListener('click', function () {
        var val = btn.getAttribute('data-value');
        if (single) {
          group.querySelectorAll('button').forEach(function (b) { b.setAttribute('aria-pressed', 'false'); });
          btn.setAttribute('aria-pressed', 'true');
          answers[key] = val;
        } else {
          var on = btn.getAttribute('aria-pressed') !== 'true';
          btn.setAttribute('aria-pressed', on ? 'true' : 'false');
          answers[key] = on
            ? answers[key].concat(val)
            : answers[key].filter(function (v) { return v !== val; });
        }
        update();
      });
    });
  });

  function update() {
    var ready = answers.minat !== null;
    sendBtn.disabled = !ready;
    helper.textContent = ready ? 'Pertanyaan 2 dan 3 opsional.' : 'Pilih satu jawaban dulu.';
    step2.classList.toggle('show', ready && answers.minat !== 'tidak');
  }

  var copy = {
    tertarik: ['Makasih! Kamu masuk daftar prioritas.', 'Kami kabari lebih dulu soal promo penitip pertama. Mau langsung atur jadwal jemput? Chat kami.'],
    mungkin:  ['Makasih, masukanmu kami catat.', 'Jawaban soal harga ini bantu kami menyusun paket yang lebih pas. Ada pertanyaan? Chat kami.'],
    tidak:    ['Makasih sudah jujur.', 'Kalau nanti butuh tempat titip barang, Ruru ada di sini. Simpan saja halaman ini.']
  };

  form.addEventListener('submit', function (e) {
    e.preventDefault();
    if (!answers.minat) return;

    fetch('{{ route('survey.store') }}', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
      },
      body: JSON.stringify(answers)
    });

    document.getElementById('thanks-title').textContent = copy[answers.minat][0];
    document.getElementById('thanks-body').textContent = copy[answers.minat][1];
    form.style.display = 'none';
    thanks.classList.add('show');
  });
})();
</script>
</body>
</html>
