@extends('layouts.ruang-titip')
@section('title', 'Opsi Logistik')

<<<<<<< HEAD
@php function rp($n){ return 'Rp'.number_format($n,0,',','.'); } @endphp
=======
@section('content')
<div class="max-w-xl mx-auto pt-6 pb-8">
    @include('dashboard.ruang-titip._progress', ['step' => 2])
    <h1 class="text-xl font-extrabold text-white font-display mb-1">Opsi Logistik</h1>
    <p class="text-xs mb-6" style="color:rgba(255,255,255,0.4);">Langkah 2 dari 4 - Pilih cara barang sampai ke gudang</p>
>>>>>>> hostinger/main

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
<<<<<<< HEAD

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
=======
        <div class="space-y-3 mb-6">
            @php
                $opts = [
                    ['id'=>'self','label'=>'Antar Sendiri','icon'=>'🚶','desc'=>'Kamu antar barang langsung ke Ruang Titip, tanpa biaya logistik.','note'=>'→ Langsung ke Checkout','color'=>'#34d399','badge'=>'Rp0'],
                    ['id'=>'rutip','label'=>'Packing + Anjem RuTip','icon'=>'🚚','desc'=>'Tim kami jemput & packing barangmu. Biaya = jarak (per km) + jasa packing per kardus.','note'=>'→ Input Alamat → Checkout','color'=>'#a78bfa','badge'=>'Terpopuler'],
                    ['id'=>'instant','label'=>'Kurir Biteship','icon'=>'🏍️','desc'=>'Dijemput kurir instan (Gojek/Grab) dari alamatmu.','note'=>'→ Input Alamat → Pilih Kurir → Checkout','color'=>'#7c3aed','badge'=>null],
                ];
            @endphp
            @foreach ($opts as $o)
                <button type="button" data-id="{{ $o['id'] }}" data-color="{{ $o['color'] }}" onclick="pickLogistic('{{ $o['id'] }}','{{ $o['color'] }}')"
                        class="opt w-full flex items-start gap-4 p-4 rounded-2xl text-left transition-all hover:scale-[1.01]"
                        style="background:rgba(255,255,255,0.04);border:1.5px solid rgba(255,255,255,0.09);">
                    <span class="text-3xl shrink-0 mt-0.5">{{ $o['icon'] }}</span>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2 mb-0.5 flex-wrap">
                            <span class="text-sm font-bold text-white">{{ $o['label'] }}</span>
                            @if ($o['badge'])<span class="px-1.5 py-0.5 rounded-full text-[9px] font-bold" style="background:{{ $o['color'] }}20;color:{{ $o['color'] }};">{{ $o['badge'] }}</span>@endif
                        </div>
                        <p class="text-xs leading-relaxed mb-1" style="color:rgba(255,255,255,0.5);">{{ $o['desc'] }}</p>
                        <p class="text-[10px] font-medium" style="color:rgba(255,255,255,0.3);">{{ $o['note'] }}</p>
                    </div>
                    <div class="radio w-5 h-5 rounded-full border-2 shrink-0 mt-0.5 flex items-center justify-center" style="border-color:rgba(255,255,255,0.2);"></div>
                </button>
            @endforeach
        </div>

        <div class="flex gap-3">
            <a href="{{ route('ruang-titip.detail-item') }}" class="flex items-center justify-center gap-1.5 py-3.5 px-4 rounded-xl font-semibold text-sm hover:bg-white/5 shrink-0" style="border:1.5px solid rgba(255,255,255,0.18);color:rgba(255,255,255,0.65);"><x-lucide-chevron-left class="w-4 h-4" /> Kembali</a>
            <button type="submit" id="nextBtn" disabled
                    onclick="if(!this.disabled){this.disabled=true;this.innerHTML='<svg class=\'w-4 h-4 animate-spin\' fill=\'none\' viewBox=\'0 0 24 24\'><circle class=\'opacity-25\' cx=\'12\' cy=\'12\' r=\'10\' stroke=\'currentColor\' stroke-width=\'4\'></circle><path class=\'opacity-75\' fill=\'currentColor\' d=\'M4 12a8 8 0 018-8v8z\'></path></svg> Memproses...';this.closest('form').submit();}"
                    class="flex-1 py-3.5 rounded-xl font-bold text-sm text-white flex items-center justify-center gap-2 transition-all hover:scale-[1.02] disabled:opacity-40" style="background:linear-gradient(135deg,#7c3aed,#6366f1);">Lanjutkan <x-lucide-arrow-right class="w-4 h-4" /></button>
>>>>>>> hostinger/main
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
