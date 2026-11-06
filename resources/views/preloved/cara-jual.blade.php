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

    <section class="card sh-card">
        <div class="sh-head">
            <h3>Cara kerjanya</h3>
            <p class="help">Dari mengajukan sampai dibayar, semuanya lewat website.</p>
        </div>

        <ol class="sh-steps">
            @foreach ([
                ['i-cam', 'Ajukan barang', 'Isi data dan unggah foto serta kondisi barangnya.'],
                ['i-check', 'Seleksi admin', 'Admin menyeleksi kelayakan barang dan kapasitas gudang.'],
                ['i-pin', 'Antar dan periksa', 'Antar barang ke gudang, lalu diperiksa bersamamu.'],
                ['i-bell', 'Penawaran harga', 'Terima atau tolak harga beli yang kami tawarkan.'],
                ['i-bank', 'Dibayar penuh', 'Kamu dibayar penuh, dan barang jadi milik Ruang Titip.'],
            ] as $i => [$icon, $title, $text])
                <li>
                    <span class="sh-n" aria-hidden="true">{{ $i + 1 }}</span>
                    <span class="sh-ic" aria-hidden="true"><svg class="ic"><use href="#{{ $icon }}"/></svg></span>
                    <b>{{ $title }}</b>
                    <p>{{ $text }}</p>
                </li>
            @endforeach
        </ol>

        <div class="alert a-info">
            <svg class="ic" aria-hidden="true"><use href="#i-info"/></svg>
            <div><b>Perlu diketahui</b><p>Persetujuan awal belum berarti barang pasti dibeli. Setelah pembelian selesai, barang tidak bisa ditarik kembali.</p></div>
        </div>
    </section>

    <section class="stack tight">
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
                                <img src="{{ asset('storage/'.$photo) }}" alt="" style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover;border-radius:14px">
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
