<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Sesi Kedaluwarsa - RUTIP</title>
<style>
  *,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
  body{min-height:100vh;background:#080212;color:#fff;font-family:'Inter',system-ui,sans-serif;display:flex;flex-direction:column;align-items:center;justify-content:center;position:relative;padding:40px 20px;}
  .bg-grid{position:fixed;inset:0;background-image:linear-gradient(rgba(245,158,11,.04) 1px,transparent 1px),linear-gradient(90deg,rgba(245,158,11,.04) 1px,transparent 1px);background-size:40px 40px;}
  .bg-orb-1{position:fixed;width:400px;height:400px;background:radial-gradient(circle,rgba(124,58,237,.18),transparent 70%);top:-80px;left:-60px;border-radius:50%;filter:blur(80px);}
  .bg-orb-2{position:fixed;width:350px;height:350px;background:radial-gradient(circle,rgba(245,158,11,.1),transparent 70%);bottom:-60px;right:-60px;border-radius:50%;filter:blur(80px);}
  .card{position:relative;z-index:10;text-align:center;max-width:440px;padding:0 20px;width:100%;}
  @media(max-width:480px){body{padding:24px 16px;}.btn{width:100%;justify-content:center;}}
  @media(min-width:768px){body{padding:60px 24px;}}
  .illus{width:100px;height:100px;margin:0 auto 28px;position:relative;}
  .timer-ring{position:absolute;inset:0;}
  .timer-core{position:absolute;inset:20px;background:rgba(245,158,11,.08);border-radius:50%;border:1.5px solid rgba(245,158,11,.25);display:flex;align-items:center;justify-content:center;}
  .badge{display:inline-flex;align-items:center;gap:6px;background:rgba(245,158,11,.1);border:1px solid rgba(245,158,11,.25);color:#fcd34d;font-size:11px;font-weight:700;padding:5px 14px;border-radius:20px;margin-bottom:16px;}
  h1{font-size:24px;font-weight:800;margin-bottom:10px;}
  p{font-size:14px;line-height:1.7;color:rgba(255,255,255,.45);margin-bottom:28px;max-width:310px;margin-left:auto;margin-right:auto;}
  .btn{display:inline-flex;align-items:center;gap:8px;background:linear-gradient(135deg,#7c3aed,#6366f1);color:#fff;font-size:13px;font-weight:700;padding:12px 28px;border-radius:14px;text-decoration:none;box-shadow:0 6px 20px rgba(124,58,237,.35);transition:opacity .2s,transform .2s;cursor:pointer;border:none;}
  .btn:hover{opacity:.9;transform:translateY(-1px);}
  footer{position:relative;z-index:10;margin-top:40px;font-size:11px;color:rgba(255,255,255,.2);}
  @keyframes rotate{to{transform:rotate(360deg);}}
  @keyframes countdown{0%{stroke-dashoffset:0;}100%{stroke-dashoffset:283;}}
</style>
</head>
<body>
<div class="bg-grid"></div>
<div class="bg-orb-1"></div>
<div class="bg-orb-2"></div>
<div class="card">
  <div class="illus">
    <svg class="timer-ring" viewBox="0 0 100 100">
      <circle cx="50" cy="50" r="45" stroke="rgba(245,158,11,.15)" stroke-width="3" fill="none"/>
      <circle cx="50" cy="50" r="45" stroke="#f59e0b" stroke-width="3" fill="none"
              stroke-dasharray="283" stroke-dashoffset="0" stroke-linecap="round"
              style="transform:rotate(-90deg);transform-origin:50% 50%;animation:countdown 60s linear forwards;"/>
    </svg>
    <div class="timer-core">
      <svg width="32" height="32" fill="none" viewBox="0 0 24 24">
        <path stroke="#f59e0b" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" d="M12 2v4m0 12v4M4.93 4.93l2.83 2.83m8.48 8.48l2.83 2.83M2 12h4m12 0h4M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83"/>
        <circle cx="12" cy="12" r="4" stroke="#f59e0b" stroke-width="1.8" fill="rgba(245,158,11,.15)"/>
      </svg>
    </div>
  </div>
  <div class="badge">⏱ Sesi Kedaluwarsa</div>
  <h1>Halamanmu Sudah Kedaluwarsa</h1>
  <p>Token keamanan sesi kamu sudah habis karena terlalu lama tidak aktif. Segarkan halaman untuk melanjutkan.</p>
  <button onclick="location.reload()" class="btn">
    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
    Segarkan Halaman
  </button>
</div>
<footer>© {{ date('Y') }} RUTIP · Ruang Titip</footer>
</body>
</html>
