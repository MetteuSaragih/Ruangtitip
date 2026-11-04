@extends('layouts.admin')

@section('title', $customer->name)

@section('content')
@php
    $wa = \App\Support\Phone::wa($customer->phone);
    $rupiah = fn ($n) => 'Rp ' . number_format((int) $n, 0, ',', '.');
    $isSelf = $customer->is(auth()->user());
@endphp

<div class="ph">
  <div>
    <p><a href="{{ route('admin.accounts') }}" class="btn-text">Kembali ke daftar pelanggan</a></p>
    <h1>{{ $customer->name }}</h1>
    <p>{{ $customer->email }}</p>
  </div>
  <div class="acts">
    @if ($wa)<a class="btn btn-ghost" href="{{ $wa }}" target="_blank" rel="noopener">WhatsApp</a>@endif
    @unless ($isSelf)
      <form method="POST" action="{{ route('admin.accounts.toggle-active', $customer) }}"
            data-confirm="{{ $customer->is_active ? 'Blokir akun ini? Pelanggan tidak bisa masuk sampai blokir dibuka.' : 'Buka blokir akun ini?' }}"
            data-confirm-title="{{ $customer->is_active ? 'Blokir akun' : 'Buka blokir' }}"
            data-confirm-ok="{{ $customer->is_active ? 'Blokir' : 'Buka blokir' }}"
            @if ($customer->is_active) data-danger @endif>
        @csrf
        <button type="submit" class="btn {{ $customer->is_active ? 'btn-danger' : 'btn-primary' }}">{{ $customer->is_active ? 'Blokir akun' : 'Buka blokir' }}</button>
      </form>
    @endunless
  </div>
</div>

<div class="stack">
  @unless ($customer->is_active)
    <div class="al err">
      <svg class="ic" aria-hidden="true"><use href="#i-warn"/></svg>
      <div><b>Akun ini diblokir</b><p>Pelanggan tidak dapat masuk ke aplikasi.</p></div>
    </div>
  @endunless

  <div class="cols">
    <div class="stack">
      <section class="card flush">
        <div class="card-h"><h2>Pesanan titip</h2></div>
        @if ($titipan->isEmpty())
          <div class="empty">Belum ada pesanan titip.</div>
        @else
          <div class="tw">
            <table class="tbl">
              <thead><tr><th>Kode</th><th>Gudang</th><th>Periode</th><th class="r">Total</th><th>Status</th></tr></thead>
              <tbody>
                @foreach ($titipan as $t)
                  @php [$label, $tone] = $t->displayStatus(); @endphp
                  <tr>
                    <td>
                      @if (Route::has('admin.titip.show'))
                        <b><a href="{{ route('admin.titip.show', $t) }}">{{ $t->code() }}</a></b>
                      @else
                        <b>{{ $t->code() }}</b>
                      @endif
                    </td>
                    <td>{{ $t->storage?->name ?? '-' }}</td>
                    <td class="num">{{ $t->date_start?->format('d/m/Y') }} - {{ $t->date_end?->format('d/m/Y') }}</td>
                    <td class="r">{{ $rupiah($t->total) }}</td>
                    <td><span class="st {{ $tone }}">{{ $label }}</span></td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        @endif
      </section>

      <section class="card flush">
        <div class="card-h"><h2>Pembelian Toko Preloved</h2></div>
        @if ($purchases->isEmpty())
          <div class="empty">Belum ada pembelian.</div>
        @else
          <div class="tw">
            <table class="tbl">
              <thead><tr><th>Nomor</th><th>Tanggal</th><th class="r">Total</th><th>Pembayaran</th><th>Status</th></tr></thead>
              <tbody>
                @foreach ($purchases as $o)
                  <tr>
                    <td><b>{{ $o->order_number ?? ('#'.$o->id) }}</b></td>
                    <td class="num">{{ $o->created_at?->translatedFormat('d M Y') }}</td>
                    <td class="r">{{ $rupiah($o->total) }}</td>
                    <td>{{ $o->payment_status ?: '-' }}</td>
                    <td>{{ $o->status ?: '-' }}</td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        @endif
      </section>

      <section class="card flush">
        <div class="card-h"><h2>Pengajuan jual</h2></div>
        @if ($submissions->isEmpty())
          <div class="empty">Belum ada pengajuan jual.</div>
        @else
          <div class="tw">
            <table class="tbl">
              <thead><tr><th>Kode</th><th>Barang</th><th>Tanggal</th><th>Status</th></tr></thead>
              <tbody>
                @foreach ($submissions as $s)
                  @php $meta = \App\Models\PrelovedSubmission::STATUS[$s->status] ?? [ucfirst((string) $s->status), 'mute']; @endphp
                  <tr>
                    <td><b class="mono">{{ $s->code }}</b></td>
                    <td>{{ $s->name }}</td>
                    <td class="num">{{ $s->created_at?->translatedFormat('d M Y') }}</td>
                    <td><span class="st {{ $meta[1] }}">{{ $meta[0] }}</span></td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        @endif
      </section>
    </div>

    <aside>
      <section class="card">
        <h2 class="sec-t">Profil</h2>
        <div class="kv">
          <div><span>Status</span><b>@if ($customer->is_active)<span class="st ok">Aktif</span>@else<span class="st err">Diblokir</span>@endif</b></div>
          <div><span>WhatsApp</span><b>{{ $customer->phone ?: '-' }}</b></div>
          <div><span>Kampus</span><b>{{ $customer->campus ?: '-' }}</b></div>
          <div><span>Jurusan</span><b>{{ $customer->major ?: '-' }}</b></div>
          <div><span>Angkatan</span><b>{{ $customer->cohort_year ?: '-' }}</b></div>
          <div><span>Daerah asal</span><b>{{ $customer->hometown ?: '-' }}</b></div>
          <div><span>Terdaftar</span><b>{{ $customer->created_at?->translatedFormat('d M Y') }}</b></div>
          <div><span>Masuk dengan</span><b>{{ $customer->isGoogleUser() ? 'Google' : 'Email' }}</b></div>
        </div>
      </section>

      <section class="card">
        <h2 class="sec-t">Alamat tersimpan</h2>
        @forelse ($addresses as $a)
          <div class="kv">
            <div>
              <span>{{ $a->label ?: 'Alamat' }}@if ($a->is_primary) <span class="tag ok">Utama</span>@endif</span>
              <b><small>{{ $a->address }}</small></b>
            </div>
          </div>
        @empty
          <p class="muted">Belum ada alamat tersimpan.</p>
        @endforelse
      </section>
    </aside>
  </div>
</div>
@endsection
