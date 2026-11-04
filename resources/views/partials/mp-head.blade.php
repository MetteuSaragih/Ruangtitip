{{-- Header halaman Toko Preloved, sama dengan fungsi head() di prototipe. --}}
{{-- Variabel: $crumbs = [[teks, url|null], ...], $title, $sub (opsional), $acts (HTML opsional) --}}
<nav class="crumbs" aria-label="Breadcrumb">
    @foreach ($crumbs as $i => $c)
        @if ($i > 0)<span aria-hidden="true">/</span>@endif
        @if (! empty($c[1]))<a href="{{ $c[1] }}">{{ $c[0] }}</a>@else<span aria-current="page">{{ $c[0] }}</span>@endif
    @endforeach
</nav>
<div class="ph"><div><h2 class="pt" tabindex="-1">{{ $title }}</h2>@if (! empty($sub))<p class="ps">{{ $sub }}</p>@endif</div>@if (! empty($acts))<div class="ph-acts">{!! $acts !!}</div>@endif</div>
