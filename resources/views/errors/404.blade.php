<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>404 - Halaman Tidak Ditemukan · RUTIP</title>
<style>
  *,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
  body{min-height:100vh;background:#080212;color:#fff;font-family:'Inter',system-ui,sans-serif;display:flex;flex-direction:column;align-items:center;justify-content:center;overflow:hidden;position:relative;}

  .bg-grid{position:fixed;inset:0;background-image:linear-gradient(rgba(139,92,246,.05) 1px,transparent 1px),linear-gradient(90deg,rgba(139,92,246,.05) 1px,transparent 1px);background-size:40px 40px;}
  .bg-orb{position:fixed;border-radius:50%;filter:blur(90px);opacity:.15;}
  .bg-orb-1{width:500px;height:500px;background:radial-gradient(circle,#7c3aed,transparent 70%);top:-150px;right:-100px;}
  .bg-orb-2{width:400px;height:400px;background:radial-gradient(circle,#6366f1,transparent 70%);bottom:-100px;left:-80px;}

  /* 404 number with glitch */
  .glitch-wrap{position:relative;margin-bottom:8px;}
  .num-404{font-size:clamp(96px,20vw,160px);font-weight:900;letter-spacing:-8px;line-height:1;
    background:linear-gradient(135deg,#7c3aed,#a78bfa,#6366f1);-webkit-background-clip:text;-webkit-text-fill-color:transparent;
    position:relative;display:inline-block;animation:glitch-main 4s ease-in-out infinite;}
  .num-404::before,.num-404::after{content:'404';position:absolute;top:0;left:0;width:100%;
    background:linear-gradient(135deg,#7c3aed,#a78bfa,#6366f1);-webkit-background-clip:text;-webkit-text-fill-color:transparent;
    font-size:inherit;font-weight:inherit;letter-spacing:inherit;}
  .num-404::before{animation:glitch-top 4s ease-in-out infinite;clip-path:polygon(0 0,100% 0,100% 35%,0 35%);}
  .num-404::after{animation:glitch-bot 4s ease-in-out infinite;clip-path:polygon(0 65%,100% 65%,100% 100%,0 100%);}

  @keyframes glitch-main{0%,90%,100%{transform:translate(0);}91%{transform:translate(-2px,1px);}92%{transform:translate(2px,-1px);}93%{transform:translate(0);}94%{transform:translate(1px,2px);}95%{transform:translate(-1px,-1px);}}
  @keyframes glitch-top{0%,90%,100%{transform:translate(0);opacity:0;}91%{transform:translate(-4px,0);opacity:.7;filter:hue-rotate(90deg);}93%{transform:translate(4px,0);opacity:.5;}95%{transform:translate(0);opacity:0;}}
  @keyframes glitch-bot{0%,90%,100%{transform:translate(0);opacity:0;}92%{transform:translate(4px,0);opacity:.7;filter:hue-rotate(-90deg);}94%{transform:translate(-4px,0);opacity:.5;}96%{transform:translate(0);opacity:0;}}

  /* Scanline effect */
  .scanline{position:fixed;inset:0;pointer-events:none;background:repeating-linear-gradient(0deg,transparent,transparent 2px,rgba(0,0,0,.03) 2px,rgba(0,0,0,.03) 4px);animation:scan 8s linear infinite;}
  @keyframes scan{0%{background-position:0 0;}100%{background-position:0 40px;}}

  .card{position:relative;z-index:10;text-align:center;max-width:480px;padding:0 20px;width:100%;}
  @media(max-width:480px){
    .num-404{font-size:clamp(80px,22vw,120px) !important;letter-spacing:-4px !important;}
    .btns{flex-direction:column;}
    .btn-primary,.btn-secondary{width:100%;justify-content:center;}
  }
  @media(min-width:768px){body{padding:40px 24px;}}

  /* Floating planet/astronaut */
  .illus{margin-bottom:0;position:relative;height:0;}

  /* Lost signal animation */
  .signal{display:flex;align-items:flex-end;justify-content:center;gap:4px;height:32px;margin:12px auto 28px;}
  .signal-bar{width:6px;border-radius:3px;background:rgba(124,58,237,.3);}
  .signal-bar.active{background:linear-gradient(to top,#7c3aed,#a78bfa);animation:signal-blink 1.5s ease-in-out infinite;}
  .signal-bar:nth-child(1){height:8px;animation-delay:0s;}
  .signal-bar:nth-child(2){height:14px;animation-delay:.15s;}
  .signal-bar:nth-child(3){height:20px;animation-delay:.3s;}
  .signal-bar:nth-child(4){height:26px;animation-delay:.45s;}
  .signal-bar:nth-child(5){height:32px;animation-delay:.6s;}
  @keyframes signal-blink{0%,100%{opacity:.3;}50%{opacity:1;}}

  h1{font-size:24px;font-weight:800;margin-bottom:10px;letter-spacing:-.3px;}
  p.desc{font-size:14px;line-height:1.7;color:rgba(255,255,255,.45);margin-bottom:28px;max-width:320px;margin-left:auto;margin-right:auto;}

  /* Error detail box */
  .err-box{background:rgba(124,58,237,.08);border:1px solid rgba(124,58,237,.2);border-radius:14px;padding:14px 20px;margin-bottom:28px;text-align:left;}
  .err-box-row{display:flex;justify-content:space-between;align-items:center;font-size:12px;}
  .err-box-row+.err-box-row{margin-top:8px;padding-top:8px;border-top:1px solid rgba(255,255,255,.06);}
  .err-key{color:rgba(255,255,255,.35);}
  .err-val{color:#a78bfa;font-family:monospace;font-weight:600;}

  /* Buttons */
  .btns{display:flex;gap:12px;justify-content:center;flex-wrap:wrap;}
  .btn-primary{display:inline-flex;align-items:center;gap:8px;background:linear-gradient(135deg,#7c3aed,#6366f1);color:#fff;font-size:13px;font-weight:700;padding:12px 24px;border-radius:14px;text-decoration:none;box-shadow:0 6px 20px rgba(124,58,237,.35);transition:opacity .2s,transform .2s;}
  .btn-primary:hover{opacity:.9;transform:translateY(-1px);}
  .btn-secondary{display:inline-flex;align-items:center;gap:8px;border:1.5px solid rgba(255,255,255,.15);color:rgba(255,255,255,.65);font-size:13px;font-weight:600;padding:12px 24px;border-radius:14px;text-decoration:none;transition:background .2s;}
  .btn-secondary:hover{background:rgba(255,255,255,.06);}

  footer{position:relative;z-index:10;margin-top:40px;font-size:11px;color:rgba(255,255,255,.2);}

  /* Particle */
  .p{position:fixed;border-radius:50%;pointer-events:none;animation:float-up linear infinite;}
  @keyframes float-up{0%{transform:translateY(100vh);opacity:0;}10%{opacity:.5;}90%{opacity:.15;}100%{transform:translateY(-20px);opacity:0;}}
</style>
</head>
<body>
<div class="bg-grid"></div>
<div class="bg-orb bg-orb-1"></div>
<div class="bg-orb bg-orb-2"></div>
<div class="scanline"></div>

<div class="p" style="width:3px;height:3px;background:#a78bfa;left:15%;animation-duration:9s;animation-delay:-2s;"></div>
<div class="p" style="width:4px;height:4px;background:#6366f1;left:40%;animation-duration:7s;animation-delay:-5s;"></div>
<div class="p" style="width:3px;height:3px;background:#7c3aed;left:70%;animation-duration:11s;animation-delay:-8s;"></div>
<div class="p" style="width:2px;height:2px;background:#a78bfa;left:85%;animation-duration:8s;animation-delay:-1s;"></div>

<div class="card">
  <!-- Logo asli RUTIP -->
  <a href="/" style="display:inline-block;margin-bottom:32px;">
    <img src="/images/logo-rutip-putih.png" alt="RUTIP"
         style="height:48px;width:auto;filter:drop-shadow(0 4px 16px rgba(124,58,237,.3));">
  </a>

  <!-- Glitch 404 -->
  <div class="glitch-wrap">
    <div class="num-404">404</div>
  </div>

  <!-- Signal bars (lost signal) -->
  <div class="signal">
    <div class="signal-bar active"></div>
    <div class="signal-bar active"></div>
    <div class="signal-bar"></div>
    <div class="signal-bar"></div>
    <div class="signal-bar"></div>
  </div>

  <h1>Halaman Tidak Ditemukan</h1>
  <p class="desc">
    Sepertinya halaman yang kamu cari sudah dipindah, dihapus, atau mungkin kamu salah ketik URL-nya. 🔍
  </p>

  <!-- Error info -->
  <div class="err-box">
    <div class="err-box-row">
      <span class="err-key">Status</span>
      <span class="err-val">404 Not Found</span>
    </div>
    <div class="err-box-row">
      <span class="err-key">URL</span>
      <span class="err-val" style="max-width:220px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{ request()->path() }}</span>
    </div>
    <div class="err-box-row">
      <span class="err-key">Waktu</span>
      <span class="err-val">{{ now()->format('d M Y, H:i') }} WIB</span>
    </div>
  </div>

  <div class="btns">
    <a href="/" class="btn-primary">
      <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 9.5L12 4l9 5.5V20H3V9.5z"/></svg>
      Kembali ke Beranda
    </a>
    <a href="javascript:history.back()" class="btn-secondary">
      <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 12H5m7-7-7 7 7 7"/></svg>
      Halaman Sebelumnya
    </a>
  </div>
</div>

<footer>© {{ date('Y') }} RUTIP · Ruang Titip</footer>
</body>
</html>
