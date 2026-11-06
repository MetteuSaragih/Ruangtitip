{{-- Ringkasan "Pesananmu", sisi kanan tiap langkah wizard titip.
     Pakai: @include('dashboard.ruang-titip._summary', ['storage' => $storage, 's' => $s, 'calc' => $calc])
     Membutuhkan function rp() sudah dideklarasikan di view pemanggil.
     ID s-date, s-items, s-log, s-lines, s-total dipakai skrip halaman. --}}
@php
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
                    @if ($calc['totalItems'] > 0)
                        @foreach ($calc['quote'] as $row){{ $row['qty'] }} &times; {{ $row['label'] }}@if (! $loop->last)<br>@endif @endforeach
                        <small>{{ $calc['days'] }} hari</small>
                    @else Belum ada barang @endif
                </b>
            </div>
            <div><span>Pengantaran</span><b id="s-log">Antar sendiri ke gudang</b></div>
            <span id="s-lines" style="display:contents">
                @foreach ($calc['quote'] as $row)
                    <div><span>{{ $row['qty'] }} &times; {{ $row['label'] }}<small>@foreach ($row['lines'] as $ln){{ $ln['qty'] }} &times; paket {{ $ln['label'] }}@if (! $loop->last), @endif @endforeach</small></span><b>{{ rp($row['subtotal']) }}</b></div>
                @endforeach
                @if ($calc['totalItems'] > 0)
                    <div><span>Biaya layanan</span><b>{{ rp($calc['platform_fee']) }}</b></div>
                @endif
            </span>
            <div class="tot"><span>Estimasi total</span><b id="s-total">{{ $calc['totalItems'] > 0 ? rp($calc['total']) : 'Rp0' }}</b></div>
        </div>
        <p class="help mt" id="s-tip">Harga memakai paket termurah untuk lama titipmu. Bingung pilih ukuran? Chat tim Ruru dan kirim foto barangmu.</p>
    </section>
</aside>
