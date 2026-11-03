@extends('layouts.rt')

@section('title', 'Notifikasi')

@section('content')
@php
    $unread = \App\Support\UserNotifications::unreadCount($notifications);
@endphp
<div class="pg">
    <div class="pg-head">
        <div>
            <h2>Notifikasi</h2>
            <p>{{ $unread ? $unread . ' belum dibaca' : 'Semua sudah dibaca' }}. Kabar soal pembayaran, penjemputan, dan status barangmu.</p>
        </div>
        @if ($unread)
            <form method="POST" action="{{ route('notifikasi.read') }}">
                @csrf
                <button class="btn btn-ghost sm" type="submit">Tandai semua dibaca</button>
            </form>
        @endif
    </div>

    <section class="rt-card">
        @if ($notifications->isNotEmpty())
            <ul class="bd-feed">
                @foreach ($notifications as $n)
                    <li>
                        <span class="dot {{ \App\Support\UserNotifications::isUnread($n) ? 'd-sun' : 'd-mute' }}" aria-hidden="true"><svg class="ic"><use href="#n-bell"/></svg></span>
                        <div>
                            <a href="{{ $n['link'] }}"><b>{{ $n['text'] }}</b></a>
                            @if ($n['at'])<span>{{ $n['at']->locale('id')->diffForHumans() }}</span>@endif
                        </div>
                    </li>
                @endforeach
            </ul>
        @else
            <p class="bd-note">Belum ada notifikasi. Kabar soal pembayaran, penjemputan, dan status barang akan muncul di sini.</p>
        @endif
    </section>
</div>
@endsection
