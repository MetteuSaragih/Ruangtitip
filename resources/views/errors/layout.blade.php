{{--
    Kerangka bersama halaman galat dan perawatan RuangTitip.
    Sengaja mandiri: CSS inline, tanpa Vite, sesi, atau database, supaya tetap tampil saat aplikasi bermasalah.

    Bagian yang diisi tiap halaman:
      title     judul tab
      badge     label kecil di atas judul (mis. "Error 404")
      pose      nama gambar Ruru di public/assets/ruru/ruru-{pose}.webp (bawaan: pusing)
      heading   judul besar
      lead      kalimat penjelasan
      actions   tombol (HTML)
      extra     isi tambahan di bawah tombol (HTML, opsional)
      report    isi apa saja untuk menampilkan kode laporan (opsional)
      code_num  nomor galat untuk kode laporan (mis. 500)
--}}
@php
    $pose = trim($__env->yieldContent('pose')) ?: 'pusing';
    $wa = 'https://wa.me/6285121091134';
    $showReport = trim($__env->yieldContent('report')) !== '';
    $reportCode = 'RT' . (trim($__env->yieldContent('code_num')) ?: '000') . '-' . now()->format('Ymd-His');
    $waText = $wa . '?text=' . rawurlencode('Halo RuangTitip, saya menemui kendala di website.' . ($showReport ? ' Kode laporan: ' . $reportCode : ''));
@endphp
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="robots" content="noindex">
    <meta name="color-scheme" content="light">
    <title>@yield('title', 'Terjadi kendala') &middot; RuangTitip</title>
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('assets/logo/favicon-32.png') }}">
    <link rel="icon" type="image/png" sizes="48x48" href="{{ asset('assets/logo/favicon-48.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('assets/logo/apple-touch-icon.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap">
    @yield('head')
    <style>
        :root {
            --bg: #F3FAF9;
            --surface: #FFFFFF;
            --aqua: #D7F2EE;
            --ink: #0C2B2A;
            --muted: #41605D;
            --teal: #0F766E;
            --teal-dark: #0B5D57;
            --accent: #FFC93C;
            --accent-hover: #F2B705;
            --line: #CFE5E1;
            --font: 'Plus Jakarta Sans', ui-sans-serif, system-ui, -apple-system, 'Segoe UI', Roboto, sans-serif;
        }

        *, *::before, *::after { box-sizing: border-box; }
        html { -webkit-text-size-adjust: 100%; }
        body {
            margin: 0;
            min-height: 100vh;
            min-height: 100svh;
            display: grid;
            grid-template-rows: auto 1fr auto;
            font-family: var(--font);
            font-size: 16px;
            line-height: 1.6;
            color: var(--ink);
            background: var(--bg);
            -webkit-font-smoothing: antialiased;
            overflow-x: hidden;
            position: relative;
        }

        /* Hiasan latar yang tenang: dua lingkaran lembut, tidak bergerak. */
        body::before, body::after {
            content: '';
            position: fixed;
            z-index: 0;
            border-radius: 50%;
            pointer-events: none;
        }
        body::before { width: 520px; height: 520px; top: -200px; right: -160px; background: radial-gradient(closest-side, rgba(45, 212, 191, .22), transparent); }
        body::after { width: 460px; height: 460px; bottom: -220px; left: -140px; background: radial-gradient(closest-side, rgba(255, 201, 60, .18), transparent); }

        a { color: var(--teal); }
        :focus-visible { outline: 3px solid var(--teal); outline-offset: 3px; border-radius: 10px; }

        .top, main, .foot { position: relative; z-index: 1; }

        .top {
            display: flex;
            justify-content: center;
            padding: 28px 20px 0;
        }
        .top a { display: inline-flex; }
        .top img { height: 44px; width: auto; display: block; }

        main {
            display: grid;
            place-items: center;
            padding: 24px 20px 40px;
        }

        .wrap {
            width: 100%;
            max-width: 560px;
            display: grid;
            justify-items: center;
            text-align: center;
        }

        /* Maskot */
        .mascot {
            position: relative;
            display: grid;
            place-items: center;
            width: min(260px, 62vw);
            margin-bottom: 8px;
        }
        .mascot img {
            position: relative;
            z-index: 1;
            width: 100%;
            height: auto;
            animation: float 5.5s ease-in-out infinite;
        }
        .mascot::after {
            content: '';
            position: absolute;
            bottom: 2%;
            left: 50%;
            width: 58%;
            height: 14px;
            margin-left: -29%;
            border-radius: 50%;
            background: rgba(12, 43, 42, .12);
            filter: blur(6px);
            animation: shadow 5.5s ease-in-out infinite;
        }
        @keyframes float { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-8px); } }
        @keyframes shadow { 0%, 100% { transform: scaleX(1); opacity: 1; } 50% { transform: scaleX(.88); opacity: .75; } }

        .badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin: 8px 0 14px;
            padding: 6px 14px;
            border-radius: 999px;
            background: var(--aqua);
            color: var(--teal-dark);
            font-size: 13px;
            font-weight: 700;
        }
        .badge::before { content: ''; width: 8px; height: 8px; border-radius: 50%; background: var(--teal); }

        h1 {
            margin: 0 0 12px;
            font-size: clamp(28px, 6vw, 40px);
            line-height: 1.15;
            font-weight: 800;
            letter-spacing: -0.03em;
            text-wrap: balance;
        }
        h1 em { font-style: normal; color: var(--teal); }

        .lead {
            margin: 0;
            max-width: 46ch;
            font-size: 16.5px;
            color: var(--muted);
            text-wrap: pretty;
        }

        .acts {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 12px;
            margin-top: 28px;
        }
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            min-height: 48px;
            padding: 0 24px;
            border-radius: 999px;
            border: 1.5px solid transparent;
            font: inherit;
            font-weight: 700;
            font-size: 15px;
            text-decoration: none;
            cursor: pointer;
            transition: background .15s, border-color .15s, transform .1s;
        }
        .btn:active { transform: translateY(1px); }
        .btn-primary { background: var(--accent); color: var(--ink); }
        .btn-primary:hover { background: var(--accent-hover); }
        .btn-ghost { background: var(--surface); color: var(--ink); border-color: var(--line); }
        .btn-ghost:hover { border-color: var(--teal); color: var(--teal-dark); }

        .box {
            width: 100%;
            margin-top: 28px;
            padding: 16px 18px;
            border: 1px solid var(--line);
            border-radius: 16px;
            background: var(--surface);
            text-align: left;
            font-size: 14.5px;
            color: var(--muted);
        }
        .box b { color: var(--ink); }
        .box p { margin: 0; }
        .box p + p { margin-top: 6px; }

        .links {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 8px 18px;
            margin-top: 24px;
            font-size: 14.5px;
        }
        .links span { color: var(--muted); }
        .links a { font-weight: 700; text-underline-offset: 3px; }

        .report {
            display: inline-flex;
            align-items: center;
            flex-wrap: wrap;
            justify-content: center;
            gap: 8px;
            margin-top: 22px;
            font-size: 13.5px;
            color: var(--muted);
        }
        .report code {
            padding: 4px 10px;
            border: 1px solid var(--line);
            border-radius: 10px;
            background: var(--surface);
            color: var(--ink);
            font-family: ui-monospace, SFMono-Regular, Menlo, Consolas, monospace;
            font-size: 13px;
        }
        .report button {
            border: 0;
            background: none;
            color: var(--teal);
            font: inherit;
            font-weight: 700;
            text-decoration: underline;
            text-underline-offset: 3px;
            cursor: pointer;
            padding: 4px;
        }

        .foot {
            padding: 0 20px calc(24px + env(safe-area-inset-bottom));
            text-align: center;
            font-size: 13px;
            color: var(--muted);
        }
        .foot a { font-weight: 700; }

        @media (max-width: 560px) {
            .top { padding-top: 22px; }
            .top img { height: 38px; }
            main { padding-top: 16px; }
            .acts { flex-direction: column; align-items: stretch; margin-top: 24px; }
            .btn { width: 100%; }
            .box { padding: 14px 16px; }
        }

        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after { animation: none !important; transition: none !important; }
        }
    </style>
    @yield('style')
</head>
<body>
    <header class="top">
        <a href="{{ url('/') }}" aria-label="RuangTitip, ke beranda"><img src="{{ asset('assets/logo/logo-horizontal.png') }}" alt="RuTip" width="110" height="43"></a>
    </header>

    <main id="main">
        <div class="wrap">
            <div class="mascot" aria-hidden="true">
                <img src="{{ asset('assets/ruru/ruru-' . $pose . '.webp') }}" alt="" width="260" height="260">
            </div>

            <span class="badge">@yield('badge', 'Terjadi kendala')</span>
            <h1>@yield('heading')</h1>
            <p class="lead">@yield('lead')</p>

            <div class="acts">
                @yield('actions')
            </div>

            @yield('extra')

            @if ($showReport)
                <div class="report">
                    <span>Kode laporan:</span>
                    <code id="reportCode">{{ $reportCode }}</code>
                    <button type="button" id="copyReport">Salin</button>
                </div>
            @endif
        </div>
    </main>

    <footer class="foot">
        Butuh bantuan? <a href="{{ $waText }}" target="_blank" rel="noopener">Chat tim RuangTitip</a>
    </footer>

    <script>
        (function () {
            var back = document.getElementById('goBack');
            if (back) {
                if (window.history.length < 2) { back.style.display = 'none'; }
                back.addEventListener('click', function () { window.history.back(); });
            }
            var reload = document.getElementById('reloadPage');
            if (reload) { reload.addEventListener('click', function () { window.location.reload(); }); }

            var copy = document.getElementById('copyReport');
            var code = document.getElementById('reportCode');
            if (copy && code) {
                copy.addEventListener('click', function () {
                    var done = function () { copy.textContent = 'Tersalin'; setTimeout(function () { copy.textContent = 'Salin'; }, 2000); };
                    if (navigator.clipboard && navigator.clipboard.writeText) {
                        navigator.clipboard.writeText(code.textContent).then(done, function () {});
                    }
                });
            }
        })();
    </script>
    @yield('script')
</body>
</html>
