<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RUTIP - Penitipan Barang Mahasiswa</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body {
            background-color: #0c0618; /* Warna dasar gelap RUTIP */
            color: white;
            font-family: 'Inter', sans-serif;
        }
        /* Background Grid yang khas dari Figma */
        .bg-grid {
            background-image: 
                linear-gradient(rgba(255, 255, 255, 0.04) 1px, transparent 1px), 
                linear-gradient(90deg, rgba(255, 255, 255, 0.04) 1px, transparent 1px);
            background-size: 64px 64px;
        }
    </style>
</head>
<body class="antialiased min-h-screen flex flex-col relative overflow-x-hidden">

    {{-- Background Grid & Glow (Bawaan Figma) --}}
    <div class="absolute inset-0 bg-grid pointer-events-none z-0"></div>
    <div class="absolute top-0 right-0 w-[600px] h-[600px] rounded-full opacity-20 blur-[100px] pointer-events-none z-0" style="background: radial-gradient(circle, #7c3aed, transparent 70%);"></div>
    <div class="absolute bottom-0 left-0 w-[500px] h-[500px] rounded-full opacity-10 blur-[100px] pointer-events-none z-0" style="background: radial-gradient(circle, #6366f1, transparent 70%);"></div>

    {{-- NAVBAR LANDING PAGE (Sesuai Gambar Figma) --}}
    <header class="relative z-50 w-full pt-6 px-6 lg:px-16">
        <div class="flex items-center justify-between">
            
            {{-- Logo --}}
            <a href="/" class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center rutip-gradient-bg">
                    <x-lucide-package class="w-5 h-5 text-white" />
                </div>
                <span class="text-2xl font-extrabold tracking-wide text-white">RUTIP</span>
            </a>

            {{-- Menu Tengah --}}
            <nav class="hidden lg:flex items-center gap-8">
                <a href="#" class="text-sm font-semibold text-gray-300 hover:text-white transition-colors">Layanan</a>
                <a href="#" class="text-sm font-semibold text-gray-300 hover:text-white transition-colors">Cara Kerja</a>
                <a href="#" class="text-sm font-semibold text-gray-300 hover:text-white transition-colors">Testimoni</a>
                <a href="#" class="text-sm font-semibold text-gray-300 hover:text-white transition-colors">FAQ</a>
                <a href="#" class="text-sm font-semibold text-gray-300 hover:text-white transition-colors">Tentang Kami</a>
            </nav>

            {{-- Tombol Masuk --}}
            <div>
                <a href="/login" class="px-6 py-2.5 rounded-xl text-sm font-bold text-white transition-all hover:bg-white/10" style="border: 1px solid rgba(255,255,255,0.2);">
                    Masuk
                </a>
            </div>
        </div>
    </header>

    {{-- HERO SECTION (Hasil Recreate dari Gambar Figma) --}}
    <main class="relative z-10 flex-grow flex flex-col justify-center px-6 lg:px-16 pt-20 pb-32">
        <div class="max-w-4xl">
            
            {{-- Badge Ungu --}}
            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full mb-8" style="background: rgba(124, 58, 237, 0.15); border: 1px solid rgba(124, 58, 237, 0.3);">
                <div class="w-1.5 h-1.5 rounded-full" style="background: #a78bfa;"></div>
                <span class="text-xs font-bold" style="color: #c4b5fd;">Layanan penitipan barang #1 di Malang</span>
            </div>

            {{-- Headline --}}
            <h1 class="text-6xl lg:text-[5.5rem] font-extrabold text-white leading-[1.1] mb-6 tracking-tight">
                Pergi Magang.<br />
                <span style="color: #a78bfa;">Bukan|</span>
            </h1>

            {{-- Subtitle --}}
            <p class="text-lg lg:text-xl max-w-2xl mb-12 leading-relaxed" style="color: rgba(255,255,255,0.5);">
                Layanan penitipan barang untuk mahasiswa — aman, terjangkau, dan ada antar-jemput langsung ke kos kamu.
            </p>

            {{-- Buttons --}}
            <div class="flex flex-col sm:flex-row items-center gap-4 mb-16">
                <a href="/titip" class="w-full sm:w-auto flex items-center justify-center gap-2 px-8 py-4 rounded-2xl font-bold text-white transition-all hover:scale-105 rutip-gradient-bg" style="box-shadow: 0 8px 24px rgba(124, 58, 237, 0.4);">
                    Titip Sekarang <x-lucide-arrow-right class="w-5 h-5" />
                </a>
                <button class="w-full sm:w-auto flex items-center justify-center gap-2 px-8 py-4 rounded-2xl font-bold text-white transition-all hover:bg-white/5" style="border: 1px solid rgba(255, 255, 255, 0.15);">
                    <x-lucide-play class="w-5 h-5" /> Lihat Cara Kerja
                </button>
            </div>

            {{-- Trust Indicators (Avatar & Stars) --}}
            <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4">
                <div class="flex -space-x-3">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center text-[10px] font-bold text-white border-2 border-[#0c0618]" style="background: #7c3aed;">MR</div>
                    <div class="w-10 h-10 rounded-full flex items-center justify-center text-[10px] font-bold text-white border-2 border-[#0c0618]" style="background: #6366f1;">SA</div>
                    <div class="w-10 h-10 rounded-full flex items-center justify-center text-[10px] font-bold text-white border-2 border-[#0c0618]" style="background: #8b5cf6;">DK</div>
                    <div class="w-10 h-10 rounded-full flex items-center justify-center text-[10px] font-bold text-white border-2 border-[#0c0618]" style="background: #a855f7;">LF</div>
                </div>
                <div class="flex flex-col">
                    <div class="flex gap-1 mb-1">
                        <x-lucide-star class="w-4 h-4 text-yellow-400 fill-yellow-400" />
                        <x-lucide-star class="w-4 h-4 text-yellow-400 fill-yellow-400" />
                        <x-lucide-star class="w-4 h-4 text-yellow-400 fill-yellow-400" />
                        <x-lucide-star class="w-4 h-4 text-yellow-400 fill-yellow-400" />
                        <x-lucide-star class="w-4 h-4 text-yellow-400 fill-yellow-400" />
                    </div>
                    <p class="text-sm" style="color: rgba(255,255,255,0.5);">Dipercaya <span class="text-white font-bold">150+</span> mahasiswa Brawijaya</p>
                </div>
            </div>

        </div>
    </main>

</body>
</html>