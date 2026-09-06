@extends('customer.layouts.app')

@section('title', config('app.name', 'Hero Barbershop') . ' - Status Pesanan')
@section('description', 'Pantau perkembangan seluruh reservasi Anda di Hero Barbershop secara real-time.')

@push('head')
<style>
    /* ── Status Pesanan Page ─────────────────── */
    .status-hero {
        background: #fff;
        border-radius: 1.5rem;
        box-shadow: 0 4px 24px rgba(31,42,29,0.08);
        border: 1px solid #E5E7EB;
    }
    .stat-card {
        background: #fff;
        border-radius: 1rem;
        border: 1px solid #E5E7EB;
        box-shadow: 0 2px 10px rgba(31,42,29,0.05);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(31,42,29,0.1);
    }
    .booking-card {
        background: #fff;
        border-radius: 1rem;
        border: 2px solid #E5E7EB;
        box-shadow: 0 2px 10px rgba(31,42,29,0.05);
        transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
        overflow: hidden;
    }
    .booking-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 16px 40px rgba(31,42,29,0.12);
        border-color: rgba(212,175,99,0.4);
    }
    .gold-icon-wrap {
        background: linear-gradient(135deg, rgba(212,176,106,0.15), rgba(212,176,106,0.08));
        border: 1px solid rgba(212,176,106,0.25);
    }
    /* Modal */
    .modal-backdrop {
        position: fixed; inset: 0;
        background: rgba(0,0,0,0.45);
        z-index: 60;
        display: flex; align-items: center; justify-content: center;
        padding: 1rem;
    }
    .modal-box {
        background: #fff;
        border-radius: 1.5rem;
        max-width: 440px;
        width: 100%;
        padding: 2rem;
        box-shadow: 0 24px 64px rgba(0,0,0,0.2);
        animation: modal-in 0.2s ease;
    }
    .modal-box-lg {
        background: #fff;
        border-radius: 1.5rem;
        max-width: 520px;
        width: 100%;
        padding: 2rem;
        box-shadow: 0 24px 64px rgba(0,0,0,0.2);
        animation: modal-in 0.2s ease;
        max-height: 90vh;
        overflow-y: auto;
    }
    @keyframes modal-in {
        from { opacity: 0; transform: scale(0.95) translateY(12px); }
        to   { opacity: 1; transform: scale(1)    translateY(0); }
    }
    .btn-gold {
        background: linear-gradient(135deg, #D4AF63, #F0D080);
        color: #1F2A1D; font-weight: 700; border-radius: 0.75rem;
        transition: opacity 0.15s, transform 0.15s;
        display: inline-flex; align-items: center; gap: 0.5rem;
    }
    .btn-gold:hover { opacity: 0.9; transform: translateY(-1px); }
    .btn-dark {
        background: linear-gradient(135deg, #1F2A1D, #2D3E2A);
        color: #fff; font-weight: 700; border-radius: 0.75rem;
        transition: opacity 0.15s;
    }
    .btn-dark:hover { opacity: 0.88; }
    .btn-red {
        background: #ef4444; color: #fff; font-weight: 700; border-radius: 0.75rem;
        transition: opacity 0.15s;
    }
    .btn-red:hover { opacity: 0.88; }
</style>
@endpush

@section('content')


<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6"
     x-data="{
        search: '',
        filterStatus: 'all',
        showCancel:   false,
        showSuccess:  false,
        showDetail:   false,
        activeBooking: null,
        bookings: {{ Js::from($bookings) }},

        get filtered() {
            return this.bookings.filter(b => {
                const q = this.search.toLowerCase();
                const matchSearch = q === '' ||
                    b.id.toLowerCase().includes(q) ||
                    b.tanggal.toLowerCase().includes(q) ||
                    b.layanan.toLowerCase().includes(q);
                const matchStatus = this.filterStatus === 'all' || b.status === this.filterStatus;
                return matchSearch && matchStatus;
            });
        },
        get countTerkonfirmasi() { return this.bookings.filter(b => b.status === 'terkonfirmasi').length; },
        get countBerlangsung() { return this.bookings.filter(b => b.status === 'berlangsung').length; },

        openCancel(booking) { this.activeBooking = booking; this.showCancel = true; },
        async confirmCancel() {
            if (!this.activeBooking) return;
            
            try {
                let response = await fetch(`/customer/status/${this.activeBooking.id_asli}/cancel`, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                });
                
                let data = await response.json();
                
                if (data.success) {
                    let idx = this.bookings.findIndex(b => b.id_asli === this.activeBooking.id_asli);
                    if (idx !== -1) { 
                        this.bookings[idx].status = 'dibatalkan'; 
                        this.activeBooking = this.bookings[idx]; 
                    }
                    this.showCancel = false; 
                    this.showSuccess = true;
                } else {
                    alert(data.message || 'Gagal membatalkan booking.');
                    this.showCancel = false;
                }
            } catch (e) {
                alert('Terjadi kesalahan sistem atau jaringan.');
                this.showCancel = false;
            }
        },
        openDetail(booking) { this.activeBooking = booking; this.showDetail = true; },
        statusLabel(s) {
            const map = {
                'terkonfirmasi': { text: 'Terkonfirmasi', class: 'bg-[#FFFBEB] text-[#D4AF63] border-[#F0D080]' },
                'berlangsung': { text: 'Berlangsung', class: 'bg-blue-50 text-blue-600 border-blue-200' },
                'dibatalkan': { text: 'Dibatalkan', class: 'bg-red-50 text-red-500 border-red-200' }
            };
            return map[s] || { text: s, class: 'bg-gray-100 text-gray-700 border-gray-200' };
        }
     }">


    {{-- ============================================================ --}}
    {{-- HERO CARD                                                     --}}
    {{-- ============================================================ --}}
    <div class="status-hero p-7 lg:p-10">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-5">
            <div class="flex items-start gap-4">
                <div class="w-14 h-14 rounded-2xl flex items-center justify-center flex-shrink-0"
                     style="background: rgba(34,197,94,0.1); border: 1px solid rgba(34,197,94,0.2);">
                    <svg class="w-7 h-7 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                         stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                        <polyline points="22 4 12 14.01 9 11.01"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl lg:text-3xl font-bold text-[#1F2A1D] mb-0.5">Status Pesanan</h1>
                    <p class="text-gray-500 text-sm lg:text-base leading-relaxed max-w-lg">
                        Pantau perkembangan seluruh reservasi Anda secara real-time.
                    </p>
                </div>
            </div>
            <div class="flex-shrink-0">
                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full text-sm font-semibold"
                     style="background: rgba(31,42,29,0.07); color: #1F2A1D; border: 1px solid rgba(31,42,29,0.15);">
                    <span class="w-2 h-2 rounded-full bg-[#D4AF63] inline-block"></span>
                    <span x-text="(countTerkonfirmasi + countBerlangsung) + ' Booking Aktif'"></span>
                </div>
            </div>
        </div>
    </div>


    {{-- ============================================================ --}}
    {{-- STAT CARDS                                                    --}}
    {{-- ============================================================ --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        {{-- Terkonfirmasi --}}
        <div class="stat-card p-5 cursor-pointer" @click="filterStatus = 'terkonfirmasi'">
            <div class="flex items-center justify-between mb-3">
                <div class="gold-icon-wrap w-10 h-10 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="#D4AF63" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
                    </svg>
                </div>
                <span class="text-3xl font-bold text-[#D4AF63]" x-text="countTerkonfirmasi"></span>
            </div>
            <p class="text-sm font-semibold text-gray-700">Terkonfirmasi</p>
            <p class="text-xs text-gray-400 mt-0.5">Booking telah dikonfirmasi</p>
        </div>

        {{-- Berlangsung --}}
        <div class="stat-card p-5 cursor-pointer" @click="filterStatus = 'berlangsung'">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center"
                     style="background: rgba(37,99,235,0.1); border: 1px solid rgba(37,99,235,0.2);">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                         stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/>
                    </svg>
                </div>
                <span class="text-3xl font-bold text-blue-600" x-text="countBerlangsung"></span>
            </div>
            <p class="text-sm font-semibold text-gray-700">Berlangsung</p>
            <p class="text-xs text-gray-400 mt-0.5">Layanan sedang berjalan</p>
        </div>
    </div>


    {{-- ============================================================ --}}
    {{-- FILTER BAR                                                    --}}
    {{-- ============================================================ --}}
    <div class="flex flex-col sm:flex-row gap-3 items-start sm:items-center">
        <div class="relative flex-1 max-w-sm">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                 viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
                <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
            </svg>
            <input type="text" x-model="search" placeholder="Cari nomor booking, tanggal, layanan…"
                   class="w-full bg-white border border-[#E5E7EB] rounded-xl pl-10 pr-4 py-2.5 text-sm text-gray-700 shadow-sm focus:outline-none focus:ring-2 focus:ring-[#D4AF63]/40 focus:border-[#D4AF63]">
        </div>
        <div class="relative">
            <select x-model="filterStatus"
                    class="appearance-none bg-white border border-[#E5E7EB] rounded-xl px-4 py-2.5 pr-9 text-sm font-medium text-gray-700 shadow-sm focus:outline-none focus:ring-2 focus:ring-[#D4AF63]/40 focus:border-[#D4AF63] cursor-pointer">
                <option value="all">Semua Status</option>
                <option value="terkonfirmasi">Terkonfirmasi</option>
                <option value="berlangsung">Berlangsung</option>
            </select>
            <svg class="absolute right-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none"
                 fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
                <path d="M6 9l6 6 6-6"/>
            </svg>
        </div>
        <p class="text-sm text-gray-400 sm:ml-auto">
            Menampilkan <span class="font-semibold text-[#1F2A1D]" x-text="filtered.length"></span> booking
        </p>
    </div>


    {{-- ============================================================ --}}
    {{-- EMPTY STATE                                                   --}}
    {{-- ============================================================ --}}
    <div x-show="filtered.length === 0"
         class="bg-white rounded-2xl border border-[#E5E7EB] shadow-sm py-16 px-6 text-center">
        <div class="gold-icon-wrap w-20 h-20 rounded-2xl flex items-center justify-center mx-auto mb-5">
            <svg class="w-10 h-10" viewBox="0 0 24 24" fill="none" stroke="#D4AF63" stroke-width="1.5"
                 stroke-linecap="round" stroke-linejoin="round">
                <rect x="3" y="4" width="18" height="18" rx="2"/>
                <line x1="16" y1="2" x2="16" y2="6"/>
                <line x1="8" y1="2" x2="8" y2="6"/>
                <line x1="3" y1="10" x2="21" y2="10"/>
            </svg>
        </div>
        <h3 class="text-lg font-bold text-[#1F2A1D] mb-2">Belum Ada Booking</h3>
        <p class="text-gray-500 text-sm mb-6">Silakan lakukan booking layanan terlebih dahulu.</p>
        <a href="{{ route('customer.booking') }}"
           class="btn-gold px-6 py-2.5 text-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"><path d="M12 4v16m8-8H4"/></svg>
            Buat Booking
        </a>
    </div>


    {{-- ============================================================ --}}
    {{-- BOOKING GRID                                                  --}}
    {{-- ============================================================ --}}
    <div x-show="filtered.length > 0" class="grid grid-cols-1 sm:grid-cols-2 gap-5">
        <template x-for="b in filtered" :key="b.id">
            <div class="booking-card">

                {{-- Color accent bar --}}
                <div class="h-1 w-full"
                     :class="{
                         'bg-[#D4AF63]': b.status === 'terkonfirmasi',
                         'bg-blue-500':  b.status === 'berlangsung',
                         'bg-red-400':   b.status === 'dibatalkan',
                     }">
                </div>

                <div class="p-5">
                    {{-- Header: Booking ID --}}
                    <div class="flex items-start justify-between gap-2 mb-4">
                        <div>
                            <span class="text-xs text-gray-400 font-medium">Nomor Booking</span>
                            <p class="font-mono text-sm font-bold text-[#1F2A1D] mt-0.5" x-text="b.id"></p>
                        </div>
                    </div>

                    {{-- Status Badge --}}
                    <div class="mb-4">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold border"
                              :class="statusLabel(b.status).class"
                              x-text="statusLabel(b.status).text">
                        </span>
                    </div>

                    {{-- Info rows --}}
                    <div class="space-y-2 mb-4">
                        <div class="flex items-center gap-2.5 text-sm">
                            <svg class="w-4 h-4 text-[#D4AF63] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                            <span class="text-gray-500">Nama:</span>
                            <span class="font-semibold text-[#1F2A1D]" x-text="b.nama"></span>
                        </div>
                        <div class="flex items-center gap-2.5 text-sm">
                            <svg class="w-4 h-4 text-[#D4AF63] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                            <span class="text-gray-500">Tanggal:</span>
                            <span class="font-semibold text-[#1F2A1D]" x-text="b.tanggal"></span>
                        </div>
                        <div class="flex items-center gap-2.5 text-sm">
                            <svg class="w-4 h-4 text-[#D4AF63] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                            <span class="text-gray-500">Jam:</span>
                            <span class="font-semibold text-[#1F2A1D]" x-text="b.jam + ' WIB'"></span>
                        </div>
                        <div class="flex items-center gap-2.5 text-sm">
                            <svg class="w-4 h-4 text-[#D4AF63] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"><path d="M14.5 10c-.83 0-1.5-.67-1.5-1.5v-5c0-.83.67-1.5 1.5-1.5s1.5.67 1.5 1.5v5c0 .83-.67 1.5-1.5 1.5z"/><path d="M20.5 10H19V8.5c0-.83.67-1.5 1.5-1.5s1.5.67 1.5 1.5-.67 1.5-1.5 1.5z"/><path d="M9.5 14c.83 0 1.5.67 1.5 1.5v5c0 .83-.67 1.5-1.5 1.5S8 21.33 8 20.5v-5c0-.83.67-1.5 1.5-1.5z"/><path d="M3.5 14H5v1.5c0 .83-.67 1.5-1.5 1.5S2 16.33 2 15.5 2.67 14 3.5 14z"/><path d="M14 14.5c0-.83.67-1.5 1.5-1.5h5c.83 0 1.5.67 1.5 1.5s-.67 1.5-1.5 1.5h-5c-.83 0-1.5-.67-1.5-1.5z"/><path d="M15.5 19H14v1.5c0 .83.67 1.5 1.5 1.5s1.5-.67 1.5-1.5-.67-1.5-1.5-1.5z"/><path d="M10 9.5C10 8.67 9.33 8 8.5 8h-5C2.67 8 2 8.67 2 9.5S2.67 11 3.5 11h5c.83 0 1.5-.67 1.5-1.5z"/><path d="M8.5 5H10V3.5C10 2.67 9.33 2 8.5 2S7 2.67 7 3.5 7.67 5 8.5 5z"/></svg>
                            <span class="text-gray-500">Layanan:</span>
                            <span class="font-semibold text-[#1F2A1D]" x-text="b.layanan"></span>
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="pt-3 border-t border-[#F3F4F6] grid grid-cols-2 gap-3" x-show="b.status === 'terkonfirmasi'">
                        <button type="button" @click="openDetail(b)"
                                class="w-full py-2.5 rounded-xl text-sm font-semibold border border-[#D4AF63] text-[#D4AF63] hover:bg-[#D4AF63]/10 transition-colors flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                            Detail
                        </button>
                        <button type="button" @click="openCancel(b)"
                                class="w-full py-2.5 rounded-xl text-sm font-semibold border border-red-200 text-red-500 hover:bg-red-50 transition-colors flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                            Batal
                        </button>
                    </div>

                    <div class="pt-3 border-t border-[#F3F4F6]" x-show="b.status !== 'terkonfirmasi'">
                        <button type="button" @click="openDetail(b)"
                                class="w-full py-2.5 rounded-xl text-sm font-semibold border border-[#D4AF63] text-[#D4AF63] hover:bg-[#D4AF63]/10 transition-colors flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                            Lihat Detail
                        </button>
                    </div>
                </div>
            </div>
        </template>
    </div>


    {{-- ============================================================ --}}
    {{-- MODAL: BATALKAN BOOKING                                      --}}
    {{-- ============================================================ --}}
    <div class="modal-backdrop" x-show="showCancel" x-cloak
         @click.self="showCancel = false"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        <div class="modal-box" @click.stop>
            <div class="flex justify-center mb-4">
                <div class="w-14 h-14 rounded-2xl flex items-center justify-center"
                     style="background: rgba(239,68,68,0.1); border: 1px solid rgba(239,68,68,0.2);">
                    <svg class="w-7 h-7 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                         stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8">
                        <circle cx="12" cy="12" r="10"/>
                        <line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/>
                    </svg>
                </div>
            </div>
            <h3 class="text-xl font-bold text-[#1F2A1D] text-center mb-1">Batalkan Booking?</h3>
            <p class="text-gray-500 text-sm text-center mb-5">Apakah Anda yakin ingin membatalkan booking ini?</p>
            <div class="bg-[#F8F8F5] rounded-xl p-4 mb-5 border border-[#E5E7EB] space-y-2">
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500">Nomor</span>
                    <span class="font-mono text-xs font-semibold text-[#1F2A1D]" x-text="activeBooking ? activeBooking.id : ''"></span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500">Tanggal</span>
                    <span class="font-semibold text-[#1F2A1D]" x-text="activeBooking ? activeBooking.tanggal : ''"></span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500">Jam</span>
                    <span class="font-semibold text-[#1F2A1D]" x-text="activeBooking ? activeBooking.jam + ' WIB' : ''"></span>
                </div>
            </div>
            <div class="flex gap-3">
                <button type="button"
                        class="flex-1 py-2.5 rounded-xl text-sm font-semibold bg-gray-100 text-gray-600 hover:bg-gray-200 transition-colors"
                        @click="showCancel = false">
                    Kembali
                </button>
                <button type="button" class="btn-red flex-1 py-2.5 text-sm" @click="confirmCancel()">
                    Ya, Batalkan
                </button>
            </div>
        </div>
    </div>


    {{-- ============================================================ --}}
    {{-- MODAL: BERHASIL DIBATALKAN                                   --}}
    {{-- ============================================================ --}}
    <div class="modal-backdrop" x-show="showSuccess" x-cloak
         @click.self="showSuccess = false"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        <div class="modal-box text-center" @click.stop>
            <div class="flex justify-center mb-5">
                <div class="w-20 h-20 rounded-full flex items-center justify-center"
                     style="background: rgba(34,197,94,0.12); border: 2px solid rgba(34,197,94,0.25);">
                    <svg class="w-10 h-10 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                         stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                        <polyline points="22 4 12 14.01 9 11.01"/>
                    </svg>
                </div>
            </div>
            <h3 class="text-xl font-bold text-[#1F2A1D] mb-2">Booking Berhasil Dibatalkan</h3>
            <p class="text-gray-500 text-sm mb-6">Reservasi berhasil dibatalkan.</p>
            <button type="button" class="btn-dark w-full py-2.5 text-sm" @click="showSuccess = false">
                Tutup
            </button>
        </div>
    </div>


    {{-- ============================================================ --}}
    {{-- MODAL: DETAIL BOOKING                                        --}}
    {{-- ============================================================ --}}
    <div class="modal-backdrop" x-show="showDetail" x-cloak
         @click.self="showDetail = false"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        <div class="modal-box-lg" @click.stop>
            {{-- Modal Header --}}
            <div class="flex items-center justify-between mb-5">
                <div class="flex items-center gap-3">
                    <div class="gold-icon-wrap w-10 h-10 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="#D4AF63" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/>
                            <line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-[#1F2A1D]">Detail Booking</h3>
                        <p class="text-xs text-gray-400 font-mono" x-text="activeBooking ? activeBooking.id : ''"></p>
                    </div>
                </div>
                <button type="button" @click="showDetail = false"
                        class="w-8 h-8 rounded-lg flex items-center justify-center hover:bg-gray-100 text-gray-400 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
                        <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                    </svg>
                </button>
            </div>

            {{-- Status badge in modal --}}
            <div class="mb-5">
                <template x-if="activeBooking && activeBooking.status === 'menunggu'">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-700">
                        <span class="w-1.5 h-1.5 rounded-full bg-yellow-500 inline-block"></span>Menunggu Konfirmasi
                    </span>
                </template>
                <template x-if="activeBooking && activeBooking.status === 'dikonfirmasi'">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold bg-green-100 text-green-700">
                        <span class="w-1.5 h-1.5 rounded-full bg-green-500 inline-block"></span>Dikonfirmasi
                    </span>
                </template>
                <template x-if="activeBooking && activeBooking.status === 'selesai'">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold bg-blue-100 text-blue-700">
                        <span class="w-1.5 h-1.5 rounded-full bg-blue-500 inline-block"></span>Selesai
                    </span>
                </template>
                <template x-if="activeBooking && activeBooking.status === 'dibatalkan'">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold bg-red-100 text-red-700">
                        <span class="w-1.5 h-1.5 rounded-full bg-red-500 inline-block"></span>Dibatalkan
                    </span>
                </template>
            </div>

            {{-- Detail table --}}
            <template x-if="activeBooking">
                <div class="divide-y divide-[#F3F4F6] border border-[#E5E7EB] rounded-xl overflow-hidden mb-5">
                    <div class="flex justify-between px-4 py-3 bg-[#F8F8F5]/60">
                        <span class="text-sm text-gray-500">Nomor Booking</span>
                        <span class="text-sm font-semibold text-[#1F2A1D] font-mono" x-text="activeBooking.id"></span>
                    </div>
                    <div class="flex justify-between px-4 py-3">
                        <span class="text-sm text-gray-500">Nama</span>
                        <span class="text-sm font-semibold text-[#1F2A1D]" x-text="activeBooking.nama"></span>
                    </div>
                    <div class="flex justify-between px-4 py-3 bg-[#F8F8F5]/60">
                        <span class="text-sm text-gray-500">Email</span>
                        <span class="text-sm font-semibold text-[#1F2A1D]" x-text="activeBooking.email"></span>
                    </div>
                    <div class="flex justify-between px-4 py-3">
                        <span class="text-sm text-gray-500">Nomor HP</span>
                        <span class="text-sm font-semibold text-[#1F2A1D]" x-text="activeBooking.hp"></span>
                    </div>
                    <div class="flex justify-between px-4 py-3 bg-[#F8F8F5]/60">
                        <span class="text-sm text-gray-500">Tanggal</span>
                        <span class="text-sm font-semibold text-[#1F2A1D]" x-text="activeBooking.tanggal"></span>
                    </div>
                    <div class="flex justify-between px-4 py-3">
                        <span class="text-sm text-gray-500">Jam</span>
                        <span class="text-sm font-semibold text-[#1F2A1D]" x-text="activeBooking.jam + ' WIB'"></span>
                    </div>
                    <div class="flex justify-between px-4 py-3 bg-[#F8F8F5]/60">
                        <span class="text-sm text-gray-500">Status</span>
                        <span class="text-sm font-semibold" x-text="statusLabel(activeBooking.status)"
                              :class="{
                                  'text-yellow-700': activeBooking.status === 'menunggu',
                                  'text-green-700':  activeBooking.status === 'dikonfirmasi',
                                  'text-blue-700':   activeBooking.status === 'selesai',
                                  'text-red-700':    activeBooking.status === 'dibatalkan',
                              }">
                        </span>
                    </div>
                    <div class="flex justify-between px-4 py-3">
                        <span class="text-sm text-gray-500">Layanan</span>
                        <span class="text-sm font-semibold text-[#1F2A1D]" x-text="activeBooking.layanan"></span>
                    </div>
                    <div class="flex justify-between px-4 py-3 bg-[#F8F8F5]/60">
                        <span class="text-sm text-gray-500">Durasi</span>
                        <span class="text-sm font-semibold text-[#1F2A1D]" x-text="activeBooking.durasi"></span>
                    </div>
                    <div class="flex justify-between px-4 py-3">
                        <span class="text-sm text-gray-500">Admin</span>
                        <span class="text-sm font-semibold text-[#1F2A1D]" x-text="activeBooking.admin"></span>
                    </div>
                </div>
            </template>

            <button type="button" class="btn-dark w-full py-2.5 text-sm" @click="showDetail = false">
                Tutup
            </button>
        </div>
    </div>

</div>

@endsection
