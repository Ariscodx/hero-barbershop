<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', 'Hero Barbershop') . ' - Dashboard Pelanggan')</title>
    <meta name="description" content="@yield('description', 'Dashboard Pelanggan Hero Barbershop — Booking layanan potong rambut kini lebih mudah secara online.')">

    {{-- Fonts: preload untuk cegah FOUT (flash of unstyled text) --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    {{-- Vite assets --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- ===================== GLOBAL STYLES ===================== --}}
    {{--
        Semua CSS global customer ada di sini — TIDAK di dalam komponen
        agar browser tidak merecompute style tiap pindah halaman.

        Aturan navbar:
        - Hanya transition-colors dan transition-opacity
        - TIDAK ada transition-all, animate-pulse, animate-ping pada navbar
        - CSS active state dihitung server-side (PHP), bukan JS

        Animasi konten halaman (hero section dll) tetap boleh ada,
        tapi TIDAK pada elemen navbar.
    --}}
    <style>
        /* ── Base ─────────────────────────────────────── */
        *,
        *::before,
        *::after {
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #F4F8F5;
            color: #1F2A1D;
        }

        /* Mencegah FOUC pada logo */
        img {
            display: block;
        }

        /* Alpine.js: sembunyikan elemen x-cloak sebelum JS siap */
        [x-cloak] {
            display: none !important;
        }

        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
        }

        ::-webkit-scrollbar-track {
            background: #F4F8F5;
        }

        ::-webkit-scrollbar-thumb {
            background: #D4B06A;
            border-radius: 3px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #C49A55;
        }

        /* ── Navbar — ZERO flicker rules ─────────────── */
        /*
            Navbar sticky, tidak ada animasi masuk/keluar.
            Active state adalah static CSS class yang di-render server-side.
        */
        .customer-nav {
            position: sticky;
            top: 0;
            z-index: 50;
            /* Gunakan will-change transform agar browser alokasikan layer GPU
               sehingga navbar tidak re-paint saat konten di bawah bergerak */
            will-change: transform;
            /* Backdrop blur ringan supaya navbar tetap terbaca saat discroll */
            backdrop-filter: blur(0px);
        }

        /* Nav link base — hanya transition-colors */
        .cust-nav-link {
            display: inline-flex;
            align-items: center;
            padding: 0.5rem 1rem;
            font-size: 0.875rem;
            font-weight: 500;
            color: #6B7280;
            border-radius: 0.75rem;
            position: relative;
            text-decoration: none;
            transition: color 150ms ease-in-out, background-color 150ms ease-in-out;
        }

        .cust-nav-link:hover {
            color: #1F2A1D;
            background-color: #F3F4F6;
        }

        /* Active nav link — rendered statically by PHP, no JS needed */
        .cust-nav-active {
            color: #D4B06A !important;
            background-color: rgba(212, 176, 106, 0.08);
            border-radius: 0.75rem;
            font-weight: 600;
        }

        /* Underline tipis di bawah menu aktif */
        .cust-nav-active::after {
            content: '';
            position: absolute;
            bottom: 5px;
            left: 50%;
            transform: translateX(-50%);
            width: 18px;
            height: 2px;
            background: #D4B06A;
            border-radius: 2px;
        }

        /* Avatar pelanggan */
        .cust-avatar {
            width: 2.25rem;
            height: 2.25rem;
            border-radius: 9999px;
            background: linear-gradient(135deg, #1F2A1D, #2D3E2A);
            color: #fff;
            font-size: 0.875rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        /* Mobile nav link */
        .cust-mobile-link {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem 1rem;
            border-radius: 0.75rem;
            font-size: 0.875rem;
            font-weight: 500;
            color: #4B5563;
            text-decoration: none;
            transition: color 150ms ease-in-out, background-color 150ms ease-in-out;
        }

        .cust-mobile-link:hover {
            background-color: #F9FAFB;
            color: #1F2A1D;
        }

        .cust-mobile-active {
            background-color: rgba(212, 176, 106, 0.10);
            color: #D4B06A !important;
            font-weight: 600;
        }

        /* ── Content animations (BUKAN navbar) ──────── */
        /* Card hover — hanya untuk konten halaman */
        .card-hover {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .card-hover:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.10);
        }

        /* Badge pulse — HANYA untuk konten hero, tidak di navbar */
        .badge-pulse {
            animation: cust-badge-pulse 2.5s ease-in-out infinite;
        }

        @@keyframes cust-badge-pulse {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.5;
            }
        }

        /* Shimmer — HANYA untuk konten, tidak di navbar */
        .content-shimmer {
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.06), transparent);
            background-size: 200% 100%;
            animation: cust-shimmer 3s ease-in-out infinite;
        }

        @@keyframes cust-shimmer {
            0% {
                background-position: -200% 0;
            }

            100% {
                background-position: 200% 0;
            }
        }

        /* Page content fade-in halus saat pertama load */
        .page-content {
            animation: cust-fadein 0.18s ease-in-out;
        }

        @@keyframes cust-fadein {
            from {
                opacity: 0.85;
            }

            to {
                opacity: 1;
            }
        }
    </style>

    {{-- Slot untuk tambahan head (meta, title, dsb) per halaman --}}
    @stack('head')
</head>

<body class="antialiased">

    {{-- Navbar — satu komponen, dipanggil sekali dari layout --}}
    <x-customer.navbar />

    {{-- Konten halaman --}}
    <main class="min-h-screen page-content">
        @yield('content')
    </main>

    {{-- ===== FOOTER ===== --}}
    <footer class="bg-[#1F2A1D] text-white mt-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-10">

                {{-- Brand --}}
                <div>
                    <div class="flex items-center gap-3 mb-4">
                        <img src="{{ asset('images/logo_hero_barbershop.png') }}" alt="Hero Barbershop"
                            class="object-contain w-auto opacity-90 ml-1" style="height: 80px;">
                        <div class="flex flex-col leading-none">
                            <span class="text-base font-bold tracking-widest text-white">HERO</span>
                            <span
                                class="text-xs font-semibold tracking-[0.2em] text-[#D4B06A] uppercase">Barbershop</span>
                        </div>
                    </div>
                    <p class="text-gray-400 text-sm leading-relaxed">
                        Layanan potong rambut profesional dengan pengalaman terbaik.
                        Booking mudah, hasil memuaskan.
                    </p>
                </div>

                {{-- Kontak --}}
                <div>
                    <h4 class="font-semibold text-[#D4B06A] mb-4 uppercase tracking-wider text-sm">Kontak & Sosial</h4>
                    <ul class="space-y-3 text-sm text-gray-400">
                        <li class="flex items-start gap-2">
                            <svg class="w-4 h-4 text-[#D4B06A] mt-0.5 flex-shrink-0" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            Mungkid, Magelang
                        </li>
                        <li>
                            <a href="https://instagram.com/herobarbershop" target="_blank" rel="noopener"
                                class="flex items-center gap-2 transition-colors duration-200 ease-in-out hover:text-[#D4B06A]">
                                <svg class="w-4 h-4 text-[#D4B06A]" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z" />
                                </svg>
                                @herobarbershop
                            </a>
                        </li>
                        <li>
                            <a href="https://wa.me/6281542435587" target="_blank" rel="noopener"
                                class="flex items-center gap-2 transition-colors duration-200 ease-in-out hover:text-[#D4B06A]">
                                <svg class="w-4 h-4 text-[#D4B06A]" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z" />
                                </svg>
                                +62 812-3456-789
                            </a>
                        </li>
                    </ul>
                </div>

            </div>

            <div
                class="border-t border-gray-700/50 mt-10 pt-6 flex flex-col sm:flex-row items-center justify-between gap-2">
                <p class="text-gray-500 text-xs">© {{ date('Y') }} Hero Barbershop. All rights reserved.</p>
                <p class="text-gray-600 text-xs">Dibuat dengan ❤ untuk pelanggan setia kami</p>
            </div>
        </div>
    </footer>
    {{-- ===== END FOOTER ===== --}}

    {{-- Slot untuk script tambahan per halaman --}}
    @stack('scripts')

    {{-- Modal Konfirmasi Logout --}}
    <x-organisms.logout-modal />

</body>

</html>
{{-- ===== END FOOTER ===== --}}

{{-- Slot untuk script tambahan per halaman --}}
@stack('scripts')

{{-- Modal Konfirmasi Logout --}}
<x-organisms.logout-modal />

</body>

</html>
