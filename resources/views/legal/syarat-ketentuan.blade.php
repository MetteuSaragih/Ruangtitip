<!doctype html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Syarat &amp; Ketentuan &middot; RuangTitip</title>
<meta name="description" content="Syarat dan Ketentuan layanan penitipan barang RuangTitip.">
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
.snk-tabel tbody tr:not(.grup) td:first-child{color:transparent;user-select:none}
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
      <h1>Syarat &amp; Ketentuan</h1>
      <p>Aturan main layanan titip barang ruangtitip. Baca sampai selesai sebelum menitipkan barang, terutama bagian Garansi Batas Tetap.</p>
      <div class="meta">
        <span><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>Berlaku sejak 2 Oktober 2026</span>
        <span><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg>± 10 menit membaca</span>
      </div>
    </div>
    <div class="head-ruru" aria-hidden="true">
      <p class="say">Dibaca dulu, ya!</p>
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
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4M12 8h.01"/></svg>
        <div>Dengan masuk ke Platform atau menggunakan layanan ruangtitip, Anda menyatakan telah membaca, memahami, dan menyetujui Syarat dan Ketentuan ini. Jika tidak setuju, mohon untuk tidak menggunakan layanan kami.</div>
      </div>

      <!-- 1 -->
      <section class="snk-sec" id="ketentuan-umum" data-judul="Ketentuan Umum dan Definisi">
        <h2><span class="n">1</span>Ketentuan Umum dan Definisi</h2>
        <p>ruangtitip adalah layanan penitipan barang untuk mahasiswa dan masyarakat umum, dikelola oleh <span class="snk-placeholder">[nama tim pengelola / nama badan usaha setelah terdaftar]</span> (selanjutnya disebut "Kami"). Seluruh fasilitas penyimpanan, pengawasan barang, dan layanan antar-jemput dikelola langsung oleh tim ruangtitip secara terpusat, tanpa melibatkan pihak lain sebagai penyedia tempat.</p>
        <p>Dalam Syarat dan Ketentuan ini:</p>
        <ul class="snk-def">
          <li><b>Platform</b>Situs web ruangtitip.id beserta aplikasi dan layanan lain yang Kami sediakan.</li>
          <li><b>Pengguna / Penitip</b>Setiap orang yang masuk ke Platform untuk menitipkan barang atau memakai layanan lain dari ruangtitip.</li>
          <li><b>Gudang ruangtitip</b>Fasilitas penyimpanan yang dikelola tim ruangtitip, saat ini berlokasi di Kota Malang.</li>
          <li><b>Barang</b>Kardus, koper/tas, atau barang umum milik Penitip yang dititipkan melalui Platform.</li>
          <li><b>Pesanan</b>Permintaan layanan yang dibuat Pengguna dan dibayar melalui Platform.</li>
          <li><b>Masa Penitipan</b>Jangka waktu penitipan yang dipilih dalam Pesanan, dalam hitungan hari, bulan, atau tahun.</li>
          <li><b>Biaya Penitipan</b>Harga penitipan berdasarkan jenis, ukuran, dan Masa Penitipan Barang.</li>
          <li><b>Segel ruangtitip</b>Stiker segel resmi yang ditempel pada setiap Barang saat diterima di Gudang ruangtitip.</li>
          <li><b>Galeri Visual</b>Halaman di Platform yang menampilkan foto kondisi Barang yang diunggah oleh tim ruangtitip.</li>
          <li><b>Garansi Batas Tetap</b>Jaminan ganti rugi dari Kami atas kerusakan atau kehilangan Barang sesuai Bagian 8.</li>
        </ul>
        <p>Syarat dan Ketentuan ini berlaku bersama Kebijakan Privasi ruangtitip, yang mengatur cara Kami mengumpulkan dan menggunakan data pribadi Anda.</p>
      </section>

      <!-- 2 -->
      <section class="snk-sec" id="akun" data-judul="Akun dan Akses Platform">
        <h2><span class="n">2</span>Akun dan Akses Platform</h2>
        <ol>
          <li>Anda tidak perlu mendaftar secara terpisah. Cukup masuk menggunakan akun Google atau alamat email Anda.</li>
          <li>Jika masuk dengan email, Kami akan mengirimkan kode OTP (One-Time Password) ke email tersebut. Jangan berikan kode OTP kepada siapa pun, termasuk orang yang mengaku sebagai tim ruangtitip.</li>
          <li>Nomor WhatsApp dan alamat (kos atau titik jemput) baru diminta saat Anda membuat Pesanan. Pastikan data tersebut benar dan aktif, karena dipakai untuk penjemputan, pengantaran, dan pemberitahuan status Pesanan.</li>
          <li>Anda bertanggung jawab atas semua aktivitas yang terjadi melalui akun Google atau email yang Anda gunakan. Segera hubungi Kami jika Anda menduga akun Anda disalahgunakan.</li>
        </ol>
      </section>

      <!-- 3 -->
      <section class="snk-sec" id="hak-kewajiban-penitip" data-judul="Hak dan Kewajiban Penitip">
        <h2><span class="n">3</span>Hak dan Kewajiban Penitip</h2>
        <div class="snk-dua">
          <div class="snk-kartu hak">
            <h3><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>Penitip berhak</h3>
            <ul>
              <li>Mendapatkan ruang penyimpanan sesuai jenis, ukuran, dan Masa Penitipan dalam Pesanan.</li>
              <li>Memantau status Pesanan dan melihat foto kondisi Barang melalui Galeri Visual.</li>
              <li>Mengambil kembali Barang dengan Segel ruangtitip dalam keadaan utuh.</li>
              <li>Mengajukan klaim Garansi Batas Tetap sesuai Bagian 8.</li>
            </ul>
          </div>
          <div class="snk-kartu">
            <h3><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 11l3 3L22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg>Penitip wajib</h3>
            <ol>
              <li>Memastikan Barang adalah milik sendiri atau sah dikuasai, dan isinya tidak termasuk barang yang dilarang (Bagian 5).</li>
              <li>Memilih jenis dan ukuran Barang (kardus, koper/tas, atau barang umum) sesuai kondisi sebenarnya. Penitip tidak wajib merinci isi Barang, tetapi tetap bertanggung jawab penuh atas isinya.</li>
              <li>Memahami dan menyetujui bahwa ganti rugi atas Barang dibatasi sesuai Garansi Batas Tetap (Bagian 8), berapa pun nilai isi Barang tersebut.</li>
              <li>Mengemas Barang dengan layak dan tertutup, kecuali memakai layanan pengemasan oleh tim ruangtitip.</li>
              <li>Membayar seluruh biaya Pesanan melalui Platform sebelum penjemputan atau penyerahan Barang.</li>
              <li>Hadir atau menyiapkan Barang sesuai jadwal yang dipilih, baik saat menitip maupun mengambil Barang.</li>
              <li>Tidak melakukan pembayaran di luar Platform atau kepada pihak yang mengatasnamakan ruangtitip tanpa melalui Platform.</li>
            </ol>
          </div>
        </div>
      </section>

      <!-- 4 -->
      <section class="snk-sec" id="kewajiban-ruangtitip" data-judul="Kewajiban ruangtitip">
        <h2><span class="n">4</span>Kewajiban ruangtitip</h2>
        <p>Sebagai pengelola layanan, Kami wajib:</p>
        <ol>
          <li>Menyimpan Barang di Gudang ruangtitip yang aman, kering, terkunci, diawasi kamera CCTV, dan hanya dapat diakses oleh tim yang berwenang.</li>
          <li>Menempelkan Segel ruangtitip pada setiap Barang saat diterima di gudang, lalu mengunggah foto Barang beserta segelnya ke Galeri Visual.</li>
          <li>Tidak membuka, memakai, meminjamkan, menjual, atau menggadaikan Barang. Segel hanya dapat dibuka bersama Penitip, atau bila ada dugaan kuat Barang berisi barang terlarang atau membahayakan.</li>
          <li>Tidak memindahkan Barang ke lokasi lain tanpa memberi tahu Penitip, kecuali dalam keadaan darurat untuk melindungi Barang.</li>
          <li>Memperbarui status Pesanan dan memberi tahu Penitip melalui WhatsApp pada setiap tahap layanan.</li>
          <li>Segera memberi tahu Penitip jika terjadi kerusakan, kehilangan, atau kejadian lain yang memengaruhi Barang.</li>
          <li>Menyimpan Barang sampai Masa Penitipan berakhir atau sampai Penitip mengambilnya.</li>
        </ol>
      </section>

      <!-- 5 -->
      <section class="snk-sec" id="barang-dilarang" data-judul="Barang yang Dilarang">
        <h2><span class="n">5</span>Barang yang Dilarang</h2>
        <p>Barang berikut tidak boleh dititipkan melalui ruangtitip:</p>
        <ul class="snk-larang">
          <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"><circle cx="12" cy="12" r="10"/><path d="M4.9 4.9l14.2 14.2"/></svg>Uang tunai, emas, perhiasan, surat berharga, dan dokumen penting seperti ijazah, sertifikat, atau paspor.</li>
          <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"><circle cx="12" cy="12" r="10"/><path d="M4.9 4.9l14.2 14.2"/></svg>Narkotika, psikotropika, obat keras tanpa resep, dan minuman beralkohol.</li>
          <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"><circle cx="12" cy="12" r="10"/><path d="M4.9 4.9l14.2 14.2"/></svg>Senjata api, senjata tajam, amunisi, dan bahan peledak, termasuk petasan dan kembang api.</li>
          <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"><circle cx="12" cy="12" r="10"/><path d="M4.9 4.9l14.2 14.2"/></svg>Bahan mudah terbakar atau berbahaya, seperti tabung gas, bensin, dan cairan kimia.</li>
          <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"><circle cx="12" cy="12" r="10"/><path d="M4.9 4.9l14.2 14.2"/></svg>Makanan, minuman, atau bahan lain yang mudah busuk.</li>
          <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"><circle cx="12" cy="12" r="10"/><path d="M4.9 4.9l14.2 14.2"/></svg>Hewan dan tanaman hidup.</li>
          <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"><circle cx="12" cy="12" r="10"/><path d="M4.9 4.9l14.2 14.2"/></svg>Barang hasil kejahatan, barang selundupan, atau barang yang melanggar hukum.</li>
          <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"><circle cx="12" cy="12" r="10"/><path d="M4.9 4.9l14.2 14.2"/></svg>Barang yang menimbulkan bau, kebocoran, atau berpotensi merusak barang lain.</li>
        </ul>
        <p>Jika Barang terbukti termasuk barang yang dilarang, Kami berhak menolak Barang tersebut dan membatalkan Pesanan tanpa pengembalian biaya. Garansi Batas Tetap atas barang tersebut juga tidak berlaku. Bila diwajibkan hukum, Kami dapat melaporkannya kepada pihak berwenang.</p>
      </section>

      <!-- 6 -->
      <section class="snk-sec" id="biaya-pembayaran" data-judul="Biaya dan Pembayaran">
        <h2><span class="n">6</span>Biaya dan Pembayaran</h2>
        <h3>Biaya</h3>
        <ol>
          <li>Biaya Penitipan dihitung berdasarkan jenis Barang (kardus, koper/tas, atau barang umum), ukuran atau dimensinya, dan Masa Penitipan (harian, bulanan, atau tahunan). Daftar tarif terbaru ditampilkan di Platform.</li>
          <li>Biaya logistik mengikuti opsi yang dipilih Penitip:</li>
        </ol>
        <div class="snk-opsi">
          <div><b>Packing &amp; Antar-Jemput Tim ruangtitip</b><span>Biaya pengemasan per kardus ditambah tarif jarak tempuh (per km).</span><em class="tag">Premium</em></div>
          <div><b>Instant Biteship</b><span>Tarif kurir mitra sesuai perhitungan Biteship.</span><em class="tag">Mitra</em></div>
          <div><b>Antar/Ambil Sendiri</b><span>Tanpa biaya logistik.</span><em class="tag">Rp0</em></div>
        </div>
        <ol start="3">
          <li>Perlengkapan tambahan seperti kardus atau lakban dapat ditambahkan ke Pesanan dengan harga yang tertera di Platform.</li>
          <li>Seluruh biaya di atas dihitung otomatis oleh Platform. Total tagihan ditampilkan sebelum Penitip mengonfirmasi Pesanan.</li>
        </ol>
        <h3>Pembayaran</h3>
        <ol start="5">
          <li>Semua pembayaran dilakukan melalui Platform menggunakan mitra payment gateway resmi, saat ini Tripay, dengan metode QRIS atau Virtual Account. Kami tidak menyimpan data kartu atau rekening Anda.</li>
          <li>Pesanan baru aktif setelah pembayaran berhasil diverifikasi secara otomatis oleh sistem. Pesanan yang belum dibayar dalam <strong>1×24 jam</strong> akan dibatalkan otomatis.</li>
          <li>Pembayaran yang sudah berhasil tidak dapat dibatalkan atau dikembalikan, kecuali Kami tidak dapat menyediakan layanan sesuai Pesanan.</li>
          <li>Pembayaran di luar Platform tidak dilindungi oleh Garansi Batas Tetap dan bukan tanggung jawab Kami.</li>
        </ol>
        <h3>Perpanjangan</h3>
        <ol start="9">
          <li>Masa Penitipan dapat diperpanjang melalui Platform paling lambat <strong>3 hari</strong> sebelum masa berakhir, dengan tarif yang berlaku saat itu.</li>
        </ol>
      </section>

      <!-- 7 -->
      <section class="snk-sec" id="antar-jemput" data-judul="Antar-Jemput, Serah Terima, dan Masa Penitipan">
        <h2><span class="n">7</span>Antar-Jemput, Serah Terima, dan Masa Penitipan</h2>
        <h3>Antar-Jemput</h3>
        <ol>
          <li>Jadwal penjemputan atau pengantaran dipilih melalui Platform dan hanya tersedia pada jam operasional tim ruangtitip.</li>
          <li>Untuk opsi Instant Biteship, kerusakan atau kehilangan selama perjalanan mengikuti ketentuan dan tanggung jawab kurir mitra. Kami akan membantu proses klaimnya ke kurir.</li>
          <li>Untuk opsi Antar/Ambil Sendiri, Penitip datang ke Gudang ruangtitip sesuai jadwal yang dipilih.</li>
        </ol>
        <h3>Serah Terima dan Dokumentasi</h3>
        <ol start="4">
          <li>Setelah Barang tiba di Gudang ruangtitip, tim Kami menempelkan Segel ruangtitip lalu mengunggah foto Barang beserta segelnya ke Galeri Visual. Ketentuan ini berlaku sama untuk ketiga opsi logistik.</li>
          <li>Foto di Galeri Visual menjadi bukti utama kondisi Barang saat diterima. Jika ada ketidaksesuaian, Penitip wajib melapor paling lambat <strong>1×24 jam</strong> setelah foto diunggah.</li>
          <li>Saat mengambil Barang, Penitip wajib memeriksa kondisi kemasan dan Segel ruangtitip. Keberatan harus disampaikan paling lambat <strong>2×24 jam</strong> setelah Barang diterima. Lewat dari waktu itu, Barang dianggap diterima dalam kondisi baik.</li>
        </ol>
        <h3>Keterlambatan Pengambilan</h3>
        <ol start="7">
          <li>Penitip mendapat <strong>masa tenggang 2 hari</strong> setelah Masa Penitipan berakhir tanpa biaya tambahan.</li>
          <li>Setelah masa tenggang, Penitip dikenai biaya keterlambatan sebesar <strong>1,5 kali tarif harian</strong> sesuai jenis dan ukuran Barang, untuk setiap hari keterlambatan, sampai Barang diambil atau Masa Penitipan diperpanjang.</li>
          <li>Kami akan mengirim pengingat melalui WhatsApp 3 hari sebelum Masa Penitipan berakhir, pada hari terakhir, dan selama masa keterlambatan.</li>
        </ol>
        <h3>Barang yang Tidak Diambil</h3>
        <ol start="10">
          <li>Jika Barang tidak diambil dan Penitip tidak dapat dihubungi selama <strong>30 hari</strong> setelah Masa Penitipan berakhir, Kami akan mengirim pemberitahuan terakhir secara tertulis.</li>
          <li>Jika dalam <strong>14 hari</strong> setelah pemberitahuan terakhir tetap tidak ada tanggapan, Kami berhak mengirim Barang ke alamat Penitip yang terdaftar atas biaya Penitip, atau menyalurkan Barang sebagai donasi. Biaya penitipan dan keterlambatan yang belum dibayar tetap menjadi kewajiban Penitip.</li>
        </ol>
      </section>

      <!-- 8 -->
      <section class="snk-sec" id="garansi" data-judul="Garansi Batas Tetap">
        <h2><span class="n">8</span>Garansi Batas Tetap</h2>
        <p>Setiap Barang yang dititipkan otomatis dilindungi Garansi Batas Tetap. Kami memberikan ganti rugi atas Barang yang rusak atau hilang selama berada di Gudang ruangtitip, dengan nilai maksimum sesuai jenis dan ukuran Barang. Karena Penitip tidak wajib merinci isi Barang, batas ini berlaku berapa pun nilai isi Barang tersebut.</p>
        <div class="snk-tabel-wrap">
          <table class="snk-tabel">
            <thead>
              <tr><th>Jenis Barang</th><th>Ukuran</th><th>Dimensi</th><th>Nilai Acuan Isi</th><th>Batas Maks. Ganti Rugi</th></tr>
            </thead>
            <tbody>
              <tr class="grup"><td>Kardus</td><td>S</td><td>35 × 25 × 20 cm</td><td class="uang">Rp100.000</td><td class="uang maks">Rp50.000</td></tr>
              <tr><td>Kardus</td><td>M</td><td>45 × 30 × 25 cm</td><td class="uang">Rp200.000</td><td class="uang maks">Rp100.000</td></tr>
              <tr><td>Kardus</td><td>L</td><td>55 × 35 × 28 cm</td><td class="uang">Rp300.000</td><td class="uang maks">Rp150.000</td></tr>
              <tr><td>Kardus</td><td>XL</td><td>60 × 40 × 30 cm</td><td class="uang">Rp400.000</td><td class="uang maks">Rp200.000</td></tr>
              <tr class="grup"><td>Koper/Tas</td><td>S</td><td>21"–23"</td><td class="uang">Rp200.000</td><td class="uang maks">Rp100.000</td></tr>
              <tr><td>Koper/Tas</td><td>M</td><td>24"–25"</td><td class="uang">Rp300.000</td><td class="uang maks">Rp150.000</td></tr>
              <tr><td>Koper/Tas</td><td>L</td><td>26"–28"</td><td class="uang">Rp400.000</td><td class="uang maks">Rp200.000</td></tr>
              <tr><td>Koper/Tas</td><td>XL</td><td>30"</td><td class="uang">Rp500.000</td><td class="uang maks">Rp250.000</td></tr>
              <tr class="grup"><td>Barang Umum</td><td>Satu ukuran</td><td>Maks. 50 × 70 × 100 cm</td><td class="uang">Rp400.000</td><td class="uang maks">Rp200.000</td></tr>
            </tbody>
          </table>
        </div>
        <p>Nilai Acuan Isi adalah perkiraan nilai barang dalam satu paket, dengan asumsi isinya barang kebutuhan mahasiswa seperti pakaian dan buku bekas pakai. Batas Maksimum Ganti Rugi ditetapkan sebesar 50% dari Nilai Acuan Isi. Ganti rugi yang dibayarkan adalah nilai kerugian yang terbukti, paling banyak sebesar batas tersebut, termasuk jika Barang hilang seluruhnya.</p>
        <div class="snk-catatan">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10.3 3.9 1.8 18a2 2 0 0 0 1.7 3h17a2 2 0 0 0 1.7-3L13.7 3.9a2 2 0 0 0-3.4 0z"/><path d="M12 9v4M12 17h.01"/></svg>
          <div>Barang bernilai tinggi seperti laptop, gawai, atau perhiasan sebaiknya tidak dititipkan, karena nilainya jauh melebihi batas ganti rugi.</div>
        </div>
        <h3>Syarat klaim</h3>
        <ol>
          <li>Barang tercatat dalam Pesanan dengan jenis dan ukuran yang benar, dan fotonya sudah ada di Galeri Visual.</li>
          <li>Klaim diajukan melalui WhatsApp Admin paling lambat <strong>2×24 jam</strong> setelah Barang diterima kembali, atau setelah Kami memberi tahu adanya kehilangan.</li>
          <li>Penitip melampirkan foto kerusakan dan, jika ada, bukti pendukung lain.</li>
          <li>Untuk kardus atau barang tertutup, kerusakan dinilai dari kondisi kemasan dan Segel ruangtitip. Jika segel masih utuh dan kemasan tidak rusak, isi Barang dianggap dalam kondisi sama seperti saat dititipkan.</li>
        </ol>
        <h3>Garansi Batas Tetap tidak berlaku untuk</h3>
        <ul>
          <li>Barang yang dilarang (Bagian 5) atau Barang yang tidak tercatat dalam Pesanan.</li>
          <li>Kerusakan karena kemasan yang dibuat Penitip tidak layak, sifat barang itu sendiri (misalnya mudah pecah tanpa pelindung), atau keausan wajar.</li>
          <li>Kerugian karena bencana alam, kebakaran, kerusuhan, atau keadaan kahar lainnya.</li>
          <li>Kerusakan selama pengiriman oleh kurir mitra (Bagian 7 butir 2).</li>
          <li>Transaksi atau pembayaran di luar Platform.</li>
        </ul>
        <p>Kami akan memproses klaim paling lambat <strong>14 hari kerja</strong> setelah dokumen lengkap. Ganti rugi dibayarkan ke rekening atau e-wallet Penitip.</p>
      </section>

      <!-- 9 -->
      <section class="snk-sec" id="layanan-tambahan" data-judul="Layanan Tambahan">
        <h2><span class="n">9</span>Layanan Tambahan</h2>
        <h3>Toko Packing</h3>
        <ol>
          <li>Pengguna dapat membeli perlengkapan pengemasan (kardus, lakban, bubble wrap) bersamaan dengan Pesanan penitipan atau secara terpisah tanpa menitipkan barang.</li>
          <li>Perlengkapan dapat diambil sendiri atau dikirim melalui Instant Biteship. Barang yang sudah dibeli tidak dapat ditukar atau dikembalikan, kecuali rusak atau tidak sesuai pesanan saat diterima.</li>
        </ol>
        <h3>Toko Preloved</h3>
        <ol start="3">
          <li>Pengguna yang ingin menjual barang bekas pakai membawa barangnya ke Gudang ruangtitip untuk diperiksa. Kami berhak menolak barang yang rusak, tidak layak jual, atau termasuk barang yang dilarang.</li>
          <li>Harga jual disepakati bersama sebelum barang ditampilkan di katalog Platform. Kami mengambil biaya layanan sebesar <span class="snk-placeholder">[__]%</span> dari harga jual, dan sisanya dibayarkan ke penjual paling lambat <span class="snk-placeholder">[__] hari kerja</span> setelah barang terjual.</li>
          <li>Barang yang belum terjual dalam <span class="snk-placeholder">[__] hari</span> dapat diambil kembali oleh penjual atau dikenai Biaya Penitipan sesuai tarif yang berlaku.</li>
          <li>Pembeli disarankan memeriksa foto dan deskripsi barang dengan teliti. Barang preloved dijual sesuai kondisi yang ditampilkan dan tidak dapat dikembalikan, kecuali kondisinya berbeda dari deskripsi.</li>
        </ol>
        <h3>Kirim Barang ke Kota Lain</h3>
        <ol start="7">
          <li>Selama Barang berstatus "Dalam Gudang ruangtitip", Penitip dapat meminta Barang dikirim ke alamat lain melalui fitur "Kirim Barang Saya".</li>
          <li>Biaya pengiriman dihitung otomatis berdasarkan tarif ekspedisi melalui Biteship, ditambah biaya layanan ruangtitip. Setelah Barang diserahkan ke ekspedisi, tanggung jawab pengiriman beralih ke ekspedisi tersebut sesuai ketentuannya.</li>
        </ol>
        <h3>Ulasan</h3>
        <ol start="9">
          <li>Setelah Pesanan selesai, Pengguna dapat memberi rating dan ulasan. Ulasan dapat Kami tampilkan di Platform sebagai testimoni. Kami berhak menyembunyikan ulasan yang mengandung kata kasar, spam, atau informasi pribadi.</li>
        </ol>
      </section>

      <!-- 10 -->
      <section class="snk-sec" id="tanggung-jawab" data-judul="Batasan Tanggung Jawab dan Penyelesaian Sengketa">
        <h2><span class="n">10</span>Batasan Tanggung Jawab dan Penyelesaian Sengketa</h2>
        <h3>Batasan Tanggung Jawab</h3>
        <ol>
          <li>Tanggung jawab Kami atas kerusakan atau kehilangan Barang adalah sebesar ganti rugi dalam Garansi Batas Tetap (Bagian 8), kecuali kerugian terbukti disebabkan kesengajaan atau kelalaian berat tim ruangtitip.</li>
          <li>Kebenaran informasi Barang, termasuk jenis, jumlah, dan nilainya, menjadi tanggung jawab Penitip.</li>
          <li>Kami berupaya menjaga Platform tetap berjalan, tetapi tidak menjamin Platform selalu bebas gangguan. Kami tidak bertanggung jawab atas gangguan yang disebabkan pihak ketiga, seperti penyedia jaringan, layanan login Google, payment gateway, atau kurir.</li>
        </ol>
        <h3>Penyelesaian Sengketa</h3>
        <ol start="4">
          <li>Keluhan atau sengketa terkait layanan diselesaikan lebih dulu secara musyawarah melalui WhatsApp Admin ruangtitip (Bagian 11).</li>
          <li>Kami akan menanggapi keluhan paling lambat <strong>3 hari kerja</strong> dan berupaya menyelesaikannya paling lambat <strong>14 hari kerja</strong>, berdasarkan bukti Serah Terima, riwayat percakapan, dan dokumen lain di Platform.</li>
          <li>Jika sengketa tetap tidak selesai, para pihak dapat menempuh jalur Badan Penyelesaian Sengketa Konsumen (BPSK) atau Pengadilan Negeri Malang.</li>
        </ol>
      </section>

      <!-- 11 -->
      <section class="snk-sec" id="ketentuan-lain" data-judul="Ketentuan Lain">
        <h2><span class="n">11</span>Ketentuan Lain</h2>
        <h3>Penangguhan dan Penutupan Akun</h3>
        <ol>
          <li>Kami berhak memberi peringatan, membatasi fitur, menangguhkan, atau menutup akun yang melanggar Syarat dan Ketentuan ini. Pelanggaran tersebut antara lain memberikan data palsu, bertransaksi di luar Platform, menitipkan barang terlarang, atau menyalahgunakan layanan.</li>
          <li>Penutupan akun tidak menghapus kewajiban yang belum selesai, seperti Pesanan yang masih berjalan atau klaim yang sedang diproses.</li>
          <li>Anda dapat menutup akun kapan saja melalui pengaturan akun, setelah semua Pesanan selesai.</li>
        </ol>
        <h3>Perubahan Syarat dan Ketentuan</h3>
        <ol start="4">
          <li>Kami dapat mengubah Syarat dan Ketentuan ini sewaktu-waktu. Perubahan penting akan diberitahukan melalui email atau Platform paling lambat <strong>7 hari</strong> sebelum berlaku. Dengan tetap memakai Platform setelah tanggal itu, Anda dianggap menyetujui perubahannya.</li>
        </ol>
        <h3>Hukum yang Berlaku</h3>
        <ol start="5">
          <li>Syarat dan Ketentuan ini tunduk pada hukum Republik Indonesia.</li>
        </ol>
        <h3>Hubungi Kami</h3>
        <p>Pertanyaan, laporan, atau keluhan dapat disampaikan melalui:</p>
        <div class="snk-kontak">
          <a href="mailto:ruangtitipmu@gmail.com">
            <span class="ik"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="m22 6-10 7L2 6"/></svg></span>
            <span><small>Email</small><b>ruangtitipmu@gmail.com</b></span>
          </a>
          <a href="https://wa.me/6285121091134" target="_blank" rel="noopener">
            <span class="ik"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 11.5a8.4 8.4 0 0 1-12.4 7.4L3 21l2.1-5.6A8.4 8.4 0 1 1 21 11.5z"/></svg></span>
            <span><small>WhatsApp Admin</small><b>0851-2109-1134</b></span>
          </a>
          <a href="https://ruangtitip.id" target="_blank" rel="noopener">
            <span class="ik"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M2 12h20M12 2a15.3 15.3 0 0 1 0 20M12 2a15.3 15.3 0 0 0 0 20"/></svg></span>
            <span><small>Situs</small><b>ruangtitip.id</b></span>
          </a>
        </div>
      </section>


      <aside class="cs">
        <img src="{{ asset('assets/ruru/ruru-tunjuk.webp') }}" alt="" width="92" height="86">
        <div class="t">
          <strong>Ada yang belum jelas? Tanya Ruru aja.</strong>
          <p>Tim RuangTitip siap jelasin isi Syarat &amp; Ketentuan ini lewat WhatsApp atau email.</p>
          <div class="acts">
            <a class="btn btn-wa btn-sm" href="https://wa.me/6285121091134?text=Halo%20RuangTitip%2C%20saya%20mau%20tanya%20soal%20Syarat%20%26%20Ketentuan" target="_blank" rel="noopener">
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
  <div class="wrap"><span>&copy; {{ date('Y') }} RuangTitip &middot; Malang, Indonesia</span><span><a href="{{ route('legal.terms') }}" aria-current="page">Syarat &amp; Ketentuan</a><a href="{{ route('legal.privacy') }}">Kebijakan Privasi</a></span></div>
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

  var top = document.getElementById('to-top');
  window.addEventListener('scroll', function () { top.classList.toggle('tampil', window.scrollY > 700); }, { passive: true });
  top.addEventListener('click', function () { window.scrollTo({ top: 0, behavior: 'smooth' }); });
})();
</script>
</body>
</html>
