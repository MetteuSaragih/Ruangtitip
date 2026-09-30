<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kode Login RuangTitip</title>
</head>
<body style="margin:0; padding:0; background:#F5F1E8; font-family:'Segoe UI',Arial,sans-serif;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#F5F1E8; padding:32px 0;">
        <tr>
            <td align="center">
                <table role="presentation" width="440" cellpadding="0" cellspacing="0"
                       style="background:#FFFDF8; border-radius:20px; overflow:hidden; border:1.5px solid #1C1B18;">
                    <!-- Header -->
                    <tr>
                        <td style="background:#1C1B18; padding:28px 32px; text-align:center;">
                            <span style="color:#F5F1E8; font-size:22px; font-family:Georgia,'Times New Roman',serif; font-weight:800; letter-spacing:.5px;">RuangTitip</span>
                        </td>
                    </tr>
                    <!-- Body -->
                    <tr>
                        <td style="padding:36px 32px;">
                            <h1 style="margin:0 0 8px; font-size:22px; font-weight:800; color:#1C1B18;">Kode Login Kamu</h1>
                            <p style="margin:0 0 24px; font-size:14px; color:#4F4A40; line-height:1.6;">
                                Masukkan kode di bawah ini untuk masuk ke akun RuangTitip kamu.
                                Kode berlaku selama <strong>10 menit</strong>.
                            </p>

                            <div style="text-align:center; margin:0 0 24px;">
                                <span style="display:inline-block; background:#F6E3D3; color:#9A4415;
                                             font-size:34px; font-weight:800; letter-spacing:10px;
                                             padding:16px 20px 16px 30px; border-radius:14px; border:1.5px solid #B4531D;">
                                    {{ $code }}
                                </span>
                            </div>

                            <p style="margin:0; font-size:12px; color:#8A8374; line-height:1.6;">
                                Jika kamu tidak meminta kode ini, abaikan email ini.
                                Jangan pernah membagikan kode ini kepada siapa pun.
                            </p>
                        </td>
                    </tr>
                    <!-- Footer -->
                    <tr>
                        <td style="padding:20px 32px; background:#E8DFCD; text-align:center; border-top:1px solid #DDD5C4;">
                            <p style="margin:0; font-size:11px; color:#5C574D;">
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
