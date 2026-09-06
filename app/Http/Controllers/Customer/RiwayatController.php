<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use Carbon\Carbon;

/**
 * Class RiwayatController (Customer)
 *
 * Bertanggung jawab menampilkan riwayat booking pelanggan
 * yang sudah selesai atau dibatalkan.
 */
class RiwayatController extends Controller
{
    /**
     * Menampilkan daftar riwayat booking pelanggan (status: selesai atau dibatalkan).
     *
     * Alur:
     * 1. Ambil ID pelanggan yang sedang login.
     * 2. Query booking dengan status 'selesai' atau 'dibatalkan'.
     * 3. Format data ke array yang siap ditampilkan di tabel riwayat.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Ambil ID pelanggan yang sedang aktif login
        $pelangganId = auth('pelanggan')->id();

        // Ambil semua booking yang sudah selesai atau dibatalkan milik pelanggan ini
        // Sertakan relasi kuotaBooking untuk informasi slot jika dibutuhkan
        $bookingsData = Booking::with('kuotaBooking')
            ->where('id_pelanggan', $pelangganId)
            ->whereIn('status', ['selesai', 'dibatalkan'])
            ->orderBy('created_at', 'desc') // Urutkan dari yang terbaru
            ->get();

        // Format ulang (mapping) data booking ke array yang sesuai kebutuhan tampilan tabel
        $riwayat = $bookingsData->map(function ($b) {
            // Parse jam booking dan hitung estimasi selesai (+30 menit)
            $jamMulai   = Carbon::parse($b->jam_booking);
            $jamSelesai = clone $jamMulai;
            $jamSelesai->addMinutes(30); // Asumsi durasi setiap sesi potong rambut adalah 30 menit

            return [
                'id_asli' => $b->id_booking,

                // Format kode booking unik: BK-YYYYMMDD-XXX (contoh: BK-20260906-001)
                'id'      => 'BK-' . Carbon::parse($b->tgl_booking)->format('Ymd') . '-' . str_pad($b->id_booking, 3, '0', STR_PAD_LEFT),

                'nama'    => $b->nama,
                'email'   => $b->email,
                'hp'      => $b->no_tlp,

                // Format tanggal dalam Bahasa Indonesia (contoh: 06 September 2026)
                'tanggal' => Carbon::parse($b->tgl_booking)->translatedFormat('d F Y'),

                // Tampilkan rentang jam mulai dan selesai (contoh: 09.00 – 09.30)
                'jam'     => $jamMulai->format('H.i') . ' – ' . $jamSelesai->format('H.i'),

                'layanan' => 'Potong Rambut',  // Default layanan
                'durasi'  => '30 Menit',

                // Status booking: 'selesai' atau 'dibatalkan'
                'status'  => $b->status,

                // Keterangan singkat berdasarkan status
                'catatan' => $b->status === 'selesai' ? 'Layanan telah selesai' : 'Booking dibatalkan',
            ];
        });

        return view('customer.riwayat', compact('riwayat'));
    }
}
