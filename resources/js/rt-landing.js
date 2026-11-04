/**
 * Perilaku landing page: navbar, menu mobile, animasi bar, tab Preloved,
 * slider testimoni, FAQ, dan survei (dikirim ke route survey.store).
 */
(function () {
    'use strict';

    const $ = (s, c = document) => c.querySelector(s);
    const $$ = (s, c = document) => [...c.querySelectorAll(s)];
    const el = (tag, cls, text) => {
        const e = document.createElement(tag);
        if (cls) e.className = cls;
        if (text != null) e.textContent = text;
        return e;
    };

    /* ----- Navbar ----- */
    const nav = $('#nav');
    if (nav) addEventListener('scroll', () => nav.classList.toggle('scrolled', scrollY > 10), { passive: true });

    const menuBtn = $('#menuBtn');
    const menu = $('#mMenu');
    if (menuBtn && menu) {
        menuBtn.addEventListener('click', () => {
            const open = menu.classList.toggle('open');
            menuBtn.setAttribute('aria-expanded', open);
        });
        $$('a', menu).forEach((a) =>
            a.addEventListener('click', () => {
                menu.classList.remove('open');
                menuBtn.setAttribute('aria-expanded', false);
            }),
        );
    }

    /* ----- Animasi bar saat terlihat ----- */
    const cardA = $('#cardA');
    if (cardA) {
        if ('IntersectionObserver' in window) {
            const io = new IntersectionObserver(
                (entries) => entries.forEach((e) => {
                    if (e.isIntersecting) {
                        cardA.classList.add('in');
                        io.disconnect();
                    }
                }),
                { threshold: 0.4 },
            );
            io.observe(cardA);
        } else {
            cardA.classList.add('in');
        }
    }

    /* ----- Preloved: jual / beli ----- */
    const PL = {
        jual: {
            s: [
                ['Ajukan barangnya', 'Kirim foto, deskripsi, kondisi, dan kekurangannya lewat website.'],
                ['Cek bareng petugas', 'Lolos seleksi awal? Antar sesuai jadwal, kondisi dan fungsinya dicek bersama.'],
                ['Terima tawaran', 'Setuju dengan harganya, lanjut. Nggak setuju, barang tetap milikmu.'],
                ['Dibayar penuh', 'Uang dibayar 100% saat serah terima, lengkap dengan buktinya.'],
            ],
            n: 'Barang yang sedang kamu titipkan juga bisa dijual. Tagihan titipnya dilunasi dulu.',
        },
        beli: {
            s: [
                ['Lihat katalog', 'Foto asli, kondisi, kelengkapan, dan kekurangannya ditulis jujur.'],
                ['Pilih cara terima', 'Ambil di gudang, lewat perwakilan, atau dikirim via Biteship.'],
                ['Bayar dalam 24 jam', 'Barang dikunci untukmu. Lewat 24 jam, barang tersedia lagi.'],
                ['Cek dulu, baru selesai', 'Ada 24 jam setelah barang diterima untuk komplain kalau tidak sesuai.'],
            ],
            n: 'Paling enak ambil langsung di gudang, jadi kamu bisa cek barangnya sendiri.',
        },
    };

    const seg = $$('.seg button');
    const preList = $('#preList');
    const preNote = $('#preNote');

    function showPL(i, focus) {
        if (!seg.length || !preList) return;
        seg.forEach((b, j) => {
            b.setAttribute('aria-selected', i === j);
            b.tabIndex = i === j ? 0 : -1;
        });
        if (focus) seg[i].focus();
        const m = PL[seg[i].dataset.m];
        preList.innerHTML = '';
        m.s.forEach((x, k) => {
            const li = el('li', 'fade');
            li.style.animationDelay = k * 0.06 + 's';
            const d = el('div');
            d.append(el('h3', null, x[0]), el('p', null, x[1]));
            li.append(el('span', 'n', k + 1), d);
            preList.appendChild(li);
        });
        preNote.textContent = m.n;
    }

    seg.forEach((b, i) => {
        b.addEventListener('click', () => showPL(i));
        b.addEventListener('keydown', (e) => {
            if (e.key === 'ArrowRight' || e.key === 'ArrowLeft') {
                e.preventDefault();
                showPL(i ? 0 : 1, true);
            }
        });
    });
    showPL(0);

    /* ----- Testimoni ----- */
    const track = $('#tTrack');
    const bar = $('#tBar');
    const prev = $('#tPrev');
    const next = $('#tNext');
    if (track && bar && prev && next) {
        const step = () => track.querySelector('.t-card').getBoundingClientRect().width + 16;
        prev.onclick = () => track.scrollBy({ left: -step(), behavior: 'smooth' });
        next.onclick = () => track.scrollBy({ left: step(), behavior: 'smooth' });
        const update = () => {
            const max = track.scrollWidth - track.clientWidth;
            const r = max > 0 ? track.scrollLeft / max : 0;
            const w = Math.max(track.clientWidth / track.scrollWidth, 0.2);
            bar.style.width = w * 100 + '%';
            bar.style.transform = 'translateX(' + (r * (1 - w) / w * 100) + '%)';
            prev.disabled = track.scrollLeft < 4;
            next.disabled = track.scrollLeft > max - 4;
        };
        track.addEventListener('scroll', update, { passive: true });
        addEventListener('resize', update);
        update();
    }

    /* ----- FAQ ----- */
    const FAQ = [
        ['Saya masih perlu bayar kos selama pulang kampung?', 'Nggak perlu. Barangmu kami simpan, jadi kamar kos bisa kamu lepas selama pergi.'],
        ['Isi kardus saya dibuka?', 'Tidak. Kami hanya mengecek jumlah, ukuran, kelayakan kemasan, dan kondisi luar. Setelah itu kardus dikode, disegel, dan difoto.'],
        ['Apakah barang dijemput ke kos?', 'Untuk saat ini barang diantar sendiri ke gudang, boleh lewat teman atau jasa yang kamu atur. Saat mengambil, barang bisa kami kirim lewat layanan Kirim Barang Saya.'],
        ['Kalau saya telat mengantar, masa titipnya mundur?', 'Tidak. Masa titip dihitung dari tanggal mulai di pesanan, jadi usahakan datang sesuai jadwal.'],
        ['Bisa ambil sebagian barang dulu?', 'Bisa. Lunasi sisa tagihan untuk barang yang diambil saja. Barang lainnya tetap tersimpan.'],
        ['Boleh diambil teman?', 'Boleh. Temanmu cukup membawa nomor pesanan, lalu kamu konfirmasi lewat website sebelum barang diserahkan.'],
    ];
    const faqList = $('#faqList');
    if (faqList) {
        FAQ.forEach((f, i) => {
            const li = el('li');
            const b = el('button', 'fq');
            b.type = 'button';
            b.id = 'fq' + i;
            b.setAttribute('aria-expanded', i === 0);
            b.setAttribute('aria-controls', 'fa' + i);
            b.append(el('span', null, f[0]), el('span', 'pm'));
            const a = el('div', 'fa');
            a.id = 'fa' + i;
            a.setAttribute('role', 'region');
            a.setAttribute('aria-labelledby', 'fq' + i);
            const d = el('div');
            d.appendChild(el('p', null, f[1]));
            a.appendChild(d);
            b.onclick = () => b.setAttribute('aria-expanded', b.getAttribute('aria-expanded') !== 'true');
            li.append(b, a);
            faqList.appendChild(li);
        });
    }

    /* ----- Survei ----- */
    const form = $('#survey-form');
    if (form) {
        const answers = { minat: null, layanan: [], harga: null };
        const step2 = $('#step2');
        const sendBtn = $('#send-btn');
        const helper = $('#helper');
        const thanks = $('#thanks');

        const copy = {
            tertarik: ['Makasih! Kamu masuk daftar prioritas.', 'Kami kabari lebih dulu soal promo penitip pertama. Mau langsung atur jadwal? Masuk dan buat pesanan.'],
            mungkin: ['Makasih, masukanmu kami catat.', 'Jawaban soal harga ini bantu kami menyusun paket yang lebih pas. Ada pertanyaan? Chat kami.'],
            tidak: ['Makasih sudah jujur.', 'Kalau nanti butuh tempat titip barang, Ruru ada di sini. Simpan saja halaman ini.'],
        };

        function update() {
            const ready = answers.minat !== null;
            sendBtn.disabled = !ready;
            helper.textContent = ready ? 'Pertanyaan 2 dan 3 opsional.' : 'Pilih satu jawaban dulu.';
            step2.classList.toggle('show', ready && answers.minat !== 'tidak');
        }

        $$('[data-group]', form).forEach((group) => {
            const key = group.getAttribute('data-group');
            const single = group.hasAttribute('data-single');
            $$('button', group).forEach((btn) => {
                btn.addEventListener('click', () => {
                    const val = btn.getAttribute('data-value');
                    if (single) {
                        $$('button', group).forEach((b) => b.setAttribute('aria-pressed', 'false'));
                        btn.setAttribute('aria-pressed', 'true');
                        answers[key] = val;
                    } else {
                        const on = btn.getAttribute('aria-pressed') !== 'true';
                        btn.setAttribute('aria-pressed', on ? 'true' : 'false');
                        answers[key] = on ? answers[key].concat(val) : answers[key].filter((v) => v !== val);
                    }
                    update();
                });
            });
        });

        form.addEventListener('submit', (e) => {
            e.preventDefault();
            if (!answers.minat) return;

            fetch(form.getAttribute('action'), {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    Accept: 'application/json',
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').content,
                },
                body: JSON.stringify(answers),
            });

            $('#thanks-title').textContent = copy[answers.minat][0];
            $('#thanks-body').textContent = copy[answers.minat][1];
            form.style.display = 'none';
            thanks.classList.add('show');
        });
    }
})();
