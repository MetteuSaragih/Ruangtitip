@extends('layouts.rt')

@section('title', 'Jual Barang')

@php
    if (! function_exists('rp')) {
        function rp($n) { return 'Rp' . number_format($n, 0, ',', '.'); }
    }
    $statuses = \App\Models\PrelovedSubmission::STATUS;
@endphp

@section('content')
<div class="mp">
<div class="stack">
    @include('partials.mp-head', [
        'crumbs' => [['Jual Barang', null]],
        'title' => 'Jual barang ke Ruang Titip',
        'sub' => 'Kami membeli barangmu dan membayar penuh setelah pemeriksaan dan kesepakatan harga.',
        'acts' => '<a class="btn btn-sun" href="' . e(route('sell.create')) . '">Ajukan barang</a>',
    ])

    <section class="card">
        <h3>Cara kerjanya</h3>
        <ol class="steps4">
            <li>Ajukan barang dengan foto dan kondisinya</li>
            <li>Admin menyeleksi kelayakan dan kapasitas</li>
            <li>Antar barang, lalu diperiksa bersamamu</li>
            <li>Terima atau tolak penawaran harga</li>
            <li>Kamu dibayar penuh, barang jadi milik Ruang Titip</li>
        </ol>
        <p class="help mt">Persetujuan awal belum berarti barang pasti dibeli. Setelah pembelian selesai, barang tidak bisa ditarik kembali.</p>
    </section>

    <section class="stack" style="gap:12px">
        <h3>Pengajuanmu</h3>
        @if ($submissions->isEmpty())
            <section class="card state"><svg viewBox="0 0 44 40" width="90" height="82" aria-hidden="true"><use href="#box"/></svg><h3>Belum ada pengajuan</h3><p>Barang yang kamu ajukan akan muncul di sini beserta statusnya.</p></section>
        @else
            <div class="olist">
                @foreach ($submissions as $s)
                    @php $photo = collect($s->photos ?? [])->first(); @endphp
                    <article class="card oc">
                        <div class="pic" style="--tone:var(--alt)">
                            @if ($photo)
                                <img src="{{ \Illuminate\Support\Facades\Storage::url($photo) }}" alt="" style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover;border-radius:14px">
                            @else
                                <svg viewBox="0 0 24 24" aria-hidden="true"><use href="#n-tag"/></svg>
                            @endif
                        </div>
                        <div>
                            <div class="top"><span class="code">{{ $s->code }}</span><span class="badge b-{{ $statuses[$s->status][1] ?? 'mute' }}"><i></i>{{ $statuses[$s->status][0] ?? $s->status }}</span></div>
                            <p><b>{{ $s->name }}</b></p>
                            <p class="meta">{{ $s->source === 'titip' ? 'Dari titipan ' . $s->titip_ref : 'Diantar sendiri' }}@if ($s->offer && in_array($s->status, ['tawar', 'titip', 'sepakat', 'dibeli'], true)), harga beli {{ rp($s->offer) }}@endif</p>
                        </div>
                        <div class="oc-acts"><a class="btn {{ $s->needsAction() ? 'btn-primary' : 'btn-ghost' }} sm" href="{{ route('sell.show', $s->code) }}">{{ $s->needsAction() ? 'Tindak lanjuti' : 'Lihat' }}</a></div>
                    </article>
                @endforeach
            </div>
        @endif
    </section>
</div>
</div>
@endsection
