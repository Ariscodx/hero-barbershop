<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Jadwal;
use Carbon\Carbon;

/**
 * Class JadwalController (Customer)
 *
 * Bertanggung jawab menampilkan informasi jadwal operasional mingguan
 * Hero Barbershop kepada pelanggan, termasuk status buka/tutup hari ini.
 */
class JadwalController extends Controller
{
    /**
     * Menampilkan halaman jadwal operasional barbershop untuk pelanggan.
     *
     * Data yang ditampilkan:
     * - Jadwal operasional seluruh hari dalam seminggu (Senin–Minggu).
     * - Nama hari saat ini dalam Bahasa Indonesia.
     * - Apakah barbershop sedang buka hari ini.
     * - Jam operasional hari ini (atau 'Libur' jika tutup).
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Ambil semua jadwal operasional dari database (7 hari: Senin s/d Minggu)
        $jadwal = Jadwal::all();

        // Tentukan nama hari saat ini dalam Bahasa Indonesia (contoh: "Minggu", "Senin")
        $hariIni    = Carbon::now()->locale('id')->dayName;

        // Cari data jadwal khusus untuk hari ini
        $dataHariIni = Jadwal::where('hari', $hariIni)->first();

        // Tentukan apakah barbershop sedang buka hari ini
        // (status harus 'buka', tidak case-sensitive)
        $sedangBuka = $dataHariIni && strtolower($dataHariIni->status) === 'buka';

        // Format jam operasional hari ini
        // Jika buka: tampilkan rentang jam (contoh: "08:00 – 17:00")
        // Jika tutup/libur: tampilkan teks "Libur"
        $jamHariIni = 'Libur';
        if ($sedangBuka) {
            $jamHariIni = Carbon::parse($dataHariIni->jam_buka)->format('H:i')
                        . ' – '
                        . Carbon::parse($dataHariIni->jam_tutup)->format('H:i');
        }

        // Kirim semua data ke view jadwal pelanggan
        return view('customer.jadwal', compact('jadwal', 'hariIni', 'sedangBuka', 'jamHariIni'));
    }
}
