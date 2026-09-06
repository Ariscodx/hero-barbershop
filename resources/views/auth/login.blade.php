<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - Hero Barbershop</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=poppins:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in {
            animation: fadeIn 1s ease-out forwards;
        }
    </style>
</head>

<body class="font-poppins bg-[#F8F8F8] text-gray-900 antialiased min-h-screen flex flex-col" x-data="{ showPassword: false, mobileMenu: false }">

    <!-- Navbar -->
    <nav class="sticky top-0 z-50 bg-[#F8F8F8]/90 backdrop-blur-md border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <!-- Logo -->
                <a href="{{ route('welcome') }}" class="flex items-center gap-3 sm:gap-4 group">
                    <div class="w-[60px] h-[60px] rounded-full bg-[#1F2A1D] flex items-center justify-center border-2 border-[#D4B06A]/30 overflow-hidden shadow-sm group-hover:opacity-90 group-hover:scale-105 transition-all duration-300">
                        <img src="{{ asset('images/logo_hero_barbershop.png') }}" alt="Hero Barbershop"
                            class="object-contain w-[50px] h-[50px]">
                    </div>
                    <span class="font-bold text-lg tracking-wider text-[#1F2A1D]">HERO BARBERSHOP</span>
                </a>
                
                <!-- Desktop Menu -->
                <div class="hidden md:flex items-center gap-8 text-sm font-medium">
                    <a href="{{ route('welcome') }}" class="text-[#1F2A1D] hover:text-[#C8A96A] transition-colors">Beranda</a>
                    <a href="#" class="text-gray-500 hover:text-[#C8A96A] transition-colors">Lokasi</a>
                    <a href="#" class="text-gray-500 hover:text-[#C8A96A] transition-colors">Kontak</a>
                </div>

                <!-- Mobile Menu Button -->
                <button @click="mobileMenu = !mobileMenu" class="md:hidden p-2 rounded-xl text-gray-600 hover:bg-gray-100 transition-colors focus:outline-none focus:ring-2 focus:ring-[#C8A96A]">
                    <svg x-show="!mobileMenu" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    <svg x-show="mobileMenu" class="w-6 h-6" style="display: none;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
        </div>

        <!-- Mobile Menu Dropdown -->
        <div x-show="mobileMenu" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 -translate-y-4"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-4"
             class="md:hidden bg-white border-b border-gray-200 px-4 pt-2 pb-6 space-y-3 absolute w-full shadow-lg"
             style="display: none;">
            <a href="{{ route('welcome') }}" class="block px-4 py-3 rounded-xl bg-gray-50 text-[#1F2A1D] font-medium transition-colors hover:bg-gray-100">Beranda</a>
            <a href="#" class="block px-4 py-3 rounded-xl text-gray-600 font-medium transition-colors hover:bg-gray-50 hover:text-[#1F2A1D]">Lokasi</a>
            <a href="#" class="block px-4 py-3 rounded-xl text-gray-600 font-medium transition-colors hover:bg-gray-50 hover:text-[#1F2A1D]">Kontak</a>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="flex-1 flex flex-col lg:flex-row">
        <!-- Mobile Header (Visible only on mobile, order 0) -->
        <div class="w-full lg:hidden flex flex-col justify-center px-6 sm:px-12 pt-8 pb-4 order-0 bg-[#F8F8F8]">
            <div class="w-full max-w-md mx-auto">
                <h1 class="text-3xl sm:text-4xl font-bold text-[#1F2A1D] mb-2 leading-tight">Masuk ke Layanan Booking
                    Hero Barbershop</h1>
                <p class="text-gray-500 text-sm sm:text-base leading-relaxed">Silakan masuk menggunakan akun Anda
                    untuk melakukan booking layanan potong rambut atau mengelola sistem Hero Barbershop.</p>
            </div>
        </div>

        <!-- Left Section (45%) -->
        <div class="w-full lg:w-[45%] flex flex-col justify-center px-6 sm:px-12 lg:px-16 xl:px-24 pb-12 pt-4 lg:py-12 order-2 lg:order-1">
            <div class="w-full max-w-md mx-auto">
                <h1 class="hidden lg:block text-3xl sm:text-4xl font-bold text-[#1F2A1D] mb-4 leading-tight">Masuk ke Layanan Booking
                    Hero Barbershop</h1>
                <p class="hidden lg:block text-gray-500 text-sm sm:text-base mb-10 leading-relaxed">Silakan masuk menggunakan akun Anda
                    untuk melakukan booking layanan potong rambut atau mengelola sistem Hero Barbershop.</p>

                <!-- Login Form -->
                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf

                    <!-- Email/Phone -->
                    <div class="space-y-2">
                        <label class="block text-sm font-semibold text-gray-700">Email atau Nomor HP</label>
                        <div class="relative">
                            <div
                                class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-gray-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                    </path>
                                </svg>
                            </div>
                            <input type="text" name="email"
                                class="w-full rounded-xl border-gray-200 shadow-sm focus:border-[#C8A96A] focus:ring focus:ring-[#C8A96A] focus:ring-opacity-30 pl-11 pr-4 py-3 text-sm text-gray-900 bg-white transition-all duration-300"
                                placeholder="Masukkan email atau nomor HP..." required>
                        </div>
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <!-- Password -->
                    <div class="space-y-2">
                        <label class="block text-sm font-semibold text-gray-700">Password</label>
                        <div class="relative">
                            <div
                                class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-gray-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                                    </path>
                                </svg>
                            </div>
                            <input :type="showPassword ? 'text' : 'password'" name="password"
                                class="w-full rounded-xl border-gray-200 shadow-sm focus:border-[#C8A96A] focus:ring focus:ring-[#C8A96A] focus:ring-opacity-30 pl-11 pr-12 py-3 text-sm text-gray-900 bg-white transition-all duration-300"
                                placeholder="••••••••" required>

                            <!-- Toggle Password -->
                            <button type="button" @click="showPassword = !showPassword"
                                class="absolute inset-y-0 right-0 flex items-center pr-4 text-gray-400 hover:text-gray-600 focus:outline-none">
                                <svg x-show="!showPassword" class="w-5 h-5" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                    </path>
                                </svg>
                                <svg x-show="showPassword" class="w-5 h-5" style="display: none;" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21">
                                    </path>
                                </svg>
                            </button>
                        </div>
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <!-- Remember Me (Hidden but present for logic if needed) -->
                    <div class="hidden">
                        <label for="remember_me" class="inline-flex items-center">
                            <input id="remember_me" type="checkbox"
                                class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                                name="remember" checked>
                            <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
                        </label>
                    </div>

                    <!-- Buttons -->
                    <div class="flex flex-col sm:flex-row gap-4 pt-2">
                        <a href="{{ route('register') }}"
                            class="w-full sm:w-1/2 px-6 py-3 bg-white border border-[#1F2A1D] text-[#1F2A1D] font-medium rounded-xl hover:bg-[#1F2A1D] hover:text-white transition-all duration-300 hover:scale-[1.02] active:scale-[0.98] text-center shadow-sm">
                            Daftar Akun
                        </a>
                        <button type="submit"
                            class="w-full sm:w-1/2 px-6 py-3 bg-[#1F2A1D] border border-transparent text-white font-medium rounded-xl hover:bg-[#2c3d29] transition-all duration-300 hover:scale-[1.02] active:scale-[0.98] shadow-md flex justify-center items-center gap-2">
                            Masuk
                        </button>
                    </div>
                </form>

                <div class="mt-8 text-center sm:text-left">
                    <a href="{{ route('password.request') }}"
                        class="text-sm font-medium text-gray-500 hover:text-[#C8A96A] transition-colors">Lupa
                        Password?</a>
                </div>
            </div>
        </div>

        <!-- Right Section (55%) -->
        <div class="w-full lg:w-[55%] bg-gradient-to-br from-[#1F2A1D] to-[#2C3B28] flex flex-col items-center justify-center p-12 lg:p-20 relative overflow-hidden min-h-[40vh] lg:min-h-0 order-1 lg:order-2">
            <!-- Ornaments -->
            <div class="absolute inset-0 z-0 opacity-10 pointer-events-none">
                <!-- Diagonal pattern -->
                <div class="absolute inset-0"
                    style="background-image: repeating-linear-gradient(45deg, #000 0, #000 1px, transparent 0, transparent 50%); background-size: 20px 20px;">
                </div>

                <!-- Blurred circles -->
                <div class="absolute -top-32 -left-32 w-96 h-96 bg-[#C8A96A] rounded-full blur-3xl opacity-30"></div>
                <div class="absolute -bottom-32 -right-32 w-96 h-96 bg-[#C8A96A] rounded-full blur-3xl opacity-20">
                </div>

                <!-- Thin lines -->
                <div
                    class="absolute top-1/4 left-0 w-full h-[1px] bg-gradient-to-r from-transparent via-[#C8A96A] to-transparent opacity-50">
                </div>
                <div
                    class="absolute bottom-1/3 left-0 w-full h-[1px] bg-gradient-to-r from-transparent via-[#C8A96A] to-transparent opacity-30">
                </div>
            </div>

            <!-- Content -->
            <div class="relative z-10 flex flex-col items-center text-center animate-fade-in">
                <!-- Logo -->
                <img src="{{ asset('images/logo_hero_barbershop.png') }}" alt="Hero Barbershop Logo"
                    class="w-56 sm:w-64 lg:w-[300px] h-auto mb-8 drop-shadow-2xl">
                <p class="text-[#C8A96A] text-sm sm:text-lg lg:text-xl font-medium tracking-widest uppercase mb-8">
                    Haircuts &bull; Shaves &bull; Grooming</p>

                <div class="max-w-xs sm:max-w-sm relative">
                    <!-- Slogan Quotes -->
                    <span class="absolute -top-4 -left-4 text-4xl text-[#C8A96A]/30 font-serif">"</span>
                    <p class="text-gray-300 text-sm sm:text-base italic leading-relaxed">Tampil Lebih Percaya Diri
                        Bersama Hero Barbershop.</p>
                    <span class="absolute -bottom-6 -right-4 text-4xl text-[#C8A96A]/30 font-serif">"</span>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-200 py-6">
        <div
            class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row justify-between items-center gap-4">
            <p class="text-xs text-gray-500">&copy; 2026 Hero Barbershop. Semua Hak Dilindungi.</p>
            <div class="flex flex-wrap justify-center gap-4 sm:gap-6 text-xs font-medium text-gray-500">
                <a href="#" class="hover:text-[#C8A96A] transition-colors">Kebijakan Privasi</a>
                <a href="#" class="hover:text-[#C8A96A] transition-colors">Syarat & Ketentuan</a>
                <a href="#" class="hover:text-[#C8A96A] transition-colors">Instagram</a>
                <a href="#" class="hover:text-[#C8A96A] transition-colors">WhatsApp</a>
            </div>
        </div>
    </footer>

</body>

</html>
