<x-mail::message>
# Kode OTP Reset Password

Halo,

Anda menerima email ini karena kami menerima permintaan reset password untuk akun Anda. Berikut adalah kode OTP Anda:

<x-mail::panel>
# {{ $otp }}
</x-mail::panel>

Kode ini akan kadaluarsa dalam **10 menit**. Jangan berikan kode ini kepada siapapun.

Jika Anda tidak merasa melakukan permintaan ini, silakan abaikan email ini.

Terima kasih,<br>
Tim {{ config('app.name') }}
</x-mail::message>
