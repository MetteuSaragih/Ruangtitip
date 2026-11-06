@extends('layouts.rt')

@section('title', 'Katalog')

@php
    if (! function_exists('rp')) {
        function rp($n) { return 'Rp' . number_format($n, 0, ',', '.'); }
    }
    $images = ! empty($product['images']) ? $product['images'] : (! empty($product['image']) ? [$product['image']] : []);
    $kode = 'PL-' . str_pad((string) $product['id'], 3, '0', STR_PAD_LEFT);
@endphp

@section('content')
<div class="mp">
<div class="stack">
    @include('partials.mp-head', [
        'crumbs' => [['Katalog', route('preloved.index')], [$kode, null]],
        'title' => $product['name'],
        'sub' => $product['category_label'] . '. Kode barang ' . $kode . '.',
        'acts' => '<span class="badge b-ok"><i></i>Tersedia</span>',
    ])

    <div class="two">
        <div class="stack">
            <div class="gal">
                @forelse ($images as $k => $img)
                    <div class="pic" style="--tone:var(--alt)"><img src="{{ asset('storage/'.$img) }}" alt="{{ $product['name'] }}" style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover"><small>Foto {{ $k + 1 }}</small></div>
                @empty
                    <div class="pic" style="--tone:var(--alt)"><svg viewBox="0 0 24 24" aria-hidden="true"><use href="#n-tag"/></svg><small>Foto 1</small></div>
                @endforelse
            </div>

            <section class="card">
                <h3>Kondisi barang</h3>
                <div class="rows">
                    <div><span>Kondisi</span><b>{{ $product['cond_text'] }}</b></div>
                    <div><span>Fungsi</span><b>{{ $product['func'] }}</b></div>
                    <div><span>Kelengkapan</span><b>{{ $product['kel'] }}</b></div>
                    <div><span>Kekurangan</span><b>{{ $product['min'] }}</b></div>
                </div>
                <h4 class="sub">Deskripsi</h4>
                <p>{{ $product['description'] }}</p>
            </section>

            <section class="card">
                <h3>Sebelum membeli</h3>
                <ul class="bul">
                    <li>Harga tetap dan sudah termasuk pemeriksaan petugas.</li>
                    <li>Pembayaran penuh lewat website, batas 24 jam setelah pesanan dibuat. Selama itu barang dicadangkan untukmu.</li>
                    <li>Ambil langsung di gudang disarankan supaya kamu bisa memeriksa barang saat serah terima.</li>
                    <li>Kalau barang tidak sesuai katalog, ajukan komplain dengan bukti dalam 24 jam setelah barang diterima.</li>
                </ul>
            </section>
        </div>

        <aside class="aside">
            <section class="card stack">
                <p class="help">Harga</p>
                <p class="big-price">{{ rp($product['price']) }}</p>
                <p class="help">Lokasi barang: Gudang Ruru, Lowokwaru, Malang</p>
                <button type="button" class="btn btn-sun wide" id="buy">Beli sekarang</button>
                <p class="msg" id="buy-msg" hidden></p>
            </section>
        </aside>
    </div>
</div>
</div>
@endsection

@push('scripts')
<script>
(function () {
    var productId = {{ $product['id'] }};
    var csrf = document.querySelector('meta[name="csrf-token"]').content;
    var cartUrl = @json(route('preloved.cart.add'));
    var btn = document.getElementById('buy'), msg = document.getElementById('buy-msg');
    btn.addEventListener('click', function () {
        btn.disabled = true;
        fetch(cartUrl, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrf },
            body: JSON.stringify({ type: 'preloved', product_id: productId, qty: 1, buy_now: true })
        }).then(function (r) { return r.json(); }).then(function (data) {
            if (data.redirect) { window.location.href = data.redirect; return; }
            btn.disabled = false;
            msg.textContent = data.message || 'Gagal memproses.';
            msg.hidden = false;
        }).catch(function () {
            btn.disabled = false;
            msg.textContent = 'Terjadi kesalahan.';
            msg.hidden = false;
        });
    });
})();
</script>
@endpush
