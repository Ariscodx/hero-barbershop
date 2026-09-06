<x-templates.admin-layout>
    <div x-data="kuotaManager()" x-init="init()" class="space-y-6 pb-10">
        
        <!-- Header Section -->
        <div>
            <h2 class="text-2xl font-bold text-brand-text font-poppins">Manajemen Slot Booking</h2>
            <p class="text-sm text-gray-500 mt-1">Buat dan kelola slot waktu booking untuk pelanggan (durasi 30 menit per slot).</p>
        </div>

        <!-- Alert -->
        <div x-show="jadwalTutup" style="display: none;" class="bg-red-50 border-l-4 border-red-500 p-4 rounded-r-xl transition-all">
            <div class="flex items-center gap-3">
                <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
                <p class="text-red-700 font-medium text-sm">Barbershop tutup pada tanggal tersebut (berdasarkan Jadwal Operasional).</p>
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
            /* Menghilangkan calendar picker icon default browser */
            input[type="date"]::-webkit-calendar-picker-indicator {
                opacity: 0;
                width: 100%;
                height: 100%;
                position: absolute;
                top: 0;
                left: 0;
                cursor: pointer;
            }
        </style>

        <!-- Pilih Hari Card -->
        <div class="bg-brand-card rounded-xl shadow-sm border border-brand-border p-6">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <h3 class="text-lg font-bold text-brand-text font-poppins">Pilih Hari (30 Hari Ke Depan)</h3>
                </div>
                <div class="relative w-full sm:w-auto">
                    <input type="date" x-model="selectedDate" @change="handleDateInput()" class="relative z-10 w-full rounded-lg border-gray-200 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50 text-sm text-gray-700 bg-transparent px-3 py-1.5 cursor-pointer">
                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                </div>
            </div>

            <div class="relative flex items-center">
                <!-- Left Navigation Button -->
                <button type="button" @click="$refs.calendarScroll.scrollBy({left: -150, behavior: 'smooth'})" class="hidden md:flex absolute -left-4 z-10 w-10 h-10 items-center justify-center bg-white border border-gray-200 rounded-full shadow hover:bg-gray-50 transition-colors focus:outline-none">
                    <svg class="w-5 h-5 text-gray-600 -ml-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                </button>

                <!-- Calendar Container (Scrollable) -->
                <div x-ref="calendarScroll" class="flex-1 overflow-x-auto hide-scrollbar scroll-smooth px-8 py-4 -my-4 -mx-8">
                    <div class="flex items-stretch gap-3 min-w-max px-2">
                        <template x-for="(day, index) in calendarDays" :key="index">
                            <button type="button" @click="selectDate(day.dateFull)" 
                                :class="day.active ? 'bg-primary border-primary text-white shadow-md' : 'bg-white border-gray-200 text-gray-700 hover:border-primary hover:shadow-sm'"
                                class="w-28 sm:w-32 flex-shrink-0 relative group flex flex-col items-center justify-center p-4 rounded-xl border transition-all duration-300">
                                
                                <template x-if="day.today">
                                    <span class="absolute -top-3 bg-green-500 text-white text-[10px] font-bold px-2.5 py-0.5 rounded-full shadow-sm">
                                        Hari Ini
                                    </span>
                                </template>

                                <span :class="day.active ? 'text-gray-200' : 'text-gray-400 group-hover:text-primary'" class="text-xs font-medium uppercase tracking-wider" x-text="day.day"></span>
                                <span class="text-3xl font-bold font-poppins my-1" x-text="day.date"></span>
                                <span :class="day.active ? 'text-gray-300' : 'text-gray-400'" class="text-xs font-medium uppercase tracking-wider" x-text="day.month"></span>
                            </button>
                        </template>
                    </div>
                </div>

                <!-- Right Navigation Button -->
                <button type="button" @click="$refs.calendarScroll.scrollBy({left: 150, behavior: 'smooth'})" class="hidden md:flex absolute -right-4 z-10 w-10 h-10 items-center justify-center bg-white border border-gray-200 rounded-full shadow hover:bg-gray-50 transition-colors focus:outline-none">
                    <svg class="w-5 h-5 text-gray-600 ml-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </button>
            </div>
            
            <!-- Information Card -->
            <div class="mt-6 bg-yellow-50 border border-yellow-200 rounded-xl p-4 flex items-start gap-3 shadow-sm">
                <div class="mt-0.5 text-yellow-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <div>
                    <p class="text-sm text-yellow-800 font-medium">Slot Booking akan dibuat berdasarkan Jadwal Operasional pada tanggal yang dipilih.</p>
                </div>
            </div>
        </div>

        <!-- Generate Banner Card -->
        <div x-show="!jadwalTutup" class="bg-accent rounded-xl shadow-md p-6 flex flex-col md:flex-row items-center justify-between gap-6 relative overflow-hidden mb-8">
            <!-- Background Decoration -->
            <div class="absolute right-0 top-0 w-64 h-64 bg-white opacity-5 rounded-full -translate-y-1/2 translate-x-1/3 pointer-events-none"></div>
            
            <div class="flex items-start gap-4 relative z-10">
                <div class="mt-1">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-white font-poppins">Generate Slot Otomatis</h3>
                    <p class="text-white/80 text-sm mt-1">Slot Booking akan dibuat otomatis berdasarkan jam operasional dan durasi layanan.</p>
                </div>
            </div>
            
            <button type="button" @click="generateSlot()" :disabled="isLoading" class="relative z-10 w-full md:w-auto flex-shrink-0 inline-flex items-center justify-center gap-2 px-6 py-3 bg-white text-accent text-sm font-bold rounded-xl hover:bg-gray-50 transition-all duration-300 hover:scale-105 shadow-sm focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-accent disabled:opacity-75 disabled:hover:scale-100 disabled:cursor-not-allowed">
                <svg x-show="!isLoading" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
                <svg x-show="isLoading" style="display: none;" class="animate-spin -ml-1 mr-3 h-5 w-5 text-accent" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span x-text="isLoading ? 'Memproses...' : 'Generate Slot'"></span>
            </button>
        </div>

        <!-- Daftar Slot Booking (Container) -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="p-6 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h3 class="text-lg font-bold text-[#1F2A1D] font-poppins">Daftar Slot Booking</h3>
                    <p class="text-sm text-gray-500 mt-1">Slot booking hasil generate untuk tanggal <span x-text="formatDateIndo(selectedDate)"></span>.</p>
                </div>
                <div class="flex items-center gap-2 text-sm text-gray-500 font-medium">
                    <span>Total:</span>
                    <span class="px-2.5 py-1 bg-gray-100 rounded-lg text-gray-700 font-bold"><span x-text="slots.length"></span> Slot</span>
                </div>
            </div>
            
            <div class="p-6 bg-[#F5F5F5]">
                
                <!-- Information Card -->
                <div class="mb-6 bg-yellow-50 border border-yellow-200 rounded-xl p-4 flex items-start gap-3 shadow-sm">
                    <div class="mt-0.5 text-yellow-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <div>
                        <p class="text-sm text-yellow-800 font-medium">Slot yang berstatus Aktif akan ditampilkan kepada pelanggan pada halaman Booking. Slot yang dinonaktifkan tidak dapat dipilih oleh pelanggan.</p>
                    </div>
                </div>

                <div x-show="slots.length === 0 && !isLoading" style="display: none;" class="flex flex-col items-center justify-center py-12 text-center bg-white rounded-xl border border-gray-200 border-dashed">
                    <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mb-4 border border-gray-100 shadow-sm">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-1">Belum Ada Slot</h3>
                    <p class="text-gray-500">Silakan klik Generate Slot untuk membuat daftar kuota hari ini.</p>
                </div>

                <div x-show="isLoading" style="display: none;" class="flex flex-col items-center justify-center py-12 text-center">
                    <svg class="animate-spin h-8 w-8 text-primary" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <p class="text-gray-500 mt-4 font-medium text-sm">Memuat data slot...</p>
                </div>

                <!-- Grid Slot -->
                <div x-show="slots.length > 0 && !isLoading" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <template x-for="slot in slots" :key="slot.id_kuota">
                        <!-- Slot Card -->
                        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 hover:-translate-y-1 hover:shadow-lg transition-all duration-300 flex flex-col h-full relative">
                            
                            <!-- Tombol Reset (Hanya muncul jika tersedia) -->
                            <template x-if="slot.status_booking === 'tersedia'">
                                <button type="button" @click="resetSlot(slot)" title="Reset Slot" class="absolute top-4 right-4 p-1.5 text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-red-200">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                    </svg>
                                </button>
                            </template>

                            <!-- Top Section: Time and Badge -->
                            <div class="mb-5 border-b border-gray-100 pb-5">
                                <div class="flex items-center gap-2 mb-3">
                                    <svg class="w-5 h-5 text-[#C8A96A]" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span class="font-bold text-[#1F2A1D] text-lg" x-text="slot.jam + ' - ' + slot.jam_selesai"></span>
                                </div>
                                
                                <!-- Status Badge -->
                                <div>
                                    <template x-if="slot.status_booking === 'tersedia'">
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-green-50 text-green-700 text-xs font-semibold rounded-full border border-green-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> Tersedia
                                        </span>
                                    </template>
                                    <template x-if="slot.status_booking === 'terbooking'">
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-blue-50 text-blue-700 text-xs font-semibold rounded-full border border-blue-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span> Terbooking
                                        </span>
                                    </template>
                                    <template x-if="slot.status_booking === 'selesai'">
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-gray-100 text-gray-700 text-xs font-semibold rounded-full border border-gray-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-gray-500"></span> Selesai
                                        </span>
                                    </template>
                                </div>
                            </div>

                            <!-- Toggle Switch Section -->
                            <div class="mt-auto">
                                <div class="text-[11px] text-gray-400 font-bold mb-2.5 uppercase tracking-wider">Status Slot</div>
                                <div class="flex items-start justify-between">
                                    <div class="flex flex-col pr-2">
                                        <span class="text-sm font-semibold transition-colors duration-300" :class="slot.status_kuota === 'aktif' ? 'text-green-600' : 'text-gray-500'" x-text="slot.status_kuota === 'aktif' ? 'Aktif' : 'Nonaktif'"></span>
                                        <span class="text-xs mt-0.5 transition-colors duration-300" :class="slot.status_kuota === 'aktif' ? 'text-green-600/80' : 'text-gray-400'" x-text="slot.status_kuota === 'aktif' ? 'Ditampilkan kepada pelanggan' : 'Tidak ditampilkan kepada pelanggan'"></span>
                                    </div>
                                    
                                    <button type="button" 
                                            @click="toggleSlot(slot)"
                                            :disabled="jadwalTutup"
                                            :class="[slot.status_kuota === 'aktif' ? 'bg-green-500' : 'bg-gray-300', jadwalTutup ? 'opacity-50 cursor-not-allowed' : '']"
                                            class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-300 ease-in-out focus:outline-none focus:ring-2 focus:ring-[#C8A96A] focus:ring-offset-2 mt-0.5 disabled:opacity-50 disabled:cursor-not-allowed">
                                        <span aria-hidden="true" 
                                              :class="slot.status_kuota === 'aktif' ? 'translate-x-5' : 'translate-x-0'"
                                              class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-300 ease-in-out"></span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>

    </div>

    <!-- Notification Toast Element (Optional for success messages) -->
    <div id="toast-container" class="fixed bottom-5 right-5 z-50 flex flex-col gap-3"></div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('kuotaManager', () => ({
                selectedDate: '{{ \Carbon\Carbon::now()->format('Y-m-d') }}',
                calendarDays: [],
                slots: [],
                isLoading: false,
                jadwalTutup: false,

                init() {
                    this.generateCalendar();
                    this.fetchSlots();
                    
                    this.$watch('selectedDate', (val) => {
                        this.generateCalendar();
                        this.fetchSlots();
                    });
                },

                handleDateInput() {
                    if (this.selectedDate) {
                        this.generateCalendar();
                        this.fetchSlots();
                    }
                },

                generateCalendar() {
                    const today = new Date();
                    today.setHours(0,0,0,0);
                    
                    const days = [];
                    
                    for(let i = 0; i < 30; i++) {
                        const d = new Date(today);
                        d.setDate(today.getDate() + i);
                        
                        const dayName = d.toLocaleDateString('id-ID', { weekday: 'long' });
                        const dateNum = d.getDate().toString().padStart(2, '0');
                        const monthName = d.toLocaleDateString('id-ID', { month: 'short' });
                        
                        // Compare ignoring time
                        const loopDateStr = d.toLocaleDateString('en-CA');
                        const todayStr = today.toLocaleDateString('en-CA');
                        
                        days.push({
                            dateFull: loopDateStr,
                            day: dayName,
                            date: dateNum,
                            month: monthName,
                            today: loopDateStr === todayStr,
                            active: loopDateStr === this.selectedDate
                        });
                    }
                    this.calendarDays = days;
                },

                selectDate(dateStr) {
                    this.selectedDate = dateStr;
                    // fetchSlots automatically triggered by $watch
                },

                formatDateIndo(dateStr) {
                    if(!dateStr) return '';
                    const d = new Date(dateStr);
                    return d.toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' });
                },

                showToast(message, type = 'success') {
                    const container = document.getElementById('toast-container');
                    const toast = document.createElement('div');
                    
                    const bgClass = type === 'success' ? 'bg-green-500' : 'bg-red-500';
                    const icon = type === 'success' 
                        ? '<svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>'
                        : '<svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>';

                    toast.className = `flex items-center gap-3 px-4 py-3 text-white rounded-xl shadow-lg transform transition-all duration-300 translate-x-full opacity-0 ${bgClass}`;
                    toast.innerHTML = `
                        ${icon}
                        <p class="font-medium text-sm">${message}</p>
                    `;
                    
                    container.appendChild(toast);
                    
                    setTimeout(() => {
                        toast.classList.remove('translate-x-full', 'opacity-0');
                    }, 10);

                    setTimeout(() => {
                        toast.classList.add('translate-x-full', 'opacity-0');
                        setTimeout(() => toast.remove(), 300);
                    }, 3000);
                },

                async fetchSlots() {
                    this.isLoading = true;
                    this.jadwalTutup = false;
                    try {
                        const response = await fetch(`/admin/kuota/${this.selectedDate}`);
                        const res = await response.json();
                        if(res.success) {
                            this.slots = res.data;
                            if (res.jadwal_tutup) {
                                this.jadwalTutup = true;
                            }
                        } else {
                            this.slots = [];
                        }
                    } catch(e) {
                        console.error(e);
                        this.slots = [];
                    } finally {
                        this.isLoading = false;
                    }
                },

                async generateSlot() {
                    this.isLoading = true;
                    try {
                        const response = await fetch(`/admin/kuota/generate`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({ tanggal: this.selectedDate })
                        });
                        const res = await response.json();
                        
                        if(!res.success) {
                            if(res.message.includes('tutup')) {
                                this.jadwalTutup = true;
                            }
                            this.showToast(res.message, 'error');
                        } else {
                            this.showToast('Slot booking berhasil di-generate!', 'success');
                            this.fetchSlots();
                        }
                    } catch(e) {
                        console.error(e);
                        this.showToast('Terjadi kesalahan koneksi', 'error');
                    } finally {
                        this.isLoading = false;
                    }
                },

                async toggleSlot(slot) {
                    if (this.jadwalTutup) {
                        this.showToast('Jadwal operasional tutup pada hari tersebut.', 'error');
                        return;
                    }
                    try {
                        const response = await fetch(`/admin/kuota/${slot.id_kuota}/toggle`, {
                            method: 'PATCH',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                'Accept': 'application/json'
                            }
                        });
                        const res = await response.json();
                        if(res.success) {
                            slot.status_kuota = res.status_kuota;
                            this.showToast('Status slot diperbarui', 'success');
                        } else {
                            this.showToast(res.message || 'Gagal mengubah status', 'error');
                        }
                    } catch(e) {
                        console.error(e);
                        this.showToast('Gagal mengubah status', 'error');
                    }
                },

                async resetSlot(slot) {
                    if(slot.status_booking !== 'tersedia') {
                        this.showToast('Slot tidak dapat direset karena sudah terbooking.', 'error');
                        return;
                    }
                    
                    if(!confirm('Apakah Anda yakin ingin mereset slot ini menjadi Aktif?')) return;
                    
                    try {
                        const response = await fetch(`/admin/kuota/${slot.id_kuota}/reset`, {
                            method: 'PATCH',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                'Accept': 'application/json'
                            }
                        });
                        const res = await response.json();
                        if(res.success) {
                            slot.status_kuota = res.status_kuota;
                            this.showToast('Slot berhasil direset!', 'success');
                        } else {
                            this.showToast(res.message, 'error');
                        }
                    } catch(e) {
                        console.error(e);
                        this.showToast('Gagal mereset slot', 'error');
                    }
                }
            }));
        });
    </script>
</x-templates.admin-layout>
