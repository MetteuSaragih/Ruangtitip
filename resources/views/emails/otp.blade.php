<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kode Login RUTIP</title>
</head>
<body style="margin:0; padding:0; background:#f4f4f7; font-family:'Segoe UI',Arial,sans-serif;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#f4f4f7; padding:32px 0;">
        <tr>
            <td align="center">
                <table role="presentation" width="440" cellpadding="0" cellspacing="0"
                       style="background:#ffffff; border-radius:16px; overflow:hidden; box-shadow:0 4px 24px rgba(0,0,0,0.06);">
                    <!-- Header -->
                    <tr>
                        <td style="background:linear-gradient(135deg,#7c3aed,#6366f1); padding:28px 32px; text-align:center;">
                            <span style="color:#ffffff; font-size:22px; font-weight:800; letter-spacing:1px;">RUTIP</span>
                        </td>
                    </tr>
                    <!-- Body -->
                    <tr>
                        <td style="padding:36px 32px;">
                            <h1 style="margin:0 0 8px; font-size:20px; color:#1f2937;">Kode Login Kamu</h1>
                            <p style="margin:0 0 24px; font-size:14px; color:#6b7280; line-height:1.6;">
                                Masukkan kode di bawah ini untuk masuk ke akun RUTIP kamu.
                                Kode berlaku selama <strong>10 menit</strong>.
                            </p>

                            <div style="text-align:center; margin:0 0 24px;">
                                <span style="display:inline-block; background:#f5f3ff; color:#7c3aed;
                                             font-size:34px; font-weight:800; letter-spacing:10px;
                                             padding:16px 28px; border-radius:12px; border:1px solid #ede9fe;">
                                    {{ $code }}
                                </span>
                            </div>

                            <p style="margin:0; font-size:12px; color:#9ca3af; line-height:1.6;">
                                Jika kamu tidak meminta kode ini, abaikan email ini.
                                Jangan pernah membagikan kode ini kepada siapa pun.
                            </p>
                        </td>
                    </tr>
                    <!-- Footer -->
                    <tr>
                        <td style="padding:20px 32px; background:#fafafa; text-align:center;">
                            <p style="margin:0; font-size:11px; color:#9ca3af;">
                                &copy; {{ date('Y') }} RUTIP · Malang, Indonesia
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
