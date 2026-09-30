<<<<<<< HEAD
<section class="section" id="faq" style="padding-top:0">
  <div class="wrap faq">
    <div class="faq-intro">
      <h2 class="h2-sm">Yang sering ditanyain.</h2>
      <p class="lead" style="font-size:17px">Belum ketemu jawabannya? <a href="https://wa.me/6285121091134" target="_blank" rel="noopener">Tanya langsung lewat WhatsApp</a>.</p>
=======
{{-- FAQ Section --}}
<section id="faq" class="py-24 relative overflow-hidden" style="background: linear-gradient(180deg, #100720 0%, #0c0618 100%);">
    <div class="absolute top-0 inset-x-0 h-px" style="background: linear-gradient(90deg, transparent, rgba(139,92,246,0.25), transparent);"></div>
    <div class="absolute bottom-0 left-1/2 -translate-x-1/2 w-[400px] h-48 opacity-10 blur-3xl pointer-events-none" style="background: radial-gradient(ellipse, #6366f1, transparent);"></div>

    <div class="max-w-6xl mx-auto px-6 relative z-10">
        {{-- Header --}}
        <div class="text-center max-w-lg mx-auto mb-12 reveal">
            <span class="inline-block px-3 py-1.5 rounded-full text-xs font-semibold border mb-5"
                  style="background: rgba(139,92,246,0.15); border-color: rgba(139,92,246,0.3); color: #c4b5fd;">
                FAQ
            </span>
            <h2 class="text-3xl lg:text-4xl font-extrabold text-white font-[var(--font-display)]">
                Ada yang mau ditanyain?
            </h2>
            <p class="mt-3 text-sm leading-relaxed" style="color: rgba(255,255,255,0.42);">
                Pertanyaan yang sering ditanyakan seputar layanan RuTIP.
            </p>
        </div>

        {{-- Accordion --}}
        <div class="max-w-2xl mx-auto space-y-2.5">
            @php
                $faqs = [
                    [
                        'q' => 'Apakah saya tetap perlu membayar kos saat pulang kampung?',
                        'a' => 'Tidak. Dengan menyimpan barang di RuTIP, kamu bisa mengosongkan kamar kos dan menghemat biaya selama tidak berada di Malang.',
                    ],
                    [
                        'q' => 'Barang apa saja yang bisa dititipkan?',
                        'a' => 'Kardus, koper, perlengkapan kos, buku, peralatan elektronik, dan berbagai barang pribadi non-berbahaya.',
                    ],
                    [
                        'q' => 'Apakah RuTIP menyediakan layanan jemput barang?',
                        'a' => 'Ya. Kamu bisa memilih layanan jemput barang oleh tim RuTIP atau mengantarnya sendiri.',
                    ],
                    [
                        'q' => 'Bagaimana saya mengetahui kondisi barang saya?',
                        'a' => 'Status penitipan dan dokumentasi barang dapat dipantau melalui akun RuTIP.',
                    ],
                ];
            @endphp

            @foreach ($faqs as $i => $faq)
                <div class="faq-item reveal rounded-2xl overflow-hidden cursor-pointer transition-all duration-200"
                     style="background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.08); transition-delay: {{ $i * 70 }}ms;">
                    <button type="button" class="faq-trigger w-full flex items-center justify-between p-5 gap-4 text-left">
                        <span class="font-semibold text-sm font-[var(--font-display)]" style="color: rgba(255,255,255,0.82);">
                            {{ $faq['q'] }}
                        </span>
                        <span class="faq-icon shrink-0 w-6 h-6 rounded-full flex items-center justify-center transition-transform duration-200"
                              style="background: rgba(255,255,255,0.08); color: rgba(255,255,255,0.4);">
                            <x-lucide-plus class="w-3.5 h-3.5" />
                        </span>
                    </button>
                    <div class="faq-panel overflow-hidden transition-all duration-200" style="height: 0px;">
                        <p class="px-5 pb-5 text-sm leading-relaxed" style="color: rgba(255,255,255,0.48);">{{ $faq['a'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>

        <p class="reveal text-sm mt-8 text-center max-w-2xl mx-auto" style="color: rgba(255,255,255,0.32);">
            Masih ada pertanyaan?
            <a href="https://wa.me/6285121091134" target="_blank" rel="noopener noreferrer"
               class="transition-colors hover:underline underline-offset-2 font-medium" style="color: #a78bfa;">
                Chat kami di WhatsApp
            </a>
        </p>
>>>>>>> hostinger/main
    </div>
    <div class="faq-list">
      <details open>
        <summary>Saya masih perlu bayar kos selama pulang kampung?<span class="ic" aria-hidden="true">+</span></summary>
        <p>Nggak perlu. Barangmu kami simpan, jadi kamar kos bisa kamu lepas selama libur.</p>
      </details>
      <details>
        <summary>Barang apa saja yang bisa dititipkan?<span class="ic" aria-hidden="true">+</span></summary>
        <p>Kardus, koper, buku, kipas, rak, dan elektronik kecil. Lengkapi daftar barang yang tidak diterima di halaman pesanan.</p>
      </details>
      <details>
        <summary>Apakah RuangTitip bisa jemput ke kos?<span class="ic" aria-hidden="true">+</span></summary>
        <p>Bisa, untuk area Malang. Pilih tanggal jemput saat memesan.</p>
      </details>
      <details>
        <summary>Gimana saya tahu kondisi barang saya?<span class="ic" aria-hidden="true">+</span></summary>
        <p>Tiap barang difoto saat diterima. Status dan fotonya bisa kamu cek di platform RuangTitip.</p>
      </details>
    </div>
  </div>
</section>
