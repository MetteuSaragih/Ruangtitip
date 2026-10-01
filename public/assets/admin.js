/* RuangTitip — Panel Admin: sidebar, tab, modal, toast, helper */
(function () {
  var root = document.documentElement, KEY = 'rt_admin_sb';
  var RA = window.RA = {};

  /* ---------- Ikon ---------- */
  RA.icon = function (name, cls) {
    return '<svg class="ico' + (cls ? ' ' + cls : '') + '" viewBox="0 0 24 24" aria-hidden="true">' + ((window.RA_ICONS || {})[name] || '') + '</svg>';
  };
  RA.rp = function (n) { return 'Rp ' + Number(n || 0).toLocaleString('id-ID'); };
  RA.esc = function (s) { return String(s == null ? '' : s).replace(/[&<>"']/g, function (c) { return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c]; }); };
  RA.initials = function (n) { return String(n || '').trim().split(/\s+/).slice(0, 2).map(function (w) { return w[0] || ''; }).join('').toUpperCase(); };

  /* ---------- Sidebar ---------- */
  var mini = false;
  try { mini = localStorage.getItem(KEY) === 'mini'; } catch (e) {}
  if (mini) root.classList.add('sb-mini');

  function isMobile() { return window.matchMedia('(max-width:900px)').matches; }
  function syncToggle() {
    var t = document.getElementById('sbToggle');
    if (!t) return;
    var m = root.classList.contains('sb-mini');
    t.setAttribute('aria-expanded', String(!m));
    t.setAttribute('aria-label', m ? 'Lebarkan sidebar' : 'Ciutkan sidebar');
  }

  document.addEventListener('DOMContentLoaded', function () {
    syncToggle();
    var t = document.getElementById('sbToggle');
    t && t.addEventListener('click', function () {
      root.classList.toggle('sb-mini');
      hideTip();
      try { localStorage.setItem(KEY, root.classList.contains('sb-mini') ? 'mini' : 'full'); } catch (e) {}
      syncToggle();
    });
    var menu = document.getElementById('menuBtn');
    menu && menu.addEventListener('click', function () { root.classList.add('sb-open'); });
    var scrim = document.getElementById('scrim');
    scrim && scrim.addEventListener('click', function () { root.classList.remove('sb-open'); });

    /* Tooltip saat sidebar mini */
    document.querySelectorAll('.sb [data-tip]').forEach(function (el) {
      el.addEventListener('mouseenter', function () { showTip(el); });
      el.addEventListener('focus', function () { showTip(el); });
      el.addEventListener('mouseleave', hideTip);
      el.addEventListener('blur', hideTip);
    });

    initTabs(document);
    initModals();

    /* Flash message dari server (Laravel session) */
    document.querySelectorAll('[data-flash]').forEach(function (el) {
      RA.toast(el.dataset.flashMsg, el.dataset.flash);
    });
  });

  var tip;
  function showTip(el) {
    if (!root.classList.contains('sb-mini') || isMobile()) return;
    if (!tip) { tip = document.createElement('div'); tip.className = 'tip'; tip.setAttribute('role', 'tooltip'); document.body.appendChild(tip); }
    var r = el.getBoundingClientRect();
    tip.textContent = el.getAttribute('data-tip');
    tip.style.left = (r.right + 12) + 'px'; tip.style.top = (r.top + r.height / 2) + 'px'; tip.hidden = false;
  }
  function hideTip() { if (tip) tip.hidden = true; }

  /* ---------- Tab (client-side, role=tab) ---------- */
  function initTabs(scope) {
    scope.querySelectorAll('[role="tablist"][data-client]').forEach(function (list) {
      var tabs = [].slice.call(list.querySelectorAll('[role="tab"]'));
      tabs.forEach(function (tab, i) {
        tab.addEventListener('click', function (e) { if (tab.tagName === 'A' && !tab.hasAttribute('data-nohref')) return; e.preventDefault(); select(tab); });
        tab.addEventListener('keydown', function (e) {
          var d = e.key === 'ArrowRight' ? 1 : e.key === 'ArrowLeft' ? -1 : 0;
          if (!d) return;
          var n = tabs[(i + d + tabs.length) % tabs.length]; n.focus(); select(n);
        });
      });
      function select(tab) {
        tabs.forEach(function (t) {
          var on = t === tab;
          t.setAttribute('aria-selected', String(on)); t.tabIndex = on ? 0 : -1;
          var p = t.getAttribute('aria-controls');
          if (p) { var panel = document.getElementById(p); if (panel) panel.hidden = !on; }
        });
        list.dispatchEvent(new CustomEvent('tabchange', { detail: tab }));
      }
    });
  }

  /* ---------- Modal ---------- */
  var lastFocus;
  RA.open = function (id) {
    var m = document.getElementById(id); if (!m) return;
    lastFocus = document.activeElement; m.hidden = false;
    var f = m.querySelector('input:not([type=hidden]):not([disabled]),select,textarea,button'); f && f.focus();
  };
  RA.close = function (m) {
    if (typeof m === 'string') m = document.getElementById(m);
    if (!m) return; m.hidden = true; lastFocus && lastFocus.focus && lastFocus.focus();
  };
  function initModals() {
    document.addEventListener('click', function (e) {
      var o = e.target.closest('[data-open]'); if (o) { e.preventDefault(); RA.open(o.getAttribute('data-open')); return; }
      var c = e.target.closest('[data-close]'); if (c) { RA.close(c.closest('.modal')); return; }
      if (e.target.classList && e.target.classList.contains('modal')) RA.close(e.target);
    });
    document.addEventListener('keydown', function (e) {
      if (e.key !== 'Escape') return;
      var m = document.querySelector('.modal:not([hidden])'); if (m) RA.close(m);
      root.classList.remove('sb-open');
    });
  }

  /* Konfirmasi sederhana memakai modal. Jika opt.form diberikan, submit form itu saat OK. */
  RA.confirm = function (opt) {
    var m = document.getElementById('confirmModal');
    if (!m) {
      m = document.createElement('div'); m.className = 'modal'; m.id = 'confirmModal'; m.hidden = true;
      m.innerHTML = '<div class="dialog sm" role="dialog" aria-modal="true" aria-labelledby="cfT"><div class="d-head"><div><h2 id="cfT"></h2><p id="cfP"></p></div></div><div class="d-foot"><button class="btn btn-ghost" type="button" data-close>Batal</button><button class="btn btn-primary" type="button" id="cfOk"></button></div></div>';
      document.body.appendChild(m);
    }
    m.querySelector('#cfT').textContent = opt.title; m.querySelector('#cfP').textContent = opt.text || '';
    var ok = m.querySelector('#cfOk'); ok.textContent = opt.ok || 'Ya, lanjutkan';
    ok.className = 'btn ' + (opt.danger ? 'btn-danger' : 'btn-primary');
    ok.onclick = function () { RA.close(m); if (opt.form) opt.form.submit(); opt.onOk && opt.onOk(); };
    RA.open('confirmModal'); ok.focus();
  };

  /** Pasang konfirmasi otomatis pada form bertanda data-confirm="Judul|Teks". */
  document.addEventListener('submit', function (e) {
    var f = e.target.closest('form[data-confirm]');
    if (!f || f.dataset.confirmed) return;
    e.preventDefault();
    var parts = f.getAttribute('data-confirm').split('|');
    RA.confirm({ title: parts[0], text: parts[1] || '', ok: f.dataset.confirmOk || 'Ya, lanjutkan', danger: f.dataset.confirmDanger !== 'false', form: f });
  }, true);
  document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('form[data-confirm]').forEach(function (f) {
      f.addEventListener('submit', function () { f.dataset.confirmed = f.dataset.confirmed ? '' : '1'; });
    });
  });

  /* ---------- Toast ---------- */
  var tt;
  RA.toast = function (msg, type) {
    var el = document.getElementById('toast');
    if (!el) { el = document.createElement('div'); el.id = 'toast'; el.className = 'toast'; el.setAttribute('role', 'status'); document.body.appendChild(el); }
    el.className = 'toast' + (type ? ' ' + type : '');
    el.innerHTML = RA.icon(type === 'error' ? 'x' : 'check') + '<span>' + RA.esc(msg) + '</span>';
    el.classList.add('show'); clearTimeout(tt); tt = setTimeout(function () { el.classList.remove('show'); }, 3200);
  };

  /* ---------- Unggah foto dengan pratinjau (mendukung foto yang sudah tersimpan di server) ---------- */
  RA.photoInput = function (dropId, inputId, prevId, max, existingCount) {
    var input = document.getElementById(inputId), prev = document.getElementById(prevId), drop = document.getElementById(dropId), files = [];
    existingCount = existingCount || 0;
    drop.addEventListener('click', function () { input.click(); });
    drop.addEventListener('keydown', function (e) { if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); input.click(); } });
    input.addEventListener('change', function () {
      var room = Math.max((max || 10) - existingCount - files.length, 0);
      [].slice.call(input.files).forEach(function (f) { if (room > 0 && /image\//.test(f.type)) { files.push(f); room--; } });
      rebuild(); draw();
    });
    function rebuild() {
      var dt = new DataTransfer();
      files.forEach(function (f) { dt.items.add(f); });
      input.files = dt.files;
    }
    function draw() {
      prev.innerHTML = files.map(function (f, i) {
        return '<div class="thumb"><img src="' + URL.createObjectURL(f) + '" alt=""><button type="button" aria-label="Hapus foto" data-i="' + i + '">&times;</button></div>';
      }).join('');
    }
    prev.addEventListener('click', function (e) { var b = e.target.closest('button[data-i]'); if (b) { files.splice(+b.dataset.i, 1); rebuild(); draw(); } });
    draw();
    return {
      count: function () { return existingCount + files.length; },
      reset: function (newExisting) { files = []; existingCount = newExisting == null ? existingCount : newExisting; rebuild(); draw(); },
    };
  };

  /* ---------- Empty state ---------- */
  RA.empty = function (icon, title, text) {
    return '<div class="empty"><div class="e-ico">' + RA.icon(icon) + '</div><b>' + title + '</b><p>' + text + '</p></div>';
  };
})();
