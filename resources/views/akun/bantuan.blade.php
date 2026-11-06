@extends('akun.layout')

@section('title', 'Bantuan')
@section('key', 'bantuan')
@section('sub', 'Jawaban cepat untuk pertanyaan yang paling sering muncul.')

@section('body')
<section class="card stack">
    <h3>Pertanyaan umum</h3>
    @foreach ($faq as [$q, $a])
        <details class="faq"><summary>{{ $q }}</summary><p>{{ $a }}</p></details>
    @endforeach
</section>

<section class="card">
    <h3>Hubungi tim Ruru</h3>
    <p class="help">Balasan di jam kerja, Senin sampai Sabtu.</p>
    <div style="display:flex;gap:8px;flex-wrap:wrap" class="mt">
        <a class="btn btn-sun" href="https://wa.me/6285121091134" target="_blank" rel="noopener">Chat WhatsApp</a>
        <a class="btn btn-ghost" href="mailto:ruangtitipmu@gmail.com"><svg class="ic" aria-hidden="true"><use href="#i-mail"/></svg>ruangtitipmu@gmail.com</a>
    </div>
</section>

<section class="card">
    <h3>Dokumen</h3>
    <ul class="list mt">
        <li class="li"><div><b>Syarat dan Ketentuan</b><p>Aturan penitipan, blind box, pembayaran, dan Toko Preloved.</p></div><div class="acts"><a class="btn btn-link" href="{{ route('legal.terms') }}">Baca</a></div></li>
        <li class="li"><div><b>Kebijakan Privasi</b><p>Data apa yang kami simpan dan cara kami melindunginya.</p></div><div class="acts"><a class="btn btn-link" href="{{ route('legal.privacy') }}">Baca</a></div></li>
    </ul>
</section>
@endsection
