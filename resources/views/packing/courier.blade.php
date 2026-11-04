@extends('layouts.rt')

@section('title', 'Pilih Kurir')

@section('content')
<x-courier-group-script />
<div class="wrap">
    <a class="back-link" href="{{ route('packing.address') }}">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M19 12H5M11 18l-6-6 6-6"/></svg>
        Kembali
    </a>

    <div class="layout">
        <div>
            <x-checkout-progress :labels="['Opsi logistik', 'Alamat', 'Kurir', 'Pembayaran']" :step="3" />

            <div class="step-head">
                <h1>Pilih kurir</h1>
                <p>Dari gudang RuangTitip ke alamatmu.</p>
            </div>

            <div class="panel" style="display:flex;align-items:flex-start;gap:10px">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--tape-dark)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0;margin-top:2px" aria-hidden="true"><path d="M12 21s-7-6.2-7-11.5a7 7 0 0 1 14 0C19 14.8 12 21 12 21z"/><circle cx="12" cy="9.5" r="2.5"/></svg>
                <p style="font-size:14px;color:var(--muted)">{{ $address }}</p>
            </div>

            @if ($errors->any())
                <p class="err">{{ $errors->first() }}</p>
            @endif

            <form method="POST" action="{{ route('packing.courier.choose') }}">
                @csrf
                <input type="hidden" name="courier_code" id="courierCode">
                <input type="hidden" name="service_code" id="serviceCode">
                <div id="courierList" style="margin-top:16px">
                    <div class="panel" style="text-align:center;color:var(--muted);font-size:14px">Menghitung ongkos kirim...</div>
                </div>
                <div class="actions">
                    <a href="{{ route('packing.address') }}" class="btn btn-outline">Kembali</a>
                    <button type="submit" id="nextBtn" disabled class="btn btn-primary">Lanjutkan
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
                    </button>
                </div>
            </form>
        </div>

        @include('packing._summary', ['items' => $items ?? []])
    </div>
</div>
@endsection

@push('scripts')
<script>
(function () {
    var courierCode = document.getElementById('courierCode');
    var serviceCode = document.getElementById('serviceCode');
    var nextBtn = document.getElementById('nextBtn');
    var courierList = document.getElementById('courierList');

    function renderCouriers(pricing) {
        if (!pricing || !pricing.length) {
            courierList.innerHTML = '<div class="panel" style="text-align:center;color:var(--muted);font-size:14px">Kurir tidak tersedia untuk tujuan ini.</div>';
            return;
        }
        window.renderCourierGroups(courierList, pricing, function (c) {
            courierCode.value = c.courier_code;
            serviceCode.value = c.courier_service_code || '';
            nextBtn.disabled = false;
        });
    }

    fetch(@json(route('packing.courier.rates')), {
        method: 'POST',
        headers: { 'Accept': 'application/json', 'Content-Type': 'application/json', 'X-CSRF-TOKEN': @json(csrf_token()) }
    })
        .then(function (r) { if (!r.ok) { throw new Error('http ' + r.status); } return r.json(); })
        .then(function (data) { renderCouriers(data.pricing || []); })
        .catch(function () { renderCouriers([]); });
})();
</script>
@endpush
