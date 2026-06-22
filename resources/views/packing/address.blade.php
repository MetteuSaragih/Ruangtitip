@extends('layouts.packing-checkout')

@section('title', 'Alamat Pengiriman')

@section('checkout-content')
<x-checkout-progress :labels="['Opsi Logistik', 'Alamat', 'Kurir', 'Pembayaran']" :step="2" />
<h1 class="text-lg font-extrabold text-white font-display mb-0.5">Alamat Pengiriman</h1>
<p class="text-xs mb-5" style="color:rgba(255,255,255,0.4);">Pilih alamat tersimpan atau tambah baru</p>

@if ($errors->any())
    <div class="rounded-xl px-4 py-2.5 mb-4 text-xs" style="background:rgba(239,68,68,0.12);border:1px solid rgba(239,68,68,0.3);color:#fca5a5;">
        {{ $errors->first() }}
    </div>
@endif

<form method="POST" action="{{ route('packing.address.save') }}" id="addressForm">
    @csrf
    <input type="hidden" name="mode" id="modeInput" value="new">
    <input type="hidden" name="address_id" id="addressId" value="{{ old('address_id') }}">

    <div class="mb-6">
        @if($addresses->isNotEmpty())
            <p class="text-xs font-bold text-white mb-3">Alamat Tersimpan</p>
            <div class="space-y-3 mb-4">
                @foreach($addresses as $address)
                    <button type="button" data-id="{{ $address->id }}"
                            onclick="pickAddress('{{ $address->id }}')"
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
                            @if(! $address->area_id)
                                <p class="text-[10px] mt-1" style="color:#fbbf24;">Belum ada kecamatan tersimpan, lengkapi dulu via "tambah alamat baru".</p>
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
            <x-biteship-area-search />
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

    <div class="flex gap-3">
        <a href="{{ route('packing.logistics') }}" class="flex items-center justify-center gap-1.5 py-3.5 px-4 rounded-xl font-semibold text-sm hover:bg-white/5 shrink-0" style="border:1.5px solid rgba(255,255,255,0.18);color:rgba(255,255,255,0.65);"><x-lucide-chevron-left class="w-4 h-4" /> Kembali</a>
        <button type="submit" id="continueBtn" disabled class="flex-1 py-3.5 rounded-xl font-bold text-sm text-white flex items-center justify-center gap-2 transition-all hover:scale-[1.02] disabled:opacity-40" style="background:linear-gradient(135deg,#7c3aed,#6366f1);">Simpan &amp; Lanjutkan <x-lucide-arrow-right class="w-4 h-4" /></button>
    </div>
</form>

<script>
(function () {
    const addr = document.getElementById('addressInput');
    const mode = document.getElementById('modeInput');
    const addressId = document.getElementById('addressId');
    const btn = document.getElementById('continueBtn');

    const refresh = () => {
        const ok = (mode.value === 'select' && addressId.value !== '') || (mode.value === 'new' && addr.value.trim().length > 0);
        btn.disabled = !ok;
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

    refresh();
})();
</script>
@endsection
