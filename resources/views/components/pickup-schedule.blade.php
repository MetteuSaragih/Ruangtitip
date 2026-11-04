@props(['oldDate' => null, 'oldTime' => null, 'theme' => 'light'])
@php
    // Parameter $theme dipertahankan demi kompatibilitas; komponen ini hanya punya tema terang.
    $uid = 'pickup_' . substr(md5(random_int(0, 999999)), 0, 8);
    $isScheduled = $oldDate && $oldTime;
@endphp

<div data-pickup-schedule>
    <span class="area-search-label" id="{{ $uid }}_label">Waktu Penjemputan</span>
    <div class="pickup-toggle" role="group" aria-labelledby="{{ $uid }}_label">
        <button type="button" id="{{ $uid }}_now" data-mode="now" class="pickup-btn {{ ! $isScheduled ? 'on' : '' }}" aria-pressed="{{ ! $isScheduled ? 'true' : 'false' }}">Secepatnya</button>
        <button type="button" id="{{ $uid }}_scheduled" data-mode="scheduled" class="pickup-btn {{ $isScheduled ? 'on' : '' }}" aria-pressed="{{ $isScheduled ? 'true' : 'false' }}">Jadwalkan</button>
    </div>
    <div id="{{ $uid }}_fields" class="pickup-fields" style="{{ $isScheduled ? '' : 'display:none;' }}">
        <input type="date" name="pickup_date" id="{{ $uid }}_date" value="{{ $oldDate }}" class="pickup-input" aria-label="Tanggal penjemputan">
        <input type="time" name="pickup_time" id="{{ $uid }}_time" value="{{ $oldTime }}" class="pickup-input" aria-label="Jam penjemputan">
    </div>
    <p class="pickup-hint">Pilih "Secepatnya" jika ingin kurir mencari penjemputan sekarang.</p>
</div>

<script>
(function () {
    const nowBtn = document.getElementById('{{ $uid }}_now');
    const scheduledBtn = document.getElementById('{{ $uid }}_scheduled');
    const fields = document.getElementById('{{ $uid }}_fields');
    const dateInput = document.getElementById('{{ $uid }}_date');
    const timeInput = document.getElementById('{{ $uid }}_time');
    if (!nowBtn || !scheduledBtn || !fields || !dateInput || !timeInput) { return; }

    // Tanggal lokal (toISOString memakai UTC dan bisa mundur sehari di WIB dini hari).
    const d = new Date();
    const pad = (n) => String(n).padStart(2, '0');
    const today = d.getFullYear() + '-' + pad(d.getMonth() + 1) + '-' + pad(d.getDate());
    dateInput.min = today;

    function setMode(mode) {
        const scheduled = mode === 'scheduled';
        fields.style.display = scheduled ? '' : 'none';
        nowBtn.classList.toggle('on', !scheduled);
        scheduledBtn.classList.toggle('on', scheduled);
        nowBtn.setAttribute('aria-pressed', scheduled ? 'false' : 'true');
        scheduledBtn.setAttribute('aria-pressed', scheduled ? 'true' : 'false');
        if (!scheduled) {
            dateInput.value = '';
            timeInput.value = '';
        } else if (!dateInput.value) {
            dateInput.value = today;
        }
    }

    nowBtn.addEventListener('click', () => setMode('now'));
    scheduledBtn.addEventListener('click', () => setMode('scheduled'));
})();
</script>
