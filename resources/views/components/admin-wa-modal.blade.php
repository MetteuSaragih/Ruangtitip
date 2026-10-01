{{-- Modal kirim pesan WhatsApp dengan template siap pakai. Satu instance per halaman, dipakai lewat openWaModal(phone, templates, subtitle). --}}
<div id="wa-modal" class="modal" hidden>
  <div class="dialog" role="dialog" aria-modal="true" aria-labelledby="waModalT">
    <div class="d-head">
      <div><h2 id="waModalT">Kirim pesan WhatsApp</h2><p id="wa-modal-subtitle">Pilih template, lalu kirim</p></div>
      <button class="icon-btn" type="button" onclick="closeWaModal()" aria-label="Tutup"><svg class="ico" viewBox="0 0 24 24" aria-hidden="true"><path d="M18 6 6 18M6 6l12 12"/></svg></button>
    </div>
    <div class="d-body">
      <div id="wa-template-list" style="display:grid;gap:8px;max-height:224px;overflow-y:auto"></div>
      <div class="field">
        <label for="wa-message-text">Pesan yang akan dikirim</label>
        <textarea class="textarea" id="wa-message-text" rows="5" placeholder="Pilih salah satu template di atas, atau tulis pesan bebas..."></textarea>
      </div>
    </div>
    <div class="d-foot">
      <button class="btn btn-ghost" type="button" onclick="closeWaModal()">Batal</button>
      <button class="btn btn-green" type="button" onclick="sendWaMessage()"><svg class="ico sm" viewBox="0 0 24 24" aria-hidden="true"><path d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2h-5l-5 5v-5z"/></svg> Kirim via WhatsApp</button>
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
        btn.className = 'chip';
        btn.style.cssText = 'width:100%;text-align:left;height:auto;padding:10px 14px;white-space:normal;line-height:1.4';
        btn.innerHTML = '<span style="display:block;font-weight:700;color:var(--tape-dark)">' + tpl.label + '</span>' +
            '<span style="display:block;color:var(--muted);font-size:12px;margin-top:2px">' + tpl.text.slice(0, 70) + (tpl.text.length > 70 ? '…' : '') + '</span>';
        btn.addEventListener('click', () => {
            document.getElementById('wa-message-text').value = tpl.text;
        });
        list.appendChild(btn);
    });

    (window.RA ? window.RA.open : function (id) { document.getElementById(id).hidden = false; })('wa-modal');
}

function closeWaModal() {
    (window.RA ? window.RA.close : function (id) { document.getElementById(id).hidden = true; })('wa-modal');
}

function sendWaMessage() {
    const text = document.getElementById('wa-message-text').value.trim();
    if (!text || !waModalPhone) return;
    window.open('https://wa.me/' + waModalPhone + '?text=' + encodeURIComponent(text), '_blank');
    closeWaModal();
}
</script>
