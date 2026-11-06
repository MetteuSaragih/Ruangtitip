@extends('layouts.rt')

@section('title', 'Jual Barang')

@php
    $source = old('source', 'luar');
@endphp

@section('content')
<div class="mp">
<div class="stack">
    @include('partials.mp-head', [
        'crumbs' => [['Jual Barang', route('preloved.cara-jual')], ['Ajukan barang', null]],
        'title' => 'Ajukan barang',
        'sub' => 'Isi selengkap dan sejujur mungkin supaya seleksi dan pemeriksaan berjalan cepat.',
    ])

    @if (session('error'))
        <div class="alert a-err" role="alert"><svg class="ic" aria-hidden="true"><use href="#i-warn"/></svg><div><b>Pengajuan belum terkirim</b><p>{{ session('error') }}</p></div></div>
    @elseif ($errors->any())
        <div class="alert a-err" role="alert" id="formErr" tabindex="-1">
            <svg class="ic" aria-hidden="true"><use href="#i-warn"/></svg>
            <div><b>Pengajuan belum terkirim</b><p>Periksa {{ $errors->count() }} isian yang ditandai di bawah, lalu kirim lagi.</p>
                <ul style="margin:6px 0 0 18px;padding:0">@foreach ($errors->all() as $msg)<li>{{ $msg }}</li>@endforeach</ul></div>
        </div>
    @endif

    <form method="POST" action="{{ route('sell.store') }}" enctype="multipart/form-data" novalidate>
        @csrf
        <div class="two">
            <div class="stack">
                <section class="card stack">
                    <h3>Asal barang</h3>
                    <div class="picks">
                        <label class="pick"><input type="radio" name="source" value="luar" {{ $source === 'luar' ? 'checked' : '' }}><span><b>Barang yang akan kuantar</b><small>Kalau lolos seleksi, kamu memilih jadwal antar ke gudang.</small></span></label>
                        <label class="pick"><input type="radio" name="source" value="titip" {{ $source === 'titip' ? 'checked' : '' }}><span><b>Barang yang sedang kutitipkan</b><small>Barang sudah di gudang. Kemasan perlu dibuka untuk diperiksa.</small></span></label>
                    </div>
                    <div id="titipBox" class="stack tight" {{ $source === 'titip' ? '' : 'hidden' }}>
                        <div class="field">
                            <label for="sTitip">Pilih barang titipan</label>
                            <select class="input" id="sTitip" name="titip_ref" @error('titip_ref') aria-invalid="true" @enderror>
                                <option value="">Pilih kode barang</option>
                                @foreach ($titips as $t)
                                    <option value="{{ $t['id'] }}" {{ old('titip_ref') === $t['id'] ? 'selected' : '' }}>{{ $t['id'] }}, {{ $t['label'] }}</option>
                                @endforeach
                            </select>
                            @error('titip_ref')<p class="msg">{{ $message }}</p>@enderror
                        </div>
                        <label class="pick"><input type="checkbox" name="allow_open" value="1" {{ old('allow_open') ? 'checked' : '' }}><span>Saya mengizinkan petugas membuka kemasan barang titipan ini untuk pemeriksaan. Kalau pembelian tidak terjadi, status titipan tetap berlaku.</span></label>
                        @error('allow_open')<p class="msg">{{ $message }}</p>@enderror
                        <div class="alert a-info"><svg class="ic" aria-hidden="true"><use href="#i-info"/></svg><div><b>Tagihan titipan diselesaikan dulu</b><p>Kalau harga disepakati, sisa tagihan penitipan barang ini diselesaikan sebelum barang menjadi stok preloved.</p></div></div>
                    </div>
                    @error('source')<p class="msg">{{ $message }}</p>@enderror
                </section>

                <section class="card stack">
                    <h3>Detail barang</h3>
                    <div class="field">
                        <label for="sName">Nama barang</label>
                        <input class="input" id="sName" name="name" value="{{ old('name') }}" placeholder="Contoh: Kipas angin meja 9 inci" @error('name') aria-invalid="true" @enderror>
                        @error('name')<p class="msg">{{ $message }}</p>@enderror
                    </div>
                    <div class="grid2">
                        <div class="field">
                            <label for="sCat">Kategori</label>
                            <select class="input" id="sCat" name="category" @error('category') aria-invalid="true" @enderror>
                                <option value="">Pilih kategori</option>
                                @foreach ($categories as $c)
                                    <option value="{{ $c }}" {{ old('category') === $c ? 'selected' : '' }}>{{ $c }}</option>
                                @endforeach
                            </select>
                            @error('category')<p class="msg">{{ $message }}</p>@enderror
                        </div>
                        <div class="field">
                            <label for="sCond">Kondisi</label>
                            <select class="input" id="sCond" name="condition" @error('condition') aria-invalid="true" @enderror>
                                <option value="">Pilih kondisi</option>
                                @foreach ($conditions as $k => $l)
                                    <option value="{{ $k }}" {{ old('condition') === $k ? 'selected' : '' }}>{{ $l }}</option>
                                @endforeach
                            </select>
                            @error('condition')<p class="msg">{{ $message }}</p>@enderror
                        </div>
                    </div>
                    <div class="field">
                        <label for="sDesc">Deskripsi dan fungsi</label>
                        <textarea class="input" id="sDesc" name="description" rows="3" placeholder="Lama pemakaian, fungsi yang masih normal, ukuran" @error('description') aria-invalid="true" @enderror>{{ old('description') }}</textarea>
                        @error('description')<p class="msg">{{ $message }}</p>@enderror
                    </div>
                    <div class="field">
                        <label for="sKel">Kelengkapan</label>
                        <input class="input" id="sKel" name="completeness" value="{{ old('completeness') }}" placeholder="Contoh: unit, kabel, dus" @error('completeness') aria-invalid="true" @enderror>
                        @error('completeness')<p class="msg">{{ $message }}</p>@enderror
                    </div>
                    <div class="field">
                        <label for="sMin">Kekurangan</label>
                        <input class="input" id="sMin" name="flaws" value="{{ old('flaws') }}" placeholder="Tulis 'Tidak ada' kalau memang tidak ada" @error('flaws') aria-invalid="true" @enderror>
                        <p class="help">Kekurangan yang tidak disebutkan bisa membuat barang ditolak saat pemeriksaan.</p>
                        @error('flaws')<p class="msg">{{ $message }}</p>@enderror
                    </div>
                    <div class="field">
                        <span class="lab">Foto barang (2 sampai 8)</span>
                        <label class="drop"><svg class="ic" aria-hidden="true"><use href="#i-cam"/></svg><span id="dropLabel">Pilih foto</span><input type="file" name="photos[]" accept="image/*" multiple id="photoInput"></label>
                        <div class="thumbs" id="thumbs"></div>
                        <p class="help">Foto bagian depan, belakang, dan bagian yang rusak kalau ada. Format JPG, PNG, atau WEBP, maksimal 4 MB per foto.</p>
                        @error('photos')<p class="msg">{{ $message }}</p>@enderror
                        @error('photos.*')<p class="msg">{{ $message }}</p>@enderror
                    </div>
                </section>

                <section class="card stack">
                    <h3>Rekening penerimaan</h3>
                    <p class="help">Dipakai admin untuk membayar 100% harga beli setelah harga disepakati.</p>
                    <div class="grid3">
                        <div class="field">
                            <label for="sBank">Bank atau e-wallet</label>
                            <input class="input" id="sBank" name="bank_name" value="{{ old('bank_name', $bank?->bank) }}" placeholder="BRI" @error('bank_name') aria-invalid="true" @enderror>
                            @error('bank_name')<p class="msg">{{ $message }}</p>@enderror
                        </div>
                        <div class="field">
                            <label for="sNo">Nomor rekening</label>
                            <input class="input" id="sNo" name="bank_number" inputmode="numeric" value="{{ old('bank_number', $bank?->account_number) }}" @error('bank_number') aria-invalid="true" @enderror>
                            @error('bank_number')<p class="msg">{{ $message }}</p>@enderror
                        </div>
                        <div class="field">
                            <label for="sHolder">Atas nama</label>
                            <input class="input" id="sHolder" name="bank_holder" value="{{ old('bank_holder', $holder) }}" @error('bank_holder') aria-invalid="true" @enderror>
                            @error('bank_holder')<p class="msg">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </section>

                <div class="nav-btns">
                    <a class="btn btn-ghost" href="{{ route('preloved.cara-jual') }}">Batal</a>
                    <button type="submit" class="btn btn-sun">Kirim pengajuan</button>
                </div>
            </div>

            <aside class="aside">
                <section class="card">
                    <h3>Yang perlu kamu tahu</h3>
                    <ul class="bul">
                        <li>Admin menyeleksi kelayakan barang dan kapasitas gudang.</li>
                        <li>Persetujuan awal belum kepastian pembelian.</li>
                        <li>Barang diperiksa bersamamu: kondisi, kelengkapan, dan fungsi dasar. Barang yang perlu perbaikan tidak dibeli.</li>
                        <li>Harga beli ditentukan dari estimasi harga jual kembali, kondisi, dan biaya penanganan.</li>
                        <li>Kalau kamu menolak penawaran, barang tetap milikmu.</li>
                    </ul>
                </section>
            </aside>
        </div>
    </form>
</div>
</div>
@endsection

@push('scripts')
<script>
(function () {
    var err = document.getElementById('formErr');
    if (err) err.scrollIntoView({ block: 'center' });
    var box = document.getElementById('titipBox');
    document.querySelectorAll('input[name="source"]').forEach(function (r) {
        r.addEventListener('change', function () { box.hidden = r.value !== 'titip' || !r.checked; });
    });
    var input = document.getElementById('photoInput'), thumbs = document.getElementById('thumbs'), lab = document.getElementById('dropLabel');
    input.addEventListener('change', function () {
        thumbs.innerHTML = '';
        var files = [].slice.call(input.files).slice(0, 8);
        lab.textContent = files.length ? files.length + ' foto dipilih' : 'Pilih foto';
        files.forEach(function (f, k) {
            var s = document.createElement('span'), im = document.createElement('img');
            im.src = URL.createObjectURL(f); im.alt = 'Foto ' + (k + 1);
            s.appendChild(im); thumbs.appendChild(s);
        });
    });
})();
</script>
@endpush
