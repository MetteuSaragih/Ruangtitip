@extends('layouts.admin')

@section('title', 'Pesanan ' . $order->code())

@push('styles')
<style>
    .proof-img { width: 100%; max-height: 320px; object-fit: cover; border-radius: 10px; border: 1px solid var(--color-border); display: block; }
    .radio-row { display: flex; align-items: center; gap: 10px; padding: 10px 12px; border: 1px solid var(--color-border); border-radius: 10px; cursor: pointer; font-weight: 600; }
    .radio-row input { width: 16px; height: 16px; }
    .req-item { padding: 10px 0; border-bottom: 1px solid var(--color-divider); display: flex; justify-content: space-between; gap: 10px; align-items: center; }
    .req-item:last-child { border-bottom: 0; }
</style>
@endpush

@php
    $rp = fn ($n) => 'Rp ' . number_format((int) $n, 0, ',', '.');
    $tone = ['menunggu_pembayaran' => 'warn', 'penjadwalan_penjemputan' => 'info', 'dalam_gudang' => 'ok', 'proses_pengembalian' => 'info', 'selesai' => 'mute'];
    $stepLabels = ['menunggu_pembayaran' => 'Pembayaran', 'penjadwalan_penjemputan' => 'Penjemputan', 'dalam_gudang' => 'Di gudang', 'proses_pengembalian' => 'Pengembalian', 'selesai' => 'Selesai'];
    $curStep = $order->statusStep();
    $logisticLabel = ['self' => 'Antar sendiri ke gudang', 'rutip' => 'Dijemput atau diantar RuangTitip', 'instant' => 'Ekspedisi Biteship'][$order->logistic] ?? ($order->logistic ?: '-');
    $daysLeft = $order->date_end ? (int) now()->startOfDay()->diffInDays($order->date_end->copy()->startOfDay(), false) : null;
    $isPaid = strtoupper((string) $order->payment_status) === 'PAID';
    $proofUrl = $order->proof_photo ? asset('storage/' . $order->proof_photo) : null;
    $user = $order->user;

    $events = collect([['at' => $order->created_at, 'who' => 'Sistem', 'text' => 'Pesanan dibuat']]);
    if ($order->payment_proof_at) {
        $events->push(['at' => $order->payment_proof_at, 'who' => 'Pelanggan', 'text' => 'Bukti pembayaran dikirim' . (($order->payment_attempts ?? 0) > 1 ? ' ulang' : '')]);
    }
    if (strtoupper((string) $order->payment_status) === 'REJECTED' && $order->payment_reviewed_at) {
        $events->push(['at' => $order->payment_reviewed_at, 'who' => 'Admin', 'text' => 'Pembayaran ditandai gagal: ' . $order->payment_reject_reason]);
    }
    if ($isPaid) {
        $events->push(['at' => $order->paid_at ?: $order->created_at, 'who' => 'Admin', 'text' => 'Pembayaran dikonfirmasi via QRIS']);
    }
    foreach ($requests as $r) {
        $events->push(['at' => $r->created_at, 'who' => 'Pelanggan', 'text' => 'Permintaan ' . mb_strtolower($r->typeLabel()) . ' (' . $r->statusLabel() . ')']);
    }
    if ($proofUrl) {
        $events->push(['at' => $order->updated_at, 'who' => 'Admin', 'text' => 'Bukti foto penitipan tersedia']);
    }
    if ($order->updated_at && $order->created_at && $order->updated_at->gt($order->created_at->copy()->addMinute())) {
        $events->push(['at' => $order->updated_at, 'who' => 'Admin', 'text' => 'Pembaruan terakhir, status ' . $order->statusMeta()['label']]);
    }
    $events = $events->sortBy(fn ($e) => optional($e['at'])->timestamp ?? 0)->values();
@endphp

@section('content')
<div class="ph">
    <div>
        <h1><span class="mono">{{ $order->code() }}</span></h1>
        <p>Dibuat {{ optional($order->created_at)->format('d M Y, H:i') }} &middot; <span class="st {{ $tone[$order->status] ?? 'info' }}">{{ $order->statusMeta()['label'] }}</span></p>
    </div>
    <div class="acts">
        <a class="btn btn-ghost" href="{{ route('admin.titip.index') }}">Kembali ke daftar</a>
        <button type="button" class="btn btn-ghost" data-open="proofDialog">{{ $proofUrl ? 'Ganti bukti foto' : 'Unggah bukti foto' }}</button>
        <button type="button" class="btn btn-sun" data-open="statusDialog">Perbarui status</button>
    </div>
</div>

<div class="stack">
    @if ($order->needs_admin_attention)
        <div class="al err">
            <svg class="ic" aria-hidden="true"><use href="#i-warn"/></svg>
            <div><b>Perlu tindakan admin</b><p>{{ $order->biteship_last_error ?: 'Pemesanan kurir gagal atau pesanan perlu dicek manual.' }}</p></div>
        </div>
    @endif
    @if ($order->status === 'menunggu_pembayaran')
        @php $bill = \App\Services\BillingFeed::normalize('titip', $order); @endphp
        @if ($bill['state'] === 'unpaid')
            <div class="al warn">
                <svg class="ic" aria-hidden="true"><use href="#i-clock"/></svg>
                <div><b>Menunggu pembayaran</b><p>Pelanggan belum mengunggah bukti pembayaran {{ $rp($order->total) }}. Setelah bukti masuk, periksa dan konfirmasi di sini atau di Tagihan.</p></div>
            </div>
        @else
            @include('admin.partials.payment-review', ['type' => 'titip', 'id' => $order->id, 'bill' => $bill])
        @endif
    @elseif ($order->status === 'penjadwalan_penjemputan')
        <div class="al info">
            <svg class="ic" aria-hidden="true"><use href="#i-truck"/></svg>
            <div><b>Penjemputan perlu dijadwalkan</b><p>Pembayaran sudah masuk. Atur penjemputan atau penerimaan barang, lalu perbarui status ke Dalam gudang setelah barang diterima.</p></div>
        </div>
    @endif
    @if ($daysLeft !== null && in_array($order->status, ['dalam_gudang', 'proses_pengembalian'], true))
        @if ($daysLeft < 0)
            <div class="al err">
                <svg class="ic" aria-hidden="true"><use href="#i-clock"/></svg>
                <div><b>Masa titip sudah lewat</b><p>Berakhir {{ $order->date_end->format('d M Y') }}, terlambat {{ abs($daysLeft) }} hari.</p></div>
            </div>
        @elseif ($daysLeft <= 7)
            <div class="al warn">
                <svg class="ic" aria-hidden="true"><use href="#i-clock"/></svg>
                <div><b>Masa titip hampir habis</b><p>Berakhir {{ $order->date_end->format('d M Y') }}, sisa {{ $daysLeft }} hari.</p></div>
            </div>
        @endif
    @endif

    <section class="card">
        <ol class="steps" aria-label="Progres pesanan">
            @foreach ($stepLabels as $key => $label)
                @php $idx = array_search($key, array_keys($stepLabels), true); @endphp
                <li class="{{ $idx < $curStep ? 'done' : ($idx === $curStep ? 'cur' : '') }}" @if ($idx === $curStep) aria-current="step" @endif>{{ $label }}</li>
            @endforeach
        </ol>
    </section>

    <div class="cols">
        <div class="stack">
            <section class="card flush">
                <div class="card-h"><h3>Barang</h3><small>{{ $order->totalItems() }} item</small></div>
                @if (empty($order->items))
                    <div class="empty">Tidak ada data barang.</div>
                @else
                    <div class="tw" style="margin-top:12px">
                        <table class="tbl">
                            <thead><tr><th>Ukuran</th><th>Kode</th><th class="r">Jumlah</th></tr></thead>
                            <tbody>
                                @foreach ($order->items as $code => $qty)
                                    <tr>
                                        <td>{{ $sizes[$code] ?? strtoupper((string) $code) }}</td>
                                        <td class="mono">{{ strtoupper((string) $code) }}</td>
                                        <td class="r">{{ (int) $qty }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
                @if ($order->note)
                    <div style="padding:14px 18px;border-top:1px solid var(--color-divider)"><span class="sec-t">Catatan pelanggan</span><p>{{ $order->note }}</p></div>
                @endif
            </section>

            <section class="card">
                <div class="card-h"><h3>Periode dan jadwal</h3></div>
                <div class="kv">
                    <div><span>Ruang</span><b>{{ $order->storage?->name ?? '-' }}</b></div>
                    <div><span>Mulai</span><b>{{ optional($order->date_start)->format('d M Y') ?? '-' }}</b></div>
                    <div><span>Berakhir</span><b>{{ optional($order->date_end)->format('d M Y') ?? '-' }}@if ($daysLeft !== null && $order->status !== 'selesai')<small>{{ $daysLeft >= 0 ? 'Sisa ' . $daysLeft . ' hari' : 'Lewat ' . abs($daysLeft) . ' hari' }}</small>@endif</b></div>
                    <div><span>Logistik</span><b>{{ $logisticLabel }}</b></div>
                    @if ($order->pickup_date)
                        <div><span>Jadwal penjemputan</span><b>{{ $order->pickup_date->format('d M Y') }}<small>{{ $order->pickup_time }}{{ $order->pickup_time_end ? ' - ' . $order->pickup_time_end : '' }}</small></b></div>
                    @endif
                    @if ($order->courier_company)
                        <div><span>Kurir</span><b>{{ strtoupper($order->courier_company) }} {{ $order->courier_service_code }}</b></div>
                    @endif
                    @if ($order->biteship_tracking_id)
                        <div><span>Nomor resi</span><b class="mono">{{ $order->biteship_tracking_id }}</b></div>
                    @endif
                    @if ($order->address)
                        <div><span>Alamat</span><b style="max-width:60%">{{ $order->address }}</b></div>
                    @endif
                </div>
            </section>

            <section class="card">
                <div class="card-h"><h3>Riwayat</h3></div>
                <ul class="tl">
                    @foreach ($events as $e)
                        <li class="{{ $e['who'] }}">
                            <i></i>
                            <div><b>{{ $e['text'] }}</b><small>{{ $e['who'] }} &middot; {{ optional($e['at'])->format('d M Y, H:i') }}</small></div>
                        </li>
                    @endforeach
                </ul>
            </section>
        </div>

        <aside>
            <section class="card">
                <div class="card-h"><h3>Pelanggan</h3></div>
                @if ($user)
                    <div class="kv">
                        <div><span>Nama</span><b>{{ $user->name }}</b></div>
                        <div><span>Email</span><b style="word-break:break-all">{{ $user->email }}</b></div>
                        <div><span>Telepon</span><b>{{ $user->phone ?: '-' }}</b></div>
                    </div>
                    @if (Route::has('admin.accounts.show'))
                        <p style="margin-top:10px"><a class="btn-text" href="{{ route('admin.accounts.show', $user) }}">Lihat profil pelanggan</a></p>
                    @endif
                @else
                    <p class="muted">Data pelanggan tidak ditemukan.</p>
                @endif
            </section>

            <section class="card">
                <div class="card-h"><h3>Biaya</h3><span class="st {{ \App\Services\ManualPayment::tone($order->payment_status) }}">{{ $isPaid ? 'Lunas' : \App\Services\ManualPayment::label($order->payment_status) }}</span></div>
                <div class="kv">
                    <div><span>Subtotal barang</span><b>{{ $rp($order->item_subtotal) }}</b></div>
                    <div><span>Ongkos kurir</span><b>{{ $rp($order->courier_cost) }}</b></div>
                    <div><span>Packing</span><b>{{ $rp($order->packing_cost) }}</b></div>
                    <div><span>Biaya platform</span><b>{{ $rp($order->platform_fee) }}</b></div>
                    <div class="tot"><span>Total</span><b>{{ $rp($order->total) }}</b></div>
                    <div><span>Status bayar</span><b>{{ \App\Services\ManualPayment::label($order->payment_status) }}</b></div>
                    <div><span>Metode</span><b>QRIS</b></div>
                    @if ($order->tripay_reference)
                        <div><span>Referensi lama</span><b class="mono" style="word-break:break-all">{{ $order->tripay_reference }}</b></div>
                    @endif
                    @if (\App\Services\ManualPayment::proofExists($order) && $order->status !== 'menunggu_pembayaran')
                        <div><span>Bukti bayar</span><b><a href="{{ route('payment.proof.show', ['titip', $order->id]) }}" target="_blank" rel="noopener">Lihat bukti</a></b></div>
                    @endif
                </div>
            </section>

            <section class="card">
                <div class="card-h">
                    <h3>Permintaan pelanggan</h3>
                    @if (Route::has('admin.permintaan.index'))<a class="btn-text" href="{{ route('admin.permintaan.index') }}">Semua</a>@endif
                </div>
                @forelse ($requests as $r)
                    <div class="req-item">
                        <span><b>{{ $r->typeLabel() }}</b><small class="muted" style="display:block">{{ optional($r->created_at)->format('d M Y') }}</small></span>
                        <span class="st {{ $r->status === 'selesai' ? 'ok' : ($r->status === 'ditolak' ? 'err' : 'warn') }}">{{ $r->statusLabel() }}</span>
                    </div>
                @empty
                    <p class="muted">Belum ada permintaan.</p>
                @endforelse
            </section>

            <section class="card">
                <div class="card-h"><h3>Bukti foto</h3></div>
                @if ($proofUrl)
                    <a href="{{ $proofUrl }}" target="_blank" rel="noopener"><img class="proof-img" src="{{ $proofUrl }}" alt="Bukti foto kondisi barang"></a>
                @else
                    <p class="muted">Belum ada bukti foto. Unggah foto kondisi barang saat diterima di gudang.</p>
                @endif
            </section>
        </aside>
    </div>
</div>

{{-- Dialog perbarui status --}}
<dialog class="dlg" id="statusDialog" aria-labelledby="statusDialogT">
    <form method="POST" action="{{ route('admin.ruang-titip.orders.status', $order) }}">
        @csrf
        <div class="dh"><h3 id="statusDialogT">Perbarui status pesanan</h3><button class="x" type="button" data-close aria-label="Tutup"><svg class="ic" aria-hidden="true"><use href="#i-x"/></svg></button></div>
        <div class="db">
            @foreach (\App\Models\TitipanOrder::FLOW as $key => $meta)
                <label class="radio-row"><input type="radio" name="status" value="{{ $key }}" @checked($order->status === $key) required> {{ $meta['label'] }}</label>
            @endforeach
            <p class="help">Status baru bisa dilanjutkan setelah pembayaran dikonfirmasi di Tagihan. Disarankan mengunggah bukti foto sebelum memindahkan barang ke Dalam gudang.</p>
        </div>
        <div class="df">
            <button class="btn btn-ghost" type="button" data-close>Batal</button>
            <button class="btn btn-primary" type="submit" data-confirm="Ubah status pesanan {{ $order->code() }}?" data-confirm-ok="Ubah status">Simpan status</button>
        </div>
    </form>
</dialog>

{{-- Dialog unggah bukti --}}
<dialog class="dlg" id="proofDialog" aria-labelledby="proofDialogT" @if ($errors->has('proof_photo')) data-reopen @endif>
    <form method="POST" action="{{ route('admin.ruang-titip.orders.proof', $order) }}" enctype="multipart/form-data">
        @csrf
        <div class="dh"><h3 id="proofDialogT">{{ $proofUrl ? 'Ganti bukti foto' : 'Unggah bukti foto' }}</h3><button class="x" type="button" data-close aria-label="Tutup"><svg class="ic" aria-hidden="true"><use href="#i-x"/></svg></button></div>
        <div class="db">
            <div class="field">
                <label for="proofFile">Foto kondisi barang</label>
                <input class="input" id="proofFile" type="file" name="proof_photo" accept="image/*" required>
                <span class="help">JPG atau PNG, maksimal 5 MB.</span>
                @error('proof_photo')<span class="err-t">{{ $message }}</span>@enderror
            </div>
        </div>
        <div class="df">
            <button class="btn btn-ghost" type="button" data-close>Batal</button>
            <button class="btn btn-primary" type="submit">Unggah</button>
        </div>
    </form>
</dialog>
@endsection
