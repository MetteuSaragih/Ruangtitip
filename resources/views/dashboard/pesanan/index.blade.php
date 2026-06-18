@extends('layouts.dashboard')
@section('title', 'Pesanan Saya')

@section('content')
<div class="max-w-2xl mx-auto pt-6 pb-28">
    <h1 class="text-xl font-extrabold text-white font-display mb-1">Pesanan Saya</h1>
    <p class="text-xs mb-5" style="color:rgba(255,255,255,0.4);">Pantau status penitipan dan riwayat pesananmu</p>

    {{-- ── Tab switcher ── --}}
    <div class="flex gap-1 p-1 rounded-2xl mb-6" style="background:rgba(255,255,255,0.05);border:1px solid rgba(255,255,255,0.08);">
        @php
            $tabs = [
                'berlangsung' => ['label' => 'Sedang Berlangsung', 'count' => $countBerlangsung],
                'selesai'     => ['label' => 'Selesai', 'count' => $countSelesai],
            ];
        @endphp
        @foreach ($tabs as $key => $t)
            @php $on = $tab === $key; @endphp
            <a href="{{ route('pesanan.index', ['tab' => $key]) }}"
               class="flex-1 flex items-center justify-center gap-2 py-2.5 rounded-xl text-xs font-bold transition-all"
               style="{{ $on ? 'background:linear-gradient(135deg,#7c3aed,#6366f1);color:#fff;box-shadow:0 4px 14px rgba(124,58,237,0.4);' : 'color:rgba(255,255,255,0.5);' }}">
                {{ $t['label'] }}
                @if ($t['count'] > 0)
                    <span class="px-1.5 py-0.5 rounded-full text-[9px] font-bold"
                          style="{{ $on ? 'background:rgba(255,255,255,0.25);color:#fff;' : 'background:rgba(124,58,237,0.2);color:#a78bfa;' }}">{{ $t['count'] }}</span>
                @endif
            </a>
        @endforeach
    </div>

    {{-- ── Daftar pesanan ── --}}
    @if ($orders->count())
        <div class="space-y-3">
            @foreach ($orders as $order)
                @include('dashboard.pesanan._card', ['order' => $order])
            @endforeach
        </div>
    @else
        {{-- Empty state --}}
        <div class="flex flex-col items-center text-center py-16">
            <div class="w-16 h-16 rounded-2xl flex items-center justify-center mb-4 text-3xl" style="background:rgba(124,58,237,0.1);border:1px solid rgba(124,58,237,0.2);">
                {{ $tab === 'selesai' ? '✅' : '📭' }}
            </div>
            <p class="text-sm font-bold text-white mb-1">
                {{ $tab === 'selesai' ? 'Belum ada pesanan selesai' : 'Belum ada pesanan aktif' }}
            </p>
            <p class="text-xs mb-6 max-w-xs" style="color:rgba(255,255,255,0.4);">
                {{ $tab === 'selesai'
                    ? 'Pesanan yang sudah selesai akan muncul di sini.'
                    : 'Mulai titipkan barangmu sekarang dan nikmati ketenangan pikiran!' }}
            </p>
            @if ($tab !== 'selesai')
                <a href="{{ route('ruang-titip.index') }}" class="inline-flex items-center gap-2 px-6 py-3 rounded-xl font-bold text-sm text-white transition-all hover:scale-105" style="background:linear-gradient(135deg,#7c3aed,#6366f1);box-shadow:0 6px 20px rgba(124,58,237,0.4);">
                    <x-lucide-plus class="w-4 h-4" /> Titip Barang Sekarang
                </a>
            @endif
        </div>
    @endif
</div>
@endsection
