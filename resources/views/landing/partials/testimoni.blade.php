<section class="sec" id="cerita" aria-labelledby="t-title">
    <div class="wrap">
        <div class="t-head">
            <h2 id="t-title">Mereka sudah pulang kampung dengan tenang.</h2>
            <div class="t-nav">
                <button id="tPrev" type="button" aria-label="Testimoni sebelumnya"><svg aria-hidden="true" style="transform:scaleX(-1)"><use href="#arr"/></svg></button>
                <button id="tNext" type="button" aria-label="Testimoni berikutnya"><svg aria-hidden="true"><use href="#arr"/></svg></button>
            </div>
        </div>
        <div class="t-track" id="tTrack" tabindex="0" aria-label="Testimoni penitip">
            @foreach ([
                ['Magang 3 bulan', 'Magang 3 bulan di Jakarta, semua barang aman di RuangTitip. Balik ke Malang, kondisinya sama persis.', 'MR', 'M. Rizal A.', '#FFC93C'],
                ['KKN', 'Dulu tiap KKN selalu bingung naruh barang. Sekarang tinggal pesan, semuanya beres lewat website.', 'SA', 'Siti Aisyah', '#fff'],
                ['Libur semester', 'Ada foto kondisi tiap barang. Buat yang pulang kampung lama, ini ngebantu banget.', 'DK', 'Dimas Kurnia', '#2DD4BF'],
            ] as [$ctx, $quote, $initials, $name, $color])
                <figure class="t-card">
                    <div><span class="ctx">{{ $ctx }}</span><blockquote>&ldquo;{{ $quote }}&rdquo;</blockquote></div>
                    <figcaption class="who"><span class="av" style="background:{{ $color }}">{{ $initials }}</span><span><b>{{ $name }}</b><span>Penitip</span></span></figcaption>
                </figure>
            @endforeach
        </div>
        <div class="t-bar" aria-hidden="true"><i id="tBar"></i></div>
    </div>
</section>
