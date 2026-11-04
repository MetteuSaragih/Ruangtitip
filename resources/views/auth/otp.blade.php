@extends('layouts.auth')

@section('title', 'Verifikasi OTP')
@section('bubble-title', 'Hampir selesai!')
@section('back-href', route('login'))
@section('back-label', 'Ganti email')

@section('content')
@php
    $otpError = $errors->first('code') ?: $errors->first();
    $otpInfo = session('status');
@endphp
<section class="view on" id="vOtp" aria-labelledby="t2">
    <div class="head"><svg aria-hidden="true"><use href="#st-envelope"/></svg><h2 id="t2">Masukkan kode</h2></div>
    <p class="sub">Kode 6 digit sudah kami kirim. Berlaku 10 menit.</p>
    <div class="sent"><svg aria-hidden="true"><use href="#st-mail"/></svg><b id="sentTo">{{ $email }}</b><button type="button" id="changeEmail">Ubah</button></div>

    <form id="otpForm" action="{{ route('otp.verify') }}" method="POST" novalidate>
        @csrf
        <input type="hidden" name="code" id="codeField">
        <div class="otp-wrap">
            <div class="otp-label"><span id="otpLbl">Kode verifikasi</span><small>Bisa langsung paste</small></div>
            <div class="otp {{ $otpError ? 'bad' : '' }}" id="otp" role="group" aria-labelledby="otpLbl">
                <div class="grp">
                    <input inputmode="numeric" autocomplete="one-time-code" maxlength="1" placeholder=" " aria-label="Digit 1">
                    <input inputmode="numeric" maxlength="1" placeholder=" " aria-label="Digit 2">
                    <input inputmode="numeric" maxlength="1" placeholder=" " aria-label="Digit 3">
                </div>
                <span class="dash" aria-hidden="true"></span>
                <div class="grp">
                    <input inputmode="numeric" maxlength="1" placeholder=" " aria-label="Digit 4">
                    <input inputmode="numeric" maxlength="1" placeholder=" " aria-label="Digit 5">
                    <input inputmode="numeric" maxlength="1" placeholder=" " aria-label="Digit 6">
                </div>
            </div>
            <p class="otp-msg {{ ! $otpError && $otpInfo ? 'info' : '' }}" id="otpMsg" role="status">{{ $otpError ?: $otpInfo }}</p>
        </div>
        <button class="btn btn-primary" type="submit" id="verifyBtn" disabled><span class="lbl">Verifikasi dan masuk</span><span class="spin" aria-hidden="true"></span></button>
    </form>

    <div class="resend">
        <span class="timer" id="timer"><svg viewBox="0 0 20 20" aria-hidden="true"><circle class="tr" cx="10" cy="10" r="8"/><circle class="tp" id="ring" cx="10" cy="10" r="8"/></svg><span id="timerTxt">Kirim ulang dalam <b id="count">1:00</b></span></span>
        <button class="link" id="resendBtn" type="submit" form="resendForm" disabled>Kirim ulang</button>
    </div>
    <form id="resendForm" action="{{ route('otp.resend') }}" method="POST">@csrf</form>
    <p class="hint">Belum masuk? Cek folder Spam atau Promosi.</p>
</section>

@push('scripts')
<script>
(function () {
    var $ = function (s) { return document.querySelector(s); };
    var otp = $('#otp');
    var boxes = Array.prototype.slice.call(otp.querySelectorAll('input'));
    var verify = $('#verifyBtn');
    var form = $('#otpForm');
    var codeField = $('#codeField');
    var msg = $('#otpMsg');
    var timer = null;

    var code = function () { return boxes.map(function (b) { return b.value; }).join(''); };
    function say(t, info) { msg.textContent = t; msg.classList.toggle('info', !!info); }
    function refresh() {
        boxes.forEach(function (b) { b.classList.toggle('filled', !!b.value); });
        codeField.value = code();
        verify.disabled = code().length !== 6;
    }
    function submitIfFull() {
        if (code().length === 6 && !verify.classList.contains('loading')) {
            verify.classList.add('loading');
            form.submit();
        }
    }
    function fill(from, digits) {
        digits.split('').forEach(function (d, k) { if (boxes[from + k]) boxes[from + k].value = d; });
        boxes[Math.min(from + digits.length, 5)].focus();
        refresh();
        submitIfFull();
    }
    boxes.forEach(function (b, i) {
        b.addEventListener('input', function () {
            var d = b.value.replace(/\D/g, '');
            if (otp.classList.contains('bad')) { otp.classList.remove('bad'); say(''); }
            if (d.length > 1) { fill(i, d.slice(0, 6 - i)); return; }
            b.value = d;
            refresh();
            if (d && i < 5) boxes[i + 1].focus();
            submitIfFull();
        });
        b.addEventListener('keydown', function (e) {
            if (e.key === 'Backspace' && !b.value && i > 0) { e.preventDefault(); boxes[i - 1].value = ''; boxes[i - 1].focus(); refresh(); }
            if (e.key === 'ArrowLeft' && i > 0) { e.preventDefault(); boxes[i - 1].focus(); }
            if (e.key === 'ArrowRight' && i < 5) { e.preventDefault(); boxes[i + 1].focus(); }
        });
        b.addEventListener('paste', function (e) {
            var d = ((e.clipboardData && e.clipboardData.getData('text')) || '').replace(/\D/g, '');
            if (d) { e.preventDefault(); fill(0, d.slice(0, 6)); }
        });
        b.addEventListener('focus', function () { setTimeout(function () { if (document.activeElement === b) b.select(); }, 0); });
    });
    form.addEventListener('submit', function (e) {
        refresh();
        if (code().length !== 6) { e.preventDefault(); return; }
        verify.classList.add('loading');
    });
    $('#changeEmail').addEventListener('click', function () { window.location.href = @json(route('login')); });

    var ring = $('#ring');
    var resendBtn = $('#resendBtn');
    var txt = $('#timerTxt');
    var FULL = 50.3;
    var T = 60;
    var fmt = function (t) { return Math.floor(t / 60) + ':' + String(t % 60).padStart(2, '0'); };
    var t = T;
    ring.style.strokeDashoffset = 0;
    timer = setInterval(function () {
        t--;
        var c = document.getElementById('count');
        if (c) c.textContent = fmt(t);
        ring.style.strokeDashoffset = FULL * (1 - t / T);
        if (t <= 0) {
            clearInterval(timer);
            resendBtn.disabled = false;
            txt.textContent = 'Kodenya belum sampai?';
        }
    }, 1000);

    boxes[0].focus();
})();
</script>
@endpush
@endsection
