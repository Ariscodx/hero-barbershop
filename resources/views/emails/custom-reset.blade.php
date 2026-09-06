<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reset Password - Hero Barbershop</title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; background-color: #f4f4f4; margin: 0; padding: 20px; }
        .container { background-color: #ffffff; max-width: 600px; margin: 0 auto; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
        .header { background-color: #1F2A1D; color: #D4B06A; padding: 20px; text-align: center; }
        .header h1 { margin: 0; font-size: 24px; }
        .content { padding: 30px; color: #333333; line-height: 1.6; }
        .content h2 { color: #1F2A1D; margin-top: 0; }
        .footer { background-color: #f4f4f4; color: #777777; text-align: center; padding: 20px; font-size: 12px; }
        .btn { display: inline-block; background-color: #D4B06A; color: #1F2A1D; text-decoration: none; padding: 12px 25px; border-radius: 4px; font-weight: bold; margin-top: 20px; margin-bottom: 20px;}
        .note { font-size: 13px; color: #666; background: #f9f9f9; padding: 15px; border-left: 4px solid #D4B06A; margin-top: 30px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>HERO BARBERSHOP</h1>
        </div>
        <div class="content">
            <h2>Halo {{ $notifiable->nama ?? 'Pengguna' }},</h2>
            
            <p>Kami menerima permintaan untuk mereset password akun Hero Barbershop Anda. Silakan klik tombol di bawah ini untuk membuat password baru:</p>
            
            <div style="text-align: center;">
                <a href="{{ $url }}" class="btn">Reset Password</a>
            </div>
            
            <p>Link reset password ini akan kedaluwarsa dalam <strong>60 menit</strong>.</p>
            <p>Jika Anda tidak pernah meminta reset password, abaikan saja email ini. Akun Anda tetap aman.</p>

            <div class="note">
                Jika tombol di atas tidak berfungsi, Anda bisa copy-paste link berikut ke browser: <br>
                <a href="{{ $url }}" style="color: #1F2A1D; word-break: break-all;">{{ $url }}</a>
            </div>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} Hero Barbershop. All rights reserved.
        </div>
    </div>
</body>
</html>
