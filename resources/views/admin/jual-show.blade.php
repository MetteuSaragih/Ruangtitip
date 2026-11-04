@extends('layouts.admin')

@section('title', 'Pengajuan ' . $s->code)

@php
$meta = \App\Models\PrelovedSubmission::STATUS[$s->status] ?? [$s->status, 'mute'];
$stepLabels = ['Seleksi', 'Jadwal', 'Antar', 'Periksa', 'Penawaran', 'Pembayaran', 'Selesai'];
$stepIndex = ['seleksi' => 0, 'tolak' => 0, 'jadwal' => 1, 'antar' => 2, 'periksa' => 3, 'tidak' => 3, 'tawar' => 4, 'batal' => 4, 'titip' => 5, 'sepakat' => 5, 'dibeli' => 7];
$cur = $stepIndex[$s->status] ?? 0;
$ended = in_array($s->status, ['tolak', 'tidak', 'batal'], true);
$photos = collect($s->photos ?? [])->filter()->values();
$slotLabel = $s->schedule_slot ? ($slots[$s->schedule_slot] ?? $s->schedule_slot) : null;
$logs = collect($s->log ?? [])->reverse()->values();
@endphp

@push('styles')
<style>
  .js-photos { display: grid; grid-template-columns: repeat(auto-fill, minmax(96px, 1fr)); gap: 8px; }
  .js-photos a { display: block; aspect-ratio: 1; border-radius: 10px; overflow: hidden; border: 1px solid var(--color-border); background: var(--color-aqua); }
  .js-photos img { width: 100%; height: 100%; object-fit: cover; display: block; }
  .js-text { white-space: pre-line; }
</style>
@endpush

@section('content')
<div class="ph">
  <div>
    <h1>{{ $s->name }}</h1>
    <p><span class="mono">{{ $s->code }}</span> &middot; diajukan {{ optional($s->created_at)->format('d M Y, H:i') }}</p>
  </div>
  <div class="acts">
    <span class="st {{ $meta[1] }}">{{ $meta[0] }}</span>
    <a class="btn btn-ghost" href="{{ route('admin.jual.index') }}">Kembali ke daftar</a>
  </div>
</div>

<div class="stack">
  <div class="card">
    <ol class="steps" aria-label="Tahap pengajuan">
      @foreach ($stepLabels as $i => $label)
        <li class="{{ $i < $cur ? 'done' : ($i === $cur ? 'cur' : '') }}">{{ $label }}</li>
      @endforeach
    </ol>
  </div>

  {{-- Aksi sesuai status --}}
  @switch($s->status)
    @case('seleksi')
      <div class="al warn">
        <svg class="ic" aria-hidden="true"><use href="#i-warn"/></svg>
        <div><b>Pengajuan menunggu seleksi</b><p>Periksa foto dan data barang. Bila layak, setujui agar pelanggan bisa memilih jadwal antar.</p></div>
        <div class="acts">
          <form method="POST" action="{{ route('admin.jual.act', $s->code) }}" data-confirm="Setujui pengajuan {{ $s->code }}? Pelanggan akan diminta memilih jadwal antar." data-confirm-title="Setujui pengajuan" data-confirm-ok="Setujui">
            @csrf
            <input type="hidden" name="aksi" value="approve">
            <button class="btn btn-primary sm" type="submit">Setujui</button>
          </form>
          <button class="btn btn-danger sm" type="button" data-open="dlgReject">Tolak</button>
        </div>
      </div>
      @break

    @case('jadwal')
      <div class="al info">
        <svg class="ic" aria-hidden="true"><use href="#i-cal"/></svg>
        <div><b>Menunggu pelanggan memilih jadwal antar</b><p>Pelanggan memilih sendiri. Bila perlu, Anda dapat mengaturkan jadwalnya.</p></div>
        <div class="acts"><button class="btn btn-ghost sm" type="button" data-open="dlgSchedule">Atur jadwal</button></div>
      </div>
      @break

    @case('antar')
      <div class="al info">
        <svg class="ic" aria-hidden="true"><use href="#i-truck"/></svg>
        <div>
          <b>Menunggu barang diantar</b>
          <p>Jadwal: {{ optional($s->schedule_date)->locale('id')->translatedFormat('j F Y') ?? '-' }}{{ $slotLabel ? ', ' . $slotLabel : '' }}</p>
        </div>
        <div class="acts">
          <form method="POST" action="{{ route('admin.jual.act', $s->code) }}" data-confirm="Catat barang {{ $s->name }} sudah sampai di gudang dan mulai diperiksa?" data-confirm-title="Barang datang" data-confirm-ok="Barang datang">
            @csrf
            <input type="hidden" name="aksi" value="arrived">
            <button class="btn btn-primary sm" type="submit">Barang datang</button>
          </form>
        </div>
      </div>
      @break

    @case('periksa')
      <div class="al warn">
        <svg class="ic" aria-hidden="true"><use href="#i-warn"/></svg>
        <div><b>Barang sedang diperiksa</b><p>Setelah diperiksa, buat penawaran harga beli atau tutup pengajuan bila barang tidak dibeli.</p></div>
        <div class="acts">
          <button class="btn btn-primary sm" type="button" data-open="dlgOffer">Buat penawaran</button>
          <button class="btn btn-danger sm" type="button" data-open="dlgNotBuy">Tidak dibeli</button>
        </div>
      </div>
      @break

    @case('tawar')
      <div class="al info">
        <svg class="ic" aria-hidden="true"><use href="#i-clock"/></svg>
        <div><b>Menunggu keputusan pelanggan</b><p>Penawaran Rp {{ number_format((int) $s->offer, 0, ',', '.') }} sudah dikirim. Pelanggan dapat menerima atau menolak.</p></div>
      </div>
      @break

    @case('titip')
      <div class="al warn">
        <svg class="ic" aria-hidden="true"><use href="#i-warn"/></svg>
        <div><b>Pelanggan menyelesaikan tagihan titipan</b><p>Barang berasal dari titipan. Setelah tagihan titipan selesai, lanjutkan ke pembayaran ke penjual.</p></div>
        <div class="acts">
          <form method="POST" action="{{ route('admin.jual.act', $s->code) }}" data-confirm="Catat tagihan titipan sudah selesai dan lanjut ke pembayaran ke penjual?" data-confirm-title="Tagihan titipan selesai" data-confirm-ok="Lanjutkan">
            @csrf
            <input type="hidden" name="aksi" value="titip_ok">
            <button class="btn btn-primary sm" type="submit">Tagihan titipan selesai</button>
          </form>
        </div>
      </div>
      @break

    @case('sepakat')
      <div class="al warn">
        <svg class="ic" aria-hidden="true"><use href="#n-coin"/></svg>
        <div>
          <b>Bayar penjual Rp {{ number_format((int) $s->offer, 0, ',', '.') }}</b>
          <p>Transfer ke {{ $s->bank_name }} {{ $s->bank_number }} a.n. {{ $s->bank_holder }}, lalu catat pembayaran.</p>
        </div>
        <div class="acts">
          <form method="POST" action="{{ route('admin.jual.act', $s->code) }}" data-confirm="Catat bahwa pembayaran Rp {{ number_format((int) $s->offer, 0, ',', '.') }} ke penjual sudah dilakukan? Draft barang akan dibuat di katalog." data-confirm-title="Catat pembayaran ke penjual" data-confirm-ok="Catat pembayaran">
            @csrf
            <input type="hidden" name="aksi" value="paid">
            <button class="btn btn-primary sm" type="submit">Catat pembayaran ke penjual</button>
          </form>
        </div>
      </div>
      @break

    @case('dibeli')
      <div class="al ok">
        <svg class="ic" aria-hidden="true"><use href="#i-check"/></svg>
        <div>
          <b>Barang sudah dibeli Ruang Titip</b>
          <p>Pembayaran Rp {{ number_format((int) $s->offer, 0, ',', '.') }} ke penjual sudah dicatat.@if (! $draft) Draft katalog tidak ditemukan; tambahkan barang secara manual bila perlu.@endif</p>
        </div>
        <div class="acts">
          @if ($draft)
            <a class="btn btn-primary sm" href="{{ route('admin.preloved', ['tab' => 'draft', 'q' => $s->code]) }}">Buka draft di katalog</a>
          @else
            <a class="btn btn-ghost sm" href="{{ route('admin.preloved') }}">Buka katalog</a>
          @endif
        </div>
      </div>
      @break

    @case('tolak')
      <div class="al err">
        <svg class="ic" aria-hidden="true"><use href="#i-x"/></svg>
        <div><b>{{ $meta[0] }}</b><p>{{ $s->reason ?: 'Tanpa alasan tercatat.' }}</p></div>
      </div>
      @break

    @case('tidak')
      <div class="al err">
        <svg class="ic" aria-hidden="true"><use href="#i-x"/></svg>
        <div><b>{{ $meta[0] }}</b><p>{{ $s->reason ?: 'Tanpa alasan tercatat.' }}</p></div>
      </div>
      @break

    @case('batal')
      <div class="al">
        <svg class="ic" aria-hidden="true"><use href="#i-info"/></svg>
        <div><b>Pelanggan menolak penawaran</b><p>Pembelian dibatalkan. Tidak ada tindakan lanjutan.</p></div>
      </div>
      @break
  @endswitch

  <div class="cols">
    <div class="stack">
      <div class="card">
        <div class="card-h"><h3>Data barang</h3></div>
        <div class="kv">
          <div><span>Nama</span><b>{{ $s->name }}</b></div>
          <div><span>Kategori</span><b>{{ $s->category }}</b></div>
          <div><span>Kondisi</span><b>{{ $conditions[$s->condition] ?? $s->condition }}</b></div>
          <div><span>Asal barang</span><b>{{ $s->source === 'titip' ? 'Dari titipan' : 'Barang luar' }}@if ($s->titip_ref)<small class="mono">{{ $s->titip_ref }}</small>@endif</b></div>
          @if ($s->source === 'titip')<div><span>Izin buka kemasan</span><b>{{ $s->allow_open ? 'Ya' : 'Tidak' }}</b></div>@endif
          <div><span>Kelengkapan</span><b>{{ $s->completeness ?: '-' }}</b></div>
          <div><span>Kekurangan</span><b>{{ $s->flaws ?: '-' }}</b></div>
          @if ($s->offer)<div><span>Penawaran</span><b>Rp {{ number_format((int) $s->offer, 0, ',', '.') }}</b></div>@endif
        </div>
        <p class="sec-t" style="margin-top:14px">Deskripsi dan fungsi</p>
        <p class="js-text">{{ $s->description ?: '-' }}</p>
        @if ($photos->isNotEmpty())
          <p class="sec-t" style="margin-top:14px">Foto ({{ $photos->count() }})</p>
          <div class="js-photos">
            @foreach ($photos as $p)
              <a href="{{ \Illuminate\Support\Facades\Storage::url($p) }}" target="_blank" rel="noopener"><img src="{{ \Illuminate\Support\Facades\Storage::url($p) }}" alt="Foto {{ $loop->iteration }}"></a>
            @endforeach
          </div>
        @endif
      </div>

      <div class="card">
        <div class="card-h"><h3>Riwayat</h3></div>
        @if ($logs->isEmpty())
          <p class="muted">Belum ada riwayat.</p>
        @else
          <ul class="tl">
            @foreach ($logs as $e)
              @php
                $actor = $e['a'] ?? 'Sistem';
                try { $when = \Illuminate\Support\Carbon::parse($e['t'] ?? null)->format('d M Y, H:i'); } catch (\Throwable $ex) { $when = ''; }
              @endphp
              <li class="{{ $actor }}"><i></i><div><b>{{ $e['x'] ?? '' }}</b><small>{{ $actor === 'Kamu' ? 'Pelanggan' : $actor }} &middot; {{ $when }}</small></div></li>
            @endforeach
          </ul>
        @endif
      </div>
    </div>

    <aside>
      <div class="card">
        <div class="card-h"><h3>Penjual</h3></div>
        <div class="kv">
          <div><span>Nama</span><b>{{ optional($s->user)->name ?? '-' }}</b></div>
          <div><span>Email</span><b>{{ optional($s->user)->email ?? '-' }}</b></div>
          <div><span>Telepon</span><b>{{ optional($s->user)->phone ?: '-' }}</b></div>
        </div>
      </div>
      <div class="card">
        <div class="card-h"><h3>Rekening pembayaran</h3></div>
        <div class="kv">
          <div><span>Bank / e-wallet</span><b>{{ $s->bank_name ?: '-' }}</b></div>
          <div><span>Nomor</span><b class="mono">{{ $s->bank_number ?: '-' }}</b></div>
          <div><span>Atas nama</span><b>{{ $s->bank_holder ?: '-' }}</b></div>
        </div>
      </div>
      @if ($s->schedule_date)
        <div class="card">
          <div class="card-h"><h3>Jadwal antar</h3></div>
          <div class="kv">
            <div><span>Tanggal</span><b>{{ $s->schedule_date->locale('id')->translatedFormat('j F Y') }}</b></div>
            <div><span>Jam</span><b>{{ $slotLabel ?: '-' }}</b></div>
          </div>
        </div>
      @endif
    </aside>
  </div>
</div>

{{-- Dialog aksi --}}
<dialog class="dlg" id="dlgReject" @if ($errors->has('reason') && old('aksi') === 'reject') data-reopen @endif>
  <form method="POST" action="{{ route('admin.jual.act', $s->code) }}">
    @csrf
    <input type="hidden" name="aksi" value="reject">
    <div class="dh"><h3>Tolak pengajuan</h3><button class="x" type="button" data-close aria-label="Tutup"><svg class="ic" aria-hidden="true"><use href="#i-x"/></svg></button></div>
    <div class="db">
      <div class="field">
        <label for="rejReason">Alasan penolakan</label>
        <textarea class="input" id="rejReason" name="reason" required maxlength="500" placeholder="Jelaskan alasan agar pelanggan paham">{{ old('aksi') === 'reject' ? old('reason') : '' }}</textarea>
        <span class="help">Alasan ini terlihat oleh pelanggan.</span>
      </div>
    </div>
    <div class="df"><button class="btn btn-ghost" type="button" data-close>Batal</button><button class="btn btn-danger" type="submit">Tolak pengajuan</button></div>
  </form>
</dialog>

<dialog class="dlg" id="dlgNotBuy" @if ($errors->has('reason') && old('aksi') === 'not_buy') data-reopen @endif>
  <form method="POST" action="{{ route('admin.jual.act', $s->code) }}">
    @csrf
    <input type="hidden" name="aksi" value="not_buy">
    <div class="dh"><h3>Barang tidak dibeli</h3><button class="x" type="button" data-close aria-label="Tutup"><svg class="ic" aria-hidden="true"><use href="#i-x"/></svg></button></div>
    <div class="db">
      <div class="field">
        <label for="nbReason">Alasan</label>
        <textarea class="input" id="nbReason" name="reason" required maxlength="500" placeholder="Contoh: kondisi tidak sesuai foto">{{ old('aksi') === 'not_buy' ? old('reason') : '' }}</textarea>
        <span class="help">Alasan ini terlihat oleh pelanggan.</span>
      </div>
    </div>
    <div class="df"><button class="btn btn-ghost" type="button" data-close>Batal</button><button class="btn btn-danger" type="submit">Tutup pengajuan</button></div>
  </form>
</dialog>

<dialog class="dlg" id="dlgOffer" @if ($errors->has('offer')) data-reopen @endif>
  <form method="POST" action="{{ route('admin.jual.act', $s->code) }}">
    @csrf
    <input type="hidden" name="aksi" value="offer">
    <div class="dh"><h3>Buat penawaran harga beli</h3><button class="x" type="button" data-close aria-label="Tutup"><svg class="ic" aria-hidden="true"><use href="#i-x"/></svg></button></div>
    <div class="db">
      <div class="field">
        <label for="offerPrice">Harga beli (Rp)</label>
        <input class="input num" id="offerPrice" name="offer" type="number" min="1" inputmode="numeric" required value="{{ old('offer', $s->offer) }}" placeholder="75000">
        <span class="help">Pelanggan akan menerima atau menolak penawaran ini.</span>
      </div>
    </div>
    <div class="df"><button class="btn btn-ghost" type="button" data-close>Batal</button><button class="btn btn-primary" type="submit">Kirim penawaran</button></div>
  </form>
</dialog>

<dialog class="dlg" id="dlgSchedule" @if ($errors->has('date') || $errors->has('slot')) data-reopen @endif>
  <form method="POST" action="{{ route('admin.jual.act', $s->code) }}">
    @csrf
    <input type="hidden" name="aksi" value="schedule">
    <div class="dh"><h3>Atur jadwal antar</h3><button class="x" type="button" data-close aria-label="Tutup"><svg class="ic" aria-hidden="true"><use href="#i-x"/></svg></button></div>
    <div class="db">
      <div class="fgrid">
        <div class="field">
          <label for="schDate">Tanggal</label>
          <input class="input" id="schDate" name="date" type="date" required min="{{ now()->toDateString() }}" max="{{ now()->addDays(30)->toDateString() }}" value="{{ old('date') }}">
          @error('date')<span class="err-t">{{ $message }}</span>@enderror
        </div>
        <div class="field">
          <label for="schSlot">Jam</label>
          <select class="input" id="schSlot" name="slot" required>
            @foreach ($slots as $key => $label)
              <option value="{{ $key }}" @selected(old('slot') === $key)>{{ $label }}</option>
            @endforeach
          </select>
          @error('slot')<span class="err-t">{{ $message }}</span>@enderror
        </div>
      </div>
      <span class="help">Gudang tutup hari Minggu. Hari Sabtu hanya jam pagi.</span>
    </div>
    <div class="df"><button class="btn btn-ghost" type="button" data-close>Batal</button><button class="btn btn-primary" type="submit">Simpan jadwal</button></div>
  </form>
</dialog>
@endsection
