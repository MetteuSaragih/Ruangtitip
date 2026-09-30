<<<<<<< HEAD
<section class="section" id="cara">
  <div class="wrap">
    <div class="head-row">
      <h2 class="h2">Tiga langkah, selesai dari kamar kos.</h2>
      <p class="lead">Nggak perlu cari gudang, nggak perlu pinjam mobil teman. Semua diurus lewat satu pesanan.</p>
=======
{{-- How It Works Section --}}
<section id="cara-kerja" class="py-24 relative overflow-hidden" style="background: linear-gradient(180deg, #130829 0%, #0f0720 100%);">
    <div class="absolute top-0 inset-x-0 h-px" style="background: linear-gradient(90deg, transparent, rgba(139,92,246,0.4), transparent);"></div>
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[600px] h-32 opacity-15 blur-3xl pointer-events-none" style="background: radial-gradient(ellipse, #7c3aed, transparent);"></div>

    <div class="max-w-6xl mx-auto px-6 relative z-10">
        {{-- Header --}}
        <div class="text-center max-w-lg mx-auto mb-16 reveal">
            <span class="inline-block px-3 py-1.5 rounded-full text-xs font-semibold border mb-5"
                  style="background: rgba(139,92,246,0.15); border-color: rgba(139,92,246,0.3); color: #c4b5fd;">
                Cara Kerja
            </span>
            <h2 class="text-3xl lg:text-4xl font-extrabold text-white font-[var(--font-display)]">
                Titip barang hanya dalam 3 langkah.
            </h2>
            <p class="mt-3 text-sm leading-relaxed" style="color: rgba(255,255,255,0.42);">
                Dari pesan sampai barang aman, semua bisa kamu kontrol dari genggaman.
            </p>
        </div>

        <div class="relative">
            {{-- Connecting line --}}
            <div class="hidden lg:block absolute top-10 left-[calc(16.67%+40px)] right-[calc(16.67%+40px)] h-px"
                 style="background: linear-gradient(90deg, #7c3aed, #6366f1);"></div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
                @php
                    $steps = [
                        [
                            'icon'      => 'clipboard-list',
                            'title'     => 'Buat Pesanan',
                            'desc'      => 'Pilih jenis barang, jumlah, durasi penitipan, dan jadwal penjemputan.',
                            'highlight' => 'Mudah & cepat',
                        ],
                        [
                            'icon'      => 'truck',
                            'title'     => 'Barang Dijemput',
                            'desc'      => 'Tim RuTIP akan mengambil barang langsung dari lokasi kamu atau kamu bisa mengantarkannya sendiri.',
                            'highlight' => 'Seluruh Malang',
                        ],
                        [
                            'icon'      => 'layout-dashboard',
                            'title'     => 'Simpan & Pantau',
                            'desc'      => 'Barang disimpan dengan aman dan kamu dapat memantau statusnya melalui platform RuTIP.',
                            'highlight' => 'Notif real-time',
                        ],
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
                            ✓ {{ $step['highlight'] }}
                        </span>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="reveal mt-14 text-center">
            <a href="{{ route('login') }}" class="inline-flex items-center gap-2 px-6 py-3 rounded-xl font-semibold text-white text-sm transition-all duration-200 hover:scale-105"
               style="background: linear-gradient(135deg, #7c3aed, #6366f1); box-shadow: 0 6px 20px rgba(124,58,237,0.3);">
                Mulai Titip Sekarang <x-lucide-arrow-right class="w-4 h-4" />
            </a>
        </div>
>>>>>>> hostinger/main
    </div>
    <ol class="steps">
      <li>
        <span class="num">01</span>
        <h3>Pesan &amp; pilih durasi</h3>
        <p>Isi jenis dan jumlah barang, tanggal jemput, dan berapa lama mau dititip. Harga langsung kelihatan.</p>
      </li>
      <li>
        <span class="num">02</span>
        <h3>Pilih opsi penjemputan</h3>
        <p>Tim Ruru bisa langsung menjemput barangmu, atau kamu antar sendiri ke gudang tim Ruru.</p>
      </li>
      <li>
        <span class="num">03</span>
        <h3>Simpan, pantau, ambil</h3>
        <p>Barang disimpan di gudang terkunci. Cek statusnya kapan saja, dan atur pengantaran saat kamu balik.</p>
      </li>
    </ol>
  </div>
</section>
