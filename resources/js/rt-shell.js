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

    // ----- Laci menu (tablet dan mobile) -----
    const burger = $('#rtBurger');
    const scrim = $('#rtScrim');
    const drawerClose = $('#rtDrawerClose');
    const mobileMq = window.matchMedia('(max-width: 960px)');

    function setDrawer(open, focus) {
        root.classList.toggle('nav-open', open);
        if (scrim) scrim.hidden = !open;
        if (burger) {
            burger.setAttribute('aria-expanded', String(open));
            burger.setAttribute('aria-label', open ? 'Tutup menu' : 'Buka menu');
        }
        if (side) {
            if (mobileMq.matches) side.setAttribute('aria-hidden', String(!open));
            else side.removeAttribute('aria-hidden');
        }
        if (focus && open && drawerClose) drawerClose.focus({ preventScroll: true });
        if (focus && !open && burger && mobileMq.matches) burger.focus({ preventScroll: true });
    }

    if (side) {
        setDrawer(false);
        if (burger) burger.addEventListener('click', () => setDrawer(!root.classList.contains('nav-open'), true));
        if (drawerClose) drawerClose.addEventListener('click', () => setDrawer(false, true));
        if (scrim) scrim.addEventListener('click', () => setDrawer(false, true));
        side.addEventListener('click', (e) => {
            if (e.target.closest('a[href]') && mobileMq.matches) setDrawer(false);
        });
        const onMq = () => setDrawer(false);
        if (mobileMq.addEventListener) mobileMq.addEventListener('change', onMq);
    }

    function showTip(el) {
        if (!tip || mobileMq.matches) return;
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
            if (root.classList.contains('nav-open')) setDrawer(false, true);
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

    // Dialog "Berhasil masuk" setelah login (OTP atau Google).
    const welcome = $('#rtWelcome');
    if (welcome && typeof welcome.showModal === 'function') welcome.showModal();

    // Konfirmasi sebelum keluar: berlaku untuk semua form POST ke /logout.
    const logoutDlg = $('#rtLogout');
    if (logoutDlg && typeof logoutDlg.showModal === 'function') {
        let pending = null;
        document.addEventListener('submit', (e) => {
            const form = e.target;
            if (!(form instanceof HTMLFormElement) || !/\/logout\/?$/.test(form.getAttribute('action') || '')) return;
            e.preventDefault();
            pending = form;
            logoutDlg.showModal();
        });
        $('#rtLogoutNo').addEventListener('click', () => { pending = null; logoutDlg.close(); });
        $('#rtLogoutYes').addEventListener('click', () => {
            const form = pending;
            logoutDlg.close();
            if (form) form.submit();
        });
    }

    // Toast dari flash session: <div id="rtToast" data-msg="...">
    const toast = $('#rtToast');
    if (toast && toast.dataset.msg) {
        toast.textContent = toast.dataset.msg;
        requestAnimationFrame(() => toast.classList.add('show'));
        setTimeout(() => toast.classList.remove('show'), 4500);
    }
})();
