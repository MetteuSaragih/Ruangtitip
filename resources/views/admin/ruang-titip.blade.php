@extends('layouts.admin')

@section('title', 'Ruang Titip')

@php
$orderTabCfg = [
    ['key' => 'baru', 'label' => 'Baru masuk', 'hint' => 'Pesanan lunas yang menunggu dijemput kurir RuangTitip.'],
    ['key' => 'inspeksi', 'label' => 'Proses inspeksi', 'hint' => 'Barang sudah dijemput, cek kondisinya sebelum masuk rak.'],
    ['key' => 'gudang', 'label' => 'Dalam gudang', 'hint' => 'Barang tersimpan aman. Perhatikan yang hampir habis masa titipnya.'],
    ['key' => 'keluar', 'label' => 'Permintaan keluar', 'hint' => 'Pelanggan minta barangnya dikembalikan.'],
];
$returnTone = ['Ambil Sendiri' => 'n', 'Minta Diantar' => 'o', 'Ekspedisi Biteship' => 'y'];
$totalItems = collect($orderData)->flatten(1)->sum('qty');
@endphp

@section('content')
<div class="ph"><div><h1>Ruang Titip</h1><p>Kelola penitipan dari penjemputan sampai barang keluar gudang.</p></div></div>

<div class="seg" role="tablist" aria-label="Bagian halaman" data-client>
  <button type="button" role="tab" aria-controls="opsPanel" aria-selected="true">
    {!! \App\Support\Icons::svg('clipboard') !!}
    <span>Operasional pesanan<small>Penjemputan, inspeksi, dan barang keluar</small></span>
  </button>
  <button type="button" role="tab" aria-controls="roomPanel" aria-selected="false">
    {!! \App\Support\Icons::svg('warehouse') !!}
    <span>Manajemen ruangan<small>Data gudang yang tayang di aplikasi</small></span>
  </button>
</div>

<div id="opsPanel" role="tabpanel">
  <div class="stats">
    <div class="card stat">
      <div class="stat-top"><span class="stat-ico">{!! \App\Support\Icons::svg('box') !!}</span></div>
      <small>Barang di gudang</small>
      <strong>{{ $totalItems }} item</strong>
      <span class="sub">Dari {{ count($orderData['gudang']) }} pesanan aktif</span>
    </div>
    <div class="card stat">
      <div class="stat-top"><span class="stat-ico g">{!! \App\Support\Icons::svg('wallet') !!}</span><span class="pill {{ $pendapatanGrowth >= 0 ? 'g' : 'r' }}">{{ $pendapatanGrowth >= 0 ? '+' : '' }}{{ $pendapatanGrowth }}% dari bulan lalu</span></div>
      <small>Pendapatan penitipan</small>
      <strong>Rp {{ number_format($pendapatanBulanIni, 0, ',', '.') }}</strong>
      <span class="sub">Bulan ini</span>
    </div>
    <div class="card stat">
      <div class="stat-top"><span class="stat-ico s">{!! \App\Support\Icons::svg('truck') !!}</span></div>
      <small>Menunggu penjemputan</small>
      <strong>{{ count($orderData['baru']) }} pesanan</strong>
      <span class="sub"><span style="color:var(--tape-dark);font-weight:700">Perlu dijemput hari ini</span></span>
    </div>
  </div>

  <section class="card">
    <div class="ltabs" role="tablist" aria-label="Status pesanan" data-client>
      @foreach ($orderTabCfg as $i => $ot)
        <button role="tab" aria-selected="{{ $i === 0 ? 'true' : 'false' }}" aria-controls="statPanel-{{ $ot['key'] }}" type="button">
          {{ $ot['label'] }} <span class="n">{{ count($orderData[$ot['key']]) }}</span>
        </button>
      @endforeach
    </div>
    @foreach ($orderTabCfg as $i => $ot)
      @php $rows = $orderData[$ot['key']]; $isKeluar = $ot['key'] === 'keluar'; $isGudang = $ot['key'] === 'gudang'; @endphp
      <div id="statPanel-{{ $ot['key'] }}" role="tabpanel" @if($i !== 0) hidden @endif>
        <div class="card-h" style="border-bottom:0;padding-bottom:6px"><p style="color:var(--muted);font-size:14px">{{ $ot['hint'] }}</p></div>
        <div class="tbl-wrap">
          @if (count($rows) === 0)
            {!! \App\Support\Icons::empty('clipboard', 'Tidak ada pesanan di tahap ini', 'Pesanan akan muncul di sini sesuai statusnya.') !!}
          @else
            <table>
              <thead>
                <tr>
                  <th>ID pesanan</th><th>Pelanggan</th><th>Barang</th>
                  <th>{{ $isKeluar ? 'Metode keluar' : ($isGudang ? 'Kode rak' : 'Durasi') }}</th>
                  <th>{{ $isKeluar || $isGudang ? 'Berakhir / tenggat' : 'Jadwal jemput' }}</th>
                  <th><span class="sr">Aksi</span></th>
                </tr>
              </thead>
              <tbody>
                @foreach ($rows as $row)
                  <tr>
                    <td class="mono">
                      {{ $row['id'] }}
                      @if ($row['needsAttention'])
                        <span class="pill r" title="{{ $row['biteshipError'] ?? 'Pemesanan kurir Biteship gagal' }}" style="margin-top:4px">{!! \App\Support\Icons::svg('alert', 'sm') !!} Perlu tindakan</span>
                      @endif
                    </td>
                    <td>
                      <div class="who"><span class="avatar c{{ ($row['orderId'] % 4) + 1 }}">{{ \Illuminate\Support\Str::of($row['customer'])->trim()->explode(' ')->map(fn($w)=>mb_substr($w,0,1))->take(2)->implode('') }}</span><div><b>{{ $row['customer'] }}</b><small>{{ $row['wa'] }}</small></div></div>
                    </td>
                    <td>{{ $row['items'] }}<br><small style="color:var(--muted)">{{ $row['qty'] }} item</small></td>
                    @if ($isKeluar)
                      <td>@if ($row['returnMode'])<span class="pill {{ $returnTone[$row['returnMode']] ?? 'n' }}">{{ $row['returnMode'] }}</span>@endif</td>
                    @elseif ($isGudang)
                      <td><span class="pill k mono">{{ $row['rackCode'] ?? '–' }}</span></td>
                    @else
                      <td>{{ $row['duration'] }} bulan</td>
                    @endif
                    <td>{{ $row['deadline'] }}</td>
                    <td>
                      <div class="t-actions">
                        <button class="btn btn-sm {{ $row['hasProof'] ? 'btn-ghost' : 'btn-primary' }}" type="button"
                          title="{{ $row['hasProof'] ? 'Lihat bukti' : 'Upload bukti visual' }}"
                          onclick="openProofModal({{ $row['orderId'] }}, {{ $row['hasProof'] ? 'true' : 'false' }}, @js($row['proofUrl']))">
                          {!! \App\Support\Icons::svg($row['hasProof'] ? 'eye' : 'image', 'sm') !!}
                        </button>
                        <button class="btn btn-sm btn-ghost" type="button" {{ $row['hasProof'] ? '' : 'disabled' }}
                          title="{{ $row['hasProof'] ? 'Perbarui status' : 'Upload bukti terlebih dahulu' }}"
                          onclick="openStatusModal({{ $row['orderId'] }}, '{{ $row['statusKey'] }}')">
                          {!! \App\Support\Icons::svg('rotate', 'sm') !!}
                        </button>
                        @if ($isKeluar && $row['returnMode'] === 'Ekspedisi Biteship')
                          <button class="btn btn-sm btn-ghost" type="button" onclick="showResiToast(@js($row['trackingId']))">{!! \App\Support\Icons::svg('truck', 'sm') !!}</button>
                        @endif
                        <button class="btn btn-sm btn-green" type="button" {{ $row['waNumber'] ? '' : 'disabled' }}
                          title="{{ $row['waNumber'] ? 'Kirim pesan WhatsApp ke ' . $row['customer'] : 'Nomor WA pelanggan belum diisi' }}"
                          onclick="openWaModal(@js($row['waNumber']), @js($row['waTemplates']), @js('Kirim ke ' . $row['customer'] . ' (' . $row['wa'] . ')'))">
                          {!! \App\Support\Icons::svg('send', 'sm') !!}
                        </button>
                      </div>
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          @endif
        </div>
        <div class="card-f"><span>{{ count($rows) }} pesanan</span></div>
      </div>
    @endforeach
  </section>
</div>

<div id="roomPanel" role="tabpanel" hidden>
  <section class="card">
    <div class="card-h"><div><h2>Daftar gudang</h2><p>{{ $rooms->count() }} ruangan terdaftar</p></div>
      <button class="btn btn-primary" type="button" onclick="openRoomModal(null)">{!! \App\Support\Icons::svg('plus', 'sm') !!} Tambah ruangan</button>
    </div>
    <div class="card-b">
      @if ($rooms->isEmpty())
        {!! \App\Support\Icons::empty('warehouse', 'Belum ada ruangan', 'Klik "Tambah ruangan" untuk mulai.') !!}
      @else
        <div class="rooms">
          @foreach ($rooms as $room)
            @php
              $pct = $room->capacity_total > 0 ? round($room->capacity_used / $room->capacity_total * 100) : 0;
              $minP = collect($room->pricing['kardus'] ?? [])->min('price') ?? 0;
              $maxP = collect($room->pricing['koper'] ?? [])->max('price') ?? 0;
            @endphp
            <article class="room">
              <div class="room-img">
                @if ($room->primary_photo)
                  <img src="{{ asset('storage/' . $room->primary_photo) }}" alt="">
                @else
                  {!! \App\Support\Icons::svg('warehouse') !!}
                @endif
                <span class="pill {{ $room->active ? 'g' : 'n' }}">{{ $room->active ? 'Tayang' : 'Disembunyikan' }}</span>
              </div>
              <div class="room-b">
                <h3>{{ $room->name }}</h3>
                <p>{!! \App\Support\Icons::svg('pin', 'sm') !!}{{ \Illuminate\Support\Str::limit($room->address, 50) }}{{ $room->location ? ', ' . $room->location : '' }}</p>
                <div class="room-meta">
                  <div><small>Mulai</small><b>Rp {{ number_format($minP, 0, ',', '.') }}<span>/hari</span></b></div>
                  <div><small>Terisi</small><b>{{ $room->capacity_used }}<span> / {{ $room->capacity_total }} slot</span></b></div>
                </div>
                <span class="bar {{ $pct >= 85 ? 'r' : ($pct >= 70 ? 'o' : '') }}"><i style="width:{{ $pct }}%"></i></span>
                <div class="room-f">
                  <label class="toggle-row sm">
                    <span class="switch">
                      <input type="checkbox" {{ $room->active ? 'checked' : '' }} onchange="toggleRoomActive({{ $room->id }}, this)" aria-label="Tayangkan {{ $room->name }}">
                      <span></span>
                    </span>
                    <span>Tayang di aplikasi</span>
                  </label>
                  <div style="display:flex;gap:6px">
                    <button class="btn btn-sm btn-ghost" type="button" onclick='openRoomModal(@json($room))'>{!! \App\Support\Icons::svg('edit', 'sm') !!} Ubah</button>
                    <form method="POST" action="{{ route('admin.ruang-titip.destroy', $room) }}" data-confirm="Hapus {{ addslashes($room->name) }}?|Ruangan ini akan dihapus permanen." data-confirm-ok="Hapus">
                      @csrf @method('DELETE')
                      <button class="icon-btn sm" type="submit" aria-label="Hapus {{ $room->name }}">{!! \App\Support\Icons::svg('trash', 'sm') !!}</button>
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
</div>

{{-- ── Modal ruangan (tambah/ubah) ── --}}
<div class="modal" id="room-modal" hidden>
  <div class="dialog" role="dialog" aria-modal="true" aria-labelledby="roomModalT">
    <form id="room-form" method="POST" action="{{ route('admin.ruang-titip.store') }}" enctype="multipart/form-data" style="display:contents">
      @csrf
      <div id="method-field"></div>
      <div class="d-head">
        <div><h2 id="roomModalT">Tambah ruangan baru</h2><p id="modal-subtitle">Ruangan yang tayang akan muncul di aplikasi pelanggan.</p></div>
        <button class="icon-btn" type="button" onclick="closeRoomModal()" aria-label="Tutup"><svg class="ico" viewBox="0 0 24 24" aria-hidden="true"><path d="M18 6 6 18M6 6l12 12"/></svg></button>
      </div>
      <div class="d-body">
        <div class="grid2">
          <div class="field"><label for="field-name">Nama gudang <span class="req">*</span></label><input class="input" id="field-name" name="name" required placeholder="Contoh: Gudang Sumbersari"></div>
          <div class="field"><label for="field-location">Lokasi / kecamatan</label><input class="input" id="field-location" name="location" placeholder="Lowokwaru, Malang"></div>
        </div>
        <div class="field"><label for="field-address">Alamat lengkap <span class="req">*</span></label><input class="input" id="field-address" name="address" required placeholder="Jl. Sumbersari No. 12, Lowokwaru, Malang"></div>
        <div class="field"><label for="field-description">Deskripsi</label><textarea class="textarea" id="field-description" name="description" placeholder="Contoh: ruangan tertutup, ada CCTV, cocok untuk koper dan kardus."></textarea></div>
        <div class="field">
          <span class="lbl">Foto <span style="color:var(--tape)">*</span></span>
          <div class="drop" id="roomDrop" role="button" tabindex="0">
            <span class="thumb">{!! \App\Support\Icons::svg('image') !!}</span>
            <span><b>Klik untuk pilih / tambah foto</b><small>JPG / PNG &middot; maks 5 MB per foto &middot; rasio 4:3, min. 800&times;600 px</small></span>
          </div>
          <input type="file" id="roomFile" name="images[]" accept="image/png,image/jpeg" multiple hidden>
          <div class="previews" id="roomPrev"></div>
          <span class="help" id="room-images-label">Wajib minimal 1 foto, maksimal 10 foto per ruangan.</span>
          @error('images')<span class="help" style="color:var(--danger)">{{ $message }}</span>@enderror
        </div>

        <div>
          <p class="lbl" style="margin-bottom:8px">Harga per hari</p>
          <div class="card" style="margin-bottom:10px">
            <div class="card-h" style="padding:10px 16px"><h2 style="font-size:14px">📦 Kardus</h2></div>
            <div class="card-b grid2" id="pricing-kardus" style="grid-template-columns:repeat(4,1fr);gap:10px"></div>
          </div>
          <div class="card" style="margin-bottom:10px">
            <div class="card-h" style="padding:10px 16px"><h2 style="font-size:14px">🧳 Koper</h2></div>
            <div class="card-b grid2" id="pricing-koper" style="grid-template-columns:repeat(4,1fr);gap:10px"></div>
          </div>
          <div class="card">
            <div class="card-b" style="display:flex;align-items:center;gap:14px">
              <div style="flex:1"><b style="font-size:13px">📐 Dimensi lain</b><br><small style="color:var(--muted)">30&times;30&times;30 &ndash; 100&times;100&times;100 cm</small></div>
              <div class="affix" style="width:160px"><span>Rp</span><input type="number" id="field-dimensiLain" name="pricing[dimensiLain]" min="0" placeholder="0"></div>
            </div>
          </div>
        </div>

        <div class="field"><label for="field-capacity">Kapasitas total (slot)</label><input class="input" id="field-capacity" name="capacity_total" type="number" min="1" placeholder="500"></div>

        <div class="field">
          <span class="lbl">Fasilitas</span>
          <div class="opts" id="facilities-list">
            @foreach ($allFacilities as $f)
              <label><input type="checkbox" name="facilities[]" value="{{ $f }}"><span>{{ $f }}</span></label>
            @endforeach
          </div>
        </div>

        <label class="toggle-row">
          <span><b>Langsung tayang</b><small>Matikan kalau masih mau dicek dulu</small></span>
          <span class="switch"><input type="hidden" name="active" value="0"><input type="checkbox" id="field-active" name="active" value="1" checked><span></span></span>
        </label>
      </div>
      <div class="d-foot">
        <button class="btn btn-ghost" type="button" onclick="closeRoomModal()">Batal</button>
        <button class="btn btn-primary" type="submit">{!! \App\Support\Icons::svg('check', 'sm') !!} <span id="modal-submit-label">Simpan ruangan</span></button>
      </div>
    </form>
  </div>
</div>

{{-- ── Modal upload bukti ── --}}
<div class="modal" id="proof-modal" hidden>
  <div class="dialog sm" role="dialog" aria-modal="true" aria-labelledby="proofModalT">
    <div class="d-head">
      <div><h2 id="proofModalT">Upload bukti penitipan</h2><p>Foto kondisi barang saat diterima di gudang.</p></div>
      <button class="icon-btn" type="button" onclick="closeProofModal()" aria-label="Tutup"><svg class="ico" viewBox="0 0 24 24" aria-hidden="true"><path d="M18 6 6 18M6 6l12 12"/></svg></button>
    </div>
    <form id="proof-form" method="POST" enctype="multipart/form-data" style="display:contents">
      @csrf
      <div class="d-body">
        <div id="proof-existing" class="thumb" style="width:100%;height:160px;display:none">
          <img id="proof-existing-img" src="" alt="Bukti" style="width:100%;height:100%;object-fit:cover">
        </div>
        <div class="field"><label for="proof-photo-input">Foto bukti baru</label><input class="input" id="proof-photo-input" type="file" name="proof_photo" accept="image/*" required></div>
      </div>
      <div class="d-foot">
        <button class="btn btn-ghost" type="button" onclick="closeProofModal()">Batal</button>
        <button class="btn btn-primary" type="submit">{!! \App\Support\Icons::svg('check', 'sm') !!} Unggah</button>
      </div>
    </form>
  </div>
</div>

{{-- ── Modal perbarui status ── --}}
<div class="modal" id="status-modal" hidden>
  <div class="dialog sm" role="dialog" aria-modal="true" aria-labelledby="statusModalT">
    <div class="d-head">
      <div><h2 id="statusModalT">Perbarui status pesanan</h2><p>Ubah fase penitipan barang pelanggan.</p></div>
      <button class="icon-btn" type="button" onclick="closeStatusModal()" aria-label="Tutup"><svg class="ico" viewBox="0 0 24 24" aria-hidden="true"><path d="M18 6 6 18M6 6l12 12"/></svg></button>
    </div>
    <form id="status-form" method="POST" style="display:contents">
      @csrf
      <div class="d-body">
        @foreach (\App\Models\TitipanOrder::FLOW as $key => $meta)
          <label class="toggle-row" style="cursor:pointer">
            <span style="display:flex;align-items:center;gap:10px"><input type="radio" name="status" value="{{ $key }}" style="width:16px;height:16px;accent-color:var(--tape)"><b style="font-weight:600;font-size:14px">{{ $meta['label'] }}</b></span>
          </label>
        @endforeach
      </div>
      <div class="d-foot">
        <button class="btn btn-ghost" type="button" onclick="closeStatusModal()">Batal</button>
        <button class="btn btn-primary" type="submit">{!! \App\Support\Icons::svg('check', 'sm') !!} Simpan status</button>
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
  var DEFAULT_PRICING = @json($defaultPricing);
  var photos = RA.photoInput('roomDrop', 'roomFile', 'roomPrev', 10, 0);

  function buildPricingFields(pricing) {
    ['kardus', 'koper'].forEach(function (type) {
      var container = document.getElementById('pricing-' + type);
      container.innerHTML = '';
      (pricing[type] || []).forEach(function (row) {
        var div = document.createElement('div');
        div.className = 'field';
        div.innerHTML = '<span class="help" style="text-align:center;font-weight:700;color:var(--ink)">' + row.label + '</span>' +
          '<span class="help" style="text-align:center;margin-top:-6px">' + row.dims + '</span>' +
          '<div class="affix"><span>Rp</span><input type="number" name="pricing[' + type + '][' + row.id + ']" min="0" value="' + row.price + '" placeholder="0"></div>';
        container.appendChild(div);
      });
    });
    document.getElementById('field-dimensiLain').value = pricing.dimensiLain || 0;
  }

  window.openRoomModal = function (room) {
    var form = document.getElementById('room-form');
    var mf = document.getElementById('method-field');
    document.querySelectorAll('#facilities-list input[type=checkbox]').forEach(function (cb) { cb.checked = false; });

    if (room) {
      document.getElementById('roomModalT').textContent = 'Ubah ruangan';
      document.getElementById('modal-subtitle').textContent = 'Mengubah: ' + room.name;
      document.getElementById('modal-submit-label').textContent = 'Simpan perubahan';
      form.action = '{{ url('/admin/ruang-titip') }}/' + room.id;
      mf.innerHTML = '<input type="hidden" name="_method" value="PUT">';

      form.elements['name'].value = room.name || '';
      form.elements['location'].value = room.location || '';
      form.elements['address'].value = room.address || '';
      form.elements['description'].value = room.description || '';
      document.getElementById('field-capacity').value = room.capacity_total || '';
      buildPricingFields(room.pricing || DEFAULT_PRICING);

      var roomFacs = room.facilities || [];
      document.querySelectorAll('#facilities-list input[type=checkbox]').forEach(function (cb) {
        cb.checked = roomFacs.indexOf(cb.value) !== -1;
      });

      document.getElementById('field-active').checked = room.active == true || room.active == 1;
      photos.reset(Array.isArray(room.photos) ? room.photos.length : 0);
    } else {
      document.getElementById('roomModalT').textContent = 'Tambah ruangan baru';
      document.getElementById('modal-subtitle').textContent = 'Ruangan yang tayang akan muncul di aplikasi pelanggan.';
      document.getElementById('modal-submit-label').textContent = 'Simpan ruangan';
      form.action = '{{ route('admin.ruang-titip.store') }}';
      mf.innerHTML = '';
      form.reset();
      buildPricingFields(DEFAULT_PRICING);
      document.getElementById('field-active').checked = true;
      photos.reset(0);
    }
    RA.open('room-modal');
  };
  window.closeRoomModal = function () { RA.close('room-modal'); };

  window.toggleRoomActive = function (id, el) {
    fetch('{{ url('/admin/ruang-titip') }}/' + id + '/toggle-active', {
      method: 'POST',
      headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
    }).then(function (r) { return r.json(); }).then(function (data) {
      var card = el.closest('.room');
      var pill = card.querySelector('.room-img .pill');
      pill.textContent = data.active ? 'Tayang' : 'Disembunyikan';
      pill.className = 'pill ' + (data.active ? 'g' : 'n');
      el.checked = data.active;
      RA.toast(data.active ? 'Ruangan sekarang tayang' : 'Ruangan disembunyikan');
    });
  };

  window.openProofModal = function (orderId, hasProof, proofUrl) {
    var form = document.getElementById('proof-form');
    var existing = document.getElementById('proof-existing');
    var existingImg = document.getElementById('proof-existing-img');
    document.getElementById('proofModalT').textContent = hasProof ? 'Perbarui bukti penitipan' : 'Upload bukti penitipan';
    form.action = '{{ url('/admin/ruang-titip/orders') }}/' + orderId + '/proof';
    form.reset();
    if (hasProof && proofUrl) { existingImg.src = proofUrl; existing.style.display = 'block'; } else { existing.style.display = 'none'; }
    RA.open('proof-modal');
  };
  window.closeProofModal = function () { RA.close('proof-modal'); };

  window.openStatusModal = function (orderId, currentStatus) {
    var form = document.getElementById('status-form');
    form.action = '{{ url('/admin/ruang-titip/orders') }}/' + orderId + '/status';
    form.querySelectorAll('input[name="status"]').forEach(function (input) { input.checked = input.value === currentStatus; });
    RA.open('status-modal');
  };
  window.closeStatusModal = function () { RA.close('status-modal'); };

  window.currentResiNumber = '';
  window.showResiToast = function (trackingId) {
    window.currentResiNumber = trackingId || '';
    var t = document.getElementById('resi-toast');
    document.getElementById('resi-toast-number').textContent = window.currentResiNumber || 'Belum tersedia (resi belum dibuat Biteship)';
    t.classList.add('show');
    clearTimeout(window._resiTimer);
    window._resiTimer = setTimeout(function () { t.classList.remove('show'); }, 5000);
  };

  buildPricingFields(DEFAULT_PRICING);
})();
</script>
@endpush
