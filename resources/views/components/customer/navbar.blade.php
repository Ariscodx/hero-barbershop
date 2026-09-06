{{--
    Customer Navbar Component
    Digunakan oleh: customer/layouts/app.blade.php
    Active state: request()->routeIs()
    Tidak ada animate-*, transition-all, atau badge-pulse di navbar
--}}

<nav class="customer-nav bg-white border-b border-[#E5E7EB]" x-data="{ open: false }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between gap-4" style="height: 80px;">

            {{-- ===== LEFT: Logo ===== --}}
            <a href="{{ route('customer.dashboard') }}"
               class="flex items-center gap-3 flex-shrink-0">
                <div class="w-[60px] h-[60px] rounded-full bg-black flex items-center justify-center border-2 border-[#D4B06A]/30 overflow-hidden shadow-sm">
                    <img src="{{ asset('images/logo_hero_barbershop.png') }}"
                         alt="Hero Barbershop"
                         class="object-contain w-[50px] h-[50px]"
                         style="display: block;">
                </div>
                <div class="hidden sm:flex flex-col leading-none select-none">
                    <span class="text-base font-bold tracking-widest text-[#1F2A1D]">HERO</span>
                    <span class="text-xs font-semibold tracking-[0.2em] text-[#D4B06A] uppercase">Barbershop</span>
                </div>
            </a>

            {{-- ===== CENTER: Desktop Menu ===== --}}
            <div class="hidden lg:flex items-center gap-0.5 flex-1 justify-center">

                {{-- Dashboard --}}
                <a href="{{ route('customer.dashboard') }}"
                   class="cust-nav-link {{ request()->routeIs('customer.dashboard') ? 'cust-nav-active' : '' }}">
                    Dashboard
                </a>

                {{-- Jadwal Operasional --}}
                <a href="{{ route('customer.jadwal') }}"
                   class="cust-nav-link {{ request()->routeIs('customer.jadwal') ? 'cust-nav-active' : '' }}">
                    Jadwal Operasional
                </a>

                {{-- Buat Booking --}}
                <a href="{{ route('customer.booking') }}"
                   class="cust-nav-link {{ request()->routeIs('customer.booking') ? 'cust-nav-active' : '' }}">
                    Buat Booking
                </a>

                {{-- Status Pesanan --}}
                <a href="{{ route('customer.status') }}"
                   class="cust-nav-link {{ request()->routeIs('customer.status') ? 'cust-nav-active' : '' }}">
                    Status Pesanan
                </a>

                {{-- Riwayat --}}
                <a href="{{ route('customer.riwayat') }}"
                   class="cust-nav-link {{ request()->routeIs('customer.riwayat') ? 'cust-nav-active' : '' }}">
                    Riwayat
                </a>

            </div>

            {{-- ===== RIGHT: Profile Dropdown + Hamburger ===== --}}
            <div class="flex items-center gap-2 flex-shrink-0">

                {{-- Profile Dropdown — Desktop only --}}
                <div class="hidden lg:block relative" x-data="{ dropOpen: false }">
                    <button @click="dropOpen = !dropOpen"
                            @click.away="dropOpen = false"
                            class="flex items-center gap-2.5 px-3 py-2 rounded-xl transition-colors duration-200 ease-in-out hover:bg-gray-50 focus:outline-none"
                            :aria-expanded="dropOpen">
                        {{-- Avatar --}}
                        <div class="cust-avatar" aria-hidden="true">
                            {{ strtoupper(substr(Auth::guard('pelanggan')->user()->nama ?? 'P', 0, 1)) }}
                        </div>
                        {{-- Name --}}
                        <div class="text-left hidden xl:block">
                            <p class="text-sm font-semibold text-[#1F2A1D] leading-tight">
                                {{ Auth::guard('pelanggan')->user()->nama ?? 'Pelanggan' }}
                            </p>
                            <p class="text-xs text-gray-500 leading-tight">Pelanggan</p>
                        </div>
                        {{-- Chevron --}}
                        <svg class="w-4 h-4 text-gray-400 transition-transform duration-200 ease-in-out"
                             :class="{ 'rotate-180': dropOpen }"
                             fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>

                    {{-- Dropdown panel --}}
                    <div x-show="dropOpen"
                         x-transition:enter="transition ease-out duration-150"
                         x-transition:enter-start="opacity-0 -translate-y-1"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-100"
                         x-transition:leave-start="opacity-100 translate-y-0"
                         x-transition:leave-end="opacity-0 -translate-y-1"
                         class="absolute right-0 mt-2 w-52 bg-white rounded-2xl shadow-xl border border-[#E5E7EB] py-2 z-50"
                         style="display: none;">
                        {{-- User header --}}
                        <div class="px-4 py-3 border-b border-gray-100">
                            <p class="text-xs text-gray-400">Masuk sebagai</p>
                            <p class="text-sm font-semibold text-[#1F2A1D] truncate">
                                {{ Auth::guard('pelanggan')->user()->nama ?? 'Pelanggan' }}
                            </p>
                        </div>
                        {{-- Profil link --}}
                        <a href="{{ route('customer.profile') }}"
                           class="flex items-center gap-3 px-4 py-2.5 text-sm font-medium transition-colors duration-200 ease-in-out
                                  {{ request()->routeIs('customer.profile') ? 'text-[#D4B06A] bg-[#D4B06A]/5' : 'text-gray-700 hover:bg-gray-50 hover:text-[#1F2A1D]' }}">
                            <svg class="w-4 h-4 {{ request()->routeIs('customer.profile') ? 'text-[#D4B06A]' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            Profil Saya
                        </a>
                        {{-- Logout --}}
                        <div class="border-t border-gray-100 mt-1 pt-1">
                            <button type="button" @click="$dispatch('open-logout-modal')"
                                    class="flex items-center gap-3 w-full px-4 py-2.5 text-sm font-medium text-red-500 transition-colors duration-200 ease-in-out hover:bg-red-50 hover:text-red-600">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                </svg>
                                Keluar
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Hamburger — Mobile/Tablet --}}
                <button @click="open = !open"
                        class="lg:hidden flex items-center justify-center w-10 h-10 rounded-xl transition-colors duration-200 ease-in-out hover:bg-gray-100 text-gray-600 focus:outline-none"
                        :aria-expanded="open"
                        aria-label="Toggle menu">
                    <svg x-show="!open" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                    <svg x-show="open" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display:none;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>

            </div>
        </div>
    </div>

    {{-- ===== MOBILE MENU ===== --}}
    <div x-show="open"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 -translate-y-1"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 -translate-y-1"
         class="lg:hidden border-t border-[#E5E7EB] bg-white shadow-lg"
         style="display: none;">
        <div class="max-w-7xl mx-auto px-4 py-4 space-y-1">

            {{-- User info --}}
            <div class="flex items-center gap-3 p-3 rounded-xl bg-gray-50 mb-3">
                <div class="cust-avatar flex-shrink-0">
                    {{ strtoupper(substr(Auth::guard('pelanggan')->user()->nama ?? 'P', 0, 1)) }}
                </div>
                <div>
                    <p class="text-sm font-semibold text-[#1F2A1D]">{{ Auth::guard('pelanggan')->user()->nama ?? 'Pelanggan' }}</p>
                    <p class="text-xs text-gray-500">Pelanggan</p>
                </div>
            </div>

            {{-- Mobile nav links --}}
            <a href="{{ route('customer.dashboard') }}" @click="open = false"
               class="cust-mobile-link {{ request()->routeIs('customer.dashboard') ? 'cust-mobile-active' : '' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                Dashboard
            </a>

            <a href="{{ route('customer.jadwal') }}" @click="open = false"
               class="cust-mobile-link {{ request()->routeIs('customer.jadwal') ? 'cust-mobile-active' : '' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                Jadwal Operasional
            </a>

            <a href="{{ route('customer.booking') }}" @click="open = false"
               class="cust-mobile-link {{ request()->routeIs('customer.booking') ? 'cust-mobile-active' : '' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Buat Booking
            </a>

            <a href="{{ route('customer.status') }}" @click="open = false"
               class="cust-mobile-link {{ request()->routeIs('customer.status') ? 'cust-mobile-active' : '' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Status Pesanan
            </a>

            <a href="{{ route('customer.riwayat') }}" @click="open = false"
               class="cust-mobile-link {{ request()->routeIs('customer.riwayat') ? 'cust-mobile-active' : '' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Riwayat Booking
            </a>

            <div class="border-t border-gray-100 pt-2 mt-2 space-y-1">
                <a href="{{ route('customer.profile') }}" @click="open = false"
                   class="cust-mobile-link {{ request()->routeIs('customer.profile') ? 'cust-mobile-active' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    Profil Saya
                </a>
                {{-- Logout --}}
                <button type="button" @click="$dispatch('open-logout-modal')"
                        class="flex items-center gap-3 w-full px-4 py-3 rounded-xl text-sm font-medium text-red-500 hover:bg-red-50 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    Keluar
                </button>
            </div>

        </div>
    </div>
    {{-- ===== END MOBILE MENU ===== --}}
</nav>
