<x-templates.admin-layout>
    <div class="space-y-8">

        <!-- Welcome Card -->
        <div class="bg-brand-card rounded-2xl shadow-sm border border-brand-border overflow-hidden relative">
            <div class="flex flex-col md:flex-row items-stretch min-h-[220px]">

                <!-- Text Section -->
                <div class="p-8 md:p-10 flex flex-col justify-center w-full md:w-7/12 lg:w-2/3 z-10">
                    <h2 class="text-3xl font-bold text-brand-text font-poppins mb-3 tracking-tight">Selamat Datang,
                        Admin!</h2>
                    <p class="text-gray-500 text-base max-w-lg leading-relaxed">Berikut ringkasan aktivitas Hero
                        Barbershop hari ini. Pantau jadwal, kuota booking, dan kelola pelanggan dengan mudah.</p>

                    <div class="mt-8">
                        <a href="{{ route('admin.jadwal') }}"
                            class="inline-flex items-center justify-center px-6 py-2.5 bg-primary text-white font-medium rounded-lg hover:bg-gold hover:shadow-lg hover:scale-[1.02] active:scale-[0.98] transition-all duration-200 shadow-md focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 cursor-pointer">
                            Kelola Jadwal
                        </a>
                    </div>
                </div>

                <!-- Image Section -->
                <div class="hidden md:block md:w-5/12 lg:w-1/3 relative bg-gray-50/50">
                    <!-- Subtle gradient overlay -->
                    <div class="absolute inset-0 bg-gradient-to-r from-brand-card to-transparent z-10 w-24"></div>
                    <!-- Generated Image -->
                    <img src="{{ asset('images/barbershop_banner.png') }}" alt="Hero Barbershop Banner"
                        class="absolute inset-0 w-full h-full object-cover object-center opacity-90">
                </div>

                <!-- Decorative Elements -->
                <div class="absolute -top-24 -left-24 w-64 h-64 bg-accent/5 rounded-full blur-3xl pointer-events-none">
                </div>
            </div>
        </div>

        <!-- Statistic Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

            <!-- Card 1: Total Booking -->
            <x-organisms.stat-card title="Total Booking" value="{{ $totalBooking }}">
                <x-slot name="icon">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                        </path>
                    </svg>
                </x-slot>
            </x-organisms.stat-card>

            <!-- Card 2: Kuota Tersedia -->
            <x-organisms.stat-card title="Kuota Tersedia" value="{{ $kuotaTersedia }}">
                <x-slot name="icon">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z">
                        </path>
                    </svg>
                </x-slot>
            </x-organisms.stat-card>

            <!-- Card 3: Total Pengguna -->
            <x-organisms.stat-card title="Total Pengguna" value="{{ $totalPengguna }}">
                <x-slot name="icon">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                        </path>
                    </svg>
                </x-slot>
            </x-organisms.stat-card>

        </div>

        <!-- Table Booking Component -->
        <x-organisms.table-booking :bookings="$todayBookings" />

    </div>
</x-templates.admin-layout>
