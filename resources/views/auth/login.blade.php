@extends('layouts.auth')

@section('title', 'Masuk')

@section('content')
<h1>Masuk ke RuangTitip</h1>
<p class="sub">Selamat datang kembali. Masuk untuk cek dan atur barang titipanmu.</p>

<form id="login-form" action="{{ route('otp.send') }}" method="POST" novalidate>
    @csrf
    <div class="field">
        <label for="email">Email</label>
        <div class="input">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="m3 7 9 6 9-6"/></svg>
            <input id="email" name="email" type="email" autocomplete="email" placeholder="nama@student.ub.ac.id"
                   value="{{ old('email') }}" required aria-describedby="email-error"
                   aria-invalid="{{ $errors->has('email') ? 'true' : 'false' }}">
        </div>
        <p class="error {{ $errors->has('email') ? 'show' : '' }}" id="email-error">
            {{ $errors->first('email') ?: 'Masukkan email yang valid, ya.' }}
        </p>
    </div>
    <button type="submit" class="btn btn-primary">
        Masuk
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
    </button>
</form>

<div class="divider">atau lanjutkan dengan</div>

<a class="btn btn-google" href="{{ route('auth.google') }}">
    <svg width="20" height="20" viewBox="0 0 48 48" aria-hidden="true"><path fill="#FFC107" d="M43.6 20.5H42V20H24v8h11.3C33.7 32.7 29.2 36 24 36c-6.6 0-12-5.4-12-12s5.4-12 12-12c3.1 0 5.8 1.2 7.9 3.1l5.7-5.7C34 6.1 29.3 4 24 4 12.9 4 4 12.9 4 24s8.9 20 20 20 20-8.9 20-20c0-1.3-.1-2.4-.4-3.5z"/><path fill="#FF3D00" d="m6.3 14.7 6.6 4.8C14.7 15.1 19 12 24 12c3.1 0 5.8 1.2 7.9 3.1l5.7-5.7C34 6.1 29.3 4 24 4 16.3 4 9.7 8.3 6.3 14.7z"/><path fill="#4CAF50" d="M24 44c5.2 0 9.9-2 13.4-5.2l-6.2-5.2C29.2 35.1 26.7 36 24 36c-5.2 0-9.6-3.3-11.3-8l-6.5 5C9.5 39.6 16.2 44 24 44z"/><path fill="#1976D2" d="M43.6 20.5H42V20H24v8h11.3c-.8 2.2-2.2 4.2-4.1 5.6l6.2 5.2C37 39.2 44 34 44 24c0-1.3-.1-2.4-.4-3.5z"/></svg>
    Masuk dengan Google
</a>

<p class="legal">Dengan masuk, kamu setuju dengan <a href="{{ route('legal.terms') }}">Syarat &amp; Ketentuan</a> dan <a href="{{ route('legal.privacy') }}">Kebijakan Privasi</a> RuangTitip.</p>

@push('scripts')
<script>
(function () {
  var form = document.getElementById('login-form');
  var email = document.getElementById('email');
  var err = document.getElementById('email-error');
  var defaultMsg = 'Masukkan email yang valid, ya.';
  function check() {
    var ok = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.value.trim());
    email.setAttribute('aria-invalid', ok ? 'false' : 'true');
    if (!ok) err.textContent = defaultMsg;
    err.classList.toggle('show', !ok);
    return ok;
  }
  form.addEventListener('submit', function (e) {
    if (!check()) { e.preventDefault(); email.focus(); }
  });
  email.addEventListener('input', function () {
    if (email.getAttribute('aria-invalid') === 'true') check();
  });
})();
</script>
@endpush
@endsection
