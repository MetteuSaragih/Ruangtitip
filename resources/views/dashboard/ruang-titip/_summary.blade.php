{{-- Ringkasan "Pesananmu", sisi kanan tiap langkah wizard titip.
     Pakai: @include('dashboard.ruang-titip._summary', ['storage' => $storage, 's' => $s, 'calc' => $calc])
     Membutuhkan function rp() sudah dideklarasikan di view pemanggil.
     ID s-date, s-items, s-log, s-lines, s-total dipakai skrip halaman. --}}
@php
    $sumLogisticLabel = match ($s['logistic'] ?? null) {
        'self' => 'Antar sendiri',
        'rutip' => 'Packing + Anjem RuTip',
        'instant' => isset($courier) && $courier ? trim($courier->name . ' ' . ($courier->service ?? '')) : 'Kurir instan',
        default => null,
    };
    $sumDateText = isset($s['date_start'], $s['date_end'])
        ? \Illuminate\Support\Carbon::parse($s['date_start'])->translatedFormat('j M Y') . ' sampai ' . \Illuminate\Support\Carbon::parse($s['date_end'])->translatedFormat('j M Y') . ' (' . $calc['days'] . ' hari)'
        : null;
@endphp
<aside class="aside" aria-label="Ringkasan pesanan">
    <section class="card">
        <h3>Pesananmu</h3>
        <div class="rows">
            <div><span>Gudang</span><b>{{ $storage->name ?? '-' }}<small>{{ $storage->location ?? '' }} &middot; <a href="{{ route('ruang-titip.index') }}">Ganti</a></small></b></div>
            <div><span>Periode</span><b id="s-date">{{ $sumDateText ?? 'Belum dipilih' }}</b></div>
            <div><span>Barang</span>
                <b id="s-items">
                    @if ($calc['totalItems'] > 0){{ $calc['totalItems'] }} item dipilih<small>{{ $calc['days'] }} hari</small>@else Belum ada barang @endif
                </b>
            </div>
            <div><span>Pengiriman</span><b id="s-log">{{ $sumLogisticLabel ?? 'Belum dipilih' }}</b></div>
            <span id="s-lines" style="display:contents">
                @if ($calc['itemSubtotal'] > 0)
                    <div><span>Subtotal barang</span><b>{{ rp($calc['itemSubtotal']) }}</b></div>
                @endif
                @if ($calc['courierCost'] > 0)
                    <div><span>Kurir/anjem</span><b>{{ rp($calc['courierCost']) }}</b></div>
                @endif
                @if ($calc['totalItems'] > 0)
                    <div><span>Biaya layanan</span><b>{{ rp($calc['platform_fee']) }}</b></div>
                @endif
            </span>
            <div class="tot"><span>Estimasi total</span><b id="s-total">{{ $calc['totalItems'] > 0 ? rp($calc['total']) : 'Rp0' }}</b></div>
        </div>
        <p class="help mt" id="s-tip">Bingung pilih ukuran? Chat tim Ruru dan kirim foto barangmu.</p>
    </section>
</aside>
