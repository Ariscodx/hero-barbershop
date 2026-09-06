<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pelanggan;
use Illuminate\Http\Request;
use Carbon\Carbon;

/**
 * Class PenggunaController (Admin)
 *
 * Bertanggung jawab mengelola data pengguna/pelanggan yang terdaftar
 * di sistem Hero Barbershop dari sisi administrator.
 * Menyediakan fitur daftar pelanggan, pencarian, dan statistik ringkasan.
 */
class PenggunaController extends Controller
{
    /**
     * Menampilkan daftar seluruh pelanggan beserta statistik dan fitur pencarian.
     *
     * Alur:
     * 1. Filter data berdasarkan kata kunci pencarian (nama/email) jika ada.
     * 2. Hitung statistik: total pengguna, pengguna baru (30 hari), dan akun aktif.
     * 3. Ambil data pelanggan dengan relasi booking dan paginasi.
     * 4. Format data ke array yang siap ditampilkan di view.
     *
     * @param  Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        // Inisialisasi query builder untuk tabel pelanggan
        $query = Pelanggan::query();

        // Terapkan filter pencarian berdasarkan nama atau email jika admin mengisi kolom search
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Hitung total seluruh pelanggan yang terdaftar di sistem
        $totalPengguna = Pelanggan::count();

        // Hitung pelanggan yang mendaftar dalam 30 hari terakhir (pengguna baru)
        $penggunaBaru = Pelanggan::where('created_at', '>=', Carbon::now()->subDays(30))->count();

        // Hitung akun aktif (saat ini diasumsikan semua akun yang ada di database berstatus aktif)
        $akunAktif = Pelanggan::count();

        // Ambil data pelanggan beserta:
        // - Jumlah total booking (withCount)
        // - Data booking terakhir (with relasi, diambil 1 saja untuk efisiensi)
        $pelanggans = $query
            ->withCount('bookings') // Menambah kolom 'bookings_count' di setiap data pelanggan
            ->with(['bookings' => function ($q) {
                // Hanya ambil 1 booking terakhir berdasarkan tanggal booking
                $q->latest('tgl_booking')->take(1);
            }])
            ->orderBy('created_at', 'desc')    // Urutkan dari pelanggan terbaru
            ->paginate(10)                      // Batasi 10 data per halaman
            ->withQueryString();                // Pertahankan parameter pencarian saat ganti halaman

        // Format ulang data pelanggan ke array yang sesuai kebutuhan tampilan (frontend/Alpine.js)
        $users = [];
        $no    = $pelanggans->firstItem(); // Nomor urut awal di halaman ini (contoh: halaman 2 mulai dari 11)

        foreach ($pelanggans as $p) {
            // Ambil data booking terakhir milik pelanggan ini (jika ada)
            $lastBooking = $p->bookings->first();

            $users[] = [
                'id'            => $p->id_pelanggan,
                'no'            => $no++,                    // Nomor urut yang bertambah otomatis
                'name'          => $p->nama,
                'email'         => $p->email,
                'phone'         => $p->no_tlp  ?? '-',       // Tampilkan '-' jika nomor telepon kosong
                'address'       => $p->alamat  ?? '-',       // Tampilkan '-' jika alamat kosong

                // Format tanggal registrasi ke format Indonesia (contoh: 12 Juli 2026)
                'date'          => $p->created_at ? $p->created_at->translatedFormat('d F Y') : '-',

                'status'        => 'Aktif',                  // Status akun (saat ini default Aktif)
                'total_booking' => $p->bookings_count,       // Hasil dari withCount('bookings')

                // Tanggal booking terakhir dalam format Indonesia, atau '-' jika belum pernah booking
                'last_booking'  => $lastBooking
                    ? Carbon::parse($lastBooking->tgl_booking)->translatedFormat('d F Y')
                    : '-',

                // Status booking terakhir dengan huruf kapital di awal (contoh: Selesai, Dibatalkan)
                'last_status'   => $lastBooking ? ucfirst($lastBooking->status) : '-',
            ];
        }

        // Kirim semua data yang sudah diproses ke view pengguna admin
        return view('admin.pengguna', compact(
            'users',
            'pelanggans',
            'totalPengguna',
            'penggunaBaru',
            'akunAktif'
        ));
    }
}
