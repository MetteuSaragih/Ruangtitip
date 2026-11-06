@extends('layouts.rt')
@section('title', 'Detail Penitipan')

@php
    if (! function_exists('rp')) {
        function rp($n) { return 'Rp' . number_format($n, 0, ',', '.'); }
    }
    $selectedItems = old('items', $selectedItems ?? []);
    $dateStart = old('date_start', $s['date_start'] ?? '');
    $dateEnd = old('date_end', $s['date_end'] ?? '');
    $wa = 'https://wa.me/6285121091134?text=' . urlencode('Halo RuangTitip, saya mau titip barang yang ukurannya tidak ada di pilihan' . ($storage ? ' ' . $storage->name : '') . '. Barangnya: ');
    $largest = $sizes->last();
    $activeCode = optional($sizes->first(fn ($z) => (int) ($selectedItems[$z->code] ?? 0) > 0) ?? $sizes->first())->code;
@endphp

@section('content')
@include('dashboard.ruang-titip._icons')
<div class="ttp">
<div class="stack">
    <nav class="crumbs" aria-label="Breadcrumb"><a href="{{ route('ruang-titip.index') }}">Ruang Titip</a><span aria-hidden="true">/</span>@if ($storage)<a href="{{ route('ruang-titip.detail', $storage) }}">{{ $storage->name }}</a>@else<span>Pesan</span>@endif<span aria-hidden="true">/</span><span aria-current="page">Pesan</span></nav>
    <div class="ph">
        <div>
            <h2 class="pt" tabindex="-1">Atur pesanan titipmu</h2>
            <p class="ps">Tentukan periode, lalu pilih ukuran barang. Harga mengikuti paket termurah untuk lama titipmu.</p>
        </div>
    </div>

    @include('dashboard.ruang-titip._progress', ['step' => 1])

    <div class="two">
        <form method="POST" action="{{ route('ruang-titip.detail-item.store') }}" id="itemForm" class="stack">
            @csrf

            @if ($errors->any())
                <div class="alert a-err"><svg class="ic" aria-hidden="true"><use href="#i-warn"/></svg><div><b>{{ $errors->first() }}</b></div></div>
            @endif

            <section class="card stack">
                <h3>Periode penitipan</h3>
                <div class="grid2">
                    <div class="field"><label for="dateStart">Tanggal mulai</label><input class="input" type="date" id="dateStart" name="date_start" required min="{{ date('Y-m-d') }}" value="{{ $dateStart }}"></div>
                    <div class="field"><label for="dateEnd">Tanggal akhir</label><input class="input" type="date" id="dateEnd" name="date_end" required min="{{ date('Y-m-d') }}" value="{{ $dateEnd }}"></div>
                </div>
                <div class="field">
                    <span class="lab">Atau pilih durasi</span>
                    <div class="presets">
                        @foreach ([[7, '7 hari'], [30, '1 bulan'], [90, '3 bulan'], [180, '6 bulan'], [360, '12 bulan']] as [$d, $l])
                            <button type="button" class="chip" data-preset="{{ $d }}" aria-pressed="false">{{ $l }}</button>
                        @endforeach
                    </div>
                </div>
                <p id="dur-info" aria-live="polite">Pilih tanggal mulai dan akhir dulu.</p>
                <div class="alert a-info">
                    <svg class="ic" aria-hidden="true"><use href="#i-info"/></svg>
                    <div><b>Makin lama titip, makin hemat</b><p>Ada paket harian, 7 hari (bayar 6 hari), 1 bulan, 3, 6, dan 12 bulan. Untuk lama titip bebas, sistem memilih gabungan paket yang paling murah. Hari mulai dan hari selesai ikut dihitung, dan masa titip dihitung dari tanggal mulai walau barang datang terlambat.</p></div>
                </div>
            </section>

            <section class="card stack" id="barang">
                <div>
                    <h3>Barang yang dititip</h3>
                    <p class="help">{{ $storage ? $storage->name : 'Ruang ini' }} menerima ukuran {{ $sizes->pluck('code')->implode(', ') }}. Pilih ukuran, lalu atur jumlahnya. Boleh campur ukuran, misalnya 2 barang ukuran S dan 1 barang ukuran M.</p>
                </div>

                <div class="szs" role="radiogroup" aria-label="Ukuran barang">
                    @foreach ($sizes as $sz)
                        @php $qty = (int) ($selectedItems[$sz->code] ?? 0); @endphp
                        <div class="sz">
                            <input type="radio" name="sz_view" id="sz-{{ $sz->code }}" value="{{ $sz->code }}" @checked($sz->code === $activeCode)>
                            <label for="sz-{{ $sz->code }}">
                                <span class="sz-top"><b>{{ $sz->code }}</b><span class="sz-price">{{ rp($sz->price) }}<small>/hari</small></span></span>
                                <span class="sz-row"><svg class="ic" aria-hidden="true"><use href="#n-box"/></svg>Sisi terpanjang maks. {{ $sz->side }} cm</span>
                                <span class="sz-row"><svg class="ic" aria-hidden="true"><use href="#n-box"/></svg>P+L+T maks. {{ $sz->sum }} cm</span>
                                <span class="sz-row"><svg class="ic" aria-hidden="true"><use href="#n-bag"/></svg>Berat maks. {{ $sz->kg }} kg</span>
                                <span class="sz-qty" data-badge @if ($qty <= 0) hidden @endif>{{ $qty }} dipilih</span>
                            </label>
                        </div>
                    @endforeach
                </div>

                @foreach ($sizes as $sz)
                    @php $qty = (int) ($selectedItems[$sz->code] ?? 0); @endphp
                    @php $tierData = collect($sz->tiers)->map(fn ($t) => ['days' => $t['days'], 'price' => $t['price'], 'label' => $t['label']])->values(); @endphp
                    <div class="ln size-panel" data-code="{{ $sz->code }}" data-label="{{ $sz->label }}" data-price="{{ $sz->price }}" data-unit="{{ $sz->unit }}" data-tiers="{{ json_encode($tierData) }}" @if ($sz->code !== $activeCode) hidden @endif>
                        <div class="ln-top">
                            <div>
                                <b>Jumlah barang {{ $sz->label }}</b>
                                <p class="help">Satu barang ukuran {{ $sz->code }} memakai {{ $sz->unit }} setara ukuran S di ruang.</p>
                            </div>
                            <div class="field">
                                <div class="qty-step" role="group" aria-label="Jumlah {{ $sz->label }}">
                                    <button type="button" onclick="changeQty(this,-1)" aria-label="Kurangi {{ $sz->label }}">&minus;</button>
                                    <output class="qty-val" aria-live="polite">{{ $qty }}</output>
                                    <input type="hidden" name="items[{{ $sz->code }}]" value="{{ $qty }}" class="qty-input">
                                    <button type="button" onclick="changeQty(this,1)" aria-label="Tambah {{ $sz->label }}">+</button>
                                </div>
                            </div>
                        </div>
                        <div class="sz-info">
                            <p><b>{{ $sz->label }} cocok untuk:</b> {{ $sz->fits }}.</p>
                            <ul class="sz-spec">
                                <li>Sisi terpanjang <b>maks. {{ $sz->side }} cm</b></li>
                                <li>Panjang + lebar + tinggi <b>maks. {{ $sz->sum }} cm</b></li>
                                <li>Berat <b>maks. {{ $sz->kg }} kg</b></li>
                            </ul>
                            <div class="tier-grid" aria-label="Paket harga {{ $sz->label }}">
                                @foreach ($sz->tiers as $t)
                                    <div><span>{{ $t['label'] }}</span><b>{{ rp($t['price']) }}</b><small>{{ $t['note'] }}</small></div>
                                @endforeach
                            </div>
                            <p class="sz-sub" data-line aria-live="polite"></p>
                        </div>
                    </div>
                @endforeach

                <div class="sz-tally" id="tally" aria-live="polite"></div>

                <div class="wa-note">
                    <svg class="ic" aria-hidden="true"><use href="#n-chat"/></svg>
                    <div><b>Ukuran barangmu tidak ada di pilihan?</b><p>Misalnya sisi terpanjangnya lebih dari {{ $largest->side ?? 0 }} cm, panjang + lebar + tinggi lebih dari {{ $largest->sum ?? 0 }} cm, atau beratnya lebih dari {{ $largest->kg ?? 0 }} kg. Hubungi admin lewat WhatsApp, nanti kami bantu cek dan tawarkan solusinya.</p></div>
                    <a class="btn btn-primary sm" href="{{ $wa }}" target="_blank" rel="noopener">Hubungi admin via WhatsApp</a>
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
                <button type="submit" class="btn btn-sun">Lanjut ke jadwal antar</button>
            </div>
        </form>

        @include('dashboard.ruang-titip._summary', ['storage' => $storage, 's' => $s, 'calc' => $calc])
    </div>
</div>
</div>

@push('scripts')
<script>
    const REMAINING_UNITS = {{ (int) $remainingUnits }};
    const PLATFORM_FEE = {{ (int) $calc['platform_fee'] }};
    const MAX_QTY = 99;

    function rpf(n) { return 'Rp' + Math.round(n).toLocaleString('id-ID'); }

    function showSize(code) {
        document.querySelectorAll('.size-panel').forEach(p => { p.hidden = p.dataset.code !== code; });
    }

    function changeQty(btn, delta) {
        const panel = btn.closest('.size-panel');
        const out = panel.querySelector('.qty-val');
        const input = panel.querySelector('.qty-input');
        const v = Math.min(MAX_QTY, Math.max(0, (parseInt(out.textContent, 10) || 0) + delta));
        out.textContent = v; input.value = v;
        recalc();
    }

    function daysBetween() {
        const s = document.getElementById('dateStart').value;
        const e = document.getElementById('dateEnd').value;
        if (s && e && new Date(e) > new Date(s)) {
            return Math.round((new Date(e) - new Date(s)) / 86400000) + 1;
        }
        return 0;
    }

    function fmt(d) { return new Date(d).toLocaleDateString('id-ID', {day:'numeric',month:'short',year:'numeric'}); }

    // Biaya termurah satu barang untuk 'days' hari: gabungan paket (boleh melebihi lama titip bila lebih murah).
    // Aturannya sama persis dengan StorageSizes::cost() di server.
    function bestCost(tiers, days) {
        const t = tiers.filter(x => x.price > 0 && x.days > 0).slice().sort((a, b) => b.days - a.days);
        if (!t.length) return { total: 0, lines: [] };
        const best = [0], pick = [];
        for (let d = 1; d <= days; d++) {
            best[d] = Infinity;
            for (let i = 0; i < t.length; i++) {
                const c = t[i].price + best[Math.max(0, d - t[i].days)];
                if (c < best[d]) { best[d] = c; pick[d] = i; }
            }
        }
        const counts = {};
        for (let d = days; d > 0; d = Math.max(0, d - t[pick[d]].days)) { counts[pick[d]] = (counts[pick[d]] || 0) + 1; }
        const lines = Object.keys(counts).map(Number).sort((a, b) => b - a).map(i => ({ label: t[i].label, price: t[i].price, qty: counts[i] }));
        return { total: best[days], lines: lines };
    }

    function recalc() {
        const days = Math.max(daysBetween(), 1);
        let totalItems = 0, itemSubtotal = 0, units = 0;
        const selected = [], rows = [], sumRows = [];

        document.querySelectorAll('.size-panel').forEach(panel => {
            const code = panel.dataset.code;
            const tiers = JSON.parse(panel.dataset.tiers || '[]');
            const v = parseInt(panel.querySelector('.qty-val').textContent, 10) || 0;
            const one = bestCost(tiers, days);
            const planText = one.lines.map(l => l.qty + ' × paket ' + l.label).join(', ');
            const line = panel.querySelector('[data-line]');
            if (line) {
                line.innerHTML = 'Untuk ' + days + ' hari: <b>' + rpf(one.total) + '</b> per barang (' + planText + ')' +
                    (v > 0 ? '. ' + v + ' barang = <b>' + rpf(one.total * v) + '</b>' : '');
            }
            const badge = document.querySelector('label[for="sz-' + code + '"] [data-badge]');
            if (badge) { badge.textContent = v + ' dipilih'; badge.hidden = v <= 0; }
            if (v <= 0) return;
            totalItems += v;
            itemSubtotal += one.total * v;
            units += v * parseInt(panel.dataset.unit, 10);
            selected.push(v + ' × ' + panel.dataset.label);
            rows.push('<div><span>' + v + ' &times; ' + panel.dataset.label + '<small>' + planText + ' per barang</small></span><b>' + rpf(one.total * v) + '</b></div>');
            sumRows.push('<div><span>' + v + ' &times; ' + panel.dataset.label + '<small>' + planText + '</small></span><b>' + rpf(one.total * v) + '</b></div>');
        });

        const total = totalItems > 0 ? itemSubtotal + PLATFORM_FEE : 0;

        const tally = document.getElementById('tally');
        if (tally) {
            if (totalItems > 0) {
                const over = units > REMAINING_UNITS;
                tally.innerHTML = '<div class="rows">' + rows.join('') +
                    '<div class="tot"><span>Subtotal barang (' + days + ' hari)</span><b>' + rpf(itemSubtotal) + '</b></div></div>' +
                    '<p class="help">Kebutuhan ruang ' + units + ' setara ukuran S, sisa kapasitas ' + REMAINING_UNITS + ' setara ukuran S.</p>' +
                    (over ? '<p class="msg" role="alert">Barangmu melebihi sisa kapasitas ruang ini. Kurangi jumlah barang atau pilih ruang lain.</p>' : '');
            } else {
                tally.innerHTML = '<p class="help">Belum ada barang. Pilih ukuran, lalu tambah jumlahnya.</p>';
            }
        }

        const sItems = document.getElementById('s-items');
        if (sItems) sItems.innerHTML = totalItems ? selected.join('<br>') + '<small>' + days + ' hari</small>' : 'Belum ada barang';
        const sLines = document.getElementById('s-lines');
        if (sLines) {
            sLines.innerHTML = totalItems ? sumRows.join('') + '<div><span>Biaya layanan</span><b>' + rpf(PLATFORM_FEE) + '</b></div>' : '';
        }
        const sTotal = document.getElementById('s-total');
        if (sTotal) sTotal.textContent = rpf(total);
    }

    function updateDuration() {
        const s = document.getElementById('dateStart').value;
        const e = document.getElementById('dateEnd').value;
        const text = document.getElementById('dur-info');
        const sDate = document.getElementById('s-date');
        const days = daysBetween();
        if (days > 0) {
            text.innerHTML = 'Total <b>' + days + ' hari</b>, ' + fmt(s) + ' sampai ' + fmt(e) + '.';
            if (sDate) sDate.textContent = fmt(s) + ' sampai ' + fmt(e) + ' (' + days + ' hari)';
        } else {
            text.textContent = 'Pilih tanggal mulai dan akhir dulu.';
            if (sDate) sDate.textContent = 'Belum dipilih';
        }
        document.querySelectorAll('[data-preset]').forEach(b => {
            b.setAttribute('aria-pressed', days === parseInt(b.dataset.preset, 10) ? 'true' : 'false');
        });
        recalc();
    }

    document.querySelectorAll('[data-preset]').forEach(b => b.addEventListener('click', () => {
        const startEl = document.getElementById('dateStart');
        if (!startEl.value) startEl.value = new Date(Date.now() + 86400000).toISOString().slice(0, 10);
        const d = new Date(startEl.value);
        d.setDate(d.getDate() + parseInt(b.dataset.preset, 10) - 1);
        document.getElementById('dateEnd').value = d.toISOString().slice(0, 10);
        updateDuration();
    }));
    document.querySelectorAll('input[name="sz_view"]').forEach(r => r.addEventListener('change', () => showSize(r.value)));
    document.getElementById('dateStart').addEventListener('change', updateDuration);
    document.getElementById('dateEnd').addEventListener('change', updateDuration);
    updateDuration();
</script>
@endpush
@endsection
