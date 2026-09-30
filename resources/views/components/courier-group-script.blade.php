{{--
    Skrip global sekali pakai: window.renderCourierGroups(container, pricing, onSelect, theme)
    Mengelompokkan daftar kurir per perusahaan (JNE, J&T, SiCepat, dst) jadi panel
    collapsible, supaya daftar kurir tidak terlalu panjang ke bawah.
    theme: 'dark' (default, tema dashboard lama, pakai kelas Tailwind) atau
    'light' (tema cream RuangTitip baru, pakai kelas .pmt-* di layouts/ruang-titip.blade.php).
    Aman di-include berulang kali di halaman yang sama (dijaga oleh window.__courierGroupsReady).
--}}
@once
<script>
if (! window.__courierGroupsReady) {
    window.__courierGroupsReady = true;

    window.renderCourierGroups = function (container, pricing, onSelect, theme) {
        const light = theme === 'light';
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

        const rupiah = (n) => 'Rp ' + Number(n).toLocaleString('id-ID');

        container.className = light ? 'pmt-list' : 'space-y-2.5';
        container.innerHTML = groups.map((g, gi) => light ? `
            <div class="pmt-group courier-group" data-gi="${gi}">
                <button type="button" class="group-trigger pmt-group-head">
                    <span class="pmt-group-title">${g.name}</span>
                    <span class="pmt-group-right">
                        <span class="pmt-count">${g.items.length}</span>
                        <span class="group-icon pmt-chev">
                            <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
                        </span>
                    </span>
                </button>
                <div class="group-panel" style="display:none;">
                    <div class="pmt-panel">
                        ${g.items.map(i => `
                            <button type="button" data-i="${i}" class="courier-opt pmt-opt">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="var(--tape-dark)" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" class="shrink-0"><path d="M3 7h11v10H3zM14 10h4l3 3v4h-7"/><circle cx="7" cy="18" r="1.6"/><circle cx="17" cy="18" r="1.6"/></svg>
                                <span class="pmt-opt-title">${pricing[i].courier_service_name || pricing[i].courier_name}<br><span class="pmt-opt-sub">${pricing[i].duration || ''}</span></span>
                                <span style="font-weight:700;color:var(--tape-dark);white-space:nowrap;">${rupiah(pricing[i].price)}</span>
                                <span class="radio pmt-radio">
                                    <svg class="check-icon" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" style="display:none;"><polyline points="20 6 9 17 4 12"/></svg>
                                </span>
                            </button>
                        `).join('')}
                    </div>
                </div>
            </div>
        ` : `
            <div class="courier-group rounded-2xl overflow-hidden" style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.09);">
                <button type="button" class="group-trigger w-full flex items-center justify-between p-4 gap-3 text-left" data-gi="${gi}">
                    <span class="text-sm font-bold" style="color:#fff;">${g.name}</span>
                    <span class="flex items-center gap-2 shrink-0">
                        <span class="text-[10px] font-semibold px-2 py-0.5 rounded-full" style="background:rgba(124,58,237,0.15);color:#a78bfa;">${g.items.length}</span>
                        <span class="group-icon w-5 h-5 rounded-full flex items-center justify-center transition-transform duration-200" style="background:rgba(255,255,255,0.08);color:rgba(255,255,255,0.45);">
                            <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
                        </span>
                    </span>
                </button>
                <div class="group-panel" style="display:none;">
                    <div class="px-4 pb-4 space-y-2.5">
                        ${g.items.map(i => `
                            <button type="button" data-i="${i}"
                                    class="courier-opt w-full flex items-center gap-4 p-3.5 rounded-2xl text-left transition-all hover:scale-[1.01]"
                                    style="background:rgba(255,255,255,0.04);border:1.5px solid rgba(255,255,255,0.09);">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#a78bfa" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" class="shrink-0"><path d="M3 7h11v10H3zM14 10h4l3 3v4h-7"/><circle cx="7" cy="18" r="1.6"/><circle cx="17" cy="18" r="1.6"/></svg>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-bold" style="color:#fff;">${pricing[i].courier_service_name || pricing[i].courier_name}</p>
                                    <p class="text-xs" style="color:rgba(255,255,255,0.45);">${pricing[i].duration || ''}</p>
                                </div>
                                <p class="text-sm font-bold shrink-0" style="color:#7c3aed;">${rupiah(pricing[i].price)}</p>
                                <div class="radio w-5 h-5 rounded-full border-2 shrink-0 flex items-center justify-center" style="border-color:rgba(255,255,255,0.2);">
                                    <svg class="check-icon w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" style="display:none;"><polyline points="20 6 9 17 4 12"/></svg>
                                </div>
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
                icon.style.transform = open ? 'rotate(0deg)' : 'rotate(180deg)';
            });
        });

        container.querySelectorAll('.courier-opt').forEach(opt => {
            opt.addEventListener('click', (e) => {
                e.stopPropagation();
                const c = pricing[parseInt(opt.dataset.i, 10)];

                container.querySelectorAll('.courier-opt').forEach(o => {
                    const selected = o === opt;
                    if (light) {
                        o.classList.toggle('on', selected);
                    } else {
                        o.style.background = selected ? 'rgba(124,58,237,0.1)' : 'rgba(255,255,255,0.04)';
                        o.style.borderColor = selected ? '#7c3aed' : 'rgba(255,255,255,0.09)';
                    }
                    o.querySelector('.check-icon').style.display = selected ? 'block' : 'none';
                });

                onSelect(c);
            });
        });

        if (groups.length === 1) {
            container.querySelector('.group-trigger')?.click();
        }
    };
}
</script>
@endonce
