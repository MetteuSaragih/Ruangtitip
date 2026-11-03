@php
    $meta = $order->statusMeta();
    $step = $order->statusStep();
    $flow = \App\Models\TitipanOrder::FLOW;
    $badgeTone = match ($order->status) {
        'menunggu_pembayaran' => 'warn',
        'dalam_gudang', 'selesai' => 'ok',
        default => 'info',
    };
    $logisticLabel = [
        'sendiri' => 'Antar sendiri', 'self' => 'Antar sendiri',
        'anjem' => 'Packing + Anjem RuTip', 'rutip' => 'Packing + Anjem RuTip',
        'kurir' => 'Kurir instan', 'instant' => 'Kurir instan',
    ][$order->logistic] ?? '-';
    $isDone = $order->isDone();
    $left = $order->date_end ? (int) ceil(now()->startOfDay()->diffInDays($order->date_end->copy()->startOfDay(), false)) : null;
    $awaitingPay = $order->status === 'menunggu_pembayaran';
@endphp
<article class="rt-card po-card {{ $awaitingPay ? 'attn' : '' }}">
    <div class="po-top">
        <span class="po-thumb">
            @if ($order->storage && $order->storage->primary_photo)
                <img src="{{ asset('storage/' . $order->storage->primary_photo) }}" alt="{{ $order->storage->name }}">
            @else
                <svg aria-hidden="true"><use href="#n-ware"/></svg>
            @endif
        </span>
        <div class="po-who"><strong>{{ $order->storage->name ?? 'Penitipan Barang' }}</strong><small>{{ $order->code() }} &middot; {{ $order->created_at->format('d M Y') }}</small></div>
        <span class="badge b-{{ $badgeTone }}"><i></i>{{ $meta['label'] }}</span>
    </div>

    <p class="po-meta"><span>Ruang Titip</span><span>{{ $order->totalItems() }} barang</span><span>{{ $logisticLabel }}</span></p>

    @if ($order->date_start && $order->date_end && ! $isDone)
        @php
            $total = $order->date_start->diffInDays($order->date_end) ?: 1;
            $elapsed = now()->greaterThan($order->date_start) ? $order->date_start->diffInDays(now()) : 0;
            $used = min($elapsed, $total);
            $isSoon = $left !== null && $left <= 7;
        @endphp
        <div class="po-period {{ $isSoon ? 'soon' : '' }}">
            <div class="row"><span>{{ $order->date_start->format('d M Y') }} sampai {{ $order->date_end->format('d M Y') }}</span><b>{{ $left !== null && $left > 0 ? 'Sisa ' . $left . ' hari' : 'Masa titip habis' }}</b></div>
            <div class="po-bar"><i style="width:{{ min(100, round($used / $total * 100)) }}%"></i></div>
        </div>
    @endif

    @if ($awaitingPay)
        <p class="po-pay"><svg aria-hidden="true"><use href="#i-clock"/></svg>Menunggu pembayaran{{ $order->tripay_checkout_url ? '. Selesaikan lewat tombol di bawah.' : '.' }}</p>
    @endif

    @unless ($isDone)
        <ol class="track" style="--n:{{ count($flow) }}" aria-label="Progres pesanan">
            @foreach ($flow as $key => $f)
                @php $i = array_search($key, array_keys($flow), true); @endphp
                <li class="{{ $i < $step ? 'done' : ($i === $step ? 'cur' : '') }}" @if ($i === $step) aria-current="step" @endif>
                    <span class="d">@if ($i < $step)<svg aria-hidden="true"><use href="#i-check"/></svg>@endif</span>{{ $f['label'] }}
                </li>
            @endforeach
        </ol>
    @endunless

    <div class="po-foot">
        <div class="po-total"><small>Total</small><strong>{{ rp($order->total) }}</strong></div>
        <div class="po-acts">
            @if ($awaitingPay && $order->tripay_checkout_url)
                <a href="{{ route('pesanan.detail', $order) }}" class="btn btn-ghost sm">Detail</a>
                <a href="{{ $order->tripay_checkout_url }}" class="btn btn-sun sm">Bayar sekarang</a>
            @else
                <a href="{{ route('pesanan.detail', $order) }}" class="btn btn-ink sm">Lihat detail</a>
            @endif
        </div>
    </div>
</article>
