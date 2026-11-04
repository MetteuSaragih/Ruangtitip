@extends('layouts.admin')

@section('title', 'Ruang & kapasitas')

@push('styles')
<style>
    .rooms { display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 16px; }
    .room { display: flex; flex-direction: column; gap: 12px; }
    .room-top { display: flex; gap: 12px; align-items: flex-start; }
    .room-top .thumb { width: 64px; height: 64px; border-radius: 10px; flex: none; }
    .room-top h3 { font-size: 16px; }
    .room-top p { font-size: 12.5px; color: var(--color-text-secondary); }
    .room-top .st { margin-left: auto; }
    .room-nums { display: flex; justify-content: space-between; gap: 12px; font-size: 13.5px; }
    .room-nums small { display: block; color: var(--color-text-secondary); font-size: 12px; }
    .room-nums b { font-variant-numeric: tabular-nums; }
    .room-tags { display: flex; gap: 6px; flex-wrap: wrap; }
    .room-foot { display: flex; gap: 8px; flex-wrap: wrap; border-top: 1px solid var(--color-divider); padding-top: 12px; }
    .room-foot form { margin: 0; }
    .price-grid { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 12px; }
    .price-grid .field > span { font-size: 12px; color: var(--color-text-secondary); display: block; margin-top: 4px; }
    .price-grid .field > label { font-weight: 700; }
    .grp { border: 1px solid var(--color-border); border-radius: 12px; padding: 12px; display: grid; gap: 10px; }
    .grp > .lab { margin: 0; }
    .size-pick { display: grid; gap: 8px; }
    .size-pick .check small { display: block; color: var(--color-text-secondary); font-size: 12px; }
    dialog.dlg.wide { width: min(680px, 94vw); }
    .photo-edit { display: grid; grid-template-columns: repeat(auto-fill, minmax(92px, 1fr)); gap: 8px; margin-bottom: 8px; }
    .photo-tile { position: relative; display: grid; gap: 4px; font-size: 12px; color: var(--color-text-secondary); cursor: pointer; }
    .photo-tile img { width: 100%; aspect-ratio: 4 / 3; object-fit: cover; border-radius: 8px; border: 1px solid var(--color-border); }
    .photo-tile input { position: absolute; top: 6px; right: 6px; width: 18px; height: 18px; }
    .photo-tile input:checked ~ span { color: var(--color-danger-text); font-weight: 700; }
    .photo-tile:has(input:checked) img { opacity: 0.4; }
    @media (max-width: 860px) { .price-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); } }
</style>
@endpush

@section('content')
@php
    $rp = fn ($n) => 'Rp ' . number_format((int) $n, 0, ',', '.');
    $codes = array_keys($sizes);

    $roomData = [];
    foreach ($rooms as $room) {
        $roomData[$room->id] = [
            'id' => $room->id,
            'name' => $room->name,
            'title' => $room->title,
            'location' => $room->location,
            'address' => $room->address,
            'description' => $room->description,
            'long_description' => $room->long_description,
            'sizes' => $room->accepted_sizes,
            'facilities' => array_values((array) ($room->facilities ?? [])),
            'rules' => array_values((array) ($room->rules ?? [])),
            'capacity_total' => $room->capacity_total,
            'active' => (bool) $room->active,
            'photoList' => array_values($room->photos ?? []),
            'photoBase' => asset('storage') . '/',
            'hours' => $room->hours,
        ];
    }
    $editingId = old('_room');
    $hasOld = old('name') !== null;
    $pickedSizes = $hasOld ? (array) old('sizes', []) : $codes;
@endphp

<div class="ph">
    <div>
        <h1>Ruang &amp; kapasitas</h1>
        <p>Kelola gudang yang tayang di aplikasi pelanggan: kapasitas, ukuran yang diterima, dan harga per ukuran.</p>
    </div>
    <div class="acts">
        <button type="button" class="btn btn-sun" id="roomAddBtn"><svg class="ic" aria-hidden="true"><use href="#i-plus"/></svg> Tambah ruang</button>
    </div>
</div>

<section class="card" style="margin-bottom:16px">
    <div class="card-h"><h3>Harga per ukuran</h3><small>per barang per hari, berlaku di semua ruang</small></div>
    <form method="POST" action="{{ route('admin.ruang-titip.sizes') }}">
        @csrf
        <div class="price-grid">
            @foreach ($sizes as $code => $spec)
                <div class="field">
                    <label for="price{{ $code }}">{{ $spec['label'] }} (Rp per hari)</label>
                    <input class="input num" id="price{{ $code }}" name="prices[{{ $code }}]" type="number" min="1000" step="1" inputmode="numeric" required value="{{ old('prices.' . $code, $spec['price']) }}">
                    <span>Kardus {{ $spec['box'] }}@if (! empty($spec['koper'])), koper {{ $spec['koper'] }}@endif, maks {{ $spec['kg'] }} kg</span>
                    <span>Jatah ruang {{ $spec['unit'] }} setara ukuran S</span>
                    @error('prices.' . $code)<span class="err-t">{{ $message }}</span>@enderror
                </div>
            @endforeach
        </div>
        <div style="display:flex;gap:12px;align-items:center;flex-wrap:wrap;margin-top:12px">
            <button type="submit" class="btn btn-primary">Simpan harga</button>
            <span class="help">Perubahan harga hanya berlaku untuk pesanan baru. Pesanan yang sudah dibuat tetap memakai harga lamanya.</span>
        </div>
    </form>
</section>

@if ($rooms->isEmpty())
    <section class="card"><div class="empty"><b>Belum ada ruang</b>Klik &quot;Tambah ruang&quot; untuk mulai.</div></section>
@else
    <div class="rooms">
        @foreach ($rooms as $room)
            @php $pct = $room->capacity_pct; @endphp
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
                        <h3>{{ $room->name }}@if ($room->title) <span class="muted">&middot; {{ $room->title }}</span>@endif</h3>
                        <p>{{ $room->location ?: 'Kode lokasi belum diisi' }}</p>
                        <p>{{ \Illuminate\Support\Str::limit($room->address, 60) }}</p>
                    </div>
                    <span class="st {{ $room->active ? 'ok' : 'mute' }}">{{ $room->active ? 'Tayang' : 'Disembunyikan' }}</span>
                </div>

                <div>
                    <div class="room-nums" style="margin-bottom:6px">
                        <span><small>Terpakai</small><b>{{ $room->capacity_used }} / {{ $room->capacity_total }} setara ukuran S</b></span>
                        <span style="text-align:right"><small>Okupansi</small><b>{{ $pct }}%</b></span>
                    </div>
                    <div class="meter {{ $pct >= 90 ? 'full' : ($pct >= 70 ? 'hi' : '') }}"><i style="width: {{ min(100, $pct) }}%"></i></div>
                </div>

                <div class="room-nums">
                    <span>
                        <small>Ukuran diterima</small>
                        <span class="room-tags">
                            @foreach ($room->accepted_sizes as $code)
                                <span class="tag info">{{ $code }}</span>
                            @endforeach
                        </span>
                    </span>
                    <span style="text-align:right"><small>Mulai dari</small><b>{{ $rp($room->min_price) }} / hari</b></span>
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
<dialog class="dlg wide" id="roomDialog" aria-labelledby="roomDialogT" @if ($errors->any() && $hasOld) data-reopen @endif>
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
                    <label for="rName">Nama ruang</label>
                    <input class="input" id="rName" name="name" required maxlength="255" value="{{ old('name') }}" placeholder="Contoh: Ruang A">
                    @error('name')<span class="err-t">{{ $message }}</span>@enderror
                </div>
                <div class="field">
                    <label for="rTitle">Jenis ruang</label>
                    <input class="input" id="rTitle" name="title" maxlength="255" value="{{ old('title') }}" placeholder="Contoh: Rak Kardus">
                    @error('title')<span class="err-t">{{ $message }}</span>@enderror
                </div>
                <div class="field">
                    <label for="rLocation">Kode lokasi</label>
                    <input class="input" id="rLocation" name="location" maxlength="255" value="{{ old('location') }}" placeholder="Contoh: Rak B">
                    @error('location')<span class="err-t">{{ $message }}</span>@enderror
                </div>
                <div class="field">
                    <label for="rCap">Kapasitas total (setara ukuran S)</label>
                    <input class="input num" id="rCap" name="capacity_total" type="number" min="1" required value="{{ old('capacity_total') }}" placeholder="500">
                    <span class="help">Per barang: @foreach ($sizes as $code => $spec){{ $code }} = {{ $spec['unit'] }}@if (! $loop->last), @endif @endforeach.</span>
                    @error('capacity_total')<span class="err-t">{{ $message }}</span>@enderror
                </div>
                <div class="field full">
                    <label for="rAddress">Alamat lengkap gudang</label>
                    <input class="input" id="rAddress" name="address" required maxlength="500" value="{{ old('address') }}" placeholder="Jl. Sumbersari No. 12, Lowokwaru, Malang">
                    @error('address')<span class="err-t">{{ $message }}</span>@enderror
                </div>
                <div class="field full">
                    <label for="rDesc">Deskripsi singkat</label>
                    <textarea class="input" id="rDesc" name="description" maxlength="500" placeholder="Satu atau dua kalimat yang tampil di daftar ruang.">{{ old('description') }}</textarea>
                    @error('description')<span class="err-t">{{ $message }}</span>@enderror
                </div>
                <div class="field full">
                    <label for="rLong">Deskripsi lengkap</label>
                    <textarea class="input" id="rLong" name="long_description" maxlength="3000" rows="4" placeholder="Penjelasan lengkap yang tampil di halaman detail ruang.">{{ old('long_description') }}</textarea>
                    @error('long_description')<span class="err-t">{{ $message }}</span>@enderror
                </div>
            </div>

            <div class="grp">
                <span class="lab">Ukuran yang diterima</span>
                <div class="size-pick" id="sizePick">
                    @foreach ($sizes as $code => $spec)
                        <label class="check">
                            <input type="checkbox" name="sizes[]" value="{{ $code }}" @checked(in_array($code, $pickedSizes, true))>
                            <span><b>{{ $spec['label'] }}</b>
                                <small>Kardus {{ $spec['box'] }}@if (! empty($spec['koper'])), koper {{ $spec['koper'] }}@endif, maks {{ $spec['kg'] }} kg, {{ $rp($spec['price']) }} per hari</small>
                            </span>
                        </label>
                    @endforeach
                </div>
                <span class="help">Pilih minimal satu. Pelanggan hanya bisa menitipkan ukuran yang dicentang di ruang ini. Harga diatur di bagian &quot;Harga per ukuran&quot; di atas.</span>
                @error('sizes')<span class="err-t">{{ $message }}</span>@enderror
                @error('sizes.*')<span class="err-t">{{ $message }}</span>@enderror
            </div>

            <div class="fgrid">
                <div class="field full">
                    <label for="rFac">Fitur ruang</label>
                    <textarea class="input" id="rFac" name="facilities" rows="4" placeholder="Satu fitur per baris, contoh:&#10;CCTV 24 jam&#10;Bebas banjir">{{ old('facilities') }}</textarea>
                    @error('facilities')<span class="err-t">{{ $message }}</span>@enderror
                </div>
                <div class="field full">
                    <label for="rRules">Aturan khusus ruang <span class="muted">(opsional)</span></label>
                    <textarea class="input" id="rRules" name="rules" rows="3" placeholder="Satu aturan per baris, contoh:&#10;Dilarang menitipkan barang cair">{{ old('rules') }}</textarea>
                    @error('rules')<span class="err-t">{{ $message }}</span>@enderror
                </div>
                <div class="field full">
                    <label for="rImages">Foto ruangan</label>
                    <div class="photo-edit" id="rPhotos" hidden></div>
                    <input class="input" id="rImages" name="images[]" type="file" accept="image/png,image/jpeg,image/webp" multiple>
                    <span class="help" id="rImagesHelp">Wajib minimal 1 foto, maksimal 10 foto, 5 MB per foto. Foto pertama jadi foto utama di aplikasi pelanggan.</span>
                    @error('images')<span class="err-t">{{ $message }}</span>@enderror
                    @error('images.*')<span class="err-t">{{ $message }}</span>@enderror
                </div>
            </div>

            <div class="grp">
                <span class="lab">Jam operasional (tampil di halaman detail ruang pelanggan)</span>
                <div class="fgrid">
                    <div class="field">
                        <label for="rHWeek">Senin sampai Jumat</label>
                        <input class="input" id="rHWeek" name="opening_hours[weekday]" value="{{ old('opening_hours.weekday') }}" placeholder="08.00–17.00">
                    </div>
                    <div class="field">
                        <label for="rHSat">Sabtu</label>
                        <input class="input" id="rHSat" name="opening_hours[saturday]" value="{{ old('opening_hours.saturday') }}" placeholder="08.00–12.00">
                    </div>
                    <div class="field">
                        <label for="rHSun">Minggu</label>
                        <input class="input" id="rHSun" name="opening_hours[sunday]" value="{{ old('opening_hours.sunday') }}" placeholder="Tutup">
                    </div>
                </div>
                <span class="help">Kosongkan untuk memakai jam bawaan (Senin sampai Jumat 08.00–17.00, Sabtu 08.00–12.00, Minggu tutup).</span>
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
    var TEXT_FIELDS = ['name', 'title', 'location', 'address', 'description', 'long_description', 'capacity_total', 'facilities', 'rules',
        'opening_hours[weekday]', 'opening_hours[saturday]', 'opening_hours[sunday]'];

    function setMode(room) {
        if (room) {
            title.textContent = 'Ubah ruang';
            submit.textContent = 'Simpan perubahan';
            form.action = UPDATE_URL + '/' + room.id;
            methodBox.innerHTML = '<input type="hidden" name="_method" value="PUT">';
            idField.value = room.id;
            imgHelp.textContent = 'Centang foto yang ingin dihapus. Foto baru akan ditambahkan, total maksimal 10 dan minimal 1.';
        } else {
            title.textContent = 'Tambah ruang';
            submit.textContent = 'Simpan ruang';
            form.action = STORE_URL;
            methodBox.innerHTML = '';
            idField.value = '';
            imgHelp.textContent = 'Wajib minimal 1 foto, maksimal 10 foto, 5 MB per foto. Foto pertama jadi foto utama di aplikasi pelanggan.';
        }
    }

    function setSizes(list) {
        form.querySelectorAll('#sizePick input[type=checkbox]').forEach(function (cb) {
            cb.checked = list.indexOf(cb.value) !== -1;
        });
    }

    function fill(room) {
        form.elements['name'].value = room.name || '';
        form.elements['title'].value = room.title || '';
        form.elements['location'].value = room.location || '';
        form.elements['address'].value = room.address || '';
        form.elements['description'].value = room.description || '';
        form.elements['long_description'].value = room.long_description || '';
        form.elements['capacity_total'].value = room.capacity_total || '';
        form.elements['facilities'].value = (room.facilities || []).join('\n');
        form.elements['rules'].value = (room.rules || []).join('\n');
        setSizes(room.sizes || []);
        document.getElementById('rActive').checked = !!room.active;
        form.elements['images[]'].value = '';
        form.elements['opening_hours[weekday]'].value = (room.hours && room.hours.weekday) || '';
        form.elements['opening_hours[saturday]'].value = (room.hours && room.hours.saturday) || '';
        form.elements['opening_hours[sunday]'].value = (room.hours && room.hours.sunday) || '';
        renderPhotos(room);
    }

    // Foto yang sudah ada: bisa dicentang untuk dihapus (tetap harus tersisa minimal satu).
    function renderPhotos(room) {
        var box = document.getElementById('rPhotos');
        box.innerHTML = '';
        var list = (room && room.photoList) || [];
        box.hidden = list.length === 0;
        list.forEach(function (path, i) {
            var label = document.createElement('label');
            label.className = 'photo-tile';
            var img = document.createElement('img');
            img.src = room.photoBase + path;
            img.alt = 'Foto ' + (i + 1);
            var cb = document.createElement('input');
            cb.type = 'checkbox';
            cb.name = 'remove_photos[]';
            cb.value = path;
            var span = document.createElement('span');
            span.textContent = i === 0 ? 'Utama, hapus' : 'Hapus';
            label.appendChild(img);
            label.appendChild(cb);
            label.appendChild(span);
            box.appendChild(label);
        });
    }

    // Form kosong: semua ukuran tercentang, tayang aktif.
    function reset() {
        form.reset();
        TEXT_FIELDS.forEach(function (n) { form.elements[n].value = ''; });
        form.elements['images[]'].value = '';
        setSizes(@json($codes));
        document.getElementById('rActive').checked = true;
        form.querySelectorAll('.err-t').forEach(function (el) { el.remove(); });
        renderPhotos(null);
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
    if (EDITING && ROOMS[EDITING]) {
        setMode(ROOMS[EDITING]);
        renderPhotos(ROOMS[EDITING]);
    }
})();
</script>
@endpush
