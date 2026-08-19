@props(['prefix' => null, 'oldAddress' => null, 'oldLat' => null, 'oldLng' => null])
@php
    $fieldName = fn (string $field) => $prefix ? "{$prefix}[{$field}]" : $field;
    $uid = 'addrSearch_' . substr(md5($prefix . random_int(0, 999999)), 0, 8);
@endphp

<div class="relative" data-address-autocomplete>
    <label class="block text-[11px] font-semibold mb-2" style="color:rgba(255,255,255,0.55);">
        Alamat Lengkap <span style="color:#f87171;">*</span>
    </label>
    <textarea id="{{ $uid }}_input" rows="2" autocomplete="off"
              placeholder="Ketik alamat, misal: Jl. Veteran No. 10, Lowokwaru, Malang"
              class="w-full px-4 py-3 rounded-xl text-sm text-white placeholder:text-white/20 outline-none resize-none"
              style="background:rgba(255,255,255,0.06);border:1.5px solid rgba(255,255,255,0.1);">{{ $oldAddress }}</textarea>
    <div id="{{ $uid }}_results" class="absolute left-0 right-0 mt-1 rounded-xl overflow-hidden z-20 hidden"
         style="background:#1c1530;border:1.5px solid rgba(255,255,255,0.12);max-height:220px;overflow-y:auto;"></div>
    <p class="text-[10px] mt-1.5" style="color:rgba(255,255,255,0.35);">Pilih saran yang muncul supaya lokasi terdeteksi otomatis, atau lanjut ketik manual.</p>

    <input type="hidden" name="{{ $fieldName('address') }}" id="{{ $uid }}_address" value="{{ $oldAddress }}">
    <input type="hidden" name="{{ $fieldName('latitude') }}" id="{{ $uid }}_lat" value="{{ $oldLat }}">
    <input type="hidden" name="{{ $fieldName('longitude') }}" id="{{ $uid }}_lng" value="{{ $oldLng }}">
</div>

<script>
(function () {
    const input = document.getElementById('{{ $uid }}_input');
    const results = document.getElementById('{{ $uid }}_results');
    const addressField = document.getElementById('{{ $uid }}_address');
    const latField = document.getElementById('{{ $uid }}_lat');
    const lngField = document.getElementById('{{ $uid }}_lng');
    let timer = null;

    function render(items) {
        if (!items.length) {
            results.classList.add('hidden');
            results.innerHTML = '';
            return;
        }
        results.innerHTML = items.map((it, i) =>
            `<button type="button" data-i="${i}" class="addr-opt w-full text-left px-4 py-2.5 text-xs text-white hover:bg-white/10" style="border-bottom:1px solid rgba(255,255,255,0.06);">${it.label}</button>`
        ).join('');
        results.classList.remove('hidden');

        results.querySelectorAll('.addr-opt').forEach(btn => {
            btn.addEventListener('click', () => {
                const it = items[parseInt(btn.dataset.i, 10)];
                input.value = it.label;
                addressField.value = it.label;
                latField.value = it.lat;
                lngField.value = it.lng;
                results.classList.add('hidden');
                input.dispatchEvent(new CustomEvent('address-selected', { bubbles: true, detail: it }));
            });
        });
    }

    input.addEventListener('input', () => {
        addressField.value = input.value;
        latField.value = '';
        lngField.value = '';
        clearTimeout(timer);
        const q = input.value.trim();
        if (q.length < 5) {
            results.classList.add('hidden');
            return;
        }
        timer = setTimeout(() => {
            fetch(`{{ route('api.address.suggest') }}?q=` + encodeURIComponent(q))
                .then(r => r.json())
                .then(data => render(data.results || []))
                .catch(() => render([]));
        }, 400);
    });

    document.addEventListener('click', (e) => {
        if (!e.target.closest('[data-address-autocomplete]')) {
            results.classList.add('hidden');
        }
    });
})();
</script>
