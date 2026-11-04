{{--
    Skrip global sekali pakai: window.renderCourierGroups(container, pricing, onSelect)
    Mengelompokkan daftar kurir per perusahaan (JNE, J&T, SiCepat, dst) jadi panel
    collapsible, supaya daftar kurir tidak terlalu panjang ke bawah.
    Hanya tema terang (kelas .pmt-* di resources/css/rt/legacy.css). Argumen ke-4 (theme)
    masih diterima demi kompatibilitas tetapi diabaikan.
    Aman di-include berulang kali di halaman yang sama (dijaga oleh window.__courierGroupsReady).
--}}
@once
<script>
if (! window.__courierGroupsReady) {
    window.__courierGroupsReady = true;

    window.renderCourierGroups = function (container, pricing, onSelect) {
        if (!container) { return; }
        pricing = Array.isArray(pricing) ? pricing : [];

        const esc = (s) => String(s == null ? '' : s).replace(/[&<>"']/g, (ch) => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[ch]));
        const rupiah = (n) => 'Rp ' + Number(n || 0).toLocaleString('id-ID');

        if (!pricing.length) {
            container.className = 'pmt-list';
            container.innerHTML = '<div class="pmt-loading" role="status">Belum ada kurir yang tersedia untuk tujuan ini.</div>';
            return;
        }

        const groups = [];
        const groupIndex = {};
        pricing.forEach((c, i) => {
            const g = c.courier_name || 'Lainnya';
            if (!(g in groupIndex)) {
                groupIndex[g] = groups.length;
                groups.push({ name: g, items: [] });
            }
            groups[groupIndex[g]].items.push(i);
        });

        container.className = 'pmt-list';
        container.innerHTML = groups.map((g, gi) => `
            <div class="pmt-group courier-group" data-gi="${gi}">
                <button type="button" class="group-trigger pmt-group-head" aria-expanded="false">
                    <span class="pmt-group-title">${esc(g.name)}</span>
                    <span class="pmt-group-right">
                        <span class="pmt-count">${g.items.length}</span>
                        <span class="group-icon pmt-chev" aria-hidden="true">
                            <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
                        </span>
                    </span>
                </button>
                <div class="group-panel" style="display:none;">
                    <div class="pmt-panel">
                        ${g.items.map(i => `
                            <button type="button" data-i="${i}" class="courier-opt pmt-opt" aria-pressed="false">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="var(--color-primary)" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" class="shrink-0" aria-hidden="true"><path d="M3 7h11v10H3zM14 10h4l3 3v4h-7"/><circle cx="7" cy="18" r="1.6"/><circle cx="17" cy="18" r="1.6"/></svg>
                                <span class="pmt-opt-title">${esc(pricing[i].courier_service_name || pricing[i].courier_name)}<br><span class="pmt-opt-sub">${esc(pricing[i].duration || '')}</span></span>
                                <span style="font-weight:700;color:var(--color-primary);white-space:nowrap;">${rupiah(pricing[i].price)}</span>
                                <span class="radio pmt-radio" aria-hidden="true">
                                    <svg class="check-icon" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" style="display:none;"><polyline points="20 6 9 17 4 12"/></svg>
                                </span>
                            </button>
                        `).join('')}
                    </div>
                </div>
            </div>
        `).join('');

        container.querySelectorAll('.group-trigger').forEach(trigger => {
            trigger.addEventListener('click', () => {
                const panel = trigger.nextElementSibling;
                const icon = trigger.querySelector('.group-icon');
                const open = panel.style.display !== 'none';
                panel.style.display = open ? 'none' : 'block';
                trigger.setAttribute('aria-expanded', open ? 'false' : 'true');
                if (icon) { icon.style.transform = open ? 'rotate(0deg)' : 'rotate(180deg)'; }
            });
        });

        container.querySelectorAll('.courier-opt').forEach(opt => {
            opt.addEventListener('click', (e) => {
                e.stopPropagation();
                const c = pricing[parseInt(opt.dataset.i, 10)];
                if (!c) { return; }

                container.querySelectorAll('.courier-opt').forEach(o => {
                    const selected = o === opt;
                    o.classList.toggle('on', selected);
                    o.setAttribute('aria-pressed', selected ? 'true' : 'false');
                    const check = o.querySelector('.check-icon');
                    if (check) { check.style.display = selected ? 'block' : 'none'; }
                });

                if (typeof onSelect === 'function') { onSelect(c); }
            });
        });

        if (groups.length === 1) {
            const first = container.querySelector('.group-trigger');
            if (first) { first.click(); }
        }
    };
}
</script>
@endonce
