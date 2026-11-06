@extends('errors.layout')

@section('title', 'Akses ditolak')
@section('badge', 'Error 403')
@section('pose', 'perisai')

@section('heading')
    Halaman ini <em>dijaga Ruru</em>
@endsection

@section('lead', 'Kamu belum punya izin untuk membuka halaman ini. Kalau kamu merasa seharusnya bisa masuk, coba masuk dengan akun yang benar atau hubungi kami.')

@section('actions')
    <a class="btn btn-primary" href="{{ url('/') }}">Ke beranda</a>
    <a class="btn btn-ghost" href="{{ url('login') }}">Masuk dengan akun lain</a>
@endsection
