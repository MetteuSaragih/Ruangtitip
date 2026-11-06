@extends('errors.layout')

@section('title', 'Ada gangguan')
@section('badge', 'Error 500')
@section('pose', 'pusing')
@section('code_num', '500')
@section('report', '1')

@section('heading')
    Waduh, ada yang <em>tidak beres</em>
@endsection

@section('lead', 'Ada gangguan di sisi kami, jadi halaman ini belum bisa dibuka. Tenang, itu bukan salahmu, dan barang titipanmu tetap aman di gudang.')

@section('actions')
    <button class="btn btn-primary" id="reloadPage" type="button">Coba lagi</button>
    <a class="btn btn-ghost" href="{{ url('/') }}">Ke beranda</a>
@endsection

@section('extra')
    <div class="box">
        <p><b>Kalau masih terjadi:</b> tunggu beberapa menit lalu muat ulang. Bila tetap sama, kirim kode laporan di bawah ke tim kami supaya cepat kami cek.</p>
    </div>
@endsection
