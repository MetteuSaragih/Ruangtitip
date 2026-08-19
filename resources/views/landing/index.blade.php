<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RUTIP - Titip Barangmu, Simpan Uangmu</title>

    <!-- SEO & Open Graph -->
    <meta name="description" content="Layanan penitipan barang untuk mahasiswa. Aman, terjangkau, dan ada antar-jemput langsung ke kos kamu di Malang.">
    <meta name="keywords" content="ruang titip malang, titip barang mahasiswa, packing kos, preloved malang, RUTIP">
    <meta property="og:title" content="RUTIP - Titip Barangmu, Simpan Uangmu">
    <meta property="og:description" content="Layanan penitipan barang untuk mahasiswa. Aman, terjangkau, antar-jemput ke kos.">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url('/') }}">
    <meta property="og:image" content="{{ asset('images/logo-rutip-putih.png') }}">
    <meta property="og:site_name" content="RUTIP">
    <meta property="og:locale" content="id_ID">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="RUTIP - Titip Barangmu, Simpan Uangmu">
    <meta name="twitter:description" content="Layanan penitipan barang untuk mahasiswa di Malang. Aman, terjangkau, antar-jemput ke kos.">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    
    <!-- Memanggil Tailwind CSS & JS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        :root { --font-display:'Plus Jakarta Sans',sans-serif; --font-body:'Inter',sans-serif; }
        body { font-family: var(--font-body); }
        .font-display { font-family: var(--font-display); }

        /* ── Scroll reveal ── */
        .reveal {
            opacity: 0;
            transform: translateY(28px);
            transition: opacity 0.6s ease, transform 0.6s ease;
        }
        .reveal.visible {
            opacity: 1;
            transform: translateY(0);
        }

        /* ── Page loader bar ── */
        #rt-page-bar {
            position: fixed; top: 0; left: 0; z-index: 9999;
            height: 3px; width: 0%;
            background: linear-gradient(90deg, #7c3aed, #a78bfa, #6366f1);
            transition: width 0.3s ease, opacity 0.4s ease;
            box-shadow: 0 0 12px rgba(124,58,237,0.7);
        }
    </style>
</head>
<body>

    <div class="min-h-screen overflow-x-hidden" style="background: #0c0618;">
        {{-- Navbar khusus Landing Page --}}
        @include('landing.partials.navbar')
        
        {{-- Konten Landing Page --}}
        @include('landing.partials.hero')
        @include('landing.partials.stats')
        @include('landing.partials.problem')
        @include('landing.partials.solution')
        @include('landing.partials.how-it-works')
        @include('landing.partials.trust')
        @include('landing.partials.testimoni')
        @include('landing.partials.faq')
        @include('landing.partials.tentang-kami')
        @include('landing.partials.footer')
    </div>

{{-- Page loader bar --}}
<div id="rt-page-bar"></div>

<script>
(function () {

/* ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
   1. PARTICLES (hero background)
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ */
const canvas = document.getElementById('rt-particles');
if (canvas) {
    const ctx = canvas.getContext('2d');
    let W, H, pts;
    function initParticles() {
        W = canvas.width  = canvas.offsetWidth;
        H = canvas.height = canvas.offsetHeight;
        pts = Array.from({ length: 60 }, () => ({
            x:  Math.random() * W,
            y:  Math.random() * H,
            r:  Math.random() * 1.5 + 0.3,
            vx: (Math.random() - 0.5) * 0.3,
            vy: (Math.random() - 0.5) * 0.3,
            a:  Math.random() * 0.35 + 0.07,
        }));
    }
    initParticles();
    window.addEventListener('resize', initParticles);

    (function drawParticles() {
        ctx.clearRect(0, 0, W, H);
        for (let i = 0; i < pts.length; i++) {
            const p = pts[i];
            p.x += p.vx; p.y += p.vy;
            if (p.x < 0) p.x = W; if (p.x > W) p.x = 0;
            if (p.y < 0) p.y = H; if (p.y > H) p.y = 0;
            ctx.beginPath();
            ctx.arc(p.x, p.y, p.r, 0, Math.PI * 2);
            ctx.fillStyle = 'rgba(167,139,250,' + p.a + ')';
            ctx.fill();
        }
        // Draw connecting lines between nearby particles
        for (let i = 0; i < pts.length; i++) {
            for (let j = i + 1; j < pts.length; j++) {
                const dx = pts[i].x - pts[j].x;
                const dy = pts[i].y - pts[j].y;
                const d  = Math.sqrt(dx * dx + dy * dy);
                if (d < 115) {
                    ctx.beginPath();
                    ctx.moveTo(pts[i].x, pts[i].y);
                    ctx.lineTo(pts[j].x, pts[j].y);
                    ctx.strokeStyle = 'rgba(124,58,237,' + (0.09 * (1 - d / 115)) + ')';
                    ctx.lineWidth = 0.5;
                    ctx.stroke();
                }
            }
        }
        requestAnimationFrame(drawParticles);
    })();
}

/* ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
   2. TYPING EFFECT (hero headline)
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ */
const l1 = document.getElementById('rt-hero-l1');
const l2 = document.getElementById('rt-hero-l2');
if (l1 && l2) {
    const txt1 = l1.textContent.trim();
    const txt2 = l2.textContent.trim();
    l1.textContent = '';
    l2.textContent = '';

    const heroReveal = document.querySelectorAll('.hero-reveal');
    heroReveal.forEach(el => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(18px)';
        el.style.transition = 'opacity 0.55s ease, transform 0.55s ease';
    });

    function typeOut(el, text, speed, done) {
        let i = 0;
        const iv = setInterval(() => {
            el.textContent += text[i++];
            if (i >= text.length) { clearInterval(iv); if (done) setTimeout(done, 90); }
        }, speed);
    }

    setTimeout(() => {
        typeOut(l1, txt1, 55, () => {
            typeOut(l2, txt2, 55, () => {
                heroReveal.forEach((el, idx) => {
                    setTimeout(() => {
                        el.style.opacity = '1';
                        el.style.transform = 'translateY(0)';
                    }, idx * 140);
                });
            });
        });
    }, 350);
}

/* ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
   3. 3D CARD TILT
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ */
document.querySelectorAll('.rt-tilt').forEach(card => {
    card.style.willChange = 'transform';
    card.addEventListener('mouseenter', () => {
        card.style.transition = 'transform 0.12s ease, box-shadow 0.12s ease';
    });
    card.addEventListener('mousemove', e => {
        const r  = card.getBoundingClientRect();
        const dx = (e.clientX - (r.left + r.width  / 2)) / (r.width  / 2);
        const dy = (e.clientY - (r.top  + r.height / 2)) / (r.height / 2);
        card.style.transform  = 'perspective(700px) rotateY(' + (dx * 10) + 'deg) rotateX(' + (-dy * 10) + 'deg) translateY(-6px) scale(1.018)';
        card.style.boxShadow  = '0 20px 50px rgba(124,58,237,0.28)';
    });
    card.addEventListener('mouseleave', () => {
        card.style.transition = 'transform 0.5s ease, box-shadow 0.5s ease';
        card.style.transform  = '';
        card.style.boxShadow  = '';
    });
});

/* ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
   4. SCROLL REVEAL
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ */
const revealIO = new IntersectionObserver(entries => {
    entries.forEach(e => {
        if (e.isIntersecting) { e.target.classList.add('visible'); revealIO.unobserve(e.target); }
    });
}, { threshold: 0.1 });
document.querySelectorAll('.reveal').forEach(el => revealIO.observe(el));

/* ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
   5. PAGE TRANSITION OVERLAY
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ */
const overlay = document.createElement('div');
overlay.style.cssText = 'position:fixed;inset:0;background:#0c0618;z-index:99998;opacity:1;pointer-events:none;transition:opacity 0.45s ease;';
document.body.appendChild(overlay);
requestAnimationFrame(() => requestAnimationFrame(() => { overlay.style.opacity = '0'; }));

document.querySelectorAll('a[href]').forEach(a => {
    const href = a.getAttribute('href');
    if (!href || href.startsWith('#') || href.startsWith('http') || href.startsWith('//') || a.target === '_blank') return;
    a.addEventListener('click', () => { overlay.style.opacity = '0.85'; });
});

/* ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
   6. PAGE LOADER BAR
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ */
const bar = document.getElementById('rt-page-bar');
if (bar) {
    let barTimer;
    document.querySelectorAll('a[href]').forEach(a => {
        const href = a.getAttribute('href');
        if (!href || href.startsWith('#') || href.startsWith('http') || href.startsWith('//') || a.target === '_blank') return;
        a.addEventListener('click', () => {
            bar.style.width = '0%'; bar.style.opacity = '1';
            clearTimeout(barTimer);
            setTimeout(() => { bar.style.width = '70%'; }, 10);
            barTimer = setTimeout(() => { bar.style.width = '95%'; }, 400);
        });
    });
    window.addEventListener('pageshow', () => {
        bar.style.width = '100%';
        setTimeout(() => { bar.style.opacity = '0'; bar.style.width = '0%'; }, 300);
    });
}

})();
</script>
</body>
</html>