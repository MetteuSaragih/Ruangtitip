<<<<<<< HEAD
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
=======
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Maintenance - RUTIP</title>
<style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}

:root{
  --v:#7c3aed;--vi:#6366f1;--va:#a78bfa;
  --bg:#07010f;
}

body{
  min-height:100vh;
  background:var(--bg);
  color:#fff;
  font-family:'Inter',system-ui,-apple-system,sans-serif;
  display:flex;flex-direction:column;
  align-items:center;justify-content:center;
  padding:40px 16px;
}

/* ── Background ── */
.bg-grid{
  position:fixed;inset:0;
  background-image:
    linear-gradient(rgba(139,92,246,.06) 1px,transparent 1px),
    linear-gradient(90deg,rgba(139,92,246,.06) 1px,transparent 1px);
  background-size:44px 44px;
  pointer-events:none;
}
.orb{position:fixed;border-radius:50%;filter:blur(80px);pointer-events:none;}
.orb-1{width:min(600px,80vw);height:min(600px,80vw);background:radial-gradient(circle,rgba(124,58,237,.22),transparent 65%);top:-20%;left:-15%;animation:drift 12s ease-in-out infinite alternate;}
.orb-2{width:min(500px,70vw);height:min(500px,70vw);background:radial-gradient(circle,rgba(99,102,241,.2),transparent 65%);bottom:-15%;right:-10%;animation:drift 9s ease-in-out infinite alternate-reverse;}
.orb-3{width:min(300px,50vw);height:min(300px,50vw);background:radial-gradient(circle,rgba(167,139,250,.1),transparent 65%);top:50%;left:50%;transform:translate(-50%,-50%);animation:drift 15s ease-in-out infinite alternate;}
@keyframes drift{to{transform:translate(24px,16px) scale(1.06);}}
.orb-3{animation:drift3 15s ease-in-out infinite alternate;}
@keyframes drift3{to{transform:translate(calc(-50% + 20px),calc(-50% + 12px)) scale(1.08);}}

/* Particles */
.p{position:fixed;border-radius:50%;pointer-events:none;animation:rise linear infinite;}
@keyframes rise{
  0%{transform:translateY(100vh) scale(.3);opacity:0;}
  10%{opacity:.7;}90%{opacity:.2;}
  100%{transform:translateY(-5vh) scale(1);opacity:0;}
}

/* ── Card ── */
.card{
  position:relative;z-index:10;
  width:100%;max-width:460px;
  display:flex;flex-direction:column;align-items:center;
  text-align:center;gap:0;
}

/* ── Gear illustration ── */
.illus{
  position:relative;
  width:clamp(140px,28vw,180px);
  height:clamp(140px,28vw,180px);
  margin-bottom:36px;
  flex-shrink:0;
}
.ring{
  position:absolute;inset:0;border-radius:50%;
  border:1.5px solid rgba(124,58,237,.28);
  animation:spin 14s linear infinite;
}
.ring-2{
  position:absolute;inset:18px;border-radius:50%;
  border:1.5px dashed rgba(124,58,237,.18);
  animation:spin 9s linear infinite reverse;
}
.ring-3{
  position:absolute;inset:34px;border-radius:50%;
  border:1px solid rgba(167,139,250,.12);
  animation:spin 20s linear infinite;
}
.core{
  position:absolute;inset:42px;
  background:linear-gradient(135deg,rgba(124,58,237,.18),rgba(99,102,241,.1));
  border-radius:50%;
  border:1.5px solid rgba(124,58,237,.4);
  display:flex;align-items:center;justify-content:center;
  box-shadow:0 0 32px rgba(124,58,237,.2),inset 0 0 20px rgba(124,58,237,.08);
}
@keyframes spin{to{transform:rotate(360deg);}}

.gear-a{animation:spin 5s linear infinite;}
.gear-b{animation:spin 3s linear infinite reverse;}

/* dots on ring */
.dot-ring{
  position:absolute;
  width:8px;height:8px;border-radius:50%;
  background:#a78bfa;
  box-shadow:0 0 8px #a78bfa;
  animation:orbit 6s linear infinite;
  top:50%;left:0;
  transform-origin:clamp(70px,14vw,90px) 0;
  margin-top:-4px;
}
@keyframes orbit{to{transform:rotate(360deg);}}

/* ── Badge ── */
.badge{
  display:inline-flex;align-items:center;gap:7px;
  background:rgba(124,58,237,.15);
  border:1px solid rgba(124,58,237,.35);
  color:#c4b5fd;
  font-size:11px;font-weight:700;letter-spacing:.6px;
  padding:6px 16px;border-radius:99px;
  margin-bottom:20px;
}
.badge-dot{
  width:7px;height:7px;border-radius:50%;
  background:#a78bfa;
  animation:blink 1.6s ease-in-out infinite;
}
@keyframes blink{0%,100%{opacity:1;box-shadow:0 0 0 0 rgba(167,139,250,.5);}50%{opacity:.4;box-shadow:0 0 6px rgba(167,139,250,.3);}}

/* ── Heading ── */
h1{
  font-size:clamp(24px,5vw,32px);
  font-weight:900;
  letter-spacing:-.5px;
  line-height:1.15;
  margin-bottom:14px;
}
h1 em{
  font-style:normal;
  background:linear-gradient(90deg,#a78bfa,#818cf8);
  -webkit-background-clip:text;
  -webkit-text-fill-color:transparent;
}
p.sub{
  font-size:clamp(13px,2.5vw,15px);
  line-height:1.75;
  color:rgba(255,255,255,.48);
  margin-bottom:36px;
  max-width:340px;
}

/* ── Progress section ── */
.progress-card{
  width:100%;
  background:rgba(255,255,255,.04);
  border:1px solid rgba(124,58,237,.2);
  border-radius:18px;
  padding:18px 22px;
  margin-bottom:28px;
}
.progress-header{
  display:flex;align-items:center;justify-content:space-between;
  margin-bottom:12px;
}
.progress-label{
  font-size:12px;font-weight:600;
  color:rgba(255,255,255,.5);
  display:flex;align-items:center;gap:6px;
}
.progress-label svg{animation:spin 2s linear infinite;}
.progress-pct{
  font-size:12px;font-weight:700;
  color:#a78bfa;
  font-variant-numeric:tabular-nums;
}
.bar-track{
  height:6px;
  background:rgba(255,255,255,.07);
  border-radius:99px;
  overflow:hidden;
  margin-bottom:10px;
}
.bar-fill{
  height:100%;
  background:linear-gradient(90deg,#7c3aed,#a78bfa,#6366f1);
  background-size:200% 100%;
  border-radius:99px;
  animation:bar-move 2.4s ease-in-out infinite, shimmer 2s linear infinite;
}
@keyframes bar-move{
  0%{width:8%;margin-left:0;}
  45%{width:40%;margin-left:5%;}
  55%{width:40%;margin-left:15%;}
  100%{width:8%;margin-left:84%;}
}
@keyframes shimmer{
  0%{background-position:200% 0;}
  100%{background-position:-200% 0;}
}
.progress-steps{
  display:flex;gap:8px;flex-wrap:wrap;
}
.step{
  display:flex;align-items:center;gap:5px;
  font-size:10px;font-weight:600;
  color:rgba(255,255,255,.3);
}
.step.done{color:#86efac;}
.step.active{color:#a78bfa;}
.step-icon{width:14px;height:14px;border-radius:50%;border:1.5px solid currentColor;display:flex;align-items:center;justify-content:center;flex-shrink:0;}
.step.done .step-icon{background:#86efac;border-color:#86efac;color:#052e16;}
.step.active .step-icon{border-color:#a78bfa;animation:blink 1.2s ease-in-out infinite;}

/* ── Button ── */
.btn-wa{
  display:inline-flex;align-items:center;gap:10px;
  background:linear-gradient(135deg,#25d366,#128c7e);
  color:#fff;
  font-size:14px;font-weight:700;
  padding:14px 28px;
  border-radius:16px;
  text-decoration:none;
  transition:transform .2s,opacity .2s,box-shadow .2s;
  box-shadow:0 8px 24px rgba(37,211,102,.28);
  width:100%;
  justify-content:center;
}
.btn-wa:hover{transform:translateY(-2px);opacity:.93;box-shadow:0 12px 32px rgba(37,211,102,.38);}
.btn-wa:active{transform:translateY(0);}

/* ── Footer ── */
footer{
  position:relative;z-index:10;
  margin-top:32px;
  font-size:11px;
  color:rgba(255,255,255,.18);
  letter-spacing:.4px;
}

/* ── Responsive ── */
@media(max-width:480px){
  .illus{margin-bottom:28px;}
  .progress-card{padding:14px 16px;}
  .btn-wa{padding:13px 20px;font-size:13px;}
  h1{margin-bottom:12px;}
  p.sub{margin-bottom:28px;}
}
@media(min-width:768px){
  body{padding:60px 24px;}
  h1{font-size:36px;}
>>>>>>> hostinger/main
}
</style>
</head>
<body>
<<<<<<< HEAD
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
=======
<!-- BG layers -->
<div class="bg-grid"></div>
<div class="orb orb-1"></div>
<div class="orb orb-2"></div>
<div class="orb orb-3"></div>

<!-- Particles -->
<div class="p" style="width:3px;height:3px;background:#a78bfa;left:8%;animation-duration:9s;animation-delay:0s;"></div>
<div class="p" style="width:4px;height:4px;background:#6366f1;left:22%;animation-duration:11s;animation-delay:-4s;"></div>
<div class="p" style="width:3px;height:3px;background:#7c3aed;left:55%;animation-duration:8s;animation-delay:-6s;"></div>
<div class="p" style="width:4px;height:4px;background:#a78bfa;left:78%;animation-duration:13s;animation-delay:-2s;"></div>
<div class="p" style="width:2px;height:2px;background:#818cf8;left:90%;animation-duration:10s;animation-delay:-8s;"></div>

<!-- Content -->
<div class="card">

  <!-- Gear illustration -->
  <div class="illus">
    <div class="ring"></div>
    <div class="ring-2"></div>
    <div class="ring-3"></div>
    <div class="dot-ring"></div>
    <div class="core">
      <svg width="54" height="54" viewBox="0 0 48 48" fill="none">
        <!-- Gear besar -->
        <g class="gear-a" style="transform-origin:20px 20px;">
          <path d="M20 8a12 12 0 1 0 0 24 12 12 0 0 0 0-24z"
                fill="rgba(124,58,237,.18)" stroke="#a78bfa" stroke-width="1.8"/>
          <circle cx="20" cy="20" r="4"
                  fill="rgba(124,58,237,.3)" stroke="#a78bfa" stroke-width="1.8"/>
          <path d="M20 4v4M20 36v4M4 20h4M36 20h4
                   M7.51 7.51l2.83 2.83M29.66 29.66l2.83 2.83
                   M7.51 32.49l2.83-2.83M29.66 10.34l2.83-2.83"
                stroke="#a78bfa" stroke-width="1.8" stroke-linecap="round"/>
        </g>
        <!-- Gear kecil -->
        <g class="gear-b" style="transform-origin:35px 33px;">
          <path d="M35 27a6 6 0 1 0 0 12 6 6 0 0 0 0-12z"
                fill="rgba(99,102,241,.18)" stroke="#818cf8" stroke-width="1.6"/>
          <circle cx="35" cy="33" r="2.2"
                  fill="rgba(99,102,241,.3)" stroke="#818cf8" stroke-width="1.5"/>
          <path d="M35 24v2M35 40v2M29 33h2M41 33h2"
                stroke="#818cf8" stroke-width="1.6" stroke-linecap="round"/>
        </g>
      </svg>
    </div>
  </div>

  <!-- Badge -->
  <div class="badge">
    <span class="badge-dot"></span>
    MAINTENANCE MODE
  </div>

  <h1>Lagi <em>diperbaiki</em> nih!</h1>
  <p class="sub">
    Tim RUTIP sedang melakukan perawatan sistem untuk pengalaman yang lebih baik.
    Mohon tunggu sebentar, kami segera kembali! ✨
  </p>

  <!-- Progress card -->
  <div class="progress-card">
    <div class="progress-header">
      <span class="progress-label">
        <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
          <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
        </svg>
        Memperbarui sistem
      </span>
      <span class="progress-pct" id="pctLabel">0%</span>
    </div>
    <div class="bar-track">
      <div class="bar-fill"></div>
    </div>
    <div class="progress-steps">
      <span class="step done">
        <span class="step-icon">
          <svg width="8" height="8" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
        </span>
        Backup data
      </span>
      <span class="step done">
        <span class="step-icon">
          <svg width="8" height="8" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
        </span>
        Update modul
      </span>
      <span class="step active">
        <span class="step-icon"></span>
        Pengujian akhir
      </span>
    </div>
  </div>

  <!-- WA Button -->
  <a href="https://wa.me/6285121091134" target="_blank" rel="noopener" class="btn-wa">
    <svg width="20" height="20" viewBox="0 0 24 24" fill="white">
      <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/>
      <path d="M12 0C5.373 0 0 5.373 0 12c0 2.12.553 4.112 1.522 5.842L.057 23.852a.5.5 0 00.614.614l5.946-1.44A11.934 11.934 0 0012 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 22c-1.924 0-3.726-.519-5.271-1.42l-.358-.213-3.717.9.929-3.634-.233-.374A9.948 9.948 0 012 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10z"/>
    </svg>
    Hubungi Kami via WhatsApp
  </a>

</div>

<footer>© {{ date('Y') }} RUTIP · Ruang Titip &nbsp;·&nbsp; Segera kembali</footer>

<script>
/* Animasikan angka persentase sesuai gerakan bar */
(function(){
  const el = document.getElementById('pctLabel');
  let v = 0, dir = 1;
  setInterval(function(){
    v += dir * (Math.random() * 3 + 1);
    if(v >= 78){ dir = -1; }
    if(v <= 8){ dir = 1; }
    v = Math.max(8, Math.min(84, v));
    el.textContent = Math.round(v) + '%';
  }, 120);
>>>>>>> hostinger/main
})();
</script>
</body>
</html>
