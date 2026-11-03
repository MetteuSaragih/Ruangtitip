{{-- Animasi Ruru naik motor. Dipakai di hero landing dan panel kiri login. $say = teks balon. --}}
<div class="scene" aria-hidden="true">
    <svg viewBox="0 0 1440 320" preserveAspectRatio="xMidYMax slice">
        <g class="far"><use href="#skyline"/><use href="#skyline" x="720"/><use href="#skyline" x="1440"/></g>
        <g class="mid"><use href="#street"/><use href="#street" x="720"/><use href="#street" x="1440"/></g>
        <rect x="0" y="230" width="1440" height="10" fill="#0A4F4A"/>
        <rect x="0" y="240" width="1440" height="80" fill="#0C2B2A"/>
        <g class="lane" fill="#FFC93C">
            @for ($i = 0; $i < 20; $i++)
                <rect x="{{ $i * 80 }}" y="277" width="40" height="5" rx="2.5"/>
            @endfor
        </g>
        <ellipse cx="724" cy="272" rx="140" ry="9" fill="#000" opacity=".3"/>
        <use href="#rider" transform="translate(629 93) scale(1.3)"/>
        <g transform="translate(838 34)">
            <g class="say">
                <path d="M0 0 h132 a14 14 0 0 1 14 14 v20 a14 14 0 0 1 -14 14 h-118 l-14 10 z" fill="#fff" stroke="#0C2B2A" stroke-width="2.5"/>
                <text x="73" y="30" font-size="16" font-family="Bricolage Grotesque, sans-serif" font-weight="700" fill="#0C2B2A" text-anchor="middle">{{ $say ?? 'Otw gudang!' }}</text>
            </g>
        </g>
    </svg>
</div>
