<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Daftar Akun - Hero Barbershop</title>

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

<body class="font-poppins bg-[#F8F8F8] text-gray-900 antialiased min-h-screen flex flex-col" x-data="{ showPassword: false, showConfirm: false, mobileMenu: false }">

    <!-- Navbar -->
    <nav class="sticky top-0 z-50 bg-[#F8F8F8]/90 backdrop-blur-md border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <!-- Logo -->
                <a href="{{ route('welcome') }}" class="flex items-center gap-3 sm:gap-4 group">
                    <div
                        class="w-[60px] h-[60px] rounded-full bg-[#1F2A1D] flex items-center justify-center border-2 border-[#D4B06A]/30 overflow-hidden shadow-sm group-hover:opacity-90 group-hover:scale-105 transition-all duration-300">
                        <img src="{{ asset('images/logo_hero_barbershop.png') }}" alt="Hero Barbershop"
                            class="object-contain w-[50px] h-[50px]">
                    </div>
                    <span class="font-bold text-lg tracking-wider text-[#1F2A1D]">HERO BARBERSHOP</span>
                </a>

                <!-- Desktop Menu -->
                <div class="hidden md:flex items-center gap-8 text-sm font-medium">
                    <a href="{{ route('welcome') }}"
                        class="text-[#1F2A1D] hover:text-[#C8A96A] transition-colors">Beranda</a>
                    <a href="#" class="text-gray-500 hover:text-[#C8A96A] transition-colors">Lokasi</a>
                    <a href="#" class="text-gray-500 hover:text-[#C8A96A] transition-colors">Kontak</a>
                </div>

                <!-- Mobile Menu Button -->
                <button @click="mobileMenu = !mobileMenu"
                    class="md:hidden p-2 rounded-xl text-gray-600 hover:bg-gray-100 transition-colors focus:outline-none focus:ring-2 focus:ring-[#C8A96A]">
                    <svg x-show="!mobileMenu" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                    <svg x-show="mobileMenu" class="w-6 h-6" style="display: none;" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>

        <!-- Mobile Menu Dropdown -->
        <div x-show="mobileMenu" x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 -translate-y-4" x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 -translate-y-4"
            class="md:hidden bg-white border-b border-gray-200 px-4 pt-2 pb-6 space-y-3 absolute w-full shadow-lg"
            style="display: none;">
            <a href="{{ route('welcome') }}"
                class="block px-4 py-3 rounded-xl bg-gray-50 text-[#1F2A1D] font-medium transition-colors hover:bg-gray-100">Beranda</a>
            <a href="#"
                class="block px-4 py-3 rounded-xl text-gray-600 font-medium transition-colors hover:bg-gray-50 hover:text-[#1F2A1D]">Lokasi</a>
            <a href="#"
                class="block px-4 py-3 rounded-xl text-gray-600 font-medium transition-colors hover:bg-gray-50 hover:text-[#1F2A1D]">Kontak</a>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="flex-1 flex flex-col lg:flex-row">
        <!-- Left Section (45%) -->
        <div class="w-full lg:w-[45%] flex flex-col justify-center px-6 sm:px-12 lg:px-16 xl:px-24 py-12">
            <div class="w-full max-w-md mx-auto">
                <h1 class="text-3xl sm:text-4xl font-bold text-[#1F2A1D] mb-4 leading-tight">Buat Akun
                    Pelanggan Baru</h1>
                <p class="text-gray-500 text-sm sm:text-base mb-8 leading-relaxed">Daftarkan diri Anda untuk
                    menikmati layanan booking Hero Barbershop dengan mudah dan cepat.</p>

                <!-- Register Form -->
                <form method="POST" action="{{ route('register') }}" class="space-y-5">
                    @csrf

                    <!-- Nama Lengkap -->
                    <div class="space-y-2">
                        <label class="block text-sm font-semibold text-gray-700">Nama Lengkap</label>
                        <div class="relative">
                            <div
                                class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-gray-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            <input type="text" name="nama" value="{{ old('nama') }}"
                                class="w-full rounded-xl border-gray-200 shadow-sm focus:border-[#C8A96A] focus:ring focus:ring-[#C8A96A] focus:ring-opacity-30 pl-11 pr-4 py-3 text-sm text-gray-900 bg-white transition-all duration-300"
                                placeholder="Masukkan nama lengkap..." required autofocus>
                        </div>
                        <x-input-error :messages="$errors->get('nama')" class="mt-1" />
                    </div>

                    <!-- Email -->
                    <div class="space-y-2">
                        <label class="block text-sm font-semibold text-gray-700">Email</label>
                        <div class="relative">
                            <div
                                class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-gray-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <input type="email" name="email" value="{{ old('email') }}"
                                class="w-full rounded-xl border-gray-200 shadow-sm focus:border-[#C8A96A] focus:ring focus:ring-[#C8A96A] focus:ring-opacity-30 pl-11 pr-4 py-3 text-sm text-gray-900 bg-white transition-all duration-300"
                                placeholder="Masukkan alamat email..." required>
                        </div>
                        <x-input-error :messages="$errors->get('email')" class="mt-1" />
                    </div>

                    <!-- No Telepon -->
                    <div class="space-y-2">
                        <label class="block text-sm font-semibold text-gray-700">No Telepon <span
                                class="text-gray-400 font-normal">(opsional)</span></label>
                        <div class="relative">
                            <div
                                class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-gray-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                </svg>
                            </div>
                            <input type="text" name="no_tlp" value="{{ old('no_tlp') }}"
                                class="w-full rounded-xl border-gray-200 shadow-sm focus:border-[#C8A96A] focus:ring focus:ring-[#C8A96A] focus:ring-opacity-30 pl-11 pr-4 py-3 text-sm text-gray-900 bg-white transition-all duration-300"
                                placeholder="08xxxxxxxxxx">
                        </div>
                        <x-input-error :messages="$errors->get('no_tlp')" class="mt-1" />
                    </div>

                    <!-- Alamat -->
                    <div class="space-y-2">
                        <label class="block text-sm font-semibold text-gray-700">Alamat <span
                                class="text-gray-400 font-normal">(opsional)</span></label>
                        <div class="relative">
                            <div class="absolute top-3 left-0 flex items-start pl-4 pointer-events-none text-gray-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </div>
                            <textarea name="alamat" rows="2"
                                class="w-full rounded-xl border-gray-200 shadow-sm focus:border-[#C8A96A] focus:ring focus:ring-[#C8A96A] focus:ring-opacity-30 pl-11 pr-4 py-3 text-sm text-gray-900 bg-white transition-all duration-300 resize-none"
                                placeholder="Masukkan alamat lengkap...">{{ old('alamat') }}</textarea>
                        </div>
                        <x-input-error :messages="$errors->get('alamat')" class="mt-1" />
                    </div>

                    <!-- Password -->
                    <div class="space-y-2">
                        <label class="block text-sm font-semibold text-gray-700">Password</label>
                        <div class="relative">
                            <div
                                class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-gray-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                            </div>
                            <input :type="showPassword ? 'text' : 'password'" name="password"
                                class="w-full rounded-xl border-gray-200 shadow-sm focus:border-[#C8A96A] focus:ring focus:ring-[#C8A96A] focus:ring-opacity-30 pl-11 pr-12 py-3 text-sm text-gray-900 bg-white transition-all duration-300"
                                placeholder="Min. 8 karakter" required>
                            <button type="button" @click="showPassword = !showPassword"
                                class="absolute inset-y-0 right-0 flex items-center pr-4 text-gray-400 hover:text-gray-600 focus:outline-none">
                                <svg x-show="!showPassword" class="w-5 h-5" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <svg x-show="showPassword" class="w-5 h-5" style="display: none;" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                </svg>
                            </button>
                        </div>
                        <x-input-error :messages="$errors->get('password')" class="mt-1" />
                    </div>

                    <!-- Konfirmasi Password -->
                    <div class="space-y-2">
                        <label class="block text-sm font-semibold text-gray-700">Konfirmasi Password</label>
                        <div class="relative">
                            <div
                                class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-gray-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                </svg>
                            </div>
                            <input :type="showConfirm ? 'text' : 'password'" name="password_confirmation"
                                class="w-full rounded-xl border-gray-200 shadow-sm focus:border-[#C8A96A] focus:ring focus:ring-[#C8A96A] focus:ring-opacity-30 pl-11 pr-12 py-3 text-sm text-gray-900 bg-white transition-all duration-300"
                                placeholder="Ulangi password..." required>
                            <button type="button" @click="showConfirm = !showConfirm"
                                class="absolute inset-y-0 right-0 flex items-center pr-4 text-gray-400 hover:text-gray-600 focus:outline-none">
                                <svg x-show="!showConfirm" class="w-5 h-5" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <svg x-show="showConfirm" class="w-5 h-5" style="display: none;" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                </svg>
                            </button>
                        </div>
                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1" />
                    </div>

                    <!-- Buttons -->
                    <div class="flex flex-col sm:flex-row gap-4 pt-2">
                        <a href="{{ route('login') }}"
                            class="w-full sm:w-1/2 px-6 py-3 bg-white border border-[#1F2A1D] text-[#1F2A1D] font-medium rounded-xl hover:bg-[#1F2A1D] hover:text-white transition-all duration-300 hover:scale-[1.02] active:scale-[0.98] text-center shadow-sm">
                            Sudah Punya Akun?
                        </a>
                        <button type="submit"
                            class="w-full sm:w-1/2 px-6 py-3 bg-[#1F2A1D] border border-transparent text-white font-medium rounded-xl hover:bg-[#2c3d29] transition-all duration-300 hover:scale-[1.02] active:scale-[0.98] shadow-md flex justify-center items-center gap-2">
                            Daftar Sekarang
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Right Section (55%) -->
        <div
            class="w-full lg:w-[55%] bg-gradient-to-br from-[#1F2A1D] to-[#2C3B28] flex flex-col items-center justify-center p-12 lg:p-20 relative overflow-hidden min-h-[40vh] lg:min-h-0">
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
                    <p class="text-gray-300 text-sm sm:text-base italic leading-relaxed">Bergabunglah dan nikmati
                        kemudahan booking layanan premium Hero Barbershop.</p>
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
