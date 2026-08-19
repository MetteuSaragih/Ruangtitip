<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Terlalu Banyak Request - RUTIP</title>
<style>
  *,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
  body{min-height:100vh;background:#080212;color:#fff;font-family:'Inter',system-ui,sans-serif;display:flex;flex-direction:column;align-items:center;justify-content:center;position:relative;padding:40px 20px;}
  .bg-grid{position:fixed;inset:0;background-image:linear-gradient(rgba(239,68,68,.04) 1px,transparent 1px),linear-gradient(90deg,rgba(239,68,68,.04) 1px,transparent 1px);background-size:40px 40px;}
  .bg-orb-1{position:fixed;width:400px;height:400px;background:radial-gradient(circle,rgba(124,58,237,.18),transparent 70%);top:-80px;right:-60px;border-radius:50%;filter:blur(80px);}
  .bg-orb-2{position:fixed;width:350px;height:350px;background:radial-gradient(circle,rgba(239,68,68,.1),transparent 70%);bottom:-60px;left:-60px;border-radius:50%;filter:blur(80px);}
  .card{position:relative;z-index:10;text-align:center;max-width:440px;padding:0 20px;width:100%;}
  @media(max-width:480px){body{padding:24px 16px;}.btn{width:100%;justify-content:center;}}
  @media(min-width:768px){body{padding:60px 24px;}}
  /* Wave animation */
  .wave-illus{display:flex;align-items:flex-end;justify-content:center;gap:5px;height:80px;margin-bottom:28px;}
  .wave-bar{width:8px;border-radius:4px;background:linear-gradient(to top,#7c3aed,#ef4444);animation:wave 1s ease-in-out infinite;}
  .wave-bar:nth-child(1){height:20%;animation-delay:0s;}
  .wave-bar:nth-child(2){height:55%;animation-delay:.1s;}
  .wave-bar:nth-child(3){height:80%;animation-delay:.2s;}
  .wave-bar:nth-child(4){height:100%;animation-delay:.3s;}
  .wave-bar:nth-child(5){height:80%;animation-delay:.4s;}
  .wave-bar:nth-child(6){height:55%;animation-delay:.5s;}
  .wave-bar:nth-child(7){height:20%;animation-delay:.6s;}
  @keyframes wave{0%,100%{transform:scaleY(1);}50%{transform:scaleY(.4);}}
  .badge{display:inline-flex;align-items:center;gap:6px;background:rgba(239,68,68,.1);border:1px solid rgba(239,68,68,.25);color:#fca5a5;font-size:11px;font-weight:700;padding:5px 14px;border-radius:20px;margin-bottom:16px;}
  h1{font-size:24px;font-weight:800;margin-bottom:10px;}
  p{font-size:14px;line-height:1.7;color:rgba(255,255,255,.45);margin-bottom:28px;max-width:320px;margin-left:auto;margin-right:auto;}
  .cooldown{font-size:13px;color:rgba(255,255,255,.35);margin-bottom:28px;}
  .cooldown span{color:#a78bfa;font-weight:700;font-size:20px;display:block;margin-bottom:4px;}
  .btn{display:inline-flex;align-items:center;gap:8px;background:linear-gradient(135deg,#7c3aed,#6366f1);color:#fff;font-size:13px;font-weight:700;padding:12px 28px;border-radius:14px;text-decoration:none;box-shadow:0 6px 20px rgba(124,58,237,.35);transition:opacity .2s,transform .2s;}
  .btn:hover{opacity:.9;transform:translateY(-1px);}
  footer{position:relative;z-index:10;margin-top:40px;font-size:11px;color:rgba(255,255,255,.2);}
</style>
</head>
<body>
<div class="bg-grid"></div>
<div class="bg-orb-1"></div>
<div class="bg-orb-2"></div>
<div class="card">
  <div class="wave-illus">
    <div class="wave-bar"></div><div class="wave-bar"></div><div class="wave-bar"></div>
    <div class="wave-bar"></div><div class="wave-bar"></div><div class="wave-bar"></div>
    <div class="wave-bar"></div>
  </div>
  <div class="badge">🚦 429 Too Many Requests</div>
  <h1>Sabar ya, Kamu Terlalu Cepat!</h1>
  <p>Kamu mengirim terlalu banyak permintaan dalam waktu singkat. Tunggu sebentar, lalu coba lagi.</p>
  <div class="cooldown">
    <span id="countdown">60</span>
    detik sebelum bisa mencoba lagi
  </div>
  <a href="/" class="btn">
    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 9.5L12 4l9 5.5V20H3V9.5z"/></svg>
    Kembali ke Beranda
  </a>
</div>
<footer>© {{ date('Y') }} RUTIP · Ruang Titip</footer>
<script>
let t = 60;
const el = document.getElementById('countdown');
const iv = setInterval(() => { if(--t <= 0){clearInterval(iv);el.textContent='0';} else { el.textContent = t; } }, 1000);
</script>
</body>
</html>
