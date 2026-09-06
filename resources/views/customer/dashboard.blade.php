{{-- Menggunakan layout utama khusus untuk pelanggan (customer) --}}
@extends('customer.layouts.app')

{{-- Konfigurasi meta tag title dan description untuk halaman dashboard --}}
@section('title', config('app.name', 'Hero Barbershop') . ' - Dashboard')
@section('description', 'Dashboard pelanggan Hero Barbershop — pantau booking, jadwal, dan riwayat layanan kamu.')

@section('content')

    {{-- ===================== HERO SECTION ===================== --}}
    {{-- Bagian banner atas yang menyapa pelanggan setelah login --}}
    <section class="relative overflow-hidden"
        style="background: linear-gradient(135deg, #1F2A1D 0%, #2D3E2A 60%, #1A2418 100%);">
        {{-- Dekorasi background berupa blob bercahaya --}}
        <div class="absolute top-0 right-0 w-80 h-80 rounded-full blur-3xl opacity-20 pointer-events-none"
            style="background: radial-gradient(circle, #D4B06A, transparent 70%);"></div>
        <div class="absolute bottom-0 left-0 w-64 h-64 rounded-full blur-3xl opacity-10 pointer-events-none"
            style="background: radial-gradient(circle, #D4B06A, transparent 70%);"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 lg:py-24 relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">

                {{-- KIRI: Konten Teks Sambutan --}}
                <div class="text-white">
                    {{-- Badge informasi Barbershop --}}
                    <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full text-xs font-semibold mb-6 border"
                        style="background: rgba(212,176,106,0.12); border-color: rgba(212,176,106,0.3); color: #D4B06A;">
                        <span class="w-1.5 h-1.5 rounded-full bg-[#D4B06A] badge-pulse"></span>
                        Hero Barbershop · Booking Online
                    </div>

                    <h1 class="text-4xl lg:text-5xl xl:text-6xl font-bold leading-tight mb-4">
                        Selamat Datang,<br>
                        {{-- Mengambil nama pelanggan yang sedang login menggunakan Auth Guard 'pelanggan' --}}
                        <span
                            style="background: linear-gradient(135deg, #D4B06A, #F0D080); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">
                            {{ Auth::guard('pelanggan')->user()->nama ?? 'Pelanggan' }}!
                        </span>
                    </h1>

                    <p class="text-gray-300 text-base lg:text-lg leading-relaxed mb-8 max-w-md">
                        Selamat datang di <strong class="text-white">Hero Barbershop</strong>.
                        Booking layanan potong rambut kini lebih mudah.
                        Pilih jadwal yang tersedia dan lakukan reservasi secara online
                        <span class="text-[#D4B06A] font-medium">tanpa perlu menunggu antrean</span>.
                    </p>

                    {{-- Tombol navigasi utama di Hero Section --}}
                    <div class="flex flex-col sm:flex-row gap-4">
                        <a href="{{ route('customer.booking') }}"
                            class="inline-flex items-center justify-center gap-2 px-7 py-3.5 rounded-xl text-[#1F2A1D] font-semibold text-sm transition-colors duration-200 ease-in-out shadow-lg hover:opacity-90"
                            style="background: linear-gradient(135deg, #D4B06A, #F0D080);">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Buat Booking Sekarang
                        </a>
                        <a href="{{ route('customer.status') }}"
                            class="inline-flex items-center justify-center gap-2 px-7 py-3.5 rounded-xl text-white font-semibold text-sm border-2 transition-colors duration-200 ease-in-out hover:bg-white/10"
                            style="border-color: rgba(212,176,106,0.5);">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Lihat Status Booking
                        </a>
                    </div>
                </div>

                {{-- KANAN: Kartu Ilustrasi Logo Barbershop --}}
                <div class="flex justify-center lg:justify-end">
                    <div class="relative w-full max-w-sm">
                        <div class="rounded-2xl overflow-hidden shadow-2xl"
                            style="background: linear-gradient(135deg, #2D3E2A, #1F2A1D);">
                            {{-- Top Bar mirip desain jendela aplikasi/browser --}}
                            <div class="flex items-center gap-2 px-4 py-3"
                                style="background: rgba(212,176,106,0.1); border-bottom: 1px solid rgba(212,176,106,0.2);">
                                <div class="w-2.5 h-2.5 rounded-full bg-red-400"></div>
                                <div class="w-2.5 h-2.5 rounded-full bg-yellow-400"></div>
                                <div class="w-2.5 h-2.5 rounded-full bg-green-400"></div>
                                <span class="ml-2 text-xs text-[#D4B06A] font-medium">Hero Barbershop Booking</span>
                            </div>

                            <div class="p-6 relative min-h-[280px] flex items-center justify-center">
                                <div class="flex items-center justify-center py-6">
                                    <div class="relative">
                                        <div class="w-56 h-56 rounded-full flex items-center justify-center bg-black"
                                            style="border: 2px solid rgba(212,176,106,0.3);">
                                            <img src="{{ asset('images/logo_hero_barbershop.png') }}" alt="Hero Barbershop"
                                                class="w-48 h-48 object-contain drop-shadow-[0_4px_16px_rgba(212,176,106,0.4)]">
                                        </div>
                                        {{-- Efek denyut (pulse) di sekeliling logo --}}
                                        <div class="absolute inset-0 rounded-full animate-ping opacity-20"
                                            style="border: 2px solid #D4B06A;"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>
    {{-- ==================== END HERO SECTION ==================== --}}


    {{-- ===================== MAIN CONTENT ===================== --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 space-y-10">

        {{-- ---- QUICK INFO CARDS ---- --}}
        {{-- Menampilkan 4 metrik utama pelanggan berupa angka statistik --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6">

            {{-- Kartu 1: Jumlah Booking Aktif (Pending/Dikonfirmasi yang belum selesai) --}}
            <div class="bg-white rounded-2xl p-5 lg:p-6 shadow-sm border border-[#E5E7EB] card-hover cursor-pointer group">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-11 h-11 rounded-xl flex items-center justify-center transition-transform duration-200 group-hover:scale-110"
                        style="background: linear-gradient(135deg, rgba(31,42,29,0.08), rgba(31,42,29,0.15));">
                        <svg class="w-6 h-6 text-[#1F2A1D]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <span class="text-3xl font-bold text-[#1F2A1D]">{{ $bookingAktif }}</span>
                </div>
                <p class="text-sm font-semibold text-gray-700">Booking Aktif</p>
                <p class="text-xs text-gray-400 mt-0.5">Booking yang sedang berjalan</p>
            </div>

            {{-- Kartu 2: Status Booking (Total booking dalam periode tertentu/bulan ini) --}}
            <div class="bg-white rounded-2xl p-5 lg:p-6 shadow-sm border border-[#E5E7EB] card-hover cursor-pointer group">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-11 h-11 rounded-xl flex items-center justify-center transition-transform duration-200 group-hover:scale-110"
                        style="background: linear-gradient(135deg, rgba(212,176,106,0.12), rgba(212,176,106,0.2));">
                        <svg class="w-6 h-6 text-[#D4B06A]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <span class="text-3xl font-bold text-[#D4B06A]">{{ $statusBooking }}</span>
                </div>
                <p class="text-sm font-semibold text-gray-700">Status Booking</p>
                <p class="text-xs text-gray-400 mt-0.5">Total booking bulan ini</p>
            </div>

            {{-- Kartu 3: Riwayat Booking Keseluruhan --}}
            <div class="bg-white rounded-2xl p-5 lg:p-6 shadow-sm border border-[#E5E7EB] card-hover cursor-pointer group">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-11 h-11 rounded-xl flex items-center justify-center transition-transform duration-200 group-hover:scale-110"
                        style="background: linear-gradient(135deg, rgba(59,130,246,0.08), rgba(59,130,246,0.15));">
                        <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <span class="text-3xl font-bold text-blue-500">{{ $riwayatBooking }}</span>
                </div>
                <p class="text-sm font-semibold text-gray-700">Riwayat Booking</p>
                <p class="text-xs text-gray-400 mt-0.5">Total kunjungan tercatat</p>
            </div>

            {{-- Kartu 4: Info Operasional Hari Ini (Buka/Tutup beserta jamnya) --}}
            <div class="bg-white rounded-2xl p-5 lg:p-6 shadow-sm border border-[#E5E7EB] card-hover cursor-pointer group">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-11 h-11 rounded-xl flex items-center justify-center transition-transform duration-200 group-hover:scale-110"
                        style="background: linear-gradient(135deg, rgba(34,197,94,0.08), rgba(34,197,94,0.15));">
                        <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="flex flex-col items-end">
                        {{-- Logika pengecekan status barbershop (Buka atau Tutup) hari ini --}}
                        @if ($hariIni && strtolower($hariIni->status) == 'buka')
                            <span class="text-xs font-bold text-green-500 badge-pulse">● BUKA</span>
                        @else
                            <span class="text-xs font-bold text-red-500">● TUTUP</span>
                        @endif
                    </div>
                </div>
                <p class="text-sm font-semibold text-gray-700">Hari Ini</p>
                <p class="text-xs text-gray-400 mt-0.5">
                    {{-- Parsing jam buka tutup menggunakan Carbon jika hari ini buka, jika null tampilkan 'Libur' --}}
                    {{ $hariIni ? \Carbon\Carbon::parse($hariIni->jam_buka)->format('H:i') . ' - ' . \Carbon\Carbon::parse($hariIni->jam_tutup)->format('H:i') . ' WIB' : 'Libur' }}
                </p>
            </div>

        </div>
        {{-- ---- END QUICK INFO CARDS ---- --}}


        {{-- ---- TWO COLUMN ROW: Jadwal + Booking Terakhir ---- --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

            {{-- ---- KOLOM KIRI: Tabel Jadwal Operasional Selama Seminggu ---- --}}
            <div class="bg-white rounded-2xl shadow-sm border border-[#E5E7EB] overflow-hidden">
                <div class="px-6 py-5 border-b border-[#E5E7EB] flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl flex items-center justify-center"
                            style="background: rgba(31,42,29,0.08);">
                            <svg class="w-5 h-5 text-[#1F2A1D]" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <h2 class="text-base font-bold text-[#1F2A1D]">Jadwal Operasional</h2>
                    </div>
                    <span class="text-xs text-[#D4B06A] font-medium bg-[#D4B06A]/10 px-3 py-1 rounded-full">Hero
                        Barbershop</span>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr style="background: #F8F8F5;">
                                <th
                                    class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Hari</th>
                                <th
                                    class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Jam Buka</th>
                                <th
                                    class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Jam Tutup</th>
                                <th
                                    class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            {{-- Mendapatkan nama hari ini dalam bahasa Indonesia untuk me-highlight row --}}
                            @php
                                $hariIniName = now()->locale('id')->dayName;
                            @endphp
                            {{-- Looping seluruh data jadwal dari controller --}}
                            @foreach ($jadwal as $item)
                                <tr
                                    class="transition-colors duration-150 {{ $hariIniName === $item->hari ? 'bg-[#1F2A1D]/[0.03] hover:bg-[#1F2A1D]/[0.06]' : 'hover:bg-gray-50/50' }}">
                                    <td class="px-6 py-3.5">
                                        <div class="flex items-center gap-2">
                                            @if ($hariIniName === $item->hari)
                                                <span class="w-2 h-2 rounded-full bg-[#D4B06A]"></span>
                                            @endif
                                            <span
                                                class="font-medium text-gray-800 {{ $hariIniName === $item->hari ? 'text-[#1F2A1D] font-semibold' : '' }}">
                                                {{ $item->hari }}
                                            </span>
                                            @if ($hariIniName === $item->hari)
                                                <span class="text-xs text-[#D4B06A] font-medium">(Hari ini)</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-4 py-3.5 text-gray-600">
                                        {{ \Carbon\Carbon::parse($item->jam_buka)->format('H:i') }}</td>
                                    <td class="px-4 py-3.5 text-gray-600">
                                        {{ \Carbon\Carbon::parse($item->jam_tutup)->format('H:i') }}</td>
                                    <td class="px-4 py-3.5">
                                        {{-- Badge Status Buka/Tutup berdasarkan data jadwal --}}
                                        @if (strtolower($item->status) === 'buka')
                                            <span
                                                class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">
                                                <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> Buka
                                            </span>
                                        @else
                                            <span
                                                class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-600">
                                                <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span> Tutup
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="px-6 py-4 border-t border-[#E5E7EB] bg-gray-50/50">
                    <a href="{{ route('customer.jadwal') }}"
                        class="inline-flex items-center gap-1.5 text-sm font-medium text-[#1F2A1D] transition-colors duration-200 ease-in-out hover:text-[#D4B06A]">
                        Lihat Jadwal Lengkap
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                </div>
            </div>

            {{-- ---- KOLOM KANAN: Daftar Transaksi / Booking Terakhir ---- --}}
            <div class="bg-white rounded-2xl shadow-sm border border-[#E5E7EB] overflow-hidden">
                <div class="px-6 py-5 border-b border-[#E5E7EB] flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl flex items-center justify-center"
                            style="background: rgba(212,176,106,0.12);">
                            <svg class="w-5 h-5 text-[#D4B06A]" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                        </div>
                        <h2 class="text-base font-bold text-[#1F2A1D]">Booking Terakhir</h2>
                    </div>
                    <a href="{{ route('customer.riwayat') }}"
                        class="text-xs text-[#D4B06A] hover:underline font-medium">Lihat Semua</a>
                </div>
                <div class="p-6 space-y-4">
                    {{-- Array konfigurasi untuk mapping warna badge berdasarkan status booking --}}
                    @php
                        $statusConfig = [
                            'menunggu' => [
                                'label' => 'Menunggu',
                                'bg' => 'bg-yellow-100',
                                'text' => 'text-yellow-700',
                                'dot' => 'bg-yellow-500',
                            ],
                            'pending' => [
                                'label' => 'Menunggu',
                                'bg' => 'bg-yellow-100',
                                'text' => 'text-yellow-700',
                                'dot' => 'bg-yellow-500',
                            ],
                            'dikonfirmasi' => [
                                'label' => 'Dikonfirmasi',
                                'bg' => 'bg-green-100',
                                'text' => 'text-green-700',
                                'dot' => 'bg-green-500',
                            ],
                            'selesai' => [
                                'label' => 'Selesai',
                                'bg' => 'bg-blue-100',
                                'text' => 'text-blue-700',
                                'dot' => 'bg-blue-500',
                            ],
                            'dibatalkan' => [
                                'label' => 'Dibatalkan',
                                'bg' => 'bg-red-100',
                                'text' => 'text-red-600',
                                'dot' => 'bg-red-500',
                            ],
                        ];
                    @endphp
                    
                    {{-- Looping menampilkan 3 atau beberapa data booking terakhir pelanggan --}}
                    @forelse($bookingTerakhir as $booking)
                        @php $cfg = $statusConfig[strtolower($booking->status)] ?? $statusConfig['menunggu']; @endphp
                        <div
                            class="flex items-start gap-4 p-4 rounded-xl border border-[#E5E7EB] transition-colors duration-200 ease-in-out hover:border-[#D4B06A]/40 hover:bg-amber-50/30 group">
                            <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0"
                                style="background: rgba(31,42,29,0.06);">
                                <svg class="w-5 h-5 text-[#1F2A1D]" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between gap-2 flex-wrap">
                                    {{-- Format nomor invoice: BK-0001 --}}
                                    <p class="text-sm font-semibold text-[#1F2A1D] truncate">
                                        BK-{{ str_pad($booking->id_booking, 4, '0', STR_PAD_LEFT) }}</p>
                                    <span
                                        class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold {{ $cfg['bg'] }} {{ $cfg['text'] }} flex-shrink-0">
                                        <span class="w-1.5 h-1.5 rounded-full {{ $cfg['dot'] }}"></span>
                                        {{ $cfg['label'] }}
                                    </span>
                                </div>
                                <p class="text-xs text-gray-500 mt-1">
                                    <span>{{ \Carbon\Carbon::parse($booking->tgl_booking)->locale('id')->translatedFormat('d F Y') }}</span>
                                    <span class="mx-1">·</span>
                                    <span>{{ \Carbon\Carbon::parse($booking->jam_booking)->format('H:i') }} WIB</span>
                                    <span class="mx-1">·</span>
                                    <span class="font-medium text-gray-600">Barbershop</span>
                                </p>
                            </div>
                        </div>
                    @empty
                        {{-- Fallback teks jika belum pernah melakukan booking --}}
                        <div class="text-center py-6 text-gray-500 text-sm">Belum ada booking terakhir.</div>
                    @endforelse
                </div>
                <div class="px-6 py-4 border-t border-[#E5E7EB] bg-gray-50/50">
                    <a href="{{ route('customer.riwayat') }}"
                        class="inline-flex items-center gap-1.5 text-sm font-medium text-[#1F2A1D] transition-colors duration-200 ease-in-out hover:text-[#D4B06A]">
                        Lihat Semua Riwayat
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                </div>
            </div>

        </div>
        {{-- ---- END TWO COLUMN ROW ---- --}}


        {{-- ---- QUICK ACTION BUTTONS ---- --}}
        {{-- Area navigasi menu berbentuk card/tombol besar --}}
        <div>
            <div class="flex items-center gap-3 mb-6">
                <div class="h-px flex-1 bg-gradient-to-r from-transparent to-[#E5E7EB]"></div>
                <h2 class="text-lg font-bold text-[#1F2A1D] px-4">Aksi Cepat</h2>
                <div class="h-px flex-1 bg-gradient-to-l from-transparent to-[#E5E7EB]"></div>
            </div>

            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">

                {{-- Tombol: Buat Booking Baru --}}
                <a href="{{ route('customer.booking') }}"
                    class="group flex flex-col items-center justify-center gap-3 p-6 rounded-2xl text-center card-hover border-2 text-white"
                    style="background: linear-gradient(135deg, #1F2A1D, #2D3E2A); border-color: #2D3E2A;">
                    <div class="w-14 h-14 rounded-2xl flex items-center justify-center transition-transform duration-200 group-hover:scale-110"
                        style="background: rgba(212,176,106,0.15);">
                        <svg class="w-7 h-7 text-[#D4B06A]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                    </div>
                    <div>
                        <p class="font-semibold text-sm">Buat Booking</p>
                        <p class="text-xs text-gray-400 mt-0.5">Reservasi layanan</p>
                    </div>
                </a>

                {{-- Tombol: Lihat Jadwal --}}
                <a href="{{ route('customer.jadwal') }}"
                    class="group flex flex-col items-center justify-center gap-3 p-6 rounded-2xl text-center card-hover border border-[#E5E7EB] bg-white">
                    ...
                </a>

                {{-- Tombol: Cek Status Booking --}}
                <a href="{{ route('customer.status') }}"
                    class="group flex flex-col items-center justify-center gap-3 p-6 rounded-2xl text-center card-hover border border-[#E5E7EB] bg-white">
                    ...
                </a>

                {{-- Tombol: Lihat Riwayat Booking --}}
                <a href="{{ route('customer.riwayat') }}"
                    class="group flex flex-col items-center justify-center gap-3 p-6 rounded-2xl text-center card-hover border border-[#E5E7EB] bg-white">
                    ...
                </a>

            </div>
        </div>
        {{-- ---- END QUICK ACTION BUTTONS ---- --}}


        {{-- ---- RIWAYAT BOOKING TABLE (List Lengkap) ---- --}}
        {{-- Tabel lengkap seluruh transaksi booking yang dilakukan pelanggan --}}
        <div class="bg-white rounded-2xl shadow-sm border border-[#E5E7EB] overflow-hidden">
            <div class="px-6 py-5 border-b border-[#E5E7EB] flex items-center justify-between flex-wrap gap-3">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl flex items-center justify-center"
                        style="background: rgba(59,130,246,0.08);">
                        <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-base font-bold text-[#1F2A1D]">Riwayat Semua Booking</h2>
                        {{-- Memanggil total data booking dari instance Paginator --}}
                        <p class="text-xs text-gray-400">{{ $semuaBooking->total() }} booking tercatat</p>
                    </div>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr style="background: #F8F8F5;">
                            <th class="text-left px-6 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">No. Booking</th>
                            <th class="text-left px-4 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">Tanggal</th>
                            <th class="text-left px-4 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">Jam</th>
                            <th class="text-left px-4 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">Barber</th>
                            <th class="text-left px-4 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="text-left px-4 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        {{-- Menggunakan kembali $statusConfig untuk konsistensi warna label --}}
                        @php
                            // ... [Konfigurasi array $statusConfig sama dengan atas] ...
                        @endphp
                        {{-- Looping data tabel utama (mendukung pagination) --}}
                        @forelse($semuaBooking as $row)
                            @php $cfg = $statusConfig[strtolower($row->status)] ?? $statusConfig['menunggu']; @endphp
                            <tr class="transition-colors duration-150 hover:bg-gray-50/50">
                                <td class="px-6 py-4">
                                    <span class="font-mono text-xs font-semibold text-[#1F2A1D] bg-gray-100 px-2 py-1 rounded-lg">BK-{{ str_pad($row->id_booking, 4, '0', STR_PAD_LEFT) }}</span>
                                </td>
                                <td class="px-4 py-4 text-gray-600">
                                    {{ \Carbon\Carbon::parse($row->tgl_booking)->locale('id')->translatedFormat('d M Y') }}
                                </td>
                                <td class="px-4 py-4 text-gray-600">
                                    {{ \Carbon\Carbon::parse($row->jam_booking)->format('H:i') }} WIB</td>
                                <td class="px-4 py-4">
                                    <div class="flex items-center gap-2">
                                        <div class="w-7 h-7 rounded-full flex items-center justify-center text-white text-xs font-semibold flex-shrink-0"
                                            style="background: linear-gradient(135deg, #1F2A1D, #2D3E2A);">H</div>
                                        <span class="text-gray-700 font-medium">Hero Barbershop</span>
                                    </div>
                                </td>
                                <td class="px-4 py-4">
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold {{ $cfg['bg'] }} {{ $cfg['text'] }}">
                                        <span class="w-1.5 h-1.5 rounded-full {{ $cfg['dot'] }}"></span>
                                        {{ $cfg['label'] }}
                                    </span>
                                </td>
                                <td class="px-4 py-4">
                                    {{-- Link menuju detail booking --}}
                                    <a href="{{ route('customer.status') }}"
                                        class="text-xs font-medium text-[#1F2A1D] transition-colors duration-200 ease-in-out hover:text-[#D4B06A] px-3 py-1.5 rounded-lg hover:bg-[#D4B06A]/10">
                                        Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-8 text-center text-gray-500">Belum ada riwayat booking.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            {{-- Footer tabel: Pagination & Info Jumlah Data --}}
            <div class="px-6 py-4 border-t border-[#E5E7EB] bg-gray-50/50 flex items-center justify-between flex-wrap gap-3">
                <p class="text-xs text-gray-400">Menampilkan {{ $semuaBooking->firstItem() ?? 0 }} -
                    {{ $semuaBooking->lastItem() ?? 0 }} dari {{ $semuaBooking->total() }} booking</p>
                
                {{-- Tombol "Muat Lebih Banyak" akan muncul jika ada halaman selanjutnya --}}
                @if ($semuaBooking->hasMorePages())
                    <a href="{{ $semuaBooking->nextPageUrl() }}"
                        class="inline-flex items-center gap-1.5 text-sm font-medium text-[#1F2A1D] transition-colors duration-200 ease-in-out hover:text-[#D4B06A]">
                        Muat Lebih Banyak
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </a>
                @endif
            </div>
        </div>
        {{-- ---- END RIWAYAT BOOKING TABLE ---- --}}

    </div>
    {{-- ==================== END MAIN CONTENT ==================== --}}

@endsection