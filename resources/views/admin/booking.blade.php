<x-templates.admin-layout>
    <div x-data="{ showModal: false, selectedBooking: {} }" class="space-y-6 pb-10">

        <!-- Breadcrumb -->
        <div class="flex items-center gap-2 text-sm text-gray-500 font-medium">
            <a href="#" class="hover:text-primary transition-colors">Dashboard</a>
            <span>/</span>
            <span class="text-brand-text font-bold">Daftar Booking</span>
        </div>

        <!-- Header Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 flex items-start gap-4">
            <div class="p-3 bg-yellow-50 text-accent rounded-xl shadow-sm">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                    </path>
                </svg>
            </div>
            <div>
                <h2 class="text-xl font-bold text-brand-text font-poppins">Daftar Booking</h2>
                <p class="text-sm text-gray-500 mt-1">Lihat dan monitor seluruh data booking pelanggan Hero Barbershop
                    berdasarkan tanggal yang dipilih.</p>
            </div>
        </div>

        <!-- CSS styles for hide-scrollbar -->
        <style>
            .hide-scrollbar::-webkit-scrollbar {
                display: none;
            }

            .hide-scrollbar {
                -ms-overflow-style: none;
                scrollbar-width: none;
            }
        </style>

        <!-- Pilih Hari Card -->
        <div class="bg-brand-card rounded-xl shadow-sm border border-brand-border p-6">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                        </path>
                    </svg>
                    <h3 class="text-lg font-bold text-brand-text font-poppins">Pilih Hari</h3>
                </div>
                <form method="GET" action="{{ route('admin.booking') }}">
                    <input type="hidden" name="search" value="{{ request('search') }}">
                    <input type="date" name="date"
                        value="{{ request('date', \Carbon\Carbon::today()->format('Y-m-d')) }}"
                        class="rounded-lg border-gray-200 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50 text-sm text-gray-700 bg-white px-3 py-1.5 cursor-pointer"
                        onchange="this.form.submit()">
                </form>
            </div>

            <div class="relative flex items-center">
                <!-- Left Navigation Button -->
                <button type="button" @click="$refs.calendarScroll.scrollBy({left: -150, behavior: 'smooth'})"
                    class="hidden md:flex absolute -left-4 z-10 w-10 h-10 items-center justify-center bg-white border border-gray-200 rounded-full shadow hover:bg-gray-50 transition-colors focus:outline-none">
                    <svg class="w-5 h-5 text-gray-600 -ml-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7">
                        </path>
                    </svg>
                </button>

                <!-- Calendar Container (Scrollable) -->
                <div x-ref="calendarScroll"
                    class="flex-1 overflow-x-auto hide-scrollbar scroll-smooth px-8 py-4 -my-4 -mx-8">
                    <div class="flex items-stretch gap-3 min-w-max px-2">

                        @foreach ($days as $day)
                            <a href="{{ route('admin.booking', ['date' => $day['full_date'], 'search' => request('search')]) }}"
                                class="w-28 sm:w-32 flex-shrink-0 relative group flex flex-col items-center justify-center p-4 rounded-xl border transition-all duration-300 {{ $day['active'] ? 'bg-primary border-primary text-white shadow-md' : 'bg-white border-gray-200 text-gray-700 hover:border-primary hover:shadow-sm' }}">

                                @if ($day['today'])
                                    <span
                                        class="absolute -top-3 bg-green-500 text-white text-[10px] font-bold px-2.5 py-0.5 rounded-full shadow-sm">
                                        Hari Ini
                                    </span>
                                @endif

                                <span
                                    class="text-xs font-medium uppercase tracking-wider {{ $day['active'] ? 'text-gray-200' : 'text-gray-400 group-hover:text-primary' }}">{{ $day['day'] }}</span>
                                <span class="text-3xl font-bold font-poppins my-1">{{ $day['date'] }}</span>
                                <span
                                    class="text-xs font-medium uppercase tracking-wider {{ $day['active'] ? 'text-gray-300' : 'text-gray-400' }}">{{ $day['month'] }}</span>
                            </a>
                        @endforeach

                    </div>
                </div>

                <!-- Right Navigation Button -->
                <button type="button" @click="$refs.calendarScroll.scrollBy({left: 150, behavior: 'smooth'})"
                    class="hidden md:flex absolute -right-4 z-10 w-10 h-10 items-center justify-center bg-white border border-gray-200 rounded-full shadow hover:bg-gray-50 transition-colors focus:outline-none">
                    <svg class="w-5 h-5 text-gray-600 ml-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </button>
            </div>

            <div class="mt-8 pt-6 border-t border-brand-border">
                <form method="GET" action="{{ route('admin.booking') }}" class="relative w-full md:w-[350px]">
                    <input type="hidden" name="date"
                        value="{{ request('date', \Carbon\Carbon::today()->format('Y-m-d')) }}">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}"
                        class="bg-white border border-gray-200 text-gray-900 text-sm rounded-lg focus:ring-primary focus:border-primary block w-full pl-10 p-2.5 shadow-sm transition-colors"
                        placeholder="Cari nama pelanggan...">
                </form>
            </div>
        </div>

        <!-- Table Booking Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="p-6 border-b border-gray-100">
                <h3 class="text-lg font-bold text-brand-text font-poppins">Daftar Booking Pelanggan</h3>
                <p class="text-sm text-gray-500 mt-1">Data booking berdasarkan tanggal yang dipilih.</p>
            </div>

            <!-- Table Container -->
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-500 whitespace-nowrap">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 border-b border-gray-200 sticky top-0">
                        <tr>
                            <th scope="col" class="px-6 py-4 font-semibold">No</th>
                            <th scope="col" class="px-6 py-4 font-semibold">Nama Pelanggan</th>
                            <th scope="col" class="px-6 py-4 font-semibold">Email</th>
                            <th scope="col" class="px-6 py-4 font-semibold">No Telepon</th>
                            <th scope="col" class="px-6 py-4 font-semibold">Alamat</th>
                            <th scope="col" class="px-6 py-4 font-semibold">Jam Booking</th>
                            <th scope="col" class="px-6 py-4 font-semibold text-center">Status Booking</th>
                            <th scope="col" class="px-6 py-4 font-semibold text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($bookings as $booking)
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="px-6 py-4 font-medium text-gray-900">{{ $booking['no'] }}</td>
                                <td class="px-6 py-4 font-medium text-gray-900">{{ $booking['name'] }}</td>
                                <td class="px-6 py-4">{{ $booking['email'] }}</td>
                                <td class="px-6 py-4">{{ $booking['phone'] }}</td>
                                <td class="px-6 py-4">{{ $booking['address'] }}</td>
                                <td class="px-6 py-4 font-medium text-gray-700">{{ $booking['time'] }}</td>
                                <td class="px-6 py-4 text-center">
                                    <span
                                        class="inline-flex items-center px-2.5 py-1 text-xs font-medium border rounded-full {{ $booking['status_color'] }}">
                                        {{ $booking['status'] }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <button @click="selectedBooking = {{ json_encode($booking) }}; showModal = true"
                                        class="inline-flex items-center justify-center gap-1.5 px-3 py-1.5 bg-accent text-white text-xs font-medium rounded-lg hover:bg-gold transition-colors focus:outline-none focus:ring-2 focus:ring-accent focus:ring-offset-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                            </path>
                                        </svg>
                                        Detail
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <div
                                            class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mb-4">
                                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4">
                                                </path>
                                            </svg>
                                        </div>
                                        <h3 class="text-lg font-bold text-gray-900 mb-1">Belum Ada Booking</h3>
                                        <p class="text-gray-500">Belum terdapat booking pada tanggal yang dipilih.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="p-4 border-t border-gray-100 flex items-center justify-between">
                <span class="text-sm text-gray-500">Menampilkan <span
                        class="font-medium text-gray-900">{{ $bookingsData->firstItem() ?? 0 }}</span> sampai <span
                        class="font-medium text-gray-900">{{ $bookingsData->lastItem() ?? 0 }}</span> dari <span
                        class="font-medium text-gray-900">{{ $bookingsData->total() }}</span> booking</span>
                <div class="flex items-center gap-2">
                    {{ $bookingsData->appends(['date' => request('date'), 'search' => request('search')])->links('pagination::tailwind') }}
                </div>
            </div>
        </div>

        <!-- Modal Detail Pesanan -->
        <div x-show="showModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">

                <!-- Background overlay -->
                <div x-show="showModal" x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                    x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0"
                    class="fixed inset-0 transition-opacity bg-gray-900 bg-opacity-50" @click="showModal = false">
                </div>

                <!-- Modal Panel -->
                <div x-show="showModal" x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    class="inline-block w-full max-w-2xl p-6 my-8 overflow-hidden text-left align-middle transition-all transform bg-white shadow-xl rounded-xl sm:align-middle relative z-10 border border-gray-100">

                    <div class="flex items-start justify-between mb-5">
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-yellow-50 text-accent rounded-lg shadow-sm">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                                    </path>
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-brand-text font-poppins">Detail Pesanan</h3>
                        </div>
                        <button @click="showModal = false"
                            class="text-gray-400 hover:text-gray-500 transition-colors focus:outline-none">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    <!-- Identitas Pelanggan -->
                    <div class="flex items-center gap-4 mb-6 pb-6 border-b border-gray-100">
                        <div
                            class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center border border-gray-200 shadow-sm flex-shrink-0">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <div>
                            <h4 class="text-lg font-bold text-gray-900" x-text="selectedBooking.name">Nama Lengkap
                            </h4>
                            <p class="text-sm text-gray-500" x-text="selectedBooking.email">email@example.com</p>
                        </div>
                    </div>

                    <!-- Detail Booking (Dua Kolom) -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-4 mb-6">
                        <div class="space-y-4">
                            <div>
                                <span class="block text-sm font-medium text-gray-500 mb-1">Tanggal Booking</span>
                                <span class="block text-sm text-gray-900 font-medium"
                                    x-text="selectedBooking.date">-</span>
                            </div>
                            <div>
                                <span class="block text-sm font-medium text-gray-500 mb-1">Hari</span>
                                <span class="block text-sm text-gray-900 font-medium"
                                    x-text="selectedBooking.day">-</span>
                            </div>
                            <div>
                                <span class="block text-sm font-medium text-gray-500 mb-1">Jam Booking</span>
                                <span class="block text-sm text-gray-900 font-medium"
                                    x-text="selectedBooking.time">-</span>
                            </div>
                            <div>
                                <span class="block text-sm font-medium text-gray-500 mb-1">Durasi</span>
                                <span class="block text-sm text-gray-900 font-medium"
                                    x-text="selectedBooking.duration">-</span>
                            </div>
                            <div>
                                <span class="block text-sm font-medium text-gray-500 mb-1">Layanan</span>
                                <span class="block text-sm text-gray-900 font-medium"
                                    x-text="selectedBooking.service">-</span>
                            </div>
                        </div>
                        <div class="space-y-4">
                            <div>
                                <span class="block text-sm font-medium text-gray-500 mb-1">Nomor Booking</span>
                                <span class="block text-sm text-gray-900 font-medium"
                                    x-text="selectedBooking.booking_code">-</span>
                            </div>
                            <div>
                                <span class="block text-sm font-medium text-gray-500 mb-1">Status Booking</span>
                                <span
                                    class="inline-flex items-center px-2.5 py-1 text-xs font-medium border rounded-full mt-1"
                                    :class="selectedBooking.status_color">
                                    <span x-text="selectedBooking.status"></span>
                                </span>
                            </div>
                            <div>
                                <span class="block text-sm font-medium text-gray-500 mb-1">Status Slot</span>
                                <span class="block text-sm text-gray-900 font-medium"
                                    x-text="selectedBooking.slot_status">-</span>
                            </div>
                            <div>
                                <span class="block text-sm font-medium text-gray-500 mb-1">Metode Booking</span>
                                <span class="block text-sm text-gray-900 font-medium"
                                    x-text="selectedBooking.method">-</span>
                            </div>
                        </div>
                    </div>

                    <!-- Ringkasan Pesanan Card -->
                    <div class="bg-gray-50 rounded-xl p-5 mb-6 border border-gray-100">
                        <h4 class="text-sm font-bold text-gray-900 mb-3 uppercase tracking-wide">Ringkasan Pesanan</h4>
                        <div class="space-y-3">
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-500">Tanggal Dibuat</span>
                                <span class="text-sm font-medium text-gray-900"
                                    x-text="selectedBooking.created_date">-</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-500">Jam Dibuat</span>
                                <span class="text-sm font-medium text-gray-900"
                                    x-text="selectedBooking.created_time">-</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-500">Booking Terakhir Diupdate</span>
                                <span class="text-sm font-medium text-gray-900"
                                    x-text="selectedBooking.updated_at">-</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-500">Estimasi Selesai</span>
                                <span class="text-sm font-medium text-gray-900"
                                    x-text="selectedBooking.estimated_end">-</span>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end pt-5 border-t border-gray-100">
                        <button @click="showModal = false" type="button"
                            class="inline-flex justify-center w-full sm:w-auto px-6 py-2.5 bg-[#1F2A1D] text-white text-sm font-medium rounded-lg hover:bg-[#2c3d29] focus:outline-none focus:ring-2 focus:ring-[#1F2A1D] focus:ring-offset-2 transition-colors shadow-sm">
                            Tutup
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </div>
</x-templates.admin-layout>
