{{-- Problem Section --}}
<section class="py-24 relative overflow-hidden" style="background: linear-gradient(180deg, #0c0618 0%, #10071f 100%);">
    <div class="absolute inset-0 opacity-[0.025] pointer-events-none" style="background-image: linear-gradient(rgba(255,255,255,1) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,1) 1px, transparent 1px); background-size: 64px 64px;"></div>

    <div class="max-w-6xl mx-auto px-6 relative z-10">
        {{-- Header --}}
        <div class="text-center max-w-xl mx-auto mb-12 reveal">
            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold border mb-5"
                  style="background: rgba(124,58,237,0.15); border-color: rgba(124,58,237,0.3); color: #c4b5fd;">
                Masalah nyata mahasiswa
            </span>
            <h2 class="text-3xl lg:text-4xl font-extrabold text-white font-[var(--font-display)]">
                Pernah mengalami hal seperti ini?
            </h2>
            <p class="mt-3 text-sm leading-relaxed" style="color: rgba(255,255,255,0.42);">
                Saat harus meninggalkan Malang untuk beberapa minggu atau bulan, banyak mahasiswa menghadapi masalah yang sama.
            </p>
        </div>

        {{-- Pain point cards --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-12">
            @php
                $pains = [
                    [
                        'icon'       => 'wallet',
                        'title'      => 'Bayar kos yang tidak ditempati',
                        'desc'       => 'Tetap membayar kos hingga jutaan rupiah hanya karena barang masih tertinggal di kamar.',
                        'iconBg'     => 'rgba(124,58,237,0.18)',
                        'iconColor'  => '#a78bfa',
                        'border'     => 'rgba(124,58,237,0.22)',
                        'glow'       => 'rgba(124,58,237,0.06)',
                    ],
                    [
                        'icon'       => 'shield-alert',
                        'title'      => 'Khawatir barang rusak atau hilang',
                        'desc'       => 'Barang elektronik, buku, dan perlengkapan kuliah rentan rusak akibat kelembapan, jamur, rayap, maupun risiko pencurian.',
                        'iconBg'     => 'rgba(251,191,36,0.18)',
                        'iconColor'  => '#fbbf24',
                        'border'     => 'rgba(251,191,36,0.22)',
                        'glow'       => 'rgba(251,191,36,0.05)',
                    ],
                    [
                        'icon'       => 'truck',
                        'title'      => 'Ribet memindahkan barang',
                        'desc'       => 'Tidak punya kendaraan atau kesulitan mengemas barang membuat proses pindahan menjadi melelahkan.',
                        'iconBg'     => 'rgba(167,139,250,0.18)',
                        'iconColor'  => '#c4b5fd',
                        'border'     => 'rgba(167,139,250,0.22)',
                        'glow'       => 'rgba(167,139,250,0.06)',
                    ],
                ];
            @endphp

            @foreach ($pains as $i => $p)
                <div class="reveal relative rounded-2xl p-6 overflow-hidden hover:-translate-y-1 transition-all duration-300 group"
                     style="background: rgba(255,255,255,0.04); border: 1px solid {{ $p['border'] }}; transition-delay: {{ $i * 100 }}ms;">
                    <div class="absolute inset-0 opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none rounded-2xl" style="background: {{ $p['glow'] }};"></div>
                    <div class="w-11 h-11 rounded-xl flex items-center justify-center mb-4 relative z-10" style="background: {{ $p['iconBg'] }};">
                        <x-dynamic-component :component="'lucide-' . $p['icon']" class="w-5 h-5" style="color: {{ $p['iconColor'] }};" />
                    </div>
                    <h3 class="font-bold text-white text-sm mb-2 font-[var(--font-display)] leading-snug relative z-10">{{ $p['title'] }}</h3>
                    <p class="text-sm leading-relaxed relative z-10" style="color: rgba(255,255,255,0.45);">{{ $p['desc'] }}</p>
                </div>
            @endforeach
        </div>

        {{-- Stat callout --}}
        <div class="reveal relative rounded-3xl overflow-hidden p-10"
             style="background: linear-gradient(135deg, #1e0a3c 0%, #160a2e 60%, #1c0b36 100%); border: 1px solid rgba(139,92,246,0.2);">
            <div class="absolute inset-0 pointer-events-none" style="background: radial-gradient(ellipse at 20% 50%, rgba(124,58,237,0.35), transparent 55%), radial-gradient(ellipse at 80% 50%, rgba(99,102,241,0.2), transparent 55%);"></div>
            <div class="relative z-10 flex flex-col md:flex-row md:items-center gap-6">
                <div class="shrink-0">
                    <p class="text-xs font-semibold tracking-widest uppercase mb-2" style="color: #c4b5fd;">Statistik</p>
                    <div class="text-5xl lg:text-6xl font-extrabold font-[var(--font-display)] mb-1"
                         style="background: linear-gradient(135deg, #a78bfa 0%, #7c3aed 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
                        93,5%
                    </div>
                </div>
                <div class="flex-1">
                    <p class="text-white text-lg font-bold font-[var(--font-display)] leading-snug">
                        mahasiswa UB pernah tetap membayar kos kosong
                    </p>
                    <p class="text-sm mt-2 leading-relaxed" style="color: rgba(255,255,255,0.42);">
                        karena tidak memiliki alternatif penyimpanan barang yang aman dan terpercaya.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>
