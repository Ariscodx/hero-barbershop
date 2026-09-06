<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Jadwal
 * Model untuk merepresentasikan data jadwal operasional (jam buka/tutup) barbershop.
 */
class Jadwal extends Model
{
    // Menentukan nama tabel di database
    protected $table = 'jadwal';
    
    // Menentukan primary key dari tabel
    protected $primaryKey = 'id_Jadwal_operasional';

    // Kolom-kolom yang dapat diisi secara massal
    protected $fillable = [
        'hari',      // Nama hari (Senin, Selasa, dll)
        'tanggal',   // Tanggal spesifik (jika ada, biasanya tidak dipakai jika jadwal rutin mingguan)
        'jam_buka',  // Jam mulai operasional
        'jam_tutup', // Jam selesai operasional
        'status',    // Status operasional: buka atau tutup
    ];

    /**
     * Relasi ke model KuotaBooking.
     * Satu hari jadwal operasional dapat memiliki banyak slot kuota booking.
     */
    public function kuotaBookings()
    {
        return $this->hasMany(KuotaBooking::class, 'id_jadwal', 'id_Jadwal_operasional');
    }
}
