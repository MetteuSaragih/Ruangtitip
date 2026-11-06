@extends('errors.layout')

@section('title', 'Sesi berakhir')
@section('badge', 'Error 419')
@section('pose', 'tunjuk')

@section('heading')
    Halamannya <em>terlalu lama</em> terbuka
@endsection

@section('lead', 'Demi keamanan, sesi halaman ini sudah berakhir. Muat ulang halamannya lalu ulangi langkah terakhirmu. Isian yang belum terkirim mungkin perlu diisi lagi.')

@section('actions')
    <button class="btn btn-primary" id="reloadPage" type="button">Muat ulang halaman</button>
    <a class="btn btn-ghost" href="{{ url('/') }}">Ke beranda</a>
@endsection
