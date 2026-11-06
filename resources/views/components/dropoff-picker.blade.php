{{--
    Pilih tanggal dan jam antar barang ke gudang, sesuai jam operasional gudang.
    Props:
      drop         hasil RuangTitipController::dropoffInfo (min, max, hours, slots, now, min_ahead)
      defaultDate  tanggal awal (Y-m-d), dipakai bila tidak ada old
      oldDate/oldTime  isian sebelumnya setelah validasi gagal
    Mengirim: pickup_date (Y-m-d) dan pickup_time (HH:MM). Server memeriksa ulang semuanya.
--}}
@props(['drop', 'defaultDate' => null, 'oldDate' => null, 'oldTime' => null])
@php
    $uid = 'drop_' . substr(md5(random_int(0, 999999)), 0, 8);
    $minStr = $drop['min']->toDateString();
    $maxStr = $drop['max']->toDateString();
    $startDate = $oldDate ?: $defaultDate;
    if (! $startDate || $startDate < $minStr || $startDate > $maxStr) {
        $startDate = $maxStr;
    }
    $hoursRows = [['Senin sampai Jumat', $drop['hours']['weekday']], ['Sabtu', $drop['hours']['saturday']], ['Minggu', $drop['hours']['sunday']]];
@endphp

<div class="dropoff" id="{{ $uid }}">
    <div class="field">
        <label for="{{ $uid }}_date">Tanggal antar</label>
        <input class="input" type="date" id="{{ $uid }}_date" name="pickup_date" required min="{{ $minStr }}" max="{{ $maxStr }}" value="{{ $startDate }}">
        <p class="help">Bisa dipilih dari {{ $drop['min']->translatedFormat('j M Y') }} sampai {{ $drop['max']->translatedFormat('j M Y') }}.</p>
    </div>

    <div class="field">
        <span class="lab" id="{{ $uid }}_lbl">Jam antar</span>
        <div class="drop-slots" id="{{ $uid }}_slots" role="radiogroup" aria-labelledby="{{ $uid }}_lbl"></div>
        <input type="hidden" name="pickup_time" id="{{ $uid }}_time" value="{{ $oldTime }}">
        <p class="help" id="{{ $uid }}_hint" aria-live="polite"></p>
        <p class="msg" id="{{ $uid }}_err" role="alert" style="display:none"></p>
    </div>

    <div class="drop-hours" aria-label="Jam operasional gudang">
        <b>Jam operasional gudang</b>
        @foreach ($hoursRows as [$day, $text])
            <div><span>{{ $day }}</span><span>{{ $text }}</span></div>
        @endforeach
    </div>
</div>

<script>
(function () {
    const root = document.getElementById(@json($uid));
    if (!root) { return; }
    const slotsByDay = @json($drop['slots']);
    const nowWib = @json($drop['now']);          // "YYYY-MM-DD HH:MM" waktu WIB dari server
    const minAhead = {{ (int) $drop['min_ahead'] }};
    const dateEl = document.getElementById(@json($uid . '_date'));
    const timeEl = document.getElementById(@json($uid . '_time'));
    const listEl = document.getElementById(@json($uid . '_slots'));
    const hintEl = document.getElementById(@json($uid . '_hint'));
    const errEl = document.getElementById(@json($uid . '_err'));
    const form = root.closest('form');
    if (!dateEl || !timeEl || !listEl) { return; }

    const nowDate = nowWib.slice(0, 10);
    const nowMin = parseInt(nowWib.slice(11, 13), 10) * 60 + parseInt(nowWib.slice(14, 16), 10);

    function dayType(value) {
        const p = value.split('-').map(Number);
        const dow = new Date(p[0], p[1] - 1, p[2]).getDay();
        return dow === 0 ? 'sunday' : (dow === 6 ? 'saturday' : 'weekday');
    }
    function toMin(hhmm) { return parseInt(hhmm.slice(0, 2), 10) * 60 + parseInt(hhmm.slice(3, 5), 10); }
    function label(hhmm) { return hhmm.replace(':', '.'); }
    function showErr(msg) { errEl.textContent = msg || ''; errEl.style.display = msg ? '' : 'none'; }

    function render() {
        const value = dateEl.value;
        listEl.innerHTML = '';
        showErr('');
        if (!value || value < dateEl.min || value > dateEl.max) {
            timeEl.value = '';
            hintEl.textContent = 'Pilih tanggal antar yang masih dalam rentang di atas.';
            return;
        }
        const slots = slotsByDay[dayType(value)] || [];
        if (!slots.length) {
            timeEl.value = '';
            hintEl.textContent = 'Gudang tutup di hari itu. Pilih tanggal lain.';
            return;
        }
        let firstOk = null;
        let selectedStillOk = false;
        slots.forEach(function (t) {
            const tooSoon = value === nowDate && toMin(t) < nowMin + minAhead;
            const b = document.createElement('button');
            b.type = 'button';
            b.className = 'chip';
            b.textContent = label(t);
            b.dataset.time = t;
            b.disabled = tooSoon;
            b.setAttribute('role', 'radio');
            const on = !tooSoon && timeEl.value === t;
            b.setAttribute('aria-pressed', on ? 'true' : 'false');
            b.setAttribute('aria-checked', on ? 'true' : 'false');
            if (tooSoon) { b.title = 'Sudah terlalu dekat, minimal ' + minAhead + ' menit dari sekarang'; }
            if (on) { selectedStillOk = true; }
            if (!tooSoon && firstOk === null) { firstOk = t; }
            b.addEventListener('click', function () {
                timeEl.value = t;
                showErr('');
                listEl.querySelectorAll('.chip').forEach(function (c) {
                    const sel = c === b;
                    c.setAttribute('aria-pressed', sel ? 'true' : 'false');
                    c.setAttribute('aria-checked', sel ? 'true' : 'false');
                });
                hintEl.textContent = 'Kamu antar pada ' + b.textContent + ' WIB.';
            });
            listEl.appendChild(b);
        });
        if (!selectedStillOk) { timeEl.value = ''; }
        if (firstOk === null) {
            hintEl.textContent = 'Jam operasional hari ini sudah lewat atau terlalu dekat. Pilih tanggal lain.';
        } else {
            hintEl.textContent = timeEl.value ? 'Kamu antar pada ' + label(timeEl.value) + ' WIB.' : 'Pilih jam kedatanganmu. Waktu dalam WIB.';
        }
    }

    dateEl.addEventListener('change', render);
    render();

    if (form) {
        form.addEventListener('submit', function (e) {
            if (!dateEl.value) { e.preventDefault(); showErr('Pilih tanggal antar barang.'); dateEl.focus(); return; }
            if (!timeEl.value) { e.preventDefault(); showErr('Pilih jam antar barang.'); listEl.scrollIntoView({ block: 'center' }); return; }
            const btn = document.getElementById('placeBtn');
            if (btn) { btn.disabled = true; }
        });
    }
})();
</script>
