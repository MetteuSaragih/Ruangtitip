@extends('errors.layout')

@section('title', 'Sedang dirapikan')
@section('badge', 'Sedang perawatan')
@section('pose', 'tukang')

@php
    // Selang muat ulang otomatis (detik): ikut Retry-After dari `php artisan down --retry=...`, minimal 30.
    $retryAfter = 0;
    if (isset($exception) && method_exists($exception, 'getHeaders')) {
        $retryAfter = (int) ($exception->getHeaders()['Retry-After'] ?? 0);
    }
    $interval = max(30, $retryAfter ?: 60);
@endphp

@section('heading')
    Ruru lagi <em>beres-beres</em> gudang
@endsection

@section('lead', 'RuangTitip sedang kami rapikan supaya makin nyaman dipakai. Tidak lama lagi buka kembali. Barang titipanmu tetap aman, dan pesananmu tidak hilang.')

@section('actions')
    <button class="btn btn-primary" id="reloadPage" type="button">Cek lagi sekarang</button>
    <a class="btn btn-ghost" href="https://wa.me/6285121091134" target="_blank" rel="noopener">Chat tim kami</a>
@endsection

@section('style')
    <style>
        .progress { width: min(320px, 100%); height: 6px; margin-top: 28px; border-radius: 99px; background: var(--aqua); overflow: hidden; }
        .progress i { display: block; width: 40%; height: 100%; border-radius: 99px; background: var(--teal); animation: slide 3s ease-in-out infinite; }
        @keyframes slide { 0% { transform: translateX(-100%); } 100% { transform: translateX(260%); } }

        .tip { min-height: 3.4em; transition: opacity .5s; }
        .tip.is-out { opacity: 0; }
        .auto { margin-top: 14px; font-size: 13px; color: var(--muted); }
    </style>
@endsection

@section('extra')
    <div class="progress" role="presentation"><i></i></div>

    <div class="box" aria-live="polite">
        <p><b>Sambil menunggu</b></p>
        <p class="tip" id="tip">Pesanan dan pembayaranmu aman tersimpan. Semuanya bisa kamu cek lagi begitu kami buka.</p>
    </div>

    <p class="auto">Halaman ini mencoba memuat ulang sendiri setiap {{ $interval >= 90 ? round($interval / 60) . ' menit' : $interval . ' detik' }}.</p>
@endsection

@section('script')
    <script>
        (function () {
            // Muat ulang otomatis, pelan-pelan.
            setTimeout(function () { window.location.reload(); }, {{ $interval * 1000 }});

            // Tips bergantian setiap 8 detik, dengan pudar halus.
            var tips = [
                'Pesanan dan pembayaranmu aman tersimpan. Semuanya bisa kamu cek lagi begitu kami buka.',
                'Sudah membayar? Kamu tidak perlu membayar ulang, cukup tunggu kami buka kembali.',
                'Ada urusan mendesak soal barangmu? Chat tim kami lewat WhatsApp.'
            ];
            var el = document.getElementById('tip');
            var i = 0;
            if (!el || (window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches)) { return; }
            setInterval(function () {
                el.classList.add('is-out');
                setTimeout(function () {
                    i = (i + 1) % tips.length;
                    el.textContent = tips[i];
                    el.classList.remove('is-out');
                }, 500);
            }, 8000);
        })();
    </script>
@endsection
