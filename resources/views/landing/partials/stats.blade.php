{{-- Stats Section --}}
<section class="py-14 relative overflow-hidden" style="background:#0c0618;">
    <div class="absolute top-0 inset-x-0 h-px" style="background:linear-gradient(90deg,transparent,rgba(139,92,246,0.25),transparent);"></div>
    <div class="absolute bottom-0 inset-x-0 h-px" style="background:linear-gradient(90deg,transparent,rgba(99,102,241,0.15),transparent);"></div>

    <div class="max-w-6xl mx-auto px-6 relative z-10">
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6">
            @foreach ([
                ['value' => 150, 'suffix' => '+', 'label' => 'Penitip Aktif', 'sub' => 'Mahasiswa Brawijaya & sekitarnya', 'color' => '#a78bfa', 'glow' => 'rgba(167,139,250,0.15)', 'border' => 'rgba(167,139,250,0.2)'],
                ['value' => 500, 'suffix' => '+', 'label' => 'Barang Tersimpan', 'sub' => 'Aman di gudang RUTIP', 'color' => '#818cf8', 'glow' => 'rgba(129,140,248,0.15)', 'border' => 'rgba(129,140,248,0.2)'],
                ['value' => 3,   'suffix' => '',  'label' => 'Layanan Unggulan', 'sub' => 'Titip · Packing · Preloved', 'color' => '#34d399', 'glow' => 'rgba(52,211,153,0.12)', 'border' => 'rgba(52,211,153,0.2)'],
                ['value' => 100, 'suffix' => '%', 'label' => 'Puas & Kembali', 'sub' => 'Berdasarkan survei pelanggan', 'color' => '#fbbf24', 'glow' => 'rgba(251,191,36,0.12)', 'border' => 'rgba(251,191,36,0.2)'],
            ] as $i => $s)
            <div class="rt-stat reveal rounded-2xl p-5 lg:p-7 text-center relative overflow-hidden"
                 style="background:rgba(255,255,255,0.035);border:1px solid {{ $s['border'] }};transition-delay:{{ $i * 90 }}ms;">
                <div class="absolute inset-0 pointer-events-none" style="background:radial-gradient(ellipse at 50% 0%,{{ $s['glow'] }},transparent 70%);"></div>
                <div class="rt-counter text-4xl lg:text-5xl font-extrabold mb-1 leading-none"
                     style="color:{{ $s['color'] }};"
                     data-target="{{ $s['value'] }}"
                     data-suffix="{{ $s['suffix'] }}">0{{ $s['suffix'] }}</div>
                <div class="text-sm font-bold text-white mb-1">{{ $s['label'] }}</div>
                <div class="text-[11px]" style="color:rgba(255,255,255,0.35);">{{ $s['sub'] }}</div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<script>
(function () {
    function animateCount(el) {
        const target = parseInt(el.dataset.target, 10);
        const suffix = el.dataset.suffix || '';
        const dur = 1400;
        const start = performance.now();
        function step(now) {
            const pct = Math.min((now - start) / dur, 1);
            const ease = 1 - Math.pow(1 - pct, 3);
            el.textContent = Math.round(ease * target) + suffix;
            if (pct < 1) requestAnimationFrame(step);
        }
        requestAnimationFrame(step);
    }

    const io = new IntersectionObserver(entries => {
        entries.forEach(e => {
            if (e.isIntersecting) {
                animateCount(e.target);
                io.unobserve(e.target);
            }
        });
    }, { threshold: 0.4 });

    document.querySelectorAll('.rt-counter').forEach(el => io.observe(el));
})();
</script>
