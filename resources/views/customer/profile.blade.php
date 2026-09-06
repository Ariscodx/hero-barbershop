<x-templates.customer-layout>
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        
        <!-- Header -->
        <div class="flex items-center gap-4 mb-8">
            <div class="w-16 h-16 rounded-2xl flex items-center justify-center bg-[#1F2A1D]/10">
                <svg class="w-8 h-8 text-[#1F2A1D]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
            </div>
            <div>
                <h1 class="text-3xl font-bold text-[#1F2A1D]">Profil Saya</h1>
                <p class="text-gray-500 mt-1">Perbarui informasi profil dan pengaturan keamanan akun Anda.</p>
            </div>
        </div>

        <!-- Notifikasi Sukses -->
        @if(session('success'))
            <div class="mb-6 p-4 rounded-xl bg-green-50 border border-green-200 text-green-700 flex items-center gap-3 animate-fade-in-down">
                <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span class="font-medium">{{ session('success') }}</span>
            </div>
        @endif

        <!-- Notifikasi Error -->
        @if($errors->any())
            <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200 text-red-700 animate-fade-in-down">
                <div class="flex items-center gap-3 mb-2">
                    <svg class="w-5 h-5 text-red-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span class="font-bold">Ada kesalahan pada input:</span>
                </div>
                <ul class="list-disc list-inside text-sm ml-2 text-red-600">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Form Container -->
        <div class="bg-white rounded-3xl shadow-sm border border-[#E5E7EB] overflow-hidden">
            <form action="{{ route('customer.profile.update') }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="p-8 space-y-10">
                    
                    <!-- Bagian Informasi Pribadi -->
                    <div>
                        <h2 class="text-lg font-semibold text-[#1F2A1D] mb-5 flex items-center gap-2 border-b border-gray-100 pb-3">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path></svg>
                            Informasi Pribadi
                        </h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Nama Lengkap -->
                            <div>
                                <label for="nama" class="block text-sm font-medium text-gray-700 mb-1.5">Nama Lengkap / Username</label>
                                <input type="text" id="nama" name="nama" value="{{ old('nama', $pelanggan->nama) }}" required
                                    class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-[#1F2A1D] focus:ring-1 focus:ring-[#1F2A1D] outline-none transition-all duration-200">
                            </div>

                            <!-- Email (Readonly) -->
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-1.5">Alamat Email <span class="text-xs text-gray-400 font-normal ml-1">(Tidak dapat diubah)</span></label>
                                <input type="email" id="email" value="{{ $pelanggan->email }}" readonly
                                    class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 text-gray-500 cursor-not-allowed outline-none select-none">
                            </div>

                            <!-- Nomor Telepon -->
                            <div>
                                <label for="no_tlp" class="block text-sm font-medium text-gray-700 mb-1.5">Nomor Telepon / WhatsApp</label>
                                <input type="text" id="no_tlp" name="no_tlp" value="{{ old('no_tlp', $pelanggan->no_tlp) }}" required
                                    class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-[#1F2A1D] focus:ring-1 focus:ring-[#1F2A1D] outline-none transition-all duration-200">
                            </div>
                            
                            <!-- Alamat -->
                            <div class="md:col-span-2">
                                <label for="alamat" class="block text-sm font-medium text-gray-700 mb-1.5">Alamat Lengkap</label>
                                <textarea id="alamat" name="alamat" rows="3" required
                                    class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-[#1F2A1D] focus:ring-1 focus:ring-[#1F2A1D] outline-none transition-all duration-200">{{ old('alamat', $pelanggan->alamat) }}</textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Bagian Keamanan Akun -->
                    <div>
                        <h2 class="text-lg font-semibold text-[#1F2A1D] mb-1 flex items-center gap-2">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                            Keamanan Akun
                        </h2>
                        <p class="text-sm text-gray-500 mb-5 border-b border-gray-100 pb-3">Kosongkan kolom kata sandi jika Anda tidak ingin mengubahnya.</p>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="md:col-span-2">
                                <div class="flex justify-between items-center w-full md:w-1/2 mb-1.5">
                                    <label for="current_password" class="block text-sm font-medium text-gray-700">Password Saat Ini</label>
                                    <a href="{{ route('password.request') }}" class="text-xs font-medium text-[#C8A96A] hover:text-[#1F2A1D] transition-colors">Lupa Password?</a>
                                </div>
                                <div class="relative w-full md:w-1/2">
                                    <input type="password" id="current_password" name="current_password" placeholder="Masukkan password saat ini"
                                        class="w-full px-4 py-3 pr-12 rounded-xl border border-gray-300 focus:border-[#1F2A1D] focus:ring-1 focus:ring-[#1F2A1D] outline-none transition-all duration-200">
                                    <button type="button" onclick="togglePassword('current_password')" class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-500 hover:text-[#1F2A1D] transition-colors focus:outline-none">
                                        <svg id="eye-icon-current_password" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            <!-- Password Baru -->
                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-700 mb-1.5">Password Baru</label>
                                <div class="relative">
                                    <input type="password" id="password" name="password" placeholder="Minimal 8 karakter"
                                        class="w-full px-4 py-3 pr-12 rounded-xl border border-gray-300 focus:border-[#1F2A1D] focus:ring-1 focus:ring-[#1F2A1D] outline-none transition-all duration-200">
                                    <button type="button" onclick="togglePassword('password')" class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-500 hover:text-[#1F2A1D] transition-colors focus:outline-none">
                                        <svg id="eye-icon-password" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            <!-- Konfirmasi Password Baru -->
                            <div>
                                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1.5">Konfirmasi Password Baru</label>
                                <div class="relative">
                                    <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Ulangi password baru"
                                        class="w-full px-4 py-3 pr-12 rounded-xl border border-gray-300 focus:border-[#1F2A1D] focus:ring-1 focus:ring-[#1F2A1D] outline-none transition-all duration-200">
                                    <button type="button" onclick="togglePassword('password_confirmation')" class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-500 hover:text-[#1F2A1D] transition-colors focus:outline-none">
                                        <svg id="eye-icon-password_confirmation" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer / Actions -->
                <div class="px-8 py-5 bg-gray-50/50 border-t border-gray-100 flex items-center justify-end gap-3">
                    <a href="{{ route('customer.dashboard') }}" class="px-6 py-3 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-xl hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-200 transition-all">
                        Kembali
                    </a>
                    <button type="submit" class="px-6 py-3 text-sm font-medium text-[#D4AF37] bg-[#1F2A1D] rounded-xl hover:bg-[#2A3B27] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#1F2A1D] transition-all flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <script>
        function togglePassword(inputId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById('eye-icon-' + inputId);
            if (input.type === 'password') {
                input.type = 'text';
                icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />';
            } else {
                input.type = 'password';
                icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />';
            }
        }
    </script>
</x-templates.customer-layout>
