{{--
    Layout untuk langkah-langkah checkout Toko Packing & Toko Preloved.
    Disamakan dengan pola flat Ruang Titip — tanpa kartu pembungkus, tombol
    "Kembali" dikelola masing-masing halaman (di bawah, sejajar tombol lanjut).

    View anak mengisi:
      @section('title')            judul tab (opsional)
      @section('checkout-content') isi langkah
--}}
@extends('layouts.dashboard')

@section('content')
<div class="max-w-xl mx-auto pt-6 pb-8">
    @yield('checkout-content')
</div>
@endsection
