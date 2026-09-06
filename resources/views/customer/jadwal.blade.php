@extends('customer.layouts.app')

@section('title', config('app.name', 'Hero Barbershop') . ' - Jadwal Operasional')
@section('description', 'Informasi jam operasional Hero Barbershop. Lihat jadwal lengkap sebelum melakukan reservasi.')

@push('head')
<style>
    /* ── Jadwal Page Specific ──────────────────── */
    .jadwal-hero-card {
        background: #ffffff;
        border-radius: 1.5rem;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
        border: 1px solid rgba(212, 176, 106, 0.3);
    }

    .info-card {
        background: #ffffff;
        border-radius: 1rem;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(212, 176, 106, 0.2);
        transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
    }
    .info-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 32px rgba(0, 0, 0, 0.15);
        border-color: rgba(212, 176, 106, 0.6);
    }

    .schedule-card {
        background: #ffffff;
        border-radius: 1rem;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(212, 176, 106, 0.2);
        overflow: hidden;
    }

    .schedule-table thead tr {
        background: #F8F8F5;
    }
    .schedule-table tbody tr {
        transition: background-color 0.2s ease;
    }
    .schedule-table tbody tr:hover {
        background-color: #F9FAFB;
    }
    .schedule-table tbody tr.row-today {
        background-color: rgba(31, 42, 29, 0.03); /* Faint brand green instead of gold */
    }
    .schedule-table tbody tr.row-today:hover {
        background-color: rgba(31, 42, 29, 0.06);
    }

    .gold-icon-wrap {
        background: linear-gradient(135deg, rgba(212,176,106,0.15), rgba(212,176,106,0.08));
        border: 1px solid rgba(212,176,106,0.25);
    }

    .info-box {
        background: #FFFBEB;
        border: 1.5px solid rgba(212, 176, 106, 0.45);
        border-radius: 1rem;
    }

    .status-badge-open {
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 600;
        background-color: #dcfce7;
        color: #15803d;
    }
    .status-badge-closed {
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 600;
        background-color: #fee2e2;
        color: #b91c1c;
    }

    .today-badge-open {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.375rem 1rem;
        border-radius: 9999px;
        font-size: 0.8rem;
        font-weight: 600;
        background: #dcfce7;
        color: #15803d;
        border: 1px solid rgba(21,128,61,0.2);
    }
    .today-badge-closed {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.375rem 1rem;
        border-radius: 9999px;
        font-size: 0.8rem;
        font-weight: 600;
        background: #fee2e2;
        color: #b91c1c;
        border: 1px solid rgba(185,28,28,0.2);
    }
    .today-dot-green {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: #22c55e;
        animation: cust-badge-pulse 2s infinite;
    }
    .today-dot-red {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: #ef4444;
        animation: cust-badge-pulse 2s infinite;
    }
</style>
@endpush

@section('content')



<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-8 relative z-10">

    {{-- ============================================================ --}}
    {{-- HERO CARD                                                     --}}
    {{-- ============================================================ --}}
    <div class="jadwal-hero-card p-8 lg:p-10">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">

            {{-- LEFT: Icon + Title + Desc --}}
            <div class="flex items-start gap-5">
                {{-- Gold calendar icon --}}
                <div class="gold-icon-wrap w-16 h-16 rounded-2xl flex items-center justify-center flex-shrink-0">
                    <svg class="w-8 h-8" viewBox="0 0 24 24" fill="none"
                         stroke="#D4AF63" stroke-width="1.8"
                         stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                        <line x1="16" y1="2" x2="16" y2="6"/>
                        <line x1="8" y1="2" x2="8" y2="6"/>
                        <line x1="3" y1="10" x2="21" y2="10"/>
                    </svg>
                </div>

                <div>
                    <h1 class="text-2xl lg:text-3xl font-bold text-[#1F2A1D] mb-1">
                        Jadwal Operasional
                    </h1>
                    <p class="text-gray-500 text-sm lg:text-base leading-relaxed max-w-lg">
                        Informasi jam operasional Hero Barbershop. Silakan lihat jadwal
                        sebelum melakukan reservasi.
                    </p>
                </div>
            </div>

            {{-- RIGHT: Today + Status Badge --}}
            <div class="flex flex-col items-start lg:items-end gap-2 flex-shrink-0">
                <div class="flex items-center gap-2 text-sm text-gray-500 font-medium">
                    <svg class="w-4 h-4 text-[#D4AF63]" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                        <line x1="16" y1="2" x2="16" y2="6"/>
                        <line x1="8" y1="2" x2="8" y2="6"/>
                        <line x1="3" y1="10" x2="21" y2="10"/>
                    </svg>
                    Hari ini:
                    <span class="font-semibold text-[#1F2A1D]">{{ $hariIni }}</span>
                </div>

                @if($sedangBuka)
                    <div class="today-badge-open">
                        <span class="today-dot-green"></span>
                        Sedang Buka
                    </div>
                @else
                    <div class="today-badge-closed">
                        <span class="today-dot-red"></span>
                        Sedang Tutup
                    </div>
                @endif
            </div>

        </div>
    </div>
    {{-- END HERO CARD --}}


    {{-- ============================================================ --}}
    {{-- 3 INFO CARDS                                                  --}}
    {{-- ============================================================ --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 lg:gap-6">

        {{-- Card 1: Hari Ini --}}
        <div class="info-card p-6">
            <div class="flex items-center gap-3 mb-4">
                <div class="gold-icon-wrap w-10 h-10 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none"
                         stroke="#D4AF63" stroke-width="1.8"
                         stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                        <line x1="16" y1="2" x2="16" y2="6"/>
                        <line x1="8" y1="2" x2="8" y2="6"/>
                        <line x1="3" y1="10" x2="21" y2="10"/>
                    </svg>
                </div>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Hari Hari Ini</p>
            </div>
            <p class="text-2xl font-bold text-[#1F2A1D]">{{ $hariIni }}</p>
            <p class="text-xs text-gray-400 mt-1">{{ now()->locale('id')->translatedFormat('d F Y') }}</p>
        </div>

        {{-- Card 2: Jam Operasional --}}
        <div class="info-card p-6">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center"
                     style="background: rgba(31,42,29,0.07); border: 1px solid rgba(31,42,29,0.12);">
                    <svg class="w-5 h-5 text-[#1F2A1D]" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="1.8"
                         stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"/>
                        <polyline points="12 6 12 12 16 14"/>
                    </svg>
                </div>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Jam Operasional Hari Ini</p>
            </div>
            <p class="text-xl font-bold text-[#1F2A1D]">{{ $jamHariIni }}</p>
            <p class="text-xs text-gray-400 mt-1">Waktu Indonesia Barat (WIB)</p>
        </div>

        {{-- Card 3: Status --}}
        <div class="info-card p-6">
            <div class="flex items-center gap-3 mb-4">
                @if($sedangBuka)
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center"
                         style="background: rgba(34,197,94,0.10); border:1px solid rgba(34,197,94,0.2);">
                        <svg class="w-5 h-5 text-green-600"
                             viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="1.8"
                             stroke-linecap="round" stroke-linejoin="round">
                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                            <polyline points="22 4 12 14.01 9 11.01"/>
                        </svg>
                    </div>
                @else
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center"
                         style="background: rgba(239,68,68,0.10); border:1px solid rgba(239,68,68,0.2);">
                        <svg class="w-5 h-5 text-red-500"
                             viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="1.8"
                             stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"/>
                            <line x1="15" y1="9" x2="9" y2="15"/>
                            <line x1="9" y1="9" x2="15" y2="15"/>
                        </svg>
                    </div>
                @endif
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Status</p>
            </div>
            @if($sedangBuka)
                <p class="text-2xl font-bold text-green-600">Buka</p>
                <p class="text-xs text-gray-400 mt-1">Menerima reservasi hari ini</p>
            @else
                <p class="text-2xl font-bold text-red-500">Tutup</p>
                <p class="text-xs text-gray-400 mt-1">Tidak menerima reservasi hari ini</p>
            @endif
        </div>

    </div>
    {{-- END INFO CARDS --}}


    {{-- ============================================================ --}}
    {{-- SCHEDULE TABLE CARD                                           --}}
    {{-- ============================================================ --}}
    <div class="schedule-card">

        {{-- Card Header --}}
        <div class="px-6 lg:px-8 py-5 border-b border-[#E5E7EB] flex items-center justify-between flex-wrap gap-3">
            <div class="flex items-center gap-3">
                <div class="gold-icon-wrap w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none"
                         stroke="#D4AF63" stroke-width="1.8"
                         stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                        <line x1="16" y1="2" x2="16" y2="6"/>
                        <line x1="8" y1="2" x2="8" y2="6"/>
                        <line x1="3" y1="10" x2="21" y2="10"/>
                    </svg>
                </div>
                <div>
                    <h2 class="text-base font-bold text-[#1F2A1D]">📅 Jadwal Mingguan</h2>
                    <p class="text-xs text-gray-400 mt-0.5">Jam operasional Hero Barbershop.</p>
                </div>
            </div>
            <span class="text-xs font-medium text-[#D4AF63] bg-[#D4AF63]/10 px-3 py-1.5 rounded-full border border-[#D4AF63]/20">
                Hero Barbershop
            </span>
        </div>

        {{-- Table: horizontal scroll on mobile --}}
        <div class="overflow-x-auto">
            <table class="w-full text-sm schedule-table" style="min-width: 520px;">
                <thead>
                    <tr>
                        <th class="text-left px-6 lg:px-8 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider whitespace-nowrap">
                            Hari
                        </th>
                        <th class="text-left px-5 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider whitespace-nowrap">
                            Jam Buka
                        </th>
                        <th class="text-left px-5 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider whitespace-nowrap">
                            Jam Tutup
                        </th>
                        <th class="text-left px-5 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider whitespace-nowrap">
                            Status
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($jadwal as $item)
                        @php $isToday = ($hariIni === $item->hari); @endphp
                        <tr class="{{ $isToday ? 'row-today' : '' }}">

                            {{-- Hari --}}
                            <td class="px-6 lg:px-8 py-4">
                                <div class="flex items-center gap-2.5">
                                    @if($isToday)
                                        <span class="w-2 h-2 rounded-full bg-[#D4AF63] badge-pulse flex-shrink-0"></span>
                                    @else
                                        <span class="w-2 h-2 rounded-full bg-gray-200 flex-shrink-0"></span>
                                    @endif
                                    <span class="{{ $isToday ? 'font-bold text-[#1F2A1D]' : 'font-medium text-gray-700' }} whitespace-nowrap">
                                        {{ $item->hari }}
                                    </span>
                                    @if($isToday)
                                        <span class="text-xs font-semibold text-[#D4AF63] bg-[#D4AF63]/10 px-2 py-0.5 rounded-full whitespace-nowrap">
                                            Hari ini
                                        </span>
                                    @endif
                                </div>
                            </td>

                            {{-- Jam Buka --}}
                            <td class="px-5 py-4">
                                <span class="font-semibold text-gray-700 whitespace-nowrap">
                                    {{ $item->jam_buka ? \Carbon\Carbon::parse($item->jam_buka)->format('H:i') . ' WIB' : '—' }}
                                </span>
                            </td>

                            {{-- Jam Tutup --}}
                            <td class="px-5 py-4">
                                <span class="font-semibold text-gray-700 whitespace-nowrap">
                                    {{ $item->jam_tutup ? \Carbon\Carbon::parse($item->jam_tutup)->format('H:i') . ' WIB' : '—' }}
                                </span>
                            </td>

                            {{-- Status --}}
                            <td class="px-5 py-4">
                                @if(strtolower($item->status) === 'buka')
                                    <span class="status-badge-open">
                                        <span class="w-1.5 h-1.5 rounded-full bg-green-500 inline-block"></span>
                                        Buka
                                    </span>
                                @else
                                    <span class="status-badge-closed">
                                        <span class="w-1.5 h-1.5 rounded-full bg-red-500 inline-block"></span>
                                        Tutup
                                    </span>
                                @endif
                            </td>

                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Card Footer --}}
        <div class="px-6 lg:px-8 py-4 bg-gray-50/60 border-t border-[#E5E7EB] flex items-center justify-between flex-wrap gap-2">
            <p class="text-xs text-gray-400">
                Menampilkan {{ $jadwal->count() }} hari operasional
            </p>
            <a href="{{ route('customer.booking') }}"
               class="inline-flex items-center gap-1.5 text-sm font-semibold text-[#1F2A1D] hover:text-[#D4AF63] transition-colors duration-200">
                Buat Booking Sekarang
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                     stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
                    <path d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        </div>

    </div>
    {{-- END SCHEDULE TABLE CARD --}}


    {{-- ============================================================ --}}
    {{-- INFO BOX                                                      --}}
    {{-- ============================================================ --}}
    <div class="info-box px-6 lg:px-8 py-5 flex items-start gap-4">

        {{-- Info Icon --}}
        <div class="flex-shrink-0 w-9 h-9 rounded-xl flex items-center justify-center gold-icon-wrap">
            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none"
                 stroke="#D4AF63" stroke-width="2"
                 stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"/>
                <line x1="12" y1="8" x2="12" y2="12"/>
                <line x1="12" y1="16" x2="12.01" y2="16"/>
            </svg>
        </div>

        {{-- Content --}}
        <div>
            <p class="text-sm font-bold text-[#1F2A1D] mb-2">Informasi Penting</p>
            <ul class="space-y-1.5 text-sm text-gray-600">
                <li class="flex items-start gap-2">
                    <span class="text-[#D4AF63] font-bold mt-0.5 flex-shrink-0">•</span>
                    Booking hanya dapat dilakukan pada hari yang berstatus <strong class="text-[#1F2A1D]">Buka</strong>.
                </li>
                <li class="flex items-start gap-2">
                    <span class="text-[#D4AF63] font-bold mt-0.5 flex-shrink-0">•</span>
                    Slot booking mengikuti jam operasional yang berlaku.
                </li>
                <li class="flex items-start gap-2">
                    <span class="text-[#D4AF63] font-bold mt-0.5 flex-shrink-0">•</span>
                    Durasi setiap layanan ±30 menit.
                </li>
            </ul>
        </div>

    </div>
    {{-- END INFO BOX --}}

</div>

@endsection
