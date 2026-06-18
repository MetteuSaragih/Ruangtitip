<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RUTIP — Titip Barangmu, Simpan Uangmu</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    
    <!-- Memanggil Tailwind CSS & JS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        :root { --font-display:'Plus Jakarta Sans',sans-serif; --font-body:'Inter',sans-serif; }
        body { font-family: var(--font-body); }
        .font-display { font-family: var(--font-display); }
    </style>
</head>
<body>

    <div class="min-h-screen overflow-x-hidden" style="background: #0c0618;">
        {{-- Navbar khusus Landing Page --}}
        @include('landing.partials.navbar')
        
        {{-- Konten Landing Page --}}
        @include('landing.partials.hero')
        @include('landing.partials.problem')
        @include('landing.partials.solution')
        @include('landing.partials.how-it-works')
        @include('landing.partials.trust')
        @include('landing.partials.testimoni')
        @include('landing.partials.faq')
        @include('landing.partials.tentang-kami')
        @include('landing.partials.footer')
    </div>

</body>
</html>