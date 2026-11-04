@extends('layouts.admin')

@section('title', 'Toko Packing')

@php
$categories = ['Kardus', 'Pelindung', 'Perekat', 'Aksesoris'];
$units = ['pcs', 'roll', 'meter', 'lembar'];
$statusOpts = ['pending' => 'Pending', 'diproses' => 'Diproses', 'dikirim' => 'Dikirim', 'selesai' => 'Selesai'];
$statusTone = ['pending' => 'warn', 'diproses' => 'info', 'dikirim' => 'info', 'selesai' => 'ok'];
$orderTabs = ['' => 'Semua', 'pending' => 'Pending', 'diproses' => 'Diproses', 'dikirim' => 'Dikirim', 'selesai' => 'Selesai'];
$editId = old('_edit_id');
$hasErr = $tab === 'inventaris' && $errors->any() && old('name') !== null;
$formAction = $editId ? route('admin.packing.update', $editId) : route('admin.packing.store');
@endphp

@push('styles')
<style>
  .pk-stock { display: flex; align-items: center; gap: 6px; }
  .pk-stock .input { width: 84px; min-height: 34px; padding: 4px 8px; }
  .pk-sel { min-height: 32px; padding: 2px 8px; font-size: 12.5px; width: auto; }
  .pk-sub { font-size: 12.5px; color: var(--color-text-secondary); }
</style>
@endpush

@section('content')
<div class="ph">
  <div>
    <h1>Toko Packing</h1>
    <p>Stok perlengkapan packing dan pesanan dari pelanggan. Penjualan packing untuk pelanggan sedang ditunda; panel ini tetap berfungsi penuh.</p>
  </div>
  @if ($tab === 'inventaris')
    <div class="acts">
      <button class="btn btn-sun" type="button" id="pkAdd" data-open="pkDialog">
        <svg class="ic" aria-hidden="true"><use href="#i-plus"/></svg> Tambah produk
      </button>
    </div>
  @endif
</div>

<div class="g3" style="margin-bottom:16px">
  <div class="kpi">
    <small>Stok menipis</small>
    <b>{{ $lowItems->count() }}</b>
    <span>
      @if ($lowItems->isEmpty())
        Semua stok aman
      @else
        {{ $lowItems->take(3)->map(fn ($l) => $l->name . ' (sisa ' . $l->stock . ')')->implode(', ') }}
      @endif
    </span>
  </div>
  <div class="kpi">
    <small>Item terjual</small>
    <b>{{ number_format($totalSold, 0, ',', '.') }}</b>
    <span>Dari pesanan lunas</span>
  </div>
  <div class="kpi">
    <small>Pendapatan packing</small>
    <b>Rp {{ number_format($revenue, 0, ',', '.') }}</b>
    <span>Dari pesanan lunas</span>
  </div>
</div>

<div class="bar">
  <div class="tabs">
    <a href="{{ route('admin.packing.index') }}" @if ($tab === 'inventaris') aria-current="page" @endif>Inventaris <i>{{ $totalProducts }}</i></a>
    <a href="{{ route('admin.packing.index', ['tab' => 'pesanan']) }}" @if ($tab === 'pesanan') aria-current="page" @endif>Pesanan packing</a>
  </div>
  <form method="GET" action="{{ route('admin.packing.index') }}" class="search" role="search">
    <input type="hidden" name="tab" value="{{ $tab }}">
    @if ($tab === 'inventaris' && $cat !== '')<input type="hidden" name="cat" value="{{ $cat }}">@endif
    @if ($tab === 'pesanan' && $st !== '')<input type="hidden" name="st" value="{{ $st }}">@endif
    <svg class="ic" aria-hidden="true"><use href="#n-search"/></svg>
    <input type="search" name="q" value="{{ $q }}" placeholder="{{ $tab === 'pesanan' ? 'Cari kode atau pelanggan' : 'Cari nama produk' }}" aria-label="Cari">
  </form>
</div>

@if ($tab === 'pesanan')
  <div class="bar">
    <div class="tabs">
      @foreach ($orderTabs as $k => $label)
        @php $cnt = $orderCounts[$k === '' ? 'semua' : $k] ?? 0; @endphp
        <a href="{{ route('admin.packing.index', array_filter(['tab' => 'pesanan', 'st' => $k ?: null, 'q' => $q ?: null])) }}" @if ($st === (string) $k) aria-current="page" @endif>{{ $label }} <i>{{ $cnt }}</i></a>
      @endforeach
    </div>
  </div>

  <div class="card flush">
    @if ($orders->isEmpty())
      <div class="empty"><b>Tidak ada pesanan</b>Pesanan dari pelanggan akan muncul di sini.</div>
    @else
      <div class="tw">
        <table class="tbl">
          <thead>
            <tr><th>Kode</th><th>Pelanggan</th><th>Isi pesanan</th><th class="r">Total</th><th>Pembayaran</th><th>Status order</th><th>Tanggal</th></tr>
          </thead>
          <tbody>
            @foreach ($orders as $order)
              @php
                $payTone = match ($order->payment_status) { 'PAID' => 'ok', 'FAILED', 'EXPIRED' => 'err', default => 'warn' };
                $payLabel = match ($order->payment_status) { 'PAID' => 'Lunas', 'FAILED', 'EXPIRED' => 'Gagal', default => 'Menunggu' };
                $orderStatus = $order->status ?? 'pending';
              @endphp
              <tr>
                <td>
                  <b class="mono">{{ $order->order_code }}</b>
                  @if ($order->needs_admin_attention)
                    <span class="tag err" title="{{ $order->biteship_last_error ?? 'Pemesanan kurir Biteship gagal' }}">Perlu tindakan</span>
                  @endif
                </td>
                <td>{{ $order->user->name ?? '-' }}</td>
                <td>{{ \Illuminate\Support\Str::limit(collect($order->items)->pluck('name')->implode(', '), 60) }}</td>
                <td class="r num">Rp {{ number_format($order->total, 0, ',', '.') }}</td>
                <td><span class="st {{ $payTone }}">{{ $payLabel }}</span></td>
                <td>
                  <form action="{{ route('admin.packing.order.status', $order->id) }}" method="POST" class="row-flex">
                    @csrf
                    <span class="st {{ $statusTone[$orderStatus] ?? 'mute' }}">{{ $statusOpts[$orderStatus] ?? 'Pending' }}</span>
                    <select name="status" class="input pk-sel" aria-label="Ubah status {{ $order->order_code }}" onchange="this.form.submit()">
                      @foreach ($statusOpts as $val => $label)
                        <option value="{{ $val }}" @selected($orderStatus === $val)>{{ $label }}</option>
                      @endforeach
                    </select>
                  </form>
                </td>
                <td>{{ optional($order->created_at)->format('d M Y, H:i') }}</td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
      @include('admin.preloved-pager', ['p' => $orders])
    @endif
  </div>
@else
  <div class="bar">
    <div class="tabs">
      <a href="{{ route('admin.packing.index', array_filter(['q' => $q ?: null])) }}" @if ($cat === '') aria-current="page" @endif>Semua kategori</a>
      @foreach ($categories as $c)
        <a href="{{ route('admin.packing.index', array_filter(['cat' => $c, 'q' => $q ?: null])) }}" @if ($cat === $c) aria-current="page" @endif>{{ $c }}</a>
      @endforeach
    </div>
  </div>

  <div class="card flush">
    @if ($items->isEmpty())
      <div class="empty">
        <b>{{ ($q !== '' || $cat !== '') ? 'Produk tidak ditemukan' : 'Belum ada produk' }}</b>
        {{ ($q !== '' || $cat !== '') ? 'Coba kata kunci atau kategori lain.' : 'Klik "Tambah produk" untuk mengisi inventaris.' }}
      </div>
    @else
      <div class="tw">
        <table class="tbl">
          <thead>
            <tr><th>Produk</th><th>Kategori</th><th class="r">Harga</th><th>Stok sistem</th><th>Stok fisik (opname)</th><th class="acts">Aksi</th></tr>
          </thead>
          <tbody>
            @foreach ($items as $item)
              @php $isLow = $item->stock <= $item->low_threshold; @endphp
              <tr>
                <td>
                  <div class="row-flex">
                    <span class="thumb">
                      @if ($item->primary_image)
                        <img src="{{ asset('storage/' . $item->primary_image) }}" alt="">
                      @else
                        <svg class="ic" aria-hidden="true"><use href="#n-box"/></svg>
                      @endif
                    </span>
                    <div><b>{{ $item->name }}</b></div>
                  </div>
                </td>
                <td>{{ $item->category }}</td>
                <td class="r num">Rp {{ number_format($item->price, 0, ',', '.') }}<small>per {{ $item->unit }}</small></td>
                <td>
                  <b class="num">{{ $item->stock }}</b>
                  <span class="st {{ $item->stock <= 0 ? 'err' : ($isLow ? 'warn' : 'ok') }}">{{ $item->stock <= 0 ? 'Habis' : ($isLow ? 'Menipis' : 'Aman') }}</span>
                </td>
                <td>
                  <form action="{{ route('admin.packing.update', $item->id) }}" method="POST" class="pk-stock">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="quick_stock" value="1">
                    <input class="input num" type="number" min="0" name="stock" value="{{ $item->stock }}" aria-label="Stok fisik {{ $item->name }}">
                    <button class="btn btn-ghost sm" type="submit">Simpan</button>
                  </form>
                </td>
                <td class="acts">
                  <button class="btn btn-ghost sm" type="button" data-edit-item="{{ e($item->toJson()) }}">Ubah</button>
                  <form action="{{ route('admin.packing.destroy', $item->id) }}" method="POST" style="display:inline"
                        data-confirm="Produk {{ $item->name }} akan dihapus permanen." data-confirm-title="Hapus produk?" data-confirm-ok="Hapus" data-danger>
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

  <dialog class="dlg" id="pkDialog" @if ($hasErr) data-reopen @endif>
    <form method="POST" id="pkForm" action="{{ $formAction }}" enctype="multipart/form-data">
      @csrf
      <input type="hidden" name="_method" value="PUT" id="pkMethod" @if (! $editId) disabled @endif>
      <input type="hidden" name="_edit_id" value="{{ $editId }}" id="pkEditId" @if (! $editId) disabled @endif>
      <div class="dh">
        <h3 id="pkTitle">{{ $editId ? 'Ubah produk' : 'Tambah produk packing' }}</h3>
        <button class="x" type="button" data-close aria-label="Tutup"><svg class="ic" aria-hidden="true"><use href="#i-x"/></svg></button>
      </div>
      <div class="db">
        <div class="field">
          <label for="pkImages">Foto <span class="muted">(wajib minimal 1, maksimal 10, JPG/PNG, 5 MB per foto)</span></label>
          <input type="file" id="pkImages" name="images[]" accept="image/png,image/jpeg" multiple>
          <span class="help">Foto baru ditambahkan ke foto yang sudah ada.</span>
          @error('images')<span class="err-t">{{ $message }}</span>@enderror
          @error('images.*')<span class="err-t">{{ $message }}</span>@enderror
        </div>
        <div class="fgrid">
          <div class="field full">
            <label for="pkName">Nama produk</label>
            <input class="input" id="pkName" name="name" value="{{ old('name') }}" required placeholder="Contoh: Kardus ukuran M">
            @error('name')<span class="err-t">{{ $message }}</span>@enderror
          </div>
          <div class="field">
            <label for="pkCat">Kategori</label>
            <select class="input" id="pkCat" name="category">
              @foreach ($categories as $c)<option value="{{ $c }}" @selected(old('category') === $c)>{{ $c }}</option>@endforeach
            </select>
            @error('category')<span class="err-t">{{ $message }}</span>@enderror
          </div>
          <div class="field">
            <label for="pkUnit">Satuan</label>
            <select class="input" id="pkUnit" name="unit">
              @foreach ($units as $u)<option value="{{ $u }}" @selected(old('unit') === $u)>{{ $u }}</option>@endforeach
            </select>
            @error('unit')<span class="err-t">{{ $message }}</span>@enderror
          </div>
          <div class="field full">
            <label for="pkPrice">Harga jual (Rp)</label>
            <input class="input num" id="pkPrice" name="price" type="number" min="0" inputmode="numeric" value="{{ old('price') }}" required placeholder="6000">
            @error('price')<span class="err-t">{{ $message }}</span>@enderror
          </div>
          <div class="field">
            <label for="pkStock">Stok</label>
            <input class="input num" id="pkStock" name="stock" type="number" min="0" value="{{ old('stock', 0) }}" required>
            @error('stock')<span class="err-t">{{ $message }}</span>@enderror
          </div>
          <div class="field">
            <label for="pkMin">Ambang peringatan</label>
            <input class="input num" id="pkMin" name="low_threshold" type="number" min="1" value="{{ old('low_threshold', 10) }}" required>
            <span class="help">Peringatan muncul bila stok di bawah angka ini.</span>
            @error('low_threshold')<span class="err-t">{{ $message }}</span>@enderror
          </div>
          <div class="field full"><span class="pk-sub">Berat dan dimensi dipakai untuk menghitung ongkir Biteship.</span></div>
          <div class="field">
            <label for="pkWeight">Berat (gram)</label>
            <input class="input num" id="pkWeight" name="weight" type="number" min="1" value="{{ old('weight', 500) }}" required>
            @error('weight')<span class="err-t">{{ $message }}</span>@enderror
          </div>
          <div class="field">
            <label for="pkLength">Panjang (cm)</label>
            <input class="input num" id="pkLength" name="length" type="number" min="1" value="{{ old('length', 30) }}" required>
            @error('length')<span class="err-t">{{ $message }}</span>@enderror
          </div>
          <div class="field">
            <label for="pkWidth">Lebar (cm)</label>
            <input class="input num" id="pkWidth" name="width" type="number" min="1" value="{{ old('width', 20) }}" required>
            @error('width')<span class="err-t">{{ $message }}</span>@enderror
          </div>
          <div class="field">
            <label for="pkHeight">Tinggi (cm)</label>
            <input class="input num" id="pkHeight" name="height" type="number" min="1" value="{{ old('height', 15) }}" required>
            @error('height')<span class="err-t">{{ $message }}</span>@enderror
          </div>
        </div>
      </div>
      <div class="df">
        <button class="btn btn-ghost" type="button" data-close>Batal</button>
        <button class="btn btn-primary" type="submit" id="pkSubmit">{{ $editId ? 'Simpan perubahan' : 'Tambah produk' }}</button>
      </div>
    </form>
  </dialog>
@endif
@endsection

@push('scripts')
@if ($tab === 'inventaris')
<script>
(function () {
  var form = document.getElementById('pkForm');
  if (!form) return;
  var storeUrl = @json(route('admin.packing.store'));
  var baseUrl = @json(url('/admin/toko-packing'));
  var method = document.getElementById('pkMethod');
  var editId = document.getElementById('pkEditId');
  var title = document.getElementById('pkTitle');
  var submit = document.getElementById('pkSubmit');
  var dialog = document.getElementById('pkDialog');
  var keys = ['name', 'category', 'unit', 'price', 'stock', 'low_threshold', 'weight', 'length', 'width', 'height'];
  var defaults = { name: '', category: 'Kardus', unit: 'pcs', price: '', stock: 0, low_threshold: 10, weight: 500, length: 30, width: 20, height: 15 };
  var images = document.getElementById('pkImages');

  function fill(data) {
    keys.forEach(function (k) {
      if (form.elements[k]) form.elements[k].value = data[k] === undefined || data[k] === null ? '' : data[k];
    });
  }

  document.getElementById('pkAdd').addEventListener('click', function () {
    form.reset();
    form.action = storeUrl;
    method.disabled = true;
    editId.disabled = true;
    title.textContent = 'Tambah produk packing';
    submit.textContent = 'Tambah produk';
    fill(defaults);
  });

  document.querySelectorAll('[data-edit-item]').forEach(function (btn) {
    btn.addEventListener('click', function () {
      var item = JSON.parse(btn.dataset.editItem);
      form.reset();
      form.action = baseUrl + '/' + item.id;
      method.disabled = false;
      editId.disabled = false;
      editId.value = item.id;
      title.textContent = 'Ubah produk';
      submit.textContent = 'Simpan perubahan';
      fill(item);
      dialog.showModal();
    });
  });
})();
</script>
@endif
@endpush
