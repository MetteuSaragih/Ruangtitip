@extends('layouts.admin')

@section('title', 'Pelanggan')

@section('content')
@php
    $tabDefs = ['semua' => 'Semua', 'aktif' => 'Aktif', 'diblokir' => 'Diblokir'];
    $ini = fn ($u) => $u->initials ?: 'U';
@endphp

<div class="ph">
  <div><h1>Pelanggan</h1><p>Mahasiswa yang terdaftar di RuangTitip. Klik baris untuk melihat detail.</p></div>
</div>

<div class="bar">
  <nav class="tabs" aria-label="Status akun">
    @foreach ($tabDefs as $key => $label)
      <a href="{{ route('admin.accounts', array_filter(['tab' => $key, 'search' => $search])) }}" @if ($tab === $key) aria-current="page" @endif>{{ $label }} <i>{{ $counts[$key] }}</i></a>
    @endforeach
  </nav>
  <form method="GET" action="{{ route('admin.accounts') }}" class="search" role="search">
    <svg class="ic" aria-hidden="true"><use href="#n-search"/></svg>
    <input type="search" name="search" value="{{ $search }}" placeholder="Cari nama, email, atau nomor" aria-label="Cari pelanggan">
    <input type="hidden" name="tab" value="{{ $tab }}">
  </form>
</div>

<section class="card flush">
  @if ($customers->isEmpty())
    <div class="empty"><b>Tidak ada pelanggan</b>Coba ubah kata kunci atau tab.</div>
  @else
    <div class="tw">
      <table class="tbl">
        <thead>
          <tr>
            <th>Nama</th>
            <th>WhatsApp</th>
            <th class="r">Titipan aktif</th>
            <th>Terdaftar</th>
            <th>Status</th>
            <th class="acts">Aksi</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($customers as $c)
            <tr class="link" data-href="{{ route('admin.accounts.show', $c) }}">
              <td>
                <div style="display:flex;align-items:center;gap:10px">
                  <span class="av" aria-hidden="true">{{ $ini($c) }}</span>
                  <div><b><a href="{{ route('admin.accounts.show', $c) }}">{{ $c->name }}</a></b><small>{{ $c->email }}</small></div>
                </div>
              </td>
              <td class="num">{{ $c->phone ?: '-' }}</td>
              <td class="r">{{ (int) ($activeOrders[$c->id] ?? 0) }}</td>
              <td class="num">{{ $c->created_at?->translatedFormat('d M Y') }}</td>
              <td>@if ($c->is_active)<span class="st ok">Aktif</span>@else<span class="st err">Diblokir</span>@endif</td>
              <td class="acts">
                @if (! $c->is(auth()->user()))
                  <form method="POST" action="{{ route('admin.accounts.toggle-active', $c) }}" style="display:inline"
                        data-confirm="{{ $c->is_active ? 'Blokir akun '.$c->name.'? Pelanggan tidak bisa masuk sampai blokir dibuka.' : 'Buka blokir akun '.$c->name.'?' }}"
                        data-confirm-title="{{ $c->is_active ? 'Blokir akun' : 'Buka blokir' }}"
                        data-confirm-ok="{{ $c->is_active ? 'Blokir' : 'Buka blokir' }}"
                        @if ($c->is_active) data-danger @endif>
                    @csrf
                    <button type="submit" class="btn btn-ghost sm">{{ $c->is_active ? 'Blokir' : 'Buka blokir' }}</button>
                  </form>
                @endif
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>

    <div class="pager">
      <span>{{ $customers->total() }} pelanggan &middot; halaman {{ $customers->currentPage() }} dari {{ $customers->lastPage() }}</span>
      <nav aria-label="Halaman">
        @if ($customers->previousPageUrl())<a href="{{ $customers->previousPageUrl() }}">Sebelumnya</a>@else<span class="pg off">Sebelumnya</span>@endif
        @if ($customers->nextPageUrl())<a href="{{ $customers->nextPageUrl() }}">Berikutnya</a>@else<span class="pg off">Berikutnya</span>@endif
      </nav>
    </div>
  @endif
</section>
@endsection

@push('scripts')
<script>
  document.querySelectorAll('tr.link[data-href]').forEach(function (row) {
    row.addEventListener('click', function (e) {
      if (e.target.closest('a, button, form')) return;
      window.location.href = row.dataset.href;
    });
  });
</script>
@endpush
