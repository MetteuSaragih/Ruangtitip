{{-- Langkah checkout bergaya prototipe (ol.wiz). Variabel: $step (1 sampai 4) --}}
<ol class="wiz">
    @foreach (['Pengambilan', 'Alamat', 'Kurir', 'Bayar'] as $i => $label)
        <li class="{{ $i + 1 < $step ? 'done' : '' }}" @if ($i + 1 === $step) aria-current="step" @endif><span class="n">@if ($i + 1 < $step)<svg class="ic" aria-hidden="true"><use href="#i-check"/></svg>@else{{ $i + 1 }}@endif</span><span class="t">{{ $label }}</span></li>
    @endforeach
</ol>
