<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;
use Carbon\Carbon;

/**
 * Class BookingController (Admin)
 *
 * Bertanggung jawab mengelola tampilan dan pengelolaan data booking
 * dari sisi administrator, termasuk filter tanggal dan pencarian.
 */
class BookingController extends Controller
{
    /**
     * Menampilkan daftar booking untuk tanggal yang dipilih beserta fitur pencarian.
     *
     * Alur:
     * 1. Perbarui status booking secara otomatis (berlangsung/selesai) sebelum menampilkan data.
     * 2. Ambil tanggal yang dipilih dari parameter URL, jika kosong gunakan hari ini.
     * 3. Buat daftar navigasi hari (30 hari: 3 hari sebelum hingga 26 hari sesudah tanggal terpilih).
     * 4. Query booking berdasarkan tanggal dan filter pencarian (nama/email).
     * 5. Format data booking untuk keperluan tampilan antarmuka admin.
     *
     * @param  Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        // Perbarui status booking secara otomatis sebelum data ditampilkan
        // (misalnya: 'terkonfirmasi' → 'berlangsung' → 'selesai' berdasarkan waktu)
        Booking::updateStatusOtomatis();

        // Ambil tanggal yang dipilih dari parameter URL '?date=...',
        // jika tidak ada maka gunakan hari ini sebagai default
        $selectedDate = $request->get('date')
            ? Carbon::parse($request->get('date'))
            : Carbon::today();

        // Buat daftar navigasi tanggal sebanyak 30 hari
        // Dimulai dari 3 hari sebelum tanggal terpilih
        $days          = [];
        $startOfWeek   = $selectedDate->copy()->subDays(3);

        for ($i = 0; $i < 30; $i++) {
            $date = clone $startOfWeek;
            $date->addDays($i);

            $days[] = [
                'day'       => $date->translatedFormat('l'),       // Nama hari penuh (contoh: Senin)
                'date'      => $date->format('d'),                  // Tanggal angka (contoh: 06)
                'month'     => $date->translatedFormat('M'),        // Nama bulan singkat (contoh: Sep)
                'full_date' => $date->format('Y-m-d'),              // Format untuk query/link
                'active'    => $date->format('Y-m-d') === $selectedDate->format('Y-m-d'), // Apakah hari yang dipilih
                'today'     => $date->isToday(),                    // Apakah hari ini
            ];
        }

        // Ambil data booking yang sesuai dengan tanggal yang dipilih,
        // sertakan relasi ke KuotaBooking untuk info slot
        $query = Booking::with('kuotaBooking')->whereDate('tgl_booking', $selectedDate);

        // Terapkan filter pencarian berdasarkan nama atau email pelanggan
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Ambil data dengan paginasi 10 data per halaman, diurutkan berdasarkan jam booking
        $bookingsData = $query->orderBy('jam_booking', 'asc')->paginate(10);

        // Peta warna badge status untuk tampilan UI
        $statusColors = [
            'terkonfirmasi' => 'bg-green-100 text-green-700 border-green-200',
            'berlangsung'   => 'bg-blue-100 text-blue-700 border-blue-200',
            'selesai'       => 'bg-gray-100 text-gray-700 border-gray-200',
            'dibatalkan'    => 'bg-red-100 text-red-700 border-red-200',
        ];

        // Peta label teks status agar lebih ramah dibaca oleh admin
        $statusLabels = [
            'terkonfirmasi' => 'Dikonfirmasi',
            'berlangsung'   => 'Sedang Berlangsung',
            'selesai'       => 'Selesai',
            'dibatalkan'    => 'Dibatalkan',
        ];

        // Format ulang (mapping) data booking ke array yang sesuai kebutuhan tampilan
        $bookings = collect($bookingsData->items())->map(function ($booking, $index) use ($bookingsData, $statusColors, $statusLabels) {

            // Hitung estimasi waktu selesai (jam booking + 30 menit)
            $jamBooking   = Carbon::parse($booking->jam_booking);
            $estimatedEnd = clone $jamBooking;
            $estimatedEnd->addMinutes(30); // Durasi default layanan potong rambut: 30 menit

            return [
                'no'           => $bookingsData->firstItem() + $index, // Nomor urut di halaman ini
                'name'         => $booking->nama,
                'email'        => $booking->email,
                'phone'        => $booking->no_tlp,
                'address'      => $booking->alamat,
                'time'         => $jamBooking->format('H:i') . ' - ' . $estimatedEnd->format('H:i'),
                'status'       => $statusLabels[$booking->status] ?? ucfirst($booking->status),
                'status_color' => $statusColors[$booking->status]  ?? 'bg-gray-100 text-gray-700 border-gray-200',

                // Kode booking unik: BK-YYYYMMDD-XXX (contoh: BK-20260906-001)
                'booking_code' => 'BK-' . Carbon::parse($booking->tgl_booking)->format('Ymd') . '-' . str_pad($booking->id_booking, 3, '0', STR_PAD_LEFT),

                'date'          => Carbon::parse($booking->tgl_booking)->translatedFormat('d F Y'),
                'day'           => $booking->hari,
                'duration'      => '30 Menit',
                'service'       => 'Potong Rambut',
                'slot_status'   => $booking->kuotaBooking ? ucfirst($booking->kuotaBooking->status) : 'Slot Aktif',
                'method'        => 'Booking Online',
                'created_date'  => $booking->created_at->translatedFormat('d F Y'),
                'created_time'  => $booking->created_at->format('H:i'),
                'updated_at'    => $booking->updated_at->translatedFormat('d F Y H:i'),
                'estimated_end' => $estimatedEnd->format('H:i'),
            ];
        });

        return view('admin.booking', compact('days', 'bookings', 'selectedDate', 'bookingsData'));
    }
}
