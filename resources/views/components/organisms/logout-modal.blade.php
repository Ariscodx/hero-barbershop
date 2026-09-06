<div x-data="{ show: false }" 
     @open-logout-modal.window="show = true"
     @keydown.escape.window="show = false"
     x-cloak>
    
    <!-- Backdrop -->
    <div x-show="show" style="display: none;"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-[100] bg-gray-900/60 backdrop-blur-sm"
         aria-hidden="true"></div>

    <!-- Modal Panel -->
    <div x-show="show" style="display: none;"
         class="fixed inset-0 z-[100] overflow-y-auto overflow-x-hidden flex items-center justify-center p-4 sm:p-0">
        
        <div x-show="show" style="display: none;"
             @click.away="show = false"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             class="relative bg-white rounded-2xl shadow-xl border border-gray-100 w-full max-w-sm p-6 overflow-hidden">
            
            <!-- Icon -->
            <div class="mx-auto flex items-center justify-center h-14 w-14 rounded-full bg-red-50 mb-5">
                <svg class="h-8 w-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                </svg>
            </div>
            
            <!-- Content -->
            <div class="text-center mb-6">
                <h3 class="text-xl font-bold text-gray-900 font-poppins mb-2">Konfirmasi Keluar</h3>
                <p class="text-sm text-gray-500 leading-relaxed">
                    Apakah Anda yakin ingin keluar dari sesi ini? Anda harus login kembali untuk mengakses sistem.
                </p>
            </div>
            
            <!-- Actions -->
            <div class="flex flex-col sm:flex-row gap-3">
                <button type="button" @click="show = false" class="w-full sm:w-1/2 px-4 py-2.5 bg-white border border-gray-300 text-gray-700 font-medium rounded-xl hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-200 transition-colors">
                    Batal
                </button>
                <form method="POST" action="{{ route('logout') }}" class="w-full sm:w-1/2">
                    @csrf
                    <button type="submit" class="w-full px-4 py-2.5 bg-red-600 text-white font-medium rounded-xl hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 transition-colors shadow-sm shadow-red-200">
                        Ya, Keluar
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
