# RUTIP Landing Page — Konversi Figma (React/TSX) ke Laravel Blade

Konversi `LandingPage.tsx` (beserta 9 komponen section di
`src/app/components/`) menjadi struktur Blade yang siap dipakai di proyek
Laravel kamu (Laravel + Tailwind v4 + Vite + `mallardduck/blade-lucide-icons`).

## Struktur file

```
resources/
├── css/
│   └── app.css                 # entry Tailwind + token desain (--font-display, --font-body, @keyframes blink, .reveal)
├── js/
│   └── app.js                  # navbar scroll, mobile menu, typewriter hero, reveal-on-scroll, accordion FAQ, carousel testimoni
└── views/
    ├── layouts/
    │   └── app.blade.php       # <html> shell, load font Google + @vite
    └── landing/
        ├── index.blade.php     # @extends('layouts.app'), merangkai semua partial
        └── partials/
            ├── navbar.blade.php
            ├── hero.blade.php
            ├── problem.blade.php
            ├── solution.blade.php
            ├── how-it-works.blade.php
            ├── trust.blade.php
            ├── testimoni.blade.php
            ├── faq.blade.php
            ├── tentang-kami.blade.php
            └── footer.blade.php

routes/web.php                  # contoh route GET / -> landing.index
vite.config.js                  # plugin laravel + @tailwindcss/postcss
```

Salin folder `resources/views/landing`, `resources/css/app.css`, dan
`resources/js/app.js` ke proyek Laravel kamu. Sesuaikan `layouts/app.blade.php`
jika kamu sudah punya layout master sendiri (cukup pastikan `@vite(...)` dan
font Google ikut dimuat).

## Dependency yang dibutuhkan

```bash
composer require mallardduck/blade-lucide-icons
npm install -D @tailwindcss/postcss tailwindcss
```

`tailwindcss` v4 dipakai via `@import "tailwindcss";` di `app.css` (lihat
`postcss.config.mjs` / `vite.config.js`).

## Pemetaan komponen React → Blade

| Komponen Figma (.tsx)     | Partial Blade                          | Catatan |
|---------------------------|------------------------------------------|---------|
| `Navbar.tsx`               | `partials/navbar.blade.php`              | Scroll-blur & mobile toggle dipindah ke `app.js` (`#navbar`, `#navbar-mobile-toggle`) |
| `HeroSection.tsx`          | `partials/hero.blade.php`                | Efek typewriter (`useState`/`useEffect`) → vanilla JS di `app.js` (`#hero-line-1`, `#hero-line-2`) |
| `ProblemSection.tsx`       | `partials/problem.blade.php`             | 3 kartu pain-point + stat callout "93,5%" |
| `SolutionSection.tsx`      | `partials/solution.blade.php`            | 6 kartu layanan |
| `HowItWorksSection.tsx`    | `partials/how-it-works.blade.php`        | 3 langkah dengan connecting line |
| `TrustSection.tsx`         | `partials/trust.blade.php`               | 4 kartu trust + badge "100% Terjamin" |
| `TestimoniSection.tsx`     | `partials/testimoni.blade.php`           | Carousel 6 testimoni / 3 per halaman → `app.js` (`#testimoni-carousel`) |
| `FAQSection.tsx`           | `partials/faq.blade.php`                 | Accordion → `app.js` (`.faq-item`, `.faq-trigger`, `.faq-panel`) |
| `TentangKamiSection.tsx`   | `partials/tentang-kami.blade.php`        | Visi/Misi + timeline 3 milestone |
| `Footer.tsx`                | `partials/footer.blade.php`              | — |

`CTABanner.tsx` ada di source export tapi **tidak** dipakai di
`LandingPage.tsx`, sehingga tidak diikutkan. Tinggal beri tahu jika ingin
ditambahkan sebagai partial terpisah.

## Penggantian library

| React (Figma export)              | Blade / Vanilla |
|------------------------------------|------------------|
| `motion/react` (`motion.div`, `whileInView`, `AnimatePresence`) | Class `.reveal` + `IntersectionObserver` di `app.js` (fade + slide-up sekali saat masuk viewport) |
| `lucide-react` (`<Package />`, dst.) | `<x-lucide-package />` dari `mallardduck/blade-lucide-icons`. Nama ikon di-kebab-case-kan (`ShoppingBag` → `shopping-bag`, `AlertTriangle` → `triangle-alert`, `RefreshCw` → `refresh-cw`, `ImageIcon` → `image`, `Music2` → `music-2`). |
| `react-router` `<Link to="/login">` | `<a href="{{ route('login') }}">` — pastikan route bernama `login` & `register` tersedia |

## Yang perlu disesuaikan di proyek kamu

1. **Route `login` & `register`** — partial `navbar`, `hero`, `solution`, dan
   `how-it-works` memanggil `route('login')` / `route('register')`. Pastikan
   kedua named route ini ada (mis. dari Laravel Breeze/Fortify), atau ganti
   sementara dengan URL statis (`/login`, `/register`).
2. **Font** — `layouts/app.blade.php` memuat Plus Jakarta Sans & Inter dari
   Google Fonts CDN (sama seperti `fonts.css` di export Figma). Jika proyek
   sudah punya pemuatan font sendiri, hapus baris `<link>` yang duplikat.
3. **Ikon dinamis** — beberapa partial (`problem`, `solution`,
   `how-it-works`, `trust`) memakai `<x-dynamic-component :component="'lucide-' . $icon">`
   agar nama ikon bisa di-loop dari array PHP. Pastikan paket
   `blade-lucide-icons` ter-install supaya komponen `x-lucide-*` ter-resolve.
4. **CTA "Titip Sekarang"** — beberapa CTA (`hero`, `solution`,
   `how-it-works`) diarahkan ke `route('register')` (anggapan: alur "titip
   barang" mengharuskan user login/daftar dulu). Sesuaikan target href bila
   alur bisnis berbeda (misalnya langsung ke form penitipan).
