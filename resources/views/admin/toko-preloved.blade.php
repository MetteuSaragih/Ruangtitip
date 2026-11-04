@extends('layouts.admin')

@section('title', 'Katalog Preloved')

@php
$statusTone = ['Tersedia' => 'ok', 'Terjual' => 'mute', 'Draft' => 'warn'];
$tabs = ['semua' => 'Semua', 'tersedia' => 'Tersedia', 'draft' => 'Draft', 'terjual' => 'Terjual'];
$editId = old('_edit_id');
$hasErr = $errors->any() && old('name') !== null;
$formAction = $editId ? route('admin.preloved.update', $editId) : route('admin.preloved.store');
@endphp

@push('styles')
<style>
  .pl-photos { display: grid; gap: 6px; }
  .pl-photos input[type=file] { font-size: 13px; }
  .pl-sub { font-size: 12.5px; color: var(--color-text-secondary); }
  .photo-edit { display: grid; grid-template-columns: repeat(auto-fill, minmax(92px, 1fr)); gap: 8px; margin-bottom: 8px; }
  .photo-tile { position: relative; display: grid; gap: 4px; font-size: 12px; color: var(--color-text-secondary); cursor: pointer; }
  .photo-tile img { width: 100%; aspect-ratio: 4 / 3; object-fit: cover; border-radius: 8px; border: 1px solid var(--color-border); }
  .photo-tile input { position: absolute; top: 6px; right: 6px; width: 18px; height: 18px; }
  .photo-tile input:checked ~ span { color: var(--color-danger-text); font-weight: 700; }
  .photo-tile:has(input:checked) img { opacity: 0.4; }
</style>
@endpush

@section('content')
<div class="ph">
  <div>
    <h1>Katalog Preloved</h1>
    <p>Barang bekas yang dijual di Toko Preloved. Barang berstatus draft tidak tampil untuk pelanggan.</p>
  </div>
  <div class="acts">
    @if (Route::has('admin.jual.index'))
      <a class="btn btn-ghost" href="{{ route('admin.jual.index') }}">Pengajuan jual</a>
    @endif
    @if (Route::has('admin.preloved.orders'))
      <a class="btn btn-ghost" href="{{ route('admin.preloved.orders') }}">Pesanan preloved</a>
    @endif
    <button class="btn btn-sun" type="button" id="plAdd" data-open="plDialog">
      <svg class="ic" aria-hidden="true"><use href="#i-plus"/></svg> Tambah barang
    </button>
  </div>
</div>

<div class="bar">
  <div class="tabs">
    @foreach ($tabs as $k => $label)
      <a href="{{ route('admin.preloved', array_filter(['tab' => $k === 'semua' ? null : $k, 'q' => $q ?: null])) }}" @if ($tab === $k) aria-current="page" @endif>{{ $label }} <i>{{ $counts[$k] }}</i></a>
    @endforeach
  </div>
  <form method="GET" action="{{ route('admin.preloved') }}" class="search" role="search">
    @if ($tab !== 'semua')<input type="hidden" name="tab" value="{{ $tab }}">@endif
    <svg class="ic" aria-hidden="true"><use href="#n-search"/></svg>
    <input type="search" name="q" value="{{ $q }}" placeholder="Cari nama, kategori, penjual" aria-label="Cari barang">
  </form>
</div>

<div class="card flush">
  @if ($items->isEmpty())
    <div class="empty">
      <b>{{ $q !== '' ? 'Barang tidak ditemukan' : 'Belum ada barang' }}</b>
      {{ $q !== '' ? 'Coba kata kunci lain atau ganti tab.' : 'Klik "Tambah barang" untuk mengisi katalog.' }}
    </div>
  @else
    <div class="tw">
      <table class="tbl">
        <thead>
          <tr>
            <th>Barang</th><th>Kategori</th><th>Kondisi</th><th class="r">Harga</th><th>Status</th><th class="acts">Aksi</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($items as $item)
            @php $cl = \App\Models\PrelovedItem::conditionText((int) $item->condition); @endphp
            <tr>
              <td>
                <div class="row-flex">
                  <span class="thumb">
                    @if ($item->primary_photo)
                      <img src="{{ asset('storage/' . $item->primary_photo) }}" alt="">
                    @else
                      <svg class="ic" aria-hidden="true"><use href="#n-tag"/></svg>
                    @endif
                  </span>
                  <div><b>{{ $item->name }}</b>@if ($item->seller)<small>Penjual: {{ $item->seller }}</small>@endif</div>
                </div>
              </td>
              <td>{{ $item->category }}</td>
              <td>{{ $cl }}</td>
              <td class="r num">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
              <td><span class="st {{ $statusTone[$item->status] ?? 'mute' }}">{{ $item->status }}</span></td>
              <td class="acts">
                @if ($item->status !== 'Terjual')
                  <button class="btn btn-ghost sm" type="button" data-edit-item="{{ $item->toJson() }}">Ubah</button>
                  <form method="POST" action="{{ route('admin.preloved.toggle-draft', $item) }}" style="display:inline">
                    @csrf
                    <button class="btn btn-ghost sm" type="submit">{{ $item->status === 'Draft' ? 'Publikasikan' : 'Jadikan draft' }}</button>
                  </form>
                @endif
                <form method="POST" action="{{ route('admin.preloved.destroy', $item) }}" style="display:inline"
                      data-confirm="Barang {{ $item->name }} akan dihapus permanen dari katalog." data-confirm-title="Hapus barang?" data-confirm-ok="Hapus" data-danger>
                  @csrf
                  @method('DELETE')
                  <button class="btn btn-danger sm" type="submit">Hapus</button>
                </form>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
    @include('admin.preloved-pager', ['p' => $items])
  @endif
</div>

<dialog class="dlg" id="plDialog" @if ($hasErr) data-reopen @endif>
  <form method="POST" id="plForm" action="{{ $formAction }}" enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="_method" value="PUT" id="plMethod" @if (! $editId) disabled @endif>
    <input type="hidden" name="_edit_id" value="{{ $editId }}" id="plEditId" @if (! $editId) disabled @endif>
    <div class="dh">
      <h3 id="plTitle">{{ $editId ? 'Ubah barang' : 'Tambah barang preloved' }}</h3>
      <button class="x" type="button" data-close aria-label="Tutup"><svg class="ic" aria-hidden="true"><use href="#i-x"/></svg></button>
    </div>
    <div class="db">
      <div class="field pl-photos">
        <label for="plImages">Foto <span class="muted">(wajib minimal 1, maksimal 10, JPG/PNG/WEBP, 5 MB per foto)</span></label>
        <div class="photo-edit" id="plPhotoList" hidden></div>
        <input type="file" id="plImages" name="images[]" accept="image/png,image/jpeg,image/webp" multiple>
        <span class="help" id="plPhotoHelp">Foto pertama jadi foto utama di katalog pelanggan.</span>
        @error('images')<span class="err-t">{{ $message }}</span>@enderror
        @error('images.*')<span class="err-t">{{ $message }}</span>@enderror
      </div>
      <div class="fgrid">
        <div class="field full">
          <label for="plName">Nama barang</label>
          <input class="input" id="plName" name="name" value="{{ old('name') }}" required placeholder="Contoh: Koper Polo 24 inci">
          @error('name')<span class="err-t">{{ $message }}</span>@enderror
        </div>
        <div class="field">
          <label for="plCategory">Kategori</label>
          <select class="input" id="plCategory" name="category" required>
            @foreach ($categories as $cat)
              <option value="{{ $cat }}" @selected(old('category') === $cat)>{{ $cat }}</option>
            @endforeach
          </select>
          @error('category')<span class="err-t">{{ $message }}</span>@enderror
        </div>
        <div class="field">
          <label for="plCondition">Kondisi</label>
          <select class="input" id="plCondition" name="condition" required>
            @foreach ($conditions as $pct => $label)
              <option value="{{ $pct }}" @selected((int) old('condition', 85) === $pct)>{{ $label }} ({{ $pct }}%)</option>
            @endforeach
          </select>
          @error('condition')<span class="err-t">{{ $message }}</span>@enderror
        </div>
        <div class="field">
          <label for="plPrice">Harga jual (Rp)</label>
          <input class="input num" id="plPrice" name="price" type="number" min="1000" inputmode="numeric" value="{{ old('price') }}" required placeholder="150000">
          @error('price')<span class="err-t">{{ $message }}</span>@enderror
        </div>
        <div class="field">
          <label for="plSeller">Pemilik barang (catatan internal)</label>
          <input class="input" id="plSeller" name="seller" value="{{ old('seller') }}" placeholder="Nama pemilik awal, tidak tampil ke pembeli">
          @error('seller')<span class="err-t">{{ $message }}</span>@enderror
        </div>
        <div class="field full"><span class="pl-sub">Isian di bawah ini tampil di halaman produk pelanggan, persis seperti yang kamu tulis.</span></div>
        <div class="field full">
          <label for="plDesc">Deskripsi</label>
          <textarea class="input" id="plDesc" name="description" rows="3" required placeholder="Lama pemakaian, ukuran, dan hal lain yang perlu pembeli tahu">{{ old('description') }}</textarea>
          @error('description')<span class="err-t">{{ $message }}</span>@enderror
        </div>
        <div class="field full">
          <label for="plFunc">Fungsi <span class="muted">(opsional)</span></label>
          <input class="input" id="plFunc" name="function_note" value="{{ old('function_note') }}" maxlength="255" placeholder="Contoh: Semua fungsi normal, sudah dites petugas">
          @error('function_note')<span class="err-t">{{ $message }}</span>@enderror
        </div>
        <div class="field">
          <label for="plKel">Kelengkapan</label>
          <input class="input" id="plKel" name="completeness" value="{{ old('completeness') }}" maxlength="255" required placeholder="Contoh: Unit, kabel, dus">
          @error('completeness')<span class="err-t">{{ $message }}</span>@enderror
        </div>
        <div class="field">
          <label for="plFlaws">Kekurangan</label>
          <input class="input" id="plFlaws" name="flaws" value="{{ old('flaws') }}" maxlength="255" required placeholder="Tulis &quot;Tidak ada&quot; kalau memang tidak ada">
          @error('flaws')<span class="err-t">{{ $message }}</span>@enderror
        </div>
        <div class="field full"><span class="pl-sub">Berat dan dimensi dipakai untuk menghitung ongkir Biteship.</span></div>
        <div class="field">
          <label for="plWeight">Berat (gram)</label>
          <input class="input num" id="plWeight" name="weight" type="number" min="1" value="{{ old('weight', 1000) }}" required>
          @error('weight')<span class="err-t">{{ $message }}</span>@enderror
        </div>
        <div class="field">
          <label for="plLength">Panjang (cm)</label>
          <input class="input num" id="plLength" name="length" type="number" min="1" value="{{ old('length', 30) }}" required>
          @error('length')<span class="err-t">{{ $message }}</span>@enderror
        </div>
        <div class="field">
          <label for="plWidth">Lebar (cm)</label>
          <input class="input num" id="plWidth" name="width" type="number" min="1" value="{{ old('width', 20) }}" required>
          @error('width')<span class="err-t">{{ $message }}</span>@enderror
        </div>
        <div class="field">
          <label for="plHeight">Tinggi (cm)</label>
          <input class="input num" id="plHeight" name="height" type="number" min="1" value="{{ old('height', 15) }}" required>
          @error('height')<span class="err-t">{{ $message }}</span>@enderror
        </div>
      </div>
    </div>
    <div class="df">
      <button class="btn btn-ghost" type="button" data-close>Batal</button>
      <button class="btn btn-primary" type="submit" id="plSubmit">{{ $editId ? 'Simpan perubahan' : 'Tambah ke katalog' }}</button>
    </div>
  </form>
</dialog>
@endsection

@push('scripts')
<script>
(function () {
  var form = document.getElementById('plForm');
  if (!form) return;
  var storeUrl = @json(route('admin.preloved.store'));
  var baseUrl = @json(url('/admin/preloved'));
  var method = document.getElementById('plMethod');
  var editId = document.getElementById('plEditId');
  var title = document.getElementById('plTitle');
  var submit = document.getElementById('plSubmit');
  var images = document.getElementById('plImages');
  var dialog = document.getElementById('plDialog');
  var hasErrors = @json($hasErr);

  function setVal(name, val) { if (form.elements[name]) form.elements[name].value = val; }

  // Foto yang sudah ada: bisa dicentang untuk dihapus (tetap harus tersisa minimal satu).
  var photoBase = @json(asset('storage') . '/');
  function renderPhotos(item) {
    var box = document.getElementById('plPhotoList');
    box.innerHTML = '';
    var list = [];
    if (item) {
      list = (item.photos || []).slice();
      if (item.photo && list.indexOf(item.photo) === -1) list.unshift(item.photo);
    }
    box.hidden = list.length === 0;
    list.forEach(function (path, i) {
      var label = document.createElement('label');
      label.className = 'photo-tile';
      var img = document.createElement('img');
      img.src = photoBase + path; img.alt = 'Foto ' + (i + 1);
      var cb = document.createElement('input');
      cb.type = 'checkbox'; cb.name = 'remove_photos[]'; cb.value = path;
      var span = document.createElement('span');
      span.textContent = i === 0 ? 'Utama, hapus' : 'Hapus';
      label.appendChild(img); label.appendChild(cb); label.appendChild(span);
      box.appendChild(label);
    });
  }

  document.getElementById('plAdd').addEventListener('click', function () {
    form.reset();
    form.action = storeUrl;
    method.disabled = true;
    editId.disabled = true;
    title.textContent = 'Tambah barang preloved';
    submit.textContent = 'Tambah ke katalog';
    images.required = true;
    setVal('condition', '85');
    setVal('weight', '1000'); setVal('length', '30'); setVal('width', '20'); setVal('height', '15');
    renderPhotos(null);
  });

  document.querySelectorAll('[data-edit-item]').forEach(function (btn) {
    btn.addEventListener('click', function () {
      var item = JSON.parse(btn.dataset.editItem);
      form.reset();
      form.action = baseUrl + '/' + item.id;
      method.disabled = false;
      editId.disabled = false;
      editId.value = item.id;
      title.textContent = 'Ubah barang';
      submit.textContent = 'Simpan perubahan';
      images.required = false;
      setVal('name', item.name || '');
      // Kategori lama yang tidak ada di daftar resmi tetap bisa dipilih agar tidak berubah diam-diam.
      var cat = form.elements['category'];
      if (item.category && ![].some.call(cat.options, function (o) { return o.value === item.category; })) {
        var extra = document.createElement('option');
        extra.value = item.category; extra.textContent = item.category + ' (kategori lama)';
        cat.appendChild(extra);
      }
      setVal('category', item.category || '');
      var c = parseInt(item.condition, 10) || 85;
      setVal('condition', String(c >= 90 ? 95 : (c >= 80 ? 85 : 75)));
      setVal('price', item.price || '');
      setVal('seller', item.seller || '');
      setVal('description', item.description || '');
      setVal('function_note', item.function_note || '');
      setVal('completeness', item.completeness || '');
      setVal('flaws', item.flaws || '');
      renderPhotos(item);
      setVal('weight', item.weight || 1000);
      setVal('length', item.length || 30);
      setVal('width', item.width || 20);
      setVal('height', item.height || 15);
      dialog.showModal();
    });
  });

  images.required = !(method && !method.disabled) && !hasErrors;
})();
</script>
@endpush
