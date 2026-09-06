<x-templates.admin-layout>
    <div class="space-y-6 pb-10 max-w-4xl mx-auto">
        
        <!-- Breadcrumb -->
        <div class="flex items-center gap-2 text-sm text-gray-500 font-medium">
            <a href="{{ route('admin.dashboard') }}" class="hover:text-primary transition-colors">Dashboard</a>
            <span>/</span>
            <span class="text-brand-text font-bold">Profil Admin</span>
        </div>

        <!-- Header Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 flex items-start gap-4">
            <div class="p-3 bg-yellow-50 text-accent rounded-xl shadow-sm">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div>
                <h2 class="text-xl font-bold text-brand-text font-poppins">Profil Admin</h2>
                <p class="text-sm text-gray-500 mt-1">Kelola informasi akun administrator Hero Barbershop.</p>
            </div>
        </div>

        <!-- Success Message -->
        @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl shadow-sm relative flex items-start gap-3" role="alert">
            <svg class="w-5 h-5 mt-0.5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <div>
                <span class="block sm:inline font-medium">{{ session('success') }}</span>
            </div>
        </div>
        @endif

        <!-- Profile Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 flex flex-col items-center justify-center text-center">
            <div class="w-24 h-24 bg-gray-50 rounded-full flex items-center justify-center border-4 border-white shadow-md mb-4 overflow-hidden relative ring-1 ring-gray-100">
                <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
            </div>
            <h3 class="text-2xl font-bold text-brand-text font-poppins">{{ $admin->nama }}</h3>
            <p class="text-gray-500 mb-4">{{ $admin->email }}</p>
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-[#1F2A1D]/10 text-[#1F2A1D] border border-[#1F2A1D]/20 uppercase tracking-wider">
                Administrator
            </span>
        </div>

        <!-- Form Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="p-6 border-b border-gray-100">
                <h3 class="text-lg font-bold text-brand-text font-poppins">Informasi Personal</h3>
                <p class="text-sm text-gray-500 mt-1">Perbarui data diri dan kata sandi Anda.</p>
            </div>
            <div class="p-6 md:p-8">
                <form action="{{ route('admin.profil.update') }}" method="POST" class="space-y-8">
                    @csrf
                    @method('PUT')
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Nama Lengkap -->
                        <div class="space-y-2">
                            <label for="nama" class="block text-sm font-semibold text-gray-700">Nama Lengkap</label>
                            <input type="text" id="nama" name="nama" value="{{ old('nama', $admin->nama) }}" class="w-full rounded-lg shadow-sm focus:ring-opacity-50 text-sm text-gray-900 bg-white px-4 py-2.5 transition-colors {{ $errors->has('nama') ? 'border-red-300 focus:border-red-500 focus:ring-red-500' : 'border-gray-200 focus:border-primary focus:ring-primary' }}">
                            @error('nama')
                                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Email -->
                        <div class="space-y-2">
                            <label for="email" class="block text-sm font-semibold text-gray-700">Alamat Email</label>
                            <input type="email" id="email" name="email" value="{{ old('email', $admin->email) }}" class="w-full rounded-lg shadow-sm focus:ring-opacity-50 text-sm text-gray-900 bg-white px-4 py-2.5 transition-colors {{ $errors->has('email') ? 'border-red-300 focus:border-red-500 focus:ring-red-500' : 'border-gray-200 focus:border-primary focus:ring-primary' }}">
                            @error('email')
                                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <hr class="border-gray-100">

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Password Lama -->
                        <div class="space-y-2" x-data="{ show: false }">
                            <label for="current_password" class="block text-sm font-semibold text-gray-700">Password Lama</label>
                            <div class="relative">
                                <input :type="show ? 'text' : 'password'" id="current_password" name="current_password" placeholder="********" class="w-full rounded-lg shadow-sm focus:ring-opacity-50 text-sm text-gray-900 bg-white px-4 py-2.5 pr-10 transition-colors {{ $errors->has('current_password') ? 'border-red-300 focus:border-red-500 focus:ring-red-500' : 'border-gray-200 focus:border-primary focus:ring-primary' }}">
                                <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600 focus:outline-none">
                                    <svg x-show="!show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                    <svg x-show="show" style="display: none;" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>
                                    </svg>
                                </button>
                            </div>
                            @error('current_password')
                                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Password Baru -->
                        <div class="space-y-2" x-data="{ show: false }">
                            <label for="password" class="block text-sm font-semibold text-gray-700">Password Baru</label>
                            <div class="relative">
                                <input :type="show ? 'text' : 'password'" id="password" name="password" placeholder="********" class="w-full rounded-lg shadow-sm focus:ring-opacity-50 text-sm text-gray-900 bg-white px-4 py-2.5 pr-10 transition-colors {{ $errors->has('password') ? 'border-red-300 focus:border-red-500 focus:ring-red-500' : 'border-gray-200 focus:border-primary focus:ring-primary' }}">
                                <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600 focus:outline-none">
                                    <svg x-show="!show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                    <svg x-show="show" style="display: none;" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>
                                    </svg>
                                </button>
                            </div>
                            @error('password')
                                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Konfirmasi Password Baru -->
                        <div class="space-y-2" x-data="{ show: false }">
                            <label for="password_confirmation" class="block text-sm font-semibold text-gray-700">Konfirmasi Password Baru</label>
                            <div class="relative">
                                <input :type="show ? 'text' : 'password'" id="password_confirmation" name="password_confirmation" placeholder="********" class="w-full rounded-lg border-gray-200 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50 text-sm text-gray-900 bg-white px-4 py-2.5 pr-10 transition-colors">
                                <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600 focus:outline-none">
                                    <svg x-show="!show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                    <svg x-show="show" style="display: none;" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Information Card -->
                    <div class="mt-8 bg-yellow-50 border border-yellow-200/70 rounded-xl p-4 flex items-start gap-3 shadow-sm">
                        <div class="mt-0.5 text-yellow-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-yellow-800 font-medium leading-relaxed">Pastikan informasi akun yang diperbarui sudah benar sebelum menyimpan perubahan.</p>
                        </div>
                    </div>

                    <!-- Buttons -->
                    <div class="flex flex-col sm:flex-row justify-end items-center gap-3 pt-6 border-t border-gray-100">
                        <a href="{{ route('admin.dashboard') }}" class="w-full sm:w-auto px-6 py-2.5 bg-white border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-200 focus:ring-offset-2 transition-colors shadow-sm text-center">
                            Batal
                        </a>
                        <button type="submit" class="w-full sm:w-auto inline-flex justify-center items-center gap-2 px-6 py-2.5 bg-[#1F2A1D] text-white text-sm font-medium rounded-lg hover:bg-[#2c3d29] focus:outline-none focus:ring-2 focus:ring-[#1F2A1D] focus:ring-offset-2 transition-colors shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path>
                            </svg>
                            Simpan Perubahan
                        </button>
                    </div>

                </form>
            </div>
        </div>

    </div>
</x-templates.admin-layout>
