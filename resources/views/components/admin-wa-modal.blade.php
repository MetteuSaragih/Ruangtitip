{{-- Modal kirim pesan WhatsApp dgn template siap pakai. Satu instance per halaman, dipakai lewat openWaModal(phone, templates). --}}
<div id="wa-modal" class="fixed inset-0 z-50 hidden items-center justify-center p-6"
     style="background:rgba(0,0,0,0.78);backdrop-filter:blur(6px);"
     onclick="if(event.target===this) closeWaModal()">
    <div class="w-full max-w-md rounded-3xl overflow-hidden"
         style="background:rgba(12,6,24,0.99);border:1px solid rgba(139,92,246,0.25);box-shadow:0 24px 80px rgba(0,0,0,0.75);">
        <div class="px-7 py-5 flex items-center justify-between" style="border-bottom:1px solid rgba(255,255,255,0.07);background:rgba(37,211,102,0.08);">
            <div>
                <h2 class="text-base font-extrabold text-white font-display">Kirim Pesan WhatsApp</h2>
                <p id="wa-modal-subtitle" class="text-xs mt-0.5" style="color:rgba(255,255,255,0.38);">Pilih template, lalu kirim</p>
            </div>
            <button onclick="closeWaModal()" class="w-9 h-9 rounded-xl flex items-center justify-center hover:bg-white/10 transition-colors" style="color:rgba(255,255,255,0.4);">✕</button>
        </div>

        <div class="p-6">
            <div id="wa-template-list" class="space-y-2 mb-4 max-h-56 overflow-y-auto"></div>

            <label class="block text-[11px] font-semibold mb-2" style="color:rgba(255,255,255,0.55);">Pesan yang akan dikirim</label>
            <textarea id="wa-message-text" rows="5"
                      class="w-full px-4 py-3 rounded-xl text-sm text-white placeholder:text-white/20 outline-none resize-none"
                      style="background:rgba(255,255,255,0.06);border:1.5px solid rgba(255,255,255,0.1);"
                      placeholder="Pilih salah satu template di atas, atau tulis pesan bebas..."></textarea>
        </div>

        <div class="px-7 py-5 flex gap-3" style="border-top:1px solid rgba(255,255,255,0.07);">
            <button type="button" onclick="closeWaModal()" class="flex-1 py-3.5 rounded-2xl text-sm font-semibold transition-all hover:bg-white/5" style="border:1.5px solid rgba(255,255,255,0.14);color:rgba(255,255,255,0.7);">Batal</button>
            <button type="button" onclick="sendWaMessage()" class="flex-[2] py-3.5 rounded-2xl text-sm font-bold text-white flex items-center justify-center gap-2 transition-all hover:scale-[1.01]" style="background:linear-gradient(135deg,#25d366,#128c7e);box-shadow:0 6px 20px rgba(37,211,102,0.4);">
                <x-lucide-message-circle class="w-4 h-4" /> Kirim via WhatsApp
            </button>
        </div>
    </div>
</div>

<script>
let waModalPhone = null;

function openWaModal(phone, templates, subtitle) {
    waModalPhone = phone;
    document.getElementById('wa-modal-subtitle').textContent = subtitle || 'Pilih template, lalu kirim';
    document.getElementById('wa-message-text').value = '';

    const list = document.getElementById('wa-template-list');
    list.innerHTML = '';

    (templates || []).forEach((tpl) => {
        const btn = document.createElement('button');
        btn.type = 'button';
        btn.className = 'w-full text-left px-4 py-3 rounded-xl text-xs transition-all hover:scale-[1.01]';
        btn.style.background = 'rgba(255,255,255,0.04)';
        btn.style.border = '1px solid rgba(255,255,255,0.09)';
        btn.style.color = 'rgba(255,255,255,0.8)';
        btn.innerHTML = '<span style="font-weight:700;color:#a78bfa;">' + tpl.label + '</span><br>' +
            '<span style="color:rgba(255,255,255,0.45);font-size:11px;">' + tpl.text.slice(0, 70) + (tpl.text.length > 70 ? '…' : '') + '</span>';
        btn.addEventListener('click', () => {
            document.getElementById('wa-message-text').value = tpl.text;
        });
        list.appendChild(btn);
    });

    const modal = document.getElementById('wa-modal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeWaModal() {
    const modal = document.getElementById('wa-modal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

function sendWaMessage() {
    const text = document.getElementById('wa-message-text').value.trim();
    if (!text || !waModalPhone) return;
    window.open('https://wa.me/' + waModalPhone + '?text=' + encodeURIComponent(text), '_blank');
    closeWaModal();
}
</script>
