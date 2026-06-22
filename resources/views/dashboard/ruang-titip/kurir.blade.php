@extends('layouts.dashboard')
@section('title', 'Pilih Kurir')

@php function rp($n){ return 'Rp '.number_format($n,0,',','.'); } @endphp

@section('content')
<x-courier-group-script />
<div class="max-w-xl mx-auto pt-6 pb-8">
    @include('dashboard.ruang-titip._progress', ['step' => 3])
    <h1 class="text-xl font-extrabold text-white font-display mb-1">Pilih Kurir Instan</h1>
    <p class="text-xs mb-5" style="color:rgba(255,255,255,0.4);">Langkah 3 dari 4 — Dari alamatmu ke gudang RUTIP</p>

    <div class="flex items-start gap-2.5 px-4 py-3 rounded-xl mb-5" style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.09);">
        <x-lucide-map-pin class="w-4 h-4 shrink-0 mt-0.5" style="color:#a78bfa;" />
        <p class="text-xs leading-relaxed" style="color:rgba(255,255,255,0.6);">{{ $s['address'] ?? '' }}</p>
    </div>

    @if ($errors->any())
        <div class="rounded-xl px-4 py-2.5 mb-4 text-sm" style="background:rgba(239,68,68,0.1);border:1px solid rgba(239,68,68,0.3);color:#fca5a5;">{{ $errors->first() }}</div>
    @endif

    <form method="POST" action="{{ route('ruang-titip.kurir.store') }}">
        @csrf
        <input type="hidden" name="courier_code" id="courierCode">
        <input type="hidden" name="service_code" id="serviceCode">
        <div class="space-y-3 mb-6" id="courierList">
            <div class="rounded-2xl p-4 text-xs text-center" style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.08);color:rgba(255,255,255,0.45);">
                Menghitung ongkos kirim...
            </div>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('ruang-titip.alamat') }}" class="flex items-center justify-center gap-1.5 py-3.5 px-4 rounded-xl font-semibold text-sm hover:bg-white/5 shrink-0" style="border:1.5px solid rgba(255,255,255,0.18);color:rgba(255,255,255,0.65);"><x-lucide-chevron-left class="w-4 h-4" /> Kembali</a>
            <button type="submit" id="nextBtn" disabled class="flex-1 py-3.5 rounded-xl font-bold text-sm text-white flex items-center justify-center gap-2 transition-all hover:scale-[1.02] disabled:opacity-40" style="background:linear-gradient(135deg,#7c3aed,#6366f1);">Lanjutkan <x-lucide-arrow-right class="w-4 h-4" /></button>
        </div>
    </form>
</div>
<script>
(function () {
    const courierCode = document.getElementById('courierCode');
    const serviceCode = document.getElementById('serviceCode');
    const nextBtn = document.getElementById('nextBtn');
    const courierList = document.getElementById('courierList');

    function renderCouriers(pricing) {
        if (!pricing || !pricing.length) {
            courierList.innerHTML = '<div class="rounded-2xl p-4 text-xs text-center" style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.08);color:rgba(255,255,255,0.45);">Kurir instan tidak tersedia untuk rute ini.</div>';
            return;
        }

        window.renderCourierGroups(courierList, pricing, (c) => {
            courierCode.value = c.courier_code;
            serviceCode.value = c.courier_service_code || '';
            nextBtn.disabled = false;
        });
    }

    fetch('{{ route("ruang-titip.kurir.rates") }}', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
    })
    .then(r => r.json())
    .then(data => renderCouriers(data.pricing || []))
    .catch(() => renderCouriers([]));
})();
</script>
@endsection
