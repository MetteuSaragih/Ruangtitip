@extends('layouts.rt')

{{--
    Kerangka halaman Akun saya: menu akun di kiri, isi di kanan.
    Halaman anak mengisi: title, key (menu aktif), sub, dan body.
--}}
@php
    $accMenu = [
        ['profil', 'Profil', 'i-user', 'akun.profil'],
        ['alamat', 'Alamat', 'i-pin', 'akun.alamat'],
        ['rekening', 'Rekening', 'i-bank', 'akun.rekening'],
        ['keamanan', 'Keamanan', 'i-lock', 'akun.keamanan'],
        ['notifikasi', 'Notifikasi', 'i-bell', 'akun.notifikasi'],
        ['bantuan', 'Bantuan', 'i-help', 'akun.bantuan'],
    ];
    $accKey = trim($__env->yieldContent('key')) ?: 'profil';
@endphp

@section('content')
<div class="ma">
    <div class="acc">
        <nav class="card acc-nav" aria-label="Menu akun">
            <a href="{{ route('profile.index') }}" @if ($accKey === 'akun') aria-current="page" @endif><svg class="ic" aria-hidden="true"><use href="#i-user"/></svg>Ringkasan</a>
            @foreach ($accMenu as [$k, $label, $icon, $route])
                <a href="{{ route($route) }}" @if ($accKey === $k) aria-current="page" @endif><svg class="ic" aria-hidden="true"><use href="#{{ $icon }}"/></svg>{{ $label }}</a>
            @endforeach
            <hr>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="out" type="submit"><svg class="ic" aria-hidden="true"><use href="#i-out"/></svg>Keluar</button>
            </form>
        </nav>

        <div class="stack">
            <nav class="crumbs" aria-label="Breadcrumb">
                <a href="{{ route('profile.index') }}">Akun saya</a><span aria-hidden="true">/</span><span aria-current="page">@yield('title')</span>
            </nav>
            <div class="ph">
                <div>
                    <a class="btn btn-link back" href="{{ route('profile.index') }}"><svg class="ic" aria-hidden="true"><use href="#i-back"/></svg>Akun saya</a>
                    <h2 class="pt" tabindex="-1">@yield('title')</h2>
                    <p class="ps">@yield('sub')</p>
                </div>
            </div>

            @if (session('error'))
                <div class="alert a-err" role="alert"><svg class="ic" aria-hidden="true"><use href="#i-warn"/></svg><div><b>{{ session('error') }}</b></div></div>
            @endif

            @yield('body')
        </div>
    </div>
</div>
@endsection
