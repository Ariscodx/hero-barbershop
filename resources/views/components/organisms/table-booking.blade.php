@props(['bookings' => []])
<div class="bg-brand-card rounded-xl shadow-sm border border-brand-border overflow-hidden">
    <div class="px-8 py-6 border-b border-gray-100 flex items-center justify-between">
        <h3 class="text-xl font-bold text-brand-text font-poppins">Daftar Pemesan Hari Ini</h3>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50/50 text-gray-500 text-xs uppercase tracking-wider font-semibold">
                    <th class="px-8 py-5">Nama</th>
                    <th class="px-8 py-5">Tanggal</th>
                    <th class="px-8 py-5">Jam</th>
                    <th class="px-8 py-5">Status</th>
                    <th class="px-8 py-5 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-sm">
                @forelse($bookings as $booking)
                    <tr class="hover:bg-gray-50/80 transition-colors">
                        <td class="px-8 py-5 whitespace-nowrap">
                            <div class="font-medium text-brand-text text-base">{{ $booking->nama }}</div>
                            <div class="text-gray-500 text-xs mt-0.5">{{ $booking->email }}</div>
                        </td>
                        <td class="px-8 py-5 text-gray-600 font-medium">
                            {{ \Carbon\Carbon::parse($booking->tgl_booking)->translatedFormat('d M Y') }}</td>
                        <td class="px-8 py-5 text-gray-600 font-medium">
                            {{ \Carbon\Carbon::parse($booking->jam_booking)->format('H:i') }}</td>
                        <td class="px-8 py-5">
                            @if ($booking->status == 'terkonfirmasi' || $booking->status == 'selesai')
                                <span
                                    class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                    {{ ucfirst($booking->status) }}
                                </span>
                            @elseif($booking->status == 'berlangsung')
                                <span
                                    class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800">
                                    {{ ucfirst($booking->status) }}
                                </span>
                            @elseif($booking->status == 'dibatalkan')
                                <span
                                    class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800">
                                    {{ ucfirst($booking->status) }}
                                </span>
                            @else
                                <span
                                    class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-800">
                                    {{ ucfirst($booking->status) }}
                                </span>
                            @endif
                        </td>
                        <td class="px-8 py-5 text-right">
                            <a href="{{ route('admin.booking') }}"
                                class="inline-flex items-center justify-center px-5 py-2 bg-accent text-white text-xs font-semibold rounded-full hover:bg-gold hover:shadow-lg hover:scale-[1.05] active:scale-[0.95] transition-all duration-200 shadow-sm focus:outline-none focus:ring-2 focus:ring-accent focus:ring-offset-1 cursor-pointer">
                                Detail
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-8 py-5 text-center text-gray-500">Tidak ada pemesan hari ini.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="px-8 py-4 border-t border-gray-100 bg-gray-50/50 text-right">
        <a href="{{ route('admin.booking') }}"
            class="text-sm font-semibold text-accent hover:text-hover transition-colors">Lihat Semua Pemesan &rarr;</a>
    </div>
</div>
