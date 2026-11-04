@extends('akun.layout')

@section('title', 'Profil')
@section('key', 'profil')
@section('sub', 'Data ini dipakai untuk pesanan, bukti penitipan, dan saat tim Ruru menghubungimu.')

@section('body')
<form method="POST" action="{{ route('akun.profil.save') }}" enctype="multipart/form-data" class="stack" novalidate>
    @csrf

    <section class="card">
        <h3>Foto profil</h3>
        <p class="help">JPG atau PNG, maksimal 1 MB.</p>
        <div class="avatar-edit">
            <span class="av big-av">@if ($user->avatar)<img src="{{ $user->avatar_url }}" alt="">@else{{ $user->initials }}@endif</span>
            <label class="btn btn-ghost upl"><svg class="ic" aria-hidden="true"><use href="#i-cam"/></svg>{{ $user->avatar ? 'Ganti foto' : 'Unggah foto' }}<input type="file" name="avatar" accept="image/png,image/jpeg" aria-label="Pilih foto profil" onchange="this.form.submit()"></label>
            @if ($user->avatar)
                <label class="btn btn-link" style="cursor:pointer">Hapus foto<input type="checkbox" name="remove_avatar" value="1" class="sr" onchange="this.form.submit()"></label>
            @endif
        </div>
        @error('avatar')<p class="msg mt">{{ $message }}</p>@enderror
    </section>

    <section class="card stack" style="gap:16px">
        <div><h3>Data diri</h3><p class="help">Nama sesuai identitas memudahkan petugas memverifikasimu saat mengambil barang.</p></div>

        <div class="field">
            <label for="pName">Nama lengkap</label>
            <input class="input" id="pName" name="name" value="{{ old('name', $user->name) }}" autocomplete="name" @error('name') aria-invalid="true" aria-describedby="e-pName" @enderror>
            @error('name')<p class="msg" id="e-pName">{{ $message }}</p>@enderror
        </div>

        <div class="field">
            <label for="pEmail">Email</label>
            <input class="input" id="pEmail" value="{{ $user->email }}" readonly aria-describedby="h-pEmail">
            <p class="help" id="h-pEmail">Email dipakai untuk masuk dan tidak bisa diubah di sini. Hubungi admin kalau perlu menggantinya.</p>
        </div>

        <div class="field">
            <label for="pWa">Nomor WhatsApp</label>
            <input class="input" id="pWa" name="phone" type="tel" inputmode="tel" autocomplete="tel" value="{{ old('phone', $user->phone) }}" placeholder="081234567890" @error('phone') aria-invalid="true" aria-describedby="e-pWa" @enderror>
            @error('phone')<p class="msg" id="e-pWa">{{ $message }}</p>@else<p class="help">Dipakai tim Ruru untuk menghubungimu soal pesanan.</p>@enderror
        </div>
    </section>

    <div class="savebar" role="region" aria-label="Simpan perubahan">
        <span>Periksa datamu, lalu simpan.</span>
        <div style="display:flex;gap:8px">
            <a class="btn btn-ghost sm" href="{{ route('profile.index') }}">Batal</a>
            <button class="btn btn-sun sm" type="submit">Simpan perubahan</button>
        </div>
    </div>
</form>
@endsection
