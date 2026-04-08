<x-mail::message>
# Kode OTP Verifikasi

Halo **{{ $otp['user'] ?? 'Pengguna' }}**,

Anda menerima email ini karena Anda meminta untuk mengubah password akun Bus 88 Anda.

Berikut adalah kode OTP Anda:

<x-mail::panel>
# <span style="font-size: 32px; letter-spacing: 4px;">{{ $otp }}</span>
</x-mail::panel>

**Kode ini hanya berlaku selama 5 menit.**

Jika Anda tidak merasa melakukan permintaan ini, abaikan email ini.

Terima kasih,<br>
Tim **Bus 88**
</x-mail::message>