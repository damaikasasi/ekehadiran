<x-dashboard-layout :title="'Presensi Pegawai'">

    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-900 tracking-tight">Rekapitulasi Kehadiran</h2>
        <p class="mt-1 text-sm text-gray-500">Ringkasan kehadiran dan akumulasi keterlambatan per pegawai</p>
    </div>

    @include('kehadiran._tabs')

    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
        <!-- Filter & Action Bar (Filter di Kiri, Export di Kanan) -->
        <div class="flex flex-wrap items-end justify-between gap-4 mb-6">
            <form method="GET" action="{{ route('kehadiran.rekap') }}" class="flex flex-wrap items-end gap-2.5">
                <div>
                    <label for="rekap-bulan" class="block text-xs font-semibold text-gray-700 mb-1.5 whitespace-nowrap">Bulan</label>
                    <select id="rekap-bulan" name="bulan" aria-label="Pilih Bulan Rekapitulasi" class="border-gray-300 rounded-xl text-sm pl-3.5 pr-8 py-2.5 focus:ring-green-500 focus:border-green-500 shadow-sm text-gray-700 font-medium">
                        <option value="semua" {{ $bulan === 'semua' ? 'selected' : '' }}>Semua Bulan</option>
                        @foreach (['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'] as $i => $namaBulan)
                            <option value="{{ $i + 1 }}" {{ (string)$bulan === (string)($i + 1) ? 'selected' : '' }}>{{ $namaBulan }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="rekap-tahun" class="block text-xs font-semibold text-gray-700 mb-1.5 whitespace-nowrap">Tahun</label>
                    <select id="rekap-tahun" name="tahun" aria-label="Pilih Tahun Rekapitulasi" class="border-gray-300 rounded-xl text-sm pl-3.5 pr-8 py-2.5 focus:ring-green-500 focus:border-green-500 shadow-sm text-gray-700 font-medium">
                        <option value="semua" {{ $tahun === 'semua' ? 'selected' : '' }}>Semua Tahun</option>
                        @for ($y = now()->year; $y >= now()->year - 3; $y--)
                            <option value="{{ $y }}" {{ (string)$tahun === (string)$y ? 'selected' : '' }}>{{ $y }}</option>
                        @endfor
                    </select>
                </div>
                <button type="submit" class="inline-flex items-center justify-center gap-1.5 rounded-xl bg-[#0b602b] hover:bg-[#084d22] px-4 py-2.5 text-sm font-semibold text-white transition shadow-sm cursor-pointer whitespace-nowrap">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <span>Cari</span>
                </button>
                @if((request('bulan') && request('bulan') !== 'semua') || (request('tahun') && request('tahun') !== 'semua'))
                    <a href="{{ route('kehadiran.rekap') }}" aria-label="Reset Filter" title="Reset Filter" class="inline-flex items-center justify-center p-2.5 rounded-xl border border-gray-300 bg-gray-50 hover:bg-gray-100 text-gray-600 transition shadow-sm shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </a>
                @endif
            </form>

            @if($hasSearched)
                <div class="shrink-0">
                    <a href="{{ route('kehadiran.rekap.export', ['bulan' => $bulan, 'tahun' => $tahun]) }}"
                       class="inline-flex items-center justify-center gap-2 rounded-full bg-[#0b602b] hover:bg-[#084d22] px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:shadow transition whitespace-nowrap">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                        </svg>
                        <span>Download Excel</span>
                    </a>
                </div>
            @endif
        </div>

        @if($hasSearched)
            <div class="overflow-x-auto rounded-xl border border-gray-200">
                <table class="w-full text-center border-collapse">
                    <thead class="bg-[#0b602b]">
                        <tr>
                            <th scope="col" class="px-6 py-4 text-xs font-semibold uppercase tracking-wider text-white whitespace-nowrap text-center">Nama Pegawai</th>
                            <th scope="col" class="px-6 py-4 text-xs font-semibold uppercase tracking-wider text-white whitespace-nowrap text-center">Hadir</th>
                            <th scope="col" class="px-6 py-4 text-xs font-semibold uppercase tracking-wider text-white whitespace-nowrap text-center">WFO</th>
                            <th scope="col" class="px-6 py-4 text-xs font-semibold uppercase tracking-wider text-white whitespace-nowrap text-center">WFH</th>
                            <th scope="col" class="px-6 py-4 text-xs font-semibold uppercase tracking-wider text-white whitespace-nowrap text-center">Terlambat</th>
                            <th scope="col" class="px-6 py-4 text-xs font-semibold uppercase tracking-wider text-white whitespace-nowrap text-center">Akumulasi Telat</th>
                            <th scope="col" class="px-6 py-4 text-xs font-semibold uppercase tracking-wider text-white whitespace-nowrap text-center">Sakit/Izin</th>
                            <th scope="col" class="px-6 py-4 text-xs font-semibold uppercase tracking-wider text-white whitespace-nowrap text-center">Alpha</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-sm text-gray-700">
                        @forelse ($rekap as $pegawaiId => $statuses)
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="px-6 py-4 text-sm text-gray-800 font-semibold whitespace-nowrap text-center align-middle">
                                    {{ $statuses->first()->pegawai->nama ?? '-' }}
                                </td>
                                <td class="px-6 py-4 text-sm text-green-700 font-bold whitespace-nowrap text-center align-middle">
                                    {{ $statuses->whereIn('status', ['hadir', 'terlambat'])->sum('total') }}
                                </td>
                                <td class="px-6 py-4 text-sm text-emerald-700 font-bold whitespace-nowrap text-center align-middle">
                                    {{ $statuses->where('jenis', 'wfo')->sum('total') }}
                                </td>
                                <td class="px-6 py-4 text-sm text-blue-700 font-bold whitespace-nowrap text-center align-middle">
                                    {{ $statuses->where('jenis', 'wfh')->sum('total') }}
                                </td>
                                <td class="px-6 py-4 text-sm text-amber-800 font-bold whitespace-nowrap text-center align-middle">
                                    {{ $statuses->where('status', 'terlambat')->sum('total') }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-700 whitespace-nowrap text-center align-middle">
                                    @php
                                        $totalMenit = $statuses->sum('total_menit_telat');
                                        $jam = floor($totalMenit / 60);
                                        $menit = $totalMenit % 60;
                                    @endphp
                                    <span class="inline-block px-2.5 py-1 text-xs font-bold rounded-full {{ $totalMenit > 0 ? 'bg-red-50 text-red-700 border border-red-100' : 'bg-gray-100 text-gray-600 border border-gray-200' }} whitespace-nowrap">
                                        {{ $totalMenit > 0 ? "{$jam} jam {$menit} menit" : 'Tidak ada' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-blue-700 font-bold whitespace-nowrap text-center align-middle">
                                    {{ $statuses->whereIn('status', ['sakit', 'izin'])->sum('total') }}
                                </td>
                                <td class="px-6 py-4 text-sm text-red-700 font-bold whitespace-nowrap text-center align-middle">
                                    {{ $statuses->where('status', 'alpha')->sum('total') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-12 text-center text-gray-500">
                                    Belum ada data untuk direkap pada periode ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        @else
            <div class="rounded-xl border border-dashed border-gray-300 p-12 text-center text-gray-500">
                <svg class="w-12 h-12 mx-auto mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <p class="font-medium text-gray-700 mb-1">Silakan pilih filter Bulan dan Tahun</p>
                <p class="text-xs text-gray-500">Klik tombol Cari untuk menampilkan data rekapitulasi kehadiran.</p>
            </div>
        @endif
    </div>

</x-dashboard-layout>