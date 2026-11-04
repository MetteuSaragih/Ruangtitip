@extends('layouts.auth')

{{-- Layar "Berhasil masuk!" setelah OTP benar, sama dengan tampilan vDone di prototipe. --}}
@section('title', 'Berhasil masuk')
@section('bubble-title', 'Selamat datang!')
@section('back-visibility', 'hidden')

@push('styles')
<meta http-equiv="refresh" content="4;url={{ $to }}">
@endpush

@section('content')
<section class="view done on" aria-labelledby="t3" aria-live="polite">
    <svg class="ruru" viewBox="0 0 160 150" aria-hidden="true">
        <ellipse cx="80" cy="142" rx="44" ry="6" fill="#0C2B2A" opacity=".18"/>
        <rect x="52" y="104" width="8" height="30" rx="4" fill="#4A2E1C"/><rect x="96" y="104" width="8" height="30" rx="4" fill="#4A2E1C"/>
        <path d="M40 140q0-12 16-12q12 0 12 12z" fill="#FFC93C" stroke="#0C2B2A" stroke-width="2"/><path d="M90 140q0-12 14-12q16 0 16 12z" fill="#FFC93C" stroke="#0C2B2A" stroke-width="2"/>
        <path d="M114 44 L130 34 L130 98 L114 110 Z" fill="#B9814A" stroke="#0C2B2A" stroke-width="2.5" stroke-linejoin="round"/>
        <rect x="38" y="44" width="76" height="66" rx="5" fill="#D9A066" stroke="#0C2B2A" stroke-width="2.5"/>
        <path d="M38 44 L22 26 L64 22 L76 44 Z" fill="#E7B984" stroke="#0C2B2A" stroke-width="2.5" stroke-linejoin="round"/>
        <path d="M76 44 L88 20 L124 26 L114 44 Z" fill="#E7B984" stroke="#0C2B2A" stroke-width="2.5" stroke-linejoin="round"/>
        <rect x="70" y="44" width="12" height="24" fill="#0F766E"/>
        <path d="M58 78 q4 -6 8 0 M88 78 q4 -6 8 0" fill="none" stroke="#0C2B2A" stroke-width="3.5" stroke-linecap="round"/>
        <circle cx="52" cy="90" r="5" fill="#F28C82" opacity=".8"/><circle cx="102" cy="90" r="5" fill="#F28C82" opacity=".8"/>
        <path d="M68 88 q9 12 18 0 z" fill="#0C2B2A"/>
        <path d="M40 70 q-18 -6 -22 -28" fill="none" stroke="#4A2E1C" stroke-width="7" stroke-linecap="round"/><circle cx="18" cy="40" r="7" fill="#4A2E1C"/>
        <path d="M128 60 q16 -2 20 -22" fill="none" stroke="#4A2E1C" stroke-width="7" stroke-linecap="round"/><circle cx="148" cy="36" r="7" fill="#4A2E1C"/>
        <path d="M10 22l-6-6M26 12l1-8M146 18l4-7M156 30l7-2" stroke="#FFC93C" stroke-width="3" stroke-linecap="round"/>
    </svg>
    <h2 id="t3">Berhasil masuk!</h2>
    <p class="sub">Ruru lagi siapin dasbormu. Sebentar ya.</p>
    <div class="progress" aria-hidden="true"><i></i></div>
    <p class="hint"><a class="link" href="{{ $to }}">Langsung ke beranda</a></p>
</section>
@endsection

@push('scripts')
<script>
setTimeout(function () { window.location.replace(@json($to)); }, 2200);
</script>
@endpush
