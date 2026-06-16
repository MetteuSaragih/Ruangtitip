{{-- ==========================================================================
     Tentang Kami Section
     Visi & Misi cards + "Perjalanan RUTIP" horizontal timeline
     ========================================================================== --}}
<section id="tentang" class="py-24 relative overflow-hidden" style="background: linear-gradient(180deg, #0c0618 0%, #0f0720 100%);">
    <div class="absolute top-0 inset-x-0 h-px" style="background: linear-gradient(90deg, transparent, rgba(139,92,246,0.25), transparent);"></div>
    <div class="absolute top-0 left-0 w-[500px] h-[400px] opacity-12 blur-3xl pointer-events-none" style="background: radial-gradient(ellipse, #7c3aed, transparent);"></div>
    <div class="absolute bottom-0 right-0 w-80 h-80 opacity-8 blur-3xl pointer-events-none" style="background: radial-gradient(circle, #7c3aed, transparent);"></div>

    <div class="max-w-6xl mx-auto px-6 relative z-10">

        {{-- Header --}}
        <div class="text-center max-w-xl mx-auto mb-16 reveal">
            <span class="inline-block px-3 py-1.5 rounded-full text-xs font-semibold border mb-5"
                  style="background: rgba(139,92,246,0.15); border-color: rgba(139,92,246,0.3); color: #c4b5fd;">
                Tentang Kami
            </span>
            <h2 class="text-3xl lg:text-4xl font-extrabold text-white font-[var(--font-display)]">
                Kami hadir karena pernah ada di posisi kamu.
            </h2>
            <p class="mt-3 text-sm leading-relaxed" style="color: rgba(255,255,255,0.42);">
                RUTIP didirikan oleh mahasiswa, untuk mahasiswa. Kami tahu persis rasa frustrasi bayar kos kosong saat jauh dari Malang.
            </p>
        </div>

        {{-- Visi & Misi --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-16">
            {{-- Visi --}}
            <div class="reveal rounded-2xl p-7 relative overflow-hidden"
                 style="background: rgba(255,255,255,0.04); border: 1px solid rgba(167,139,250,0.25);">
                <div class="absolute -top-10 -right-10 w-40 h-40 rounded-full opacity-10 blur-2xl pointer-events-none" style="background: #a78bfa;"></div>
                <div class="w-11 h-11 rounded-xl flex items-center justify-center mb-5" style="background: rgba(167,139,250,0.18);">
                    <x-lucide-eye class="w-5 h-5" style="color: #a78bfa;" />
                </div>
                <h3 class="font-extrabold text-white text-lg mb-3 font-[var(--font-display)]">Visi</h3>
                <p class="text-sm leading-relaxed" style="color: rgba(255,255,255,0.55);">
                    Menjadi platform logistik dan penitipan barang mahasiswa paling terpercaya di Indonesia — menjadikan mobilitas mahasiswa lebih bebas, hemat, dan tanpa khawatir.
                </p>
            </div>

            {{-- Misi --}}
            <div class="reveal rounded-2xl p-7 relative overflow-hidden"
                 style="background: rgba(255,255,255,0.04); border: 1px solid rgba(99,102,241,0.25);">
                <div class="absolute -top-10 -right-10 w-40 h-40 rounded-full opacity-10 blur-2xl pointer-events-none" style="background: #818cf8;"></div>
                <div class="w-11 h-11 rounded-xl flex items-center justify-center mb-5" style="background: rgba(99,102,241,0.18);">
                    <x-lucide-target class="w-5 h-5" style="color: #818cf8;" />
                </div>
                <h3 class="font-extrabold text-white text-lg mb-3 font-[var(--font-display)]">Misi</h3>
                <ul class="space-y-2.5">
                    @foreach ([
                        'Menyediakan layanan penitipan barang yang aman, transparan, dan terjangkau.',
                        'Menghadirkan kemudahan antar-jemput barang hingga ke depan kos.',
                        'Membangun ekosistem logistik mahasiswa yang terintegrasi secara digital.',
                        'Mengurangi pemborosan finansial mahasiswa akibat kos kosong.',
                    ] as $item)
                        <li class="flex items-start gap-2.5">
                            <span class="mt-1 w-1.5 h-1.5 rounded-full shrink-0" style="background: #818cf8;"></span>
                            <span class="text-sm leading-relaxed" style="color: rgba(255,255,255,0.55);">{{ $item }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>

        {{-- Perjalanan --}}
        <div>
            <div class="text-center mb-10 reveal">
                <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full text-xs font-semibold border"
                     style="background: rgba(124,58,237,0.15); border-color: rgba(124,58,237,0.3); color: #c4b5fd;">
                    <x-lucide-milestone class="w-3 h-3" /> Perjalanan RUTIP
                </div>
            </div>

            {{-- Horizontal timeline — 3 cards in a row --}}
            <div class="relative">
                {{-- Horizontal connector line --}}
                <div class="hidden md:block absolute top-[22px] left-[calc(1/6*100%)] right-[calc(1/6*100%)] h-px"
                     style="background: linear-gradient(90deg, #7c3aed, #6366f1, #7c3aed);"></div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @php
                        $milestones = [
                            ['year' => 'Jan 2026', 'title' => 'RUTIP Lahir',       'desc' => 'Ide RUTIP muncul dari frustrasi mahasiswa Brawijaya yang harus bayar kos kosong saat magang. Dimulai dengan 5 pengguna pertama dari kamar kos.'],
                            ['year' => 'Mar 2026', 'title' => 'Gudang Pertama',    'desc' => 'Gudang pertama RUTIP resmi beroperasi di Malang. Kapasitas 200 kardus, CCTV terpasang, dan layanan jemput mulai berjalan.'],
                            ['year' => 'Mei 2026', 'title' => 'Platform Digital',  'desc' => 'Website RUTIP diluncurkan. Mahasiswa bisa pesan, pantau, dan kelola titipan secara online dengan notifikasi WhatsApp real-time.'],
                        ];
                    @endphp

                    @foreach ($milestones as $i => $m)
                        <div class="reveal flex flex-col items-center text-center" style="transition-delay: {{ $i * 120 }}ms;">
                            {{-- Dot --}}
                            <div class="w-11 h-11 rounded-full border-2 flex items-center justify-center mb-5 shrink-0"
                                 style="background: #1a0d36; border-color: #7c3aed; box-shadow: 0 0 16px rgba(124,58,237,0.35);">
                                <div class="w-4 h-4 rounded-full" style="background: linear-gradient(135deg, #a78bfa, #7c3aed);"></div>
                            </div>

                            {{-- Card --}}
                            <div class="rounded-2xl p-5 w-full" style="background: rgba(255,255,255,0.04); border: 1px solid rgba(139,92,246,0.2);">
                                <span class="text-xs font-bold mb-2 block" style="color: #a78bfa;">{{ $m['year'] }}</span>
                                <h4 class="font-extrabold text-white text-sm mb-1.5 font-[var(--font-display)]">{{ $m['title'] }}</h4>
                                <p class="text-xs leading-relaxed" style="color: rgba(255,255,255,0.48);">{{ $m['desc'] }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

    </div>
</section>
