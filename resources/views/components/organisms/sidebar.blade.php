<!-- Sidebar Backdrop (Mobile) -->
<div x-show="sidebarOpen" class="fixed inset-0 z-40 bg-gray-900 bg-opacity-50 transition-opacity lg:hidden" 
     @click="sidebarOpen = false" x-transition.opacity></div>

<!-- Sidebar -->
<aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" 
       class="fixed inset-y-0 left-0 z-50 w-64 bg-primary flex flex-col transition-transform duration-300 ease-in-out lg:static lg:translate-x-0 shadow-lg shadow-black/10">
    
    <!-- Logo Area -->
    <div class="flex items-center justify-center h-20 border-b border-gray-700/50">
        <div class="flex items-center gap-2 text-2xl font-bold tracking-wider">
            <!-- Logo Image -->
            <img src="{{ asset('images/logo_hero_barbershop.png') }}" class="w-10 h-10 object-contain" alt="Logo">
            <span class="font-poppins text-white">HERO <span class="text-accent">BARBERSHOP</span></span>
        </div>
    </div>

    <!-- Navigation Area -->
    <div class="flex-1 overflow-y-auto py-6 px-4">
        <p class="px-4 text-xs font-semibold text-gray-400/80 uppercase tracking-wider mb-4">Navigasi Utama</p>
        
        <nav class="space-y-1.5">
            <!-- Dashboard -->
            <a href="{{ route('admin.dashboard') }}" class="group flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-lg transition-all duration-200 active:scale-[0.98] {{ request()->routeIs('admin.dashboard') ? 'bg-accent text-white shadow-sm shadow-accent/20' : 'text-gray-300 hover:bg-white/5 hover:text-white' }}">
                <svg class="w-5 h-5 {{ request()->routeIs('admin.dashboard') ? 'text-white' : 'text-gray-400 group-hover:text-gray-300' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                Dashboard
            </a>

            <!-- Jadwal Operasional -->
            <a href="{{ route('admin.jadwal') }}" class="group flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-lg transition-all duration-200 active:scale-[0.98] {{ request()->routeIs('admin.jadwal') ? 'bg-accent text-white shadow-sm shadow-accent/20' : 'text-gray-300 hover:bg-white/5 hover:text-white' }}">
                <svg class="w-5 h-5 {{ request()->routeIs('admin.jadwal') ? 'text-white' : 'text-gray-400 group-hover:text-gray-300' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                Jadwal Operasional
            </a>

            <!-- Kuota Booking -->
            <a href="{{ route('admin.kuota') }}" class="group flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-lg transition-all duration-200 active:scale-[0.98] {{ request()->routeIs('admin.kuota') ? 'bg-accent text-white shadow-sm shadow-accent/20' : 'text-gray-300 hover:bg-white/5 hover:text-white' }}">
                <svg class="w-5 h-5 {{ request()->routeIs('admin.kuota') ? 'text-white' : 'text-gray-400 group-hover:text-gray-300' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path></svg>
                Kuota Booking
            </a>

            <!-- Daftar Booking -->
            <a href="{{ route('admin.booking') }}" class="group flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-lg transition-all duration-200 active:scale-[0.98] {{ request()->routeIs('admin.booking') ? 'bg-accent text-white shadow-sm shadow-accent/20' : 'text-gray-300 hover:bg-white/5 hover:text-white' }}">
                <svg class="w-5 h-5 {{ request()->routeIs('admin.booking') ? 'text-white' : 'text-gray-400 group-hover:text-gray-300' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                Daftar Booking
            </a>

            <!-- Daftar Pengguna -->
            <a href="{{ route('admin.pengguna') }}" class="group flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-lg transition-all duration-200 active:scale-[0.98] {{ request()->routeIs('admin.pengguna') ? 'bg-accent text-white shadow-sm shadow-accent/20' : 'text-gray-300 hover:bg-white/5 hover:text-white' }}">
                <svg class="w-5 h-5 {{ request()->routeIs('admin.pengguna') ? 'text-white' : 'text-gray-400 group-hover:text-gray-300' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                Daftar Pengguna
            </a>

            <!-- Profil Admin -->
            <a href="{{ route('admin.profil') }}" class="group flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-lg transition-all duration-200 active:scale-[0.98] {{ request()->routeIs('admin.profil') ? 'bg-accent text-white shadow-sm shadow-accent/20' : 'text-gray-300 hover:bg-white/5 hover:text-white' }}">
                <svg class="w-5 h-5 {{ request()->routeIs('admin.profil') ? 'text-white' : 'text-gray-400 group-hover:text-gray-300' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                Profil Admin
            </a>
        </nav>
    </div>

    <!-- Logout Area -->
    <div class="p-4 border-t border-gray-700/50">
        <button type="button" @click="$dispatch('open-logout-modal')" class="flex items-center w-full gap-3 px-4 py-3 text-sm font-medium text-red-400 rounded-lg hover:bg-red-500/10 hover:text-red-300 active:scale-[0.98] transition-all duration-200">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
            Keluar
        </button>
    </div>
</aside>
