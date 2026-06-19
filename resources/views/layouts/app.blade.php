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

    @include('layouts.footer')

</body>
</html>
