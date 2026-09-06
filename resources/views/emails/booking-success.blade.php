<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Booking Berhasil - Hero Barbershop</title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; background-color: #f4f4f4; margin: 0; padding: 20px; }
        .container { background-color: #ffffff; max-width: 600px; margin: 0 auto; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
        .header { background-color: #1F2A1D; color: #D4B06A; padding: 20px; text-align: center; }
        .header h1 { margin: 0; font-size: 24px; }
        .content { padding: 30px; color: #333333; line-height: 1.6; }
        .content h2 { color: #1F2A1D; margin-top: 0; }
        .booking-details { background-color: #f9f9f9; border-left: 4px solid #D4B06A; padding: 15px; margin: 20px 0; border-radius: 4px; }
        .booking-details p { margin: 5px 0; }
        .footer { background-color: #f4f4f4; color: #777777; text-align: center; padding: 20px; font-size: 12px; }
        .btn { display: inline-block; background-color: #D4B06A; color: #1F2A1D; text-decoration: none; padding: 10px 20px; border-radius: 4px; font-weight: bold; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>HERO BARBERSHOP</h1>
        </div>
        <div class="content">
            <h2>Halo {{ $pelanggan->nama }},</h2>
            <p>Terima kasih telah melakukan reservasi di Hero Barbershop. Booking kamu telah berhasil dikonfirmasi dengan detail sebagai berikut:</p>
            
            <div class="booking-details">
                <p><strong>Hari/Tanggal:</strong> {{ \Carbon\Carbon::parse($kuota->tgl)->locale('id')->translatedFormat('l, d F Y') }}</p>
                <p><strong>Jam:</strong> {{ \Carbon\Carbon::parse($kuota->jam)->format('H:i') }} WIB</p>
                <p><strong>Status:</strong> Terkonfirmasi</p>
            </div>
            
            <p>Mohon datang tepat waktu (sekitar 5-10 menit sebelum jadwal) ya. Kami tunggu kedatangannya!</p>
            
            <a href="{{ url('/') }}" class="btn">Ke Website Hero Barbershop</a>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} Hero Barbershop. All rights reserved.
        </div>
    </div>
</body>
</html>
