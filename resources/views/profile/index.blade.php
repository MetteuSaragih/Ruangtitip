{{-- PENTING: Ganti 'layouts.app' dengan nama file layout utama Anda. --}}
{{-- Misalnya jika file layout Anda bernama 'dashboard.blade.php', ubah menjadi @extends('dashboard') --}}
@extends('layouts.dashboard')

@section('title', 'Profil Saya')

@section('content')
    <!-- Alpine.js untuk interaksi tab & form -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- HANYA ISI KONTEN (Tanpa HTML, Head, Body, atau Navbar) -->
    <div class="max-w-xl mx-auto pb-12" x-data="{ 
        screen: '{{ $currentTab ?? 'profil' }}',
        addresses: {{ json_encode($addresses ?? []) }},
        whatsapp: '812-3456-7890',
        showAddForm: false,
        newLabel: '',
        newAddr: '',
        saved: false,
        wpFocused: false,

        setPrimary(id) {
            this.addresses.forEach(a => a.isPrimary = (a.id === id));
        },
        deleteAddress(id) {
            this.addresses = this.addresses.filter(a => a.id !== id);
        },
        addAddress() {
            if(!this.newAddr.trim()) return;
            this.addresses.push({
                id: Date.now(),
                label: this.newLabel || 'Alamat Baru',
                address: this.newAddr,
                isPrimary: false
            });
            this.newLabel = ''; this.newAddr = ''; this.showAddForm = false;
        },
        saveChanges() {
            this.saved = true;
            setTimeout(() => this.saved = false, 2500);
        }
    }">
        
        <!-- Base Container -->
        <div class="rounded-3xl overflow-hidden mt-4 bg-[#0f0720]" style="box-shadow: 0 12px 48px rgba(0,0,0,0.65), 0 0 0 1px rgba(139,92,246,0.14);">
            
            <!-- Segmented Control Tab Navigation -->
            <div class="px-6 pt-6 pb-0">
                <div class="flex gap-1 p-1 rounded-2xl bg-white/5 border border-white/8">
                    <button @click="screen = 'profil'"
                            :class="screen === 'profil' ? 'bg-gradient-to-r from-violet-600 to-indigo-500 text-white shadow-lg shadow-violet-600/35' : 'text-white/45 bg-transparent'"
                            class="flex-1 py-2.5 rounded-xl text-xs font-bold transition-all duration-200">
                        Profil Saya
                    </button>
                    <button @click="screen = 'bantuan'"
                            :class="screen === 'bantuan' ? 'bg-gradient-to-r from-violet-600 to-indigo-500 text-white shadow-lg shadow-violet-600/35' : 'text-white/45 bg-transparent'"
                            class="flex-1 py-2.5 rounded-xl text-xs font-bold transition-all duration-200">
                        Bantuan
                    </button>
                </div>
            </div>

            <!-- CONTENT WRAPPER -->
            <div class="px-6 pb-6 pt-4">
                
                <!-- ══ TAB: PROFIL ══ -->
                <div x-show="screen === 'profil'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-x-4" x-transition:enter-end="opacity-100 translate-x-0" style="display: none;">
                    <h1 class="text-xl font-extrabold text-white mb-6">Profil Saya</h1>

                    <!-- Avatar Section -->
                    <div class="flex flex-col items-center mb-8">
                        <div class="relative group">
                            <div class="w-24 h-24 rounded-full flex items-center justify-center text-3xl font-extrabold text-white bg-gradient-to-br from-violet-600 to-violet-700 shadow-xl shadow-violet-600/45">
                                AR
                            </div>
                            <button class="absolute -bottom-1 -right-1 w-8 h-8 rounded-full flex items-center justify-center bg-gradient-to-br from-violet-600 to-indigo-500 border-2 border-[#0c0618] shadow-md hover:scale-110 transition-all">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" /><circle cx="12" cy="13" r="3" /></svg>
                            </button>
                        </div>
                        <button class="mt-3 text-xs font-semibold text-violet-400 hover:text-violet-300 transition-colors">Ubah Foto Profil</button>
                    </div>

                    <!-- Input Fields -->
                    <div class="space-y-4 mb-6">
                        <!-- Email Read Only -->
                        <div>
                            <label class="block text-xs font-bold mb-2 text-white/55">Username (Email)</label>
                            <div class="relative">
                                <div class="absolute left-3.5 top-1/2 -translate-y-1/2 text-white/20">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                                </div>
                                <input type="email" value="ahmadrizki@student.ub.ac.id" readonly disabled class="w-full pl-10 pr-24 py-3.5 rounded-xl text-sm bg-white/3 border border-white/7 text-white/35 cursor-not-allowed outline-none">
                                <div class="absolute right-3.5 top-1/2 -translate-y-1/2 px-2 py-0.5 rounded bg-white/7 text-[9px] font-semibold text-white/30">Read-only</div>
                            </div>
                            <p class="text-[10px] mt-1.5 text-white/30">Email tidak dapat diubah. Hubungi support jika ada masalah.</p>
                        </div>

                        <!-- WhatsApp Field -->
                        <div>
                            <label class="block text-xs font-bold mb-2 text-white/55">Nomor WhatsApp</label>
                            <div class="flex">
                                <div class="flex items-center px-3.5 rounded-l-xl text-sm font-semibold bg-white/6 border border-r-0 border-white/10 text-white/50">🇮🇩 +62</div>
                                <div class="relative flex-1">
                                    <div class="absolute left-3.5 top-1/2 -translate-y-1/2 transition-colors" :class="wpFocused ? 'text-violet-400' : 'text-white/30'">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.94.725l.548 2.2a1 1 0 01-.321.988l-1.305.98a10.582 10.582 0 004.872 4.872l.98-1.305a1 1 0 01.988-.321l2.2.548a1 1 0 01.725.94V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" /></svg>
                                    </div>
                                    <input type="tel" x-model="whatsapp" @focus="wpFocused = true" @blur="wpFocused = false"
                                           :style="wpFocused ? 'border-color: rgba(124,58,237,0.55); box-shadow: 0 0 0 3px rgba(124,58,237,0.1);' : 'border-color: rgba(255,255,255,0.1);'"
                                           class="w-full pl-10 pr-4 py-3.5 rounded-r-xl text-sm text-white bg-white/6 border outline-none transition-all duration-200">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Address List Management -->
                    <div class="mb-6">
                        <div class="flex items-center justify-between mb-3">
                            <h2 class="text-sm font-bold text-white">Daftar Alamat</h2>
                            <button @click="showAddForm = !showAddForm" class="flex items-center gap-1.5 text-xs font-semibold text-violet-400 hover:text-violet-300 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" /></svg>
                                Tambah Alamat
                            </button>
                        </div>

                        <!-- Loop Address Container -->
                        <div class="space-y-3">
                            <template x-for="addr in addresses" :key="addr.id">
                                <div class="rounded-2xl p-4 transition-all duration-200 border"
                                     :class="addr.isPrimary ? 'bg-violet-600/10 border-violet-500/35' : 'bg-white/4 border-white/10'">
                                    <div class="flex items-start justify-between gap-2 mb-2">
                                        <div class="flex items-center gap-2">
                                            <div class="shrink-0 mt-0.5" :class="addr.isPrimary ? 'text-violet-400' : 'text-white/35'">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                            </div>
                                            <div>
                                                <div class="flex items-center gap-2">
                                                    <span class="text-xs font-bold text-white" x-text="addr.label"></span>
                                                    <template x-if="addr.isPrimary">
                                                        <span class="px-1.5 py-0.5 rounded-full text-[9px] font-bold bg-violet-600/20 text-violet-400">Utama</span>
                                                    </template>
                                                </div>
                                                <p class="text-xs mt-0.5 leading-relaxed text-white/50" x-text="addr.address"></p>
                                            </div>
                                        </div>
                                        <button @click="deleteAddress(addr.id)" class="w-7 h-7 rounded-lg flex items-center justify-center shrink-0 hover:bg-red-500/20 text-white/30 hover:text-red-400 transition-all">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-4v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                        </button>
                                    </div>
                                    
                                    <button x-show="!addr.isPrimary" @click="setPrimary(addr.id)" class="flex items-center gap-1.5 text-[11px] font-semibold text-white/40 hover:text-violet-300 transition-colors mt-1">
                                        <div class="w-3.5 h-3.5 rounded-full border border-white/25"></div>
                                        Tetapkan sebagai Alamat Utama
                                    </button>
                                    <div x-show="addr.isPrimary" class="flex items-center gap-1.5 text-[11px] font-semibold text-violet-400 mt-1">
                                        <div class="w-3.5 h-3.5 rounded-full bg-violet-600 flex items-center justify-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-2.5 h-2.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                                        </div>
                                        Alamat Utama
                                    </div>
                                </div>
                            </template>
                        </div>

                        <!-- Slide Add Address Inline Box -->
                        <div x-show="showAddForm" x-collapse class="overflow-hidden">
                            <div class="mt-3 rounded-2xl p-4 bg-violet-600/8 border border-violet-500/20 space-y-3">
                                <p class="text-xs font-bold text-white mb-1">Tambah Alamat Baru</p>
                                <div>
                                    <label class="block text-[10px] font-semibold mb-1.5 text-white/50">Label (opsional)</label>
                                    <input type="text" x-model="newLabel" placeholder="Contoh: Kos, Kantor, Rumah..." class="w-full px-3.5 py-2.5 rounded-xl text-sm bg-white/6 border border-white/10 text-white outline-none">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-semibold mb-1.5 text-white/50">Alamat Lengkap <span class="text-red-400">*</span></label>
                                    <textarea x-model="newAddr" placeholder="Contoh: Jl. Veteran No. 10, Kec. Lowokwaru, Malang" rows="2" class="w-full px-3.5 py-2.5 rounded-xl text-sm bg-white/6 border border-white/10 text-white outline-none resize-none"></textarea>
                                </div>
                                <div class="flex gap-2 pt-1">
                                    <button @click="addAddress" :disabled="!newAddr.trim()" class="flex-1 py-2.5 rounded-xl text-xs font-bold text-white bg-gradient-to-r from-violet-600 to-indigo-500 disabled:opacity-40 transition-all hover:scale-105 duration-150">Simpan Alamat</button>
                                    <button @click="showAddForm = false; newLabel = ''; newAddr = '';" class="px-4 py-2.5 rounded-xl text-xs font-semibold bg-transparent border border-white/15 text-white/60 hover:bg-white/8 transition-all">Batal</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <button @click="saveChanges" class="w-full py-4 rounded-2xl font-bold text-sm text-white bg-gradient-to-r from-violet-600 to-indigo-500 flex items-center justify-center gap-2 transition-all active:scale-[0.98] hover:scale-[1.01]" style="box-shadow: 0 6px 20px rgba(124,58,237,0.4);">
                        <span x-show="!saved">Simpan Perubahan</span>
                        <span x-show="saved" class="flex items-center gap-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-white animate-bounce" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                            Perubahan Tersimpan!
                        </span>
                    </button>
                </div>

                <!-- ══ TAB: BANTUAN (FAQ) ══ -->
                <div x-show="screen === 'bantuan'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0" style="display: none;">
                    <h1 class="text-xl font-extrabold text-white mb-6">Bantuan</h1>

                    <!-- Accordion Section -->
                    <div class="mb-8">
                        <div class="flex items-center gap-2 mb-4">
                            <div class="w-7 h-7 rounded-lg flex items-center justify-center bg-violet-600/15 text-violet-400">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                            </div>
                            <h2 class="text-sm font-bold text-white">FAQ</h2>
                        </div>

                        <!-- Accordion Items Loop -->
                        <div class="space-y-2">
                            @foreach([
                                ['q' => 'Apa saja barang yang bisa dititipkan?', 'a' => 'Kardus, koper, elektronik, buku, dan barang rumah tangga. Tidak menerima: makanan mudah busuk, bahan kimia berbahaya, atau barang ilegal.'],
                                ['q' => 'Bagaimana jika barang saya rusak atau hilang?', 'a' => 'RUTIP memberikan jaminan ganti rugi penuh untuk kerusakan akibat kelalaian kami. Setiap barang difoto dan disegel sebagai bukti kondisi awal.'],
                                ['q' => 'Berapa lama minimal penitipan?', 'a' => 'Minimal 1 bulan. Tersedia paket 1, 3, dan 6 bulan — semakin lama, semakin hemat.'],
                                ['q' => 'Apakah ada layanan jemput ke kos?', 'a' => 'Ya! Tim RUTIP menjangkau seluruh Kota Malang. Pilih jadwal saat pesan, kami datang tepat waktu.'],
                                ['q' => 'Bagaimana cara membayar?', 'a' => 'Transfer bank, QRIS, GoPay, OVO, dan Dana. Pembayaran di depan, bukti bayar langsung dikirim.'],
                                ['q' => 'Bisakah saya memperpanjang durasi penitipan?', 'a' => 'Tentu! Kamu bisa perpanjang kapan saja melalui menu Pesanan Saya → Tambah Durasi Sewa sebelum masa titip habis.']
                            ] as $faq)
                                <div x-data="{ open: false }" class="rounded-2xl overflow-hidden border transition-all duration-200"
                                     :class="open ? 'bg-violet-600/10 border-violet-500/35' : 'bg-white/4 border-white/8'">
                                    <button @click="open = !open" class="w-full flex items-center justify-between gap-3 px-5 py-4 text-left">
                                        <span class="text-sm font-semibold text-white leading-snug">{{ $faq['q'] }}</span>
                                        <div class="w-6 h-6 rounded-full flex items-center justify-center shrink-0 transition-colors"
                                             :class="open ? 'bg-violet-600/25 text-violet-300' : 'bg-white/7 text-white/40'">
                                            <svg x-show="!open" xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" /></svg>
                                            <svg x-show="open" xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7" /></svg>
                                        </div>
                                    </button>
                                    <div x-show="open" x-collapse>
                                        <p class="px-5 pb-5 text-sm leading-relaxed text-white/55">{{ $faq['a'] }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Customer Service Card Panel -->
                    <div>
                        <h2 class="text-sm font-bold text-white mb-4">Butuh Bantuan Lanjutan?</h2>
                        <div class="relative overflow-hidden rounded-2xl p-5 border border-emerald-500/35 bg-gradient-to-br from-emerald-500/15 to-emerald-700/10">
                            <div class="absolute -top-8 -right-8 w-32 h-32 rounded-full blur-3xl opacity-20 bg-emerald-400"></div>
                            <div class="relative z-10 flex items-start gap-4">
                                <div class="w-12 h-12 rounded-2xl flex items-center justify-center text-2xl shrink-0 bg-emerald-500/20">💬</div>
                                <div class="flex-1">
                                    <p class="text-sm font-bold text-white mb-1">Customer Service RUTIP</p>
                                    <p class="text-xs leading-relaxed mb-4 text-white/60">Tim kami siap membantu kamu 7 hari seminggu. Respons cepat via WhatsApp.</p>
                                    
                                    <div class="flex flex-wrap gap-x-3 gap-y-1.5 mb-4 text-white/55 text-[10px] font-medium">
                                        <div class="flex items-center gap-1"><span class="text-amber-400">★</span> Respons Cepat</div>
                                        <div class="flex items-center gap-1"><span class="text-emerald-400">✓</span> Terpercaya</div>
                                        <div class="flex items-center gap-1"><span class="text-sky-400">🕒</span> 24/7 Siap</div>
                                    </div>

                                    <a href="https://wa.me/6281234567890?text=Halo%20RUTIP%2C%20saya%20butuh%20bantuan" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 px-5 py-3 rounded-xl font-bold text-sm text-white bg-gradient-to-r from-emerald-500 to-teal-600 hover:scale-105 active:scale-95 transition-all shadow-lg shadow-emerald-500/40">
                                        Hubungi Pusat Bantuan
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 opacity-70" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" /></svg>
                                    </a>
                                    <p class="text-[10px] mt-2 text-white/35">Akan membuka WhatsApp CS RUTIP</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-center gap-2 mt-8 text-white/25 text-[10px]">
                        <span>RUTIP v1.0.0 · © 2026</span>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection