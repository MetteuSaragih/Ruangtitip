@extends('layouts.rt')

@section('title', 'Jual Barang')

@php
    if (! function_exists('rp')) {
        function rp($n) { return 'Rp' . number_format($n, 0, ',', '.'); }
    }
    $titip = $s->source === 'titip';
    $statuses = \App\Models\PrelovedSubmission::STATUS;
    $stMap = ['seleksi' => 1, 'tolak' => 1, 'jadwal' => 2, 'antar' => 2, 'periksa' => 3, 'tidak' => 3, 'tawar' => 4, 'batal' => 4, 'titip' => 5, 'sepakat' => 5, 'dibeli' => 6];
    $st = $stMap[$s->status] ?? 1;
    $steps = ['Diajukan', 'Seleksi', $titip ? 'Jadwal periksa' : 'Antar', 'Pemeriksaan', 'Penawaran', 'Dibayar'];
    $fT = fn ($t) => \Illuminate\Support\Carbon::parse($t)->locale('id')->translatedFormat('j M Y, H.i');
    $gudang = 'Gudang Ruru, Lowokwaru, Malang';
    $slotLabel = $s->schedule_slot ? ($slots[$s->schedule_slot] ?? '-') : '-';
    $schedDate = $s->schedule_date ? $s->schedule_date->locale('id')->translatedFormat('l, j F Y') : '-';
    $tomorrow = now()->addDay()->toDateString();
    $maxDate = now()->addDays(14)->toDateString();
    $logs = array_reverse($s->log ?? []);
    $conds = \App\Models\PrelovedSubmission::CONDITIONS;
@endphp

@section('content')
<div class="mp">
<div class="stack">
    @include('partials.mp-head', [
        'crumbs' => [['Jual Barang', route('preloved.cara-jual')], [$s->code, null]],
        'title' => $s->name,
        'sub' => 'Pengajuan ' . $s->code . ', dikirim ' . $fT($s->created_at) . '.',
        'acts' => '<span class="badge b-' . ($statuses[$s->status][1] ?? 'mute') . '"><i></i>' . e($statuses[$s->status][0] ?? $s->status) . '</span>',
    ])

    @if ($s->status === 'seleksi')
        <div class="alert a-info"><svg class="ic" aria-hidden="true"><use href="#i-clock"/></svg><div><b>Menunggu seleksi admin</b><p>Admin memeriksa kelayakan awal dan ketersediaan kapasitas gudang. Kamu akan diberi tahu hasilnya.</p></div></div>
    @elseif ($s->status === 'tolak')
        <div class="alert a-err"><svg class="ic" aria-hidden="true"><use href="#i-x"/></svg><div><b>Pengajuan ditolak</b><p>{{ $s->reason }}</p></div></div>
    @elseif ($s->status === 'jadwal')
        <section class="card stack">
            <div class="alert a-ok"><svg class="ic" aria-hidden="true"><use href="#i-check"/></svg><div><b>Pengajuan lolos seleksi awal</b><p>Persetujuan ini belum kepastian pembelian. Harga ditawarkan setelah barang diperiksa bersamamu.</p></div></div>
            <h3 id="sjT">{{ $titip ? 'Pilih jadwal datang untuk pemeriksaan' : 'Pilih jadwal antar barang' }}</h3>
            <p class="help">{{ $titip ? 'Barang sudah di gudang. Kamu datang untuk ikut memeriksa.' : 'Kamu mengatur pengantaran sendiri ke ' . $gudang . '.' }}</p>
            <form method="POST" action="{{ route('sell.schedule', $s->code) }}" class="stack tight">
                @csrf
                <div class="field">
                    <label for="sjDate">Tanggal</label>
                    <input class="input" type="date" id="sjDate" name="date" value="{{ old('date', $tomorrow) }}" min="{{ $tomorrow }}" max="{{ $maxDate }}" style="max-width:240px" @error('date') aria-invalid="true" @enderror>
                    @error('date')<p class="msg">{{ $message }}</p>@enderror
                </div>
                <div class="slots" role="radiogroup" aria-labelledby="sjT">
                    @foreach ($slots as $id => $label)
                        <div class="slot"><input type="radio" name="slot" id="sj-{{ $id }}" value="{{ $id }}" {{ old('slot') === $id ? 'checked' : '' }}><label for="sj-{{ $id }}"><span>{{ $label }}</span></label></div>
                    @endforeach
                </div>
                @error('slot')<p class="msg">{{ $message }}</p>@enderror
                <p class="help">Gudang tutup di hari Minggu. Hari Sabtu hanya jam pagi.</p>
                <button class="btn btn-sun" type="submit" style="justify-self:start">Simpan jadwal</button>
            </form>
        </section>
    @elseif ($s->status === 'antar')
        <div class="alert a-info"><svg class="ic" aria-hidden="true"><use href="#i-clock"/></svg><div><b>{{ $titip ? 'Datang' : 'Antar barang' }} {{ $schedDate }}, pukul {{ $slotLabel }}</b><p>Tunjukkan nomor pengajuan {{ $s->code }}. Petugas memeriksa kondisi, kelengkapan, dan fungsi dasar bersamamu.</p></div></div>
    @elseif ($s->status === 'periksa')
        <div class="alert a-info"><svg class="ic" aria-hidden="true"><use href="#i-info"/></svg><div><b>Barang sedang diperiksa</b><p>Petugas mencocokkan barang dengan pengajuan dan memeriksa kondisi fisik, kelengkapan, dan fungsi dasar.</p></div></div>
    @elseif ($s->status === 'tidak')
        <div class="alert a-err"><svg class="ic" aria-hidden="true"><use href="#i-x"/></svg><div><b>Barang tidak dibeli</b><p>{{ $s->reason }} {{ $titip ? 'Status titipan barang ini tetap berlaku.' : 'Barang tetap milikmu dan bisa dibawa pulang.' }}</p></div></div>
    @elseif ($s->status === 'tawar')
        <section class="offer">
            <b>Penawaran harga beli</b>
            <p class="big-price">{{ rp($s->offer) }}</p>
            <p>Dihitung dari estimasi harga jual kembali, kondisi, dan biaya penanganan barang ini.</p>
            @if ($titip)<p>Sisa tagihan titipan {{ $s->titip_ref }} perlu dilunasi dulu sebelum barang menjadi stok preloved.</p>@endif
            <form method="POST" action="{{ route('sell.offer', $s->code) }}" class="stack tight">
                @csrf
                <label class="pick"><input type="checkbox" name="agree" value="1" id="agree"><span>Saya paham setelah pembelian selesai, barang tidak bisa ditarik kembali. Kalau ingin memilikinya lagi, saya membelinya lewat katalog dengan harga Ruang Titip.</span></label>
                @error('agree')<p class="msg">{{ $message }}</p>@enderror
                <div class="nav-btns">
                    <button type="submit" name="decision" value="decline" class="btn btn-ghost">Tolak penawaran</button>
                    <button type="submit" name="decision" value="accept" id="accept" class="btn btn-ink" disabled>Terima {{ rp($s->offer) }}</button>
                </div>
            </form>
        </section>
    @elseif ($s->status === 'titip')
        <div class="alert a-warn"><svg class="ic" aria-hidden="true"><use href="#i-clock"/></svg><div><b>Lunasi tagihan titipan</b><p>Setelah tagihan titipan lunas, admin membayar harga beli ke rekeningmu. Hubungi admin kalau tagihan belum muncul di Pesanan Saya.</p><div class="acts"><a class="btn btn-sun sm" href="{{ route('pesanan.index') }}">Lihat Pesanan Saya</a></div></div></div>
    @elseif ($s->status === 'sepakat')
        <div class="alert a-info"><svg class="ic" aria-hidden="true"><use href="#i-clock"/></svg><div><b>Harga disepakati</b><p>Admin membayar {{ rp($s->offer) }} ke rekening {{ $s->bank_name }} atas nama {{ $s->bank_holder }} dan mencatat bukti serah terima.</p></div></div>
    @elseif ($s->status === 'dibeli')
        <div class="alert a-ok"><svg class="ic" aria-hidden="true"><use href="#i-check"/></svg><div><b>Kamu sudah dibayar {{ rp($s->offer) }}</b><p>Barang kini milik Ruang Titip dan tidak bisa ditarik kembali.</p></div></div>
    @elseif ($s->status === 'batal')
        <div class="alert a-mute"><svg class="ic" aria-hidden="true"><use href="#i-info"/></svg><div><b>Pembelian batal</b><p>Kamu menolak penawaran. Barang tetap milikmu{{ $titip ? ' dan status titipannya tetap berlaku' : ', silakan bawa pulang' }}.</p></div></div>
    @endif

    <section class="card">
        <ol class="track" style="--n:{{ count($steps) }}">
            @foreach ($steps as $i => $t)
                <li class="{{ $i < $st ? 'done' : ($i === $st ? 'cur' : '') }}" @if ($i === $st) aria-current="step" @endif><span class="d">@if ($i < $st)<svg class="ic" aria-hidden="true"><use href="#i-check"/></svg>@endif</span>{{ $t }}</li>
            @endforeach
        </ol>
    </section>

    <div class="two">
        <div class="stack">
            <section class="card">
                <h3>Riwayat</h3>
                <ol class="act">
                    @foreach ($logs as $e)
                        <li><span class="dt {{ $e['a'] }}"></span><div><b>{{ $e['x'] }}</b><small>{{ $fT($e['t']) }}, {{ $e['a'] }}</small></div></li>
                    @endforeach
                </ol>
            </section>
        </div>
        <aside class="aside">
            <section class="card">
                <h3>Data pengajuan</h3>
                <div class="rows">
                    <div><span>Asal</span><b>{{ $titip ? 'Titipan ' . $s->titip_ref : 'Diantar sendiri' }}</b></div>
                    <div><span>Kategori</span><b>{{ $s->category }}</b></div>
                    <div><span>Kondisi</span><b>{{ $conds[$s->condition] ?? $s->condition }}</b></div>
                    <div><span>Kelengkapan</span><b>{{ $s->completeness }}</b></div>
                    <div><span>Kekurangan</span><b>{{ $s->flaws }}</b></div>
                    <div><span>Foto</span><b>{{ count($s->photos ?? []) }} foto</b></div>
                    <div><span>Rekening</span><b>{{ $s->bank_name }} ••••{{ substr($s->bank_number, -4) }}<small>{{ $s->bank_holder }}</small></b></div>
                </div>
            </section>
        </aside>
    </div>
</div>
</div>
@endsection

@push('scripts')
<script>
(function () {
    var agree = document.getElementById('agree'), accept = document.getElementById('accept');
    if (agree && accept) agree.addEventListener('change', function () { accept.disabled = !agree.checked; });
})();
</script>
@endpush
