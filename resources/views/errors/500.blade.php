<!doctype html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="robots" content="noindex">
<title>Terjadi kesalahan &middot; RuangTitip</title>
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
html{overflow-x:hidden}
body{margin:0;min-height:100vh;font-family:var(--font-body);background:var(--cream);color:var(--ink);font-size:16px;line-height:1.6;-webkit-font-smoothing:antialiased}
img,svg{display:block;max-width:100%}
a{color:var(--tape-dark)}
h1,h2{font-family:var(--font-display);margin:0;line-height:1.05}
p{margin:0}
button{font:inherit;color:inherit}
:focus-visible{outline:3px solid var(--tape);outline-offset:3px}

.page{max-width:1080px;margin:0 auto;padding:28px 24px 56px}
.top{display:flex;justify-content:space-between;align-items:center}
.top .logo svg{height:40px;width:auto}

.hero{display:grid;grid-template-columns:1fr 1.1fr;gap:40px;align-items:center;margin-top:48px}

/* Ruru yang "tertimpa" kardus */
.scene{position:relative;display:flex;justify-content:center;align-items:flex-end;min-height:420px}
.scene .disc{position:absolute;bottom:26px;width:min(380px,90%);aspect-ratio:1;border-radius:50%;background:var(--sand);border:2px solid var(--ink)}
.scene .ruru{position:relative;width:min(260px,62vw);margin-bottom:40px;transform:rotate(-9deg);transform-origin:50% 100%;animation:wobble 3s ease-in-out infinite}
@keyframes wobble{0%,100%{transform:rotate(-9deg)}50%{transform:rotate(-4deg)}}
.scene .shadow{position:absolute;bottom:30px;width:220px;height:22px;border-radius:50%;background:var(--ink);opacity:.14}
.fallen{position:absolute;width:74px;height:56px;background:var(--kraft);border:2px solid var(--ink);border-radius:5px}
.fallen::before{content:"";position:absolute;left:50%;top:-2px;bottom:-2px;width:12px;transform:translateX(-50%);background:var(--tape)}
.fallen.f1{left:10%;bottom:34px;transform:rotate(-14deg)}
.fallen.f2{right:8%;bottom:34px;width:60px;height:46px;transform:rotate(11deg)}
.fallen.f3{right:18%;bottom:74px;width:52px;height:40px;transform:rotate(-24deg)}
.stars{position:absolute;top:18%;left:50%;transform:translateX(-10%);font-family:var(--font-display);font-weight:800;color:var(--tape);font-size:30px;letter-spacing:6px;animation:spin 2.4s linear infinite}
@keyframes spin{50%{transform:translateX(-10%) rotate(10deg) scale(1.1)}}

.code{display:inline-flex;align-items:center;gap:8px;padding:6px 14px;border-radius:999px;background:var(--tape-soft);border:1.5px solid var(--tape);color:var(--tape-dark);font-weight:700;font-size:14px}
h1{font-size:clamp(40px,5vw,62px);font-weight:800;letter-spacing:-2px;margin-top:16px}
h1 em{font-style:normal;color:var(--tape)}
.lead{font-size:18px;color:var(--body);margin-top:14px;max-width:500px}
.safe{display:flex;gap:12px;align-items:flex-start;margin-top:20px;padding:14px 16px;background:var(--depot-light);border:1.5px solid var(--depot);border-radius:14px;color:#1F4535;font-weight:600;font-size:15px;max-width:500px}
.safe svg{flex-shrink:0;margin-top:2px}
.actions{display:flex;gap:10px;flex-wrap:wrap;margin-top:24px}
.btn{display:inline-flex;align-items:center;justify-content:center;gap:8px;min-height:50px;padding:0 22px;border-radius:999px;font-weight:700;font-size:15px;text-decoration:none;border:2px solid var(--ink);cursor:pointer;white-space:nowrap;transition:transform .12s}
.btn-primary{background:var(--tape);color:#fff;box-shadow:3px 3px 0 var(--ink)}
.btn-primary:hover{background:var(--tape-dark);color:#fff}
.btn-primary:active{transform:translate(2px,2px);box-shadow:1px 1px 0 var(--ink)}
.btn-outline{background:var(--paper);color:var(--ink)}
.btn-outline:hover{background:var(--sand);color:var(--ink)}
.ref{margin-top:18px;font-size:13px;color:var(--muted)}
.ref code{font-family:ui-monospace,Menlo,monospace;background:var(--paper);border:1px solid var(--line-strong);padding:2px 8px;border-radius:6px;color:var(--ink)}
.ref button{background:none;border:0;padding:0 4px;color:var(--tape-dark);font-weight:700;cursor:pointer;text-decoration:underline;text-underline-offset:3px;min-height:32px}

/* Game */
.game{margin-top:64px;background:var(--paper);border:2px solid var(--ink);border-radius:24px;box-shadow:6px 6px 0 var(--ink);overflow:hidden}
.g-head{display:flex;justify-content:space-between;align-items:center;gap:16px;padding:18px 24px;border-bottom:2px solid var(--ink);flex-wrap:wrap}
.g-head h2{font-size:26px;letter-spacing:-.5px}
.g-head p{color:var(--body);font-size:15px}
.hud{display:flex;gap:10px}
.hud div{padding:8px 14px;border-radius:12px;background:var(--cream);border:1.5px solid var(--line-strong);text-align:center;min-width:78px}
.hud small{display:block;font-size:12px;font-weight:700;color:var(--muted)}
.hud b{font-family:var(--font-display);font-size:22px;line-height:1.15}
.hud .lives b{color:var(--tape)}
.arena{position:relative;height:400px;background:var(--cream);touch-action:none;user-select:none;cursor:none}
.arena canvas{display:block;width:100%;height:100%}
.legend{display:flex;gap:18px;flex-wrap:wrap;padding:12px 24px;border-top:1px solid var(--line);font-size:14px;color:var(--body)}
.legend span{display:inline-flex;align-items:center;gap:8px}
.legend i{width:18px;height:18px;border-radius:4px;border:1.5px solid var(--ink);display:inline-block}
.overlay{position:absolute;inset:0;display:flex;flex-direction:column;align-items:center;justify-content:center;gap:12px;text-align:center;background:rgba(245,241,232,.92);padding:24px;cursor:default}
.overlay[hidden]{display:none}
.overlay strong{font-family:var(--font-display);font-size:30px}
.overlay p{color:var(--body);max-width:380px}

.foot{margin-top:36px;text-align:center;font-size:14px;color:var(--muted)}

@media (max-width:860px){
  .hero{grid-template-columns:1fr;gap:4px;margin-top:24px}
  .scene{min-height:340px}
  .arena{height:360px}
}
@media (prefers-reduced-motion:reduce){*{animation:none!important;transition:none!important}}
</style>
</head>
<body>
<div class="page">
  <header class="top">
    <a class="logo" href="{{ url('/') }}" aria-label="RuangTitip, ke beranda"><svg role="img" aria-label="RuangTitip" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 715 198" width="715" height="198"><g transform="translate(24.00 24.00) scale(1.5000)"><polyline points="10,50 50,13 90,50" fill="none" stroke="#1C1B18" stroke-width="9" stroke-linecap="round" stroke-linejoin="round"/><rect x="22" y="46" width="56" height="42" rx="5" fill="#C9A27A"/><rect x="44" y="46" width="12" height="21" fill="#B4531D"/><rect x="22" y="46" width="56" height="42" rx="5" fill="none" stroke="#1C1B18" stroke-width="6"/></g><path fill="#1C1B18" d="M198.448 155.25V100.338H212.176L212.488 118.95400000000001H214.56799999999998Q215.296 111.674 217.324 107.358Q219.352 103.042 223.148 101.066Q226.944 99.09 232.56 99.09Q233.392 99.09 234.43200000000002 99.142Q235.472 99.194 236.824 99.402L236.2 117.28999999999999Q234.536 116.458 232.612 116.19800000000001Q230.688 115.938 229.232 115.938Q225.176 115.938 222.212 117.49799999999999Q219.248 119.05799999999999 217.53199999999998 122.282Q215.816 125.506 215.296 130.394V155.25Z"/><path fill="#1C1B18" d="M261.784 156.706Q252.008 156.706 247.06799999999998 150.10199999999998Q242.128 143.498 242.128 129.978V100.338H258.976V127.794Q258.976 135.49 261.264 139.286Q263.552 143.082 268.23199999999997 143.082Q271.04 143.082 273.068 141.678Q275.096 140.274 276.5 137.726Q277.904 135.178 278.58 131.85Q279.256 128.522 279.256 124.57V100.338H296.0V155.25H282.688L282.376 138.298H280.296Q278.736 144.746 276.39599999999996 148.85399999999998Q274.056 152.962 270.52 154.834Q266.984 156.706 261.784 156.706Z"/><path fill="#1C1B18" d="M319.92 156.706Q315.344 156.706 311.808 154.886Q308.27200000000005 153.066 306.29600000000005 149.582Q304.32000000000005 146.098 304.32000000000005 141.106Q304.32000000000005 136.426 306.14000000000004 133.358Q307.96000000000004 130.29 311.444 128.47Q314.928 126.65 319.81600000000003 125.45400000000001Q324.704 124.25800000000001 330.944 123.322Q333.96000000000004 122.80199999999999 335.98800000000006 122.334Q338.016 121.866 339.05600000000004 120.826Q340.096 119.786 340.096 117.81Q340.096 115.21000000000001 338.22400000000005 113.33800000000001Q336.35200000000003 111.46600000000001 332.192 111.46600000000001Q329.28000000000003 111.46600000000001 326.94000000000005 112.506Q324.6 113.54599999999999 322.93600000000004 115.52199999999999Q321.27200000000005 117.498 320.44 120.30600000000001L305.672 115.938Q307.024 111.57 309.468 108.346Q311.91200000000003 105.122 315.34400000000005 102.99000000000001Q318.776 100.858 323.196 99.87Q327.61600000000004 98.882 332.608 98.882Q340.928 98.882 346.18 101.534Q351.432 104.186 353.98 109.75Q356.528 115.314 356.528 124.05V132.474Q356.528 136.218 356.684 140.014Q356.84000000000003 143.81 357.1 147.606Q357.36 151.402 357.776 155.25H343.00800000000004Q342.59200000000004 152.858 342.17600000000004 149.478Q341.76 146.098 341.552 142.562H339.576Q338.12 146.514 335.36400000000003 149.79000000000002Q332.608 153.066 328.70799999999997 154.886Q324.808 156.706 319.92 156.706ZM327.512 144.85Q329.384 144.85 331.308 144.17399999999998Q333.232 143.498 335.0 142.302Q336.76800000000003 141.106 338.22400000000005 139.338Q339.68 137.57 340.408 135.49L340.20000000000005 128.938Q339.264 129.458 338.12 129.77Q335.728 130.498 333.284 130.914Q330.84000000000003 131.33 328.5 131.798Q326.16 132.266 324.34000000000003 132.994Q322.52000000000004 133.722 321.48 134.97Q320.44 136.218 320.44 138.402Q320.44 141.314 322.416 143.082Q324.392 144.85 327.512 144.85Z"/><path fill="#1C1B18" d="M367.032 155.25V100.338H380.552L380.76 116.458H382.84000000000003Q384.192 110.634 386.688 106.682Q389.184 102.73 393.03200000000004 100.80600000000001Q396.88 98.882 402.08 98.882Q411.752 98.882 416.79600000000005 105.642Q421.84000000000003 112.402 421.84000000000003 127.17V155.25H404.992V129.042Q404.992 120.51400000000001 402.548 116.614Q400.104 112.714 395.32 112.714Q391.368 112.714 388.82 115.106Q386.272 117.498 385.024 121.45Q383.776 125.402 383.776 130.29V155.25Z"/><path fill="#1C1B18" d="M454.184 174.49Q445.032 174.49 439.156 173.086Q433.28000000000003 171.682 430.576 168.926Q427.872 166.17 427.872 162.218Q427.872 157.434 431.668 154.418Q435.464 151.402 442.848 150.986V148.802Q437.128 148.802 434.06 147.65800000000002Q430.992 146.514 430.992 143.394Q430.992 140.482 434.00800000000004 138.09Q437.024 135.698 443.992 134.346V132.162Q437.648 131.642 433.904 127.95Q430.16 124.25800000000001 430.16 118.226Q430.16 113.13 433.072 108.918Q435.984 104.706 441.808 102.158Q447.632 99.61 456.368 99.61H484.864V112.194L467.496 109.906V112.402Q474.256 113.338 477.168 115.52199999999999Q480.08 117.706 480.08 121.658Q480.08 125.818 477.22 129.042Q474.36 132.266 469.15999999999997 134.086Q463.96 135.906 456.784 135.906Q455.536 135.906 454.028 135.80200000000002Q452.52 135.698 448.464 135.282Q446.8 136.738 445.86400000000003 137.726Q444.928 138.714 444.928 139.546Q444.928 140.17 445.448 140.586Q445.968 141.002 446.956 141.15800000000002Q447.944 141.314 449.088 141.314H465.832Q467.912 141.314 471.29200000000003 141.574Q474.672 141.834 478.052 143.186Q481.432 144.538 483.77200000000005 147.554Q486.112 150.57 486.112 156.186Q486.112 162.322 482.68 166.43Q479.248 170.538 472.176 172.514Q465.104 174.49 454.184 174.49ZM456.368 161.178Q462.4 161.178 465.72799999999995 160.554Q469.056 159.93 470.356 158.578Q471.656 157.226 471.656 155.25Q471.656 153.378 470.928 152.286Q470.2 151.194 469.056 150.778Q467.912 150.362 466.716 150.25799999999998Q465.52 150.154 464.688 150.154H451.272Q447.424 150.154 445.604 151.974Q443.784 153.794 443.784 156.186Q443.784 158.162 445.032 159.25400000000002Q446.28 160.346 449.036 160.762Q451.79200000000003 161.178 456.368 161.178ZM455.64 127.586Q460.112 127.586 462.348 125.19399999999999Q464.584 122.80199999999999 464.584 119.162Q464.584 115.21000000000001 462.296 112.662Q460.008 110.114 455.744 110.114Q451.48 110.114 449.088 112.662Q446.696 115.21000000000001 446.696 119.162Q446.696 121.554 447.736 123.47800000000001Q448.776 125.402 450.752 126.494Q452.728 127.586 455.64 127.586Z"/><path fill="#B4531D" d="M514.712 156.602Q505.04 156.602 500.46400000000006 151.454Q495.88800000000003 146.306 495.88800000000003 134.97V113.44200000000001H488.088L488.40000000000003 100.44200000000001H494.016Q497.34400000000005 100.338 499.06000000000006 99.402Q500.776 98.46600000000001 501.192 95.866L502.54400000000004 88.17H512.008V100.338H525.216V113.962H512.008V134.242Q512.008 137.778 513.724 139.442Q515.44 141.106 518.976 141.106Q520.952 141.106 522.72 140.69Q524.488 140.274 525.6320000000001 139.546V155.042Q522.3040000000001 156.082 519.548 156.34199999999998Q516.792 156.602 514.712 156.602Z"/><path fill="#B4531D" d="M533.64 155.25V100.338H550.384V155.25ZM542.0640000000001 92.642Q537.2800000000001 92.642 534.7320000000001 90.61399999999999Q532.1840000000001 88.58599999999998 532.1840000000001 84.84199999999998Q532.1840000000001 80.88999999999999 534.7320000000001 78.862Q537.2800000000001 76.83399999999999 542.0640000000001 76.83399999999999Q546.952 76.83399999999999 549.5 78.862Q552.048 80.88999999999999 552.048 84.73799999999999Q552.048 88.58599999999998 549.5 90.61399999999999Q546.952 92.642 542.0640000000001 92.642Z"/><path fill="#B4531D" d="M582.9359999999999 156.602Q573.264 156.602 568.688 151.454Q564.112 146.306 564.112 134.97V113.44200000000001H556.312L556.6239999999999 100.44200000000001H562.24Q565.568 100.338 567.284 99.402Q569.0 98.46600000000001 569.4159999999999 95.866L570.7679999999999 88.17H580.232V100.338H593.4399999999999V113.962H580.232V134.242Q580.232 137.778 581.948 139.442Q583.664 141.106 587.1999999999999 141.106Q589.1759999999999 141.106 590.944 140.69Q592.712 140.274 593.856 139.546V155.042Q590.528 156.082 587.7719999999999 156.34199999999998Q585.016 156.602 582.9359999999999 156.602Z"/><path fill="#B4531D" d="M601.8639999999999 155.25V100.338H618.608V155.25ZM610.288 92.642Q605.504 92.642 602.956 90.61399999999999Q600.408 88.58599999999998 600.408 84.84199999999998Q600.408 80.88999999999999 602.956 78.862Q605.504 76.83399999999999 610.288 76.83399999999999Q615.1759999999999 76.83399999999999 617.7239999999999 78.862Q620.2719999999999 80.88999999999999 620.2719999999999 84.73799999999999Q620.2719999999999 88.58599999999998 617.7239999999999 90.61399999999999Q615.1759999999999 92.642 610.288 92.642Z"/><path fill="#B4531D" d="M629.7359999999999 172.826V100.338H643.4639999999999L643.6719999999999 115.938L645.5439999999999 116.146Q646.4799999999999 110.322 648.8719999999998 106.474Q651.2639999999999 102.626 655.06 100.754Q658.8559999999999 98.882 663.7439999999999 98.882Q671.0239999999999 98.882 676.2759999999998 102.418Q681.5279999999999 105.95400000000001 684.3359999999999 112.506Q687.1439999999999 119.05799999999999 687.1439999999999 128.21Q687.1439999999999 136.322 684.6999999999998 142.76999999999998Q682.2559999999999 149.218 677.3159999999998 152.962Q672.3759999999999 156.706 664.7839999999999 156.706Q659.6879999999999 156.706 656.1519999999998 154.886Q652.6159999999999 153.066 650.1719999999998 149.53Q647.7279999999998 145.994 646.0639999999999 140.69H644.0879999999999Q644.7119999999999 143.498 645.232 146.41Q645.752 149.322 646.116 152.026Q646.4799999999999 154.73 646.4799999999999 157.226V172.826ZM658.6479999999999 143.498Q662.0799999999999 143.498 664.5239999999999 141.62599999999998Q666.9679999999998 139.754 668.3199999999999 136.322Q669.6719999999999 132.89 669.6719999999999 128.314Q669.6719999999999 123.426 668.2679999999999 119.94200000000001Q666.8639999999999 116.458 664.2639999999999 114.53399999999999Q661.6639999999999 112.61 658.2319999999999 112.61Q655.0079999999999 112.61 652.7719999999999 114.01400000000001Q650.536 115.418 649.1319999999998 117.654Q647.7279999999998 119.89 647.1039999999998 122.386Q646.4799999999999 124.882 646.4799999999999 127.066V129.354Q646.4799999999999 131.33 646.9999999999999 133.306Q647.5199999999999 135.282 648.56 137.102Q649.5999999999999 138.922 651.0559999999999 140.378Q652.512 141.834 654.4359999999999 142.666Q656.3599999999999 143.498 658.6479999999999 143.498Z"/></svg></a>
  </header>

  <main>
    <section class="hero">
      <div class="scene" aria-hidden="true">
        <div class="disc"></div>
        <div class="shadow"></div>
        <span class="stars">&#10038; &#10038;</span>
        <img class="ruru" src="{{ asset('assets/ruru/ruru-pusing.webp') }}" alt="">
        <span class="fallen f1"></span>
        <span class="fallen f2"></span>
        <span class="fallen f3"></span>
      </div>

      <div>
        <span class="code">Error 500 &middot; Kesalahan server</span>
        <h1>Waduh, rak Ruru <em>ambruk.</em></h1>
        <p class="lead">Ada yang salah di server kami, jadi halaman ini belum bisa dibuka. Coba muat ulang sebentar lagi, ya.</p>
        <p class="safe">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#2E5A45" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3 4 6v6c0 5 3.5 8 8 9 4.5-1 8-4 8-9V6z"/><path d="m9 12 2 2 4-4"/></svg>
          Tenang, ini cuma gangguan di website. Barang titipanmu tetap aman di gudang.
        </p>
        <div class="actions">
          <button class="btn btn-primary" type="button" id="retry">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12a9 9 0 1 1-3-6.7L21 8"/><path d="M21 3v5h-5"/></svg>
            Coba lagi
          </button>
          <a class="btn btn-outline" href="{{ url('/') }}">Ke beranda</a>
          <a class="btn btn-outline" id="wa" href="https://wa.me/6285121091134" target="_blank" rel="noopener">Lapor via WhatsApp</a>
        </div>
        <p class="ref">Kode laporan: <code id="ref">&ndash;</code><button type="button" id="copy">Salin</button></p>
      </div>
    </section>

    <section class="game" aria-labelledby="g-title">
      <div class="g-head">
        <div>
          <h2 id="g-title">Bantu Ruru tangkap barang yang jatuh</h2>
          <p>Geser Ruru pakai mouse, jari, atau tombol panah. Hindari barang retak.</p>
        </div>
        <div class="hud">
          <div><small>Skor</small><b id="score">0</b></div>
          <div class="lives"><small>Nyawa</small><b id="lives">&hearts;&hearts;&hearts;</b></div>
          <div><small>Rekor</small><b id="best">0</b></div>
        </div>
      </div>
      <div class="arena" id="arena" tabindex="0" aria-label="Area permainan. Gunakan panah kiri dan kanan untuk menggerakkan Ruru.">
        <canvas id="cv"></canvas>
        <div class="overlay" id="overlay">
          <strong id="ov-t">Rak ambruk, barang berjatuhan!</strong>
          <p id="ov-p">Tangkap kardus, koper, dan buku sebelum jatuh ke lantai. Barang retak bikin nyawa berkurang.</p>
          <button class="btn btn-primary" type="button" id="start">Mulai tangkap</button>
        </div>
      </div>
      <div class="legend">
        <span><i style="background:#C9A27A"></i>Kardus +1</span>
        <span><i style="background:#2E5A45"></i>Koper +2</span>
        <span><i style="background:#E8A677"></i>Buku +1</span>
        <span><i style="background:#A3321A"></i>Barang retak, hindari</span>
      </div>
    </section>
  </main>

  <p class="foot">&copy; {{ date('Y') }} RuangTitip &middot; <a href="mailto:ruangtitipmu@gmail.com">ruangtitipmu@gmail.com</a></p>
</div>

<script>
(function () {
  var $ = function (x) { return document.getElementById(x); };

  /* ---------- Tombol & kode laporan ---------- */
  $('retry').addEventListener('click', function () { location.reload(); });
  var d = new Date(), pad = function (n) { return String(n).padStart(2, '0'); };
  var ref = 'RT500-' + d.getFullYear() + pad(d.getMonth() + 1) + pad(d.getDate()) + '-' + pad(d.getHours()) + pad(d.getMinutes()) + pad(d.getSeconds());
  $('ref').textContent = ref;
  $('wa').href = 'https://wa.me/6285121091134?text=' + encodeURIComponent('Halo RuangTitip, saya dapat error 500 di ' + location.href + ' (kode: ' + ref + ').');
  $('copy').addEventListener('click', function () {
    var b = this;
    (navigator.clipboard ? navigator.clipboard.writeText(ref) : Promise.reject()).then(function () {
      b.textContent = 'Tersalin'; setTimeout(function () { b.textContent = 'Salin'; }, 1500);
    }, function () { b.textContent = 'Salin manual, ya'; });
  });

  /* ---------- Mini game ---------- */
  var arena = $('arena'), cv = $('cv'), ctx = cv.getContext('2d');
  var ruru = new Image(); ruru.src = document.querySelector('.scene .ruru').src;
  var W, H, dpr, px, targetX, items, score, lives, best = 0, running = false, last, spawnT, speedK, raf, keys = {};
  try { best = +localStorage.getItem('rt_catch_best') || 0; } catch (e) {}
  $('best').textContent = best;

  var TYPES = [
    { k: 'kardus', w: 46, h: 36, c: '#C9A27A', pts: 1, p: .42 },
    { k: 'koper',  w: 38, h: 48, c: '#2E5A45', pts: 2, p: .16 },
    { k: 'buku',   w: 40, h: 30, c: '#E8A677', pts: 1, p: .22 },
    { k: 'retak',  w: 44, h: 36, c: '#A3321A', pts: 0, p: .20 }
  ];
  var RW = 92, RH = 108;

  function size() {
    dpr = Math.min(2, window.devicePixelRatio || 1);
    W = arena.clientWidth; H = arena.clientHeight;
    cv.width = W * dpr; cv.height = H * dpr;
    ctx.setTransform(dpr, 0, 0, dpr, 0, 0);
    if (px === undefined) px = targetX = W / 2;
    draw();
  }
  window.addEventListener('resize', size);

  function pick() {
    var r = Math.random(), a = 0;
    for (var i = 0; i < TYPES.length; i++) { a += TYPES[i].p; if (r < a) return TYPES[i]; }
    return TYPES[0];
  }
  function start() {
    items = []; score = 0; lives = 3; speedK = 1; spawnT = 0; px = targetX = W / 2;
    $('score').textContent = 0; drawLives();
    $('overlay').hidden = true; running = true; last = performance.now();
    arena.focus(); cancelAnimationFrame(raf); raf = requestAnimationFrame(loop);
  }
  function drawLives() { $('lives').textContent = lives > 0 ? '♥'.repeat(lives) : '–'; }

  function loop(t) {
    var dt = Math.min(40, t - last) / 16.67; last = t;
    if (keys.ArrowLeft) targetX -= 9 * dt;
    if (keys.ArrowRight) targetX += 9 * dt;
    targetX = Math.max(RW / 2, Math.min(W - RW / 2, targetX));
    px += (targetX - px) * Math.min(1, .35 * dt);

    speedK = 1 + score * 0.04;
    spawnT -= dt;
    if (spawnT <= 0) {
      var ty = pick();
      items.push({ t: ty, x: 24 + Math.random() * (W - 48), y: -50, vy: (2.2 + Math.random() * 1.4) * speedK, rot: (Math.random() - .5) * .6, vr: (Math.random() - .5) * .05 });
      spawnT = Math.max(20, 62 - score * 1.2);
    }
    var catchY = H - 26 - RH * .62;
    for (var i = items.length - 1; i >= 0; i--) {
      var it = items[i];
      it.y += it.vy * dt; it.rot += it.vr * dt;
      if (it.y + it.t.h / 2 >= catchY && it.y - it.t.h / 2 <= catchY + 30 && Math.abs(it.x - px) < RW / 2 + it.t.w / 4) {
        items.splice(i, 1);
        if (it.t.k === 'retak') { lives--; drawLives(); flash('Aduh!', it.x, '#A3321A'); }
        else { score += it.t.pts; $('score').textContent = score; flash('+' + it.t.pts, it.x, '#2E5A45'); }
        continue;
      }
      if (it.y - it.t.h > H) {
        items.splice(i, 1);
        if (it.t.k !== 'retak') { lives--; drawLives(); flash('Jatuh!', it.x, '#9A4415'); }
      }
    }
    draw();
    if (lives <= 0) return over();
    raf = requestAnimationFrame(loop);
  }

  var pops = [];
  function flash(txt, x, c) { pops.push({ txt: txt, x: x, y: H - 150, a: 1, c: c }); }

  function drawItem(it) {
    var t = it.t, w = t.w, h = t.h;
    ctx.save(); ctx.translate(it.x, it.y); ctx.rotate(it.rot);
    ctx.lineWidth = 2; ctx.strokeStyle = '#1C1B18'; ctx.fillStyle = t.c;
    roundRect(-w / 2, -h / 2, w, h, 5); ctx.fill(); ctx.stroke();
    if (t.k === 'kardus') { ctx.fillStyle = '#B4531D'; ctx.fillRect(-5, -h / 2 + 1, 10, h - 2); }
    if (t.k === 'koper') { ctx.beginPath(); ctx.moveTo(-8, -h / 2); ctx.lineTo(-8, -h / 2 - 8); ctx.lineTo(8, -h / 2 - 8); ctx.lineTo(8, -h / 2); ctx.stroke(); ctx.strokeStyle = '#EAF1EC'; ctx.beginPath(); ctx.moveTo(-w / 4, -h / 2 + 8); ctx.lineTo(-w / 4, h / 2 - 8); ctx.moveTo(w / 4, -h / 2 + 8); ctx.lineTo(w / 4, h / 2 - 8); ctx.stroke(); }
    if (t.k === 'buku') { ctx.fillStyle = '#FFFDF8'; ctx.fillRect(-w / 2 + 6, -h / 2 + 5, w - 12, 5); ctx.fillRect(-w / 2 + 6, -h / 2 + 14, w - 20, 4); }
    if (t.k === 'retak') { ctx.strokeStyle = '#FFFDF8'; ctx.lineWidth = 2.5; ctx.beginPath(); ctx.moveTo(-6, -h / 2 + 3); ctx.lineTo(2, -4); ctx.lineTo(-4, 3); ctx.lineTo(6, h / 2 - 3); ctx.stroke(); }
    ctx.restore();
  }
  function roundRect(x, y, w, h, r) { ctx.beginPath(); ctx.moveTo(x + r, y); ctx.arcTo(x + w, y, x + w, y + h, r); ctx.arcTo(x + w, y + h, x, y + h, r); ctx.arcTo(x, y + h, x, y, r); ctx.arcTo(x, y, x + w, y, r); ctx.closePath(); }

  function draw() {
    ctx.clearRect(0, 0, W, H);
    ctx.strokeStyle = '#DDD5C4'; ctx.lineWidth = 1;
    for (var y = 40; y < H - 26; y += 40) { ctx.beginPath(); ctx.moveTo(0, y); ctx.lineTo(W, y); ctx.stroke(); }
    ctx.fillStyle = '#C9A27A'; ctx.fillRect(0, H - 22, W, 22);
    ctx.fillStyle = '#1C1B18'; ctx.fillRect(0, H - 24, W, 2);
    if (items) items.forEach(drawItem);
    if (ruru.complete && ruru.naturalWidth) {
      ctx.globalAlpha = .15; ctx.fillStyle = '#1C1B18'; ctx.beginPath(); ctx.ellipse(px, H - 24, RW * .5, 8, 0, 0, Math.PI * 2); ctx.fill(); ctx.globalAlpha = 1;
      ctx.drawImage(ruru, px - RW / 2, H - 22 - RH, RW, RH);
    }
    for (var i = pops.length - 1; i >= 0; i--) {
      var p = pops[i]; p.y -= 1.2; p.a -= .025;
      if (p.a <= 0) { pops.splice(i, 1); continue; }
      ctx.globalAlpha = p.a; ctx.fillStyle = p.c; ctx.font = '800 22px "Bricolage Grotesque", sans-serif'; ctx.textAlign = 'center';
      ctx.fillText(p.txt, p.x, p.y); ctx.globalAlpha = 1;
    }
  }

  function over() {
    running = false;
    var rec = score > best;
    if (rec) { best = score; $('best').textContent = best; try { localStorage.setItem('rt_catch_best', best); } catch (e) {} }
    $('ov-t').textContent = rec && score > 0 ? 'Rekor baru: ' + score + ' poin!' : score + ' poin terkumpul';
    $('ov-p').textContent = score >= 30 ? 'Mantap, kamu layak jadi kepala gudang Ruru.' : score >= 12 ? 'Lumayan! Sambil nunggu, coba kalahkan skormu.' : 'Barangnya kabur semua. Coba lagi, pasti bisa.';
    $('start').textContent = 'Main lagi';
    $('overlay').hidden = false; $('start').focus();
  }

  /* Kontrol */
  function setX(e) { var r = arena.getBoundingClientRect(); targetX = e.clientX - r.left; }
  arena.addEventListener('pointermove', function (e) { if (running) setX(e); });
  arena.addEventListener('pointerdown', function (e) { if (running) setX(e); });
  arena.addEventListener('keydown', function (e) {
    if (e.key === 'ArrowLeft' || e.key === 'ArrowRight') { keys[e.key] = true; e.preventDefault(); }
    if (!running && (e.key === 'Enter' || e.key === ' ')) { e.preventDefault(); start(); }
  });
  arena.addEventListener('keyup', function (e) { keys[e.key] = false; });
  $('start').addEventListener('click', function (e) { e.stopPropagation(); start(); });
  ruru.onload = size;
  size();
})();
</script>
</body>
</html>
