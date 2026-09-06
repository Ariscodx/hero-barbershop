<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * Class Pelanggan
 * Model untuk merepresentasikan data pelanggan (customer) di aplikasi.
 * Mewarisi Authenticatable agar pelanggan bisa melakukan proses registrasi dan login.
 */
class Pelanggan extends Authenticatable
{
    use HasFactory, Notifiable;

    // Menentukan nama tabel di database
    protected $table = 'pelanggan';
    
    // Menentukan primary key
    protected $primaryKey = 'id_pelanggan';

    /**
     * Nonaktifkan fitur remember token ('Ingat Saya').
     * Hal ini karena tabel 'pelanggan' tidak memiliki kolom 'remember_token'.
     */
    protected $rememberTokenName = null;

    // Kolom-kolom yang dapat diisi secara massal saat registrasi atau update profil
    protected $fillable = [
        'nama',
        'email',
        'password',
        'no_tlp',
        'alamat',
        'tgl',      // Tanggal registrasi/lahir (sesuai penggunaan)
    ];

    // Kolom-kolom yang disembunyikan agar tidak bocor saat dikonversi ke array/JSON
    protected $hidden = [
        'password',
    ];

    /**
     * Mengatur tipe data kolom saat diambil atau disimpan ke database.
     * Laravel akan otomatis melakukan hashing (enkripsi searah) untuk kolom password.
     *
     * @return array
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    /**
     * Mengirimkan notifikasi reset password khusus untuk pelanggan.
     * Menggunakan notifikasi kustom jika pelanggan lupa password.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token): void
    {
        $this->notify(new \App\Notifications\CustomResetPassword($token));
    }

    /**
     * Relasi ke model Booking.
     * Satu orang pelanggan dapat memiliki banyak transaksi booking.
     */
    public function bookings()
    {
        return $this->hasMany(Booking::class, 'id_pelanggan', 'id_pelanggan');
    }

    /**
     * Relasi ke model Notifikasi.
     * Satu orang pelanggan dapat menerima banyak notifikasi pesan/email/WA.
     */
    public function notifikasis()
    {
        return $this->hasMany(Notifikasi::class, 'id_pelanggan', 'id_pelanggan');
    }
}
