@extends('layouts.app')

@section('title', 'RUTIP — Titip Barangmu, Simpan Uangmu')

@section('content')
<div class="min-h-screen overflow-x-hidden" style="background: #0c0618;">
    @include('landing.partials.navbar')
    @include('landing.partials.hero')
    @include('landing.partials.problem')
    @include('landing.partials.solution')
    @include('landing.partials.how-it-works')
    @include('landing.partials.trust')
    @include('landing.partials.testimoni')
    @include('landing.partials.faq')
    @include('landing.partials.tentang-kami')
    @include('landing.partials.footer')
</div>
@endsection

@push('scripts')
    @vite('resources/js/app.js')
@endpush
