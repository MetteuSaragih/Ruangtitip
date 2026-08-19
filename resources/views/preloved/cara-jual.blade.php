@extends('layouts.dashboard')

@section('title', 'Cara Menjual Barang Preloved')

@php
    $warehouseAddress = config('biteship.warehouse.address', 'Malang, Jawa Timur');
    $mapsUrl = 'https://maps.google.com/?q=' . rawurlencode($warehouseAddress);
@endphp

@section('content')

{{-- ─── BACK LINK ─────────────────────────────────── --}}
<div class="pt-2 pb-10">
    <a href="{{ route('preloved.index') }}"
       class="inline-flex items-center gap-1.5 text-xs font-medium mb-8 hover:text-violet-300 transition-colors"
       style="color:rgba(255,255,255,0.4);">
        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 18l-6-6 6-6"/></svg>
        Kembali ke Toko Preloved
    </a>

    {{-- ─── HERO HEADER ──────────────────────────────── --}}
    <div class="relative rounded-3xl overflow-hidden mb-10 px-6 py-12 text-center"
         style="background:linear-gradient(135deg,#1a0640 0%,#160a2e 50%,#1c0b36 100%);border:1px solid rgba(139,92,246,0.2);">
        {{-- Glow blobs --}}
        <div class="absolute -top-20 -left-20 w-64 h-64 rounded-full blur-3xl opacity-30 pointer-events-none"
             style="background:radial-gradient(circle,#7c3aed,transparent);"></div>
        <div class="absolute -bottom-20 -right-10 w-56 h-56 rounded-full blur-3xl opacity-20 pointer-events-none"
             style="background:radial-gradient(circle,#6366f1,transparent);"></div>

        <div class="relative z-10">
            <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full text-xs font-semibold mb-5"
                  style="background:rgba(124,58,237,0.25);border:1px solid rgba(139,92,246,0.4);color:#c4b5fd;">
                <span class="w-1.5 h-1.5 rounded-full bg-violet-400 animate-pulse"></span>
                Panduan Menjual
            </span>

            <h1 class="text-3xl font-extrabold text-white font-display leading-tight mb-3">
                Cara Menjual Barang Preloved<br>
                <span style="background:linear-gradient(90deg,#a78bfa,#7c3aed);-webkit-background-clip:text;-webkit-text-fill-color:transparent;">
                    di RuTip
                </span>
            </h1>
            <p class="text-sm leading-relaxed max-w-sm mx-auto" style="color:rgba(255,255,255,0.5);">
                Hanya 3 langkah mudah. Gratis listing, bayar hanya saat barangmu terjual.
            </p>

            {{-- Stats row --}}
            <div class="flex items-center justify-center gap-6 mt-8 flex-wrap">
                @foreach([['val'=>'Gratis','label'=>'Biaya Listing'],['val'=>'0%','label'=>'Komisi Awal'],['val'=>'3 hari','label'=>'Rata-rata Terjual']] as $stat)
                <div class="text-center">
                    <p class="text-xl font-extrabold font-display" style="color:#a78bfa;">{{ $stat['val'] }}</p>
                    <p class="text-[10px] mt-0.5" style="color:rgba(255,255,255,0.35);">{{ $stat['label'] }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- ─── STEPS ────────────────────────────────────── --}}
    <div class="mb-10">
        <p class="text-xs font-semibold uppercase tracking-widest text-center mb-8" style="color:rgba(255,255,255,0.3);">Proses Menjual</p>

        {{-- Mobile: vertical timeline / Desktop: horizontal row --}}

        {{-- DESKTOP (lg+): 3 cards in a row with arrow connectors --}}
        <div class="hidden lg:flex items-stretch gap-0">

            {{-- Card 1 --}}
            <div class="flex-1 rounded-2xl p-6 relative overflow-hidden group hover:-translate-y-1 transition-all duration-300"
                 style="background:rgba(124,58,237,0.08);border:1px solid rgba(124,58,237,0.25);">
                <div class="absolute top-0 left-0 right-0 h-0.5" style="background:linear-gradient(90deg,#7c3aed,#a78bfa);"></div>
                <div class="mb-5 flex items-center justify-between">
                    <div class="w-12 h-12 rounded-2xl flex items-center justify-center" style="background:rgba(124,58,237,0.2);">
                        <svg class="w-6 h-6" fill="none" stroke="#a78bfa" stroke-width="1.5" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/>
                        </svg>
                    </div>
                    <span class="text-5xl font-extrabold font-display opacity-10 text-white">01</span>
                </div>
                <h3 class="font-extrabold text-white text-base font-display mb-2">Datang ke Lokasi RuTip</h3>
                <p class="text-sm leading-relaxed" style="color:rgba(255,255,255,0.5);">Bawa barang bekas kosan yang ingin kamu jual ke gudang RuTip selama jam operasional.</p>
                <div class="mt-4 inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold"
                     style="background:rgba(124,58,237,0.15);color:#c4b5fd;border:1px solid rgba(124,58,237,0.25);">
                    ✓ Cepat & Praktis
                </div>
            </div>

            {{-- Arrow --}}
            <div class="flex items-center justify-center px-3 shrink-0">
                <div class="flex items-center gap-1" style="color:rgba(139,92,246,0.5);">
                    <div class="w-8 h-px" style="background:rgba(139,92,246,0.4);"></div>
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 18l6-6-6-6"/></svg>
                </div>
            </div>

            {{-- Card 2 --}}
            <div class="flex-1 rounded-2xl p-6 relative overflow-hidden group hover:-translate-y-1 transition-all duration-300"
                 style="background:rgba(99,102,241,0.08);border:1px solid rgba(99,102,241,0.25);">
                <div class="absolute top-0 left-0 right-0 h-0.5" style="background:linear-gradient(90deg,#6366f1,#a78bfa);"></div>
                <div class="mb-5 flex items-center justify-between">
                    <div class="w-12 h-12 rounded-2xl flex items-center justify-center" style="background:rgba(99,102,241,0.2);">
                        <svg class="w-6 h-6" fill="none" stroke="#a5b4fc" stroke-width="1.5" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87"/><path d="M16 3.13a4 4 0 010 7.75"/>
                        </svg>
                    </div>
                    <span class="text-5xl font-extrabold font-display opacity-10 text-white">02</span>
                </div>
                <h3 class="font-extrabold text-white text-base font-display mb-2">Inspeksi & Sepakati Harga</h3>
                <p class="text-sm leading-relaxed" style="color:rgba(255,255,255,0.5);">Tim kami mengecek kondisi barang dan menyepakati harga jual yang adil bersamamu.</p>
                <div class="mt-4 inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold"
                     style="background:rgba(99,102,241,0.15);color:#a5b4fc;border:1px solid rgba(99,102,241,0.25);">
                    ✓ Harga Transparan
                </div>
            </div>

            {{-- Arrow --}}
            <div class="flex items-center justify-center px-3 shrink-0">
                <div class="flex items-center gap-1" style="color:rgba(139,92,246,0.5);">
                    <div class="w-8 h-px" style="background:rgba(139,92,246,0.4);"></div>
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 18l6-6-6-6"/></svg>
                </div>
            </div>

            {{-- Card 3 --}}
            <div class="flex-1 rounded-2xl p-6 relative overflow-hidden group hover:-translate-y-1 transition-all duration-300"
                 style="background:rgba(52,211,153,0.06);border:1px solid rgba(52,211,153,0.2);">
                <div class="absolute top-0 left-0 right-0 h-0.5" style="background:linear-gradient(90deg,#34d399,#6ee7b7);"></div>
                <div class="mb-5 flex items-center justify-between">
                    <div class="w-12 h-12 rounded-2xl flex items-center justify-center" style="background:rgba(52,211,153,0.15);">
                        <svg class="w-6 h-6" fill="none" stroke="#34d399" stroke-width="1.5" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="20 6 9 17 4 12"/>
                        </svg>
                    </div>
                    <span class="text-5xl font-extrabold font-display opacity-10 text-white">03</span>
                </div>
                <h3 class="font-extrabold text-white text-base font-display mb-2">Serah Terima & Tayang</h3>
                <p class="text-sm leading-relaxed" style="color:rgba(255,255,255,0.5);">Serahkan barangmu. Kami foto dan iklankan langsung ke katalog digital RuTip.</p>
                <div class="mt-4 inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold"
                     style="background:rgba(52,211,153,0.1);color:#6ee7b7;border:1px solid rgba(52,211,153,0.2);">
                    ✓ Langsung Tayang
                </div>
            </div>
        </div>

        {{-- MOBILE (< lg): clean left-side timeline --}}
        <div class="lg:hidden space-y-0">
            @php
                $mobileSteps = [
                    [
                        'num' => '01',
                        'icon_svg' => '<path stroke-linecap="round" stroke-linejoin="round" d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/>',
                        'heading' => 'Datang ke Lokasi RuTip',
                        'desc' => 'Bawa barang bekas kosan yang ingin kamu jual ke gudang RuTip selama jam operasional.',
                        'pill' => 'Cepat & Praktis',
                        'accent' => '#a78bfa',
                        'iconBg' => 'rgba(124,58,237,0.2)',
                        'dotBorder' => '#7c3aed',
                        'dotGlow' => 'rgba(124,58,237,0.5)',
                        'cardBorder' => 'rgba(124,58,237,0.2)',
                        'pillBg' => 'rgba(124,58,237,0.15)',
                        'pillBorder' => 'rgba(124,58,237,0.3)',
                    ],
                    [
                        'num' => '02',
                        'icon_svg' => '<path stroke-linecap="round" stroke-linejoin="round" d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87"/><path d="M16 3.13a4 4 0 010 7.75"/>',
                        'heading' => 'Inspeksi & Sepakati Harga',
                        'desc' => 'Tim kami mengecek kondisi barang dan menyepakati harga jual yang adil bersamamu.',
                        'pill' => 'Harga Transparan',
                        'accent' => '#a5b4fc',
                        'iconBg' => 'rgba(99,102,241,0.2)',
                        'dotBorder' => '#6366f1',
                        'dotGlow' => 'rgba(99,102,241,0.5)',
                        'cardBorder' => 'rgba(99,102,241,0.2)',
                        'pillBg' => 'rgba(99,102,241,0.15)',
                        'pillBorder' => 'rgba(99,102,241,0.3)',
                    ],
                    [
                        'num' => '03',
                        'icon_svg' => '<polyline points="20 6 9 17 4 12"/>',
                        'heading' => 'Serah Terima & Tayang',
                        'desc' => 'Serahkan barangmu. Kami foto dan iklankan langsung ke katalog digital RuTip.',
                        'pill' => 'Langsung Tayang',
                        'accent' => '#34d399',
                        'iconBg' => 'rgba(52,211,153,0.15)',
                        'dotBorder' => '#34d399',
                        'dotGlow' => 'rgba(52,211,153,0.5)',
                        'cardBorder' => 'rgba(52,211,153,0.2)',
                        'pillBg' => 'rgba(52,211,153,0.1)',
                        'pillBorder' => 'rgba(52,211,153,0.25)',
                    ],
                ];
            @endphp

            @foreach ($mobileSteps as $idx => $s)
            <div class="flex gap-4">
                {{-- Left: dot + line --}}
                <div class="flex flex-col items-center" style="width:44px;flex-shrink:0;">
                    {{-- Dot --}}
                    <div class="w-10 h-10 rounded-2xl flex items-center justify-center shrink-0 relative z-10"
                         style="background:{{ $s['iconBg'] }};border:1.5px solid {{ $s['dotBorder'] }};box-shadow:0 0 16px {{ $s['dotGlow'] }};">
                        <svg class="w-5 h-5" fill="none" stroke="{{ $s['accent'] }}" stroke-width="1.5" viewBox="0 0 24 24">
                            {!! $s['icon_svg'] !!}
                        </svg>
                    </div>
                    {{-- Connector line --}}
                    @if (!$loop->last)
                    <div class="w-px flex-1 my-2" style="background:linear-gradient(180deg,{{ $s['dotBorder'] }}80,transparent);min-height:40px;"></div>
                    @endif
                </div>

                {{-- Right: content --}}
                <div class="{{ $loop->last ? 'pb-0' : 'pb-8' }} flex-1 pt-1.5">
                    <div class="flex items-center gap-2 mb-1.5">
                        <span class="text-[10px] font-extrabold tracking-widest" style="color:{{ $s['accent'] }};">LANGKAH {{ $s['num'] }}</span>
                    </div>
                    <h3 class="font-extrabold text-white text-base font-display mb-1.5 leading-snug">{{ $s['heading'] }}</h3>
                    <p class="text-sm leading-relaxed mb-3" style="color:rgba(255,255,255,0.5);">{{ $s['desc'] }}</p>
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold"
                          style="background:{{ $s['pillBg'] }};color:{{ $s['accent'] }};border:1px solid {{ $s['pillBorder'] }};">
                        ✓ {{ $s['pill'] }}
                    </span>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    {{-- ─── BENEFITS ROW ────────────────────────────── --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 mb-8">
        @foreach([
            ['icon'=>'tag','title'=>'Gratis Listing','desc'=>'Tidak ada biaya untuk mendaftarkan barang.','c'=>'rgba(124,58,237,0.18)','s'=>'#a78bfa'],
            ['icon'=>'zap','title'=>'Cepat Laku','desc'=>'Barang ditampilkan langsung ke ribuan pengguna.','c'=>'rgba(251,191,36,0.15)','s'=>'#fcd34d'],
            ['icon'=>'shield-check','title'=>'Transaksi Aman','desc'=>'Proses pembayaran dikelola langsung oleh RuTip.','c'=>'rgba(52,211,153,0.12)','s'=>'#34d399'],
        ] as $b)
        <div class="rounded-2xl p-4 flex items-start gap-3" style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.07);">
            <div class="w-9 h-9 rounded-xl flex items-center justify-center shrink-0" style="background:{{ $b['c'] }};">
                <x-dynamic-component :component="'lucide-' . $b['icon']" class="w-4 h-4" style="color:{{ $b['s'] }};" />
            </div>
            <div>
                <p class="text-xs font-bold text-white mb-0.5">{{ $b['title'] }}</p>
                <p class="text-xs leading-snug" style="color:rgba(255,255,255,0.4);">{{ $b['desc'] }}</p>
            </div>
        </div>
        @endforeach
    </div>

    {{-- ─── INFO CARD ───────────────────────────────── --}}
    <div class="rounded-2xl p-5 mb-6 flex items-start gap-4" style="background:rgba(124,58,237,0.07);border:1px solid rgba(124,58,237,0.18);">
        <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0" style="background:rgba(124,58,237,0.15);">
            <svg class="w-5 h-5" fill="none" stroke="#a78bfa" stroke-width="1.5" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
            </svg>
        </div>
        <div>
            <p class="text-sm font-bold text-white mb-1">Jam Operasional RuTip</p>
            <p class="text-xs leading-relaxed" style="color:rgba(255,255,255,0.5);">
                Senin – Jumat: 08.00 – 17.00 WIB &nbsp;·&nbsp; Sabtu: 09.00 – 14.00 WIB
            </p>
            <p class="text-xs mt-0.5" style="color:rgba(255,255,255,0.3);">Minggu & Hari Libur: Tutup</p>
        </div>
    </div>

    {{-- ─── CTAs ────────────────────────────────────── --}}
    <div class="flex flex-col gap-3">
        <a href="{{ $mapsUrl }}" target="_blank" rel="noopener noreferrer"
           class="w-full flex items-center justify-center gap-2 py-4 rounded-2xl font-bold text-sm text-white transition-all hover:scale-[1.02] active:scale-[0.98]"
           style="background:linear-gradient(135deg,#7c3aed,#6366f1);box-shadow:0 6px 20px rgba(124,58,237,0.4);">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>
            Lihat Lokasi Gudang
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 18l6-6-6-6"/></svg>
        </a>
        <a href="{{ route('preloved.index') }}"
           class="w-full flex items-center justify-center py-3.5 rounded-2xl text-sm font-semibold transition-all hover:bg-white/5"
           style="border:1.5px solid rgba(255,255,255,0.12);color:rgba(255,255,255,0.65);">
            Lihat Katalog Preloved
        </a>
    </div>

</div>
@endsection
