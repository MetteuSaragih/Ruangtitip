@php
    $statusMap = [
        'pending'  => ['label' => 'Menunggu Konfirmasi', 'class' => 's-pay'],
        'diproses' => ['label' => 'Sedang Diproses',     'class' => 's-run'],
        'dikirim'  => ['label' => 'Dalam Pengiriman',    'class' => 's-run'],
        'selesai'  => ['label' => 'Selesai',             'class' => 's-done'],
    ];
    $meta = $statusMap[$order->status ?? 'pending'] ?? $statusMap['pending'];
    $isPaid = $order->payment_status === 'PAID';
    $itemNames = collect($order->items ?? [])->pluck('name')->implode(', ');
    $itemCount = collect($order->items ?? [])->sum('qty');
@endphp
<article class="order {{ !$isPaid ? 'attn' : '' }}">
  <div class="o-head">
    <span class="ic" style="background:var(--tape-soft)">
      <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#1C1B18" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M21 8 12 3 3 8v8l9 5 9-5z"/><path d="m3 8 9 5 9-5M12 13v8"/></svg>
    </span>
    <div class="who"><strong>Toko Packing</strong><small><code>{{ $order->order_code }}</code> · {{ $order->created_at->format('d M Y') }}</small></div>
    <span class="status {{ $meta['class'] }}">{{ $meta['label'] }}</span>
  </div>
  <div class="o-body">
    <p class="o-items">{{ $itemNames ?: '-' }}<small>{{ $itemCount }} barang &middot; Toko Packing</small></p>

    @unless ($isPaid)
      <div class="pay-due">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="var(--tape-dark)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg>
        <span>Pesanan menunggu pembayaran.</span>
      </div>
    @endunless
  </div>
  <div class="o-foot">
    <span style="display:flex;align-items:center;gap:6px;font-size:13px;font-weight:600;color:{{ $isPaid ? '#1F4535' : 'var(--tape-dark)' }}">
      <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="4" width="22" height="16" rx="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
      {{ $isPaid ? 'Lunas' : 'Menunggu Pembayaran' }}
    </span>
    <div class="total"><small>Total</small><strong>{{ rp($order->total) }}</strong></div>
  </div>
</article>
