@extends('layouts.ruang-titip')
@section('title', 'Opsi Pengiriman')

@php function rp($n){ return 'Rp'.number_format($n,0,',','.'); } @endphp

@push('styles')
<style>.eta{display:inline-block;font-size:13px;font-weight:700;color:var(--depot);margin-top:4px}</style>
@endpush

@section('content')
<main class="wrap">
  <a class="back-link" href="{{ route('preloved.cart.index') }}">
    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5M11 18l-6-6 6-6"/></svg>
    Lanjut belanja
  </a>

  <div class="layout">
    <div>
      <ol class="stepper" aria-label="Langkah checkout">
        <li class="now"><button type="button"><span class="bar"></span><span class="lbl"><span class="num">1</span><span>Pengambilan</span></span></button></li>
        <li><button type="button"><span class="bar"></span><span class="lbl"><span class="num">2</span><span>Alamat</span></span></button></li>
        <li><button type="button"><span class="bar"></span><span class="lbl"><span class="num">3</span><span>Kurir</span></span></button></li>
        <li><button type="button"><span class="bar"></span><span class="lbl"><span class="num">4</span><span>Bayar</span></span></button></li>
      </ol>

      <div class="step-head">
        <h1>Pesananmu mau diambil atau dikirim?</h1>
        <p>Kalau kosmu dekat gudang, ambil sendiri lebih hemat.</p>
      </div>

      @if ($errors->any())
        <p class="err" style="margin-top:12px">{{ $errors->first() }}</p>
      @endif

      <form method="POST" action="{{ route('checkout.shipping.choose') }}">
        @csrf
        <input type="hidden" name="shipping_method" id="shippingMethod">

        @php
            $opts = [
                ['id' => 'pickup', 'icon' => '🏪', 'label' => 'Jemput Sendiri ke Gudang', 'desc' => 'Ambil pesananmu langsung di gudang RuangTitip. Tidak ada biaya tambahan.', 'note' => '→ Langsung ke pembayaran', 'bg' => 'bg-depot', 'badge' => 'Rp0', 'pill' => 'pill-green'],
                ['id' => 'biteship', 'icon' => '🛵', 'label' => 'Dikirim ke Alamatmu', 'desc' => 'Diantar kurir instan (GoSend, GrabExpress) dari gudang ke kosmu.', 'note' => '→ Input alamat → pilih kurir → pembayaran', 'bg' => 'bg-tape', 'badge' => 'Instan', 'pill' => 'pill-orange'],
            ];
        @endphp
        <fieldset class="choices" style="border:0;padding:0;margin-top:0">
          <legend class="sr">Cara pengiriman</legend>
          @foreach ($opts as $o)
            <button type="button" class="choice opt" data-id="{{ $o['id'] }}" onclick="pickShipping('{{ $o['id'] }}')">
              <span class="ic {{ $o['bg'] }}" aria-hidden="true">{{ $o['icon'] }}</span>
              <span class="t">
                <strong>{{ $o['label'] }}</strong>
                <span class="pill {{ $o['pill'] }}">{{ $o['badge'] }}</span>
                <p class="desc">{{ $o['desc'] }}</p>
                <p class="meta">{{ $o['note'] }}</p>
              </span>
              <span class="radio" aria-hidden="true"></span>
            </button>
          @endforeach
        </fieldset>

        <div class="actions">
          <a href="{{ route('preloved.cart.index') }}" class="btn btn-outline">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
            Kembali
          </a>
          <button type="submit" id="nextBtn" disabled class="btn btn-primary">Lanjutkan
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
          </button>
        </div>
      </form>
    </div>

    @include('checkout._summary', ['cart' => $cart, 'shipping' => $shipping])
  </div>
</main>

@push('scripts')
<script>
    function pickShipping(id) {
        document.getElementById('shippingMethod').value = id;
        document.getElementById('nextBtn').disabled = false;
        document.querySelectorAll('.opt').forEach(b => b.classList.toggle('on', b.dataset.id === id));
        const sShip = document.getElementById('s-ship');
        if (sShip) sShip.textContent = id === 'pickup' ? 'Jemput sendiri di gudang' : 'Dikirim, kurir belum dipilih';
    }
</script>
@endpush
@endsection
