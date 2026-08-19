<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kebijakan Privasi - RUTIP</title>
    <meta name="description" content="Kebijakan Privasi layanan RUTIP - Ruang Titip.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root { --font-display:'Plus Jakarta Sans',sans-serif; --font-body:'Inter',sans-serif; }
        body { font-family: var(--font-body); background:#0c0618; }
        .font-display { font-family: var(--font-display); }
        @keyframes float { 0%,100%{transform:translateY(0)} 50%{transform:translateY(-10px)} }
        @keyframes pulse-ring { 0%{transform:scale(1);opacity:.4} 100%{transform:scale(1.6);opacity:0} }
        @keyframes shimmer { 0%{background-position:-400px 0} 100%{background-position:400px 0} }
        .float-icon { animation: float 3s ease-in-out infinite; }
        .pulse-ring {
            position:absolute; inset:-16px; border-radius:50%;
            border:1.5px solid rgba(52,211,153,0.3);
            animation: pulse-ring 2.2s ease-out infinite;
        }
        .pulse-ring-2 {
            position:absolute; inset:-32px; border-radius:50%;
            border:1px solid rgba(52,211,153,0.15);
            animation: pulse-ring 2.2s ease-out infinite .7s;
        }
        .progress-bar {
            height:4px; border-radius:4px;
            background: linear-gradient(90deg,rgba(255,255,255,0.07) 25%,rgba(255,255,255,0.15) 50%,rgba(255,255,255,0.07) 75%);
            background-size:800px 100%;
            animation: shimmer 1.8s ease-in-out infinite;
        }
    </style>
</head>
<body>
@include('landing.partials.navbar')

<main style="min-height:100vh;display:flex;align-items:center;justify-content:center;padding:120px 24px 80px;position:relative;overflow:hidden;">
    {{-- Background glows --}}
    <div style="position:absolute;top:-80px;right:-80px;width:500px;height:500px;border-radius:50%;background:radial-gradient(circle,rgba(52,211,153,0.1),transparent 70%);filter:blur(60px);pointer-events:none;"></div>
    <div style="position:absolute;bottom:-60px;left:-60px;width:400px;height:400px;border-radius:50%;background:radial-gradient(circle,rgba(124,58,237,0.1),transparent 70%);filter:blur(60px);pointer-events:none;"></div>

    <div style="position:relative;z-index:10;text-align:center;max-width:520px;width:100%;">

        {{-- Icon --}}
        <div style="display:flex;justify-content:center;margin-bottom:36px;">
            <div style="position:relative;width:88px;height:88px;">
                <div class="pulse-ring"></div>
                <div class="pulse-ring-2"></div>
                <div class="float-icon" style="width:88px;height:88px;border-radius:24px;display:flex;align-items:center;justify-content:center;background:linear-gradient(135deg,rgba(52,211,153,0.15),rgba(16,185,129,0.1));border:1.5px solid rgba(52,211,153,0.3);box-shadow:0 16px 48px rgba(52,211,153,0.15);">
                    <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#34d399" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                        <polyline points="9 12 11 14 15 10"/>
                    </svg>
                </div>
            </div>
        </div>

        {{-- Badge --}}
        <div style="display:inline-flex;align-items:center;gap:6px;padding:5px 14px;border-radius:999px;font-size:11px;font-weight:700;background:rgba(251,191,36,0.12);border:1px solid rgba(251,191,36,0.3);color:#fcd34d;margin-bottom:20px;">
            <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
            Segera Hadir
        </div>

        {{-- Title --}}
        <h1 class="font-display" style="font-size:clamp(1.8rem,4vw,2.4rem);font-weight:800;color:#fff;margin:0 0 14px;line-height:1.2;">
            Kebijakan Privasi
        </h1>

        {{-- Subtitle --}}
        <p style="font-size:15px;color:rgba(255,255,255,0.48);line-height:1.75;margin:0 0 32px;">
            Privasi kamu adalah prioritas kami. Kami sedang menyusun dokumen ini agar transparan dan jelas tentang bagaimana data personalmu dikelola di platform RUTIP.
        </p>

        {{-- Progress bars (decorative) --}}
        <div style="display:flex;flex-direction:column;gap:10px;margin-bottom:36px;text-align:left;">
            @foreach([['label'=>'Pengumpulan & Penggunaan Data','w'=>'80%'],['label'=>'Keamanan & Perlindungan Data','w'=>'65%'],['label'=>'Hak Pengguna & Kontak DPO','w'=>'45%']] as $item)
            <div style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.08);border-radius:12px;padding:12px 14px;display:flex;align-items:center;gap:12px;">
                <div style="flex:1;">
                    <div style="font-size:12px;color:rgba(255,255,255,0.5);margin-bottom:6px;">{{ $item['label'] }}</div>
                    <div style="height:4px;background:rgba(255,255,255,0.07);border-radius:4px;overflow:hidden;">
                        <div class="progress-bar" style="width:{{ $item['w'] }};height:4px;border-radius:4px;"></div>
                    </div>
                </div>
                <div style="font-size:11px;font-weight:600;color:#fcd34d;">{{ $item['w'] }}</div>
            </div>
            @endforeach
        </div>

        {{-- Actions --}}
        <div style="display:flex;flex-wrap:wrap;gap:12px;justify-content:center;">
            <a href="{{ url()->previous() !== url()->current() ? url()->previous() : route('home') }}"
               style="display:inline-flex;align-items:center;gap:8px;padding:12px 24px;border-radius:14px;font-size:13px;font-weight:700;background:linear-gradient(135deg,#7c3aed,#6366f1);color:#fff;text-decoration:none;box-shadow:0 6px 20px rgba(124,58,237,0.35);transition:opacity .2s,transform .2s;"
               onmouseover="this.style.opacity='.88';this.style.transform='translateY(-1px)'"
               onmouseout="this.style.opacity='1';this.style.transform=''">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 12H5M12 19l-7-7 7-7"/></svg>
                Kembali
            </a>
            <a href="{{ route('home') }}"
               style="display:inline-flex;align-items:center;gap:8px;padding:12px 24px;border-radius:14px;font-size:13px;font-weight:700;color:rgba(255,255,255,0.6);text-decoration:none;border:1.5px solid rgba(255,255,255,0.12);transition:border-color .2s,color .2s;"
               onmouseover="this.style.borderColor='rgba(124,58,237,0.5)';this.style.color='#a78bfa'"
               onmouseout="this.style.borderColor='rgba(255,255,255,0.12)';this.style.color='rgba(255,255,255,0.6)'">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 9.5L12 4l9 5.5V20H3V9.5z"/></svg>
                Ke Beranda
            </a>
        </div>

        {{-- Footer note --}}
        <p style="margin-top:28px;font-size:11px;color:rgba(255,255,255,0.2);">
            Pertanyaan soal data? Hubungi kami di <span style="color:rgba(255,255,255,0.35);">ruangtitipmu@gmail.com</span>
        </p>
    </div>
</main>

@include('layouts.footer')
</body>
</html>
