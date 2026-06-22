@extends('layouts.packing-checkout')

@section('title', 'Opsi Pengiriman')

@php
    if (! function_exists('rupiah')) {
        function rupiah($n) { return 'Rp ' . number_format($n, 0, ',', '.'); }
    }
@endphp

@section('checkout-content')
<x-checkout-progress :labels="['Opsi Logistik', 'Alamat', 'Kurir', 'Pembayaran']" :step="1" />
<h1 class="text-lg font-extrabold text-white font-display mb-0.5">Opsi Pengiriman Toko</h1>
<p class="text-xs mb-6" style="color:rgba(255,255,255,0.4);">Pilih cara kamu menerima pesanan</p>

<form method="POST" action="{{ route('packing.logistics.choose') }}" id="logisticForm">
    @csrf
    <input type="hidden" name="logistic" id="logisticInput" value="">

    <div class="space-y-3 mb-6">
        @php
            $opts = [
                ['id' => 'pickup',   'icon' => '🏪', 'label' => 'Jemput Sendiri ke Toko', 'desc' => 'Ambil pesananmu langsung di gudang RUTIP. Tidak ada biaya tambahan.', 'note' => '→ Langsung ke Pembayaran', 'color' => '#34d399', 'badge' => 'Rp0'],
                ['id' => 'biteship', 'icon' => '🛵', 'label' => 'Pengiriman Biteship',    'desc' => 'Dikirim ke alamatmu oleh kurir instan.', 'note' => '→ Input Alamat → Pilih Kurir → Pembayaran', 'color' => '#7c3aed', 'badge' => 'Instan'],
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
        <a href="{{ route('packing.index') }}" class="flex items-center justify-center gap-1.5 py-3.5 px-4 rounded-xl font-semibold text-sm hover:bg-white/5 shrink-0" style="border:1.5px solid rgba(255,255,255,0.18);color:rgba(255,255,255,0.65);"><x-lucide-chevron-left class="w-4 h-4" /> Kembali</a>
        <button type="submit" id="nextBtn" disabled class="flex-1 py-3.5 rounded-xl font-bold text-sm text-white flex items-center justify-center gap-2 transition-all hover:scale-[1.02] disabled:opacity-40" style="background:linear-gradient(135deg,#7c3aed,#6366f1);">Lanjutkan <x-lucide-arrow-right class="w-4 h-4" /></button>
    </div>
</form>

<script>
    function pickLogistic(id, color) {
        document.getElementById('logisticInput').value = id;
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
