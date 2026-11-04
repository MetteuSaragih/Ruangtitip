@props(['prefix' => null, 'oldAreaId' => null, 'oldAreaName' => null, 'oldPostalCode' => null, 'theme' => 'light'])
@php
    // Parameter $theme dipertahankan demi kompatibilitas; komponen ini hanya punya tema terang.
    $fieldName = fn (string $field) => $prefix ? "{$prefix}[{$field}]" : $field;
    $uid = 'areaSearch_' . substr(md5($prefix . random_int(0, 999999)), 0, 8);
@endphp

<div class="area-search" data-biteship-area-search style="position:relative">
    <label class="area-search-label" for="{{ $uid }}_input">
        Kecamatan / Kota <span style="color:var(--color-danger-text)">*</span>
    </label>
    <input type="text" id="{{ $uid }}_input" autocomplete="off" placeholder="Cari kecamatan atau kota, misal: Lowokwaru"
           value="{{ $oldAreaName }}" class="area-search-input"
           aria-describedby="{{ $uid }}_hint {{ $uid }}_status" aria-controls="{{ $uid }}_results" aria-autocomplete="list">
    <div id="{{ $uid }}_results" class="area-search-results" style="position:absolute;left:0;right:0;display:none"></div>
    <p class="area-search-hint" id="{{ $uid }}_hint">Dipakai untuk menghitung ongkos kirim secara akurat.</p>
    <p class="area-search-hint" id="{{ $uid }}_status" role="status" aria-live="polite" style="min-height:1em"></p>

    <input type="hidden" name="{{ $fieldName('area_id') }}" id="{{ $uid }}_areaId" value="{{ $oldAreaId }}">
    <input type="hidden" name="{{ $fieldName('area_name') }}" id="{{ $uid }}_areaName" value="{{ $oldAreaName }}">
    <input type="hidden" name="{{ $fieldName('postal_code') }}" id="{{ $uid }}_postalCode" value="{{ $oldPostalCode }}">
</div>

<script>
(function () {
    const input = document.getElementById('{{ $uid }}_input');
    const results = document.getElementById('{{ $uid }}_results');
    const status = document.getElementById('{{ $uid }}_status');
    const areaId = document.getElementById('{{ $uid }}_areaId');
    const areaName = document.getElementById('{{ $uid }}_areaName');
    const postalCode = document.getElementById('{{ $uid }}_postalCode');
    if (!input || !results || !areaId || !areaName || !postalCode) { return; }

    const esc = (s) => String(s == null ? '' : s).replace(/[&<>"']/g, (ch) => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[ch]));
    let timer = null;
    let seq = 0;

    function setStatus(msg) { if (status) { status.textContent = msg || ''; } }
    function hideResults() { results.style.display = 'none'; }
    function showResults() { results.style.display = 'block'; }

    function render(areas) {
        if (!areas.length) {
            hideResults();
            results.innerHTML = '';
            setStatus('Area tidak ditemukan. Coba kata kunci lain.');
            return;
        }
        setStatus(areas.length + ' area ditemukan.');
        results.innerHTML = areas.map((a, i) => `<button type="button" data-i="${i}" class="area-search-opt">${esc(a.name)}</button>`).join('');
        showResults();

        results.querySelectorAll('button').forEach(btn => {
            btn.addEventListener('click', () => {
                const a = areas[parseInt(btn.dataset.i, 10)];
                if (!a) { return; }
                input.value = a.name;
                areaId.value = a.id;
                areaName.value = a.name;
                postalCode.value = a.postal_code || '';
                hideResults();
                setStatus('Area dipilih: ' + a.name);
                input.dispatchEvent(new CustomEvent('biteship-area-selected', { bubbles: true, detail: a }));
            });
        });
    }

    input.addEventListener('input', () => {
        areaId.value = '';
        areaName.value = '';
        postalCode.value = '';
        clearTimeout(timer);
        const q = input.value.trim();
        if (q.length < 3) {
            hideResults();
            setStatus('');
            return;
        }
        const mine = ++seq;
        timer = setTimeout(() => {
            setStatus('Mencari area...');
            fetch('{{ route('api.biteship.areas') }}' + '?q=' + encodeURIComponent(q), { headers: { 'Accept': 'application/json' } })
                .then(r => { if (!r.ok) { throw new Error('http ' + r.status); } return r.json(); })
                .then(data => { if (mine === seq) { render(Array.isArray(data.areas) ? data.areas : []); } })
                .catch(() => { if (mine === seq) { render([]); setStatus('Gagal memuat area. Periksa koneksi lalu coba lagi.'); } });
        }, 350);
    });

    document.addEventListener('click', (e) => {
        if (e.target !== input && !results.contains(e.target)) { hideResults(); }
    });
})();
</script>
