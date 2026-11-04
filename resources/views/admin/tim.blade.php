@extends('layouts.admin')

@section('title', 'Tim admin')

@section('content')
<div class="ph">
  <div><h1>Tim admin</h1><p>Orang yang dapat mengakses panel admin RuangTitip.</p></div>
  <div class="acts"><button type="button" class="btn btn-primary" data-open="dlgTambahAdmin"><svg class="ic" aria-hidden="true"><use href="#i-plus"/></svg> Tambah admin</button></div>
</div>

<div class="stack">
  <section class="card flush">
    <div class="tw">
      <table class="tbl">
        <thead>
          <tr>
            <th>Nama</th>
            <th>{{ $hasLastLogin ? 'Terakhir aktif' : 'Bergabung' }}</th>
            <th>Status</th>
            <th class="acts">Aksi</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($admins as $a)
            <tr>
              <td>
                <div style="display:flex;align-items:center;gap:10px">
                  <span class="av" aria-hidden="true">{{ $a->initials }}</span>
                  <div><b>{{ $a->name }}</b>@if ($a->is(auth()->user())) <span class="tag">Kamu</span>@endif<small>{{ $a->email }}</small></div>
                </div>
              </td>
              <td class="num">
                @if ($hasLastLogin && $a->last_login_at)
                  {{ \Illuminate\Support\Carbon::parse($a->last_login_at)->diffForHumans() }}
                @else
                  {{ $a->created_at?->translatedFormat('d M Y') }}
                @endif
              </td>
              <td>@if ($a->is_active)<span class="st ok">Aktif</span>@else<span class="st err">Nonaktif</span>@endif</td>
              <td class="acts">
                @if (! $a->is(auth()->user()) && $admins->count() > 1)
                  <form method="POST" action="{{ route('admin.tim.revoke', $a) }}" style="display:inline"
                        data-confirm="Cabut akses admin {{ $a->name }}? Akunnya kembali menjadi pelanggan biasa."
                        data-confirm-title="Cabut akses admin" data-confirm-ok="Cabut akses" data-danger>
                    @csrf
                    <button type="submit" class="btn btn-ghost sm">Cabut akses</button>
                  </form>
                @endif
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </section>

  <section class="card flush">
    <div class="card-h"><h2>Hak akses admin</h2></div>
    <div class="tw">
      <table class="tbl">
        <thead><tr><th>Peran</th><th>Modul</th><th>Akses</th></tr></thead>
        <tbody>
          <tr>
            <td><b>Admin</b></td>
            <td>Pesanan titip, gudang, packing, Toko Preloved, keuangan, pelanggan, tim admin</td>
            <td><span class="st ok">Semua modul</span></td>
          </tr>
        </tbody>
      </table>
    </div>
    <p class="help" style="padding:12px 18px">Saat ini hanya ada satu peran. Semua admin dapat mengakses seluruh modul.</p>
  </section>
</div>

<dialog class="dlg" id="dlgTambahAdmin">
  <form method="POST" action="{{ route('admin.tim.store') }}">
    @csrf
    <div class="dh"><h3>Tambah admin</h3><button type="button" class="btn btn-text" data-close>Tutup</button></div>
    <div class="db">
      <div class="field">
        <label for="adminEmail">Email pengguna terdaftar</label>
        <input class="input" id="adminEmail" type="email" name="email" value="{{ old('email') }}" required @error('email') aria-invalid="true" @enderror>
        @error('email')<span class="err-t">{{ $message }}</span>@enderror
        <span class="help">Pengguna harus sudah punya akun. Perannya akan diubah menjadi admin.</span>
      </div>
    </div>
    <div class="df">
      <button type="button" class="btn btn-ghost" data-close>Batal</button>
      <button type="submit" class="btn btn-primary">Jadikan admin</button>
    </div>
  </form>
</dialog>
@endsection

@push('scripts')
@if ($errors->has('email'))
<script>document.addEventListener('DOMContentLoaded', function () { var d = document.getElementById('dlgTambahAdmin'); if (d && d.showModal) d.showModal(); });</script>
@endif
@endpush
