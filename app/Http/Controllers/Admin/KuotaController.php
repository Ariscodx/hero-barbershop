<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Jadwal;
use App\Models\KuotaBooking;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Class KuotaController (Admin)
 *
 * Bertanggung jawab mengelola slot/kuota booking harian barbershop.
 * Admin dapat melihat, membuat (generate), mengaktifkan/menonaktifkan,
 * dan mereset slot kuota untuk tanggal tertentu.
 */
class KuotaController extends Controller
{
    /**
     * Menampilkan halaman manajemen kuota booking.
     * Data slot dimuat secara dinamis via AJAX melalui method getSlots().
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('admin.kuota');
    }

    /**
     * Mengambil daftar slot kuota untuk tanggal tertentu via AJAX.
     *
     * Mengembalikan informasi jadwal operasional hari itu (buka/tutup)
     * beserta semua slot yang sudah di-generate dalam format JSON.
     *
     * @param  string $tanggal Format Y-m-d (contoh: 2026-09-06)
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSlots(string $tanggal)
    {
        // Tentukan nama hari dalam Bahasa Indonesia berdasarkan tanggal
        $hari        = Carbon::parse($tanggal)->locale('id')->translatedFormat('l');
        $jadwal      = Jadwal::where('hari', $hari)->first();

        // Tandai apakah hari tersebut barbershop tutup
        $jadwalTutup = $jadwal ? ($jadwal->status === 'tutup') : false;

        // Ambil semua slot untuk tanggal ini, urutkan berdasarkan jam
        $slots = KuotaBooking::where('tgl', $tanggal)
            ->orderBy('jam', 'asc')
            ->get()
            ->map(function ($slot) {
                return [
                    'id_kuota'       => $slot->id_kuota,
                    'jam'            => Carbon::parse($slot->jam)->format('H:i'),
                    'jam_selesai'    => Carbon::parse($slot->jam)->addMinutes(30)->format('H:i'), // Estimasi selesai +30 menit
                    'status_kuota'   => $slot->status_kuota,   // aktif / nonaktif
                    'status_booking' => $slot->status_booking, // tersedia / terbooking
                ];
            });

        return response()->json([
            'success'      => true,
            'jadwal_tutup' => $jadwalTutup,
            'data'         => $slots,
        ]);
    }

    /**
     * Men-generate slot kuota secara otomatis berdasarkan jadwal operasional.
     *
     * Alur:
     * 1. Validasi tanggal dari request.
     * 2. Ambil jadwal operasional untuk hari tersebut.
     * 3. Cek apakah sudah ada slot — jika sudah ada yang dipesan, tolak generate ulang.
     * 4. Buat slot per 30 menit dari jam buka hingga jam tutup.
     * 5. Simpan semua slot sekaligus ke database (bulk insert).
     *
     * @param  Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function generate(Request $request)
    {
        // Validasi: tanggal wajib diisi dan harus format tanggal valid
        $request->validate([
            'tanggal' => 'required|date',
        ]);

        $tanggal = $request->tanggal;

        try {
            DB::beginTransaction();

            // Tentukan nama hari dari tanggal yang diberikan
            $hari   = Carbon::parse($tanggal)->locale('id')->translatedFormat('l');
            $jadwal = Jadwal::where('hari', $hari)->first();

            // Pastikan jadwal operasional untuk hari ini sudah dibuat admin
            if (!$jadwal) {
                return response()->json([
                    'success' => false,
                    'message' => 'Jadwal operasional belum dibuat.',
                ], 400);
            }

            // Tolak generate jika hari ini barbershop tutup
            if ($jadwal->status === 'tutup') {
                return response()->json([
                    'success' => false,
                    'message' => 'Barbershop tutup pada hari tersebut.',
                ], 400);
            }

            // Periksa apakah sudah ada slot untuk tanggal ini
            $existingSlots = KuotaBooking::where('tgl', $tanggal)->get();
            if ($existingSlots->count() > 0) {
                // Jika sudah ada slot yang dipesan pelanggan, generate ulang tidak diizinkan
                $hasBooked = $existingSlots->where('status_booking', '!=', 'tersedia')->count() > 0;
                if ($hasBooked) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Slot tidak bisa di-generate ulang karena sudah ada yang dipesan oleh pelanggan.',
                    ], 400);
                }

                // Jika belum ada yang dipesan, hapus slot lama untuk di-generate ulang sesuai jadwal terbaru
                KuotaBooking::where('tgl', $tanggal)->delete();
            }

            // Parse jam buka dan jam tutup dari jadwal operasional
            $jamBuka  = Carbon::parse($jadwal->jam_buka);
            $jamTutup = Carbon::parse($jadwal->jam_tutup);

            // Tangani kasus jam tutup melewati tengah malam (misalnya: buka 09:00, tutup 00:00 keesokan hari)
            if ($jamTutup <= $jamBuka && $jamTutup->format('H:i') !== '00:00') {
                $jamTutup->addDay();
            }

            // Buat slot per interval 30 menit dari jam buka hingga jam tutup
            $slotsToInsert = [];
            $currentSlot   = $jamBuka->copy();

            while ($currentSlot->copy()->addMinutes(30) <= $jamTutup) {
                $slotsToInsert[] = [
                    'id_jadwal'      => $jadwal->id_Jadwal_operasional,
                    'tgl'            => $tanggal,
                    'hari'           => $jadwal->hari,
                    'jam'            => $currentSlot->format('H:i:s'),
                    'status_kuota'   => 'nonaktif',  // Default nonaktif, admin harus aktifkan manual
                    'status_booking' => 'tersedia',  // Default tersedia untuk dipesan
                    'created_at'     => now(),
                    'updated_at'     => now(),
                ];
                $currentSlot->addMinutes(30); // Geser ke slot berikutnya
            }

            // Pastikan ada minimal satu slot yang bisa dibuat
            if (empty($slotsToInsert)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Durasi operasional terlalu singkat untuk membuat slot 30 menit.',
                ], 400);
            }

            // Simpan semua slot sekaligus ke database (lebih efisien dari insert satu per satu)
            KuotaBooking::insert($slotsToInsert);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Berhasil men-generate slot booking.',
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Mengubah status aktif/nonaktif sebuah slot kuota.
     *
     * Slot yang 'aktif' dapat dipesan oleh pelanggan.
     * Slot yang 'nonaktif' tidak akan ditampilkan ke pelanggan.
     * Tidak bisa mengaktifkan slot jika jadwal hari itu sedang 'tutup'.
     *
     * @param  int $id ID kuota yang akan di-toggle
     * @return \Illuminate\Http\JsonResponse
     */
    public function toggle(int $id)
    {
        // Cari slot berdasarkan ID, lempar 404 jika tidak ditemukan
        $slot   = KuotaBooking::findOrFail($id);
        $jadwal = Jadwal::where('hari', $slot->hari)->first();

        // Cegah pengaktifan slot jika jadwal hari itu berstatus tutup
        if ($jadwal && $jadwal->status === 'tutup' && $slot->status_kuota === 'nonaktif') {
            return response()->json([
                'success' => false,
                'message' => 'Tidak dapat mengaktifkan slot karena Jadwal Operasional hari ' . $slot->hari . ' sedang TUTUP.',
            ], 400);
        }

        // Toggle status: aktif → nonaktif, atau nonaktif → aktif
        $slot->status_kuota = $slot->status_kuota === 'aktif' ? 'nonaktif' : 'aktif';
        $slot->save();

        return response()->json([
            'success'      => true,
            'message'      => 'Status slot berhasil diperbarui.',
            'status_kuota' => $slot->status_kuota,
        ]);
    }

    /**
     * Mereset sebuah slot kuota ke kondisi 'aktif'.
     *
     * Hanya bisa dilakukan jika:
     * - Jadwal hari itu tidak sedang 'tutup'.
     * - Slot belum digunakan (status_booking masih 'tersedia').
     *
     * @param  int $id ID kuota yang akan direset
     * @return \Illuminate\Http\JsonResponse
     */
    public function reset(int $id)
    {
        $slot   = KuotaBooking::findOrFail($id);
        $jadwal = Jadwal::where('hari', $slot->hari)->first();

        // Tolak reset jika jadwal hari itu berstatus tutup
        if ($jadwal && $jadwal->status === 'tutup') {
            return response()->json([
                'success' => false,
                'message' => 'Tidak dapat mereset slot karena Jadwal Operasional hari ' . $slot->hari . ' sedang TUTUP.',
            ], 400);
        }

        // Tolak reset jika slot sudah pernah dipesan pelanggan (tidak boleh dihapus sembarangan)
        if ($slot->status_booking !== 'tersedia') {
            return response()->json([
                'success' => false,
                'message' => 'Slot tidak dapat direset karena sudah digunakan pelanggan.',
            ], 400);
        }

        // Kembalikan status slot ke 'aktif'
        $slot->status_kuota = 'aktif';
        $slot->save();

        return response()->json([
            'success'      => true,
            'message'      => 'Slot berhasil direset ke kondisi awal.',
            'status_kuota' => $slot->status_kuota,
        ]);
    }
}
