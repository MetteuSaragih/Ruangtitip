@extends('akun.layout')

@section('title', 'Keamanan')
@section('key', 'keamanan')
@section('sub', 'Jaga akunmu tetap aman, terutama karena akun ini terhubung ke barang yang kamu titipkan.')

@section('body')
@php $blocked = $activeTitip > 0 || $unpaid > 0; @endphp

<section class="card stack">
    <div><h3>Cara masuk</h3><p class="help">Akun ini masuk dengan kode OTP ke emailmu atau dengan akun Google, jadi tidak ada kata sandi yang perlu diingat atau diganti.</p></div>
    <p class="help">Email masuk: <b>{{ $user->email }}</b></p>
</section>

<section class="card">
    <div class="card-h">
        <div><h3>Perangkat yang masuk</h3><p class="help">Keluarkan perangkat yang tidak kamu kenali.</p></div>
        @if ($sessions->count() > 1)
            <form method="POST" action="{{ route('akun.sesi.others') }}" onsubmit="return confirm('Keluarkan semua perangkat lain?')">@csrf @method('DELETE')<button class="btn btn-ghost sm" type="submit">Keluarkan semua perangkat lain</button></form>
        @endif
    </div>
    @if ($sessions->isNotEmpty())
        <ul class="list">
            @foreach ($sessions as $s)
                <li class="li">
                    <div style="display:flex;gap:12px">
                        <span class="r-ic" style="width:44px;height:44px;flex:none"><svg class="ic" aria-hidden="true"><use href="#i-device"/></svg></span>
                        <div>
                            <div class="top"><b>{{ $s['device'] }}</b>@if ($s['current'])<span class="badge b-ok"><i></i>Perangkat ini</span>@endif</div>
                            <p>{{ $s['ip'] }}. {{ $s['current'] ? 'Aktif sekarang' : 'Terakhir aktif ' . $s['last']->locale('id')->translatedFormat('j M Y, H.i') }}</p>
                        </div>
                    </div>
                    <div class="acts">
                        @unless ($s['current'])
                            <form method="POST" action="{{ route('akun.sesi.revoke', $s['id']) }}">@csrf @method('DELETE')<button class="btn btn-link" style="color:var(--err-ink)" type="submit">Keluarkan</button></form>
                        @endunless
                    </div>
                </li>
            @endforeach
        </ul>
    @else
        <p class="help">Daftar perangkat belum tersedia.</p>
    @endif
</section>

<section class="card danger">
    <h3>Hapus akun</h3>
    <p class="help">Akun dan riwayatmu akan dihapus permanen sesuai <a href="{{ route('legal.privacy') }}">Kebijakan Privasi</a>.</p>
    @if ($blocked)
        <div class="alert a-warn">
            <svg class="ic" aria-hidden="true"><use href="#i-warn"/></svg>
            <div>
                <b>Akun belum bisa dihapus</b>
                <p>Masih ada {{ $activeTitip ? $activeTitip . ' titipan dalam penyimpanan' : '' }}{{ $activeTitip && $unpaid ? ' dan ' : '' }}{{ $unpaid ? $unpaid . ' tagihan belum lunas' : '' }}. Selesaikan dulu supaya barangmu tidak tertinggal di gudang.</p>
                <div class="acts"><a class="btn btn-ghost sm" href="{{ route('pesanan.index') }}">Lihat Pesanan Saya</a></div>
            </div>
        </div>
    @endif
    <form method="POST" action="{{ route('akun.hapus') }}" class="mt" onsubmit="return confirm('Hapus akun secara permanen? Tindakan ini tidak bisa dibatalkan.')">
        @csrf @method('DELETE')
        <button class="btn btn-danger" type="submit" @disabled($blocked)>Hapus akun</button>
    </form>
</section>
@endsection
