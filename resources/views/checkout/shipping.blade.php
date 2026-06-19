@extends('layouts.dashboard')

@section('title', 'Opsi Pengiriman')

@section('content')
<div class="max-w-xl mx-auto pt-6 pb-8">
    <h1 class="text-xl font-extrabold text-white font-display mb-1">Opsi Pengiriman Toko</h1>
    <p class="text-xs mb-6" style="color:rgba(255,255,255,0.4);">Pilih cara kamu menerima pesanan</p>

    @if ($errors->any())
        <div class="rounded-xl px-4 py-2.5 mb-4 text-sm" style="background:rgba(239,68,68,0.1);border:1px solid rgba(239,68,68,0.3);color:#fca5a5;">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('checkout.shipping.choose') }}" id="shippingForm">
        @csrf
        <input type="hidden" name="shipping_method" id="shippingMethod" value="">

        <div class="space-y-3 mb-6">
            @foreach ([
                ['id' => 'pickup', 'label' => 'Jemput Sendiri ke Toko', 'desc' => 'Ambil pesananmu langsung di gudang RUTIP. Tidak ada biaya tambahan.', 'cost' => 'Gratis', 'icon' => 'store', 'color' => '#34d399'],
                ['id' => 'biteship', 'label' => 'Instant Shipper', 'desc' => 'Dikirim ke alamatmu oleh kurir instan. Alamat dan kurir dipilih di langkah berikutnya.', 'cost' => 'Dihitung', 'icon' => 'bike', 'color' => '#7c3aed'],
            ] as $option)
                <button type="button" class="ship-opt w-full flex items-start gap-4 p-5 rounded-2xl text-left transition-all hover:scale-[1.01]"
                        data-id="{{ $option['id'] }}" data-color="{{ $option['color'] }}"
                        style="background:rgba(255,255,255,0.04);border:1.5px solid rgba(255,255,255,0.09);">
                    <span class="w-11 h-11 rounded-xl flex items-center justify-center shrink-0" style="background:rgba(124,58,237,0.12);color:{{ $option['color'] }};">
                        <x-dynamic-component :component="'lucide-' . $option['icon']" class="w-5 h-5" />
                    </span>
                    <span class="flex-1 min-w-0">
                        <span class="block text-sm font-bold text-white mb-1">{{ $option['label'] }}</span>
                        <span class="block text-xs leading-relaxed" style="color:rgba(255,255,255,0.5);">{{ $option['desc'] }}</span>
                    </span>
                    <span class="text-right shrink-0">
                        <span class="block text-xs font-bold whitespace-nowrap" style="color:{{ $option['color'] }};">{{ $option['cost'] }}</span>
                        <span class="opt-radio w-5 h-5 rounded-full border-2 mt-1 ml-auto flex items-center justify-center" style="border-color:rgba(255,255,255,0.2);">
                            <x-lucide-check class="check-icon w-3 h-3 text-white" style="display:none;" />
                        </span>
                    </span>
                </button>
            @endforeach
        </div>

        <div class="flex gap-3">
            <a href="{{ route('preloved.cart.index') }}" class="flex items-center justify-center gap-1.5 py-3.5 px-4 rounded-xl font-semibold text-sm hover:bg-white/5 shrink-0" style="border:1.5px solid rgba(255,255,255,0.18);color:rgba(255,255,255,0.65);">
                <x-lucide-chevron-left class="w-4 h-4" /> Kembali
            </a>
            <button type="submit" id="continueBtn" disabled
                    class="flex-1 py-3.5 rounded-xl font-bold text-sm text-white flex items-center justify-center gap-2 transition-all hover:scale-[1.02] disabled:opacity-40"
                    style="background:linear-gradient(135deg,#7c3aed,#6366f1);">
                Lanjutkan <x-lucide-arrow-right class="w-4 h-4" />
            </button>
        </div>
    </form>
</div>

<script>
(function () {
    const opts = document.querySelectorAll('.ship-opt');
    const input = document.getElementById('shippingMethod');
    const btn = document.getElementById('continueBtn');

    opts.forEach(opt => {
        opt.addEventListener('click', () => {
            input.value = opt.dataset.id;

            opts.forEach(o => {
                const selected = o === opt;
                const color = o.dataset.color;
                o.style.background = selected ? color + '18' : 'rgba(255,255,255,0.04)';
                o.style.borderColor = selected ? color : 'rgba(255,255,255,0.09)';
                const radio = o.querySelector('.opt-radio');
                const check = o.querySelector('.check-icon');
                radio.style.background = selected ? color : 'transparent';
                radio.style.borderColor = selected ? color : 'rgba(255,255,255,0.2)';
                check.style.display = selected ? 'block' : 'none';
            });

            btn.disabled = false;
        });
    });
})();
</script>
@endsection
