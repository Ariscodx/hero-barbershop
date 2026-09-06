<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Hero Barbershop') }} - Dashboard Pelanggan</title>
    <meta name="description" content="Dashboard Pelanggan Hero Barbershop — Booking layanan potong rambut kini lebih mudah secara online.">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { font-family: 'Poppins', sans-serif; }

        /* Custom scrollbar */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #F4F8F5; }
        ::-webkit-scrollbar-thumb { background: #D4B06A; border-radius: 3px; }

        /* Card hover lift */
        .card-hover { transition: transform 0.2s ease, box-shadow 0.2s ease; }
        .card-hover:hover { transform: translateY(-4px); box-shadow: 0 20px 40px rgba(0,0,0,0.12); }

        /* Badge pulse animation */
        .badge-pulse { animation: cust-pulse 2s infinite; }
        @@keyframes cust-pulse { 0%, 100% { opacity: 1; } 50% { opacity: 0.7; } }

        /* Shimmer effect */
        .shimmer {
            background: linear-gradient(90deg, transparent 0%, rgba(255,255,255,0.08) 50%, transparent 100%);
            background-size: 200% 100%;
            animation: cust-shimmer 3s infinite;
        }
        @@keyframes cust-shimmer { 0% { background-position: -200% 0; } 100% { background-position: 200% 0; } }

        /* Nav active state */
        .nav-item-active {
            color: #D4B06A !important;
            background-color: rgba(212, 176, 106, 0.08);
            border-radius: 0.75rem;
            position: relative;
        }
        .nav-item-active::after {
            content: '';
            position: absolute;
            bottom: 4px;
            left: 50%;
            transform: translateX(-50%);
            width: 20px;
            height: 2px;
            background: #D4B06A;
            border-radius: 1px;
        }
        .nav-item {
            position: relative;
            transition: color 0.15s ease, background-color 0.15s ease;
            border-radius: 0.75rem;
        }
        .nav-item:hover {
            color: #1F2A1D;
            background-color: #F3F4F6;
        }
    </style>
</head>
<body class="antialiased bg-[#F4F8F5] text-gray-800" x-data="{ mobileMenuOpen: false }">

    <!-- ===================== NAVBAR ===================== -->
    <nav class="bg-white sticky top-0 z-50 shadow-sm border-b border-[#E5E7EB]" style="height: 80px;">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-full">
            <div class="flex items-center justify-between h-full gap-4">

                {{-- ========== LEFT: Logo ========== --}}
                <a href="{{ route('customer.dashboard') }}"
                   class="flex items-center gap-3 flex-shrink-0 group">
                    {{-- Logo image — no background, no border, no clip --}}
                    <div class="w-[60px] h-[60px] rounded-full bg-black flex items-center justify-center border-2 border-[#D4B06A]/30 overflow-hidden shadow-sm group-hover:opacity-90 transition-opacity">
                        <img src="{{ asset('images/logo_hero_barbershop.png') }}"
                             alt="Hero Barbershop Logo"
                             class="object-contain w-[50px] h-[50px]">
                    </div>
                    {{-- Brand text --}}
                    <div class="hidden sm:flex flex-col leading-none">
                        <span class="text-base font-bold tracking-widest text-[#1F2A1D]">HERO</span>
                        <span class="text-xs font-semibold tracking-[0.2em] text-[#D4B06A] uppercase">Barbershop</span>
                    </div>
                </a>

                {{-- ========== CENTER: Desktop Nav Links ========== --}}
                <div class="hidden lg:flex items-center gap-0.5 flex-1 justify-center">

                    <a href="{{ route('customer.dashboard') }}"
                       class="nav-item px-4 py-2 text-sm font-medium {{ request()->routeIs('customer.dashboard') ? 'nav-item-active' : 'text-gray-600' }}">
                        Dashboard
                    </a>

                    <a href="{{ route('customer.jadwal') }}"
                       class="nav-item px-4 py-2 text-sm font-medium {{ request()->routeIs('customer.jadwal') ? 'nav-item-active' : 'text-gray-600' }}">
                        Jadwal Operasional
                    </a>

                    <a href="{{ route('customer.booking') }}"
                       class="nav-item px-4 py-2 text-sm font-medium {{ request()->routeIs('customer.booking') ? 'nav-item-active' : 'text-gray-600' }}">
                        Buat Booking
                    </a>

                    <a href="{{ route('customer.status') }}"
                       class="nav-item px-4 py-2 text-sm font-medium {{ request()->routeIs('customer.status') ? 'nav-item-active' : 'text-gray-600' }}">
                        Status Pesanan
                    </a>

                    <a href="{{ route('customer.riwayat') }}"
                       class="nav-item px-4 py-2 text-sm font-medium {{ request()->routeIs('customer.riwayat') ? 'nav-item-active' : 'text-gray-600' }}">
                        Riwayat
                    </a>

                </div>

                {{-- ========== RIGHT: Profile Dropdown + Hamburger ========== --}}
                <div class="flex items-center gap-2 flex-shrink-0">

                    {{-- Profile Dropdown — Desktop --}}
                    <div class="hidden lg:block relative" x-data="{ open: false }">
                        <button @click="open = !open"
                                @click.away="open = false"
                                class="flex items-center gap-2.5 px-3 py-2 rounded-xl hover:bg-gray-50 transition-colors duration-200 group"
                                id="profile-menu-btn">
                            {{-- Avatar initial --}}
                            <div class="w-9 h-9 rounded-full flex items-center justify-center text-white text-sm font-bold flex-shrink-0 shadow-sm"
                                 style="background: linear-gradient(135deg, #1F2A1D, #2D3E2A);">
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
                            <svg class="w-4 h-4 text-gray-400 transition-transform duration-200"
                                 :class="{ 'rotate-180': open }"
                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>

                        {{-- Dropdown Panel --}}
                        <div x-show="open"
                             x-transition:enter="transition ease-out duration-150"
                             x-transition:enter-start="opacity-0 scale-95 -translate-y-1"
                             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                             x-transition:leave="transition ease-in duration-100"
                             x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                             x-transition:leave-end="opacity-0 scale-95 -translate-y-1"
                             class="absolute right-0 mt-2 w-52 bg-white rounded-2xl shadow-xl border border-[#E5E7EB] py-2 z-50"
                             style="display: none;">
                            {{-- User info header --}}
                            <div class="px-4 py-3 border-b border-gray-100">
                                <p class="text-xs text-gray-400">Masuk sebagai</p>
                                <p class="text-sm font-semibold text-[#1F2A1D] truncate">
                                    {{ Auth::guard('pelanggan')->user()->nama ?? 'Pelanggan' }}
                                </p>
                            </div>
                            {{-- Profil --}}
                            <a href="{{ route('customer.profile') }}"
                               class="flex items-center gap-3 px-4 py-2.5 text-sm font-medium {{ request()->routeIs('customer.profile') ? 'text-[#D4B06A] bg-[#D4B06A]/5' : 'text-gray-700 hover:bg-gray-50 hover:text-[#1F2A1D]' }} transition-colors">
                                <svg class="w-4 h-4 {{ request()->routeIs('customer.profile') ? 'text-[#D4B06A]' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                Profil Saya
                            </a>
                            {{-- Logout --}}
                            <div class="border-t border-gray-100 mt-1 pt-1">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit"
                                            class="flex items-center gap-3 w-full px-4 py-2.5 text-sm font-medium text-red-500 hover:bg-red-50 hover:text-red-600 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                        </svg>
                                        Keluar
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    {{-- Hamburger Button — Mobile/Tablet --}}
                    <button @click="mobileMenuOpen = !mobileMenuOpen"
                            class="lg:hidden flex items-center justify-center w-10 h-10 rounded-xl hover:bg-gray-100 transition-colors text-gray-600"
                            :aria-expanded="mobileMenuOpen"
                            aria-label="Toggle menu">
                        <svg x-show="!mobileMenuOpen" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                        <svg x-show="mobileMenuOpen" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display:none;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>

                </div>
            </div>
        </div>

        {{-- ========== MOBILE MENU ========== --}}
        <div x-show="mobileMenuOpen"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 -translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-2"
             class="lg:hidden bg-white border-t border-[#E5E7EB] shadow-lg"
             style="display: none;">
            <div class="max-w-7xl mx-auto px-4 py-4 space-y-1">

                {{-- User info block --}}
                <div class="flex items-center gap-3 p-3 rounded-xl bg-gray-50 mb-3">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center text-white text-sm font-bold shadow-sm flex-shrink-0"
                         style="background: linear-gradient(135deg, #1F2A1D, #2D3E2A);">
                        {{ strtoupper(substr(Auth::guard('pelanggan')->user()->nama ?? 'P', 0, 1)) }}
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-[#1F2A1D]">{{ Auth::guard('pelanggan')->user()->nama ?? 'Pelanggan' }}</p>
                        <p class="text-xs text-gray-500">Pelanggan</p>
                    </div>
                </div>

                {{-- Dashboard --}}
                <a href="{{ route('customer.dashboard') }}"
                   @click="mobileMenuOpen = false"
                   class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-colors
                          {{ request()->routeIs('customer.dashboard') ? 'bg-[#D4B06A]/10 text-[#D4B06A]' : 'text-gray-600 hover:bg-gray-50 hover:text-[#1F2A1D]' }}">
                    <svg class="w-5 h-5 {{ request()->routeIs('customer.dashboard') ? 'text-[#D4B06A]' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    Dashboard
                </a>

                {{-- Jadwal Operasional --}}
                <a href="{{ route('customer.jadwal') }}"
                   @click="mobileMenuOpen = false"
                   class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-colors
                          {{ request()->routeIs('customer.jadwal') ? 'bg-[#D4B06A]/10 text-[#D4B06A]' : 'text-gray-600 hover:bg-gray-50 hover:text-[#1F2A1D]' }}">
                    <svg class="w-5 h-5 {{ request()->routeIs('customer.jadwal') ? 'text-[#D4B06A]' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    Jadwal Operasional
                </a>

                {{-- Buat Booking --}}
                <a href="{{ route('customer.booking') }}"
                   @click="mobileMenuOpen = false"
                   class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-colors
                          {{ request()->routeIs('customer.booking') ? 'bg-[#D4B06A]/10 text-[#D4B06A]' : 'text-gray-600 hover:bg-gray-50 hover:text-[#1F2A1D]' }}">
                    <svg class="w-5 h-5 {{ request()->routeIs('customer.booking') ? 'text-[#D4B06A]' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Buat Booking
                </a>

                {{-- Status Pesanan --}}
                <a href="{{ route('customer.status') }}"
                   @click="mobileMenuOpen = false"
                   class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-colors
                          {{ request()->routeIs('customer.status') ? 'bg-[#D4B06A]/10 text-[#D4B06A]' : 'text-gray-600 hover:bg-gray-50 hover:text-[#1F2A1D]' }}">
                    <svg class="w-5 h-5 {{ request()->routeIs('customer.status') ? 'text-[#D4B06A]' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Status Pesanan
                </a>

                {{-- Riwayat --}}
                <a href="{{ route('customer.riwayat') }}"
                   @click="mobileMenuOpen = false"
                   class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-colors
                          {{ request()->routeIs('customer.riwayat') ? 'bg-[#D4B06A]/10 text-[#D4B06A]' : 'text-gray-600 hover:bg-gray-50 hover:text-[#1F2A1D]' }}">
                    <svg class="w-5 h-5 {{ request()->routeIs('customer.riwayat') ? 'text-[#D4B06A]' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Riwayat Booking
                </a>

                {{-- Divider --}}
                <div class="border-t border-gray-100 pt-2 mt-2 space-y-1">

                    {{-- Profil --}}
                    <a href="{{ route('customer.profile') }}"
                       @click="mobileMenuOpen = false"
                       class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-colors
                              {{ request()->routeIs('customer.profile') ? 'bg-[#D4B06A]/10 text-[#D4B06A]' : 'text-gray-600 hover:bg-gray-50 hover:text-[#1F2A1D]' }}">
                        <svg class="w-5 h-5 {{ request()->routeIs('customer.profile') ? 'text-[#D4B06A]' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        Profil Saya
                    </a>

                    {{-- Logout --}}
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                                class="flex items-center gap-3 w-full px-4 py-3 rounded-xl text-sm font-medium text-red-500 hover:bg-red-50 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                            Keluar
                        </button>
                    </form>

                </div>
            </div>
        </div>
        {{-- ========== END MOBILE MENU ========== --}}

    </nav>
    <!-- ==================== END NAVBAR ==================== -->

    <!-- Page Content -->
    <main class="min-h-screen">
        {{ $slot }}
    </main>

    <!-- ===================== FOOTER ===================== -->
    <footer class="bg-[#1F2A1D] text-white mt-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-10">

                {{-- Brand --}}
                <div>
                    <div class="flex items-center gap-3 mb-4">
                        <img src="{{ asset('images/logo_hero_barbershop.png') }}"
                             alt="Hero Barbershop Logo"
                             class="object-contain w-auto opacity-90 ml-1"
                             style="height: 80px;">
                    </div>
                    <p class="text-gray-400 text-sm leading-relaxed">
                        Layanan potong rambut profesional dengan pengalaman terbaik.
                        Booking mudah, hasil memuaskan.
                    </p>
                </div>

                {{-- Kontak --}}
                <div>
                    <h4 class="font-semibold text-[#D4B06A] mb-4 uppercase tracking-wider text-sm">Kontak & Sosial</h4>
                    <ul class="space-y-3 text-sm text-gray-400">
                        <li class="flex items-start gap-2">
                            <svg class="w-4 h-4 text-[#D4B06A] mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            Mungkid, Magelang
                        </li>
                        <li>
                            <a href="https://instagram.com/herobarbershop" target="_blank"
                               class="flex items-center gap-2 hover:text-[#D4B06A] transition-colors">
                                <svg class="w-4 h-4 text-[#D4B06A]" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/>
                                </svg>
                                @herobarbershop
                            </a>
                        </li>
                        <li>
                            <a href="https://wa.me/628123456789" target="_blank"
                               class="flex items-center gap-2 hover:text-[#D4B06A] transition-colors">
                                <svg class="w-4 h-4 text-[#D4B06A]" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                                </svg>
                                +62 812-3456-789
                            </a>
                        </li>
                    </ul>
                </div>

            </div>

            <div class="border-t border-gray-700/50 mt-10 pt-6 flex flex-col sm:flex-row items-center justify-between gap-2">
                <p class="text-gray-500 text-xs">© {{ date('Y') }} Hero Barbershop. All rights reserved.</p>
                <p class="text-gray-600 text-xs">Dibuat dengan ❤ untuk pelanggan setia kami</p>
            </div>
        </div>
    </footer>
    <!-- ==================== END FOOTER ==================== -->

    <!-- Modal Konfirmasi Logout -->
    <x-organisms.logout-modal />
</body>
</html>
