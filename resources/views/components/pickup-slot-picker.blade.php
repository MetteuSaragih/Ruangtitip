@props(['oldDate' => null, 'oldStart' => null, 'oldEnd' => null])
@php
    $uid = 'slot_' . substr(md5(random_int(0, 999999)), 0, 8);
    $slots = config('pickup_slots.slots');
    $minHours = config('pickup_slots.min_hours_ahead');
    $maxDays = config('pickup_slots.max_days_ahead');
@endphp

<div id="{{ $uid }}">
    <div class="field">
        <label for="{{ $uid }}_date">Tanggal penjemputan</label>
        <input type="date" id="{{ $uid }}_date" class="pickup-input" value="{{ $oldDate }}">
    </div>

    <input type="hidden" name="pickup_date" id="{{ $uid }}_date_hidden" value="{{ $oldDate }}">
    <input type="hidden" name="pickup_time" id="{{ $uid }}_start_hidden" value="{{ $oldStart }}">
    <input type="hidden" name="pickup_time_end" id="{{ $uid }}_end_hidden" value="{{ $oldEnd }}">

    <p class="pickup-hint" style="margin-top:14px">Pilih jam jemput</p>
    <div class="slot-grid" id="{{ $uid }}_buttons">
        @foreach ($slots as $slot)
          <button type="button" class="slot-btn" data-start="{{ $slot['start'] }}" data-end="{{ $slot['end'] }}">
            {{ str_replace(':', '.', $slot['start']) }}&ndash;{{ str_replace(':', '.', $slot['end']) }}
          </button>
        @endforeach
    </div>
    <p class="pickup-hint" id="{{ $uid }}_feedback"></p>
</div>

<script>
(function () {
    const minHours = {{ (float) $minHours }};
    const maxDays = {{ (int) $maxDays }};
    const dateInput = document.getElementById('{{ $uid }}_date');
    const dateHidden = document.getElementById('{{ $uid }}_date_hidden');
    const startHidden = document.getElementById('{{ $uid }}_start_hidden');
    const endHidden = document.getElementById('{{ $uid }}_end_hidden');
    const buttons = Array.from(document.getElementById('{{ $uid }}_buttons').querySelectorAll('.slot-btn'));
    const feedback = document.getElementById('{{ $uid }}_feedback');

    const todayStr = new Date().toISOString().split('T')[0];
    const maxDate = new Date(Date.now() + maxDays * 86400000).toISOString().split('T')[0];
    dateInput.min = todayStr;
    dateInput.max = maxDate;
    if (!dateInput.value) dateInput.value = todayStr;

    function slotDateTime(dateStr, timeStr) {
        return new Date(dateStr + 'T' + timeStr + ':00');
    }

    function refreshSlots() {
        const dateStr = dateInput.value;
        dateHidden.value = dateStr;
        const earliest = new Date(Date.now() + minHours * 3600000);

        let anyEnabled = false;
        buttons.forEach(function (btn) {
            const dt = slotDateTime(dateStr, btn.dataset.start);
            const disabled = dt < earliest;
            btn.disabled = disabled;
            if (disabled && btn.classList.contains('on')) {
                btn.classList.remove('on');
                startHidden.value = '';
                endHidden.value = '';
            }
            if (!disabled) anyEnabled = true;
        });

        feedback.textContent = anyEnabled
            ? 'Minimal ' + minHours + ' jam dari sekarang, maksimal ' + maxDays + ' hari ke depan.'
            : 'Tidak ada slot tersisa di tanggal ini, pilih tanggal lain.';
    }

    buttons.forEach(function (btn) {
        btn.addEventListener('click', function () {
            if (btn.disabled) return;
            buttons.forEach(function (b) { b.classList.remove('on'); });
            btn.classList.add('on');
            startHidden.value = btn.dataset.start;
            endHidden.value = btn.dataset.end;
        });
    });

    dateInput.addEventListener('change', refreshSlots);
    refreshSlots();

    if (startHidden.value) {
        const match = buttons.find(function (b) { return b.dataset.start === startHidden.value; });
        if (match && !match.disabled) match.classList.add('on');
    }
})();
</script>
