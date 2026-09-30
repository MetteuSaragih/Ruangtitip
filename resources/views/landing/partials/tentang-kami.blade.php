<<<<<<< HEAD
<section class="section" style="padding-top:0">
  <div class="wrap team">
    <div class="team-intro">
      <h2 class="h2-sm">Dibuat mahasiswa yang pernah bingung titip barang di mana.</h2>
      <p class="lead" style="font-size:17px">RuangTitip lahir Februari 2026 dari masalah kami sendiri, go digital Mei 2026, dan buka gudang pertama Agustus 2026.</p>
=======
{{-- Tentang Kami Section --}}
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
                    Menjadi layanan penitipan barang profesional berbasis platform digital nomor satu di Indonesia yang menyediakan solusi penyimpanan aman, terjangkau, dan fleksibel bagi mahasiswa, guna mendukung mobilitas yang dinamis serta efisiensi finansial civitas akademika.
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
                <ul class="space-y-3">
                    @foreach ([
                        'Menghadirkan layanan penitipan barang yang aman, terjangkau, dan mudah diakses melalui platform digital berbasis aplikasi/web yang dirancang khusus untuk kebutuhan mahasiswa perantau.',
                        'Menyediakan fasilitas penyimpanan yang dikelola secara profesional dan terpusat oleh tim RUTIP dengan standar keamanan berlapis.',
                        'Memberikan kemudahan mobilisasi barang melalui layanan antar-jemput logistik lokal yang terintegrasi langsung dalam platform.',
                        'Membangun kepercayaan pengguna melalui transparansi operasional, jaminan keamanan barang, serta sistem layanan yang responsif dan bertanggung jawab.',
                    ] as $item)
                        <li class="flex items-start gap-2.5">
                            <span class="mt-1.5 w-1.5 h-1.5 rounded-full shrink-0" style="background: #818cf8;"></span>
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

            <div class="relative">
                {{-- Horizontal connector line --}}
                <div class="hidden md:block absolute top-[22px] left-[calc(1/6*100%)] right-[calc(1/6*100%)] h-px"
                     style="background: linear-gradient(90deg, #7c3aed, #6366f1, #7c3aed);"></div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @php
                        $milestones = [
                            [
                                'year'  => 'Februari 2026',
                                'title' => 'RUTIP Lahir',
                                'desc'  => 'Ide RUTIP muncul dari frustrasi mahasiswa Brawijaya yang harus bayar kos kosong saat magang. Dimulai dengan 5 pengguna pertama dari kamar kos.',
                            ],
                            [
                                'year'  => 'Mei 2026',
                                'title' => 'Platform Digital',
                                'desc'  => 'Website RUTIP diluncurkan. Mahasiswa bisa pesan, pantau, dan kelola titipan secara online dengan notifikasi WhatsApp real-time.',
                            ],
                            [
                                'year'  => 'Agustus 2026',
                                'title' => 'Gudang Pertama',
                                'desc'  => 'Gudang pertama RUTIP resmi beroperasi di Malang. Kapasitas 200 kardus, CCTV terpasang, dan layanan jemput mulai berjalan.',
                            ],
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

        {{-- Tim Kami --}}
        <div style="margin-top:80px;">
            {{-- Spacer pemisah dari section milestone --}}
            <div style="height:1px;background:linear-gradient(90deg,transparent,rgba(139,92,246,0.2),transparent);margin-bottom:64px;"></div>

            <div class="reveal" style="text-align:center;margin-bottom:52px;">
                <div style="display:inline-flex;align-items:center;gap:6px;padding:5px 16px;border-radius:999px;font-size:11px;font-weight:700;border:1px solid rgba(124,58,237,0.3);background:rgba(124,58,237,0.15);color:#c4b5fd;">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                    Tim Pendiri
                </div>
                <div style="height:28px;"></div>
                <h3 style="font-size:clamp(1.6rem,3vw,2.1rem);font-weight:800;color:#fff;margin:0;line-height:1.2;">Orang-orang di balik RUTIP</h3>
                <div style="height:14px;"></div>
                <p style="font-size:14px;color:rgba(255,255,255,0.42);margin:0;">Mahasiswa yang punya masalah yang sama, lalu memutuskan untuk menyelesaikannya.</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 max-w-3xl mx-auto">
                @php
                    $team = [
                        ['init' => 'MS', 'name' => 'Metteu AK. Saragih',       'role' => 'Founder & CEO',       'desc' => 'Inisiator di balik RUTIP. Menggerakkan visi agar penitipan barang semudah memesan ojek online, dari ide sampai produk nyata.',    'from' => 'Sistem Informasi', 'grad1' => '#7c3aed', 'grad2' => '#6366f1'],
                        ['init' => 'GG', 'name' => 'Gracyella Exaudi Girsang', 'role' => 'Co-Founder & CTO',    'desc' => 'Arsitek platform digital RUTIP. Bertanggung jawab membangun sistem yang andal agar pengalaman pengguna tetap mulus dari ujung ke ujung.', 'from' => 'Sistem Informasi', 'grad1' => '#6366f1', 'grad2' => '#8b5cf6'],
                        ['init' => 'JL', 'name' => 'Jeanete Arthika Lorentz',  'role' => 'Co-Founder & COO',   'desc' => 'Memastikan roda operasional RUTIP berjalan tepat waktu. Dari koordinasi gudang hingga layanan pelanggan, semua ada di bawah kendalinya.', 'from' => 'Sistem Informasi', 'grad1' => '#8b5cf6', 'grad2' => '#a78bfa'],
                    ];
                @endphp
                @foreach ($team as $i => $m)
                <div class="reveal rt-tilt group rounded-2xl p-6 flex flex-col items-center text-center"
                     style="background:rgba(255,255,255,0.04);border:1px solid rgba(139,92,246,0.18);transition-delay:{{ $i * 110 }}ms;">
                    {{-- Avatar / Foto placeholder --}}
                    <div class="w-20 h-20 rounded-2xl flex items-center justify-center text-2xl font-extrabold text-white mb-5 shrink-0 relative overflow-hidden"
                         style="background:linear-gradient(135deg,{{ $m['grad1'] }},{{ $m['grad2'] }});box-shadow:0 10px 28px rgba(124,58,237,0.35);">
                        {{-- Ganti div ini dengan <img> ketika foto tersedia --}}
                        {{ $m['init'] }}
                    </div>
                    <div class="font-extrabold text-white text-base mb-1 font-[var(--font-display)] leading-tight">{{ $m['name'] }}</div>
                    <div class="inline-flex items-center text-[11px] font-bold mb-4 px-3 py-1 rounded-full"
                         style="background:rgba(124,58,237,0.18);color:#c4b5fd;border:1px solid rgba(124,58,237,0.25);">
                        {{ $m['role'] }}
                    </div>
                    <p class="text-xs leading-relaxed mb-4" style="color:rgba(255,255,255,0.48);">{{ $m['desc'] }}</p>
                    <div class="mt-auto flex items-center gap-1.5 text-[10px] font-medium" style="color:rgba(255,255,255,0.28);">
                        <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c3 3 9 3 12 0v-5"/></svg>
                        {{ $m['from'] }}
                    </div>
                </div>
                @endforeach
            </div>
        </div>

>>>>>>> hostinger/main
    </div>
    <ul class="team-list">
      {{-- Ganti isi .ph dengan <img src="{{ asset('assets/tim-metteu.webp') }}" alt="Metteu AK. Saragih"> --}}
      <li><div class="ph">[Foto]</div><strong>Metteu AK. Saragih</strong><span>Founder &amp; CEO</span></li>
      <li><div class="ph">[Foto]</div><strong>Gracyella Exaudi Girsang</strong><span>Co-Founder &amp; CTO</span></li>
      <li><div class="ph">[Foto]</div><strong>Jeanete Arthika Lorentz</strong><span>Co-Founder &amp; COO</span></li>
    </ul>
  </div>
</section>
