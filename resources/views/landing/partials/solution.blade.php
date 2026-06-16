{{-- ==========================================================================
     Solution Section
     "RUTIP hadir buat kamu." — 6 service cards
     ========================================================================== --}}
<section id="layanan" class="py-24 relative overflow-hidden" style="background: linear-gradient(180deg, #10071f 0%, #130829 100%);">
    <div class="absolute top-0 right-0 w-[500px] h-[400px] opacity-10 blur-3xl pointer-events-none" style="background: radial-gradient(ellipse, #7c3aed, transparent);"></div>

    <div class="max-w-6xl mx-auto px-6 relative z-10">
        {{-- Header — centered --}}
        <div class="text-center max-w-lg mx-auto mb-12 reveal">
            <span class="inline-block px-3 py-1.5 rounded-full text-xs font-semibold border mb-5"
                  style="background: rgba(139,92,246,0.15); border-color: rgba(139,92,246,0.3); color: #c4b5fd;">
                Layanan
            </span>
            <h2 class="text-3xl lg:text-4xl font-extrabold text-white font-[var(--font-display)]">
                RUTIP hadir buat kamu.
            </h2>
            <p class="mt-3 text-sm leading-relaxed" style="color: rgba(255,255,255,0.42);">
                Semua kebutuhanmu — dari titip barang hingga kirim paket — ada di satu tempat.
            </p>
        </div>

        {{-- Service cards --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3.5 mb-10">
            @php
                $services = [
                    ['icon' => 'package',       'title' => 'Penitipan Barang',      'desc' => 'Kardus, koper, elektronik — disimpan aman di gudang ber-CCTV.',            'tag' => 'Populer',   'tagBg' => 'rgba(167,139,250,0.25)', 'tagColor' => '#c4b5fd'],
                    ['icon' => 'truck',         'title' => 'Antar-Jemput Logistik', 'desc' => 'Tim RUTIP datang ke kos kamu, atau kamu antar langsung ke gudang.',        'tag' => 'Praktis',   'tagBg' => 'rgba(129,140,248,0.25)', 'tagColor' => '#a5b4fc'],
                    ['icon' => 'shopping-bag',  'title' => 'Toko Packing',          'desc' => 'Kardus, bubble wrap, lakban — semua perlengkapan tersedia lengkap.',       'tag' => null,        'tagBg' => '',                       'tagColor' => ''],
                    ['icon' => 'refresh-cw',    'title' => 'Toko Preloved',         'desc' => 'Jual atau beli barang bekas berkualitas dari sesama mahasiswa.',           'tag' => 'Baru',      'tagBg' => 'rgba(124,58,237,0.25)',  'tagColor' => '#c4b5fd'],
                    ['icon' => 'send',          'title' => 'Pengiriman Ekspedisi',  'desc' => 'Kirim ke seluruh Indonesia, harga transparan, packaging terjamin.',        'tag' => null,        'tagBg' => '',                       'tagColor' => ''],
                    ['icon' => 'smartphone',    'title' => 'Platform Digital',      'desc' => 'Pantau semua titipan dari dashboard yang mudah dan real-time.',            'tag' => 'Real-time', 'tagBg' => 'rgba(52,211,153,0.2)',   'tagColor' => '#6ee7b7'],
                ];
            @endphp

            @foreach ($services as $i => $svc)
                <div class="reveal group relative rounded-2xl p-5 overflow-hidden hover:-translate-y-1.5 transition-all duration-300 cursor-pointer"
                     style="background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.08); transition-delay: {{ $i * 70 }}ms;">
                    <div class="absolute inset-0 rounded-2xl opacity-0 group-hover:opacity-100 transition-all duration-300 pointer-events-none"
                         style="background: rgba(124,58,237,0.1); border: 1px solid rgba(139,92,246,0.35);"></div>
                    <div class="relative z-10">
                        <div class="flex items-start justify-between mb-4">
                            <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background: rgba(139,92,246,0.2);">
                                <x-dynamic-component :component="'lucide-' . $svc['icon']" class="w-5 h-5 text-violet-400" />
                            </div>
                            @if ($svc['tag'])
                                <span class="text-[10px] font-bold px-2 py-0.5 rounded-full" style="background: {{ $svc['tagBg'] }}; color: {{ $svc['tagColor'] }};">
                                    {{ $svc['tag'] }}
                                </span>
                            @endif
                        </div>
                        <h3 class="font-bold text-white text-sm mb-1.5 font-[var(--font-display)]">{{ $svc['title'] }}</h3>
                        <p class="text-xs leading-relaxed" style="color: rgba(255,255,255,0.42);">{{ $svc['desc'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- CTA --}}
        <div class="reveal text-center">
            <a href="{{ route('login') }}" class="inline-flex items-center gap-2 px-6 py-3 rounded-xl font-semibold text-white text-sm transition-all duration-200 hover:scale-105"
               style="background: linear-gradient(135deg, #7c3aed, #6366f1); box-shadow: 0 6px 24px rgba(124,58,237,0.35);">
                Mulai Titip Sekarang <x-lucide-arrow-right class="w-4 h-4" />
            </a>
            <p class="mt-2 text-xs text-center" style="color: rgba(255,255,255,0.28);">Gratis daftar &middot; Tanpa biaya tersembunyi</p>
        </div>
    </div>
</section>
