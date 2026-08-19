@extends('layouts.dashboard')
@section('title', 'Detail Penitipan')

@php
    function rp($n){ return 'Rp '.number_format($n,0,',','.'); }
    $selectedItems = old('items', $selectedItems ?? []);
    $dateStart = old('date_start', $s['date_start'] ?? '');
    $dateEnd = old('date_end', $s['date_end'] ?? '');
@endphp

@section('content')
<div class="max-w-xl mx-auto pt-6 pb-8">
    @include('dashboard.ruang-titip._progress', ['step' => 1])
    <h1 class="text-xl font-extrabold text-white font-display mb-1">Detail Penitipan Barang</h1>
    <p class="text-xs mb-5" style="color:rgba(255,255,255,0.4);">Langkah 1 dari 4 - Pilih barang dan rentang waktu penitipan</p>

    @if ($errors->any())
        <div class="rounded-xl px-4 py-2.5 mb-4 text-sm" style="background:rgba(239,68,68,0.1);border:1px solid rgba(239,68,68,0.3);color:#fca5a5;">{{ $errors->first() }}</div>
    @endif

    <form method="POST" action="{{ route('ruang-titip.detail-item.store') }}" id="itemForm">
        @csrf
        <input type="hidden" name="item_type" id="itemType" value="kardus">

        {{-- ── RENTANG WAKTU PENITIPAN (langsung di bawah judul) ── --}}
        <div class="rounded-2xl p-5 mb-5" style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.09);">
            <p class="text-xs font-bold text-white mb-3">Rentang Waktu Penitipan</p>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-[11px] font-semibold mb-2" style="color:rgba(255,255,255,0.55);">Tanggal Mulai</label>
                    <input type="date" name="date_start" id="dateStart" required min="{{ date('Y-m-d') }}" value="{{ $dateStart }}"
                           class="w-full px-4 py-3 rounded-xl text-sm text-white outline-none"
                           style="background:rgba(255,255,255,0.06);border:1.5px solid rgba(255,255,255,0.1);color-scheme:dark;">
                </div>
                <div>
                    <label class="block text-[11px] font-semibold mb-2" style="color:rgba(255,255,255,0.55);">Tanggal Selesai</label>
                    <input type="date" name="date_end" id="dateEnd" required min="{{ date('Y-m-d') }}" value="{{ $dateEnd }}"
                           class="w-full px-4 py-3 rounded-xl text-sm text-white outline-none"
                           style="background:rgba(255,255,255,0.06);border:1.5px solid rgba(255,255,255,0.1);color-scheme:dark;">
                </div>
            </div>
            <div id="durBox" class="hidden mt-3 items-center gap-2 px-3 py-2 rounded-lg" style="background:rgba(52,211,153,0.1);border:1px solid rgba(52,211,153,0.2);">
                <x-lucide-clock class="w-3.5 h-3.5" style="color:#34d399;" />
                <span class="text-[11px] font-semibold" style="color:rgba(255,255,255,0.7);">Durasi: <span id="durText" class="text-white"></span></span>
            </div>
        </div>

        {{-- ── PILIH TIPE ── --}}
        <div class="flex gap-2 mb-5">
            @foreach (['kardus' => '📦 Kardus', 'koper' => '🧳 Koper', 'dimensi' => '📐 Dimensi Lain'] as $key => $lbl)
                <button type="button" data-type="{{ $key }}" onclick="switchType('{{ $key }}')"
                        class="type-btn flex-1 py-2.5 rounded-xl text-xs font-bold transition-all"
                        style="background:rgba(255,255,255,0.05);color:rgba(255,255,255,0.5);border:1px solid rgba(255,255,255,0.1);">
                    {{ $lbl }}
                </button>
            @endforeach
        </div>

        {{-- ── DAFTAR UKURAN (harga beda per ukuran, sesuai dokumen) ── --}}
        <div class="rounded-2xl overflow-hidden mb-5" style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.09);">
            <div class="px-4 pt-4 pb-2">
                <p class="text-xs font-bold text-white">Pilih Ukuran &amp; Jumlah</p>
                <p class="text-[10px] mt-0.5" style="color:rgba(255,255,255,0.38);">Harga berbeda untuk tiap ukuran (per bulan)</p>
            </div>
            @foreach (['kardus' => $kardus, 'koper' => $koper, 'dimensi' => $dimensi] as $type => $sizes)
                <div class="size-group grid grid-cols-1 sm:grid-cols-2 gap-3 p-4" data-group="{{ $type }}" style="{{ $type === 'kardus' ? '' : 'display:none;' }}">
                    @foreach ($sizes as $sz)
                        @php
                            $qty = (int) ($selectedItems[$sz->code] ?? 0);
                            $typeLabel = ['kardus' => 'Kardus', 'koper' => 'Koper', 'dimensi' => 'Dimensi Lain'][$type];
                        @endphp
                        <div class="size-row rounded-2xl"
                             data-price="{{ $sz->price }}"
                             data-type-label="{{ $typeLabel }}"
                             data-label="{{ $sz->label }}"
                             style="background:{{ $qty > 0 ? 'rgba(124,58,237,0.12)' : 'rgba(255,255,255,0.04)' }};border:1.5px solid {{ $qty > 0 ? 'rgba(124,58,237,0.4)' : 'rgba(255,255,255,0.08)' }};padding:14px 16px;">
                            {{-- Label + harga --}}
                            <div style="margin-bottom:12px;">
                                <p class="text-sm font-bold text-white">{{ $sz->label }}</p>
                                @if ($sz->dims && $sz->dims !== '-')
                                    <p class="text-[10px] mt-1" style="color:rgba(255,255,255,0.4);">{{ $sz->dims }}</p>
                                @endif
                                <p class="text-sm font-extrabold" style="color:#a78bfa;margin-top:4px;">{{ rp($sz->price) }}<span class="font-normal text-[10px]" style="color:rgba(255,255,255,0.35);">/bln</span></p>
                            </div>
                            {{-- Qty controls --}}
                            <div class="flex items-center gap-3">
                                <button type="button" onclick="changeQty(this,-1)"
                                        class="w-8 h-8 rounded-xl flex items-center justify-center shrink-0"
                                        style="background:rgba(255,255,255,0.08);color:rgba(255,255,255,0.7);border:1px solid rgba(255,255,255,0.1);">
                                    <x-lucide-minus class="w-3.5 h-3.5" />
                                </button>
                                <span class="qty flex-1 text-center text-sm font-bold text-white">{{ $qty }}</span>
                                <input type="hidden" name="items[{{ $sz->code }}]" value="{{ $qty }}" class="qty-input">
                                <button type="button" onclick="changeQty(this,1)"
                                        class="w-8 h-8 rounded-xl flex items-center justify-center shrink-0"
                                        style="background:rgba(124,58,237,0.25);color:#a78bfa;border:1px solid rgba(124,58,237,0.4);">
                                    <x-lucide-plus class="w-3.5 h-3.5" />
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endforeach
        </div>

        {{-- Total live --}}
        <div id="totalBox" class="hidden items-center justify-between px-4 py-3.5 rounded-xl mb-5" style="background:rgba(124,58,237,0.12);border:1px solid rgba(124,58,237,0.3);">
            <div>
                <p class="text-xs font-semibold text-white"><span id="totalItems">0</span> item dipilih</p>
                <p class="text-[10px] mt-0.5" style="color:rgba(255,255,255,0.45);">Estimasi biaya item × durasi</p>
            </div>
            <p class="text-base font-extrabold font-display" style="color:#a78bfa;" id="totalPrice">Rp 0</p>
        </div>
        <div id="selectedList" class="hidden -mt-3 mb-5 space-y-2"></div>

        <div class="flex gap-3">
            <a href="{{ route('ruang-titip.index') }}" class="flex items-center justify-center gap-1.5 py-3.5 px-4 rounded-xl font-semibold text-sm transition-all hover:bg-white/5 shrink-0" style="border:1.5px solid rgba(255,255,255,0.18);color:rgba(255,255,255,0.65);">
                <x-lucide-chevron-left class="w-4 h-4" /> Kembali
            </a>
            <button type="submit" class="flex-1 py-3.5 rounded-xl font-bold text-sm text-white flex items-center justify-center gap-2 transition-all hover:scale-[1.02]" style="background:linear-gradient(135deg,#7c3aed,#6366f1);box-shadow:0 6px 20px rgba(124,58,237,0.4);">
                Lanjutkan <x-lucide-arrow-right class="w-4 h-4" />
            </button>
        </div>
    </form>
</div>

<script>
    let currentType = 'kardus';

    function switchType(type) {
        currentType = type;
        document.getElementById('itemType').value = type;
        document.querySelectorAll('.size-group').forEach(g => {
            g.style.display = g.dataset.group === type ? '' : 'none';
        });
        document.querySelectorAll('.type-btn').forEach(b => {
            const on = b.dataset.type === type;
            b.style.background = on ? 'linear-gradient(135deg,#7c3aed,#6366f1)' : 'rgba(255,255,255,0.05)';
            b.style.color = on ? 'white' : 'rgba(255,255,255,0.5)';
            b.style.border = on ? 'none' : '1px solid rgba(255,255,255,0.1)';
        });
        recalc();
    }

    function changeQty(btn, delta) {
        const row = btn.closest('.size-row');
        const qtyEl = row.querySelector('.qty');
        const input = row.querySelector('.qty-input');
        let v = Math.max(0, parseInt(qtyEl.textContent) + delta);
        qtyEl.textContent = v; input.value = v;
        row.style.background = v > 0 ? 'rgba(124,58,237,0.12)' : 'rgba(255,255,255,0.04)';
        row.style.borderColor = v > 0 ? 'rgba(124,58,237,0.4)' : 'rgba(255,255,255,0.08)';
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
            const v = parseInt(row.querySelector('.qty').textContent);
            if (v <= 0) return;

            const price = parseInt(row.dataset.price);
            totalItems += v;
            base += v * price;
            selected.push({
                name: row.dataset.typeLabel + ' ' + row.dataset.label,
                qty: v,
                price: price,
            });
        });

        const total = base * monthsBetween();
        const box = document.getElementById('totalBox');
        box.classList.toggle('hidden', totalItems === 0);
        box.classList.toggle('flex', totalItems > 0);
        document.getElementById('totalItems').textContent = totalItems;
        document.getElementById('totalPrice').textContent = 'Rp ' + total.toLocaleString('id-ID');

        const list = document.getElementById('selectedList');
        list.classList.toggle('hidden', totalItems === 0);
        list.innerHTML = selected.map(item => `
            <div class="flex items-center justify-between gap-3 px-4 py-2.5 rounded-xl" style="background:rgba(255,255,255,0.035);border:1px solid rgba(255,255,255,0.08);">
                <span class="text-xs font-semibold text-white">${item.name}</span>
                <span class="text-xs shrink-0" style="color:rgba(255,255,255,0.55);">${item.qty} item</span>
            </div>
        `).join('');
    }

    function updateDuration() {
        const s = document.getElementById('dateStart').value;
        const e = document.getElementById('dateEnd').value;
        const box = document.getElementById('durBox');
        if (s && e && new Date(e) > new Date(s)) {
            const days = Math.round((new Date(e) - new Date(s)) / 86400000);
            document.getElementById('durText').textContent = days + ' hari (~' + Math.ceil(days/30) + ' bulan)';
            box.classList.remove('hidden'); box.classList.add('flex');
        } else { box.classList.add('hidden'); box.classList.remove('flex'); }
        recalc();
    }
    document.getElementById('dateStart').addEventListener('change', updateDuration);
    document.getElementById('dateEnd').addEventListener('change', updateDuration);
    switchType('kardus');
    updateDuration();
</script>
@endsection
