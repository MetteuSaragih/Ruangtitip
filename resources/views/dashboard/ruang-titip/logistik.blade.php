@extends('layouts.dashboard')
@section('title', 'Opsi Logistik')

@section('content')
<div class="max-w-xl mx-auto pt-6 pb-8">
    @include('dashboard.ruang-titip._progress', ['step' => 2])
    <h1 class="text-xl font-extrabold text-white font-display mb-1">Opsi Logistik</h1>
    <p class="text-xs mb-6" style="color:rgba(255,255,255,0.4);">Langkah 2 dari 4 - Pilih cara barang sampai ke gudang</p>

    <form method="POST" action="{{ route('ruang-titip.logistik.store') }}">
        @csrf
        <input type="hidden" name="logistic" id="logistic">
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
        </div>
    </form>
</div>
<script>
    function pickLogistic(id, color) {
        document.getElementById('logistic').value = id;
        document.getElementById('nextBtn').disabled = false;
        document.querySelectorAll('.opt').forEach(b => {
            const on = b.dataset.id === id; const c = b.dataset.color;
            b.style.background = on ? c + '12' : 'rgba(255,255,255,0.04)';
            b.style.borderColor = on ? c : 'rgba(255,255,255,0.09)';
            const radio = b.querySelector('.radio');
            radio.style.background = on ? c : 'transparent';
            radio.style.borderColor = on ? c : 'rgba(255,255,255,0.2)';
            radio.innerHTML = on ? '<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>' : '';
        });
    }
</script>
@endsection
