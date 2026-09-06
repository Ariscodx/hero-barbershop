<x-templates.admin-layout>
    <div x-data="{ showModal: false, selectedUser: {} }" class="space-y-6 pb-10">
        
        <!-- Breadcrumb -->
        <div class="flex items-center gap-2 text-sm text-gray-500 font-medium">
            <a href="{{ route('admin.dashboard') }}" class="hover:text-primary transition-colors">Dashboard</a>
            <span>/</span>
            <span class="text-brand-text font-bold">Daftar Pengguna</span>
        </div>

        <!-- Header Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 flex items-start gap-4">
            <div class="p-3 bg-yellow-50 text-accent rounded-xl shadow-sm">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                </svg>
            </div>
            <div>
                <h2 class="text-xl font-bold text-brand-text font-poppins">Daftar Pengguna</h2>
                <p class="text-sm text-gray-500 mt-1">Kelola dan lihat seluruh data pelanggan yang telah terdaftar pada sistem Hero Barbershop.</p>
            </div>
        </div>

        <!-- Search Area -->
        <div>
            <form method="GET" action="{{ route('admin.pengguna') }}" class="relative w-full md:w-[350px]">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                <input type="text" name="search" value="{{ request('search') }}" class="bg-white border border-gray-200 text-gray-900 text-sm rounded-lg focus:ring-primary focus:border-primary block w-full pl-10 p-2.5 shadow-sm transition-colors" placeholder="Cari nama pelanggan atau email...">
            </form>
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <x-organisms.stat-card title="👥 Total Pengguna" value="{{ $totalPengguna }}" />
            <x-organisms.stat-card title="🆕 Pengguna Baru" value="{{ $penggunaBaru }}" />
            <x-organisms.stat-card title="✅ Akun Aktif" value="{{ $akunAktif }}" />
        </div>

        <!-- Table Pengguna Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="p-6 border-b border-gray-100">
                <h3 class="text-lg font-bold text-brand-text font-poppins">Daftar Pengguna</h3>
                <p class="text-sm text-gray-500 mt-1">Seluruh pelanggan yang telah memiliki akun Hero Barbershop.</p>
            </div>
            
            <!-- Table Container -->
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-500 whitespace-nowrap">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 border-b border-gray-200 sticky top-0">
                        <tr>
                            <th scope="col" class="px-6 py-4 font-semibold">No</th>
                            <th scope="col" class="px-6 py-4 font-semibold">Nama Lengkap</th>
                            <th scope="col" class="px-6 py-4 font-semibold">Email</th>
                            <th scope="col" class="px-6 py-4 font-semibold">Nomor Telepon</th>
                            <th scope="col" class="px-6 py-4 font-semibold">Tanggal Registrasi</th>
                            <th scope="col" class="px-6 py-4 font-semibold text-center">Status Akun</th>
                            <th scope="col" class="px-6 py-4 font-semibold text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        
                        @forelse($users as $user)
                            <tr class="hover:bg-gray-50 transition-colors even:bg-gray-50/30">
                                <td class="px-6 py-4 font-medium text-gray-900">{{ $user['no'] }}</td>
                                <td class="px-6 py-4 font-medium text-gray-900">{{ $user['name'] }}</td>
                                <td class="px-6 py-4">{{ $user['email'] }}</td>
                                <td class="px-6 py-4">{{ $user['phone'] }}</td>
                                <td class="px-6 py-4 font-medium text-gray-700">{{ $user['date'] }}</td>
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex items-center px-2.5 py-1 text-xs font-medium border rounded-full {{ $user['status'] === 'Aktif' ? 'bg-green-100 text-green-700 border-green-200' : 'bg-red-100 text-red-700 border-red-200' }}">
                                        {{ $user['status'] }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <button @click="selectedUser = {{ json_encode($user) }}; showModal = true" class="inline-flex items-center justify-center gap-1.5 px-3 py-1.5 bg-[#1F2A1D] text-white text-xs font-medium rounded-lg hover:bg-[#2c3d29] transition-colors focus:outline-none focus:ring-2 focus:ring-[#1F2A1D] focus:ring-offset-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                        Detail
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mb-4 border border-gray-100 shadow-sm">
                                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                            </svg>
                                        </div>
                                        <h3 class="text-lg font-bold text-gray-900 mb-1">Belum Ada Pengguna</h3>
                                        <p class="text-gray-500">Belum terdapat pelanggan yang terdaftar pada Hero Barbershop.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="p-4 border-t border-gray-100 flex items-center justify-between">
                <span class="text-sm text-gray-500">Menampilkan <span class="font-medium text-gray-900">{{ $pelanggans->firstItem() ?? 0 }}</span> sampai <span class="font-medium text-gray-900">{{ $pelanggans->lastItem() ?? 0 }}</span> dari <span class="font-medium text-gray-900">{{ $pelanggans->total() }}</span> pengguna</span>
                <div class="flex items-center gap-2">
                    @if ($pelanggans->onFirstPage())
                        <button class="px-3 py-1.5 border border-gray-200 rounded-lg text-sm font-medium text-gray-500 hover:bg-gray-50 disabled:opacity-50" disabled>Sebelumnya</button>
                    @else
                        <a href="{{ $pelanggans->previousPageUrl() }}" class="px-3 py-1.5 border border-gray-200 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50">Sebelumnya</a>
                    @endif

                    @if ($pelanggans->hasMorePages())
                        <a href="{{ $pelanggans->nextPageUrl() }}" class="px-3 py-1.5 border border-gray-200 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50">Selanjutnya</a>
                    @else
                        <button class="px-3 py-1.5 border border-gray-200 rounded-lg text-sm font-medium text-gray-500 hover:bg-gray-50 disabled:opacity-50" disabled>Selanjutnya</button>
                    @endif
                </div>
            </div>
        </div>

        <!-- Modal Detail Pengguna -->
        <div x-show="showModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
                
                <!-- Background overlay -->
                <div x-show="showModal" 
                     x-transition:enter="ease-out duration-300" 
                     x-transition:enter-start="opacity-0" 
                     x-transition:enter-end="opacity-100" 
                     x-transition:leave="ease-in duration-200" 
                     x-transition:leave-start="opacity-100" 
                     x-transition:leave-end="opacity-0" 
                     class="fixed inset-0 transition-opacity bg-gray-900 bg-opacity-50" 
                     @click="showModal = false"></div>

                <!-- Modal Panel -->
                <div x-show="showModal" 
                     x-transition:enter="ease-out duration-300" 
                     x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                     x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" 
                     x-transition:leave="ease-in duration-200" 
                     x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" 
                     x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                     class="inline-block w-full max-w-lg p-6 my-8 overflow-hidden text-left align-middle transition-all transform bg-white shadow-xl rounded-xl sm:align-middle relative z-10">
                    
                    <div class="flex items-start justify-between mb-5">
                        <h3 class="text-xl font-bold text-brand-text font-poppins">Detail Pengguna</h3>
                        <button @click="showModal = false" class="text-gray-400 hover:text-gray-500 transition-colors focus:outline-none">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>

                    <div class="flex items-center gap-4 mb-6 pb-6 border-b border-gray-100">
                        <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center border border-gray-200 shadow-sm flex-shrink-0">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <div>
                            <h4 class="text-lg font-bold text-gray-900" x-text="selectedUser.name">Nama Lengkap</h4>
                            <p class="text-sm text-gray-500" x-text="selectedUser.email">email@example.com</p>
                        </div>
                    </div>

                    <div class="space-y-4 mb-6">
                        <div class="grid grid-cols-3 gap-4">
                            <div class="col-span-1 text-sm font-medium text-gray-500">Nomor Telepon</div>
                            <div class="col-span-2 text-sm text-gray-900 font-medium" x-text="selectedUser.phone">-</div>
                        </div>
                        <div class="grid grid-cols-3 gap-4">
                            <div class="col-span-1 text-sm font-medium text-gray-500">Alamat</div>
                            <div class="col-span-2 text-sm text-gray-900 font-medium leading-relaxed" x-text="selectedUser.address">-</div>
                        </div>
                        <div class="grid grid-cols-3 gap-4 items-center">
                            <div class="col-span-1 text-sm font-medium text-gray-500">Tanggal Registrasi</div>
                            <div class="col-span-2 text-sm text-gray-900 font-medium" x-text="selectedUser.date">-</div>
                        </div>
                        <div class="grid grid-cols-3 gap-4 items-center">
                            <div class="col-span-1 text-sm font-medium text-gray-500">Status Akun</div>
                            <div class="col-span-2">
                                <span class="inline-flex items-center px-2.5 py-1 text-xs font-medium border rounded-full" 
                                      :class="selectedUser.status === 'Aktif' ? 'bg-green-100 text-green-700 border-green-200' : 'bg-red-100 text-red-700 border-red-200'">
                                    <span x-text="selectedUser.status === 'Aktif' ? '🟢 Aktif' : '🔴 Tidak Aktif'"></span>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 rounded-xl p-5 mb-6 border border-gray-100">
                        <h4 class="text-sm font-bold text-gray-900 mb-3 uppercase tracking-wide">Ringkasan Aktivitas</h4>
                        <div class="space-y-3">
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-500">Total Booking</span>
                                <span class="text-sm font-bold text-brand-text" x-text="selectedUser.total_booking + ' Booking'">-</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-500">Booking Terakhir</span>
                                <span class="text-sm font-medium text-gray-900" x-text="selectedUser.last_booking">-</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-500">Status Booking Terakhir</span>
                                <span class="text-sm font-medium text-gray-900" x-text="selectedUser.last_status">-</span>
                            </div>
                        </div>
                    </div>

                    <!-- Detail Booking Terakhir -->
                    <div class="bg-gray-50 rounded-xl p-5 mb-6 border border-gray-100">
                        <h4 class="text-sm font-bold text-gray-900 mb-3 uppercase tracking-wide">Detail Booking Terakhir</h4>
                        
                        <template x-if="selectedUser.total_booking > 0">
                            <div class="space-y-3">
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-gray-500">Nomor Booking</span>
                                    <span class="text-sm font-medium text-gray-900" x-text="selectedUser.last_booking_code || 'BK-20260714-001'">-</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-gray-500">Tanggal Booking</span>
                                    <span class="text-sm font-medium text-gray-900" x-text="selectedUser.last_booking">-</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-gray-500">Jam Booking</span>
                                    <span class="text-sm font-medium text-gray-900" x-text="selectedUser.last_time || '09.00 - 09.30'">-</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-gray-500">Layanan</span>
                                    <span class="text-sm font-medium text-gray-900" x-text="selectedUser.last_service || 'Potong Rambut'">-</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-gray-500">Status Booking</span>
                                    <span class="text-sm font-medium text-gray-900" x-text="selectedUser.last_status">-</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-gray-500">Status Slot</span>
                                    <span class="text-sm font-medium text-gray-900" x-text="selectedUser.last_slot_status || 'Aktif'">-</span>
                                </div>
                            </div>
                        </template>

                        <template x-if="selectedUser.total_booking === 0">
                            <div class="flex flex-col items-center justify-center py-4">
                                <svg class="w-8 h-8 text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <p class="text-sm text-gray-500 text-center">Belum memiliki riwayat booking.</p>
                            </div>
                        </template>
                    </div>

                    <div class="flex justify-end pt-5 border-t border-gray-100">
                        <button @click="showModal = false" type="button" class="inline-flex justify-center w-full sm:w-auto px-6 py-2.5 bg-[#1F2A1D] text-white text-sm font-medium rounded-lg hover:bg-[#2c3d29] focus:outline-none focus:ring-2 focus:ring-[#1F2A1D] focus:ring-offset-2 transition-colors shadow-sm">
                            Tutup
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-templates.admin-layout>
