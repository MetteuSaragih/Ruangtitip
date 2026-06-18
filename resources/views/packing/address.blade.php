@extends('layouts.packing-checkout')

@section('title', 'Detail Pengiriman')
@section('back-url', route('packing.logistics'))

@php
    if (! function_exists('rupiah')) {
        function rupiah($n) { return 'Rp ' . number_format($n, 0, ',', '.'); }
    }
@endphp

@section('checkout-content')
<h1 class="text-lg font-extrabold text-white font-display mb-0.5">Detail Pengiriman</h1>
<p class="text-xs mb-5" style="color:rgba(255,255,255,0.4);">Masukkan alamat dan pilih kurir pengiriman</p>

@if ($errors->any())
    <div class="rounded-xl px-4 py-2.5 mb-4 text-xs" style="background:rgba(239,68,68,0.12);border:1px solid rgba(239,68,68,0.3);color:#fca5a5;">
        {{ $errors->first() }}
    </div>
@endif

<form method="POST" action="{{ route('packing.address.choose') }}" id="addressForm">
    @csrf
    <input type="hidden" name="courier" id="courierInput" value="{{ old('courier') }}">

    {{-- Alamat --}}
    <div class="mb-6">
        <label class="block text-xs font-bold mb-2 text-white">Alamat Pengiriman</label>
        <div class="relative">
            <x-lucide-search class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 pointer-events-none" style="color:rgba(255,255,255,0.3);" />
            <input type="text" name="address" id="addressInput" value="{{ old('address') }}"
                   placeholder="Masukkan alamat lengkap pengiriman..."
                   class="w-full pl-10 pr-10 py-3.5 rounded-xl text-sm text-white outline-none transition-all"
                   style="background:rgba(255,255,255,0.06);border:1.5px solid rgba(255,255,255,0.1);" />
            <x-lucide-map-pin class="absolute right-3.5 top-1/2 -translate-y-1/2 w-4 h-4 pointer-events-none" style="color:rgba(255,255,255,0.25);" />
        </div>
    </div>

    {{-- Kurir --}}
    <div class="mb-6">
        <label class="block text-xs font-bold mb-3 text-white">Pilih Kurir</label>
        <div class="space-y-2.5">
            @foreach ($couriers as $c)
                <button type="button" class="courier-opt w-full flex items-center gap-4 p-4 rounded-2xl text-left transition-all hover:scale-[1.01]"
                        data-id="{{ $c['id'] }}"
                        style="background:rgba(255,255,255,0.04);border:1.5px solid rgba(255,255,255,0.09);">
                    <span class="text-2xl shrink-0">{{ $c['logo'] }}</span>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-bold text-white">{{ $c['provider'] }}</p>
                        <p class="text-xs" style="color:rgba(255,255,255,0.45);">{{ $c['name'] }} · {{ $c['eta'] }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-bold" style="color:#7c3aed;">{{ rupiah($c['price']) }}</p>
                        <div class="opt-radio w-5 h-5 rounded-full border-2 mt-1 ml-auto flex items-center justify-center"
                             style="border-color:rgba(255,255,255,0.2);">
                            <x-lucide-check class="check-icon w-3 h-3 text-white" style="display:none;" />
                        </div>
                    </div>
                </button>
            @endforeach
        </div>
    </div>

    <button type="submit" id="continueBtn" disabled
            class="w-full py-4 rounded-2xl font-bold text-sm text-white flex items-center justify-center gap-2 transition-all hover:scale-[1.02]"
            style="background:linear-gradient(135deg,#7c3aed,#6366f1);opacity:0.4;cursor:not-allowed;">
        Lanjutkan <x-lucide-arrow-right class="w-4 h-4" />
    </button>
</form>

<script>
(function () {
    const addr    = document.getElementById('addressInput');
    const couriers= document.querySelectorAll('.courier-opt');
    const cInput  = document.getElementById('courierInput');
    const btn     = document.getElementById('continueBtn');

    const refresh = () => {
        const ok = addr.value.trim().length > 0 && cInput.value !== '';
        btn.disabled = !ok;
        btn.style.opacity = ok ? '1' : '0.4';
        btn.style.cursor  = ok ? 'pointer' : 'not-allowed';
        btn.style.boxShadow = ok ? '0 6px 20px rgba(124,58,237,0.4)' : 'none';
        addr.style.borderColor = addr.value.trim() ? 'rgba(124,58,237,0.55)' : 'rgba(255,255,255,0.1)';
        addr.style.boxShadow   = addr.value.trim() ? '0 0 0 3px rgba(124,58,237,0.1)' : 'none';
    };

    addr.addEventListener('input', refresh);
    couriers.forEach(opt => {
        opt.addEventListener('click', () => {
            cInput.value = opt.dataset.id;
            couriers.forEach(o => {
                const selected = o === opt;
                o.style.background  = selected ? 'rgba(124,58,237,0.1)' : 'rgba(255,255,255,0.04)';
                o.style.borderColor = selected ? '#7c3aed' : 'rgba(255,255,255,0.09)';
                const radio = o.querySelector('.opt-radio');
                const check = o.querySelector('.check-icon');
                radio.style.borderColor = selected ? '#7c3aed' : 'rgba(255,255,255,0.2)';
                radio.style.background  = selected ? '#7c3aed' : 'transparent';
                check.style.display     = selected ? 'block' : 'none';
            });
            refresh();
        });
        if (opt.dataset.id === cInput.value) opt.click();
    });
    refresh();
})();
</script>
@endsection
