<!doctype html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Beranda &middot; RuangTitip</title>
<link rel="icon" type="image/svg+xml" href="{{ asset('assets/favicon.svg') }}">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,700;12..96,800&family=Figtree:wght@400;500;600;700&display=swap">
@php
    function rupiah($n) { return 'Rp' . number_format($n, 0, ',', '.'); }
@endphp
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
  --tape-soft:#F6E3D3;
  --depot:#2E5A45;
  --depot-light:#EAF1EC;
  --body:#4F4A40;
  --muted:#5C574D;
  --line:#DDD5C4;
  --line-strong:#CFC6B3;
  --font-display:'Bricolage Grotesque','Figtree',system-ui,sans-serif;
  --font-body:'Figtree',system-ui,sans-serif;
}
*,*::before,*::after{box-sizing:border-box}
body{margin:0;font-family:var(--font-body);background:var(--cream);color:var(--ink);font-size:16px;line-height:1.6;-webkit-font-smoothing:antialiased}
img{display:block;max-width:100%}
a{color:var(--tape-dark)}
h1,h2,h3{font-family:var(--font-display);margin:0;line-height:1.1}
p{margin:0}
button{font:inherit;color:inherit}
:focus-visible{outline:3px solid var(--tape);outline-offset:3px}
.wrap{max-width:1248px;margin:0 auto;padding:0 24px}

/* Tombol */
.btn{display:inline-flex;align-items:center;justify-content:center;gap:8px;min-height:48px;padding:0 22px;border-radius:999px;font-weight:700;font-size:15px;text-decoration:none;border:1.5px solid transparent;cursor:pointer;white-space:nowrap;transition:transform .15s,background .15s,border-color .15s}
.btn:hover{transform:translateY(-1px)}
.btn-primary{background:var(--tape);color:#fff;border-color:var(--ink);box-shadow:4px 4px 0 var(--ink)}
.btn-primary:hover{background:var(--tape-dark);color:#fff}
.btn-primary:active{transform:translate(2px,2px);box-shadow:2px 2px 0 var(--ink)}
.btn-outline{background:var(--paper);color:var(--ink);border-color:var(--ink)}
.btn-outline:hover{background:var(--sand);color:var(--ink)}
.btn-sm{min-height:40px;padding:0 16px;font-size:14px}
.btn-ghost{background:none;border:0;color:var(--muted);font-weight:600;min-height:40px;padding:0 12px;cursor:pointer}
.btn-ghost:hover{color:var(--ink)}

/* ================= Navbar ================= */
.nav{position:sticky;top:0;z-index:40;background:rgba(245,241,232,.95);backdrop-filter:blur(8px);border-bottom:1px solid var(--line)}
.nav-in{display:flex;align-items:center;gap:32px;height:72px}
.nav .logo img{height:36px;width:auto}
.menu{display:flex;gap:4px;list-style:none;margin:0;padding:0;flex-grow:1;justify-content:center}
.menu a{display:block;padding:10px 14px;border-radius:999px;color:var(--ink);text-decoration:none;font-weight:600;font-size:15px}
.menu a:hover{background:var(--sand)}
.menu a[aria-current="page"]{background:var(--ink);color:var(--cream)}
.nav-right{display:flex;align-items:center;gap:6px}
.icon-btn{position:relative;width:44px;height:44px;border-radius:50%;display:grid;place-items:center;color:var(--ink);text-decoration:none;border:0;background:none;cursor:pointer}
.icon-btn:hover{background:var(--sand)}
.icon-btn .badge{position:absolute;top:8px;right:8px;width:9px;height:9px;border-radius:50%;background:var(--tape);border:2px solid var(--cream)}
.dd-wrap{position:relative}
.dd-wrap summary{list-style:none;cursor:pointer}
.dd-wrap summary::-webkit-details-marker{display:none}
.user summary{display:flex;align-items:center;gap:10px;padding:4px 10px 4px 4px;border-radius:999px;border:1px solid var(--line-strong);background:var(--paper)}
.avatar{width:36px;height:36px;border-radius:50%;background:var(--tape);color:#fff;display:grid;place-items:center;font-weight:700;font-size:14px;flex-shrink:0}
.user .who strong{display:block;font-size:14px;line-height:1.2}
.user .who span{font-size:12px;color:var(--depot);font-weight:600}
.dd{position:absolute;right:0;top:calc(100% + 8px);width:240px;background:var(--paper);border:1.5px solid var(--ink);border-radius:14px;box-shadow:5px 5px 0 var(--ink);padding:8px;list-style:none;margin:0}
.dd a{display:block;padding:10px 12px;border-radius:8px;color:var(--ink);text-decoration:none;font-weight:500;font-size:15px}
.dd a:hover{background:var(--sand)}
.dd hr{border:0;border-top:1px solid var(--line);margin:6px 0}
.dd .dd-head{padding:6px 12px 10px}
.dd .dd-head strong{display:block;font-size:14px}
.dd .dd-head span{font-size:12px;color:var(--muted)}
.dd .dd-empty{padding:16px 12px;text-align:center;font-size:13px;color:var(--muted)}
.dd .dd-item{padding:10px 12px;border-radius:8px}
.dd .dd-item p{font-size:13px;line-height:1.4;color:var(--ink)}
.dd .dd-item span{font-size:11px;color:var(--muted)}
.notif-dd{width:300px}
.notif-dd .dd-item + .dd-item{border-top:1px solid var(--line)}

/* ================= Banner profil ================= */
.notice{margin-top:24px;display:flex;align-items:center;gap:16px;padding:16px 20px;background:var(--tape-soft);border:1.5px solid var(--tape);border-radius:14px}
.notice .ic{width:40px;height:40px;border-radius:10px;background:var(--tape);display:grid;place-items:center;flex-shrink:0}
.notice .txt{flex-grow:1}
.notice strong{display:block;font-size:15px}
.notice span{font-size:14px;color:var(--body)}
.notice .acts{display:flex;align-items:center;gap:4px}

/* ================= Hero sapaan ================= */
.hero{margin-top:24px;position:relative;display:grid;grid-template-columns:1.3fr 1fr;gap:24px;background:var(--sand);border:1.5px solid var(--ink);border-radius:24px;overflow:hidden}
.hero-copy{padding:48px 0 48px 48px;display:flex;flex-direction:column;gap:18px}
.hello{align-self:flex-start;font-size:14px;font-weight:700;color:var(--depot);background:var(--paper);border:1px solid var(--line-strong);padding:6px 14px;border-radius:999px}
.hero h1{font-size:clamp(36px,4.4vw,56px);font-weight:800;letter-spacing:-1.8px;line-height:1}
.hero h1 em{font-style:normal;color:var(--tape)}
.hero p{color:var(--body);font-size:17px;max-width:480px}
.hero-actions{display:flex;flex-wrap:wrap;gap:12px;margin-top:6px}
.hero-art{position:relative;min-height:340px}
.hero-art .disc{position:absolute;right:-60px;bottom:-80px;width:420px;height:420px;border-radius:50%;background:var(--cream);border:1.5px solid var(--ink)}
.hero-art .ruru{position:absolute;right:56px;bottom:20px;width:240px;height:auto;animation:bob 4s ease-in-out infinite}
@keyframes bob{0%,100%{transform:translateY(0)}50%{transform:translateY(-6px)}}
.hero-art .say{position:absolute;top:24px;right:240px;background:var(--paper);border:1.5px solid var(--ink);border-radius:16px;box-shadow:4px 4px 0 var(--ink);padding:12px 16px;font-family:var(--font-display);font-weight:800;font-size:18px;line-height:1.2;max-width:210px}
.hero-art .say::after{content:"";position:absolute;right:30px;bottom:-9px;width:16px;height:16px;background:var(--paper);border-right:1.5px solid var(--ink);border-bottom:1.5px solid var(--ink);transform:rotate(45deg)}

/* ================= Akses cepat ================= */
.quick{margin-top:24px;display:grid;grid-template-columns:repeat(4,1fr);gap:16px}
.quick a{display:flex;align-items:center;gap:14px;padding:18px;background:var(--paper);border:1px solid var(--line-strong);border-radius:16px;color:var(--ink);text-decoration:none;transition:border-color .15s,transform .15s}
.quick a:hover{border-color:var(--ink);transform:translateY(-2px)}
.quick .ic{width:46px;height:46px;border-radius:12px;display:grid;place-items:center;flex-shrink:0;border:1.5px solid var(--ink)}
.quick strong{display:block;font-size:15px;line-height:1.3}
.quick span{font-size:13px;color:var(--muted)}
.bg-tape{background:var(--tape-soft)}
.bg-depot{background:var(--depot-light)}
.bg-sand{background:var(--sand)}
.bg-cream{background:var(--cream)}

/* ================= Section ================= */
.sec{margin-top:56px}
.sec-head{display:flex;justify-content:space-between;align-items:flex-end;gap:16px;margin-bottom:18px}
.sec-head h2{font-size:28px;font-weight:800;letter-spacing:-.6px}
.sec-head p{font-size:15px;color:var(--muted);margin-top:4px}
.see-all{display:inline-flex;align-items:center;gap:6px;font-weight:700;font-size:15px;text-decoration:none;color:var(--tape-dark);padding:8px 0}
.see-all:hover{color:#7A3510}

/* Empty state */
.empty{display:flex;align-items:center;gap:24px;padding:32px;background:var(--paper);border:1.5px dashed var(--line-strong);border-radius:18px}
.empty .ic{width:64px;height:64px;border-radius:16px;display:grid;place-items:center;flex-shrink:0;border:1.5px solid var(--ink)}
.empty .txt{flex-grow:1}
.empty h3{font-size:20px;font-weight:700;margin-bottom:4px}
.empty p{color:var(--body);font-size:15px}

/* Kartu produk */
.grid-cards{display:grid;grid-template-columns:repeat(4,1fr);gap:16px}
.grid-cards.cols-3{grid-template-columns:repeat(3,1fr)}
.card{background:var(--paper);border:1px solid var(--line-strong);border-radius:16px;overflow:hidden;text-decoration:none;color:var(--ink);display:block;transition:border-color .15s,transform .15s}
.card:hover{border-color:var(--ink);transform:translateY(-2px)}
.card .thumb{aspect-ratio:4/3;background:var(--sand);display:flex;align-items:center;justify-content:center;overflow:hidden}
.card .thumb img{width:100%;height:100%;object-fit:cover}
.card .body{padding:14px 16px 16px}
.card strong{display:block;font-size:15px;margin-bottom:2px}
.card .meta{font-size:12px;color:var(--muted);margin-bottom:8px;display:flex;align-items:center;gap:4px}
.card .price{font-weight:700;color:var(--tape-dark)}
.card .bar{height:6px;border-radius:99px;background:var(--sand);overflow:hidden;margin:8px 0}
.card .bar span{display:block;height:100%;background:var(--tape)}
.card .tags{display:flex;flex-wrap:wrap;gap:6px;margin-bottom:10px}
.card .tag-sm{font-size:11px;font-weight:600;padding:3px 9px;border-radius:999px;background:var(--sand);color:var(--body)}
.card .cond{display:inline-block;font-size:11px;font-weight:700;padding:3px 9px;border-radius:999px;background:var(--depot-light);color:var(--depot);margin-bottom:8px}

/* Promo preloved */
.promo{display:flex;align-items:center;gap:20px;padding:24px 28px;background:var(--ink);color:var(--cream);border-radius:18px;margin-bottom:16px}
.promo .ic{width:52px;height:52px;border-radius:14px;background:var(--tape-light);display:grid;place-items:center;flex-shrink:0}
.promo .txt{flex-grow:1}
.promo strong{display:block;font-family:var(--font-display);font-size:20px;font-weight:700}
.promo span{font-size:14px;color:#C9C2B3}
.promo .btn{background:var(--tape-light);color:var(--ink);border-color:var(--tape-light)}
.promo .btn:hover{background:#F0B98F;color:var(--ink)}

/* Testimoni */
.testi{display:grid;grid-template-columns:repeat(3,1fr);gap:16px}
.testi figure{margin:0;display:flex;flex-direction:column;justify-content:space-between;gap:20px;padding:24px;background:var(--paper);border:1px solid var(--line-strong);border-radius:16px}
.testi blockquote{margin:0;font-size:16px;line-height:1.55}
.testi figcaption{display:flex;align-items:center;gap:12px}
.testi .av{width:40px;height:40px;border-radius:50%;display:grid;place-items:center;font-weight:700;font-size:14px;border:1.5px solid var(--ink)}
.testi figcaption strong{display:block;font-size:15px;line-height:1.2}
.testi figcaption span{font-size:13px;color:var(--muted)}

/* ================= Footer ================= */
.footer{margin-top:96px;background:var(--ink);color:var(--cream);padding:64px 0 40px}
.foot-grid{display:grid;grid-template-columns:1.4fr 1fr 1fr 1.2fr;gap:32px;font-size:15px}
.foot-grid h3{font-family:var(--font-body);font-size:13px;font-weight:700;letter-spacing:.8px;text-transform:uppercase;color:#A39C8D;margin-bottom:14px}
.foot-grid ul{list-style:none;margin:0;padding:0;display:flex;flex-direction:column;gap:10px}
.foot-grid a{color:#D6D0C3;text-decoration:none}
.foot-grid a:hover{color:var(--tape-light)}
.foot-brand img{height:38px;width:auto;margin-bottom:14px}
.foot-brand p{color:#BDB6A7;max-width:300px}
.btn-wa{margin-top:8px;background:var(--depot);color:#fff;border-color:var(--depot)}
.btn-wa:hover{background:#244A38;color:#fff}
.foot-bottom{display:flex;justify-content:space-between;gap:16px;flex-wrap:wrap;margin-top:48px;padding-top:24px;border-top:1px solid #3A3833;font-size:13px;color:#A39C8D}
.foot-bottom a{color:#A39C8D;margin-left:16px}

/* Tombol WA mengambang */
.fab{position:fixed;right:24px;bottom:24px;z-index:30;width:58px;height:58px;border-radius:50%;background:var(--depot);display:grid;place-items:center;border:1.5px solid var(--ink);box-shadow:4px 4px 0 var(--ink)}
.fab:hover{background:#244A38}

/* Tab bar bawah (HP) */
.tabbar{display:none}

/* ================= Responsif ================= */
@media (max-width:1080px){
  .menu{display:none}
  .nav-in{justify-content:space-between}
  .user .who{display:none}
  .quick{grid-template-columns:repeat(2,1fr)}
  .grid-cards{grid-template-columns:repeat(2,1fr)}
  .testi{grid-template-columns:1fr}
  .foot-grid{grid-template-columns:1fr 1fr}
  .tabbar{display:grid;grid-template-columns:repeat(5,1fr);position:fixed;left:0;right:0;bottom:0;z-index:40;background:var(--paper);border-top:1.5px solid var(--ink);padding:6px 4px calc(6px + env(safe-area-inset-bottom))}
  .tabbar a{display:flex;flex-direction:column;align-items:center;gap:2px;padding:6px 0;font-size:11px;font-weight:600;color:var(--muted);text-decoration:none;min-height:48px;justify-content:center}
  .tabbar a[aria-current="page"]{color:var(--tape-dark)}
  body{padding-bottom:76px}
  .fab{bottom:92px}
}
@media (max-width:720px){
  .hero{grid-template-columns:1fr}
  .hero-copy{padding:32px 24px 0}
  .hero-art{min-height:260px}
  .hero-art .ruru{width:180px;right:24px}
  .hero-art .say{left:auto;right:16px;top:40px;font-size:16px}
  .hero-art .disc{width:320px;height:320px;right:-40px;bottom:-110px}
  .notice{flex-wrap:wrap}
  .notice .acts{width:100%;justify-content:flex-end}
  .empty{flex-direction:column;align-items:flex-start;padding:24px}
  .promo{flex-wrap:wrap;padding:20px}
  .quick a{flex-direction:column;align-items:flex-start;gap:10px;padding:16px}
  .foot-grid{grid-template-columns:1fr}
  .sec-head h2{font-size:24px}
  .notif-dd{width:260px}
}
@media (prefers-reduced-motion:reduce){
  .hero-art .ruru{animation:none}
  .btn:hover,.quick a:hover{transform:none}
}
</style>
</head>
<body>

{{-- ================= NAVBAR ================= --}}
<header class="nav">
  <div class="wrap nav-in">
    <a class="logo" href="{{ route('dashboard') }}" aria-label="RuangTitip, ke beranda"><img src="{{ asset('assets/logo-ruangtitip.svg') }}" alt="RuangTitip" width="200" height="50"></a>
    <nav aria-label="Menu utama">
      <ul class="menu">
        <li><a href="{{ route('dashboard') }}" aria-current="page">Beranda</a></li>
        <li><a href="{{ route('ruang-titip.index') }}">Ruang Titip</a></li>
        <li><a href="{{ route('packing.index') }}">Toko Packing</a></li>
        <li><a href="{{ route('preloved.index') }}">Toko Preloved</a></li>
        <li><a href="{{ route('pesanan.index') }}">Pesanan Saya</a></li>
      </ul>
    </nav>
    <div class="nav-right">
      <a class="icon-btn" href="{{ route('preloved.cart.index') }}" aria-label="Keranjang">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="20" r="1.4"/><circle cx="18" cy="20" r="1.4"/><path d="M3 4h2l2.4 11.2a1.5 1.5 0 0 0 1.5 1.2h8.6a1.5 1.5 0 0 0 1.5-1.2L21 8H6.2"/></svg>
      </a>

      <details class="dd-wrap" name="navMenus">
        <summary class="icon-btn" aria-label="Notifikasi{{ $notifUnreadCount > 0 ? ', ada yang baru' : '' }}">
          <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M6 8a6 6 0 1 1 12 0c0 7 3 8 3 8H3s3-1 3-8"/><path d="M10 20a2 2 0 0 0 4 0"/></svg>
          @if ($notifUnreadCount > 0)
            <span class="badge" aria-hidden="true"></span>
          @endif
        </summary>
        <ul class="dd notif-dd">
          <li class="dd-head"><strong>Notifikasi</strong><span>{{ $notifUnreadCount }} baru</span></li>
          @forelse ($notifications as $n)
            <li class="dd-item">
              <p>{{ $n['text'] }}</p>
              <span>{{ $n['at']?->diffForHumans() }}</span>
            </li>
          @empty
            <li class="dd-empty">Belum ada notifikasi.</li>
          @endforelse
        </ul>
      </details>

      <details class="dd-wrap user" name="navMenus">
        <summary aria-label="Menu akun">
          <span class="avatar" aria-hidden="true">{{ $initials ?: '?' }}</span>
          <span class="who"><strong>{{ $user?->name ?? 'Pengguna' }}</strong><span>Penitip aktif</span></span>
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m6 9 6 6 6-6"/></svg>
        </summary>
        <ul class="dd">
          <li><a href="{{ route('profile.index') }}">Profil saya</a></li>
          <li><a href="{{ route('pesanan.index') }}">Pesanan saya</a></li>
          <li><a href="{{ route('profile.index', ['tab' => 'bantuan']) }}">Bantuan</a></li>
          <li><hr></li>
          <li>
            <form method="POST" action="{{ route('logout') }}">
              @csrf
              <button type="submit" style="width:100%;text-align:left;background:none;border:0;padding:10px 12px;border-radius:8px;font-weight:500;font-size:15px;color:var(--ink);cursor:pointer;">Keluar</button>
            </form>
          </li>
        </ul>
      </details>
    </div>
  </div>
</header>

<main class="wrap">

  {{-- ================= BANNER LENGKAPI PROFIL ================= --}}
  @if ((empty($user->name) || empty($user->phone)) && ! session('profile_banner_dismissed'))
  <div class="notice" id="notice" role="region" aria-label="Pengingat profil">
    <span class="ic" aria-hidden="true">
      <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="4"/><path d="M4 21a8 8 0 0 1 16 0"/></svg>
    </span>
    <div class="txt">
      <strong>Lengkapi profil kamu dulu, yuk</strong>
      <span>Isi nama dan nomor WhatsApp supaya tim Ruru bisa menghubungimu soal pesanan.</span>
    </div>
    <div class="acts">
      <a class="btn btn-primary btn-sm" href="{{ route('profile.index') }}">Lengkapi sekarang</a>
      <button class="btn-ghost" type="button" id="skip-notice">Nanti saja</button>
    </div>
  </div>
  @endif

  {{-- ================= HERO SAPAAN ================= --}}
  <section class="hero" aria-labelledby="hero-title">
    <div class="hero-copy">
      <span class="hello">Selamat datang, {{ $user->name ? explode(' ', $user->name)[0] : 'Penitip' }}!</span>
      <h1 id="hero-title">Titip barangmu, <em>simpan uangmu.</em></h1>
      <p>Mau pulang kampung, magang, atau pindah kos? Titipkan barangmu di gudang RuangTitip dan nggak perlu bayar kos kosong.</p>
      <div class="hero-actions">
        <a class="btn btn-primary" href="{{ route('ruang-titip.index') }}">
          Titip barang sekarang
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
        </a>
        <a class="btn btn-outline" href="{{ route('pesanan.index') }}">Lihat pesanan aktif</a>
      </div>
    </div>
    <div class="hero-art" aria-hidden="true">
      <div class="disc"></div>
      <img class="ruru" src="{{ asset('assets/ruru/ruru-halo.webp') }}" alt="" width="800" height="800">
      <p class="say">Barangmu mau dititip kapan?</p>
    </div>
  </section>

  {{-- ================= AKSES CEPAT ================= --}}
  <nav class="quick" aria-label="Akses cepat">
    <a href="{{ route('ruang-titip.index') }}">
      <span class="ic bg-tape" aria-hidden="true"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#1C1B18" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M21 8 12 3 3 8v8l9 5 9-5z"/><path d="m3 8 9 5 9-5M12 13v8"/></svg></span>
      <span><strong>Titip barang</strong><span>Jemput dari kos</span></span>
    </a>
    <a href="{{ route('pesanan.index') }}">
      <span class="ic bg-depot" aria-hidden="true"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#1C1B18" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="5" width="18" height="16" rx="2"/><path d="M3 10h18M8 3v4M16 3v4"/></svg></span>
      <span><strong>Perpanjang titipan</strong><span>Tambah durasi simpan</span></span>
    </a>
    <a href="{{ route('packing.index') }}">
      <span class="ic bg-sand" aria-hidden="true"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#1C1B18" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M4 7h16v13H4zM4 7l2-4h12l2 4M10 11h4"/></svg></span>
      <span><strong>Beli perlengkapan</strong><span>Kardus, lakban, bubble</span></span>
    </a>
    <a href="{{ route('preloved.cara-jual') }}">
      <span class="ic bg-cream" aria-hidden="true"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#1C1B18" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M20.6 13.4 13.4 20.6a2 2 0 0 1-2.8 0L3 13V3h10l7.6 7.6a2 2 0 0 1 0 2.8z"/><circle cx="7.5" cy="7.5" r="1.5"/></svg></span>
      <span><strong>Jual barang</strong><span>Lewat Toko Preloved</span></span>
    </a>
  </nav>

  {{-- ================= RUANG TITIP ================= --}}
  <section class="sec" aria-labelledby="sec-gudang">
    <div class="sec-head">
      <div><h2 id="sec-gudang">Ruang Titip</h2><p>Gudang penyimpanan terdekat dari kosmu.</p></div>
      <a class="see-all" href="{{ route('ruang-titip.index') }}">Lihat semua <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m9 6 6 6-6 6"/></svg></a>
    </div>
    @forelse ($storages as $s)
      @if ($loop->first)<div class="grid-cards cols-3">@endif
        <a class="card" href="{{ route('ruang-titip.detail', $s) }}">
          <div class="thumb">
            @if ($s->primary_photo)
              <img src="{{ asset('storage/'.$s->primary_photo) }}" alt="{{ $s->name }}">
            @else
              <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="#9A4415" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M3 10 12 4l9 6v10H3z"/><path d="M8 20v-6h8v6M8 17h8"/></svg>
            @endif
          </div>
          <div class="body">
            <strong>{{ $s->name }}</strong>
            <div class="meta">
              <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="10" r="3"/><path d="M12 21s-7-7.5-7-12a7 7 0 0 1 14 0c0 4.5-7 12-7 12z"/></svg>
              {{ $s->address }}
            </div>
            @if (isset($s->facilities) && count($s->facilities ?? []))
              <div class="tags">
                @foreach (array_slice($s->facilities, 0, 3) as $tag)
                  <span class="tag-sm">{{ $tag }}</span>
                @endforeach
              </div>
            @endif
            <div class="bar"><span style="width:{{ $s->capacity_pct }}%"></span></div>
            <p class="price">{{ rupiah($s->min_price) }} <span style="color:var(--muted);font-weight:500;font-size:12px">/ hari</span></p>
          </div>
        </a>
      @if ($loop->last)</div>@endif
    @empty
      <div class="empty">
        <span class="ic bg-depot" aria-hidden="true"><svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#1C1B18" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 10 12 4l9 6v10H3z"/><path d="M8 20v-6h8v6M8 17h8"/></svg></span>
        <div class="txt">
          <h3>Gudang belum tersedia di kotamu</h3>
          <p>Ruru lagi siapin gudangnya. Kami kabari lewat notifikasi begitu sudah buka.</p>
        </div>
      </div>
    @endforelse
  </section>

  {{-- ================= TOKO PACKING ================= --}}
  <section class="sec" aria-labelledby="sec-packing">
    <div class="sec-head">
      <div><h2 id="sec-packing">Toko Packing</h2><p>Kardus, lakban, dan bubble wrap untuk titipanmu.</p></div>
      <a class="see-all" href="{{ route('packing.index') }}">Lihat semua <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m9 6 6 6-6 6"/></svg></a>
    </div>
    @forelse ($packing as $p)
      @if ($loop->first)<div class="grid-cards">@endif
        <a class="card" href="{{ route('packing.show', $p) }}">
          <div class="thumb">
            @if ($p->primary_image)
              <img src="{{ asset('storage/'.$p->primary_image) }}" alt="{{ $p->name }}">
            @else
              <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#9A4415" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 8 12 3 3 8v8l9 5 9-5V8z"/><path d="M3 8l9 5 9-5"/><path d="M12 13v8"/></svg>
            @endif
          </div>
          <div class="body">
            <strong>{{ $p->name }}</strong>
            <p class="price">{{ rupiah($p->price) }}</p>
            <div class="meta">Stok: {{ $p->stock }}</div>
          </div>
        </a>
      @if ($loop->last)</div>@endif
    @empty
      <div class="empty">
        <span class="ic bg-tape" aria-hidden="true"><svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#1C1B18" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M21 8 12 3 3 8v8l9 5 9-5z"/><path d="m3 8 9 5 9-5M12 13v8"/></svg></span>
        <div class="txt">
          <h3>Produk packing segera hadir</h3>
          <p>Sementara itu, tim kami tetap bisa bawa perlengkapan packing saat menjemput barangmu.</p>
        </div>
      </div>
    @endforelse
  </section>

  {{-- ================= TOKO PRELOVED ================= --}}
  <section class="sec" aria-labelledby="sec-preloved">
    <div class="sec-head">
      <div><h2 id="sec-preloved">Toko Preloved</h2><p>Barang bekas mahasiswa, masih layak pakai.</p></div>
      <a class="see-all" href="{{ route('preloved.index') }}">Lihat semua <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m9 6 6 6-6 6"/></svg></a>
    </div>
    <div class="promo">
      <span class="ic" aria-hidden="true"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#1C1B18" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.6 13.4 13.4 20.6a2 2 0 0 1-2.8 0L3 13V3h10l7.6 7.6a2 2 0 0 1 0 2.8z"/><circle cx="7.5" cy="7.5" r="1.5"/></svg></span>
      <div class="txt">
        <strong>Punya barang nganggur? Jual di sini.</strong>
        <span>Gratis pasang barang, bayar saat terjual.</span>
      </div>
      <a class="btn btn-sm" href="{{ route('preloved.cara-jual') }}">Pelajari</a>
    </div>
    @forelse ($preloved as $p)
      @if ($loop->first)<div class="grid-cards">@endif
        <a class="card" href="{{ route('preloved.show', $p->id) }}">
          <div class="thumb">
            @if ($p->primary_photo)
              <img src="{{ asset('storage/'.$p->primary_photo) }}" alt="{{ $p->name }}">
            @else
              <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#9A4415" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><path d="M3 6h18M16 10a4 4 0 0 1-8 0"/></svg>
            @endif
          </div>
          <div class="body">
            <span class="cond">{{ $p->condition }}%</span>
            <strong>{{ $p->name }}</strong>
            <p class="price">{{ rupiah($p->price) }}</p>
          </div>
        </a>
      @if ($loop->last)</div>@endif
    @empty
      <div class="empty">
        <span class="ic bg-sand" aria-hidden="true"><svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#1C1B18" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><path d="M3 6h18M16 10a4 4 0 0 1-8 0"/></svg></span>
        <div class="txt">
          <h3>Belum ada barang preloved</h3>
          <p>Jadi yang pertama pasang barang, biar lebih cepat dilihat pembeli.</p>
        </div>
        <a class="btn btn-outline btn-sm" href="{{ route('preloved.cara-jual') }}">Jual barang</a>
      </div>
    @endforelse
  </section>

  {{-- ================= TESTIMONI ================= --}}
  <section class="sec" aria-labelledby="sec-testi">
    <div class="sec-head">
      <div><h2 id="sec-testi">Kata mereka</h2><p>Cerita dari penitip RuangTitip.</p></div>
    </div>
    <div class="testi">
      @foreach ($testimonials as $t)
        <figure>
          <blockquote>&ldquo;{{ $t['text'] }}&rdquo;</blockquote>
          <figcaption><span class="av bg-{{ $t['color'] }}" aria-hidden="true">{{ $t['avatar'] }}</span><span><strong>{{ $t['name'] }}</strong><span>{{ $t['major'] }}</span></span></figcaption>
        </figure>
      @endforeach
    </div>
  </section>
</main>

{{-- ================= FOOTER ================= --}}
<footer class="footer">
  <div class="wrap">
    <div class="foot-grid">
      <div class="foot-brand">
        <img src="{{ asset('assets/logo-ruangtitip-putih.svg') }}" alt="RuangTitip" width="200" height="50">
        <p>Solusi titip barang untuk mahasiswa dan perantau. Aman, hemat, dan mudah.</p>
      </div>
      <div>
        <h3>Layanan</h3>
        <ul>
          <li><a href="{{ route('ruang-titip.index') }}">Titip barang</a></li>
          <li><a href="{{ route('packing.index') }}">Toko Packing</a></li>
          <li><a href="{{ route('preloved.index') }}">Toko Preloved</a></li>
          <li><a href="{{ route('pesanan.index') }}">Pesanan saya</a></li>
        </ul>
      </div>
      <div>
        <h3>Akun</h3>
        <ul>
          <li><a href="{{ route('profile.index') }}">Profil saya</a></li>
          <li><a href="{{ route('pesanan.index') }}">Pesanan saya</a></li>
          <li><a href="{{ route('profile.index', ['tab' => 'bantuan']) }}">Bantuan</a></li>
        </ul>
      </div>
      <div>
        <h3>Kontak</h3>
        <ul>
          <li><a href="mailto:ruangtitipmu@gmail.com">ruangtitipmu@gmail.com</a></li>
          <li><a href="https://wa.me/6285121091134" target="_blank" rel="noopener">+62 851-2109-1134</a></li>
          <li>Malang, Jawa Timur</li>
        </ul>
        <a class="btn btn-wa btn-sm" href="https://wa.me/6285121091134" target="_blank" rel="noopener">Chat via WhatsApp</a>
      </div>
    </div>
    <div class="foot-bottom">
      <span>&copy; 2026 RuangTitip &middot; Malang, Indonesia</span>
      <span><a href="{{ route('legal.terms') }}">Syarat &amp; Ketentuan</a><a href="{{ route('legal.privacy') }}">Kebijakan Privasi</a></span>
    </div>
  </div>
</footer>

{{-- Tombol WhatsApp mengambang --}}
<a class="fab" href="https://wa.me/6285121091134" target="_blank" rel="noopener" aria-label="Chat dengan tim RuangTitip lewat WhatsApp">
  <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12a8.5 8.5 0 0 1-12.6 7.4L3 21l1.6-5.2A8.5 8.5 0 1 1 21 12z"/></svg>
</a>

{{-- Tab bar bawah, hanya tampil di HP/tablet --}}
<nav class="tabbar" aria-label="Menu utama (seluler)">
  <a href="{{ route('dashboard') }}" aria-current="page"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><path d="M3 11 12 4l9 7v9h-6v-6H9v6H3z"/></svg>Beranda</a>
  <a href="{{ route('ruang-titip.index') }}"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><path d="M21 8 12 3 3 8v8l9 5 9-5z"/><path d="m3 8 9 5 9-5"/></svg>Titip</a>
  <a href="{{ route('packing.index') }}"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><path d="M4 7h16v13H4zM4 7l2-4h12l2 4"/></svg>Packing</a>
  <a href="{{ route('preloved.index') }}"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><path d="M20.6 13.4 13.4 20.6a2 2 0 0 1-2.8 0L3 13V3h10l7.6 7.6a2 2 0 0 1 0 2.8z"/></svg>Preloved</a>
  <a href="{{ route('pesanan.index') }}"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><path d="M9 4h6l1 2h3v15H5V6h3z"/><path d="M9 12h6M9 16h4"/></svg>Pesanan</a>
</nav>

<script>
(function () {
  /* Tutup banner profil */
  var skip = document.getElementById('skip-notice');
  if (skip) skip.addEventListener('click', function () {
    document.getElementById('notice').remove();
    fetch('{{ route('profile.dismiss-banner') }}', {
      method: 'POST',
      headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        'Accept': 'application/json',
      },
    });
  });

  /* Tutup dropdown saat klik di luar */
  document.addEventListener('click', function (e) {
    document.querySelectorAll('.dd-wrap[open]').forEach(function (d) {
      if (!d.contains(e.target)) d.removeAttribute('open');
    });
  });
})();
</script>
</body>
</html>
