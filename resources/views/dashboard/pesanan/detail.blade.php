@extends('layouts.rt')

@section('title', 'Detail Pesanan')

@php
    if (! function_exists('rp')) {
        function rp($n) { return 'Rp' . number_format($n, 0, ',', '.'); }
    }
    [$statusLabel, $tone, $stage] = $order->displayStatus();
    $steps = ['Pesanan dibuat', 'Pembayaran awal', 'Pengantaran', 'Pemeriksaan', 'Disimpan', 'Selesai'];
    $awaitingPay = $order->status === 'menunggu_pembayaran';
    $inStorage = $order->status === 'dalam_gudang';
    $left = $order->date_end ? (int) ceil(now()->startOfDay()->diffInDays($order->date_end->copy()->startOfDay(), false)) : null;
    $isPaid = ! $awaitingPay;
    $wa = 'https://wa.me/6285121091134';
    $waText = fn ($t) => $wa . '?text=' . urlencode($t . ' ' . $order->code());
    $storageName = $order->storage->name ?? 'Penitipan Barang';
    $days = $order->date_start && $order->date_end ? $order->date_start->diffInDays($order->date_end) + 1 : null;
    $payMethod = $order->tripay_payment_method ?: $order->payment_method;
    $itemLabel = function ($k) {
        foreach (\App\Models\StorageRoom::SIZE_META as $type => $sizes) {
            if (isset($sizes[$k])) {
                return ucfirst($type) . ' ' . $sizes[$k][0];
            }
        }

        return $k === 'dimensi_lain' ? 'Dimensi lain' : ucfirst(str_replace('_', ' ', $k));
    };
    $requests = $requests ?? collect();
    $addresses = $addresses ?? collect();
    $hasOpen = fn ($t) => $requests->contains(fn ($r) => $r->type === $t && $r->isOpen());
    $reqTone = ['menunggu' => 'warn', 'diproses' => 'info', 'selesai' => 'ok', 'ditolak' => 'err'];
@endphp

@section('content')
@include('dashboard.ruang-titip._icons')
<div class="ttp">
<div class="stack">
    <nav class="crumbs" aria-label="Breadcrumb"><a href="{{ route('pesanan.index') }}">Pesanan Saya</a><span aria-hidden="true">/</span><span aria-current="page">{{ $order->code() }}</span></nav>

    <div class="ph">
        <div>
            <h2 class="pt" tabindex="-1">Pesanan {{ $order->code() }}</h2>
            <p class="ps">{{ $storageName }}. Dibuat {{ $order->created_at->translatedFormat('j M Y') }}, {{ $order->created_at->format('H.i') }}.</p>
        </div>
        <div class="ph-acts">
            <span class="badge b-{{ $tone }}"><i></i>{{ $statusLabel }}</span>
            @if ($inStorage)
                <button type="button" class="btn btn-sun" data-open="dlgPerpanjang" @disabled($hasOpen('perpanjang'))>Tambah periode</button>
                <button type="button" class="btn btn-ink" data-open="dlgKirim" @disabled($hasOpen('kirim'))>Kirim barang</button>
                <button type="button" class="btn btn-ghost" data-open="dlgAmbil" @disabled($hasOpen('ambil'))>Ambil di gudang</button>
            @elseif ($awaitingPay && $order->tripay_checkout_url)
                <a class="btn btn-sun" href="{{ $order->tripay_checkout_url }}">Bayar {{ rp($order->total) }}</a>
            @endif
        </div>
    </div>

    @if (session('error') || $errors->any())
        <div class="alert a-err"><svg class="ic" aria-hidden="true"><use href="#i-warn"/></svg><div><b>{{ session('error') ?? $errors->first() }}</b></div></div>
    @endif
    @if (session('success'))
        <div class="alert a-ok"><svg class="ic" aria-hidden="true"><use href="#i-check"/></svg><div><b>{{ session('success') }}</b></div></div>
    @endif

    @if ($awaitingPay)
        <div class="alert a-warn">
            <svg class="ic" aria-hidden="true"><use href="#i-clock"/></svg>
            <div>
                <b>Bayar tagihan pertama {{ rp($order->total) }}</b>
                <p>Kalau lewat batas bayar, pesanan batal dan kapasitas dilepas.</p>
                @if ($order->tripay_checkout_url)
                    <div class="acts"><a class="btn btn-primary sm" href="{{ $order->tripay_checkout_url }}">Bayar sekarang</a></div>
                @endif
            </div>
        </div>
    @elseif ($order->status === 'penjadwalan_penjemputan')
        <div class="alert a-ok">
            <svg class="ic" aria-hidden="true"><use href="#i-check"/></svg>
            <div><b>Pesanan terkonfirmasi</b><p>Antar barang yang sudah dikemas ke {{ $storageName }}. Tunjukkan nomor pesanan {{ $order->code() }} ke petugas.</p></div>
        </div>
    @elseif ($inStorage && $left !== null && $left < 0)
        <div class="alert a-err">
            <svg class="ic" aria-hidden="true"><use href="#i-warn"/></svg>
            <div>
                <b>Masa titip sudah berakhir</b>
                <p>Periode berakhir {{ $order->date_end->translatedFormat('l, j F Y') }}. Hubungi admin untuk penyelesaian.</p>
                <div class="acts"><a class="btn btn-ghost sm" href="{{ $waText('Halo RuangTitip, masa titip habis untuk pesanan') }}" target="_blank" rel="noopener">Hubungi admin</a></div>
            </div>
        </div>
    @elseif ($inStorage && $left !== null && $left <= 3)
        <div class="alert a-warn">
            <svg class="ic" aria-hidden="true"><use href="#i-clock"/></svg>
            <div>
                <b>{{ $left === 0 ? 'Masa titip berakhir hari ini' : 'Masa titip berakhir ' . $left . ' hari lagi' }}</b>
                <p>Periode sampai {{ $order->date_end->translatedFormat('l, j F Y') }}. Perpanjang atau ajukan pengambilan.</p>
                <div class="acts">
                    <button type="button" class="btn btn-primary sm" data-open="dlgPerpanjang" @disabled($hasOpen('perpanjang'))>Tambah periode</button>
                    <button type="button" class="btn btn-ghost sm" data-open="dlgKirim" @disabled($hasOpen('kirim'))>Kirim barang</button>
                    <button type="button" class="btn btn-ghost sm" data-open="dlgAmbil" @disabled($hasOpen('ambil'))>Ambil di gudang</button>
                </div>
            </div>
        </div>
    @elseif ($order->status === 'selesai')
        <div class="alert a-ok">
            <svg class="ic" aria-hidden="true"><use href="#i-check"/></svg>
            <div><b>Pesanan selesai</b><p>Semua barang sudah keluar dan pencatatan ditutup.</p></div>
        </div>
    @endif

    <div class="two">
        <div class="stack">
            <section class="card">
                <ol class="track" style="--n:{{ count($steps) }}">
                    @foreach ($steps as $i => $t)
                        <li class="{{ $i < $stage ? 'done' : ($i === $stage ? 'cur' : '') }}" @if ($i === $stage) aria-current="step" @endif>
                            <span class="d">@if ($i < $stage)<svg class="ic" aria-hidden="true"><use href="#i-check"/></svg>@endif</span>{{ $t }}
                        </li>
                    @endforeach
                </ol>
            </section>

            <section class="card">
                <h3>Barang</h3>
                <div class="tbl-wrap">
                    <table class="tbl">
                        <thead><tr><th>Ukuran</th><th>Kategori</th><th>Jumlah</th></tr></thead>
                        <tbody>
                            @forelse (($order->items ?? []) as $name => $qty)
                                @if ((int) $qty > 0)
                                    <tr><td><b>{{ $itemLabel($name) }}</b></td><td>{{ \Illuminate\Support\Str::before($itemLabel($name), ' ') }}</td><td>{{ (int) $qty }}</td></tr>
                                @endif
                            @empty
                                <tr><td colspan="3">Belum ada data barang.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt">
                    <div class="alert a-mute">
                        <svg class="ic" aria-hidden="true"><use href="#i-info"/></svg>
                        <div><b>Kode dan foto belum tersedia</b><p>Kode identifikasi, segel, lokasi, dan foto kondisi awal muncul setelah barang diterima dan diperiksa petugas.</p></div>
                    </div>
                </div>
                @if ($order->proof_photo)
                    <h4 class="sub">Bukti penitipan</h4>
                    <img src="{{ asset('storage/' . $order->proof_photo) }}" alt="Bukti penitipan {{ $order->code() }}" style="border-radius:16px;width:100%;max-width:420px">
                @endif
                <p class="help mt">Isi kemasan tidak dibuka. Foto hanya menunjukkan kondisi luar.</p>
            </section>

            <section class="card">
                <h3>Riwayat aktivitas</h3>
                <ol class="act">
                    @if ($isPaid)
                        <li><span class="dt Sistem"></span><div><b>Pembayaran pertama {{ rp($order->total) }} terverifikasi lewat Tripay{{ $payMethod ? ' (' . $payMethod . ')' : '' }}. Pesanan dan jadwal antar terkonfirmasi.</b><small>{{ $order->updated_at->translatedFormat('j M Y') }}, {{ $order->updated_at->format('H.i') }}, Sistem</small></div></li>
                    @endif
                    <li><span class="dt Pelanggan"></span><div><b>Pesanan dibuat dan Syarat &amp; Ketentuan disetujui.</b><small>{{ $order->created_at->translatedFormat('j M Y') }}, {{ $order->created_at->format('H.i') }}, Pelanggan</small></div></li>
                </ol>
            </section>
        </div>

        <aside class="aside">
            <section class="card">
                <h3>Periode dan jadwal</h3>
                <div class="rows">
                    @if ($order->date_start && $order->date_end)
                        <div><span>Periode</span><b>{{ $order->date_start->translatedFormat('j M Y') }} sampai {{ $order->date_end->translatedFormat('j M Y') }}<small>{{ $days }} hari</small></b></div>
                    @endif
                    @if ($order->pickup_date)
                        <div><span>Jadwal antar</span><b>{{ $order->pickup_date->translatedFormat('j M Y') }}@if ($order->pickup_time)<small>{{ $order->pickup_time }}{{ $order->pickup_time_end ? '–' . $order->pickup_time_end : '' }}</small>@endif</b></div>
                    @endif
                    <div><span>Lokasi</span><b>{{ $storageName }}@if ($order->storage?->address)<small>{{ $order->storage->address }}</small>@endif</b></div>
                </div>
            </section>

            @if ($requests->isNotEmpty())
                <section class="card">
                    <h3>Permintaanmu</h3>
                    <div class="rows">
                        @foreach ($requests as $rq)
                            <div>
                                <span>{{ $rq->typeLabel() }}<small>{{ $rq->created_at->translatedFormat('j M Y') }}</small></span>
                                <b><span class="badge b-{{ $reqTone[$rq->status] ?? 'info' }}"><i></i>{{ $rq->statusLabel() }}</span></b>
                            </div>
                        @endforeach
                    </div>
                </section>
            @endif

            <section class="card">
                <h3>Biaya</h3>
                <div class="rows">
                    <div><span>Biaya awal</span><b>{{ rp($order->item_subtotal) }}</b></div>
                    @if ($order->courier_cost > 0)
                        <div><span>Biaya logistik</span><b>{{ rp($order->courier_cost) }}</b></div>
                    @endif
                    @if ($order->packing_cost > 0)
                        <div><span>Biaya packing</span><b>{{ rp($order->packing_cost) }}</b></div>
                    @endif
                    <div><span>Biaya layanan</span><b>{{ rp($order->platform_fee) }}</b></div>
                    <div><span>Total</span><b>{{ rp($order->total) }}</b></div>
                    <div><span>Sudah dibayar</span><b>{{ rp($isPaid ? $order->total : 0) }}</b></div>
                    <div class="tot"><span>Sisa tagihan</span><b>{{ rp($isPaid ? 0 : $order->total) }}</b></div>
                </div>
                <p class="help mt">Sisa tagihan tiap barang dilunasi dalam 24 jam sebelum barang itu diambil.</p>
            </section>

            <section class="card">
                <h3>Pembayaran tercatat</h3>
                @if ($isPaid)
                    <div class="rows"><div><span>Tagihan pertama<small>{{ $order->updated_at->translatedFormat('j M Y') }}, {{ $order->updated_at->format('H.i') }}</small></span><b>{{ rp($order->total) }}@if ($payMethod)<small>{{ $payMethod }}</small>@endif</b></div></div>
                @else
                    <p class="help">Belum ada pembayaran.</p>
                @endif
            </section>

        </aside>
    </div>

    @if ($inStorage)
        <dialog class="rt-dlg req-dlg" id="dlgPerpanjang" aria-labelledby="dlgPerpanjangT">
            <form method="POST" action="{{ route('pesanan.request.perpanjang', $order) }}" class="stack" style="gap:14px">
                @csrf
                <h3 id="dlgPerpanjangT">Tambah periode titip</h3>
                <p>Periode sekarang berakhir {{ $order->date_end?->translatedFormat('j F Y') ?? '-' }}. Admin akan mengonfirmasi dan menerbitkan tagihan.</p>
                <div class="field">
                    <label for="rqMonths">Tambah berapa bulan</label>
                    <select class="input" id="rqMonths" name="months" required>
                        @for ($m = 1; $m <= 12; $m++)<option value="{{ $m }}">{{ $m }} bulan</option>@endfor
                    </select>
                </div>
                <div class="field">
                    <label for="rqNote1">Catatan (opsional)</label>
                    <textarea class="input" id="rqNote1" name="catatan" rows="2" maxlength="500" style="height:auto;padding:12px"></textarea>
                </div>
                <div class="rt-dlg-acts">
                    <button class="btn btn-ghost" type="button" data-close>Batal</button>
                    <button class="btn btn-primary" type="submit">Kirim permintaan</button>
                </div>
            </form>
        </dialog>

        <dialog class="rt-dlg req-dlg" id="dlgKirim" aria-labelledby="dlgKirimT">
            <form method="POST" action="{{ route('pesanan.request.kirim', $order) }}" class="stack" style="gap:14px">
                @csrf
                <h3 id="dlgKirimT">Kirim barang ke alamatku</h3>
                <p>Admin akan menghitung ongkir dan menerbitkan tagihan sebelum barang dikirim.</p>
                @if ($addresses->isNotEmpty())
                    <div class="field">
                        <label for="rqAddr">Alamat tujuan</label>
                        <select class="input" id="rqAddr" name="address_id" required>
                            @foreach ($addresses as $ad)
                                <option value="{{ $ad->id }}" @selected($ad->is_primary)>{{ ($ad->label ?: 'Alamat') . ': ' . IlluminateSupportStr::limit($ad->address, 60) }}</option>
                            @endforeach
                        </select>
                    </div>
                @else
                    <p class="help">Kamu belum punya alamat tersimpan. <a href="{{ route('akun.alamat', ['baru' => 1]) }}">Tambah alamat dulu</a>.</p>
                @endif
                <div class="field">
                    <label for="rqNote2">Catatan (opsional)</label>
                    <textarea class="input" id="rqNote2" name="catatan" rows="2" maxlength="500" style="height:auto;padding:12px"></textarea>
                </div>
                <div class="rt-dlg-acts">
                    <button class="btn btn-ghost" type="button" data-close>Batal</button>
                    <button class="btn btn-primary" type="submit" @disabled($addresses->isEmpty())>Kirim permintaan</button>
                </div>
            </form>
        </dialog>

        <dialog class="rt-dlg req-dlg" id="dlgAmbil" aria-labelledby="dlgAmbilT">
            <form method="POST" action="{{ route('pesanan.request.ambil', $order) }}" class="stack" style="gap:14px">
                @csrf
                <h3 id="dlgAmbilT">Ambil di gudang</h3>
                <p>Admin akan mengonfirmasi jadwal dan pelunasan sebelum barang dikeluarkan.</p>
                <div class="field">
                    <label for="rqDate">Tanggal ambil</label>
                    <input class="input" type="date" id="rqDate" name="date" required min="{{ now()->toDateString() }}">
                </div>
                <div class="picks" role="radiogroup" aria-label="Cara pengambilan">
                    <label class="pick"><input type="radio" name="method" value="sendiri" checked><span><b>Diambil sendiri</b></span></label>
                    <label class="pick"><input type="radio" name="method" value="diwakilkan"><span><b>Diwakilkan orang lain</b></span></label>
                </div>
                <div class="field" id="rqRepBox" hidden>
                    <label for="rqRep">Nama perwakilan</label>
                    <input class="input" type="text" id="rqRep" name="rep_name" maxlength="100">
                </div>
                <div class="field">
                    <label for="rqNote3">Catatan (opsional)</label>
                    <textarea class="input" id="rqNote3" name="catatan" rows="2" maxlength="500" style="height:auto;padding:12px"></textarea>
                </div>
                <div class="rt-dlg-acts">
                    <button class="btn btn-ghost" type="button" data-close>Batal</button>
                    <button class="btn btn-primary" type="submit">Kirim permintaan</button>
                </div>
            </form>
        </dialog>
    @endif
</div>
</div>
@endsection

@push('scripts')
<script>
(function () {
    document.querySelectorAll('[data-open]').forEach(function (b) {
        b.addEventListener('click', function () {
            var d = document.getElementById(b.dataset.open);
            if (d && typeof d.showModal === 'function') d.showModal();
        });
    });
    document.querySelectorAll('dialog [data-close]').forEach(function (b) {
        b.addEventListener('click', function () { b.closest('dialog').close(); });
    });
    var rep = document.getElementById('rqRepBox');
    document.querySelectorAll('input[name="method"]').forEach(function (r) {
        r.addEventListener('change', function () {
            var on = document.querySelector('input[name="method"]:checked').value === 'diwakilkan';
            rep.hidden = !on;
            document.getElementById('rqRep').required = on;
        });
    });
})();
</script>
@endpush
