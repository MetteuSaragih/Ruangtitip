@extends('layouts.dashboard')
@section('title', 'Detail Pesanan')

@php
    function rp_d($n){ return 'Rp '.number_format($n,0,',','.'); }
    $meta = $order->statusMeta();
    $step = $order->statusStep();
    $flow = \App\Models\TitipanOrder::FLOW;
    $logisticLabel = [
        'self'    => 'Antar Sendiri',
        'rutip'   => 'Packing + Anjem RuTip',
        'instant' => 'Kurir Biteship',
    ][$order->logistic] ?? '-';
@endphp

@section('content')
<div class="max-w-xl mx-auto pt-6 pb-28">
    <a href="{{ route('pesanan.index') }}" class="flex items-center gap-1.5 text-sm mb-5 hover:text-violet-300 transition-colors" style="color:rgba(255,255,255,0.4);">
        <x-lucide-chevron-left class="w-4 h-4" /> Kembali ke Pesanan
    </a>

    {{-- Header --}}
    <div class="flex items-start justify-between gap-3 mb-5">
        <div class="flex items-center gap-3 min-w-0">
            @if ($order->storage)
                <div class="w-11 h-11 rounded-xl flex items-center justify-center overflow-hidden shrink-0" style="background:rgba(124,58,237,0.15);">
                    @if ($order->storage->primary_photo)
                        <img src="{{ asset('storage/'.$order->storage->primary_photo) }}" alt="{{ $order->storage->name }}" class="w-full h-full object-cover">
                    @else
                        <x-lucide-warehouse class="w-5 h-5" style="color:#a78bfa;" />
                    @endif
                </div>
            @endif
            <div class="min-w-0">
                <h1 class="text-lg font-extrabold text-white font-display">#{{ $order->code() }}</h1>
                <p class="text-xs mt-0.5" style="color:rgba(255,255,255,0.4);">Dibuat {{ $order->created_at->format('d M Y, H:i') }}</p>
            </div>
        </div>
        <span class="flex items-center gap-1 px-3 py-1.5 rounded-full text-[11px] font-bold shrink-0" style="background:{{ $meta['color'] }}1f;color:{{ $meta['color'] }};">
            <x-dynamic-component :component="'lucide-' . $meta['icon']" class="w-3.5 h-3.5" /> {{ $meta['label'] }}
        </span>
    </div>

    {{-- Timeline status (5 fase dokumen) --}}
    <div class="rounded-2xl p-5 mb-4" style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.09);">
        <p class="text-xs font-bold text-white mb-4">Status Pesanan</p>
        <div class="space-y-0">
            @foreach ($flow as $key => $f)
                @php
                    $i = array_search($key, array_keys($flow), true);
                    $reached = $i <= $step;
                    $isLast = $i === count($flow) - 1;
                @endphp
                <div class="flex gap-3">
                    <div class="flex flex-col items-center">
                        <div class="w-7 h-7 rounded-full flex items-center justify-center shrink-0"
                             style="background:{{ $reached ? $f['color'] : 'rgba(255,255,255,0.07)' }};">
                            @if ($reached)
                                <x-dynamic-component :component="'lucide-' . $f['icon']" class="w-3.5 h-3.5 text-white" />
                            @else
                                <div class="w-2 h-2 rounded-full" style="background:rgba(255,255,255,0.2);"></div>
                            @endif
                        </div>
                        @if (!$isLast)
                            <div class="w-0.5 flex-1 my-1" style="min-height:24px;background:{{ $i < $step ? $f['color'] : 'rgba(255,255,255,0.08)' }};"></div>
                        @endif
                    </div>
                    <div class="pb-4">
                        <p class="text-xs font-semibold" style="color:{{ $reached ? '#fff' : 'rgba(255,255,255,0.35)' }};">{{ $f['label'] }}</p>
                        @if ($i === $step)
                            <p class="text-[10px] mt-0.5" style="color:{{ $f['color'] }};">Tahap saat ini</p>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    {{-- Info penitipan --}}
    <div class="rounded-2xl p-5 mb-4" style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.09);">
        <p class="text-xs font-bold text-white mb-3">Detail Penitipan</p>
        <div class="space-y-2.5 text-xs">
            <div class="flex justify-between gap-2"><span style="color:rgba(255,255,255,0.45);">Gudang</span><span class="text-white font-medium text-right">{{ $order->storage->name ?? '-' }}</span></div>
            <div class="flex justify-between gap-2"><span style="color:rgba(255,255,255,0.45);">Jenis Barang</span><span class="text-white font-medium capitalize">{{ $order->item_type }}</span></div>
            <div class="flex justify-between gap-2"><span style="color:rgba(255,255,255,0.45);">Jumlah Item</span><span class="text-white font-medium">{{ $order->totalItems() }} item</span></div>
            @if ($order->date_start && $order->date_end)
                <div class="flex justify-between gap-2"><span style="color:rgba(255,255,255,0.45);">Periode</span><span class="text-white font-medium text-right">{{ $order->date_start->format('d M Y') }} – {{ $order->date_end->format('d M Y') }}</span></div>
            @endif
            <div class="flex justify-between gap-2"><span style="color:rgba(255,255,255,0.45);">Logistik</span><span class="text-white font-medium">{{ $logisticLabel }}</span></div>
            @if ($order->address)
                <div class="flex justify-between gap-2"><span style="color:rgba(255,255,255,0.45);">Alamat</span><span class="text-white font-medium text-right max-w-[60%]">{{ $order->address }}</span></div>
            @endif
        </div>
    </div>

    {{-- Rincian biaya --}}
    <div class="rounded-2xl p-5 mb-4" style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.09);">
        <p class="text-xs font-bold text-white mb-3">Rincian Biaya</p>
        <div class="space-y-2.5 text-xs mb-3">
            <div class="flex justify-between"><span style="color:rgba(255,255,255,0.45);">Biaya Penitipan</span><span class="text-white font-medium">{{ rp_d($order->item_subtotal) }}</span></div>
            @if ($order->courier_cost > 0)
                <div class="flex justify-between"><span style="color:rgba(255,255,255,0.45);">Biaya Logistik</span><span class="text-white font-medium">{{ rp_d($order->courier_cost) }}</span></div>
            @endif
            <div class="flex justify-between"><span style="color:rgba(255,255,255,0.45);">Biaya Layanan</span><span class="text-white font-medium">{{ rp_d($order->platform_fee) }}</span></div>
        </div>
        <div class="flex items-center justify-between pt-3" style="border-top:1px solid rgba(255,255,255,0.1);">
            <span class="text-sm font-bold text-white">Total</span>
            <span class="text-lg font-extrabold font-display" style="color:#a78bfa;">{{ rp_d($order->total) }}</span>
        </div>
    </div>

    {{-- Aksi sesuai status --}}
    @if ($order->status === 'menunggu_pembayaran')
        @if ($order->tripay_checkout_url)
            <a href="{{ $order->tripay_checkout_url }}"
               class="flex items-center justify-center gap-2 w-full py-3.5 rounded-xl text-sm font-bold text-white mb-4 transition-all hover:scale-[1.02]"
               style="background:linear-gradient(135deg,#7c3aed,#6366f1);">
                <x-lucide-credit-card class="w-4 h-4" /> Lanjutkan Pembayaran
            </a>
        @else
            <div class="flex items-center gap-2 px-3 py-2.5 rounded-xl mb-4 text-[11px]" style="background:rgba(251,191,36,0.08);border:1px solid rgba(251,191,36,0.2);color:#fbbf24;">
                <x-lucide-info class="w-3.5 h-3.5 shrink-0" /> Menunggu pembayaran.
            </div>
        @endif
    @endif

    {{-- Bantuan via WhatsApp (sesuai dokumen) --}}
    <a href="https://wa.me/6285121091134?text=Halo%20RUTIP,%20saya%20mau%20tanya%20pesanan%20{{ $order->code() }}"
       target="_blank" rel="noopener noreferrer"
       class="flex items-center justify-center gap-2 w-full py-3.5 rounded-xl text-sm font-bold transition-all hover:scale-[1.02]"
       style="border:1.5px solid rgba(37,211,102,0.4);color:#25d366;background:rgba(37,211,102,0.06);">
        <x-lucide-message-circle class="w-4 h-4" /> Hubungi Admin via WhatsApp
    </a>
</div>
@endsection
