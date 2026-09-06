<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class KuotaBooking
 * Model untuk merepresentasikan satu slot waktu (misal: 09:00 - 09:30)
 * yang tersedia untuk dibooking pada tanggal tertentu.
 */
class KuotaBooking extends Model
{
    // Menentukan nama tabel di database
    protected $table = 'kuota_booking';
    
    // Menentukan primary key
    protected $primaryKey = 'id_kuota';

    // Kolom-kolom yang dapat diisi secara massal
    protected $fillable = [
        'id_jadwal',      // ID jadwal operasional terkait (hari apa)
        'tgl',            // Tanggal spesifik slot ini berlaku
        'hari',           // Nama hari
        'jam',            // Waktu mulai slot (misal: 09:00:00)
        'status_kuota',   // Status dari admin: aktif atau nonaktif
        'status_booking', // Status pemesanan: tersedia atau terbooking
    ];

    /**
     * Relasi ke model Jadwal.
     * Setiap slot kuota mengacu pada satu pengaturan jadwal operasional harian.
     */
    public function jadwal()
    {
        return $this->belongsTo(Jadwal::class, 'id_jadwal', 'id_Jadwal_operasional');
    }

    /**
     * Relasi ke model Booking.
     * Satu slot kuota hanya bisa dimiliki/digunakan oleh maksimal satu transaksi booking.
     */
    public function booking()
    {
        return $this->hasOne(Booking::class, 'id_kuota', 'id_kuota');
    }
}
