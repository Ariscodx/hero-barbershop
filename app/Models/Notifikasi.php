<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Notifikasi
 * Model untuk merepresentasikan data riwayat notifikasi atau pesan
 * yang pernah dikirimkan ke pelanggan (misalnya email/WA).
 */
class Notifikasi extends Model
{
    // Menentukan nama tabel di database
    protected $table = 'notifikasi';
    
    // Menentukan primary key
    protected $primaryKey = 'id_notifikasi';

    // Kolom-kolom yang dapat diisi secara massal
    protected $fillable = [
        'id_pelanggan', // ID pelanggan yang menerima notifikasi
        'id_booking',   // ID transaksi booking terkait (jika ada)
        'email',        // Alamat email tujuan
        'subjek',       // Subjek atau judul pesan
        'pesan',        // Isi pesan lengkap
    ];

    /**
     * Relasi ke model Pelanggan.
     * Setiap notifikasi ditujukan kepada satu pelanggan tertentu.
     */
    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class, 'id_pelanggan', 'id_pelanggan');
    }

    /**
     * Relasi ke model Booking.
     * Sebuah notifikasi bisa terkait dengan satu transaksi booking tertentu.
     */
    public function booking()
    {
        return $this->belongsTo(Booking::class, 'id_booking', 'id_booking');
    }
}
