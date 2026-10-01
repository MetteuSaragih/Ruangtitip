@extends('layouts.ruang-titip')
@section('title', 'Ruang Titip')

@php function rp($n){ return 'Rp'.number_format($n,0,',','.'); } @endphp

@section('content')
<main class="wrap">
  <div class="page-head">
    <div>
      <h1>Ruang Titip</h1>
      <p>Pilih gudang terdekat, lalu atur barang dan jadwal titipmu.</p>
    </div>
    <div class="how" aria-label="Alur titip">
      <span><b>1</b>Pilih gudang</span>
      <span><b>2</b>Isi barang &amp; tanggal</span>
      <span><b>3</b>Bayar</span>
    </div>
  </div>

  @if ($storages->isNotEmpty())
  <div class="filters" role="search">
    <label class="search">
      <span class="sr">Cari gudang</span>
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5"/></svg>
      <input type="search" id="q" placeholder="Cari nama gudang atau kecamatan">
    </label>
    <label><span class="sr">Urutkan</span>
      <select id="sort">
        <option value="near">Terbaru</option>
        <option value="cheap">Harga termurah</option>
        <option value="space">Paling lega</option>
      </select>
    </label>
  </div>

  <div class="grid" id="grid">
    @foreach ($storages as $s)
      @php
        $status = $s->capacity_pct >= 100 ? 'full' : ($s->capacity_pct >= 80 ? 'few' : 'open');
        $statusLabel = ['open' => 'Tersedia', 'few' => 'Hampir penuh', 'full' => 'Penuh'][$status];
        $statusClass = ['open' => 'st-open', 'few' => 'st-few', 'full' => 'st-full'][$status];
      @endphp
      <a class="wh" href="{{ route('ruang-titip.detail', $s) }}"
         data-name="{{ strtolower($s->name.' '.$s->location) }}" data-price="{{ $s->min_price }}" data-fill="{{ $s->capacity_pct }}">
        <div class="photo">
          <span class="status {{ $statusClass }}">{{ $statusLabel }}</span>
          @if ($s->primary_photo)
            <img src="{{ asset('storage/'.$s->primary_photo) }}" alt="{{ $s->name }}">
          @else
            <span class="ph"><svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="#6B6557" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M3 10 12 4l9 6v10H3z"/><path d="M8 20v-6h8v6M8 17h8"/></svg>[Foto gudang]</span>
          @endif
        </div>
        <div class="body">
          <div>
            <h2>{{ $s->name }}</h2>
            <p class="loc"><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 21s-7-6.2-7-11.5a7 7 0 0 1 14 0C19 14.8 12 21 12 21z"/><circle cx="12" cy="9.5" r="2.5"/></svg>{{ $s->address }}</p>
          </div>
          <div>
            <div class="cap-row"><span>Kapasitas terisi</span><b>{{ $s->capacity_pct }}%</b></div>
            <div class="bar" role="img" aria-label="Kapasitas terisi {{ $s->capacity_pct }} persen"><i class="{{ $s->capacity_pct >= 80 ? 'warn' : '' }}" style="width:{{ $s->capacity_pct }}%"></i></div>
          </div>
          @if (count($s->facilities ?? []))
            <ul class="feat" aria-label="Fasilitas">
              @foreach ($s->facilities as $tag)<li>{{ $tag }}</li>@endforeach
            </ul>
          @endif
          <div class="foot">
            <p class="price"><small>Mulai dari</small><strong>{{ rp($s->min_price) }}</strong><span> /bulan</span></p>
            @if ($status === 'full')
              <span class="btn btn-primary" aria-disabled="true">Penuh</span>
            @else
              <span class="btn btn-primary">Pilih gudang</span>
            @endif
          </div>
        </div>
      </a>
    @endforeach
  </div>

  <p id="no-result" style="display:none;padding:40px 0;text-align:center;color:var(--muted)">Gudang nggak ditemukan. Coba kata kunci lain.</p>
  @else
  <div class="wh" style="cursor:default;padding:48px;text-align:center;">
    <p style="font-weight:700;font-size:18px;margin-bottom:6px">Belum ada gudang tersedia</p>
    <p style="color:var(--muted)">Ruru lagi siapin gudangnya. Kami kabari lewat notifikasi begitu sudah buka.</p>
  </div>
  @endif

  <aside class="help">
    <img src="{{ asset('assets/ruru/ruru-tunjuk.webp') }}" alt="" width="84" height="79">
    <div>
      <strong>Bingung pilih ukuran kardus?</strong>
      <p>Chat tim Ruru, kirim foto barangmu, nanti kami bantu hitung butuh berapa kardus.</p>
    </div>
    <a class="btn btn-outline" href="https://wa.me/6285121091134" target="_blank" rel="noopener">Tanya via WhatsApp</a>
  </aside>
</main>

@push('scripts')
<script>
(function(){
  var grid=document.getElementById('grid'), q=document.getElementById('q'), sort=document.getElementById('sort'), none=document.getElementById('no-result');
  if (!grid || !q) return;
  function apply(){
    var term=q.value.trim().toLowerCase(), cards=[].slice.call(grid.children), shown=0;
    cards.forEach(function(c){var ok=c.dataset.name.indexOf(term)!==-1;c.style.display=ok?'':'none';if(ok)shown++;});
    none.style.display=shown?'none':'block';
    var s=sort.value;
    if(s!=='near'){cards.sort(function(a,b){return s==='cheap'?a.dataset.price-b.dataset.price:a.dataset.fill-b.dataset.fill;}).forEach(function(c){grid.appendChild(c);});}
  }
  q.addEventListener('input',apply);sort.addEventListener('change',apply);
})();
</script>
@endpush
@endsection
