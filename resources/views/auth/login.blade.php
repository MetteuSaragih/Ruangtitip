@extends('layouts.auth')

@section('title', 'Masuk')

@section('content')
<div>
    {{-- Logo desktop --}}
    <div class="hidden lg:flex items-center gap-2.5 mb-8">
        <div class="w-9 h-9 rounded-xl flex items-center justify-center"
             style="background:linear-gradient(135deg,#7c3aed,#6366f1);box-shadow:0 4px 16px rgba(124,58,237,0.35);">
            <x-lucide-package class="w-5 h-5 text-white" />
        </div>
        <span class="text-xl font-extrabold text-white font-display">RUTIP</span>
    </div>

    <h1 class="text-2xl font-extrabold text-white font-display mb-1">Masuk ke RUTIP</h1>
    <p class="text-sm mb-8" style="color:rgba(255,255,255,0.42);">
        Selamat datang kembali, silakan login untuk lanjut.
    </p>

    {{-- Error --}}
    @if ($errors->any())
        <div class="rounded-xl px-4 py-3 mb-5 text-sm flex items-start gap-2"
             style="background:rgba(239,68,68,0.1);border:1px solid rgba(239,68,68,0.3);color:#fca5a5;">
            <x-lucide-alert-circle class="w-4 h-4 mt-0.5 shrink-0" />
            <span>{{ $errors->first() }}</span>
        </div>
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
            Login <x-lucide-arrow-right class="w-4 h-4" />
        </button>
    </form>

    {{-- Divider --}}
    <div class="flex items-center gap-3 my-6">
        <div class="flex-1 h-px" style="background:rgba(255,255,255,0.08);"></div>
        <span class="text-xs" style="color:rgba(255,255,255,0.3);">Atau lanjutkan dengan</span>
        <div class="flex-1 h-px" style="background:rgba(255,255,255,0.08);"></div>
    </div>

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
        Login dengan Google
    </a>
</div>
@endsection
