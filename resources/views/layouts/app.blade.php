<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'RUTIP - Ruang Titip')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { background-color: #0c0618; color: white; }
    </style>
</head>
<body class="antialiased min-h-screen flex flex-col">

    {{-- NAVBAR (Hasil Konversi Figma) --}}
    <header class="fixed top-0 left-0 right-0 z-50 bg-white" style="height: 80px; border-bottom: 1px solid rgba(0,0,0,0.07); box-shadow: 0 1px 12px rgba(0,0,0,0.06);">
        <div class="h-full flex items-center justify-between max-w-7xl mx-auto px-6">
            
            {{-- Logo --}}
            <a href="/" class="flex items-center gap-2.5 shrink-0">
                <div class="w-9 h-9 rounded-xl flex items-center justify-center rutip-gradient-bg">
                    <x-lucide-package class="w-5 h-5 text-white" />
                </div>
                <span class="text-xl font-extrabold" style="color: #1a0533">RUTIP</span>
            </a>

            {{-- Center Nav (Desktop) --}}
            <nav class="hidden lg:flex items-center gap-4">
                <a href="/" class="text-sm font-medium" style="color: #7c3aed">Beranda</a>
                <a href="/titip" class="text-sm font-medium" style="color: #4b5563">Ruang Titip</a>
                <a href="/toko-packing" class="text-sm font-medium" style="color: #4b5563">Toko Packing</a>
                <a href="/toko-preloved" class="text-sm font-medium" style="color: #4b5563">Toko Preloved</a>
            </nav>

            {{-- Right Actions (Login Button) --}}
            <div class="flex items-center gap-4">
                <a href="/login" class="hidden lg:flex px-5 py-2 rounded-xl text-sm font-bold text-white transition-all hover:scale-105 rutip-gradient-bg">
                    Masuk
                </a>
                {{-- Mobile Menu Button --}}
                <button class="lg:hidden w-10 h-10 rounded-xl flex items-center justify-center text-gray-500 bg-gray-100">
                    <x-lucide-menu class="w-5 h-5" />
                </button>
            </div>
        </div>
    </header>

    {{-- TEMPAT KONTEN HALAMAN BERUBAH-UBAH --}}
    <main class="flex-grow pt-[80px]">
        @yield('content')
    </main>

    {{-- FOOTER (Hasil Konversi Figma) --}}
    <footer style="background: #0f0720; border-top: 1px solid rgba(139,92,246,0.15)">
        <div class="max-w-7xl mx-auto px-6 py-12">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-10 pb-12" style="border-bottom: 1px solid rgba(255,255,255,0.07)">
                
                {{-- Brand Column --}}
                <div>
                    <div class="flex items-center gap-2.5 mb-4">
                        <div class="w-9 h-9 rounded-xl flex items-center justify-center rutip-gradient-bg">
                            <x-lucide-package class="w-5 h-5 text-white" />
                        </div>
                        <span class="text-xl font-extrabold text-white">RUTIP</span>
                    </div>
                    <p class="text-sm leading-relaxed mb-6" style="color: rgba(255,255,255,0.4)">
                        Solusi penitipan barang terpercaya untuk mahasiswa Brawijaya. Aman, hemat, dan mudah.
                    </p>
                </div>

                {{-- Links --}}
                <div>
                    <h5 class="text-xs font-bold uppercase tracking-widest mb-5" style="color: rgba(255,255,255,0.35)">Layanan</h5>
                    <ul class="space-y-3 text-sm" style="color: rgba(255,255,255,0.5)">
                        <li><a href="#" class="hover:text-violet-400">Ruang Titip</a></li>
                        <li><a href="#" class="hover:text-violet-400">Toko Packing</a></li>
                        <li><a href="#" class="hover:text-violet-400">Toko Preloved</a></li>
                    </ul>
                </div>

                <div>
                    <h5 class="text-xs font-bold uppercase tracking-widest mb-5" style="color: rgba(255,255,255,0.35)">Perusahaan</h5>
                    <ul class="space-y-3 text-sm" style="color: rgba(255,255,255,0.5)">
                        <li><a href="#" class="hover:text-violet-400">Tentang Kami</a></li>
                        <li><a href="#" class="hover:text-violet-400">Syarat & Ketentuan</a></li>
                    </ul>
                </div>

                <div>
                    <h5 class="text-xs font-bold uppercase tracking-widest mb-5" style="color: rgba(255,255,255,0.35)">Bantuan</h5>
                    <ul class="space-y-3 text-sm" style="color: rgba(255,255,255,0.5)">
                        <li><a href="#" class="hover:text-violet-400">FAQ</a></li>
                        <li><a href="#" class="hover:text-violet-400">Hubungi Support</a></li>
                    </ul>
                </div>
            </div>
            <div class="py-5 text-xs text-center" style="color: rgba(255,255,255,0.22)">
                © 2026 RuTip. All rights reserved.
            </div>
        </div>
    </footer>

</body>
</html>