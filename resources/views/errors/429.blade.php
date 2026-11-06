@extends('errors.layout')

@section('title', 'Terlalu banyak percobaan')
@section('badge', 'Error 429')
@section('pose', 'halo')

@php
    // Waktu tunggu dari server (detik), bila ada.
    $retryAfter = 0;
    if (isset($exception) && method_exists($exception, 'getHeaders')) {
        $retryAfter = (int) ($exception->getHeaders()['Retry-After'] ?? 0);
    }
@endphp

@section('heading')
    Pelan-pelan dulu <em>ya</em>
@endsection

@section('lead', 'Ada terlalu banyak percobaan dalam waktu singkat. Tarik napas sebentar, lalu coba lagi.')

@section('actions')
    <button class="btn btn-primary" id="reloadPage" type="button">Coba lagi</button>
    <a class="btn btn-ghost" href="{{ url('/') }}">Ke beranda</a>
@endsection

@section('extra')
    @if ($retryAfter > 0)
        <div class="box"><p><b>Coba lagi sekitar {{ $retryAfter >= 90 ? ceil($retryAfter / 60) . ' menit' : $retryAfter . ' detik' }}.</b></p></div>
    @endif
@endsection
