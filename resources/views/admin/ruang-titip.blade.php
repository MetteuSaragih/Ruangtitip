@extends('layouts.admin')

@section('title', 'Ruang Titip')

@php
function rupiah2($n) { return 'Rp ' . number_format($n, 0, ',', '.'); }
$badgeStyle = [
    'Jadwal Jemput'         => ['bg'=>'rgba(99,102,241,0.15)',  'color'=>'#818cf8', 'dot'=>'#6366f1'],
    'Antar/Kirim Ekspedisi' => ['bg'=>'rgba(245,158,11,0.15)',  'color'=>'#fbbf24', 'dot'=>'#f59e0b'],
    'Batas Waktu Habis'     => ['bg'=>'rgba(239,68,68,0.18)',   'color'=>'#f87171', 'dot'=>'#ef4444'],
];
$returnBadge = [
    'Ambil Sendiri'      => ['bg'=>'rgba(99,102,241,0.15)',  'color'=>'#818cf8'],
    'Minta Diantar'      => ['bg'=>'rgba(245,158,11,0.15)',  'color'=>'#fbbf24'],
    'Ekspedisi Biteship' => ['bg'=>'rgba(56,189,248,0.15)',  'color'=>'#38bdf8'],
];
$orderTabCfg = [
    ['key'=>'baru',     'label'=>'Baru Masuk (Lunas)'],
    ['key'=>'inspeksi', 'label'=>'Proses Inspeksi'],
    ['key'=>'gudang',   'label'=>'Dalam Gudang'],
    ['key'=>'keluar',   'label'=>'Permintaan Keluar'],
];
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
        <h1 class="text-xl font-extrabold text-white font-display">Ruang Titip</h1>
        <p class="text-xs mt-0.5" style="color:rgba(255,255,255,0.38);">Manajemen logistik gudang dan pengelolaan data ruangan</p>
    </div>

    {{-- Page Tab Switcher --}}
    <div class="flex gap-1 p-1 rounded-2xl mb-6"
         style="background:rgba(255,255,255,0.05);border:1px solid rgba(255,255,255,0.08);">
        @foreach([['key'=>'operasional','label'=>'Operasional Pesanan','desc'=>'Kelola transaksi & logistik'],['key'=>'ruangan','label'=>'Manajemen Ruangan','desc'=>'CRUD gudang & data publikasi']] as $pt)
        <button onclick="switchPageTab('{{ $pt['key'] }}')"
                id="ptab-{{ $pt['key'] }}"
                class="page-tab-btn flex-1 py-3 rounded-xl text-sm font-bold transition-all">
            {{ $pt['label'] }}
            <p class="text-[10px] font-normal mt-0.5">{{ $pt['desc'] }}</p>
        </button>
        @endforeach
    </div>

    {{-- ══ OPERASIONAL TAB ══ --}}
    <div id="panel-operasional">

        {{-- Scorecards --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
            @php
                $totalItems = collect($orderData)->flatten(1)->sum('qty');
                $gudangCount = count($orderData['gudang']);
                $baruCount   = count($orderData['baru']);
            @endphp
            <div class="rounded-2xl p-5 flex flex-col gap-3" style="background:rgba(255,255,255,0.035);border:1px solid rgba(255,255,255,0.08);">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background:rgba(124,58,237,0.18);">
                    <svg class="w-5 h-5" style="color:#a78bfa" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                </div>
                <div>
                    <p class="text-xs font-medium mb-1" style="color:rgba(255,255,255,0.42);">Total Kardus/Koper di Gudang</p>
                    <p class="text-2xl font-extrabold text-white font-display">{{ $totalItems }} Item</p>
                    <p class="text-xs mt-1" style="color:rgba(255,255,255,0.38);">{{ $gudangCount }} pesanan aktif tersimpan</p>
                </div>
            </div>
            <div class="rounded-2xl p-5 flex flex-col gap-3" style="background:rgba(255,255,255,0.035);border:1px solid rgba(255,255,255,0.08);">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background:rgba(52,211,153,0.15);">
                    <svg class="w-5 h-5" style="color:#34d399" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                </div>
                <div>
                    <p class="text-xs font-medium mb-1" style="color:rgba(255,255,255,0.42);">Pendapatan Penitipan (Bulan ini)</p>
                    <p class="text-2xl font-extrabold text-white font-display">Rp 8.250.000</p>
                    <span class="inline-block mt-1 text-xs font-semibold px-2 py-0.5 rounded-full" style="background:rgba(52,211,153,0.12);color:#34d399;">+9% bulan lalu</span>
                </div>
            </div>
            <div class="rounded-2xl p-5 flex flex-col gap-3" style="background:rgba(255,255,255,0.035);border:1px solid rgba(255,255,255,0.08);">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background:rgba(245,158,11,0.15);">
                    <svg class="w-5 h-5" style="color:#fbbf24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <p class="text-xs font-medium mb-1" style="color:rgba(255,255,255,0.42);">Menunggu Penjemputan</p>
                    <p class="text-2xl font-extrabold text-white font-display">{{ $baruCount }} Pesanan</p>
                    <p class="text-xs mt-1" style="color:#f59e0b;">Perlu dijemput hari ini</p>
                </div>
            </div>
        </div>

        {{-- Order Table --}}
        <div class="rounded-2xl overflow-hidden" style="background:rgba(255,255,255,0.03);border:1px solid rgba(255,255,255,0.08);">

            {{-- Sub-tab bar --}}
            <div class="px-5 pt-4 pb-0 flex items-center gap-1 overflow-x-auto" style="border-bottom:1px solid rgba(255,255,255,0.06);">
                @foreach($orderTabCfg as $ot)
                <button onclick="switchOrderTab('{{ $ot['key'] }}')"
                        id="otab-{{ $ot['key'] }}"
                        class="order-tab-btn relative flex items-center gap-2 px-4 py-3 text-xs font-semibold whitespace-nowrap transition-all shrink-0">
                    {{ $ot['label'] }}
                    <span class="px-1.5 py-0.5 rounded-full text-[10px] font-bold order-tab-badge-{{ $ot['key'] }}"
                          style="background:rgba(255,255,255,0.07);color:rgba(255,255,255,0.35);">
                        {{ count($orderData[$ot['key']]) }}
                    </span>
                </button>
                @endforeach
            </div>

            {{-- Tables per sub-tab --}}
            @foreach($orderTabCfg as $ot)
            @php
                $rows = $orderData[$ot['key']];
                $isKeluar = $ot['key'] === 'keluar';
                $isGudang = $ot['key'] === 'gudang';
            @endphp
            <div id="opanel-{{ $ot['key'] }}" class="order-panel">
                <div class="overflow-x-auto">
                    <table class="w-full text-xs" style="min-width:700px;">
                        <thead>
                            <tr style="border-bottom:1px solid rgba(255,255,255,0.06);">
                                @foreach(['ID Pesanan','Pelanggan','Barang', $isKeluar ? 'Metode Keluar' : ($isGudang ? 'Kode Rak' : 'Durasi'), $isKeluar||$isGudang ? 'Berakhir/Tenggat' : 'Jadwal Jemput','Aksi'] as $h)
                                <th class="text-left px-5 py-3.5 font-semibold whitespace-nowrap" style="color:rgba(255,255,255,0.3);">{{ $h }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($rows as $i => $row)
                            <tr style="{{ $i < count($rows)-1 ? 'border-bottom:1px solid rgba(255,255,255,0.04)' : '' }}"
                                onmouseover="this.style.background='rgba(124,58,237,0.05)'"
                                onmouseout="this.style.background='transparent'">
                                <td class="px-5 py-4 whitespace-nowrap">
                                    <span class="font-mono font-semibold" style="color:#a78bfa;">{{ $row['id'] }}</span>
                                </td>
                                <td class="px-5 py-4">
                                    <p class="font-semibold text-white">{{ $row['customer'] }}</p>
                                    <p class="text-[10px] mt-0.5" style="color:rgba(255,255,255,0.38);">{{ $row['wa'] }}</p>
                                </td>
                                <td class="px-5 py-4">
                                    <p class="text-white">{{ $row['items'] }}</p>
                                    <p class="text-[10px] mt-0.5" style="color:rgba(255,255,255,0.35);">{{ $row['qty'] }} item</p>
                                </td>
                                {{-- Context column --}}
                                @if($isKeluar)
                                <td class="px-5 py-4">
                                    @if($row['returnMode'])
                                    @php $rb = $returnBadge[$row['returnMode']] ?? ['bg'=>'rgba(255,255,255,0.1)','color'=>'#fff']; @endphp
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-semibold whitespace-nowrap"
                                          style="background:{{ $rb['bg'] }};color:{{ $rb['color'] }};">
                                        <span class="w-1.5 h-1.5 rounded-full" style="background:{{ $rb['color'] }};"></span>
                                        {{ $row['returnMode'] }}
                                    </span>
                                    @endif
                                </td>
                                @elseif($isGudang)
                                <td class="px-5 py-4">
                                    <span class="font-mono font-bold text-xs px-2.5 py-1 rounded-lg"
                                          style="background:rgba(124,58,237,0.12);color:#c4b5fd;border:1px solid rgba(124,58,237,0.2);">
                                        {{ $row['rackCode'] }}
                                    </span>
                                </td>
                                @else
                                <td class="px-5 py-4 whitespace-nowrap" style="color:rgba(255,255,255,0.55);">{{ $row['duration'] }}</td>
                                @endif

                                <td class="px-5 py-4 whitespace-nowrap text-xs" style="color:rgba(255,255,255,0.5);">{{ $row['deadline'] }}</td>

                                {{-- Actions --}}
                                <td class="px-5 py-4">
                                    <div class="flex items-center gap-1.5 flex-nowrap">
                                        {{-- Upload bukti --}}
                                        <button title="{{ $row['hasProof'] ? 'Lihat Bukti' : 'Upload Bukti Visual' }}"
                                                class="flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg text-[11px] font-semibold transition-all hover:scale-105"
                                                style="background:{{ $row['hasProof'] ? 'rgba(52,211,153,0.12)' : 'rgba(124,58,237,0.13)' }};border:1px solid {{ $row['hasProof'] ? 'rgba(52,211,153,0.3)' : 'rgba(124,58,237,0.25)' }};color:{{ $row['hasProof'] ? '#34d399' : '#a78bfa' }};">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                @if($row['hasProof'])
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                @else
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                                                @endif
                                            </svg>
                                            <span class="hidden xl:inline">{{ $row['hasProof'] ? 'Lihat' : 'Upload' }}</span>
                                        </button>
                                        {{-- Update status --}}
                                        <button {{ !$row['hasProof'] ? 'disabled' : '' }}
                                                class="flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg text-[11px] font-semibold transition-all"
                                                style="background:{{ $row['hasProof'] ? 'rgba(99,102,241,0.14)' : 'rgba(255,255,255,0.04)' }};border:1px solid {{ $row['hasProof'] ? 'rgba(99,102,241,0.3)' : 'rgba(255,255,255,0.07)' }};color:{{ $row['hasProof'] ? '#818cf8' : 'rgba(255,255,255,0.2)' }};cursor:{{ $row['hasProof'] ? 'pointer' : 'not-allowed' }};">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                            <span class="hidden xl:inline">Status</span>
                                        </button>
                                        {{-- Resi (keluar + biteship) --}}
                                        @if($isKeluar && $row['returnMode'] === 'Ekspedisi Biteship')
                                        <button class="flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg text-[11px] font-semibold transition-all hover:scale-105"
                                                style="background:rgba(56,189,248,0.12);border:1px solid rgba(56,189,248,0.28);color:#38bdf8;">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 17H7l-4-4V5a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2z"/></svg>
                                            <span class="hidden xl:inline">Resi</span>
                                        </button>
                                        @endif
                                        {{-- WA --}}
                                        <button onclick="showWaToast('{{ $row['customer'] }}')"
                                                class="flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg text-[11px] font-semibold transition-all hover:scale-105"
                                                style="background:rgba(37,211,102,0.1);border:1px solid rgba(37,211,102,0.25);color:#34d399;">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
                                            <span class="hidden xl:inline">WA</span>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @if(count($rows) === 0)
                    <div class="py-16 flex flex-col items-center gap-3">
                        <p class="text-sm" style="color:rgba(255,255,255,0.3);">Tidak ada data di tab ini</p>
                    </div>
                    @endif
                </div>
                <div class="px-5 py-3" style="border-top:1px solid rgba(255,255,255,0.05);">
                    <p class="text-[10px]" style="color:rgba(255,255,255,0.28);">{{ count($rows) }} entri</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    {{-- ══ RUANGAN TAB ══ --}}
    <div id="panel-ruangan" class="hidden">
        <div class="rounded-2xl overflow-hidden" style="background:rgba(255,255,255,0.03);border:1px solid rgba(255,255,255,0.08);">
            <div class="px-5 py-4 flex items-center justify-between" style="border-bottom:1px solid rgba(255,255,255,0.06);">
                <div>
                    <h3 class="text-sm font-bold text-white">Daftar Ruangan / Gudang</h3>
                    <p class="text-[10px] mt-0.5" style="color:rgba(255,255,255,0.35);">{{ $rooms->count() }} ruangan terdaftar</p>
                </div>
                <button onclick="openRoomModal(null)"
                        class="flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs font-bold text-white transition-all hover:scale-105"
                        style="background:linear-gradient(135deg,#7c3aed,#6366f1);box-shadow:0 4px 16px rgba(124,58,237,0.4);">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                    Tambah Ruangan Baru
                </button>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-xs" style="min-width:760px;">
                    <thead>
                        <tr style="border-bottom:1px solid rgba(255,255,255,0.06);">
                            @foreach(['Foto','Nama Gudang','Lokasi','Rentang Harga/Hari','Kapasitas Terisi','Status','Aksi'] as $h)
                            <th class="text-left px-5 py-3.5 font-semibold whitespace-nowrap" style="color:rgba(255,255,255,0.3);">{{ $h }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($rooms as $i => $room)
                        @php
                            $pct = $room->capacity_total > 0 ? round(($room->capacity_used / $room->capacity_total) * 100) : 0;
                            $barColor = $pct > 80 ? '#ef4444' : ($pct > 50 ? '#f59e0b' : '#34d399');
                            $minP = collect($room->pricing['kardus'] ?? [])->min('price') ?? 0;
                            $maxP = collect($room->pricing['koper'] ?? [])->max('price') ?? 0;
                        @endphp
                        <tr style="{{ $i < $rooms->count()-1 ? 'border-bottom:1px solid rgba(255,255,255,0.04)' : '' }}"
                            onmouseover="this.style.background='rgba(124,58,237,0.05)'"
                            onmouseout="this.style.background='transparent'">
                            <td class="px-5 py-4">
                                <div class="w-16 h-12 rounded-xl overflow-hidden flex items-center justify-center" style="background:rgba(124,58,237,0.08);border:1px solid rgba(124,58,237,0.15);">
                                    @if($room->primary_photo)
                                        <img src="{{ asset('storage/'.$room->primary_photo) }}" alt="{{ $room->name }}" class="w-full h-full object-cover">
                                    @else
                                        <x-lucide-image class="w-5 h-5" style="color:rgba(167,139,250,0.45)" />
                                    @endif
                                </div>
                            </td>
                            <td class="px-5 py-4">
                                <p class="font-semibold text-white">{{ $room->name }}</p>
                                <p class="text-[10px] mt-0.5" style="color:rgba(255,255,255,0.35);">{{ Str::limit($room->address, 35) }}</p>
                            </td>
                            <td class="px-5 py-4" style="color:rgba(255,255,255,0.55);">{{ $room->location }}</td>
                            <td class="px-5 py-4 whitespace-nowrap">
                                <span class="text-xs font-semibold text-white">{{ rupiah2($minP) }}</span>
                                <span class="text-[10px] ml-0.5" style="color:rgba(255,255,255,0.35);">– {{ rupiah2($maxP) }}/hari</span>
                            </td>
                            <td class="px-5 py-4" style="min-width:140px;">
                                <div class="flex items-center gap-2">
                                    <div class="flex-1 h-2 rounded-full overflow-hidden" style="background:rgba(255,255,255,0.08);">
                                        <div class="h-full rounded-full" style="width:{{ $pct }}%;background:linear-gradient(90deg,{{ $barColor }}99,{{ $barColor }});"></div>
                                    </div>
                                    <span class="text-[10px] font-bold shrink-0" style="color:{{ $barColor }};">{{ $pct }}%</span>
                                </div>
                                <p class="text-[9px] mt-1" style="color:rgba(255,255,255,0.3);">{{ $room->capacity_used }}/{{ $room->capacity_total }} slot</p>
                            </td>
                            <td class="px-5 py-4">
                                <button onclick="toggleRoomActive({{ $room->id }}, this)"
                                        data-active="{{ $room->active ? '1' : '0' }}"
                                        class="flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-semibold transition-all hover:scale-105"
                                        style="background:{{ $room->active ? 'rgba(52,211,153,0.12)' : 'rgba(255,255,255,0.06)' }};border:1px solid {{ $room->active ? 'rgba(52,211,153,0.3)' : 'rgba(255,255,255,0.1)' }};color:{{ $room->active ? '#34d399' : 'rgba(255,255,255,0.35)' }};">
                                    {{ $room->active ? 'Aktif' : 'Nonaktif' }}
                                </button>
                            </td>
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-1.5">
                                    <button onclick='openRoomModal(@json($room))'
                                            class="flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg text-[11px] font-semibold transition-all hover:scale-105"
                                            style="background:rgba(99,102,241,0.13);border:1px solid rgba(99,102,241,0.28);color:#818cf8;">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        Edit
                                    </button>
                                    <form method="POST" action="{{ route('admin.ruang-titip.destroy', $room) }}"
                                          onsubmit="return confirm('Hapus ruangan ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                                class="flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg text-[11px] font-semibold transition-all hover:scale-105"
                                                style="background:rgba(239,68,68,0.1);border:1px solid rgba(239,68,68,0.25);color:#f87171;">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="px-5 py-16 text-center text-sm" style="color:rgba(255,255,255,0.3);">Belum ada ruangan. Klik "Tambah Ruangan Baru" untuk mulai.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- ══ ROOM MODAL ══ --}}
<div id="room-modal" class="fixed inset-0 z-50 hidden items-center justify-center p-6 overflow-y-auto"
     style="background:rgba(0,0,0,0.78);backdrop-filter:blur(6px);"
     onclick="if(event.target===this) closeRoomModal()">
    <div class="w-full max-w-2xl rounded-3xl overflow-hidden my-6"
         style="background:rgba(12,6,24,0.99);border:1px solid rgba(139,92,246,0.25);box-shadow:0 24px 80px rgba(0,0,0,0.75);">

        {{-- Modal header --}}
        <div class="px-7 py-5 flex items-center justify-between"
             style="border-bottom:1px solid rgba(255,255,255,0.07);background:rgba(124,58,237,0.06);">
            <div>
                <h2 id="modal-title" class="text-base font-extrabold text-white font-display">Tambah Ruangan Baru</h2>
                <p id="modal-subtitle" class="text-xs mt-0.5" style="color:rgba(255,255,255,0.38);">Data ruangan baru akan tayang di aplikasi pelanggan</p>
            </div>
            <button onclick="closeRoomModal()" class="w-9 h-9 rounded-xl flex items-center justify-center hover:bg-white/10 transition-colors" style="color:rgba(255,255,255,0.4);">✕</button>
        </div>

        {{-- Modal form --}}
        <form id="room-form" method="POST" action="{{ route('admin.ruang-titip.store') }}" enctype="multipart/form-data">
            @csrf
            <div id="method-field"></div>

            <div class="px-7 py-6 space-y-5 max-h-[70vh] overflow-y-auto">

                {{-- Basic info --}}
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold mb-2" style="color:rgba(255,255,255,0.55);">Nama Gudang <span style="color:#f87171">*</span></label>
                        <input name="name" id="field-name" required placeholder="Gudang Utama A"
                               class="w-full px-4 py-3 rounded-xl text-sm text-white placeholder:text-white/20 outline-none"
                               style="background:rgba(255,255,255,0.06);border:1.5px solid rgba(255,255,255,0.1);"
                               onfocus="this.style.borderColor='rgba(124,58,237,0.55)'"
                               onblur="this.style.borderColor='rgba(255,255,255,0.1)'">
                    </div>
                    <div>
                        <label class="block text-xs font-bold mb-2" style="color:rgba(255,255,255,0.55);">Lokasi / Kecamatan</label>
                        <input name="location" id="field-location" placeholder="Lowokwaru, Malang"
                               class="w-full px-4 py-3 rounded-xl text-sm text-white placeholder:text-white/20 outline-none"
                               style="background:rgba(255,255,255,0.06);border:1.5px solid rgba(255,255,255,0.1);"
                               onfocus="this.style.borderColor='rgba(124,58,237,0.55)'"
                               onblur="this.style.borderColor='rgba(255,255,255,0.1)'">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold mb-2" style="color:rgba(255,255,255,0.55);">Alamat Lengkap <span style="color:#f87171">*</span></label>
                    <input name="address" id="field-address" required placeholder="Jl. Veteran No. 10, Kec. Lowokwaru, Malang"
                           class="w-full px-4 py-3 rounded-xl text-sm text-white placeholder:text-white/20 outline-none"
                           style="background:rgba(255,255,255,0.06);border:1.5px solid rgba(255,255,255,0.1);"
                           onfocus="this.style.borderColor='rgba(124,58,237,0.55)'"
                           onblur="this.style.borderColor='rgba(255,255,255,0.1)'">
                </div>

                <div>
                    <label class="block text-xs font-bold mb-2" style="color:rgba(255,255,255,0.55);">Deskripsi</label>
                    <textarea name="description" id="field-description" rows="3" placeholder="Jelaskan detail gudang…"
                              class="w-full px-4 py-3 rounded-xl text-sm text-white placeholder:text-white/20 outline-none resize-none"
                              style="background:rgba(255,255,255,0.06);border:1.5px solid rgba(255,255,255,0.1);"
                              onfocus="this.style.borderColor='rgba(124,58,237,0.55)'"
                              onblur="this.style.borderColor='rgba(255,255,255,0.1)'"></textarea>
                </div>

                <div>
                    <label class="block text-xs font-bold mb-2" style="color:rgba(255,255,255,0.55);">Foto Ruangan</label>
                    <label for="room-images-input" class="rt-img-drop">
                        <div class="rt-img-drop-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-white">Klik untuk pilih / tambah foto</p>
                            <p class="text-[10px]" style="color:rgba(255,255,255,0.35);">Bisa diklik berkali-kali untuk menambah foto satu per satu.</p>
                        </div>
                        <input id="room-images-input" type="file" name="images[]" accept="image/*" multiple data-existing-count="0" class="sr-only">
                    </label>
                    <div id="room-images-preview" class="rt-img-pick-grid hidden"></div>
                    <p id="room-images-label" class="text-[10px] mt-1.5" style="color:rgba(255,255,255,0.35);">Bisa pilih lebih dari satu gambar. Maksimal 10 foto asli per ruangan.</p>
                    @error('images')
                        <p class="text-[10px] mt-1.5 text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Pricing --}}
                <div>
                    <p class="text-xs font-bold text-white mb-3">Harga per Hari</p>
                    {{-- Kardus --}}
                    <div class="rounded-2xl overflow-hidden mb-3" style="background:rgba(124,58,237,0.06);border:1px solid rgba(124,58,237,0.2);">
                        <div class="px-4 py-2.5 text-xs font-bold text-white" style="border-bottom:1px solid rgba(255,255,255,0.06);background:rgba(124,58,237,0.1);">📦 Kardus</div>
                        <div class="p-4 grid grid-cols-4 gap-3" id="pricing-kardus"></div>
                    </div>
                    {{-- Koper --}}
                    <div class="rounded-2xl overflow-hidden mb-3" style="background:rgba(56,189,248,0.05);border:1px solid rgba(56,189,248,0.18);">
                        <div class="px-4 py-2.5 text-xs font-bold text-white" style="border-bottom:1px solid rgba(255,255,255,0.06);background:rgba(56,189,248,0.08);">🧳 Koper</div>
                        <div class="p-4 grid grid-cols-4 gap-3" id="pricing-koper"></div>
                    </div>
                    {{-- Dimensi lain --}}
                    <div class="rounded-2xl p-4 flex items-center gap-4" style="background:rgba(255,255,255,0.03);border:1px solid rgba(255,255,255,0.09);">
                        <div class="flex-1">
                            <p class="text-xs font-bold text-white">📐 Dimensi Lain</p>
                            <p class="text-[9px] mt-0.5" style="color:rgba(255,255,255,0.35);">30×30×30 – 100×100×100 cm</p>
                        </div>
                        <div class="w-36">
                            <div class="flex items-center rounded-xl overflow-hidden" style="background:rgba(255,255,255,0.06);border:1.5px solid rgba(255,255,255,0.1);">
                                <span class="px-2.5 text-xs shrink-0" style="color:rgba(255,255,255,0.4);">Rp</span>
                                <input type="number" id="field-dimensiLain" name="pricing[dimensiLain]" min="0" placeholder="0"
                                       class="flex-1 w-full px-2 py-2 text-xs text-white placeholder:text-white/20 outline-none bg-transparent">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Kapasitas --}}
                <div>
                    <label class="block text-xs font-bold mb-2" style="color:rgba(255,255,255,0.55);">Kapasitas Total (slot)</label>
                    <input type="number" name="capacity_total" id="field-capacity" min="1" placeholder="500"
                           class="w-full px-4 py-3 rounded-xl text-sm text-white placeholder:text-white/20 outline-none"
                           style="background:rgba(255,255,255,0.06);border:1.5px solid rgba(255,255,255,0.1);">
                </div>

                {{-- Fasilitas --}}
                <div>
                    <p class="text-xs font-bold text-white mb-3">Fasilitas</p>
                    <div class="flex flex-wrap gap-2" id="facilities-list">
                        @foreach($allFacilities as $f)
                        <button type="button" onclick="toggleFacility(this, '{{ $f }}')"
                                class="facility-btn flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-semibold transition-all"
                                data-value="{{ $f }}"
                                style="background:rgba(255,255,255,0.05);border:1.5px solid rgba(255,255,255,0.1);color:rgba(255,255,255,0.45);">
                            {{ $f }}
                        </button>
                        @endforeach
                    </div>
                    <div id="facilities-hidden"></div>
                </div>

                {{-- Status --}}
                <div class="flex items-center justify-between px-4 py-3.5 rounded-2xl"
                     style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.08);">
                    <div>
                        <p class="text-sm font-semibold text-white">Status Publikasi</p>
                        <p class="text-xs mt-0.5" style="color:rgba(255,255,255,0.38);">Ruangan ini akan tampil di aplikasi pelanggan</p>
                    </div>
                    <button type="button" id="active-toggle" onclick="toggleActiveField()"
                            class="relative w-12 h-6 rounded-full transition-all"
                            style="background:linear-gradient(135deg,#7c3aed,#6366f1);">
                        <div id="active-knob" class="absolute top-1 w-4 h-4 rounded-full bg-white transition-all" style="left:24px;"></div>
                    </button>
                    <input type="hidden" name="active" id="field-active" value="1">
                </div>
            </div>

            {{-- Footer --}}
            <div class="px-7 py-5 flex gap-3" style="border-top:1px solid rgba(255,255,255,0.07);">
                <button type="button" onclick="closeRoomModal()"
                        class="flex-1 py-3.5 rounded-2xl text-sm font-semibold transition-all hover:bg-white/5"
                        style="border:1.5px solid rgba(255,255,255,0.14);color:rgba(255,255,255,0.7);">Batal</button>
                <button type="submit"
                        class="flex-[2] py-3.5 rounded-2xl text-sm font-bold text-white flex items-center justify-center gap-2 transition-all hover:scale-[1.01]"
                        style="background:linear-gradient(135deg,#7c3aed,#6366f1);box-shadow:0 6px 20px rgba(124,58,237,0.4);">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span id="modal-submit-label">Simpan Ruangan</span>
                </button>
            </div>
        </form>
    </div>
</div>

{{-- WA Toast --}}
<div id="wa-toast" class="fixed bottom-6 right-6 z-50 hidden items-center gap-3 px-4 py-3 rounded-2xl"
     style="background:rgba(37,211,102,0.15);border:1px solid rgba(37,211,102,0.35);backdrop-filter:blur(12px);box-shadow:0 8px 32px rgba(0,0,0,0.4);">
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
/* ── Default pricing data ── */
const DEFAULT_PRICING = @json($defaultPricing);
const ALL_FACILITIES  = @json($allFacilities);
const MAX_ROOM_IMAGES = 10;

let activeField = true;

const roomImagePicker = createMultiImagePicker({
    inputId: 'room-images-input',
    previewId: 'room-images-preview',
    labelId: 'room-images-label',
    maxImages: MAX_ROOM_IMAGES,
    emptyText: 'Bisa pilih lebih dari satu gambar. Maksimal 10 foto asli per ruangan.',
});

/* ── Page tab ── */
function switchPageTab(key) {
    ['operasional','ruangan'].forEach(k => {
        document.getElementById('panel-' + k).classList.toggle('hidden', k !== key);
        const btn = document.getElementById('ptab-' + k);
        if (k === key) {
            btn.style.background = 'linear-gradient(135deg,#7c3aed,#6366f1)';
            btn.style.color = 'white';
            btn.style.boxShadow = '0 2px 12px rgba(124,58,237,0.35)';
        } else {
            btn.style.background = 'transparent';
            btn.style.color = 'rgba(255,255,255,0.45)';
            btn.style.boxShadow = 'none';
        }
    });
}

/* ── Order sub-tab ── */
function switchOrderTab(key) {
    document.querySelectorAll('.order-panel').forEach(p => p.classList.add('hidden'));
    document.querySelectorAll('.order-tab-btn').forEach(b => {
        b.style.color = 'rgba(255,255,255,0.38)';
    });
    document.getElementById('opanel-' + key).classList.remove('hidden');
    document.getElementById('otab-' + key).style.color = '#c4b5fd';
}

/* ── WA Toast ── */
function showWaToast(name) {
    const t = document.getElementById('wa-toast');
    document.getElementById('wa-toast-name').textContent = 'ke ' + name;
    t.classList.remove('hidden');
    t.classList.add('flex');
    setTimeout(() => { t.classList.add('hidden'); t.classList.remove('flex'); }, 3500);
}

/* ── Active toggle ── */
function toggleActiveField() {
    activeField = !activeField;
    document.getElementById('field-active').value = activeField ? '1' : '0';
    const btn  = document.getElementById('active-toggle');
    const knob = document.getElementById('active-knob');
    btn.style.background  = activeField ? 'linear-gradient(135deg,#7c3aed,#6366f1)' : 'rgba(255,255,255,0.12)';
    knob.style.left = activeField ? '24px' : '2px';
}

/* ── Facility toggle ── */
function toggleFacility(btn, value) {
    const active = btn.dataset.selected === '1';
    if (active) {
        btn.dataset.selected = '0';
        btn.style.background = 'rgba(255,255,255,0.05)';
        btn.style.border = '1.5px solid rgba(255,255,255,0.1)';
        btn.style.color = 'rgba(255,255,255,0.45)';
    } else {
        btn.dataset.selected = '1';
        btn.style.background = 'rgba(124,58,237,0.2)';
        btn.style.border = '1.5px solid rgba(124,58,237,0.5)';
        btn.style.color = '#c4b5fd';
    }
    renderFacilityHidden();
}

function renderFacilityHidden() {
    const container = document.getElementById('facilities-hidden');
    container.innerHTML = '';
    document.querySelectorAll('.facility-btn[data-selected="1"]').forEach(btn => {
        const inp = document.createElement('input');
        inp.type = 'hidden';
        inp.name = 'facilities[]';
        inp.value = btn.dataset.value;
        container.appendChild(inp);
    });
}

/* ── Pricing fields builder ── */
function buildPricingFields(pricing) {
    ['kardus','koper'].forEach(type => {
        const container = document.getElementById('pricing-' + type);
        container.innerHTML = '';
        (pricing[type] || []).forEach(row => {
            container.innerHTML += `
            <div>
                <p class="text-[10px] font-bold text-center mb-1" style="color:${type==='kardus'?'#a78bfa':'#38bdf8'}">${row.label}</p>
                <p class="text-[9px] text-center mb-2" style="color:rgba(255,255,255,0.3)">${row.dims}</p>
                <div class="flex items-center rounded-xl overflow-hidden" style="background:rgba(255,255,255,0.06);border:1.5px solid rgba(255,255,255,0.1)">
                    <span class="px-2.5 text-xs shrink-0" style="color:rgba(255,255,255,0.4)">Rp</span>
                    <input type="number" name="pricing[${type}][${row.id}]" min="0" value="${row.price}"
                           placeholder="0"
                           class="flex-1 w-full px-2 py-2 text-xs text-white placeholder:text-white/20 outline-none bg-transparent">
                </div>
            </div>`;
        });
    });
    document.getElementById('field-dimensiLain').value = pricing.dimensiLain || 0;
}

/* ── Room modal open/close ── */
function openRoomModal(room) {
    const modal = document.getElementById('room-modal');
    const form  = document.getElementById('room-form');
    const mf    = document.getElementById('method-field');

    // Reset facilities
    document.querySelectorAll('.facility-btn').forEach(btn => {
        btn.dataset.selected = '0';
        btn.style.background = 'rgba(255,255,255,0.05)';
        btn.style.border = '1.5px solid rgba(255,255,255,0.1)';
        btn.style.color = 'rgba(255,255,255,0.45)';
    });

    if (room) {
        // Edit mode
        document.getElementById('modal-title').textContent = 'Edit Ruangan';
        document.getElementById('modal-subtitle').textContent = 'Mengedit: ' + room.name;
        document.getElementById('modal-submit-label').textContent = 'Simpan Perubahan';
        form.action = '/admin/ruang-titip/' + room.id;
        mf.innerHTML = '<input type="hidden" name="_method" value="PUT">';

        document.getElementById('field-name').value        = room.name || '';
        document.getElementById('field-location').value   = room.location || '';
        document.getElementById('field-address').value    = room.address || '';
        document.getElementById('field-description').value = room.description || '';
        document.getElementById('field-capacity').value   = room.capacity_total || '';

        // Pricing
        buildPricingFields(room.pricing || DEFAULT_PRICING);

        // Facilities
        const roomFacs = room.facilities || [];
        document.querySelectorAll('.facility-btn').forEach(btn => {
            if (roomFacs.includes(btn.dataset.value)) {
                btn.dataset.selected = '1';
                btn.style.background = 'rgba(124,58,237,0.2)';
                btn.style.border = '1.5px solid rgba(124,58,237,0.5)';
                btn.style.color = '#c4b5fd';
            }
        });

        // Active
        activeField = room.active == true || room.active == 1;
        document.getElementById('field-active').value = activeField ? '1' : '0';
        document.getElementById('active-toggle').style.background = activeField ? 'linear-gradient(135deg,#7c3aed,#6366f1)' : 'rgba(255,255,255,0.12)';
        document.getElementById('active-knob').style.left = activeField ? '24px' : '2px';
        roomImagePicker.reset(Array.isArray(room.photos) ? room.photos.length : 0);

    } else {
        // Create mode
        document.getElementById('modal-title').textContent = 'Tambah Ruangan Baru';
        document.getElementById('modal-subtitle').textContent = 'Data ruangan baru akan tayang di aplikasi pelanggan';
        document.getElementById('modal-submit-label').textContent = 'Simpan Ruangan';
        form.action = '{{ route("admin.ruang-titip.store") }}';
        mf.innerHTML = '';
        form.reset();
        buildPricingFields(DEFAULT_PRICING);

        activeField = true;
        document.getElementById('field-active').value = '1';
        document.getElementById('active-toggle').style.background = 'linear-gradient(135deg,#7c3aed,#6366f1)';
        document.getElementById('active-knob').style.left = '24px';
        roomImagePicker.reset(0);
    }

    renderFacilityHidden();
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeRoomModal() {
    const modal = document.getElementById('room-modal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

/* ── Toggle active via AJAX ── */
function toggleRoomActive(id, btn) {
    fetch('/admin/ruang-titip/' + id + '/toggle-active', {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
    })
    .then(r => r.json())
    .then(data => {
        const isActive = data.active;
        btn.textContent = isActive ? 'Aktif' : 'Nonaktif';
        btn.style.background = isActive ? 'rgba(52,211,153,0.12)' : 'rgba(255,255,255,0.06)';
        btn.style.border = isActive ? '1px solid rgba(52,211,153,0.3)' : '1px solid rgba(255,255,255,0.1)';
        btn.style.color = isActive ? '#34d399' : 'rgba(255,255,255,0.35)';
        btn.dataset.active = isActive ? '1' : '0';
    });
}

/* ── Init ── */
document.addEventListener('DOMContentLoaded', () => {
    const pageTab  = '{{ $pageTab }}';
    const orderTab = '{{ $orderTab }}';
    switchPageTab(pageTab);
    switchOrderTab(orderTab);
    buildPricingFields(DEFAULT_PRICING);
});
</script>
@endpush
@endsection
