@extends('layouts.ruang-titip')
@section('title', 'Alamat Pengiriman')

@php function rp($n){ return 'Rp'.number_format($n,0,',','.'); } @endphp

@section('content')
<main class="wrap">
  <a class="back-link" href="{{ route('checkout.shipping') }}">
    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5M11 18l-6-6 6-6"/></svg>
    Kembali
  </a>

  <div class="layout">
    <div>
      <ol class="stepper" aria-label="Langkah checkout">
        <li class="done"><button type="button"><span class="bar"></span><span class="lbl"><span class="num"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg></span><span>Pengambilan</span></span></button></li>
        <li class="now"><button type="button"><span class="bar"></span><span class="lbl"><span class="num">2</span><span>Alamat</span></span></button></li>
        <li><button type="button"><span class="bar"></span><span class="lbl"><span class="num">3</span><span>Kurir</span></span></button></li>
        <li><button type="button"><span class="bar"></span><span class="lbl"><span class="num">4</span><span>Bayar</span></span></button></li>
      </ol>

      <div class="step-head">
        <h1>Dikirim ke mana?</h1>
        <p>Pilih alamat tersimpan atau tambah yang baru.</p>
      </div>

      @if ($errors->any())
        <p class="err" style="margin-top:12px">{{ $errors->first() }}</p>
      @endif

      <form method="POST" action="{{ route('checkout.address.save') }}" id="addressForm">
        @csrf
        <input type="hidden" name="mode" id="modeInput" value="new">
        <input type="hidden" name="address_id" id="addressId" value="{{ old('address_id') }}">

        @if ($addresses->isNotEmpty())
          <div class="panel">
            <h2 style="margin-bottom:4px">Alamat Tersimpan</h2>
            <fieldset class="choices" style="border:0;padding:0;margin-top:14px">
              <legend class="sr">Alamat tersimpan</legend>
              @foreach ($addresses as $address)
                <button type="button" class="choice addr" data-id="{{ $address->id }}" onclick="pickAddress('{{ $address->id }}')">
                  <span class="ic bg-sand" aria-hidden="true">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="var(--tape-dark)" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 21s-7-6.2-7-11.5a7 7 0 0 1 14 0C19 14.8 12 21 12 21z"/><circle cx="12" cy="9.5" r="2.5"/></svg>
                  </span>
                  <span class="t">
                    <strong>{{ $address->label ?: 'Alamat' }}</strong>
                    @if ($address->is_primary)<span class="pill pill-green">Utama</span>@endif
                    <p class="desc">{{ $address->address }}</p>
                    @if ($address->note)<p class="meta">Catatan: {{ $address->note }}</p>@endif
                    @if (! $address->area_id)<p class="meta" style="color:var(--tape-dark)">Belum ada kecamatan tersimpan, lengkapi dulu lewat "tambah alamat baru".</p>@endif
                  </span>
                  <span class="radio" aria-hidden="true"></span>
                </button>
              @endforeach
            </fieldset>
          </div>

          <div style="display:flex;align-items:center;gap:14px;margin:24px 0;color:var(--muted);font-size:14px">
            <span style="flex:1;height:1px;background:var(--line)"></span>
            atau tambah alamat baru
            <span style="flex:1;height:1px;background:var(--line)"></span>
          </div>
        @endif

        <div class="panel">
          <h2>{{ $addresses->count() ? 'Daftarkan Alamat Baru' : 'Masukkan Alamat Pengiriman' }}</h2>
          <div class="field" style="margin-top:16px">
            <label for="label">Label <span style="font-weight:400;color:var(--muted)">(opsional)</span></label>
            <input type="text" id="label" name="label" placeholder="Kos / Rumah / Kontrakan" value="{{ old('label') }}">
          </div>
          <div style="margin-top:16px"><x-biteship-area-search prefix="address" theme="light" /></div>
          <div class="field" style="margin-top:16px">
            <label for="addressInput">Alamat lengkap <span style="color:var(--danger)">*</span></label>
            <textarea id="addressInput" name="address[full]" rows="3" placeholder="Jl. Veteran No. 10, Kec. Lowokwaru, Malang">{{ old('address.full') }}</textarea>
          </div>
          <div class="field" style="margin-top:16px">
            <label for="note">Catatan untuk kurir <span style="font-weight:400;color:var(--muted)">(opsional)</span></label>
            <textarea id="note" name="address[note]" rows="2" placeholder="Rumah cat hijau, pagar depan, dekat masjid">{{ old('address.note') }}</textarea>
          </div>
          <label style="display:flex;align-items:center;gap:10px;margin-top:16px;cursor:pointer">
            <input type="checkbox" name="is_primary" value="1" style="width:18px;height:18px;accent-color:var(--tape)" {{ old('is_primary') ? 'checked' : '' }}>
            <span style="font-size:14px;color:var(--body)">Jadikan alamat utama</span>
          </label>
        </div>

        <div class="actions">
          <a href="{{ route('checkout.shipping') }}" class="btn btn-outline">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
            Kembali
          </a>
          <button type="submit" id="continueBtn" disabled class="btn btn-primary">Simpan &amp; Lanjutkan
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
(function () {
    const addr = document.getElementById('addressInput');
    const mode = document.getElementById('modeInput');
    const addressId = document.getElementById('addressId');
    const btn = document.getElementById('continueBtn');

    const refresh = () => {
        const ok = (mode.value === 'select' && addressId.value !== '') || (mode.value === 'new' && addr.value.trim().length > 0);
        btn.disabled = !ok;
    };

    window.pickAddress = (id) => {
        mode.value = 'select';
        addressId.value = id;
        document.querySelectorAll('.addr').forEach(b => b.classList.toggle('on', b.dataset.id === String(id)));
        refresh();
    };

    addr.addEventListener('input', () => {
        if (addr.value.trim().length > 0) {
            mode.value = 'new';
            addressId.value = '';
            document.querySelectorAll('.addr').forEach(b => b.classList.remove('on'));
        }
        refresh();
    });

    refresh();
})();
</script>
@endpush
@endsection
