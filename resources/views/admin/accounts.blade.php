@extends('layouts.admin')

@section('title', 'Manajemen Akun')

@php
    $tabs = [
        ['key' => 'customers', 'label' => 'Pelanggan', 'desc' => 'Mahasiswa pengguna aplikasi', 'icon' => 'users'],
        ['key' => 'staff', 'label' => 'Staf', 'desc' => 'Admin dan karyawan internal', 'icon' => 'shield'],
        ['key' => 'reviews', 'label' => 'Moderasi ulasan', 'desc' => 'Kurasi ulasan pelanggan', 'icon' => 'star'],
    ];
    $initials = fn ($name, $email = '') => \Illuminate\Support\Str::of(trim((string) ($name ?: $email ?: 'User')))->explode(' ')->map(fn($w) => mb_strtoupper(mb_substr($w, 0, 1)))->take(2)->implode('');
@endphp

@section('content')
<div class="ph"><div><h1>Manajemen Akun</h1><p>Kontrol akses, moderasi pengguna, dan ulasan pelanggan.</p></div></div>

<div class="seg" role="tablist" aria-label="Bagian halaman">
  @foreach ($tabs as $item)
    <a role="tab" aria-selected="{{ $tab === $item['key'] ? 'true' : 'false' }}" href="{{ route('admin.accounts', ['tab' => $item['key']]) }}">
      {!! \App\Support\Icons::svg($item['icon']) !!}
      <span>{{ $item['label'] }}<small>{{ $item['desc'] }}</small></span>
    </a>
  @endforeach
</div>

@if ($tab === 'customers')
  <section class="card">
    <div class="card-h">
      <div><h2>Pengguna terdaftar</h2><p>{{ $activeCustomers }} aktif &middot; <span style="color:var(--danger);font-weight:700">{{ $suspendedCustomers }} suspended</span> &middot; {{ $totalCustomers }} total</p></div>
      <form method="GET" action="{{ route('admin.accounts') }}" class="search">
        <span class="sr">Cari pengguna</span>
        <input type="hidden" name="tab" value="customers">
        {!! \App\Support\Icons::svg('search', 'sm') !!}
        <input type="search" name="search" value="{{ $search }}" placeholder="Cari nama, email, atau nomor WA">
      </form>
    </div>
    <div class="tbl-wrap">
      @if ($customers->isNotEmpty())
        <table>
          <thead><tr><th>Nama</th><th>Kontak</th><th>Bergabung</th><th class="num">Pesanan</th><th>Status</th><th><span class="sr">Aksi</span></th></tr></thead>
          <tbody>
            @foreach ($customers as $user)
              @php $active = (bool) $user->is_active; $orders = (int) ($orderCounts[$user->id] ?? 0); @endphp
              <tr style="opacity:{{ $active ? 1 : .55 }}">
                <td>
                  <div class="who">
                    <span class="avatar c{{ ($user->id % 4) + 1 }}">{{ $initials($user->name, $user->email) }}</span>
                    <div><b>{{ $user->name ?: 'Pengguna RuangTitip' }}</b><small>ID #{{ $user->id }}</small></div>
                  </div>
                </td>
                <td>
                  <div class="contact">{!! \App\Support\Icons::svg('mail', 'sm') !!}{{ $user->email }}</div>
                  <div class="contact" style="color:var(--muted)">{!! \App\Support\Icons::svg('phone', 'sm') !!}{{ $user->phone ?: '-' }}</div>
                </td>
                <td>{{ $user->created_at?->translatedFormat('d M Y') ?? '-' }}</td>
                <td class="num"><b>{{ $orders }}</b></td>
                <td>
                  @if ($active)
                    <span class="pill g">{!! \App\Support\Icons::svg('check', 'sm') !!} Aktif</span>
                  @else
                    <span class="pill r">{!! \App\Support\Icons::svg('ban', 'sm') !!} Suspended</span>
                  @endif
                </td>
                <td>
                  <div class="t-actions">
                    <form method="POST" action="{{ route('admin.accounts.toggle-active', $user) }}" @if($active) data-confirm="Suspend {{ addslashes($user->name ?: 'pengguna ini') }}?|Pengguna ini tidak bisa login dan membuat pesanan baru sampai diaktifkan lagi." data-confirm-ok="Suspend" @endif>
                      @csrf
                      <button class="btn btn-sm {{ $active ? 'btn-danger' : 'btn-ghost' }}" type="submit">{{ $active ? 'Suspend' : 'Aktifkan' }}</button>
                    </form>
                  </div>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      @else
        {!! \App\Support\Icons::empty('users', 'Tidak ada pengguna', $search ? 'Coba kata kunci pencarian lain.' : 'Data pengguna akan muncul setelah pelanggan mendaftar.') !!}
      @endif
    </div>
    <div class="card-f"><span>{{ $customers->count() }} pengguna ditampilkan</span></div>
  </section>
@elseif ($tab === 'staff')
  <section class="card">
    <div class="card-h"><div><h2>Staf dan admin internal</h2><p>{{ $staff->count() }} akun admin terdaftar</p></div></div>
    @if ($staff->isNotEmpty())
      <ul class="staff">
        @foreach ($staff as $admin)
          <li>
            <span class="avatar c{{ ($admin->id % 4) + 1 }}">{{ $initials($admin->name, $admin->email) }}</span>
            <div class="t-main">
              <b>{{ $admin->name ?: 'Admin RuangTitip' }}{{ $admin->is(Auth::user()) ? ' · Kamu' : '' }}</b>
              <small>{{ $admin->email }}</small>
            </div>
            <span class="pill k">Admin</span>
          </li>
        @endforeach
      </ul>
    @else
      {!! \App\Support\Icons::empty('shield', 'Belum ada staf admin', 'Tambahkan akun admin untuk mengelola platform.') !!}
    @endif
  </section>
@else
  <section class="card">
    <div class="empty" style="padding:64px 24px">
      <img class="ruru" src="{{ asset('assets/ruru.webp') }}" alt="">
      <b>Belum ada ulasan</b>
      <p>Ulasan pelanggan akan muncul di sini setelah fitur review aktif. Kamu bisa menyetujui atau menyembunyikan ulasan sebelum tayang.</p>
    </div>
  </section>
@endif
@endsection
