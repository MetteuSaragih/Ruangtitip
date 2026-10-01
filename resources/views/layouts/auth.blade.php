<!doctype html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>@yield('title', 'Masuk') &middot; RuangTitip</title>
<link rel="icon" type="image/svg+xml" href="{{ asset('assets/favicon.svg') }}">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,700;12..96,800&family=Figtree:wght@400;500;600;700&display=swap">
<style>
/* ================= Palet RuangTitip (sama dengan landing page) ================= */
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
  --danger:#A3321A;
  --danger-light:#F6E7E2;
  --font-display:'Bricolage Grotesque','Figtree',system-ui,sans-serif;
  --font-body:'Figtree',system-ui,sans-serif;
}
*,*::before,*::after{box-sizing:border-box}
body{margin:0;min-height:100vh;font-family:var(--font-body);background:var(--cream);color:var(--ink);font-size:16px;line-height:1.6;-webkit-font-smoothing:antialiased}
img{display:block;max-width:100%}
a{color:var(--tape-dark);font-weight:600}
a:hover{color:#7A3510}
h1,h2{font-family:var(--font-display);margin:0;line-height:1.05}
p{margin:0}
:focus-visible{outline:3px solid var(--tape);outline-offset:3px}

.page{display:grid;grid-template-columns:1.1fr 1fr;min-height:100vh}

/* ================= Panel kiri: Ruru ================= */
.side{position:relative;background:var(--sand);display:flex;flex-direction:column;justify-content:center;gap:40px;padding:40px 56px 48px;overflow:hidden;border-right:1.5px solid var(--ink)}
.stage{position:relative;display:flex;align-items:center;justify-content:center}
.art{position:relative;width:min(420px,100%);aspect-ratio:1}
.art .disc{position:absolute;inset:0;border-radius:50%;background:var(--cream);border:1.5px solid var(--ink)}
.art .shadow{position:absolute;left:50%;bottom:6%;transform:translateX(-50%);width:54%;height:24px;border-radius:50%;background:var(--ink);opacity:.14}
.art .ruru{position:absolute;left:50%;bottom:8%;transform:translateX(-50%);width:68%;height:auto;animation:bob 4s ease-in-out infinite}
@keyframes bob{0%,100%{transform:translateX(-50%) translateY(0)}50%{transform:translateX(-50%) translateY(-8px)}}
.bubble{position:absolute;top:-4%;left:-26%;background:var(--paper);border:1.5px solid var(--ink);border-radius:20px;box-shadow:6px 6px 0 var(--ink);padding:16px 22px;width:290px}
.bubble strong{display:block;font-family:var(--font-display);font-size:24px;font-weight:800;letter-spacing:-.4px;line-height:1.15;min-height:1.15em}
.bubble span{display:block;font-size:15px;color:var(--body);margin-top:4px;min-height:1.4em}
.bubble .caret{display:inline-block;width:2px;height:1em;background:currentColor;margin-left:2px;vertical-align:-2px;animation:caret-blink .8s step-end infinite}
@keyframes caret-blink{0%,100%{opacity:1}50%{opacity:0}}
.bubble::after{content:"";position:absolute;right:44px;bottom:-11px;width:20px;height:20px;background:var(--paper);border-right:1.5px solid var(--ink);border-bottom:1.5px solid var(--ink);transform:rotate(45deg)}
.tag{position:absolute;display:flex;align-items:center;gap:8px;padding:10px 16px;background:var(--paper);border:1px solid var(--line-strong);border-radius:999px;font-size:14px;font-weight:600;white-space:nowrap;box-shadow:0 4px 12px rgba(28,27,24,.06)}
.tag .dot{width:22px;height:22px;border-radius:50%;display:grid;place-items:center;flex-shrink:0}
.tag-1{right:-34%;top:40%}
.tag-1 .dot{background:var(--depot)}
.tag-2{left:-10%;bottom:14%}
.tag-2 .dot{background:var(--tape)}
.tagline{text-align:center}
.tagline h2{font-size:clamp(30px,3vw,40px);font-weight:800;letter-spacing:-1.3px}
.tagline h2 em{font-style:normal;color:var(--tape)}
.tagline p{color:var(--body);margin:10px auto 0;max-width:420px}

/* ================= Panel kanan: form ================= */
.main{display:flex;flex-direction:column;justify-content:center;align-items:center;padding:48px 24px}
.card{width:100%;max-width:420px}
.mobile-head{display:none}
.card h1{font-size:clamp(30px,3vw,38px);font-weight:800;letter-spacing:-1.1px}
.card .sub{color:var(--body);margin:10px 0 32px}

.field{display:flex;flex-direction:column;gap:8px;margin-bottom:20px}
.field label{font-weight:700;font-size:15px}
.input{position:relative}
.input svg{position:absolute;left:16px;top:50%;transform:translateY(-50%);color:var(--muted);pointer-events:none}
.input input{width:100%;height:54px;padding:0 16px 0 48px;border-radius:12px;border:1.5px solid var(--line-strong);background:var(--paper);font:inherit;font-size:16px;color:var(--ink);transition:border-color .15s,box-shadow .15s}
.input input::placeholder{color:#8A8374}
.input input:hover{border-color:#B8AE98}
.input input:focus{outline:none;border-color:var(--ink);box-shadow:4px 4px 0 var(--ink)}
.input input[aria-invalid="true"]{border-color:var(--danger)}
.error{display:none;font-size:14px;color:var(--danger);font-weight:600}
.error.show{display:block}

.alert{border-radius:12px;padding:12px 16px;margin-bottom:20px;font-size:14px;font-weight:600}
.alert-success{background:var(--depot-light);color:#1F4535;border:1.5px solid var(--depot)}
.alert-error{background:var(--danger-light);color:var(--danger);border:1.5px solid var(--danger)}

.btn{display:flex;align-items:center;justify-content:center;gap:10px;width:100%;min-height:54px;border-radius:999px;font:inherit;font-weight:700;font-size:16px;text-decoration:none;cursor:pointer;border:0;transition:transform .15s,background .15s}
.btn:hover{transform:translateY(-1px)}
.btn:disabled{opacity:.55;cursor:not-allowed;transform:none}
.btn-primary{background:var(--tape);color:#fff;border:1.5px solid var(--ink);box-shadow:4px 4px 0 var(--ink)}
.btn-primary:hover{background:var(--tape-dark);color:#fff}
.btn-primary:active{transform:translate(2px,2px);box-shadow:2px 2px 0 var(--ink)}
.btn-google{background:var(--paper);color:var(--ink);border:1.5px solid var(--line-strong)}
.btn-google:hover{border-color:var(--ink);color:var(--ink)}

.divider{display:flex;align-items:center;gap:14px;margin:28px 0;color:var(--muted);font-size:14px}
.divider::before,.divider::after{content:"";flex:1;height:1px;background:var(--line)}

.switch{margin-top:32px;text-align:center;color:var(--body)}
.legal{margin-top:40px;font-size:13px;color:var(--muted);text-align:center}
.legal a{color:var(--muted);font-weight:500}
.back{position:absolute;top:28px;right:32px;font-size:14px;color:var(--ink);text-decoration:none;display:flex;align-items:center;gap:6px;font-weight:600}
.back:hover{color:var(--tape)}
.back-inline{display:inline-flex;align-items:center;gap:6px;font-size:14px;color:var(--muted);text-decoration:none;font-weight:600;margin-bottom:28px}
.back-inline:hover{color:var(--tape)}

/* ================= OTP boxes ================= */
.otp-boxes{display:flex;gap:10px;justify-content:center;margin-bottom:16px}
.otp-box{width:48px;height:56px;text-align:center;font-size:20px;font-weight:800;font-family:var(--font-display);color:var(--ink);border-radius:12px;border:1.5px solid var(--line-strong);background:var(--paper);outline:none;transition:border-color .15s,box-shadow .15s,background .15s}
.otp-box:focus{border-color:var(--ink);box-shadow:4px 4px 0 var(--ink)}
.otp-filled{background:var(--tape-light);border-color:var(--tape)}
.otp-hint{font-size:13px;color:var(--muted);text-align:center;margin-bottom:28px}
.otp-hint strong{color:var(--ink)}
.resend{text-align:center}
.resend-btn{background:none;border:0;font:inherit;display:inline-flex;align-items:center;gap:6px;font-size:14px;font-weight:700;color:var(--tape-dark);cursor:pointer}
.resend-btn:disabled{color:var(--muted);cursor:not-allowed}

/* ================= Responsif ================= */
@media (max-width:960px){
  .page{grid-template-columns:1fr}
  .side{display:none}
  .main{justify-content:flex-start;padding:24px 20px 48px}
  .back{position:static;align-self:flex-start;margin-bottom:24px}
  .mobile-head{display:flex;flex-direction:column;gap:20px;margin-bottom:28px}
  .mobile-head .logo img{height:36px;width:auto}
  .mini{display:flex;align-items:flex-end;gap:12px;padding:16px;background:var(--sand);border:1.5px solid var(--ink);border-radius:16px}
  .mini img{width:78px;height:auto;flex-shrink:0}
  .mini .say{background:var(--paper);border:1.5px solid var(--ink);border-radius:14px;padding:10px 14px;font-weight:700;font-family:var(--font-display);font-size:18px;line-height:1.2;margin-bottom:24px}
}
@media (prefers-reduced-motion:reduce){
  .art .ruru{animation:none}
  .btn:hover{transform:none}
}
</style>
@stack('styles')
</head>
<body>
<div class="page">

  {{-- ================= Panel kiri ================= --}}
  <aside class="side" aria-label="RuangTitip">
    <div class="stage">
      <div class="art">
        <div class="disc" aria-hidden="true"></div>
        <div class="shadow" aria-hidden="true"></div>
        <img class="ruru" src="{{ asset('assets/ruru/ruru-halo.webp') }}" alt="Ruru, maskot RuangTitip, melambaikan tangan" width="800" height="800">
        <div class="bubble">
          <strong id="bubble-title">@yield('bubble-title', 'Hii, ketemu lagi!')</strong>
          <span id="bubble-text">@yield('bubble-text', 'Barangmu aman sama aku. Yuk masuk dulu.')</span>
        </div>
        <div class="tag tag-1">
          <span class="dot" aria-hidden="true"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="3.2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg></span>
          Tersimpan di gudang
        </div>
        <div class="tag tag-2">
          <span class="dot" aria-hidden="true"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M3 7h11v10H3zM14 10h4l3 3v4h-7"/><circle cx="7" cy="18" r="1.5"/><circle cx="17" cy="18" r="1.5"/></svg></span>
          Dijemput dari kos
        </div>
      </div>
    </div>

    <div class="tagline">
      <h2>Titip barangmu, <em>simpan uangmu.</em></h2>
      <p>Nggak perlu bayar kos kosong selama libur. Pantau barang titipanmu dari mana saja.</p>
    </div>
  </aside>

  {{-- ================= Panel kanan ================= --}}
  <main class="main" style="position:relative">
    <a class="back" href="{{ route('home') }}">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5M11 18l-6-6 6-6"/></svg>
      Kembali ke beranda
    </a>

    <div class="card">
      {{-- Hanya tampil di HP --}}
      <div class="mobile-head">
        <a class="logo" href="{{ route('home') }}" aria-label="RuangTitip, ke beranda"><img src="{{ asset('assets/logo-ruangtitip.svg') }}" alt="RuangTitip" width="220" height="56"></a>
        <div class="mini">
          <img src="{{ asset('assets/ruru/ruru-halo.webp') }}" alt="" width="78" height="78">
          <p class="say">@yield('bubble-title', 'Hii, ketemu lagi!')</p>
        </div>
      </div>

      @yield('content')
    </div>
  </main>
</div>

<script>
(function () {
  var titleEl = document.getElementById('bubble-title');
  var textEl = document.getElementById('bubble-text');
  if (!titleEl || !textEl) return;

  var titleFull = titleEl.textContent.trim();
  var textFull = textEl.textContent.trim();

  /* Lock each line's final height (with the full text still rendered)
     so wrapping doesn't shift the bubble while typing. */
  titleEl.style.minHeight = titleEl.offsetHeight + 'px';
  textEl.style.minHeight = textEl.offsetHeight + 'px';

  titleEl.textContent = '';
  textEl.textContent = '';

  function typeOut(el, text, speed, done) {
    var caret = document.createElement('span');
    caret.className = 'caret';
    el.appendChild(caret);
    var i = 0;
    var iv = setInterval(function () {
      caret.insertAdjacentText('beforebegin', text[i]);
      i++;
      if (i >= text.length) {
        clearInterval(iv);
        caret.remove();
        if (done) done();
      }
    }, speed);
  }

  setTimeout(function () {
    typeOut(titleEl, titleFull, 45, function () {
      setTimeout(function () { typeOut(textEl, textFull, 18); }, 150);
    });
  }, 300);
})();
</script>

@stack('scripts')
</body>
</html>
