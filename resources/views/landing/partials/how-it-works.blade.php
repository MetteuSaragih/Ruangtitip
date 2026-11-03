<section class="sec how" id="cara-kerja" aria-labelledby="h-title">
    <div class="wrap">
        <div class="head">
            <h2 id="h-title">Empat langkah. Selebihnya urusan kami.</h2>
            <p class="lede">Dari pesan sampai ambil, semuanya lewat website. Nggak perlu chat bolak-balik.</p>
        </div>
        <ol class="steps">
            @foreach ([
                ['st-cal', 'Pesan online', 'Pilih ukuran, jumlah kardus, periode, dan jadwal antar. Bayar 50% dalam 24 jam.'],
                ['st-moto', 'Antar ke gudang', 'Bawa kardus yang sudah dikemas sesuai jadwal, sendiri atau lewat teman.'],
                ['st-cam', 'Kami amankan', 'Dicek, dikode, disegel, difoto, lalu masuk rak yang tercatat.'],
                ['st-hand', 'Ambil kapan saja', 'Lunasi sisanya, lalu ambil, wakilkan, atau minta dikirim. Mau lebih lama? Perpanjang dari HP.'],
            ] as $i => [$icon, $title, $body])
                <li class="step">
                    <div class="step-ic"><svg class="stk" aria-hidden="true"><use href="#{{ $icon }}"/></svg><span class="step-n">{{ $i + 1 }}</span></div>
                    <h3>{{ $title }}</h3>
                    <p>{{ $body }}</p>
                </li>
            @endforeach
        </ol>
        <div class="rules">
            <div class="rule"><span class="tag">24 jam</span><div><b>Batas bayar pertama</b>Lewat dari itu, pesanan kedaluwarsa dan slotnya dilepas.</div></div>
            <div class="rule"><span class="tag">Tetap</span><div><b>Tanggal mulai</b>Masa titip dihitung dari tanggal di pesanan, walau kamu datang telat.</div></div>
            <div class="rule"><span class="tag">0 kali</span><div><b>Antar ulang saat perpanjang</b>Barang tetap di rak yang sama, cukup bayar dari HP.</div></div>
        </div>
    </div>
</section>
