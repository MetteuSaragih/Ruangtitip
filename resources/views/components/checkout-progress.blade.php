{{--
    Progress langkah checkout, dipakai di Ruang Titip, Toko Packing, dan Toko Preloved.
    Pakai: <x-checkout-progress :labels="['A','B','C','D']" :step="2" />
    Gaya mengikuti .stepper di resources/css/rt/legacy.css
--}}
@props(['labels', 'step'])
<ol class="stepper" aria-label="Langkah pemesanan" style="grid-template-columns:repeat({{ count($labels) }},1fr)">
    @foreach ($labels as $i => $label)
        <li class="{{ $i < $step - 1 ? 'done' : ($i === $step - 1 ? 'now' : '') }}" @if ($i === $step - 1) aria-current="step" @endif>
            <button type="button" tabindex="-1">
                <span class="bar"></span>
                <span class="lbl"><span class="num">{{ $i + 1 }}</span><span>{{ $label }}</span></span>
            </button>
        </li>
    @endforeach
</ol>
