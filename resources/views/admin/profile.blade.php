@extends('layouts.admin')

@section('title', 'Profil admin')

@push('styles')
<style>
    .pf-av { width: 72px; height: 72px; border-radius: 50%; object-fit: cover; display: grid; place-items: center; background: var(--color-primary); color: #fff; font-family: var(--display); font-weight: 800; font-size: 26px; }
</style>
@endpush

@section('content')
<div class="ph"><div><h1>Profil admin</h1><p>Kelola informasi akun administrator.</p></div></div>

<div class="cols">
  <section class="card">
    <h2 class="sec-t">Informasi akun</h2>
    <form method="POST" action="{{ route('admin.profile.update') }}" class="stack" novalidate>
      @csrf
      <div style="display:flex;align-items:center;gap:14px">
        @if ($user->avatar_url)
          <img class="pf-av" src="{{ $user->avatar_url }}" alt="Foto profil {{ $user->name }}">
        @else
          <span class="pf-av" aria-hidden="true">{{ $user->initials }}</span>
        @endif
        <div><b>{{ $user->name }}</b><p class="muted">Admin RuangTitip</p></div>
      </div>
      <div class="field">
        <label for="fName">Nama tampilan</label>
        <input class="input" id="fName" name="name" value="{{ old('name', $user->name) }}" required @error('name') aria-invalid="true" @enderror>
        @error('name')<span class="err-t">{{ $message }}</span>@enderror
      </div>
      <div class="field">
        <label for="fMail">Email</label>
        <input class="input" id="fMail" value="{{ $user->email }}" readonly>
        <span class="help">Email tidak bisa diubah dari panel ini.</span>
      </div>
      <div><button class="btn btn-primary" type="submit">Simpan perubahan</button></div>
    </form>
  </section>

  <aside>
    <section class="card">
      <h2 class="sec-t">Akun</h2>
      <div class="kv">
        <div><span>Bergabung</span><b>{{ $user->created_at?->translatedFormat('d M Y') ?? '-' }}</b></div>
        <div><span>Masuk dengan</span><b>{{ $user->isGoogleUser() ? 'Google' : 'Email' }}</b></div>
      </div>
    </section>
    <section class="card">
      <h2 class="sec-t">Keluar</h2>
      <p class="muted">Kamu perlu masuk lagi untuk membuka panel ini.</p>
      <form method="POST" action="{{ route('logout') }}" style="margin-top:10px">
        @csrf
        <button class="btn btn-ghost w" type="submit"><svg class="ic" aria-hidden="true"><use href="#i-out"/></svg> Keluar</button>
      </form>
    </section>
  </aside>
</div>
@endsection
