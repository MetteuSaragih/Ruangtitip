@props(['inputName' => 'payment_method', 'buttonId' => 'payBtn', 'theme' => 'light'])
@php
    // Parameter $theme dipertahankan demi kompatibilitas; komponen ini hanya punya tema terang.
    $uid = 'tripay_' . substr(md5(random_int(0, 999999)), 0, 8);
@endphp

<div id="{{ $uid }}_list" aria-live="polite">
    <div class="pmt-loading" role="status">Memuat metode pembayaran...</div>
</div>

<script>
(function () {
    const list = document.getElementById('{{ $uid }}_list');
    const input = document.querySelector('input[name="{{ $inputName }}"]');
    const buttonId = @json($buttonId);
    if (!list) { return; }

    const esc = (s) => String(s == null ? '' : s).replace(/[&<>"']/g, (ch) => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[ch]));
    const warn = (msg) => '<div class="pmt-loading" role="alert" style="background:var(--color-pending-bg);border-color:var(--color-accent);color:var(--color-pending-text);">' + msg + '</div>';

    fetch(@json(route('api.tripay.channels')), { headers: { 'Accept': 'application/json' } })
        .then(r => { if (!r.ok) { throw new Error('http ' + r.status); } return r.json(); })
        .then(data => {
            const channels = Array.isArray(data.channels) ? data.channels : [];
            if (!channels.length) {
                list.innerHTML = warn('Metode pembayaran belum tersedia.');
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

            list.className = 'pmt-list';
            list.innerHTML = groups.map((g, gi) => `
                <div class="pmt-group channel-group" data-gi="${gi}">
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
                                <button type="button" data-i="${i}" class="channel-opt pmt-opt" aria-pressed="false">
                                    <span class="pmt-opt-title">${esc(channels[i].name)}</span>
                                    <span class="radio pmt-radio" aria-hidden="true"></span>
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
                    trigger.setAttribute('aria-expanded', open ? 'false' : 'true');
                    if (icon) { icon.style.transform = open ? 'rotate(0deg)' : 'rotate(180deg)'; }
                });
            });

            list.querySelectorAll('.channel-opt').forEach(opt => {
                opt.addEventListener('click', (e) => {
                    e.stopPropagation();
                    const c = channels[parseInt(opt.dataset.i, 10)];
                    if (!c) { return; }
                    if (input) { input.value = c.code; }

                    const btn = document.getElementById(buttonId);
                    if (btn) { btn.disabled = false; }

                    list.querySelectorAll('.channel-opt').forEach(o => {
                        o.classList.toggle('on', o === opt);
                        o.setAttribute('aria-pressed', o === opt ? 'true' : 'false');
                    });
                });
            });

            if (groups.length === 1) {
                const first = list.querySelector('.group-trigger');
                if (first) { first.click(); }
            }
        })
        .catch(() => {
            list.innerHTML = warn('Gagal memuat metode pembayaran. Muat ulang halaman untuk mencoba lagi.');
        });
})();
</script>
