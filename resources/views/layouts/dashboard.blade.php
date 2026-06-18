<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — RUTIP</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root { --font-display:'Plus Jakarta Sans',sans-serif; --font-body:'Inter',sans-serif; }
        body { font-family: var(--font-body); }
        .font-display { font-family: var(--font-display); }
        .no-scrollbar::-webkit-scrollbar { display:none; }
        .no-scrollbar { -ms-overflow-style:none; scrollbar-width:none; }
    </style>
</head>
<body class="min-h-screen pb-20 lg:pb-0" style="background:#0c0618;">

@php
    $u = $user ?? auth()->user();
    $initials = '';
    if ($u && $u->name) {
        $parts = preg_split('/\s+/', trim($u->name));
        foreach (array_slice($parts, 0, 2) as $p) { $initials .= strtoupper(substr($p, 0, 1)); }
    }
    $navLinks = [
        ['label' => 'Beranda', 'route' => 'dashboard'],
        ['label' => 'Toko Packing', 'url' => 'Toko Packing', 'route' => 'packing.index'],
        ['label' => 'Ruang Titip', 'route' => 'ruang-titip.index'],
        ['label' => 'Toko Preloved', 'url' => '#'],
        ['label' => 'Pesanan Saya', 'route' => 'pesanan.index'],
    ];
@endphp

{{-- ─── NAVBAR (fixed top) ─── --}}
<nav class="fixed top-0 left-0 right-0 z-50"
     style="background:rgba(10,5,20,0.97);backdrop-filter:blur(20px);border-bottom:1px solid rgba(139,92,246,0.18);">
    <div class="max-w-7xl mx-auto px-4 lg:px-6 h-16 flex items-center justify-between">

        {{-- Logo --}}
        <a href="{{ route('dashboard') }}" class="flex items-center gap-2 shrink-0">
            <div class="w-8 h-8 rounded-lg flex items-center justify-center" style="background:linear-gradient(135deg,#7c3aed,#6366f1);">
                <x-lucide-package class="w-4 h-4 text-white" />
            </div>
            <span class="text-xl font-extrabold text-white font-display">RUTIP</span>
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
                        $active = request()->routeIs($link['route']) || request()->routeIs($base);
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
            <a href="#" class="relative w-9 h-9 rounded-lg flex items-center justify-center transition-all hover:bg-white/5" style="color:rgba(255,255,255,0.6);">
                <x-lucide-shopping-cart class="w-5 h-5" />
            </a>

          
            {{-- Notifikasi --}}
            <div class="relative">
                <button type="button" onclick="toggleMenu('notifMenu')" class="relative w-9 h-9 rounded-lg flex items-center justify-center transition-all hover:bg-white/5" style="color:rgba(255,255,255,0.6);">
                    <x-lucide-bell class="w-5 h-5" />
                    <span class="absolute top-1 right-1 w-4 h-4 rounded-full text-[9px] font-bold text-white flex items-center justify-center" style="background:#ef4444;">2</span>
                </button>
                <div id="notifMenu" class="hidden absolute right-0 top-12 w-80 rounded-2xl overflow-hidden z-50"
                      style="background:rgba(18,10,35,0.98);border:1px solid rgba(139,92,246,0.25);box-shadow:0 20px 60px rgba(0,0,0,0.5);">
                    <div class="px-4 py-3 flex items-center justify-between" style="border-bottom:1px solid rgba(255,255,255,0.07);">
                        <span class="text-sm font-semibold text-white">Notifikasi</span>
                        <span class="text-xs px-2 py-0.5 rounded-full font-semibold" style="background:rgba(167,139,250,0.15);color:#a78bfa;">2 baru</span>
                    </div>
                    @foreach ([
                        ['Pesanan #RTP-001 dalam proses penjemputan', '5 mnt lalu', true],
                        ['Pembayaran bulan Juni berhasil dikonfirmasi', '1 jam lalu', true],
                        ['Barangmu aman tersimpan di gudang RUTIP', '2 hari lalu', false],
                    ] as $n)
                        <div class="px-4 py-3 flex gap-3" style="border-bottom:1px solid rgba(255,255,255,0.04);">
                            <div class="w-2 h-2 rounded-full mt-1.5 shrink-0" style="background:{{ $n[2] ? '#a78bfa' : 'rgba(255,255,255,0.15)' }};"></div>
                            <div>
                                <p class="text-xs leading-relaxed" style="color:{{ $n[2] ? 'rgba(255,255,255,0.85)' : 'rgba(255,255,255,0.4)' }};">{{ $n[0] }}</p>
                                <p class="text-[10px] mt-1" style="color:rgba(255,255,255,0.28);">{{ $n[1] }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Profil dropdown (Hanya boleh ada satu) --}}
            <div class="relative hidden lg:block">
                <button type="button" onclick="toggleMenu('profileMenu')" class="flex items-center gap-2 px-2 py-1.5 rounded-xl transition-all hover:bg-white/5">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center text-white text-xs font-bold shrink-0" style="background:linear-gradient(135deg,#7c3aed,#6366f1);">{{ $initials ?: '?' }}</div>
                    <div class="text-left">
                        <p class="text-xs font-semibold text-white leading-tight">{{ $u?->name ?? 'Pengguna' }}</p>
                        <p class="text-[10px]" style="color:rgba(255,255,255,0.35);">Penitip Aktif</p>
                    </div>
                    <x-lucide-chevron-down class="w-3.5 h-3.5 ml-0.5" style="color:rgba(255,255,255,0.35);" />
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
                        $active = request()->routeIs($link['route']) || request()->routeIs($base);
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
<main class="max-w-7xl mx-auto px-4 lg:px-6 pt-20">
    @yield('content')
</main>

{{-- ─── BOTTOM NAV (mobile) ─── --}}
<nav class="lg:hidden fixed bottom-0 left-0 right-0 z-50 flex items-center"
     style="background:rgba(10,5,20,0.97);backdrop-filter:blur(20px);border-top:1px solid rgba(139,92,246,0.18);height:64px;">
    @php
        $bottom = [
            ['label' => 'Beranda', 'route' => 'dashboard', 'icon' => 'home'],
            ['label' => 'Pesanan', 'route' => 'pesanan.index', 'icon' => 'clipboard-list'],
            ['label' => 'Toko', 'url' => '#', 'icon' => 'shopping-bag'],
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
                $active = request()->routeIs($item['route']) || request()->routeIs($base);
            }
        @endphp
        <a href="{{ $href }}" class="flex-1 flex flex-col items-center justify-center gap-1" style="color:{{ $active ? '#a78bfa' : 'rgba(255,255,255,0.35)' }};">
            <x-dynamic-component :component="'lucide-' . $item['icon']" class="w-5 h-5" />
            <span class="text-[10px] font-medium">{{ $item['label'] }}</span>
        </a>
    @endforeach
</nav>

{{-- ─── WHATSAPP FLOATING ─── --}}
<a href="https://wa.me/6281234567890" target="_blank" rel="noopener noreferrer"
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

</body>
</html>
