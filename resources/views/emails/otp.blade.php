<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="color-scheme" content="light">
    <meta name="supported-color-schemes" content="light">
    <title>Kode Login RuangTitip</title>
</head>
<body style="margin:0; padding:0; background:#F3FAF9; font-family:'Plus Jakarta Sans','Segoe UI',Arial,sans-serif; color:#0C2B2A;">
    {{-- Pratinjau di daftar email --}}
    <div style="display:none; max-height:0; overflow:hidden; opacity:0; font-size:1px; line-height:1px; color:#F3FAF9;">
        Kode loginmu {{ $code }}, berlaku 10 menit.
    </div>

    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#F3FAF9;">
        <tr>
            <td align="center" style="padding:32px 16px;">
                <table role="presentation" width="100%" cellpadding="0" cellspacing="0"
                       style="max-width:480px; background:#FFFFFF; border:1px solid #CFE5E1; border-radius:20px; overflow:hidden;">

                    <!-- Header -->
                    <tr>
                        <td align="center" style="background:#0F766E; padding:28px 32px 26px;">
                            <img src="{{ asset('assets/logo/logo-horizontal-light.png') }}" alt="RuangTitip" width="150" style="display:block; width:150px; max-width:100%; height:auto; border:0; margin:0 auto; color:#FFFFFF; font-size:22px; font-weight:800;">
                        </td>
                    </tr>
                    <tr>
                        <td style="background:#FFC93C; height:4px; line-height:4px; font-size:0;">&nbsp;</td>
                    </tr>

                    <!-- Isi -->
                    <tr>
                        <td style="padding:36px 32px 12px;">
                            <h1 style="margin:0 0 10px; font-size:24px; line-height:1.25; font-weight:800; color:#0C2B2A; letter-spacing:-0.02em;">Kode login kamu</h1>
                            <p style="margin:0; font-size:15px; line-height:1.6; color:#41605D;">
                                Masukkan kode di bawah ini untuk masuk ke akun RuangTitip.
                                Kode berlaku selama <strong style="color:#0C2B2A;">10 menit</strong>.
                            </p>
                        </td>
                    </tr>

                    <!-- Kode -->
                    <tr>
                        <td align="center" style="padding:20px 32px 8px;">
                            <table role="presentation" cellpadding="0" cellspacing="0" style="background:#D7F2EE; border:1.5px solid #0F766E; border-radius:16px;">
                                <tr>
                                    <td align="center" style="padding:18px 22px 18px 32px;">
                                        <span style="display:block; font-family:'Courier New',Consolas,monospace; font-size:36px; line-height:1; font-weight:800; letter-spacing:12px; color:#0B5D57;">{{ $code }}</span>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Catatan keamanan -->
                    <tr>
                        <td style="padding:20px 32px 36px;">
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#F3FAF9; border-radius:12px;">
                                <tr>
                                    <td style="padding:14px 16px; font-size:13px; line-height:1.6; color:#41605D;">
                                        <strong style="color:#0C2B2A;">Jaga kodemu.</strong>
                                        Jangan bagikan kode ini ke siapa pun, termasuk yang mengaku dari RuangTitip.
                                        Kalau kamu tidak meminta kode ini, abaikan saja email ini.
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td align="center" style="padding:20px 32px 24px; border-top:1px solid #CFE5E1; background:#FFFFFF;">
                            <p style="margin:0 0 6px; font-size:12px; line-height:1.6; color:#41605D;">
                                Butuh bantuan?
                                <a href="https://wa.me/6285121091134" style="color:#0F766E; font-weight:700; text-decoration:underline;">Chat tim kami</a>
                            </p>
                            <p style="margin:0; font-size:11.5px; line-height:1.6; color:#6B8B87;">
                                &copy; {{ date('Y') }} RuangTitip &middot; Malang, Indonesia
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
