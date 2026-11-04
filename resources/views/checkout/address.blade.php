@extends('layouts.rt')
@section('title', 'Keranjang')

@php
    if (! function_exists('rp')) {
        function rp($n) { return 'Rp' . number_format($n, 0, ',', '.'); }
    }
@endphp

@section('content')
<div class="mp">
<div class="stack">
    @include('partials.mp-head', [
        'crumbs' => [['Toko Preloved', route('preloved.index')], ['Keranjang', route('preloved.cart.index')], ['Checkout', null]],
        'title' => 'Dikirim ke mana?',
        'sub' => 'Pilih alamat tersimpan atau tambah yang baru.',
    ])
    @include('checkout._wiz', ['step' => 2])

    <div class="two">
        <div class="stack">
            @if ($errors->any())
                <div class="alert a-err"><svg class="ic" aria-hidden="true"><use href="#i-warn"/></svg><div><b>{{ $errors->first() }}</b></div></div>
            @endif

            <form method="POST" action="{{ route('checkout.address.save') }}" id="addressForm" class="stack">
                @csrf
                <input type="hidden" name="mode" id="modeInput" value="new">
                <input type="hidden" name="address_id" id="addressId" value="{{ old('address_id') }}">

                @if ($addresses->isNotEmpty())
                    <section class="card stack" style="gap:12px">
                        <h3>Alamat tersimpan</h3>
                        <div class="picks" role="radiogroup" aria-label="Alamat tersimpan">
                            @foreach ($addresses as $address)
                                <label class="pick addr" data-id="{{ $address->id }}">
                                    <input type="radio" name="addr_pick" value="{{ $address->id }}" onchange="pickAddress('{{ $address->id }}')">
                                    <span>
                                        <b>{{ $address->label ?: 'Alamat' }}@if ($address->is_primary)<span class="tag">Utama</span>@endif</b>
                                        <small>{{ $address->address }}</small>
                                        @if ($address->note)<small>Catatan: {{ $address->note }}</small>@endif
                                        @if (! $address->area_id)<small style="color:var(--sun-ink)">Belum ada kecamatan tersimpan, lengkapi dulu lewat alamat baru.</small>@endif
                                    </span>
                                </label>
                            @endforeach
                        </div>
                    </section>
                @endif

                <section class="card stack" style="gap:12px">
                    <h3>{{ $addresses->count() ? 'Alamat baru' : 'Alamat pengiriman' }}</h3>
                    <div class="field"><label for="label">Label (opsional)</label><input class="input" type="text" id="label" name="label" placeholder="Kos / Rumah / Kontrakan" value="{{ old('label') }}"></div>
                    <x-biteship-area-search prefix="address" theme="light" />
                    <div class="field"><label for="addressInput">Alamat lengkap</label><textarea class="input" id="addressInput" name="address[full]" rows="2" placeholder="Jl. Veteran No. 10, Kec. Lowokwaru, Malang">{{ old('address.full') }}</textarea></div>
                    <div class="field"><label for="note">Catatan untuk kurir (opsional)</label><textarea class="input" id="note" name="address[note]" rows="2" placeholder="Rumah cat hijau, pagar depan, dekat masjid">{{ old('address.note') }}</textarea></div>
                    <label class="pick"><input type="checkbox" name="is_primary" value="1" {{ old('is_primary') ? 'checked' : '' }}><span>Jadikan alamat utama</span></label>
                </section>

                <div class="nav-btns">
                    <a href="{{ route('checkout.shipping') }}" class="btn btn-ghost">Kembali</a>
                    <button type="submit" id="continueBtn" disabled class="btn btn-sun">Simpan dan lanjutkan</button>
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
        refresh();
    };

    addr.addEventListener('input', () => {
        if (addr.value.trim().length > 0) {
            mode.value = 'new';
            addressId.value = '';
            document.querySelectorAll('input[name="addr_pick"]').forEach(r => { r.checked = false; });
        }
        refresh();
    });

    refresh();
})();
</script>
@endpush
