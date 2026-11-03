{{-- Ringkasan produk di sisi kanan langkah checkout Toko Packing. Pakai: @include('packing._summary', ['items' => $items]) --}}
@php
    $sumSubtotal = 0;
    foreach ($items as $it) {
        $sumSubtotal += $it['price'] * $it['qty'];
    }
@endphp
<aside class="summary" aria-label="Ringkasan pesanan">
    <div class="sec" style="display:flex;justify-content:space-between;align-items:center">
        <h3 style="margin:0">Pesananmu</h3>
        <a href="{{ route('packing.index') }}" style="font-size:13px;font-weight:700">Ubah</a>
    </div>
    <div class="sec">
        <ul class="items">
            @foreach ($items as $it)
                <li><span>{{ $it['name'] }} &times;{{ $it['qty'] }}</span><span>Rp{{ number_format($it['price'] * $it['qty'], 0, ',', '.') }}</span></li>
            @endforeach
        </ul>
    </div>
    <div class="foot">
        <div class="total"><span style="font-weight:700">Subtotal produk</span><strong>Rp{{ number_format($sumSubtotal, 0, ',', '.') }}</strong></div>
        <p class="note">Ongkir dan biaya layanan dihitung di langkah pembayaran.</p>
        <div class="ruru-tip">
            <img src="{{ asset('assets/ruru/ruru-wajah-happy.webp') }}" alt="">
            <p>Tip dari Ruru: status pesanan bisa dicek di Pesanan Saya setelah bayar.</p>
        </div>
    </div>
</aside>
