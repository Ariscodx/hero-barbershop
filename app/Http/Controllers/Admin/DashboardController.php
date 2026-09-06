<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\KuotaBooking;
use App\Models\Pelanggan;
use Carbon\Carbon;

/**
 * Class DashboardController (Admin)
 *
 * Bertanggung jawab menampilkan halaman utama (dashboard) administrator
 * berisi ringkasan statistik harian dan daftar booking terkini.
 */
class DashboardController extends Controller
{
    /**
     * Menampilkan halaman dashboard admin dengan statistik dan booking hari ini.
     *
     * Data yang ditampilkan:
     * - Total seluruh booking yang pernah ada di sistem.
     * - Jumlah kuota/slot yang tersedia untuk hari ini.
     * - Total pengguna (pelanggan) yang terdaftar.
     * - 5 booking terbaru hari ini (diurutkan berdasarkan jam).
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Perbarui status booking secara otomatis sebelum data ditampilkan
        // (contoh: 'terkonfirmasi' → 'berlangsung' → 'selesai' berdasarkan waktu saat ini)
        Booking::updateStatusOtomatis();

        // Ambil tanggal hari ini dalam format Y-m-d untuk digunakan dalam query
        $today = Carbon::today()->toDateString();

        // Hitung total semua booking yang pernah masuk ke sistem
        $totalBooking = Booking::count();

        // Hitung jumlah slot kuota yang tersedia untuk hari ini
        // (hanya slot dengan status 'Tersedia', bukan yang sudah dipesan/nonaktif)
        $kuotaTersedia = KuotaBooking::where('tgl', $today)
            ->where('status_kuota', 'Tersedia')
            ->count();

        // Hitung total pelanggan yang sudah terdaftar di sistem
        $totalPengguna = Pelanggan::count();

        // Ambil maksimal 5 booking untuk hari ini, diurutkan berdasarkan jam booking
        // Disertakan relasi pelanggan dan kuotaBooking untuk keperluan tampilan
        $todayBookings = Booking::with(['pelanggan', 'kuotaBooking'])
            ->where('tgl_booking', $today)
            ->orderBy('jam_booking', 'asc')
            ->take(5) // Batasi hanya 5 data untuk ringkasan di dashboard
            ->get();

        // Kirim semua data ke view dashboard admin
        return view('admin.dashboard', compact(
            'totalBooking',
            'kuotaTersedia',
            'totalPengguna',
            'todayBookings'
        ));
    }
}
