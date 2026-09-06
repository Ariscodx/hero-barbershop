<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\KuotaBooking;

/**
 * Class StatusController (Customer)
 *
 * Bertanggung jawab menampilkan status booking aktif pelanggan
 * (yang masih berstatus 'terkonfirmasi' atau 'berlangsung')
 * dan menyediakan fitur pembatalan booking.
 */
class StatusController extends Controller
{
    /**
     * Menampilkan daftar booking aktif pelanggan yang sedang login.
     *
     * Hanya menampilkan booking dengan status:
     * - 'terkonfirmasi' : Booking sudah dikonfirmasi, menunggu waktu layanan.
     * - 'berlangsung'   : Booking sedang dalam proses layanan.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Ambil ID pelanggan yang sedang aktif login
        $pelangganId = auth('pelanggan')->id();

        // Perbarui status booking secara otomatis sebelum data ditampilkan
        // (contoh: 'terkonfirmasi' → 'berlangsung' → 'selesai' berdasarkan waktu saat ini)
        Booking::updateStatusOtomatis();

        // Ambil semua booking aktif milik pelanggan ini (status: terkonfirmasi atau berlangsung)
        // Sertakan relasi kuotaBooking untuk informasi slot
        $bookingsData = Booking::with('kuotaBooking')
            ->where('id_pelanggan', $pelangganId)
            ->whereIn('status', ['terkonfirmasi', 'berlangsung'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Peta label status yang akan ditampilkan di kartu booking
        $statusMap = [
            'terkonfirmasi' => 'terkonfirmasi',
            'berlangsung'   => 'berlangsung',
        ];

        // Format ulang (mapping) data booking ke array yang sesuai kebutuhan tampilan
        $bookings = $bookingsData->map(function ($b) use ($statusMap) {
            // Parse jam booking dan hitung estimasi selesai (+30 menit)
            $jamMulai   = Carbon::parse($b->jam_booking);
            $jamSelesai = clone $jamMulai;
            $jamSelesai->addMinutes(30); // Asumsi durasi layanan 30 menit

            return [
                'id_asli' => $b->id_booking,

                // Format kode booking unik: BK-YYYYMMDD-XXX (contoh: BK-20260906-001)
                'id'      => 'BK-' . Carbon::parse($b->tgl_booking)->format('Ymd') . '-' . str_pad($b->id_booking, 3, '0', STR_PAD_LEFT),

                'nama'    => $b->nama,
                'email'   => $b->email,
                'hp'      => $b->no_tlp,

                // Format tanggal dalam Bahasa Indonesia (contoh: 06 September 2026)
                'tanggal' => Carbon::parse($b->tgl_booking)->translatedFormat('d F Y'),

                // Tampilkan rentang jam (contoh: 09.00 – 09.30)
                'jam'     => $jamMulai->format('H.i') . ' – ' . $jamSelesai->format('H.i'),

                'layanan' => 'Potong Rambut',       // Nama layanan
                'durasi'  => '30 Menit',
                'admin'   => 'Hero Barbershop',      // Nama tempat layanan

                // Ambil status dari statusMap, gunakan 'menunggu' sebagai fallback
                'status'  => $statusMap[$b->status] ?? 'menunggu',
            ];
        });

        return view('customer.status', compact('bookings'));
    }

    /**
     * Membatalkan booking yang masih berstatus 'terkonfirmasi'.
     *
     * Alur:
     * 1. Pastikan booking milik pelanggan yang sedang login.
     * 2. Hanya booking berstatus 'terkonfirmasi' yang bisa dibatalkan.
     * 3. Ubah status booking menjadi 'dibatalkan'.
     * 4. Kembalikan status slot kuota menjadi 'tersedia' agar bisa dipesan ulang.
     *
     * @param  Request $request
     * @param  string  $id ID booking yang akan dibatalkan
     * @return \Illuminate\Http\JsonResponse
     */
    public function cancel(Request $request, string $id)
    {
        // Ambil ID pelanggan yang sedang aktif login
        $pelangganId = auth('pelanggan')->id();

        try {
            // Mulai database transaction untuk menjaga konsistensi data
            DB::beginTransaction();

            // Cari booking berdasarkan ID dan pastikan milik pelanggan ini
            // firstOrFail() akan melempar 404 jika tidak ditemukan
            $booking = Booking::where('id_booking', $id)
                ->where('id_pelanggan', $pelangganId)
                ->firstOrFail();

            // Hanya booking dengan status 'terkonfirmasi' yang bisa dibatalkan
            // Booking yang sedang berlangsung atau sudah selesai tidak bisa dibatalkan
            if ($booking->status !== 'terkonfirmasi') {
                return response()->json([
                    'success' => false,
                    'message' => 'Hanya booking dengan status Menunggu Konfirmasi yang dapat dibatalkan.',
                ], 400);
            }

            // Ubah status booking menjadi 'dibatalkan'
            $booking->status = 'dibatalkan';
            $booking->save();

            // Kembalikan status slot kuota menjadi 'tersedia' agar bisa dipesan pelanggan lain
            if ($booking->id_kuota) {
                $kuota = KuotaBooking::find($booking->id_kuota);
                if ($kuota) {
                    $kuota->status_booking = 'tersedia';
                    $kuota->save();
                }
            }

            // Simpan semua perubahan ke database
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Booking berhasil dibatalkan.',
            ]);

        } catch (\Exception $e) {
            // Batalkan semua perubahan jika terjadi error
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage(),
            ], 500);
        }
    }
}
