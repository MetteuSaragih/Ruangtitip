{{-- Stepper 4 langkah. Pakai: @include('dashboard.ruang-titip._progress', ['step' => 1]) --}}
@php
    $labels = ['Detail Penitipan', 'Opsi Logistik', 'Alamat', 'Checkout'];
@endphp
<ol class="stepper" aria-label="Langkah pemesanan">
    @foreach ($labels as $i => $label)
        @php $n = $i + 1; $state = $n < $step ? 'done' : ($n === $step ? 'now' : ''); @endphp
        <li class="{{ $state }}">
            <button type="button">
                <span class="bar"></span>
                <span class="lbl">
                    <span class="num">
                        @if ($state === 'done')
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                        @else
                            {{ $n }}
                        @endif
                    </span>
                    <span>{{ $label }}</span>
                </span>
            </button>
        </li>
    @endforeach
</ol>
