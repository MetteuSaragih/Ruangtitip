@extends('errors.layout')

@section('title', 'Server lama menjawab')
@section('badge', 'Error 504')
@section('pose', 'pusing')
@section('code_num', '504')
@section('report', '1')

@section('heading')
    Servernya <em>lama menjawab</em>
@endsection

@section('lead', 'Permintaanmu memakan waktu lebih lama dari biasanya. Tunggu sebentar lalu coba lagi. Kalau kamu baru saja membayar atau mengirim formulir, cek dulu di Pesanan Saya sebelum mengulang.')

@section('actions')
    <button class="btn btn-primary" id="reloadPage" type="button">Coba lagi</button>
    <a class="btn btn-ghost" href="{{ url('/') }}">Ke beranda</a>
@endsection
