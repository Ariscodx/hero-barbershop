@extends('customer.layouts.app')

@section('title', config('app.name', 'Hero Barbershop') . ' - Riwayat Booking')
@section('description', 'Lihat seluruh riwayat reservasi layanan Hero Barbershop yang pernah Anda lakukan.')

@push('head')
<style>
    /* ── Riwayat Booking Page ─────────────────── */
    .riwayat-hero {
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
        cursor: pointer;
    }
    .stat-card:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(31,42,29,0.1); }
    .riwayat-card {
        background: #fff;
        border-radius: 1rem;
        border: 2px solid #E5E7EB;
        box-shadow: 0 2px 10px rgba(31,42,29,0.05);
        transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
        overflow: hidden;
    }
    .riwayat-card:hover {
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
    .modal-box-lg {
        background: #fff;
        border-radius: 1.5rem;
        max-width: 540px;
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
    /* Timeline */
    .timeline-wrap { position: relative; padding-left: 1.5rem; }
    .timeline-wrap::before {
        content: '';
        position: absolute; left: 0.65rem; top: 0.75rem; bottom: 0.75rem;
        width: 2px; background: #E5E7EB;
    }
    .timeline-dot {
        position: absolute; left: 0;
        width: 1.25rem; height: 1.25rem;
        border-radius: 9999px;
        display: flex; align-items: center; justify-content: center;
        border: 2px solid #E5E7EB;
        background: #fff;
        flex-shrink: 0;
    }
    .timeline-dot.done  { border-color: #22c55e; background: #dcfce7; }
    .timeline-dot.gold  { border-color: #D4AF63; background: #FFFBEB; }
    .timeline-dot.red   { border-color: #ef4444; background: #fee2e2; }
    .timeline-dot.gray  { border-color: #d1d5db; background: #f9fafb; }
    .timeline-item { position: relative; padding-bottom: 1.25rem; }
    .timeline-item:last-child { padding-bottom: 0; }
</style>
@endpush

@section('content')



<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6"
     x-data="{
        search: '',
        filterStatus: 'all',
        filterBulan: 'all',
        filterTahun: 'all',
        showDetail: false,
        activeItem: null,

        riwayat: {{ Js::from($riwayat) }},

        get filtered() {
            return this.riwayat.filter(r => {
                const q = this.search.toLowerCase();
                const matchSearch = q === '' ||
                    r.id.toLowerCase().includes(q) ||
                    r.tanggal.toLowerCase().includes(q) ||
                    r.layanan.toLowerCase().includes(q);
                const matchStatus = this.filterStatus === 'all' || r.status === this.filterStatus;
                const matchBulan = this.filterBulan === 'all' || r.tanggal.includes(this.filterBulan);
                const matchTahun = this.filterTahun === 'all' || r.tanggal.includes(this.filterTahun);
                return matchSearch && matchStatus && matchBulan && matchTahun;
            });
        },
        get countSelesai()    { return this.riwayat.filter(r => r.status === 'selesai').length; },
        get countDibatalkan() { return this.riwayat.filter(r => r.status === 'dibatalkan').length; },

        openDetail(item) { this.activeItem = item; this.showDetail = true; },
     }">


    {{-- ============================================================ --}}
    {{-- HERO CARD                                                     --}}
    {{-- ============================================================ --}}
    <div class="riwayat-hero p-7 lg:p-10">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-5">
            <div class="flex items-start gap-4">
                <div class="gold-icon-wrap w-14 h-14 rounded-2xl flex items-center justify-center flex-shrink-0">
                    <svg class="w-7 h-7" viewBox="0 0 24 24" fill="none" stroke="#D4AF63" stroke-width="1.8"
                         stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"/>
                        <polyline points="12 6 12 12 16 14"/>
                        <path d="M3.05 11a9 9 0 1 0 .5-3"/>
                        <polyline points="3 4 3 11 10 11"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl lg:text-3xl font-bold text-[#1F2A1D] mb-0.5">Riwayat Booking</h1>
                    <p class="text-gray-500 text-sm lg:text-base leading-relaxed max-w-lg">
                        Lihat seluruh riwayat reservasi layanan Hero Barbershop yang pernah Anda lakukan.
                    </p>
                </div>
            </div>
            <div class="flex-shrink-0">
                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full text-sm font-semibold"
                     style="background: rgba(212,175,99,0.12); color: #92680c; border: 1px solid rgba(212,175,99,0.35);">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                        <polyline points="14 2 14 8 20 8"/>
                        <line x1="16" y1="13" x2="8" y2="13"/>
                        <line x1="16" y1="17" x2="8" y2="17"/>
                        <polyline points="10 9 9 9 8 9"/>
                    </svg>
                    Arsip Booking
                </div>
            </div>
        </div>
    </div>


    {{-- ============================================================ --}}
    {{-- STAT CARDS                                                    --}}
    {{-- ============================================================ --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">

        {{-- Total --}}
        <div class="stat-card p-5" @click="filterStatus = 'all'">
            <div class="flex items-center justify-between mb-3">
                <div class="gold-icon-wrap w-10 h-10 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="#D4AF63" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/>
                        <line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>
                    </svg>
                </div>
                <span class="text-3xl font-bold text-[#1F2A1D]" x-text="riwayat.length"></span>
            </div>
            <p class="text-sm font-semibold text-gray-700">Total Booking</p>
            <p class="text-xs text-gray-400 mt-0.5">Semua riwayat</p>
        </div>

        {{-- Selesai --}}
        <div class="stat-card p-5" @click="filterStatus = 'selesai'">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center"
                     style="background: rgba(34,197,94,0.1); border: 1px solid rgba(34,197,94,0.2);">
                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                         stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/>
                    </svg>
                </div>
                <span class="text-3xl font-bold text-green-600" x-text="countSelesai"></span>
            </div>
            <p class="text-sm font-semibold text-gray-700">Booking Selesai</p>
            <p class="text-xs text-gray-400 mt-0.5">Layanan tuntas</p>
        </div>

        {{-- Dibatalkan --}}
        <div class="stat-card p-5" @click="filterStatus = 'dibatalkan'">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center"
                     style="background: rgba(239,68,68,0.1); border: 1px solid rgba(239,68,68,0.2);">
                    <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                         stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
                        <circle cx="12" cy="12" r="10"/>
                        <line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/>
                    </svg>
                </div>
                <span class="text-3xl font-bold text-red-500" x-text="countDibatalkan"></span>
            </div>
            <p class="text-sm font-semibold text-gray-700">Booking Dibatalkan</p>
            <p class="text-xs text-gray-400 mt-0.5">Tidak terlaksana</p>
        </div>

        {{-- Total Kunjungan --}}
        <div class="stat-card p-5" @click="filterStatus = 'selesai'">
            <div class="flex items-center justify-between mb-3">
                <div class="gold-icon-wrap w-10 h-10 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="#D4AF63" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                        <circle cx="9" cy="7" r="4"/>
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                    </svg>
                </div>
                <span class="text-3xl font-bold text-[#D4AF63]" x-text="countSelesai + ' Kali'"></span>
            </div>
            <p class="text-sm font-semibold text-gray-700">Total Kunjungan</p>
            <p class="text-xs text-gray-400 mt-0.5">Kunjungan nyata</p>
        </div>

    </div>


    {{-- ============================================================ --}}
    {{-- FILTER BAR                                                    --}}
    {{-- ============================================================ --}}
    <div class="bg-white border border-[#E5E7EB] rounded-2xl shadow-sm p-4">
        <div class="flex flex-wrap gap-3 items-center">

            {{-- Search --}}
            <div class="relative flex-1 min-w-[200px]">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                     viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
                    <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
                </svg>
                <input type="text" x-model="search" placeholder="Cari tanggal atau nomor booking…"
                       class="w-full bg-[#F8F8F5] border border-[#E5E7EB] rounded-xl pl-10 pr-4 py-2.5 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-[#D4AF63]/40 focus:border-[#D4AF63] focus:bg-white transition-colors">
            </div>

            {{-- Status --}}
            <div class="relative">
                <select x-model="filterStatus"
                        class="appearance-none bg-[#F8F8F5] border border-[#E5E7EB] rounded-xl px-4 py-2.5 pr-8 text-sm font-medium text-gray-700 focus:outline-none focus:ring-2 focus:ring-[#D4AF63]/40 focus:border-[#D4AF63] cursor-pointer">
                    <option value="all">Semua Status</option>
                    <option value="selesai">Selesai</option>
                    <option value="dibatalkan">Dibatalkan</option>
                </select>
                <svg class="absolute right-2.5 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"><path d="M6 9l6 6 6-6"/></svg>
            </div>

            {{-- Bulan --}}
            <div class="relative">
                <select x-model="filterBulan"
                        class="appearance-none bg-[#F8F8F5] border border-[#E5E7EB] rounded-xl px-4 py-2.5 pr-8 text-sm font-medium text-gray-700 focus:outline-none focus:ring-2 focus:ring-[#D4AF63]/40 focus:border-[#D4AF63] cursor-pointer">
                    <option value="all">Semua Bulan</option>
                    <option>Januari</option><option>Februari</option><option>Maret</option>
                    <option>April</option><option>Mei</option><option>Juni</option>
                    <option>Juli</option><option>Agustus</option><option>September</option>
                    <option>Oktober</option><option>November</option><option>Desember</option>
                </select>
                <svg class="absolute right-2.5 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"><path d="M6 9l6 6 6-6"/></svg>
            </div>

            {{-- Tahun --}}
            <div class="relative">
                <select x-model="filterTahun"
                        class="appearance-none bg-[#F8F8F5] border border-[#E5E7EB] rounded-xl px-4 py-2.5 pr-8 text-sm font-medium text-gray-700 focus:outline-none focus:ring-2 focus:ring-[#D4AF63]/40 focus:border-[#D4AF63] cursor-pointer">
                    <option value="all">Semua Tahun</option>
                    <option>2025</option><option>2026</option><option>2027</option>
                </select>
                <svg class="absolute right-2.5 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"><path d="M6 9l6 6 6-6"/></svg>
            </div>

            {{-- Count --}}
            <p class="text-sm text-gray-400 sm:ml-auto whitespace-nowrap">
                <span class="font-semibold text-[#1F2A1D]" x-text="filtered.length"></span> riwayat ditemukan
            </p>
        </div>
    </div>


    {{-- ============================================================ --}}
    {{-- EMPTY STATE                                                   --}}
    {{-- ============================================================ --}}
    <div x-show="filtered.length === 0"
         class="bg-white rounded-2xl border border-[#E5E7EB] shadow-sm py-20 px-6 text-center">
        <div class="gold-icon-wrap w-20 h-20 rounded-2xl flex items-center justify-center mx-auto mb-5">
            <svg class="w-10 h-10" viewBox="0 0 24 24" fill="none" stroke="#D4AF63" stroke-width="1.5"
                 stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"/>
                <polyline points="12 6 12 12 16 14"/>
                <path d="M3.05 11a9 9 0 1 0 .5-3"/>
                <polyline points="3 4 3 11 10 11"/>
            </svg>
        </div>
        <h3 class="text-lg font-bold text-[#1F2A1D] mb-2">Belum Ada Riwayat Booking</h3>
        <p class="text-gray-500 text-sm mb-6">Riwayat booking akan muncul setelah Anda menyelesaikan reservasi.</p>
        <a href="{{ route('customer.booking') }}" class="btn-gold px-6 py-2.5 text-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"><path d="M12 4v16m8-8H4"/></svg>
            Buat Booking
        </a>
    </div>


    {{-- ============================================================ --}}
    {{-- RIWAYAT GRID                                                  --}}
    {{-- ============================================================ --}}
    <div x-show="filtered.length > 0" class="bg-white rounded-2xl border border-[#E5E7EB] shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-[#F8F8F5] border-b border-[#E5E7EB]">
                        <th class="py-4 px-6 text-xs font-semibold text-gray-500 uppercase tracking-wider">No. Booking</th>
                        <th class="py-4 px-6 text-xs font-semibold text-gray-500 uppercase tracking-wider">Pelanggan</th>
                        <th class="py-4 px-6 text-xs font-semibold text-gray-500 uppercase tracking-wider">Layanan</th>
                        <th class="py-4 px-6 text-xs font-semibold text-gray-500 uppercase tracking-wider">Tanggal & Waktu</th>
                        <th class="py-4 px-6 text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="py-4 px-6 text-xs font-semibold text-gray-500 uppercase tracking-wider text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#E5E7EB]">
                    <template x-for="item in filtered" :key="item.id">
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="py-4 px-6 align-middle">
                                <span class="font-mono text-sm font-bold text-[#1F2A1D]" x-text="item.id"></span>
                            </td>
                            <td class="py-4 px-6 align-middle">
                                <span class="text-sm font-semibold text-[#1F2A1D] block" x-text="item.nama"></span>
                                <span class="text-xs text-gray-500" x-text="item.hp"></span>
                            </td>
                            <td class="py-4 px-6 align-middle">
                                <span class="text-sm font-semibold text-[#1F2A1D] block truncate max-w-[150px]" x-text="item.layanan"></span>
                                <span class="text-xs text-gray-500" x-text="item.durasi"></span>
                            </td>
                            <td class="py-4 px-6 align-middle">
                                <span class="text-sm font-semibold text-[#1F2A1D] block" x-text="item.tanggal"></span>
                                <span class="text-xs text-gray-500" x-text="item.jam + ' WIB'"></span>
                            </td>
                            <td class="py-4 px-6 align-middle">
                                <template x-if="item.status === 'selesai'">
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700 border border-green-200">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                                        Selesai
                                    </span>
                                </template>
                                <template x-if="item.status === 'dibatalkan'">
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700 border border-red-200">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                                        Dibatalkan
                                    </span>
                                </template>
                            </td>
                            <td class="py-4 px-6 align-middle text-center">
                                <button type="button" @click="openDetail(item)"
                                        class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-[#F8F8F5] text-[#D4AF63] border border-[#E5E7EB] hover:bg-[#D4AF63] hover:text-white hover:border-[#D4AF63] transition-colors"
                                        title="Lihat Detail">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                </button>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
    </div>


    {{-- ============================================================ --}}
    {{-- MODAL: DETAIL + TIMELINE                                      --}}
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

            {{-- Modal header --}}
            <div class="flex items-center justify-between mb-5">
                <div class="flex items-center gap-3">
                    <div class="gold-icon-wrap w-10 h-10 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="#D4AF63" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                            <polyline points="14 2 14 8 20 8"/>
                            <line x1="16" y1="13" x2="8" y2="13"/>
                            <line x1="16" y1="17" x2="8" y2="17"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-[#1F2A1D]">Detail Riwayat</h3>
                        <p class="text-xs text-gray-400 font-mono" x-text="activeItem ? activeItem.id : ''"></p>
                    </div>
                </div>
                <button type="button" @click="showDetail = false"
                        class="w-8 h-8 rounded-lg flex items-center justify-center hover:bg-gray-100 text-gray-400 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
                        <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                    </svg>
                </button>
            </div>

            <template x-if="activeItem">
                <div>
                    {{-- Info table --}}
                    <div class="divide-y divide-[#F3F4F6] border border-[#E5E7EB] rounded-xl overflow-hidden mb-6">
                        <div class="flex justify-between px-4 py-3 bg-[#F8F8F5]/60">
                            <span class="text-sm text-gray-500">Nomor Booking</span>
                            <span class="text-xs font-mono font-bold text-[#1F2A1D]" x-text="activeItem.id"></span>
                        </div>
                        <div class="flex justify-between px-4 py-3">
                            <span class="text-sm text-gray-500">Nama</span>
                            <span class="text-sm font-semibold text-[#1F2A1D]" x-text="activeItem.nama"></span>
                        </div>
                        <div class="flex justify-between px-4 py-3 bg-[#F8F8F5]/60">
                            <span class="text-sm text-gray-500">Email</span>
                            <span class="text-sm font-semibold text-[#1F2A1D]" x-text="activeItem.email"></span>
                        </div>
                        <div class="flex justify-between px-4 py-3">
                            <span class="text-sm text-gray-500">Nomor HP</span>
                            <span class="text-sm font-semibold text-[#1F2A1D]" x-text="activeItem.hp"></span>
                        </div>
                        <div class="flex justify-between px-4 py-3 bg-[#F8F8F5]/60">
                            <span class="text-sm text-gray-500">Tanggal</span>
                            <span class="text-sm font-semibold text-[#1F2A1D]" x-text="activeItem.tanggal"></span>
                        </div>
                        <div class="flex justify-between px-4 py-3">
                            <span class="text-sm text-gray-500">Jam</span>
                            <span class="text-sm font-semibold text-[#1F2A1D]" x-text="activeItem.jam + ' WIB'"></span>
                        </div>
                        <div class="flex justify-between px-4 py-3 bg-[#F8F8F5]/60">
                            <span class="text-sm text-gray-500">Layanan</span>
                            <span class="text-sm font-semibold text-[#1F2A1D]" x-text="activeItem.layanan"></span>
                        </div>
                        <div class="flex justify-between px-4 py-3">
                            <span class="text-sm text-gray-500">Durasi</span>
                            <span class="text-sm font-semibold text-[#1F2A1D]" x-text="activeItem.durasi"></span>
                        </div>
                        <div class="flex justify-between px-4 py-3 bg-[#F8F8F5]/60">
                            <span class="text-sm text-gray-500">Status Akhir</span>
                            <span class="text-sm font-semibold"
                                  :class="activeItem.status === 'selesai' ? 'text-green-700' : 'text-red-600'"
                                  x-text="activeItem.status === 'selesai' ? 'Selesai' : 'Dibatalkan'">
                            </span>
                        </div>
                        <div class="flex justify-between px-4 py-3">
                            <span class="text-sm text-gray-500">Catatan Admin</span>
                            <span class="text-sm font-semibold text-[#1F2A1D] text-right max-w-[55%]" x-text="activeItem.catatan"></span>
                        </div>
                    </div>

                    {{-- Timeline --}}
                    <div class="mb-6">
                        <p class="text-sm font-bold text-[#1F2A1D] mb-4 flex items-center gap-2">
                            <svg class="w-4 h-4 text-[#D4AF63]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"><line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/><line x1="3" y1="6" x2="3.01" y2="6"/><line x1="3" y1="12" x2="3.01" y2="12"/><line x1="3" y1="18" x2="3.01" y2="18"/></svg>
                            Timeline Booking
                        </p>

                        {{-- Timeline: Selesai --}}
                        <template x-if="activeItem.status === 'selesai'">
                            <div class="timeline-wrap">
                                <div class="timeline-item">
                                    <div class="timeline-dot gold" style="top:0.1rem;">
                                        <svg class="w-2.5 h-2.5 text-[#D4AF63]" fill="currentColor" viewBox="0 0 8 8"><circle cx="4" cy="4" r="3"/></svg>
                                    </div>
                                    <div class="ml-8">
                                        <p class="text-sm font-semibold text-[#1F2A1D]">Booking Dibuat</p>
                                        <p class="text-xs text-gray-400" x-text="activeItem.tanggal"></p>
                                    </div>
                                </div>
                                <div class="timeline-item">
                                    <div class="timeline-dot done" style="top:0.1rem;">
                                        <svg class="w-2.5 h-2.5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round" stroke-width="3"><path d="M20 6L9 17l-5-5"/></svg>
                                    </div>
                                    <div class="ml-8">
                                        <p class="text-sm font-semibold text-[#1F2A1D]">Dikonfirmasi</p>
                                        <p class="text-xs text-gray-400">Admin mengkonfirmasi booking</p>
                                    </div>
                                </div>
                                <div class="timeline-item">
                                    <div class="timeline-dot done" style="top:0.1rem;">
                                        <svg class="w-2.5 h-2.5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round" stroke-width="3"><path d="M20 6L9 17l-5-5"/></svg>
                                    </div>
                                    <div class="ml-8">
                                        <p class="text-sm font-semibold text-green-700">Selesai</p>
                                        <p class="text-xs text-gray-400">Layanan berhasil diselesaikan</p>
                                    </div>
                                </div>
                            </div>
                        </template>

                        {{-- Timeline: Dibatalkan --}}
                        <template x-if="activeItem.status === 'dibatalkan'">
                            <div class="timeline-wrap">
                                <div class="timeline-item">
                                    <div class="timeline-dot gold" style="top:0.1rem;">
                                        <svg class="w-2.5 h-2.5 text-[#D4AF63]" fill="currentColor" viewBox="0 0 8 8"><circle cx="4" cy="4" r="3"/></svg>
                                    </div>
                                    <div class="ml-8">
                                        <p class="text-sm font-semibold text-[#1F2A1D]">Booking Dibuat</p>
                                        <p class="text-xs text-gray-400" x-text="activeItem.tanggal"></p>
                                    </div>
                                </div>
                                <div class="timeline-item">
                                    <div class="timeline-dot gray" style="top:0.1rem;">
                                        <svg class="w-2.5 h-2.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                    </div>
                                    <div class="ml-8">
                                        <p class="text-sm font-semibold text-[#1F2A1D]">Menunggu Konfirmasi</p>
                                        <p class="text-xs text-gray-400">Menunggu tindakan admin</p>
                                    </div>
                                </div>
                                <div class="timeline-item">
                                    <div class="timeline-dot red" style="top:0.1rem;">
                                        <svg class="w-2.5 h-2.5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                                    </div>
                                    <div class="ml-8">
                                        <p class="text-sm font-semibold text-red-600">Dibatalkan</p>
                                        <p class="text-xs text-gray-400" x-text="activeItem.catatan"></p>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>

                    <button type="button" class="btn-dark w-full py-2.5 text-sm" @click="showDetail = false">
                        Tutup
                    </button>
                </div>
            </template>

        </div>
    </div>

</div>

@endsection
