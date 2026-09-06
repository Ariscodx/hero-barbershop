<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Reset Password - Hero Barbershop</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=poppins:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-poppins bg-[#F8F8F8] text-gray-900 antialiased min-h-screen flex flex-col">

    <!-- Navbar -->
    <nav class="sticky top-0 z-50 bg-[#F8F8F8]/90 backdrop-blur-md border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <!-- Logo -->
                <a href="/" class="flex items-center gap-3 sm:gap-4 group">
                    <div
                        class="w-[60px] h-[60px] rounded-full bg-[#1F2A1D] flex items-center justify-center border-2 border-[#D4B06A]/30 overflow-hidden shadow-sm group-hover:opacity-90 transition-opacity">
                        <img src="{{ asset('images/logo_hero_barbershop.png') }}" alt="Hero Barbershop"
                            class="object-contain w-[50px] h-[50px]">
                    </div>
                    <span class="font-bold text-lg tracking-wider text-[#1F2A1D]">HERO BARBERSHOP</span>
                </a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="flex-1 flex flex-col items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="w-full max-w-md bg-white p-8 rounded-2xl shadow-sm border border-[#E5E7EB]">
            <div class="mb-8 text-center">
                <div class="w-16 h-16 rounded-2xl flex items-center justify-center mx-auto mb-4"
                    style="background: rgba(31,42,29,0.08);">
                    <svg class="w-8 h-8 text-[#1F2A1D]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                        </path>
                    </svg>
                </div>
                <h1 class="text-2xl font-bold text-[#1F2A1D] mb-2">Buat Password Baru</h1>
                <p class="text-sm text-gray-500">
                    Silakan masukkan email Anda dan password baru yang ingin Anda gunakan.
                </p>
            </div>

            @if ($errors->any())
                <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200 text-red-700 text-sm">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('password.store') }}" class="space-y-6">
                @csrf

                <!-- Password Reset Token -->
                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                <div>
                    <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">Alamat Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email', $request->email) }}"
                        required readonly
                        class="w-full rounded-xl border-gray-200 bg-gray-50 text-gray-500 shadow-sm px-4 py-3 text-sm">
                </div>

                <div>
                    <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">Password Baru</label>
                    <div class="relative">
                        <input type="password" id="password" name="password" required autofocus
                            class="w-full rounded-xl border-gray-300 shadow-sm focus:border-[#1F2A1D] focus:ring focus:ring-[#1F2A1D] focus:ring-opacity-20 px-4 py-3 pr-12 text-sm transition-all"
                            placeholder="Minimal 8 karakter...">
                        <button type="button" onclick="togglePassword('password')"
                            class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-500 hover:text-[#1F2A1D] transition-colors focus:outline-none">
                            <svg id="eye-icon-password" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </button>
                    </div>
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-2">Konfirmasi
                        Password Baru</label>
                    <div class="relative">
                        <input type="password" id="password_confirmation" name="password_confirmation" required
                            class="w-full rounded-xl border-gray-300 shadow-sm focus:border-[#1F2A1D] focus:ring focus:ring-[#1F2A1D] focus:ring-opacity-20 px-4 py-3 pr-12 text-sm transition-all"
                            placeholder="Ulangi password baru...">
                        <button type="button" onclick="togglePassword('password_confirmation')"
                            class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-500 hover:text-[#1F2A1D] transition-colors focus:outline-none">
                            <svg id="eye-icon-password_confirmation" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="pt-2">
                    <button type="submit"
                        class="w-full px-6 py-3 bg-[#1F2A1D] text-white text-sm font-medium rounded-xl hover:bg-[#2c3d29] transition-all duration-300 shadow-md">
                        Simpan Password Baru
                    </button>
                </div>
            </form>
        </div>
    </main>

    <script>
        function togglePassword(inputId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById('eye-icon-' + inputId);
            if (input.type === 'password') {
                input.type = 'text';
                icon.innerHTML =
                    '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />';
            } else {
                input.type = 'password';
                icon.innerHTML =
                    '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />';
            }
        }
    </script>
</body>

</html>
