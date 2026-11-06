@php
    $badge = match ($kind) {
        'order' => ['Pesanan baru', '#D7F2EE', '#0B5D57'],
        'action' => ['Perlu tindakan', '#FFF4D6', '#7A5400'],
        default => ['Info', '#E6EFEE', '#41605D'],
    };
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="color-scheme" content="light">
    <meta name="supported-color-schemes" content="light">
    <title>{{ $headline }}</title>
</head>
<body style="margin:0; padding:0; background:#F3FAF9; font-family:'Plus Jakarta Sans','Segoe UI',Arial,sans-serif; color:#0C2B2A;">
    <div style="display:none; max-height:0; overflow:hidden; opacity:0; font-size:1px; line-height:1px; color:#F3FAF9;">
        {{ $headline }}{{ count($rows) ? ': ' . $rows[0][1] : '' }}
    </div>

    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#F3FAF9;">
        <tr>
            <td align="center" style="padding:28px 16px;">
                <table role="presentation" width="100%" cellpadding="0" cellspacing="0"
                       style="max-width:520px; background:#FFFFFF; border:1px solid #CFE5E1; border-radius:20px; overflow:hidden;">

                    <tr>
                        <td align="center" style="background:#0F766E; padding:22px 28px;">
                            <img src="{{ asset('assets/logo/logo-horizontal-light.png') }}" alt="RuangTitip Admin" width="130" style="display:block; width:130px; max-width:100%; height:auto; border:0; margin:0 auto; color:#FFFFFF; font-size:20px; font-weight:800;">
                        </td>
                    </tr>
                    <tr><td style="background:#FFC93C; height:4px; line-height:4px; font-size:0;">&nbsp;</td></tr>

                    <tr>
                        <td style="padding:28px 28px 8px;">
                            <span style="display:inline-block; padding:4px 12px; border-radius:999px; background:{{ $badge[1] }}; color:{{ $badge[2] }}; font-size:12px; font-weight:700;">{{ $badge[0] }}</span>
                            <h1 style="margin:12px 0 0; font-size:22px; line-height:1.3; font-weight:800; color:#0C2B2A; letter-spacing:-0.02em;">{{ $headline }}</h1>
                        </td>
                    </tr>

                    @if (count($rows))
                        <tr>
                            <td style="padding:16px 28px 4px;">
                                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="border-top:1px solid #E3EFED;">
                                    @foreach ($rows as [$label, $value])
                                        <tr>
                                            <td valign="top" style="padding:10px 12px 10px 0; border-bottom:1px solid #E3EFED; font-size:13px; color:#5C7472; width:36%;">{{ $label }}</td>
                                            <td valign="top" style="padding:10px 0; border-bottom:1px solid #E3EFED; font-size:14px; font-weight:700; color:#0C2B2A; text-align:right; word-break:break-word;">{!! nl2br(e($value)) !!}</td>
                                        </tr>
                                    @endforeach
                                </table>
                            </td>
                        </tr>
                    @endif

                    @if ($note)
                        <tr>
                            <td style="padding:16px 28px 0;">
                                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#F3FAF9; border-radius:12px;">
                                    <tr><td style="padding:12px 14px; font-size:13px; line-height:1.6; color:#41605D;">{{ $note }}</td></tr>
                                </table>
                            </td>
                        </tr>
                    @endif

                    @if ($url)
                        <tr>
                            <td align="center" style="padding:24px 28px 28px;">
                                <a href="{{ $url }}" style="display:inline-block; background:#FFC93C; color:#0C2B2A; font-weight:800; font-size:15px; text-decoration:none; padding:14px 28px; border-radius:999px;">Buka di panel admin</a>
                            </td>
                        </tr>
                    @else
                        <tr><td style="padding:0 0 20px;">&nbsp;</td></tr>
                    @endif

                    <tr>
                        <td align="center" style="padding:16px 28px 20px; border-top:1px solid #CFE5E1;">
                            <p style="margin:0; font-size:12px; line-height:1.6; color:#6B8B87;">
                                Kamu menerima email ini karena terdaftar sebagai admin RuangTitip.<br>
                                {{ now('Asia/Jakarta')->locale('id')->translatedFormat('j F Y, H.i') }} WIB
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
