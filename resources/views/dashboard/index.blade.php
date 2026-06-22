@extends('layouts.dashboard')

@section('title', 'Beranda')

@php
    function rupiah($n) { return 'Rp ' . number_format($n, 0, ',', '.'); }
@endphp

@section('content')

{{-- ─── HERO BANNER ─── --}}
<section class="pt-4 lg:pt-6 mb-8">
    <div class="relative overflow-hidden rounded-2xl lg:rounded-3xl"
         style="background:linear-gradient(135deg,#3b0764 0%,#4c1d95 30%,#312e81 60%,#1e1b4b 100%);min-height:320px;">
        {{-- Dekorasi --}}
        <div class="absolute inset-0 pointer-events-none">
            <div class="absolute -top-20 -right-20 w-72 h-72 rounded-full opacity-30 blur-3xl" style="background:radial-gradient(circle,#7c3aed,transparent 70%);"></div>
            <div class="absolute bottom-0 left-1/4 w-48 h-48 rounded-full opacity-20 blur-3xl" style="background:radial-gradient(circle,#7c3aed,transparent 70%);"></div>
            <div class="absolute inset-0 opacity-[0.04]" style="background-image:linear-gradient(rgba(255,255,255,1) 1px,transparent 1px),linear-gradient(90deg,rgba(255,255,255,1) 1px,transparent 1px);background-size:32px 32px;"></div>
        </div>

        <div class="relative z-10 flex flex-col lg:flex-row items-center gap-8 px-6 py-8 lg:px-10 lg:py-10">
            {{-- Kiri --}}
            <div class="flex-1 text-center lg:text-left">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold mb-4"
                      style="background:rgba(251,146,60,0.2);color:#fb923c;border:1px solid rgba(251,146,60,0.3);">
                    <x-lucide-star class="w-3 h-3" /> Selamat datang{{ $user->name ? ', ' . explode(' ', $user->name)[0] : '' }}! 👋
                </span>
                <h1 class="text-2xl lg:text-4xl font-extrabold text-white font-display leading-tight mb-3">
                    Titip Barangmu,<br>
                    <span style="background:linear-gradient(90deg,#fb923c,#f472b6);-webkit-background-clip:text;-webkit-text-fill-color:transparent;">Simpan Uangmu</span>
                </h1>
                <p class="text-sm lg:text-base mb-6 max-w-md mx-auto lg:mx-0" style="color:rgba(255,255,255,0.65);">
                    Hemat biaya kos hingga jutaan rupiah dengan menitipkan barangmu di gudang RUTIP yang aman dan terpercaya.
                </p>
                <div class="flex flex-col sm:flex-row gap-3 justify-center lg:justify-start">
                    <a href="#" class="flex items-center justify-center gap-2 px-6 py-3 rounded-xl font-bold text-sm transition-all hover:scale-105"
                       style="background:linear-gradient(135deg,#ea580c,#fb923c);color:#fff;box-shadow:0 6px 24px rgba(234,88,12,0.45);">
                        Titip Barang Sekarang <x-lucide-arrow-right class="w-4 h-4" />
                    </a>
                    <a href="#" class="flex items-center justify-center gap-2 px-6 py-3 rounded-xl font-semibold text-sm transition-all hover:bg-white/10"
                       style="color:rgba(255,255,255,0.8);border:1px solid rgba(255,255,255,0.2);">
                        Lihat Pesanan Aktif
                    </a>
                </div>
                <div class="flex items-center gap-6 mt-6 justify-center lg:justify-start">
                    <div class="flex items-center gap-1.5"><x-lucide-shield class="w-3.5 h-3.5" style="color:#a78bfa;" /><span class="text-xs font-medium" style="color:rgba(255,255,255,0.6);">100% Aman</span></div>
                    <div class="flex items-center gap-1.5"><x-lucide-clock class="w-3.5 h-3.5" style="color:#34d399;" /><span class="text-xs font-medium" style="color:rgba(255,255,255,0.6);">Jemput 24 Jam</span></div>
                    <div class="flex items-center gap-1.5"><x-lucide-star class="w-3.5 h-3.5" style="color:#fb923c;" /><span class="text-xs font-medium" style="color:rgba(255,255,255,0.6);">4.9 Rating</span></div>
                </div>
            </div>

            {{-- Kanan: ilustrasi --}}
            <div class="shrink-0 relative">
                <div class="relative w-52 h-52 lg:w-64 lg:h-64">
                    <div class="absolute inset-0 rounded-full opacity-20" style="background:radial-gradient(circle,#a78bfa,transparent 70%);"></div>
                    <svg viewBox="0 0 200 200" class="w-full h-full" fill="none">
                        <ellipse cx="100" cy="185" rx="55" ry="8" fill="rgba(0,0,0,0.2)"/>
                        <rect x="72" y="88" width="56" height="68" rx="8" fill="#7c3aed"/>
                        <rect x="90" y="96" width="20" height="14" rx="3" fill="rgba(255,255,255,0.15)"/>
                        <circle cx="100" cy="72" r="22" fill="#fbbf24"/>
                        <path d="M78 65 Q100 48 122 65 Q120 56 100 52 Q80 56 78 65Z" fill="#1c1917"/>
                        <circle cx="93" cy="70" r="3" fill="#1c1917"/><circle cx="107" cy="70" r="3" fill="#1c1917"/>
                        <path d="M93 78 Q100 84 107 78" stroke="#1c1917" stroke-width="2" stroke-linecap="round" fill="none"/>
                        <rect x="48" y="100" width="24" height="12" rx="6" fill="#fbbf24" transform="rotate(-20 60 106)"/>
                        <rect x="128" y="100" width="24" height="12" rx="6" fill="#fbbf24" transform="rotate(20 140 106)"/>
                        <rect x="52" y="108" width="96" height="60" rx="6" fill="#f97316"/>
                        <rect x="52" y="108" width="96" height="20" rx="6" fill="#ea580c"/>
                        <rect x="82" y="104" width="36" height="8" rx="2" fill="#fbbf24" opacity="0.9"/>
                        <text x="100" y="158" text-anchor="middle" fill="white" font-size="10" font-weight="bold" opacity="0.8">RUTIP</text>
                        <rect x="78" y="152" width="18" height="36" rx="9" fill="#312e81"/>
                        <rect x="104" y="152" width="18" height="36" rx="9" fill="#312e81"/>
                        <ellipse cx="87" cy="188" rx="13" ry="6" fill="#1c1917"/>
                        <ellipse cx="113" cy="188" rx="13" ry="6" fill="#1c1917"/>
                    </svg>
                    <div class="absolute -top-2 -right-2 px-2.5 py-1.5 rounded-xl text-xs font-bold" style="background:linear-gradient(135deg,#059669,#10b981);color:white;box-shadow:0 4px 12px rgba(5,150,105,0.4);">Hemat Rp 2,4jt/bln</div>
                    <div class="absolute -bottom-2 -left-4 px-2.5 py-1.5 rounded-xl text-xs font-bold flex items-center gap-1" style="background:rgba(124,58,237,0.9);color:white;border:1px solid rgba(167,139,250,0.3);">
                        <x-lucide-shield class="w-3 h-3" /> Terjamin Aman
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ─── RUANG TITIP ─── --}}
<section class="mb-10">
    <div class="flex items-center justify-between mb-5">
        <h2 class="text-base font-bold text-white">Ruang Titip</h2>
        <a href="{{ route('ruang-titip.index') }}" class="flex items-center gap-1 text-xs font-semibold" style="color:#a78bfa;">Lihat Semua <x-lucide-chevron-right class="w-3.5 h-3.5" /></a>
    </div>
    @if ($storages->isEmpty())
        <div class="rounded-2xl p-6 text-center" style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.09);">
            <p class="text-xs" style="color:rgba(255,255,255,0.45);">Belum ada gudang yang tersedia saat ini.</p>
        </div>
    @else
    <div class="flex gap-4 overflow-x-auto pb-2 lg:overflow-visible lg:grid lg:grid-cols-3 lg:pb-0 no-scrollbar">
        @foreach ($storages as $s)
            <div class="rounded-2xl overflow-hidden shrink-0 w-72 lg:w-auto transition-all hover:-translate-y-0.5" style="background:rgba(255,255,255,0.05);border:1px solid rgba(255,255,255,0.09);">
                <div class="h-32 flex items-center justify-center relative" style="background:linear-gradient(135deg,#7c3aed20,#7c3aed08);">
                    @if($s->primary_photo)
                        <img src="{{ asset('storage/'.$s->primary_photo) }}" alt="{{ $s->name }}" class="w-full h-full object-cover">
                    @else
                        <x-lucide-warehouse class="w-12 h-12" style="color:#a78bfa;" />
                    @endif
                </div>
                <div class="p-4">
                    <h3 class="text-sm font-bold text-white leading-snug mb-1">{{ $s->name }}</h3>
                    <div class="flex items-center gap-1.5 mb-3">
                        <x-lucide-map-pin class="w-3 h-3 shrink-0" style="color:rgba(255,255,255,0.3);" />
                        <p class="text-[11px] truncate" style="color:rgba(255,255,255,0.45);">{{ $s->address }}</p>
                    </div>
                    <div class="mb-3">
                        <div class="flex justify-between mb-1.5">
                            <span class="text-[10px]" style="color:rgba(255,255,255,0.4);">Kapasitas terisi</span>
                            <span class="text-[10px] font-bold" style="color:{{ $s->capacity_pct >= 80 ? '#7c3aed' : '#34d399' }};">{{ $s->capacity_pct }}%</span>
                        </div>
                        <div class="h-1.5 rounded-full overflow-hidden" style="background:rgba(255,255,255,0.07);">
                            <div class="h-full rounded-full" style="width:{{ $s->capacity_pct }}%;background:linear-gradient(90deg,#7c3aed,#7c3aed99);"></div>
                        </div>
                    </div>
                    <div class="flex flex-wrap gap-1.5 mb-4">
                        @foreach (($s->facilities ?? []) as $tag)
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-medium" style="background:rgba(255,255,255,0.07);color:rgba(255,255,255,0.5);">{{ $tag }}</span>
                        @endforeach
                    </div>
                    <div class="pt-3 mb-3" style="border-top:1px solid rgba(255,255,255,0.07);">
                        <p class="text-sm font-bold" style="color:#7c3aed;">{{ rupiah($s->min_price) }}</p>
                        <p class="text-[10px]" style="color:rgba(255,255,255,0.3);">mulai dari / hari</p>
                    </div>
                    <a href="{{ route('ruang-titip.detail', $s) }}" class="block text-center w-full py-2.5 rounded-xl text-xs font-bold transition-all hover:scale-[1.02]" style="border:1.5px solid #7c3aed;color:#7c3aed;background:#7c3aed10;">Lihat</a>
                </div>
            </div>
        @endforeach
    </div>
    @endif
</section>

{{-- ─── TOKO PACKING ─── --}}
<section class="mb-10">
    <div class="flex items-center justify-between mb-5">
        <h2 class="text-base font-bold text-white">Toko Packing</h2>
        <a href="#" class="flex items-center gap-1 text-xs font-semibold" style="color:#a78bfa;">Lihat Semua <x-lucide-chevron-right class="w-3.5 h-3.5" /></a>
    </div>
    @if ($packing->isEmpty())
        <div class="rounded-2xl p-6 text-center" style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.09);">
            <p class="text-xs" style="color:rgba(255,255,255,0.45);">Belum ada produk di Toko Packing saat ini.</p>
        </div>
    @else
    <div class="flex gap-4 overflow-x-auto pb-2 lg:overflow-visible lg:grid lg:grid-cols-4 lg:pb-0 no-scrollbar">
        @foreach ($packing as $p)
            <div class="rounded-2xl overflow-hidden shrink-0 w-44 lg:w-auto transition-all hover:-translate-y-0.5" style="background:rgba(255,255,255,0.05);border:1px solid rgba(255,255,255,0.09);">
                <div class="h-24 flex items-center justify-center text-4xl" style="background:#7c3aed12;">
                    @if($p->primary_image)
                        <img src="{{ asset('storage/'.$p->primary_image) }}" alt="{{ $p->name }}" class="w-full h-full object-cover">
                    @else
                        <x-lucide-package class="w-10 h-10" style="color:#a78bfa;" />
                    @endif
                </div>
                <div class="p-3">
                    <p class="text-xs font-bold text-white mb-0.5">{{ $p->name }}</p>
                    <p class="text-xs font-bold mb-0.5" style="color:#7c3aed;">{{ rupiah($p->price) }}</p>
                    <p class="text-[10px] mb-3" style="color:rgba(255,255,255,0.35);">Stok: {{ $p->stock }}</p>
                    <div class="flex gap-1.5">
                        <a href="{{ route('packing.show', $p) }}" class="flex-1 flex items-center justify-center gap-0.5 py-2 rounded-xl text-[10px] font-bold" style="border:1.5px solid rgba(124,58,237,0.5);color:#a78bfa;"><x-lucide-plus class="w-3 h-3" /> Keranjang</a>
                        <a href="{{ route('packing.show', $p) }}" class="flex-1 text-center py-2 rounded-xl text-[10px] font-bold text-white" style="background:linear-gradient(135deg,#7c3aed,#6366f1);">Beli</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    @endif
</section>

{{-- ─── TOKO PRELOVED ─── --}}
<section class="mb-10">
    <div class="flex items-center justify-between mb-5">
        <h2 class="text-base font-bold text-white">Toko Preloved</h2>
        <a href="#" class="flex items-center gap-1 text-xs font-semibold" style="color:#a78bfa;">Lihat Semua <x-lucide-chevron-right class="w-3.5 h-3.5" /></a>
    </div>
    <div class="flex items-center justify-between px-5 py-4 rounded-2xl mb-5"
         style="background:linear-gradient(135deg,rgba(124,58,237,0.15),rgba(99,102,241,0.1));border:1px solid rgba(124,58,237,0.3);">
        <div class="flex items-center gap-3">
            <span class="text-2xl">🏷️</span>
            <div>
                <p class="text-xs font-bold text-white">Punya barang nganggur? Jual lewat RuTip Preloved!</p>
                <p class="text-[10px] mt-0.5" style="color:rgba(255,255,255,0.45);">Gratis listing · Bayar saat terjual</p>
            </div>
        </div>
        <a href="{{ route('preloved.cara-jual') }}" class="shrink-0 flex items-center gap-1 px-3 py-2 rounded-xl text-[11px] font-bold" style="border:1.5px solid rgba(124,58,237,0.4);color:#a78bfa;background:rgba(124,58,237,0.08);">Pelajari <x-lucide-arrow-right class="w-3 h-3" /></a>
    </div>
    @if ($preloved->isEmpty())
        <div class="rounded-2xl p-6 text-center" style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.09);">
            <p class="text-xs" style="color:rgba(255,255,255,0.45);">Belum ada produk preloved yang tersedia saat ini.</p>
        </div>
    @else
    <div class="flex gap-4 overflow-x-auto pb-2 lg:overflow-visible lg:grid lg:grid-cols-4 lg:pb-0 no-scrollbar">
        @foreach ($preloved as $p)
            <div class="rounded-2xl overflow-hidden shrink-0 w-44 lg:w-auto transition-all hover:-translate-y-0.5" style="background:rgba(255,255,255,0.05);border:1px solid rgba(255,255,255,0.09);">
                <div class="h-24 flex items-center justify-center text-4xl" style="background:rgba(255,255,255,0.04);">
                    @if($p->primary_photo)
                        <img src="{{ asset('storage/'.$p->primary_photo) }}" alt="{{ $p->name }}" class="w-full h-full object-cover">
                    @else
                        <x-lucide-image class="w-10 h-10" style="color:#a78bfa;" />
                    @endif
                </div>
                <div class="p-3">
                    <span class="inline-block text-[9px] font-bold px-2 py-0.5 rounded-full mb-2" style="background:rgba(52,211,153,0.12);color:#34d399;">{{ $p->condition }}%</span>
                    <p class="text-xs font-bold text-white mb-0.5">{{ $p->name }}</p>
                    <p class="text-xs font-bold mb-3" style="color:#a78bfa;">{{ rupiah($p->price) }}</p>
                    <a href="{{ route('preloved.show', $p->id) }}" class="block text-center w-full py-2 rounded-xl text-[10px] font-bold text-white" style="background:linear-gradient(135deg,#7c3aed,#a78bfa);">Beli</a>
                </div>
            </div>
        @endforeach
    </div>
    @endif
</section>

{{-- ─── TESTIMONI ─── --}}
<section class="mb-12">
    <div class="text-center mb-5">
        <h2 class="text-base font-bold text-white mb-0.5">Apa Kata Mereka</h2>
        <p class="text-xs" style="color:rgba(255,255,255,0.4);">Cerita nyata dari pengguna RUTIP</p>
    </div>
    <div class="flex gap-4 overflow-x-auto pb-2 lg:overflow-visible lg:grid lg:grid-cols-3 lg:pb-0 no-scrollbar">
        @foreach ($testimonials as $t)
            <div class="rounded-2xl p-4 shrink-0 w-72 lg:w-auto transition-all hover:-translate-y-0.5" style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.09);">
                <div class="w-8 h-8 rounded-lg flex items-center justify-center mb-3" style="background:rgba(124,58,237,0.15);">
                    <x-lucide-quote class="w-3.5 h-3.5" style="color:#a78bfa;" />
                </div>
                <div class="flex gap-0.5 mb-2.5">
                    @for ($i = 0; $i < 5; $i++)<x-lucide-star class="w-3.5 h-3.5" style="color:#fbbf24;" />@endfor
                </div>
                <p class="text-xs leading-relaxed mb-4" style="color:rgba(255,255,255,0.6);font-style:italic;">"{{ $t['text'] }}"</p>
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center text-white text-[10px] font-bold shrink-0" style="background:{{ $t['color'] }};">{{ $t['avatar'] }}</div>
                    <div>
                        <p class="text-xs font-semibold text-white">{{ $t['name'] }}</p>
                        <p class="text-[10px]" style="color:rgba(255,255,255,0.35);">{{ $t['major'] }}</p>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</section>

@endsection
