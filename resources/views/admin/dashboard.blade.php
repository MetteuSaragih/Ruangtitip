@extends('layouts.admin')

@section('title', 'Dasbor Overview')

@section('content')
<div class="p-6 max-w-[1280px]">

    {{-- Page title --}}
    <div class="mb-6">
        <h1 class="text-xl font-extrabold text-white font-display">Dasbor Overview</h1>
        <p class="text-xs mt-0.5" style="color:rgba(255,255,255,0.38);">Ringkasan eksekutif operasional RUTIP hari ini</p>
    </div>

    {{-- Scorecards --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">

        {{-- Pendapatan --}}
        <div class="rounded-2xl p-5 flex flex-col gap-3"
             style="background:rgba(255,255,255,0.035);border:1px solid rgba(255,255,255,0.08);">
            <div class="flex items-center justify-between">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center"
                     style="background:rgba(124,58,237,0.18);">
                    <svg class="w-5 h-5" style="color:#a78bfa" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                    </svg>
                </div>
                <span class="text-xs font-semibold px-2 py-0.5 rounded-full"
                      style="background:rgba(52,211,153,0.12);color:#34d399;">+12% bulan lalu</span>
            </div>
            <div>
                <p class="text-xs font-medium mb-1" style="color:rgba(255,255,255,0.45);">Pendapatan (Bulan Ini)</p>
                <p class="text-2xl font-extrabold text-white font-display">Rp {{ number_format($pendapatan, 0, ',', '.') }}</p>
            </div>
        </div>

        {{-- Transaksi Aktif --}}
        <div class="rounded-2xl p-5 flex flex-col gap-3"
             style="background:rgba(255,255,255,0.035);border:1px solid rgba(255,255,255,0.08);">
            <div class="flex items-center justify-between">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center"
                     style="background:rgba(56,189,248,0.15);">
                    <svg class="w-5 h-5" style="color:#38bdf8" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
            </div>
            <div>
                <p class="text-xs font-medium mb-1" style="color:rgba(255,255,255,0.45);">Transaksi Aktif</p>
                <p class="text-2xl font-extrabold text-white font-display">{{ $transaksiAktif }}</p>
                <p class="text-xs mt-1" style="color:rgba(255,255,255,0.38);">Penitipan, Preloved, Packing</p>
            </div>
        </div>

        {{-- Kapasitas Gudang --}}
        <div class="rounded-2xl p-5 flex flex-col gap-3"
             style="background:rgba(255,255,255,0.035);border:1px solid rgba(255,255,255,0.08);">
            <div class="flex items-center justify-between">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center"
                     style="background:rgba(245,158,11,0.15);">
                    <svg class="w-5 h-5" style="color:#f59e0b" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            <div>
                <p class="text-xs font-medium mb-1" style="color:rgba(255,255,255,0.45);">Kapasitas Gudang Terpakai</p>
                <p class="text-2xl font-extrabold text-white font-display">{{ $kapasitasGudang }}%</p>
                @php $color = $kapasitasGudang > 80 ? '#ef4444' : ($kapasitasGudang > 50 ? '#f59e0b' : '#34d399'); @endphp
                <div class="mt-2">
                    <div class="h-2.5 rounded-full overflow-hidden" style="background:rgba(255,255,255,0.08);">
                        <div class="h-full rounded-full transition-all duration-700"
                             style="width:{{ $kapasitasGudang }}%;background:linear-gradient(90deg,{{ $color }}cc,{{ $color }});box-shadow:0 0 8px {{ $color }}66;"></div>
                    </div>
                    <div class="flex justify-between mt-1">
                        <span class="text-[10px]" style="color:rgba(255,255,255,0.3);">0%</span>
                        <span class="text-[10px]" style="color:{{ $color }};">Kapasitas penuh: 100%</span>
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- Tugas Prioritas --}}
    <div class="mb-4 rounded-2xl overflow-hidden"
         style="background:rgba(255,255,255,0.03);border:1px solid rgba(255,255,255,0.08);">

        <div class="px-5 py-4 flex items-center gap-2.5"
             style="border-bottom:1px solid rgba(255,255,255,0.06);">
            <div class="w-7 h-7 rounded-lg flex items-center justify-center"
                 style="background:rgba(245,158,11,0.15);">
                <svg class="w-3.5 h-3.5" style="color:#f59e0b" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                </svg>
            </div>
            <h2 class="text-sm font-bold text-white">Tugas Prioritas Hari Ini</h2>
            <span class="ml-auto text-xs px-2 py-0.5 rounded-full font-semibold"
                  style="background:rgba(239,68,68,0.12);color:#f87171;">{{ count($tugasPrioritas) }} tugas</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-xs">
                <thead>
                    <tr style="border-bottom:1px solid rgba(255,255,255,0.05);">
                        @foreach(['ID Pesanan','Kategori','Pelanggan','Tenggat','Aksi'] as $h)
                        <th class="text-left px-5 py-3 font-semibold whitespace-nowrap"
                            style="color:rgba(255,255,255,0.32);">{{ $h }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach($tugasPrioritas as $i => $task)
                    @php
                        $badgeStyle = match($task['badge']) {
                            'Jadwal Jemput'         => ['bg'=>'rgba(99,102,241,0.15)',  'color'=>'#818cf8', 'dot'=>'#6366f1'],
                            'Antar/Kirim Ekspedisi' => ['bg'=>'rgba(245,158,11,0.15)',  'color'=>'#fbbf24', 'dot'=>'#f59e0b'],
                            'Batas Waktu Habis'     => ['bg'=>'rgba(239,68,68,0.18)',   'color'=>'#f87171', 'dot'=>'#ef4444'],
                            default                 => ['bg'=>'rgba(255,255,255,0.1)',  'color'=>'#fff',    'dot'=>'#fff'],
                        };
                    @endphp
                    <tr style="{{ $i < count($tugasPrioritas)-1 ? 'border-bottom:1px solid rgba(255,255,255,0.04)' : '' }}"
                        onmouseover="this.style.background='rgba(124,58,237,0.06)'"
                        onmouseout="this.style.background='transparent'">
                        <td class="px-5 py-3.5">
                            <span class="font-mono font-semibold" style="color:#a78bfa;">{{ $task['id'] }}</span>
                        </td>
                        <td class="px-5 py-3.5 whitespace-nowrap">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-semibold"
                                  style="background:{{ $badgeStyle['bg'] }};color:{{ $badgeStyle['color'] }};">
                                <span class="w-1.5 h-1.5 rounded-full shrink-0"
                                      style="background:{{ $badgeStyle['dot'] }};"></span>
                                {{ $task['badge'] }}
                            </span>
                        </td>
                        <td class="px-5 py-3.5">
                            <p class="font-medium text-white">{{ $task['customer'] }}</p>
                            <p style="color:rgba(255,255,255,0.35);">{{ $task['wa'] }}</p>
                        </td>
                        <td class="px-5 py-3.5 whitespace-nowrap" style="color:rgba(255,255,255,0.55);">
                            {{ $task['deadline'] }}
                        </td>
                        <td class="px-5 py-3.5">
                            <a href="{{ $task['href'] }}"
                               class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-[11px] font-semibold transition-all hover:scale-105"
                               style="background:rgba(124,58,237,0.18);color:#c4b5fd;border:1px solid rgba(124,58,237,0.25);">
                                Lihat
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- Tren Pesanan --}}
    <div class="rounded-2xl p-5"
         style="background:rgba(255,255,255,0.03);border:1px solid rgba(255,255,255,0.08);">

        <div class="flex items-center gap-2.5 mb-5">
            <div class="w-7 h-7 rounded-lg flex items-center justify-center"
                 style="background:rgba(124,58,237,0.15);">
                <svg class="w-3.5 h-3.5" style="color:#a78bfa" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                </svg>
            </div>
            <div>
                <h2 class="text-sm font-bold text-white leading-tight">Tren Pesanan Masuk</h2>
                <p class="text-[10px]" style="color:rgba(255,255,255,0.35);">7 Hari Terakhir</p>
            </div>
        </div>

        <canvas id="trenChart" height="80"></canvas>

        <div class="flex gap-3 mt-4 pt-4" style="border-top:1px solid rgba(255,255,255,0.06);">
            <div class="flex-1">
                <p class="text-[10px]" style="color:rgba(255,255,255,0.32);">Total 7 hari</p>
                <p class="text-xs font-bold text-white mt-0.5">{{ array_sum(array_column($trenPesanan, 'pesanan')) }} pesanan</p>
            </div>
            <div class="flex-1">
                <p class="text-[10px]" style="color:rgba(255,255,255,0.32);">Rata-rata/hari</p>
                <p class="text-xs font-bold text-white mt-0.5">{{ round(array_sum(array_column($trenPesanan, 'pesanan')) / count($trenPesanan)) }} pesanan</p>
            </div>
            <div class="flex-1">
                <p class="text-[10px]" style="color:rgba(255,255,255,0.32);">Puncak hari ini</p>
                <p class="text-xs font-bold text-white mt-0.5">{{ max(array_column($trenPesanan, 'pesanan')) }} pesanan</p>
            </div>
        </div>
    </div>

</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('trenChart').getContext('2d');
const gradient = ctx.createLinearGradient(0, 0, 0, 200);
gradient.addColorStop(0, 'rgba(124,58,237,0.35)');
gradient.addColorStop(1, 'rgba(124,58,237,0)');

new Chart(ctx, {
    type: 'line',
    data: {
        labels: @json(array_column($trenPesanan, 'day')),
        datasets: [{
            data: @json(array_column($trenPesanan, 'pesanan')),
            borderColor: '#7c3aed',
            borderWidth: 2.5,
            backgroundColor: gradient,
            fill: true,
            tension: 0.4,
            pointRadius: 0,
            pointHoverRadius: 5,
            pointHoverBackgroundColor: '#a78bfa',
            pointHoverBorderColor: '#c4b5fd',
            pointHoverBorderWidth: 2,
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { display: false },
            tooltip: {
                backgroundColor: 'rgba(18,10,35,0.97)',
                borderColor: 'rgba(124,58,237,0.35)',
                borderWidth: 1,
                titleColor: '#fff',
                bodyColor: '#a78bfa',
                callbacks: {
                    label: ctx => ctx.parsed.y + ' pesanan'
                }
            }
        },
        scales: {
            x: {
                grid: { color: 'rgba(255,255,255,0.05)', drawTicks: false },
                ticks: { color: 'rgba(255,255,255,0.35)', font: { size: 11 } },
                border: { display: false }
            },
            y: {
                grid: { color: 'rgba(255,255,255,0.05)', drawTicks: false },
                ticks: { color: 'rgba(255,255,255,0.28)', font: { size: 10 } },
                border: { display: false }
            }
        }
    }
});
</script>
@endpush
@endsection