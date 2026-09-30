@extends('layouts.auth')

@section('title', 'Verifikasi OTP')
@section('bubble-title', 'Hampir selesai!')
@section('bubble-text', 'Cek email kamu buat kode OTP-nya.')

@section('content')
<a href="{{ route('login') }}" class="back-inline">
    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5M11 18l-6-6 6-6"/></svg>
    Kembali
</a>

<h1>Masukkan Kode OTP</h1>
<p class="sub" style="margin-bottom:4px">Kode verifikasi dikirim ke</p>
<p class="sub" style="margin:0 0 28px;color:var(--tape-dark);font-weight:700">{{ $email }}</p>

@if (session('status'))
    <div class="alert alert-success">{{ session('status') }}</div>
@endif
@if ($errors->any())
    <div class="alert alert-error">{{ $errors->first() }}</div>
@endif

<form method="POST" action="{{ route('otp.verify') }}" id="otpForm">
    @csrf
    <input type="hidden" name="code" id="codeField">

    <div class="otp-boxes" id="otpBoxes">
        @for ($i = 0; $i < 6; $i++)
            <input type="text" inputmode="numeric" maxlength="1" data-index="{{ $i }}" class="otp-box">
        @endfor
    </div>

    <p class="otp-hint">Kode berlaku selama <strong>10 menit</strong></p>

    <button type="submit" class="btn btn-primary" style="margin-bottom:20px">
        Verifikasi &amp; Masuk
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
    </button>
</form>

<div class="resend">
    <form method="POST" action="{{ route('otp.resend') }}" id="resendForm">
        @csrf
        <button type="submit" id="resendBtn" disabled class="resend-btn">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12a9 9 0 1 1-2.64-6.36"/><path d="M21 3v6h-6"/></svg>
            <span id="resendLabel">Kirim ulang dalam <span id="countdown">60</span>s</span>
        </button>
    </form>
</div>

@push('scripts')
<script>
(function () {
    var boxes = Array.from(document.querySelectorAll('.otp-box'));
    var codeField = document.getElementById('codeField');

    function syncCode() {
        codeField.value = boxes.map(function (b) { return b.value; }).join('');
    }
    function styleBox(b) {
        b.classList.toggle('otp-filled', !!b.value);
    }

    boxes.forEach(function (box, i) {
        box.addEventListener('input', function (e) {
            box.value = e.target.value.replace(/\D/g, '').slice(-1);
            styleBox(box);
            if (box.value && i < 5) boxes[i + 1].focus();
            syncCode();
        });
        box.addEventListener('keydown', function (e) {
            if (e.key === 'Backspace' && !box.value && i > 0) boxes[i - 1].focus();
        });
        box.addEventListener('paste', function (e) {
            var digits = (e.clipboardData.getData('text') || '').replace(/\D/g, '').slice(0, 6).split('');
            boxes.forEach(function (b, j) { b.value = digits[j] || ''; styleBox(b); });
            boxes[Math.min(digits.length, 5)].focus();
            syncCode();
            e.preventDefault();
        });
    });
    if (boxes[0]) boxes[0].focus();

    var secs = 60;
    var btn = document.getElementById('resendBtn');
    var label = document.getElementById('resendLabel');
    var cd = document.getElementById('countdown');
    var timer = setInterval(function () {
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
@endpush
@endsection
