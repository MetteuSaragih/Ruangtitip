@extends('layouts.ruang-titip')
@section('title', 'Detail Penitipan')

@php
    function rp($n){ return 'Rp'.number_format($n,0,',','.'); }
    $selectedItems = old('items', $selectedItems ?? []);
    $dateStart = old('date_start', $s['date_start'] ?? '');
    $dateEnd = old('date_end', $s['date_end'] ?? '');
@endphp

@section('content')
<main class="wrap">
  <a class="back-link" href="{{ route('ruang-titip.index') }}">
    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5M11 18l-6-6 6-6"/></svg>
    Ganti gudang
  </a>

  <div class="layout">
    <div>
      @include('dashboard.ruang-titip._progress', ['step' => 1])

      <div class="step-head">
        <h1>Barang apa yang mau dititip?</h1>
        <p>Pilih tanggal dan jumlah barang. Harga langsung dihitung di ringkasan.</p>
      </div>

      @if ($errors->any())
        <p class="err" style="margin-top:12px">{{ $errors->first() }}</p>
      @endif

      <form method="POST" action="{{ route('ruang-titip.detail-item.store') }}" id="itemForm">
        @csrf
        <input type="hidden" name="item_type" id="itemType" value="kardus">

        <div class="panel">
          <h2>Kapan dititip?</h2>
          <p class="hint">Harga dihitung per bulan. Kurang dari 30 hari tetap dihitung 1 bulan.</p>
          <div class="row2">
            <div class="field"><label for="dateStart">Tanggal mulai</label><input type="date" id="dateStart" name="date_start" required min="{{ date('Y-m-d') }}" value="{{ $dateStart }}"></div>
            <div class="field"><label for="dateEnd">Tanggal selesai</label><input type="date" id="dateEnd" name="date_end" required min="{{ date('Y-m-d') }}" value="{{ $dateEnd }}"></div>
          </div>
          <div class="info empty" id="dur-info" aria-live="polite">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg>
            <span id="durText">Pilih tanggal mulai dan selesai dulu.</span>
          </div>
        </div>

        <div class="panel">
          <h2>Jenis &amp; ukuran barang</h2>
          <p class="hint">Boleh campur, misalnya 2 kardus kecil dan 1 koper.</p>
          <div class="seg" role="tablist" aria-label="Jenis barang">
            @foreach (['kardus' => 'Kardus', 'koper' => 'Koper', 'dimensi' => 'Dimensi Lain'] as $key => $lbl)
              <button type="button" role="tab" aria-selected="{{ $key === 'kardus' ? 'true' : 'false' }}" data-type="{{ $key }}" onclick="switchType('{{ $key }}')">{{ $lbl }}</button>
            @endforeach
          </div>

          @foreach (['kardus' => $kardus, 'koper' => $koper, 'dimensi' => $dimensi] as $type => $sizes)
            <div class="sizes size-group" data-group="{{ $type }}" style="{{ $type === 'kardus' ? '' : 'display:none;' }}">
              @foreach ($sizes as $sz)
                @php $qty = (int) ($selectedItems[$sz->code] ?? 0); $typeLabel = ['kardus' => 'Kardus', 'koper' => 'Koper', 'dimensi' => 'Dimensi Lain'][$type]; @endphp
                <div class="size size-row {{ $qty > 0 ? 'on' : '' }}" data-price="{{ $sz->price }}" data-type-label="{{ $typeLabel }}" data-label="{{ $sz->label }}">
                  <div>
                    <strong>{{ $sz->label }}</strong>
                    @if ($sz->dims && $sz->dims !== '-')<small>{{ $sz->dims }}</small>@endif
                    <span class="p">{{ rp($sz->price) }} /bln</span>
                  </div>
                  <div class="qty">
                    <button type="button" onclick="changeQty(this,-1)" aria-label="Kurangi {{ $sz->label }}">&minus;</button>
                    <output class="qty-val">{{ $qty }}</output>
                    <input type="hidden" name="items[{{ $sz->code }}]" value="{{ $qty }}" class="qty-input">
                    <button type="button" onclick="changeQty(this,1)" aria-label="Tambah {{ $sz->label }}">+</button>
                  </div>
                  @if ($sz->code === 'dimensi_lain')
                    @php $dimLainCfg = config('item_sizes.dimensi_lain'); @endphp
                    <div class="field" style="margin-top:10px">
                      <label for="dimensiLainWeight">Perkiraan berat per item (kg)</label>
                      <input type="number" id="dimensiLainWeight" name="items_weight[dimensi_lain]"
                        min="{{ $dimLainCfg['weight_min'] / 1000 }}" max="{{ $dimLainCfg['weight_max'] / 1000 }}"
                        placeholder="Contoh: {{ $dimLainCfg['weight_default'] / 1000 }}"
                        value="{{ old('items_weight.dimensi_lain', $s['dimensi_lain_weight_kg'] ?? '') }}">
                      <small class="hint">Dipakai untuk menghitung ongkir. Kosongkan untuk pakai perkiraan default ({{ $dimLainCfg['weight_default'] / 1000 }} kg).</small>
                    </div>
                  @endif
                </div>
              @endforeach
            </div>
          @endforeach
        </div>

        <div class="actions">
          <a href="{{ route('ruang-titip.index') }}" class="btn btn-outline">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
            Kembali
          </a>
          <button type="submit" class="btn btn-primary">Lanjutkan
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
    let currentType = 'kardus';

    function switchType(type) {
        currentType = type;
        document.getElementById('itemType').value = type;
        document.querySelectorAll('.size-group').forEach(g => {
            g.style.display = g.dataset.group === type ? '' : 'none';
        });
        document.querySelectorAll('.seg button').forEach(b => {
            b.setAttribute('aria-selected', b.dataset.type === type ? 'true' : 'false');
        });
        recalc();
    }

    function changeQty(btn, delta) {
        const row = btn.closest('.size-row');
        const out = row.querySelector('.qty-val');
        const input = row.querySelector('.qty-input');
        let v = Math.max(0, parseInt(out.textContent) + delta);
        out.textContent = v; input.value = v;
        row.classList.toggle('on', v > 0);
        recalc();
    }

    function monthsBetween() {
        const s = document.getElementById('dateStart').value;
        const e = document.getElementById('dateEnd').value;
        if (s && e && new Date(e) > new Date(s)) {
            const days = Math.round((new Date(e) - new Date(s)) / 86400000);
            return Math.max(1, Math.ceil(days / 30));
        }
        return 1;
    }

    function recalc() {
        let totalItems = 0, base = 0;
        const selected = [];

        document.querySelectorAll('.size-row').forEach(row => {
            const v = parseInt(row.querySelector('.qty-val').textContent);
            if (v <= 0) return;
            const price = parseInt(row.dataset.price);
            totalItems += v;
            base += v * price;
            selected.push({ name: row.dataset.typeLabel + ' ' + row.dataset.label, qty: v });
        });

        const months = monthsBetween();
        const itemSubtotal = base * months;
        const total = totalItems > 0 ? itemSubtotal + {{ $calc['platform_fee'] }} : 0;

        const sItems = document.getElementById('s-items');
        if (sItems) {
            sItems.innerHTML = totalItems
                ? selected.map(it => '<li><span>' + it.name + '</span><span>' + it.qty + '&times;</span></li>').join('')
                : '<li class="dim">Belum ada barang</li>';
        }
        const sLines = document.getElementById('s-lines');
        if (sLines) {
            sLines.innerHTML = itemSubtotal ? '<div class="line"><span>Subtotal barang</span><span>Rp' + itemSubtotal.toLocaleString('id-ID') + '</span></div>' : '';
        }
        const sTotal = document.getElementById('s-total');
        if (sTotal) sTotal.textContent = 'Rp' + total.toLocaleString('id-ID');
    }

    function updateDuration() {
        const s = document.getElementById('dateStart').value;
        const e = document.getElementById('dateEnd').value;
        const box = document.getElementById('dur-info');
        const text = document.getElementById('durText');
        const sDate = document.getElementById('s-date');
        if (s && e && new Date(e) > new Date(s)) {
            const days = Math.round((new Date(e) - new Date(s)) / 86400000);
            const months = Math.max(1, Math.ceil(days / 30));
            text.textContent = 'Durasi: ' + days + ' hari (~' + months + ' bulan)';
            box.classList.remove('empty');
            if (sDate) sDate.textContent = new Date(s).toLocaleDateString('id-ID', {day:'numeric',month:'short',year:'numeric'}) + ' – ' + new Date(e).toLocaleDateString('id-ID', {day:'numeric',month:'short',year:'numeric'}) + ' (' + months + ' bln)';
        } else {
            text.textContent = 'Pilih tanggal mulai dan selesai dulu.';
            box.classList.add('empty');
            if (sDate) sDate.textContent = 'Belum dipilih';
        }
        recalc();
    }
    document.getElementById('dateStart').addEventListener('change', updateDuration);
    document.getElementById('dateEnd').addEventListener('change', updateDuration);
    switchType('kardus');
    updateDuration();
</script>
@endpush
@endsection
