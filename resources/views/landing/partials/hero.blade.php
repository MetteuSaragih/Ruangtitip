<section class="hero" aria-labelledby="hero-title">
    <div class="wrap hero-copy">
        <span class="kicker"><b>Malang</b>Penitipan barang khusus mahasiswa</span>
        <h1 id="hero-title"><span>Kamarnya kosong.</span><span>Tagihannya enggak.</span></h1>
        <p class="lede">Mau libur semester, KKN, atau magang di luar kota? Lepas kosmu, titip barangnya ke kami. Mulai Rp5.000 per barang per hari, disegel, difoto, dan dijaga sampai kamu balik.</p>
        <div class="hero-actions">
            <a class="btn btn-sun" href="{{ route('login') }}">Titip barang sekarang</a>
            <a class="btn btn-line" href="#cara-kerja">Lihat cara kerja</a>
        </div>
        <ul class="proof">
            <li><strong>150+</strong>penitip aktif</li>
            <li><strong>500+</strong>barang tersimpan</li>
            <li><strong>Rp5.000</strong>per hari</li>
        </ul>
    </div>

    @include('landing.partials.scene', ['say' => 'Otw gudang!'])
</section>

<div class="ticker" aria-label="Cocok untuk">
    <div class="ticker-track">
        @foreach ([false, true] as $hidden)
            <ul @if ($hidden) aria-hidden="true" @endif>
                @foreach (['Libur semester', 'KKN', 'Magang luar kota', 'Pindah kos', 'Pulang kampung lama'] as $label)
                    <li>{{ $label }}<svg aria-hidden="true"><use href="#box"/></svg></li>
                @endforeach
            </ul>
        @endforeach
    </div>
</div>
