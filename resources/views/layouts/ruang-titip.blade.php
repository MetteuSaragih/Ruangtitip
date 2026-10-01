<!doctype html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>@yield('title', 'Ruang Titip') &middot; RuangTitip</title>
<link rel="icon" type="image/svg+xml" href="{{ asset('assets/favicon.svg') }}">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,700;12..96,800&family=Figtree:wght@400;500;600;700&display=swap">
<style>
:root{
  --ink:#1C1B18;--cream:#F5F1E8;--paper:#FFFDF8;--sand:#E8DFCD;
  --tape:#B4531D;--tape-dark:#9A4415;--tape-light:#E8A677;--tape-soft:#F6E3D3;
  --depot:#2E5A45;--depot-light:#EAF1EC;--danger:#A3321A;
  --body:#4F4A40;--muted:#5C574D;--line:#DDD5C4;--line-strong:#CFC6B3;
  --font-display:'Bricolage Grotesque','Figtree',system-ui,sans-serif;
  --font-body:'Figtree',system-ui,sans-serif;
}
*,*::before,*::after{box-sizing:border-box}
body{margin:0;font-family:var(--font-body);background:var(--cream);color:var(--ink);font-size:16px;line-height:1.6;-webkit-font-smoothing:antialiased}
img{display:block;max-width:100%}
a{color:var(--tape-dark)}
h1,h2,h3{font-family:var(--font-display);margin:0;line-height:1.15}
p{margin:0}
button,input,select,textarea{font:inherit;color:inherit}
:focus-visible{outline:3px solid var(--tape);outline-offset:3px}
.wrap{max-width:1248px;margin:0 auto;padding:0 24px}
.sr{position:absolute;left:-9999px}

.btn{display:inline-flex;align-items:center;justify-content:center;gap:8px;min-height:48px;padding:0 22px;border-radius:999px;font-weight:700;font-size:16px;text-decoration:none;border:1.5px solid transparent;cursor:pointer;white-space:nowrap;transition:transform .15s,background .15s}
.btn:hover{transform:translateY(-1px)}
.btn-primary{background:var(--tape);color:#fff;border-color:var(--ink);box-shadow:3px 3px 0 var(--ink)}
.btn-primary:hover{background:var(--tape-dark)}
.btn-primary:active{transform:translate(2px,2px);box-shadow:1px 1px 0 var(--ink)}
.btn-primary:disabled{background:#B9B1A2;border-color:#9C9585;box-shadow:none;cursor:not-allowed;transform:none}
.btn-outline{background:var(--paper);color:var(--ink);border-color:var(--ink)}
.btn-outline:hover{background:var(--sand)}

/* Navbar (sama dengan beranda) */
.nav{position:sticky;top:0;z-index:40;background:rgba(245,241,232,.95);backdrop-filter:blur(8px);border-bottom:1px solid var(--line)}
.nav-in{display:flex;align-items:center;gap:32px;height:72px}
.nav .logo img{height:36px;width:auto}
.menu{display:flex;gap:4px;list-style:none;margin:0;padding:0;flex-grow:1;justify-content:center}
.menu a{display:block;padding:10px 14px;border-radius:999px;color:var(--ink);text-decoration:none;font-weight:600;font-size:15px}
.menu a:hover{background:var(--sand)}
.menu a[aria-current="page"]{background:var(--ink);color:var(--cream)}
.nav-right{display:flex;align-items:center;gap:6px}
.icon-btn{position:relative;width:44px;height:44px;border-radius:50%;display:grid;place-items:center;color:var(--ink);text-decoration:none}
.icon-btn:hover{background:var(--sand)}
.cart-count{position:absolute;top:2px;right:2px;min-width:16px;height:16px;padding:0 3px;border-radius:999px;background:var(--tape);color:#fff;font-size:10px;font-weight:700;display:grid;place-items:center;border:1.5px solid var(--cream)}
.avatar{width:36px;height:36px;border-radius:50%;background:var(--tape);color:#fff;display:grid;place-items:center;font-weight:700;font-size:14px;border:1.5px solid var(--ink)}

/* Header halaman */
.page-head{padding:40px 0 28px;display:flex;justify-content:space-between;align-items:flex-end;gap:24px;flex-wrap:wrap}
.page-head h1{font-size:clamp(34px,4vw,48px);font-weight:800;letter-spacing:-1.4px}
.page-head p{color:var(--body);margin-top:6px}
.how{display:flex;gap:8px;flex-wrap:wrap}
.how span{display:flex;align-items:center;gap:8px;font-size:14px;font-weight:600;padding:8px 14px;background:var(--paper);border:1px solid var(--line-strong);border-radius:999px}
.how b{width:22px;height:22px;border-radius:50%;background:var(--ink);color:var(--cream);display:grid;place-items:center;font-size:12px}

/* Filter */
.filters{display:flex;gap:12px;flex-wrap:wrap;margin-bottom:24px}
.search{position:relative;flex:1 1 280px}
.search svg{position:absolute;left:16px;top:50%;transform:translateY(-50%);color:var(--muted)}
.search input{width:100%;height:50px;border-radius:999px;border:1.5px solid var(--line-strong);background:var(--paper);padding:0 18px 0 46px;font-size:15px}
.search input:focus{outline:none;border-color:var(--ink)}
.filters select{height:50px;border-radius:999px;border:1.5px solid var(--line-strong);background:var(--paper);padding:0 18px;font-weight:600;font-size:15px;cursor:pointer}

/* Kartu gudang */
.grid{display:grid;grid-template-columns:repeat(3,1fr);gap:20px}
.wh{display:flex;flex-direction:column;background:var(--paper);border:1.5px solid var(--ink);border-radius:18px;overflow:hidden;transition:transform .15s,box-shadow .15s;text-decoration:none;color:var(--ink)}
.wh:hover{transform:translateY(-3px);box-shadow:5px 5px 0 var(--ink)}
.wh .photo{position:relative;aspect-ratio:16/10;background:var(--sand);display:grid;place-items:center;border-bottom:1.5px solid var(--ink);overflow:hidden}
.wh .photo img{position:absolute;inset:0;width:100%;height:100%;object-fit:cover}
.wh .photo .ph{display:flex;flex-direction:column;align-items:center;gap:6px;font-size:13px;color:#6B6557}
.wh .status{position:absolute;top:12px;left:12px;font-size:13px;font-weight:700;padding:5px 12px;border-radius:999px;border:1px solid var(--ink)}
.st-open{background:var(--depot-light);color:#1F4535}
.st-few{background:var(--tape-soft);color:var(--tape-dark)}
.st-full{background:var(--line);color:var(--muted)}
.wh .body{padding:20px;display:flex;flex-direction:column;gap:14px;flex-grow:1}
.wh h2{font-size:22px;font-weight:700;letter-spacing:-.3px}
.loc{display:flex;align-items:center;gap:6px;font-size:14px;color:var(--muted);margin-top:4px}
.cap-row{display:flex;justify-content:space-between;font-size:14px}
.cap-row b{font-weight:700}
.bar{height:8px;border-radius:999px;background:var(--line);overflow:hidden;margin-top:6px}
.bar i{display:block;height:100%;border-radius:999px;background:var(--depot)}
.bar i.warn{background:var(--tape)}
.feat{display:flex;flex-wrap:wrap;gap:6px;list-style:none;margin:0;padding:0}
.feat li{font-size:13px;font-weight:600;padding:4px 10px;border-radius:999px;background:var(--cream);border:1px solid var(--line-strong)}
.wh .foot{margin-top:auto;display:flex;justify-content:space-between;align-items:center;gap:12px;padding-top:16px;border-top:1px solid var(--line)}
.price small{display:block;font-size:13px;color:var(--muted)}
.price strong{font-family:var(--font-display);font-size:24px;font-weight:800;color:var(--tape-dark)}
.price span{font-size:14px;color:var(--muted)}
.btn[aria-disabled="true"]{background:var(--line);color:var(--muted);border-color:var(--line-strong);box-shadow:none;pointer-events:none}

/* Bantuan */
.help{margin-top:40px;display:flex;align-items:center;gap:20px;padding:24px 28px;background:var(--sand);border:1.5px solid var(--ink);border-radius:18px}
.help img{width:84px;height:auto;flex-shrink:0}
.help div{flex-grow:1}
.help strong{display:block;font-family:var(--font-display);font-size:20px}
.help p{color:var(--body);font-size:15px}

/* ================= Wizard titip ================= */
.back-link{display:inline-flex;align-items:center;gap:6px;margin-top:28px;font-weight:600;font-size:15px;color:var(--ink);text-decoration:none}
.back-link:hover{color:var(--tape)}
.layout{display:grid;grid-template-columns:minmax(0,1fr) 360px;gap:32px;margin-top:20px;align-items:start}

.stepper{display:grid;grid-template-columns:repeat(4,1fr);gap:8px;list-style:none;margin:0 0 28px;padding:0;counter-reset:s}
.stepper li{position:relative}
.stepper button{width:100%;display:flex;flex-direction:column;align-items:flex-start;gap:8px;padding:0;border:0;background:none;text-align:left;cursor:default}
.stepper .bar{width:100%;height:6px;border-radius:999px;background:var(--line)}
.stepper .lbl{display:flex;align-items:center;gap:8px;font-size:14px;font-weight:600;color:var(--muted)}
.stepper .num{width:24px;height:24px;border-radius:50%;display:grid;place-items:center;font-size:12px;font-weight:700;border:1.5px solid var(--line-strong);background:var(--paper)}
.stepper li.done .bar{background:var(--depot)}
.stepper li.done .num{background:var(--depot);border-color:var(--depot);color:#fff}
.stepper li.done button{cursor:pointer}
.stepper li.done button:hover .lbl{color:var(--ink)}
.stepper li.now .bar{background:var(--tape)}
.stepper li.now .num{background:var(--tape);border-color:var(--ink);color:#fff}
.stepper li.now .lbl{color:var(--ink)}
.stepper li.skip .lbl{text-decoration:line-through}

.step-head h1{font-size:clamp(28px,3vw,36px);font-weight:800;letter-spacing:-.8px}
.step-head p{color:var(--body);margin-top:4px}
.panel{background:var(--paper);border:1px solid var(--line-strong);border-radius:18px;padding:24px;margin-top:20px}
.panel h2{font-size:19px;font-weight:700}
.panel .hint{font-size:14px;color:var(--muted);margin-top:2px}

.row2{display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-top:16px}
.field{display:flex;flex-direction:column;gap:6px}
.field label{font-weight:600;font-size:14px}
.field input,.field select,.field textarea{min-height:50px;border-radius:12px;border:1.5px solid var(--line-strong);background:var(--cream);padding:10px 14px;font-size:16px;width:100%}
.field textarea{min-height:96px;resize:vertical}
.field input:focus,.field select:focus,.field textarea:focus{outline:none;border-color:var(--ink);background:var(--paper)}
.info{display:flex;align-items:center;gap:10px;margin-top:16px;padding:12px 14px;border-radius:12px;background:var(--depot-light);color:#1F4535;font-size:15px;font-weight:600}
.info.empty{background:var(--cream);color:var(--muted);font-weight:500}

.seg{display:grid;grid-template-columns:repeat(3,1fr);gap:6px;padding:6px;background:var(--sand);border-radius:999px;margin-top:16px}
.seg button{min-height:44px;border-radius:999px;border:0;background:none;font-weight:700;font-size:15px;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:8px}
.seg button[aria-selected="true"]{background:var(--paper);box-shadow:0 0 0 1.5px var(--ink)}

.sizes{display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-top:16px}
.size{display:flex;justify-content:space-between;align-items:center;gap:12px;padding:16px;border:1.5px solid var(--line-strong);border-radius:14px;background:var(--paper)}
.size.on{border-color:var(--tape);background:var(--tape-soft)}
.size strong{display:block;font-size:17px}
.size small{display:block;font-size:13px;color:var(--muted)}
.size .p{font-weight:700;color:var(--tape-dark);font-size:15px}
.qty{display:flex;align-items:center;gap:4px;flex-shrink:0}
.qty button{width:40px;height:40px;border-radius:50%;border:1.5px solid var(--ink);background:var(--paper);font-size:20px;font-weight:700;line-height:1;cursor:pointer;display:grid;place-items:center}
.qty button:hover{background:var(--sand)}
.qty button:disabled{border-color:var(--line-strong);color:var(--line-strong);cursor:not-allowed;background:var(--paper)}
.qty output{min-width:32px;text-align:center;font-weight:700;font-size:17px}

.choices{display:flex;flex-direction:column;gap:12px;margin-top:20px}
.choice{position:relative;display:flex;gap:16px;padding:20px;border:1.5px solid var(--line-strong);border-radius:16px;background:var(--paper);cursor:pointer;transition:border-color .15s;width:100%;text-align:left}
.choice:hover{border-color:var(--ink)}
.choice input{position:absolute;opacity:0}
.choice .ic{width:52px;height:52px;border-radius:14px;border:1.5px solid var(--ink);display:grid;place-items:center;flex-shrink:0;font-size:24px}
.choice .t{flex-grow:1}
.choice strong{font-size:17px}
.choice .desc{color:var(--body);font-size:15px;margin-top:2px}
.choice .meta{font-size:14px;font-weight:600;margin-top:8px;color:var(--muted)}
.pill{display:inline-block;font-size:12px;font-weight:700;padding:2px 10px;border-radius:999px;margin-left:8px;vertical-align:2px}
.pill-green{background:var(--depot-light);color:#1F4535;border:1px solid var(--depot)}
.pill-orange{background:var(--tape-soft);color:var(--tape-dark);border:1px solid var(--tape)}
.radio{width:24px;height:24px;border-radius:50%;border:2px solid var(--line-strong);flex-shrink:0;margin-top:2px;display:grid;place-items:center}
.choice.on{border-color:var(--ink);background:var(--tape-soft);box-shadow:4px 4px 0 var(--ink)}
.choice.on .radio{border-color:var(--tape);background:var(--tape);box-shadow:inset 0 0 0 4px var(--tape-soft)}
.bg-tape{background:var(--tape-soft)}.bg-depot{background:var(--depot-light)}.bg-sand{background:var(--sand)}

.lines{display:flex;flex-direction:column;gap:10px;margin-top:14px}
.line{display:flex;justify-content:space-between;gap:16px;font-size:15px}
.line span:first-child{color:var(--body)}
.total{display:flex;justify-content:space-between;align-items:baseline;margin-top:14px;padding-top:14px;border-top:1.5px dashed var(--line-strong)}
.total strong{font-family:var(--font-display);font-size:28px;font-weight:800;color:var(--tape-dark)}
.note{font-size:13px;color:var(--muted);margin-top:8px}
.agree{display:flex;gap:12px;align-items:flex-start;cursor:pointer;font-size:15px}
.agree input{width:22px;height:22px;margin-top:2px;accent-color:var(--tape);flex-shrink:0}

.actions{display:flex;gap:12px;align-items:center;margin-top:24px}
.actions .btn-primary{flex-grow:1}
.err{margin-top:12px;font-size:14px;font-weight:600;color:var(--danger);min-height:1em}

.summary{position:sticky;top:96px;background:var(--paper);border:1.5px solid var(--ink);border-radius:18px;overflow:hidden}
.summary .wh-row{display:flex;gap:12px;align-items:center;padding:18px 20px;background:var(--sand);border-bottom:1.5px solid var(--ink)}
.summary .wh-row .ph{width:52px;height:52px;border-radius:12px;background:var(--cream);border:1px solid var(--ink);display:grid;place-items:center;flex-shrink:0;overflow:hidden}
.summary .wh-row .ph img{width:100%;height:100%;object-fit:cover}
.summary .wh-row strong{display:block;font-size:16px}
.summary .wh-row span{font-size:13px;color:var(--muted)}
.summary .wh-row a{margin-left:auto;font-size:14px;font-weight:600}
.summary .sec{padding:16px 20px;border-bottom:1px solid var(--line)}
.summary h3{font-family:var(--font-body);font-size:13px;font-weight:700;letter-spacing:.6px;text-transform:uppercase;color:var(--muted);margin-bottom:8px}
.summary .dim{color:var(--muted);font-size:15px}
.summary .items{list-style:none;margin:0;padding:0;display:flex;flex-direction:column;gap:6px;font-size:15px}
.summary .items li{display:flex;justify-content:space-between;gap:8px}
.summary .foot{padding:16px 20px}
.summary .foot .total{margin-top:0;padding-top:0;border:0}
.summary .foot .total strong{font-size:26px}
.ruru-tip{display:flex;gap:10px;align-items:flex-start;margin-top:16px;font-size:14px;color:var(--body)}
.ruru-tip img{width:44px;height:auto;flex-shrink:0}

/* ================= Widget: pencarian area, kurir & pembayaran ================= */
.area-search{position:relative}
.area-search-label{display:block;font-size:14px;font-weight:600;margin-bottom:6px}
.area-search-input{width:100%;min-height:50px;border-radius:12px;border:1.5px solid var(--line-strong);background:var(--cream);padding:10px 14px;font-size:16px}
.area-search-input:focus{outline:none;border-color:var(--ink);background:var(--paper)}
.area-search-results{position:absolute;left:0;right:0;margin-top:4px;border-radius:12px;overflow:hidden;z-index:20;background:var(--paper);border:1.5px solid var(--ink);max-height:220px;overflow-y:auto}
.area-search-opt{display:block;width:100%;text-align:left;padding:10px 14px;font-size:13px;border:0;background:none;cursor:pointer;color:var(--ink);border-bottom:1px solid var(--line)}
.area-search-opt:last-child{border-bottom:0}
.area-search-opt:hover{background:var(--sand)}
.area-search-hint{font-size:12px;color:var(--muted);margin-top:6px}

.pmt-list{display:flex;flex-direction:column;gap:10px}
.pmt-loading{border-radius:16px;padding:16px;text-align:center;font-size:13px;background:var(--paper);border:1.5px solid var(--line-strong);color:var(--muted)}
.pmt-group{border-radius:16px;overflow:hidden;background:var(--paper);border:1.5px solid var(--line-strong)}
.pmt-group-head{width:100%;display:flex;align-items:center;justify-content:space-between;gap:12px;padding:16px;text-align:left;background:none;border:0;cursor:pointer;font:inherit}
.pmt-group-title{font-size:15px;font-weight:700;color:var(--ink)}
.pmt-group-right{display:flex;align-items:center;gap:8px;flex-shrink:0}
.pmt-count{font-size:11px;font-weight:700;padding:3px 9px;border-radius:999px;background:var(--tape-soft);color:var(--tape-dark)}
.pmt-chev{width:22px;height:22px;border-radius:50%;display:grid;place-items:center;transition:transform .2s;background:var(--sand);color:var(--muted)}
.pmt-group[data-open="true"] .pmt-chev{transform:rotate(180deg)}
.pmt-panel{display:flex;flex-direction:column;gap:10px;padding:0 16px 16px}
.pmt-opt{width:100%;display:flex;align-items:center;gap:12px;padding:14px;border-radius:14px;text-align:left;cursor:pointer;background:var(--paper);border:1.5px solid var(--line-strong);transition:transform .15s,border-color .15s,background .15s}
.pmt-opt:hover{transform:translateY(-1px)}
.pmt-opt.on{background:var(--tape-soft);border-color:var(--tape)}
.pmt-opt-title{flex:1;min-width:0;font-size:14px;font-weight:700;color:var(--ink)}
.pmt-opt-sub{font-size:12px;color:var(--muted)}
.pmt-radio{width:20px;height:20px;border-radius:50%;border:2px solid var(--line-strong);flex-shrink:0;display:grid;place-items:center;background:var(--paper)}
.pmt-opt.on .pmt-radio{border-color:var(--tape);background:var(--tape)}

.pickup-toggle{display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-bottom:12px}
.pickup-btn{min-height:44px;border-radius:12px;font-size:13px;font-weight:600;cursor:pointer;background:var(--paper);border:1.5px solid var(--line-strong);color:var(--ink)}
.pickup-btn.on{background:var(--tape-soft);border-color:var(--tape)}
.pickup-fields{display:grid;grid-template-columns:1fr 1fr;gap:10px}
.pickup-input{width:100%;min-height:46px;border-radius:12px;border:1.5px solid var(--line-strong);background:var(--cream);padding:10px 12px;font-size:15px}
.pickup-input:focus{outline:none;border-color:var(--ink);background:var(--paper)}
.pickup-hint{font-size:12px;color:var(--muted);margin-top:6px}

.slot-grid{display:grid;grid-template-columns:1fr 1fr;gap:8px;margin-top:10px}
.slot-btn{min-height:48px;border-radius:12px;font-size:14px;font-weight:600;cursor:pointer;background:var(--paper);border:1.5px solid var(--line-strong);color:var(--ink)}
.slot-btn.on{background:var(--tape-soft);border-color:var(--tape);color:var(--tape-dark)}
.slot-btn:disabled{opacity:.4;cursor:not-allowed;text-decoration:line-through}

.footer{margin-top:96px;background:var(--ink);color:#A39C8D;padding:32px 0;font-size:14px}
.footer .wrap{display:flex;justify-content:space-between;flex-wrap:wrap;gap:12px}
.footer a{color:#D6D0C3;text-decoration:none;margin-left:16px}

@media (max-width:1080px){
  .menu{display:none}.nav-in{justify-content:space-between}
  .grid{grid-template-columns:repeat(2,1fr)}
}
@media (max-width:960px){
  .layout{grid-template-columns:1fr}
  .summary{position:static}
}
@media (max-width:680px){
  .grid{grid-template-columns:1fr}
  .help{flex-direction:column;align-items:flex-start}
  .filters select{flex:1}
}
@media (max-width:640px){
  .row2,.sizes{grid-template-columns:1fr}
  .stepper .lbl span{display:none}
  .stepper li.now .lbl span{display:inline}
  .panel{padding:18px}
  .choice{padding:16px;gap:12px}
  .choice .ic{width:44px;height:44px}
}
@media (prefers-reduced-motion:reduce){.wh:hover,.btn:hover{transform:none}}
</style>
@stack('styles')
</head>
<body>
@php
    $navUser = auth()->user();
    $navInitials = '';
    if ($navUser && $navUser->name) {
        foreach (array_slice(preg_split('/\s+/', trim($navUser->name)), 0, 2) as $p) {
            $navInitials .= strtoupper(substr($p, 0, 1));
        }
    }
    $navCartCount = count(session('cart', []));
    $navLinks = [
        ['label' => 'Beranda', 'route' => 'dashboard'],
        ['label' => 'Ruang Titip', 'route' => 'ruang-titip.index', 'match' => 'ruang-titip.*'],
        ['label' => 'Toko Packing', 'route' => 'packing.index', 'match' => 'packing.*'],
        ['label' => 'Toko Preloved', 'route' => 'preloved.index', 'match' => 'preloved.*'],
        ['label' => 'Pesanan Saya', 'route' => 'pesanan.index'],
    ];
@endphp

<header class="nav">
  <div class="wrap nav-in">
    <a class="logo" href="{{ route('dashboard') }}" aria-label="RuangTitip, ke beranda"><img src="{{ asset('assets/logo-ruangtitip.svg') }}" alt="RuangTitip" width="200" height="50"></a>
    <nav aria-label="Menu utama">
      <ul class="menu">
        @foreach ($navLinks as $link)
          <li><a href="{{ route($link['route']) }}" @if(request()->routeIs($link['match'] ?? $link['route'])) aria-current="page" @endif>{{ $link['label'] }}</a></li>
        @endforeach
      </ul>
    </nav>
    <div class="nav-right">
      <a class="icon-btn" href="{{ route('preloved.cart.index') }}" aria-label="Keranjang{{ $navCartCount ? ', ' . $navCartCount . ' produk' : '' }}">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="20" r="1.4"/><circle cx="18" cy="20" r="1.4"/><path d="M3 4h2l2.4 11.2a1.5 1.5 0 0 0 1.5 1.2h8.6a1.5 1.5 0 0 0 1.5-1.2L21 8H6.2"/></svg>
        @if ($navCartCount)
          <span class="cart-count">{{ $navCartCount }}</span>
        @endif
      </a>
      <a href="{{ route('profile.index') }}" aria-label="Profil"><span class="avatar">{{ $navInitials ?? '?' }}</span></a>
    </div>
  </div>
</header>

@yield('content')

<footer class="footer">
  <div class="wrap"><span>&copy; 2026 RuangTitip &middot; Malang, Indonesia</span><span><a href="{{ route('legal.terms') }}">Syarat &amp; Ketentuan</a><a href="{{ route('legal.privacy') }}">Kebijakan Privasi</a></span></div>
</footer>

@stack('scripts')
</body>
</html>
