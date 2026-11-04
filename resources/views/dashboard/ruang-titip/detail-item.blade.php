@extends('layouts.rt')
@section('title', 'Detail Penitipan')

@php
    if (! function_exists('rp')) {
        function rp($n) { return 'Rp' . number_format($n, 0, ',', '.'); }
    }
    $selectedItems = old('items', $selectedItems ?? []);
    $dateStart = old('date_start', $s['date_start'] ?? '');
    $dateEnd = old('date_end', $s['date_end'] ?? '');
    $wa = 'https://wa.me/6285121091134?text=' . urlencode('Halo RuangTitip, saya mau titip barang yang ukurannya tidak ada di pilihan. Barangnya: ');
@endphp

@section('content')
@include('dashboard.ruang-titip._icons')
<div class="ttp">
<div class="stack">
    <nav class="crumbs" aria-label="Breadcrumb"><a href="{{ route('ruang-titip.index') }}">Ruang Titip</a><span aria-hidden="true">/</span>@if ($storage)<a href="{{ route('ruang-titip.detail', $storage) }}">{{ $storage->name }}</a>@else<span>Pesan</span>@endif<span aria-hidden="true">/</span><span aria-current="page">Pesan</span></nav>
    <div class="ph">
        <div>
            <h2 class="pt" tabindex="-1">Atur pesanan titipmu</h2>
            <p class="ps">Tentukan periode, lalu pilih ukuran barang. Harga langsung muncul di ringkasan.</p>
        </div>
    </div>

    @include('dashboard.ruang-titip._progress', ['step' => 1])

    <div class="two">
        <form method="POST" action="{{ route('ruang-titip.detail-item.store') }}" id="itemForm" class="stack">
            @csrf
            <input type="hidden" name="item_type" id="itemType" value="kardus">

            @if ($errors->any())
                <div class="alert a-err"><svg class="ic" aria-hidden="true"><use href="#i-warn"/></svg><div><b>{{ $errors->first() }}</b></div></div>
            @endif

            <section class="card stack" style="gap:16px">
                <h3>Periode penitipan</h3>
                <div class="grid2">
                    <div class="field"><label for="dateStart">Tanggal mulai</label><input class="input" type="date" id="dateStart" name="date_start" required min="{{ date('Y-m-d') }}" value="{{ $dateStart }}"></div>
                    <div class="field"><label for="dateEnd">Tanggal akhir</label><input class="input" type="date" id="dateEnd" name="date_end" required min="{{ date('Y-m-d') }}" value="{{ $dateEnd }}"></div>
                </div>
                <div class="field">
                    <span class="lab">Atau pilih durasi</span>
                    <div class="presets">
                        @foreach ([[14, '2 minggu'], [30, '1 bulan'], [60, '2 bulan'], [90, '3 bulan'], [180, '6 bulan']] as [$d, $l])
                            <button type="button" class="chip" data-preset="{{ $d }}" aria-pressed="false">{{ $l }}</button>
                        @endforeach
                    </div>
                </div>
                <p id="dur-info" aria-live="polite">Pilih tanggal mulai dan akhir dulu.</p>
                <div class="alert a-info">
                    <svg class="ic" aria-hidden="true"><use href="#i-info"/></svg>
                    <div><b>Harga dihitung per bulan</b><p>Kurang dari 30 hari tetap dihitung 1 bulan. Masa titip dihitung dari tanggal mulai; kalau barang datang terlambat, periode tidak bertambah otomatis.</p></div>
                </div>
            </section>

            <section class="card stack" style="gap:14px" id="barang">
                <div>
                    <h3>Barang yang dititip</h3>
                    <p class="help">Pilih jenis, lalu atur jumlah per ukuran. Boleh campur, misalnya 2 kardus kecil dan 1 koper.</p>
                </div>
                <div class="tabs" role="tablist" aria-label="Jenis barang">
                    @foreach (['kardus' => 'Kardus', 'koper' => 'Koper', 'dimensi' => 'Dimensi Lain'] as $key => $lbl)
                        <button type="button" class="chip" role="tab" aria-selected="{{ $key === 'kardus' ? 'true' : 'false' }}" data-type="{{ $key }}" onclick="switchType('{{ $key }}')">{{ $lbl }}</button>
                    @endforeach
                </div>

                @foreach (['kardus' => $kardus, 'koper' => $koper, 'dimensi' => $dimensi] as $type => $sizes)
                    <div class="stack size-group" data-group="{{ $type }}" style="gap:12px;{{ $type === 'kardus' ? '' : 'display:none;' }}">
                        @foreach ($sizes as $sz)
                            @php $qty = (int) ($selectedItems[$sz->code] ?? 0); $typeLabel = ['kardus' => 'Kardus', 'koper' => 'Koper', 'dimensi' => 'Dimensi Lain'][$type]; @endphp
                            <div class="ln size-row" data-price="{{ $sz->price }}" data-type-label="{{ $typeLabel }}" data-label="{{ $sz->label }}">
                                <div class="ln-top">
                                    <div>
                                        <b>{{ $sz->label }}</b>
                                        @if ($sz->dims && $sz->dims !== '-')<p class="help">{{ $sz->dims }}</p>@endif
                                        <p class="sz-sub" style="border:0;padding-top:4px">{{ rp($sz->price) }} <span class="help">per bulan</span></p>
                                    </div>
                                    <div class="field">
                                        <span class="lab">Jumlah</span>
                                        <div class="stepper">
                                            <button type="button" onclick="changeQty(this,-1)" aria-label="Kurangi {{ $sz->label }}">&minus;</button>
                                            <output class="qty-val" aria-live="polite">{{ $qty }}</output>
                                            <input type="hidden" name="items[{{ $sz->code }}]" value="{{ $qty }}" class="qty-input">
                                            <button type="button" onclick="changeQty(this,1)" aria-label="Tambah {{ $sz->label }}">+</button>
                                        </div>
                                    </div>
                                </div>
                                @if ($sz->code === 'dimensi_lain')
                                    @php $dimLainCfg = config('item_sizes.dimensi_lain'); @endphp
                                    <div class="field">
                                        <label for="dimensiLainWeight">Perkiraan berat per item (kg)</label>
                                        <input class="input" type="number" id="dimensiLainWeight" name="items_weight[dimensi_lain]"
                                            min="{{ $dimLainCfg['weight_min'] / 1000 }}" max="{{ $dimLainCfg['weight_max'] / 1000 }}"
                                            placeholder="Contoh: {{ $dimLainCfg['weight_default'] / 1000 }}"
                                            value="{{ old('items_weight.dimensi_lain', $s['dimensi_lain_weight_kg'] ?? '') }}">
                                        <p class="help">Dipakai untuk menghitung ongkir. Kosongkan untuk pakai perkiraan default ({{ $dimLainCfg['weight_default'] / 1000 }} kg).</p>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endforeach

                <div class="wa-note">
                    <svg class="ic" aria-hidden="true"><use href="#n-chat"/></svg>
                    <div><b>Ukuran barangmu tidak ada di pilihan?</b><p>Misalnya terlalu besar, terlalu berat, atau tidak bisa dikemas dalam kardus atau koper. Chat admin dulu, nanti kami bantu cek.</p></div>
                    <a class="btn btn-primary sm" href="{{ $wa }}" target="_blank" rel="noopener">Chat WhatsApp</a>
                </div>

                <details class="rules-mini">
                    <summary>Ketentuan blind box dan barang yang tidak diterima</summary>
                    <div class="grid2">
                        <div>
                            <h4 class="sub">Blind box</h4>
                            <ul class="bul"><li>Isi kemasan tidak dibuka petugas.</li><li>Yang diperiksa hanya jumlah, kategori, ukuran, kemasan, dan kondisi luar.</li><li>Kalau ukuran tidak sesuai, koreksi biaya masuk ke tagihan kedua.</li></ul>
                        </div>
                        <div>
                            <h4 class="sub">Tidak diterima</h4>
                            <ul class="bul">
                                <li>Makanan atau bahan yang mudah basi</li>
                                <li>Barang mudah terbakar atau meledak (tabung gas, bensin)</li>
                                <li>Hewan atau tanaman hidup</li>
                                <li>Uang tunai, perhiasan, dan surat berharga</li>
                                <li>Narkotika, senjata, dan barang ilegal</li>
                                <li>Barang bocor, berbau, atau kemasannya rusak</li>
                            </ul>
                        </div>
                    </div>
                </details>
            </section>

            <div class="nav-btns">
                <a href="{{ $storage ? route('ruang-titip.detail', $storage) : route('ruang-titip.index') }}" class="btn btn-ghost">Kembali</a>
                <button type="submit" class="btn btn-sun">Lanjut ke logistik</button>
            </div>
        </form>

        @include('dashboard.ruang-titip._summary', ['storage' => $storage, 's' => $s, 'calc' => $calc])
    </div>
</div>
</div>

@push('scripts')
<script>
    function switchType(type) {
        document.getElementById('itemType').value = type;
        document.querySelectorAll('.size-group').forEach(g => {
            g.style.display = g.dataset.group === type ? '' : 'none';
        });
        document.querySelectorAll('.tabs button[data-type]').forEach(b => {
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

    function fmt(d) { return new Date(d).toLocaleDateString('id-ID', {day:'numeric',month:'short',year:'numeric'}); }

    function recalc() {
        let totalItems = 0, base = 0;
        const selected = [];

        document.querySelectorAll('.size-row').forEach(row => {
            const v = parseInt(row.querySelector('.qty-val').textContent);
            if (v <= 0) return;
            totalItems += v;
            base += v * parseInt(row.dataset.price);
            selected.push(v + ' × ' + row.dataset.typeLabel + ' ' + row.dataset.label);
        });

        const months = monthsBetween();
        const itemSubtotal = base * months;
        const total = totalItems > 0 ? itemSubtotal + {{ $calc['platform_fee'] }} : 0;

        const sItems = document.getElementById('s-items');
        if (sItems) sItems.innerHTML = totalItems ? selected.join('<br>') : 'Belum ada barang';
        const sLines = document.getElementById('s-lines');
        if (sLines) {
            sLines.innerHTML = itemSubtotal ? '<div><span>Subtotal barang</span><b>Rp' + itemSubtotal.toLocaleString('id-ID') + '</b></div>' : '';
        }
        const sTotal = document.getElementById('s-total');
        if (sTotal) sTotal.textContent = 'Rp' + total.toLocaleString('id-ID');
    }

    function updateDuration() {
        const s = document.getElementById('dateStart').value;
        const e = document.getElementById('dateEnd').value;
        const text = document.getElementById('dur-info');
        const sDate = document.getElementById('s-date');
        let days = 0;
        if (s && e && new Date(e) > new Date(s)) {
            days = Math.round((new Date(e) - new Date(s)) / 86400000) + 1;
            const months = Math.max(1, Math.ceil((days - 1) / 30));
            text.innerHTML = 'Total <b>' + days + ' hari</b> (~' + months + ' bulan), ' + fmt(s) + ' sampai ' + fmt(e) + '.';
            if (sDate) sDate.textContent = fmt(s) + ' sampai ' + fmt(e) + ' (' + months + ' bln)';
        } else {
            text.textContent = 'Pilih tanggal mulai dan akhir dulu.';
            if (sDate) sDate.textContent = 'Belum dipilih';
        }
        document.querySelectorAll('[data-preset]').forEach(b => {
            b.setAttribute('aria-pressed', days === parseInt(b.dataset.preset) ? 'true' : 'false');
        });
        recalc();
    }

    document.querySelectorAll('[data-preset]').forEach(b => b.addEventListener('click', () => {
        const startEl = document.getElementById('dateStart');
        if (!startEl.value) startEl.value = new Date(Date.now() + 86400000).toISOString().slice(0, 10);
        const d = new Date(startEl.value);
        d.setDate(d.getDate() + parseInt(b.dataset.preset) - 1);
        document.getElementById('dateEnd').value = d.toISOString().slice(0, 10);
        updateDuration();
    }));
    document.getElementById('dateStart').addEventListener('change', updateDuration);
    document.getElementById('dateEnd').addEventListener('change', updateDuration);
    switchType('kardus');
    updateDuration();
</script>
@endpush
@endsection
