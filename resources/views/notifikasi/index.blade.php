@extends('layouts.rt')

@section('title', 'Notifikasi')

@section('content')
@php
    $unread = \App\Support\UserNotifications::unreadCount($notifications);
@endphp
<div class="ttp">
<div class="stack">
    <nav class="crumbs" aria-label="Breadcrumb"><span aria-current="page">Notifikasi</span></nav>
    <div class="ph">
        <div>
            <h2 class="pt" tabindex="-1">Notifikasi</h2>
            <p class="ps">Kabar terbaru soal pesanan, pembayaran, dan jadwal.</p>
        </div>
        @if ($unread)
            <div class="ph-acts">
                <form method="POST" action="{{ route('notifikasi.read') }}">
                    @csrf
                    <button class="btn btn-ghost" type="submit">Tandai semua dibaca</button>
                </form>
            </div>
        @endif
    </div>

    @if ($notifications->isNotEmpty())
        <ul class="nl">
            @foreach ($notifications as $n)
                @php $isUnread = \App\Support\UserNotifications::isUnread($n); @endphp
                <li>
                    <a href="{{ $n['link'] }}" class="{{ $isUnread ? '' : 'read' }}">
                        <span class="u" aria-hidden="true"></span>
                        <span>
                            <b>{{ $n['text'] }}</b>
                            <small>@if ($n['at']){{ $n['at']->translatedFormat('j M Y') }}, {{ $n['at']->format('H.i') }}@endif{{ $isUnread ? ', belum dibaca' : '' }}</small>
                        </span>
                    </a>
                </li>
            @endforeach
        </ul>
    @else
        <section class="card state">
            <svg viewBox="0 0 44 40" width="90" height="82" aria-hidden="true"><use href="#box"/></svg>
            <h3>Belum ada notifikasi</h3>
            <p>Kabar soal pembayaran, jadwal, dan barangmu akan muncul di sini.</p>
        </section>
    @endif
</div>
</div>
@endsection
