{{--
    Layout untuk langkah-langkah checkout Toko Packing (Tier 2).
    Tampil sebagai kartu sempit di tengah dengan background lebih gelap,
    meniru desain TokoPackingPage.tsx.

    View anak mengisi:
      @section('title')            judul tab (opsional)
      @section('back-url')         URL tombol "Kembali" (opsional)
      @section('checkout-content') isi langkah
--}}
@extends('layouts.dashboard')

@section('content')
@php $backUrl = trim($__env->yieldContent('back-url')); @endphp
<div class="py-4 pb-28 max-w-xl mx-auto">

    @if ($backUrl !== '')
        <a href="{{ $backUrl }}"
           class="flex items-center gap-1.5 text-sm mb-3 transition-colors hover:text-violet-300"
           style="color:rgba(255,255,255,0.4);">
            <x-lucide-chevron-left class="w-4 h-4" /> Kembali
        </a>
    @endif

    <div class="rounded-3xl oaverflow-hidden"
         style="background:#0f0720;box-shadow:0 12px 48px rgba(0,0,0,0.65),0 0 0 1px rgba(139,92,246,0.14);">
        <div class="px-6 py-6">
            @yield('checkout-content')
        </div>
    </div>
</div>
@endsection
