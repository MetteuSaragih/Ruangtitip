@php
    [$statusLabel, $badgeTone] = $order->displayStatus();
    $awaitingPay = $order->status === 'menunggu_pembayaran';
    $payText = $awaitingPay
        ? \App\Services\ManualPayment::userText($order)
        : 'Lunas, total ' . rp($order->total) . '.';
    $payCta = $awaitingPay ? \App\Services\ManualPayment::userCta($order) : null;
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
        @if ($payCta)
            <a href="{{ \App\Services\ManualPayment::pageUrl($order) }}" class="btn btn-primary sm">{{ $payCta }}</a>
        @endif
        <a href="{{ route('pesanan.detail', $order) }}" class="btn btn-ghost sm">Lihat detail</a>
    </div>
</article>
