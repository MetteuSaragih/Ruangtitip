@extends('layouts.admin')

@section('title', 'Ruang & kapasitas')

@push('styles')
<style>
    .rooms { display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 16px; }
    .room { display: flex; flex-direction: column; gap: 12px; }
    .room-top { display: flex; gap: 12px; align-items: flex-start; }
    .room-top .thumb { width: 64px; height: 64px; border-radius: 10px; }
    .room-top h3 { font-size: 16px; }
    .room-top p { font-size: 12.5px; color: var(--color-text-secondary); }
    .room-top .st { margin-left: auto; }
    .room-nums { display: flex; justify-content: space-between; gap: 12px; font-size: 13.5px; }
    .room-nums small { display: block; color: var(--color-text-secondary); font-size: 12px; }
    .room-nums b { font-variant-numeric: tabular-nums; }
    .room-foot { display: flex; gap: 8px; flex-wrap: wrap; border-top: 1px solid var(--color-divider); padding-top: 12px; }
    .room-foot form { margin: 0; }
    .price-grid { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 10px; }
    .price-grid .field span { font-size: 12px; text-align: center; color: var(--color-text-secondary); }
    .price-grid .field span b { display: block; color: var(--color-ink); }
    .facs { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 8px; }
    .grp { border: 1px solid var(--color-border); border-radius: 12px; padding: 12px; display: grid; gap: 10px; }
    .grp > .lab { margin: 0; }
    dialog.dlg.wide { width: min(680px, 94vw); }
    @media (max-width: 860px) { .price-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); } }
</style>
@endpush

@section('content')
@php
    $rp = fn ($n) => 'Rp ' . number_format((int) $n, 0, ',', '.');

    // Normalisasi harga tersimpan (bisa berupa baris [id,label,price] atau peta id => harga).
    $normPrices = function ($pricing, string $type) {
        $out = [];
        foreach (($pricing[$type] ?? []) as $k => $row) {
            if (is_array($row)) {
                $out[$row['id'] ?? $k] = (int) ($row['price'] ?? 0);
            } else {
                $out[$k] = (int) $row;
            }
        }
        return $out;
    };

    $roomData = [];
    foreach ($rooms as $room) {
        $roomData[$room->id] = [
            'id' => $room->id,
            'name' => $room->name,
            'location' => $room->location,
            'address' => $room->address,
            'description' => $room->description,
            'capacity_total' => $room->capacity_total,
            'active' => (bool) $room->active,
            'facilities' => $room->facilities ?? [],
            'photos' => count($room->photos ?? []),
            'kardus' => $normPrices($room->pricing, 'kardus'),
            'koper' => $normPrices($room->pricing, 'koper'),
            'dimensiLain' => (int) ($room->pricing['dimensiLain'] ?? 0),
        ];
    }
    $editingId = old('_room');
@endphp

<div class="ph">
    <div>
        <h1>Ruang &amp; kapasitas</h1>
        <p>Kelola gudang yang tayang di aplikasi pelanggan: kapasitas, harga, dan fasilitas.</p>
    </div>
    <div class="acts">
        <button type="button" class="btn btn-sun" id="roomAddBtn"><svg class="ic" aria-hidden="true"><use href="#i-plus"/></svg> Tambah ruang</button>
    </div>
</div>

@if ($rooms->isEmpty())
    <section class="card"><div class="empty"><b>Belum ada ruang</b>Klik &quot;Tambah ruang&quot; untuk mulai.</div></section>
@else
    <div class="rooms">
        @foreach ($rooms as $room)
            @php
                $pct = $room->capacity_pct;
                $minP = collect($roomData[$room->id]['kardus'])->filter(fn ($p) => $p > 0)->min() ?? 0;
                $maxP = collect($roomData[$room->id]['koper'])->max() ?? 0;
            @endphp
            <article class="card room">
                <div class="room-top">
                    <div class="thumb">
                        @if ($room->primary_photo)
                            <img src="{{ asset('storage/' . $room->primary_photo) }}" alt="Foto {{ $room->name }}">
                        @else
                            <svg class="ic" aria-hidden="true"><use href="#n-ware"/></svg>
                        @endif
                    </div>
                    <div>
                        <h3>{{ $room->name }}</h3>
                        <p>{{ \Illuminate\Support\Str::limit($room->address, 60) }}{{ $room->location ? ', ' . $room->location : '' }}</p>
                    </div>
                    <span class="st {{ $room->active ? 'ok' : 'mute' }}">{{ $room->active ? 'Tayang' : 'Disembunyikan' }}</span>
                </div>

                <div>
                    <div class="room-nums" style="margin-bottom:6px">
                        <span><small>Terpakai</small><b>{{ $room->capacity_used }} / {{ $room->capacity_total }} slot</b></span>
                        <span style="text-align:right"><small>Okupansi</small><b>{{ $pct }}%</b></span>
                    </div>
                    <div class="meter {{ $pct >= 90 ? 'full' : ($pct >= 70 ? 'hi' : '') }}"><i style="width: {{ min(100, $pct) }}%"></i></div>
                </div>

                <div class="room-nums">
                    <span><small>Kardus mulai</small><b>{{ $rp($minP) }}</b></span>
                    <span style="text-align:right"><small>Koper hingga</small><b>{{ $rp($maxP) }}</b></span>
                </div>

                <div class="room-foot">
                    <button type="button" class="btn btn-ghost sm" data-edit-room="{{ $room->id }}">Ubah</button>
                    <form method="POST" action="{{ route('admin.ruang-titip.toggle-active', $room) }}">
                        @csrf
                        <button type="submit" class="btn btn-ghost sm" data-confirm="{{ $room->active ? 'Sembunyikan ' . $room->name . ' dari aplikasi pelanggan?' : 'Tayangkan ' . $room->name . ' di aplikasi pelanggan?' }}" data-confirm-ok="{{ $room->active ? 'Sembunyikan' : 'Tayangkan' }}">{{ $room->active ? 'Nonaktifkan' : 'Aktifkan' }}</button>
                    </form>
                    <form method="POST" action="{{ route('admin.ruang-titip.destroy', $room) }}" style="margin-left:auto">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-text" data-danger data-confirm-title="Hapus ruang" data-confirm="Hapus {{ $room->name }}? Ruang akan dihapus permanen." data-confirm-ok="Hapus">Hapus</button>
                    </form>
                </div>
            </article>
        @endforeach
    </div>
@endif

{{-- Dialog tambah / ubah ruang --}}
<dialog class="dlg wide" id="roomDialog" aria-labelledby="roomDialogT" @if ($errors->any() && old('name') !== null) data-reopen @endif>
    <form id="roomForm" method="POST" action="{{ route('admin.ruang-titip.store') }}" enctype="multipart/form-data">
        @csrf
        <span id="roomMethod"></span>
        <input type="hidden" name="_room" id="roomIdField" value="{{ $editingId }}">
        <div class="dh">
            <h3 id="roomDialogT">Tambah ruang</h3>
            <button class="x" type="button" data-close aria-label="Tutup"><svg class="ic" aria-hidden="true"><use href="#i-x"/></svg></button>
        </div>
        <div class="db">
            <div class="fgrid">
                <div class="field">
                    <label for="rName">Nama gudang</label>
                    <input class="input" id="rName" name="name" required value="{{ old('name') }}" placeholder="Contoh: Gudang Sumbersari">
                    @error('name')<span class="err-t">{{ $message }}</span>@enderror
                </div>
                <div class="field">
                    <label for="rLocation">Lokasi atau kecamatan</label>
                    <input class="input" id="rLocation" name="location" value="{{ old('location') }}" placeholder="Lowokwaru, Malang">
                    @error('location')<span class="err-t">{{ $message }}</span>@enderror
                </div>
                <div class="field full">
                    <label for="rAddress">Alamat lengkap</label>
                    <input class="input" id="rAddress" name="address" required value="{{ old('address') }}" placeholder="Jl. Sumbersari No. 12, Lowokwaru, Malang">
                    @error('address')<span class="err-t">{{ $message }}</span>@enderror
                </div>
                <div class="field full">
                    <label for="rDesc">Deskripsi</label>
                    <textarea class="input" id="rDesc" name="description" placeholder="Contoh: ruangan tertutup, ada CCTV, cocok untuk koper dan kardus.">{{ old('description') }}</textarea>
                    @error('description')<span class="err-t">{{ $message }}</span>@enderror
                </div>
                <div class="field">
                    <label for="rCap">Kapasitas total (slot)</label>
                    <input class="input num" id="rCap" name="capacity_total" type="number" min="1" required value="{{ old('capacity_total') }}" placeholder="500">
                    @error('capacity_total')<span class="err-t">{{ $message }}</span>@enderror
                </div>
                <div class="field">
                    <label for="rImages">Foto ruangan</label>
                    <input class="input" id="rImages" name="images[]" type="file" accept="image/png,image/jpeg" multiple>
                    <span class="help" id="rImagesHelp">Wajib minimal 1 foto, maksimal 10 foto, 5 MB per foto.</span>
                    @error('images')<span class="err-t">{{ $message }}</span>@enderror
                    @error('images.*')<span class="err-t">{{ $message }}</span>@enderror
                </div>
            </div>

            <div class="grp">
                <span class="lab">Harga per hari, kardus</span>
                <div class="price-grid">
                    @foreach ($defaultPricing['kardus'] as $row)
                        <div class="field">
                            <span><b>{{ $row['label'] }}</b>{{ $row['dims'] }}</span>
                            <input class="input num" type="number" min="0" name="pricing[kardus][{{ $row['id'] }}]" data-price="kardus.{{ $row['id'] }}" data-default="{{ $row['price'] }}" value="{{ old('pricing.kardus.' . $row['id'], $row['price']) }}" aria-label="Harga kardus {{ $row['label'] }}">
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="grp">
                <span class="lab">Harga per hari, koper</span>
                <div class="price-grid">
                    @foreach ($defaultPricing['koper'] as $row)
                        <div class="field">
                            <span><b>{{ $row['label'] }}</b>{{ $row['dims'] }}</span>
                            <input class="input num" type="number" min="0" name="pricing[koper][{{ $row['id'] }}]" data-price="koper.{{ $row['id'] }}" data-default="{{ $row['price'] }}" value="{{ old('pricing.koper.' . $row['id'], $row['price']) }}" aria-label="Harga koper {{ $row['label'] }}">
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="field">
                <label for="rDim">Dimensi lain (30x30x30 sampai 100x100x100 cm), harga per hari</label>
                <input class="input num" id="rDim" type="number" min="0" name="pricing[dimensiLain]" data-price="dimensiLain" data-default="{{ $defaultPricing['dimensiLain'] }}" value="{{ old('pricing.dimensiLain', $defaultPricing['dimensiLain']) }}">
                @error('pricing')<span class="err-t">{{ $message }}</span>@enderror
            </div>

            <div>
                <span class="lab">Fasilitas</span>
                <div class="facs" id="facsList" style="margin-top:6px">
                    @foreach ($allFacilities as $f)
                        <label class="check"><input type="checkbox" name="facilities[]" value="{{ $f }}" @checked(in_array($f, (array) old('facilities', [])))> <span>{{ $f }}</span></label>
                    @endforeach
                </div>
            </div>

            <label class="sw">
                <input type="hidden" name="active" value="0">
                <input type="checkbox" name="active" id="rActive" value="1" @checked(old('active', '1') == '1')>
                <i></i>
                <span>Tayang di aplikasi pelanggan</span>
            </label>
        </div>
        <div class="df">
            <button class="btn btn-ghost" type="button" data-close>Batal</button>
            <button class="btn btn-primary" type="submit" id="roomSubmit">Simpan ruang</button>
        </div>
    </form>
</dialog>
@endsection

@push('scripts')
<script>
(function () {
    var ROOMS = @json($roomData);
    var EDITING = @json($editingId);
    var STORE_URL = @json(route('admin.ruang-titip.store'));
    var UPDATE_URL = @json(url('/admin/ruang-titip'));

    var dlg = document.getElementById('roomDialog');
    var form = document.getElementById('roomForm');
    var methodBox = document.getElementById('roomMethod');
    var idField = document.getElementById('roomIdField');
    var title = document.getElementById('roomDialogT');
    var submit = document.getElementById('roomSubmit');
    var imgHelp = document.getElementById('rImagesHelp');

    function setMode(room) {
        if (room) {
            title.textContent = 'Ubah ruang';
            submit.textContent = 'Simpan perubahan';
            form.action = UPDATE_URL + '/' + room.id;
            methodBox.innerHTML = '<input type="hidden" name="_method" value="PUT">';
            idField.value = room.id;
            imgHelp.textContent = 'Foto sekarang: ' + room.photos + '. Foto baru akan ditambahkan, total maksimal 10.';
        } else {
            title.textContent = 'Tambah ruang';
            submit.textContent = 'Simpan ruang';
            form.action = STORE_URL;
            methodBox.innerHTML = '';
            idField.value = '';
            imgHelp.textContent = 'Wajib minimal 1 foto, maksimal 10 foto, 5 MB per foto.';
        }
    }

    function fill(room) {
        form.elements['name'].value = room.name || '';
        form.elements['location'].value = room.location || '';
        form.elements['address'].value = room.address || '';
        form.elements['description'].value = room.description || '';
        form.elements['capacity_total'].value = room.capacity_total || '';
        form.querySelectorAll('[data-price]').forEach(function (el) {
            var p = el.dataset.price;
            var v;
            if (p === 'dimensiLain') { v = room.dimensiLain; }
            else { var parts = p.split('.'); v = (room[parts[0]] || {})[parts[1]]; }
            el.value = (v === undefined || v === null) ? el.dataset.default : v;
        });
        form.querySelectorAll('#facsList input[type=checkbox]').forEach(function (cb) {
            cb.checked = (room.facilities || []).indexOf(cb.value) !== -1;
        });
        document.getElementById('rActive').checked = !!room.active;
        form.elements['images[]'].value = '';
    }

    function reset() {
        form.reset();
        form.querySelectorAll('[data-price]').forEach(function (el) { el.value = el.dataset.default; });
    }

    document.getElementById('roomAddBtn').addEventListener('click', function () {
        reset();
        setMode(null);
        dlg.showModal();
    });

    document.querySelectorAll('[data-edit-room]').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var room = ROOMS[btn.dataset.editRoom];
            if (!room) return;
            reset();
            setMode(room);
            fill(room);
            dlg.showModal();
        });
    });

    // Setelah validasi gagal: pulihkan mode ubah (isian lama sudah diisi server).
    if (EDITING && ROOMS[EDITING]) { setMode(ROOMS[EDITING]); }
})();
</script>
@endpush
