@extends('layouts.ruang-titip')
@section('title', 'Opsi Logistik')

@php function rp($n){ return 'Rp'.number_format($n,0,',','.'); } @endphp

@section('content')
<main class="wrap">
  <a class="back-link" href="{{ route('ruang-titip.detail-item') }}">
    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5M11 18l-6-6 6-6"/></svg>
    Kembali
  </a>

  <div class="layout">
    <div>
      @include('dashboard.ruang-titip._progress', ['step' => 2])

      <div class="step-head">
        <h1>Barangnya sampai ke gudang gimana?</h1>
        <p>Pilih yang paling praktis buatmu.</p>
      </div>

      <form method="POST" action="{{ route('ruang-titip.logistik.store') }}">
        @csrf
        <input type="hidden" name="logistic" id="logistic">

        @php
            $opts = [
                ['id'=>'self','label'=>'Antar Sendiri','icon'=>'🚶','desc'=>'Kamu antar barang langsung ke Ruang Titip, tanpa biaya logistik.','note'=>'→ Langsung ke Checkout','bg'=>'bg-depot','badge'=>'Rp0','pill'=>'pill-green'],
                ['id'=>'rutip','label'=>'Packing + Anjem RuTip','icon'=>'🚚','desc'=>'Tim kami jemput & packing barangmu. Biaya = jarak (per km) + jasa packing per kardus.','note'=>'→ Input Alamat → Checkout','bg'=>'bg-tape','badge'=>'Terpopuler','pill'=>'pill-orange'],
                ['id'=>'instant','label'=>'Kurir Biteship','icon'=>'🏍️','desc'=>'Dijemput kurir instan (Gojek/Grab) dari alamatmu.','note'=>'→ Input Alamat → Pilih Kurir → Checkout','bg'=>'bg-sand','badge'=>null,'pill'=>null],
            ];
            $optLabels = ['self' => 'Antar sendiri', 'rutip' => 'Packing + Anjem RuTip', 'instant' => 'Kurir instan'];
        @endphp
        <fieldset class="choices" style="border:0;padding:0;margin-top:0">
          <legend class="sr">Cara pengiriman</legend>
          @foreach ($opts as $o)
            <button type="button" class="choice opt" data-id="{{ $o['id'] }}" data-label="{{ $optLabels[$o['id']] }}" onclick="pickLogistic('{{ $o['id'] }}')">
              <span class="ic {{ $o['bg'] }}" aria-hidden="true">{{ $o['icon'] }}</span>
              <span class="t">
                <strong>{{ $o['label'] }}</strong>
                @if ($o['badge'])<span class="pill {{ $o['pill'] }}">{{ $o['badge'] }}</span>@endif
                <p class="desc">{{ $o['desc'] }}</p>
                <p class="meta">{{ $o['note'] }}</p>
              </span>
              <span class="radio" aria-hidden="true"></span>
            </button>
          @endforeach
        </fieldset>

        <div class="actions">
          <a href="{{ route('ruang-titip.detail-item') }}" class="btn btn-outline">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
            Kembali
          </a>
          <button type="submit" id="nextBtn" disabled class="btn btn-primary">Lanjutkan
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
    function pickLogistic(id) {
        document.getElementById('logistic').value = id;
        document.getElementById('nextBtn').disabled = false;
        document.querySelectorAll('.opt').forEach(b => {
            b.classList.toggle('on', b.dataset.id === id);
        });
        const sLog = document.getElementById('s-log');
        if (sLog) sLog.textContent = document.querySelector('.opt[data-id="' + id + '"]').dataset.label;
    }
</script>
@endpush
@endsection
