@extends('akun.layout')

@section('title', 'Rekening')
@section('key', 'rekening')
@section('sub', 'Tujuan pembayaran saat Ruang Titip membeli barangmu lewat Toko Preloved.')

@section('body')
<div class="alert a-info">
    <svg class="ic" aria-hidden="true"><use href="#i-info"/></svg>
    <div><b>Hanya untuk menerima pembayaran</b><p>Rekening ini tidak dipakai untuk menarik dana. Pembayaran tagihanmu tetap lewat QRIS.</p></div>
</div>

@if ($adding)
    @php $isEdit = (bool) $editing; @endphp
    <section class="card stack">
        <h3>{{ $isEdit ? 'Ubah rekening' : 'Tambah rekening' }}</h3>
        <form method="POST" action="{{ $isEdit ? route('akun.rekening.update', $editing->id) : route('akun.rekening.store') }}" class="stack tight" novalidate>
            @csrf
            @if ($isEdit) @method('PUT') @endif

            <div class="grid2">
                <div class="field">
                    <label for="bBank">Bank atau e-wallet</label>
                    <select class="input" id="bBank" name="bank" @error('bank') aria-invalid="true" aria-describedby="e-bBank" @enderror>
                        <option value="">Pilih</option>
                        @foreach ($bankNames as $b)
                            <option value="{{ $b }}" @selected(old('bank', $editing?->bank) === $b)>{{ $b }}</option>
                        @endforeach
                    </select>
                    @error('bank')<p class="msg" id="e-bBank">{{ $message }}</p>@enderror
                </div>
                <div class="field">
                    <label for="bNo">Nomor rekening atau akun</label>
                    <input class="input" id="bNo" name="account_number" inputmode="numeric" value="{{ old('account_number', $editing?->account_number) }}" @error('account_number') aria-invalid="true" aria-describedby="e-bNo" @enderror>
                    @error('account_number')<p class="msg" id="e-bNo">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="field">
                <label for="bName">Atas nama</label>
                <input class="input" id="bName" name="holder" value="{{ old('holder', $editing?->holder) }}" aria-describedby="h-bName @error('holder') e-bName @enderror" @error('holder') aria-invalid="true" @enderror>
                <p class="help" id="h-bName">Harus sama dengan nama di rekening. Nama yang berbeda bisa membuat pembayaran tertunda.</p>
                @error('holder')<p class="msg" id="e-bName">{{ $message }}</p>@enderror
            </div>

            <label class="pick">
                <input type="checkbox" name="is_primary" value="1" @checked(old('is_primary', $editing?->is_primary) || $banks->isEmpty()) @disabled($banks->isEmpty() || $editing?->is_primary)>
                <span>Jadikan rekening utama</span>
            </label>

            <div style="display:flex;gap:8px;justify-content:flex-end;flex-wrap:wrap">
                <a class="btn btn-ghost" href="{{ route('akun.rekening') }}">Batal</a>
                <button class="btn btn-sun" type="submit">Simpan rekening</button>
            </div>
        </form>
    </section>
@endif

<section class="card">
    <div class="card-h">
        <h3>Rekening tersimpan</h3>
        @unless ($adding)
            <a class="btn btn-ghost sm" href="{{ route('akun.rekening', ['baru' => 1]) }}"><svg class="ic" aria-hidden="true"><use href="#i-plus"/></svg>Tambah rekening</a>
        @endunless
    </div>

    @if ($banks->isNotEmpty())
        <ul class="list">
            @foreach ($banks as $b)
                <li class="li">
                    <div>
                        <div class="top"><b>{{ $b->bank }}</b>@if ($b->is_primary)<span class="badge b-ok"><i></i>Utama</span>@endif</div>
                        <p>•••• {{ substr($b->account_number, -4) }}, a.n. {{ $b->holder }}</p>
                    </div>
                    <div class="acts">
                        @unless ($b->is_primary)
                            <form method="POST" action="{{ route('akun.rekening.main', $b->id) }}">@csrf<button class="btn btn-link" type="submit">Jadikan utama</button></form>
                        @endunless
                        <a class="btn btn-link" href="{{ route('akun.rekening', ['ubah' => $b->id]) }}">Ubah</a>
                        <form method="POST" action="{{ route('akun.rekening.destroy', $b->id) }}" onsubmit="return confirm('Hapus rekening ini?')">@csrf @method('DELETE')<button class="btn btn-link" style="color:var(--err-ink)" type="submit">Hapus</button></form>
                    </div>
                </li>
            @endforeach
        </ul>
    @else
        <div class="state">
            <div class="r-ic"><svg class="ic" aria-hidden="true"><use href="#i-bank"/></svg></div>
            <h3>Belum ada rekening</h3>
            <p>Tambahkan rekening sebelum menjual barang supaya pembayaran ke kamu tidak tertunda.</p>
        </div>
    @endif
</section>
@endsection
