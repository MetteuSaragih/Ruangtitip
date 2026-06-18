@extends('layouts.packing-checkout')

@section('title', 'Opsi Pengiriman')
@section('back-url', route('packing.index'))

@php
    if (! function_exists('rupiah')) {
        function rupiah($n) { return 'Rp ' . number_format($n, 0, ',', '.'); }
    }
@endphp

@section('checkout-content')
<h1 class="text-lg font-extrabold text-white font-display mb-0.5">Opsi Pengiriman Toko</h1>
<p class="text-xs mb-6" style="color:rgba(255,255,255,0.4);">Pilih cara kamu menerima pesanan</p>

<form method="POST" action="{{ route('packing.logistics.choose') }}" id="logisticForm">
    @csrf
    <input type="hidden" name="logistic" id="logisticInput" value="">

    <div class="space-y-3 mb-6">
        @php
            $opts = [
                ['id' => 'pickup',   'icon' => '🏪', 'label' => 'Jemput Sendiri ke Toko', 'desc' => 'Ambil pesananmu langsung di gudang RUTIP. Tidak ada biaya tambahan.', 'cost' => 'Gratis',   'color' => '#34d399', 'note' => null],
                ['id' => 'biteship', 'icon' => '🛵', 'label' => 'Pengiriman Biteship',    'desc' => 'Dikirim ke alamatmu oleh kurir instan. Pilih kurir dan lihat harga di langkah berikutnya.', 'cost' => 'Dihitung', 'color' => '#7c3aed', 'note' => 'Instan'],
            ];
        @endphp
        @foreach ($opts as $opt)
            <button type="button" class="logistic-opt w-full flex items-start gap-4 p-5 rounded-2xl text-left transition-all hover:scale-[1.01]"
                    data-id="{{ $opt['id'] }}" data-color="{{ $opt['color'] }}"
                    style="background:rgba(255,255,255,0.04);border:1.5px solid rgba(255,255,255,0.09);">
                <span class="text-3xl shrink-0 mt-0.5">{{ $opt['icon'] }}</span>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2 mb-1 flex-wrap">
                        <span class="text-sm font-bold text-white">{{ $opt['label'] }}</span>
                        @if ($opt['note'])
                            <span class="px-1.5 py-0.5 rounded-full text-[9px] font-bold" style="background:rgba(124,58,237,0.2);color:#7c3aed;">{{ $opt['note'] }}</span>
                        @endif
                    </div>
                    <p class="text-xs leading-relaxed" style="color:rgba(255,255,255,0.5);">{{ $opt['desc'] }}</p>
                </div>
                <div class="text-right shrink-0">
                    <p class="text-xs font-bold whitespace-nowrap" style="color:{{ $opt['color'] }};">{{ $opt['cost'] }}</p>
                    <div class="opt-radio w-5 h-5 rounded-full border-2 mt-1 ml-auto flex items-center justify-center"
                         style="border-color:rgba(255,255,255,0.2);">
                        <x-lucide-check class="check-icon w-3 h-3 text-white" style="display:none;" />
                    </div>
                </div>
            </button>
        @endforeach
    </div>

    <button type="submit" id="continueBtn" disabled
            class="w-full py-4 rounded-2xl font-bold text-sm text-white flex items-center justify-center gap-2 transition-all hover:scale-[1.02]"
            style="background:linear-gradient(135deg,#7c3aed,#6366f1);opacity:0.4;cursor:not-allowed;">
        Lanjutkan <x-lucide-arrow-right class="w-4 h-4" />
    </button>
</form>

<script>
(function () {
    const opts  = document.querySelectorAll('.logistic-opt');
    const input = document.getElementById('logisticInput');
    const btn   = document.getElementById('continueBtn');

    opts.forEach(opt => {
        opt.addEventListener('click', () => {
            const id    = opt.dataset.id;
            const color = opt.dataset.color;
            input.value = id;

            opts.forEach(o => {
                const selected = o === opt;
                const c = o.dataset.color;
                o.style.background  = selected ? c + '10' : 'rgba(255,255,255,0.04)';
                o.style.borderColor = selected ? c : 'rgba(255,255,255,0.09)';
                o.style.boxShadow   = selected ? '0 4px 20px ' + c + '18' : 'none';
                const radio = o.querySelector('.opt-radio');
                const check = o.querySelector('.check-icon');
                radio.style.borderColor = selected ? c : 'rgba(255,255,255,0.2)';
                radio.style.background  = selected ? c : 'transparent';
                check.style.display     = selected ? 'block' : 'none';
            });

            btn.disabled = false;
            btn.style.opacity = '1';
            btn.style.cursor  = 'pointer';
            btn.style.boxShadow = '0 6px 20px rgba(124,58,237,0.4)';
        });
    });
})();
</script>
@endsection
