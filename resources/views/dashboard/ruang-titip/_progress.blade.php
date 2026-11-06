{{-- Stepper wizard titip. Pakai: @include('dashboard.ruang-titip._progress', ['step' => 1]) --}}
@php
    $labels = ['Periode & barang', 'Jadwal antar & bayar'];
@endphp
<ol class="wiz" aria-label="Langkah pemesanan">
    @foreach ($labels as $i => $label)
        @php $n = $i + 1; @endphp
        <li class="{{ $n < $step ? 'done' : '' }}" @if ($n === $step) aria-current="step" @endif>
            <span class="n">@if ($n < $step)<svg class="ic" aria-hidden="true"><use href="#i-check"/></svg>@else{{ $n }}@endif</span>
            <span class="t">{{ $label }}</span>
        </li>
    @endforeach
</ol>
