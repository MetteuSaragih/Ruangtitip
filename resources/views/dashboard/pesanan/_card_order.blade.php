@php
    $items = collect($order->items ?? []);
    $itemTypes = $items->pluck('type')->filter()->unique();
    $typeLabel = $itemTypes->count() > 1 ? 'Belanja' : ($itemTypes->first() === 'packing' ? 'Toko Packing' : 'Toko Preloved');
    $itemNames = $items->pluck('name')->implode(', ');
    $itemCount = $items->sum('qty');
    $isPaid = $order->payment_status === 'PAID';
    $pending = $order->status === \App\Models\Order::STATUS_PENDING;

    $statusMap = [
        \App\Models\Order::STATUS_PENDING => ['Menunggu Pembayaran', 'warn'],
        \App\Models\Order::STATUS_PAID => ['Dikemas', 'info'],
        \App\Models\Order::STATUS_PROCESSING => ['Dikemas', 'info'],
        \App\Models\Order::STATUS_SHIPPED => ['Dalam Pengiriman', 'info'],
        \App\Models\Order::STATUS_DELIVERED => ['Selesai', 'mute'],
        \App\Models\Order::STATUS_CANCELLED => ['Dibatalkan', 'mute'],
    ];
    [$label, $tone] = $statusMap[$order->status] ?? [ucfirst($order->status), 'info'];
    $payText = ! $isPaid && $pending
        ? 'Tagihan ' . rp($order->total) . ' belum dibayar.'
        : 'Lunas, total ' . rp($order->total) . '.';
@endphp
<article class="card oc">
    <div>
        <div class="top">
            <span class="code">{{ $order->order_number }}</span>
            <span class="badge b-{{ $tone }}"><i></i>{{ $label }}</span>
        </div>
        <p class="meta">
            <span>{{ $typeLabel }}</span>
            <span>{{ $order->created_at->translatedFormat('j M Y') }}</span>
            <span>{{ $itemNames ?: '-' }}, {{ $itemCount }} barang</span>
        </p>
        <p class="pay">{{ $payText }}</p>
    </div>
    <div class="oc-acts">
        @if (! $isPaid && $pending && $order->tripay_checkout_url)
            <a href="{{ $order->tripay_checkout_url }}" class="btn btn-primary sm">Bayar</a>
        @endif
        <a href="{{ route('checkout.success', $order->order_number) }}" class="btn btn-ghost sm">Lihat detail</a>
    </div>
</article>
