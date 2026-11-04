@extends('layouts.rt')
@section('title', 'Ruang Titip')

@php
    if (! function_exists('rp')) {
        function rp($n) { return 'Rp' . number_format($n, 0, ',', '.'); }
    }
@endphp

@section('content')
@include('dashboard.ruang-titip._icons')
<div class="ttp">
<div class="stack">
    <nav class="crumbs" aria-label="Breadcrumb"><a href="{{ route('ruang-titip.index') }}">Ruang Titip</a><span aria-hidden="true">/</span>@if ($storage)<a href="{{ route('ruang-titip.detail', $storage) }}">{{ $storage->name }}</a>@else<span>Pesan</span>@endif<span aria-hidden="true">/</span><span aria-current="page">Pesan</span></nav>
    <div class="ph">
        <div>
            <h2 class="pt" tabindex="-1">Barangnya dijemput di mana?</h2>
            <p class="ps">Isi selengkap mungkin biar tim tidak nyasar.</p>
        </div>
    </div>

    @include('dashboard.ruang-titip._progress', ['step' => 3])

    <div class="two">
        <div class="stack">
            @if ($errors->any())
                <div class="alert a-err"><svg class="ic" aria-hidden="true"><use href="#i-warn"/></svg><div><b>{{ $errors->first() }}</b></div></div>
            @endif

            @if ($addresses->count())
                <form method="POST" action="{{ route('ruang-titip.alamat.store') }}">
                    @csrf
                    <input type="hidden" name="mode" value="select">
                    <section class="card stack" style="gap:14px">
                        <h3>Alamat tersimpan</h3>
                        <div class="picks" role="radiogroup" aria-label="Alamat tersimpan">
                            @foreach ($addresses as $addr)
                                <label class="pick">
                                    <input type="radio" name="address_id" value="{{ $addr->id }}" data-has-area="{{ $addr->area_id ? '1' : '0' }}">
                                    <span>
                                        <b>{{ $addr->label ?: 'Alamat' }}</b>
                                        <small>{{ $addr->address }}</small>
                                        @if ($addr->note)<small>Catatan: {{ $addr->note }}</small>@endif
                                        @if (! $addr->area_id)<small>Belum ada kecamatan tersimpan, tidak bisa dipakai untuk kurir instan.</small>@endif
                                    </span>
                                    @if ($addr->is_primary)<span class="badge b-ok"><i></i>Utama</span>@endif
                                </label>
                            @endforeach
                        </div>
                        <button type="submit" id="useSelected" disabled class="btn btn-primary wide">Gunakan alamat ini</button>
                    </section>
                </form>
            @endif

            <form method="POST" action="{{ route('ruang-titip.alamat.store') }}" class="stack">
                @csrf
                <input type="hidden" name="mode" value="new">
                <section class="card stack" style="gap:16px">
                    <h3>{{ $addresses->count() ? 'Daftarkan alamat baru' : 'Masukkan alamat penjemputan' }}</h3>
                    <div class="field">
                        <label for="label">Label (opsional)</label>
                        <input class="input" type="text" id="label" name="label" placeholder="Kos / Rumah / Kontrakan" value="{{ old('label') }}">
                    </div>
                    <div><x-biteship-area-search theme="light" /></div>
                    <div class="field">
                        <label for="address">Alamat lengkap</label>
                        <textarea class="input" style="height:auto;padding:12px" id="address" name="address" rows="3" placeholder="Jl. Veteran No. 10, Kec. Lowokwaru, Malang">{{ old('address') }}</textarea>
                    </div>
                    <div class="field">
                        <label for="note">Catatan untuk kurir (opsional)</label>
                        <textarea class="input" style="height:auto;padding:12px" id="note" name="note" rows="2" placeholder="Rumah cat hijau, pagar depan, dekat masjid">{{ old('note') }}</textarea>
                    </div>
                    <label class="pick">
                        <input type="checkbox" name="is_primary" value="1">
                        <span>Jadikan alamat utama</span>
                    </label>
                </section>
                <div class="nav-btns">
                    <a href="{{ route('ruang-titip.logistik') }}" class="btn btn-ghost">Kembali</a>
                    <button type="submit" class="btn btn-sun">Simpan dan lanjutkan</button>
                </div>
            </form>
        </div>

        @include('dashboard.ruang-titip._summary', ['storage' => $storage, 's' => $s, 'calc' => $calc])
    </div>
</div>
</div>

@push('scripts')
<script>
    (function () {
        const btn = document.getElementById('useSelected');
        if (!btn) return;
        document.querySelectorAll('input[name="address_id"]').forEach(r => r.addEventListener('change', () => {
            btn.disabled = r.dataset.hasArea !== '1';
        }));
    })();
</script>
@endpush
@endsection
