@php
    $items = collect($order->items ?? []);
    $itemTypes = $items->pluck('type')->filter()->unique();
    $typeLabel = $itemTypes->count() > 1 ? 'Belanja' : ($itemTypes->first() === 'packing' ? 'Toko Packing' : 'Toko Preloved');
    $first = $items->first();
    $itemNames = $items->pluck('name')->implode(', ');
    $itemCount = $items->sum('qty');
    $isPaid = $order->payment_status === 'PAID';
    $isCancelled = $order->status === \App\Models\Order::STATUS_CANCELLED;
    $isDone = $order->status === \App\Models\Order::STATUS_DELIVERED || $isCancelled;
    $pending = $order->status === \App\Models\Order::STATUS_PENDING;

    $statusMap = [
        \App\Models\Order::STATUS_PENDING => ['Menunggu Pembayaran', 'warn'],
        \App\Models\Order::STATUS_PAID => ['Dikemas', 'info'],
        \App\Models\Order::STATUS_PROCESSING => ['Dikemas', 'info'],
        \App\Models\Order::STATUS_SHIPPED => ['Dalam Pengiriman', 'info'],
        \App\Models\Order::STATUS_DELIVERED => ['Selesai', 'ok'],
        \App\Models\Order::STATUS_CANCELLED => ['Dibatalkan', 'mute'],
    ];
    [$label, $tone] = $statusMap[$order->status] ?? [ucfirst($order->status), 'info'];

    $trackSteps = ['Bayar', 'Dikemas', 'Dikirim / diambil', 'Selesai'];
    $trackIndex = match ($order->status) {
        \App\Models\Order::STATUS_PENDING => 0,
        \App\Models\Order::STATUS_PAID, \App\Models\Order::STATUS_PROCESSING => 1,
        \App\Models\Order::STATUS_SHIPPED => 2,
        default => 3,
    };
@endphp
<article class="rt-card po-card {{ ! $isPaid && $pending ? 'attn' : '' }}">
    <div class="po-top">
        <span class="po-thumb">
            @if ($first && ! empty($first['image']))
                <img src="{{ ($first['type'] ?? null) === 'packing' ? asset('storage/' . $first['image']) : \Illuminate\Support\Facades\Storage::url($first['image']) }}" alt="">
            @else
                <svg aria-hidden="true"><use href="#n-tag"/></svg>
            @endif
        </span>
        <div class="po-who"><strong>{{ $typeLabel }}</strong><small>{{ $order->order_number }} &middot; {{ $order->created_at->format('d M Y') }}</small></div>
        <span class="badge b-{{ $tone }}"><i></i>{{ $label }}</span>
    </div>

    <p class="po-meta"><span>{{ $itemNames ?: '-' }}</span><span>{{ $itemCount }} barang</span></p>

    @if (! $isPaid && $pending)
        <p class="po-pay"><svg aria-hidden="true"><use href="#i-clock"/></svg>Menunggu pembayaran{{ $order->tripay_checkout_url ? '. Selesaikan lewat tombol di bawah.' : '.' }}</p>
    @endif

    @unless ($isDone)
        <ol class="track" style="--n:{{ count($trackSteps) }}" aria-label="Progres pesanan">
            @foreach ($trackSteps as $i => $step)
                <li class="{{ $i < $trackIndex ? 'done' : ($i === $trackIndex ? 'cur' : '') }}" @if ($i === $trackIndex) aria-current="step" @endif>
                    <span class="d">@if ($i < $trackIndex)<svg aria-hidden="true"><use href="#i-check"/></svg>@endif</span>{{ $step }}
                </li>
            @endforeach
        </ol>
    @endunless

    <div class="po-foot">
        <div class="po-total"><small>Total</small><strong>{{ rp($order->total) }}</strong></div>
        <div class="po-acts">
            <a href="{{ route('checkout.success', $order->order_number) }}" class="btn btn-ghost sm">Detail</a>
            @if (! $isPaid && $pending && $order->tripay_checkout_url)
                <a href="{{ $order->tripay_checkout_url }}" class="btn btn-sun sm">Bayar sekarang</a>
            @endif
        </div>
    </div>
</article>
