@extends('layouts.rt')

@section('title', 'Detail Pesanan')

@php
    if (! function_exists('rp')) {
        function rp($n) { return 'Rp' . number_format($n, 0, ',', '.'); }
    }
    $meta = $order->statusMeta();
    $step = $order->statusStep();
    $flow = \App\Models\TitipanOrder::FLOW;
    $logisticLabel = [
        'sendiri' => 'Antar sendiri', 'self' => 'Antar sendiri',
        'anjem' => 'Packing + Anjem RuTip', 'rutip' => 'Packing + Anjem RuTip',
        'kurir' => 'Kurir instan', 'instant' => 'Kurir instan',
    ][$order->logistic] ?? '-';
    $tone = match ($order->status) {
        'menunggu_pembayaran' => 'warn',
        'dalam_gudang', 'selesai' => 'ok',
        default => 'info',
    };
    $left = $order->date_end ? (int) ceil(now()->startOfDay()->diffInDays($order->date_end->copy()->startOfDay(), false)) : null;
    $awaitingPay = $order->status === 'menunggu_pembayaran';
@endphp

@section('content')
<div class="pg">
    <nav class="crumbs" aria-label="Breadcrumb"><a href="{{ route('pesanan.index') }}">Pesanan Saya</a><span aria-hidden="true">/</span><span aria-current="page">{{ $order->code() }}</span></nav>

    <div class="pg-head">
        <div>
            <h2>Pesanan {{ $order->code() }}</h2>
            <p>{{ $order->storage->name ?? 'Penitipan Barang' }}. Dibuat {{ $order->created_at->format('d M Y, H:i') }}.</p>
        </div>
        <div class="pg-acts">
            <span class="badge b-{{ $tone }}"><i></i>{{ $meta['label'] }}</span>
            @if ($awaitingPay && $order->tripay_checkout_url)
                <a class="btn btn-sun" href="{{ $order->tripay_checkout_url }}">Lanjutkan pembayaran</a>
            @endif
        </div>
    </div>

    @if ($awaitingPay)
        <div class="alert a-warn">
            <svg class="ic" aria-hidden="true"><use href="#i-clock"/></svg>
            <div><b>Menunggu pembayaran</b><p>{{ $order->tripay_checkout_url ? 'Selesaikan pembayaran lewat tombol Lanjutkan pembayaran.' : 'Tagihan sedang disiapkan.' }} Jika lewat batas waktu, pesanan bisa kedaluwarsa.</p></div>
        </div>
    @elseif ($order->status === 'dalam_gudang' && $left !== null && $left <= 7)
        <div class="alert a-warn">
            <svg class="ic" aria-hidden="true"><use href="#i-warn"/></svg>
            <div><b>{{ $left > 0 ? 'Masa titip berakhir ' . $left . ' hari lagi' : 'Masa titip sudah berakhir' }}</b><p>Periode sampai {{ $order->date_end->format('d M Y') }}. Hubungi admin untuk perpanjangan atau pengambilan.</p></div>
        </div>
    @endif

    <section class="rt-card">
        <ol class="track" style="--n:{{ count($flow) }}" aria-label="Progres pesanan">
            @foreach ($flow as $key => $f)
                @php $i = array_search($key, array_keys($flow), true); @endphp
                <li class="{{ $i < $step ? 'done' : ($i === $step ? 'cur' : '') }}" @if ($i === $step) aria-current="step" @endif>
                    <span class="d">@if ($i < $step)<svg aria-hidden="true"><use href="#i-check"/></svg>@endif</span>{{ $f['label'] }}
                </li>
            @endforeach
        </ol>
    </section>

    <div class="pg-two">
        <div class="pg">
            <section class="rt-card">
                <div class="rt-card-h"><h3>Detail penitipan</h3></div>
                <div class="rt-rows">
                    <div><span>Gudang</span><b>{{ $order->storage->name ?? '-' }}@if ($order->storage?->address)<small>{{ $order->storage->address }}</small>@endif</b></div>
                    <div><span>Jenis barang</span><b>{{ ucfirst($order->item_type) }}</b></div>
                    <div><span>Jumlah barang</span><b>{{ $order->totalItems() }} barang</b></div>
                    @if ($order->date_start && $order->date_end)
                        <div><span>Periode</span><b>{{ $order->date_start->format('d M Y') }} sampai {{ $order->date_end->format('d M Y') }}</b></div>
                    @endif
                    <div><span>Logistik</span><b>{{ $logisticLabel }}</b></div>
                    @if ($order->address)
                        <div><span>Alamat</span><b>{{ $order->address }}</b></div>
                    @endif
                </div>
            </section>

            @if (! empty($order->items))
                <section class="rt-card">
                    <div class="rt-card-h"><h3>Barang</h3></div>
                    <ul class="it-list">
                        @foreach ($order->items as $name => $qty)
                            <li class="it">
                                <span class="it-pic"><svg viewBox="0 0 44 40" aria-hidden="true"><use href="#box"/></svg></span>
                                <div><b>{{ ucfirst(str_replace('_', ' ', $name)) }}</b></div>
                                <span class="it-r">&times;{{ (int) $qty }}</span>
                            </li>
                        @endforeach
                    </ul>
                    <p class="rt-help" style="margin-top:12px">Isi kemasan tidak dibuka. Foto hanya menunjukkan kondisi luar.</p>
                </section>
            @endif

            @if ($order->proof_photo)
                <section class="rt-card">
                    <div class="rt-card-h"><h3>Bukti penitipan</h3></div>
                    <img src="{{ asset('storage/' . $order->proof_photo) }}" alt="Bukti penitipan {{ $order->code() }}" style="border-radius:16px;width:100%;max-width:420px">
                </section>
            @endif
        </div>

        <aside class="pg-aside">
            <section class="rt-card">
                <div class="rt-card-h"><h3>Rincian biaya</h3></div>
                <div class="rt-rows">
                    <div><span>Biaya penitipan</span><b>{{ rp($order->item_subtotal) }}</b></div>
                    @if ($order->courier_cost > 0)
                        <div><span>Biaya logistik</span><b>{{ rp($order->courier_cost) }}</b></div>
                    @endif
                    <div><span>Biaya layanan</span><b>{{ rp($order->platform_fee) }}</b></div>
                    <div class="tot"><span>Total</span><b>{{ rp($order->total) }}</b></div>
                </div>
            </section>

            <a class="btn btn-ghost wide" target="_blank" rel="noopener" href="https://wa.me/6285121091134?text={{ urlencode('Halo RuangTitip, saya mau tanya pesanan ' . $order->code()) }}">
                <svg class="ic" aria-hidden="true"><use href="#n-chat"/></svg>Hubungi admin via WhatsApp
            </a>
        </aside>
    </div>
</div>
@endsection
