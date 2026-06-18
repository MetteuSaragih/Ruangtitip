@extends('layouts.dashboard')
@section('title', 'Pilih Kurir')

@php function rp($n){ return 'Rp '.number_format($n,0,',','.'); } @endphp

@section('content')
<div class="max-w-xl mx-auto pt-6 pb-8">
    @include('dashboard.ruang-titip._progress', ['step' => 3])
    <h1 class="text-xl font-extrabold text-white font-display mb-1">Pilih Kurir Instan</h1>
    <p class="text-xs mb-5" style="color:rgba(255,255,255,0.4);">Langkah 3 dari 4 — Dari alamatmu ke gudang RUTIP</p>

    <div class="flex items-center gap-2 px-3 py-2 rounded-lg mb-4 text-[10px]" style="background:rgba(251,191,36,0.08);border:1px solid rgba(251,191,36,0.2);color:#fbbf24;">
        <x-lucide-info class="w-3.5 h-3.5 shrink-0" /> Tarif kurir masih dummy — nanti disambungkan ke API Biteship.
    </div>

    <div class="flex items-start gap-2.5 px-4 py-3 rounded-xl mb-5" style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.09);">
        <x-lucide-map-pin class="w-4 h-4 shrink-0 mt-0.5" style="color:#a78bfa;" />
        <p class="text-xs leading-relaxed" style="color:rgba(255,255,255,0.6);">{{ $s['address'] ?? '' }}</p>
    </div>

    @if ($errors->any())
        <div class="rounded-xl px-4 py-2.5 mb-4 text-sm" style="background:rgba(239,68,68,0.1);border:1px solid rgba(239,68,68,0.3);color:#fca5a5;">{{ $errors->first() }}</div>
    @endif

    <form method="POST" action="{{ route('ruang-titip.kurir.store') }}">
        @csrf
        <input type="hidden" name="courier_code" id="courierCode">
        <div class="space-y-3 mb-6">
            @foreach ($couriers as $c)
                <button type="button" data-code="{{ $c->code }}" onclick="pickCourier('{{ $c->code }}')"
                        class="courier w-full flex items-center gap-4 p-4 rounded-2xl text-left transition-all hover:scale-[1.01]"
                        style="background:rgba(255,255,255,0.04);border:1.5px solid rgba(255,255,255,0.09);">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center text-xl shrink-0" style="background:rgba(255,255,255,0.07);">{{ $c->logo }}</div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-bold text-white">{{ $c->name }}</p>
                        <p class="text-xs" style="color:rgba(255,255,255,0.45);">{{ $c->service }} · {{ $c->eta }}</p>
                    </div>
                    <p class="text-sm font-bold shrink-0" style="color:#a78bfa;">{{ rp($c->price) }}</p>
                    <div class="radio w-5 h-5 rounded-full border-2 shrink-0 flex items-center justify-center" style="border-color:rgba(255,255,255,0.2);"></div>
                </button>
            @endforeach
        </div>
        <div class="flex gap-3">
            <a href="{{ route('ruang-titip.alamat') }}" class="flex items-center justify-center gap-1.5 py-3.5 px-4 rounded-xl font-semibold text-sm hover:bg-white/5 shrink-0" style="border:1.5px solid rgba(255,255,255,0.18);color:rgba(255,255,255,0.65);"><x-lucide-chevron-left class="w-4 h-4" /> Kembali</a>
            <button type="submit" id="nextBtn" disabled class="flex-1 py-3.5 rounded-xl font-bold text-sm text-white flex items-center justify-center gap-2 transition-all hover:scale-[1.02] disabled:opacity-40" style="background:linear-gradient(135deg,#7c3aed,#6366f1);">Lanjutkan <x-lucide-arrow-right class="w-4 h-4" /></button>
        </div>
    </form>
</div>
<script>
    function pickCourier(code) {
        document.getElementById('courierCode').value = code;
        document.getElementById('nextBtn').disabled = false;
        document.querySelectorAll('.courier').forEach(b => {
            const on = b.dataset.code === code;
            b.style.background = on ? 'rgba(124,58,237,0.12)' : 'rgba(255,255,255,0.04)';
            b.style.borderColor = on ? '#7c3aed' : 'rgba(255,255,255,0.09)';
            const radio = b.querySelector('.radio');
            radio.style.background = on ? '#7c3aed' : 'transparent';
            radio.style.borderColor = on ? '#7c3aed' : 'rgba(255,255,255,0.2)';
            radio.innerHTML = on ? '<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>' : '';
        });
    }
</script>
@endsection
