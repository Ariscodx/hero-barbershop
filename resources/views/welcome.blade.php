@php
    $user = Auth::guard('pelanggan')->user() ?? Auth::guard('admin')->user();
    $isPelanggan = Auth::guard('pelanggan')->check();
    $isAdmin = Auth::guard('admin')->check();

    if ($isPelanggan) {
        $bookingUrl = route('customer.booking');
        $dashboardUrl = route('customer.dashboard');
    } elseif ($isAdmin) {
        $bookingUrl = route('admin.booking');
        $dashboardUrl = route('admin.dashboard');
    } else {
        $bookingUrl = route('login');
        $dashboardUrl = route('login');
    }

    // Ambil jadwal hari ini dari database
    $namaHariInggris = now()->locale('id')->dayName; // e.g. "Minggu"
    $hariIni = now()->isoFormat('dddd'); // nama hari dalam bahasa lokal
    // Map nama hari Inggris ke Indonesia
    $hariMap = [
        'Sunday'    => 'Minggu',
        'Monday'    => 'Senin',
        'Tuesday'   => 'Selasa',
        'Wednesday' => 'Rabu',
        'Thursday'  => 'Kamis',
        'Friday'    => 'Jumat',
        'Saturday'  => 'Sabtu',
    ];
    $namaHariId = $hariMap[now()->format('l')] ?? now()->format('l');
    $jadwalHariIni = DB::table('jadwal')->where('hari', $namaHariId)->first();
    $isTokoOpen    = $jadwalHariIni && $jadwalHariIni->status === 'buka';
    $jamBuka       = $jadwalHariIni ? substr($jadwalHariIni->jam_buka, 0, 5) : null;
    $jamTutup      = $jadwalHariIni ? substr($jadwalHariIni->jam_tutup, 0, 5) : null;
@endphp
<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Hero Barbershop — Sistem Layanan Booking Online</title>
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Outfit:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Styles / Scripts -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <script src="https://cdn.tailwindcss.com"></script>
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @endif

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #111810;
            color: #F8F8F5;
            overflow-x: hidden;
        }
        h1, h2, h3, h4, .font-heading {
            font-family: 'Outfit', sans-serif;
        }
        .gold-gradient-text {
            background: linear-gradient(135deg, #FFE59D 0%, #D4B06A 50%, #AA823C 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .gold-gradient-bg {
            background: linear-gradient(135deg, #D4B06A 0%, #F0D080 50%, #B89047 100%);
        }
        .gold-gradient-bg:hover {
            background: linear-gradient(135deg, #E5C27D 0%, #FFE296 50%, #C9A158 100%);
        }
        .dark-card {
            background: linear-gradient(135deg, rgba(31, 42, 29, 0.75) 0%, rgba(21, 29, 20, 0.85) 100%);
            backdrop-filter: blur(16px);
            border: 1px solid rgba(212, 176, 106, 0.18);
        }
        .dark-card:hover {
            border-color: rgba(212, 176, 106, 0.45);
            box-shadow: 0 20px 40px -15px rgba(0, 0, 0, 0.7), 0 0 25px -5px rgba(212, 176, 106, 0.15);
        }
        @keyframes floatSlow {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-12px) rotate(1.5deg); }
        }
        .animate-float {
            animation: floatSlow 6s ease-in-out infinite;
        }
        @keyframes pulseGlow {
            0%, 100% { opacity: 0.3; transform: scale(1); }
            50% { opacity: 0.6; transform: scale(1.08); }
        }
        .animate-pulse-glow {
            animation: pulseGlow 4s ease-in-out infinite;
        }
    </style>
</head>
<body x-data="{ mobileMenu: false }" class="min-h-screen flex flex-col justify-between selection:bg-[#D4B06A] selection:text-[#1F2A1D]">

    {{-- ===================== TOP NAVBAR ===================== --}}
    <header class="sticky top-0 z-40 bg-[#111810]/85 backdrop-blur-md border-b border-white/10 transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                
                {{-- Logo & Brand --}}
                <a href="{{ route('welcome') }}" class="flex items-center gap-3.5 group">
                    <div class="relative w-20 h-20 rounded-full bg-black border-2 border-[#D4B06A]/40 flex items-center justify-center p-1.5 shadow-md group-hover:border-[#D4B06A] transition-all duration-300 overflow-hidden">
                        <img src="{{ asset('images/logo_hero_barbershop.png') }}" alt="Logo" class="w-full h-full object-contain group-hover:scale-105 transition-transform duration-300">
                    </div>
                    <div class="flex flex-col">
                        <span class="font-heading text-xl font-extrabold tracking-wider text-white group-hover:text-[#D4B06A] transition-colors">
                            HERO <span class="gold-gradient-text">BARBERSHOP</span>
                        </span>
                        <span class="text-[10px] tracking-widest uppercase text-gray-400 font-semibold">Premium Men's Grooming</span>
                    </div>
                </a>

                {{-- Desktop Navigation Links — Cukup Login & Registrasi Saja --}}
                <div class="hidden sm:flex items-center gap-4">
                    @if ($isPelanggan || $isAdmin)
                        <a href="{{ $dashboardUrl }}" class="px-6 py-2.5 rounded-xl bg-[#1F2A1D] border border-[#D4B06A]/40 text-[#D4B06A] font-semibold text-sm hover:bg-[#D4B06A]/10 transition-all duration-200">
                            Dashboard Saya
                        </a>
                        <a href="{{ $bookingUrl }}" class="px-6 py-2.5 rounded-xl gold-gradient-bg text-[#111810] font-bold text-sm shadow-lg hover:shadow-[#D4B06A]/25 hover:scale-[1.02] active:scale-95 transition-all duration-200 flex items-center gap-2">
                            <span>Booking Sekarang</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="px-6 py-2.5 rounded-xl bg-[#1F2A1D] border border-white/15 text-gray-200 hover:text-white hover:border-[#D4B06A]/50 font-semibold text-sm transition-all duration-200">
                            Login
                        </a>
                        <a href="{{ route('register') }}" class="px-6 py-2.5 rounded-xl gold-gradient-bg text-[#111810] font-bold text-sm shadow-lg hover:shadow-[#D4B06A]/25 hover:scale-[1.02] active:scale-95 transition-all duration-200 flex items-center gap-2">
                            <span>Registrasi</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                        </a>
                    @endif
                </div>

                {{-- Mobile Menu Button --}}
                <button @click="mobileMenu = !mobileMenu" class="sm:hidden p-2.5 rounded-xl bg-[#1F2A1D] border border-white/10 text-gray-300 hover:text-white">
                    <svg x-show="!mobileMenu" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    <svg x-show="mobileMenu" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
        </div>

        {{-- Mobile Dropdown — Cukup Login & Registrasi Saja --}}
        <div x-show="mobileMenu" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 -translate-y-4"
             x-transition:enter-end="opacity-100 translate-y-0"
             class="sm:hidden bg-[#151D14] border-b border-white/10 px-4 py-6 space-y-3"
             style="display: none;">
            @if ($isPelanggan || $isAdmin)
                <a href="{{ $dashboardUrl }}" class="w-full block text-center py-3 rounded-xl bg-[#1F2A1D] border border-[#D4B06A]/40 text-[#D4B06A] font-semibold text-sm">Dashboard Saya</a>
                <a href="{{ $bookingUrl }}" class="w-full block text-center py-3 rounded-xl gold-gradient-bg text-[#111810] font-bold text-sm shadow-lg">
                    💈 Booking Sekarang Tanpa Antre
                </a>
            @else
                <a href="{{ route('login') }}" class="w-full block text-center py-3 rounded-xl bg-[#1F2A1D] border border-white/15 text-gray-200 font-semibold text-base">Login</a>
                <a href="{{ route('register') }}" class="w-full block text-center py-3 rounded-xl gold-gradient-bg text-[#111810] font-bold text-base shadow-lg">Registrasi</a>
            @endif
        </div>
    </header>

    {{-- ===================== HERO SECTION ===================== --}}
    <main class="flex-grow flex items-center relative py-6 lg:py-0 overflow-hidden">
        {{-- Background Gradients & Glows --}}
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[400px] bg-gradient-to-tr from-[#1F2A1D] to-[#D4B06A]/20 blur-[150px] opacity-60 pointer-events-none rounded-full"></div>
        <div class="absolute -top-24 -left-24 w-96 h-96 bg-[#D4B06A]/10 rounded-full blur-[100px] pointer-events-none"></div>
        <div class="absolute bottom-10 right-0 w-80 h-80 bg-emerald-500/10 rounded-full blur-[100px] pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 w-full">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-10 items-center">
                
                {{-- Left Text Column --}}
                <div class="lg:col-span-7 text-left space-y-6">
                    
                    {{-- Badge --}}
                    <div class="inline-flex items-center gap-2.5 px-4 py-2 rounded-full bg-[#1F2A1D]/90 border border-[#D4B06A]/30 shadow-lg animate-fade-in">
                        <span class="flex h-2.5 w-2.5 relative">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-[#D4B06A] opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-[#D4B06A]"></span>
                        </span>
                        <span class="text-xs sm:text-sm font-semibold tracking-wide text-gray-200">
                            Reservasi Online <span class="text-[#D4B06A]">100% Bebas Antre</span>
                        </span>
                    </div>

                    {{-- Headline Sesuai Permintaan --}}
                    <h1 class="font-heading text-4xl sm:text-5xl lg:text-6xl font-extrabold text-white leading-[1.18] tracking-tight">
                        Selamat Datang di <br>
                        <span class="gold-gradient-text">Sistem Layanan Booking</span> <br>
                        Hero Barbershop.
                    </h1>

                    {{-- Description --}}
                    <p class="text-gray-300 text-base sm:text-lg leading-relaxed max-w-xl">
                        Nikmati kemudahan reservasi potong rambut secara online real-time. Pilih jadwal favoritmu, datang tepat waktu, dan langsung duduk di kursi kehormatan nikmati pelayanannya
                    </p>

                    {{-- CTA Buttons --}}
                    <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-4 pt-2">
                        <a href="{{ $bookingUrl }}" 
                           class="group relative px-8 py-4 rounded-2xl gold-gradient-bg text-[#111810] font-extrabold text-base shadow-[0_10px_25px_-5px_rgba(212,176,106,0.4)] hover:shadow-[0_15px_30px_-5px_rgba(212,176,106,0.6)] hover:scale-[1.02] active:scale-95 transition-all duration-300 flex items-center justify-center gap-3 overflow-hidden">
                            <span class="relative z-10">Booking Sekarang</span>
                            <svg class="w-5 h-5 relative z-10 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                            <div class="absolute inset-0 bg-white/20 translate-y-full group-hover:translate-y-0 transition-transform duration-300"></div>
                        </a>

                        @if (!$isPelanggan && !$isAdmin)
                            <a href="{{ route('register') }}" 
                               class="px-8 py-4 rounded-2xl bg-[#1F2A1D]/80 hover:bg-[#1F2A1D] border border-white/15 hover:border-[#D4B06A]/50 text-gray-200 hover:text-white font-semibold text-base transition-all duration-200 text-center flex items-center justify-center gap-2">
                                <span>✨ Daftar Sekarang</span>
                            </a>
                        @endif
                    </div>
                </div>

                {{-- Right Illustration Banner Column — Menggunakan logo2.png --}}
                <div class="lg:col-span-5 relative flex justify-center">
                    <div class="relative w-full max-w-md lg:max-w-none animate-float">
                        
                        {{-- Outer Glowing Frame --}}
                        <div class="absolute -inset-1 rounded-3xl bg-gradient-to-r from-[#D4B06A]/50 via-emerald-500/20 to-[#D4B06A]/50 blur-xl opacity-70"></div>
                        
                        {{-- Main Glass Card --}}
                        <div class="relative rounded-3xl overflow-hidden dark-card shadow-2xl border border-white/20 p-6 sm:p-8 flex flex-col items-center justify-center text-center">
                            
                            {{-- Image Container (logo2.png) --}}
                            <div class="relative w-full py-6 flex items-center justify-center group">
                                <div class="absolute w-56 h-56 rounded-full bg-gradient-to-tr from-[#D4B06A]/25 to-emerald-500/10 blur-2xl pointer-events-none group-hover:scale-110 transition-transform duration-500"></div>
                                <img src="{{ asset('images/logo3.png') }}" alt="Hero Barbershop Logo" class="relative z-10 w-64 sm:w-72 h-auto object-contain drop-shadow-[0_15px_25px_rgba(0,0,0,0.6)] group-hover:scale-105 transition-transform duration-500">
                            </div>

                            {{-- Status Badges --}}
                            <div class="w-full mt-6 space-y-3">
                                <div class="p-3.5 rounded-2xl bg-[#1F2A1D]/90 backdrop-blur-md border border-white/15 flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-xl gold-gradient-bg flex items-center justify-center text-[#111810] font-bold text-lg shadow-md">
                                            💈
                                        </div>
                                        <div class="text-left">
                                            <div class="text-xs text-gray-400 font-medium">Status Toko Hari Ini</div>
                                            <div class="text-sm font-bold text-white flex items-center gap-1.5">
                                                @if($isTokoOpen && $jamBuka && $jamTutup)
                                                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-ping"></span>
                                                    Buka ({{ $jamBuka }} - {{ $jamTutup }} WIB)
                                                @elseif($jadwalHariIni)
                                                    <span class="w-2 h-2 rounded-full bg-red-400"></span>
                                                    Tutup Hari Ini
                                                @else
                                                    <span class="w-2 h-2 rounded-full bg-gray-400"></span>
                                                    Jadwal Tidak Tersedia
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <span class="text-xs font-bold px-3 py-1.5 rounded-lg bg-[#D4B06A]/20 text-[#D4B06A] border border-[#D4B06A]/30">
                                        Siap Reservasi
                                    </span>
                                </div>
                            </div>

                            {{-- Bottom Mini Info Bar --}}
                            <div class="w-full mt-4 pt-4 border-t border-white/10 flex items-center justify-between text-xs text-gray-300">
                                <span class="flex items-center gap-1.5 font-medium">
                                    <svg class="w-4 h-4 text-[#D4B06A]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                                    Jaminan Garansi Tampilan 100%
                                </span>
                                <span class="text-[#D4B06A] font-semibold">📍 Lokasi Strategis</span>
                            </div>

                        </div>

                        {{-- Floating Decorative Element Left --}}
                        <div class="absolute -top-6 -left-6 hidden sm:flex items-center gap-3 p-3.5 rounded-2xl bg-[#1A2418]/95 border border-[#D4B06A]/40 shadow-xl backdrop-blur-md animate-bounce" style="animation-duration: 4s;">
                            <div class="w-9 h-9 rounded-xl bg-[#D4B06A]/20 flex items-center justify-center text-[#D4B06A] font-bold text-lg">✂️</div>
                            <div>
                                <div class="text-[11px] text-gray-400 font-medium">Kapster Spesialis</div>
                                <div class="text-xs font-bold text-white">Master Fade & Trim</div>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </main>

    {{-- ===================== FOOTER — LANGSUNG DI BAWAH HERO ===================== --}}
    <footer class="bg-[#0E140D] border-t border-white/10 text-gray-400 py-10 mt-auto">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row items-center justify-between gap-6">
                
                {{-- Brand & Copyright --}}
                <div class="flex items-center gap-3">
                    <img src="{{ asset('images/logo_hero_barbershop.png') }}" alt="Logo" class="w-9 h-9 object-contain">
                    <div>
                        <span class="font-heading text-base font-bold tracking-wider text-white">HERO <span class="gold-gradient-text">BARBERSHOP</span></span>
                        <p class="text-xs text-gray-500">&copy; {{ date('Y') }} Hero Barbershop. All rights reserved.</p>
                    </div>
                </div>

                {{-- Quick Info / Links --}}
                <div class="flex flex-wrap items-center justify-center gap-6 text-xs text-gray-300">
                    <span class="flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-[#D4B06A]"></span>
                        @if($isTokoOpen && $jamBuka && $jamTutup)
                            {{ $namaHariId }}: <strong>{{ $jamBuka }} - {{ $jamTutup }} WIB</strong>
                        @else
                            Hari Ini: <strong>Tutup</strong>
                        @endif
                    </span>
                    <span>📍 Mungkid, Magelang</span>
                </div>

            </div>
        </div>
    </footer>

</body>
</html>
