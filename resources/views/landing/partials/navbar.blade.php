{{-- ==========================================================================
     Navbar
     Fixed top nav with scroll-based blur transition (handled in app.js)
     and a mobile menu toggle.
     ========================================================================== --}}
<nav id="navbar" class="fixed top-0 left-0 right-0 z-50 transition-all duration-300"
     style="background: transparent; backdrop-filter: none; border-bottom: 1px solid transparent; box-shadow: none;">
    <div class="max-w-6xl mx-auto px-6 h-16 flex items-center justify-between">
        {{-- Logo --}}
        <a href="{{ url('/') }}" class="flex items-center">
            <img src="{{ asset('images/logo-rutip-putih.png') }}" alt="RUTIP" class="h-12 w-auto">
        </a>

        {{-- Desktop nav --}}
        <div class="hidden md:flex items-center gap-8">
            <a href="#layanan" class="text-sm font-medium transition-colors hover:text-violet-300" style="color: rgba(255,255,255,0.65);">Layanan</a>
            <a href="#cara-kerja" class="text-sm font-medium transition-colors hover:text-violet-300" style="color: rgba(255,255,255,0.65);">Cara Kerja</a>
            <a href="#testimoni" class="text-sm font-medium transition-colors hover:text-violet-300" style="color: rgba(255,255,255,0.65);">Testimoni</a>
            <a href="#faq" class="text-sm font-medium transition-colors hover:text-violet-300" style="color: rgba(255,255,255,0.65);">FAQ</a>
            <a href="#tentang" class="text-sm font-medium transition-colors hover:text-violet-300" style="color: rgba(255,255,255,0.65);">Tentang Kami</a>
        </div>

        {{-- CTA --}}
        <div class="hidden md:flex items-center gap-3">
            <a href="{{ route('login') }}" class="px-4 py-2 rounded-lg text-sm font-semibold transition-all hover:bg-white/8"
               style="color: rgba(255,255,255,0.75); border: 1px solid rgba(255,255,255,0.18);">
                Masuk
            </a>
        </div>

        {{-- Mobile toggle --}}
        <button id="navbar-mobile-toggle" class="md:hidden p-2 rounded-lg" style="color: rgba(255,255,255,0.7);">
            <x-lucide-menu id="navbar-icon-menu" class="w-5 h-5" />
            <x-lucide-x id="navbar-icon-close" class="w-5 h-5 hidden" />
        </button>
    </div>

    {{-- Mobile menu --}}
    <div id="navbar-mobile-menu" class="md:hidden hidden px-6 py-4 space-y-1"
         style="background: rgba(14,8,28,0.98); backdrop-filter: blur(20px); border-top: 1px solid rgba(139,92,246,0.15);">
        <a href="#layanan" class="block py-2.5 text-sm font-medium transition-colors hover:text-violet-300" style="color: rgba(255,255,255,0.65);">Layanan</a>
        <a href="#cara-kerja" class="block py-2.5 text-sm font-medium transition-colors hover:text-violet-300" style="color: rgba(255,255,255,0.65);">Cara Kerja</a>
        <a href="#testimoni" class="block py-2.5 text-sm font-medium transition-colors hover:text-violet-300" style="color: rgba(255,255,255,0.65);">Testimoni</a>
        <a href="#faq" class="block py-2.5 text-sm font-medium transition-colors hover:text-violet-300" style="color: rgba(255,255,255,0.65);">FAQ</a>
        <a href="#tentang" class="block py-2.5 text-sm font-medium transition-colors hover:text-violet-300" style="color: rgba(255,255,255,0.65);">Tentang Kami</a>
        <div class="pt-3" style="border-top: 1px solid rgba(255,255,255,0.08);">
            <a href="{{ route('login') }}" class="block text-center px-4 py-2.5 rounded-lg text-sm font-semibold"
               style="color: rgba(255,255,255,0.75); border: 1px solid rgba(255,255,255,0.18);">Masuk</a>
        </div>
    </div>
</nav>
