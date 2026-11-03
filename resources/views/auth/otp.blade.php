@extends('layouts.auth')

@section('title', 'Verifikasi OTP')
@section('bubble-title', 'Hampir selesai!')

@section('content')
<div class="head">
    <svg aria-hidden="true"><use href="#st-envelope"/></svg>
    <h1>Masukkan kode</h1>
</div>
<p class="sub">Kode 6 digit sudah kami kirim ke <strong>{{ $email }}</strong>. Berlaku 10 menit.</p>

@if (session('status'))
    <div class="alert a-ok auth-alert" role="status"><svg class="ic" aria-hidden="true"><use href="#i-check"/></svg><div>{{ session('status') }}</div></div>
@endif
@if ($errors->any())
    <div class="alert a-err auth-alert" role="alert"><svg class="ic" aria-hidden="true"><use href="#i-warn"/></svg><div>{{ $errors->first() }}</div></div>
@endif

<form method="POST" action="{{ route('otp.verify') }}" id="otpForm">
    @csrf
    <input type="hidden" name="code" id="codeField">

    <div class="otp-label"><span id="otpLbl">Kode verifikasi</span><small>Bisa langsung paste</small></div>
    <div class="otp-boxes" id="otpBoxes" role="group" aria-labelledby="otpLbl">
        @for ($i = 0; $i < 6; $i++)
            <input type="text" inputmode="numeric" maxlength="1" data-index="{{ $i }}" class="otp-box" aria-label="Digit {{ $i + 1 }}" @if ($i === 0) autocomplete="one-time-code" @endif>
            @if ($i === 2)<span class="dash" aria-hidden="true"></span>@endif
        @endfor
    </div>
    <p class="otp-hint">Belum masuk? Cek folder Spam atau Promosi.</p>

    <button type="submit" class="btn btn-sun btn-block btn-submit">Verifikasi dan masuk</button>
</form>

<div class="resend">
    <a class="back" href="{{ route('login') }}" style="padding:0">Ganti email</a>
    <form method="POST" action="{{ route('otp.resend') }}" id="resendForm">
        @csrf
        <button type="submit" id="resendBtn" disabled class="resend-btn">
            <span id="resendLabel">Kirim ulang dalam <span id="countdown">60</span>s</span>
        </button>
    </form>
</div>

@push('scripts')
<script>
(function () {
    var boxes = Array.from(document.querySelectorAll('.otp-box'));
    var codeField = document.getElementById('codeField');
    var form = document.getElementById('otpForm');

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
        box.addEventListener('focus', function () { box.select(); });
    });
    if (boxes[0]) boxes[0].focus();

    form.addEventListener('submit', syncCode);

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
