@extends('layouts.rt')

@section('title', 'Opsi Pengiriman')

@section('content')
<div class="wrap">
    <a class="back-link" href="{{ route('packing.index') }}">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M19 12H5M11 18l-6-6 6-6"/></svg>
        Lanjut belanja
    </a>

    <div class="layout">
        <div>
            <x-checkout-progress :labels="['Opsi logistik', 'Alamat', 'Kurir', 'Pembayaran']" :step="1" />

            <div class="step-head">
                <h1>Pesananmu mau diambil atau dikirim?</h1>
                <p>Kalau kosmu dekat gudang, ambil sendiri lebih hemat.</p>
            </div>

            @if ($errors->any())
                <p class="err">{{ $errors->first() }}</p>
            @endif

            <form method="POST" action="{{ route('packing.logistics.choose') }}" id="logisticForm">
                @csrf
                <input type="hidden" name="logistic" id="logisticInput" value="">

                @php
                    $opts = [
                        ['id' => 'pickup', 'icon' => '🏪', 'bg' => 'bg-depot', 'label' => 'Jemput sendiri ke gudang', 'badge' => 'Gratis', 'pill' => 'pill-green', 'desc' => 'Ambil pesananmu langsung di gudang RuangTitip. Tidak ada biaya tambahan.', 'note' => 'Langsung ke pembayaran'],
                        ['id' => 'biteship', 'icon' => '🛵', 'bg' => 'bg-tape', 'label' => 'Dikirim ke alamatmu', 'badge' => '', 'pill' => '', 'desc' => 'Diantar kurir dari gudang ke alamatmu.', 'note' => 'Isi alamat, pilih kurir, lalu bayar'],
                    ];
                @endphp
                <fieldset class="choices" style="border:0;padding:0;margin-top:0">
                    <legend class="sr">Cara menerima pesanan</legend>
                    @foreach ($opts as $o)
                        <button type="button" class="choice opt" data-id="{{ $o['id'] }}" onclick="pickLogistic('{{ $o['id'] }}')">
                            <span class="ic {{ $o['bg'] }}" aria-hidden="true">{{ $o['icon'] }}</span>
                            <span class="t">
                                <strong>{{ $o['label'] }}</strong>
                                @if ($o['badge'])<span class="pill {{ $o['pill'] }}">{{ $o['badge'] }}</span>@endif
                                <p class="desc">{{ $o['desc'] }}</p>
                                <p class="meta">{{ $o['note'] }}</p>
                            </span>
                            <span class="radio" aria-hidden="true"></span>
                        </button>
                    @endforeach
                </fieldset>

                <div class="actions">
                    <a href="{{ route('packing.index') }}" class="btn btn-outline">Kembali</a>
                    <button type="submit" id="nextBtn" disabled class="btn btn-primary">Lanjutkan
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
                    </button>
                </div>
            </form>
        </div>

        @include('packing._summary', ['items' => $items])
    </div>
</div>
@endsection

@push('scripts')
<script>
    function pickLogistic(id) {
        document.getElementById('logisticInput').value = id;
        document.getElementById('nextBtn').disabled = false;
        document.querySelectorAll('.opt').forEach(function (b) { b.classList.toggle('on', b.dataset.id === id); });
    }
</script>
@endpush
