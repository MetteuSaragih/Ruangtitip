@extends('layouts.auth')

@section('title', 'Masuk')
@section('bubble-title', 'Aku jagain!')

@section('content')
<section class="view on" id="vEmail" aria-labelledby="t1">
    <div class="head"><svg aria-hidden="true"><use href="#st-key"/></svg><h2 id="t1">Masuk ke RuangTitip</h2></div>
    <p class="sub">Belum punya akun? Akunmu dibuat otomatis saat pertama kali masuk.</p>

    <a class="btn btn-google" id="gBtn" href="{{ route('auth.google') }}"><span class="g-slot" aria-hidden="true"><svg width="20" height="20" viewBox="0 0 48 48" aria-hidden="true"><path fill="#FFC107" d="M43.6 20.5H42V20H24v8h11.3C33.7 32.7 29.2 36 24 36c-6.6 0-12-5.4-12-12s5.4-12 12-12c3.1 0 5.8 1.2 7.9 3.1l5.7-5.7C34 6.1 29.3 4 24 4 12.9 4 4 12.9 4 24s8.9 20 20 20 20-8.9 20-20c0-1.3-.1-2.4-.4-3.5z"/><path fill="#FF3D00" d="m6.3 14.7 6.6 4.8C14.7 15.1 19 12 24 12c3.1 0 5.8 1.2 7.9 3.1l5.7-5.7C34 6.1 29.3 4 24 4 16.3 4 9.7 8.3 6.3 14.7z"/><path fill="#4CAF50" d="M24 44c5.2 0 9.9-2 13.4-5.2l-6.2-5.2C29.2 35.1 26.7 36 24 36c-5.2 0-9.6-3.3-11.3-8l-6.5 5C9.5 39.6 16.2 44 24 44z"/><path fill="#1976D2" d="M43.6 20.5H42V20H24v8h11.3c-.8 2.2-2.2 4.2-4.1 5.6l6.2 5.2C37 39.2 44 34 44 24c0-1.3-.1-2.4-.4-3.5z"/></svg></span><span class="lbl">Lanjutkan dengan Google</span><span class="spin" aria-hidden="true"></span></a>

    <div class="or">atau pakai email</div>

    <form id="emailForm" action="{{ route('otp.send') }}" method="POST" novalidate>
        @csrf
        <div class="field {{ $errors->has('email') ? 'bad' : '' }}" id="emailField">
            <label for="email">Email</label>
            <div class="input">
                <svg aria-hidden="true"><use href="#st-mail"/></svg>
                <input type="email" id="email" name="email" value="{{ old('email') }}" autocomplete="email" inputmode="email" placeholder="nama@email.com" required aria-describedby="emailMsg" @if ($errors->has('email')) aria-invalid="true" @endif>
            </div>
            <p class="msg" id="emailMsg" role="alert">{{ $errors->first('email') ?: 'Format email belum benar. Contoh: nama@email.com' }}</p>
        </div>
        <button class="btn btn-primary" type="submit" id="sendBtn"><span class="lbl">Kirim kode masuk</span><span class="spin" aria-hidden="true"></span></button>
    </form>
    <p class="legal">Dengan masuk, kamu menyetujui <a href="{{ route('legal.terms') }}">Syarat &amp; Ketentuan</a> dan <a href="{{ route('legal.privacy') }}">Kebijakan Privasi</a>.</p>
</section>

@push('scripts')
<script>
(function () {
    var field = document.getElementById('emailField');
    var email = document.getElementById('email');
    var msg = document.getElementById('emailMsg');
    var defaultMsg = 'Format email belum benar. Contoh: nama@email.com';
    var valid = function (v) { return /^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/.test(v); };

    email.addEventListener('input', function () {
        if (field.classList.contains('bad') && valid(email.value.trim())) {
            field.classList.remove('bad');
            email.removeAttribute('aria-invalid');
        }
    });
    document.getElementById('emailForm').addEventListener('submit', function (e) {
        if (!valid(email.value.trim())) {
            e.preventDefault();
            msg.textContent = defaultMsg;
            field.classList.add('bad');
            email.setAttribute('aria-invalid', 'true');
            email.focus();
            return;
        }
        var b = document.getElementById('sendBtn');
        b.classList.add('loading');
    });
    document.getElementById('gBtn').addEventListener('click', function (e) {
        e.currentTarget.classList.add('loading');
    });
})();
</script>
@endpush
@endsection
