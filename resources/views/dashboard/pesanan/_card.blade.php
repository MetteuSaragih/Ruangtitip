
@php
    $meta = $order->statusMeta();
    if (!function_exists('rp_ps')) {
        function rp_ps($n){ return 'Rp '.number_format($n,0,',','.'); }
    }
    $logisticLabel = [
        'self'    => 'Antar Sendiri',
        'rutip'   => 'Packing + Anjem RuTip',
        'instant' => 'Instant Shipper',
    ][$order->logistic] ?? '—';
@endphp
<a href="{{ route('pesanan.detail', $order) }}"
   class="block rounded-2xl p-4 transition-all hover:-translate-y-0.5"
   style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.09);">

    <div class="flex items-start justify-between gap-3 mb-3">
        <div class="flex items-center gap-2.5 min-w-0">
            <div class="w-9 h-9 rounded-xl flex items-center justify-center text-lg shrink-0" style="background:rgba(124,58,237,0.15);">📦</div>
            <div class="min-w-0">
                <p class="text-sm font-bold text-white truncate">{{ $order->storage->name ?? 'Penitipan Barang' }}</p>
                <p class="text-[11px]" style="color:rgba(255,255,255,0.4);">#{{ $order->code() }} · {{ $order->created_at->format('d M Y') }}</p>
            </div>
        </div>
        <span class="flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold shrink-0"
              style="background:{{ $meta['color'] }}1f;color:{{ $meta['color'] }};">
            <x-dynamic-component :component="'lucide-' . $meta['icon']" class="w-3 h-3" />
            {{ $meta['label'] }}
        </span>
    </div>

    <div class="flex items-center gap-4 mb-3 text-[11px]" style="color:rgba(255,255,255,0.5);">
        <span class="flex items-center gap-1"><x-lucide-package class="w-3.5 h-3.5" /> {{ $order->totalItems() }} item</span>
        <span class="flex items-center gap-1"><x-lucide-truck class="w-3.5 h-3.5" /> {{ $logisticLabel }}</span>
        @if ($order->date_start && $order->date_end)
            <span class="flex items-center gap-1"><x-lucide-calendar class="w-3.5 h-3.5" /> {{ $order->date_start->format('d/m') }}–{{ $order->date_end->format('d/m/y') }}</span>
        @endif
    </div>

    <div class="flex items-center justify-between pt-3" style="border-top:1px solid rgba(255,255,255,0.07);">
        <span class="text-[11px]" style="color:rgba(255,255,255,0.4);">Total</span>
        <span class="text-sm font-bold" style="color:#a78bfa;">{{ rp_ps($order->total) }}</span>
    </div>
</a>
