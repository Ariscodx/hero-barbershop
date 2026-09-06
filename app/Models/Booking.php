<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Booking
 * Model untuk merepresentasikan transaksi pemesanan (booking) layanan oleh pelanggan.
 */
class Booking extends Model
{
    // Menentukan nama tabel yang terkait dengan model ini
    protected $table = 'booking';
    
    // Menentukan primary key dari tabel booking
    protected $primaryKey = 'id_booking';

    // Kolom-kolom yang dapat diisi secara massal saat membuat atau mengupdate data
    protected $fillable = [
        'id_pelanggan',
        'id_kuota',
        'nama',
        'email',
        'no_tlp',
        'alamat',
        'tgl_booking',
        'hari',
        'jam_booking',
        'status', // Status booking: menunggu, terkonfirmasi, berlangsung, selesai, dibatalkan
    ];

    /**
     * Relasi ke model Pelanggan (Setiap booking dimiliki oleh satu pelanggan)
     */
    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class, 'id_pelanggan', 'id_pelanggan');
    }

    /**
     * Relasi ke model KuotaBooking (Setiap booking menggunakan satu slot/kuota tertentu)
     */
    public function kuotaBooking()
    {
        return $this->belongsTo(KuotaBooking::class, 'id_kuota', 'id_kuota');
    }

    /**
     * Relasi ke model Notifikasi (Satu booking dapat memiliki banyak notifikasi)
     */
    public function notifikasis()
    {
        return $this->hasMany(Notifikasi::class, 'id_booking', 'id_booking');
    }

    /**
     * Otomatis mengupdate status booking berdasarkan waktu saat ini.
     * Fungsi ini biasanya dipanggil setiap kali halaman daftar booking (admin/customer) dimuat.
     */
    public static function updateStatusOtomatis()
    {
        // 1. Ambil timezone lokal aplikasi (default: Asia/Jakarta) agar pencocokan waktu akurat
        $timezone = config('app.timezone', 'Asia/Jakarta');
        
        // 2. Dapatkan waktu saat ini berdasarkan timezone tersebut
        $now = \Carbon\Carbon::now($timezone);
        $todayDate = $now->format('Y-m-d');
        $currentTime = $now->format('H:i:s');

        // ========================================================================
        // TAHAP 1: UBAH 'TERKONFIRMASI' MENJADI 'BERLANGSUNG'
        // ========================================================================
        // Sistem mencari semua booking yang masih 'terkonfirmasi', lalu mengecek:
        // a. Apakah tanggal booking sudah lewat dari hari ini? (tgl_booking < hari ini)
        // b. ATAU jika tanggalnya adalah hari ini, apakah jam bookingnya sudah tiba atau lewat?
        // Jika ya, status langsung diubah menjadi 'berlangsung'.
        self::where('status', 'terkonfirmasi')
            ->where(function($query) use ($todayDate, $currentTime) {
                $query->where('tgl_booking', '<', $todayDate)
                      ->orWhere(function($q) use ($todayDate, $currentTime) {
                          $q->where('tgl_booking', '=', $todayDate)
                            ->where('jam_booking', '<=', $currentTime);
                      });
            })
            ->update(['status' => 'berlangsung']);

        // ========================================================================
        // TAHAP 2: UBAH 'BERLANGSUNG' MENJADI 'SELESAI'
        // ========================================================================
        // Sistem mencari semua booking yang sedang 'berlangsung'.
        $berlangsung = self::where('status', 'berlangsung')->get();
        foreach ($berlangsung as $b) {
            // Kita pastikan format jam dari database adalah standar (HH:MM:SS).
            // Misalnya jika database hanya menyimpan '10:00', kita jadikan '10:00:00'.
            $jam = strlen($b->jam_booking) === 5 ? $b->jam_booking . ':00' : $b->jam_booking;
            
            // Hitung jam selesai pelayanan (jam booking + durasi 30 menit).
            // Parse() juga dikunci menggunakan timezone agar tidak terjadi selisih jam.
            $bookingEnd = \Carbon\Carbon::parse($b->tgl_booking . ' ' . $jam, $timezone)->addMinutes(30);
            
            // Jika waktu saat ini sudah melewati atau sama dengan waktu selesai,
            // maka layanan dianggap sudah selesai, dan status diupdate menjadi 'selesai'.
            if ($now->greaterThanOrEqualTo($bookingEnd)) {
                $b->update(['status' => 'selesai']);
            }
        }
    }
}
