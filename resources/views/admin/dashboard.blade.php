@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
@php
    $badgeTone = [
        'Menunggu Pembayaran' => 'o',
        'Penjadwalan Penjemputan' => 'y',
        'Proses Pengembalian' => 'r',
    ];
    $maxPesanan = max(1, max(array_column($trenPesanan, 'pesanan')));
    $totalTren = array_sum(array_column($trenPesanan, 'pesanan'));
@endphp

<div class="ph">
  <div><h1>Halo, {{ Str::of(Auth::user()->name ?? 'Admin')->before(' ') }}</h1><p>Ringkasan operasional RuangTitip hari ini.</p></div>
  <a class="btn btn-ghost" href="{{ route('admin.ruang-titip') }}">{!! \App\Support\Icons::svg('clipboard', 'sm') !!} Lihat semua pesanan</a>
</div>

<div class="stats">
  <div class="card stat">
    <div class="stat-top">
      <span class="stat-ico">{!! \App\Support\Icons::svg('wallet') !!}</span>
      <span class="pill {{ $pendapatanGrowth >= 0 ? 'g' : 'r' }}">{{ $pendapatanGrowth >= 0 ? '+' : '' }}{{ $pendapatanGrowth }}% dari bulan lalu</span>
    </div>
    <small>Pendapatan bulan ini</small>
    <strong>Rp {{ number_format($pendapatan, 0, ',', '.') }}</strong>
    <span class="sub">Titip, preloved, dan packing</span>
  </div>
  <div class="card stat">
    <div class="stat-top"><span class="stat-ico s">{!! \App\Support\Icons::svg('clipboard') !!}</span></div>
    <small>Transaksi aktif</small>
    <strong>{{ $transaksiAktif }}</strong>
    <span class="sub">Pesanan yang sedang berjalan</span>
  </div>
  <div class="card stat">
    <div class="stat-top"><span class="stat-ico g">{!! \App\Support\Icons::svg('warehouse') !!}</span></div>
    <small>Kapasitas gudang terpakai</small>
    <strong>{{ $kapasitasGudang }}%</strong>
    <span class="sub"><span class="bar {{ $kapasitasGudang > 80 ? 'r' : ($kapasitasGudang > 50 ? 'o' : '') }}" style="margin:6px 0 4px"><i style="width:{{ $kapasitasGudang }}%"></i></span>Dari seluruh ruangan aktif</span>
  </div>
</div>

<div class="row r-2-1" style="margin-bottom:20px">
  <section class="card">
    <div class="card-h">
      <div><h2>Tugas prioritas hari ini</h2><p>Pesanan Ruang Titip yang butuh tindakan segera</p></div>
      <span class="pill o">{{ count($tugasPrioritas) }} tugas</span>
    </div>
    @if (count($tugasPrioritas))
      <ul class="tasks">
        @foreach ($tugasPrioritas as $task)
          <li>
            <span class="stat-ico {{ ($badgeTone[$task['badge']] ?? 'o') === 'r' ? 'r' : (($badgeTone[$task['badge']] ?? 'o') === 'g' ? 'g' : '') }}">{!! \App\Support\Icons::svg('clock') !!}</span>
            <div class="t-main">
              <b><span class="mono">{{ $task['id'] }}</span> &middot; {{ $task['badge'] }}</b>
              <small>{{ $task['customer'] }} &middot; {{ $task['wa'] }}</small>
            </div>
            <span class="t-time">{!! \App\Support\Icons::svg('calendar', 'sm') !!}{{ $task['deadline'] }}</span>
            <a class="btn btn-sm" href="{{ $task['href'] }}">Lihat</a>
          </li>
        @endforeach
      </ul>
    @else
      <div class="empty"><img class="ruru" src="{{ asset('assets/ruru.webp') }}" alt=""><b>Semua tugas beres!</b><p>Tidak ada pesanan Ruang Titip yang butuh tindakan mendesak saat ini.</p></div>
    @endif
  </section>

  <div class="stack">
    <section class="card">
      <div class="card-h"><div><h2>Stok menipis</h2><p>Toko Packing</p></div><a class="link-btn" href="{{ route('admin.packing.index') }}">Kelola {!! \App\Support\Icons::svg('chevR', 'sm') !!}</a></div>
      <div class="card-b" style="display:grid;gap:14px">
        @php $lowStockItems = \App\Models\PackingProduct::whereColumn('stock', '<=', 'low_threshold')->orderBy('stock')->limit(4)->get(); @endphp
        @forelse ($lowStockItems as $lp)
          <div class="mini-row">
            <span class="thumb">{!! \App\Support\Icons::svg('box') !!}</span>
            <div><b>{{ $lp->name }}</b><small>Ambang {{ $lp->low_threshold }} {{ $lp->unit }}</small></div>
            <span class="pill {{ $lp->stock <= 0 ? 'r' : 'y' }}">{{ $lp->stock <= 0 ? 'Habis' : 'Sisa ' . $lp->stock }}</span>
          </div>
        @empty
          <p style="color:var(--muted);font-size:13px">Semua stok packing aman.</p>
        @endforelse
      </div>
    </section>
    <section class="card">
      <div class="card-h"><div><h2>Kapasitas per gudang</h2><p>Slot terisi saat ini</p></div></div>
      <div class="card-b" style="display:grid;gap:16px">
        @php $rooms = \App\Models\StorageRoom::orderBy('id')->limit(4)->get(); @endphp
        @forelse ($rooms as $room)
          @php $pct = $room->capacity_total > 0 ? min(100, round($room->capacity_used / $room->capacity_total * 100)) : 0; @endphp
          <div class="cap">
            <div><b>{{ $room->name }}</b><span>{{ $room->capacity_used }} / {{ $room->capacity_total }}</span></div>
            <span class="bar {{ $pct > 80 ? 'r' : ($pct > 60 ? 'o' : '') }}"><i style="width:{{ $pct }}%"></i></span>
          </div>
        @empty
          <p style="color:var(--muted);font-size:13px">Belum ada ruangan terdaftar.</p>
        @endforelse
      </div>
    </section>
  </div>
</div>

<section class="card">
  <div class="card-h"><div><h2>Pesanan masuk</h2><p>7 hari terakhir, semua layanan</p></div><span class="pill n">Satuan: pesanan</span></div>
  <div class="card-b">
    <div class="chart">
      @php
        $W = 640; $H = 260; $L = 34; $B = 30; $T = 14;
        $step = ($W - $L) / count($trenPesanan); $bw = 44;
        $gridMax = (int) (ceil($maxPesanan / 4) * 4) ?: 4;
      @endphp
      <svg viewBox="0 0 {{ $W }} {{ $H }}" role="img" aria-label="Pesanan masuk 7 hari terakhir">
        @for ($g = 0; $g <= $gridMax; $g += max(1, intdiv($gridMax, 4)))
          @php $y = $H - $B - ($g / $gridMax) * ($H - $B - $T); @endphp
          <line x1="{{ $L }}" x2="{{ $W }}" y1="{{ $y }}" y2="{{ $y }}" stroke="#DDD5C4" stroke-width="1" @if($g) stroke-dasharray="3 4" @endif />
          <text x="{{ $L - 8 }}" y="{{ $y + 4 }}" text-anchor="end" font-size="12" fill="#5C574D">{{ $g }}</text>
        @endfor
        @foreach ($trenPesanan as $i => $d)
          @php
            $x = $L + $step * $i + ($step - $bw) / 2;
            $h = $gridMax > 0 ? ($d['pesanan'] / $gridMax) * ($H - $B - $T) : 0;
            $y = $H - $B - $h;
            $last = $i === count($trenPesanan) - 1;
          @endphp
          <g class="bar-g" tabindex="0" data-day="{{ $d['day'] }}" data-n="{{ $d['pesanan'] }}">
            <rect x="{{ $L + $step * $i }}" y="{{ $T }}" width="{{ $step }}" height="{{ $H - $B - $T }}" fill="transparent"/>
            <path d="M{{ $x }} {{ $H - $B }}V{{ $y + 4 }}q0-4 4-4h{{ $bw - 8 }}q4 0 4 4V{{ $H - $B }}z" fill="{{ $last ? '#B4531D' : '#E8A677' }}"/>
            <text x="{{ $x + $bw / 2 }}" y="{{ $H - 10 }}" text-anchor="middle" font-size="12" fill="#5C574D" font-weight="{{ $last ? 700 : 500 }}">{{ $last ? 'Hari ini' : $d['day'] }}</text>
          </g>
        @endforeach
        <line x1="{{ $L }}" x2="{{ $W }}" y1="{{ $H - $B }}" y2="{{ $H - $B }}" stroke="#1C1B18" stroke-width="1.5"/>
      </svg>
      <div class="ctip" hidden></div>
    </div>
  </div>
  <div class="kpis">
    <div><small>Total 7 hari</small><b>{{ $totalTren }} pesanan</b></div>
    <div><small>Rata-rata per hari</small><b>{{ round($totalTren / max(1, count($trenPesanan)), 1) }} pesanan</b></div>
    <div><small>Puncak hari ini</small><b>{{ max(array_column($trenPesanan, 'pesanan')) }} pesanan</b></div>
  </div>
</section>

@push('scripts')
<script>
(function () {
  var el = document.querySelector('.chart'); if (!el) return;
  var tip = el.querySelector('.ctip');
  function show(gEl) {
    var r = gEl.getBoundingClientRect(), c = el.getBoundingClientRect();
    tip.innerHTML = '<small>' + gEl.dataset.day + '</small><b>' + gEl.dataset.n + ' pesanan</b>';
    tip.hidden = false;
    tip.style.left = (r.left - c.left + r.width / 2) + 'px';
    tip.style.top = (r.top - c.top) + 'px';
  }
  el.querySelectorAll('.bar-g').forEach(function (gEl) {
    gEl.addEventListener('mouseenter', function () { show(gEl); });
    gEl.addEventListener('focus', function () { show(gEl); });
    gEl.addEventListener('mouseleave', function () { tip.hidden = true; });
    gEl.addEventListener('blur', function () { tip.hidden = true; });
  });
})();
</script>
@endpush
@endsection
