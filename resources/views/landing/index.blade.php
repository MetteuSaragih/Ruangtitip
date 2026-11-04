<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="color-scheme" content="dark">
    <title>RuangTitip | Titip barang mahasiswa di Malang</title>
    <meta name="description" content="Lepas kosmu selama libur, KKN, atau magang. Titip barangnya ke RuangTitip mulai Rp1.000 per kardus per hari.">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('assets/logo/favicon-32.png') }}">
    <link rel="icon" type="image/png" sizes="48x48" href="{{ asset('assets/logo/favicon-48.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('assets/logo/apple-touch-icon.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wdth,wght@12..96,75..100,400..800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap">
    @vite(['resources/css/rt-landing.css', 'resources/js/rt-landing.js'])
</head>
<body class="p-landing">
@include('landing.partials.symbols')

@include('landing.partials.navbar')

<main id="top" tabindex="-1">
    @include('landing.partials.hero')
    @include('landing.partials.solution')
    @include('landing.partials.how-it-works')
    @include('landing.partials.harga')
    @include('landing.partials.preloved')
    @include('landing.partials.testimoni')
    @include('landing.partials.faq')
</main>

@include('landing.partials.footer')
</body>
</html>
