<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Masuk') — RUTIP</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root { --font-display:'Plus Jakarta Sans',sans-serif; --font-body:'Inter',sans-serif; }
        body { font-family: var(--font-body); }
        .font-display { font-family: var(--font-display); }
    </style>
</head>
<body class="min-h-screen" style="background:#0c0618;">

    <div class="min-h-screen flex overflow-hidden">

        {{-- ─── LEFT PANEL (branding + ilustrasi) ─── --}}
        <div class="hidden lg:flex" style="width:55%;">
            <div class="relative flex flex-col justify-between w-full p-14 overflow-hidden"
                 style="background:linear-gradient(145deg,#1a0533 0%,#130829 40%,#0f0720 100%);">

                {{-- Glows --}}
                <div class="absolute inset-0 pointer-events-none">
                    <div class="absolute -top-24 -left-24 w-[480px] h-[480px] rounded-full opacity-35 blur-3xl"
                         style="background:radial-gradient(circle,#5b21b6,transparent 70%);"></div>
                    <div class="absolute bottom-0 right-0 w-[360px] h-[360px] rounded-full opacity-25 blur-3xl"
                         style="background:radial-gradient(circle,#6d28d9,transparent 70%);"></div>
                    <div class="absolute inset-0 opacity-[0.03]"
                         style="background-image:linear-gradient(rgba(255,255,255,1) 1px,transparent 1px),linear-gradient(90deg,rgba(255,255,255,1) 1px,transparent 1px);background-size:48px 48px;"></div>
                </div>

                {{-- Logo --}}
                <a href="{{ route('home') }}" class="relative z-10 flex items-center w-fit">
                    <img src="{{ asset('images/logo-rutip-putih.png') }}" alt="RUTIP" class="h-14 w-auto">
                </a>

                {{-- Ilustrasi + tagline --}}
                <div class="relative z-10 flex flex-col items-center gap-10">
                    <svg viewBox="0 0 360 260" class="w-full max-w-md" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <ellipse cx="180" cy="242" rx="150" ry="12" fill="rgba(124,58,237,0.1)"/>
                        <rect x="32" y="60" width="110" height="170" rx="6" fill="#1e0d3c" stroke="rgba(139,92,246,0.3)" stroke-width="1.5"/>
                        <rect x="32" y="60" width="110" height="12" rx="6" fill="#2d1260" stroke="rgba(139,92,246,0.3)" stroke-width="1.5"/>
                        <rect x="42" y="78" width="32" height="44" rx="4" fill="#3b1578" stroke="rgba(167,139,250,0.4)" stroke-width="1.5"/>
                        <rect x="80" y="84" width="24" height="38" rx="4" fill="#1e1b4b" stroke="rgba(99,102,241,0.4)" stroke-width="1.5"/>
                        <rect x="110" y="88" width="22" height="34" rx="4" fill="#2e0d5c" stroke="rgba(139,92,246,0.3)" stroke-width="1.5"/>
                        <rect x="42" y="148" width="26" height="34" rx="4" fill="#1e1b4b" stroke="rgba(99,102,241,0.4)" stroke-width="1.5"/>
                        <rect x="74" y="152" width="32" height="30" rx="4" fill="#3b1578" stroke="rgba(167,139,250,0.35)" stroke-width="1.5"/>
                        <rect x="150" y="100" width="120" height="130" rx="8" fill="#3b1578" stroke="rgba(167,139,250,0.5)" stroke-width="2"/>
                        <rect x="150" y="100" width="120" height="34" rx="8" fill="#4c1d95" stroke="rgba(167,139,250,0.5)" stroke-width="2"/>
                        <rect x="198" y="92" width="24" height="18" rx="3" fill="#fbbf24" opacity="0.95"/>
                        <rect x="176" y="168" width="68" height="22" rx="4" fill="rgba(167,139,250,0.12)" stroke="rgba(167,139,250,0.3)" stroke-width="1"/>
                        <text x="210" y="183" text-anchor="middle" fill="#a78bfa" font-size="9" font-weight="700" letter-spacing="1">RUTIP</text>
                        <ellipse cx="308" cy="154" rx="28" ry="7" fill="#fbbf24" stroke="rgba(251,191,36,0.6)" stroke-width="1.5"/>
                        <ellipse cx="308" cy="165" rx="28" ry="7" fill="#fbbf24" opacity="0.8"/>
                        <ellipse cx="308" cy="176" rx="28" ry="7" fill="#fbbf24" opacity="0.7"/>
                        <rect x="280" y="176" width="56" height="35" fill="#f59e0b" opacity="0.45"/>
                        <text x="308" y="158" text-anchor="middle" fill="rgba(120,80,0,0.7)" font-size="8" font-weight="800">Rp</text>
                        <circle cx="300" cy="110" r="26" fill="rgba(5,150,105,0.12)" stroke="rgba(52,211,153,0.28)" stroke-width="1.5"/>
                        <path d="M300 96 C300 96 289 101 289 110 C289 118 294 123 300 126 C306 123 311 118 311 110 C311 101 300 96 300 96Z" fill="rgba(52,211,153,0.25)" stroke="#34d399" stroke-width="1.5"/>
                        <path d="M295 110 L298.5 114 L306 106" stroke="#34d399" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M278 88 L278 72 M272 79 L278 72 L284 79" stroke="#34d399" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" opacity="0.7"/>
                    </svg>

                    <h2 class="text-4xl font-extrabold text-white font-display leading-tight text-center">
                        Titip Barangmu,<br>
                        <span style="background:linear-gradient(90deg,#fbbf24,#a78bfa);-webkit-background-clip:text;-webkit-text-fill-color:transparent;">
                            Simpan Uangmu
                        </span>
                    </h2>
                </div>
            </div>
        </div>

        {{-- Divider --}}
        <div class="hidden lg:block w-px self-stretch my-10 shrink-0" style="background:rgba(139,92,246,0.15);"></div>

        {{-- ─── RIGHT PANEL (form) ─── --}}
        <div class="flex-1 flex flex-col justify-center items-center px-6 py-12 lg:px-12 relative overflow-hidden">
            {{-- Mobile logo --}}
            <a href="{{ route('home') }}" class="lg:hidden flex items-center mb-10 self-start">
                <img src="{{ asset('images/logo-rutip-putih.png') }}" alt="RUTIP" class="h-11 w-auto">
            </a>

            <div class="w-full" style="max-width:400px;">
                @yield('content')
            </div>
        </div>
    </div>

</body>
</html>
