@extends('customer.layouts.app')

@section('title', config('app.name', 'Hero Barbershop') . ' - Buat Booking')
@section('description',
    'Buat reservasi layanan Hero Barbershop secara online. Pilih tanggal dan slot waktu yang
    tersedia.')

    @push('head')
        <style>
            /* ── Booking Page ───────────────────────── */
            .booking-hero {
                background: #fff;
                border-radius: 1.5rem;
                box-shadow: 0 4px 24px rgba(31, 42, 29, 0.08);
                border: 1px solid #E5E7EB;
            }

            /* Step indicator */
            .step-wrap {
                display: flex;
                align-items: center;
                gap: 0;
            }

            .step-circle {
                width: 2rem;
                height: 2rem;
                border-radius: 9999px;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 0.75rem;
                font-weight: 700;
                flex-shrink: 0;
                transition: background 0.2s, color 0.2s;
            }

            .step-circle.active {
                background: #1F2A1D;
                color: #D4AF63;
            }

            .step-circle.done {
                background: #D4AF63;
                color: #1F2A1D;
            }

            .step-circle.pending {
                background: #E5E7EB;
                color: #9CA3AF;
            }

            .step-line {
                flex: 1;
                height: 2px;
                background: #E5E7EB;
                min-width: 24px;
            }

            .step-line.done {
                background: #D4AF63;
            }

            /* Date card */
            .date-card {
                flex: 1;
                min-width: 72px;
                padding: 0.625rem 0.875rem;
                border-radius: 0.875rem;
                border: 2px solid #E5E7EB;
                background: #fff;
                text-align: center;
                cursor: pointer;
                transition: all 0.18s ease;
                user-select: none;
            }

            .date-card:hover {
                border-color: #D4AF63;
                background: #FFFBF0;
            }

            .date-card.selected {
                background: #1F2A1D;
                border-color: #D4AF63;
                box-shadow: 0 8px 24px rgba(31, 42, 29, 0.25);
            }

            .date-card.selected .dc-day {
                color: #D4AF63;
            }

            .date-card.selected .dc-date {
                color: #fff;
            }

            .date-card.today-mark {
                border-color: rgba(212, 175, 99, 0.5);
            }

            .dc-day {
                font-size: 0.68rem;
                font-weight: 600;
                color: #9CA3AF;
                text-transform: uppercase;
                letter-spacing: 0.04em;
            }

            .dc-date {
                font-size: 1rem;
                font-weight: 700;
                color: #1F2A1D;
                line-height: 1.2;
                margin-top: 2px;
            }

            .dc-month {
                font-size: 0.68rem;
                color: #6B7280;
                margin-top: 1px;
            }

            /* Slot card */
            .slot-card {
                background: #fff;
                border: 2px solid #E5E7EB;
                border-radius: 1rem;
                padding: 1.25rem 1rem;
                transition: all 0.2s ease;
                cursor: pointer;
            }

            .slot-card.available:hover {
                transform: translateY(-3px);
                box-shadow: 0 12px 32px rgba(31, 42, 29, 0.12);
                border-color: #D4AF63;
            }

            .slot-card.full {
                opacity: 0.75;
                cursor: default;
            }

            .slot-card.booked {
                opacity: 0.75;
                cursor: default;
            }

            /* Modal */
            .modal-backdrop {
                position: fixed;
                inset: 0;
                background: rgba(0, 0, 0, 0.45);
                z-index: 60;
                display: flex;
                align-items: center;
                justify-content: center;
                padding: 1rem;
            }

            .modal-box {
                background: #fff;
                border-radius: 1.5rem;
                max-width: 420px;
                width: 100%;
                padding: 2rem;
                box-shadow: 0 24px 64px rgba(0, 0, 0, 0.2);
                animation: modal-in 0.2s ease;
            }

            @keyframes modal-in {
                from {
                    opacity: 0;
                    transform: scale(0.95) translateY(12px);
                }

                to {
                    opacity: 1;
                    transform: scale(1) translateY(0);
                }
            }

            /* Info box */
            .info-box-gold {
                background: #FFFBEB;
                border: 1.5px solid rgba(212, 175, 99, 0.45);
                border-radius: 1rem;
            }

            .gold-icon-wrap {
                background: linear-gradient(135deg, rgba(212, 176, 106, 0.15), rgba(212, 176, 106, 0.08));
                border: 1px solid rgba(212, 176, 106, 0.25);
            }

            .btn-gold {
                background: linear-gradient(135deg, #D4AF63, #F0D080);
                color: #1F2A1D;
                font-weight: 700;
                border-radius: 0.75rem;
                transition: opacity 0.15s, transform 0.15s;
            }

            .btn-gold:hover {
                opacity: 0.92;
                transform: translateY(-1px);
            }

            .btn-dark {
                background: linear-gradient(135deg, #1F2A1D, #2D3E2A);
                color: #fff;
                font-weight: 700;
                border-radius: 0.75rem;
                transition: opacity 0.15s;
            }

            .btn-dark:hover {
                opacity: 0.88;
            }
        </style>
    @endpush

@section('content')



    {{-- ========= ALPINE DATA ========= --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6" x-data="{
        /* ── State ── */
        selectedDate: 0,
        filterDay: 'all',
        showConfirm: false,
        showSuccess: false,
        selectedSlot: null,
        step: 1,
        isLoadingSlots: false,
    
        init() {
            this.fetchSlotsForDate(this.selectedDate);
        },
    
        /* ── Dates (7 hari mulai hari ini, dari PHP) ── */
        dates: {{ Js::from($datesData) }},
    
        /* ── Slots ── */
        allSlots: {{ Js::from($allSlots) }},
    
        get slots() {
            let selectedYmd = this.dates[this.selectedDate]?.ymd;
            return this.allSlots[selectedYmd] || [];
        },
    
        /* ── Methods ── */
        pickSlot(slot) {
            if (slot.status !== 'tersedia') return;
            this.selectedSlot = slot;
            this.showConfirm = true;
            this.step = 3;
        },
        async confirmBooking() {
            try {
                let response = await fetch('{{ route('customer.booking.store') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ id_kuota: this.selectedSlot.id })
                });
                let data = await response.json();
                if (data.success) {
                    this.showConfirm = false;
                    this.showSuccess = true;
                    this.step = 4;
    
                    let ymd = this.dates[this.selectedDate].ymd;
                    let slotIndex = this.allSlots[ymd].findIndex(s => s.id === this.selectedSlot.id);
                    if (slotIndex > -1) {
                        this.allSlots[ymd][slotIndex].status = 'terbooking';
                    }
                } else {
                    alert(data.message || 'Gagal melakukan booking.');
                    this.showConfirm = false;
                    this.step = 2;
                }
            } catch (e) {
                alert('Terjadi kesalahan sistem atau jaringan.');
                this.showConfirm = false;
                this.step = 2;
            }
        },
        resetAll() {
            this.showSuccess = false;
            this.selectedSlot = null;
            this.step = 1;
        },
        selectDate(i) {
            this.selectedDate = i;
            this.step = 2;
            this.fetchSlotsForDate(i);
        },
        async fetchSlotsForDate(i) {
            this.isLoadingSlots = true;
            try {
                let ymd = this.dates[i]?.ymd;
                if (!ymd) return;
                let res = await fetch(`/customer/booking/slots/${ymd}`);
                let data = await res.json();
                if (data.success) {
                    this.allSlots[ymd] = data.data;
                }
            } catch (e) {
                console.error('Gagal mengambil slot terbaru:', e);
            } finally {
                this.isLoadingSlots = false;
            }
        },
        get currentDate() {
            return this.dates[this.selectedDate];
        },
        get availableCount() {
            return this.slots.filter(s => s.status === 'tersedia').length;
        }
    }">

        {{-- ============================================================ --}}
        {{-- HERO CARD                                                     --}}
        {{-- ============================================================ --}}
        <div class="booking-hero p-7 lg:p-10">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-5">

                {{-- Left --}}
                <div class="flex items-start gap-4">
                    <div class="gold-icon-wrap w-14 h-14 rounded-2xl flex items-center justify-center flex-shrink-0">
                        <svg class="w-7 h-7" viewBox="0 0 24 24" fill="none" stroke="#D4AF63" stroke-width="1.8"
                            stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2" />
                            <line x1="16" y1="2" x2="16" y2="6" />
                            <line x1="8" y1="2" x2="8" y2="6" />
                            <line x1="3" y1="10" x2="21" y2="10" />
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-2xl lg:text-3xl font-bold text-[#1F2A1D] mb-0.5">Buat Booking</h1>
                        <p class="text-gray-500 text-sm lg:text-base leading-relaxed max-w-lg">
                            Pilih tanggal dan slot waktu yang tersedia untuk melakukan reservasi layanan Hero Barbershop.
                        </p>
                    </div>
                </div>

                {{-- Badge --}}
                <div class="flex-shrink-0">
                    <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full text-sm font-semibold"
                        style="background: rgba(34,197,94,0.1); color: #16a34a; border: 1px solid rgba(34,197,94,0.25);">
                        <span class="w-2 h-2 rounded-full bg-green-500 badge-pulse inline-block"></span>
                        Booking Online
                    </div>
                </div>

            </div>
        </div>
        {{-- END HERO CARD --}}


        {{-- ============================================================ --}}
        {{-- STEP INDICATOR                                                --}}
        {{-- ============================================================ --}}
        <div class="bg-white rounded-2xl border border-[#E5E7EB] shadow-sm px-6 py-5">
            <div class="flex items-center justify-between max-w-lg mx-auto">

                {{-- Step 1 --}}
                <div class="flex flex-col items-center gap-1.5">
                    <div class="step-circle" :class="step >= 1 ? (step > 1 ? 'done' : 'active') : 'pending'">
                        <template x-if="step > 1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                stroke-linecap="round" stroke-linejoin="round" stroke-width="3">
                                <path d="M5 13l4 4L19 7" />
                            </svg>
                        </template>
                        <template x-if="step <= 1"><span>1</span></template>
                    </div>
                    <span class="text-xs font-medium text-center leading-tight"
                        :class="step >= 1 ? 'text-[#1F2A1D]' : 'text-gray-400'">Pilih Hari</span>
                </div>

                <div class="step-line" :class="step > 1 ? 'done' : ''"></div>

                {{-- Step 2 --}}
                <div class="flex flex-col items-center gap-1.5">
                    <div class="step-circle" :class="step >= 2 ? (step > 2 ? 'done' : 'active') : 'pending'">
                        <template x-if="step > 2">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                stroke-linecap="round" stroke-linejoin="round" stroke-width="3">
                                <path d="M5 13l4 4L19 7" />
                            </svg>
                        </template>
                        <template x-if="step <= 2"><span>2</span></template>
                    </div>
                    <span class="text-xs font-medium text-center leading-tight"
                        :class="step >= 2 ? 'text-[#1F2A1D]' : 'text-gray-400'">Pilih Slot</span>
                </div>

                <div class="step-line" :class="step > 2 ? 'done' : ''"></div>

                {{-- Step 3 --}}
                <div class="flex flex-col items-center gap-1.5">
                    <div class="step-circle" :class="step >= 3 ? (step > 3 ? 'done' : 'active') : 'pending'">
                        <template x-if="step > 3">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                stroke-linecap="round" stroke-linejoin="round" stroke-width="3">
                                <path d="M5 13l4 4L19 7" />
                            </svg>
                        </template>
                        <template x-if="step <= 3"><span>3</span></template>
                    </div>
                    <span class="text-xs font-medium text-center leading-tight"
                        :class="step >= 3 ? 'text-[#1F2A1D]' : 'text-gray-400'">Konfirmasi</span>
                </div>

                <div class="step-line" :class="step > 3 ? 'done' : ''"></div>

                {{-- Step 4 --}}
                <div class="flex flex-col items-center gap-1.5">
                    <div class="step-circle" :class="step >= 4 ? 'done' : 'pending'">
                        <template x-if="step >= 4">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                stroke-linecap="round" stroke-linejoin="round" stroke-width="3">
                                <path d="M5 13l4 4L19 7" />
                            </svg>
                        </template>
                        <template x-if="step < 4"><span>4</span></template>
                    </div>
                    <span class="text-xs font-medium text-center leading-tight"
                        :class="step >= 4 ? 'text-[#D4AF63]' : 'text-gray-400'">Selesai</span>
                </div>

            </div>
        </div>
        {{-- END STEP --}}


        {{-- ============================================================ --}}
        {{-- PILIH TANGGAL                                                 --}}
        {{-- ============================================================ --}}
        <div class="bg-white rounded-2xl border border-[#E5E7EB] shadow-sm px-6 py-5">
            <div class="flex items-center gap-2 mb-4">
                <div class="gold-icon-wrap w-8 h-8 rounded-xl flex items-center justify-center">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="#D4AF63" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2" />
                        <line x1="16" y1="2" x2="16" y2="6" />
                        <line x1="8" y1="2" x2="8" y2="6" />
                        <line x1="3" y1="10" x2="21" y2="10" />
                    </svg>
                </div>
                <h2 class="text-base font-bold text-[#1F2A1D]">Pilih Tanggal</h2>
                <span
                    class="text-xs text-[#D4AF63] font-medium bg-[#D4AF63]/10 px-2.5 py-1 rounded-full border border-[#D4AF63]/20 ml-auto">
                    7 Hari ke Depan
                </span>
            </div>

            {{-- Horizontal scroll date strip --}}
            <div class="flex gap-3 overflow-x-auto pb-2 -mx-1 px-1"
                style="scrollbar-width: thin; scrollbar-color: #D4AF63 #F8F8F5;">
                <template x-for="d in dates" :key="d.index">
                    <button type="button" class="date-card"
                        :class="{ 'selected': selectedDate === d.index, 'today-mark': d.today && selectedDate !== d.index }"
                        @click="selectDate(d.index)">
                        <div class="dc-day" x-text="d.day"></div>
                        <div class="dc-date" x-text="d.date"></div>
                        <div class="dc-month" x-text="d.month"></div>
                        <div x-show="d.today" class="mt-1">
                            <span class="text-[10px] font-semibold px-1.5 py-0.5 rounded-full"
                                :class="selectedDate === 0 ? 'bg-[#D4AF63]/20 text-[#D4AF63]' : 'bg-[#1F2A1D]/20 text-white'">
                                Hari ini
                            </span>
                        </div>
                    </button>
                </template>
            </div>

            {{-- Selected date info --}}
            <div class="mt-3 flex items-center gap-2 text-sm text-gray-500">
                <svg class="w-4 h-4 text-[#D4AF63]" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
                    <circle cx="12" cy="12" r="10" />
                    <polyline points="12 6 12 12 16 14" />
                </svg>
                Menampilkan slot untuk:
                <span class="font-semibold text-[#1F2A1D]" x-text="currentDate.full"></span>
            </div>
        </div>
        {{-- END PILIH TANGGAL --}}


        {{-- ============================================================ --}}
        {{-- SLOT GRID                                                     --}}
        {{-- ============================================================ --}}
        <div class="bg-white rounded-2xl border border-[#E5E7EB] shadow-sm px-6 py-5">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-5">
                <div class="flex items-center gap-3">
                    <div class="gold-icon-wrap w-10 h-10 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="#D4AF63" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10" />
                            <polyline points="12 6 12 12 16 14" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-base font-bold text-[#1F2A1D] flex items-center gap-2">
                            Slot Booking Tersedia
                            <span x-show="isLoadingSlots" class="inline-block animate-spin text-[#D4AF63]">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                        stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>
                            </span>
                        </h2>
                        <p class="text-xs text-gray-400">Durasi layanan ±30 menit.</p>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <button type="button" @click="fetchSlotsForDate(selectedDate)" :disabled="isLoadingSlots"
                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold bg-[#D4AF63]/10 text-[#D4AF63] border border-[#D4AF63]/30 hover:bg-[#D4AF63]/20 transition-all cursor-pointer disabled:opacity-50">
                        <svg class="w-3.5 h-3.5" :class="{ 'animate-spin': isLoadingSlots }" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        <span>Perbarui Slot</span>
                    </button>

                    <div
                        class="flex items-center gap-2 text-sm text-green-700 bg-green-50 px-3 py-1.5 rounded-full border border-green-100">
                        <span class="w-2 h-2 rounded-full bg-green-500 inline-block badge-pulse"></span>
                        <span class="font-semibold" x-text="availableCount + ' slot tersedia'"></span>
                    </div>
                </div>
            </div>

            <div x-show="isLoadingSlots" class="text-center py-12 text-gray-500 my-2">
                <svg class="animate-spin w-8 h-8 text-[#D4AF63] mx-auto mb-2" xmlns="http://www.w3.org/2000/svg"
                    fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                        stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor"
                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                    </path>
                </svg>
                <p class="text-sm font-medium">Memuat slot terbaru...</p>
            </div>

            <div x-show="!isLoadingSlots && slots.length === 0"
                class="text-center py-12 text-gray-500 bg-[#F8F8F5] rounded-2xl border border-dashed border-[#E5E7EB] my-2">
                <div class="gold-icon-wrap w-12 h-12 rounded-2xl flex items-center justify-center mx-auto mb-3">
                    <svg class="w-6 h-6 text-[#D4AF63]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="10" />
                        <polyline points="12 6 12 12 16 14" />
                    </svg>
                </div>
                <p class="font-bold text-[#1F2A1D]">Belum ada slot booking untuk tanggal ini.</p>
                <p class="text-xs text-gray-500 mt-1 max-w-sm mx-auto">Admin mungkin belum membuat jadwal slot atau kuota
                    telah ditutup. Silakan coba klik ulang tanggal ini atau pilih tanggal lainnya.</p>
            </div>

            <div x-show="!isLoadingSlots && slots.length > 0"
                class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
                <template x-for="slot in slots" :key="slot.id">
                    <div class="slot-card"
                        :class="{
                            'available': slot.status === 'tersedia',
                            'full': slot.status === 'penuh' || slot.status === 'lewat',
                            'booked': slot.status === 'terbooking',
                        }"
                        @click="pickSlot(slot)">

                        {{-- Time --}}
                        <div class="flex items-center gap-2 mb-3">
                            <div class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0"
                                :class="{
                                    'bg-green-50': slot.status === 'tersedia',
                                    'bg-red-50': slot.status === 'penuh',
                                    'bg-blue-50': slot.status === 'terbooking',
                                    'bg-gray-50': slot.status === 'lewat',
                                }">
                                <svg class="w-4.5 h-4.5"
                                    :class="{
                                        'text-green-600': slot.status === 'tersedia',
                                        'text-red-500': slot.status === 'penuh',
                                        'text-blue-500': slot.status === 'terbooking',
                                        'text-gray-500': slot.status === 'lewat',
                                    }"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="10" />
                                    <polyline points="12 6 12 12 16 14" />
                                </svg>
                            </div>
                            <span class="font-bold text-[#1F2A1D] text-sm" x-text="slot.time"></span>
                        </div>

                        {{-- Date --}}
                        <p class="text-xs text-gray-500 mb-3" x-text="currentDate.full"></p>

                        {{-- Status badge --}}
                        <div class="mb-3">
                            <template x-if="slot.status === 'tersedia'">
                                <span
                                    class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">
                                    <span class="w-1.5 h-1.5 rounded-full bg-green-500 inline-block"></span>
                                    Tersedia
                                </span>
                            </template>
                            <template x-if="slot.status === 'penuh'">
                                <span
                                    class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700">
                                    <span class="w-1.5 h-1.5 rounded-full bg-red-500 inline-block"></span>
                                    Penuh
                                </span>
                            </template>
                            <template x-if="slot.status === 'terbooking'">
                                <span
                                    class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-700">
                                    <span class="w-1.5 h-1.5 rounded-full bg-blue-500 inline-block"></span>
                                    Terbooking
                                </span>
                            </template>
                            <template x-if="slot.status === 'lewat'">
                                <span
                                    class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-700">
                                    <span class="w-1.5 h-1.5 rounded-full bg-gray-500 inline-block"></span>
                                    Waktu Lewat
                                </span>
                            </template>
                        </div>

                        {{-- CTA Button --}}
                        <template x-if="slot.status === 'tersedia'">
                            <button type="button" class="btn-gold w-full py-2 text-xs text-center rounded-lg"
                                @click.stop="pickSlot(slot)">
                                Pesan Sekarang
                            </button>
                        </template>
                        <template x-if="slot.status === 'penuh'">
                            <button type="button" disabled
                                class="w-full py-2 text-xs text-center rounded-lg font-semibold bg-gray-100 text-gray-400 cursor-not-allowed opacity-60">
                                Kuota Penuh
                            </button>
                        </template>
                        <template x-if="slot.status === 'terbooking'">
                            <button type="button" disabled
                                class="w-full py-2 text-xs text-center rounded-lg font-semibold bg-gray-100 text-gray-400 cursor-not-allowed opacity-60">
                                Sudah Dipesan
                            </button>
                        </template>
                        <template x-if="slot.status === 'lewat'">
                            <button type="button" disabled
                                class="w-full py-2 text-xs text-center rounded-lg font-semibold bg-gray-100 text-gray-400 cursor-not-allowed opacity-60">
                                Slot Berakhir
                            </button>
                        </template>

                    </div>
                </template>
            </div>
        </div>
        {{-- END SLOT GRID --}}


        {{-- ============================================================ --}}
        {{-- INFO BOX                                                      --}}
        {{-- ============================================================ --}}
        <div class="info-box-gold px-6 py-5 flex items-start gap-4">
            <div class="flex-shrink-0 w-9 h-9 rounded-xl flex items-center justify-center gold-icon-wrap">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="#D4AF63" stroke-width="2"
                    stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10" />
                    <line x1="12" y1="8" x2="12" y2="12" />
                    <line x1="12" y1="16" x2="12.01" y2="16" />
                </svg>
            </div>
            <div>
                <p class="text-sm font-bold text-[#1F2A1D] mb-2">Informasi Booking</p>
                <ul class="space-y-1.5 text-sm text-gray-600">
                    <li class="flex items-start gap-2"><span
                            class="text-[#D4AF63] font-bold mt-0.5 flex-shrink-0">•</span>Setiap slot hanya dapat digunakan
                        oleh satu pelanggan.</li>
                    <li class="flex items-start gap-2"><span
                            class="text-[#D4AF63] font-bold mt-0.5 flex-shrink-0">•</span>Durasi layanan ±30 menit per
                        slot.</li>
                    <li class="flex items-start gap-2"><span
                            class="text-[#D4AF63] font-bold mt-0.5 flex-shrink-0">•</span>Pelanggan dapat memiliki beberapa
                        booking pada tanggal yang berbeda.</li>
                    <li class="flex items-start gap-2"><span
                            class="text-[#D4AF63] font-bold mt-0.5 flex-shrink-0">•</span>Slot yang penuh atau terbooking
                        tidak dapat dipilih.</li>
                </ul>
            </div>
        </div>
        {{-- END INFO BOX --}}


        {{-- ============================================================ --}}
        {{-- MODAL: KONFIRMASI BOOKING                                     --}}
        {{-- ============================================================ --}}
        <div class="modal-backdrop" x-show="showConfirm" x-cloak @click.self="showConfirm = false; step = 2;"
            x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
            <div class="modal-box" @click.stop>
                {{-- Icon --}}
                <div class="flex justify-center mb-5">
                    <div class="gold-icon-wrap w-16 h-16 rounded-2xl flex items-center justify-center">
                        <svg class="w-8 h-8" viewBox="0 0 24 24" fill="none" stroke="#D4AF63" stroke-width="1.8"
                            stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2" />
                            <line x1="16" y1="2" x2="16" y2="6" />
                            <line x1="8" y1="2" x2="8" y2="6" />
                            <line x1="3" y1="10" x2="21" y2="10" />
                        </svg>
                    </div>
                </div>

                <h3 class="text-xl font-bold text-[#1F2A1D] text-center mb-1">Konfirmasi Booking</h3>
                <p class="text-gray-500 text-sm text-center mb-5">
                    Apakah Anda yakin ingin melakukan booking pada jadwal ini?
                </p>

                {{-- Detail --}}
                <div class="bg-[#F8F8F5] rounded-xl p-4 space-y-3 mb-5 border border-[#E5E7EB]">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Tanggal</span>
                        <span class="font-semibold text-[#1F2A1D]" x-text="currentDate.full"></span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Jam</span>
                        <span class="font-semibold text-[#1F2A1D]" x-text="selectedSlot ? selectedSlot.time : ''"></span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Durasi</span>
                        <span class="font-semibold text-[#1F2A1D]">30 Menit</span>
                    </div>
                </div>

                {{-- Buttons --}}
                <div class="flex gap-3">
                    <button type="button"
                        class="flex-1 py-2.5 rounded-xl text-sm font-semibold bg-gray-100 text-gray-600 hover:bg-gray-200 transition-colors"
                        @click="showConfirm = false; step = 2;">
                        Batal
                    </button>
                    <button type="button" class="btn-gold flex-1 py-2.5 text-sm" @click="confirmBooking()">
                        Ya, Booking
                    </button>
                </div>
            </div>
        </div>
        {{-- END MODAL KONFIRMASI --}}


        {{-- ============================================================ --}}
        {{-- MODAL: BOOKING BERHASIL                                       --}}
        {{-- ============================================================ --}}
        <div class="modal-backdrop" x-show="showSuccess" x-cloak @click.self="resetAll()"
            x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
            <div class="modal-box text-center" @click.stop>

                {{-- Success icon --}}
                <div class="flex justify-center mb-5">
                    <div class="w-20 h-20 rounded-full flex items-center justify-center"
                        style="background: rgba(34,197,94,0.12); border: 2px solid rgba(34,197,94,0.25);">
                        <svg class="w-10 h-10 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
                            <polyline points="22 4 12 14.01 9 11.01" />
                        </svg>
                    </div>
                </div>

                <h3 class="text-xl font-bold text-[#1F2A1D] mb-2">Booking Berhasil! 🎉</h3>
                <p class="text-gray-500 text-sm leading-relaxed mb-5">
                    Reservasi Anda berhasil dikirim.<br>
                    Silakan menunggu konfirmasi dari admin.
                </p>

                {{-- Detail ringkas --}}
                <div class="bg-green-50 border border-green-100 rounded-xl p-4 mb-5 text-left space-y-2">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Tanggal</span>
                        <span class="font-semibold text-[#1F2A1D]" x-text="currentDate.full"></span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Slot</span>
                        <span class="font-semibold text-[#1F2A1D]" x-text="selectedSlot ? selectedSlot.time : ''"></span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Status</span>
                        <span class="font-semibold text-green-600">Menunggu Konfirmasi</span>
                    </div>
                </div>

                <div class="flex flex-col gap-3">
                    <a href="{{ route('customer.status') }}"
                        class="btn-dark w-full py-2.5 text-sm text-center block rounded-xl">
                        Lihat Status Booking
                    </a>
                    <button type="button"
                        class="w-full py-2.5 rounded-xl text-sm font-semibold text-gray-600 border-2 border-gray-200 hover:bg-gray-50 transition-colors"
                        @click="resetAll()">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
        {{-- END MODAL BERHASIL --}}

    </div>

@endsection
