<!doctype html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="robots" content="noindex">
<title>Terjadi kesalahan &middot; RuangTitip</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,700;12..96,800&family=Figtree:wght@400;500;600;700&display=swap">
<style>
:root{
  --ink:#E6F5F3;--deep:#0C2B2A;--cream:#0A2423;--paper:#11332F;--sand:#163C38;--kraft:#C9A27A;
  --tape:#FFC93C;--tape-dark:#FFD86B;--tape-light:#FFC93C;--tape-soft:#1E3B37;
  --depot:#2DD4BF;--depot-light:#173F2A;--danger:#F08A80;
  --body:#A5C6C2;--muted:#A5C6C2;--line:#24504B;--line-strong:#24504B;
  --font-display:'Bricolage Grotesque','Figtree',system-ui,sans-serif;
  --font-body:'Figtree',system-ui,sans-serif;
}
*,*::before,*::after{box-sizing:border-box}
html{overflow-x:hidden}
body{margin:0;min-height:100vh;font-family:var(--font-body);background:var(--cream);color:var(--ink);font-size:16px;line-height:1.6;-webkit-font-smoothing:antialiased}
img,svg{display:block;max-width:100%}
a{color:var(--tape-dark)}
h1,h2{font-family:var(--font-display);margin:0;line-height:1.05}
p{margin:0}
button{font:inherit;color:inherit}
:focus-visible{outline:3px solid var(--tape);outline-offset:3px}

.page{max-width:1080px;margin:0 auto;padding:28px 24px 56px}
.top{display:flex;justify-content:space-between;align-items:center}
.top .logo svg{height:40px;width:auto}

.hero{display:grid;grid-template-columns:1fr 1.1fr;gap:40px;align-items:center;margin-top:48px}

/* Ruru yang "tertimpa" kardus */
.scene{position:relative;display:flex;justify-content:center;align-items:flex-end;min-height:420px}
.scene .disc{position:absolute;bottom:26px;width:min(380px,90%);aspect-ratio:1;border-radius:50%;background:var(--sand);border:2px solid var(--ink)}
.scene .ruru{position:relative;width:min(260px,62vw);margin-bottom:40px;transform:rotate(-9deg);transform-origin:50% 100%;animation:wobble 3s ease-in-out infinite}
@keyframes wobble{0%,100%{transform:rotate(-9deg)}50%{transform:rotate(-4deg)}}
.scene .shadow{position:absolute;bottom:30px;width:220px;height:22px;border-radius:50%;background:var(--ink);opacity:.14}
.fallen{position:absolute;width:74px;height:56px;background:var(--kraft);border:2px solid var(--ink);border-radius:5px}
.fallen::before{content:"";position:absolute;left:50%;top:-2px;bottom:-2px;width:12px;transform:translateX(-50%);background:var(--tape)}
.fallen.f1{left:10%;bottom:34px;transform:rotate(-14deg)}
.fallen.f2{right:8%;bottom:34px;width:60px;height:46px;transform:rotate(11deg)}
.fallen.f3{right:18%;bottom:74px;width:52px;height:40px;transform:rotate(-24deg)}
.stars{position:absolute;top:18%;left:50%;transform:translateX(-10%);font-family:var(--font-display);font-weight:800;color:var(--tape);font-size:30px;letter-spacing:6px;animation:spin 2.4s linear infinite}
@keyframes spin{50%{transform:translateX(-10%) rotate(10deg) scale(1.1)}}

.code{display:inline-flex;align-items:center;gap:8px;padding:6px 14px;border-radius:999px;background:var(--tape-soft);border:1.5px solid var(--tape);color:var(--tape-dark);font-weight:700;font-size:14px}
h1{font-size:clamp(40px,5vw,62px);font-weight:800;letter-spacing:-2px;margin-top:16px}
h1 em{font-style:normal;color:var(--tape)}
.lead{font-size:18px;color:var(--body);margin-top:14px;max-width:500px}
.safe{display:flex;gap:12px;align-items:flex-start;margin-top:20px;padding:14px 16px;background:var(--depot-light);border:1.5px solid var(--depot);border-radius:14px;color:#1F4535;font-weight:600;font-size:15px;max-width:500px}
.safe svg{flex-shrink:0;margin-top:2px}
.actions{display:flex;gap:10px;flex-wrap:wrap;margin-top:24px}
.btn{display:inline-flex;align-items:center;justify-content:center;gap:8px;min-height:50px;padding:0 22px;border-radius:999px;font-weight:700;font-size:15px;text-decoration:none;border:2px solid var(--ink);cursor:pointer;white-space:nowrap;transition:transform .12s}
.btn-primary{background:var(--tape);color:var(--deep);box-shadow:none}
.btn-primary:hover{background:var(--tape-dark);color:var(--deep)}
.btn-primary:active{transform:translate(2px,2px);box-shadow:none}
.btn-outline{background:var(--paper);color:var(--ink)}
.btn-outline:hover{background:var(--sand);color:var(--ink)}
.ref{margin-top:18px;font-size:13px;color:var(--muted)}
.ref code{font-family:ui-monospace,Menlo,monospace;background:var(--paper);border:1px solid var(--line-strong);padding:2px 8px;border-radius:6px;color:var(--ink)}
.ref button{background:none;border:0;padding:0 4px;color:var(--tape-dark);font-weight:700;cursor:pointer;text-decoration:underline;text-underline-offset:3px;min-height:32px}

/* Game */
.game{margin-top:64px;background:var(--paper);border:2px solid var(--ink);border-radius:24px;box-shadow:none;overflow:hidden}
.g-head{display:flex;justify-content:space-between;align-items:center;gap:16px;padding:18px 24px;border-bottom:2px solid var(--ink);flex-wrap:wrap}
.g-head h2{font-size:26px;letter-spacing:-.5px}
.g-head p{color:var(--body);font-size:15px}
.hud{display:flex;gap:10px}
.hud div{padding:8px 14px;border-radius:12px;background:var(--cream);border:1.5px solid var(--line-strong);text-align:center;min-width:78px}
.hud small{display:block;font-size:12px;font-weight:700;color:var(--muted)}
.hud b{font-family:var(--font-display);font-size:22px;line-height:1.15}
.hud .lives b{color:var(--tape)}
.arena{position:relative;height:400px;background:var(--cream);touch-action:none;user-select:none;cursor:none}
.arena canvas{display:block;width:100%;height:100%}
.legend{display:flex;gap:18px;flex-wrap:wrap;padding:12px 24px;border-top:1px solid var(--line);font-size:14px;color:var(--body)}
.legend span{display:inline-flex;align-items:center;gap:8px}
.legend i{width:18px;height:18px;border-radius:4px;border:1.5px solid var(--ink);display:inline-block}
.overlay{position:absolute;inset:0;display:flex;flex-direction:column;align-items:center;justify-content:center;gap:12px;text-align:center;background:rgba(245,241,232,.92);padding:24px;cursor:default}
.overlay[hidden]{display:none}
.overlay strong{font-family:var(--font-display);font-size:30px}
.overlay p{color:var(--body);max-width:380px}

.foot{margin-top:36px;text-align:center;font-size:14px;color:var(--muted)}

@media (max-width:860px){
  .hero{grid-template-columns:1fr;gap:4px;margin-top:24px}
  .scene{min-height:340px}
  .arena{height:360px}
}
@media (prefers-reduced-motion:reduce){*{animation:none!important;transition:none!important}}
</style>
  <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('assets/logo/favicon-32.png') }}">
  <link rel="icon" type="image/png" sizes="48x48" href="{{ asset('assets/logo/favicon-48.png') }}">
  <link rel="apple-touch-icon" href="{{ asset('assets/logo/apple-touch-icon.png') }}">
</head>
<body>
<div class="page">
  <header class="top">
    <a class="logo" href="{{ url('/') }}" aria-label="RuangTitip, ke beranda"><img src="{{ asset('assets/logo/logo-horizontal-light.png') }}" alt="RuangTitip" width="109" height="44" style="height:44px;width:auto"></a>
  </header>

  <main>
    <section class="hero">
      <div class="scene" aria-hidden="true">
        <div class="disc"></div>
        <div class="shadow"></div>
        <span class="stars">&#10038; &#10038;</span>
        <img class="ruru" src="{{ asset('assets/ruru/ruru-pusing.webp') }}" alt="">
        <span class="fallen f1"></span>
        <span class="fallen f2"></span>
        <span class="fallen f3"></span>
      </div>

      <div>
        <span class="code">Error 500 &middot; Kesalahan server</span>
        <h1>Waduh, rak Ruru <em>ambruk.</em></h1>
        <p class="lead">Ada yang salah di server kami, jadi halaman ini belum bisa dibuka. Coba muat ulang sebentar lagi, ya.</p>
        <p class="safe">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3 4 6v6c0 5 3.5 8 8 9 4.5-1 8-4 8-9V6z"/><path d="m9 12 2 2 4-4"/></svg>
          Tenang, ini cuma gangguan di website. Barang titipanmu tetap aman di gudang.
        </p>
        <div class="actions">
          <button class="btn btn-primary" type="button" id="retry">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12a9 9 0 1 1-3-6.7L21 8"/><path d="M21 3v5h-5"/></svg>
            Coba lagi
          </button>
          <a class="btn btn-outline" href="{{ url('/') }}">Ke beranda</a>
          <a class="btn btn-outline" id="wa" href="https://wa.me/6285121091134" target="_blank" rel="noopener">Lapor via WhatsApp</a>
        </div>
        <p class="ref">Kode laporan: <code id="ref">&ndash;</code><button type="button" id="copy">Salin</button></p>
      </div>
    </section>

    <section class="game" aria-labelledby="g-title">
      <div class="g-head">
        <div>
          <h2 id="g-title">Bantu Ruru tangkap barang yang jatuh</h2>
          <p>Geser Ruru pakai mouse, jari, atau tombol panah. Hindari barang retak.</p>
        </div>
        <div class="hud">
          <div><small>Skor</small><b id="score">0</b></div>
          <div class="lives"><small>Nyawa</small><b id="lives">&hearts;&hearts;&hearts;</b></div>
          <div><small>Rekor</small><b id="best">0</b></div>
        </div>
      </div>
      <div class="arena" id="arena" tabindex="0" aria-label="Area permainan. Gunakan panah kiri dan kanan untuk menggerakkan Ruru.">
        <canvas id="cv"></canvas>
        <div class="overlay" id="overlay">
          <strong id="ov-t">Rak ambruk, barang berjatuhan!</strong>
          <p id="ov-p">Tangkap kardus, koper, dan buku sebelum jatuh ke lantai. Barang retak bikin nyawa berkurang.</p>
          <button class="btn btn-primary" type="button" id="start">Mulai tangkap</button>
        </div>
      </div>
      <div class="legend">
        <span><i style="background:#C9A27A"></i>Kardus +1</span>
        <span><i style="background:#2E5A45"></i>Koper +2</span>
        <span><i style="background:#E8A677"></i>Buku +1</span>
        <span><i style="background:#A3321A"></i>Barang retak, hindari</span>
      </div>
    </section>
  </main>

  <p class="foot">&copy; {{ date('Y') }} RuangTitip &middot; <a href="mailto:ruangtitipmu@gmail.com">ruangtitipmu@gmail.com</a></p>
</div>

<script>
(function () {
  var $ = function (x) { return document.getElementById(x); };

  /* ---------- Tombol & kode laporan ---------- */
  $('retry').addEventListener('click', function () { location.reload(); });
  var d = new Date(), pad = function (n) { return String(n).padStart(2, '0'); };
  var ref = 'RT500-' + d.getFullYear() + pad(d.getMonth() + 1) + pad(d.getDate()) + '-' + pad(d.getHours()) + pad(d.getMinutes()) + pad(d.getSeconds());
  $('ref').textContent = ref;
  $('wa').href = 'https://wa.me/6285121091134?text=' + encodeURIComponent('Halo RuangTitip, saya dapat error 500 di ' + location.href + ' (kode: ' + ref + ').');
  $('copy').addEventListener('click', function () {
    var b = this;
    (navigator.clipboard ? navigator.clipboard.writeText(ref) : Promise.reject()).then(function () {
      b.textContent = 'Tersalin'; setTimeout(function () { b.textContent = 'Salin'; }, 1500);
    }, function () { b.textContent = 'Salin manual, ya'; });
  });

  /* ---------- Mini game ---------- */
  var arena = $('arena'), cv = $('cv'), ctx = cv.getContext('2d');
  var ruru = new Image(); ruru.src = document.querySelector('.scene .ruru').src;
  var W, H, dpr, px, targetX, items, score, lives, best = 0, running = false, last, spawnT, speedK, raf, keys = {};
  try { best = +localStorage.getItem('rt_catch_best') || 0; } catch (e) {}
  $('best').textContent = best;

  var TYPES = [
    { k: 'kardus', w: 46, h: 36, c: '#C9A27A', pts: 1, p: .42 },
    { k: 'koper',  w: 38, h: 48, c: '#2E5A45', pts: 2, p: .16 },
    { k: 'buku',   w: 40, h: 30, c: '#E8A677', pts: 1, p: .22 },
    { k: 'retak',  w: 44, h: 36, c: '#A3321A', pts: 0, p: .20 }
  ];
  var RW = 92, RH = 108;

  function size() {
    dpr = Math.min(2, window.devicePixelRatio || 1);
    W = arena.clientWidth; H = arena.clientHeight;
    cv.width = W * dpr; cv.height = H * dpr;
    ctx.setTransform(dpr, 0, 0, dpr, 0, 0);
    if (px === undefined) px = targetX = W / 2;
    draw();
  }
  window.addEventListener('resize', size);

  function pick() {
    var r = Math.random(), a = 0;
    for (var i = 0; i < TYPES.length; i++) { a += TYPES[i].p; if (r < a) return TYPES[i]; }
    return TYPES[0];
  }
  function start() {
    items = []; score = 0; lives = 3; speedK = 1; spawnT = 0; px = targetX = W / 2;
    $('score').textContent = 0; drawLives();
    $('overlay').hidden = true; running = true; last = performance.now();
    arena.focus(); cancelAnimationFrame(raf); raf = requestAnimationFrame(loop);
  }
  function drawLives() { $('lives').textContent = lives > 0 ? '♥'.repeat(lives) : '–'; }

  function loop(t) {
    var dt = Math.min(40, t - last) / 16.67; last = t;
    if (keys.ArrowLeft) targetX -= 9 * dt;
    if (keys.ArrowRight) targetX += 9 * dt;
    targetX = Math.max(RW / 2, Math.min(W - RW / 2, targetX));
    px += (targetX - px) * Math.min(1, .35 * dt);

    speedK = 1 + score * 0.04;
    spawnT -= dt;
    if (spawnT <= 0) {
      var ty = pick();
      items.push({ t: ty, x: 24 + Math.random() * (W - 48), y: -50, vy: (2.2 + Math.random() * 1.4) * speedK, rot: (Math.random() - .5) * .6, vr: (Math.random() - .5) * .05 });
      spawnT = Math.max(20, 62 - score * 1.2);
    }
    var catchY = H - 26 - RH * .62;
    for (var i = items.length - 1; i >= 0; i--) {
      var it = items[i];
      it.y += it.vy * dt; it.rot += it.vr * dt;
      if (it.y + it.t.h / 2 >= catchY && it.y - it.t.h / 2 <= catchY + 30 && Math.abs(it.x - px) < RW / 2 + it.t.w / 4) {
        items.splice(i, 1);
        if (it.t.k === 'retak') { lives--; drawLives(); flash('Aduh!', it.x, '#A3321A'); }
        else { score += it.t.pts; $('score').textContent = score; flash('+' + it.t.pts, it.x, '#2E5A45'); }
        continue;
      }
      if (it.y - it.t.h > H) {
        items.splice(i, 1);
        if (it.t.k !== 'retak') { lives--; drawLives(); flash('Jatuh!', it.x, '#9A4415'); }
      }
    }
    draw();
    if (lives <= 0) return over();
    raf = requestAnimationFrame(loop);
  }

  var pops = [];
  function flash(txt, x, c) { pops.push({ txt: txt, x: x, y: H - 150, a: 1, c: c }); }

  function drawItem(it) {
    var t = it.t, w = t.w, h = t.h;
    ctx.save(); ctx.translate(it.x, it.y); ctx.rotate(it.rot);
    ctx.lineWidth = 2; ctx.strokeStyle = '#1C1B18'; ctx.fillStyle = t.c;
    roundRect(-w / 2, -h / 2, w, h, 5); ctx.fill(); ctx.stroke();
    if (t.k === 'kardus') { ctx.fillStyle = '#B4531D'; ctx.fillRect(-5, -h / 2 + 1, 10, h - 2); }
    if (t.k === 'koper') { ctx.beginPath(); ctx.moveTo(-8, -h / 2); ctx.lineTo(-8, -h / 2 - 8); ctx.lineTo(8, -h / 2 - 8); ctx.lineTo(8, -h / 2); ctx.stroke(); ctx.strokeStyle = '#EAF1EC'; ctx.beginPath(); ctx.moveTo(-w / 4, -h / 2 + 8); ctx.lineTo(-w / 4, h / 2 - 8); ctx.moveTo(w / 4, -h / 2 + 8); ctx.lineTo(w / 4, h / 2 - 8); ctx.stroke(); }
    if (t.k === 'buku') { ctx.fillStyle = '#FFFDF8'; ctx.fillRect(-w / 2 + 6, -h / 2 + 5, w - 12, 5); ctx.fillRect(-w / 2 + 6, -h / 2 + 14, w - 20, 4); }
    if (t.k === 'retak') { ctx.strokeStyle = '#FFFDF8'; ctx.lineWidth = 2.5; ctx.beginPath(); ctx.moveTo(-6, -h / 2 + 3); ctx.lineTo(2, -4); ctx.lineTo(-4, 3); ctx.lineTo(6, h / 2 - 3); ctx.stroke(); }
    ctx.restore();
  }
  function roundRect(x, y, w, h, r) { ctx.beginPath(); ctx.moveTo(x + r, y); ctx.arcTo(x + w, y, x + w, y + h, r); ctx.arcTo(x + w, y + h, x, y + h, r); ctx.arcTo(x, y + h, x, y, r); ctx.arcTo(x, y, x + w, y, r); ctx.closePath(); }

  function draw() {
    ctx.clearRect(0, 0, W, H);
    ctx.strokeStyle = '#DDD5C4'; ctx.lineWidth = 1;
    for (var y = 40; y < H - 26; y += 40) { ctx.beginPath(); ctx.moveTo(0, y); ctx.lineTo(W, y); ctx.stroke(); }
    ctx.fillStyle = '#C9A27A'; ctx.fillRect(0, H - 22, W, 22);
    ctx.fillStyle = '#1C1B18'; ctx.fillRect(0, H - 24, W, 2);
    if (items) items.forEach(drawItem);
    if (ruru.complete && ruru.naturalWidth) {
      ctx.globalAlpha = .15; ctx.fillStyle = '#1C1B18'; ctx.beginPath(); ctx.ellipse(px, H - 24, RW * .5, 8, 0, 0, Math.PI * 2); ctx.fill(); ctx.globalAlpha = 1;
      ctx.drawImage(ruru, px - RW / 2, H - 22 - RH, RW, RH);
    }
    for (var i = pops.length - 1; i >= 0; i--) {
      var p = pops[i]; p.y -= 1.2; p.a -= .025;
      if (p.a <= 0) { pops.splice(i, 1); continue; }
      ctx.globalAlpha = p.a; ctx.fillStyle = p.c; ctx.font = '800 22px "Bricolage Grotesque", sans-serif'; ctx.textAlign = 'center';
      ctx.fillText(p.txt, p.x, p.y); ctx.globalAlpha = 1;
    }
  }

  function over() {
    running = false;
    var rec = score > best;
    if (rec) { best = score; $('best').textContent = best; try { localStorage.setItem('rt_catch_best', best); } catch (e) {} }
    $('ov-t').textContent = rec && score > 0 ? 'Rekor baru: ' + score + ' poin!' : score + ' poin terkumpul';
    $('ov-p').textContent = score >= 30 ? 'Mantap, kamu layak jadi kepala gudang Ruru.' : score >= 12 ? 'Lumayan! Sambil nunggu, coba kalahkan skormu.' : 'Barangnya kabur semua. Coba lagi, pasti bisa.';
    $('start').textContent = 'Main lagi';
    $('overlay').hidden = false; $('start').focus();
  }

  /* Kontrol */
  function setX(e) { var r = arena.getBoundingClientRect(); targetX = e.clientX - r.left; }
  arena.addEventListener('pointermove', function (e) { if (running) setX(e); });
  arena.addEventListener('pointerdown', function (e) { if (running) setX(e); });
  arena.addEventListener('keydown', function (e) {
    if (e.key === 'ArrowLeft' || e.key === 'ArrowRight') { keys[e.key] = true; e.preventDefault(); }
    if (!running && (e.key === 'Enter' || e.key === ' ')) { e.preventDefault(); start(); }
  });
  arena.addEventListener('keyup', function (e) { keys[e.key] = false; });
  $('start').addEventListener('click', function (e) { e.stopPropagation(); start(); });
  ruru.onload = size;
  size();
})();
</script>
</body>
</html>
