{{-- Ringkasan pesanan, tampil di sisi kanan tiap langkah wizard titip.
     Pakai: @include('dashboard.ruang-titip._summary', ['storage' => $storage, 's' => $s, 'calc' => $calc])
     Membutuhkan function rp() sudah dideklarasikan di view pemanggil. --}}
@php
    $sumLogisticLabel = match ($s['logistic'] ?? null) {
        'self' => 'Antar sendiri',
        'rutip' => 'Packing + Anjem RuTip',
        'instant' => isset($courier) && $courier ? trim($courier->name . ' ' . ($courier->service ?? '')) : 'Kurir instan',
        default => null,
    };
@endphp
@php
    $sumDateText = isset($s['date_start'], $s['date_end'])
        ? \Illuminate\Support\Carbon::parse($s['date_start'])->translatedFormat('d M Y') . ' – ' . \Illuminate\Support\Carbon::parse($s['date_end'])->translatedFormat('d M Y') . ' (' . $calc['months'] . ' bln)'
        : null;
@endphp
<aside class="summary" aria-label="Ringkasan pesanan">
    <div class="wh-row">
        <span class="ph" aria-hidden="true">
            @if ($storage && $storage->primary_photo)
                <img src="{{ asset('storage/'.$storage->primary_photo) }}" alt="{{ $storage->name }}">
            @else
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="var(--tape-dark)" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 10 12 4l9 6v10H3z"/><path d="M8 20v-6h8v6"/></svg>
            @endif
        </span>
        <div><strong>{{ $storage->name ?? '-' }}</strong><span>{{ $storage->location ?? '' }}</span></div>
        <a href="{{ route('ruang-titip.index') }}">Ganti</a>
    </div>
    <div class="sec">
        <h3>Tanggal</h3>
        <p class="dim" id="s-date">{{ $sumDateText ?? 'Belum dipilih' }}</p>
    </div>
    <div class="sec">
        <h3>Barang</h3>
        <ul class="items" id="s-items">
            @if ($calc['totalItems'] > 0)
                <li><span>{{ $calc['totalItems'] }} item dipilih</span><span>{{ $calc['months'] }} bln</span></li>
            @else
                <li class="dim">Belum ada barang</li>
            @endif
        </ul>
    </div>
    <div class="sec">
        <h3>Pengiriman</h3>
        <p class="dim" id="s-log">{{ $sumLogisticLabel ?? 'Belum dipilih' }}</p>
    </div>
    <div class="foot">
        <div class="lines" id="s-lines">
            @if ($calc['itemSubtotal'] > 0)
                <div class="line"><span>Subtotal barang</span><span>{{ rp($calc['itemSubtotal']) }}</span></div>
            @endif
            @if ($calc['courierCost'] > 0)
                <div class="line"><span>Kurir/anjem</span><span>{{ rp($calc['courierCost']) }}</span></div>
            @endif
            @if ($calc['totalItems'] > 0)
                <div class="line"><span>Biaya layanan</span><span>{{ rp($calc['platform_fee']) }}</span></div>
            @endif
        </div>
        <div class="total"><span style="font-weight:700">Total</span><strong id="s-total">{{ $calc['totalItems'] > 0 ? rp($calc['total']) : 'Rp0' }}</strong></div>
        <div class="ruru-tip">
            <img src="{{ asset('assets/ruru/ruru-wajah-happy.webp') }}" alt="">
            <p id="s-tip">Tip dari Ruru: bingung butuh berapa kardus? Chat kami dan kirim foto barangmu.</p>
        </div>
    </div>
</aside>
