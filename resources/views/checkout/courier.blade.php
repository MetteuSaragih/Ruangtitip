@extends('layouts.rt')
@section('title', 'Keranjang')

@php
    if (! function_exists('rp')) {
        function rp($n) { return 'Rp' . number_format($n, 0, ',', '.'); }
    }
@endphp

@section('content')
<x-courier-group-script />
<div class="mp">
<div class="stack">
    @include('partials.mp-head', [
        'crumbs' => [['Toko Preloved', route('preloved.index')], ['Keranjang', route('preloved.cart.index')], ['Checkout', null]],
        'title' => 'Pilih kurir',
        'sub' => 'Dari gudang RuangTitip ke alamatmu.',
    ])
    @include('checkout._wiz', ['step' => 3])

    <div class="two">
        <div class="stack">
            <section class="card"><h3>Alamat tujuan</h3><p>{{ $address }}</p></section>

            @if ($errors->any())
                <div class="alert a-err"><svg class="ic" aria-hidden="true"><use href="#i-warn"/></svg><div><b>{{ $errors->first() }}</b></div></div>
            @endif

            <form method="POST" action="{{ route('checkout.courier.choose') }}" class="stack">
                @csrf
                <input type="hidden" name="courier_code" id="courierCode">
                <input type="hidden" name="service_code" id="serviceCode">
                <section class="card stack" style="gap:12px">
                    <h3>Layanan pengiriman</h3>
                    <div id="courierList">
                        <p class="help" style="display:flex;gap:10px;align-items:center"><span class="spin"></span>Mengambil ongkir dari Biteship…</p>
                    </div>
                </section>
                <div class="nav-btns">
                    <a href="{{ route('checkout.address') }}" class="btn btn-ghost">Kembali</a>
                    <button type="submit" id="nextBtn" disabled class="btn btn-sun">Lanjutkan</button>
                </div>
            </form>
        </div>
        @include('checkout._summary', ['cart' => $cart, 'shipping' => $shipping])
    </div>
</div>
</div>
@endsection

@php
    $courierItems = array_map(fn ($i) => ['n' => $i['name'] . ' ×' . $i['qty'], 's' => (int) $i['subtotal']], array_values($cart));
@endphp
@push('scripts')
<script>
(function () {
    const courierCode = document.getElementById('courierCode');
    const serviceCode = document.getElementById('serviceCode');
    const nextBtn = document.getElementById('nextBtn');
    const courierList = document.getElementById('courierList');
    const sRows = document.getElementById('s-rows');
    const items = @json($courierItems);
    const baseSubtotal = {{ array_sum(array_column($cart, 'subtotal')) }};
    const serviceFee = 2000;
    const rpf = (n) => 'Rp' + Number(n).toLocaleString('id-ID');
    const esc = (s) => String(s).replace(/[&<>"]/g, (c) => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;' }[c]));

    function renderCouriers(pricing) {
        if (!pricing || !pricing.length) {
            courierList.innerHTML = '<p class="help">Kurir tidak tersedia untuk tujuan ini.</p>';
            return;
        }

        window.renderCourierGroups(courierList, pricing, (c) => {
            courierCode.value = c.courier_code;
            serviceCode.value = c.courier_service_code || '';
            nextBtn.disabled = false;

            if (sRows) {
                sRows.innerHTML = items.map((i) => '<div><span>' + esc(i.n) + '</span><b>' + rpf(i.s) + '</b></div>').join('') +
                    '<div><span>Biaya layanan</span><b>' + rpf(serviceFee) + '</b></div>' +
                    '<div><span>Ongkir</span><b>' + rpf(c.price) + '</b></div>' +
                    '<div class="tot"><span>Total</span><b id="s-total">' + rpf(baseSubtotal + serviceFee + Number(c.price)) + '</b></div>';
            }
        });
    }

    fetch('{{ route("checkout.courier.rates") }}', {
        method: 'POST',
        headers: { 'Accept': 'application/json', 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
    })
    .then(r => r.json())
    .then(data => renderCouriers(data.pricing || []))
    .catch(() => renderCouriers([]));
})();
</script>
@endpush
