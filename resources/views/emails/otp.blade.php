<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kode OTP Verifikasi</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f8fafc; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; -webkit-font-smoothing: antialiased;">
    <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color: #f8fafc; padding: 40px 20px;">
        <tr>
            <td align="center">
                <table width="100%" max-width="600" cellpadding="0" cellspacing="0" border="0" style="background-color: #ffffff; border-radius: 24px; overflow: hidden; box-shadow: 0 10px 25px rgba(0,0,0,0.05); max-width: 600px; margin: 0 auto;">
                    
                    <!-- Header -->
                    <tr>
                        <td style="background-color: #cc0000; padding: 40px 30px; text-align: center;">
                            <h1 style="color: #ffffff; font-size: 28px; font-weight: 800; margin: 0; letter-spacing: 1px;">BUS 88</h1>
                            <p style="color: #ffcccc; margin: 10px 0 0 0; font-size: 14px;">Mitra Perjalanan Terbaik Anda</p>
                        </td>
                    </tr>
                    
                    <!-- Body -->
                    <tr>
                        <td style="padding: 40px 30px;">
                            <h2 style="color: #1e293b; font-size: 22px; font-weight: 700; margin: 0 0 20px 0;">Verifikasi Keamanan</h2>
                            <p style="color: #64748b; font-size: 16px; line-height: 1.6; margin: 0 0 30px 0;">
                                Halo! Anda menerima email ini karena ada permintaan untuk mengubah kata sandi akun Bus 88 Anda. Gunakan kode OTP di bawah ini untuk melanjutkan proses verifikasi:
                            </p>
                            
                            <!-- OTP Box -->
                            <div style="background-color: #fef2f2; border: 2px dashed #fca5a5; border-radius: 16px; padding: 30px; text-align: center; margin-bottom: 30px;">
                                <p style="color: #ef4444; font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 2px; margin: 0 0 15px 0;">Kode OTP Anda</p>
                                <h1 style="color: #cc0000; font-size: 42px; font-weight: 900; letter-spacing: 12px; margin: 0;">{{ $otp }}</h1>
                            </div>
                            
                            <p style="color: #64748b; font-size: 14px; line-height: 1.6; margin: 0 0 20px 0;">
                                <strong>⚠️ Penting:</strong> Kode ini hanya berlaku selama <strong>5 menit</strong>. Jangan pernah memberikan kode ini kepada siapa pun, termasuk pihak Bus 88.
                            </p>
                            
                            <hr style="border: 0; border-top: 1px solid #e2e8f0; margin: 30px 0;">
                            
                            <p style="color: #94a3b8; font-size: 12px; line-height: 1.5; margin: 0;">
                                Jika Anda tidak merasa melakukan permintaan perubahan kata sandi, Anda bisa mengabaikan email ini atau segera hubungi layanan pelanggan kami jika merasa ada aktivitas mencurigakan.
                            </p>
                        </td>
                    </tr>
                    
                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #f1f5f9; padding: 20px 30px; text-align: center;">
                            <p style="color: #64748b; font-size: 12px; margin: 0;">
                                &copy; {{ date('Y') }} Bus 88. Hak Cipta Dilindungi Undang-Undang.
                            </p>
                        </td>
                    </tr>
                    
                </table>
            </td>
        </tr>
    </table>
</body>
</html>