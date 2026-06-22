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

        /* ─── Product card image carousel ─── */
        .rt-carousel { position: relative; }
        .rt-carousel img.rt-slide {
            display: none;
            width: 100%; height: 100%;
            object-fit: cover;
            position: absolute; inset: 0;
        }
        .rt-carousel img.rt-slide.active { display: block; }
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
    </style>
</head>
<body class="min-h-screen pb-20 lg:pb-0 flex flex-col" style="background:#0c0618;">

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

</body>
</html>
