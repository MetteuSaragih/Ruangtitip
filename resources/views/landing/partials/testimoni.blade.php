{{-- ==========================================================================
     Testimoni Section
     "Kata mereka yang sudah nitip." — paginated testimonial carousel
     (carousel logic handled in app.js via #testimoni-carousel)
     ========================================================================== --}}
<section id="testimoni" class="py-24 relative overflow-hidden" style="background: linear-gradient(180deg, #130829 0%, #100720 100%);">
    <div class="absolute top-0 inset-x-0 h-px" style="background: linear-gradient(90deg, transparent, rgba(139,92,246,0.3), transparent);"></div>
    <div class="absolute top-1/3 right-0 w-72 h-72 opacity-10 blur-3xl pointer-events-none rounded-full" style="background: radial-gradient(circle, #6366f1, transparent);"></div>

    <div class="max-w-6xl mx-auto px-6 relative z-10">
        @php
            $testimonials = [
                ['initials' => 'MR', 'name' => 'M. Rizal A.',    'jurusan' => "Teknik Informatika '22", 'rating' => 5, 'quote' => 'Magang 3 bulan di Jakarta, semua barang aman di RUTIP. Waktu balik ke Malang, kondisi barang persis sama. Recommended banget!', 'color' => '#7c3aed'],
                ['initials' => 'SA', 'name' => 'Siti Aisyah',    'jurusan' => "Ilmu Komunikasi '21",   'rating' => 5, 'quote' => 'Dulu tiap KKN selalu bingung naruh barang. Sekarang ada RUTIP, tinggal WA, tim langsung dateng jemput. Praktis banget!', 'color' => '#6366f1'],
                ['initials' => 'DK', 'name' => 'Dimas Kurnia',   'jurusan' => "Manajemen Bisnis '23",  'rating' => 5, 'quote' => 'Layanan antar-jemputnya on-time, foto bukti segel dikirim ke WA. Ini yang mahasiswa butuhkan!', 'color' => '#8b5cf6'],
                ['initials' => 'LF', 'name' => 'Laila Fauziah',  'jurusan' => "Psikologi '22",         'rating' => 5, 'quote' => 'Baru pertama nyoba RUTIP buat simpan koper dan kardus. Prosesnya cepet, ramah, dan bikin tenang!', 'color' => '#a78bfa'],
                ['initials' => 'BP', 'name' => 'Bagas Pratama',  'jurusan' => "Teknik Sipil '21",      'rating' => 5, 'quote' => 'Harganya worth banget dibanding bayar kos kosong. Hemat jutaan rupiah tiap tahun gara-gara RUTIP!', 'color' => '#5b21b6'],
                ['initials' => 'NR', 'name' => 'Nadia Rahma',    'jurusan' => "Kedokteran '23",        'rating' => 5, 'quote' => 'Co-ass di Surabaya 4 bulan, semua barang dititipin. Tenang banget karena dapat update kondisi barang.', 'color' => '#4f46e5'],
            ];
            $perPage = 3;
            $pages = array_chunk($testimonials, $perPage);
        @endphp

        {{-- Header + pagination --}}
        <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-6 mb-12 reveal">
            <div class="max-w-md text-left">
                <span class="inline-block px-3 py-1.5 rounded-full text-xs font-semibold border mb-4"
                      style="background: rgba(139,92,246,0.15); border-color: rgba(139,92,246,0.3); color: #c4b5fd;">
                    Testimoni
                </span>
                <h2 class="text-3xl lg:text-4xl font-extrabold text-white font-[var(--font-display)]">
                    Kata mereka yang sudah nitip.
                </h2>
            </div>

            <div class="flex items-center gap-2">
                <button id="testimoni-prev" type="button" disabled
                        class="w-9 h-9 rounded-xl flex items-center justify-center transition-all disabled:opacity-25 hover:border-violet-500/50"
                        style="border: 1px solid rgba(255,255,255,0.15); color: rgba(255,255,255,0.6); opacity: 0.25;">
                    <x-lucide-chevron-left class="w-4 h-4" />
                </button>
                <div class="flex gap-1.5">
                    @foreach ($pages as $i => $page)
                        <button type="button" class="testimoni-dot rounded-full transition-all"
                                style="width: {{ $i === 0 ? '20px' : '8px' }}; height: 8px; background: {{ $i === 0 ? '#a78bfa' : 'rgba(255,255,255,0.18)' }};"></button>
                    @endforeach
                </div>
                <button id="testimoni-next" type="button"
                        class="w-9 h-9 rounded-xl flex items-center justify-center transition-all disabled:opacity-25 hover:border-violet-500/50"
                        style="border: 1px solid rgba(255,255,255,0.15); color: rgba(255,255,255,0.6);">
                    <x-lucide-chevron-right class="w-4 h-4" />
                </button>
            </div>
        </div>

        {{-- Pages --}}
        <div id="testimoni-carousel" class="reveal">
            @foreach ($pages as $pageIndex => $page)
                <div class="testimoni-page grid grid-cols-1 md:grid-cols-3 gap-4 {{ $pageIndex === 0 ? '' : 'hidden' }}">
                    @foreach ($page as $t)
                        <div class="rounded-2xl p-5 hover:-translate-y-1 transition-all duration-300 group relative overflow-hidden"
                             style="background: rgba(255,255,255,0.045); backdrop-filter: blur(16px); border: 1px solid rgba(255,255,255,0.08);">
                            <div class="absolute -top-8 -right-8 w-24 h-24 rounded-full opacity-0 group-hover:opacity-20 transition-opacity blur-2xl pointer-events-none" style="background: {{ $t['color'] }};"></div>
                            <div class="flex mb-3">
                                @for ($r = 0; $r < $t['rating']; $r++)
                                    <x-lucide-star class="w-3.5 h-3.5 fill-amber-400 text-amber-400" />
                                @endfor
                            </div>
                            <p class="text-sm leading-relaxed mb-5 relative z-10" style="color: rgba(255,255,255,0.7);">&ldquo;{{ $t['quote'] }}&rdquo;</p>
                            <div class="flex items-center gap-3 relative z-10">
                                <div class="w-9 h-9 rounded-full flex items-center justify-center text-white text-[10px] font-bold border-2 shrink-0"
                                     style="background: {{ $t['color'] }}; border-color: rgba(255,255,255,0.15);">
                                    {{ $t['initials'] }}
                                </div>
                                <div>
                                    <p class="text-white font-semibold text-sm">{{ $t['name'] }}</p>
                                    <p class="text-xs" style="color: rgba(255,255,255,0.38);">{{ $t['jurusan'] }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endforeach
        </div>
    </div>
</section>
