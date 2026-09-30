<<<<<<< HEAD
<section class="hero">
  <div class="wrap hero-grid">
    <div class="hero-copy">
      <span class="eyebrow">Khusus mahasiswa di Malang</span>
      <h1>Pulang kampung, barang kos tetap aman.</h1>
      <p class="lead">Libur semester, magang di luar kota, atau pindah kos? Titip barangmu di RuangTitip. Kami jemput, kami simpan, kamu nggak perlu bayar kos kosong.</p>
      <div class="hero-actions">
        <a class="btn btn-primary" href="#survei">Mulai titip barang</a>
        <a class="link-under" href="#cara">Lihat cara kerjanya</a>
      </div>
      <dl class="stats">
        <div><dt>penitip aktif</dt><dd>150+</dd></div>
        <div><dt>barang tersimpan</dt><dd>500+</dd></div>
        <div><dt>layanan: titip, jemput, packing</dt><dd>3</dd></div>
      </dl>
    </div>

    <div class="hero-art">
      <div class="disc" aria-hidden="true"></div>
      <div class="shadow" aria-hidden="true"></div>
      <img class="ruru" src="{{ asset('assets/ruru.webp') }}" alt="Ruru, maskot RuangTitip berbentuk kardus, melambaikan tangan" width="769" height="900">
      <div class="bubble"><strong id="ruru-bubble-text">Hii, aku Ruru!</strong></div>
      <div class="chip-ok">
        <span class="dot" aria-hidden="true"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg></span>
        Dijemput langsung dari kos
      </div>
=======
{{-- Hero Section --}}
<section class="relative min-h-screen flex items-center overflow-hidden" style="background: #0c0618;">
    {{-- Mesh background --}}
    <div class="absolute inset-0 pointer-events-none overflow-hidden">
        <canvas id="rt-particles" style="position:absolute;inset:0;width:100%;height:100%;pointer-events:none;z-index:0;"></canvas>
        <div class="absolute -top-32 -left-32 w-[600px] h-[600px] rounded-full opacity-35 blur-3xl" style="background: radial-gradient(circle, #5b21b6 0%, transparent 70%);"></div>
        <div class="absolute top-1/3 right-0 w-[500px] h-[500px] rounded-full opacity-20 blur-3xl" style="background: radial-gradient(circle, #4f46e5 0%, transparent 70%);"></div>
        <div class="absolute -bottom-24 left-1/4 w-[400px] h-[400px] rounded-full opacity-15 blur-3xl" style="background: radial-gradient(circle, #6d28d9 0%, transparent 70%);"></div>
        <div class="absolute inset-0 opacity-[0.03]" style="background-image: linear-gradient(rgba(255,255,255,1) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,1) 1px, transparent 1px); background-size: 64px 64px;"></div>
    </div>

    <div class="relative z-10 max-w-6xl mx-auto px-6 pt-28 pb-24 w-full">
        <div class="max-w-2xl">
            {{-- Badge --}}
            <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full text-xs font-semibold border mb-8"
                 style="background: rgba(139,92,246,0.15); border-color: rgba(139,92,246,0.35); color: #c4b5fd;">
                <span class="w-1.5 h-1.5 rounded-full bg-violet-400 animate-pulse"></span>
                Layanan penitipan barang #1 di Malang
            </div>

            {{-- Headline --}}
            <div class="mb-6">
                <h1 id="rt-hero-l1" class="font-extrabold text-white font-[var(--font-display)]" style="font-size: clamp(2.8rem, 7vw, 5rem); line-height: 1.06; min-height: 1.06em;">
                    Titip Barangmu,
                </h1>
                <h1 id="rt-hero-l2" class="font-extrabold font-[var(--font-display)]" style="font-size: clamp(2.8rem, 7vw, 5rem); line-height: 1.06; min-height: 1.06em; background: linear-gradient(135deg, #a78bfa 0%, #7c3aed 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
                    Simpan Uangmu.
                </h1>
            </div>

            {{-- Subheadline --}}
            <p class="hero-reveal text-lg leading-relaxed mb-10 max-w-md" style="color: rgba(255,255,255,0.52);">
                Titipkan barangmu saat pulang kampung, libur semester, atau pindah kos. Praktis, aman, dan tanpa repot mencari tempat penyimpanan sendiri.
            </p>

            {{-- CTAs --}}
            <div class="hero-reveal flex flex-wrap gap-3 mb-10">
                <a href="{{ route('login') }}" class="inline-flex items-center gap-2 px-7 py-3.5 rounded-xl font-semibold text-white transition-all duration-200 hover:scale-105"
                   style="background: linear-gradient(135deg, #7c3aed, #6366f1); box-shadow: 0 8px 28px rgba(124,58,237,0.45);">
                    Titip Sekarang <x-lucide-arrow-right class="w-4 h-4" />
                </a>
                <a href="#cara-kerja" class="inline-flex items-center gap-2 px-7 py-3.5 rounded-xl font-semibold transition-all duration-200 hover:bg-white/8"
                   style="color: rgba(255,255,255,0.7); border: 1px solid rgba(255,255,255,0.18);">
                    <x-lucide-play class="w-4 h-4" /> Lihat Cara Kerja
                </a>
            </div>

            {{-- Social proof --}}
            <div class="hero-reveal flex items-center gap-3">
                <div class="flex -space-x-2">
                    @foreach ([['bg' => '#7c3aed', 'label' => 'MR'], ['bg' => '#6366f1', 'label' => 'SA'], ['bg' => '#8b5cf6', 'label' => 'DK'], ['bg' => '#a78bfa', 'label' => 'LF']] as $avatar)
                        <div class="w-8 h-8 rounded-full border-2 flex items-center justify-center text-white text-[10px] font-bold"
                             style="background: {{ $avatar['bg'] }}; border-color: #0c0618;">
                            {{ $avatar['label'] }}
                        </div>
                    @endforeach
                </div>
                <div class="flex items-center gap-1.5">
                    <div class="flex">
                        @for ($i = 0; $i < 5; $i++)
                            <x-lucide-star class="w-3 h-3 fill-amber-400 text-amber-400" />
                        @endfor
                    </div>
                    <span class="text-sm" style="color: rgba(255,255,255,0.45);">
                        Dipercaya <span class="text-white font-semibold">150+</span> mahasiswa Brawijaya
                    </span>
                </div>
            </div>
        </div>
    </div>

    {{-- Scroll indicator --}}
    <div class="absolute bottom-8 left-1/2 -translate-x-1/2 flex flex-col items-center opacity-25">
        <div class="w-px h-10 bg-gradient-to-b from-white/60 to-transparent"></div>
>>>>>>> hostinger/main
    </div>
  </div>
</section>
