<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') - RUTIP</title>
    <meta name="description" content="Panel Admin RUTIP - Kelola layanan penitipan barang.">
    <meta property="og:title" content="Admin - RUTIP">
    <meta property="og:site_name" content="RUTIP">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root {
            --font-display: 'Plus Jakarta Sans', sans-serif;
            --font-body: 'Inter', sans-serif;
            --sidebar-w: 260px;
            --header-h: 56px;
            --bg-dark: #080313;
            --bg-sidebar: rgba(8,2,22,0.99);
            --border: rgba(255,255,255,0.06);
            --purple: #7c3aed;
            --purple-light: #a78bfa;
        }
        *, *::before, *::after { box-sizing: border-box; }
        body { font-family: var(--font-body); background: var(--bg-dark); margin: 0; }
        .font-display { font-family: var(--font-display); }

        /* ─── Scrollbar hide ─── */
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }

        /* ─── Sidebar ─── */
        #admin-sidebar {
            position: fixed;
            top: 0; left: 0; bottom: 0;
            width: var(--sidebar-w);
            z-index: 60;
            display: flex;
            flex-direction: column;
            background: var(--bg-sidebar);
            border-right: 1px solid var(--border);
            transform: translateX(calc(-1 * var(--sidebar-w)));
            transition: transform 0.28s cubic-bezier(0.4,0,0.2,1);
            overflow: hidden;
        }
        #admin-sidebar.open { transform: translateX(0); }

        /* ─── Backdrop ─── */
        #admin-backdrop {
            position: fixed;
            inset: 0;
            z-index: 55;
            background: rgba(0,0,0,0.7);
            backdrop-filter: blur(4px);
            -webkit-backdrop-filter: blur(4px);
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.28s ease;
        }
        #admin-backdrop.open { opacity: 1; pointer-events: all; }

        /* ─── Header ─── */
        #admin-header {
            position: fixed;
            top: 0; left: 0; right: 0;
            height: var(--header-h);
            z-index: 50;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 16px;
            background: rgba(8,3,19,0.96);
            border-bottom: 1px solid var(--border);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            transition: left 0.28s cubic-bezier(0.4,0,0.2,1);
        }

        /* ─── Main ─── */
        #admin-main {
            padding-top: var(--header-h);
            min-height: 100vh;
            transition: padding-left 0.28s cubic-bezier(0.4,0,0.2,1);
        }

        /* ─── Desktop: sidebar always visible ─── */
        @media (min-width: 1024px) {
            #admin-sidebar {
                transform: translateX(0) !important;
            }
            #admin-backdrop {
                display: none !important;
            }
            #admin-header {
                left: var(--sidebar-w);
                padding: 0 24px;
            }
            #admin-main {
                padding-left: var(--sidebar-w);
            }
            #hamburger-btn { display: none !important; }
        }

        /* ─── Sidebar nav item ─── */
        .nav-link {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 12px;
            border-radius: 12px;
            font-size: 13.5px;
            font-weight: 500;
            color: rgba(255,255,255,0.5);
            text-decoration: none;
            transition: background 0.15s, color 0.15s;
            margin-bottom: 2px;
        }
        .nav-link:hover { background: rgba(255,255,255,0.06); color: rgba(255,255,255,0.9); }
        .nav-link.active { background: rgba(124,58,237,0.18); color: #c4b5fd; }
        .nav-link.active .nav-dot {
            width: 8px; height: 8px; border-radius: 50%;
            background: var(--purple);
            box-shadow: 0 0 10px var(--purple);
            flex-shrink: 0;
        }
        .nav-link svg { flex-shrink: 0; }

        /* ─── Sidebar top accent line ─── */
        .sidebar-accent {
            height: 2px;
            background: linear-gradient(90deg, #7c3aed, #6366f1, transparent);
        }

        /* ─── Image picker (admin forms) ─── */
        .rt-img-drop {
            display: flex; align-items: center; gap: 12px;
            padding: 14px 16px; cursor: pointer;
            border-radius: 14px;
            border: 1.5px dashed rgba(124,58,237,0.4);
            background: rgba(124,58,237,0.06);
            transition: border-color .2s, background .2s;
        }
        .rt-img-drop:hover { border-color: rgba(124,58,237,0.7); background: rgba(124,58,237,0.1); }
        .rt-img-drop-icon {
            width: 38px; height: 38px; border-radius: 12px; flex-shrink: 0;
            display: flex; align-items: center; justify-content: center;
            background: rgba(124,58,237,0.18); color: #a78bfa;
        }
        .rt-img-pick-grid { display: flex; flex-wrap: wrap; gap: 8px; margin-top: 10px; }
        .rt-img-pick-thumb {
            position: relative; width: 64px; height: 64px;
            border-radius: 10px; overflow: hidden; flex-shrink: 0;
            border: 1px solid rgba(255,255,255,0.14);
        }
        .rt-img-pick-thumb img { width: 100%; height: 100%; object-fit: cover; display: block; }
        .rt-img-pick-remove {
            position: absolute; top: 2px; right: 2px;
            width: 18px; height: 18px; border-radius: 50%;
            background: rgba(0,0,0,0.65); color: #fff;
            border: none; font-size: 13px; line-height: 1;
            display: flex; align-items: center; justify-content: center;
            cursor: pointer; padding: 0;
        }
        .rt-img-pick-remove:hover { background: #ef4444; }

        /* ── Page loader ── */
        #rt-page-bar {
            position: fixed; top: 0; left: 0; z-index: 9999;
            height: 3px; width: 0%;
            background: linear-gradient(90deg, #7c3aed, #a78bfa, #6366f1);
            transition: width 0.3s ease, opacity 0.4s ease;
            box-shadow: 0 0 12px rgba(124,58,237,0.7);
            pointer-events: none;
        }

        /* ── Skeleton ── */
        @keyframes rt-shimmer {
            0%   { background-position: -400px 0; }
            100% { background-position:  400px 0; }
        }
        .rt-skeleton {
            background: linear-gradient(90deg, rgba(255,255,255,0.05) 25%, rgba(255,255,255,0.1) 50%, rgba(255,255,255,0.05) 75%);
            background-size: 800px 100%;
            animation: rt-shimmer 1.4s ease-in-out infinite;
            border-radius: 8px;
        }

        /* ── Toast ── */
        #rt-toast-stack {
            position: fixed; top: 70px; right: 16px; z-index: 9990;
            display: flex; flex-direction: column; gap: 10px;
            pointer-events: none;
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
        .rt-toast-body  { flex:1; }
        .rt-toast-title { font-size:13px; font-weight:700; line-height:1.3; }
        .rt-toast-msg   { font-size:12px; margin-top:2px; line-height:1.4; opacity:.75; }
        .rt-toast-close { background:none; border:none; cursor:pointer; opacity:.5; color:inherit; padding:0; font-size:16px; line-height:1; }
        .rt-toast-close:hover { opacity:.85; }
        .rt-toast.success { background:rgba(16,45,28,0.97); border:1px solid rgba(52,211,153,0.35); color:#6ee7b7; }
        .rt-toast.error   { background:rgba(45,16,16,0.97); border:1px solid rgba(239,68,68,0.35);  color:#fca5a5; }
        .rt-toast.info    { background:rgba(16,22,45,0.97); border:1px solid rgba(99,102,241,0.4);  color:#a5b4fc; }
        .rt-toast.warning { background:rgba(45,35,16,0.97); border:1px solid rgba(251,191,36,0.35); color:#fcd34d; }
    </style>
</head>
<body>
<div id="rt-page-bar"></div>

{{-- ─── SIDEBAR ─────────────────────────────────────────── --}}
<aside id="admin-sidebar">

    {{-- Accent line --}}
    <div class="sidebar-accent"></div>

    {{-- Logo + Close (mobile) --}}
    <div style="padding:18px 20px 16px;border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between;">
        <div>
            <img src="{{ asset('images/logo-rutip-putih.png') }}" alt="RUTIP" style="height:36px;width:auto;display:block;margin-bottom:4px;">
            <span style="font-size:9px;letter-spacing:0.1em;color:rgba(255,255,255,0.28);font-weight:600;">ADMIN PANEL</span>
        </div>
        <button onclick="closeSidebar()" id="sidebar-close-btn"
                style="width:34px;height:34px;border-radius:10px;border:1px solid rgba(255,255,255,0.1);background:rgba(255,255,255,0.04);display:flex;align-items:center;justify-content:center;cursor:pointer;color:rgba(255,255,255,0.5);transition:background 0.15s;">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>

    {{-- Nav --}}
    <nav style="flex:1;padding:12px 10px;overflow-y:auto;" class="no-scrollbar">
        @php
            $navItems = [
                ['label'=>'Dashboard',       'href'=>route('admin.dashboard'),     'route'=>'admin.dashboard',
                 'icon'=>'<path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>'],
                ['label'=>'Ruang Titip',     'href'=>route('admin.ruang-titip'),   'route'=>'admin.ruang-titip',
                 'icon'=>'<path stroke-linecap="round" stroke-linejoin="round" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/>'],
                ['label'=>'Toko Preloved',   'href'=>route('admin.preloved'),      'route'=>'admin.preloved',
                 'icon'=>'<path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>'],
                ['label'=>'Toko Packing',    'href'=>route('admin.packing.index'), 'route'=>'admin.packing.index',
                 'icon'=>'<path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>'],
                ['label'=>'Manajemen Akun',  'href'=>route('admin.accounts'),      'route'=>'admin.accounts',
                 'icon'=>'<path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>'],
            ];
        @endphp

        @foreach($navItems as $item)
        @php $active = request()->routeIs($item['route']); @endphp
        <a href="{{ $item['href'] }}" onclick="if(window.innerWidth<1024) closeSidebar()"
           class="nav-link {{ $active ? 'active' : '' }}">
            @if($active)
                <span class="nav-dot"></span>
            @else
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    {!! $item['icon'] !!}
                </svg>
            @endif
            {{ $item['label'] }}
        </a>
        @endforeach
    </nav>

    {{-- User footer --}}
    <a href="{{ route('admin.profile') }}"
       style="display:flex;align-items:center;gap:12px;padding:14px 18px;border-top:1px solid var(--border);text-decoration:none;transition:background 0.15s;"
       onmouseover="this.style.background='rgba(255,255,255,0.04)'"
       onmouseout="this.style.background='transparent'">
        <div style="width:36px;height:36px;border-radius:50%;background:linear-gradient(135deg,#7c3aed,#6366f1);display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:700;color:#fff;flex-shrink:0;">
            {{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 2)) }}
        </div>
        <div style="min-width:0;flex:1;">
            <p style="font-size:12px;font-weight:600;color:#fff;margin:0;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                {{ Auth::user()->name ?? 'Admin' }}
            </p>
            <p style="font-size:10px;color:rgba(255,255,255,0.35);margin:2px 0 0;">Admin Gudang</p>
        </div>
        <svg width="14" height="14" fill="none" stroke="rgba(255,255,255,0.3)" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
        </svg>
    </a>
</aside>

{{-- ─── BACKDROP ────────────────────────────────────────── --}}
<div id="admin-backdrop" onclick="closeSidebar()"></div>

{{-- ─── HEADER ──────────────────────────────────────────── --}}
<header id="admin-header">
    <div style="display:flex;align-items:center;gap:12px;min-width:0;">
        {{-- Hamburger --}}
        <button id="hamburger-btn" onclick="openSidebar()"
                style="width:38px;height:38px;border-radius:11px;border:1px solid rgba(255,255,255,0.1);background:rgba(255,255,255,0.05);display:flex;align-items:center;justify-content:center;cursor:pointer;color:#fff;flex-shrink:0;transition:background 0.15s;"
                onmouseover="this.style.background='rgba(255,255,255,0.1)'"
                onmouseout="this.style.background='rgba(255,255,255,0.05)'">
            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
        </button>

        {{-- Page title --}}
        <div style="display:flex;align-items:center;gap:6px;font-size:12px;color:rgba(255,255,255,0.4);min-width:0;">
            <span>Dasbor</span>
            <span>/</span>
            <span style="color:#fff;font-weight:600;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">@yield('title', 'Overview')</span>
        </div>
    </div>

    <div style="display:flex;align-items:center;gap:10px;flex-shrink:0;">
        {{-- Notification --}}
        <button style="width:36px;height:36px;border-radius:11px;border:1px solid rgba(255,255,255,0.08);background:rgba(255,255,255,0.04);display:flex;align-items:center;justify-content:center;cursor:pointer;position:relative;">
            <svg width="16" height="16" fill="none" stroke="#fff" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 10-12 0v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
            </svg>
            <span style="position:absolute;top:6px;right:6px;width:6px;height:6px;border-radius:50%;background:#ef4444;border:1.5px solid #080313;"></span>
        </button>

        {{-- Avatar --}}
        <a href="{{ route('admin.profile') }}" style="display:flex;align-items:center;gap:8px;text-decoration:none;opacity:1;transition:opacity 0.15s;" onmouseover="this.style.opacity='0.75'" onmouseout="this.style.opacity='1'">
            <div style="width:34px;height:34px;border-radius:50%;background:linear-gradient(135deg,#7c3aed,#6366f1);display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:700;color:#fff;flex-shrink:0;">
                {{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 2)) }}
            </div>
            <div class="hidden-mobile" style="line-height:1.2;">
                <p style="font-size:12px;font-weight:600;color:#fff;margin:0;">{{ Auth::user()->name ?? 'Admin' }}</p>
                <p style="font-size:10px;color:rgba(255,255,255,0.35);margin:0;">Admin Gudang</p>
            </div>
        </a>
    </div>
</header>

<style>
@media (max-width: 639px) {
    .hidden-mobile { display: none !important; }
    #admin-header { padding: 0 12px; }
}
</style>

{{-- ─── MAIN ────────────────────────────────────────────── --}}
<main id="admin-main">
    @yield('content')
</main>

<script>
function openSidebar() {
    document.getElementById('admin-sidebar').classList.add('open');
    document.getElementById('admin-backdrop').classList.add('open');
    document.body.style.overflow = 'hidden';
}

function closeSidebar() {
    document.getElementById('admin-sidebar').classList.remove('open');
    document.getElementById('admin-backdrop').classList.remove('open');
    document.body.style.overflow = '';
}

/* Auto-restore body scroll if resized to desktop */
window.addEventListener('resize', function () {
    if (window.innerWidth >= 1024) {
        document.getElementById('admin-backdrop').classList.remove('open');
        document.body.style.overflow = '';
    }
});

/* ─── Multi-image picker ─── */
function createMultiImagePicker({ inputId, previewId, labelId, maxImages = 10, emptyText = '' }) {
    const input = document.getElementById(inputId);
    const preview = document.getElementById(previewId);
    const label = document.getElementById(labelId);
    let staged = [];

    function rebuildInputFiles() {
        const dt = new DataTransfer();
        staged.forEach((file) => dt.items.add(file));
        input.files = dt.files;
    }

    function render() {
        rebuildInputFiles();
        preview.innerHTML = '';
        staged.forEach((file, idx) => {
            const thumb = document.createElement('div');
            thumb.className = 'rt-img-pick-thumb';
            thumb.innerHTML = `<img src="${URL.createObjectURL(file)}" alt="">
                <button type="button" class="rt-img-pick-remove" aria-label="Hapus foto ini">&times;</button>`;
            thumb.querySelector('button').addEventListener('click', () => {
                staged.splice(idx, 1);
                render();
            });
            preview.appendChild(thumb);
        });
        preview.classList.toggle('hidden', staged.length === 0);

        const existing = parseInt(input.dataset.existingCount || '0', 10);
        const total = existing + staged.length;
        if (total > maxImages) {
            label.textContent = `Maksimal ${maxImages} foto total. Sisa slot: ${Math.max(maxImages - existing, 0)} foto.`;
            label.style.color = '#f87171';
        } else if (staged.length) {
            label.textContent = `${staged.length} foto dipilih${existing ? ` · ${existing} foto sudah tersimpan` : ''} · sisa ${maxImages - total} slot`;
            label.style.color = 'rgba(255,255,255,0.35)';
        } else {
            label.textContent = existing
                ? `${existing} foto sudah tersimpan · sisa ${maxImages - existing} foto`
                : emptyText;
            label.style.color = 'rgba(255,255,255,0.35)';
        }
    }

    input.addEventListener('change', () => {
        const existing = parseInt(input.dataset.existingCount || '0', 10);
        const room = Math.max(maxImages - existing - staged.length, 0);
        const incoming = Array.from(input.files || []);
        if (room <= 0) {
            label.textContent = `Maksimal ${maxImages} foto total tercapai.`;
            label.style.color = '#f87171';
            rebuildInputFiles();
            return;
        }
        staged.push(...incoming.slice(0, room));
        render();
    });

    render();
    return {
        reset(existingCount = 0) {
            staged = [];
            input.dataset.existingCount = existingCount;
            render();
        },
    };
}
</script>
@stack('scripts')

{{-- Toast container --}}
<div id="rt-toast-stack"></div>
@php
    $flashTypes = ['success' => 'Berhasil', 'error' => 'Gagal', 'info' => 'Info', 'warning' => 'Perhatian'];
@endphp
@foreach (array_keys($flashTypes) as $fType)
    @if (session($fType))
        <script>document.addEventListener('DOMContentLoaded', function () { rtToast('{{ $fType }}', '{{ addslashes(session($fType)) }}'); });</script>
    @endif
@endforeach

<script>
/* ── Toast system ── */
(function () {
    const icons = {
        success: '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><polyline points="20 6 9 17 4 12"/></svg>',
        error:   '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>',
        info:    '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>',
        warning: '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="m10.29 3.86-8.57 14.86A2 2 0 0 0 3.43 22h17.14a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0Z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>',
    };
    const titles = { success: 'Berhasil', error: 'Gagal', info: 'Info', warning: 'Perhatian' };

    window.rtToast = function (type, message, duration) {
        const stack = document.getElementById('rt-toast-stack');
        if (!stack) return;
        const el = document.createElement('div');
        el.className = 'rt-toast ' + (type || 'info');
        el.innerHTML =
            '<span style="flex-shrink:0;margin-top:1px;">' + (icons[type] || icons.info) + '</span>' +
            '<div class="rt-toast-body"><div class="rt-toast-title">' + (titles[type] || 'Info') + '</div>' +
            '<div class="rt-toast-msg">' + message + '</div></div>' +
            '<button class="rt-toast-close" onclick="rtToastDismiss(this.closest(\'.rt-toast\'))">×</button>';
        stack.appendChild(el);
        const t = setTimeout(() => rtToastDismiss(el), duration || 4000);
        el._rtTimer = t;
    };

    window.rtToastDismiss = function (el) {
        if (!el) return;
        clearTimeout(el._rtTimer);
        el.classList.add('out');
        el.addEventListener('animationend', () => el.remove(), { once: true });
    };
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
