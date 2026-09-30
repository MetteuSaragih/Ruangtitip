@extends('layouts.ruang-titip')
@section('title', 'Pilih Kurir')

@php function rp($n){ return 'Rp'.number_format($n,0,',','.'); } @endphp

@section('content')
<x-courier-group-script />
<main class="wrap">
  <a class="back-link" href="{{ route('ruang-titip.alamat') }}">
    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5M11 18l-6-6 6-6"/></svg>
    Kembali
  </a>

  <div class="layout">
    <div>
      @include('dashboard.ruang-titip._progress', ['step' => 3])

      <div class="step-head">
        <h1>Pilih Kurir Instan</h1>
        <p>Dari alamatmu ke gudang RuangTitip.</p>
      </div>

      <div class="panel" style="display:flex;align-items:flex-start;gap:10px">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--tape-dark)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0;margin-top:2px"><path d="M12 21s-7-6.2-7-11.5a7 7 0 0 1 14 0C19 14.8 12 21 12 21z"/><circle cx="12" cy="9.5" r="2.5"/></svg>
        <p style="font-size:14px;color:var(--body)">{{ $s['address'] ?? '' }}</p>
      </div>

      @if ($errors->any())
        <p class="err" style="margin-top:12px">{{ $errors->first() }}</p>
      @endif

      <form method="POST" action="{{ route('ruang-titip.kurir.store') }}">
        @csrf
        <input type="hidden" name="courier_code" id="courierCode">
        <input type="hidden" name="service_code" id="serviceCode">
        <div id="courierList" style="margin-top:16px">
          <div class="panel" style="text-align:center;color:var(--muted);font-size:14px">Menghitung ongkos kirim...</div>
        </div>
        <div class="actions">
          <a href="{{ route('ruang-titip.alamat') }}" class="btn btn-outline">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
            Kembali
          </a>
          <button type="submit" id="nextBtn" disabled class="btn btn-primary">Lanjutkan
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
          </button>
        </div>
      </form>
    </div>

    @include('dashboard.ruang-titip._summary', ['storage' => $storage, 's' => $s, 'calc' => $calc])
  </div>
</main>

@push('scripts')
<script>
(function () {
    const courierCode = document.getElementById('courierCode');
    const serviceCode = document.getElementById('serviceCode');
    const nextBtn = document.getElementById('nextBtn');
    const courierList = document.getElementById('courierList');
    const sLines = document.getElementById('s-lines');
    const sTotal = document.getElementById('s-total');
    const baseTotal = {{ $calc['total'] }};
    const baseSubtotal = {{ $calc['itemSubtotal'] }};

    function renderCouriers(pricing) {
        if (!pricing || !pricing.length) {
            courierList.innerHTML = '<div class="panel" style="text-align:center;color:var(--muted);font-size:14px">Kurir instan tidak tersedia untuk rute ini.</div>';
            return;
        }

        window.renderCourierGroups(courierList, pricing, (c) => {
            courierCode.value = c.courier_code;
            serviceCode.value = c.courier_service_code || '';
            nextBtn.disabled = false;

            if (sLines) {
                sLines.innerHTML =
                    (baseSubtotal ? '<div class="line"><span>Subtotal barang</span><span>Rp' + baseSubtotal.toLocaleString('id-ID') + '</span></div>' : '') +
                    '<div class="line"><span>Kurir/anjem</span><span>Rp' + Number(c.price).toLocaleString('id-ID') + '</span></div>';
            }
            if (sTotal) sTotal.textContent = 'Rp' + (baseTotal + Number(c.price)).toLocaleString('id-ID');
        }, 'light');
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
@endpush
@endsection
