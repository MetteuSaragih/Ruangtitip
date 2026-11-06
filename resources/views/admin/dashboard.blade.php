@extends('layouts.admin')

@section('title', 'Ringkasan')

@push('styles')
<style>
    .chart .c .pair { display: flex; align-items: flex-end; gap: 2px; width: 100%; flex: 1; min-height: 0; justify-content: center; }
    .chart .c .pair i { flex: 1; min-height: 2px; }
    .room-row { display: grid; gap: 6px; }
    .room-row .top-r { display: flex; justify-content: space-between; gap: 10px; font-size: 13.5px; }
    .act-list { list-style: none; margin: 0; padding: 0; }
    .act-list li { padding: 11px 18px; border-top: 1px solid var(--color-divider); display: flex; justify-content: space-between; gap: 12px; }
    .act-list li:first-child { border-top: 0; }
    .act-list a { text-decoration: none; color: inherit; }
    .act-list a:hover b { color: var(--color-primary); }
    .act-list b { display: block; font-size: 13.5px; font-weight: 600; }
    .act-list small { color: var(--color-text-secondary); font-size: 12px; }
</style>
@endpush

@section('content')
@php
    $firstName = \Illuminate\Support\Str::of(auth()->user()->name ?? 'Admin')->trim()->explode(' ')->first();
    $rp = fn ($n) => 'Rp ' . number_format((int) $n, 0, ',', '.');
    $okClass = $okupansi >= 90 ? 'full' : ($okupansi >= 70 ? 'hi' : '');
@endphp

<div class="ph">
    <div>
        <h1>Halo, {{ $firstName }}</h1>
        <p>{{ now('Asia/Jakarta')->locale('id')->translatedFormat('l, j F Y') }}</p>
    </div>
</div>

<div class="stack">
    <div class="g4">
        <div class="kpi">
            <small>Barang di gudang</small>
            <b class="num">{{ number_format($barangDiGudang, 0, ',', '.') }}</b>
            <span>item berstatus dalam gudang</span>
        </div>
        <div class="kpi">
            <small>Okupansi gudang</small>
            <b class="num">{{ $okupansi }}%</b>
            <span>dari seluruh kapasitas (setara ukuran S)</span>
        </div>
        <div class="kpi">
            <small>Pendapatan bulan ini</small>
            <b class="num">{{ $rp($pendapatan) }}</b>
            <span>titip dan preloved, sudah dibayar</span>
        </div>
        <div class="kpi">
            <small>Tagihan menunggu</small>
            <b class="num">{{ $rp($tagihanNilai) }}</b>
            <span>{{ $tagihanJumlah }} tagihan belum dibayar</span>
        </div>
    </div>

    {{-- ===== Pengunjung website ===== --}}
    <section class="card" id="visitors">
        <div class="card-h">
            <h3>Pengunjung website</h3>
            <small>Dihitung per browser, tanpa identitas. Kunjungan admin dan mesin pencari tidak ikut.</small>
        </div>

        @if ($visits === null)
            <div class="empty"><b>Statistik kunjungan belum aktif</b>Jalankan <span class="mono">php artisan migrate</span> di server, lalu data mulai terkumpul.</div>
        @else
            @php $t = $visits['periods']['today']; $w = $visits['periods']['week']; $m = $visits['periods']['month']; $a = $visits['active']; @endphp

            <div class="g4" style="margin-bottom:16px">
                <div class="kpi">
                    <small>Pengunjung hari ini</small>
                    <b class="num">{{ number_format($t['total'], 0, ',', '.') }}</b>
                    <span>{{ $t['login'] }} login, {{ $t['landing_only'] }} hanya landing page</span>
                </div>
                <div class="kpi">
                    <small>7 hari terakhir</small>
                    <b class="num">{{ number_format($w['total'], 0, ',', '.') }}</b>
                    <span>{{ $w['login'] }} login, {{ $w['landing_only'] }} hanya landing page</span>
                </div>
                <div class="kpi">
                    <small>30 hari terakhir</small>
                    <b class="num">{{ number_format($m['total'], 0, ',', '.') }}</b>
                    <span>{{ $m['login'] }} login, {{ $m['landing_only'] }} hanya landing page</span>
                </div>
                <div class="kpi">
                    <small>Sedang aktif ({{ $a['minutes'] }} menit terakhir)</small>
                    <b class="num">{{ number_format($a['total'], 0, ',', '.') }}</b>
                    <span>{{ $a['login'] }} sudah login, {{ $a['guest'] }} belum login</span>
                </div>
            </div>

            <div class="tw" style="margin-bottom:16px">
                <table class="tbl" style="min-width:560px">
                    <thead>
                        <tr>
                            <th>Periode</th>
                            <th class="r">Semua pengunjung</th>
                            <th class="r">Sudah login</th>
                            <th class="r">Hanya landing page</th>
                            <th class="r">Buka halaman lain, belum login</th>
                            <th class="r">Akun baru</th>
                            <th class="r">Rasio login</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($visits['periods'] as $row)
                            <tr>
                                <td><b>{{ $row['label'] }}</b></td>
                                <td class="r num">{{ number_format($row['total'], 0, ',', '.') }}</td>
                                <td class="r num"><span class="st ok">{{ number_format($row['login'], 0, ',', '.') }}</span></td>
                                <td class="r num">{{ number_format($row['landing_only'], 0, ',', '.') }}</td>
                                <td class="r num">{{ number_format($row['other'], 0, ',', '.') }}</td>
                                <td class="r num">{{ number_format($row['new_accounts'], 0, ',', '.') }}</td>
                                <td class="r num"><b>{{ $row['login_pct'] }}%</b></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @php $vMax = max(1, collect($visits['chart'])->max('total')); @endphp
            <div class="card-h" style="margin-bottom:8px">
                <h3 style="font-size:14px">Pengunjung 14 hari terakhir</h3>
                <div class="legend"><span>Semua pengunjung</span><span class="t">Sudah login</span></div>
            </div>
            <div class="chart" role="img" aria-label="Grafik pengunjung per hari, semua dan yang sudah login">
                @foreach ($visits['chart'] as $c)
                    <div class="c" title="{{ $c['date'] }}: {{ $c['total'] }} pengunjung, {{ $c['login'] }} login, {{ $c['landing_only'] }} hanya landing page">
                        <div class="pair">
                            <i style="height: {{ round($c['total'] / $vMax * 100) }}%"></i>
                            <i class="t" style="height: {{ round($c['login'] / $vMax * 100) }}%"></i>
                        </div>
                        <small>{{ $c['label'] }}</small>
                    </div>
                @endforeach
            </div>
            @if (! empty($visits['since']))
                <p class="help" style="margin-top:12px">Data kunjungan dicatat sejak {{ \Illuminate\Support\Carbon::parse($visits['since'])->translatedFormat('j F Y') }}.</p>
            @endif
        @endif
    </section>

    <div class="cols">
        <div class="stack">
            <section class="card flush">
                <div class="card-h"><h3>Perlu dikerjakan</h3><small>{{ count($tugas) }} jenis tugas</small></div>
                @if (count($tugas) === 0)
                    <div class="empty"><b>Semua beres</b>Tidak ada tugas yang menunggu.</div>
                @else
                    <ul class="tasks" style="margin-top:12px">
                        @foreach ($tugas as $t)
                            <li>
                                @if ($t['href'])
                                    <a href="{{ $t['href'] }}">
                                @else
                                    <a>
                                @endif
                                    <span class="n hot">{{ $t['n'] }}</span>
                                    <span><b>{{ $t['title'] }}</b><small>{{ $t['hint'] }}</small></span>
                                    <svg class="ic go" aria-hidden="true"><use href="#i-chev"/></svg>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </section>

            <section class="card">
                <div class="card-h">
                    <h3>Pendapatan 14 hari terakhir</h3>
                    <div class="legend"><span>Titip</span><span class="t">Preloved</span></div>
                </div>
                <div class="chart" role="img" aria-label="Grafik batang pendapatan titip dan preloved 14 hari terakhir">
                    @foreach ($chart as $c)
                        <div class="c" title="{{ $c['label'] }}: titip {{ $rp($c['titip']) }}, preloved {{ $rp($c['preloved']) }}">
                            <div class="pair">
                                <i style="height: {{ round($c['titip'] / $chartMax * 100) }}%"></i>
                                <i class="t" style="height: {{ round($c['preloved'] / $chartMax * 100) }}%"></i>
                            </div>
                            <small>{{ $c['label'] }}</small>
                        </div>
                    @endforeach
                </div>
            </section>
        </div>

        <aside>
            <section class="card">
                <div class="card-h">
                    <h3>Kapasitas ruang</h3>
                    @if (Route::has('admin.ruang-titip'))<a class="btn-text" href="{{ route('admin.ruang-titip') }}">Kelola</a>@endif
                </div>
                @forelse ($rooms as $room)
                    @php $pct = $room->capacity_pct; @endphp
                    <div class="room-row" style="margin-bottom:14px">
                        <div class="top-r"><b>{{ $room->name }}</b><span class="num muted">{{ $room->capacity_used }} / {{ $room->capacity_total }} setara S</span></div>
                        <div class="meter {{ $pct >= 90 ? 'full' : ($pct >= 70 ? 'hi' : '') }}"><i style="width: {{ min(100, $pct) }}%"></i></div>
                    </div>
                @empty
                    <div class="empty">Belum ada ruang terdaftar.</div>
                @endforelse
            </section>

            <section class="card flush">
                <div class="card-h"><h3>Aktivitas terbaru</h3></div>
                @if ($activity->isEmpty())
                    <div class="empty">Belum ada aktivitas.</div>
                @else
                    <ul class="act-list" style="margin-top:12px">
                        @foreach ($activity as $a)
                            <li>
                                @if ($a['href'])<a href="{{ $a['href'] }}">@else<span>@endif
                                    <b>{{ $a['text'] }}</b>
                                    <small>{{ $a['sub'] }}</small>
                                @if ($a['href'])</a>@else</span>@endif
                                <small class="num" style="white-space:nowrap">{{ optional($a['at'])->diffForHumans() }}</small>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </section>
        </aside>
    </div>
</div>
@endsection
