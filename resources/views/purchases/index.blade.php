@extends('layouts.rt')

@section('title', 'Pembelian Saya')

@php
    if (! function_exists('rp')) {
        function rp($n) { return 'Rp' . number_format($n, 0, ',', '.'); }
    }
@endphp

@section('content')
<div class="mp">
<div class="stack">
    @include('partials.mp-head', [
        'crumbs' => [['Pembelian Saya', null]],
        'title' => 'Pembelian Saya',
        'sub' => 'Pantau pembayaran, penyerahan, pengiriman, dan masa komplain.',
        'acts' => '<a class="btn btn-ghost" href="' . e(route('preloved.index')) . '">Belanja lagi</a>',
    ])

    @if ($orders->isEmpty())
        <section class="card state"><svg viewBox="0 0 44 40" width="90" height="82" aria-hidden="true"><use href="#box"/></svg><h3>Belum ada pembelian</h3><p>Barang yang kamu beli dari katalog akan muncul di sini.</p><div class="acts"><a class="btn btn-sun" href="{{ route('preloved.index') }}">Lihat katalog</a></div></section>
    @else
        <div class="olist">
            @foreach ($orders as $o)
                <article class="card oc">
                    <div class="pic" style="--tone:var(--alt)">
                        @if (! empty($o['first']['image']))
                            <img src="{{ \Illuminate\Support\Facades\Storage::url($o['first']['image']) }}" alt="" style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover;border-radius:14px">
                        @else
                            <svg viewBox="0 0 24 24" aria-hidden="true"><use href="#n-tag"/></svg>
                        @endif
                    </div>
                    <div>
                        <div class="top"><span class="code">{{ $o['number'] }}</span><span class="badge b-{{ $o['status_tone'] }}"><i></i>{{ $o['status_label'] }}</span></div>
                        <p><b>{{ $o['first']['name'] ?? 'Barang preloved' }}@if (count($o['items']) > 1) dan {{ count($o['items']) - 1 }} barang lain @endif</b></p>
                        <p class="meta">{{ $o['method'] }}, total {{ rp($o['total']) }}</p>
                    </div>
                    <div class="oc-acts">
                        @if ($o['key'] === 'bayar' && $o['pay_url'])<a class="btn btn-primary sm" href="{{ $o['pay_url'] }}">Bayar</a>@endif
                        <a class="btn btn-ghost sm" href="{{ route('purchases.show', $o['id']) }}">Lihat detail</a>
                    </div>
                </article>
            @endforeach
        </div>
    @endif
</div>
</div>
@endsection
