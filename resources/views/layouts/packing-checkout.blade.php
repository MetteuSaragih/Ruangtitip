{{--
    Layout langkah-langkah checkout Toko Packing.
    View anak mengisi @section('checkout-content'); judul tab lewat @section('title').
--}}
@extends('layouts.rt')

@section('content')
<div class="wrap" style="max-width:640px">
    @yield('checkout-content')
</div>
@endsection
