{{-- ==========================================================================
     How It Works Section
     "Semudah 3 langkah." — 3-step process with connecting line
     ========================================================================== --}}
<section id="cara-kerja" class="py-24 relative overflow-hidden" style="background: linear-gradient(180deg, #130829 0%, #0f0720 100%);">
    {{-- Subtle horizontal divider glow --}}
    <div class="absolute top-0 inset-x-0 h-px" style="background: linear-gradient(90deg, transparent, rgba(139,92,246,0.4), transparent);"></div>
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[600px] h-32 opacity-15 blur-3xl pointer-events-none" style="background: radial-gradient(ellipse, #7c3aed, transparent);"></div>

    <div class="max-w-6xl mx-auto px-6 relative z-10">
        {{-- Header — centered --}}
        <div class="text-center max-w-lg mx-auto mb-16 reveal">
            <span class="inline-block px-3 py-1.5 rounded-full text-xs font-semibold border mb-5"
                  style="background: rgba(139,92,246,0.15); border-color: rgba(139,92,246,0.3); color: #c4b5fd;">
                Cara Kerja
            </span>
            <h2 class="text-3xl lg:text-4xl font-extrabold text-white font-[var(--font-display)]">
                Semudah 3 langkah.
            </h2>
            <p class="mt-3 text-sm leading-relaxed" style="color: rgba(255,255,255,0.42);">
                Dari pesan sampai barang aman — semua bisa kamu kontrol dari genggaman.
            </p>
        </div>

        <div class="relative">
            {{-- Connecting line --}}
            <div class="hidden lg:block absolute top-10 left-[calc(16.67%+40px)] right-[calc(16.67%+40px)] h-px" style="background: rgba(139,92,246,0.2);"></div>
            <div class="hidden lg:block absolute top-10 left-[calc(16.67%+40px)] right-[calc(16.67%+40px)] h-px"
                 style="background: linear-gradient(90deg, #7c3aed, #6366f1);"></div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
                @php
                    $steps = [
                        ['icon' => 'clipboard-list',   'title' => 'Pesan lewat website',    'desc' => 'Pilih jenis barang, ukuran, durasi, dan jadwal jemput yang kamu mau. Prosesnya cuma 3 menit.', 'highlight' => '3 menit selesai'],
                        ['icon' => 'truck',            'title' => 'Kami jemput barangmu',   'desc' => 'Tim RUTIP datang ke kos kamu sesuai jadwal, atau kamu bisa antar sendiri ke gudang kami.', 'highlight' => 'Seluruh Malang'],
                        ['icon' => 'layout-dashboard', 'title' => 'Barang aman tersimpan',  'desc' => 'Pantau kondisi barang lewat dashboard, terima foto bukti segel, dan ambil kapan pun kamu mau.', 'highlight' => 'Notif real-time'],
                    ];
                @endphp

                @foreach ($steps as $i => $step)
                    <div class="reveal flex flex-col items-center text-center" style="transition-delay: {{ $i * 130 }}ms;">
                        <div class="relative mb-7">
                            <div class="w-20 h-20 rounded-2xl flex items-center justify-center"
                                 style="background: linear-gradient(135deg, #7c3aed, #6366f1); box-shadow: 0 8px 28px rgba(124,58,237,0.35);">
                                <x-dynamic-component :component="'lucide-' . $step['icon']" class="w-8 h-8 text-white" />
                            </div>
                            <div class="absolute -top-2 -right-2 w-6 h-6 rounded-full flex items-center justify-center text-[11px] font-bold text-white border-2"
                                 style="background: linear-gradient(135deg, #7c3aed, #ec4899); border-color: #0f0720;">
                                {{ $i + 1 }}
                            </div>
                        </div>
                        <h3 class="font-extrabold text-white text-base mb-2.5 font-[var(--font-display)]">{{ $step['title'] }}</h3>
                        <p class="text-sm leading-relaxed mb-4" style="color: rgba(255,255,255,0.45);">{{ $step['desc'] }}</p>
                        <span class="inline-flex items-center gap-1.5 text-xs font-semibold px-3 py-1.5 rounded-full"
                              style="background: rgba(139,92,246,0.15); color: #c4b5fd; border: 1px solid rgba(139,92,246,0.25);">
                            &check; {{ $step['highlight'] }}
                        </span>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="reveal mt-14 text-center">
            <a href="{{ route('login') }}" class="inline-flex items-center gap-2 px-6 py-3 rounded-xl font-semibold text-white text-sm transition-all duration-200 hover:scale-105"
               style="background: linear-gradient(135deg, #7c3aed, #6366f1); box-shadow: 0 6px 20px rgba(124,58,237,0.3);">
                Mulai Titip Sekarang
            </a>
        </div>
    </div>
</section>
