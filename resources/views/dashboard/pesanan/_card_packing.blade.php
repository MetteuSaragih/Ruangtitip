@php
    $statusMap = [
        'pending' => ['Menunggu Konfirmasi', 'warn'],
        'diproses' => ['Sedang Diproses', 'info'],
        'dikirim' => ['Dalam Pengiriman', 'info'],
        'selesai' => ['Selesai', 'mute'],
    ];
    [$label, $tone] = $statusMap[$order->status ?? 'pending'] ?? $statusMap['pending'];
    $isPaid = \App\Services\ManualPayment::state($order->payment_status) === 'paid';
    $itemNames = collect($order->items ?? [])->pluck('name')->implode(', ');
    $itemCount = collect($order->items ?? [])->sum('qty');
@endphp
{{-- Toko Packing ditunda: kartu hanya informasi, tanpa tautan ke halaman packing. --}}
<article class="card oc">
    <div>
        <div class="top">
            <span class="code">{{ $order->order_code }}</span>
            <span class="badge b-{{ $tone }}"><i></i>{{ $label }}</span>
        </div>
        <p class="meta">
            <span>Toko Packing</span>
            <span>{{ $order->created_at->translatedFormat('j M Y') }}</span>
            <span>{{ $itemNames ?: '-' }}, {{ $itemCount }} barang</span>
        </p>
        <p class="pay">{{ \App\Services\ManualPayment::userText($order) }}</p>
    </div>
</article>
