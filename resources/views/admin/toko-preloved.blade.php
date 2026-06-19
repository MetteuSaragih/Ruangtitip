@extends('layouts.admin')

@section('title', 'Toko Preloved')

@php
function rupiah3($n) { return 'Rp ' . number_format($n, 0, ',', '.'); }

$statusStyle = [
    'Tersedia' => ['bg'=>'rgba(52,211,153,0.12)',  'color'=>'#34d399', 'dot'=>'#34d399'],
    'Terjual'  => ['bg'=>'rgba(99,102,241,0.15)',  'color'=>'#818cf8', 'dot'=>'#6366f1'],
    'Draft'    => ['bg'=>'rgba(255,255,255,0.06)', 'color'=>'rgba(255,255,255,0.35)', 'dot'=>'rgba(255,255,255,0.25)'],
];

$orderStatusStyle = [
    'Menunggu Konfirmasi' => ['bg'=>'rgba(251,191,36,0.13)',  'color'=>'#fbbf24'],
    'Siap Dikirim'        => ['bg'=>'rgba(56,189,248,0.13)',  'color'=>'#38bdf8'],
    'Siap Diambil'        => ['bg'=>'rgba(99,102,241,0.14)',  'color'=>'#818cf8'],
    'Selesai'             => ['bg'=>'rgba(52,211,153,0.12)',  'color'=>'#34d399'],
];

$deliveryStyle = [
    'Dikirim'            => ['bg'=>'rgba(251,191,36,0.13)',  'color'=>'#fbbf24'],
    'Ambil Sendiri'      => ['bg'=>'rgba(99,102,241,0.14)',  'color'=>'#818cf8'],
    'Ekspedisi Biteship' => ['bg'=>'rgba(56,189,248,0.13)',  'color'=>'#38bdf8'],
];

$nextStatus = [
    'Menunggu Konfirmasi' => 'Siap Dikirim',
    'Siap Dikirim'        => 'Selesai',
    'Siap Diambil'        => 'Selesai',
];

$filterOptions = ['Semua', 'Menunggu Konfirmasi', 'Siap Dikirim', 'Siap Diambil', 'Selesai'];
@endphp

@section('content')
<div class="p-6">

    {{-- Flash --}}
    @if(session('success'))
    <div class="mb-4 px-4 py-3 rounded-2xl text-sm font-medium"
         style="background:rgba(52,211,153,0.12);border:1px solid rgba(52,211,153,0.3);color:#34d399;">
        {{ session('success') }}
    </div>
    @endif
    @if($errors->any())
    <div class="mb-4 px-4 py-3 rounded-2xl text-sm font-medium"
         style="background:rgba(239,68,68,0.12);border:1px solid rgba(239,68,68,0.3);color:#f87171;">
        {{ $errors->first() }}
    </div>
    @endif

    {{-- Page title --}}
    <div class="mb-5">
        <h1 class="text-xl font-extrabold text-white font-display">Toko Preloved</h1>
        <p class="text-xs mt-0.5" style="color:rgba(255,255,255,0.38);">Manajemen re-commerce barang bekas mahasiswa</p>
    </div>

    {{-- Scorecards --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
        <div class="rounded-2xl p-5 flex flex-col gap-3" style="background:rgba(255,255,255,0.035);border:1px solid rgba(255,255,255,0.08);">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background:rgba(124,58,237,0.18);">
                <svg class="w-5 h-5" style="color:#a78bfa" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
            </div>
            <div>
                <p class="text-xs font-medium mb-1" style="color:rgba(255,255,255,0.42);">Total Barang Tayang</p>
                <p class="text-2xl font-extrabold text-white font-display">{{ $available }} Item</p>
                <p class="text-xs mt-1" style="color:rgba(255,255,255,0.38);">Berstatus live di aplikasi pelanggan</p>
            </div>
        </div>
        <div class="rounded-2xl p-5 flex flex-col gap-3" style="background:rgba(255,255,255,0.035);border:1px solid rgba(255,255,255,0.08);">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background:rgba(52,211,153,0.15);">
                <svg class="w-5 h-5" style="color:#34d399" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
            </div>
            <div>
                <p class="text-xs font-medium mb-1" style="color:rgba(255,255,255,0.42);">Barang Terjual</p>
                <p class="text-2xl font-extrabold text-white font-display">{{ $sold }} Barang</p>
                <span class="inline-block mt-1 text-xs font-semibold px-2 py-0.5 rounded-full" style="background:rgba(52,211,153,0.12);color:#34d399;">Total terjual</span>
            </div>
        </div>
        <div class="rounded-2xl p-5 flex flex-col gap-3" style="background:rgba(255,255,255,0.035);border:1px solid rgba(255,255,255,0.08);">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background:rgba(245,158,11,0.15);">
                <svg class="w-5 h-5" style="color:#fbbf24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
            </div>
            <div>
                <p class="text-xs font-medium mb-1" style="color:rgba(255,255,255,0.42);">Pendapatan Preloved</p>
                <p class="text-2xl font-extrabold text-white font-display">{{ rupiah3($revenue) }}</p>
                <p class="text-xs mt-1" style="color:rgba(255,255,255,0.38);">Dari {{ $sold }} transaksi selesai</p>
            </div>
        </div>
    </div>

    {{-- Page Tab --}}
    <div class="flex gap-1 p-1 rounded-2xl mb-6" style="background:rgba(255,255,255,0.05);border:1px solid rgba(255,255,255,0.08);">
        @foreach([['key'=>'katalog','label'=>'Manajemen Katalog','desc'=>'Kelola inventaris barang preloved'],['key'=>'pesanan','label'=>'Pesanan Preloved','desc'=>'Pantau transaksi & tindak lanjut']] as $pt)
        <a href="{{ route('admin.preloved', ['tab' => $pt['key']]) }}"
           class="flex-1 py-3 rounded-xl text-sm font-bold transition-all text-center"
           style="{{ $pageTab === $pt['key'] ? 'background:linear-gradient(135deg,#7c3aed,#6366f1);color:white;box-shadow:0 2px 12px rgba(124,58,237,0.35);' : 'color:rgba(255,255,255,0.45);' }}">
            {{ $pt['label'] }}
            <p class="text-[10px] font-normal mt-0.5" style="{{ $pageTab === $pt['key'] ? 'color:rgba(255,255,255,0.7)' : 'color:rgba(255,255,255,0.28)' }}">{{ $pt['desc'] }}</p>
        </a>
        @endforeach
    </div>

    {{-- ══ KATALOG TAB ══ --}}
    @if($pageTab === 'katalog')
    <div class="rounded-2xl overflow-hidden" style="background:rgba(255,255,255,0.03);border:1px solid rgba(255,255,255,0.08);">
        <div class="px-6 py-4 flex items-center justify-between" style="border-bottom:1px solid rgba(255,255,255,0.06);">
            <div>
                <h3 class="text-sm font-bold text-white">Katalog Barang Preloved</h3>
                <p class="text-[10px] mt-0.5" style="color:rgba(255,255,255,0.35);">
                    {{ $available }} tersedia · {{ $sold }} terjual · {{ $items->count() }} total
                </p>
            </div>
            <button onclick="document.getElementById('item-modal').classList.remove('hidden');document.getElementById('item-modal').classList.add('flex');"
                    class="flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs font-bold text-white transition-all hover:scale-105"
                    style="background:linear-gradient(135deg,#7c3aed,#6366f1);box-shadow:0 4px 16px rgba(124,58,237,0.4);">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                Tambah Barang
            </button>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-xs" style="min-width:740px;">
                <thead>
                    <tr style="border-bottom:1px solid rgba(255,255,255,0.06);">
                        @foreach(['Foto','Nama Barang','Kondisi','Harga Jual','Status','Aksi'] as $h)
                        <th class="text-left px-5 py-3.5 font-semibold whitespace-nowrap" style="color:rgba(255,255,255,0.3);">{{ $h }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @forelse($items as $i => $item)
                    @php
                        $cond = $conditionLabels[$item->condition] ?? $conditionLabels[80];
                        $stat = $statusStyle[$item->status] ?? $statusStyle['Draft'];
                    @endphp
                    <tr style="{{ $i < $items->count()-1 ? 'border-bottom:1px solid rgba(255,255,255,0.04)' : '' }};opacity:{{ $item->status === 'Draft' ? '0.55' : '1' }}"
                        onmouseover="this.style.background='rgba(124,58,237,0.05)'"
                        onmouseout="this.style.background='transparent'">
                        <td class="px-5 py-4">
                            <div class="w-14 h-12 rounded-xl overflow-hidden flex items-center justify-center"
                                 style="background:rgba(124,58,237,0.08);border:1px solid rgba(124,58,237,0.15);">
                                @if($item->primary_photo)
                                <img src="{{ asset('storage/'.$item->primary_photo) }}" alt="" class="w-full h-full object-cover">
                                @else
                                <svg class="w-4 h-4" style="color:rgba(167,139,250,0.45)" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                                @endif
                            </div>
                        </td>
                        <td class="px-5 py-4">
                            <p class="font-semibold text-white">{{ $item->name }}</p>
                            <p class="text-[10px] mt-0.5" style="color:rgba(255,255,255,0.35);">{{ $item->category }}</p>
                        </td>
                        <td class="px-5 py-4">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-semibold"
                                  style="background:{{ $cond['bg'] }};color:{{ $cond['color'] }};">
                                <span class="w-1.5 h-1.5 rounded-full" style="background:{{ $cond['color'] }};"></span>
                                {{ $cond['label'] }}
                            </span>
                        </td>
                        <td class="px-5 py-4 whitespace-nowrap font-semibold text-white">{{ rupiah3($item->price) }}</td>
                        <td class="px-5 py-4">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-semibold"
                                  style="background:{{ $stat['bg'] }};color:{{ $stat['color'] }};">
                                <span class="w-1.5 h-1.5 rounded-full" style="background:{{ $stat['dot'] }};"></span>
                                {{ $item->status }}
                            </span>
                        </td>
                        <td class="px-5 py-4">
                            <div class="flex items-center gap-1.5">
                                {{-- Edit --}}
                                @if($item->status !== 'Terjual')
                                <button onclick='openEditModal(@json($item))'
                                        class="flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg text-[11px] font-semibold transition-all hover:scale-105"
                                        style="background:rgba(99,102,241,0.13);border:1px solid rgba(99,102,241,0.28);color:#818cf8;">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    <span class="hidden xl:inline">Edit</span>
                                </button>
                                @endif
                                {{-- Hapus --}}
                                <form method="POST" action="{{ route('admin.preloved.destroy', $item) }}"
                                      onsubmit="return confirm('Hapus barang ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                            class="flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg text-[11px] font-semibold transition-all hover:scale-105"
                                            style="background:rgba(239,68,68,0.1);border:1px solid rgba(239,68,68,0.25);color:#f87171;">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        <span class="hidden xl:inline">Hapus</span>
                                    </button>
                                </form>
                                {{-- Draft toggle --}}
                                @if($item->status !== 'Terjual')
                                <form method="POST" action="{{ route('admin.preloved.toggle-draft', $item) }}">
                                    @csrf
                                    <button type="submit"
                                            class="flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg text-[11px] font-semibold transition-all hover:scale-105"
                                            style="background:{{ $item->status === 'Draft' ? 'rgba(52,211,153,0.1)' : 'rgba(255,255,255,0.06)' }};border:1px solid {{ $item->status === 'Draft' ? 'rgba(52,211,153,0.28)' : 'rgba(255,255,255,0.12)' }};color:{{ $item->status === 'Draft' ? '#34d399' : 'rgba(255,255,255,0.45)' }};">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            @if($item->status === 'Draft')
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            @else
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                                            @endif
                                        </svg>
                                        <span class="hidden xl:inline">{{ $item->status === 'Draft' ? 'Publikasikan' : 'Draft' }}</span>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-20 text-center">
                            <div class="flex flex-col items-center gap-3">
                                <div class="w-14 h-14 rounded-2xl flex items-center justify-center" style="background:rgba(124,58,237,0.08);border:1px solid rgba(124,58,237,0.15);">
                                    <svg class="w-6 h-6" style="color:rgba(167,139,250,0.4)" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                                </div>
                                <p class="text-sm font-semibold" style="color:rgba(255,255,255,0.4);">Belum ada barang</p>
                                <p class="text-xs" style="color:rgba(255,255,255,0.25);">Klik "Tambah Barang" untuk menambahkan barang preloved pertama</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-5 py-3" style="border-top:1px solid rgba(255,255,255,0.05);">
            <p class="text-[10px]" style="color:rgba(255,255,255,0.28);">{{ $items->count() }} barang terdaftar</p>
        </div>
    </div>
    @endif

    {{-- ══ PESANAN TAB ══ --}}
    @if($pageTab === 'pesanan')
    <div class="rounded-2xl overflow-hidden" style="background:rgba(255,255,255,0.03);border:1px solid rgba(255,255,255,0.08);">
        <div class="px-6 py-4 flex items-start justify-between gap-4" style="border-bottom:1px solid rgba(255,255,255,0.06);">
            <div>
                <h3 class="text-sm font-bold text-white">Transaksi Pesanan Preloved</h3>
                <p class="text-[10px] mt-0.5" style="color:rgba(255,255,255,0.35);">{{ $orders->count() }} pesanan</p>
            </div>
            <div class="flex items-center gap-1.5 flex-wrap justify-end shrink-0">
                @foreach($filterOptions as $f)
                <a href="{{ route('admin.preloved', ['tab'=>'pesanan','filter'=>$f]) }}"
                   class="px-3 py-1.5 rounded-xl text-[11px] font-semibold transition-all"
                   style="{{ $filter === $f ? 'background:rgba(124,58,237,0.2);border:1px solid rgba(124,58,237,0.4);color:#c4b5fd;' : 'background:rgba(255,255,255,0.05);border:1px solid rgba(255,255,255,0.08);color:rgba(255,255,255,0.4);' }}">
                    {{ $f }}
                </a>
                @endforeach
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-xs" style="min-width:860px;">
                <thead>
                    <tr style="border-bottom:1px solid rgba(255,255,255,0.06);">
                        @foreach(['ID Pesanan','Barang','Pembeli','Metode Pengiriman','Alamat','Status','Aksi'] as $h)
                        <th class="text-left px-5 py-3.5 font-semibold whitespace-nowrap" style="color:rgba(255,255,255,0.3);">{{ $h }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $i => $order)
                    @php
                        $ds = $deliveryStyle[$order->delivery] ?? ['bg'=>'rgba(255,255,255,0.05)','color'=>'#fff'];
                        $os = $orderStatusStyle[$order->status] ?? ['bg'=>'rgba(255,255,255,0.05)','color'=>'#fff'];
                        $isDone = $order->status === 'Selesai';
                    @endphp
                    <tr style="{{ $i < $orders->count()-1 ? 'border-bottom:1px solid rgba(255,255,255,0.04)' : '' }}"
                        onmouseover="this.style.background='rgba(124,58,237,0.05)'"
                        onmouseout="this.style.background='transparent'">
                        <td class="px-5 py-4 whitespace-nowrap">
                            <span class="font-mono font-semibold" style="color:#a78bfa;">{{ $order->order_code }}</span>
                            <p class="text-[10px] mt-0.5" style="color:rgba(255,255,255,0.3);">{{ $order->created_at->format('d M Y') }}</p>
                        </td>
                        <td class="px-5 py-4">
                            <p class="font-semibold text-white">{{ $order->item?->name ?? '-' }}</p>
                            <p class="text-[10px] mt-0.5" style="color:rgba(255,255,255,0.38);">{{ rupiah3($order->price) }}</p>
                        </td>
                        <td class="px-5 py-4">
                            <p class="font-semibold text-white">{{ $order->buyer_name }}</p>
                            <p class="text-[10px] mt-0.5" style="color:rgba(255,255,255,0.38);">{{ $order->buyer_wa }}</p>
                        </td>
                        <td class="px-5 py-4">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-xl text-[11px] font-bold"
                                  style="background:{{ $ds['bg'] }};border:1px solid {{ $ds['color'] }}44;color:{{ $ds['color'] }};">
                                {{ $order->delivery }}
                            </span>
                        </td>
                        <td class="px-5 py-4" style="max-width:200px;">
                            <p class="text-xs leading-relaxed" style="color:rgba(255,255,255,0.55);">{{ $order->address }}</p>
                        </td>
                        <td class="px-5 py-4">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-semibold whitespace-nowrap"
                                  style="background:{{ $os['bg'] }};color:{{ $os['color'] }};">
                                <span class="w-1.5 h-1.5 rounded-full" style="background:{{ $os['color'] }};"></span>
                                {{ $order->status }}
                            </span>
                        </td>
                        <td class="px-5 py-4">
                            <div class="flex items-center gap-1.5">
                                @if(!$isDone && isset($nextStatus[$order->status]))
                                <form method="POST" action="{{ route('admin.preloved.advance-order', $order) }}">
                                    @csrf
                                    <button type="submit"
                                            class="flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg text-[11px] font-semibold transition-all hover:scale-105"
                                            style="background:rgba(124,58,237,0.14);border:1px solid rgba(124,58,237,0.3);color:#c4b5fd;">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                                        <span class="hidden xl:inline">
                                            {{ $nextStatus[$order->status] === 'Selesai' ? 'Selesai' : 'Proses Kirim' }}
                                        </span>
                                    </button>
                                </form>
                                @endif
                                @if($order->delivery === 'Ekspedisi Biteship' && !$isDone)
                                <button class="flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg text-[11px] font-semibold transition-all hover:scale-105"
                                        style="background:rgba(56,189,248,0.12);border:1px solid rgba(56,189,248,0.28);color:#38bdf8;">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 17H7l-4-4V5a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2z"/></svg>
                                    <span class="hidden xl:inline">Resi</span>
                                </button>
                                @endif
                                @if(!$isDone)
                                <button onclick="showWaToast('{{ $order->buyer_name }}')"
                                        class="flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg text-[11px] font-semibold transition-all hover:scale-105"
                                        style="background:rgba(37,211,102,0.1);border:1px solid rgba(37,211,102,0.25);color:#34d399;">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
                                </button>
                                @else
                                <span class="text-[11px]" style="color:rgba(255,255,255,0.28);">✓ Selesai</span>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="py-20 text-center">
                            <p class="text-sm font-semibold" style="color:rgba(255,255,255,0.4);">Belum ada pesanan</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-5 py-3" style="border-top:1px solid rgba(255,255,255,0.05);">
            <p class="text-[10px]" style="color:rgba(255,255,255,0.28);">{{ $orders->count() }} transaksi</p>
        </div>
    </div>
    @endif
</div>

{{-- ══ ITEM MODAL (Add / Edit) ══ --}}
<div id="item-modal" class="fixed inset-0 z-50 hidden items-start justify-center p-6 overflow-y-auto"
     style="background:rgba(0,0,0,0.78);backdrop-filter:blur(6px);"
     onclick="if(event.target===this) closeItemModal()">
    <div class="w-full max-w-lg rounded-3xl overflow-hidden my-6"
         style="background:rgba(12,6,24,0.99);border:1px solid rgba(139,92,246,0.25);box-shadow:0 24px 80px rgba(0,0,0,0.75);">

        <div class="px-7 py-5 flex items-center justify-between"
             style="border-bottom:1px solid rgba(255,255,255,0.07);background:rgba(124,58,237,0.06);">
            <div>
                <h2 id="modal-title" class="text-base font-extrabold text-white font-display">Tambah Barang Preloved</h2>
                <p id="modal-subtitle" class="text-xs mt-0.5" style="color:rgba(255,255,255,0.38);">Barang titipan mahasiswa yang masuk secara offline</p>
            </div>
            <button onclick="closeItemModal()" style="color:rgba(255,255,255,0.4);" class="w-9 h-9 rounded-xl flex items-center justify-center hover:bg-white/10">✕</button>
        </div>

        <form id="item-form" method="POST" action="{{ route('admin.preloved.store') }}" enctype="multipart/form-data">
            @csrf
            <div id="method-field"></div>

            <div class="px-7 py-6 space-y-5 max-h-[70vh] overflow-y-auto">

                {{-- Foto --}}
                <div>
                    <label class="block text-xs font-bold mb-2.5 text-white">Foto Barang</label>
                    <label for="item-images-input" class="flex items-center gap-2.5 py-4 px-4 cursor-pointer rounded-2xl border-2 border-dashed transition-all"
                           style="border-color:rgba(124,58,237,0.4);background:rgba(124,58,237,0.06);"
                           onmouseover="this.style.borderColor='rgba(124,58,237,0.7)'"
                           onmouseout="this.style.borderColor='rgba(124,58,237,0.4)'">
                        <div class="w-10 h-10 rounded-2xl flex items-center justify-center shrink-0" style="background:rgba(124,58,237,0.18);">
                            <svg class="w-5 h-5" style="color:#a78bfa" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-white">Klik untuk pilih / tambah foto</p>
                            <p class="text-xs mt-0.5" style="color:rgba(255,255,255,0.3);">Bisa diklik berkali-kali · JPG / PNG · Maks 5 MB</p>
                        </div>
                        <input id="item-images-input" type="file" name="images[]" accept="image/*" multiple class="sr-only"
                               data-existing-count="0">
                    </label>
                    <div id="item-images-preview" class="rt-img-pick-grid hidden"></div>
                    <p id="photo-preview-label" class="text-[10px] mt-1.5" style="color:rgba(255,255,255,0.35);"></p>
                    <p class="text-[10px] mt-1" style="color:rgba(255,255,255,0.35);">Maksimal 10 foto asli per produk.</p>
                    @error('images')
                        <p class="text-[10px] mt-1.5 text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Nama + Kategori --}}
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold mb-2" style="color:rgba(255,255,255,0.55);">Nama Barang <span style="color:#f87171">*</span></label>
                        <input name="name" id="field-name" required placeholder='Contoh: Koper Samsonite 24"'
                               class="w-full px-4 py-3 rounded-xl text-sm text-white placeholder:text-white/20 outline-none"
                               style="background:rgba(255,255,255,0.06);border:1.5px solid rgba(255,255,255,0.1);"
                               onfocus="this.style.borderColor='rgba(124,58,237,0.55)'"
                               onblur="this.style.borderColor='rgba(255,255,255,0.1)'">
                    </div>
                    <div>
                        <label class="block text-xs font-bold mb-2" style="color:rgba(255,255,255,0.55);">Kategori</label>
                        <select name="category" id="field-category"
                                class="w-full px-4 py-3 rounded-xl text-sm text-white outline-none appearance-none"
                                style="background:rgba(255,255,255,0.06);border:1.5px solid rgba(255,255,255,0.1);">
                            @foreach($categories as $cat)
                            <option value="{{ $cat }}" style="background:#0f0720">{{ $cat }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- Kondisi --}}
                <div>
                    <label class="block text-xs font-bold mb-2.5" style="color:rgba(255,255,255,0.55);">Kondisi Barang</label>
                    <div class="flex gap-2 flex-wrap" id="condition-buttons">
                        @foreach($conditions as $c)
                        @php $cl = $conditionLabels[$c]; @endphp
                        <button type="button" onclick="selectCondition({{ $c }})"
                                id="cond-{{ $c }}"
                                class="cond-btn flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-semibold transition-all"
                                data-value="{{ $c }}"
                                style="background:rgba(255,255,255,0.04);border:1.5px solid rgba(255,255,255,0.1);color:rgba(255,255,255,0.4);">
                            {{ $cl['label'] }}
                        </button>
                        @endforeach
                    </div>
                    <input type="hidden" name="condition" id="field-condition" value="90">
                </div>

                {{-- Harga --}}
                <div>
                    <label class="block text-xs font-bold mb-2" style="color:rgba(255,255,255,0.55);">Harga Jual <span style="color:#f87171">*</span></label>
                    <div class="flex">
                        <div class="flex items-center px-3.5 rounded-l-xl text-sm font-semibold shrink-0"
                             style="background:rgba(255,255,255,0.06);border:1.5px solid rgba(255,255,255,0.1);border-right:none;color:rgba(255,255,255,0.45);">Rp</div>
                        <input type="number" name="price" id="field-price" required min="1000" placeholder="150000"
                               class="flex-1 px-4 py-3 rounded-r-xl text-sm text-white placeholder:text-white/20 outline-none"
                               style="background:rgba(255,255,255,0.06);border:1.5px solid rgba(255,255,255,0.1);">
                    </div>
                </div>

                {{-- Penjual --}}
                <div>
                    <label class="block text-xs font-bold mb-2" style="color:rgba(255,255,255,0.55);">Nama Penjual / Penitip</label>
                    <input name="seller" id="field-seller" placeholder="Nama mahasiswa penitip"
                           class="w-full px-4 py-3 rounded-xl text-sm text-white placeholder:text-white/20 outline-none"
                           style="background:rgba(255,255,255,0.06);border:1.5px solid rgba(255,255,255,0.1);"
                           onfocus="this.style.borderColor='rgba(124,58,237,0.55)'"
                           onblur="this.style.borderColor='rgba(255,255,255,0.1)'">
                </div>
            </div>

            <div class="px-7 py-5 flex gap-3" style="border-top:1px solid rgba(255,255,255,0.07);">
                <button type="button" onclick="closeItemModal()"
                        class="flex-1 py-3.5 rounded-2xl text-sm font-semibold transition-all hover:bg-white/5"
                        style="border:1.5px solid rgba(255,255,255,0.14);color:rgba(255,255,255,0.7);">Batal</button>
                <button type="submit"
                        class="flex-[2] py-3.5 rounded-2xl text-sm font-bold text-white flex items-center justify-center gap-2 transition-all hover:scale-[1.01]"
                        style="background:linear-gradient(135deg,#7c3aed,#6366f1);box-shadow:0 6px 20px rgba(124,58,237,0.4);">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span id="modal-submit-label">Tambah ke Katalog</span>
                </button>
            </div>
        </form>
    </div>
</div>

{{-- WA Toast --}}
<div id="wa-toast" class="fixed bottom-6 right-6 z-50 hidden items-center gap-3 px-4 py-3 rounded-2xl"
     style="background:rgba(37,211,102,0.15);border:1px solid rgba(37,211,102,0.35);backdrop-filter:blur(12px);">
    <span class="text-xl">💬</span>
    <div>
        <p class="text-xs font-bold text-white">Notifikasi WA Terkirim</p>
        <p id="wa-toast-name" class="text-[10px]" style="color:rgba(255,255,255,0.5);"></p>
    </div>
    <button onclick="document.getElementById('wa-toast').classList.add('hidden');document.getElementById('wa-toast').classList.remove('flex');"
            style="color:rgba(255,255,255,0.35);">✕</button>
</div>

@push('scripts')
<script>
const CONDITION_COLORS = @json($conditionLabels);
const MAX_ITEM_IMAGES = 10;

function countItemImages(item) {
    const photos = Array.isArray(item?.photos) ? [...item.photos] : [];
    if (item?.photo && !photos.includes(item.photo)) photos.push(item.photo);
    return photos.length;
}

const itemImagePicker = createMultiImagePicker({
    inputId: 'item-images-input',
    previewId: 'item-images-preview',
    labelId: 'photo-preview-label',
    maxImages: MAX_ITEM_IMAGES,
    emptyText: '',
});

function selectCondition(val) {
    document.getElementById('field-condition').value = val;
    document.querySelectorAll('.cond-btn').forEach(btn => {
        const v = parseInt(btn.dataset.value);
        const cfg = CONDITION_COLORS[v];
        if (v === val) {
            btn.style.background = cfg.bg;
            btn.style.border = `1.5px solid ${cfg.color}66`;
            btn.style.color = cfg.color;
        } else {
            btn.style.background = 'rgba(255,255,255,0.04)';
            btn.style.border = '1.5px solid rgba(255,255,255,0.1)';
            btn.style.color = 'rgba(255,255,255,0.4)';
        }
    });
}

function openEditModal(item) {
    document.getElementById('modal-title').textContent = 'Edit Barang';
    document.getElementById('modal-subtitle').textContent = 'Mengedit: ' + item.name;
    document.getElementById('modal-submit-label').textContent = 'Simpan Perubahan';
    document.getElementById('item-form').action = '/admin/preloved/' + item.id;
    document.getElementById('method-field').innerHTML = '<input type="hidden" name="_method" value="PUT">';

    document.getElementById('field-name').value     = item.name || '';
    document.getElementById('field-category').value = item.category || '';
    document.getElementById('field-price').value    = item.price || '';
    document.getElementById('field-seller').value   = item.seller || '';

    selectCondition(item.condition || 90);
    itemImagePicker.reset(countItemImages(item));

    document.getElementById('item-modal').classList.remove('hidden');
    document.getElementById('item-modal').classList.add('flex');
}

function closeItemModal() {
    document.getElementById('item-modal').classList.add('hidden');
    document.getElementById('item-modal').classList.remove('flex');
    document.getElementById('item-form').reset();
    document.getElementById('item-form').action = '{{ route("admin.preloved.store") }}';
    document.getElementById('method-field').innerHTML = '';
    document.getElementById('modal-title').textContent = 'Tambah Barang Preloved';
    document.getElementById('modal-submit-label').textContent = 'Tambah ke Katalog';
    itemImagePicker.reset(0);
    selectCondition(90);
}

function showWaToast(name) {
    const t = document.getElementById('wa-toast');
    document.getElementById('wa-toast-name').textContent = 'ke ' + name;
    t.classList.remove('hidden');
    t.classList.add('flex');
    setTimeout(() => { t.classList.add('hidden'); t.classList.remove('flex'); }, 3500);
}

// Init condition selection
selectCondition(90);
</script>
@endpush
@endsection
