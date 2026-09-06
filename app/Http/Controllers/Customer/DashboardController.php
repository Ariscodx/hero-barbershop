<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Jadwal;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

/**
 * Class DashboardController (Customer)
 *
 * Bertanggung jawab menampilkan halaman utama (dashboard) pelanggan
 * berisi ringkasan statistik booking, jadwal operasional barbershop,
 * dan daftar booking terbaru milik pelanggan yang sedang login.
 */
class DashboardController extends Controller
{
    /**
     * Menampilkan halaman dashboard pelanggan dengan statistik dan data booking.
     *
     * Data yang ditampilkan:
     * - Jumlah booking aktif (belum selesai/batal).
     * - Total booking bulan ini.
     * - Total keseluruhan riwayat booking.
     * - Jadwal operasional hari ini.
     * - Booking terdekat yang akan datang.
     * - 3 booking terakhir untuk ringkasan cepat.
     * - Semua booking dengan paginasi (5 per halaman).
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Ambil ID pelanggan yang sedang login
        $pelangganId = Auth::guard('pelanggan')->id();

        // Perbarui status booking secara otomatis sebelum data ditampilkan
        // (contoh: 'terkonfirmasi' → 'berlangsung' → 'selesai' berdasarkan waktu saat ini)
        Booking::updateStatusOtomatis();

        // Hitung jumlah booking yang masih aktif (menunggu konfirmasi atau pending)
        $bookingAktif = Booking::where('id_pelanggan', $pelangganId)
            ->whereIn('status', ['menunggu', 'dikonfirmasi', 'pending'])
            ->count();

        // Hitung total booking yang dibuat pada bulan dan tahun ini
        $statusBooking = Booking::where('id_pelanggan', $pelangganId)
            ->whereMonth('tgl_booking', Carbon::now()->month)
            ->whereYear('tgl_booking', Carbon::now()->year)
            ->count();

        // Hitung total keseluruhan riwayat booking pelanggan (semua waktu)
        $riwayatBooking = Booking::where('id_pelanggan', $pelangganId)->count();

        // Ambil jadwal operasional untuk hari ini berdasarkan nama hari dalam Bahasa Indonesia
        $hariSekarang = Carbon::now()->locale('id')->dayName; // Contoh: "Minggu", "Senin"
        $hariIni      = Jadwal::where('hari', $hariSekarang)->first();

        // Cari booking terdekat yang akan datang (status aktif, tanggal >= hari ini)
        $nextBooking = Booking::where('id_pelanggan', $pelangganId)
            ->whereIn('status', ['menunggu', 'dikonfirmasi', 'pending'])
            ->whereDate('tgl_booking', '>=', Carbon::now()->toDateString())
            ->orderBy('tgl_booking', 'asc')
            ->orderBy('jam_booking', 'asc')
            ->first();

        // Ambil semua jadwal operasional mingguan untuk ditampilkan di widget jadwal
        $jadwal = Jadwal::all();

        // Ambil 3 riwayat booking terakhir untuk ditampilkan sebagai ringkasan cepat
        $bookingTerakhir = Booking::where('id_pelanggan', $pelangganId)
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();

        // Ambil semua riwayat booking dengan paginasi 5 data per halaman
        $semuaBooking = Booking::where('id_pelanggan', $pelangganId)
            ->orderBy('created_at', 'desc')
            ->paginate(5);

        // Kirim semua data ke view dashboard pelanggan
        return view('customer.dashboard', compact(
            'bookingAktif',
            'statusBooking',
            'riwayatBooking',
            'hariIni',
            'nextBooking',
            'jadwal',
            'bookingTerakhir',
            'semuaBooking'
        ));
    }
}
