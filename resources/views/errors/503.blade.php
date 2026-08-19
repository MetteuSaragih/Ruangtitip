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
}
</style>
</head>
<body>
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
})();
</script>
</body>
</html>
