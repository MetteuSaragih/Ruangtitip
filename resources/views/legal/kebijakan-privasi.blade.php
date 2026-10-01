<!doctype html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Kebijakan Privasi &middot; RuangTitip</title>
<meta name="description" content="Kebijakan Privasi RuangTitip: data apa yang dikumpulkan, untuk apa, dan hak Anda atas data pribadi.">
<link rel="icon" type="image/svg+xml" href="{{ asset('assets/favicon.svg') }}">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,700;12..96,800&family=Figtree:wght@400;500;600;700&display=swap">
<style>
/* ================= Palet RuangTitip (sama dengan halaman lain) ================= */
:root{
  --ink:#1C1B18;--cream:#F5F1E8;--paper:#FFFDF8;--sand:#E8DFCD;
  --tape:#B4531D;--tape-dark:#9A4415;--tape-light:#E8A677;--tape-soft:#F6E3D3;
  --depot:#2E5A45;--depot-light:#EAF1EC;--danger:#A3321A;--danger-soft:#F7E4DF;
  --body:#4F4A40;--muted:#5C574D;--line:#DDD5C4;--line-strong:#CFC6B3;
  --font-display:'Bricolage Grotesque','Figtree',system-ui,sans-serif;
  --font-body:'Figtree',system-ui,sans-serif;
}
*,*::before,*::after{box-sizing:border-box}
html{scroll-behavior:smooth}
body{margin:0;font-family:var(--font-body);background:var(--cream);color:var(--ink);font-size:16px;line-height:1.65;-webkit-font-smoothing:antialiased}
img{display:block;max-width:100%}
a{color:var(--tape-dark);font-weight:600}
a:hover{color:#7A3510}
h1,h2,h3{font-family:var(--font-display);margin:0;line-height:1.15}
p{margin:0}
button,select{font:inherit;color:inherit}
:focus-visible{outline:3px solid var(--tape);outline-offset:3px}
.wrap{max-width:1248px;margin:0 auto;padding:0 24px}
.sr{position:absolute;left:-9999px}

/* Tombol */
.btn{display:inline-flex;align-items:center;justify-content:center;gap:8px;min-height:46px;padding:0 20px;border-radius:999px;font-weight:700;font-size:15px;text-decoration:none;border:1.5px solid transparent;cursor:pointer;white-space:nowrap;transition:transform .15s,background .15s}
.btn:hover{transform:translateY(-1px)}
.btn-primary{background:var(--tape);color:#fff;border-color:var(--ink);box-shadow:3px 3px 0 var(--ink)}
.btn-primary:hover{background:var(--tape-dark);color:#fff}
.btn-outline{background:var(--paper);color:var(--ink);border-color:var(--ink)}
.btn-outline:hover{background:var(--sand);color:var(--ink)}
.btn-wa{background:var(--depot);color:#fff;border-color:var(--ink);box-shadow:3px 3px 0 var(--ink)}
.btn-wa:hover{background:#244A38;color:#fff}
.btn-sm{min-height:40px;padding:0 16px;font-size:14px}

/* ================= Navbar ================= */
.nav{position:sticky;top:0;z-index:40;background:rgba(245,241,232,.95);backdrop-filter:blur(8px);border-bottom:1px solid var(--line)}
.nav-in{display:flex;align-items:center;justify-content:space-between;gap:24px;height:72px}
.nav .logo img{height:36px;width:auto}
.nav .back{display:inline-flex;align-items:center;gap:6px;font-size:15px;color:var(--ink);text-decoration:none}
.nav .back:hover{color:var(--tape)}

/* ================= Header halaman ================= */
.page-head{padding:40px 0 28px;display:grid;grid-template-columns:1fr auto;gap:24px;align-items:end}
.eyebrow{font-size:14px;font-weight:700;color:var(--depot);letter-spacing:.3px}
.page-head h1{font-size:clamp(34px,4.4vw,52px);font-weight:800;letter-spacing:-1.5px;margin-top:6px}
.page-head p{color:var(--body);margin-top:8px;max-width:600px;font-size:17px}
.meta{display:flex;flex-wrap:wrap;gap:8px;margin-top:16px}
.meta span{display:inline-flex;align-items:center;gap:8px;font-size:14px;font-weight:600;padding:7px 14px;background:var(--paper);border:1px solid var(--line-strong);border-radius:999px}
.head-ruru{position:relative;width:150px}
.head-ruru img{width:130px;height:auto;margin-left:auto}
.head-ruru .say{position:absolute;right:110px;top:6px;background:var(--paper);border:1.5px solid var(--ink);border-radius:14px;box-shadow:3px 3px 0 var(--ink);padding:8px 12px;font-family:var(--font-display);font-weight:800;font-size:15px;line-height:1.2;white-space:nowrap}

/* ================= Layout ================= */
.shell{display:grid;grid-template-columns:280px minmax(0,1fr);gap:32px;align-items:start}

/* Daftar isi */
.toc{position:sticky;top:96px;background:var(--paper);border:1.5px solid var(--ink);border-radius:18px;overflow:hidden;max-height:calc(100vh - 120px);display:flex;flex-direction:column}
.toc .head{padding:14px 18px;background:var(--sand);border-bottom:1.5px solid var(--ink);font-size:13px;font-weight:700;letter-spacing:.6px;text-transform:uppercase;color:var(--muted)}
.toc ol{list-style:none;margin:0;padding:8px;overflow:auto}
.toc a{display:flex;align-items:flex-start;gap:10px;padding:9px 12px;border-radius:12px;color:var(--ink);text-decoration:none;font-weight:600;font-size:14px;line-height:1.35}
.toc a:hover{background:var(--cream);color:var(--ink)}
.toc a .n{flex:none;width:22px;height:22px;border-radius:50%;display:grid;place-items:center;font-size:11px;font-weight:700;border:1.5px solid var(--line-strong);background:var(--paper)}
.toc a.aktif{background:var(--ink);color:var(--cream)}
.toc a.aktif .n{background:var(--tape);border-color:var(--tape);color:#fff}
.toc-mobile{display:none}

/* ================= Isi ================= */
.doc{min-width:0}
.snk-intro{display:flex;gap:14px;align-items:flex-start;padding:18px 20px;background:var(--tape-soft);border:1.5px solid var(--tape);border-radius:14px;font-size:15px}
.snk-intro svg{flex:none;width:22px;height:22px;color:var(--tape-dark);margin-top:1px}

.snk-sec{background:var(--paper);border:1px solid var(--line-strong);border-radius:18px;padding:28px clamp(20px,3vw,32px);margin-top:20px;scroll-margin-top:96px}
.snk-sec h2{display:flex;align-items:center;gap:14px;font-size:clamp(22px,2.4vw,26px);font-weight:800;letter-spacing:-.5px;margin-bottom:16px}
.snk-sec h2 .n{flex:none;width:40px;height:40px;border-radius:12px;display:grid;place-items:center;background:var(--tape);color:#fff;border:1.5px solid var(--ink);box-shadow:2px 2px 0 var(--ink);font-size:17px}
.snk-sec h3{font-family:var(--font-body);font-size:13px;font-weight:700;letter-spacing:.6px;text-transform:uppercase;color:var(--depot);margin:22px 0 8px}
.snk-sec h2 + h3{margin-top:0}
.snk-sec p{margin:0 0 12px;color:var(--body)}
.snk-sec ol,.snk-sec ul{margin:0 0 12px;padding-left:22px;color:var(--body)}
.snk-sec li{margin:7px 0;padding-left:4px}
.snk-sec li::marker{color:var(--tape-dark);font-weight:700}
.snk-sec strong{color:var(--ink)}
.snk-sec ul.snk-def,.snk-sec ul.snk-larang{padding-left:0;list-style:none}
.snk-sec ul.snk-def li::marker,.snk-sec ul.snk-larang li::marker{content:none}

/* Definisi */
.snk-def{display:grid;grid-template-columns:repeat(auto-fill,minmax(240px,1fr));gap:10px;margin:12px 0 16px}
.snk-def li{margin:0;padding:14px 16px;border:1px solid var(--line-strong);border-radius:14px;background:var(--cream);font-size:14.5px;line-height:1.5}
.snk-def b{display:block;color:var(--ink);font-size:15px;margin-bottom:2px}

/* Hak / kewajiban */
.snk-dua{display:grid;grid-template-columns:1fr 1fr;gap:16px}
.snk-kartu{border:1px solid var(--line-strong);border-radius:16px;padding:18px 18px 8px;background:var(--paper)}
.snk-kartu.hak{background:var(--depot-light);border-color:var(--depot)}
.snk-kartu h3{margin-top:0;display:flex;align-items:center;gap:8px}
.snk-kartu h3 svg{width:18px;height:18px}
.snk-kartu:not(.hak) h3{color:var(--tape-dark)}

/* Barang dilarang */
.snk-larang{display:grid;grid-template-columns:repeat(auto-fill,minmax(230px,1fr));gap:10px;margin:12px 0 16px}
.snk-larang li{margin:0;display:flex;gap:10px;align-items:flex-start;padding:12px 14px;border-radius:14px;background:var(--danger-soft);border:1px solid #E5BFB4;font-size:14.5px;line-height:1.5;color:var(--ink)}
.snk-larang svg{flex:none;width:18px;height:18px;color:var(--danger);margin-top:1px}

/* Opsi logistik */
.snk-opsi{display:grid;grid-template-columns:repeat(3,1fr);gap:12px;margin:10px 0 14px}
.snk-opsi div{position:relative;border:1.5px solid var(--line-strong);border-radius:16px;padding:16px;background:var(--paper)}
.snk-opsi div:nth-child(1){background:var(--tape-soft);border-color:var(--tape)}
.snk-opsi div:nth-child(2){background:var(--sand)}
.snk-opsi div:nth-child(3){background:var(--depot-light);border-color:var(--depot)}
.snk-opsi b{display:block;font-size:16px;margin-bottom:4px;padding-right:8px}
.snk-opsi span{display:block;font-size:14px;color:var(--body);line-height:1.5}
.snk-opsi .tag{display:inline-block;margin-top:10px;font-style:normal;font-size:12px;font-weight:700;padding:2px 10px;border-radius:999px;background:var(--paper);border:1px solid var(--ink)}

/* Tabel garansi */
.snk-tabel-wrap{overflow-x:auto;margin:16px 0;border:1.5px solid var(--ink);border-radius:16px;background:var(--paper)}
.snk-tabel{width:100%;border-collapse:collapse;font-size:15px;min-width:620px}
.snk-tabel th{text-align:left;background:var(--ink);color:var(--cream);font-weight:700;padding:12px 16px;font-size:13px;letter-spacing:.3px}
.snk-tabel td{padding:12px 16px;border-top:1px solid var(--line)}
.snk-tabel tbody tr.grup td{border-top:1.5px solid var(--line-strong)}
.snk-tabel tbody tr.grup:first-child td{border-top:0}
.snk-tabel .grup td:first-child{font-weight:700}
.snk-tabel td:first-child{font-weight:700;color:var(--ink)}
.snk-tabel td{vertical-align:top;color:var(--body)}
.snk-tabel td.uang{font-variant-numeric:tabular-nums;white-space:nowrap;color:var(--body)}
.snk-tabel td.maks{font-family:var(--font-display);font-weight:800;font-size:17px;color:var(--tape-dark)}

.snk-catatan{display:flex;gap:12px;align-items:flex-start;padding:14px 16px;margin:14px 0;background:var(--sand);border:1.5px solid var(--ink);border-radius:14px;font-size:15px}
.snk-catatan svg{flex:none;width:20px;height:20px;color:var(--tape-dark);margin-top:2px}

.snk-placeholder{background:#FFF1C7;color:#7A5300;border-radius:6px;padding:0 6px;font-weight:700}

/* Kontak */
.snk-kontak{display:grid;grid-template-columns:1.35fr 1fr 1fr;gap:12px;margin-top:12px}
.snk-kontak a{display:flex;align-items:center;gap:12px;text-decoration:none;color:var(--ink);border:1.5px solid var(--line-strong);border-radius:16px;padding:14px 16px;background:var(--paper);transition:border-color .15s,transform .15s}
.snk-kontak a:hover{border-color:var(--ink);transform:translateY(-2px);color:var(--ink)}
.snk-kontak .ik{flex:none;width:42px;height:42px;border-radius:12px;display:grid;place-items:center;background:var(--sand);border:1.5px solid var(--ink)}
.snk-kontak .ik svg{width:20px;height:20px}
.snk-kontak small{display:block;font-size:12px;color:var(--muted);font-weight:700;text-transform:uppercase;letter-spacing:.5px}
.snk-kontak b{font-size:15px;overflow-wrap:anywhere}

/* Bantuan Ruru */
.cs{display:flex;gap:20px;align-items:center;margin-top:24px;padding:24px;background:var(--sand);border:1.5px solid var(--ink);border-radius:18px}
.cs img{width:92px;height:auto;flex-shrink:0}
.cs .t{flex-grow:1}
.cs strong{display:block;font-family:var(--font-display);font-size:22px}
.cs p{color:var(--body);font-size:15px;margin-top:2px}
.cs .acts{display:flex;gap:8px;flex-wrap:wrap;margin-top:14px}

/* Kembali ke atas */
.to-top{position:fixed;right:24px;bottom:24px;z-index:30;width:52px;height:52px;border-radius:50%;background:var(--paper);border:1.5px solid var(--ink);box-shadow:3px 3px 0 var(--ink);display:grid;place-items:center;cursor:pointer;opacity:0;pointer-events:none;transition:opacity .2s}
.to-top.tampil{opacity:1;pointer-events:auto}

/* Footer */
.footer{margin-top:96px;background:var(--ink);color:#A39C8D;padding:32px 0;font-size:14px}
.footer .wrap{display:flex;justify-content:space-between;flex-wrap:wrap;gap:12px}
.footer a{color:#D6D0C3;text-decoration:none;margin-left:16px;font-weight:500}
.footer a[aria-current="page"]{color:var(--tape-light)}

/* ================= Responsif ================= */
@media (max-width:960px){
  .shell{grid-template-columns:1fr}
  .toc{display:none}
  .toc-mobile{display:block;position:sticky;top:72px;z-index:20;margin:0 -24px;padding:10px 24px;background:rgba(245,241,232,.95);backdrop-filter:blur(8px);border-bottom:1px solid var(--line)}
  .toc-mobile select{width:100%;height:48px;border-radius:999px;border:1.5px solid var(--ink);background:var(--paper);padding:0 18px;font-weight:700;font-size:15px}
  .snk-sec{scroll-margin-top:150px}
  .head-ruru{display:none}
  .page-head{grid-template-columns:1fr}
  .snk-dua,.snk-opsi,.snk-kontak{grid-template-columns:1fr}
  .cs{flex-direction:column;align-items:flex-start}
}
@media (max-width:560px){
  .wrap{padding:0 16px}
  .toc-mobile{margin:0 -16px;padding:10px 16px}
  .nav .back span{display:none}
  .snk-sec{padding:22px 18px}
}
@media (max-width:640px){
  .snk-tabel-wrap{border:0;background:none;overflow:visible}
  .snk-tabel{min-width:0}
  .snk-tabel thead{display:none}
  .snk-tabel,.snk-tabel tbody,.snk-tabel tr,.snk-tabel td{display:block;width:100%}
  .snk-tabel tr{background:var(--paper);border:1.5px solid var(--line-strong);border-radius:14px;padding:12px 14px;margin-bottom:10px}
  .snk-tabel td{border:0;padding:4px 0}
  .snk-tabel td:first-child{font-family:var(--font-display);font-size:17px;padding-bottom:6px}
  .snk-tabel td:not(:first-child)::before{content:attr(data-label);display:block;font-size:11px;font-weight:700;letter-spacing:.5px;text-transform:uppercase;color:var(--depot)}
}
@media (prefers-reduced-motion:reduce){html{scroll-behavior:auto}.btn:hover,.snk-kontak a:hover{transform:none}}
@media print{
  .nav,.toc,.toc-mobile,.to-top,.cs,.head-ruru,.footer{display:none!important}
  .shell{display:block}
  .snk-sec{break-inside:avoid-page;border-color:#bbb}
}
</style>
</head>
<body>

<header class="nav">
  <div class="wrap nav-in">
    <a class="logo" href="{{ route('home') }}" aria-label="RuangTitip, ke beranda"><img src="{{ asset('assets/logo-ruangtitip.svg') }}" alt="RuangTitip" width="200" height="50"></a>
    <a class="back" href="{{ route('home') }}">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5M11 18l-6-6 6-6"/></svg>
      <span>Kembali ke beranda</span>
    </a>
  </div>
</header>

<main class="wrap">
  <div class="page-head">
    <div>
      <span class="eyebrow">Dokumen legal</span>
      <h1>Kebijakan Privasi</h1>
      <p>Data apa yang ruangtitip kumpulkan, untuk apa dipakai, dengan siapa dibagikan, dan bagaimana Anda mengendalikannya. Disusun mengacu pada UU No. 27 Tahun 2022 tentang Pelindungan Data Pribadi.</p>
      <div class="meta">
        <span><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>Berlaku sejak 2 Oktober 2026</span>
        <span><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg>± 8 menit membaca</span>
      </div>
    </div>
    <div class="head-ruru" aria-hidden="true">
      <p class="say">Datamu aman, kok!</p>
      <img src="{{ asset('assets/ruru/ruru-perisai.webp') }}" alt="" width="800" height="800">
    </div>
  </div>

  <div class="toc-mobile">
    <label for="toc-select" class="sr">Lompat ke bagian</label>
    <select id="toc-select"></select>
  </div>

  <div class="shell">
    <nav class="toc" aria-label="Daftar isi">
      <div class="head">Daftar isi</div>
      <ol id="toc-list"></ol>
    </nav>

    <div class="doc">
      <div class="snk-intro">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
        <div>Kebijakan Privasi ini menjelaskan data pribadi apa yang Kami kumpulkan saat Anda menggunakan ruangtitip, untuk apa data itu dipakai, dengan siapa dibagikan, dan bagaimana Anda dapat mengendalikannya. <strong>Kami tidak menjual data pribadi Anda kepada siapa pun.</strong></div>
      </div>

      <section class="snk-sec" id="siapa-kami" data-judul="Siapa Kami dan Cakupan Kebijakan">
        <h2><span class="n">1</span>Siapa Kami dan Cakupan Kebijakan</h2>
<ol start="1"><li>ruangtitip dikelola oleh <span class="snk-placeholder">[nama tim pengelola / nama badan usaha setelah terdaftar]</span>, berkedudukan di Kota Malang (selanjutnya disebut "Kami"). Dalam UU PDP, Kami bertindak sebagai <strong>Pengendali Data Pribadi</strong> atas data yang Anda berikan melalui Platform.</li><li>Kebijakan ini berlaku untuk seluruh layanan ruangtitip: penitipan barang, antar-jemput, Toko Packing, Toko Preloved, pengiriman ke kota lain, dan formulir lain di situs web ruangtitip.id.</li><li>Kebijakan ini adalah bagian dari <a href="{{ route('legal.terms') }}">Syarat dan Ketentuan ruangtitip</a>. Istilah seperti Platform, Penitip, Pesanan, dan Galeri Visual memiliki arti yang sama dengan yang ada di Syarat dan Ketentuan.</li></ol>
      </section>

      <section class="snk-sec" id="data-dikumpulkan" data-judul="Data yang Kami Kumpulkan">
        <h2><span class="n">2</span>Data yang Kami Kumpulkan</h2>
<p>Kami hanya mengumpulkan data yang dibutuhkan untuk menjalankan layanan.</p><div class="snk-tabel-wrap"><table class="snk-tabel"><thead><tr><th>Jenis Data</th><th>Contoh</th><th>Kapan Dikumpulkan</th></tr></thead><tbody><tr><td>Data akun</td><td>Nama, alamat email, foto profil akun Google</td><td>Saat Anda masuk dengan Google atau email (OTP)</td></tr><tr><td>Data kontak</td><td>Nomor WhatsApp</td><td>Saat membuat Pesanan atau melengkapi profil</td></tr><tr><td>Data alamat dan lokasi</td><td>Alamat kos atau titik jemput, kecamatan, patokan, titik koordinat dari Google Maps</td><td>Saat memilih antar-jemput atau pengiriman</td></tr><tr><td>Data Pesanan</td><td>Jenis, ukuran, dan jumlah Barang, Masa Penitipan, jadwal, opsi logistik, riwayat status</td><td>Saat membuat dan menjalankan Pesanan</td></tr><tr><td>Foto Barang</td><td>Foto kemasan dan Segel ruangtitip di Galeri Visual, foto barang preloved</td><td>Saat Barang diterima di gudang atau didaftarkan untuk dijual</td></tr><tr><td>Data transaksi</td><td>Nominal tagihan, metode pembayaran, status pembayaran, nomor Virtual Account</td><td>Saat pembayaran diproses oleh Tripay</td></tr><tr><td>Data pencairan dana</td><td>Nama pemilik rekening dan nomor rekening atau e-wallet</td><td>Saat menerima ganti rugi atau hasil penjualan preloved</td></tr><tr><td>Komunikasi dan ulasan</td><td>Isi chat WhatsApp atau email dengan tim, rating, ulasan, klaim</td><td>Saat Anda menghubungi Kami atau memberi ulasan</td></tr><tr><td>Data teknis</td><td>Alamat IP, jenis perangkat dan browser, waktu akses, isi keranjang belanja</td><td>Otomatis saat Anda membuka Platform</td></tr></tbody></table></div><h3>Yang tidak Kami minta dan tidak Kami simpan</h3><ul class="snk-larang"><li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"><circle cx="12" cy="12" r="10"/><path d="M4.9 4.9l14.2 14.2"/></svg>Kata sandi akun Google Anda. Login Google diproses langsung oleh Google.</li><li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"><circle cx="12" cy="12" r="10"/><path d="M4.9 4.9l14.2 14.2"/></svg>Data kartu debit atau kredit. Pembayaran diproses langsung oleh Tripay.</li><li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"><circle cx="12" cy="12" r="10"/><path d="M4.9 4.9l14.2 14.2"/></svg>Kartu identitas (KTP atau KTM), karena ruangtitip tidak menerapkan verifikasi identitas.</li><li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"><circle cx="12" cy="12" r="10"/><path d="M4.9 4.9l14.2 14.2"/></svg>Rincian isi Barang. Penitip tidak wajib menjelaskan isi kardus atau koper.</li></ul><p>Jawaban survei di beranda diisi tanpa login dan tanpa nomor HP, dan hanya dipakai dalam bentuk rekap untuk riset layanan.</p>
      </section>

      <section class="snk-sec" id="tujuan" data-judul="Untuk Apa Data Digunakan">
        <h2><span class="n">3</span>Untuk Apa Data Digunakan</h2>
<div class="snk-tabel-wrap"><table class="snk-tabel"><thead><tr><th>Tujuan</th><th>Data yang Dipakai</th><th>Dasar Pemrosesan</th></tr></thead><tbody><tr><td>Membuat akun dan memverifikasi login</td><td>Data akun</td><td>Pelaksanaan perjanjian</td></tr><tr><td>Memproses Pesanan, penjemputan, penyimpanan, dan pengantaran</td><td>Data kontak, alamat, lokasi, Pesanan</td><td>Pelaksanaan perjanjian</td></tr><tr><td>Menghitung biaya antar-jemput dan ongkir secara otomatis</td><td>Alamat dan titik koordinat</td><td>Pelaksanaan perjanjian</td></tr><tr><td>Memproses pembayaran, ganti rugi, dan pencairan hasil preloved</td><td>Data transaksi dan pencairan dana</td><td>Pelaksanaan perjanjian</td></tr><tr><td>Mendokumentasikan kondisi Barang dan menangani klaim</td><td>Foto Barang, data Pesanan, komunikasi</td><td>Pelaksanaan perjanjian dan kepentingan yang sah</td></tr><tr><td>Mengirim notifikasi status Pesanan dan pengingat masa titip</td><td>Data kontak, data akun</td><td>Pelaksanaan perjanjian</td></tr><tr><td>Menjawab pertanyaan dan keluhan</td><td>Komunikasi</td><td>Pelaksanaan perjanjian</td></tr><tr><td>Menampilkan ulasan sebagai testimoni</td><td>Nama tampilan, rating, ulasan</td><td>Persetujuan</td></tr><tr><td>Mengirim info promo atau layanan baru</td><td>Data kontak</td><td>Persetujuan (dapat dibatalkan kapan saja)</td></tr><tr><td>Menjaga keamanan Platform dan mencegah penipuan</td><td>Data teknis</td><td>Kepentingan yang sah</td></tr><tr><td>Memenuhi kewajiban hukum, misalnya pencatatan transaksi</td><td>Data transaksi</td><td>Kewajiban hukum</td></tr></tbody></table></div><p>Dasar pemrosesan mengacu pada Pasal 20 UU PDP. Kami tidak memakai data Anda untuk tujuan di luar tabel di atas tanpa meminta persetujuan Anda lebih dulu.</p>
      </section>

      <section class="snk-sec" id="pihak-ketiga" data-judul="Pihak Ketiga yang Menerima Data">
        <h2><span class="n">4</span>Pihak Ketiga yang Menerima Data</h2>
<p>Untuk menjalankan layanan, sebagian data Anda dibagikan kepada mitra berikut. Masing-masing hanya menerima data yang dibutuhkan untuk tugasnya.</p><div class="snk-tabel-wrap"><table class="snk-tabel"><thead><tr><th>Mitra</th><th>Fungsi</th><th>Data yang Dibagikan</th></tr></thead><tbody><tr><td>Google</td><td>Login dengan akun Google, peta dan titik lokasi</td><td>Nama, email, foto profil (dari Google ke Kami), alamat dan koordinat</td></tr><tr><td>Tripay</td><td>Pemrosesan pembayaran (QRIS, Virtual Account, e-wallet, minimarket)</td><td>Nama, email, nomor WhatsApp, nominal tagihan, nomor Pesanan</td></tr><tr><td>Biteship dan kurir mitranya</td><td>Penjemputan, pengantaran, dan pengiriman Barang (misalnya GoSend, GrabExpress, ekspedisi nasional)</td><td>Nama, nomor WhatsApp, alamat, catatan untuk kurir, ukuran paket</td></tr><tr><td>Hostinger</td><td>Server tempat situs web dan basis data ruangtitip berjalan</td><td>Seluruh data Platform, disimpan dalam server yang Kami kelola</td></tr><tr><td>Penyedia layanan email</td><td>Pengiriman kode OTP dan notifikasi</td><td>Alamat email</td></tr></tbody></table></div><p>Setiap mitra memproses data sesuai kebijakan privasinya masing-masing. Kami juga dapat memberikan data kepada aparat penegak hukum atau instansi pemerintah jika diwajibkan oleh peraturan perundang-undangan.</p><p>Sebagian mitra dapat menyimpan data di server luar Indonesia. Dalam hal ini, Kami memastikan mitra tersebut memiliki tingkat pelindungan data yang setara atau lebih tinggi, sesuai Pasal 56 UU PDP.</p>
      </section>

      <section class="snk-sec" id="foto-cctv-ulasan" data-judul="Foto Barang, CCTV, dan Ulasan">
        <h2><span class="n">5</span>Foto Barang, CCTV, dan Ulasan</h2>
<div class="snk-dua">
          <div class="snk-kartu hak"><h3>Hanya Anda dan tim</h3><ul>
            <li><strong>Foto Barang</strong> di Galeri Visual. Dipakai sebagai bukti kondisi Barang dan bahan penanganan klaim.</li>
            <li><strong>Rekaman CCTV</strong> gudang. Hanya diakses tim berwenang dan dapat ditunjukkan kepada Penitip yang bersangkutan saat proses klaim.</li>
          </ul></div>
          <div class="snk-kartu"><h3>Tampil secara publik</h3><ul>
            <li><strong>Foto barang preloved</strong> di katalog Toko Preloved. Nama penjual hanya berupa nama panggilan atau inisial. Alamat dan nomor WhatsApp tidak pernah ditampilkan.</li>
            <li><strong>Ulasan</strong> dengan rating tinggi di beranda, bersama nama tampilan Anda. Hubungi Kami jika ingin ulasan disembunyikan.</li>
          </ul></div>
        </div>
      </section>

      <section class="snk-sec" id="masa-simpan" data-judul="Berapa Lama Data Disimpan">
        <h2><span class="n">6</span>Berapa Lama Data Disimpan</h2>
<p>Data disimpan hanya selama dibutuhkan. Setelah masa simpan berakhir, data dihapus atau dianonimkan sehingga tidak lagi dapat dikaitkan dengan Anda.</p><div class="snk-tabel-wrap"><table class="snk-tabel"><thead><tr><th>Jenis Data</th><th>Masa Simpan</th></tr></thead><tbody><tr><td>Data akun, kontak, dan alamat tersimpan</td><td>Selama akun aktif, lalu dihapus paling lambat 30 hari setelah akun ditutup</td></tr><tr><td>Foto Barang di Galeri Visual</td><td>6 bulan setelah Pesanan selesai, atau sampai klaim yang berjalan tuntas</td></tr><tr><td>Rekaman CCTV gudang</td><td>30 hari, kecuali dibutuhkan untuk penanganan klaim atau laporan hukum</td></tr><tr><td>Data Pesanan dan transaksi</td><td>10 tahun, untuk memenuhi kewajiban pencatatan keuangan dan perpajakan</td></tr><tr><td>Data rekening untuk pencairan dana</td><td>Sampai pencairan selesai, lalu hanya disimpan sebagai bukti transaksi</td></tr><tr><td>Riwayat chat dan keluhan</td><td>1 tahun setelah percakapan terakhir</td></tr><tr><td>Data teknis dan log akses</td><td>90 hari</td></tr></tbody></table></div><p>Jika Anda menutup akun, data yang wajib disimpan karena hukum (misalnya bukti transaksi) tetap Kami simpan sampai masa simpannya berakhir, tetapi tidak lagi dipakai untuk keperluan lain.</p>
      </section>

      <section class="snk-sec" id="keamanan" data-judul="Keamanan Data">
        <h2><span class="n">7</span>Keamanan Data</h2>
<ol start="1"><li>Kami melindungi data dengan langkah-langkah berikut:<ul><li>Koneksi situs web dienkripsi dengan HTTPS.</li><li>Login tanpa kata sandi, memakai akun Google atau kode OTP sekali pakai.</li><li>Panel admin hanya dapat diakses tim ruangtitip dengan akun masing-masing, dan setiap tim hanya bisa membuka data yang ia butuhkan.</li><li>Data pembayaran ditangani Tripay yang tersertifikasi PCI DSS, sehingga data kartu tidak pernah melewati server Kami.</li><li>Pencadangan data secara berkala.</li></ul></li><li>Tidak ada sistem yang sepenuhnya aman. Jika terjadi kebocoran data pribadi, Kami akan memberi tahu Anda secara tertulis paling lambat <strong>3×24 jam</strong> sejak kebocoran diketahui, sesuai Pasal 46 UU PDP. Pemberitahuan itu berisi data apa yang terdampak, kapan dan bagaimana kejadiannya, serta langkah yang Kami ambil.</li></ol><div class="snk-catatan"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10.3 3.9 1.8 18a2 2 0 0 0 1.7 3h17a2 2 0 0 0 1.7-3L13.7 3.9a2 2 0 0 0-3.4 0z"/><path d="M12 9v4M12 17h.01"/></svg><div><strong>Jangan bagikan kode OTP kepada siapa pun.</strong> Tim ruangtitip tidak akan pernah meminta kode OTP, kata sandi, atau PIN pembayaran Anda.</div></div>
      </section>

      <section class="snk-sec" id="hak-anda" data-judul="Hak Anda atas Data Pribadi">
        <h2><span class="n">8</span>Hak Anda atas Data Pribadi</h2>
<p>Sesuai UU PDP, Anda berhak:</p><div class="snk-tabel-wrap"><table class="snk-tabel"><thead><tr><th>Hak</th><th>Artinya</th><th>Cara Menggunakannya</th></tr></thead><tbody><tr><td>Mendapat informasi</td><td>Mengetahui data apa yang Kami proses dan untuk apa</td><td>Membaca kebijakan ini atau bertanya ke Kami</td></tr><tr><td>Mengakses dan meminta salinan</td><td>Melihat dan menerima salinan data Anda</td><td>Ajukan lewat WhatsApp Admin atau email</td></tr><tr><td>Memperbaiki dan memperbarui</td><td>Mengubah data yang salah atau sudah tidak berlaku</td><td>Ubah sendiri di halaman <a href="{{ route('profile.index') }}">Profil</a>, atau minta ke Kami</td></tr><tr><td>Menghapus data</td><td>Meminta data dihapus dan akun ditutup</td><td>Ajukan lewat WhatsApp Admin atau email</td></tr><tr><td>Menarik persetujuan</td><td>Berhenti menerima promo atau menampilkan ulasan</td><td>Balas pesan promo dengan "STOP", atau hubungi Kami</td></tr><tr><td>Membatasi pemrosesan</td><td>Meminta pemrosesan data dihentikan sementara</td><td>Ajukan lewat WhatsApp Admin atau email</td></tr><tr><td>Memindahkan data</td><td>Menerima data Anda dalam format yang umum dipakai</td><td>Ajukan lewat WhatsApp Admin atau email</td></tr><tr><td>Mengajukan keberatan dan ganti rugi</td><td>Menolak pemrosesan atau menuntut ganti rugi atas pelanggaran</td><td>Hubungi Kami, lalu lembaga pengawas PDP jika belum selesai</td></tr></tbody></table></div><ol start="1"><li>Untuk keamanan, Kami akan memastikan permintaan benar-benar datang dari pemilik akun, misalnya dengan mengirim kode OTP ke email terdaftar.</li><li>Kami menanggapi permintaan paling lambat <strong>3×24 jam</strong> sejak permintaan diterima, sesuai UU PDP.</li><li>Beberapa permintaan dapat dibatasi, misalnya penghapusan data transaksi yang wajib disimpan karena hukum, atau data yang masih dibutuhkan untuk Pesanan atau klaim yang sedang berjalan. Jika demikian, Kami akan menjelaskan alasannya.</li></ol>
      </section>

      <section class="snk-sec" id="cookie" data-judul="Cookie dan Penyimpanan Lokal">
        <h2><span class="n">9</span>Cookie dan Penyimpanan Lokal</h2>
<p>Platform memakai cookie dan penyimpanan lokal browser (local storage) untuk hal-hal berikut:</p><div class="snk-opsi"><div><b>Wajib</b><span>Menjaga Anda tetap masuk ke akun, melindungi formulir dari serangan (token keamanan), dan menyimpan isi keranjang belanja.</span><em class="tag">Selalu aktif</em></div><div><b>Preferensi</b><span>Mengingat pilihan kecil, misalnya rekor permainan di halaman pemeliharaan situs.</span><em class="tag">Opsional</em></div><div><b>Analitik</b><span><span class="snk-placeholder">[isi jika memakai Google Analytics atau sejenisnya; jika tidak, hapus kartu ini]</span></span><em class="tag">Opsional</em></div></div><p>Anda dapat menghapus atau memblokir cookie lewat pengaturan browser. Jika cookie wajib diblokir, Anda mungkin tidak bisa masuk ke akun atau menyelesaikan Pesanan.</p>
      </section>

      <section class="snk-sec" id="ketentuan-lain" data-judul="Ketentuan Lain">
        <h2><span class="n">10</span>Ketentuan Lain</h2>
<h3>Data anak</h3><ol start="1"><li>Layanan ruangtitip ditujukan untuk mahasiswa dan masyarakat umum. Pengguna yang berusia di bawah 18 tahun hanya boleh memberikan data pribadi dengan persetujuan orang tua atau wali, sesuai Pasal 25 UU PDP.</li><li>Jika Kami mengetahui ada data anak yang diberikan tanpa persetujuan tersebut, Kami akan menghubungi orang tua atau wali, atau menghapus data itu.</li></ol><h3>Perubahan Kebijakan Privasi</h3><ol start="3"><li>Kebijakan ini dapat Kami perbarui sewaktu-waktu. Perubahan penting akan diberitahukan melalui email atau Platform paling lambat <strong>7 hari</strong> sebelum berlaku. Tanggal pembaruan terakhir selalu tercantum di bagian atas halaman ini.</li></ol><h3>Hubungi Kami</h3>
        <p>Pertanyaan, permintaan terkait hak Anda, atau laporan dugaan penyalahgunaan data dapat disampaikan melalui:</p>
        <div class="snk-kontak">
          <a href="mailto:ruangtitipmu@gmail.com"><span class="ik"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="m22 6-10 7L2 6"/></svg></span><span><small>Email</small><b>ruangtitipmu@gmail.com</b></span></a>
          <a href="https://wa.me/6285121091134" target="_blank" rel="noopener"><span class="ik"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 11.5a8.4 8.4 0 0 1-12.4 7.4L3 21l2.1-5.6A8.4 8.4 0 1 1 21 11.5z"/></svg></span><span><small>WhatsApp Admin</small><b>0851-2109-1134</b></span></a>
          <a href="https://ruangtitip.id" target="_blank" rel="noopener"><span class="ik"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M2 12h20M12 2a15.3 15.3 0 0 1 0 20M12 2a15.3 15.3 0 0 0 0 20"/></svg></span><span><small>Situs</small><b>ruangtitip.id</b></span></a>
        </div>
        <p style="margin-top:14px">Jika tanggapan Kami belum memuaskan, Anda dapat menyampaikan pengaduan kepada lembaga penyelenggara pelindungan data pribadi yang ditetapkan Pemerintah.</p>
      </section>

      <aside class="cs">
        <img src="{{ asset('assets/ruru/ruru-tunjuk.webp') }}" alt="" width="92" height="86">
        <div class="t">
          <strong>Mau cek atau hapus datamu? Bilang Ruru aja.</strong>
          <p>Ajukan permintaan akses, perbaikan, atau penghapusan data lewat WhatsApp atau email. Kami tanggapi paling lambat 3×24 jam.</p>
          <div class="acts">
            <a class="btn btn-wa btn-sm" href="https://wa.me/6285121091134?text=Halo%20RuangTitip%2C%20saya%20mau%20mengajukan%20permintaan%20terkait%20data%20pribadi%20saya" target="_blank" rel="noopener">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12a8.5 8.5 0 0 1-12.6 7.4L3 21l1.6-5.2A8.5 8.5 0 1 1 21 12z"/></svg>
              Chat via WhatsApp</a>
            <a class="btn btn-outline btn-sm" href="mailto:ruangtitipmu@gmail.com">Kirim email</a>
          </div>
        </div>
      </aside>
    </div>
  </div>
</main>

<button class="to-top" id="to-top" type="button" aria-label="Kembali ke atas">
  <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M18 15l-6-6-6 6"/></svg>
</button>

<footer class="footer">
  <div class="wrap"><span>&copy; {{ date('Y') }} RuangTitip &middot; Malang, Indonesia</span><span><a href="{{ route('legal.terms') }}">Syarat &amp; Ketentuan</a><a href="{{ route('legal.privacy') }}" aria-current="page">Kebijakan Privasi</a></span></div>
</footer>

<script>
(function () {
  var secs = [].slice.call(document.querySelectorAll('.snk-sec'));
  var list = document.getElementById('toc-list'), sel = document.getElementById('toc-select'), links = [];

  secs.forEach(function (s, i) {
    var judul = s.getAttribute('data-judul');
    var li = document.createElement('li'), a = document.createElement('a');
    a.href = '#' + s.id;
    a.innerHTML = '<span class="n">' + (i + 1) + '</span><span></span>';
    a.lastChild.textContent = judul;
    li.appendChild(a); list.appendChild(li); links.push(a);
    var o = document.createElement('option');
    o.value = s.id; o.textContent = (i + 1) + '. ' + judul; sel.appendChild(o);
  });

  sel.addEventListener('change', function () {
    var t = document.getElementById(sel.value);
    if (t) t.scrollIntoView({ behavior: 'smooth', block: 'start' });
  });

  function aktif(id) {
    links.forEach(function (a) { a.classList.toggle('aktif', a.getAttribute('href') === '#' + id); });
    if (sel.value !== id) sel.value = id;
  }
  if ('IntersectionObserver' in window) {
    var io = new IntersectionObserver(function (es) {
      es.forEach(function (e) { if (e.isIntersecting) aktif(e.target.id); });
    }, { rootMargin: '-25% 0px -65% 0px' });
    secs.forEach(function (s) { io.observe(s); });
  }
  aktif(secs[0].id);

  document.querySelectorAll('.snk-tabel').forEach(function (t) {
    var h = [].map.call(t.querySelectorAll('th'), function (x) { return x.textContent; });
    t.querySelectorAll('tbody tr').forEach(function (r) { [].forEach.call(r.children, function (c, i) { c.setAttribute('data-label', h[i] || ''); }); });
  });
  var top = document.getElementById('to-top');
  window.addEventListener('scroll', function () { top.classList.toggle('tampil', window.scrollY > 700); }, { passive: true });
  top.addEventListener('click', function () { window.scrollTo({ top: 0, behavior: 'smooth' }); });
})();
</script>
</body>
</html>
