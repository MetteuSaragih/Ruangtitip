@extends('layouts.dashboard')
@section('title', 'Checkout')

@php function rp($n){ return 'Rp '.number_format($n,0,',','.'); } @endphp

@section('content')
<div class="max-w-xl mx-auto pt-6 pb-8">
    @php
        $back = $s['logistic'] === 'instant' ? route('ruang-titip.kurir')
              : ($s['logistic'] === 'rutip' ? route('ruang-titip.alamat') : route('ruang-titip.logistik'));
    @endphp
    <a href="{{ $back }}" class="flex items-center gap-1.5 text-sm mb-5 hover:text-violet-300 transition-colors" style="color:rgba(255,255,255,0.4);">
        <x-lucide-chevron-left class="w-4 h-4" /> Kembali
    </a>
    @include('dashboard.ruang-titip._progress', ['step' => 4])
    <h1 class="text-xl font-extrabold text-white font-display mb-1">Checkout</h1>
    <p class="text-xs mb-6" style="color:rgba(255,255,255,0.4);">Langkah 4 dari 4 — Review &amp; selesaikan pesanan</p>

    @if ($errors->any())
        <div class="rounded-xl px-4 py-2.5 mb-4 text-sm" style="background:rgba(239,68,68,0.1);border:1px solid rgba(239,68,68,0.3);color:#fca5a5;">{{ $errors->first() }}</div>
    @endif

    {{-- Rincian --}}
    <div class="rounded-2xl p-5 mb-4" style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.09);">
        <h3 class="text-sm font-bold text-white mb-4">Rincian Total Tagihan</h3>
        <div class="space-y-3 mb-4">
            <div class="flex items-start justify-between gap-2">
                <span class="text-xs leading-relaxed" style="color:rgba(255,255,255,0.5);">Biaya Penitipan ({{ $calc['totalItems'] }} item · {{ $calc['months'] }} bln)</span>
                <span class="text-xs font-semibold text-white shrink-0">{{ rp($calc['itemSubtotal']) }}</span>
            </div>

            @if ($s['logistic'] === 'rutip')
                {{-- Rincian Anjem RuTip: jarak + packing --}}
                <div class="flex items-start justify-between gap-2">
                    <span class="text-xs leading-relaxed" style="color:rgba(255,255,255,0.5);">Antar-Jemput RuTip ({{ $calc['km'] }} km)</span>
                    <span class="text-xs font-semibold text-white shrink-0">{{ rp($calc['kmCost']) }}</span>
                </div>
                <div class="flex items-start justify-between gap-2">
                    <span class="text-xs leading-relaxed" style="color:rgba(255,255,255,0.5);">Jasa Packing ({{ $calc['totalItems'] }} × Rp15.000)</span>
                    <span class="text-xs font-semibold text-white shrink-0">{{ rp($calc['packingCost']) }}</span>
                </div>
                <div class="flex items-center gap-2 px-3 py-2 rounded-lg text-[10px]" style="background:rgba(251,191,36,0.08);border:1px solid rgba(251,191,36,0.2);color:#fbbf24;">
                    <x-lucide-info class="w-3 h-3 shrink-0" /> Jarak {{ $calc['km'] }} km masih dummy — nanti dihitung otomatis dari alamatmu ke gudang.
                </div>
            @elseif ($s['logistic'] === 'instant' && $courier)
                <div class="flex items-start justify-between gap-2">
                    <span class="text-xs leading-relaxed" style="color:rgba(255,255,255,0.5);">Pengiriman ({{ $courier->name }} {{ $courier->service }})</span>
                    <span class="text-xs font-semibold text-white shrink-0">{{ rp($calc['courierCost']) }}</span>
                </div>
            @endif

            <div class="flex items-start justify-between gap-2">
                <span class="text-xs leading-relaxed" style="color:rgba(255,255,255,0.5);">Biaya Layanan Platform</span>
                <span class="text-xs font-semibold text-white shrink-0">{{ rp($calc['platform_fee']) }}</span>
            </div>
        </div>
        <div class="flex items-center justify-between pt-3" style="border-top:1px solid rgba(255,255,255,0.1);">
            <span class="text-sm font-bold text-white">Total</span>
            <span class="text-xl font-extrabold font-display" style="color:#a78bfa;">{{ rp($calc['total']) }}</span>
        </div>
    </div>

    <form method="POST" action="{{ route('ruang-titip.place') }}">
        @csrf
        <label class="flex items-start gap-3 px-4 py-3.5 rounded-xl mb-4 cursor-pointer" style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.09);">
            <input type="checkbox" name="agree" value="1" class="mt-0.5 accent-violet-500 w-4 h-4 shrink-0">
            <span class="text-xs leading-relaxed" style="color:rgba(255,255,255,0.55);">
                Saya menyetujui <span class="font-semibold underline" style="color:#a78bfa;">Syarat &amp; Ketentuan</span> Garansi Batas Tetap dan <span class="font-semibold underline" style="color:#a78bfa;">Kebijakan Privasi</span> RUTIP.
            </span>
        </label>

        <div class="flex items-center gap-2 px-3 py-2 rounded-lg mb-3 text-[10px]" style="background:rgba(251,191,36,0.08);border:1px solid rgba(251,191,36,0.2);color:#fbbf24;">
            <x-lucide-info class="w-3.5 h-3.5 shrink-0" /> Pembayaran masih dummy — nanti disambungkan ke Midtrans.
        </div>
        <div class="rounded-2xl p-5 mb-6" style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.09);">
            <h3 class="text-sm font-bold text-white mb-4">Pilih Metode Pembayaran</h3>
            <input type="hidden" name="payment_method" id="payMethod">
            <div class="space-y-2.5">
                @php
                    $pays = [
                        ['id'=>'qris','label'=>'QRIS','desc'=>'Scan & bayar semua e-wallet','icon'=>'qr-code','color'=>'#7c3aed'],
                        ['id'=>'va','label'=>'Virtual Account','desc'=>'BCA, BRI, Mandiri, BNI','icon'=>'building-2','color'=>'#2563eb'],
                        ['id'=>'ew','label'=>'E-Wallet','desc'=>'GoPay, OVO, Dana, ShopeePay','icon'=>'wallet','color'=>'#7c3aed'],
                    ];
                @endphp
                @foreach ($pays as $p)
                    <button type="button" data-id="{{ $p['id'] }}" data-color="{{ $p['color'] }}" onclick="pickPay('{{ $p['id'] }}','{{ $p['color'] }}')"
                            class="pay w-full flex items-center gap-3 p-3.5 rounded-xl text-left transition-all hover:scale-[1.01]"
                            style="background:rgba(255,255,255,0.04);border:1.5px solid rgba(255,255,255,0.09);">
                        <div class="w-9 h-9 rounded-xl flex items-center justify-center shrink-0" style="background:{{ $p['color'] }}18;">
                            <x-dynamic-component :component="'lucide-' . $p['icon']" class="w-4.5 h-4.5" style="color:{{ $p['color'] }};" />
                        </div>
                        <div class="flex-1">
                            <p class="text-xs font-bold text-white">{{ $p['label'] }}</p>
                            <p class="text-[10px] mt-0.5" style="color:rgba(255,255,255,0.4);">{{ $p['desc'] }}</p>
                        </div>
                        <div class="radio w-5 h-5 rounded-full border-2 shrink-0 flex items-center justify-center" style="border-color:rgba(255,255,255,0.2);"></div>
                    </button>
                @endforeach
            </div>
        </div>

        <button type="submit" class="w-full py-4 rounded-2xl font-bold text-base text-white flex items-center justify-center gap-2 transition-all hover:scale-[1.02]" style="background:linear-gradient(135deg,#7c3aed,#6366f1);box-shadow:0 8px 24px rgba(124,58,237,0.5);">
            Bayar {{ rp($calc['total']) }}
        </button>
    </form>
</div>
<script>
    function pickPay(id, color) {
        document.getElementById('payMethod').value = id;
        document.querySelectorAll('.pay').forEach(b => {
            const on = b.dataset.id === id; const c = b.dataset.color;
            b.style.background = on ? c + '10' : 'rgba(255,255,255,0.04)';
            b.style.borderColor = on ? c : 'rgba(255,255,255,0.09)';
            const radio = b.querySelector('.radio');
            radio.style.background = on ? c : 'transparent';
            radio.style.borderColor = on ? c : 'rgba(255,255,255,0.2)';
            radio.innerHTML = on ? '<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>' : '';
        });
    }
</script>
@endsection
