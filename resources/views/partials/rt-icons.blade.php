{{-- Sprite ikon RuangTitip. Panggil dengan <svg class="ic"><use href="#n-home"/></svg> --}}
<svg width="0" height="0" style="position:absolute" aria-hidden="true" focusable="false">
    <defs>
        <symbol id="logo" viewBox="0 0 36 36">
            <rect width="36" height="36" rx="11" fill="#FFC93C"/>
            <path d="M7 16 L18 8 L29 16 V28 H7 Z" fill="#0C2B2A"/>
            <rect x="12.5" y="18" width="11" height="10" rx="1.5" fill="#D9A066"/>
            <rect x="16.5" y="18" width="3" height="4.5" fill="#0F766E"/>
        </symbol>
        <symbol id="box" viewBox="0 0 44 40">
            <path d="M4 12 L22 4 L40 12 L40 34 L22 39 L4 34 Z" fill="#D9A066"/>
            <path d="M22 16 L40 12 L40 34 L22 39 Z" fill="#B9814A"/>
            <path d="M4 12 L22 16 L40 12 L22 4 Z" fill="#E7B984"/>
            <path d="M11 9 L29 13 L29 18 L11 14 Z" fill="#0F766E"/>
        </symbol>

        {{-- Navigasi --}}
        <symbol id="n-side-close" viewBox="0 0 24 24"><g fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><rect x="3" y="4" width="18" height="16" rx="3.5"/><path d="M9 4v16M16 9.5 13.5 12l2.5 2.5"/></g></symbol>
        <symbol id="n-side-open" viewBox="0 0 24 24"><g fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><rect x="3" y="4" width="18" height="16" rx="3.5"/><path d="M9 4v16M13.5 9.5 16 12l-2.5 2.5"/></g></symbol>
        <symbol id="n-home" viewBox="0 0 24 24"><path d="M2.6 10.4 12 2.8l9.4 7.6c.4.3.1 1-.4 1H19.5V20a1.5 1.5 0 0 1-1.5 1.5H6A1.5 1.5 0 0 1 4.5 20v-8.6H3c-.5 0-.8-.7-.4-1z" fill="currentColor"/><rect x="9.3" y="13" width="5.4" height="8.5" rx="1.4" fill="var(--acc,#FFC93C)"/></symbol>
        <symbol id="n-ware" viewBox="0 0 24 24"><path d="M2.5 8.8 12 3.3l9.5 5.5V21h-19z" fill="currentColor"/><rect x="6.3" y="11.5" width="11.4" height="9.5" rx="1.2" fill="var(--acc,#FFC93C)"/><path d="M6.3 14.6h11.4M6.3 17.7h11.4" stroke="currentColor" stroke-width="1.5"/></symbol>
        <symbol id="n-receipt" viewBox="0 0 24 24"><path d="M5 2.5h14V21.5l-2.3-1.6-2.3 1.6-2.4-1.6-2.4 1.6-2.3-1.6L5 21.5z" fill="currentColor"/><rect x="8" y="6.5" width="8" height="2.6" rx="1.3" fill="var(--acc,#FFC93C)"/><rect x="8" y="11.3" width="5.5" height="2.6" rx="1.3" fill="var(--acc,#FFC93C)"/></symbol>
        <symbol id="n-bell" viewBox="0 0 24 24"><path d="M12 2.5a6.5 6.5 0 0 1 6.5 6.5v4.3l2 3.2H3.5l2-3.2V9A6.5 6.5 0 0 1 12 2.5z" fill="currentColor"/><path d="M8.6 18h6.8a3.4 3.4 0 0 1-6.8 0z" fill="var(--acc,#FFC93C)"/><circle cx="12" cy="2.8" r="1.6" fill="var(--acc,#FFC93C)"/></symbol>
        <symbol id="n-tag" viewBox="0 0 24 24"><path d="M2.8 4.3A1.5 1.5 0 0 1 4.3 2.8h7.6l9.3 9.3a1.5 1.5 0 0 1 0 2.1l-7 7a1.5 1.5 0 0 1-2.1 0L2.8 12z" fill="currentColor"/><circle cx="7.9" cy="7.9" r="2.6" fill="var(--acc,#FFC93C)"/><path d="M10.5 15.5l5-5" stroke="var(--acc,#FFC93C)" stroke-width="2.2" stroke-linecap="round"/></symbol>
        <symbol id="n-tape" viewBox="0 0 24 24"><path d="M10 16.5h12v4H10z" fill="currentColor"/><circle cx="10" cy="11" r="8.5" fill="currentColor"/><circle cx="10" cy="11" r="3.4" fill="var(--acc,#FFC93C)"/></symbol>
        <symbol id="n-cart" viewBox="0 0 24 24"><path d="M2.5 3.5h3l2.2 11h11l2-8H7" fill="none" stroke="currentColor" stroke-width="2.3" stroke-linejoin="round" stroke-linecap="round"/><path d="M7.3 6.5h13.4l-2 8H8.9z" fill="currentColor"/><circle cx="9" cy="19.5" r="2" fill="var(--acc,#FFC93C)"/><circle cx="17" cy="19.5" r="2" fill="var(--acc,#FFC93C)"/></symbol>
        <symbol id="n-user" viewBox="0 0 24 24"><path d="M3.6 21c.6-4.5 4-7.2 8.4-7.2s7.8 2.7 8.4 7.2z" fill="currentColor"/><circle cx="12" cy="7.8" r="4.8" fill="currentColor"/><circle cx="12" cy="7.8" r="2" fill="var(--acc,#FFC93C)"/></symbol>
        <symbol id="n-chat" viewBox="0 0 24 24"><path d="M3 11.5C3 6.8 7 3.5 12 3.5s9 3.3 9 8-4 8-9 8c-1.2 0-2.3-.2-3.3-.5L4 21l1.2-4C3.8 15.6 3 13.6 3 11.5z" fill="currentColor"/><circle cx="8.2" cy="11.5" r="1.6" fill="var(--acc,#FFC93C)"/><circle cx="12" cy="11.5" r="1.6" fill="var(--acc,#FFC93C)"/><circle cx="15.8" cy="11.5" r="1.6" fill="var(--acc,#FFC93C)"/></symbol>
        <symbol id="n-logout" viewBox="0 0 24 24"><path d="M4 4.5A1.5 1.5 0 0 1 5.5 3H13a1.5 1.5 0 0 1 1.5 1.5V19.5A1.5 1.5 0 0 1 13 21H5.5A1.5 1.5 0 0 1 4 19.5z" fill="currentColor"/><circle cx="11" cy="12" r="1.3" fill="var(--acc,#FFC93C)"/><path d="M15 12h6.5M18.5 8.8 21.7 12l-3.2 3.2" fill="none" stroke="var(--acc,#FFC93C)" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"/></symbol>

        {{-- Umum --}}
        <symbol id="i-check" viewBox="0 0 24 24"><path d="M5 12.5l4.5 4.5L19 7.5" fill="none" stroke="currentColor" stroke-width="2.8" stroke-linecap="round" stroke-linejoin="round"/></symbol>
        <symbol id="i-x" viewBox="0 0 24 24"><path d="M6 6l12 12M18 6 6 18" stroke="currentColor" stroke-width="2.6" stroke-linecap="round"/></symbol>
        <symbol id="i-info" viewBox="0 0 24 24"><circle cx="12" cy="12" r="9" fill="none" stroke="currentColor" stroke-width="2"/><path d="M12 11v6M12 7.5v.5" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"/></symbol>
        <symbol id="i-warn" viewBox="0 0 24 24"><path d="M12 3.5 2.5 20h19z" fill="none" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/><path d="M12 10v4.5M12 17v.3" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"/></symbol>
        <symbol id="i-clock" viewBox="0 0 24 24"><circle cx="12" cy="12" r="9" fill="none" stroke="currentColor" stroke-width="2"/><path d="M12 7v5l3.5 2" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></symbol>
        <symbol id="i-arrow" viewBox="0 0 24 24"><path d="M4 12h15M13 5.5l6.5 6.5-6.5 6.5" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round"/></symbol>
        <symbol id="i-chev" viewBox="0 0 24 24"><path d="M9 6l6 6-6 6" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/></symbol>
        <symbol id="i-back" viewBox="0 0 24 24"><path d="M15 6l-6 6 6 6" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/></symbol>
        <symbol id="i-mail" viewBox="0 0 24 24"><g fill="none" stroke="currentColor" stroke-width="2" stroke-linejoin="round"><rect x="3" y="5" width="18" height="14" rx="2.5"/><path d="M3.5 6.5 12 13l8.5-6.5"/></g></symbol>
        <symbol id="i-cal" viewBox="0 0 24 24"><g fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><rect x="3.5" y="5" width="17" height="15.5" rx="3"/><path d="M3.5 10h17M8 3v4M16 3v4"/></g></symbol>
        <symbol id="i-truck" viewBox="0 0 24 24"><g fill="none" stroke="currentColor" stroke-width="2" stroke-linejoin="round"><path d="M2 6h12v10H2zM14 9h4l4 4v3h-8"/><circle cx="6" cy="18" r="2"/><circle cx="18" cy="18" r="2"/></g></symbol>
        <symbol id="i-pin" viewBox="0 0 24 24"><g fill="none" stroke="currentColor" stroke-width="2"><path d="M12 21s-7-6.2-7-11.5a7 7 0 0 1 14 0C19 14.8 12 21 12 21z"/><circle cx="12" cy="9.5" r="2.5"/></g></symbol>
    </defs>
</svg>
