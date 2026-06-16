{{-- ==========================================================================
     Trust Section
     "Barangmu aman di tangan kami." — 4 trust point cards + guarantee badge
     ========================================================================== --}}
<section class="py-24 relative overflow-hidden" style="background: linear-gradient(180deg, #0f0720 0%, #130829 100%);">
    <div class="absolute top-0 inset-x-0 h-px" style="background: linear-gradient(90deg, transparent, rgba(139,92,246,0.3), transparent);"></div>
    <div class="absolute bottom-0 right-0 w-80 h-80 opacity-10 blur-3xl pointer-events-none rounded-full" style="background: radial-gradient(circle, #7c3aed, transparent);"></div>

    <div class="max-w-6xl mx-auto px-6 relative z-10">
        {{-- Header — centered --}}
        <div class="text-center max-w-lg mx-auto mb-12 reveal">
            <span class="inline-block px-3 py-1.5 rounded-full text-xs font-semibold border mb-5"
                  style="background: rgba(139,92,246,0.15); border-color: rgba(139,92,246,0.3); color: #c4b5fd;">
                Keamanan
            </span>
            <h2 class="text-3xl lg:text-4xl font-extrabold text-white font-[var(--font-display)]">
                Barangmu aman di tangan kami.
            </h2>
            <p class="mt-3 text-sm leading-relaxed" style="color: rgba(255,255,255,0.42);">
                Sistem keamanan berlapis agar setiap barang yang dititipkan benar-benar terjaga.
            </p>
        </div>

        {{-- Trust point cards --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-8">
            @php
                $trustPoints = [
                    ['icon' => 'camera',          'title' => 'CCTV 24 Jam',          'desc' => 'Setiap sudut gudang dipantau kamera keamanan aktif sepanjang waktu.', 'accent' => '#a78bfa', 'iconBg' => 'rgba(167,139,250,0.18)', 'border' => 'rgba(167,139,250,0.25)'],
                    ['icon' => 'image',           'title' => 'Foto Bukti Tersegel',  'desc' => 'Foto kondisi dan stiker segel RUTIP dikirim ke akunmu setelah barang masuk.', 'accent' => '#818cf8', 'iconBg' => 'rgba(129,140,248,0.18)', 'border' => 'rgba(129,140,248,0.25)'],
                    ['icon' => 'shield-check',    'title' => 'Jaminan Ganti Rugi',   'desc' => 'Jika ada kerusakan akibat kelalaian kami, kamu berhak mendapat ganti rugi penuh.', 'accent' => '#34d399', 'iconBg' => 'rgba(52,211,153,0.18)', 'border' => 'rgba(52,211,153,0.25)'],
                    ['icon' => 'message-circle',  'title' => 'Notifikasi Real-time', 'desc' => 'Update status barang — masuk, tersegel, siap diambil — langsung ke WhatsApp kamu.', 'accent' => '#7c3aed', 'iconBg' => 'rgba(124,58,237,0.18)', 'border' => 'rgba(124,58,237,0.25)'],
                ];
            @endphp

            @foreach ($trustPoints as $i => $p)
                <div class="reveal group rounded-2xl p-5 hover:-translate-y-1 transition-all duration-300"
                     style="background: rgba(255,255,255,0.04); backdrop-filter: blur(12px); border: 1px solid {{ $p['border'] }}; transition-delay: {{ $i * 100 }}ms;">
                    <div class="flex items-start gap-4">
                        <div class="w-11 h-11 rounded-xl flex items-center justify-center shrink-0" style="background: {{ $p['iconBg'] }};">
                            <x-dynamic-component :component="'lucide-' . $p['icon']" class="w-5 h-5" style="color: {{ $p['accent'] }};" />
                        </div>
                        <div>
                            <h4 class="font-bold text-white text-sm mb-1.5 font-[var(--font-display)]">{{ $p['title'] }}</h4>
                            <p class="text-sm leading-relaxed" style="color: rgba(255,255,255,0.48);">{{ $p['desc'] }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Guarantee badge --}}
        <div class="reveal flex justify-center">
            <div class="inline-flex items-center gap-3 px-6 py-3.5 rounded-2xl"
                 style="background: rgba(52,211,153,0.1); border: 1px solid rgba(52,211,153,0.25);">
                <x-lucide-shield-check class="w-5 h-5 text-emerald-400" />
                <div>
                    <p class="text-sm font-semibold" style="color: #6ee7b7;">100% Terjamin &amp; Terdokumentasi</p>
                    <p class="text-xs" style="color: rgba(255,255,255,0.32);">Foto bukti segel tersimpan di akunmu selamanya</p>
                </div>
            </div>
        </div>
    </div>
</section>
