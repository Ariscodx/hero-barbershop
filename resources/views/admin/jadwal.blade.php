<x-templates.admin-layout>
    <style>
        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background-color: #E5E7EB;
            border-radius: 20px;
        }
    </style>

    <div class="space-y-6 max-w-full">

        {{-- Breadcrumb --}}
        <div class="text-sm font-medium tracking-wide flex items-center gap-2 text-gray-400">
            <a href="{{ route('admin.dashboard') }}" class="hover:text-gray-600 transition-colors">Dashboard</a>
            <span>/</span>
            <span class="text-gray-500">Manajemen Operasional</span>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex flex-col md:flex-row md:items-center justify-between gap-5">
            <div class="flex flex-col md:flex-row md:items-center gap-5">
                <div class="w-16 h-16 rounded-2xl bg-[#FDF8F3] border border-[#F2E5D5] flex items-center justify-center flex-shrink-0">
                    <svg class="w-8 h-8 text-[#D4A373]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                <div>
                    <h1 class="text-[22px] font-bold text-gray-900 tracking-tight">Kelola Jadwal Operasional</h1>
                    <p class="text-sm text-gray-500 mt-1">Atur hari operasional Hero Barbershop beserta jam buka dan jam tutup layanan.</p>
                </div>
            </div>
        </div>

        {{-- Toast / Validation --}}
        @if (session('success'))
            <div x-data="{ show: true }" x-show="show" class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg flex items-center justify-between mb-4">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                    <span class="text-sm font-medium">✅ {{ session('success') }}</span>
                </div>
                <button @click="show = false" class="text-green-500 hover:text-green-700">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>
        @endif
        @if ($errors->any() || session('error'))
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-4">
                <div class="flex items-center gap-2 mb-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    <span class="font-bold text-sm">Gagal menyimpan jadwal!</span>
                </div>
                <ul class="list-disc pl-5 text-sm space-y-1">
                    @if (session('error'))<li>{{ session('error') }}</li>@endif
                    @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                </ul>
            </div>
        @endif

        {{-- Main Form & Table --}}
        <form action="{{ route('admin.jadwal.update') }}" method="POST">
            @csrf
            @method('PUT')

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-x-auto">
                <table class="w-full text-left border-collapse min-w-[800px]">
                    <thead>
                        <tr class="border-b border-gray-100">
                            <th class="px-6 py-4 text-[11px] font-bold text-gray-400 uppercase tracking-wider">Hari</th>

                            <th class="px-6 py-4 text-[11px] font-bold text-gray-400 uppercase tracking-wider">Jam Buka</th>
                            <th class="px-6 py-4 text-[11px] font-bold text-gray-400 uppercase tracking-wider">Jam Tutup</th>
                            <th class="px-6 py-4 text-[11px] font-bold text-gray-400 uppercase tracking-wider">Status Operasional</th>
                            <th class="px-6 py-4 text-[11px] font-bold text-gray-400 uppercase tracking-wider text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 text-sm">
                        @foreach ($jadwals as $index => $day)
                            <tr class="hover:bg-gray-50/50 bg-white" x-data="jadwalRow(
                                {{ $day->status === 'buka' ? 'true' : 'false' }},
                                '{{ $day->jam_buka ? substr($day->jam_buka, 0, 5) : '' }}',
                                '{{ $day->jam_tutup ? substr($day->jam_tutup, 0, 5) : '' }}'
                            )">
                                {{-- Hidden Inputs for form submission --}}
                                <input type="hidden" name="jadwal[{{ $index }}][hari]" value="{{ $day->hari }}">
                                <input type="hidden" name="jadwal[{{ $index }}][jam_buka]" :value="isOpen ? jamBuka : ''">
                                <input type="hidden" name="jadwal[{{ $index }}][jam_tutup]" :value="isOpen ? jamTutup : ''">
                                <input type="hidden" name="jadwal[{{ $index }}][status]" :value="isOpen ? 'buka' : 'tutup'">

                                {{-- Hari --}}
                                <td class="px-6 py-5 font-semibold text-gray-800">
                                    {{ $day->hari }}
                                </td>

                                {{-- Jam Buka --}}
                                <td class="px-6 py-5">
                                    <div class="relative w-40" x-data="timePicker('jamBuka', 'jamTutup', 'buka')" @click.away="closePicker()">
                                        <div x-ref="btn" @click="openPicker()"
                                                :class="editing ? 'bg-white border-gray-300 ring-1 ring-gray-100 shadow-sm cursor-pointer' : 'bg-gray-50/50 border-gray-100 text-gray-500 cursor-not-allowed opacity-80'"
                                                class="flex items-center justify-between w-full border rounded-full px-4 py-2 text-sm text-gray-700 transition-colors">
                                            <span x-text="formatJam(jamBuka) || '--:-- --'"></span>
                                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </div>
                                        
                                        <!-- Backdrop for mobile -->
                                        <div x-show="open" x-transition.opacity style="display: none;" class="fixed inset-0 bg-black/40 z-[9998] md:hidden"></div>

                                        <div x-show="open" x-transition.opacity.scale.95.duration.200ms style="display: none;" :style="pickerStyle"
                                             class="fixed z-[9999] bg-white rounded-xl shadow-xl border border-gray-200 p-4 w-[280px] flex flex-col gap-4">
                                            
                                            <div class="flex gap-1 h-[220px]">
                                                <!-- Hours -->
                                                <div x-ref="hScroll" class="w-1/2 overflow-y-auto space-y-1 pr-1 custom-scrollbar">
                                                    <template x-for="h in Array.from({length: 24}, (_, i) => i.toString().padStart(2, '0'))">
                                                        <div @click="tempH = h"
                                                             :class="tempH === h ? 'bg-[#C8A96A] text-white font-semibold active-item' : 'text-gray-700 hover:bg-[#E8F5E9]/50 cursor-pointer'"
                                                             class="px-2 py-2 text-center rounded-lg text-sm transition-colors" x-text="h"></div>
                                                    </template>
                                                </div>
                                                <!-- Minutes -->
                                                <div x-ref="mScroll" class="w-1/2 overflow-y-auto space-y-1 px-1 custom-scrollbar border-l border-gray-100">
                                                    <template x-for="m in Array.from({length: 60}, (_, i) => i.toString().padStart(2, '0'))">
                                                        <div @click="tempM = m"
                                                             :class="tempM === m ? 'bg-[#C8A96A] text-white font-semibold active-item' : 'text-gray-700 hover:bg-[#E8F5E9]/50 cursor-pointer'"
                                                             class="px-2 py-2 text-center rounded-lg text-sm transition-colors" x-text="m"></div>
                                                    </template>
                                                </div>
                                            </div>
                                
                                            <!-- Actions -->
                                            <div class="flex gap-2 justify-end pt-3 border-t border-gray-100">
                                                <button type="button" @click="closePicker()" class="px-4 py-2 text-xs font-semibold text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors">Batal</button>
                                                <button type="button" @click="savePicker()" class="px-4 py-2 text-xs font-semibold text-white bg-[#1F2B1F] hover:bg-black rounded-lg transition-colors shadow-sm">Pilih</button>
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                {{-- Jam Tutup --}}
                                <td class="px-6 py-5">
                                    <div class="relative w-40" x-data="timePicker('jamTutup', 'jamBuka', 'tutup')" @click.away="closePicker()">
                                        <div x-ref="btn" @click="openPicker()"
                                                :class="editing ? 'bg-white border-gray-300 ring-1 ring-gray-100 shadow-sm cursor-pointer' : 'bg-gray-50/50 border-gray-100 text-gray-500 cursor-not-allowed opacity-80'"
                                                class="flex items-center justify-between w-full border rounded-full px-4 py-2 text-sm text-gray-700 transition-colors">
                                            <span x-text="formatJam(jamTutup) || '--:-- --'"></span>
                                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </div>
                                        
                                        <!-- Backdrop for mobile -->
                                        <div x-show="open" x-transition.opacity style="display: none;" class="fixed inset-0 bg-black/40 z-[9998] md:hidden"></div>

                                        <div x-show="open" x-transition.opacity.scale.95.duration.200ms style="display: none;" :style="pickerStyle"
                                             class="fixed z-[9999] bg-white rounded-xl shadow-xl border border-gray-200 p-4 w-[280px] flex flex-col gap-4">
                                            
                                            <div class="flex gap-1 h-[220px]">
                                                <!-- Hours -->
                                                <div x-ref="hScroll" class="w-1/2 overflow-y-auto space-y-1 pr-1 custom-scrollbar">
                                                    <template x-for="h in Array.from({length: 24}, (_, i) => i.toString().padStart(2, '0'))">
                                                        <div @click="tempH = h"
                                                             :class="tempH === h ? 'bg-[#C8A96A] text-white font-semibold active-item' : 'text-gray-700 hover:bg-[#E8F5E9]/50 cursor-pointer'"
                                                             class="px-2 py-2 text-center rounded-lg text-sm transition-colors" x-text="h"></div>
                                                    </template>
                                                </div>
                                                <!-- Minutes -->
                                                <div x-ref="mScroll" class="w-1/2 overflow-y-auto space-y-1 px-1 custom-scrollbar border-l border-gray-100">
                                                    <template x-for="m in Array.from({length: 60}, (_, i) => i.toString().padStart(2, '0'))">
                                                        <div @click="tempM = m"
                                                             :class="tempM === m ? 'bg-[#C8A96A] text-white font-semibold active-item' : 'text-gray-700 hover:bg-[#E8F5E9]/50 cursor-pointer'"
                                                             class="px-2 py-2 text-center rounded-lg text-sm transition-colors" x-text="m"></div>
                                                    </template>
                                                </div>
                                            </div>
                                
                                            <!-- Actions -->
                                            <div class="flex gap-2 justify-end pt-3 border-t border-gray-100">
                                                <button type="button" @click="closePicker()" class="px-4 py-2 text-xs font-semibold text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors">Batal</button>
                                                <button type="button" @click="savePicker()" class="px-4 py-2 text-xs font-semibold text-white bg-[#1F2B1F] hover:bg-black rounded-lg transition-colors shadow-sm">Pilih</button>
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                {{-- Status Operasional --}}
                                <td class="px-6 py-5">
                                    <div class="flex items-center gap-3">
                                        <button type="button" @click="isOpen = !isOpen"
                                            :class="isOpen ? 'bg-[#10B981]' : 'bg-gray-200'"
                                            class="relative inline-flex h-5 w-9 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none">
                                            <span :class="isOpen ? 'translate-x-4' : 'translate-x-0'"
                                                class="pointer-events-none inline-block h-4 w-4 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"></span>
                                        </button>
                                        <span class="text-sm font-semibold"
                                            :class="isOpen ? 'text-[#10B981]' : 'text-gray-400'"
                                            x-text="isOpen ? 'Buka' : 'Tutup'"></span>
                                        
                                    </div>
                                </td>

                                {{-- Aksi --}}
                                <td class="px-6 py-5 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <button type="button" @click="editing = !editing" title="Edit Baris"
                                            class="w-[30px] h-[30px] rounded-md flex items-center justify-center transition-colors shadow-sm"
                                            :class="editing ? 'bg-blue-500 text-white hover:bg-blue-600' : 'bg-[#EBF5FF] text-[#3B82F6] hover:bg-blue-100'">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                            </svg>
                                        </button>
                                        <button type="button" @click="reset()" title="Reset ke Nilai Awal"
                                            class="w-[30px] h-[30px] rounded-md bg-[#FEE2E2] text-[#EF4444] hover:bg-red-200 flex items-center justify-center transition-colors shadow-sm">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-6 flex justify-end">
                <button type="submit"
                    class="bg-[#1C2434] hover:bg-black text-white px-5 py-2.5 rounded-lg flex items-center gap-2 font-medium text-sm transition-colors shadow-md">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                    </svg>
                    Simpan Perubahan
                </button>
            </div>

        </form>

    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            // Komponen Alpine untuk mengelola state setiap baris tabel (Senin - Minggu)
            Alpine.data('jadwalRow', (initIsOpen, initJamBuka, initJamTutup) => ({
                editing: false,         // Mode edit (true = tombol simpan aktif)
                isOpen: initIsOpen,     // Toggle switch Buka/Tutup
                jamBuka: initJamBuka,   // Simpan jam buka (format 24H)
                jamTutup: initJamTutup, // Simpan jam tutup (format 24H)
                
                // Menyimpan data orisinal dari server untuk fungsi tombol Reset (Merah)
                origBuka: initJamBuka,
                origTutup: initJamTutup,
                origStatus: initIsOpen,

                // Fungsi mengembalikan baris ke kondisi semula sebelum di-edit
                reset() {
                    this.jamBuka = this.origBuka;
                    this.jamTutup = this.origTutup;
                    this.isOpen = this.origStatus;
                    this.editing = false;
                },

                // Fungsi konversi format jam backend (contoh: 13:00) ke format tampilan UI (13:00 Siang)
                formatJam(val) {
                    if (!val) return ''; // Jika data kosong (karena hari tutup), kembalikan string kosong agar tampil '--:--'
                    const [hStr, m] = val.split(':');
                    const h = parseInt(hStr, 10);
                    
                    let ket = 'Malam';
                    if (h >= 4 && h < 11) ket = 'Pagi';
                    else if (h >= 11 && h < 15) ket = 'Siang';
                    else if (h >= 15 && h < 19) ket = 'Sore';
                    
                    return hStr.padStart(2, '0') + ':' + m + ' ' + ket;
                }
            }));

            // Komponen Custom Time Picker (Dropdown waktu yang dibuat tanpa jquery)
            Alpine.data('timePicker', (field, otherField, type) => ({
                open: false,
                pickerStyle: '', // Style posisi dinamis agar tidak terpotong container tabel
                tempH: '09',   // Jam sementara di dropdown (24 format)
                tempM: '00',   // Menit sementara (00-59)
            
                // Dipanggil saat admin mengklik input jam
                openPicker() {
                    if (!this.editing) return; // Kunci picker jika belum masuk mode edit (tombol pensil)
                    
                    let current = this[field];
                    if (current) {
                        // Jika sudah ada jam yang diset, pisahkan jam dan menit
                        let [h, m] = current.split(':');
                        this.tempH = h.padStart(2, '0');
                        this.tempM = m;
                    } else {
                        // Jika kosong (default ketika hari buka), set ke jam 9 pagi atau 9 malam
                        this.tempH = type === 'buka' ? '09' : '21';
                        this.tempM = '00';
                    }
            
                    this.open = true;
            
                    // Tunggu DOM selesai merender list, kalkulasi posisi, lalu auto-scroll
                    this.$nextTick(() => {
                        // Responsivitas: Sesuaikan tampilan berdasarkan ukuran layar
                        if (window.innerWidth < 768) {
                            // Mode Mobile: Tampilkan di tengah layar seperti Modal Pop-up
                            this.pickerStyle = `top: 50%; left: 50%; transform: translate(-50%, -50%);`;
                        } else {
                            // Mode Desktop: Kalkulasi kordinat tombol agar popover muncul persis di bawahnya (Fixed Positioning)
                            const btn = this.$refs.btn;
                            if (btn) {
                                const rect = btn.getBoundingClientRect();
                                let topPos = rect.bottom + 8; // Jarak 8px dari bawah input
                                let leftPos = rect.left + (rect.width / 2); // Rata tengah input
                                
                                // Deteksi cerdas: Jika popover akan terpotong batas bawah layar window,
                                // tampilkan popover ke arah atas (di atas tombol)
                                const popoverHeight = 320;
                                if (topPos + popoverHeight > window.innerHeight) {
                                    topPos = rect.top - popoverHeight - 8;
                                }
                                
                                this.pickerStyle = `top: ${topPos}px; left: ${leftPos}px; transform: translateX(-50%);`;
                            }
                        }

                        const hScroll = this.$refs.hScroll;
                        const mScroll = this.$refs.mScroll;
                        const hActive = hScroll.querySelector('.active-item');
                        const mActive = mScroll.querySelector('.active-item');
                        
                        if (hActive) {
                            hScroll.scrollTop = hActive.offsetTop - (hScroll.clientHeight / 2) + (hActive.clientHeight / 2);
                        }
                        if (mActive) {
                            mScroll.scrollTop = mActive.offsetTop - (mScroll.clientHeight / 2) + (mActive.clientHeight / 2);
                        }
                    });
                },
            
                closePicker() {
                    this.open = false;
                },
            
                // Dipanggil saat admin mengklik tombol "Pilih" berwarna hijau
                savePicker() {
                    // Simpan format 24 jam (untuk dikirim ke backend)
                    let selected = `${this.tempH.padStart(2, '0')}:${this.tempM}`;
            
                    // Simpan perubahan ke state jadwalRow
                    this[field] = selected;
                    this.closePicker();
                },
            
                // Menampilkan Toast notifikasi validasi
                showCustomToast(msg) {
                    let toastEl = document.getElementById('custom-toast');
                    if (!toastEl) {
                        toastEl = document.createElement('div');
                        toastEl.id = 'custom-toast';
                        toastEl.className = 'fixed top-10 left-1/2 -translate-x-1/2 z-[9999] bg-red-50 border border-red-200 text-red-700 px-6 py-3 rounded-lg flex items-center gap-3 shadow-xl transition-all duration-300 opacity-0 transform -translate-y-4';
                        toastEl.innerHTML = `<svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg><span class="font-medium text-sm" id="custom-toast-msg"></span>`;
                        document.body.appendChild(toastEl);
                    }
                    document.getElementById('custom-toast-msg').innerText = msg;
                    
                    // Show
                    setTimeout(() => {
                        toastEl.classList.remove('opacity-0', '-translate-y-4');
                        toastEl.classList.add('opacity-100', 'translate-y-0');
                    }, 10);
                    
                    // Hide
                    setTimeout(() => {
                        toastEl.classList.remove('opacity-100', 'translate-y-0');
                        toastEl.classList.add('opacity-0', '-translate-y-4');
                    }, 3000);
                }
            }));
        });
    </script>
</x-templates.admin-layout>
