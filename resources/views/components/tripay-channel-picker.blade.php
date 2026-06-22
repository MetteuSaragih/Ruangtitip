@props(['inputName' => 'payment_method', 'buttonId' => 'payBtn'])
@php
    $uid = 'tripay_' . substr(md5(random_int(0, 999999)), 0, 8);
@endphp

<div id="{{ $uid }}_list">
    <div class="rounded-2xl p-4 text-xs text-center" style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.08);color:rgba(255,255,255,0.45);">
        Memuat metode pembayaran...
    </div>
</div>

<script>
(function () {
    const list = document.getElementById('{{ $uid }}_list');
    const input = document.querySelector('input[name="{{ $inputName }}"]');
    const buttonId = '{{ $buttonId }}';

    fetch('{{ route("api.tripay.channels") }}')
        .then(r => r.json())
        .then(data => {
            const channels = data.channels || [];
            if (!channels.length) {
                list.innerHTML = '<div class="rounded-2xl p-4 text-xs text-center" style="background:rgba(239,68,68,0.08);border:1px solid rgba(239,68,68,0.2);color:#fca5a5;">Metode pembayaran belum tersedia.</div>';
                return;
            }

            const groups = [];
            const groupIndex = {};
            channels.forEach((c, i) => {
                const g = c.group || 'Lainnya';
                if (!(g in groupIndex)) {
                    groupIndex[g] = groups.length;
                    groups.push({ name: g, items: [] });
                }
                groups[groupIndex[g]].items.push(i);
            });

            list.className = 'space-y-2.5';
            list.innerHTML = groups.map((g, gi) => `
                <div class="channel-group rounded-2xl overflow-hidden" style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.09);">
                    <button type="button" class="group-trigger w-full flex items-center justify-between p-4 gap-3 text-left" data-gi="${gi}">
                        <span class="text-sm font-bold text-white">${g.name}</span>
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
                                        class="channel-opt w-full flex items-center gap-3 p-3.5 rounded-xl text-left transition-all hover:scale-[1.01]"
                                        style="background:rgba(255,255,255,0.04);border:1.5px solid rgba(255,255,255,0.09);">
                                    <img src="${channels[i].icon_url}" alt="" class="w-8 h-8 object-contain shrink-0" onerror="this.style.display='none'">
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-bold text-white">${channels[i].name}</p>
                                    </div>
                                    <div class="radio w-5 h-5 rounded-full border-2 shrink-0 flex items-center justify-center" style="border-color:rgba(255,255,255,0.2);"></div>
                                </button>
                            `).join('')}
                        </div>
                    </div>
                </div>
            `).join('');

            list.querySelectorAll('.group-trigger').forEach(trigger => {
                trigger.addEventListener('click', () => {
                    const panel = trigger.nextElementSibling;
                    const icon = trigger.querySelector('.group-icon');
                    const open = panel.style.display !== 'none';
                    panel.style.display = open ? 'none' : 'block';
                    icon.style.transform = open ? 'rotate(0deg)' : 'rotate(180deg)';
                });
            });

            list.querySelectorAll('.channel-opt').forEach(opt => {
                opt.addEventListener('click', (e) => {
                    e.stopPropagation();
                    const c = channels[parseInt(opt.dataset.i, 10)];
                    input.value = c.code;

                    const btn = document.getElementById(buttonId);
                    if (btn) btn.disabled = false;

                    list.querySelectorAll('.channel-opt').forEach(o => {
                        const selected = o === opt;
                        o.style.background = selected ? 'rgba(124,58,237,0.1)' : 'rgba(255,255,255,0.04)';
                        o.style.borderColor = selected ? '#7c3aed' : 'rgba(255,255,255,0.09)';
                        const radio = o.querySelector('.radio');
                        radio.style.background = selected ? '#7c3aed' : 'transparent';
                        radio.style.borderColor = selected ? '#7c3aed' : 'rgba(255,255,255,0.2)';
                    });

                    const group = opt.closest('.channel-group');
                    list.querySelectorAll('.channel-group').forEach(gEl => {
                        gEl.style.borderColor = gEl === group ? '#7c3aed' : 'rgba(255,255,255,0.09)';
                    });
                });
            });

            if (groups.length === 1) {
                list.querySelector('.group-trigger')?.click();
            }
        })
        .catch(() => {
            list.innerHTML = '<div class="rounded-2xl p-4 text-xs text-center" style="background:rgba(239,68,68,0.08);border:1px solid rgba(239,68,68,0.2);color:#fca5a5;">Gagal memuat metode pembayaran.</div>';
        });
})();
</script>
