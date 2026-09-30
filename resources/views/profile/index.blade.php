<<<<<<< HEAD
@extends('layouts.ruang-titip')
@section('title', 'Profil & Bantuan')
=======
@extends('layouts.dashboard')
>>>>>>> hostinger/main

@push('styles')
<style>
.shell{display:grid;grid-template-columns:260px minmax(0,1fr);gap:32px;margin-top:40px;align-items:start;padding-bottom:64px}
.btn-sm{min-height:40px;padding:0 16px;font-size:14px}
.pill-ink{background:var(--ink);color:var(--cream);border:1px solid var(--ink)}

/* Menu samping */
.side{position:sticky;top:96px;background:var(--paper);border:1.5px solid var(--ink);border-radius:18px;overflow:hidden}
.side .me{display:flex;align-items:center;gap:12px;padding:18px;background:var(--sand);border-bottom:1.5px solid var(--ink)}
.side .me .av{width:48px;height:48px;border-radius:50%;background:var(--tape);color:#fff;border:1.5px solid var(--ink);display:grid;place-items:center;font-weight:800;font-family:var(--font-display);font-size:18px;overflow:hidden;flex-shrink:0}
.side .me .av img{width:100%;height:100%;object-fit:cover}
.side .me strong{display:block;line-height:1.25}
.side .me small{color:var(--depot);font-weight:700;font-size:13px}
.side nav{display:flex;flex-direction:column;padding:8px}
.side nav button,.side nav a{display:flex;align-items:center;gap:12px;min-height:48px;padding:0 14px;border-radius:12px;border:0;background:none;font-weight:600;font-size:15px;color:var(--ink);text-decoration:none;cursor:pointer;text-align:left;width:100%}
.side nav button:hover,.side nav a:hover{background:var(--cream)}
.side nav button[aria-selected="true"]{background:var(--ink);color:var(--cream)}
.side nav button[aria-selected="true"] svg{stroke:var(--cream)}
.side nav hr{border:0;border-top:1px solid var(--line);margin:6px 4px}
.side nav .out{color:var(--danger)}
.side nav .out svg{stroke:var(--danger)}

.pane[hidden]{display:none}
.pane-head h1{font-size:clamp(30px,3.4vw,40px);font-weight:800;letter-spacing:-1px}
.pane-head p{color:var(--body);margin-top:4px}
.card{background:var(--paper);border:1px solid var(--line-strong);border-radius:18px;padding:24px;margin-top:20px}
.card h2{font-size:20px;font-weight:700}
.card .sub{font-size:14px;color:var(--muted);margin-top:2px}
.card-head{display:flex;justify-content:space-between;align-items:center;gap:12px}

/* Foto */
.photo{display:flex;align-items:center;gap:20px}
.photo .big{width:96px;height:96px;border-radius:50%;background:var(--tape);color:#fff;border:2px solid var(--ink);box-shadow:4px 4px 0 var(--ink);display:grid;place-items:center;font-family:var(--font-display);font-weight:800;font-size:38px;overflow:hidden;flex-shrink:0}
.photo .big img{width:100%;height:100%;object-fit:cover}
.photo .btns{display:flex;gap:8px;flex-wrap:wrap;margin-top:8px}
.photo small{display:block;color:var(--muted);font-size:13px}

/* Input */
.field{margin-top:18px}
.input-ic{position:relative}
.input-ic svg{position:absolute;left:14px;top:50%;transform:translateY(-50%);color:var(--muted);pointer-events:none}
.input-ic input{padding-left:44px}
.field input[readonly]{background:var(--line);color:var(--muted);cursor:not-allowed}
.lock{position:absolute;right:12px;top:50%;transform:translateY(-50%);font-size:12px;font-weight:700;padding:3px 10px;border-radius:999px;background:var(--paper);border:1px solid var(--line-strong);color:var(--muted)}
.phone{display:flex}
.phone span{display:flex;align-items:center;padding:0 14px;border:1.5px solid var(--line-strong);border-right:0;border-radius:12px 0 0 12px;background:var(--sand);font-weight:700}
.phone input{border-radius:0 12px 12px 0!important}
.field .err-msg{font-size:13px;font-weight:600;color:var(--danger);display:none;margin-top:6px}
.field.invalid .err-msg{display:block}
.field.invalid input{border-color:var(--danger)}

/* Alamat */
.addr{display:flex;flex-direction:column;gap:12px;margin-top:16px}
.addr-item{display:flex;gap:14px;align-items:flex-start;padding:16px;border:1.5px solid var(--line-strong);border-radius:14px;background:var(--paper)}
.addr-item.main{border-color:var(--ink);background:var(--cream)}
.addr-item .ic{width:40px;height:40px;border-radius:12px;background:var(--sand);border:1.5px solid var(--ink);display:grid;place-items:center;flex-shrink:0}
.addr-item .t{flex-grow:1;min-width:0}
.addr-item strong{font-size:16px}
.addr-item p{color:var(--body);font-size:15px}
.addr-item .row{display:flex;gap:6px;margin-top:10px;flex-wrap:wrap}
.link-btn{min-height:36px;padding:0 12px;border-radius:999px;border:1.5px solid var(--line-strong);background:var(--paper);font-weight:600;font-size:13px;cursor:pointer}
.link-btn:hover{border-color:var(--ink)}
.link-btn.danger{color:var(--danger)}
.link-btn.danger:hover{border-color:var(--danger)}
.new-form{margin-top:16px;padding:18px;border:1.5px dashed var(--line-strong);border-radius:14px;background:var(--cream)}
.new-form[hidden]{display:none}
.new-form .acts{display:flex;gap:8px;justify-content:flex-end;margin-top:16px}
.empty-addr{padding:20px;text-align:center;color:var(--muted);border:1.5px dashed var(--line-strong);border-radius:14px}

/* Simpan */
.savebar{position:sticky;bottom:16px;z-index:20;display:flex;justify-content:space-between;align-items:center;gap:16px;margin-top:20px;padding:14px 14px 14px 20px;background:var(--ink);color:var(--cream);border-radius:999px;transition:opacity .2s,transform .2s}
.savebar.idle{opacity:0;transform:translateY(12px);pointer-events:none}
.savebar .btn{box-shadow:none;border-color:var(--tape-light);background:var(--tape-light);color:var(--ink)}
.savebar .btn:hover{background:#F0B98F;color:var(--ink)}
.savebar .btn-ghost{background:none;border:0;color:#D6D0C3;font-weight:600;cursor:pointer;min-height:44px;padding:0 12px}

/* Bantuan */
.help-search{position:relative;margin-top:20px;display:block}
.help-search svg{position:absolute;left:16px;top:50%;transform:translateY(-50%);color:var(--muted)}
.help-search input{width:100%;height:52px;border-radius:999px;border:1.5px solid var(--line-strong);background:var(--paper);padding:0 18px 0 46px;font-size:16px}
.help-search input:focus{outline:none;border-color:var(--ink)}
.faq{margin-top:12px}
.faq details{border-bottom:1px solid var(--line)}
.faq details:first-child{border-top:1.5px solid var(--ink)}
.faq summary{list-style:none;cursor:pointer;display:flex;justify-content:space-between;align-items:center;gap:16px;padding:18px 0;font-weight:700;font-size:17px}
.faq summary::-webkit-details-marker{display:none}
.faq summary .pm{width:28px;height:28px;border-radius:50%;border:1.5px solid var(--ink);display:grid;place-items:center;flex-shrink:0;font-weight:700;transition:transform .2s}
.faq details[open] summary .pm{transform:rotate(45deg);background:var(--ink);color:var(--cream)}
.faq details p{color:var(--body);padding:0 44px 18px 0}
.no-faq{padding:20px 0;color:var(--muted)}
.cs{display:flex;gap:20px;align-items:center;margin-top:24px;padding:24px;background:var(--sand);border:1.5px solid var(--ink);border-radius:18px}
.cs img{width:92px;height:auto;flex-shrink:0}
.cs .t{flex-grow:1}
.cs strong{display:block;font-family:var(--font-display);font-size:22px}
.cs p{color:var(--body);font-size:15px;margin-top:2px}
.cs .acts{display:flex;gap:8px;flex-wrap:wrap;margin-top:14px}
.btn-wa{background:var(--depot);color:#fff;border-color:var(--ink);box-shadow:3px 3px 0 var(--ink)}
.btn-wa:hover{background:#244A38;color:#fff}
.ver{margin-top:24px;text-align:center;font-size:13px;color:var(--muted)}

.p-toast{position:fixed;left:50%;bottom:24px;transform:translate(-50%,150%);z-index:60;display:flex;align-items:center;gap:10px;padding:14px 20px;background:var(--depot);color:#fff;border:1.5px solid var(--ink);border-radius:999px;font-weight:700;transition:transform .25s}
.p-toast.show{transform:translate(-50%,0)}

@media (max-width:900px){
  .shell{grid-template-columns:1fr;margin-top:24px}
  .side{position:static}
  .side nav{flex-direction:row;overflow-x:auto}
  .side nav hr{display:none}
  .side nav button,.side nav a{white-space:nowrap;width:auto}
  .cs{flex-direction:column;align-items:flex-start}
}
@media (max-width:560px){.photo{flex-direction:column;align-items:flex-start}.card{padding:18px}}
</style>
@endpush

@section('content')
<<<<<<< HEAD
<main class="wrap">
  <div class="shell">
    <aside class="side">
      <div class="me">
        <span class="av" data-avatar>
          @if ($user->avatar)
            <img src="{{ \Illuminate\Support\Facades\Storage::url($user->avatar) }}" alt="">
          @else
            {{ $user->initials }}
          @endif
        </span>
        <div><strong data-name>{{ $user->name ?: 'Tanpa nama' }}</strong><small>Penitip aktif</small></div>
      </div>
      <nav role="tablist" aria-label="Menu akun">
        <button type="button" role="tab" id="t-profil" aria-selected="{{ $currentTab !== 'bantuan' ? 'true' : 'false' }}" aria-controls="p-profil" data-pane="profil">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="4"/><path d="M4 21a8 8 0 0 1 16 0"/></svg>Profil saya</button>
        <a href="{{ route('pesanan.index') }}"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M9 4h6l1 2h3v15H5V6h3z"/><path d="M9 12h6M9 16h4"/></svg>Pesanan saya</a>
        <button type="button" role="tab" id="t-bantuan" aria-selected="{{ $currentTab === 'bantuan' ? 'true' : 'false' }}" aria-controls="p-bantuan" data-pane="bantuan">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M9.5 9a2.5 2.5 0 1 1 3.5 2.3c-.7.3-1 .9-1 1.7M12 17h.01"/></svg>Bantuan</button>
        <hr>
        <form method="POST" action="{{ route('logout') }}">
          @csrf
          <button type="submit" class="out">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M15 4h4v16h-4M10 17l5-5-5-5M15 12H3"/></svg>Keluar
          </button>
        </form>
      </nav>
    </aside>

    <div>
      <!-- ================= PROFIL ================= -->
      <section class="pane" id="p-profil" role="tabpanel" aria-labelledby="t-profil" @if($currentTab === 'bantuan') hidden @endif>
        <div class="pane-head">
          <h1>Profil saya</h1>
          <p>Data ini dipakai tim Ruru untuk menghubungimu soal pesanan.</p>
=======
    <!-- Alpine.js untuk interaksi tab & form -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.1/dist/cdn.min.js"></script>

    <div class="max-w-xl mx-auto pt-4 pb-12 px-4 sm:px-0" x-data="{
        screen: '{{ $currentTab ?? 'profil' }}',
        addresses: {{ json_encode($addresses ?? []) }},
        name: {{ json_encode($user->name) }},
        whatsapp: {{ json_encode($user->phone) }},
        redirectAfter: {{ json_encode($redirectAfter ?? null) }},
        showAddForm: false,
        formMode: 'add',
        editingId: null,
        newLabel: '',
        formError: '',
        saved: false,
        saving: false,
        wpFocused: false,

        setPrimary(id) {
            this.addresses.forEach(a => a.isPrimary = (a.id === id));
            fetch(`{{ url('/profil/alamat') }}/${id}/primary`, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            });
        },
        deleteAddress(id) {
            const wasPrimary = this.addresses.find(a => a.id === id)?.isPrimary;
            fetch(`{{ url('/profil/alamat') }}/${id}`, {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    this.addresses = this.addresses.filter(a => a.id !== id);
                    if (wasPrimary && this.addresses.length > 0) {
                        this.addresses[this.addresses.length - 1].isPrimary = true;
                    }
                }
            });
        },
        openAddForm() {
            this.formMode = 'add';
            this.editingId = null;
            this.newLabel = '';
            this.formError = '';
            this.showAddForm = true;
            this.$nextTick(() => this.fillAddressForm({ areaId: '', areaName: '', postalCode: '', address: '', latitude: '', longitude: '' }));
        },
        editAddress(addr) {
            this.formMode = 'edit';
            this.editingId = addr.id;
            this.newLabel = addr.label;
            this.formError = '';
            this.showAddForm = true;
            this.$nextTick(() => this.fillAddressForm(addr));
        },
        fillAddressForm(addr) {
            const areaWrap = document.querySelector('[data-biteship-area-search]');
            const addrWrap = document.querySelector('[data-address-autocomplete]');
            if (areaWrap) {
                areaWrap.querySelector('input[type=text]').value = addr.areaName || '';
                areaWrap.querySelector('input[name=area_id]').value = addr.areaId || '';
                areaWrap.querySelector('input[name=area_name]').value = addr.areaName || '';
                areaWrap.querySelector('input[name=postal_code]').value = addr.postalCode || '';
            }
            if (addrWrap) {
                addrWrap.querySelector('textarea').value = addr.address || '';
                addrWrap.querySelector('input[name=address]').value = addr.address || '';
                addrWrap.querySelector('input[name=latitude]').value = addr.latitude || '';
                addrWrap.querySelector('input[name=longitude]').value = addr.longitude || '';
            }
        },
        cancelAddressForm() {
            this.showAddForm = false;
            this.formMode = 'add';
            this.editingId = null;
            this.newLabel = '';
            this.formError = '';
            this.fillAddressForm({ areaId: '', areaName: '', postalCode: '', address: '', latitude: '', longitude: '' });
        },
        saveAddressForm() {
            const areaWrap = document.querySelector('[data-biteship-area-search]');
            const addrWrap = document.querySelector('[data-address-autocomplete]');
            const payload = {
                label: this.newLabel || 'Alamat',
                address: addrWrap.querySelector('input[name=address]').value.trim(),
                area_id: areaWrap.querySelector('input[name=area_id]').value,
                area_name: areaWrap.querySelector('input[name=area_name]').value,
                postal_code: areaWrap.querySelector('input[name=postal_code]').value,
                latitude: addrWrap.querySelector('input[name=latitude]').value || null,
                longitude: addrWrap.querySelector('input[name=longitude]').value || null,
            };
            if (!payload.address) { this.formError = 'Alamat lengkap wajib diisi.'; return; }
            if (!payload.area_id) { this.formError = 'Pilih kecamatan/kota dari daftar saran.'; return; }
            this.formError = '';

            const isEdit = this.formMode === 'edit';
            const url = isEdit ? `{{ url('/profil/alamat') }}/${this.editingId}` : '{{ route('profile.address.store') }}';

            fetch(url, {
                method: isEdit ? 'PUT' : 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: JSON.stringify(payload),
            })
            .then(r => r.json())
            .then(data => {
                if (!data.success) { this.formError = data.message || 'Gagal menyimpan alamat.'; return; }
                const entry = { id: data.id, label: data.label, address: data.address, areaId: data.areaId, areaName: data.areaName, postalCode: data.postalCode, latitude: data.latitude, longitude: data.longitude, isPrimary: data.isPrimary };
                if (isEdit) {
                    const idx = this.addresses.findIndex(a => a.id === this.editingId);
                    if (idx !== -1) entry.isPrimary = this.addresses[idx].isPrimary;
                    if (idx !== -1) this.addresses[idx] = entry;
                } else {
                    this.addresses.push(entry);
                }
                this.cancelAddressForm();
            })
            .catch(() => { this.formError = 'Gagal menyimpan alamat.'; });
        },
        saveChanges() {
            if (this.saving) return;
            this.saving = true;
            fetch('{{ route('profile.update') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ name: this.name, whatsapp: this.whatsapp }),
            })
            .then(r => r.json())
            .then(data => {
                this.saving = false;
                if (data.success) {
                    this.saved = true;
                    document.getElementById('avatarInitials')?.replaceChildren(document.createTextNode((this.name || 'U').trim().split(/\s+/).slice(0,2).map(w => w[0]?.toUpperCase() || '').join('')));
                    if (this.redirectAfter && this.name.trim() && this.whatsapp && this.whatsapp.trim()) {
                        window.location.href = this.redirectAfter;
                        return;
                    }
                    setTimeout(() => this.saved = false, 2500);
                }
            })
            .catch(() => { this.saving = false; });
        }
    }">
        
        {{-- ─── Error / Info Banner ─── --}}
        @if (session('error'))
        <div class="flex items-start gap-3 px-4 py-3.5 rounded-2xl mb-4"
             style="background:rgba(239,68,68,0.08);border:1px solid rgba(239,68,68,0.25);">
            <div class="w-8 h-8 rounded-xl flex items-center justify-center shrink-0" style="background:rgba(239,68,68,0.12);">
                <svg class="w-4 h-4" fill="none" stroke="#f87171" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v4m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                </svg>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-xs font-bold mb-0.5" style="color:#fca5a5;">Profil Belum Lengkap</p>
                <p class="text-xs leading-relaxed" style="color:rgba(252,165,165,0.75);">{{ session('error') }}</p>
            </div>
        </div>
        @endif

        {{-- ─── Main Card ─── --}}
        <div class="rounded-3xl overflow-hidden bg-[#0f0720]" style="box-shadow: 0 12px 48px rgba(0,0,0,0.65), 0 0 0 1px rgba(139,92,246,0.14);">

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
                    <h1 class="text-xl font-extrabold text-white font-display mb-6">Profil Saya</h1>

                    <!-- Avatar Section -->
                    <div class="flex flex-col items-center mb-8">
                        {{-- Hidden file input --}}
                        <input type="file" id="avatarFileInput" accept="image/jpeg,image/jpg,image/png,image/webp"
                               class="hidden" onchange="handleAvatarChange(this)">

                        <div class="relative group cursor-pointer" onclick="document.getElementById('avatarFileInput').click()">
                            {{-- Avatar image or initials --}}
                            <div class="w-24 h-24 rounded-full overflow-hidden relative shadow-xl"
                                 style="box-shadow:0 0 0 3px rgba(124,58,237,0.4),0 8px 32px rgba(124,58,237,0.25);">
                                @if ($user->avatar)
                                    <img id="avatarImg"
                                         src="{{ Storage::url($user->avatar) }}"
                                         alt="Foto Profil"
                                         class="w-full h-full object-cover">
                                @else
                                    <div id="avatarImg" class="w-full h-full" style="display:none;"></div>
                                @endif
                                <div id="avatarInitials"
                                     class="w-full h-full flex items-center justify-center text-3xl font-extrabold text-white bg-gradient-to-br from-violet-600 to-violet-700"
                                     style="{{ $user->avatar ? 'display:none;' : '' }}">
                                    {{ $user->initials }}
                                </div>

                                {{-- Overlay on hover --}}
                                <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-200"
                                     style="background:rgba(0,0,0,0.55);">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                                        <circle cx="12" cy="13" r="3"/>
                                    </svg>
                                </div>
                            </div>

                            {{-- Camera badge --}}
                            <div class="absolute -bottom-1 -right-1 w-8 h-8 rounded-full flex items-center justify-center border-2 pointer-events-none"
                                 style="background:linear-gradient(135deg,#7c3aed,#6366f1);border-color:#0c0618;">
                                <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                                    <circle cx="12" cy="13" r="3"/>
                                </svg>
                            </div>
                        </div>

                        <button type="button" onclick="document.getElementById('avatarFileInput').click()"
                                class="mt-3 text-xs font-semibold transition-colors" style="color:#a78bfa;">
                            Ubah Foto Profil
                        </button>
                        <p id="avatarStatus" class="mt-1 text-[10px]" style="color:rgba(255,255,255,0.3);min-height:14px;"></p>
                    </div>

                    <!-- Input Fields -->
                    <div class="space-y-4 mb-6">
                        <!-- Nama -->
                        <div>
                            <label class="block text-xs font-bold mb-2 text-white/55">Nama Pengguna</label>
                            <div class="relative">
                                <div class="absolute left-3.5 top-1/2 -translate-y-1/2 text-white/20">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                                </div>
                                <input type="text" x-model="name" placeholder="Nama lengkap kamu" maxlength="255"
                                       class="w-full pl-10 pr-4 py-3.5 rounded-xl text-sm text-white bg-white/6 border outline-none transition-all duration-200"
                                       style="border-color:rgba(255,255,255,0.1);"
                                       onfocus="this.style.borderColor='rgba(124,58,237,0.55)';this.style.boxShadow='0 0 0 3px rgba(124,58,237,0.1)';"
                                       onblur="this.style.borderColor='rgba(255,255,255,0.1)';this.style.boxShadow='none';">
                            </div>
                        </div>

                        <!-- Email Read Only -->
                        <div>
                            <label class="block text-xs font-bold mb-2 text-white/55">Email</label>
                            <div class="relative">
                                <div class="absolute left-3.5 top-1/2 -translate-y-1/2 text-white/20">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                                </div>
                                <input type="email" value="{{ $user->email }}" readonly disabled class="w-full pl-10 pr-24 py-3.5 rounded-xl text-sm bg-white/3 border border-white/7 text-white/35 cursor-not-allowed outline-none">
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
                            <button @click="showAddForm && formMode === 'add' ? cancelAddressForm() : openAddForm()" class="flex items-center gap-1.5 text-xs font-semibold text-violet-400 hover:text-violet-300 transition-colors">
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
                                        <div class="flex items-center gap-1 shrink-0">
                                            <button @click="editAddress(addr)" class="w-7 h-7 rounded-lg flex items-center justify-center hover:bg-violet-500/20 text-white/30 hover:text-violet-300 transition-all">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                            </button>
                                            <button @click="deleteAddress(addr.id)" class="w-7 h-7 rounded-lg flex items-center justify-center hover:bg-red-500/20 text-white/30 hover:text-red-400 transition-all">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-4v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                            </button>
                                        </div>
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

                        <!-- Slide Add/Edit Address Inline Box -->
                        <div x-show="showAddForm" x-collapse class="overflow-hidden">
                            <div class="mt-3 rounded-2xl p-4 bg-violet-600/8 border border-violet-500/20 space-y-3">
                                <p class="text-xs font-bold text-white mb-1" x-text="formMode === 'edit' ? 'Edit Alamat' : 'Tambah Alamat Baru'"></p>

                                <template x-if="formError">
                                    <p class="text-[11px] px-3 py-2 rounded-lg" style="background:rgba(239,68,68,0.1);border:1px solid rgba(239,68,68,0.25);color:#fca5a5;" x-text="formError"></p>
                                </template>

                                <div>
                                    <label class="block text-[10px] font-semibold mb-1.5 text-white/50">Label (opsional)</label>
                                    <input type="text" x-model="newLabel" placeholder="Contoh: Kos, Kantor, Rumah..." class="w-full px-3.5 py-2.5 rounded-xl text-sm bg-white/6 border border-white/10 text-white outline-none">
                                </div>

                                <x-biteship-area-search />

                                <x-address-autocomplete />

                                <div class="flex gap-2 pt-1">
                                    <button @click="saveAddressForm" class="flex-1 py-2.5 rounded-xl text-xs font-bold text-white transition-all hover:scale-105 duration-150" style="background:linear-gradient(135deg,#7c3aed,#6366f1);">
                                        <span x-text="formMode === 'edit' ? 'Simpan Perubahan' : 'Simpan Alamat'"></span>
                                    </button>
                                    <button @click="cancelAddressForm" class="px-4 py-2.5 rounded-xl text-xs font-semibold bg-transparent border border-white/15 text-white/60 hover:bg-white/8 transition-all">Batal</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <button @click="saveChanges" :disabled="saving" class="w-full py-4 rounded-2xl font-bold text-sm text-white flex items-center justify-center gap-2 transition-all active:scale-[0.98] hover:scale-[1.01] disabled:opacity-60" style="background:linear-gradient(135deg,#7c3aed,#6366f1);box-shadow:0 6px 20px rgba(124,58,237,0.4);">
                        <span x-show="!saved && !saving">Simpan Perubahan</span>
                        <span x-show="saving" x-cloak>Menyimpan...</span>
                        <span x-show="saved" class="flex items-center gap-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-white animate-bounce" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                            Perubahan Tersimpan!
                        </span>
                    </button>
                </div>

                <script>
                function handleAvatarChange(input) {
                    const file = input.files[0];
                    if (!file) return;

                    const status = document.getElementById('avatarStatus');
                    const img    = document.getElementById('avatarImg');
                    const inits  = document.getElementById('avatarInitials');

                    // Show local preview immediately
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        img.src = e.target.result;
                        img.style.display = 'block';
                        if (inits) inits.style.display = 'none';
                    };
                    reader.readAsDataURL(file);

                    // Upload
                    status.textContent = 'Mengunggah...';
                    status.style.color = '#a78bfa';

                    const form = new FormData();
                    form.append('avatar', file);
                    form.append('_token', '{{ csrf_token() }}');

                    fetch('{{ route('profile.avatar') }}', { method: 'POST', body: form })
                        .then(r => r.json())
                        .then(data => {
                            if (data.success) {
                                img.src = data.avatar_url + '?t=' + Date.now();
                                status.textContent = '✓ Foto berhasil diperbarui';
                                status.style.color = '#34d399';
                                setTimeout(() => { status.textContent = ''; }, 3000);
                            } else {
                                status.textContent = 'Gagal mengunggah foto';
                                status.style.color = '#f87171';
                            }
                        })
                        .catch(() => {
                            status.textContent = 'Gagal mengunggah foto';
                            status.style.color = '#f87171';
                        });

                    // Reset input so same file can be re-selected
                    input.value = '';
                }
                </script>

                <!-- ══ TAB: BANTUAN (FAQ) ══ -->
                <div x-show="screen === 'bantuan'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0" style="display: none;">
                    <h1 class="text-xl font-extrabold text-white font-display mb-6">Bantuan</h1>

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
                                ['q' => 'Berapa lama minimal penitipan?', 'a' => 'Minimal 1 bulan. Tersedia paket 1, 3, dan 6 bulan - semakin lama, semakin hemat.'],
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

                                    <a href="https://wa.me/6285121091134?text=Halo%20RUTIP%2C%20saya%20butuh%20bantuan" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 px-5 py-3 rounded-xl font-bold text-sm text-white bg-gradient-to-r from-emerald-500 to-teal-600 hover:scale-105 active:scale-95 transition-all shadow-lg shadow-emerald-500/40">
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
>>>>>>> hostinger/main
        </div>

        @if (session('error'))
          <p class="err" style="margin-top:16px">{{ session('error') }}</p>
        @endif

        <div class="card">
          <div class="photo">
            <span class="big" id="big-av" data-avatar>
              @if ($user->avatar)
                <img src="{{ \Illuminate\Support\Facades\Storage::url($user->avatar) }}" alt="">
              @else
                {{ $user->initials }}
              @endif
            </span>
            <div>
              <h2>Foto profil</h2>
              <small>JPG, JPEG, PNG, atau WEBP, maksimal 3 MB.</small>
              <div class="btns">
                <label class="btn btn-outline btn-sm" style="cursor:pointer">Ganti foto<input type="file" id="photo" accept="image/png,image/jpeg,image/webp" style="position:absolute;left:-9999px"></label>
              </div>
              <p class="err-msg" id="photo-err" style="display:block;font-size:13px;color:var(--danger);font-weight:600;margin-top:6px;min-height:1em"></p>
            </div>
          </div>
        </div>

        <div>
          <div class="card">
            <h2>Data diri</h2>
            <div class="field">
              <label for="name">Nama lengkap</label>
              <div class="input-ic">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"><circle cx="12" cy="8" r="4"/><path d="M4 21a8 8 0 0 1 16 0"/></svg>
                <input id="name" name="name" autocomplete="name" value="{{ $user->name }}">
              </div>
              <p class="err-msg">Nama nggak boleh kosong.</p>
            </div>
            <div class="field">
              <label for="email">Email</label>
              <div class="input-ic">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="m3 7 9 6 9-6"/></svg>
                <input id="email" type="email" value="{{ $user->email }}" readonly aria-describedby="email-note">
                <span class="lock">Terkunci</span>
              </div>
              <small id="email-note">Email dipakai untuk masuk, jadi nggak bisa diubah. Butuh ganti? Hubungi bantuan.</small>
            </div>
            <div class="field">
              <label for="wa">Nomor WhatsApp</label>
              <div class="phone"><span>+62</span><input id="wa" name="whatsapp" type="tel" inputmode="tel" autocomplete="tel-national" placeholder="812xxxxxxxx" value="{{ ltrim($user->phone ?? '', '0') }}"></div>
              <p class="err-msg">Nomor belum valid. Contoh: 81234567890</p>
            </div>
          </div>
        </div>

        <div class="card">
          <div class="card-head">
            <div><h2>Alamat</h2><p class="sub">Alamat utama otomatis dipilih saat checkout.</p></div>
            <button type="button" class="btn btn-outline btn-sm" id="add-addr">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"><path d="M12 5v14M5 12h14"/></svg>Tambah
            </button>
          </div>
          <div class="addr" id="addr-list"></div>

          <div class="new-form" id="new-form" hidden>
            <h2 style="font-size:17px">Alamat baru</h2>
            <div class="field" style="margin-top:12px"><label for="n-label">Label <span style="font-weight:400;color:var(--muted)">(opsional)</span></label><input id="n-label" placeholder="Kos / Rumah / Kontrakan"></div>
            <div class="field"><label for="n-full">Alamat lengkap</label><textarea id="n-full" placeholder="Nama jalan, nomor rumah/kamar, nama kos, kecamatan"></textarea></div>
            <p class="err" id="n-err" role="alert" style="margin-top:0"></p>
            <div class="acts">
              <button type="button" class="link-btn" id="n-cancel">Batal</button>
              <button type="button" class="btn btn-primary btn-sm" id="n-save">Simpan alamat</button>
            </div>
          </div>
        </div>

        <div class="savebar idle" id="savebar" aria-live="polite">
          <span>Ada perubahan yang belum disimpan</span>
          <span style="display:flex;gap:4px">
            <button type="button" class="btn-ghost" id="discard">Batalkan</button>
            <button type="button" class="btn btn-sm" id="save">Simpan perubahan</button>
          </span>
        </div>
      </section>

      <!-- ================= BANTUAN ================= -->
      <section class="pane" id="p-bantuan" role="tabpanel" aria-labelledby="t-bantuan" @if($currentTab !== 'bantuan') hidden @endif>
        <div class="pane-head">
          <h1>Bantuan</h1>
          <p>Cari jawabannya di sini dulu. Kalau belum ketemu, tim Ruru siap bantu.</p>
        </div>

        <label class="help-search">
          <span class="sr" style="position:absolute;left:-9999px">Cari pertanyaan</span>
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5"/></svg>
          <input type="search" id="faq-q" placeholder="Contoh: jemput, bayar, perpanjang">
        </label>

        <div class="faq" id="faq">
          <details><summary>Barang apa saja yang bisa dititipkan?<span class="pm" aria-hidden="true">+</span></summary>
            <p>Kardus, koper, buku, kipas angin, rak, dan elektronik kecil. Barang yang tidak diterima: makanan mudah busuk, bahan mudah terbakar, dan barang berharga tanpa asuransi tambahan.</p></details>
          <details><summary>Bagaimana jika barang saya rusak atau hilang?<span class="pm" aria-hidden="true">+</span></summary>
            <p>Setiap barang difoto saat diterima sebagai bukti kondisi awal. Klaim ganti rugi mengikuti batas yang tercantum di Syarat &amp; Ketentuan.</p></details>
          <details><summary>Berapa lama minimal penitipan?<span class="pm" aria-hidden="true">+</span></summary>
            <p>Harga dihitung per bulan. Titip kurang dari 30 hari tetap dihitung 1 bulan.</p></details>
          <details><summary>Apakah ada layanan jemput ke kos?<span class="pm" aria-hidden="true">+</span></summary>
            <p>Ada, untuk area Malang. Pilih "Dijemput + dipacking tim Ruru" saat memesan di Ruang Titip.</p></details>
          <details><summary>Bagaimana cara membayar?<span class="pm" aria-hidden="true">+</span></summary>
            <p>Lewat Virtual Account bank, e-wallet dan QRIS, atau minimarket. Pilih metodenya di langkah terakhir pemesanan.</p></details>
          <details><summary>Bisakah saya memperpanjang durasi penitipan?<span class="pm" aria-hidden="true">+</span></summary>
            <p>Bisa. Buka <a href="{{ route('pesanan.index') }}">Pesanan Saya</a>, cari titipanmu, lalu hubungi tim Ruru untuk perpanjangan.</p></details>
        </div>
        <p class="no-faq" id="no-faq" hidden>Belum ada jawaban untuk itu. Tanya langsung ke tim Ruru di bawah, ya.</p>

        <div class="cs">
          <img src="{{ asset('assets/ruru.webp') }}" alt="" width="92" height="108">
          <div class="t">
            <strong>Masih bingung? Tanya Ruru aja.</strong>
            <p>Tim RuangTitip membalas lewat WhatsApp setiap hari, 08.00–21.00 WIB.</p>
            <div class="acts">
              <a class="btn btn-wa btn-sm" href="https://wa.me/6285121091134?text=Halo%20RuangTitip%2C%20saya%20butuh%20bantuan" target="_blank" rel="noopener">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12a8.5 8.5 0 0 1-12.6 7.4L3 21l1.6-5.2A8.5 8.5 0 1 1 21 12z"/></svg>
                Chat via WhatsApp</a>
              <a class="btn btn-outline btn-sm" href="mailto:ruangtitipmu@gmail.com">Kirim email</a>
            </div>
          </div>
        </div>
        <p class="ver">RuangTitip v1.0.0 &middot; <a href="#">Syarat &amp; Ketentuan</a> &middot; <a href="#">Kebijakan Privasi</a></p>
      </section>
    </div>
  </div>
</main>

<div class="p-toast" id="p-toast" role="status" aria-live="polite">
  <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
  <span id="p-toast-msg">Perubahan tersimpan</span>
</div>

@push('scripts')
<script>
(function () {
  var $ = function (x) { return document.getElementById(x); };

  /* ---------- Tab ---------- */
  function openPane(name) {
    document.querySelectorAll('[data-pane]').forEach(function (b) { b.setAttribute('aria-selected', b.dataset.pane === name ? 'true' : 'false'); });
    $('p-profil').hidden = name !== 'profil';
    $('p-bantuan').hidden = name !== 'bantuan';
    var url = new URL(window.location.href);
    url.searchParams.set('tab', name);
    history.replaceState(null, '', url);
  }
  document.querySelectorAll('[data-pane]').forEach(function (b) { b.addEventListener('click', function () { openPane(b.dataset.pane); }); });

  /* ---------- Foto ---------- */
  var avatarUrl = '{{ route('profile.avatar') }}';
  var csrf = document.querySelector('meta[name="csrf-token"]').content;
  $('photo').addEventListener('change', function () {
    var f = this.files[0], err = $('photo-err');
    err.textContent = '';
    if (!f) return;
    if (!/image\/(png|jpe?g|webp)/.test(f.type)) { err.textContent = 'Formatnya harus JPG, PNG, atau WEBP.'; return; }
    if (f.size > 3 * 1024 * 1024) { err.textContent = 'Ukuran foto lebih dari 3 MB.'; return; }

    var form = new FormData();
    form.append('avatar', f);
    form.append('_token', csrf);
    err.textContent = 'Mengunggah...';
    fetch(avatarUrl, { method: 'POST', body: form })
      .then(function (r) { return r.json(); })
      .then(function (data) {
        if (data.success) {
          var html = '<img src="' + data.avatar_url + '?t=' + Date.now() + '" alt="">';
          document.querySelectorAll('[data-avatar]').forEach(function (el) { el.innerHTML = html; });
          err.textContent = '';
          showToast('Foto profil diperbarui');
        } else {
          err.textContent = 'Gagal mengunggah foto.';
        }
      })
      .catch(function () { err.textContent = 'Gagal mengunggah foto.'; });
  });

  /* ---------- Data diri + savebar ---------- */
  var saved = { name: {{ Illuminate\Support\Js::from($user->name ?? '') }}, wa: {{ Illuminate\Support\Js::from(ltrim($user->phone ?? '', '0')) }} };
  var draft = { name: saved.name, wa: saved.wa };
  var redirectAfter = {{ Illuminate\Support\Js::from($redirectAfter ?? null) }};

  function isDirty() { return draft.name !== saved.name || draft.wa !== saved.wa; }
  function changed() { $('savebar').classList.toggle('idle', !isDirty()); }
  $('name').addEventListener('input', function () { draft.name = this.value; document.querySelectorAll('[data-name]').forEach(function (el) { el.textContent = draft.name || 'Tanpa nama'; }); changed(); });
  $('wa').addEventListener('input', function () { draft.wa = this.value.replace(/\D/g, ''); changed(); });

  function validate() {
    var ok = true;
    var nf = $('name').closest('.field'), wf = $('wa').closest('.field');
    var nameEmpty = !draft.name.trim();
    nf.classList.toggle('invalid', nameEmpty); if (nameEmpty) ok = false;
    var badWa = draft.wa && !/^8\d{8,12}$/.test(draft.wa);
    wf.classList.toggle('invalid', !!badWa); if (badWa) ok = false;
    return ok;
  }

  var toastTimer;
  function showToast(msg) {
    $('p-toast-msg').textContent = msg;
    $('p-toast').classList.add('show');
    clearTimeout(toastTimer);
    toastTimer = setTimeout(function () { $('p-toast').classList.remove('show'); }, 2500);
  }

  $('save').addEventListener('click', function () {
    if (!validate()) return;
    var btn = this;
    btn.disabled = true;
    fetch('{{ route('profile.update') }}', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
      body: JSON.stringify({ name: draft.name, whatsapp: draft.wa }),
    })
      .then(function (r) { return r.json(); })
      .then(function (data) {
        btn.disabled = false;
        if (data.success) {
          saved = { name: draft.name, wa: draft.wa };
          changed();
          showToast(data.message || 'Perubahan tersimpan');
          if (redirectAfter && draft.name.trim() && draft.wa.trim()) {
            window.location.href = redirectAfter;
          }
        }
      })
      .catch(function () { btn.disabled = false; });
  });
  $('discard').addEventListener('click', function () {
    draft = { name: saved.name, wa: saved.wa };
    $('name').value = draft.name;
    $('wa').value = draft.wa;
    document.querySelectorAll('[data-name]').forEach(function (el) { el.textContent = draft.name || 'Tanpa nama'; });
    document.querySelectorAll('.field.invalid').forEach(function (f) { f.classList.remove('invalid'); });
    changed();
  });
  window.addEventListener('beforeunload', function (e) { if (isDirty()) { e.preventDefault(); e.returnValue = ''; } });

  /* ---------- Alamat ---------- */
  var addresses = {{ Illuminate\Support\Js::from($addresses ?? []) }};
  function drawAddr() {
    var box = $('addr-list'); box.innerHTML = '';
    if (!addresses.length) { box.innerHTML = '<p class="empty-addr">Belum ada alamat. Tambahkan alamat kos biar checkout lebih cepat.</p>'; return; }
    addresses.forEach(function (a) {
      var el = document.createElement('div');
      el.className = 'addr-item' + (a.isPrimary ? ' main' : '');
      el.innerHTML = '<span class="ic" aria-hidden="true"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#1C1B18" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 21s-7-6.2-7-11.5a7 7 0 0 1 14 0C19 14.8 12 21 12 21z"/><circle cx="12" cy="9.5" r="2.5"/></svg></span>' +
        '<div class="t"><strong></strong>' + (a.isPrimary ? ' <span class="pill pill-ink">Utama</span>' : '') + '<p></p>' +
        '<div class="row">' + (a.isPrimary ? '' : '<button type="button" class="link-btn" data-act="main">Jadikan utama</button>') +
        '<button type="button" class="link-btn danger" data-act="del">Hapus</button></div></div>';
      el.querySelector('strong').textContent = a.label;
      el.querySelector('p').textContent = a.address;
      el.querySelectorAll('[data-act]').forEach(function (b) {
        b.addEventListener('click', function () {
          if (b.dataset.act === 'main') {
            fetch('{{ url('/profil/alamat') }}/' + a.id + '/primary', { method: 'POST', headers: { 'X-CSRF-TOKEN': csrf } })
              .then(function () { addresses.forEach(function (x) { x.isPrimary = x.id === a.id; }); drawAddr(); });
          } else {
            if (!confirm('Hapus alamat "' + a.label + '"?')) return;
            fetch('{{ url('/profil/alamat') }}/' + a.id, { method: 'DELETE', headers: { 'X-CSRF-TOKEN': csrf } })
              .then(function (r) { return r.json(); })
              .then(function (data) {
                if (data.success) {
                  var wasPrimary = a.isPrimary;
                  addresses = addresses.filter(function (x) { return x.id !== a.id; });
                  if (wasPrimary && addresses.length) addresses[addresses.length - 1].isPrimary = true;
                  drawAddr();
                }
              });
          }
        });
      });
      box.appendChild(el);
    });
  }
  function closeForm() { $('new-form').hidden = true; $('add-addr').hidden = false; $('n-label').value = ''; $('n-full').value = ''; $('n-err').textContent = ''; }
  $('add-addr').addEventListener('click', function () { $('new-form').hidden = false; this.hidden = true; $('n-label').focus(); });
  $('n-cancel').addEventListener('click', closeForm);
  $('n-save').addEventListener('click', function () {
    if (!$('n-full').value.trim()) { $('n-err').textContent = 'Isi alamat lengkap.'; return; }
    fetch('{{ route('profile.address.store') }}', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf },
      body: JSON.stringify({ label: $('n-label').value.trim(), address: $('n-full').value.trim() }),
    })
      .then(function (r) { return r.json(); })
      .then(function (data) {
        if (data.success) {
          addresses.push({ id: data.id, label: data.label, address: data.address, isPrimary: data.isPrimary });
          closeForm(); drawAddr();
        } else {
          $('n-err').textContent = 'Gagal menyimpan alamat.';
        }
      });
  });

  /* ---------- Cari FAQ ---------- */
  $('faq-q').addEventListener('input', function () {
    var q = this.value.trim().toLowerCase(), shown = 0;
    document.querySelectorAll('#faq details').forEach(function (d) {
      var ok = d.textContent.toLowerCase().indexOf(q) !== -1;
      d.hidden = !ok; if (ok) shown++;
      if (q && ok) d.open = true;
    });
    $('no-faq').hidden = shown > 0;
  });

  drawAddr();
})();
</script>
@endpush
@endsection
