@extends('layouts.admin')

@section('title', 'Toko Packing | Admin RUTIP')

@section('content')
<style>
    [x-cloak] { display: none !important; }
    .is-hidden { display: none !important; }
    input[type=number]::-webkit-inner-spin-button, 
    input[type=number]::-webkit-outer-spin-button { -webkit-appearance: none; margin: 0; }
</style>

<div id="packing-admin" data-active-tab="{{ $tab }}" class="p-6 max-w-[1280px] mx-auto min-h-screen">
    @if($errors->any())
    <div class="mb-4 px-4 py-3 rounded-2xl text-sm font-medium"
         style="background:rgba(239,68,68,0.12);border:1px solid rgba(239,68,68,0.3);color:#f87171;">
        {{ $errors->first() }}
    </div>
    @endif

    {{-- Page title --}}
    <div class="mb-6">
        <h1 class="text-xl font-extrabold text-white font-display">Toko Packing</h1>
        <p class="text-xs mt-0.5" style="color: rgba(255,255,255,0.38);">
            Manajemen stok perlengkapan logistik dan pemantauan penjualan
        </p>
    </div>

    {{-- ── Scorecards ── --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
        {{-- Card 1: Peringatan Stok --}}
        <div class="rounded-2xl p-5 flex flex-col gap-3 relative overflow-hidden"
             style="background: rgba(239,68,68,0.07); border: 1px solid rgba(239,68,68,0.3); box-shadow: 0 4px 24px rgba(239,68,68,0.12);">
            <div class="absolute -top-6 -right-6 w-24 h-24 rounded-full blur-3xl opacity-20" style="background: radial-gradient(circle,#ef4444,transparent);"></div>
            <div class="flex items-center justify-between">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background: rgba(239,68,68,0.18);">
                    <x-lucide-alert-triangle class="w-5 h-5" style="color: #f87171;" />
                </div>
                @if($lowItems->count() > 0)
                    <span class="text-[10px] font-bold px-2 py-0.5 rounded-full animate-pulse" style="background: rgba(239,68,68,0.18); color: #f87171;">PERLU RESTOCK</span>
                @endif
            </div>
            <div>
                <p class="text-xs font-medium mb-1" style="color: rgba(248,113,113,0.7);">Peringatan Stok Menipis</p>
                <p class="text-2xl font-extrabold font-display" style="color: #fca5a5;">{{ $lowItems->count() }} Produk</p>
                <div class="mt-2 space-y-1">
                    @forelse($lowItems->take(3) as $low)
                        <div class="flex items-center gap-1.5 text-xs" style="color: rgba(252,165,165,0.85);">
                            <span class="w-1.5 h-1.5 rounded-full shrink-0 bg-red-400"></span>
                            {{ $low->name }} sisa <span class="font-bold text-red-300">{{ $low->stock }} {{ $low->unit }}</span>!
                        </div>
                    @empty
                        <p class="text-xs mt-1" style="color: rgba(255,255,255,0.38);">Semua stok aman</p>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Card 2: Terjual --}}
        <div class="rounded-2xl p-5 flex flex-col gap-3" style="background: rgba(255,255,255,0.035); border: 1px solid rgba(255,255,255,0.08); box-shadow: 0 4px 24px rgba(0,0,0,0.25);">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background: rgba(52,211,153,0.15);">
                <x-lucide-trending-up class="w-5 h-5" style="color: #34d399;" />
            </div>
            <div>
                <p class="text-xs font-medium mb-1" style="color: rgba(255,255,255,0.42);">Total Terjual (Bulan ini)</p>
                <p class="text-2xl font-extrabold text-white font-display">{{ $totalSold }} Item</p>
                <p class="text-xs mt-1" style="color: rgba(255,255,255,0.38);">Kardus, lakban, dan perlengkapan</p>
            </div>
        </div>

        {{-- Card 3: Pendapatan --}}
        <div class="rounded-2xl p-5 flex flex-col gap-3" style="background: rgba(255,255,255,0.035); border: 1px solid rgba(255,255,255,0.08); box-shadow: 0 4px 24px rgba(0,0,0,0.25);">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background: rgba(245,158,11,0.15);">
                <x-lucide-wallet class="w-5 h-5" style="color: #fbbf24;" />
            </div>
            <div>
                <p class="text-xs font-medium mb-1" style="color: rgba(255,255,255,0.42);">Pendapatan Khusus Packing</p>
                <p class="text-2xl font-extrabold text-white font-display">Rp {{ number_format($revenue, 0, ',', '.') }}</p>
                <span class="inline-block mt-1 text-xs font-semibold px-2 py-0.5 rounded-full" style="background: rgba(52,211,153,0.12); color: #34d399;">
                    Dari 0 transaksi selesai
                </span>
            </div>
        </div>
    </div>

    {{-- ── Tab Switcher ── --}}
    <div class="flex gap-1 p-1 rounded-2xl mb-6" style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.08);">
        <button type="button" data-tab-button="inventaris" class="flex-1 py-3 rounded-xl text-sm font-bold transition-all">
            Manajemen Inventaris
            <p class="text-[10px] font-normal mt-0.5">Stock opname & master produk</p>
        </button>
        <button type="button" data-tab-button="pesanan" class="flex-1 py-3 rounded-xl text-sm font-bold transition-all">
            Pesanan Packing
            <p class="text-[10px] font-normal mt-0.5">Transaksi & tindak lanjut logistik</p>
        </button>
    </div>

    {{-- ── TAB 1: INVENTARIS ── --}}
    <div data-tab-panel="inventaris" class="rounded-2xl overflow-hidden {{ $tab !== 'inventaris' ? 'is-hidden' : '' }}" style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.08);">
        
        {{-- Table Header (SELALU TAMPIL AGAR BISA TAMBAH PRODUK) --}}
        <div class="px-6 py-4 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4" style="border-bottom: 1px solid rgba(255,255,255,0.06);">
            <div class="min-w-0">
                <h3 class="text-sm font-bold text-white">Master Inventaris Packing</h3>
                <p class="text-[10px] mt-0.5" style="color: {{ $lowItems->count() > 0 ? '#f87171' : 'rgba(255,255,255,0.35)' }};">
                    {{ $items->count() }} produk terdaftar
                    @if($lowItems->count() > 0) · {{ $lowItems->count() }} produk perlu restock segera @endif
                </p>
            </div>
            <div class="flex flex-wrap items-center gap-2 lg:justify-end">
                {{-- Category Filters --}}
                <div class="flex flex-wrap items-center gap-1">
                    @php $cats = ['Semua', 'Kardus', 'Pelindung', 'Perekat', 'Aksesoris']; @endphp
                    @foreach($cats as $c)
                        <button type="button" data-category-filter="{{ $c }}" class="px-3 py-1.5 rounded-xl text-[11px] font-semibold transition-all">
                            {{ $c }}
                        </button>
                    @endforeach
                </div>
                {{-- Tombol Tambah Produk (CRUD) --}}
                <button type="button" data-open-create class="flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs font-bold text-white transition-all hover:scale-105 shrink-0" style="background: linear-gradient(135deg,#7c3aed,#6366f1); box-shadow: 0 4px 16px rgba(124,58,237,0.4);">
                    <x-lucide-plus class="w-3.5 h-3.5" /> Tambah Produk
                </button>
            </div>
        </div>

        {{-- Table Body --}}
        <div class="overflow-x-auto">
            <table class="w-full text-xs min-w-[780px]">
                <thead>
                    <tr style="border-bottom: 1px solid rgba(255,255,255,0.06);">
                        <th class="text-left px-5 py-3.5 font-semibold whitespace-nowrap" style="color: rgba(255,255,255,0.3);">Nama Produk</th>
                        <th class="text-left px-5 py-3.5 font-semibold whitespace-nowrap" style="color: rgba(255,255,255,0.3);">Kategori</th>
                        <th class="text-left px-5 py-3.5 font-semibold whitespace-nowrap" style="color: rgba(255,255,255,0.3);">Harga / Satuan</th>
                        <th class="text-left px-5 py-3.5 font-semibold whitespace-nowrap" style="color: rgba(255,255,255,0.3);">Stok Sistem</th>
                        <th class="text-left px-5 py-3.5 font-semibold whitespace-nowrap" style="color: rgba(255,255,255,0.3);">Update Stok Fisik</th>
                        <th class="text-left px-5 py-3.5 font-semibold whitespace-nowrap" style="color: rgba(255,255,255,0.3);">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($items as $item)
                        @php $isLow = $item->stock <= $item->low_threshold; @endphp
                        <tr data-category="{{ $item->category }}" class="transition-colors group" 
                            style="border-bottom: 1px solid rgba(255,255,255,0.04);"
                            onmouseover="this.style.background='{{ $isLow ? "rgba(239,68,68,0.04)" : "rgba(124,58,237,0.05)" }}'"
                            onmouseout="this.style.background='transparent'">
                            
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-2.5">
                                    @if($isLow) <div class="w-1.5 h-1.5 rounded-full shrink-0 animate-pulse bg-red-500"></div> @endif
                                    <div class="w-12 h-10 rounded-lg overflow-hidden flex items-center justify-center shrink-0" style="background:rgba(124,58,237,0.08);border:1px solid rgba(124,58,237,0.15);">
                                        @if($item->primary_image)
                                            <img src="{{ asset('storage/'.$item->primary_image) }}" alt="{{ $item->name }}" class="w-full h-full object-cover">
                                        @else
                                            <x-lucide-image class="w-4 h-4" style="color:rgba(167,139,250,0.45)" />
                                        @endif
                                    </div>
                                    <p class="font-semibold text-white">{{ $item->name }}</p>
                                </div>
                            </td>
                            <td class="px-5 py-4">
                                <span class="px-2 py-0.5 rounded-lg text-[10px] font-semibold" style="background: rgba(124,58,237,0.1); color: rgba(167,139,250,0.8);">
                                    {{ $item->category }}
                                </span>
                            </td>
                            <td class="px-5 py-4 whitespace-nowrap font-semibold text-white">
                                Rp {{ number_format($item->price, 0, ',', '.') }}
                                <span class="text-[10px] font-normal ml-1" style="color: rgba(255,255,255,0.35);">/{{ $item->unit }}</span>
                            </td>
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-2">
                                    <span class="text-sm font-bold {{ $isLow ? 'text-red-400' : 'text-white' }}">{{ $item->stock }}</span>
                                    <span class="text-[10px]" style="color: rgba(255,255,255,0.3);">{{ $item->unit }}</span>
                                    @if($isLow)
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold" style="background: rgba(239,68,68,0.15); color: #f87171;">
                                            <x-lucide-alert-triangle class="w-2.5 h-2.5" /> Menipis
                                        </span>
                                    @endif
                                </div>
                            </td>
                            
                            {{-- Quick Stock (Replikasi UI React) --}}
                            <td class="px-5 py-4">
                                <div data-stock-control data-original="{{ $item->stock }}" class="flex items-center gap-2">
                                    <form action="{{ route('admin.packing.update', $item->id) }}" method="POST" class="flex items-center gap-2">
                                        @csrf @method('PUT')
                                        <input type="hidden" name="quick_stock" value="1">
                                        <div class="flex items-center rounded-xl overflow-hidden" style="border: 1.5px solid rgba(255,255,255,0.1); background: rgba(255,255,255,0.04);">
                                            <button type="button" data-stock-minus class="w-8 h-8 flex items-center justify-center transition-colors hover:bg-white/10 text-white/50" style="border-right: 1px solid rgba(255,255,255,0.08);">
                                                <x-lucide-minus class="w-3.5 h-3.5" />
                                            </button>
                                            <input type="number" name="stock" value="{{ $item->stock }}" data-stock-input class="w-16 text-center text-sm font-bold outline-none bg-transparent text-white" />
                                            <button type="button" data-stock-plus class="w-8 h-8 flex items-center justify-center transition-colors hover:bg-white/10 text-white/50" style="border-left: 1px solid rgba(255,255,255,0.08);">
                                                <x-lucide-plus class="w-3.5 h-3.5" />
                                            </button>
                                        </div>
                                        <span class="text-[10px] text-white/30 hidden sm:inline">{{ $item->unit }}</span>
                                        <button type="submit" data-stock-save class="is-hidden flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-[11px] font-bold transition-all hover:scale-105" style="background: rgba(124,58,237,0.2); border: 1px solid rgba(124,58,237,0.4); color: #c4b5fd;">
                                            <x-lucide-save class="w-3 h-3" /> Simpan
                                        </button>
                                    </form>
                                </div>
                            </td>

                            {{-- Actions --}}
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-1.5">
                                    <button type="button" data-open-edit data-item="{{ e($item->toJson()) }}" class="flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg text-[11px] font-semibold transition-all hover:scale-105" style="background: rgba(99,102,241,0.13); border: 1px solid rgba(99,102,241,0.28); color: #818cf8;">
                                        <x-lucide-edit-2 class="w-3.5 h-3.5" /> <span class="hidden xl:inline">Edit</span>
                                    </button>
                                    <button type="button" data-open-delete data-id="{{ $item->id }}" data-name="{{ e($item->name) }}" class="flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg text-[11px] font-semibold transition-all hover:scale-105" style="background: rgba(239,68,68,0.1); border: 1px solid rgba(239,68,68,0.25); color: #f87171;">
                                        <x-lucide-trash-2 class="w-3.5 h-3.5" /> <span class="hidden xl:inline">Hapus</span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-16 text-center">
                                <div class="w-14 h-14 rounded-2xl flex items-center justify-center mx-auto mb-4" style="background: rgba(239,68,68,0.1); border: 1px solid rgba(239,68,68,0.25);">
                                    <x-lucide-alert-triangle class="w-6 h-6" style="color: #f87171;" />
                                </div>
                                <h3 class="text-base font-extrabold text-white font-display">0 Produk</h3>
                                <p class="text-xs mt-1" style="color: rgba(255,255,255,0.4);">Belum ada produk. Silakan klik "Tambah Produk" di atas.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-5 py-3 flex items-center justify-between" style="border-top: 1px solid rgba(255,255,255,0.05);">
            <p class="text-[10px]" style="color: rgba(255,255,255,0.28);">{{ $items->count() }} produk ditampilkan</p>
            <button class="flex items-center gap-1 text-[10px] font-semibold hover:text-white transition-colors" style="color: rgba(255,255,255,0.35);">
                Ekspor CSV <x-lucide-chevron-right class="w-3 h-3" />
            </button>
        </div>
    </div>

    {{-- ── TAB 2: PESANAN ── --}}
    <div data-tab-panel="pesanan" class="rounded-2xl overflow-hidden {{ $tab !== 'pesanan' ? 'is-hidden' : '' }}" style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.08);">
        <div class="px-6 py-4 flex items-start justify-between gap-4" style="border-bottom: 1px solid rgba(255,255,255,0.06);">
            <div>
                <h3 class="text-sm font-bold text-white">Transaksi Pesanan Packing</h3>
                <p class="text-[10px] mt-0.5" style="color: rgba(255,255,255,0.35);">Belum ada transaksi</p>
            </div>
        </div>
        <div class="py-20 text-center">
            <x-lucide-package class="w-10 h-10 mx-auto mb-3" style="color: rgba(255,255,255,0.2);" />
            <p class="text-sm font-semibold text-white">Tidak ada pesanan masuk</p>
            <p class="text-xs mt-1" style="color: rgba(255,255,255,0.4);">Pesanan dari pelanggan akan muncul di sini.</p>
        </div>
    </div>

    {{-- ── MODAL FORM (CREATE/EDIT) ── --}}
    <div id="packing-form-modal" class="is-hidden fixed inset-0 z-50 flex items-center justify-center p-6" style="background: rgba(0,0,0,0.78); backdrop-filter: blur(6px);">
        <div data-modal-card class="w-full max-w-md rounded-3xl overflow-hidden" style="background: rgba(12,6,24,0.99); border: 1px solid rgba(139,92,246,0.25); box-shadow: 0 24px 80px rgba(0,0,0,0.75);">
            <form id="packing-product-form" action="{{ route('admin.packing.store') }}" method="POST" enctype="multipart/form-data" data-store-url="{{ route('admin.packing.store') }}" data-base-url="{{ url('/admin/toko-packing') }}">
                @csrf
                <input id="packing-form-method" type="hidden" name="_method" value="PUT" disabled>

                <div class="px-7 py-5 flex items-center justify-between" style="border-bottom: 1px solid rgba(255,255,255,0.07); background: rgba(124,58,237,0.06);">
                    <div>
                        <h2 id="packing-form-title" class="text-base font-extrabold text-white font-display">Tambah Produk Baru</h2>
                        <p id="packing-form-subtitle" class="text-xs mt-0.5" style="color: rgba(255,255,255,0.38);">Produk baru akan muncul di katalog packing</p>
                    </div>
                    <button type="button" data-close-form class="w-9 h-9 rounded-xl flex items-center justify-center hover:bg-white/10 text-white/40 transition-colors"><x-lucide-x class="w-5 h-5" /></button>
                </div>

                <div class="px-7 py-6 space-y-5">
                    <div>
                        <label class="block text-xs font-bold mb-2 text-white/55">Nama Produk <span class="text-red-400">*</span></label>
                        <input name="name" required placeholder="Contoh: Kardus Ukuran M" class="w-full px-4 py-3 rounded-xl text-sm text-white placeholder:text-white/20 outline-none transition-colors focus:border-violet-500" style="background: rgba(255,255,255,0.06); border: 1.5px solid rgba(255,255,255,0.1);" />
                    </div>
                    <div>
                        <label class="block text-xs font-bold mb-2 text-white/55">Foto Produk</label>
                        <label for="packing-images-input" class="rt-img-drop">
                            <div class="rt-img-drop-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-white">Klik untuk pilih / tambah foto</p>
                                <p class="text-[10px]" style="color:rgba(255,255,255,0.35);">Bisa diklik berkali-kali untuk menambah foto satu per satu.</p>
                            </div>
                            <input id="packing-images-input" type="file" name="images[]" accept="image/*" multiple data-existing-count="0" class="sr-only" />
                        </label>
                        <div id="packing-images-preview" class="rt-img-pick-grid hidden"></div>
                        <p id="packing-images-label" class="text-[10px] mt-1.5" style="color:rgba(255,255,255,0.35);">Maksimal 10 foto asli per produk.</p>
                        @error('images')
                            <p class="text-[10px] mt-1.5 text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold mb-2 text-white/55">Kategori</label>
                            <select name="category" class="w-full px-4 py-3 rounded-xl text-sm text-white outline-none appearance-none" style="background: rgba(255,255,255,0.06); border: 1.5px solid rgba(255,255,255,0.1);">
                                <option value="Kardus" style="background: #0f0720">Kardus</option>
                                <option value="Pelindung" style="background: #0f0720">Pelindung</option>
                                <option value="Perekat" style="background: #0f0720">Perekat</option>
                                <option value="Aksesoris" style="background: #0f0720">Aksesoris</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold mb-2 text-white/55">Satuan</label>
                            <select name="unit" class="w-full px-4 py-3 rounded-xl text-sm text-white outline-none appearance-none" style="background: rgba(255,255,255,0.06); border: 1.5px solid rgba(255,255,255,0.1);">
                                <option value="pcs" style="background: #0f0720">pcs</option>
                                <option value="roll" style="background: #0f0720">roll</option>
                                <option value="meter" style="background: #0f0720">meter</option>
                                <option value="lembar" style="background: #0f0720">lembar</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-bold mb-2 text-white/55">Harga Jual <span class="text-red-400">*</span></label>
                        <div class="flex">
                            <div class="flex items-center px-3.5 rounded-l-xl text-sm font-semibold shrink-0" style="background: rgba(255,255,255,0.06); border: 1.5px solid rgba(255,255,255,0.1); border-right: none; color: rgba(255,255,255,0.45);">Rp</div>
                            <input type="number" name="price" required class="flex-1 px-4 py-3 rounded-r-xl text-sm text-white outline-none focus:border-violet-500" style="background: rgba(255,255,255,0.06); border: 1.5px solid rgba(255,255,255,0.1);" />
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold mb-2 text-white/55">Stok Awal</label>
                            <input type="number" name="stock" min="0" class="w-full px-4 py-3 rounded-xl text-sm text-white outline-none focus:border-violet-500" style="background: rgba(255,255,255,0.06); border: 1.5px solid rgba(255,255,255,0.1);" />
                        </div>
                        <div>
                            <label class="block text-xs font-bold mb-2 text-white/55">Ambang Peringatan</label>
                            <input type="number" name="low_threshold" min="1" class="w-full px-4 py-3 rounded-xl text-sm text-white outline-none focus:border-violet-500" style="background: rgba(255,255,255,0.06); border: 1.5px solid rgba(255,255,255,0.1);" />
                        </div>
                    </div>
                </div>

                <div class="px-7 py-5 flex gap-3" style="border-top: 1px solid rgba(255,255,255,0.07);">
                    <button type="button" data-close-form class="flex-1 py-3.5 rounded-2xl text-sm font-semibold hover:bg-white/5 transition-colors" style="border: 1.5px solid rgba(255,255,255,0.14); color: rgba(255,255,255,0.7);">Batal</button>
                    <button type="submit" class="flex-[2] py-3.5 rounded-2xl text-sm font-bold text-white flex items-center justify-center gap-2 hover:scale-[1.02] transition-transform" style="background: linear-gradient(135deg,#7c3aed,#6366f1); box-shadow: 0 6px 20px rgba(124,58,237,0.4);">
                        <x-lucide-check class="w-4 h-4" /> <span id="packing-form-submit-label">Tambah Produk</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ── MODAL DELETE ── --}}
    <div id="packing-delete-modal" class="is-hidden fixed inset-0 z-50 flex items-center justify-center p-6" style="background: rgba(0,0,0,0.75); backdrop-filter: blur(6px);">
        <div data-modal-card class="w-full max-w-sm rounded-3xl overflow-hidden" style="background: rgba(15,7,32,0.99); border: 1px solid rgba(239,68,68,0.22); box-shadow: 0 24px 64px rgba(0,0,0,0.7);">
            <div class="flex flex-col items-center px-6 pt-8 pb-5">
                <div class="w-14 h-14 rounded-2xl flex items-center justify-center mb-4" style="background: rgba(239,68,68,0.12); border: 1px solid rgba(239,68,68,0.25);">
                    <x-lucide-trash-2 class="w-6 h-6" style="color: #f87171;" />
                </div>
                <h3 class="text-base font-extrabold text-white text-center font-display">Hapus Produk?</h3>
                <p class="text-xs text-center mt-2 text-white/45 leading-relaxed">
                    "<span id="packing-delete-name" class="text-white font-semibold"></span>" akan dihapus permanen.
                </p>
            </div>
            <div class="px-6 pb-7 flex flex-col gap-2">
                <form id="packing-delete-form" action="{{ url('/admin/toko-packing') }}" method="POST" data-base-url="{{ url('/admin/toko-packing') }}">
                    @csrf @method('DELETE')
                    <button type="submit" class="w-full py-3.5 rounded-2xl text-sm font-bold text-white flex items-center justify-center gap-2 transition-all hover:scale-[1.02]" style="background: linear-gradient(135deg,#dc2626,#ef4444); box-shadow: 0 6px 20px rgba(220,38,38,0.4);">
                        <x-lucide-trash-2 class="w-4 h-4" /> Ya, Hapus
                    </button>
                </form>
                <button type="button" data-close-delete class="w-full py-3.5 rounded-2xl text-sm font-semibold transition-all hover:bg-white/5" style="border: 1.5px solid rgba(255,255,255,0.12); color: rgba(255,255,255,0.7);">Batal</button>
            </div>
        </div>
    </div>

    {{-- TOAST SUCCESS --}}
    @if(session('success') || session('save_toast'))
    <div id="packing-toast" class="fixed bottom-6 right-6 z-50 flex items-center gap-3 px-4 py-3 rounded-2xl" style="background: rgba(52,211,153,0.15); border: 1px solid rgba(52,211,153,0.35); backdrop-filter: blur(12px); box-shadow: 0 8px 32px rgba(0,0,0,0.4);">
        <x-lucide-check class="w-4 h-4" style="color: #34d399;" />
        <div>
            <p class="text-xs font-bold text-white">Berhasil</p>
            <p class="text-[10px]" style="color: rgba(255,255,255,0.5);">{{ session('success') ?? session('save_toast') }}</p>
        </div>
    </div>
    @endif

</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const root = document.getElementById('packing-admin');
    if (!root) return;

    const activeStyle = 'background: linear-gradient(135deg,#7c3aed,#6366f1); color: white; box-shadow: 0 2px 12px rgba(124,58,237,0.35);';
    const inactiveStyle = 'color: rgba(255,255,255,0.45);';
    const activeFilterStyle = 'background: rgba(124,58,237,0.2); border: 1px solid rgba(124,58,237,0.4); color: #c4b5fd;';
    const inactiveFilterStyle = 'background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.08); color: rgba(255,255,255,0.4);';

    const setTab = (tab) => {
        root.querySelectorAll('[data-tab-button]').forEach((button) => {
            const active = button.dataset.tabButton === tab;
            button.style.cssText = active ? activeStyle : inactiveStyle;
            const desc = button.querySelector('p');
            if (desc) desc.style.color = active ? 'rgba(255,255,255,0.7)' : 'rgba(255,255,255,0.28)';
        });

        root.querySelectorAll('[data-tab-panel]').forEach((panel) => {
            panel.classList.toggle('is-hidden', panel.dataset.tabPanel !== tab);
        });
    };

    root.querySelectorAll('[data-tab-button]').forEach((button) => {
        button.addEventListener('click', () => setTab(button.dataset.tabButton));
    });
    setTab(root.dataset.activeTab || 'inventaris');

    const setCategory = (category) => {
        root.querySelectorAll('[data-category-filter]').forEach((button) => {
            button.style.cssText = button.dataset.categoryFilter === category ? activeFilterStyle : inactiveFilterStyle;
        });

        root.querySelectorAll('[data-category]').forEach((row) => {
            row.classList.toggle('is-hidden', category !== 'Semua' && row.dataset.category !== category);
        });
    };

    root.querySelectorAll('[data-category-filter]').forEach((button) => {
        button.addEventListener('click', () => setCategory(button.dataset.categoryFilter));
    });
    setCategory('Semua');

    root.querySelectorAll('[data-stock-control]').forEach((control) => {
        const input = control.querySelector('[data-stock-input]');
        const save = control.querySelector('[data-stock-save]');
        const original = Number(control.dataset.original || 0);

        const sync = () => {
            const changed = Number(input.value || 0) !== original;
            save.classList.toggle('is-hidden', !changed);
            input.classList.toggle('text-white', !changed);
            input.style.color = changed ? '#a78bfa' : 'white';
        };

        control.querySelector('[data-stock-minus]').addEventListener('click', () => {
            input.value = Math.max(0, Number(input.value || 0) - 1);
            sync();
        });
        control.querySelector('[data-stock-plus]').addEventListener('click', () => {
            input.value = Number(input.value || 0) + 1;
            sync();
        });
        input.addEventListener('input', sync);
    });

    const formModal = document.getElementById('packing-form-modal');
    const productForm = document.getElementById('packing-product-form');
    const methodInput = document.getElementById('packing-form-method');
    const formTitle = document.getElementById('packing-form-title');
    const formSubtitle = document.getElementById('packing-form-subtitle');
    const submitLabel = document.getElementById('packing-form-submit-label');
    const maxImages = 10;

    const imagePicker = createMultiImagePicker({
        inputId: 'packing-images-input',
        previewId: 'packing-images-preview',
        labelId: 'packing-images-label',
        maxImages,
        emptyText: 'Maksimal 10 foto asli per produk.',
    });

    const fillForm = (data) => {
        ['name', 'category', 'unit', 'price', 'stock', 'low_threshold'].forEach((key) => {
            const input = productForm.elements[key];
            if (input) input.value = data[key] ?? '';
        });
    };

    const openCreate = () => {
        productForm.action = productForm.dataset.storeUrl;
        methodInput.disabled = true;
        formTitle.textContent = 'Tambah Produk Baru';
        formSubtitle.textContent = 'Produk baru akan muncul di katalog packing';
        submitLabel.textContent = 'Tambah Produk';
        fillForm({ name: '', category: 'Kardus', unit: 'pcs', price: '', stock: 0, low_threshold: 10 });
        imagePicker.reset(0);
        formModal.classList.remove('is-hidden');
    };

    const openEdit = (item) => {
        productForm.action = `${productForm.dataset.baseUrl}/${item.id}`;
        methodInput.disabled = false;
        formTitle.textContent = 'Edit Produk';
        formSubtitle.textContent = item.name || '';
        submitLabel.textContent = 'Simpan Perubahan';
        fillForm(item);
        imagePicker.reset(Array.isArray(item.images) ? item.images.length : 0);
        formModal.classList.remove('is-hidden');
    };

    const closeForm = () => formModal.classList.add('is-hidden');

    root.querySelector('[data-open-create]')?.addEventListener('click', openCreate);
    root.querySelectorAll('[data-open-edit]').forEach((button) => {
        button.addEventListener('click', () => openEdit(JSON.parse(button.dataset.item)));
    });
    formModal.querySelectorAll('[data-close-form]').forEach((button) => button.addEventListener('click', closeForm));
    formModal.addEventListener('click', (event) => {
        if (!event.target.closest('[data-modal-card]')) closeForm();
    });

    const deleteModal = document.getElementById('packing-delete-modal');
    const deleteForm = document.getElementById('packing-delete-form');
    const deleteName = document.getElementById('packing-delete-name');
    const closeDelete = () => deleteModal.classList.add('is-hidden');

    root.querySelectorAll('[data-open-delete]').forEach((button) => {
        button.addEventListener('click', () => {
            deleteForm.action = `${deleteForm.dataset.baseUrl}/${button.dataset.id}`;
            deleteName.textContent = button.dataset.name || '';
            deleteModal.classList.remove('is-hidden');
        });
    });
    deleteModal.querySelector('[data-close-delete]').addEventListener('click', closeDelete);
    deleteModal.addEventListener('click', (event) => {
        if (!event.target.closest('[data-modal-card]')) closeDelete();
    });

    const toast = document.getElementById('packing-toast');
    if (toast) setTimeout(() => toast.classList.add('is-hidden'), 3000);
});
</script>
@endsection
