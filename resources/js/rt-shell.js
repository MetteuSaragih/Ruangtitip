/**
 * Perilaku shell pengguna: sidebar ciut (tersimpan), tooltip saat ciut,
 * menu pop notifikasi, dan toast dari flash session.
 */
(function () {
    'use strict';

    const root = document.documentElement;
    const $ = (s, c = document) => c.querySelector(s);
    const $$ = (s, c = document) => [...c.querySelectorAll(s)];

    const toggle = $('#rtSideToggle');
    const tip = $('#rtTip');
    const side = $('#rtSide');

    function setCollapsed(collapsed, persist) {
        root.classList.toggle('side-collapsed', collapsed);
        if (toggle) {
            const label = collapsed ? 'Buka menu' : 'Ciutkan menu';
            toggle.setAttribute('aria-expanded', String(!collapsed));
            toggle.setAttribute('aria-label', label);
            toggle.dataset.tip = label;
        }
        if (persist) {
            try {
                localStorage.setItem('rt-side', collapsed ? '1' : '0');
            } catch (e) {
                /* penyimpanan tidak tersedia, abaikan */
            }
        }
        if (tip) tip.classList.remove('show');
    }

    function showTip(el) {
        if (!tip) return;
        if (!root.classList.contains('side-collapsed') && !el.hasAttribute('data-tip-always')) return;
        tip.textContent = el.dataset.tip;
        if (el.dataset.kbd) {
            const k = document.createElement('kbd');
            k.textContent = el.dataset.kbd;
            tip.appendChild(k);
        }
        const r = el.getBoundingClientRect();
        tip.style.left = r.right + 12 + 'px';
        tip.style.top = r.top + r.height / 2 + 'px';
        tip.classList.add('show');
    }

    if (side) {
        setCollapsed(root.classList.contains('side-collapsed'), false);
        if (toggle) {
            toggle.addEventListener('click', () => setCollapsed(!root.classList.contains('side-collapsed'), true));
        }
        side.addEventListener('mouseover', (e) => {
            const el = e.target.closest('[data-tip]');
            if (el) showTip(el);
        });
        side.addEventListener('mouseout', (e) => {
            const el = e.target.closest('[data-tip]');
            if (el && !el.contains(e.relatedTarget) && tip) tip.classList.remove('show');
        });
        side.addEventListener('focusin', (e) => {
            const el = e.target.closest('[data-tip]');
            if (el) showTip(el);
        });
        side.addEventListener('focusout', () => tip && tip.classList.remove('show'));
        side.addEventListener('click', () => tip && tip.classList.remove('show'));
    }

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            if (tip) tip.classList.remove('show');
            $$('.rt-pop[open]').forEach((d) => d.removeAttribute('open'));
        }
        const typing = /input|textarea|select/i.test(document.activeElement.tagName);
        if (e.key === '[' && !e.ctrlKey && !e.metaKey && !typing && innerWidth > 960 && side) {
            e.preventDefault();
            setCollapsed(!root.classList.contains('side-collapsed'), true);
        }
    });

    // Tutup menu pop (notifikasi) saat klik di luar.
    document.addEventListener('click', (e) => {
        $$('.rt-pop[open]').forEach((d) => {
            if (!d.contains(e.target)) d.removeAttribute('open');
        });
    });

    // Toast dari flash session: <div id="rtToast" data-msg="...">
    const toast = $('#rtToast');
    if (toast && toast.dataset.msg) {
        toast.textContent = toast.dataset.msg;
        requestAnimationFrame(() => toast.classList.add('show'));
        setTimeout(() => toast.classList.remove('show'), 4500);
    }
})();
