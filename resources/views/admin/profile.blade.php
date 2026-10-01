@extends('layouts.admin')

@section('title', 'Profil admin')

@section('content')
@php
    $initials = \Illuminate\Support\Str::of($user->name ?? 'Admin')->trim()->explode(' ')->map(fn($w) => mb_substr($w, 0, 1))->take(2)->implode('');
@endphp

<div class="ph"><div><h1>Profil admin</h1><p>Kelola informasi akun administrator.</p></div></div>

<div class="profile">
  <section class="card card-pop p-card">
    <span class="avatar lg">{{ $initials ?: 'A' }}</span>
    <h2>{{ $user->name ?? 'Admin RuangTitip' }}</h2>
    <p>{{ $user->email }}</p>
    <span class="pill o">{!! \App\Support\Icons::svg('shield', 'sm') !!} Admin gudang</span>
    <dl class="p-meta">
      <div><dt>Bergabung</dt><dd>{{ $user->created_at?->translatedFormat('d M Y') ?? '-' }}</dd></div>
      <div><dt>Login terakhir</dt><dd>{{ $user->updated_at?->diffForHumans() ?? '-' }}</dd></div>
    </dl>
  </section>

  <div class="stack">
    <section class="card">
      <div class="card-h"><div><h2>Informasi akun</h2><p>Nama ini tampil di panel admin dan riwayat aktivitas.</p></div></div>
      <form class="card-b" method="POST" action="{{ route('admin.profile.update') }}" style="display:grid;gap:16px" novalidate>
        @csrf
        <div class="field">
          <label for="fName">Nama tampilan <span class="req">*</span></label>
          <input class="input" id="fName" name="name" value="{{ old('name', $user->name) }}" required @error('name') aria-invalid="true" @enderror>
          @error('name')<span class="help" style="color:var(--danger)">{{ $message }}</span>@enderror
        </div>
        <div class="field">
          <label for="fMail">Email</label>
          <input class="input" id="fMail" value="{{ $user->email }}" disabled>
          <span class="help">Email tidak bisa diubah dari panel ini.</span>
        </div>
        <div><button class="btn btn-primary" type="submit">{!! \App\Support\Icons::svg('check', 'sm') !!} Simpan perubahan</button></div>
      </form>
    </section>

    <section class="card danger-card">
      <div class="card-b" style="display:flex;justify-content:space-between;align-items:center;gap:16px;flex-wrap:wrap">
        <div><h2 style="font-size:18px">Keluar dari panel admin</h2><p style="color:var(--body);font-size:14px;margin-top:2px">Kamu perlu login lagi untuk masuk ke panel ini.</p></div>
        <form method="POST" action="{{ route('logout') }}">
          @csrf
          <button class="btn btn-danger" type="submit">{!! \App\Support\Icons::svg('logout', 'sm') !!} Keluar</button>
        </form>
      </div>
    </section>
  </div>
</div>
@endsection
