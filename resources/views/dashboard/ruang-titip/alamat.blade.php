@extends('layouts.ruang-titip')
@section('title', 'Alamat Penjemputan')

@php function rp($n){ return 'Rp'.number_format($n,0,',','.'); } @endphp

@section('content')
<<<<<<< HEAD
<main class="wrap">
  <a class="back-link" href="{{ route('ruang-titip.logistik') }}">
    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5M11 18l-6-6 6-6"/></svg>
    Kembali
  </a>
=======
<div class="max-w-xl mx-auto pt-6 pb-8">
    @include('dashboard.ruang-titip._progress', ['step' => 3])
    <h1 class="text-xl font-extrabold text-white font-display mb-1">Alamat Penjemputan</h1>
    <p class="text-xs mb-5" style="color:rgba(255,255,255,0.4);">Langkah 3 dari 4 - Pilih alamat tersimpan atau tambah baru</p>
>>>>>>> hostinger/main

  <div class="layout">
    <div>
      @include('dashboard.ruang-titip._progress', ['step' => 3])

      <div class="step-head">
        <h1>Barangnya dijemput di mana?</h1>
        <p>Isi selengkap mungkin biar tim nggak nyasar.</p>
      </div>

      @if ($errors->any())
        <p class="err" style="margin-top:12px">{{ $errors->first() }}</p>
      @endif

      @if ($addresses->count())
        <form method="POST" action="{{ route('ruang-titip.alamat.store') }}">
          @csrf
          <input type="hidden" name="mode" value="select">
          <input type="hidden" name="address_id" id="addressId">
          <div class="panel">
            <h2 style="margin-bottom:4px">Alamat Tersimpan</h2>
            <fieldset class="choices" style="border:0;padding:0;margin-top:14px">
              <legend class="sr">Alamat tersimpan</legend>
              @foreach ($addresses as $addr)
                <button type="button" class="choice addr" data-id="{{ $addr->id }}" data-has-area="{{ $addr->area_id ? '1' : '0' }}" onclick="pickAddr('{{ $addr->id }}', {{ $addr->area_id ? 'true' : 'false' }})">
                  <span class="ic bg-sand" aria-hidden="true">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="var(--tape-dark)" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 21s-7-6.2-7-11.5a7 7 0 0 1 14 0C19 14.8 12 21 12 21z"/><circle cx="12" cy="9.5" r="2.5"/></svg>
                  </span>
                  <span class="t">
                    <strong>{{ $addr->label ?: 'Alamat' }}</strong>
                    @if ($addr->is_primary)<span class="pill pill-green">Utama</span>@endif
                    <p class="desc">{{ $addr->address }}</p>
                    @if ($addr->note)<p class="meta">Catatan: {{ $addr->note }}</p>@endif
                    @if (! $addr->area_id)<p class="meta" style="color:var(--tape-dark)">Belum ada kecamatan tersimpan, tidak bisa dipakai untuk kurir instan.</p>@endif
                  </span>
                  <span class="radio" aria-hidden="true"></span>
                </button>
              @endforeach
            </fieldset>
            <button type="submit" id="useSelected" disabled class="btn btn-primary" style="width:100%;margin-top:16px">Gunakan Alamat Ini
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
            </button>
          </div>
        </form>

        <div style="display:flex;align-items:center;gap:14px;margin:24px 0;color:var(--muted);font-size:14px">
          <span style="flex:1;height:1px;background:var(--line)"></span>
          atau tambah alamat baru
          <span style="flex:1;height:1px;background:var(--line)"></span>
        </div>
      @endif

      <form method="POST" action="{{ route('ruang-titip.alamat.store') }}">
        @csrf
        <input type="hidden" name="mode" value="new">
        <div class="panel">
          <h2>{{ $addresses->count() ? 'Daftarkan Alamat Baru' : 'Masukkan Alamat Penjemputan' }}</h2>
          <div class="field" style="margin-top:16px">
            <label for="label">Label <span style="font-weight:400;color:var(--muted)">(opsional)</span></label>
            <input type="text" id="label" name="label" placeholder="Kos / Rumah / Kontrakan" value="{{ old('label') }}">
          </div>
          <div style="margin-top:16px"><x-biteship-area-search theme="light" /></div>
          <div class="field" style="margin-top:16px">
            <label for="address">Alamat lengkap <span style="color:var(--danger)">*</span></label>
            <textarea id="address" name="address" rows="3" placeholder="Jl. Veteran No. 10, Kec. Lowokwaru, Malang">{{ old('address') }}</textarea>
          </div>
          <div class="field" style="margin-top:16px">
            <label for="note">Catatan untuk kurir <span style="font-weight:400;color:var(--muted)">(opsional)</span></label>
            <textarea id="note" name="note" rows="2" placeholder="Rumah cat hijau, pagar depan, dekat masjid">{{ old('note') }}</textarea>
          </div>
          <label style="display:flex;align-items:center;gap:10px;margin-top:16px;cursor:pointer">
            <input type="checkbox" name="is_primary" value="1" style="width:18px;height:18px;accent-color:var(--tape)">
            <span style="font-size:14px;color:var(--body)">Jadikan alamat utama</span>
          </label>
        </div>
        <div class="actions">
          <a href="{{ route('ruang-titip.logistik') }}" class="btn btn-outline">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
            Kembali
          </a>
          <button type="submit" class="btn btn-primary">Simpan &amp; Lanjutkan
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
          </button>
        </div>
      </form>
    </div>

    @include('dashboard.ruang-titip._summary', ['storage' => $storage, 's' => $s, 'calc' => $calc])
  </div>
</main>

@push('scripts')
<script>
    function pickAddr(id, hasArea) {
        document.getElementById('addressId').value = id;
        document.getElementById('useSelected').disabled = !hasArea;
        document.querySelectorAll('.addr').forEach(b => {
            b.classList.toggle('on', b.dataset.id === String(id));
        });
    }
</script>
@endpush
@endsection
