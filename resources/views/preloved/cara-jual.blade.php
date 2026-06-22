@extends('layouts.dashboard')

@section('title', 'Cara Menjual Barang Preloved')

@php
    $warehouseAddress = config('biteship.warehouse.address', 'Malang, Jawa Timur');
    $mapsUrl = 'https://maps.google.com/?q=' . rawurlencode($warehouseAddress);

    $steps = [
        [
            'num' => 1,
            'icon' => 'map-pin',
            'heading' => 'Datang ke Lokasi RuTip',
            'subtitle' => 'Bawa barang bekas kosan yang ingin kamu jual ke gudang RuTip selama jam operasional kerja.',
            'pill' => 'Cepat & Praktis',
            'gradient' => 'linear-gradient(135deg,#7c3aed,#6366f1)',
            'glow' => 'rgba(124,58,237,0.4)',
        ],
        [
            'num' => 2,
            'icon' => 'handshake',
            'heading' => 'Inspeksi & Sepakati Harga',
            'subtitle' => 'Tim kami akan mengecek kondisi barang secara langsung dan menyepakati harga jual yang adil bersamamu.',
            'pill' => 'Harga Transparan',
            'gradient' => 'linear-gradient(135deg,#7c3aed,#a78bfa)',
            'glow' => 'rgba(167,139,250,0.4)',
        ],
        [
            'num' => 3,
            'icon' => 'package-check',
            'heading' => 'Serah Terima & Tayang',
            'subtitle' => 'Setelah sepakat, serahkan barangmu. Kami akan foto dan iklankan langsung ke katalog digital RuTip.',
            'pill' => 'Langsung Tayang',
            'gradient' => 'linear-gradient(135deg,#6366f1,#7c3aed)',
            'glow' => 'rgba(99,102,241,0.4)',
        ],
    ];
@endphp

@section('content')
<div class="max-w-lg mx-auto pt-2 pb-8">

    <a href="{{ route('preloved.index') }}" class="flex items-center gap-1.5 text-sm mb-8 hover:text-violet-300 transition-colors" style="color:rgba(255,255,255,0.4);">
        <x-lucide-chevron-left class="w-4 h-4" /> Kembali ke Toko Preloved
    </a>

    {{-- Header --}}
    <div class="text-center mb-12">
        <span class="inline-block px-3 py-1.5 rounded-full text-xs font-semibold mb-4" style="background:rgba(124,58,237,0.15);border:1px solid rgba(124,58,237,0.3);color:#c4b5fd;">
            Panduan Menjual
        </span>
        <h1 class="text-2xl font-extrabold text-white font-display leading-tight mb-3">
            Cara Menjual Barang<br>
            <span style="background:linear-gradient(90deg,#a78bfa,#7c3aed);-webkit-background-clip:text;-webkit-text-fill-color:transparent;">Preloved di RuTip</span>
        </h1>
        <p class="text-sm leading-relaxed" style="color:rgba(255,255,255,0.45);">
            Hanya 3 langkah mudah. Gratis listing, bayar hanya saat barangmu terjual.
        </p>
    </div>

    {{-- Steps --}}
    <div class="relative">
        <div class="absolute left-1/2 -translate-x-1/2 top-16 bottom-16 w-px"
             style="background:linear-gradient(180deg,rgba(124,58,237,0.4),rgba(99,102,241,0.4),rgba(124,58,237,0.2));"></div>

        <div class="space-y-14">
            @foreach ($steps as $step)
                <div class="flex flex-col items-center text-center relative z-10">
                    <div class="relative mb-5">
                        <div class="w-20 h-20 rounded-3xl flex items-center justify-center" style="background:{{ $step['gradient'] }};box-shadow:0 8px 32px {{ $step['glow'] }};">
                            <x-dynamic-component :component="'lucide-' . $step['icon']" class="w-9 h-9 text-white" style="stroke-width:1.5;" />
                        </div>
                        <div class="absolute -top-2 -right-2 w-7 h-7 rounded-full flex items-center justify-center text-sm font-extrabold text-white"
                             style="background:linear-gradient(135deg,#ec4899,#db2777);box-shadow:0 4px 12px rgba(236,72,153,0.5);">
                            {{ $step['num'] }}
                        </div>
                    </div>

                    <h2 class="text-lg font-extrabold text-white font-display mb-2">{{ $step['heading'] }}</h2>
                    <p class="text-sm leading-relaxed max-w-xs mx-auto" style="color:rgba(255,255,255,0.5);">{{ $step['subtitle'] }}</p>

                    <div class="mt-4 inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold"
                         style="background:rgba(124,58,237,0.12);border:1px solid rgba(124,58,237,0.28);color:#a78bfa;">
                        <x-lucide-check class="w-3 h-3" /> {{ $step['pill'] }}
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    {{-- Info banner --}}
    <div class="mt-14 rounded-2xl p-5" style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.08);">
        <div class="flex items-start gap-3">
            <span class="text-2xl shrink-0">💡</span>
            <div>
                <p class="text-xs font-bold text-white mb-1">Jam Operasional RuTip</p>
                <p class="text-xs leading-relaxed" style="color:rgba(255,255,255,0.5);">
                    Senin – Jumat: 08.00 – 17.00 WIB<br>
                    Sabtu: 09.00 – 14.00 WIB<br>
                    Minggu &amp; Hari Libur: Tutup
                </p>
            </div>
        </div>
    </div>

    {{-- CTA --}}
    <div class="mt-6">
        <a href="{{ $mapsUrl }}" target="_blank" rel="noopener noreferrer"
           class="w-full flex items-center justify-center gap-2 py-4 rounded-2xl font-bold text-sm text-white transition-all hover:scale-[1.02] active:scale-[0.98]"
           style="background:linear-gradient(135deg,#7c3aed,#6366f1);box-shadow:0 6px 20px rgba(124,58,237,0.4);">
            <x-lucide-map-pin class="w-4 h-4" /> Lihat Lokasi Gudang <x-lucide-arrow-right class="w-4 h-4" />
        </a>
        <a href="{{ route('preloved.index') }}"
           class="w-full flex items-center justify-center py-3.5 mt-3 rounded-2xl text-sm font-semibold transition-all hover:bg-white/5"
           style="border:1.5px solid rgba(255,255,255,0.12);color:rgba(255,255,255,0.65);">
            Lihat Katalog Preloved
        </a>
    </div>

</div>
@endsection
