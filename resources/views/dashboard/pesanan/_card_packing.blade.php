<<<<<<< HEAD
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
=======

@php
    if (!function_exists('rp_packing')) {
        function rp_packing($n){ return 'Rp '.number_format($n,0,',','.'); }
    }
    $statusMap = [
        'pending'  => ['label' => 'Menunggu Konfirmasi', 'icon' => 'clock',        'color' => '#fbbf24'],
        'diproses' => ['label' => 'Sedang Diproses',     'icon' => 'loader',       'color' => '#38bdf8'],
        'dikirim'  => ['label' => 'Dalam Pengiriman',    'icon' => 'truck',        'color' => '#a78bfa'],
        'selesai'  => ['label' => 'Selesai',             'icon' => 'check-circle', 'color' => '#34d399'],
    ];
    $st   = $statusMap[$order->status ?? 'pending'] ?? $statusMap['pending'];
    $paid = $order->payment_status === 'PAID';
    $itemNames = collect($order->items ?? [])->pluck('name')->implode(', ');
    $totalItems = collect($order->items ?? [])->sum('qty');
@endphp
<div class="block rounded-2xl p-4 transition-all hover:-translate-y-0.5"
     style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.09);">

    <div class="flex items-start justify-between gap-3 mb-3">
        <div class="flex items-center gap-2.5 min-w-0">
            <div class="w-9 h-9 rounded-xl flex items-center justify-center shrink-0" style="background:rgba(56,189,248,0.15);">
                <x-lucide-package class="w-4 h-4" style="color:#38bdf8;" />
            </div>
            <div class="min-w-0">
                <div class="flex items-center gap-1.5 flex-wrap">
                    <p class="text-sm font-bold text-white truncate">Toko Packing</p>
                    <span class="px-1.5 py-0.5 rounded-md text-[9px] font-bold" style="background:rgba(56,189,248,0.15);color:#38bdf8;">Packing</span>
                </div>
                <p class="text-[11px]" style="color:rgba(255,255,255,0.4);">#{{ $order->order_code }} · {{ $order->created_at->format('d M Y') }}</p>
            </div>
        </div>
        <span class="flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold shrink-0"
              style="background:{{ $st['color'] }}1f;color:{{ $st['color'] }};">
            <x-dynamic-component :component="'lucide-' . $st['icon']" class="w-3 h-3" />
            {{ $st['label'] }}
        </span>
    </div>

    <div class="mb-3 text-[11px] truncate" style="color:rgba(255,255,255,0.5);">
        {{ $itemNames ?: '-' }}
    </div>

    <div class="flex items-center justify-between pt-3" style="border-top:1px solid rgba(255,255,255,0.07);">
        <span class="flex items-center gap-1.5 text-[11px]" style="color:{{ $paid ? '#34d399' : '#fbbf24' }};">
            <x-lucide-credit-card class="w-3.5 h-3.5" />
            {{ $paid ? 'Lunas' : 'Menunggu Pembayaran' }}
        </span>
        <span class="text-sm font-bold" style="color:#38bdf8;">{{ rp_packing($order->total) }}</span>
    </div>
</div>
>>>>>>> hostinger/main
