{{--
    Shell panel admin RuangTitip: sidebar bergrup, topbar, dialog konfirmasi, toast.
    Halaman mengisi @section('title'), @section('content'), dan boleh @push('styles') / @push('scripts').
    Kelas UI didokumentasikan di resources/css/rt-admin.css.
--}}
@php
    use App\Models\Order;
    use App\Models\PackingOrder;
    use App\Models\PackingProduct;
    use App\Models\PrelovedSubmission;
    use App\Models\TitipanOrder;
    use App\Models\TitipanRequest;
    use Illuminate\Support\Facades\Route;
    use Illuminate\Support\Facades\Schema;

    // Hitung aman: tabel yang belum dimigrasi tidak boleh membuat panel error.
    $safe = function (callable $fn) {
        try { return (int) $fn(); } catch (\Throwable $e) { return 0; }
    };

    $cTitip = $safe(fn () => TitipanOrder::whereIn('status', ['menunggu_pembayaran', 'penjadwalan_penjemputan'])->count() + TitipanOrder::where('needs_admin_attention', true)->count());
    $cReq = fn (string $type) => $safe(fn () => Schema::hasTable('titipan_requests') ? TitipanRequest::where('type', $type)->whereIn('status', ['menunggu', 'diproses'])->count() : 0);
    $cJual = $safe(fn () => Schema::hasTable('preloved_submissions') ? PrelovedSubmission::whereIn('status', ['seleksi', 'periksa', 'sepakat'])->count() : 0);
    $cPre = $safe(fn () => Order::where('needs_admin_attention', true)->count());
    $cPack = $safe(fn () => PackingProduct::whereColumn('stock', '<=', 'low_threshold')->count() + PackingOrder::where('needs_admin_attention', true)->count());

    // [label, nama route, ikon, parameter route, badge, penanda aktif (pola routeIs / query)]
    $adminNav = [
        'Utama' => [
            ['Ringkasan', 'admin.dashboard', 'n-home', [], 0, ['admin.dashboard']],
        ],
        'Penitipan' => [
            ['Pesanan titip', 'admin.titip.index', 'n-receipt', [], $cTitip, ['admin.titip.*']],
            ['Pengambilan', 'admin.permintaan.index', 'n-hand', ['type' => 'ambil'], $cReq('ambil'), ['admin.permintaan.*'], 'ambil'],
            ['Pengiriman', 'admin.permintaan.index', 'n-truck', ['type' => 'kirim'], $cReq('kirim'), ['admin.permintaan.*'], 'kirim'],
            ['Perpanjangan', 'admin.permintaan.index', 'n-cal', ['type' => 'perpanjang'], $cReq('perpanjang'), ['admin.permintaan.*'], 'perpanjang'],
            ['Ruang & kapasitas', 'admin.ruang-titip', 'n-ware', [], 0, ['admin.ruang-titip']],
        ],
        'Toko Preloved' => [
            ['Pengajuan jual', 'admin.jual.index', 'n-coin', [], $cJual, ['admin.jual.*']],
            ['Katalog', 'admin.preloved', 'n-tag', [], 0, ['admin.preloved']],
            ['Pesanan preloved', 'admin.preloved.orders', 'n-bag', [], $cPre, ['admin.preloved.orders*']],
            ['Toko Packing', 'admin.packing.index', 'n-tape', [], $cPack, ['admin.packing.*']],
        ],
        'Keuangan' => [
            ['Tagihan & pembayaran', 'admin.tagihan.index', 'n-card', [], 0, ['admin.tagihan.*']],
            ['Laporan', 'admin.laporan', 'n-chart', [], 0, ['admin.laporan']],
        ],
        'Pengguna' => [
            ['Pelanggan', 'admin.accounts', 'n-user', [], 0, ['admin.accounts*']],
            ['Tim admin', 'admin.tim', 'n-team', [], 0, ['admin.tim']],
        ],
    ];

    $adminUser = auth()->user();
    $adminInitials = \Illuminate\Support\Str::of($adminUser->name ?? 'Admin')->trim()->explode(' ')->map(fn ($w) => mb_substr($w, 0, 1))->take(2)->implode('');
    $reqType = request()->query('type', 'ambil');
@endphp
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="color-scheme" content="light">
    <meta name="robots" content="noindex">
    <title>@yield('title', 'Ringkasan') &middot; Admin RuangTitip</title>
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('assets/logo/favicon-32.png') }}">
    <link rel="icon" type="image/png" sizes="48x48" href="{{ asset('assets/logo/favicon-48.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('assets/logo/apple-touch-icon.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap">
    @vite(['resources/css/rt-admin.css', 'resources/js/rt-admin.js'])
    @stack('styles')
</head>
<body class="adm">
@include('admin.partials.icons')

<div class="app">
    <aside class="side" id="adminSide" aria-label="Navigasi admin">
        <a class="brand" href="{{ route('admin.dashboard') }}" aria-label="RuangTitip Admin, ke ringkasan">
            <img class="brand-img" src="{{ asset('assets/logo/logo-horizontal.png') }}" alt="RuTip" width="112" height="44">
            <span class="role">ADMIN</span>
        </a>
        <button class="side-x" id="adminSideX" type="button" aria-label="Tutup menu"><svg class="ic" aria-hidden="true"><use href="#i-x"/></svg></button>

        @if (Route::has('admin.titip.index'))
            <form class="side-search" method="GET" action="{{ route('admin.titip.index') }}" role="search">
                <svg class="ic" aria-hidden="true"><use href="#n-search"/></svg>
                <input name="q" type="search" placeholder="Cari pesanan atau pelanggan" aria-label="Cari pesanan titip">
            </form>
        @endif

        @foreach ($adminNav as $group => $items)
            @php $visible = array_filter($items, fn ($i) => Route::has($i[1])); @endphp
            @if ($visible)
                <div class="ng">
                    <p>{{ $group }}</p>
                    <ul class="nav">
                        @foreach ($visible as $i)
                            @php
                                [$label, $name, $icon, $params, $count, $patterns] = $i;
                                $active = request()->routeIs(...$patterns);
                                if (isset($i[6])) { $active = $active && $reqType === $i[6]; }
                            @endphp
                            <li>
                                <a href="{{ route($name, $params) }}" @if ($active) aria-current="page" @endif>
                                    <svg class="ic" aria-hidden="true"><use href="#{{ $icon }}"/></svg>
                                    <span>{{ $label }}</span>
                                    @if ($count)<span class="cnt hot">{{ $count }}</span>@endif
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif
        @endforeach

        <div class="side-foot"><div class="env">Panel khusus tim RuangTitip. Semua perubahan tersimpan di database.</div></div>
    </aside>

    <div class="scrim" id="adminScrim" hidden></div>

    <div class="main">
        <header class="top">
            <button class="menu-t" id="adminMenuT" type="button" aria-label="Buka menu" aria-controls="adminSide" aria-expanded="false"><svg class="ic" aria-hidden="true"><use href="#i-menu"/></svg></button>
            <nav class="crumb" aria-label="Breadcrumb">
                <a href="{{ route('admin.dashboard') }}">Admin</a><span>/</span><b>@yield('title', 'Ringkasan')</b>
            </nav>
            @if (Route::has('admin.titip.index'))
                <form class="gsearch" method="GET" action="{{ route('admin.titip.index') }}" role="search">
                    <svg class="ic" aria-hidden="true"><use href="#n-search"/></svg>
                    <input name="q" type="search" placeholder="Cari kode pesanan atau pelanggan" aria-label="Cari pesanan titip">
                </form>
            @endif
            <details class="who">
                <summary class="who-btn" aria-label="Menu akun admin">
                    <span class="av">{{ $adminInitials ?: 'A' }}</span>
                    <span><b>{{ $adminUser->name ?? 'Admin' }}</b><small>Admin gudang</small></span>
                </summary>
                <div class="menu">
                    <a href="{{ route('admin.profile') }}">Profil admin</a>
                    <a href="{{ route('dashboard') }}">Lihat sisi pelanggan</a>
                    <hr>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" data-confirm="Keluar dari panel admin?">Keluar</button>
                    </form>
                </div>
            </details>
        </header>

        <main class="content" id="adminView" tabindex="-1">
            @yield('content')
        </main>
    </div>
</div>

{{-- Dialog konfirmasi bersama: dipicu atribut data-confirm pada form/tombol. --}}
<dialog class="dlg" id="aConfirm" aria-labelledby="aConfirmT">
    <div class="dh"><h3 id="aConfirmT">Konfirmasi</h3><button class="x" type="button" data-close aria-label="Tutup"><svg class="ic" aria-hidden="true"><use href="#i-x"/></svg></button></div>
    <div class="db"><p id="aConfirmP"></p></div>
    <div class="df"><button class="btn btn-ghost" type="button" data-close>Batal</button><button class="btn btn-primary" type="button" id="aConfirmOk">Lanjutkan</button></div>
</dialog>

<div class="toast" id="adminToast" role="status" aria-live="polite"
     @if (session('success') || session('save_toast')) data-msg="{{ session('success') ?? 'Perubahan tersimpan.' }}" @endif
     @if (session('error')) data-msg="{{ session('error') }}" data-type="err" @endif
     @if ($errors->any()) data-msg="{{ $errors->first() }}" data-type="err" @endif></div>

@stack('scripts')
</body>
</html>
