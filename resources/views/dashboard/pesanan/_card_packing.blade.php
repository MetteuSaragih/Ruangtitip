@php
    $statusMap = [
        'pending' => ['Menunggu Konfirmasi', 'warn'],
        'diproses' => ['Sedang Diproses', 'info'],
        'dikirim' => ['Dalam Pengiriman', 'info'],
        'selesai' => ['Selesai', 'ok'],
    ];
    [$label, $tone] = $statusMap[$order->status ?? 'pending'] ?? $statusMap['pending'];
    $isPaid = $order->payment_status === 'PAID';
    $itemNames = collect($order->items ?? [])->pluck('name')->implode(', ');
    $itemCount = collect($order->items ?? [])->sum('qty');
@endphp
<article class="rt-card po-card {{ ! $isPaid ? 'attn' : '' }}">
    <div class="po-top">
        <span class="po-thumb"><svg aria-hidden="true"><use href="#n-tape"/></svg></span>
        <div class="po-who"><strong>Toko Packing</strong><small>{{ $order->order_code }} &middot; {{ $order->created_at->format('d M Y') }}</small></div>
        <span class="badge b-{{ $tone }}"><i></i>{{ $label }}</span>
    </div>

    <p class="po-meta"><span>{{ $itemNames ?: '-' }}</span><span>{{ $itemCount }} barang</span></p>

    <p class="po-pay {{ $isPaid ? 'ok' : '' }}">
        <svg aria-hidden="true"><use href="#{{ $isPaid ? 'i-check' : 'i-clock' }}"/></svg>
        {{ $isPaid ? 'Lunas' : 'Menunggu pembayaran' }}
    </p>

    <div class="po-foot">
        <div class="po-total"><small>Total</small><strong>{{ rp($order->total) }}</strong></div>
        <div class="po-acts">
            @if (! $isPaid)
                <a href="{{ route('packing.success', $order->order_code) }}" class="btn btn-sun sm">Lanjutkan pembayaran</a>
            @else
                <a href="{{ route('packing.success', $order->order_code) }}" class="btn btn-ghost sm">Detail</a>
            @endif
        </div>
    </div>
</article>
