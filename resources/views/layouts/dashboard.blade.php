<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - RUTIP</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root { --font-display:'Plus Jakarta Sans',sans-serif; --font-body:'Inter',sans-serif; }
        body { font-family: var(--font-body); }
        .font-display { font-family: var(--font-display); }
        .no-scrollbar::-webkit-scrollbar { display:none; }
        .no-scrollbar { -ms-overflow-style:none; scrollbar-width:none; }

        /* ─── Product card image carousel ─── */
        .rt-carousel { position: relative; overflow: hidden; }
        .rt-carousel img.rt-slide {
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            width: 100%; height: 100%;
            object-fit: cover;
            opacity: 0;
            z-index: 0;
            transition: opacity .25s ease;
            display: block !important;
        }
        .rt-carousel img.rt-slide.active {
            opacity: 1;
            z-index: 1;
        }
        .rt-carousel-btn {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            width: 24px; height: 24px;
            border-radius: 50%;
            background: rgba(0,0,0,0.45);
            color: #fff;
            display: flex; align-items: center; justify-content: center;
            border: none;
            cursor: pointer;
            z-index: 3;
            transition: background .2s;
        }
        .rt-carousel-btn:hover { background: rgba(124,58,237,0.85); }
        .rt-carousel-btn.rt-prev { left: 6px; }
        .rt-carousel-btn.rt-next { right: 6px; }
        .rt-carousel-dots {
            position: absolute;
            bottom: 6px; left: 0; right: 0;
            display: flex; justify-content: center; gap: 4px;
            z-index: 3;
        }
        .rt-carousel-dots span {
            width: 5px; height: 5px; border-radius: 50%;
            background: rgba(255,255,255,0.4);
        }
        .rt-carousel-dots span.active { background: #fff; }

        /* ─── Lightbox overlay ─── */
        #rt-lightbox {
            display: none;
            position: fixed; inset: 0;
            z-index: 9999;
            background: rgba(0,0,0,0.93);
            align-items: center; justify-content: center;
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
        }
        #rt-lightbox.open { display: flex; }
        #rt-lightbox .lb-img {
            max-width: min(92vw, 560px);
            max-height: 80vh;
            object-fit: contain;
            border-radius: 12px;
            box-shadow: 0 24px 80px rgba(0,0,0,0.7);
            transition: opacity .2s;
        }
        #rt-lightbox .lb-close {
            position: absolute; top: 16px; right: 16px;
            width: 40px; height: 40px; border-radius: 50%;
            background: rgba(255,255,255,0.12);
            color: #fff; border: none; cursor: pointer;
            display: flex; align-items: center; justify-content: center;
            font-size: 20px; z-index: 2;
        }
        #rt-lightbox .lb-close:hover { background: rgba(255,255,255,0.22); }
        #rt-lightbox .lb-nav {
            position: absolute; top: 50%;
            transform: translateY(-50%);
            width: 40px; height: 40px; border-radius: 50%;
            background: rgba(255,255,255,0.12);
            color: #fff; border: none; cursor: pointer;
            display: flex; align-items: center; justify-content: center;
            z-index: 2;
        }
        #rt-lightbox .lb-nav:hover { background: rgba(124,58,237,0.6); }
        #rt-lightbox .lb-prev { left: 16px; }
        #rt-lightbox .lb-next { right: 16px; }
        #rt-lightbox .lb-counter {
            position: absolute; bottom: 18px;
            font-size: 13px; color: rgba(255,255,255,0.6);
        }
        #rt-lightbox .lb-thumbs {
            position: absolute; bottom: 44px;
            display: flex; gap: 8px;
            max-width: 90vw; overflow-x: auto;
        }
        #rt-lightbox .lb-thumbs img {
            width: 52px; height: 52px; object-fit: cover;
            border-radius: 8px; cursor: pointer;
            opacity: .55; border: 2px solid transparent;
            transition: opacity .15s, border-color .15s;
            flex-shrink: 0;
        }
        #rt-lightbox .lb-thumbs img.active {
            opacity: 1;
            border-color: #a78bfa;
        }

        /* ─── Product detail gallery thumbnails ─── */
        .rt-thumb-strip {
            display: flex; gap: 8px;
            overflow-x: auto; padding: 4px 0;
        }
        .rt-thumb-strip::-webkit-scrollbar { height: 3px; }
        .rt-thumb-strip::-webkit-scrollbar-thumb { background: rgba(124,58,237,0.4); border-radius:2px; }
        .rt-thumb-strip button {
            width: 52px; height: 52px; flex-shrink: 0;
            border-radius: 10px; overflow: hidden;
            background: rgba(255,255,255,0.05);
            border: 2px solid transparent;
            cursor: pointer; padding: 0;
            transition: border-color .15s, opacity .15s;
            opacity: .6;
        }
        .rt-thumb-strip button.active {
            border-color: #a78bfa; opacity: 1;
        }
        .rt-thumb-strip button img {
            width: 100%; height: 100%; object-fit: cover; display: block;
        }

        /* ── Welcome banner ── */
        #rt-welcome-banner {
            overflow: hidden;
            max-height: 0;
            opacity: 0;
            transition: max-height 0.5s cubic-bezier(0.22,1,0.36,1), opacity 0.4s ease, margin-bottom 0.5s ease;
            margin-bottom: 0;
        }
        #rt-welcome-banner.open {
            max-height: 200px;
            opacity: 1;
            margin-bottom: 24px;
        }
        #rt-welcome-banner.closing {
            max-height: 0;
            opacity: 0;
            margin-bottom: 0;
        }
        #rt-welcome-progress {
            height: 3px;
            border-radius: 0 0 16px 16px;
            background: linear-gradient(90deg, #7c3aed, #a78bfa);
            width: 100%;
            transition: width linear;
        }

        /* ── Scroll reveal ── */
        .reveal {
            opacity: 0;
            transform: translateY(22px);
            transition: opacity 0.55s ease, transform 0.55s ease;
        }
        .reveal.visible { opacity: 1; transform: translateY(0); }

        /* ── Page loader bar ── */
        #rt-page-bar {
            position: fixed; top: 0; left: 0; z-index: 9999;
            height: 3px; width: 0%;
            background: linear-gradient(90deg, #7c3aed, #a78bfa, #6366f1);
            transition: width 0.3s ease, opacity 0.4s ease;
            box-shadow: 0 0 12px rgba(124,58,237,0.7);
            pointer-events: none;
        }

        /* ── Skeleton loading ── */
        @keyframes rt-shimmer {
            0% { background-position: -400px 0; }
            100% { background-position: 400px 0; }
        }
        .rt-skeleton {
            background: linear-gradient(90deg, rgba(255,255,255,0.05) 25%, rgba(255,255,255,0.1) 50%, rgba(255,255,255,0.05) 75%);
            background-size: 800px 100%;
            animation: rt-shimmer 1.4s ease-in-out infinite;
            border-radius: 8px;
        }

        /* ── Toast ── */
        #rt-toast-stack {
            position: fixed; bottom: 80px; right: 16px; z-index: 9990;
            display: flex; flex-direction: column; gap: 10px;
            pointer-events: none;
        }
        @media(min-width:1024px) {
            #rt-toast-stack { bottom: 24px; right: 24px; }
        }
        .rt-toast {
            pointer-events: all;
            display: flex; align-items: flex-start; gap: 10px;
            padding: 12px 14px; border-radius: 14px;
            max-width: 320px; min-width: 220px;
            backdrop-filter: blur(16px);
            box-shadow: 0 8px 32px rgba(0,0,0,0.45);
            animation: toastIn .3s cubic-bezier(.22,1,.36,1) both;
        }
        .rt-toast.out { animation: toastOut .25s ease forwards; }
        @keyframes toastIn  { from { opacity:0; transform:translateX(40px) scale(.95); } to { opacity:1; transform:none; } }
        @keyframes toastOut { to   { opacity:0; transform:translateX(40px) scale(.95); } }
        .rt-toast-icon { width:20px; height:20px; shrink:0; margin-top:1px; }
        .rt-toast-body { flex:1; }
        .rt-toast-title { font-size:13px; font-weight:700; line-height:1.3; }
        .rt-toast-msg   { font-size:12px; margin-top:2px; line-height:1.4; opacity:.75; }
        .rt-toast-close { background:none; border:none; cursor:pointer; opacity:.5; color:inherit; padding:0; font-size:16px; line-height:1; margin-top:-1px; }
        .rt-toast-close:hover { opacity:.85; }
        .rt-toast.success { background:rgba(16,45,28,0.97); border:1px solid rgba(52,211,153,0.35); color:#6ee7b7; }
        .rt-toast.error   { background:rgba(45,16,16,0.97); border:1px solid rgba(239,68,68,0.35);  color:#fca5a5; }
        .rt-toast.info    { background:rgba(16,22,45,0.97); border:1px solid rgba(99,102,241,0.4);  color:#a5b4fc; }
        .rt-toast.warning { background:rgba(45,35,16,0.97); border:1px solid rgba(251,191,36,0.35); color:#fcd34d; }
    </style>
</head>
<body class="min-h-screen pb-20 lg:pb-0 flex flex-col" style="background:#0c0618;">
<div id="rt-page-bar"></div>

@php
    $u = $user ?? auth()->user();
    $initials = '';
    if ($u && $u->name) {
        $parts = preg_split('/\s+/', trim($u->name));
        foreach (array_slice($parts, 0, 2) as $p) { $initials .= strtoupper(substr($p, 0, 1)); }
    }

    $notifications = collect();
    if ($u) {
        $titipanMsg = [
            'menunggu_pembayaran'     => fn ($o) => "Pesanan #{$o->code()} menunggu pembayaran",
            'penjadwalan_penjemputan' => fn ($o) => "Pesanan #{$o->code()} dalam proses penjemputan",
            'dalam_gudang'            => fn ($o) => "Barangmu aman tersimpan di gudang RUTIP",
            'proses_pengembalian'     => fn ($o) => "Pesanan #{$o->code()} sedang diproses pengembalian",
            'selesai'                 => fn ($o) => "Pesanan #{$o->code()} telah selesai",
        ];
        foreach (\App\Models\TitipanOrder::where('user_id', $u->id)->latest('updated_at')->take(5)->get() as $o) {
            if ($msg = $titipanMsg[$o->status] ?? null) {
                $notifications->push(['text' => $msg($o), 'at' => $o->updated_at]);
            }
        }

        foreach (\App\Models\PackingOrder::where('user_id', $u->id)->latest('updated_at')->take(5)->get() as $o) {
            $text = match (true) {
                $o->payment_status === 'PAID' => "Pembayaran pesanan #{$o->order_code} berhasil dikonfirmasi",
                in_array($o->payment_status, ['FAILED', 'EXPIRED']) => "Pembayaran pesanan #{$o->order_code} gagal/kedaluwarsa",
                default => "Pesanan #{$o->order_code} menunggu pembayaran",
            };
            $notifications->push(['text' => $text, 'at' => $o->updated_at]);
        }

        foreach (\App\Models\Order::where('customer_email', $u->email)->latest('updated_at')->take(5)->get() as $o) {
            $text = match (true) {
                $o->status === 'delivered' => "Pesanan #{$o->order_number} telah selesai",
                $o->status === 'shipped' => "Pesanan #{$o->order_number} sedang dikirim",
                $o->status === 'processing' => "Pesanan #{$o->order_number} sedang diproses",
                $o->payment_status === 'PAID' => "Pembayaran pesanan #{$o->order_number} berhasil dikonfirmasi",
                in_array($o->payment_status, ['FAILED', 'EXPIRED']) => "Pembayaran pesanan #{$o->order_number} gagal/kedaluwarsa",
                default => "Pesanan #{$o->order_number} menunggu pembayaran",
            };
            $notifications->push(['text' => $text, 'at' => $o->updated_at]);
        }

        $notifications = $notifications->sortByDesc('at')->take(5)->values();
    }
    $notifUnreadCount = $notifications->filter(fn ($n) => $n['at'] && $n['at']->gt(now()->subDays(2)))->count();

    $navLinks = [
        ['label' => 'Beranda', 'route' => 'dashboard'],
        ['label' => 'Ruang Titip', 'route' => 'ruang-titip.index'],
        ['label' => 'Toko Packing', 'route' => 'packing.index'],
        ['label' => 'Toko Preloved', 'route' => 'preloved.index', 'match' => ['preloved.index', 'preloved.show']],
        ['label' => 'Pesanan Saya', 'route' => 'pesanan.index'],
    ];
@endphp

{{-- ─── NAVBAR (fixed top) ─── --}}
<nav class="fixed top-0 left-0 right-0 z-50"
     style="background:rgba(10,5,20,0.97);backdrop-filter:blur(20px);border-bottom:1px solid rgba(139,92,246,0.18);">
    <div class="max-w-7xl mx-auto px-4 lg:px-6 h-16 flex items-center justify-between">

        {{-- Logo --}}
        <a href="{{ route('dashboard') }}" class="flex items-center shrink-0">
            <img src="{{ asset('images/logo-rutip-putih.png') }}" alt="RUTIP" class="h-12 w-auto">
        </a>
        

        {{-- Desktop nav --}}
        <div class="hidden lg:flex items-center gap-1">
            @foreach ($navLinks as $link)
                @php
                    $href = isset($link['route']) ? route($link['route']) : $link['url'];
                    $active = false;
                    if (isset($link['route'])) {
                        $base = \Illuminate\Support\Str::contains($link['route'], '.')
                            ? \Illuminate\Support\Str::beforeLast($link['route'], '.') . '.*'
                            : $link['route'];
                        $patterns = $link['match'] ?? [$link['route'], $base];
                        foreach ((array) $patterns as $pattern) {
                            if (request()->routeIs($pattern)) {
                                $active = true;
                                break;
                            }
                        }
                    }
                @endphp
                <a href="{{ $href }}" class="relative px-3.5 py-2 rounded-lg text-sm font-medium transition-all"
                   style="color:{{ $active ? '#a78bfa' : 'rgba(255,255,255,0.55)' }};">
                    {{ $link['label'] }}
                    @if ($active)
                        <span class="absolute bottom-0 left-3.5 right-3.5 h-0.5 rounded-full" style="background:#7c3aed;"></span>
                    @endif
                </a>
            @endforeach
        </div>

        {{-- Right actions --}}
        <div class="flex items-center gap-1">
            {{-- Cart --}}
            @php $cartActive = request()->routeIs('preloved.cart.*') || request()->routeIs('checkout.*'); @endphp
            <a href="{{ route('preloved.cart.index') }}" aria-label="Keranjang" class="relative w-9 h-9 rounded-lg flex items-center justify-center transition-all hover:bg-white/5"
               style="color:{{ $cartActive ? '#a78bfa' : 'rgba(255,255,255,0.6)' }};background:{{ $cartActive ? 'rgba(124,58,237,0.16)' : 'transparent' }};">
                <x-lucide-shopping-cart class="w-5 h-5" />
                @if ($cartActive)
                    <span class="absolute bottom-0.5 left-1/2 -translate-x-1/2 w-1.5 h-1.5 rounded-full" style="background:#7c3aed;"></span>
                @endif
            </a>

          
            {{-- Notifikasi --}}
            <div class="relative">
                <button type="button" onclick="toggleMenu('notifMenu')" aria-label="Notifikasi" class="relative w-9 h-9 rounded-lg flex items-center justify-center transition-all hover:bg-white/5" style="color:rgba(255,255,255,0.6);">
                    <x-lucide-bell class="w-5 h-5" />
                    @if ($notifUnreadCount > 0)
                        <span class="absolute top-1 right-1 w-4 h-4 rounded-full text-[9px] font-bold text-white flex items-center justify-center" style="background:#ef4444;">{{ $notifUnreadCount }}</span>
                    @endif
                </button>
                <div id="notifMenu" class="hidden absolute right-0 top-12 w-80 rounded-2xl overflow-hidden z-50"
                      style="background:rgba(18,10,35,0.98);border:1px solid rgba(139,92,246,0.25);box-shadow:0 20px 60px rgba(0,0,0,0.5);">
                    <div class="px-4 py-3 flex items-center justify-between" style="border-bottom:1px solid rgba(255,255,255,0.07);">
                        <span class="text-sm font-semibold text-white">Notifikasi</span>
                        @if ($notifUnreadCount > 0)
                            <span class="text-xs px-2 py-0.5 rounded-full font-semibold" style="background:rgba(167,139,250,0.15);color:#a78bfa;">{{ $notifUnreadCount }} baru</span>
                        @endif
                    </div>
                    @forelse ($notifications as $n)
                        @php $isNew = $n['at'] && $n['at']->gt(now()->subDays(2)); @endphp
                        <div class="px-4 py-3 flex gap-3" style="border-bottom:1px solid rgba(255,255,255,0.04);">
                            <div class="w-2 h-2 rounded-full mt-1.5 shrink-0" style="background:{{ $isNew ? '#a78bfa' : 'rgba(255,255,255,0.15)' }};"></div>
                            <div>
                                <p class="text-xs leading-relaxed" style="color:{{ $isNew ? 'rgba(255,255,255,0.85)' : 'rgba(255,255,255,0.4)' }};">{{ $n['text'] }}</p>
                                <p class="text-[10px] mt-1" style="color:rgba(255,255,255,0.28);">{{ $n['at']?->diffForHumans() }}</p>
                            </div>
                        </div>
                    @empty
                        <div class="px-4 py-8 text-center">
                            <p class="text-xs" style="color:rgba(255,255,255,0.35);">Belum ada notifikasi.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Profil dropdown (Hanya boleh ada satu) --}}
            <div class="relative">
                <button type="button" onclick="toggleMenu('profileMenu')" aria-label="Profil" class="flex items-center gap-2 px-1.5 lg:px-2 py-1.5 rounded-xl transition-all hover:bg-white/5">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center text-white text-xs font-bold shrink-0" style="background:linear-gradient(135deg,#7c3aed,#6366f1);">{{ $initials ?: '?' }}</div>
                    <div class="hidden lg:block text-left">
                        <p class="text-xs font-semibold text-white leading-tight">{{ $u?->name ?? 'Pengguna' }}</p>
                        <p class="text-[10px]" style="color:rgba(255,255,255,0.35);">Penitip Aktif</p>
                    </div>
                    <x-lucide-chevron-down class="hidden lg:block w-3.5 h-3.5 ml-0.5" style="color:rgba(255,255,255,0.35);" />
                </button>
                <div id="profileMenu" class="hidden absolute right-0 w-52 rounded-2xl overflow-hidden z-50 py-1.5"
                      style="top:52px;background:rgba(18,10,35,0.98);border:1px solid rgba(139,92,246,0.25);box-shadow:0 20px 60px rgba(0,0,0,0.5);">
                    <a href="{{ route('profile.index') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm transition-colors hover:bg-white/5" style="color:rgba(255,255,255,0.7);">
                        <x-lucide-user class="w-4 h-4" style="color:rgba(255,255,255,0.35);" /> Profil
                    </a>
                    <a href="{{ route('profile.index', ['tab' => 'bantuan']) }}" class="flex items-center gap-3 px-4 py-2.5 text-sm transition-colors hover:bg-white/5" style="color:rgba(255,255,255,0.7);">
                        <x-lucide-help-circle class="w-4 h-4" style="color:rgba(255,255,255,0.35);" /> Bantuan
                    </a>

                    <div style="border-top:1px solid rgba(255,255,255,0.07);margin:4px 0;"></div>
                    <form method="POST" action="{{ route('logout') }}" class="logout-form">
                        @csrf
                        <button type="submit" class="w-full flex items-center gap-3 px-4 py-2.5 text-sm transition-colors hover:bg-red-500/10" style="color:#f87171;">
                            <x-lucide-log-out class="w-4 h-4" /> Keluar
                        </button>
                    </form>
                </div>
            </div>
            {{-- Mobile hamburger --}}
            <button type="button" onclick="toggleMenu('mobileDrawer')" class="lg:hidden w-9 h-9 flex items-center justify-center rounded-lg" style="color:rgba(255,255,255,0.7);">
                <x-lucide-menu class="w-5 h-5" />
            </button>
        </div>
    </div>

    {{-- Mobile drawer --}}
    <div id="mobileDrawer" class="hidden lg:hidden overflow-hidden" style="background:rgba(12,6,24,0.99);border-top:1px solid rgba(139,92,246,0.15);">
        <div class="px-4 py-3">
            <div class="flex items-center gap-3 px-3 py-3 rounded-xl mb-2" style="background:rgba(139,92,246,0.1);">
                <div class="w-10 h-10 rounded-full flex items-center justify-center text-white text-sm font-bold" style="background:linear-gradient(135deg,#7c3aed,#6366f1);">{{ $initials ?: '?' }}</div>
                <div>
                    <p class="text-sm font-semibold text-white">{{ $u?->name ?? 'Pengguna' }}</p>

                    <p class="text-xs" style="color:rgba(255,255,255,0.4);">Penitip Aktif</p>
                </div>
            </div>
            @foreach ($navLinks as $link)
                @php
                    $href = isset($link['route']) ? route($link['route']) : $link['url'];
                    $active = false;
                    if (isset($link['route'])) {
                        $base = \Illuminate\Support\Str::contains($link['route'], '.')
                            ? \Illuminate\Support\Str::beforeLast($link['route'], '.') . '.*'
                            : $link['route'];
                        $patterns = $link['match'] ?? [$link['route'], $base];
                        foreach ((array) $patterns as $pattern) {
                            if (request()->routeIs($pattern)) {
                                $active = true;
                                break;
                            }
                        }
                    }
                @endphp
                <a href="{{ $href }}" class="flex items-center px-3 py-2.5 rounded-xl text-sm font-medium" style="color:{{ $active ? '#a78bfa' : 'rgba(255,255,255,0.6)' }};">{{ $link['label'] }}</a>
            @endforeach
            <div style="border-top:1px solid rgba(255,255,255,0.07);margin-top:8px;padding-top:8px;">
                <form method="POST" action="{{ route('logout') }}" class="logout-form">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium" style="color:#f87171;">
                        <x-lucide-log-out class="w-4 h-4" /> Keluar
                    </button>
                </form>
            </div>
        </div>
    </div>
</nav>

{{-- ─── KONTEN ─── --}}
<main class="w-full max-w-7xl mx-auto px-4 lg:px-6 pt-20 flex-1">

    {{-- Welcome banner (only for new users after first login) --}}
    @if(session('welcome_type') === 'new')
    <div id="rt-welcome-banner">
        <div class="relative rounded-2xl overflow-hidden" style="background:linear-gradient(135deg,rgba(124,58,237,0.18),rgba(99,102,241,0.12));border:1px solid rgba(124,58,237,0.3);">
            {{-- Glow --}}
            <div class="absolute -top-10 -right-10 w-48 h-48 rounded-full blur-3xl pointer-events-none" style="background:radial-gradient(circle,rgba(124,58,237,0.25),transparent);"></div>

            <div class="relative flex items-center gap-4 px-5 py-4 pr-12">
                {{-- Icon --}}
                <div class="w-12 h-12 rounded-2xl flex items-center justify-center shrink-0 text-2xl"
                     style="background:linear-gradient(135deg,#7c3aed,#6366f1);box-shadow:0 8px 24px rgba(124,58,237,0.4);">
                    🎉
                </div>

                {{-- Text --}}
                <div class="flex-1 min-w-0">
                    <p class="font-extrabold text-white text-base font-display leading-tight">
                        Selamat datang di RUTIP, {{ session('welcome_name', 'Kamu') }}!
                    </p>
                    <p class="text-xs mt-0.5" style="color:rgba(255,255,255,0.55);">
                        Titip barang pertamamu sekarang — aman, praktis, dan bisa dipantau kapan saja.
                    </p>
                </div>

                {{-- CTA --}}
                <a href="{{ route('ruang-titip.index') }}"
                   class="hidden sm:inline-flex items-center gap-1.5 shrink-0 px-4 py-2 rounded-xl text-xs font-bold text-white transition-all hover:scale-105"
                   style="background:linear-gradient(135deg,#7c3aed,#6366f1);box-shadow:0 4px 14px rgba(124,58,237,0.4);">
                    Mulai Titip
                    <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14M12 5l7 7-7 7"/></svg>
                </a>

                {{-- Close --}}
                <button onclick="rtWelcomeDismiss()" class="absolute top-3 right-3 w-7 h-7 rounded-full flex items-center justify-center transition-all hover:bg-white/10" style="color:rgba(255,255,255,0.4);">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                </button>
            </div>

            {{-- Progress bar --}}
            <div id="rt-welcome-progress"></div>
        </div>
    </div>
    @endif

    @yield('content')
</main>
@include('layouts.footer')

{{-- ─── BOTTOM NAV (mobile) ─── --}}
<nav class="lg:hidden fixed bottom-0 left-0 right-0 z-50 flex items-center"
     style="background:rgba(10,5,20,0.97);backdrop-filter:blur(20px);border-top:1px solid rgba(139,92,246,0.18);height:64px;">
    @php
        $bottom = [
            ['label' => 'Beranda', 'route' => 'dashboard', 'icon' => 'home'],
            ['label' => 'Pesanan', 'route' => 'pesanan.index', 'icon' => 'clipboard-list'],
            ['label' => 'Toko', 'route' => 'preloved.index', 'match' => ['preloved.index', 'preloved.show'], 'icon' => 'shopping-bag'],
            ['label' => 'Profil', 'route' => 'profile.index', 'icon' => 'user'],
        ];
    @endphp
    @foreach ($bottom as $item)
        @php
            $href = isset($item['route']) ? route($item['route']) : $item['url'];
            $active = false;
            if (isset($item['route'])) {
                $base = \Illuminate\Support\Str::contains($item['route'], '.')
                    ? \Illuminate\Support\Str::beforeLast($item['route'], '.') . '.*'
                    : $item['route'];
                $patterns = $item['match'] ?? [$item['route'], $base];
                foreach ((array) $patterns as $pattern) {
                    if (request()->routeIs($pattern)) {
                        $active = true;
                        break;
                    }
                }
            }
        @endphp
        <a href="{{ $href }}" class="flex-1 flex flex-col items-center justify-center gap-1" style="color:{{ $active ? '#a78bfa' : 'rgba(255,255,255,0.35)' }};">
            <x-dynamic-component :component="'lucide-' . $item['icon']" class="w-5 h-5" />
            <span class="text-[10px] font-medium">{{ $item['label'] }}</span>
        </a>
    @endforeach
</nav>

{{-- ─── WHATSAPP FLOATING ─── --}}
<a href="https://wa.me/6285121091134" target="_blank" rel="noopener noreferrer"
   class="fixed bottom-24 right-5 lg:bottom-8 lg:right-8 w-14 h-14 rounded-full flex items-center justify-center z-40"
   style="background:linear-gradient(135deg,#25d366,#128c7e);box-shadow:0 8px 32px rgba(37,211,102,0.45);">
    <x-lucide-message-circle class="w-6 h-6 text-white" />
</a>

{{-- Logout confirmation --}}
<div id="logoutConfirm" class="hidden fixed inset-0 z-[80] items-center justify-center px-4" style="background:rgba(0,0,0,0.58);backdrop-filter:blur(8px);">
    <div class="w-full max-w-sm rounded-2xl p-5" style="background:rgba(18,10,35,0.98);border:1px solid rgba(139,92,246,0.28);box-shadow:0 24px 70px rgba(0,0,0,0.55);">
        <div class="w-11 h-11 rounded-xl flex items-center justify-center mb-4" style="background:rgba(248,113,113,0.13);color:#f87171;">
            <x-lucide-log-out class="w-5 h-5" />
        </div>
        <h2 class="text-base font-extrabold text-white font-display mb-1">Apakah anda yakin untuk keluar?</h2>
        <p class="text-xs leading-relaxed mb-5" style="color:rgba(255,255,255,0.48);">Sesi akun akan diakhiri dari perangkat ini.</p>
        <div class="flex gap-2">
            <button type="button" id="cancelLogout" class="flex-1 py-3 rounded-xl text-sm font-bold transition-all hover:bg-white/5" style="border:1.5px solid rgba(255,255,255,0.14);color:rgba(255,255,255,0.72);">Batal</button>
            <button type="button" id="confirmLogout" class="flex-1 py-3 rounded-xl text-sm font-bold text-white transition-all hover:scale-[1.01]" style="background:linear-gradient(135deg,#ef4444,#dc2626);box-shadow:0 8px 24px rgba(239,68,68,0.28);">Yakin</button>
        </div>
    </div>
</div>

<script>
    function rtCarouselNav(btn, dir) {
        const wrap = btn.closest('.rt-carousel');
        if (!wrap) return;
        const slides = wrap.querySelectorAll('.rt-slide');
        if (slides.length < 2) return;
        let idx = Array.from(slides).findIndex(s => s.classList.contains('active'));
        if (idx === -1) idx = 0;
        slides[idx].classList.remove('active');
        idx = (idx + dir + slides.length) % slides.length;
        slides[idx].classList.add('active');
        wrap.querySelectorAll('.rt-carousel-dots span').forEach((d, i) => d.classList.toggle('active', i === idx));
    }

    let pendingLogoutForm = null;

    function toggleMenu(id) {
        const el = document.getElementById(id);
        const isHidden = el.classList.contains('hidden');
        // tutup semua dropdown lain
        ['notifMenu','profileMenu','mobileDrawer'].forEach(m => {
            if (m !== id) document.getElementById(m)?.classList.add('hidden');
        });
        el.classList.toggle('hidden', !isHidden);
    }
    // klik di luar menutup dropdown
    document.addEventListener('click', function (e) {
        if (!e.target.closest('[onclick^="toggleMenu"]') && !e.target.closest('#notifMenu,#profileMenu,#mobileDrawer')) {
            ['notifMenu','profileMenu','mobileDrawer'].forEach(m => document.getElementById(m)?.classList.add('hidden'));
        }
    });

    function setLogoutModal(open) {
        const modal = document.getElementById('logoutConfirm');
        modal.classList.toggle('hidden', !open);
        modal.classList.toggle('flex', open);
    }

    document.querySelectorAll('.logout-form').forEach(form => {
        form.addEventListener('submit', function (e) {
            e.preventDefault();
            pendingLogoutForm = form;
            ['notifMenu','profileMenu','mobileDrawer'].forEach(m => document.getElementById(m)?.classList.add('hidden'));
            setLogoutModal(true);
        });
    });

    document.getElementById('cancelLogout')?.addEventListener('click', function () {
        pendingLogoutForm = null;
        setLogoutModal(false);
    });

    document.getElementById('confirmLogout')?.addEventListener('click', function () {
        if (pendingLogoutForm) pendingLogoutForm.submit();
    });

    document.getElementById('logoutConfirm')?.addEventListener('click', function (e) {
        if (e.target === this) {
            pendingLogoutForm = null;
            setLogoutModal(false);
        }
    });
</script>

{{-- ─── LIGHTBOX ─── --}}
<div id="rt-lightbox" onclick="if(event.target===this)rtLbClose()">
    <button class="lb-close" onclick="rtLbClose()">
        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
    </button>
    <button class="lb-nav lb-prev" onclick="rtLbNav(-1)">
        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="m15 18-6-6 6-6"/></svg>
    </button>
    <img class="lb-img" id="rtLbImg" src="" alt="">
    <button class="lb-nav lb-next" onclick="rtLbNav(1)">
        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="m9 18 6-6-6-6"/></svg>
    </button>
    <div class="lb-thumbs" id="rtLbThumbs"></div>
    <span class="lb-counter" id="rtLbCounter"></span>
</div>

<script>
(function () {
    let lbSrcs = [], lbIdx = 0;

    function open(srcs, idx) {
        lbSrcs = srcs; lbIdx = idx;
        render();
        document.getElementById('rt-lightbox').classList.add('open');
        document.body.style.overflow = 'hidden';
    }

    function render() {
        const img = document.getElementById('rtLbImg');
        img.style.opacity = '0';
        img.src = lbSrcs[lbIdx];
        img.onload = function () { img.style.opacity = '1'; };
        const thumbs = document.getElementById('rtLbThumbs');
        thumbs.innerHTML = '';
        if (lbSrcs.length > 1) {
            lbSrcs.forEach((src, i) => {
                const t = document.createElement('img');
                t.src = src;
                t.className = i === lbIdx ? 'active' : '';
                t.onclick = function (e) { e.stopPropagation(); lbIdx = i; render(); };
                thumbs.appendChild(t);
            });
            document.querySelector('#rt-lightbox .lb-prev').style.display = '';
            document.querySelector('#rt-lightbox .lb-next').style.display = '';
        } else {
            document.querySelector('#rt-lightbox .lb-prev').style.display = 'none';
            document.querySelector('#rt-lightbox .lb-next').style.display = 'none';
        }
        const counter = document.getElementById('rtLbCounter');
        counter.textContent = lbSrcs.length > 1 ? (lbIdx + 1) + ' / ' + lbSrcs.length : '';
    }

    window.rtLbClose = function () {
        document.getElementById('rt-lightbox').classList.remove('open');
        document.body.style.overflow = '';
    };

    window.rtLbNav = function (dir) {
        lbIdx = (lbIdx + dir + lbSrcs.length) % lbSrcs.length;
        render();
    };

    window.rtLbOpen = open;

    document.addEventListener('keydown', function (e) {
        if (!document.getElementById('rt-lightbox').classList.contains('open')) return;
        if (e.key === 'Escape') rtLbClose();
        if (e.key === 'ArrowLeft') rtLbNav(-1);
        if (e.key === 'ArrowRight') rtLbNav(1);
    });
})();
</script>

{{-- ── TOAST CONTAINER ── --}}
<div id="rt-toast-stack"></div>

{{-- Flash session toasts --}}
@php
    $flashTypes = [
        'success' => ['icon' => '✓', 'title' => 'Berhasil'],
        'error'   => ['icon' => '✕', 'title' => 'Gagal'],
        'info'    => ['icon' => 'ℹ', 'title' => 'Info'],
        'warning' => ['icon' => '⚠', 'title' => 'Perhatian'],
    ];
@endphp
@foreach ($flashTypes as $type => $meta)
    @if (session($type))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                rtToast('{{ $type }}', '{{ addslashes(session($type)) }}');
            });
        </script>
    @endif
@endforeach

<script>
/* ── Welcome banner & greeting ── */
(function () {
    const banner = document.getElementById('rt-welcome-banner');
    const bar    = document.getElementById('rt-welcome-progress');
    const DURATION = 6000;

    if (banner) {
        // Slide open
        requestAnimationFrame(() => requestAnimationFrame(() => {
            banner.classList.add('open');
            // Drain progress bar
            if (bar) {
                bar.style.transitionDuration = DURATION + 'ms';
                setTimeout(() => { bar.style.width = '0%'; }, 50);
            }
            // Auto-dismiss
            setTimeout(rtWelcomeDismiss, DURATION);
        }));
    }

    window.rtWelcomeDismiss = function () {
        if (!banner) return;
        banner.classList.add('closing');
        banner.classList.remove('open');
    };

    @if(session('welcome_type') === 'returning' && session('welcome_name'))
    // Toast for returning users
    document.addEventListener('DOMContentLoaded', function () {
        if (typeof rtToast === 'function') {
            rtToast('info', 'Selamat datang kembali, {{ session("welcome_name") }}! 👋');
        }
    });
    @endif
})();

/* ── Toast system ── */
(function () {
    const icons = {
        success: '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>',
        error:   '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>',
        info:    '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>',
        warning: '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m10.29 3.86-8.57 14.86A2 2 0 0 0 3.43 22h17.14a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0Z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>',
    };
    const titles = { success: 'Berhasil', error: 'Gagal', info: 'Info', warning: 'Perhatian' };

    window.rtToast = function (type, message, duration) {
        const stack = document.getElementById('rt-toast-stack');
        if (!stack) return;
        const t = duration || 4000;
        const el = document.createElement('div');
        el.className = 'rt-toast ' + (type || 'info');
        el.innerHTML =
            '<span class="rt-toast-icon">' + (icons[type] || icons.info) + '</span>' +
            '<div class="rt-toast-body">' +
                '<div class="rt-toast-title">' + (titles[type] || 'Info') + '</div>' +
                '<div class="rt-toast-msg">' + message + '</div>' +
            '</div>' +
            '<button class="rt-toast-close" onclick="this.closest(\'.rt-toast\') && rtToastDismiss(this.closest(\'.rt-toast\'))">×</button>';
        stack.appendChild(el);
        const timer = setTimeout(() => rtToastDismiss(el), t);
        el._rtTimer = timer;
    };

    window.rtToastDismiss = function (el) {
        clearTimeout(el._rtTimer);
        el.classList.add('out');
        el.addEventListener('animationend', () => el.remove(), { once: true });
    };
})();

/* ── Scroll reveal ── */
(function () {
    const io = new IntersectionObserver(entries => {
        entries.forEach(e => { if (e.isIntersecting) { e.target.classList.add('visible'); io.unobserve(e.target); } });
    }, { threshold: 0.1 });
    document.querySelectorAll('.reveal').forEach(el => io.observe(el));
})();

/* ── 3D card tilt ── */
(function () {
    function applyTilt(card) {
        card.style.willChange = 'transform';
        card.addEventListener('mouseenter', () => {
            card.style.transition = 'transform 0.12s ease, box-shadow 0.12s ease';
        });
        card.addEventListener('mousemove', e => {
            const r  = card.getBoundingClientRect();
            const dx = (e.clientX - (r.left + r.width  / 2)) / (r.width  / 2);
            const dy = (e.clientY - (r.top  + r.height / 2)) / (r.height / 2);
            card.style.transform = 'perspective(700px) rotateY(' + (dx * 9) + 'deg) rotateX(' + (-dy * 9) + 'deg) translateY(-5px) scale(1.015)';
            card.style.boxShadow = '0 16px 44px rgba(124,58,237,0.25)';
        });
        card.addEventListener('mouseleave', () => {
            card.style.transition = 'transform 0.45s ease, box-shadow 0.45s ease';
            card.style.transform  = '';
            card.style.boxShadow  = '';
        });
    }
    document.querySelectorAll('.rt-tilt').forEach(applyTilt);
    // Re-apply after skeleton swap (product grids load dynamically)
    const pg = document.getElementById('rt-product-grid');
    if (pg) {
        const mo = new MutationObserver(() => {
            pg.querySelectorAll('.rt-tilt').forEach(applyTilt);
        });
        mo.observe(pg, { childList: true, subtree: true, attributeFilter: ['class'] });
    }
})();

/* ── Page transition overlay ── */
(function () {
    const overlay = document.createElement('div');
    overlay.style.cssText = 'position:fixed;inset:0;background:#0c0618;z-index:99998;opacity:1;pointer-events:none;transition:opacity 0.4s ease;';
    document.body.appendChild(overlay);
    requestAnimationFrame(() => requestAnimationFrame(() => { overlay.style.opacity = '0'; }));
    document.querySelectorAll('a[href]').forEach(a => {
        const href = a.getAttribute('href');
        if (!href || href.startsWith('#') || href.startsWith('http') || href.startsWith('//') || a.target === '_blank') return;
        a.addEventListener('click', () => { overlay.style.opacity = '0.85'; });
    });
})();

/* ── Page loader ── */
(function () {
    const bar = document.getElementById('rt-page-bar');
    if (!bar) return;
    let timer;
    document.querySelectorAll('a[href]').forEach(a => {
        const href = a.getAttribute('href');
        if (!href || href.startsWith('#') || href.startsWith('http') || href.startsWith('//') || a.target === '_blank') return;
        a.addEventListener('click', function () {
            bar.style.width = '0%'; bar.style.opacity = '1';
            clearTimeout(timer);
            setTimeout(() => { bar.style.width = '65%'; }, 10);
            timer = setTimeout(() => { bar.style.width = '90%'; }, 500);
        });
    });
    window.addEventListener('pageshow', () => {
        bar.style.width = '100%';
        setTimeout(() => { bar.style.opacity = '0'; setTimeout(() => { bar.style.width = '0%'; }, 400); }, 200);
    });
})();
</script>

</body>
</html>
