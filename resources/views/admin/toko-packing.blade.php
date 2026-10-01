@extends('layouts.admin')

@section('title', 'Toko Packing')

@php
if (! function_exists('rt_wa_number')) {
    function rt_wa_number($phone) {
        if (! $phone) return null;
        $digits = preg_replace('/\D/', '', $phone);
        if (! $digits) return null;
        if (str_starts_with($digits, '0')) return '62' . substr($digits, 1);
        if (str_starts_with($digits, '62')) return $digits;
        return '62' . $digits;
    }
}
$categories = ['Kardus', 'Pelindung', 'Perekat', 'Aksesoris'];
$units = ['pcs', 'roll', 'meter', 'lembar'];
@endphp

@section('content')
<div class="ph"><div><h1>Toko Packing</h1><p>Stok perlengkapan packing dan pesanan dari pelanggan.</p></div></div>

<div class="stats">
  <div class="card stat {{ $lowItems->count() > 0 ? 'alert' : '' }}">
    <div class="stat-top"><span class="stat-ico r">{!! \App\Support\Icons::svg('alert') !!}</span></div>
    <small>Stok menipis</small>
    <strong>{{ $lowItems->count() }} produk</strong>
    <span class="sub">
      @forelse ($lowItems->take(3) as $low)
        {{ $low->name }} (sisa {{ $low->stock }} {{ $low->unit }}){{ !$loop->last ? ',' : '' }}
      @empty
        Semua stok aman
      @endforelse
    </span>
  </div>
  <div class="card stat">
    <div class="stat-top"><span class="stat-ico g">{!! \App\Support\Icons::svg('trend') !!}</span></div>
    <small>Terjual bulan ini</small>
    <strong>{{ $totalSold }} item</strong>
    <span class="sub">Kardus, lakban, dan pelindung</span>
  </div>
  <div class="card stat">
    <div class="stat-top"><span class="stat-ico s">{!! \App\Support\Icons::svg('wallet') !!}</span></div>
    <small>Pendapatan packing</small>
    <strong>Rp {{ number_format($revenue, 0, ',', '.') }}</strong>
    <span class="sub"><span class="pill g">Dari pesanan lunas</span></span>
  </div>
</div>

<div class="seg" role="tablist" aria-label="Bagian halaman">
  <a role="tab" aria-selected="{{ $tab === 'inventaris' ? 'true' : 'false' }}" href="{{ route('admin.packing.index', ['tab' => 'inventaris']) }}">
    {!! \App\Support\Icons::svg('layers') !!}
    <span>Inventaris<small>Stock opname dan master produk</small></span>
  </a>
  <a role="tab" aria-selected="{{ $tab === 'pesanan' ? 'true' : 'false' }}" href="{{ route('admin.packing.index', ['tab' => 'pesanan']) }}">
    {!! \App\Support\Icons::svg('clipboard') !!}
    <span>Pesanan packing<small>Transaksi dan pengiriman</small></span>
  </a>
</div>

@if ($tab === 'pesanan')
  <section class="card">
    <div class="card-h"><div><h2>Pesanan packing</h2><p>{{ $orders->count() }} pesanan tercatat</p></div></div>
    <div class="tbl-wrap">
      @if ($orders->isEmpty())
        {!! \App\Support\Icons::empty('clipboard', 'Tidak ada pesanan masuk', 'Pesanan dari pelanggan akan muncul di sini.') !!}
      @else
        <table>
          <thead><tr><th>Kode pesanan</th><th>Pelanggan</th><th>Isi pesanan</th><th class="num">Total</th><th>Pembayaran</th><th>Status order</th><th>Tanggal</th><th><span class="sr">Aksi</span></th></tr></thead>
          <tbody>
            @foreach ($orders as $order)
              @php
                $payBadge = match ($order->payment_status) {
                    'PAID' => ['Lunas', 'g'],
                    'FAILED', 'EXPIRED' => ['Gagal', 'r'],
                    default => ['Menunggu', 'y'],
                };
                $waNumber = rt_wa_number($order->user->phone ?? null);
                $waCustomer = $order->user->name ?? 'Pelanggan';
                $waTemplates = [
                    ['key' => 'diproses', 'label' => 'Pesanan Sedang Diproses', 'text' => "Halo {$waCustomer}, pesanan packing-mu (kode {$order->order_code}) sedang kami proses. Mohon ditunggu ya! 📦"],
                    ['key' => 'dikirim', 'label' => 'Pesanan Sudah Dikirim', 'text' => "Halo {$waCustomer}, pesanan packing-mu (kode {$order->order_code}) sudah dikirim. Terima kasih telah berbelanja di RuangTitip! 🚚"],
                    ['key' => 'selesai', 'label' => 'Pesanan Sudah Diterima/Selesai', 'text' => "Halo {$waCustomer}, terima kasih! Pesanan packing-mu (kode {$order->order_code}) sudah selesai. 🙏"],
                ];
                $statusOpts = ['pending' => 'Pending', 'diproses' => 'Diproses', 'dikirim' => 'Dikirim', 'selesai' => 'Selesai'];
                $statusTone = ['pending' => 'y', 'diproses' => 'n', 'dikirim' => 'o', 'selesai' => 'g'];
                $orderStatus = $order->status ?? 'pending';
              @endphp
              <tr>
                <td class="mono">{{ $order->order_code }}</td>
                <td>{{ $order->user->name ?? '-' }}</td>
                <td>{{ collect($order->items)->pluck('name')->implode(', ') }}</td>
                <td class="num"><b>Rp {{ number_format($order->total, 0, ',', '.') }}</b></td>
                <td><span class="pill {{ $payBadge[1] }}">{{ $payBadge[0] }}</span></td>
                <td>
                  <form action="{{ route('admin.packing.order.status', $order->id) }}" method="POST" style="display:flex;align-items:center;gap:6px">
                    @csrf
                    <span class="pill {{ $statusTone[$orderStatus] ?? 'n' }}">{{ $statusOpts[$orderStatus] ?? 'Pending' }}</span>
                    <select name="status" class="select" style="min-height:32px;padding:2px 8px;font-size:12px" onchange="this.form.submit()">
                      @foreach ($statusOpts as $val => $label)
                        <option value="{{ $val }}" {{ $orderStatus === $val ? 'selected' : '' }}>{{ $label }}</option>
                      @endforeach
                    </select>
                  </form>
                </td>
                <td>{{ $order->created_at->format('d M Y, H:i') }}</td>
                <td>
                  <div class="t-actions">
                    <button type="button" class="btn btn-sm btn-green" {{ $waNumber ? '' : 'disabled' }}
                      title="{{ $waNumber ? 'Kirim pesan WhatsApp ke ' . $waCustomer : 'Nomor WA pelanggan belum diisi' }}"
                      onclick="openWaModal(@js($waNumber), @js($waTemplates), @js('Kirim ke ' . $waCustomer))">
                      {!! \App\Support\Icons::svg('send', 'sm') !!} WA
                    </button>
                  </div>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      @endif
    </div>
  </section>
@else
  <section class="card">
    <div class="card-h">
      <div class="chips" id="kFilter" role="group" aria-label="Filter kategori">
        <button class="chip" type="button" aria-pressed="true" data-c="Semua">Semua</button>
        @foreach ($categories as $c)
          <button class="chip" type="button" aria-pressed="false" data-c="{{ $c }}">{{ $c }}</button>
        @endforeach
      </div>
      <button class="btn btn-primary" type="button" id="btnTambahProduk">{!! \App\Support\Icons::svg('plus', 'sm') !!} Tambah produk</button>
    </div>
    <div class="tbl-wrap" id="kTable">
      @if ($items->isEmpty())
        {!! \App\Support\Icons::empty('box', 'Belum ada produk', 'Klik "Tambah produk" untuk mulai mengisi inventaris.') !!}
      @else
        <table>
          <thead><tr><th>Produk</th><th>Kategori</th><th class="num">Harga</th><th>Stok sistem</th><th>Stok fisik (opname)</th><th><span class="sr">Aksi</span></th></tr></thead>
          <tbody>
            @foreach ($items as $item)
              @php $isLow = $item->stock <= $item->low_threshold; @endphp
              <tr data-category="{{ $item->category }}">
                <td>
                  <div class="who">
                    <span class="thumb">
                      @if ($item->primary_image)
                        <img src="{{ asset('storage/' . $item->primary_image) }}" alt="{{ $item->name }}">
                      @else
                        {!! \App\Support\Icons::svg('box') !!}
                      @endif
                    </span>
                    <div><b>{{ $item->name }}</b><small>{{ $item->category }}</small></div>
                  </div>
                </td>
                <td><span class="pill n">{{ $item->category }}</span></td>
                <td class="num"><b>Rp {{ number_format($item->price, 0, ',', '.') }}</b><br><small style="color:var(--muted)">per {{ $item->unit }}</small></td>
                <td>
                  <div class="stock">
                    <b>{{ $item->stock }}</b>
                    <span class="pill {{ $item->stock <= 0 ? 'r' : ($isLow ? 'y' : 'g') }}">{{ $item->stock <= 0 ? 'Habis' : ($isLow ? 'Menipis' : 'Aman') }}</span>
                  </div>
                </td>
                <td>
                  <form action="{{ route('admin.packing.update', $item->id) }}" method="POST" class="stock-form" data-original="{{ $item->stock }}" style="display:flex;align-items:center;gap:8px">
                    @csrf @method('PUT')
                    <input type="hidden" name="quick_stock" value="1">
                    <div class="stepper">
                      <button type="button" data-d="-1">−</button>
                      <input type="number" min="0" name="stock" value="{{ $item->stock }}">
                      <button type="button" data-d="1">+</button>
                    </div>
                    <button class="btn btn-sm btn-ghost save-stock-btn" type="submit" disabled>Simpan</button>
                  </form>
                </td>
                <td>
                  <div class="t-actions">
                    <button class="btn btn-sm btn-ghost" type="button" data-edit-item="{{ e($item->toJson()) }}">{!! \App\Support\Icons::svg('edit', 'sm') !!}</button>
                    <form action="{{ route('admin.packing.destroy', $item->id) }}" method="POST" data-confirm="Hapus {{ addslashes($item->name) }}?|Produk ini akan dihapus permanen dari katalog." data-confirm-ok="Hapus">
                      @csrf @method('DELETE')
                      <button class="btn btn-sm btn-danger" type="submit">{!! \App\Support\Icons::svg('trash', 'sm') !!}</button>
                    </form>
                  </div>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      @endif
    </div>
    <div class="card-f"><span>{{ $items->count() }} produk ditampilkan</span></div>
  </section>
@endif

{{-- ── Modal Tambah/Edit Produk ── --}}
<div class="modal" id="pkModal" hidden>
  <div class="dialog" role="dialog" aria-modal="true" aria-labelledby="pkModalT">
    <form id="packing-product-form" action="{{ route('admin.packing.store') }}" method="POST" enctype="multipart/form-data"
          data-store-url="{{ route('admin.packing.store') }}" data-base-url="{{ url('/admin/toko-packing') }}" style="display:contents">
      @csrf
      <input id="packing-form-method" type="hidden" name="_method" value="PUT" disabled>
      <div class="d-head">
        <div><h2 id="pkModalT">Tambah produk packing</h2><p id="packing-form-subtitle">Produk baru akan muncul di katalog Toko Packing.</p></div>
        <button class="icon-btn" type="button" id="btnCloseProdukModal" aria-label="Tutup"><svg class="ico" viewBox="0 0 24 24" aria-hidden="true"><path d="M18 6 6 18M6 6l12 12"/></svg></button>
      </div>
      <div class="d-body">
        <div class="field"><label for="kName">Nama produk <span class="req">*</span></label><input class="input" id="kName" name="name" required placeholder="Contoh: Kardus ukuran M"></div>
        <div class="field">
          <span class="lbl">Foto <span style="color:var(--tape)">*</span></span>
          <div class="drop" id="kDrop" role="button" tabindex="0" aria-describedby="kHelp">
            <span class="thumb">{!! \App\Support\Icons::svg('image') !!}</span>
            <span><b>Klik untuk pilih / tambah foto</b><small>JPG / PNG &middot; maks 5 MB per foto &middot; rasio 1:1, min. 500&times;500 px</small></span>
          </div>
          <input type="file" id="kFile" name="images[]" accept="image/png,image/jpeg" multiple hidden>
          <div class="previews" id="kPrev"></div>
          <span class="help" id="kHelp">Wajib minimal 1 foto, maksimal 10 foto per produk.</span>
          @error('images')<span class="help" style="color:var(--danger)">{{ $message }}</span>@enderror
        </div>
        <div class="grid2">
          <div class="field"><label for="kCat">Kategori</label>
            <select class="select" id="kCat" name="category">
              @foreach ($categories as $c)<option value="{{ $c }}">{{ $c }}</option>@endforeach
            </select>
          </div>
          <div class="field"><label for="kUnit">Satuan</label>
            <select class="select" id="kUnit" name="unit">
              @foreach ($units as $u)<option value="{{ $u }}">{{ $u }}</option>@endforeach
            </select>
          </div>
        </div>
        <div class="field"><label for="kPrice">Harga jual <span class="req">*</span></label><div class="affix"><span>Rp</span><input id="kPrice" name="price" type="number" min="0" placeholder="6000" inputmode="numeric" required></div></div>
        <div class="grid2">
          <div class="field"><label for="kStock">Stok awal</label><input class="input" id="kStock" name="stock" type="number" min="0" value="0" inputmode="numeric"></div>
          <div class="field"><label for="kMin">Ambang peringatan</label><input class="input" id="kMin" name="low_threshold" type="number" min="1" value="10" inputmode="numeric"><span class="help">Peringatan muncul kalau stok di bawah angka ini.</span></div>
        </div>
      </div>
      <div class="d-foot">
        <button class="btn btn-ghost" type="button" id="btnBatalProdukModal">Batal</button>
        <button class="btn btn-primary" type="submit">{!! \App\Support\Icons::svg('check', 'sm') !!} <span id="packing-form-submit-label">Tambah produk</span></button>
      </div>
    </form>
  </div>
</div>

<x-admin-wa-modal />
@endsection

@push('scripts')
<script>
(function () {
  /* Filter kategori (client-side) */
  var fEl = document.getElementById('kFilter');
  if (fEl) {
    fEl.addEventListener('click', function (e) {
      var b = e.target.closest('[data-c]'); if (!b) return;
      fEl.querySelectorAll('.chip').forEach(function (c) { c.setAttribute('aria-pressed', String(c === b)); });
      document.querySelectorAll('#kTable [data-category]').forEach(function (row) {
        row.hidden = b.dataset.c !== 'Semua' && row.dataset.category !== b.dataset.c;
      });
    });
  }

  /* Stepper stok fisik */
  document.querySelectorAll('.stock-form').forEach(function (form) {
    var input = form.querySelector('input[name="stock"]');
    var save = form.querySelector('.save-stock-btn');
    var original = Number(form.dataset.original || 0);
    function sync() { save.disabled = Number(input.value || 0) === original; }
    form.querySelector('[data-d="-1"]').addEventListener('click', function () { input.value = Math.max(0, Number(input.value || 0) - 1); sync(); });
    form.querySelector('[data-d="1"]').addEventListener('click', function () { input.value = Number(input.value || 0) + 1; sync(); });
    input.addEventListener('input', sync);
  });

  /* Modal tambah/edit produk */
  var modal = document.getElementById('pkModal');
  var form = document.getElementById('packing-product-form');
  var methodInput = document.getElementById('packing-form-method');
  var title = document.getElementById('pkModalT');
  var subtitle = document.getElementById('packing-form-subtitle');
  var submitLabel = document.getElementById('packing-form-submit-label');
  var photos = RA.photoInput('kDrop', 'kFile', 'kPrev', 10, 0);

  function fillForm(data) {
    ['name', 'category', 'unit', 'price', 'stock', 'low_threshold'].forEach(function (key) {
      if (form.elements[key]) form.elements[key].value = data[key] ?? '';
    });
  }

  function openCreate() {
    form.action = form.dataset.storeUrl;
    methodInput.disabled = true;
    title.textContent = 'Tambah produk packing';
    subtitle.textContent = 'Produk baru akan muncul di katalog Toko Packing.';
    submitLabel.textContent = 'Tambah produk';
    fillForm({ name: '', category: 'Kardus', unit: 'pcs', price: '', stock: 0, low_threshold: 10 });
    photos.reset(0);
    RA.open('pkModal');
  }

  function openEdit(item) {
    form.action = form.dataset.baseUrl + '/' + item.id;
    methodInput.disabled = false;
    title.textContent = 'Ubah produk';
    subtitle.textContent = item.name || '';
    submitLabel.textContent = 'Simpan perubahan';
    fillForm(item);
    photos.reset(Array.isArray(item.images) ? item.images.length : 0);
    RA.open('pkModal');
  }

  document.getElementById('btnTambahProduk')?.addEventListener('click', openCreate);
  document.querySelectorAll('[data-edit-item]').forEach(function (btn) {
    btn.addEventListener('click', function () { openEdit(JSON.parse(btn.dataset.editItem)); });
  });
  document.getElementById('btnCloseProdukModal')?.addEventListener('click', function () { RA.close(modal); });
  document.getElementById('btnBatalProdukModal')?.addEventListener('click', function () { RA.close(modal); });
})();
</script>
@endpush
