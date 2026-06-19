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
<p class="text-xs mb-5" style="color:rgba(255,255,255,0.4);">Pilih alamat dan kurir pengiriman</p>

@if ($errors->any())
    <div class="rounded-xl px-4 py-2.5 mb-4 text-xs" style="background:rgba(239,68,68,0.12);border:1px solid rgba(239,68,68,0.3);color:#fca5a5;">
        {{ $errors->first() }}
    </div>
@endif

<form method="POST" action="{{ route('packing.address.choose') }}" id="addressForm">
    @csrf
    <input type="hidden" name="courier" id="courierInput" value="{{ old('courier') }}">
    <input type="hidden" name="mode" id="modeInput" value="new">
    <input type="hidden" name="address_id" id="addressId" value="{{ old('address_id') }}">

    <div class="mb-6">
        @if($addresses->isNotEmpty())
            <p class="text-xs font-bold text-white mb-3">Alamat Tersimpan</p>
            <div class="space-y-3 mb-4">
                @foreach($addresses as $address)
                    <button type="button" data-id="{{ $address->id }}" onclick="pickAddress('{{ $address->id }}')"
                            class="addr w-full flex items-start gap-3 p-4 rounded-2xl text-left transition-all hover:scale-[1.01]"
                            style="background:rgba(255,255,255,0.04);border:1.5px solid rgba(255,255,255,0.09);">
                        <x-lucide-map-pin class="w-4 h-4 shrink-0 mt-0.5" style="color:#a78bfa;" />
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 mb-0.5">
                                <span class="text-sm font-bold text-white">{{ $address->label ?: 'Alamat' }}</span>
                                @if($address->is_primary)
                                    <span class="px-1.5 py-0.5 rounded-full text-[9px] font-bold" style="background:rgba(124,58,237,0.2);color:#a78bfa;">Utama</span>
                                @endif
                            </div>
                            <p class="text-xs leading-relaxed" style="color:rgba(255,255,255,0.55);">{{ $address->address }}</p>
                            @if($address->note)
                                <p class="text-[10px] mt-1" style="color:rgba(255,255,255,0.35);">Catatan: {{ $address->note }}</p>
                            @endif
                        </div>
                        <div class="addr-radio w-5 h-5 rounded-full border-2 shrink-0 mt-0.5 flex items-center justify-center" style="border-color:rgba(255,255,255,0.2);"></div>
                    </button>
                @endforeach
            </div>

            <div class="flex items-center gap-3 my-5">
                <div class="flex-1 h-px" style="background:rgba(255,255,255,0.08);"></div>
                <span class="text-[10px]" style="color:rgba(255,255,255,0.3);">atau tambah alamat baru</span>
                <div class="flex-1 h-px" style="background:rgba(255,255,255,0.08);"></div>
            </div>
        @endif

        <div class="rounded-2xl p-5 space-y-4" style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.09);">
            <p class="text-xs font-bold text-white">{{ $addresses->count() ? 'Daftarkan Alamat Baru' : 'Masukkan Alamat Pengiriman' }}</p>
            <div>
                <label class="block text-[11px] font-semibold mb-2" style="color:rgba(255,255,255,0.55);">Label <span style="color:rgba(255,255,255,0.3);">(opsional)</span></label>
                <input type="text" name="label" placeholder="Kos / Rumah / Kontrakan" value="{{ old('label') }}"
                       class="w-full px-4 py-3 rounded-xl text-sm text-white placeholder:text-white/20 outline-none"
                       style="background:rgba(255,255,255,0.06);border:1.5px solid rgba(255,255,255,0.1);">
            </div>
            <div>
                <label class="block text-[11px] font-semibold mb-2" style="color:rgba(255,255,255,0.55);">Alamat Lengkap <span style="color:#f87171;">*</span></label>
                <textarea name="address" id="addressInput" rows="3" placeholder="Jl. Veteran No. 10, Kec. Lowokwaru, Malang"
                          class="w-full px-4 py-3 rounded-xl text-sm text-white placeholder:text-white/20 outline-none resize-none"
                          style="background:rgba(255,255,255,0.06);border:1.5px solid rgba(255,255,255,0.1);">{{ old('address') }}</textarea>
            </div>
            <div>
                <label class="block text-[11px] font-semibold mb-2" style="color:rgba(255,255,255,0.55);">Catatan untuk Kurir <span style="color:rgba(255,255,255,0.3);">(opsional)</span></label>
                <textarea name="note" rows="2" placeholder="Rumah cat hijau, pagar depan, dekat masjid"
                          class="w-full px-4 py-3 rounded-xl text-sm text-white placeholder:text-white/20 outline-none resize-none"
                          style="background:rgba(255,255,255,0.06);border:1.5px solid rgba(255,255,255,0.1);">{{ old('note') }}</textarea>
            </div>
            <label class="flex items-center gap-2.5 cursor-pointer">
                <input type="checkbox" name="is_primary" value="1" class="accent-violet-500 w-4 h-4" {{ old('is_primary') ? 'checked' : '' }}>
                <span class="text-xs" style="color:rgba(255,255,255,0.6);">Jadikan alamat utama</span>
            </label>
        </div>
    </div>

    <div class="mb-6">
        <label class="block text-xs font-bold mb-3 text-white">Pilih Kurir</label>
        <div class="space-y-2.5">
            @forelse ($couriers as $courier)
                <button type="button" class="courier-opt w-full flex items-center gap-4 p-4 rounded-2xl text-left transition-all hover:scale-[1.01]"
                        data-id="{{ $courier->code }}"
                        style="background:rgba(255,255,255,0.04);border:1.5px solid rgba(255,255,255,0.09);">
                    <x-lucide-truck class="w-6 h-6 shrink-0" style="color:#a78bfa;" />
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-bold text-white">{{ $courier->name }}</p>
                        <p class="text-xs" style="color:rgba(255,255,255,0.45);">{{ $courier->service }} · {{ $courier->eta }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-bold" style="color:#7c3aed;">{{ rupiah($courier->price) }}</p>
                        <div class="opt-radio w-5 h-5 rounded-full border-2 mt-1 ml-auto flex items-center justify-center"
                             style="border-color:rgba(255,255,255,0.2);">
                            <x-lucide-check class="check-icon w-3 h-3 text-white" style="display:none;" />
                        </div>
                    </div>
                </button>
            @empty
                <div class="rounded-2xl p-4 text-xs text-center" style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.08);color:rgba(255,255,255,0.45);">
                    Belum ada kurir tersedia.
                </div>
            @endforelse
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
    const addr = document.getElementById('addressInput');
    const mode = document.getElementById('modeInput');
    const addressId = document.getElementById('addressId');
    const couriers = document.querySelectorAll('.courier-opt');
    const cInput = document.getElementById('courierInput');
    const btn = document.getElementById('continueBtn');

    const refresh = () => {
        const ok = ((mode.value === 'select' && addressId.value !== '') || (mode.value === 'new' && addr.value.trim().length > 0)) && cInput.value !== '';
        btn.disabled = !ok;
        btn.style.opacity = ok ? '1' : '0.4';
        btn.style.cursor = ok ? 'pointer' : 'not-allowed';
        btn.style.boxShadow = ok ? '0 6px 20px rgba(124,58,237,0.4)' : 'none';
    };

    window.pickAddress = (id) => {
        mode.value = 'select';
        addressId.value = id;
        document.querySelectorAll('.addr').forEach(b => {
            const on = b.dataset.id === String(id);
            b.style.background = on ? 'rgba(124,58,237,0.12)' : 'rgba(255,255,255,0.04)';
            b.style.borderColor = on ? '#7c3aed' : 'rgba(255,255,255,0.09)';
            const radio = b.querySelector('.addr-radio');
            radio.style.background = on ? '#7c3aed' : 'transparent';
            radio.style.borderColor = on ? '#7c3aed' : 'rgba(255,255,255,0.2)';
            radio.innerHTML = on ? '<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>' : '';
        });
        refresh();
    };

    addr.addEventListener('input', () => {
        if (addr.value.trim().length > 0) {
            mode.value = 'new';
            addressId.value = '';
            document.querySelectorAll('.addr').forEach(b => {
                b.style.background = 'rgba(255,255,255,0.04)';
                b.style.borderColor = 'rgba(255,255,255,0.09)';
                const radio = b.querySelector('.addr-radio');
                radio.style.background = 'transparent';
                radio.style.borderColor = 'rgba(255,255,255,0.2)';
                radio.innerHTML = '';
            });
        }
        refresh();
    });
    couriers.forEach(opt => {
        opt.addEventListener('click', () => {
            cInput.value = opt.dataset.id;
            couriers.forEach(o => {
                const selected = o === opt;
                o.style.background = selected ? 'rgba(124,58,237,0.1)' : 'rgba(255,255,255,0.04)';
                o.style.borderColor = selected ? '#7c3aed' : 'rgba(255,255,255,0.09)';
                const radio = o.querySelector('.opt-radio');
                const check = o.querySelector('.check-icon');
                radio.style.borderColor = selected ? '#7c3aed' : 'rgba(255,255,255,0.2)';
                radio.style.background = selected ? '#7c3aed' : 'transparent';
                check.style.display = selected ? 'block' : 'none';
            });
            refresh();
        });
        if (opt.dataset.id === cInput.value) opt.click();
    });
    refresh();
})();
</script>
@endsection
