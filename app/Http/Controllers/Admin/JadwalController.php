<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Jadwal;
use App\Http\Requests\UpdateJadwalRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Class JadwalController (Admin)
 *
 * Bertanggung jawab mengelola konfigurasi jam operasional (jadwal buka/tutup)
 * Hero Barbershop untuk setiap hari dalam seminggu.
 *
 * Ketika jadwal diubah, controller ini juga akan menyesuaikan
 * data KuotaBooking yang sudah ada agar tetap konsisten.
 */
class JadwalController extends Controller
{
    /**
     * Menampilkan daftar jadwal operasional seluruh hari dalam seminggu.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Ambil semua data jadwal dari database (7 hari: Senin s/d Minggu)
        $jadwals = Jadwal::all();

        return view('admin.jadwal', compact('jadwals'));
    }

    /**
     * Memperbarui jadwal operasional secara massal (bulk update) untuk semua hari.
     *
     * Alur:
     * 1. Jalankan dalam satu Database Transaction agar data tetap konsisten.
     * 2. Untuk setiap hari yang jadwalnya berubah:
     *    a. Hapus kuota/slot masa depan yang belum dipesan (status 'tersedia').
     *    b. Jika hari diubah menjadi 'tutup', nonaktifkan sisa slot yang ada.
     * 3. Simpan atau perbarui jadwal baru ke database.
     *
     * @param  UpdateJadwalRequest $request Validasi form yang sudah didefinisikan di FormRequest
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateJadwalRequest $request)
    {
        try {
            // Gunakan Database Transaction agar jika satu hari gagal disimpan,
            // semua perubahan dibatalkan dan tidak ada data yang setengah tersimpan
            DB::transaction(function () use ($request) {

                foreach ($request->jadwal as $data) {
                    // Ambil data jadwal lama untuk dibandingkan dengan data baru
                    $jadwalLama = Jadwal::where('hari', $data['hari'])->first();

                    // Jika jam_buka atau jam_tutup kosong, gunakan nilai default '00:00:00'
                    $jamBuka  = $data['jam_buka']  ?: '00:00:00';
                    $jamTutup = $data['jam_tutup'] ?: '00:00:00';
                    $status   = $data['status'];

                    // Periksa apakah jadwal hari ini memang berubah dari sebelumnya
                    if ($jadwalLama && (
                        $jadwalLama->status   !== $status  ||
                        $jadwalLama->jam_buka  !== $jamBuka ||
                        $jadwalLama->jam_tutup !== $jamTutup
                    )) {
                        // Hapus kuota/slot masa depan yang statusnya masih 'tersedia'
                        // (belum dipesan oleh pelanggan manapun) karena jadwal berubah
                        \App\Models\KuotaBooking::where('hari', $data['hari'])
                            ->where('tgl', '>=', now()->format('Y-m-d'))
                            ->where('status_booking', 'tersedia')
                            ->delete();

                        // Jika hari ini diubah menjadi 'tutup', nonaktifkan sisa slot
                        // yang masih ada (misalnya slot yang sudah dipesan tetap ada, tapi ditandai nonaktif)
                        if ($status === 'tutup') {
                            \App\Models\KuotaBooking::where('hari', $data['hari'])
                                ->where('tgl', '>=', now()->format('Y-m-d'))
                                ->update(['status_kuota' => 'nonaktif']);
                        }
                    }

                    // Simpan atau perbarui data jadwal berdasarkan nama hari
                    Jadwal::updateOrCreate(
                        ['hari' => $data['hari']], // Kunci pencarian: nama hari
                        [
                            'jam_buka'  => $jamBuka,
                            'jam_tutup' => $jamTutup,
                            'status'    => $status,
                        ]
                    );
                }
            });

            // Tampilkan pesan sukses dan arahkan kembali ke halaman jadwal
            session()->flash('success', 'Jadwal operasional berhasil disimpan dan kuota booking terkait telah disesuaikan.');
            return redirect()->route('admin.jadwal');

        } catch (\Exception $e) {
            // Tampilkan pesan error jika terjadi kegagalan
            session()->flash('error', 'Terjadi kesalahan saat menyimpan jadwal: ' . $e->getMessage());
            return redirect()->route('admin.jadwal');
        }
    }

    /**
     * Mereset jadwal operasional satu hari tertentu ke kondisi tutup/kosong.
     *
     * Alur:
     * 1. Hapus semua kuota masa depan yang belum dipesan untuk hari ini.
     * 2. Nonaktifkan sisa kuota yang masih ada.
     * 3. Set jadwal hari ini menjadi 'tutup' dengan jam buka/tutup null.
     *
     * @param  string $hari Nama hari yang akan direset (contoh: 'Senin')
     * @return \Illuminate\Http\RedirectResponse
     */
    public function reset(string $hari)
    {
        try {
            DB::transaction(function () use ($hari) {
                // Hapus kuota/slot masa depan yang belum dipesan
                \App\Models\KuotaBooking::where('hari', $hari)
                    ->where('tgl', '>=', now()->format('Y-m-d'))
                    ->where('status_booking', 'tersedia')
                    ->delete();

                // Nonaktifkan sisa kuota yang masih ada untuk hari ini ke depan
                \App\Models\KuotaBooking::where('hari', $hari)
                    ->where('tgl', '>=', now()->format('Y-m-d'))
                    ->update(['status_kuota' => 'nonaktif']);

                // Set jadwal hari ini menjadi tutup dan kosongkan jam buka/tutup
                Jadwal::where('hari', $hari)->update([
                    'jam_buka'  => null,
                    'jam_tutup' => null,
                    'status'    => 'tutup',
                ]);
            });

            session()->flash('success', "Jadwal {$hari} berhasil direset dan kuota terkait telah disesuaikan.");

        } catch (\Exception $e) {
            session()->flash('error', 'Gagal mereset jadwal: ' . $e->getMessage());
        }

        return redirect()->route('admin.jadwal');
    }
}
