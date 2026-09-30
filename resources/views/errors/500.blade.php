<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>500 - Terjadi Kesalahan · RUTIP</title>
<style>
  *,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
<<<<<<< HEAD
  body{min-height:100vh;background:#080212;color:#fff;font-family:'Inter',system-ui,sans-serif;display:flex;flex-direction:column;align-items:center;justify-content:center;overflow:hidden;position:relative;}
=======
  body{min-height:100vh;background:#080212;color:#fff;font-family:'Inter',system-ui,sans-serif;display:flex;flex-direction:column;align-items:center;justify-content:center;position:relative;padding:40px 20px;}
>>>>>>> hostinger/main

  .bg-grid{position:fixed;inset:0;background-image:linear-gradient(rgba(239,68,68,.04) 1px,transparent 1px),linear-gradient(90deg,rgba(239,68,68,.04) 1px,transparent 1px);background-size:40px 40px;}
  .bg-orb{position:fixed;border-radius:50%;filter:blur(90px);}
  .bg-orb-1{width:450px;height:450px;background:radial-gradient(circle,rgba(124,58,237,.18),transparent 70%);top:-100px;left:-80px;}
  .bg-orb-2{width:350px;height:350px;background:radial-gradient(circle,rgba(239,68,68,.12),transparent 70%);bottom:-80px;right:-60px;}

  .card{position:relative;z-index:10;text-align:center;max-width:500px;padding:0 20px;width:100%;}
  @media(max-width:480px){
    body{padding:24px 16px;}
    .btns{flex-direction:column;}
    .btn-primary,.btn-reload{width:100%;justify-content:center;}
    .err-box pre{font-size:10px;}
  }
<<<<<<< HEAD
  @media(min-width:768px){body{padding:40px 24px};h1{font-size:28px;}}
=======
  @media(min-width:768px){body{padding:60px 24px;}h1{font-size:28px;}}
>>>>>>> hostinger/main

  /* Logo */
  .logo{display:inline-flex;align-items:center;gap:8px;margin-bottom:32px;text-decoration:none;color:inherit;opacity:.7;}
  .logo-icon{width:36px;height:36px;border-radius:10px;background:linear-gradient(135deg,#7c3aed,#6366f1);display:flex;align-items:center;justify-content:center;}

  /* Error illustration */
  .illus{position:relative;width:120px;height:120px;margin:0 auto 28px;}
  .illus-ring{position:absolute;inset:0;border-radius:50%;border:2px solid rgba(239,68,68,.2);animation:spin 10s linear infinite;}
  .illus-ring-2{position:absolute;inset:14px;border-radius:50%;border:1.5px dashed rgba(239,68,68,.15);animation:spin 6s linear infinite reverse;}
  .illus-core{position:absolute;inset:28px;background:rgba(239,68,68,.08);border-radius:50%;border:1.5px solid rgba(239,68,68,.25);display:flex;align-items:center;justify-content:center;animation:core-pulse 2s ease-in-out infinite;}
  @keyframes spin{to{transform:rotate(360deg);}}
  @keyframes core-pulse{0%,100%{box-shadow:0 0 0 0 rgba(239,68,68,.2);}50%{box-shadow:0 0 0 12px rgba(239,68,68,0);}}

  /* Error code badge */
  .err-code{display:inline-flex;align-items:center;gap:8px;background:rgba(239,68,68,.1);border:1px solid rgba(239,68,68,.25);color:#fca5a5;font-size:11px;font-weight:700;letter-spacing:.5px;padding:5px 14px;border-radius:20px;margin-bottom:16px;}
  .err-dot{width:7px;height:7px;background:#f87171;border-radius:50%;animation:blink 1.2s ease-in-out infinite;}
  @keyframes blink{0%,100%{opacity:1;}50%{opacity:.2;}}

  h1{font-size:26px;font-weight:800;margin-bottom:10px;letter-spacing:-.3px;}
  p.desc{font-size:14px;line-height:1.7;color:rgba(255,255,255,.45);margin-bottom:28px;max-width:340px;margin-left:auto;margin-right:auto;}

  /* Stack trace box */
  .err-box{background:rgba(15,5,30,.6);border:1px solid rgba(239,68,68,.2);border-radius:14px;padding:16px 20px;margin-bottom:28px;text-align:left;overflow:hidden;}
  .err-box-header{display:flex;align-items:center;gap:8px;margin-bottom:12px;padding-bottom:10px;border-bottom:1px solid rgba(255,255,255,.06);}
  .dot{width:10px;height:10px;border-radius:50%;}
  .dot-r{background:#ff5f57;}
  .dot-y{background:#febc2e;}
  .dot-g{background:#28c840;}
  .err-box pre{font-family:'Courier New',monospace;font-size:11px;color:rgba(255,255,255,.4);line-height:1.6;overflow-x:auto;}
  .err-highlight{color:#fca5a5;}
  .err-green{color:#86efac;}
  .err-blue{color:#93c5fd;}

  /* Buttons */
  .btns{display:flex;gap:12px;justify-content:center;flex-wrap:wrap;}
  .btn-primary{display:inline-flex;align-items:center;gap:8px;background:linear-gradient(135deg,#7c3aed,#6366f1);color:#fff;font-size:13px;font-weight:700;padding:12px 24px;border-radius:14px;text-decoration:none;box-shadow:0 6px 20px rgba(124,58,237,.35);transition:opacity .2s,transform .2s;}
  .btn-primary:hover{opacity:.9;transform:translateY(-1px);}
  .btn-reload{display:inline-flex;align-items:center;gap:8px;border:1.5px solid rgba(239,68,68,.3);color:#fca5a5;font-size:13px;font-weight:600;padding:12px 24px;border-radius:14px;text-decoration:none;background:rgba(239,68,68,.06);transition:background .2s;cursor:pointer;}
  .btn-reload:hover{background:rgba(239,68,68,.12);}

  footer{position:relative;z-index:10;margin-top:40px;font-size:11px;color:rgba(255,255,255,.2);}

  .p{position:fixed;border-radius:50%;pointer-events:none;animation:float-up linear infinite;}
  @keyframes float-up{0%{transform:translateY(100vh);opacity:0;}10%{opacity:.4;}90%{opacity:.1;}100%{transform:translateY(-20px);opacity:0;}}
</style>
</head>
<body>
<div class="bg-grid"></div>
<div class="bg-orb bg-orb-1"></div>
<div class="bg-orb bg-orb-2"></div>

<div class="p" style="width:3px;height:3px;background:#f87171;left:20%;animation-duration:9s;animation-delay:-3s;"></div>
<div class="p" style="width:2px;height:2px;background:#a78bfa;left:50%;animation-duration:7s;animation-delay:-6s;"></div>
<div class="p" style="width:3px;height:3px;background:#f87171;left:75%;animation-duration:11s;animation-delay:-1s;"></div>

<div class="card">
<<<<<<< HEAD
  <a href="/" style="display:inline-block;margin-bottom:32px;">
    <img src="/images/logo-rutip-putih.png" alt="RUTIP"
         style="height:48px;width:auto;filter:drop-shadow(0 4px 16px rgba(124,58,237,.3));">
  </a>

=======
>>>>>>> hostinger/main
  <!-- Illustration -->
  <div class="illus">
    <div class="illus-ring"></div>
    <div class="illus-ring-2"></div>
    <div class="illus-core">
      <svg width="36" height="36" fill="none" viewBox="0 0 24 24">
        <path stroke="#f87171" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" d="M12 9v4m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
      </svg>
    </div>
  </div>

  <!-- Badge -->
  <div class="err-code">
    <span class="err-dot"></span>
    500 INTERNAL SERVER ERROR
  </div>

  <h1>Oops! Ada yang <span style="color:#f87171;">Salah</span></h1>
  <p class="desc">
    Server kami mengalami masalah tak terduga. Tim RUTIP sudah mendapat notifikasi dan sedang menanganinya. 🛠️
  </p>

  <!-- Terminal error display -->
  <div class="err-box">
    <div class="err-box-header">
      <span class="dot dot-r"></span>
      <span class="dot dot-y"></span>
      <span class="dot dot-g"></span>
      <span style="font-size:10px;color:rgba(255,255,255,.25);margin-left:4px;">rutip · server-log</span>
    </div>
    <pre><span class="err-green">✓</span> Request diterima: <span class="err-blue">{{ request()->method() }} /{{ request()->path() }}</span>
<span class="err-highlight">✗ Error:</span> Internal server error (500)
<span style="color:rgba(255,255,255,.25);">  Timestamp: {{ now()->format('Y-m-d H:i:s') }} WIB</span>
<span style="color:rgba(255,255,255,.25);">  Server  : RUTIP Production</span>
<span class="err-green">→</span> Tim kami sudah dinotifikasi otomatis.
<span class="err-green">→</span> Silakan coba lagi dalam beberapa menit.</pre>
  </div>

  <div class="btns">
    <a href="/" class="btn-primary">
      <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 9.5L12 4l9 5.5V20H3V9.5z"/></svg>
      Kembali ke Beranda
    </a>
    <button onclick="location.reload()" class="btn-reload">
      <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
      Coba Lagi
    </button>
  </div>
</div>

<footer>© {{ date('Y') }} RUTIP · Ruang Titip &nbsp;·&nbsp; Error dilaporkan otomatis</footer>
</body>
</html>
