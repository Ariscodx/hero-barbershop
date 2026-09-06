<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\KuotaBooking;
use App\Services\WablasService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\BookingSuccessMail;

/**
 * Class BookingController (Customer)
 *
 * Bertanggung jawab mengelola seluruh alur pemesanan (booking) layanan
 * dari sisi pelanggan, mulai dari menampilkan slot tersedia,
 * mengambil slot via AJAX, hingga menyimpan data booking baru.
 */
class BookingController extends Controller
{
    /**
     * Menampilkan halaman booking dengan daftar slot yang tersedia dalam 7 hari ke depan.
     *
     * Logika:
     * - Membuat array 7 hari ke depan (hari ini + 6 hari berikutnya).
     * - Mengambil semua data KuotaBooking dalam rentang tanggal tersebut dari database.
     * - Menentukan status setiap slot: tersedia, terbooking, atau lewat.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Array untuk menyimpan informasi tanggal yang akan ditampilkan di UI
        $datesData = [];

        // Array untuk menyimpan slot per tanggal (format: ['Y-m-d' => [...slots]])
        $allSlots = [];

        // Buat daftar 7 hari ke depan (hari ini s/d 6 hari ke depan)
        for ($i = 0; $i <= 6; $i++) {
            $d   = Carbon::today()->addDays($i)->locale('id');
            $ymd = $d->format('Y-m-d');

            // Simpan info tanggal dalam format yang dibutuhkan tampilan
            $datesData[] = [
                'index' => $i,
                'day'   => $d->translatedFormat('D'),       // Contoh: Sen, Sel, Rab
                'date'  => $d->format('j'),                  // Contoh: 6, 7, 8
                'month' => $d->translatedFormat('M'),        // Contoh: Sep, Okt
                'full'  => $d->translatedFormat('l, j F Y'), // Contoh: Minggu, 6 September 2026
                'ymd'   => $ymd,
                'today' => $i === 0,                         // true jika hari ini
            ];

            // Inisialisasi array kosong untuk slot tanggal ini
            $allSlots[$ymd] = [];
        }

        // Tentukan rentang tanggal untuk query database
        $startDate = Carbon::today()->format('Y-m-d');
        $endDate   = Carbon::today()->addDays(6)->format('Y-m-d');

        // Ambil semua slot (KuotaBooking) dalam rentang tanggal, diurutkan berdasarkan jam
        $kuotas = KuotaBooking::whereBetween('tgl', [$startDate, $endDate])
            ->orderBy('jam', 'asc')
            ->get();

        // Gunakan timezone dari konfigurasi aplikasi
        $timezone = config('app.timezone', 'Asia/Jakarta');
        $now      = Carbon::now($timezone);

        // Proses setiap slot dan tentukan statusnya untuk ditampilkan ke pelanggan
        foreach ($kuotas as $k) {
            $ymd = $k->tgl;

            // Lewati slot yang tanggalnya tidak ada dalam daftar 7 hari (safety check)
            if (!isset($allSlots[$ymd])) {
                continue;
            }

            // Lewati slot yang dinonaktifkan oleh admin — tidak perlu ditampilkan
            if ($k->status_kuota === 'nonaktif') {
                continue;
            }

            // Status default: tersedia
            $status = 'tersedia';

            // Normalisasi format jam agar Carbon bisa mem-parse dengan benar (HH:MM -> HH:MM:SS)
            $slotJam  = strlen($k->jam) === 5 ? $k->jam . ':00' : $k->jam;
            $slotTime = Carbon::parse($ymd . ' ' . $slotJam, $timezone);

            if ($k->status_booking === 'terbooking') {
                // Slot sudah dipesan oleh pelanggan lain
                $status = 'terbooking';
            } elseif ($now->greaterThanOrEqualTo($slotTime)) {
                // Waktu slot sudah lewat atau sedang berjalan — tidak bisa dipesan
                $status = 'lewat';
            }

            // Format jam tampilan: mulai dan estimasi selesai (+30 menit)
            $jamMulai   = Carbon::parse($k->jam)->format('H.i');
            $jamSelesai = Carbon::parse($k->jam)->addMinutes(30)->format('H.i');

            // Tambahkan slot ke array tanggal yang sesuai
            $allSlots[$ymd][] = [
                'id'     => $k->id_kuota,
                'time'   => "{$jamMulai} - {$jamSelesai}",
                'status' => $status,
            ];
        }

        return view('customer.booking', compact('datesData', 'allSlots'));
    }

    /**
     * Mengambil daftar slot secara dinamis untuk tanggal tertentu via AJAX.
     *
     * Dipanggil saat pelanggan berpindah tab tanggal di halaman booking.
     * Mengembalikan data slot dalam format JSON.
     *
     * @param  string $tanggal Format: Y-m-d (contoh: 2026-09-06)
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSlots(string $tanggal)
    {
        // Ambil semua slot untuk tanggal yang diminta, urutkan berdasarkan jam
        $kuotas = KuotaBooking::where('tgl', $tanggal)
            ->orderBy('jam', 'asc')
            ->get();

        $timezone = config('app.timezone', 'Asia/Jakarta');
        $now      = Carbon::now($timezone);
        $slots    = [];

        foreach ($kuotas as $k) {
            // Lewati slot yang dinonaktifkan admin
            if ($k->status_kuota === 'nonaktif') {
                continue;
            }

            // Tentukan status slot: tersedia, terbooking, atau lewat
            $status   = 'tersedia';
            $slotJam  = strlen($k->jam) === 5 ? $k->jam . ':00' : $k->jam;
            $slotTime = Carbon::parse($tanggal . ' ' . $slotJam, $timezone);

            if ($k->status_booking === 'terbooking') {
                $status = 'terbooking';
            } elseif ($now->greaterThanOrEqualTo($slotTime)) {
                $status = 'lewat';
            }

            // Format jam mulai dan selesai
            $jamMulai   = Carbon::parse($k->jam)->format('H.i');
            $jamSelesai = Carbon::parse($k->jam)->addMinutes(30)->format('H.i');

            $slots[] = [
                'id'     => $k->id_kuota,
                'time'   => "{$jamMulai} - {$jamSelesai}",
                'status' => $status,
            ];
        }

        return response()->json([
            'success' => true,
            'data'    => $slots,
        ]);
    }

    /**
     * Menyimpan data booking baru berdasarkan slot yang dipilih pelanggan.
     *
     * Alur:
     * 1. Validasi input dari request.
     * 2. Kunci slot menggunakan DB transaction + lockForUpdate untuk mencegah double booking.
     * 3. Buat record Booking baru dan update status KuotaBooking menjadi 'terbooking'.
     * 4. Kirim notifikasi WhatsApp (via Wablas) dan email ke pelanggan.
     * 5. Kembalikan response JSON sukses atau gagal.
     *
     * @param  Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        // Validasi: id_kuota wajib ada dan harus ada di tabel kuota_booking
        $request->validate([
            'id_kuota' => 'required|exists:kuota_booking,id_kuota',
        ]);

        // Ambil data pelanggan yang sedang login
        $pelanggan = Auth::guard('pelanggan')->user();

        try {
            // Mulai database transaction untuk menjaga konsistensi data
            DB::beginTransaction();

            // Kunci baris slot menggunakan lockForUpdate agar tidak ada dua request
            // yang berhasil memesan slot yang sama secara bersamaan (race condition)
            $kuota = KuotaBooking::where('id_kuota', $request->id_kuota)->lockForUpdate()->first();

            // Periksa apakah slot masih tersedia (belum dipesan dan masih aktif)
            if (!$kuota || $kuota->status_kuota === 'nonaktif' || $kuota->status_booking === 'terbooking') {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Maaf, slot ini sudah tidak tersedia atau telah dibooking orang lain.',
                ], 400);
            }

            // Periksa apakah waktu slot sudah lewat atau sedang berjalan
            $timezone = config('app.timezone', 'Asia/Jakarta');
            $now      = Carbon::now($timezone);
            $slotJam  = strlen($kuota->jam) === 5 ? $kuota->jam . ':00' : $kuota->jam;
            $slotTime = Carbon::parse($kuota->tgl . ' ' . $slotJam, $timezone);

            if ($now->greaterThanOrEqualTo($slotTime)) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Maaf, Anda tidak dapat memilih waktu yang sudah lewat atau sedang berjalan.',
                ], 400);
            }

            // Buat record booking baru dengan data dari pelanggan dan slot yang dipilih
            Booking::create([
                'id_pelanggan' => $pelanggan->id_pelanggan,
                'id_kuota'     => $kuota->id_kuota,
                'nama'         => $pelanggan->nama,
                'email'        => $pelanggan->email,
                'no_tlp'       => $pelanggan->no_tlp,
                'alamat'       => $pelanggan->alamat,
                'tgl_booking'  => $kuota->tgl,
                'hari'         => $kuota->hari,
                'jam_booking'  => $kuota->jam,
                'status'       => 'terkonfirmasi',
            ]);

            // Tandai slot sebagai sudah dipesan agar tidak bisa dipesan ulang
            $kuota->status_booking = 'terbooking';
            $kuota->save();

            // Simpan semua perubahan database secara permanen
            DB::commit();

            // ------------------------------------------------------------------
            // NOTIFIKASI WHATSAPP VIA WABLAS
            // Dikirim setelah transaksi berhasil. Jika gagal, tidak membatalkan booking.
            // ------------------------------------------------------------------
            if ($pelanggan->no_tlp) {
                try {
                    // Format tanggal dan jam untuk isi pesan WA
                    $jamMulai   = Carbon::parse($kuota->jam)->format('H.i');
                    $tglBooking = Carbon::parse($kuota->tgl)->locale('id')->translatedFormat('l, d F Y');

                    // Template pesan WhatsApp yang akan dikirim ke pelanggan
                    $pesan = "Halo *{$pelanggan->nama}*,\n\nTerima kasih telah melakukan booking di *Hero Barbershop*. ✂️\n\n*Detail Booking:*\n📅 Tanggal: {$tglBooking}\n⏰ Jam: {$jamMulai} WIB\n\nKami tunggu kedatangannya ya! ✨";

                    // Kirim pesan WA menggunakan WablasService
                    $wablas = new WablasService();
                    $wablas->send($pelanggan->no_tlp, $pesan);
                } catch (\Exception $e) {
                    // Catat error ke log tanpa membatalkan booking yang sudah berhasil
                    Log::error('Wablas WA Error Booking: ' . $e->getMessage());
                }
            }

            // ------------------------------------------------------------------
            // NOTIFIKASI EMAIL
            // Dikirim setelah booking berhasil. Jika gagal, tidak membatalkan booking.
            // ------------------------------------------------------------------
            if ($pelanggan->email) {
                try {
                    // Kirim email konfirmasi booking menggunakan Mailable BookingSuccessMail
                    Mail::to($pelanggan->email)->send(new BookingSuccessMail($kuota, $pelanggan));
                } catch (\Exception $e) {
                    // Catat error ke log tanpa membatalkan booking yang sudah berhasil
                    Log::error('Email Notification Error: ' . $e->getMessage());
                }
            }

            // Kembalikan response sukses ke frontend
            return response()->json([
                'success' => true,
                'message' => 'Booking berhasil dibuat.',
            ]);

        } catch (\Exception $e) {
            // Batalkan semua perubahan database jika terjadi error tak terduga
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage(),
            ], 500);
        }
    }
}
