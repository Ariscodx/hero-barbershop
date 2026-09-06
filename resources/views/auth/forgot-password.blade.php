<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Lupa Password - Hero Barbershop</title>

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
                            d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z">
                        </path>
                    </svg>
                </div>
                <h1 class="text-2xl font-bold text-[#1F2A1D] mb-2">Lupa Password?</h1>
                <p class="text-sm text-gray-500">
                    Tidak masalah. Beri tahu kami alamat email Anda dan kami akan mengirimkan tautan reset password yang
                    memungkinkan Anda memilih password baru.
                </p>
            </div>

            @if (session('status'))
                <div class="mb-6 p-4 rounded-xl bg-green-50 border border-green-200 text-green-700 text-sm font-medium">
                    {{ session('status') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200 text-red-700 text-sm">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
                @csrf

                <div>
                    <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">Alamat Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus
                        class="w-full rounded-xl border-gray-300 shadow-sm focus:border-[#1F2A1D] focus:ring focus:ring-[#1F2A1D] focus:ring-opacity-20 px-4 py-3 text-sm transition-all"
                        placeholder="Masukkan email Anda...">
                </div>

                <div class="flex items-center justify-between pt-2">
                    <a href="{{ route('login') }}" class="text-sm text-gray-500 hover:text-[#1F2A1D] transition-colors">
                        &larr; Kembali
                    </a>
                    <button type="submit"
                        class="px-6 py-3 bg-[#1F2A1D] text-white text-sm font-medium rounded-xl hover:bg-[#2c3d29] transition-all duration-300 shadow-md">
                        Kirim Link Reset
                    </button>
                </div>
            </form>
        </div>
    </main>
</body>

</html>
