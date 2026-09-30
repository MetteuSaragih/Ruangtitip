@props(['prefix' => null, 'oldAreaId' => null, 'oldAreaName' => null, 'oldPostalCode' => null, 'theme' => 'dark'])
@php
    $fieldName = fn (string $field) => $prefix ? "{$prefix}[{$field}]" : $field;
    $uid = 'areaSearch_' . substr(md5($prefix . random_int(0, 999999)), 0, 8);
    $light = $theme === 'light';
@endphp

@if ($light)
<div class="area-search" data-biteship-area-search style="position:relative">
    <label class="area-search-label" for="{{ $uid }}_input">
        Kecamatan / Kota <span style="color:var(--danger)">*</span>
    </label>
    <input type="text" id="{{ $uid }}_input" autocomplete="off" placeholder="Cari kecamatan atau kota, misal: Lowokwaru"
           value="{{ $oldAreaName }}" class="area-search-input">
    <div id="{{ $uid }}_results" class="area-search-results" style="position:absolute;left:0;right:0;display:none"></div>
    <p class="area-search-hint">Dipakai untuk menghitung ongkos kirim secara akurat.</p>

    <input type="hidden" name="{{ $fieldName('area_id') }}" id="{{ $uid }}_areaId" value="{{ $oldAreaId }}">
    <input type="hidden" name="{{ $fieldName('area_name') }}" id="{{ $uid }}_areaName" value="{{ $oldAreaName }}">
    <input type="hidden" name="{{ $fieldName('postal_code') }}" id="{{ $uid }}_postalCode" value="{{ $oldPostalCode }}">
</div>
@else
<div class="relative" data-biteship-area-search>
    <label class="block text-[11px] font-semibold mb-2" style="color:rgba(255,255,255,0.55);">
        Kecamatan / Kota <span style="color:#f87171;">*</span>
    </label>
    <input type="text" id="{{ $uid }}_input" autocomplete="off" placeholder="Cari kecamatan atau kota, misal: Lowokwaru"
           value="{{ $oldAreaName }}"
           class="w-full px-4 py-3 rounded-xl text-sm text-white placeholder:text-white/20 outline-none"
           style="background:rgba(255,255,255,0.06);border:1.5px solid rgba(255,255,255,0.1);">
    <div id="{{ $uid }}_results" class="absolute left-0 right-0 mt-1 rounded-xl overflow-hidden z-20 hidden"
         style="background:#1c1530;border:1.5px solid rgba(255,255,255,0.12);max-height:220px;overflow-y:auto;"></div>
    <p class="text-[10px] mt-1.5" style="color:rgba(255,255,255,0.35);">Dipakai untuk menghitung ongkos kirim secara akurat.</p>

    <input type="hidden" name="{{ $fieldName('area_id') }}" id="{{ $uid }}_areaId" value="{{ $oldAreaId }}">
    <input type="hidden" name="{{ $fieldName('area_name') }}" id="{{ $uid }}_areaName" value="{{ $oldAreaName }}">
    <input type="hidden" name="{{ $fieldName('postal_code') }}" id="{{ $uid }}_postalCode" value="{{ $oldPostalCode }}">
</div>
@endif

<script>
(function () {
    const light = {{ $light ? 'true' : 'false' }};
    const input = document.getElementById('{{ $uid }}_input');
    const results = document.getElementById('{{ $uid }}_results');
    const areaId = document.getElementById('{{ $uid }}_areaId');
    const areaName = document.getElementById('{{ $uid }}_areaName');
    const postalCode = document.getElementById('{{ $uid }}_postalCode');
    let timer = null;

    function hideResults() {
        if (light) { results.style.display = 'none'; } else { results.classList.add('hidden'); }
    }
    function showResults() {
        if (light) { results.style.display = 'block'; } else { results.classList.remove('hidden'); }
    }

    function render(areas) {
        if (!areas.length) {
            hideResults();
            results.innerHTML = '';
            return;
        }
        results.innerHTML = areas.map((a, i) => light
            ? `<button type="button" data-i="${i}" class="area-search-opt">${a.name}</button>`
            : `<button type="button" data-i="${i}" class="area-opt w-full text-left px-4 py-2.5 text-xs text-white hover:bg-white/10" style="border-bottom:1px solid rgba(255,255,255,0.06);">${a.name}</button>`
        ).join('');
        showResults();

        results.querySelectorAll('button').forEach(btn => {
            btn.addEventListener('click', () => {
                const a = areas[parseInt(btn.dataset.i, 10)];
                input.value = a.name;
                areaId.value = a.id;
                areaName.value = a.name;
                postalCode.value = a.postal_code || '';
                hideResults();
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
            return;
        }
        timer = setTimeout(() => {
            fetch(`{{ route('api.biteship.areas') }}?q=` + encodeURIComponent(q))
                .then(r => r.json())
                .then(data => render(data.areas || []))
                .catch(() => render([]));
        }, 350);
    });

    document.addEventListener('click', (e) => {
        if (!e.target.closest('[data-biteship-area-search]') || !document.getElementById('{{ $uid }}_input').contains(e.target)) {
            if (!e.target.closest('#{{ $uid }}_results') && e.target !== input) {
                hideResults();
            }
        }
    });
})();
</script>
