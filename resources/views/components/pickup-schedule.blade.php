@props(['oldDate' => null, 'oldTime' => null, 'theme' => 'dark'])
@php
    $uid = 'pickup_' . substr(md5(random_int(0, 999999)), 0, 8);
    $isScheduled = $oldDate && $oldTime;
    $light = $theme === 'light';
@endphp

@if ($light)
<div data-pickup-schedule>
    <label class="area-search-label" for="{{ $uid }}_date">Waktu Penjemputan</label>
    <div class="pickup-toggle">
        <button type="button" id="{{ $uid }}_now" data-mode="now" class="pickup-btn {{ ! $isScheduled ? 'on' : '' }}">Secepatnya</button>
        <button type="button" id="{{ $uid }}_scheduled" data-mode="scheduled" class="pickup-btn {{ $isScheduled ? 'on' : '' }}">Jadwalkan</button>
    </div>
    <div id="{{ $uid }}_fields" class="pickup-fields" style="{{ $isScheduled ? '' : 'display:none;' }}">
        <input type="date" name="pickup_date" id="{{ $uid }}_date" value="{{ $oldDate }}" class="pickup-input">
        <input type="time" name="pickup_time" id="{{ $uid }}_time" value="{{ $oldTime }}" class="pickup-input">
    </div>
    <p class="pickup-hint">Kosongkan jika ingin kurir mencari penjemputan sekarang.</p>
</div>
@else
<div data-pickup-schedule>
    <label class="block text-[11px] font-semibold mb-2" style="color:rgba(255,255,255,0.55);">Waktu Penjemputan</label>
    <div class="grid grid-cols-2 gap-2.5 mb-3">
        <button type="button" id="{{ $uid }}_now" data-mode="now"
                class="pickup-mode py-2.5 rounded-xl text-xs font-semibold transition-all"
                style="background:{{ ! $isScheduled ? 'rgba(124,58,237,0.18)' : 'rgba(255,255,255,0.04)' }};border:1.5px solid {{ ! $isScheduled ? '#7c3aed' : 'rgba(255,255,255,0.09)' }};color:#fff;">
            Secepatnya
        </button>
        <button type="button" id="{{ $uid }}_scheduled" data-mode="scheduled"
                class="pickup-mode py-2.5 rounded-xl text-xs font-semibold transition-all"
                style="background:{{ $isScheduled ? 'rgba(124,58,237,0.18)' : 'rgba(255,255,255,0.04)' }};border:1.5px solid {{ $isScheduled ? '#7c3aed' : 'rgba(255,255,255,0.09)' }};color:#fff;">
            Jadwalkan
        </button>
    </div>

    <div id="{{ $uid }}_fields" class="grid grid-cols-2 gap-2.5" style="{{ $isScheduled ? '' : 'display:none;' }}">
        <input type="date" name="pickup_date" id="{{ $uid }}_date" value="{{ $oldDate }}"
               class="w-full px-3 py-2.5 rounded-xl text-sm text-white outline-none"
               style="background:rgba(255,255,255,0.06);border:1.5px solid rgba(255,255,255,0.1);color-scheme:dark;">
        <input type="time" name="pickup_time" id="{{ $uid }}_time" value="{{ $oldTime }}"
               class="w-full px-3 py-2.5 rounded-xl text-sm text-white outline-none"
               style="background:rgba(255,255,255,0.06);border:1.5px solid rgba(255,255,255,0.1);color-scheme:dark;">
    </div>
    <p class="text-[10px] mt-1.5" style="color:rgba(255,255,255,0.35);">Kosongkan jika ingin kurir mencari penjemputan sekarang.</p>
</div>
@endif

<script>
(function () {
    const light = {{ $light ? 'true' : 'false' }};
    const nowBtn = document.getElementById('{{ $uid }}_now');
    const scheduledBtn = document.getElementById('{{ $uid }}_scheduled');
    const fields = document.getElementById('{{ $uid }}_fields');
    const dateInput = document.getElementById('{{ $uid }}_date');
    const timeInput = document.getElementById('{{ $uid }}_time');

    const today = new Date().toISOString().split('T')[0];
    dateInput.min = today;

    function setMode(mode) {
        const scheduled = mode === 'scheduled';
        fields.style.display = scheduled ? '' : 'none';
        if (light) {
            nowBtn.classList.toggle('on', !scheduled);
            scheduledBtn.classList.toggle('on', scheduled);
        } else {
            nowBtn.style.background = scheduled ? 'rgba(255,255,255,0.04)' : 'rgba(124,58,237,0.18)';
            nowBtn.style.borderColor = scheduled ? 'rgba(255,255,255,0.09)' : '#7c3aed';
            scheduledBtn.style.background = scheduled ? 'rgba(124,58,237,0.18)' : 'rgba(255,255,255,0.04)';
            scheduledBtn.style.borderColor = scheduled ? '#7c3aed' : 'rgba(255,255,255,0.09)';
        }
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
