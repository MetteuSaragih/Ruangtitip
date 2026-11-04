@extends('layouts.rt')
@section('title', 'Keranjang')

@php
    if (! function_exists('rp')) {
        function rp($n) { return 'Rp' . number_format($n, 0, ',', '.'); }
    }
    $opts = [
        ['id' => 'pickup', 'label' => 'Jemput Sendiri ke Gudang', 'desc' => 'Ambil pesananmu langsung di gudang RuangTitip. Tidak ada biaya tambahan.', 'tag' => 'Disarankan'],
        ['id' => 'biteship', 'label' => 'Dikirim ke Alamatmu', 'desc' => 'Diantar kurir instan (GoSend, GrabExpress) dari gudang ke kosmu. Ongkir ditambahkan ke tagihan.', 'tag' => null],
    ];
@endphp

@section('content')
<div class="mp">
<div class="stack">
    @include('partials.mp-head', [
        'crumbs' => [['Toko Preloved', route('preloved.index')], ['Keranjang', route('preloved.cart.index')], ['Checkout', null]],
        'title' => 'Pilih cara menerima barang',
    ])
    @include('checkout._wiz', ['step' => 1])

    <div class="two">
        <div class="stack">
            @if ($errors->any())
                <div class="alert a-err"><svg class="ic" aria-hidden="true"><use href="#i-warn"/></svg><div><b>{{ $errors->first() }}</b></div></div>
            @endif
            <form method="POST" action="{{ route('checkout.shipping.choose') }}" class="stack">
                @csrf
                <section class="card stack" style="gap:12px">
                    <h3>Cara penerimaan</h3>
                    <div class="picks" role="radiogroup" aria-label="Cara pengiriman">
                        @foreach ($opts as $o)
                            <label class="pick"><input type="radio" name="shipping_method" value="{{ $o['id'] }}"><span><b>{{ $o['label'] }}@if ($o['tag'])<span class="tag">{{ $o['tag'] }}</span>@endif</b><small>{{ $o['desc'] }}</small></span></label>
                        @endforeach
                    </div>
                </section>
                <div class="nav-btns">
                    <a href="{{ route('preloved.cart.index') }}" class="btn btn-ghost">Kembali</a>
                    <button type="submit" id="nextBtn" disabled class="btn btn-sun">Lanjutkan</button>
                </div>
            </form>
        </div>
        @include('checkout._summary', ['cart' => $cart, 'shipping' => $shipping])
    </div>
</div>
</div>
@endsection

@push('scripts')
<script>
    document.querySelectorAll('input[name="shipping_method"]').forEach(function (r) {
        r.addEventListener('change', function () { document.getElementById('nextBtn').disabled = false; });
    });
</script>
@endpush
