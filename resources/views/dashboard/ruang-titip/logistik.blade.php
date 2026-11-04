@extends('layouts.rt')
@section('title', 'Ruang Titip')

@php
    if (! function_exists('rp')) {
        function rp($n) { return 'Rp' . number_format($n, 0, ',', '.'); }
    }
    $opts = [
        ['id' => 'self', 'label' => 'Antar sendiri', 'desc' => 'Kamu antar barang langsung ke Ruang Titip, tanpa biaya logistik.', 'note' => 'Langsung ke ringkasan dan pembayaran', 'badge' => 'Rp0', 'tone' => 'ok'],
        ['id' => 'rutip', 'label' => 'Packing + Anjem RuTip', 'desc' => 'Tim kami jemput dan packing barangmu. Biaya = jarak (per km) + jasa packing per barang.', 'note' => 'Isi alamat, lalu ringkasan dan pembayaran', 'badge' => 'Terpopuler', 'tone' => 'warn'],
        ['id' => 'instant', 'label' => 'Kurir instan', 'desc' => 'Dijemput kurir instan (Gojek/Grab) dari alamatmu.', 'note' => 'Isi alamat, pilih kurir, lalu bayar', 'badge' => null, 'tone' => 'info'],
    ];
@endphp

@section('content')
@include('dashboard.ruang-titip._icons')
<div class="ttp">
<div class="stack">
    <nav class="crumbs" aria-label="Breadcrumb"><a href="{{ route('ruang-titip.index') }}">Ruang Titip</a><span aria-hidden="true">/</span>@if ($storage)<a href="{{ route('ruang-titip.detail', $storage) }}">{{ $storage->name }}</a>@else<span>Pesan</span>@endif<span aria-hidden="true">/</span><span aria-current="page">Pesan</span></nav>
    <div class="ph">
        <div>
            <h2 class="pt" tabindex="-1">Barangnya sampai ke gudang gimana?</h2>
            <p class="ps">Pilih yang paling praktis buatmu.</p>
        </div>
    </div>

    @include('dashboard.ruang-titip._progress', ['step' => 2])

    <div class="two">
        <form method="POST" action="{{ route('ruang-titip.logistik.store') }}" class="stack">
            @csrf
            @if ($errors->any())
                <div class="alert a-err"><svg class="ic" aria-hidden="true"><use href="#i-warn"/></svg><div><b>{{ $errors->first() }}</b></div></div>
            @endif

            <section class="card">
                <h3>Cara pengiriman</h3>
                <div class="picks" role="radiogroup" aria-label="Cara pengiriman">
                    @foreach ($opts as $o)
                        <label class="pick">
                            <input type="radio" name="logistic" value="{{ $o['id'] }}" data-label="{{ $o['label'] }}" @checked(old('logistic', $s['logistic'] ?? null) === $o['id'])>
                            <span>
                                <b>{{ $o['label'] }}</b>
                                <small>{{ $o['desc'] }}</small>
                                <small>{{ $o['note'] }}</small>
                            </span>
                            @if ($o['badge'])<span class="badge b-{{ $o['tone'] }}"><i></i>{{ $o['badge'] }}</span>@endif
                        </label>
                    @endforeach
                </div>
            </section>

            <div class="nav-btns">
                <a href="{{ route('ruang-titip.detail-item') }}" class="btn btn-ghost">Kembali</a>
                <button type="submit" id="nextBtn" class="btn btn-sun" disabled>Lanjutkan</button>
            </div>
        </form>

        @include('dashboard.ruang-titip._summary', ['storage' => $storage, 's' => $s, 'calc' => $calc])
    </div>
</div>
</div>

@push('scripts')
<script>
    (function () {
        const radios = document.querySelectorAll('input[name="logistic"]');
        const next = document.getElementById('nextBtn');
        function sync() {
            const on = document.querySelector('input[name="logistic"]:checked');
            next.disabled = !on;
            const sLog = document.getElementById('s-log');
            if (on && sLog) sLog.textContent = on.dataset.label;
        }
        radios.forEach(r => r.addEventListener('change', sync));
        sync();
    })();
</script>
@endpush
@endsection
