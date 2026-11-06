@extends('errors.layout')

@section('title', 'Halaman tidak ditemukan')
@section('badge', 'Error 404')
@section('pose', 'mencari')

@section('heading')
    Halaman ini <em>tidak ketemu</em>
@endsection

@section('lead', 'Ruru sudah mencari ke semua rak, tapi halaman ini tidak ada. Mungkin alamatnya salah ketik, atau halamannya sudah dipindah.')

@section('actions')
    <a class="btn btn-primary" href="{{ url('/') }}">Ke beranda</a>
    <button class="btn btn-ghost" id="goBack" type="button">Kembali</button>
@endsection

@section('extra')
    <p class="links">
        <span>Atau coba buka:</span>
        <a href="{{ url('dashboard/ruang-titip') }}">Ruang Titip</a>
        <a href="{{ url('toko-preloved') }}">Toko Preloved</a>
        <a href="{{ url('login') }}">Masuk</a>
    </p>
@endsection
