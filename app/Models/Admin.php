<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * Class Admin
 * Model untuk merepresentasikan data administrator di aplikasi.
 * Mewarisi Authenticatable agar dapat digunakan untuk proses otentikasi (login) admin.
 */
class Admin extends Authenticatable
{
    use HasFactory, Notifiable;

    // Menentukan nama tabel di database secara eksplisit
    protected $table = 'admin';

    // Menentukan primary key dari tabel admin
    protected $primaryKey = 'id_admin';

    /**
     * Nonaktifkan fitur remember token ('Ingat Saya').
     * Hal ini karena tabel 'admin' di database tidak memiliki kolom 'remember_token'.
     */
    protected $rememberTokenName = null;

    // Kolom-kolom yang diizinkan untuk diisi secara massal (mass assignment)
    protected $fillable = [
        'nama',
        'email',
        'password',
    ];

    // Kolom-kolom yang disembunyikan saat model diubah menjadi array atau JSON (untuk keamanan)
    protected $hidden = [
        'password',
    ];

    /**
     * Mengatur tipe data kolom saat diambil atau disimpan.
     * Di sini, kolom password akan otomatis di-hash oleh Laravel.
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
     * Mengirimkan notifikasi reset password untuk admin.
     * Menggunakan class notifikasi kustom agar isi pesan bisa disesuaikan.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token): void
    {
        $this->notify(new \App\Notifications\CustomResetPassword($token));
    }
}
