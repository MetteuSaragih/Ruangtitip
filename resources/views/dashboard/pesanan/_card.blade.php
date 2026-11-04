@php
    [$statusLabel, $badgeTone] = $order->displayStatus();
    $awaitingPay = $order->status === 'menunggu_pembayaran';
    $payText = $awaitingPay
        ? 'Tagihan ' . rp($order->total) . ' belum dibayar. Selesaikan sebelum batas waktu pembayaran.'
        : 'Lunas, total ' . rp($order->total) . '.';
@endphp
<article class="card oc">
    <div>
        <div class="top">
            <span class="code">{{ $order->code() }}</span>
            <span class="badge b-{{ $badgeTone }}"><i></i>{{ $statusLabel }}</span>
        </div>
        <p class="meta">
            <span>{{ $order->storage->name ?? 'Penitipan Barang' }}</span>
            @if ($order->date_start && $order->date_end)
                <span>{{ $order->date_start->translatedFormat('j M Y') }} sampai {{ $order->date_end->translatedFormat('j M Y') }}</span>
            @endif
            <span>{{ $order->totalItems() }} barang</span>
        </p>
        <p class="pay">{{ $payText }}</p>
    </div>
    <div class="oc-acts">
        @if ($awaitingPay && $order->tripay_checkout_url)
            <a href="{{ $order->tripay_checkout_url }}" class="btn btn-sun sm">Bayar</a>
        @endif
        <a href="{{ route('pesanan.detail', $order) }}" class="btn btn-ghost sm">Lihat detail</a>
    </div>
</article>
