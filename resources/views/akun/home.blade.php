@extends('layouts.rt')

@section('title', 'Akun saya')

@php
    $menu = [
        ['akun.profil', 'Profil', 'i-user', 'Nama, foto, kontak, dan kampus'],
        ['akun.alamat', 'Alamat', 'i-pin', $mainAddress ? $mainAddress->label . ', ' . ($mainAddress->area_name ?: 'alamat utama') : 'Alamat pengiriman Toko Preloved'],
        ['akun.rekening', 'Rekening', 'i-bank', $bankCount ? $bankCount . ' rekening tersimpan' : 'Belum ada rekening'],
        ['akun.keamanan', 'Keamanan', 'i-lock', 'Cara masuk dan perangkat yang masuk'],
        ['akun.notifikasi', 'Notifikasi', 'i-bell', 'Kabar yang ingin kamu terima'],
        ['akun.bantuan', 'Bantuan', 'i-help', 'Pertanyaan umum dan kontak admin'],
    ];
    $accMenu = $menu;
@endphp

@section('content')
<div class="ma">
    <div class="acc">
        <nav class="card acc-nav" aria-label="Menu akun">
            <a href="{{ route('profile.index') }}" aria-current="page"><svg class="ic" aria-hidden="true"><use href="#i-user"/></svg>Ringkasan</a>
            @foreach ($menu as [$route, $label, $icon])
                <a href="{{ route($route) }}"><svg class="ic" aria-hidden="true"><use href="#{{ $icon }}"/></svg>{{ $label }}</a>
            @endforeach
            <hr>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="out" type="submit"><svg class="ic" aria-hidden="true"><use href="#i-out"/></svg>Keluar</button>
            </form>
        </nav>

        <div class="stack">
            <section class="card hero">
                <span class="av big-av">@if ($user->avatar)<img src="{{ Storage::url($user->avatar) }}" alt="">@else{{ $user->initials }}@endif</span>
                <div style="flex:1;min-width:220px">
                    <h2 class="pt" tabindex="-1">{{ $user->name }}</h2>
                    <div class="meta">
                        <span><svg class="ic" aria-hidden="true"><use href="#i-mail"/></svg>{{ $user->email }}</span>
                        <span><svg class="ic" aria-hidden="true"><use href="#i-phone"/></svg>{{ $user->phone ?: 'WhatsApp belum diisi' }}</span>
                        @if ($user->campus)
                            <span><svg class="ic" aria-hidden="true"><use href="#i-school"/></svg>{{ $user->campus }}{{ $user->cohort_year ? ', ' . $user->cohort_year : '' }}</span>
                        @endif
                    </div>
                    <p class="help mt">Bergabung {{ $user->created_at?->locale('id')->translatedFormat('F Y') }}</p>
                </div>
                <a class="btn btn-ghost" href="{{ route('akun.profil') }}">Ubah profil</a>
            </section>

            @if ($done < count($steps))
                <section class="card">
                    <div class="card-h"><div><h3>Lengkapi profilmu</h3><p class="help">{{ $done }} dari {{ count($steps) }} selesai. Nomor WhatsApp dipakai tim Ruru untuk menghubungimu soal pesanan.</p></div></div>
                    <div class="prog" aria-hidden="true"><i style="width:{{ ($done / count($steps)) * 100 }}%"></i></div>
                    <ul class="todo mt">
                        @foreach ($steps as [$text, $ok, $href])
                            <li class="{{ $ok ? 'done' : '' }}"><span class="box">@if ($ok)<svg class="ic" aria-hidden="true"><use href="#i-check"/></svg>@endif</span>{{ $text }}@unless ($ok)<a href="{{ $href }}">Lengkapi</a>@endunless</li>
                        @endforeach
                    </ul>
                </section>
            @endif

            <div class="stats">
                <a class="card stat" href="{{ route('pesanan.index') }}"><small>Titipan aktif</small><b>{{ $stats['titip'] }}</b><span>Lihat Pesanan Saya</span></a>
                <a class="card stat" href="{{ route('purchases.index') }}"><small>Pembelian preloved</small><b>{{ $stats['beli'] }}</b><span>Lihat Pembelian</span></a>
                <a class="card stat" href="{{ route('preloved.cara-jual') }}"><small>Pengajuan jual</small><b>{{ $stats['jual'] }}</b><span>Lihat pengajuan</span></a>
            </div>

            <section class="stack" style="gap:12px">
                <h3>Pengaturan</h3>
                <div class="menu">
                    @foreach ($menu as [$route, $label, $icon, $desc])
                        <a href="{{ route($route) }}"><span class="mi"><svg class="ic" aria-hidden="true"><use href="#{{ $icon }}"/></svg></span><span><b>{{ $label }}</b><small>{{ $desc }}</small></span><svg class="ic ch" aria-hidden="true"><use href="#i-chev"/></svg></a>
                    @endforeach
                    <form method="POST" action="{{ route('logout') }}" style="display:contents">
                        @csrf
                        <button type="submit"><span class="mi" style="color:var(--err-ink)"><svg class="ic" aria-hidden="true"><use href="#i-out"/></svg></span><span><b>Keluar</b><small>Keluar dari akun di perangkat ini</small></span></button>
                    </form>
                </div>
            </section>
        </div>
    </div>
</div>
@endsection
