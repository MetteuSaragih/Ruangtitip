{{-- Ringkasan keranjang, tampil di sisi kanan tiap langkah checkout toko (gaya aside prototipe).
     Pakai: @include('checkout._summary', ['cart' => $cart, 'shipping' => $shipping ?? []])
     Membutuhkan function rp() sudah dideklarasikan di view pemanggil. --}}
@php
    $sumSubtotal = array_sum(array_column($cart, 'subtotal'));
    $sumMethod = $shipping['method'] ?? null;
    $sumServiceFee = $sumMethod ? ($sumMethod === 'biteship' ? 2000 : 1000) : null;
    $sumShipCost = $shipping['cost'] ?? null;
@endphp
<aside class="aside" aria-label="Ringkasan keranjang">
    <section class="card">
        <div class="card-h"><h3>Barang</h3><a class="more-link" href="{{ route('preloved.cart.index') }}">Ubah</a></div>
        <div class="rows" id="s-rows">
            @foreach ($cart as $item)
                <div><span>{{ $item['name'] }} &times;{{ $item['qty'] }}</span><b>{{ rp($item['subtotal']) }}</b></div>
            @endforeach
            @if ($sumServiceFee !== null)
                <div><span>Biaya layanan</span><b>{{ rp($sumServiceFee) }}</b></div>
            @endif
            <div>
                <span>Ongkir</span>
                <b>@if ($sumMethod === 'biteship'){{ $sumShipCost ? rp($sumShipCost) : 'Pilih layanan' }}@else Rp0 @endif</b>
            </div>
            <div class="tot"><span>Total</span><b id="s-total">{{ rp($sumSubtotal + ($sumServiceFee ?? 0) + ($sumShipCost ?? 0)) }}</b></div>
        </div>
        <p class="help mt" id="s-tip">Status pesanan bisa dicek di Pesanan Saya setelah bayar.</p>
    </section>
</aside>
