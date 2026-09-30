{{-- Ringkasan keranjang, tampil di sisi kanan tiap langkah checkout toko.
     Pakai: @include('checkout._summary', ['cart' => $cart, 'shipping' => $shipping ?? []])
     Membutuhkan function rp() sudah dideklarasikan di view pemanggil. --}}
@php
    $sumSubtotal = array_sum(array_column($cart, 'subtotal'));
    $sumMethod = $shipping['method'] ?? null;
    $sumShipLabel = match ($sumMethod) {
        'pickup' => 'Jemput sendiri di gudang',
        'biteship' => trim(($shipping['courier_name'] ?? 'Kurir instan') . (isset($shipping['cost']) ? '' : '')),
        default => null,
    };
    $sumServiceFee = $sumMethod ? ($sumMethod === 'biteship' ? 2000 : 1000) : null;
    $sumShipCost = $shipping['cost'] ?? null;
@endphp
<aside class="summary" aria-label="Ringkasan keranjang">
    <div class="sec" style="display:flex;justify-content:space-between;align-items:center">
        <h3 style="margin:0">Keranjang</h3>
        <a href="{{ route('preloved.cart.index') }}" style="font-size:13px;font-weight:700">Ubah</a>
    </div>
    <div class="sec">
        <ul class="items">
            @foreach ($cart as $item)
                <li><span>{{ $item['name'] }} &times;{{ $item['qty'] }}</span><span>{{ rp($item['subtotal']) }}</span></li>
            @endforeach
        </ul>
    </div>
    <div class="sec">
        <h3>Pengiriman</h3>
        <p class="dim" id="s-ship">{{ $sumShipLabel ?? 'Belum dipilih' }}</p>
    </div>
    <div class="foot">
        <div class="lines" id="s-lines">
            <div class="line"><span>Subtotal ({{ count($cart) }} barang)</span><span>{{ rp($sumSubtotal) }}</span></div>
            @if ($sumServiceFee !== null)
                <div class="line"><span>Biaya layanan</span><span>{{ rp($sumServiceFee) }}</span></div>
            @endif
            @if ($sumShipCost)
                <div class="line"><span>Ongkir</span><span>{{ rp($sumShipCost) }}</span></div>
            @endif
        </div>
        <div class="total"><span style="font-weight:700">Total</span><strong id="s-total">{{ rp($sumSubtotal + ($sumServiceFee ?? 0) + ($sumShipCost ?? 0)) }}</strong></div>
        <div class="ruru-tip">
            <img src="{{ asset('assets/ruru.webp') }}" alt="">
            <p id="s-tip">Tip dari Ruru: status pesanan bisa dicek di Pesanan Saya setelah bayar.</p>
        </div>
    </div>
</aside>
