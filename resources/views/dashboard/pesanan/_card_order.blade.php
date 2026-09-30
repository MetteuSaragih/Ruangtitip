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

    $statusMap = [
        \App\Models\Order::STATUS_PENDING => ['label' => 'Menunggu Pembayaran', 'class' => 's-pay'],
        \App\Models\Order::STATUS_PAID => ['label' => 'Dikemas', 'class' => 's-run'],
        \App\Models\Order::STATUS_PROCESSING => ['label' => 'Dikemas', 'class' => 's-run'],
        \App\Models\Order::STATUS_SHIPPED => ['label' => 'Dalam Pengiriman', 'class' => 's-run'],
        \App\Models\Order::STATUS_DELIVERED => ['label' => 'Selesai', 'class' => 's-done'],
        \App\Models\Order::STATUS_CANCELLED => ['label' => 'Dibatalkan', 'class' => 's-done'],
    ];
    $meta = $statusMap[$order->status] ?? ['label' => ucfirst($order->status), 'class' => 's-run'];

    $trackSteps = ['Bayar', 'Dikemas', 'Dikirim / diambil', 'Selesai'];
    $trackIndex = match ($order->status) {
        \App\Models\Order::STATUS_PENDING => 0,
        \App\Models\Order::STATUS_PAID, \App\Models\Order::STATUS_PROCESSING => 1,
        \App\Models\Order::STATUS_SHIPPED => 2,
        default => 3,
    };
@endphp
<article class="order {{ !$isPaid && $order->status === \App\Models\Order::STATUS_PENDING ? 'attn' : '' }}">
  <div class="o-head">
    <span class="ic" style="background:var(--tape-soft)">
      @if ($first && !empty($first['image']))
        <img src="{{ $first['type'] === 'packing' ? asset('storage/'.$first['image']) : \Illuminate\Support\Facades\Storage::url($first['image']) }}" alt="">
      @else
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#1C1B18" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M21 8 12 3 3 8v8l9 5 9-5z"/><path d="m3 8 9 5 9-5M12 13v8"/></svg>
      @endif
    </span>
    <div class="who"><strong>{{ $typeLabel }}</strong><small><code>{{ $order->order_number }}</code> · {{ $order->created_at->format('d M Y') }}</small></div>
    <span class="status {{ $meta['class'] }}">{{ $meta['label'] }}</span>
  </div>
  <div class="o-body">
    <p class="o-items">{{ $itemNames ?: '-' }}<small>{{ $itemCount }} barang &middot; {{ $typeLabel }}</small></p>

    @if (!$isPaid && $order->status === \App\Models\Order::STATUS_PENDING)
      <div class="pay-due">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="var(--tape-dark)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg>
        <span>Pesanan menunggu pembayaran{{ $order->tripay_checkout_url ? '. Selesaikan lewat tombol di bawah.' : '.' }}</span>
      </div>
    @endif

    @unless ($isDone)
      <ol class="track" style="grid-template-columns:repeat({{ count($trackSteps) }},1fr)" aria-label="Progres pesanan">
        @foreach ($trackSteps as $i => $label)
          <li class="{{ $i < $trackIndex ? 'done' : ($i === $trackIndex ? 'now' : '') }}"><span>{{ $label }}</span></li>
        @endforeach
      </ol>
    @endunless
  </div>
  <div class="o-foot">
    <div class="total"><small>Total</small><strong>{{ rp($order->total) }}</strong></div>
    <div class="acts">
      <a href="{{ route('checkout.success', $order->order_number) }}" class="btn btn-outline btn-sm">Detail</a>
      @if (!$isPaid && $order->status === \App\Models\Order::STATUS_PENDING && $order->tripay_checkout_url)
        <a href="{{ $order->tripay_checkout_url }}" class="btn btn-primary btn-sm">Bayar sekarang</a>
      @endif
    </div>
  </div>
</article>
