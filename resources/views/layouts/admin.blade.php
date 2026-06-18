<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') — RUTIP</title>
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
<body class="min-h-screen" style="background:#080313;">

{{-- Sidebar --}}
<aside class="fixed top-0 left-0 h-full w-60 z-40 flex flex-col no-scrollbar"
       style="background:rgba(10,4,26,0.97);border-right:1px solid rgba(255,255,255,0.06);">

    {{-- Logo --}}
    <div class="px-5 py-5 flex items-center gap-3" style="border-bottom:1px solid rgba(255,255,255,0.06);">
        <div class="w-8 h-8 rounded-xl flex items-center justify-center font-extrabold text-white text-sm"
             style="background:linear-gradient(135deg,#7c3aed,#6366f1);">R</div>
        <div>
            <p class="text-sm font-extrabold text-white font-display leading-none">RUTIP</p>
            <p class="text-[10px] mt-0.5" style="color:rgba(255,255,255,0.35);">ADMIN PANEL</p>
        </div>
    </div>

    {{-- Nav --}}
    <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto no-scrollbar">
        @php
            $navItems = [
                ['label'=>'Dashboard',       'href'=>route('admin.dashboard'), 'route'=>'admin.dashboard',
                 'icon'=>'<path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>'],
                ['label'=>'Ruang Titip',     'href'=>route('admin.ruang-titip'), 'route'=>'admin.ruang-titip',
                 'icon'=>'<path stroke-linecap="round" stroke-linejoin="round" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/>'],
                ['label'=>'Toko Preloved',   'href'=>route('admin.preloved'), 'route'=>'admin.preloved',
                 'icon'=>'<path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>'],
                ['label'=>'Toko Packing',    'href'=>route('admin.packing.index'), 'route'=>'admin.packing.index',
                 'icon'=>'<path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>'],
                ['label'=>'Manajemen Akun',  'href'=>route('admin.accounts'), 'route'=>'admin.accounts',
                 'icon'=>'<path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>'],
            ];
        @endphp

        @foreach($navItems as $item)
        @php $active = request()->routeIs($item['route']); @endphp
        <a href="{{ $item['href'] }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all"
           style="{{ $active
               ? 'background:rgba(124,58,237,0.2);color:#c4b5fd;'
               : 'color:rgba(255,255,255,0.45);' }}"
           onmouseover="{{ $active ? '' : "this.style.background='rgba(255,255,255,0.05)';this.style.color='rgba(255,255,255,0.8)'" }}"
           onmouseout="{{ $active ? '' : "this.style.background='transparent';this.style.color='rgba(255,255,255,0.45)'" }}">
            @if($active)
            <span class="w-1.5 h-1.5 rounded-full shrink-0" style="background:#7c3aed;"></span>
            @else
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                {!! $item['icon'] !!}
            </svg>
            @endif
            {{ $item['label'] }}
        </a>
        @endforeach
    </nav>

    {{-- User info --}}
    <div class="px-4 py-4" style="border-top:1px solid rgba(255,255,255,0.06);">
        <div class="flex items-center gap-3">
            <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold text-white shrink-0"
                 style="background:linear-gradient(135deg,#7c3aed,#6366f1);">
                {{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 2)) }}
            </div>
            <div class="min-w-0">
                <p class="text-xs font-semibold text-white truncate">{{ Auth::user()->name ?? 'Admin' }}</p>
                <p class="text-[10px] truncate" style="color:rgba(255,255,255,0.35);">Admin Gudang</p>
            </div>
        </div>
    </div>
</aside>

{{-- Header --}}
<header class="fixed top-0 left-60 right-0 h-14 z-30 flex items-center justify-between px-6"
        style="background:rgba(8,3,19,0.95);border-bottom:1px solid rgba(255,255,255,0.06);backdrop-filter:blur(12px);">
    <div class="flex items-center gap-2 text-xs" style="color:rgba(255,255,255,0.4);">
        <span>Dasbor</span>
        <span>/</span>
        <span class="text-white font-medium">@yield('title', 'Overview')</span>
    </div>
    <div class="flex items-center gap-3">
        <button class="w-8 h-8 rounded-xl flex items-center justify-center relative"
                style="background:rgba(255,255,255,0.05);border:1px solid rgba(255,255,255,0.08);">
            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 10-12 0v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
            </svg>
            <span class="absolute top-1.5 right-1.5 w-1.5 h-1.5 rounded-full" style="background:#ef4444;"></span>
        </button>
        <div class="flex items-center gap-2">
            <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold text-white"
                 style="background:linear-gradient(135deg,#7c3aed,#6366f1);">
                {{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 2)) }}
            </div>
            <div class="hidden sm:block">
                <p class="text-xs font-semibold text-white">{{ Auth::user()->name ?? 'Admin' }}</p>
                <p class="text-[10px]" style="color:rgba(255,255,255,0.35);">Admin Gudang</p>
            </div>
        </div>
    </div>
</header>

{{-- Main --}}
<main class="lg:pl-60 pt-14 min-h-screen">
    @yield('content')
</main>

@stack('scripts')
</body>
</html>