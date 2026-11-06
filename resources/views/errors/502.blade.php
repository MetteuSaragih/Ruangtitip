@extends('errors.layout')

@section('title', 'Server sedang sibuk')
@section('badge', 'Error 502')
@section('pose', 'pusing')
@section('code_num', '502')
@section('report', '1')

@section('heading')
    Server kami sedang <em>kewalahan</em>
@endsection

@section('lead', 'Layanan kami belum merespons dengan benar. Biasanya ini hanya sebentar. Tunggu satu atau dua menit, lalu muat ulang halamannya.')

@section('actions')
    <button class="btn btn-primary" id="reloadPage" type="button">Muat ulang</button>
    <a class="btn btn-ghost" href="{{ url('/') }}">Ke beranda</a>
@endsection
