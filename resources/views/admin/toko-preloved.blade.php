@extends('layouts.admin')

@section('title', 'Toko Preloved')

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

$statusTone = ['Tersedia' => 'g', 'Terjual' => 'k', 'Draft' => 'n'];
$conditionTone = fn ($c) => $c >= 90 ? 'g' : ($c >= 80 ? 'y' : 'o');
$orderStatusTone = ['paid' => 'y', 'processing' => 'n', 'shipped' => 'o', 'delivered' => 'g', 'cancelled' => 'r'];
$deliveryLabel = ['pickup' => 'Ambil sendiri', 'biteship' => 'Ekspedisi Biteship'];
$filterOptions = array_merge(['Semua'], array_values($orderStatusLabels));
@endphp

@section('content')
<div class="ph"><div><h1>Toko Preloved</h1><p>Jual kembali barang bekas mahasiswa, dari katalog sampai pengiriman.</p></div></div>

<div class="stats">
  <div class="card stat">
    <div class="stat-top"><span class="stat-ico">{!! \App\Support\Icons::svg('bag') !!}</span></div>
    <small>Barang tayang</small>
    <strong>{{ $available }} item</strong>
    <span class="sub">Tersedia di aplikasi pelanggan</span>
  </div>
  <div class="card stat">
    <div class="stat-top"><span class="stat-ico g">{!! \App\Support\Icons::svg('trend') !!}</span></div>
    <small>Terjual</small>
    <strong>{{ $sold }} barang</strong>
    <span class="sub"><span class="pill g">Total terjual</span></span>
  </div>
  <div class="card stat">
    <div class="stat-top"><span class="stat-ico s">{!! \App\Support\Icons::svg('wallet') !!}</span></div>
    <small>Pendapatan preloved</small>
    <strong>Rp {{ number_format($revenue, 0, ',', '.') }}</strong>
    <span class="sub">Dari pesanan selesai</span>
  </div>
</div>

<div class="seg" role="tablist" aria-label="Bagian halaman">
  <a role="tab" aria-selected="{{ $pageTab === 'katalog' ? 'true' : 'false' }}" href="{{ route('admin.preloved', ['tab' => 'katalog']) }}">
    {!! \App\Support\Icons::svg('bag') !!}
    <span>Katalog barang<small>Kelola barang yang dijual</small></span>
  </a>
  <a role="tab" aria-selected="{{ $pageTab === 'pesanan' ? 'true' : 'false' }}" href="{{ route('admin.preloved', ['tab' => 'pesanan']) }}">
    {!! \App\Support\Icons::svg('clipboard') !!}
    <span>Pesanan preloved<small>Pantau transaksi dan pengiriman</small></span>
  </a>
</div>

@if ($pageTab === 'pesanan')
  <section class="card">
    <div class="card-h">
      <div><h2>Transaksi preloved</h2><p>{{ $orders->count() }} pesanan</p></div>
      <div class="chips">
        @foreach ($filterOptions as $f)
          <a class="chip" aria-pressed="{{ $filter === $f ? 'true' : 'false' }}" href="{{ route('admin.preloved', ['tab' => 'pesanan', 'filter' => $f]) }}">{{ $f }}</a>
        @endforeach
      </div>
    </div>
    <div class="tbl-wrap">
      @if ($orders->isEmpty())
        {!! \App\Support\Icons::empty('bag', 'Belum ada pesanan', 'Transaksi dengan status ini akan muncul di sini.') !!}
      @else
        <table>
          <thead><tr><th>ID pesanan</th><th>Barang</th><th>Pembeli</th><th>Pengiriman</th><th>Alamat</th><th>Status</th><th><span class="sr">Aksi</span></th></tr></thead>
          <tbody>
            @foreach ($orders as $order)
              @php
                $isDone = $order->status === 'delivered';
                $isCancelled = $order->status === 'cancelled';
                $nextCode = match ($order->status) {
                    'paid' => 'processing',
                    'processing' => $order->shipping_method === 'biteship' ? 'shipped' : 'delivered',
                    'shipped' => 'delivered',
                    default => null,
                };
                $waNumber = rt_wa_number($order->customer_phone);
                $waTemplates = [
                    ['key' => 'diproses', 'label' => 'Pesanan Sedang Diproses', 'text' => "Halo {$order->customer_name}, pesananmu (kode {$order->order_number}) sedang kami proses. Mohon ditunggu ya! 📦"],
                    ['key' => 'dikirim', 'label' => 'Pesanan Sudah Dikirim', 'text' => "Halo {$order->customer_name}, pesananmu (kode {$order->order_number}) sudah dikirim. Terima kasih telah berbelanja di RuangTitip Preloved! 🚚"],
                    ['key' => 'selesai', 'label' => 'Pesanan Sudah Diterima/Selesai', 'text' => "Halo {$order->customer_name}, terima kasih! Pesananmu (kode {$order->order_number}) sudah selesai. Semoga puas dengan barangnya ya 🙏"],
                ];
              @endphp
              <tr>
                <td><span class="mono">{{ $order->order_number }}</span><br><small style="color:var(--muted)">{{ $order->created_at->format('d M Y') }}</small></td>
                <td><b>{{ $order->preloved_item_names ?: '-' }}</b><br><small style="color:var(--muted)">Rp {{ number_format($order->preloved_subtotal, 0, ',', '.') }}</small></td>
                <td>{{ $order->customer_name }}<br><small style="color:var(--muted)">{{ $order->customer_phone }}</small></td>
                <td><span class="pill n">{!! \App\Support\Icons::svg($order->shipping_method === 'biteship' ? 'truck' : 'warehouse', 'sm') !!}{{ $deliveryLabel[$order->shipping_method] ?? $order->shipping_method }}</span></td>
                <td style="max-width:200px"><small>{{ $order->shipping_address['full'] ?? '-' }}</small></td>
                <td><span class="pill {{ $orderStatusTone[$order->status] ?? 'n' }}">{{ $orderStatusLabels[$order->status] ?? $order->status }}</span></td>
                <td>
                  <div class="t-actions">
                    @if ($nextCode && ! $isCancelled)
                      <form method="POST" action="{{ route('admin.preloved.advance-order', $order) }}">
                        @csrf
                        <button class="btn btn-sm btn-primary" type="submit">{{ $orderStatusLabels[$nextCode] ?? 'Lanjutkan' }}</button>
                      </form>
                    @endif
                    @if ($order->shipping_method === 'biteship' && ! $isDone && ! $isCancelled)
                      <button class="btn btn-sm btn-ghost" type="button" onclick="showResiToast(@js($order->biteship_tracking_id))">Resi</button>
                    @endif
                    @if (! $isDone && ! $isCancelled)
                      <button class="btn btn-sm btn-green" type="button" {{ $waNumber ? '' : 'disabled' }}
                        title="{{ $waNumber ? 'Kirim pesan WhatsApp ke ' . $order->customer_name : 'Nomor WA pelanggan belum diisi' }}"
                        onclick="openWaModal(@js($waNumber), @js($waTemplates), @js('Kirim ke ' . $order->customer_name . ' (' . ($order->customer_phone ?: '-') . ')'))">
                        {!! \App\Support\Icons::svg('send', 'sm') !!}
                      </button>
                    @endif
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
      <div><h2>Katalog barang preloved</h2><p>{{ $available }} tersedia &middot; {{ $sold }} terjual &middot; {{ $items->count() }} total</p></div>
      <button class="btn btn-primary" type="button" id="btnTambahBarang">{!! \App\Support\Icons::svg('plus', 'sm') !!} Tambah barang</button>
    </div>
    <div class="card-b">
      @if ($items->isEmpty())
        {!! \App\Support\Icons::empty('bag', 'Belum ada barang', 'Klik "Tambah barang" untuk mulai mengisi katalog.') !!}
      @else
        <div class="products">
          @foreach ($items as $item)
            @php $cl = $conditionLabels[$item->condition] ?? $conditionLabels[80]; @endphp
            <article class="prod {{ $item->status === 'Terjual' ? 'sold' : '' }}">
              <div class="prod-img">
                @if ($item->primary_photo)
                  <img src="{{ asset('storage/' . $item->primary_photo) }}" alt="">
                @else
                  {!! \App\Support\Icons::svg('tag') !!}
                @endif
                <span class="pill {{ $statusTone[$item->status] ?? 'n' }}">{{ $item->status }}</span>
              </div>
              <div class="prod-b">
                <small>{{ $item->category }} &middot; <span class="pill {{ $conditionTone($item->condition) }}" style="height:auto;padding:1px 7px">{{ $cl['label'] }}</span></small>
                <h3>{{ $item->name }}</h3>
                <b>Rp {{ number_format($item->price, 0, ',', '.') }}</b>
                <div class="prod-f">
                  @if ($item->status !== 'Terjual')
                    <button class="btn btn-sm btn-ghost" type="button" data-edit-item="{{ e($item->toJson()) }}">{!! \App\Support\Icons::svg('edit', 'sm') !!} Ubah</button>
                  @else
                    <span></span>
                  @endif
                  <div style="display:flex;gap:6px">
                    @if ($item->status !== 'Terjual')
                      <form method="POST" action="{{ route('admin.preloved.toggle-draft', $item) }}">
                        @csrf
                        <button class="icon-btn sm" type="submit" title="{{ $item->status === 'Draft' ? 'Publikasikan' : 'Jadikan draft' }}" aria-label="{{ $item->status === 'Draft' ? 'Publikasikan' : 'Jadikan draft' }}">
                          {!! \App\Support\Icons::svg($item->status === 'Draft' ? 'eye' : 'rotate', 'sm') !!}
                        </button>
                      </form>
                    @endif
                    <form method="POST" action="{{ route('admin.preloved.destroy', $item) }}" data-confirm="Hapus {{ addslashes($item->name) }}?|Barang ini akan dihapus permanen dari katalog." data-confirm-ok="Hapus">
                      @csrf @method('DELETE')
                      <button class="icon-btn sm" type="submit" aria-label="Hapus {{ $item->name }}">{!! \App\Support\Icons::svg('trash', 'sm') !!}</button>
                    </form>
                  </div>
                </div>
              </div>
            </article>
          @endforeach
        </div>
      @endif
    </div>
  </section>
@endif

{{-- ── Modal Tambah/Edit Barang ── --}}
<div class="modal" id="plModal" hidden>
  <div class="dialog" role="dialog" aria-modal="true" aria-labelledby="plModalT">
    <form id="item-form" method="POST" action="{{ route('admin.preloved.store') }}" enctype="multipart/form-data" style="display:contents">
      @csrf
      <div id="method-field"></div>
      <div class="d-head">
        <div><h2 id="plModalT">Tambah barang preloved</h2><p id="modal-subtitle">Barang titipan mahasiswa yang dijual lewat RuangTitip.</p></div>
        <button class="icon-btn" type="button" id="btnClosePlModal" aria-label="Tutup"><svg class="ico" viewBox="0 0 24 24" aria-hidden="true"><path d="M18 6 6 18M6 6l12 12"/></svg></button>
      </div>
      <div class="d-body">
        <div class="field">
          <span class="lbl">Foto <span style="color:var(--tape)">*</span></span>
          <div class="drop" id="pDrop" role="button" tabindex="0" aria-describedby="pHelp">
            <span class="thumb">{!! \App\Support\Icons::svg('image') !!}</span>
            <span><b>Klik untuk pilih / tambah foto</b><small>JPG / PNG &middot; maks 5 MB per foto &middot; rasio 1:1, min. 500&times;500 px</small></span>
          </div>
          <input type="file" id="pFile" name="images[]" accept="image/png,image/jpeg" multiple hidden>
          <div class="previews" id="pPrev"></div>
          <span class="help" id="pHelp">Wajib minimal 1 foto, maksimal 10 foto per produk.</span>
          @error('images')<span class="help" style="color:var(--danger)">{{ $message }}</span>@enderror
        </div>
        <div class="grid2">
          <div class="field"><label for="field-name">Nama barang <span class="req">*</span></label><input class="input" id="field-name" name="name" required placeholder="Contoh: Koper Polo 24 inci"></div>
          <div class="field"><label for="field-category">Kategori</label>
            <select class="select" id="field-category" name="category">
              @foreach ($categories as $cat)<option value="{{ $cat }}">{{ $cat }}</option>@endforeach
            </select>
          </div>
        </div>
        <div class="field">
          <span class="lbl">Kondisi barang</span>
          <div class="opts" id="condition-buttons">
            @foreach ($conditions as $c)
              <label><input type="radio" name="condition_radio" data-value="{{ $c }}" {{ $c === 90 ? 'checked' : '' }}><span>{{ $conditionLabels[$c]['label'] }}</span></label>
            @endforeach
          </div>
          <input type="hidden" name="condition" id="field-condition" value="90">
        </div>
        <div class="grid2">
          <div class="field"><label for="field-price">Harga jual <span class="req">*</span></label><div class="affix"><span>Rp</span><input id="field-price" name="price" type="number" min="1000" placeholder="150000" inputmode="numeric" required></div></div>
          <div class="field"><label for="field-seller">Pemilik titipan</label><input class="input" id="field-seller" name="seller" placeholder="Nama mahasiswa"></div>
        </div>
        <div class="field">
          <span class="lbl">Berat &amp; dimensi <span style="color:var(--tape)">*</span></span>
          <span class="help">Dipakai untuk menghitung ongkir Biteship secara akurat.</span>
        </div>
        <div class="grid2">
          <div class="field"><label for="field-weight">Berat (gram)</label><input class="input" id="field-weight" name="weight" type="number" min="1" value="1000" inputmode="numeric" required></div>
          <div class="field"><label for="field-length">Panjang (cm)</label><input class="input" id="field-length" name="length" type="number" min="1" value="30" inputmode="numeric" required></div>
          <div class="field"><label for="field-width">Lebar (cm)</label><input class="input" id="field-width" name="width" type="number" min="1" value="20" inputmode="numeric" required></div>
          <div class="field"><label for="field-height">Tinggi (cm)</label><input class="input" id="field-height" name="height" type="number" min="1" value="15" inputmode="numeric" required></div>
        </div>
      </div>
      <div class="d-foot">
        <button class="btn btn-ghost" type="button" id="btnBatalPlModal">Batal</button>
        <button class="btn btn-primary" type="submit">{!! \App\Support\Icons::svg('check', 'sm') !!} <span id="modal-submit-label">Tambah ke katalog</span></button>
      </div>
    </form>
  </div>
</div>

{{-- Resi toast --}}
<div class="toast" id="resi-toast" style="bottom:90px">
  <span>📦</span>
  <span><b style="display:block">Nomor resi</b><span id="resi-toast-number" class="mono"></span></span>
</div>

<x-admin-wa-modal />
@endsection

@push('scripts')
<script>
(function () {
  var CONDITION_COLORS = @json($conditionLabels);
  var modal = document.getElementById('plModal');
  var form = document.getElementById('item-form');
  var title = document.getElementById('plModalT');
  var subtitle = document.getElementById('modal-subtitle');
  var submitLabel = document.getElementById('modal-submit-label');
  var photos = RA.photoInput('pDrop', 'pFile', 'pPrev', 10, 0);
  var storeUrl = @json(route('admin.preloved.store'));

  function countItemImages(item) {
    var p = Array.isArray(item.photos) ? item.photos.slice() : [];
    if (item.photo && p.indexOf(item.photo) === -1) p.push(item.photo);
    return p.length;
  }

  function selectCondition(val) {
    document.getElementById('field-condition').value = val;
    document.querySelectorAll('#condition-buttons input[type=radio]').forEach(function (r) {
      r.checked = Number(r.dataset.value) === Number(val);
    });
  }
  document.querySelectorAll('#condition-buttons input[type=radio]').forEach(function (r) {
    r.addEventListener('change', function () { selectCondition(r.dataset.value); });
  });

  function openCreate() {
    form.reset();
    form.action = storeUrl;
    document.getElementById('method-field').innerHTML = '';
    title.textContent = 'Tambah barang preloved';
    subtitle.textContent = 'Barang titipan mahasiswa yang dijual lewat RuangTitip.';
    submitLabel.textContent = 'Tambah ke katalog';
    selectCondition(90);
    photos.reset(0);
    RA.open('plModal');
  }

  function openEdit(item) {
    form.action = '{{ url('/admin/preloved') }}/' + item.id;
    document.getElementById('method-field').innerHTML = '<input type="hidden" name="_method" value="PUT">';
    title.textContent = 'Ubah barang';
    subtitle.textContent = 'Mengubah: ' + item.name;
    submitLabel.textContent = 'Simpan perubahan';
    form.elements['name'].value = item.name || '';
    form.elements['category'].value = item.category || '';
    form.elements['price'].value = item.price || '';
    form.elements['seller'].value = item.seller || '';
    form.elements['weight'].value = item.weight || 1000;
    form.elements['length'].value = item.length || 30;
    form.elements['width'].value = item.width || 20;
    form.elements['height'].value = item.height || 15;
    selectCondition(item.condition || 90);
    photos.reset(countItemImages(item));
    RA.open('plModal');
  }

  document.getElementById('btnTambahBarang')?.addEventListener('click', openCreate);
  document.querySelectorAll('[data-edit-item]').forEach(function (btn) {
    btn.addEventListener('click', function () { openEdit(JSON.parse(btn.dataset.editItem)); });
  });
  document.getElementById('btnClosePlModal')?.addEventListener('click', function () { RA.close(modal); });
  document.getElementById('btnBatalPlModal')?.addEventListener('click', function () { RA.close(modal); });

  window.currentResiNumber = '';
  window.showResiToast = function (trackingId) {
    window.currentResiNumber = trackingId || '';
    var t = document.getElementById('resi-toast');
    document.getElementById('resi-toast-number').textContent = window.currentResiNumber || 'Belum tersedia (resi belum dibuat Biteship)';
    t.classList.add('show');
    clearTimeout(window._resiTimer);
    window._resiTimer = setTimeout(function () { t.classList.remove('show'); }, 5000);
  };
})();
</script>
@endpush
