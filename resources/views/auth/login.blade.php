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
<<<<<<< HEAD
        <p class="error {{ $errors->has('email') ? 'show' : '' }}" id="email-error">
            {{ $errors->first('email') ?: 'Masukkan email yang valid, ya.' }}
        </p>
=======
    @endif

    {{-- Form email --}}
    <form method="POST" action="{{ route('otp.send') }}" class="space-y-4">
        @csrf
        <div>
            <label class="block text-xs font-semibold mb-2" style="color:rgba(255,255,255,0.58);">Email</label>
            <div class="relative">
                <x-lucide-mail class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 pointer-events-none" style="color:rgba(255,255,255,0.28);" />
                <input type="email" name="email" value="{{ old('email') }}" required
                       placeholder="Masukkan email kamu"
                       class="w-full pl-11 pr-4 py-3.5 rounded-xl text-sm text-white outline-none transition-all"
                       style="background:rgba(255,255,255,0.06);border:1.5px solid rgba(255,255,255,0.1);"
                       onfocus="this.style.borderColor='rgba(139,92,246,0.6)';this.style.boxShadow='0 0 0 3px rgba(124,58,237,0.1)';"
                       onblur="this.style.borderColor='rgba(255,255,255,0.1)';this.style.boxShadow='none';">
            </div>
        </div>

        <button type="submit"
                class="w-full flex items-center justify-center gap-2 py-3.5 rounded-xl font-bold text-sm text-white transition-all hover:scale-[1.02] active:scale-[0.98]"
                style="background:linear-gradient(135deg,#7c3aed,#6366f1);box-shadow:0 6px 20px rgba(124,58,237,0.4);">
            Masuk <x-lucide-arrow-right class="w-4 h-4" />
        </button>
    </form>

    {{-- Divider --}}
    <div class="flex items-center gap-3 my-6">
        <div class="flex-1 h-px" style="background:rgba(255,255,255,0.08);"></div>
        <span class="text-xs" style="color:rgba(255,255,255,0.3);">Atau lanjutkan dengan</span>
        <div class="flex-1 h-px" style="background:rgba(255,255,255,0.08);"></div>
>>>>>>> hostinger/main
    </div>
    <button type="submit" class="btn btn-primary">
        Masuk
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
    </button>
</form>

<<<<<<< HEAD
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
=======
    {{-- Google --}}
    <a href="{{ route('auth.google') }}"
       class="w-full flex items-center justify-center gap-3 py-3.5 rounded-xl text-sm font-semibold transition-all hover:bg-white/5"
       style="border:1.5px solid rgba(255,255,255,0.12);color:rgba(255,255,255,0.78);">
        <svg class="w-5 h-5 shrink-0" viewBox="0 0 24 24">
            <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
            <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
            <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l3.66-2.84z"/>
            <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
        </svg>
        Masuk dengan Google
    </a>
</div>
>>>>>>> hostinger/main
@endsection
