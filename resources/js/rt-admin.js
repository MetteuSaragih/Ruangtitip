/**
 * Perilaku shell admin: menu mobile, menu akun, dialog (data-open / data-close),
 * konfirmasi (data-confirm), dan toast dari flash session.
 */
(function () {
    'use strict';

    const $ = (s, c = document) => c.querySelector(s);
    const $$ = (s, c = document) => [...c.querySelectorAll(s)];

    // Menu mobile: laci dari kiri
    const menuT = $('#adminMenuT');
    const sideEl = $('#adminSide');
    const mq = window.matchMedia('(max-width: 860px)');
    function setNav(open, focus) {
        document.body.classList.toggle('nav-open', open);
        if (menuT) {
            menuT.setAttribute('aria-expanded', String(open));
            menuT.setAttribute('aria-label', open ? 'Tutup menu' : 'Buka menu');
        }
        const scrim = $('#adminScrim');
        if (scrim) scrim.hidden = !open;
        if (focus && menuT && !open) menuT.focus({ preventScroll: true });
    }
    if (menuT) menuT.addEventListener('click', () => setNav(!document.body.classList.contains('nav-open'), true));
    const sideX = $('#adminSideX');
    if (sideX) sideX.addEventListener('click', () => setNav(false, true));
    const scrimEl = $('#adminScrim');
    if (scrimEl) scrimEl.addEventListener('click', () => setNav(false, true));
    if (sideEl) sideEl.addEventListener('click', (e) => { if (mq.matches && e.target.closest('a[href]')) setNav(false); });
    if (mq.addEventListener) mq.addEventListener('change', () => setNav(false));
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && document.body.classList.contains('nav-open')) setNav(false, true);
    });
    document.addEventListener('click', (e) => {
        const who = $('.who[open]');
        if (who && !who.contains(e.target)) who.removeAttribute('open');
    });

    // Dialog
    document.addEventListener('click', (e) => {
        const open = e.target.closest('[data-open]');
        if (open) {
            const d = document.getElementById(open.dataset.open);
            if (d && typeof d.showModal === 'function') {
                e.preventDefault();
                d.showModal();
            }
            return;
        }
        const close = e.target.closest('[data-close]');
        if (close) {
            const d = close.closest('dialog');
            if (d) d.close();
        }
    });
    $$('dialog.dlg').forEach((d) => {
        d.addEventListener('click', (e) => { if (e.target === d) d.close(); });
    });

    // Konfirmasi: <form data-confirm="..."> atau <button data-confirm="..."> di dalam form.
    const dlg = $('#aConfirm');
    let pending = null;
    document.addEventListener('submit', (e) => {
        const form = e.target;
        const submitter = e.submitter;
        const text = (submitter && submitter.dataset.confirm) || form.dataset.confirm;
        if (!text || !dlg || typeof dlg.showModal !== 'function' || form.dataset.confirmed === '1') return;
        e.preventDefault();
        pending = { form, submitter };
        $('#aConfirmP').textContent = text;
        const title = (submitter && submitter.dataset.confirmTitle) || form.dataset.confirmTitle || 'Konfirmasi';
        $('#aConfirmT').textContent = title;
        const danger = (submitter && submitter.dataset.danger !== undefined) || form.dataset.danger !== undefined;
        const ok = $('#aConfirmOk');
        ok.className = 'btn ' + (danger ? 'btn-danger' : 'btn-primary');
        ok.textContent = (submitter && submitter.dataset.confirmOk) || form.dataset.confirmOk || 'Lanjutkan';
        dlg.showModal();
    });
    const okBtn = $('#aConfirmOk');
    if (okBtn) {
        okBtn.addEventListener('click', () => {
            if (!pending) return;
            const { form, submitter } = pending;
            pending = null;
            dlg.close();
            form.dataset.confirmed = '1';
            if (typeof form.requestSubmit === 'function') form.requestSubmit(submitter || undefined);
            else form.submit();
        });
        dlg.addEventListener('close', () => { pending = null; });
    }

    // Tutup dialog form setelah dikirim ulang dengan error: buka kembali dialog yang bertanda data-reopen.
    const reopen = $('dialog[data-reopen]');
    if (reopen && typeof reopen.showModal === 'function') reopen.showModal();

    // Toast dari flash session: <div id="adminToast" data-msg="..." data-type="err">
    const toast = $('#adminToast');
    if (toast && toast.dataset.msg) {
        toast.textContent = toast.dataset.msg;
        if (toast.dataset.type === 'err') toast.classList.add('err');
        requestAnimationFrame(() => toast.classList.add('show'));
        setTimeout(() => toast.classList.remove('show'), 4500);
    }
})();
