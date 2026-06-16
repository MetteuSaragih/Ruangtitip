{{-- ==========================================================================
     Footer
     ========================================================================== --}}
<footer class="pt-14 pb-8" style="background: #0c0618; border-top: 1px solid rgba(139,92,246,0.15);">
    <div class="max-w-5xl mx-auto px-6">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-10 mb-12">
            {{-- Brand --}}
            <div>
                <div class="flex items-center gap-2 mb-4">
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center" style="background: linear-gradient(135deg, #7c3aed, #6366f1);">
                        <x-lucide-package class="w-4 h-4 text-white" />
                    </div>
                    <span class="text-xl font-extrabold text-white font-[var(--font-display)]">RUTIP</span>
                </div>
                <p class="text-sm leading-relaxed mb-5" style="color: rgba(255,255,255,0.38);">
                    Solusi penitipan barang mahasiswa yang aman, terjangkau, dan mudah di Malang.
                </p>
                <div class="flex gap-2">
                    <a href="#" aria-label="Instagram" class="w-8 h-8 rounded-lg flex items-center justify-center transition-all duration-200 hover:scale-110"
                       style="border: 1px solid rgba(255,255,255,0.1); color: rgba(255,255,255,0.38);">
                        <x-lucide-instagram class="w-3.5 h-3.5" />
                    </a>
                    <a href="#" aria-label="TikTok" class="w-8 h-8 rounded-lg flex items-center justify-center transition-all duration-200 hover:scale-110"
                       style="border: 1px solid rgba(255,255,255,0.1); color: rgba(255,255,255,0.38);">
                        <x-lucide-music-2 class="w-3.5 h-3.5" />
                    </a>
                </div>
            </div>

            {{-- Layanan --}}
            <div>
                <h5 class="text-white font-bold text-sm mb-5 font-[var(--font-display)]">Layanan</h5>
                <ul class="space-y-2.5">
                    @foreach (['Penitipan Barang', 'Antar-Jemput', 'Toko Packing', 'Toko Preloved', 'Pengiriman Ekspedisi'] as $item)
                        <li><a href="#" class="text-sm transition-colors hover:text-violet-400" style="color: rgba(255,255,255,0.38);">{{ $item }}</a></li>
                    @endforeach
                </ul>
            </div>

            {{-- Perusahaan --}}
            <div>
                <h5 class="text-white font-bold text-sm mb-5 font-[var(--font-display)]">Perusahaan</h5>
                <ul class="space-y-2.5">
                    @foreach (['Tentang Kami', 'Blog', 'Karir', 'Press Kit'] as $item)
                        <li><a href="#" class="text-sm transition-colors hover:text-violet-400" style="color: rgba(255,255,255,0.38);">{{ $item }}</a></li>
                    @endforeach
                </ul>
            </div>

            {{-- Kontak --}}
            <div>
                <h5 class="text-white font-bold text-sm mb-5 font-[var(--font-display)]">Kontak</h5>
                <ul class="space-y-3">
                    <li>
                        <a href="mailto:hello@rutip.id" class="flex items-center gap-2.5 text-sm transition-colors hover:text-violet-400" style="color: rgba(255,255,255,0.38);">
                            <x-lucide-mail class="w-3.5 h-3.5 shrink-0" />hello@rutip.id
                        </a>
                    </li>
                    <li>
                        <a href="https://wa.me/62812345678" class="flex items-center gap-2.5 text-sm transition-colors hover:text-violet-400" style="color: rgba(255,255,255,0.38);">
                            <x-lucide-phone class="w-3.5 h-3.5 shrink-0" />+62 812-3456-7890
                        </a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center gap-2.5 text-sm transition-colors hover:text-violet-400" style="color: rgba(255,255,255,0.38);">
                            <x-lucide-instagram class="w-3.5 h-3.5 shrink-0" />Instagram @rutip.id
                        </a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center gap-2.5 text-sm transition-colors hover:text-violet-400" style="color: rgba(255,255,255,0.38);">
                            <x-lucide-music-2 class="w-3.5 h-3.5 shrink-0" />TikTok @rutip.id
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        {{-- Bottom bar --}}
        <div class="flex flex-col sm:flex-row items-center justify-between gap-3 pt-6" style="border-top: 1px solid rgba(255,255,255,0.06);">
            <p class="text-xs" style="color: rgba(255,255,255,0.22);">&copy; 2026 RUTIP. Hak cipta dilindungi undang-undang.</p>
            <div class="flex gap-4">
                @foreach (['Syarat & Ketentuan', 'Kebijakan Privasi'] as $t)
                    <a href="#" class="text-xs transition-colors hover:text-violet-400" style="color: rgba(255,255,255,0.22);">{{ $t }}</a>
                @endforeach
            </div>
        </div>
    </div>
</footer>
