@extends('layouts.admin')

@section('title', 'Profil Admin')

@section('content')
<div class="p-6 max-w-xl mx-auto">

    @if (session('success'))
    <div class="mb-4 flex items-center gap-3 px-4 py-3 rounded-2xl text-sm font-medium"
         style="background:rgba(52,211,153,0.1);border:1px solid rgba(52,211,153,0.3);color:#34d399;">
        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        {{ session('success') }}
    </div>
    @endif

    <div class="mb-6">
        <h1 class="text-xl font-extrabold text-white font-display">Profil Admin</h1>
        <p class="text-xs mt-0.5" style="color:rgba(255,255,255,0.38);">Kelola informasi akun administrator</p>
    </div>

    {{-- Avatar + Info Card --}}
    <div class="rounded-3xl p-6 mb-5" style="background:rgba(255,255,255,0.035);border:1px solid rgba(139,92,246,0.2);box-shadow:0 8px 40px rgba(0,0,0,0.4);">

        {{-- Avatar --}}
        <div class="flex items-center gap-5 mb-6 pb-6" style="border-bottom:1px solid rgba(255,255,255,0.07);">
            <div class="w-20 h-20 rounded-2xl flex items-center justify-center font-extrabold text-2xl text-white shrink-0"
                 style="background:linear-gradient(135deg,#7c3aed,#6366f1);box-shadow:0 8px 24px rgba(124,58,237,0.35);">
                {{ strtoupper(substr($user->name ?? 'A', 0, 2)) }}
            </div>
            <div>
                <p class="text-lg font-extrabold text-white font-display">{{ $user->name ?? 'Admin' }}</p>
                <p class="text-sm mt-0.5" style="color:rgba(255,255,255,0.45);">{{ $user->email }}</p>
                <span class="inline-flex items-center gap-1.5 mt-2 px-2.5 py-1 rounded-full text-[10px] font-bold"
                      style="background:rgba(124,58,237,0.2);color:#a78bfa;">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    Admin Gudang
                </span>
            </div>
        </div>

        {{-- Edit Form --}}
        <form method="POST" action="{{ route('admin.profile.update') }}">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-bold mb-2" style="color:rgba(255,255,255,0.5);">Nama Tampilan</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                           class="w-full px-4 py-3 rounded-xl text-sm text-white outline-none transition-all"
                           style="background:rgba(255,255,255,0.06);border:1.5px solid rgba(255,255,255,0.1);"
                           onfocus="this.style.borderColor='rgba(124,58,237,0.55)';this.style.boxShadow='0 0 0 3px rgba(124,58,237,0.1)'"
                           onblur="this.style.borderColor='rgba(255,255,255,0.1)';this.style.boxShadow='none'">
                    @error('name')
                        <p class="text-xs mt-1.5" style="color:#f87171;">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-xs font-bold mb-2" style="color:rgba(255,255,255,0.5);">Email</label>
                    <input type="email" value="{{ $user->email }}" disabled readonly
                           class="w-full px-4 py-3 rounded-xl text-sm outline-none cursor-not-allowed"
                           style="background:rgba(255,255,255,0.03);border:1.5px solid rgba(255,255,255,0.06);color:rgba(255,255,255,0.3);">
                    <p class="text-[10px] mt-1.5" style="color:rgba(255,255,255,0.25);">Email tidak dapat diubah melalui panel ini.</p>
                </div>
            </div>
            <button type="submit"
                    class="w-full mt-6 py-3.5 rounded-2xl font-bold text-sm text-white transition-all hover:scale-[1.01] active:scale-[0.99]"
                    style="background:linear-gradient(135deg,#7c3aed,#6366f1);box-shadow:0 6px 20px rgba(124,58,237,0.35);">
                Simpan Perubahan
            </button>
        </form>
    </div>

    {{-- Danger Zone --}}
    <div class="rounded-2xl p-5" style="background:rgba(239,68,68,0.05);border:1px solid rgba(239,68,68,0.2);">
        <p class="text-xs font-bold mb-1" style="color:#fca5a5;">Keluar dari Panel Admin</p>
        <p class="text-[11px] mb-4" style="color:rgba(255,255,255,0.35);">Kamu akan keluar dari sesi administrator.</p>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                    class="flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-semibold transition-all hover:scale-105"
                    style="background:rgba(239,68,68,0.12);border:1px solid rgba(239,68,68,0.3);color:#f87171;">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                Keluar
            </button>
        </form>
    </div>

</div>
@endsection
