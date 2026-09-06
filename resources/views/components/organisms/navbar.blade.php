<header class="sticky top-0 z-30 flex items-center justify-between px-4 py-4 bg-brand-card shadow-sm border-b border-brand-border sm:px-6 lg:px-8">
    <div class="flex items-center gap-4">
        <!-- Hamburger Menu Button -->
        <button @click="sidebarOpen = !sidebarOpen" class="text-gray-500 hover:text-gray-600 lg:hidden focus:outline-none focus:ring-2 focus:ring-inset focus:ring-accent rounded-md p-1 hover:bg-gray-100 active:scale-95 transition-all">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
            </svg>
        </button>
        
        <h1 class="text-xl font-semibold text-gray-900 font-poppins hidden sm:block">Dashboard</h1>
    </div>

    <div class="flex items-center gap-4">
        <!-- Profile Dropdown -->
        <div class="relative flex items-center gap-3" x-data="{ profileOpen: false }">
            <div class="hidden text-right sm:block">
                <p class="text-sm font-medium text-gray-900">{{ Auth::user()->nama ?? 'Admin Dummy' }}</p>
                <p class="text-xs text-gray-500">Administrator</p>
            </div>
            
            <button @click="profileOpen = !profileOpen" @click.away="profileOpen = false" class="flex items-center justify-center w-10 h-10 overflow-hidden bg-gray-100 rounded-full focus:outline-none focus:ring-2 focus:ring-accent border border-gray-200 hover:border-gray-300 transition">
                <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                </svg>
            </button>

            <!-- Dropdown Icon -->
            <svg class="w-4 h-4 text-gray-400 hidden sm:block transition-transform duration-200" :class="profileOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
            </svg>

            <!-- Dropdown Menu -->
            <div x-show="profileOpen" 
                 x-transition:enter="transition ease-out duration-100" 
                 x-transition:enter-start="transform opacity-0 scale-95" 
                 x-transition:enter-end="transform opacity-100 scale-100" 
                 x-transition:leave="transition ease-in duration-75" 
                 x-transition:leave-start="transform opacity-100 scale-100" 
                 x-transition:leave-end="transform opacity-0 scale-95" 
                 class="absolute right-0 top-12 mt-2 w-48 bg-white rounded-xl shadow-lg border border-gray-100 py-2 z-50" style="display: none;">
                
                <a href="{{ route('admin.profil') }}" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-primary transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    Profil Saya
                </a>
                
                <hr class="my-1 border-gray-100">
                
                <button type="button" @click="$dispatch('open-logout-modal')" class="flex items-center w-full gap-2 px-4 py-2 text-sm text-red-600 hover:bg-red-50 hover:text-red-700 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    Keluar
                </button>
            </div>
        </div>
    </div>
</header>
