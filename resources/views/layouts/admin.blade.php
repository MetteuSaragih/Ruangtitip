<!doctype html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta name="robots" content="noindex">
<title>@yield('title', 'Dashboard') &middot; Admin RuangTitip</title>
<link rel="icon" href="{{ asset('assets/favicon.svg') }}" type="image/svg+xml">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,700;12..96,800&family=Figtree:wght@400;500;600;700;800&display=swap">
<link rel="stylesheet" href="{{ asset('assets/admin.css') }}">
<script src="{{ asset('assets/admin-icons.js') }}"></script>
<script src="{{ asset('assets/admin.js') }}"></script>
@stack('styles')
</head>
<body>
@php
    $navItems = [
        ['label' => 'Dashboard', 'route' => 'admin.dashboard', 'href' => route('admin.dashboard'), 'icon' => 'home'],
        ['label' => 'Ruang Titip', 'route' => 'admin.ruang-titip', 'href' => route('admin.ruang-titip'), 'icon' => 'warehouse',
            'badge' => \App\Models\TitipanOrder::whereIn('status', ['menunggu_pembayaran', 'penjadwalan_penjemputan'])->count()
                + \App\Models\TitipanOrder::where('needs_admin_attention', true)->count()],
        ['label' => 'Toko Preloved', 'route' => 'admin.preloved', 'href' => route('admin.preloved'), 'icon' => 'bag',
            'badge' => \App\Models\Order::where('needs_admin_attention', true)->count()],
        ['label' => 'Toko Packing', 'route' => 'admin.packing.index', 'href' => route('admin.packing.index'), 'icon' => 'box',
            'badge' => \App\Models\PackingProduct::whereColumn('stock', '<=', 'low_threshold')->count()
                + \App\Models\PackingOrder::where('needs_admin_attention', true)->count()],
        ['label' => 'Manajemen Akun', 'route' => 'admin.accounts', 'href' => route('admin.accounts'), 'icon' => 'users'],
    ];
    $adminUser = Auth::user();
    $adminInitials = \Illuminate\Support\Str::of($adminUser->name ?? 'Admin')->trim()->explode(' ')->map(fn($w) => mb_substr($w, 0, 1))->take(2)->implode('');
@endphp

<aside class="sb" aria-label="Navigasi admin">
  <div class="sb-head">
    <a class="sb-logo" href="{{ route('admin.dashboard') }}" aria-label="RuangTitip Admin, ke dashboard">
      <img class="full" src="{{ asset('assets/logo-ruangtitip-putih.svg') }}" alt="RuangTitip">
      <svg class="mark" viewBox="0 0 198 198" xmlns="http://www.w3.org/2000/svg"><g transform="translate(24.00 24.00) scale(1.5000)"><polyline points="10,50 50,13 90,50" fill="none" stroke="#F5F1E8" stroke-width="9" stroke-linecap="round" stroke-linejoin="round"/><rect x="22" y="46" width="56" height="42" rx="5" fill="#C9A27A"/><rect x="44" y="46" width="12" height="21" fill="#B4531D"/><rect x="22" y="46" width="56" height="42" rx="5" fill="none" stroke="#F5F1E8" stroke-width="6"/></g></svg>
    </a>
    <button class="sb-toggle" id="sbToggle" type="button" aria-expanded="true" aria-label="Ciutkan sidebar"><svg class="ico sm" viewBox="0 0 24 24" aria-hidden="true"><path d="m15 18-6-6 6-6"/></svg></button>
  </div>
  <p class="sb-label">Panel admin</p>
  <nav class="sb-nav">
    @foreach ($navItems as $item)
      @php $active = request()->routeIs($item['route']); @endphp
      <a class="sb-link {{ $active ? 'active' : '' }}" @if($active) aria-current="page" @endif href="{{ $item['href'] }}" data-tip="{{ $item['label'] }}">
        {!! \App\Support\Icons::svg($item['icon']) !!}
        <span class="txt">{{ $item['label'] }}</span>
        @if (!empty($item['badge']))
          <span class="badge" aria-label="{{ $item['badge'] }} perlu tindakan">{{ $item['badge'] }}</span>
        @endif
      </a>
    @endforeach
  </nav>
  <div class="sb-foot">
    <a class="sb-user {{ request()->routeIs('admin.profile') ? 'active' : '' }}" href="{{ route('admin.profile') }}" data-tip="Profil admin">
      <span class="avatar">{{ $adminInitials ?: 'A' }}</span>
      <span class="txt"><b>{{ $adminUser->name ?? 'Admin RuangTitip' }}</b><small>Admin gudang</small></span>
    </a>
  </div>
</aside>
<div class="scrim" id="scrim"></div>

<div class="main">
  <header class="top">
    <button class="icon-btn menu" id="menuBtn" type="button" aria-label="Buka menu"><svg class="ico" viewBox="0 0 24 24" aria-hidden="true"><path d="M4 6h16M4 12h16M4 18h16"/></svg></button>
    <nav class="crumb" aria-label="Breadcrumb"><a href="{{ route('admin.dashboard') }}">Admin</a><span class="sep">/</span><b>@yield('title', 'Dashboard')</b></nav>
    <div class="top-right">
      <button class="icon-btn" type="button" id="bellBtn" aria-label="Notifikasi" aria-expanded="false" aria-controls="notif"><svg class="ico" viewBox="0 0 24 24" aria-hidden="true"><path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"/><path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"/></svg>
        @php $hasNotif = collect($navItems)->sum('badge') > 0; @endphp
        @if ($hasNotif)<span class="dot"></span>@endif
      </button>
      <a class="me" href="{{ route('admin.profile') }}"><span class="avatar">{{ $adminInitials ?: 'A' }}</span><span class="txt"><b>{{ $adminUser->name ?? 'Admin' }}</b><small>Admin gudang</small></span></a>
    </div>
    <div class="card notif" id="notif" hidden>
      <div class="card-h"><h2>Perlu tindakan</h2></div>
      @php $anyTask = false; @endphp
      @foreach ($navItems as $item)
        @if (!empty($item['badge']))
          @php $anyTask = true; @endphp
          <a href="{{ $item['href'] }}" class="n-item">
            <span class="stat-ico o">{!! \App\Support\Icons::svg($item['icon'], 'sm') !!}</span>
            <span><b>{{ $item['badge'] }} item di {{ $item['label'] }}</b><small>Butuh tindakan admin</small></span>
          </a>
        @endif
      @endforeach
      @if (!$anyTask)
        <p style="padding:18px;color:var(--muted);font-size:13px">Tidak ada tugas yang menumpuk. Mantap!</p>
      @endif
    </div>
  </header>
  <main class="content" id="main">
    @yield('content')
  </main>
</div>

@php
    $flashMap = ['success' => 'success', 'error' => 'error', 'save_toast' => 'success'];
@endphp
@foreach ($flashMap as $sessionKey => $toastType)
  @if (session($sessionKey))
    <div data-flash="{{ $toastType }}" data-flash-msg="{{ $sessionKey === 'save_toast' ? 'Stok ' . session($sessionKey) . ' diperbarui' : session($sessionKey) }}" hidden></div>
  @endif
@endforeach
@if ($errors->any())
  <div data-flash="error" data-flash-msg="{{ $errors->first() }}" hidden></div>
@endif

<script>
(function () {
  var b = document.getElementById('bellBtn'), n = document.getElementById('notif');
  if (b && n) {
    b.addEventListener('click', function (e) { e.stopPropagation(); n.hidden = !n.hidden; b.setAttribute('aria-expanded', String(!n.hidden)); });
    document.addEventListener('click', function (e) { if (!n.hidden && !n.contains(e.target) && e.target !== b) { n.hidden = true; b.setAttribute('aria-expanded', 'false'); } });
  }
})();
</script>
@stack('scripts')
</body>
</html>
