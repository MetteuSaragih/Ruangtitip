@extends('layouts.auth')

@section('title', 'Verifikasi OTP')

@section('content')
<div>
    {{-- Kembali --}}
    <a href="{{ route('login') }}" class="flex items-center gap-1.5 text-sm mb-8 transition-colors hover:text-violet-300"
       style="color:rgba(255,255,255,0.4);">
        <x-lucide-arrow-left class="w-3.5 h-3.5" /> Kembali
    </a>

    {{-- Icon --}}
    <div class="w-14 h-14 rounded-2xl flex items-center justify-center mb-6"
         style="background:rgba(124,58,237,0.15);border:1px solid rgba(124,58,237,0.3);">
        <x-lucide-shield-check class="w-7 h-7" style="color:#a78bfa;" />
    </div>

    <h1 class="text-2xl font-extrabold text-white font-display mb-1">Masukkan Kode OTP</h1>
    <p class="text-sm mb-1" style="color:rgba(255,255,255,0.42);">Kode verifikasi dikirim ke</p>
    <p class="text-sm font-semibold mb-6" style="color:#a78bfa;">{{ $email }}</p>

    {{-- Status / Error --}}
    @if (session('status'))
        <div class="rounded-xl px-4 py-2.5 mb-4 text-sm" style="background:rgba(52,211,153,0.1);border:1px solid rgba(52,211,153,0.3);color:#6ee7b7;">
            {{ session('status') }}
        </div>
    @endif
    @if ($errors->any())
        <div class="rounded-xl px-4 py-2.5 mb-4 text-sm" style="background:rgba(239,68,68,0.1);border:1px solid rgba(239,68,68,0.3);color:#fca5a5;">
            {{ $errors->first() }}
        </div>
    @endif

    {{-- Form OTP --}}
    <form method="POST" action="{{ route('otp.verify') }}" id="otpForm">
        @csrf
        <input type="hidden" name="code" id="codeField">

        <div class="flex gap-2 justify-center mb-4" id="otpBoxes">
            @for ($i = 0; $i < 6; $i++)
                <input type="text" inputmode="numeric" maxlength="1" data-index="{{ $i }}"
                       class="otp-box text-center text-lg font-bold text-white rounded-xl outline-none transition-all"
                       style="width:46px;height:52px;background:rgba(255,255,255,0.05);border:1.5px solid rgba(255,255,255,0.1);">
            @endfor
        </div>

        <p class="text-xs text-center mb-6" style="color:rgba(255,255,255,0.35);">
            Kode berlaku selama <span class="font-semibold text-white">10 menit</span>
        </p>

        <button type="submit"
                class="w-full flex items-center justify-center gap-2 py-3.5 rounded-xl font-bold text-sm text-white transition-all hover:scale-[1.02] active:scale-[0.98] mb-4"
                style="background:linear-gradient(135deg,#7c3aed,#6366f1);box-shadow:0 6px 20px rgba(124,58,237,0.4);">
            Verifikasi & Masuk <x-lucide-arrow-right class="w-4 h-4" />
        </button>
    </form>

    {{-- Resend --}}
    <div class="text-center">
        <form method="POST" action="{{ route('otp.resend') }}" id="resendForm">
            @csrf
            <button type="submit" id="resendBtn" disabled
                    class="inline-flex items-center gap-1.5 text-sm font-semibold transition-colors disabled:opacity-50"
                    style="color:#a78bfa;">
                <x-lucide-refresh-cw class="w-3.5 h-3.5" />
                <span id="resendLabel">Kirim ulang dalam <span id="countdown">60</span>s</span>
            </button>
        </form>
    </div>
</div>

<script>
(function () {
    // ── Logika 6 kotak OTP ──
    const boxes = Array.from(document.querySelectorAll('.otp-box'));
    const codeField = document.getElementById('codeField');

    function syncCode() {
        codeField.value = boxes.map(b => b.value).join('');
    }
    function styleBox(b) {
        if (b.value) {
            b.style.background = 'rgba(124,58,237,0.15)';
            b.style.borderColor = 'rgba(139,92,246,0.6)';
            b.style.boxShadow = '0 0 0 3px rgba(124,58,237,0.1)';
        } else {
            b.style.background = 'rgba(255,255,255,0.05)';
            b.style.borderColor = 'rgba(255,255,255,0.1)';
            b.style.boxShadow = 'none';
        }
    }

    boxes.forEach((box, i) => {
        box.addEventListener('input', e => {
            box.value = e.target.value.replace(/\D/g, '').slice(-1);
            styleBox(box);
            if (box.value && i < 5) boxes[i + 1].focus();
            syncCode();
        });
        box.addEventListener('keydown', e => {
            if (e.key === 'Backspace' && !box.value && i > 0) boxes[i - 1].focus();
        });
        box.addEventListener('paste', e => {
            const digits = (e.clipboardData.getData('text') || '').replace(/\D/g, '').slice(0, 6).split('');
            boxes.forEach((b, j) => { b.value = digits[j] || ''; styleBox(b); });
            boxes[Math.min(digits.length, 5)].focus();
            syncCode();
            e.preventDefault();
        });
    });
    if (boxes[0]) boxes[0].focus();

    // ── Countdown kirim ulang (60 detik) ──
    let secs = 60;
    const btn = document.getElementById('resendBtn');
    const label = document.getElementById('resendLabel');
    const cd = document.getElementById('countdown');
    const timer = setInterval(() => {
        secs--;
        if (secs <= 0) {
            clearInterval(timer);
            btn.disabled = false;
            label.textContent = 'Kirim ulang kode';
        } else {
            cd.textContent = secs;
        }
    }, 1000);
})();
</script>
@endsection
