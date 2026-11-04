@extends('layouts.rt')
@section('title', 'Ruang Titip')

@php
    if (! function_exists('rp')) {
        function rp($n) { return 'Rp' . number_format($n, 0, ',', '.'); }
    }
@endphp

@section('content')
@include('dashboard.ruang-titip._icons')
<x-courier-group-script />
<div class="ttp">
<div class="stack">
    <nav class="crumbs" aria-label="Breadcrumb"><a href="{{ route('ruang-titip.index') }}">Ruang Titip</a><span aria-hidden="true">/</span>@if ($storage)<a href="{{ route('ruang-titip.detail', $storage) }}">{{ $storage->name }}</a>@else<span>Pesan</span>@endif<span aria-hidden="true">/</span><span aria-current="page">Pesan</span></nav>
    <div class="ph">
        <div>
            <h2 class="pt" tabindex="-1">Pilih kurir instan</h2>
            <p class="ps">Dari alamatmu ke gudang RuangTitip.</p>
        </div>
    </div>

    @include('dashboard.ruang-titip._progress', ['step' => 3])

    <div class="two">
        <form method="POST" action="{{ route('ruang-titip.kurir.store') }}" class="stack">
            @csrf
            <input type="hidden" name="courier_code" id="courierCode">
            <input type="hidden" name="service_code" id="serviceCode">

            @if ($errors->any())
                <div class="alert a-err"><svg class="ic" aria-hidden="true"><use href="#i-warn"/></svg><div><b>{{ $errors->first() }}</b></div></div>
            @endif

            <section class="card">
                <div class="rows"><div><span>Alamat jemput</span><b>{{ $s['address'] ?? '-' }}</b></div></div>
            </section>

            <section class="card">
                <h3>Kurir tersedia</h3>
                <div id="courierList">
                    <p class="help">Menghitung ongkos kirim...</p>
                </div>
            </section>

            <div class="nav-btns">
                <a href="{{ route('ruang-titip.alamat') }}" class="btn btn-ghost">Kembali</a>
                <button type="submit" id="nextBtn" disabled class="btn btn-sun">Lanjutkan</button>
            </div>
        </form>

        @include('dashboard.ruang-titip._summary', ['storage' => $storage, 's' => $s, 'calc' => $calc])
    </div>
</div>
</div>

@push('scripts')
<script>
(function () {
    const courierCode = document.getElementById('courierCode');
    const serviceCode = document.getElementById('serviceCode');
    const nextBtn = document.getElementById('nextBtn');
    const courierList = document.getElementById('courierList');
    const sLines = document.getElementById('s-lines');
    const sTotal = document.getElementById('s-total');
    const baseTotal = {{ $calc['total'] }};
    const baseSubtotal = {{ $calc['itemSubtotal'] }};

    function renderCouriers(pricing) {
        if (!pricing || !pricing.length) {
            courierList.innerHTML = '<p class="help">Kurir instan tidak tersedia untuk rute ini.</p>';
            return;
        }

        window.renderCourierGroups(courierList, pricing, (c) => {
            courierCode.value = c.courier_code;
            serviceCode.value = c.courier_service_code || '';
            nextBtn.disabled = false;

            if (sLines) {
                sLines.innerHTML =
                    (baseSubtotal ? '<div><span>Subtotal barang</span><b>Rp' + baseSubtotal.toLocaleString('id-ID') + '</b></div>' : '') +
                    '<div><span>Kurir/anjem</span><b>Rp' + Number(c.price).toLocaleString('id-ID') + '</b></div>';
            }
            if (sTotal) sTotal.textContent = 'Rp' + (baseTotal + Number(c.price)).toLocaleString('id-ID');
        }, 'light');
    }

    fetch('{{ route("ruang-titip.kurir.rates") }}', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
    })
    .then(r => r.json())
    .then(data => renderCouriers(data.pricing || []))
    .catch(() => renderCouriers([]));
})();
</script>
@endpush
@endsection
