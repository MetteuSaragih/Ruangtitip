@extends('layouts.admin')

@section('title', 'Manajemen Akun')

@php
    $tabs = [
        ['key' => 'customers', 'label' => 'Pelanggan / Mahasiswa', 'desc' => 'Kontrol pengguna aktif', 'icon' => 'users'],
        ['key' => 'staff', 'label' => 'Staf / Karyawan', 'desc' => 'Admin internal', 'icon' => 'shield'],
        ['key' => 'reviews', 'label' => 'Moderasi Ulasan', 'desc' => 'Kurasi feedback', 'icon' => 'star'],
    ];

    $initials = function ($name, $email = '') {
        $source = trim((string) ($name ?: $email ?: 'User'));
        $parts = preg_split('/\s+/', $source);

        if (count($parts) === 1) {
            return mb_strtoupper(mb_substr($parts[0], 0, 2));
        }

        return mb_strtoupper(mb_substr($parts[0], 0, 1) . mb_substr($parts[1], 0, 1));
    };

    $dateLabel = fn ($date) => $date ? $date->translatedFormat('d M Y') : '-';
@endphp

@section('content')
<div class="p-6 max-w-[1280px] mx-auto min-h-screen">
    @if(session('success'))
        <div class="mb-4 px-4 py-3 rounded-2xl text-sm font-medium" style="background:rgba(52,211,153,0.12);border:1px solid rgba(52,211,153,0.3);color:#34d399;">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-4 px-4 py-3 rounded-2xl text-sm font-medium" style="background:rgba(239,68,68,0.12);border:1px solid rgba(239,68,68,0.3);color:#f87171;">
            {{ session('error') }}
        </div>
    @endif

    <div class="mb-6">
        <h1 class="text-xl font-extrabold text-white font-display">Manajemen Akun</h1>
        <p class="text-xs mt-0.5" style="color:rgba(255,255,255,0.38);">Kontrol akses, moderasi pengguna, dan kurasi ulasan</p>
    </div>

    <div class="flex gap-1 p-1 rounded-2xl mb-6" style="background:rgba(255,255,255,0.05);border:1px solid rgba(255,255,255,0.08);">
        @foreach($tabs as $item)
            @php $active = $tab === $item['key']; @endphp
            <a href="{{ route('admin.accounts', ['tab' => $item['key']]) }}"
               class="flex-1 py-3 rounded-xl text-sm font-bold transition-all text-center flex items-center justify-center gap-2"
               style="{{ $active ? 'background:linear-gradient(135deg,#7c3aed,#6366f1);color:white;box-shadow:0 2px 12px rgba(124,58,237,0.35);' : 'color:rgba(255,255,255,0.45);' }}">
                @if($item['icon'] === 'users')
                    <x-lucide-users class="w-4 h-4" />
                @elseif($item['icon'] === 'shield')
                    <x-lucide-shield class="w-4 h-4" />
                @else
                    <x-lucide-star class="w-4 h-4" />
                @endif
                <span>{{ $item['label'] }}</span>
            </a>
        @endforeach
    </div>

    @if($tab === 'customers')
        <div class="rounded-2xl overflow-hidden" style="background:rgba(255,255,255,0.03);border:1px solid rgba(255,255,255,0.08);">
            <div class="px-6 py-4 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4" style="border-bottom:1px solid rgba(255,255,255,0.06);">
                <div>
                    <h3 class="text-sm font-bold text-white">Daftar Pengguna Terdaftar</h3>
                    <p class="text-[10px] mt-0.5" style="color:rgba(255,255,255,0.35);">
                        {{ $activeCustomers }} aktif
                        <span style="color:#f87171;">- {{ $suspendedCustomers }} suspended</span>
                        - {{ $totalCustomers }} total
                    </p>
                </div>
                <form method="GET" action="{{ route('admin.accounts') }}" class="relative w-full lg:w-80">
                    <input type="hidden" name="tab" value="customers">
                    <x-lucide-search class="w-4 h-4 absolute left-4 top-1/2 -translate-y-1/2" style="color:rgba(255,255,255,0.28);" />
                    <input name="search" value="{{ $search }}" placeholder="Cari nama atau email..."
                           class="w-full pl-11 pr-4 py-3 rounded-2xl text-sm text-white placeholder:text-white/25 outline-none"
                           style="background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.1);">
                </form>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-xs min-w-[920px]">
                    <thead>
                        <tr style="border-bottom:1px solid rgba(255,255,255,0.06);">
                            @foreach(['Nama', 'Email', 'No. WhatsApp', 'Bergabung', 'Pesanan', 'Status', 'Aksi'] as $heading)
                                <th class="text-left px-6 py-3.5 font-semibold whitespace-nowrap" style="color:rgba(255,255,255,0.32);">{{ $heading }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($customers as $user)
                            @php
                                $active = (bool) $user->is_active;
                                $orders = (int) ($orderCounts[$user->id] ?? 0);
                            @endphp
                            <tr style="border-bottom:1px solid rgba(255,255,255,0.04);opacity:{{ $active ? '1' : '0.5' }};"
                                onmouseover="this.style.background='rgba(124,58,237,0.05)'"
                                onmouseout="this.style.background='transparent'">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full flex items-center justify-center text-xs font-bold text-white shrink-0" style="background:{{ $active ? 'linear-gradient(135deg,#7c3aed,#6366f1)' : 'rgba(255,255,255,0.08)' }};">
                                            {{ $initials($user->name, $user->email) }}
                                        </div>
                                        <div>
                                            <p class="font-bold text-white">{{ $user->name ?: 'Pengguna RUTIP' }}</p>
                                            <p class="text-[10px] mt-0.5" style="color:rgba(255,255,255,0.3);">ID #{{ $user->id }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2" style="color:rgba(255,255,255,0.52);">
                                        <x-lucide-mail class="w-3.5 h-3.5" />
                                        <span>{{ $user->email }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2" style="color:rgba(255,255,255,0.52);">
                                        <x-lucide-phone class="w-3.5 h-3.5" />
                                        <span>{{ $user->phone ?: '-' }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap" style="color:rgba(255,255,255,0.52);">{{ $dateLabel($user->created_at) }}</td>
                                <td class="px-6 py-4 text-white font-bold">{{ $orders }}</td>
                                <td class="px-6 py-4">
                                    @if($active)
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold" style="background:rgba(52,211,153,0.12);color:#34d399;">
                                            <x-lucide-check class="w-3 h-3" /> Aktif
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold" style="background:rgba(239,68,68,0.12);color:#f87171;">
                                            <x-lucide-ban class="w-3 h-3" /> Suspended
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <form method="POST" action="{{ route('admin.accounts.toggle-active', $user) }}">
                                        @csrf
                                        <button type="submit"
                                                class="px-3 py-2 rounded-xl text-[11px] font-bold transition-all hover:scale-105"
                                                style="background:{{ $active ? 'rgba(239,68,68,0.1)' : 'rgba(52,211,153,0.12)' }};border:1px solid {{ $active ? 'rgba(239,68,68,0.24)' : 'rgba(52,211,153,0.28)' }};color:{{ $active ? '#f87171' : '#34d399' }};">
                                            {{ $active ? 'Suspend' : 'Aktifkan' }}
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-16 text-center">
                                    <x-lucide-users class="w-10 h-10 mx-auto mb-3" style="color:rgba(255,255,255,0.2);" />
                                    <p class="text-sm font-semibold text-white">Belum ada pengguna</p>
                                    <p class="text-xs mt-1" style="color:rgba(255,255,255,0.35);">Data pengguna akan muncul setelah pelanggan masuk ke aplikasi.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-3 flex items-center justify-between" style="border-top:1px solid rgba(255,255,255,0.05);">
                <p class="text-[10px]" style="color:rgba(255,255,255,0.28);">{{ $customers->count() }} pengguna</p>
                <span class="flex items-center gap-1 text-[10px] font-semibold" style="color:rgba(255,255,255,0.35);">Ekspor CSV <x-lucide-chevron-right class="w-3 h-3" /></span>
            </div>
        </div>
    @elseif($tab === 'staff')
        <div class="rounded-2xl overflow-hidden" style="background:rgba(255,255,255,0.03);border:1px solid rgba(255,255,255,0.08);">
            <div class="px-6 py-4" style="border-bottom:1px solid rgba(255,255,255,0.06);">
                <h3 class="text-sm font-bold text-white">Staf / Admin Internal</h3>
                <p class="text-[10px] mt-0.5" style="color:rgba(255,255,255,0.35);">{{ $staff->count() }} akun admin terdaftar</p>
            </div>
            <div class="divide-y" style="border-color:rgba(255,255,255,0.06);">
                @forelse($staff as $admin)
                    <div class="px-6 py-4 flex items-center justify-between gap-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full flex items-center justify-center text-xs font-bold text-white" style="background:linear-gradient(135deg,#7c3aed,#6366f1);">{{ $initials($admin->name, $admin->email) }}</div>
                            <div>
                                <p class="text-sm font-bold text-white">{{ $admin->name ?: 'Admin RUTIP' }}</p>
                                <p class="text-xs" style="color:rgba(255,255,255,0.42);">{{ $admin->email }}</p>
                            </div>
                        </div>
                        <span class="px-2.5 py-1 rounded-full text-[11px] font-bold" style="background:rgba(124,58,237,0.16);color:#c4b5fd;">Admin</span>
                    </div>
                @empty
                    <div class="py-16 text-center">
                        <x-lucide-shield class="w-10 h-10 mx-auto mb-3" style="color:rgba(255,255,255,0.2);" />
                        <p class="text-sm font-semibold text-white">Belum ada staf admin</p>
                    </div>
                @endforelse
            </div>
        </div>
    @else
        <div class="rounded-2xl py-20 text-center" style="background:rgba(255,255,255,0.03);border:1px solid rgba(255,255,255,0.08);">
            <x-lucide-star class="w-10 h-10 mx-auto mb-3" style="color:rgba(255,255,255,0.2);" />
            <p class="text-sm font-semibold text-white">Belum ada ulasan untuk dimoderasi</p>
            <p class="text-xs mt-1" style="color:rgba(255,255,255,0.35);">Ulasan pelanggan akan tampil di sini setelah fitur review aktif.</p>
        </div>
    @endif
</div>
@endsection
