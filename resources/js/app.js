/* ==========================================================================
   RUTIP Landing Page — vanilla JS interactions
   Replaces the React/framer-motion behaviors from the Figma export:
     - Navbar scroll state (transparent -> blurred)
     - Mobile nav toggle
     - Hero typewriter headline rotation
     - Scroll-reveal animations (whileInView replacement)
     - FAQ accordion
     - Testimonial carousel pagination
   ========================================================================== */

document.addEventListener('DOMContentLoaded', () => {

    /* ----------------------------------------------------------------
     * Navbar: transparent -> blurred on scroll, mobile menu toggle
     * ---------------------------------------------------------------- */
    const navbar = document.getElementById('navbar');
    const mobileToggle = document.getElementById('navbar-mobile-toggle');
    const mobileMenu = document.getElementById('navbar-mobile-menu');
    const iconMenu = document.getElementById('navbar-icon-menu');
    const iconClose = document.getElementById('navbar-icon-close');

    if (navbar) {
        const onScroll = () => {
            const scrolled = window.scrollY > 50;
            if (scrolled) {
                navbar.style.background = 'rgba(14,8,28,0.95)';
                navbar.style.backdropFilter = 'blur(20px)';
                navbar.style.borderBottom = '1px solid rgba(139,92,246,0.18)';
                navbar.style.boxShadow = '0 4px 24px rgba(0,0,0,0.3)';
            } else {
                navbar.style.background = 'transparent';
                navbar.style.backdropFilter = 'none';
                navbar.style.borderBottom = '1px solid transparent';
                navbar.style.boxShadow = 'none';
            }
        };
        window.addEventListener('scroll', onScroll, { passive: true });
        onScroll();
    }

    if (mobileToggle && mobileMenu) {
        mobileToggle.addEventListener('click', () => {
            const isOpen = mobileMenu.classList.contains('block');
            if (isOpen) {
                mobileMenu.classList.remove('block');
                mobileMenu.classList.add('hidden');
                iconMenu.classList.remove('hidden');
                iconClose.classList.add('hidden');
            } else {
                mobileMenu.classList.remove('hidden');
                mobileMenu.classList.add('block');
                iconMenu.classList.add('hidden');
                iconClose.classList.remove('hidden');
            }
        });

        mobileMenu.querySelectorAll('a').forEach((link) => {
            link.addEventListener('click', () => {
                mobileMenu.classList.remove('block');
                mobileMenu.classList.add('hidden');
                iconMenu.classList.remove('hidden');
                iconClose.classList.add('hidden');
            });
        });
    }

    /* ----------------------------------------------------------------
     * Hero typewriter headline rotation
     * ---------------------------------------------------------------- */
    const headlines = [
        { line1: 'Pergi Magang.', line2: 'Bukan Bayar Kos.' },
        { line1: 'Titip Barang.', line2: 'Bukan Sewa Ruang.' },
        { line1: 'KKN Tenang.', line2: 'Barang Aman.' },
    ];

    const line1El = document.getElementById('hero-line-1');
    const line2El = document.getElementById('hero-line-2');
    const cursor1 = document.getElementById('hero-cursor-1');
    const cursor2 = document.getElementById('hero-cursor-2');

    if (line1El && line2El) {
        let headlineIdx = 0;
        let displayed1 = '';
        let displayed2 = '';
        let phase = 'typing1'; // 'typing1' | 'typing2' | 'waiting'

        const tick = () => {
            const hl = headlines[headlineIdx];
            let delay = 60;

            if (phase === 'typing1') {
                if (displayed1.length < hl.line1.length) {
                    displayed1 = hl.line1.slice(0, displayed1.length + 1);
                    line1El.textContent = displayed1;
                    delay = 60;
                } else {
                    phase = 'typing2';
                    delay = 200;
                }
                cursor1.style.display = 'inline';
                cursor2.style.display = 'none';
            } else if (phase === 'typing2') {
                if (displayed2.length < hl.line2.length) {
                    displayed2 = hl.line2.slice(0, displayed2.length + 1);
                    line2El.textContent = displayed2;
                    delay = 60;
                } else {
                    phase = 'waiting';
                    delay = 2600;
                }
                cursor1.style.display = 'none';
                cursor2.style.display = 'inline';
            } else {
                displayed1 = '';
                displayed2 = '';
                line1El.textContent = '';
                line2El.textContent = '';
                headlineIdx = (headlineIdx + 1) % headlines.length;
                phase = 'typing1';
                delay = 400;
                cursor1.style.display = 'none';
                cursor2.style.display = 'none';
            }

            setTimeout(tick, delay);
        };

        tick();
    }

    /* ----------------------------------------------------------------
     * Scroll-reveal: fade + slide up when entering viewport
     * ---------------------------------------------------------------- */
    const revealEls = document.querySelectorAll('.reveal');
    if ('IntersectionObserver' in window && revealEls.length) {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-visible');
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.1, rootMargin: '0px 0px -60px 0px' });

        revealEls.forEach((el) => observer.observe(el));
    } else {
        revealEls.forEach((el) => el.classList.add('is-visible'));
    }

    /* ----------------------------------------------------------------
     * FAQ accordion
     * ---------------------------------------------------------------- */
    document.querySelectorAll('.faq-item').forEach((item) => {
        const trigger = item.querySelector('.faq-trigger');
        const panel = item.querySelector('.faq-panel');
        const icon = item.querySelector('.faq-icon');

        trigger.addEventListener('click', () => {
            const isOpen = item.classList.contains('faq-open');

            if (isOpen) {
                item.classList.remove('faq-open');
                panel.style.height = '0px';
                icon.style.transform = 'rotate(0deg)';
                item.style.background = 'rgba(255,255,255,0.04)';
                item.style.borderColor = 'rgba(255,255,255,0.08)';
                trigger.querySelector('span').style.color = 'rgba(255,255,255,0.82)';
                icon.style.background = 'rgba(255,255,255,0.08)';
                icon.style.color = 'rgba(255,255,255,0.4)';
            } else {
                item.classList.add('faq-open');
                panel.style.height = panel.scrollHeight + 'px';
                icon.style.transform = 'rotate(45deg)';
                item.style.background = 'rgba(124,58,237,0.12)';
                item.style.borderColor = 'rgba(139,92,246,0.4)';
                trigger.querySelector('span').style.color = '#c4b5fd';
                icon.style.background = 'rgba(139,92,246,0.3)';
                icon.style.color = '#c4b5fd';
            }
        });
    });

    /* ----------------------------------------------------------------
     * Testimonial carousel
     * ---------------------------------------------------------------- */
    const carousel = document.getElementById('testimoni-carousel');
    if (carousel) {
        const pages = carousel.querySelectorAll('.testimoni-page');
        const dots = carousel.querySelectorAll('.testimoni-dot');
        const prevBtn = document.getElementById('testimoni-prev');
        const nextBtn = document.getElementById('testimoni-next');
        const totalPages = pages.length;
        let currentPage = 0;

        const render = () => {
            pages.forEach((page, i) => {
                page.classList.toggle('hidden', i !== currentPage);
            });
            dots.forEach((dot, i) => {
                if (i === currentPage) {
                    dot.style.width = '20px';
                    dot.style.background = '#a78bfa';
                } else {
                    dot.style.width = '8px';
                    dot.style.background = 'rgba(255,255,255,0.18)';
                }
            });
            prevBtn.disabled = currentPage === 0;
            nextBtn.disabled = currentPage === totalPages - 1;
            prevBtn.style.opacity = currentPage === 0 ? '0.25' : '1';
            nextBtn.style.opacity = currentPage === totalPages - 1 ? '0.25' : '1';
        };

        prevBtn.addEventListener('click', () => {
            currentPage = Math.max(0, currentPage - 1);
            render();
        });
        nextBtn.addEventListener('click', () => {
            currentPage = Math.min(totalPages - 1, currentPage + 1);
            render();
        });
        dots.forEach((dot, i) => {
            dot.addEventListener('click', () => {
                currentPage = i;
                render();
            });
        });

        render();
    }

});
