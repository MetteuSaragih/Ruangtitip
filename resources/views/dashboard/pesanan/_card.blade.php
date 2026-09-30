@php
    $meta = $order->statusMeta();
    $step = $order->statusStep();
    $flowKeys = array_keys(\App\Models\TitipanOrder::FLOW);
    $statusClass = match ($order->status) {
        'menunggu_pembayaran' => 's-pay',
        'dalam_gudang' => 's-store',
        'selesai' => 's-done',
        default => 's-run',
    };
    $logisticLabel = [
        'sendiri' => 'Antar sendiri',
        'self'    => 'Antar sendiri',
        'anjem'   => 'Packing + Anjem RuTip',
        'rutip'   => 'Packing + Anjem RuTip',
        'kurir'   => 'Kurir instan',
        'instant' => 'Kurir instan',
    ][$order->logistic] ?? '-';
    $isDone = $order->isDone();
    $left = $order->date_end ? (int) ceil(now()->startOfDay()->diffInDays($order->date_end->copy()->startOfDay(), false)) : null;
@endphp
<article class="order {{ $order->status === 'menunggu_pembayaran' ? 'attn' : '' }}">
  <div class="o-head">
    <span class="ic" style="background:var(--depot-light)">
      @if ($order->storage && $order->storage->primary_photo)
        <img src="{{ asset('storage/'.$order->storage->primary_photo) }}" alt="{{ $order->storage->name }}">
      @else
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#1C1B18" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 10 12 4l9 6v10H3z"/><path d="M8 20v-6h8v6"/></svg>
      @endif
    </span>
    <div class="who"><strong>{{ $order->storage->name ?? 'Penitipan Barang' }}</strong><small><code>{{ $order->code() }}</code> · {{ $order->created_at->format('d M Y') }}</small></div>
    <span class="status {{ $statusClass }}">{{ $meta['label'] }}</span>
  </div>
  <div class="o-body">
    <p class="o-items">{{ $order->totalItems() }} item &middot; {{ $logisticLabel }}<small>Ruang Titip</small></p>

    @if ($order->date_start && $order->date_end && !$isDone)
      @php
        $total = $order->date_start->diffInDays($order->date_end) ?: 1;
        $elapsed = now()->greaterThan($order->date_start) ? $order->date_start->diffInDays(now()) : 0;
        $used = min($elapsed, $total);
        $soon = $left !== null && $left <= 7;
      @endphp
      <div class="period {{ $soon ? 'soon' : '' }}">
        <div class="row"><span>{{ $order->date_start->format('d M Y') }} – {{ $order->date_end->format('d M Y') }}</span><b>{{ $left !== null && $left > 0 ? 'Sisa '.$left.' hari' : 'Masa titip habis' }}</b></div>
        <div class="bar"><i style="width:{{ min(100, round($used / $total * 100)) }}%"></i></div>
      </div>
    @endif

    @if ($order->status === 'menunggu_pembayaran')
      <div class="pay-due">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="var(--tape-dark)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg>
        <span>Pesanan menunggu pembayaran{{ $order->tripay_checkout_url ? '. Selesaikan lewat tombol di bawah.' : '.' }}</span>
      </div>
    @endif

    @unless ($isDone)
      <ol class="track" style="grid-template-columns:repeat({{ count($flowKeys) }},1fr)" aria-label="Progres pesanan">
        @foreach (\App\Models\TitipanOrder::FLOW as $key => $f)
          @php $i = array_search($key, $flowKeys, true); @endphp
          <li class="{{ $i < $step ? 'done' : ($i === $step ? 'now' : '') }}"><span>{{ $f['label'] }}</span></li>
        @endforeach
      </ol>
    @endunless
  </div>
  <div class="o-foot">
    <div class="total"><small>Total</small><strong>{{ rp($order->total) }}</strong></div>
    <div class="acts">
      @if ($order->status === 'menunggu_pembayaran' && $order->tripay_checkout_url)
        <a href="{{ route('pesanan.detail', $order) }}" class="btn btn-outline btn-sm">Detail</a>
        <a href="{{ $order->tripay_checkout_url }}" class="btn btn-primary btn-sm">Bayar sekarang</a>
      @else
        <a href="{{ route('pesanan.detail', $order) }}" class="btn btn-primary btn-sm">Lihat detail</a>
      @endif
    </div>
  </div>
</article>
