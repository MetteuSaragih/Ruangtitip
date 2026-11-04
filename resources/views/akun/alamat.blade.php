@extends('akun.layout')

@section('title', 'Alamat')
@section('key', 'alamat')
@section('sub', 'Dipakai saat checkout Toko Preloved dengan pengiriman. Alamat utama terisi otomatis.')

@section('body')
@if ($adding)
    @php
        $isEdit = (bool) $editing;
        $oldAreaId = old('area_id', $editing?->area_id);
        $oldAreaName = old('area_name', $editing?->area_name);
        $oldPostal = old('postal_code', $editing?->postal_code);
    @endphp
    <section class="card stack" style="gap:14px">
        <h3>{{ $isEdit ? 'Ubah alamat' : 'Tambah alamat' }}</h3>
        <form method="POST" action="{{ $isEdit ? route('akun.alamat.update', $editing->id) : route('akun.alamat.store') }}" class="stack" style="gap:14px" novalidate>
            @csrf
            @if ($isEdit) @method('PUT') @endif

            <div class="field">
                <label for="aLabel">Label</label>
                <input class="input" id="aLabel" name="label" value="{{ old('label', $editing?->label) }}" placeholder="Rumah, Kos, Kantor" @error('label') aria-invalid="true" aria-describedby="e-aLabel" @enderror>
                @error('label')<p class="msg" id="e-aLabel">{{ $message }}</p>@enderror
            </div>

            <div class="field">
                <x-biteship-area-search theme="light" :old-area-id="$oldAreaId" :old-area-name="$oldAreaName" :old-postal-code="$oldPostal" />
                @error('area_id')<p class="msg">{{ $message }}</p>@enderror
            </div>

            <div class="field">
                <label for="aStreet">Alamat lengkap</label>
                <textarea class="input" id="aStreet" name="address" rows="2" placeholder="Nama jalan, nomor rumah, RT/RW, kelurahan" @error('address') aria-invalid="true" aria-describedby="e-aStreet" @enderror>{{ old('address', $editing?->address) }}</textarea>
                @error('address')<p class="msg" id="e-aStreet">{{ $message }}</p>@enderror
            </div>

            <label class="pick">
                <input type="checkbox" name="is_primary" value="1" @checked(old('is_primary', $editing?->is_primary) || $addresses->isEmpty()) @disabled($addresses->isEmpty() || $editing?->is_primary)>
                <span>Jadikan alamat utama</span>
            </label>

            <div style="display:flex;gap:8px;justify-content:flex-end;flex-wrap:wrap">
                <a class="btn btn-ghost" href="{{ route('akun.alamat') }}">Batal</a>
                <button class="btn btn-sun" type="submit">Simpan alamat</button>
            </div>
        </form>
    </section>
@endif

<section class="card">
    <div class="card-h">
        <h3>Alamat tersimpan</h3>
        @unless ($adding)
            <a class="btn btn-ghost sm" href="{{ route('akun.alamat', ['baru' => 1]) }}"><svg class="ic" aria-hidden="true"><use href="#i-plus"/></svg>Tambah alamat</a>
        @endunless
    </div>

    @if ($addresses->isNotEmpty())
        <ul class="list">
            @foreach ($addresses as $a)
                <li class="li">
                    <div>
                        <div class="top"><b>{{ $a->label }}</b>@if ($a->is_primary)<span class="badge b-ok"><i></i>Utama</span>@endif</div>
                        <p>{{ $a->address }}<br>{{ $a->area_name }}{{ $a->postal_code ? ' ' . $a->postal_code : '' }}</p>
                    </div>
                    <div class="acts">
                        @unless ($a->is_primary)
                            <form method="POST" action="{{ route('akun.alamat.main', $a->id) }}">@csrf<button class="btn btn-link" type="submit">Jadikan utama</button></form>
                        @endunless
                        <a class="btn btn-link" href="{{ route('akun.alamat', ['ubah' => $a->id]) }}">Ubah</a>
                        <form method="POST" action="{{ route('akun.alamat.destroy', $a->id) }}" onsubmit="return confirm('Hapus alamat ini?')">@csrf @method('DELETE')<button class="btn btn-link" style="color:var(--err-ink)" type="submit">Hapus</button></form>
                    </div>
                </li>
            @endforeach
        </ul>
    @else
        <div class="state">
            <div class="r-ic"><svg class="ic" aria-hidden="true"><use href="#i-pin"/></svg></div>
            <h3>Belum ada alamat</h3>
            <p>Tambahkan alamat supaya checkout Toko Preloved lebih cepat.</p>
        </div>
    @endif
</section>
@endsection
