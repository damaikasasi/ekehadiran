<x-dashboard-layout :title="'Rekap Kehadiran Saya'">

    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-900 tracking-tight">Rekap Kehadiran Saya</h2>
        <p class="mt-1 text-sm text-gray-500">Ringkasan kehadiran pribadi per bulan</p>
    </div>

    <style>
        .rekap-cards-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
            margin-bottom: 24px;
        }
        @media (min-width: 640px) {
            .rekap-cards-grid {
                grid-template-columns: repeat(3, 1fr);
            }
        }
        @media (min-width: 1024px) {
            .rekap-cards-grid {
                grid-template-columns: repeat(auto-fit, minmax(130px, 1fr));
            }
        }
        .rekap-card-item {
            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 14px;
            padding: 10px 12px;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            display: flex;
            align-items: center;
            gap: 10px;
            min-width: 0;
            transition: box-shadow 0.15s ease, transform 0.15s ease;
        }
        .rekap-card-item:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06);
        }
        .rekap-icon-box {
            width: 34px;
            height: 34px;
            min-width: 34px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .rekap-icon-box.hadir { background-color: #dcfce7 !important; color: #047857 !important; }
        .rekap-icon-box.terlambat { background-color: #fef3c7 !important; color: #d97706 !important; }
        .rekap-icon-box.telat { background-color: #fee2e2 !important; color: #dc2626 !important; }
        .rekap-icon-box.izin { background-color: #dbeafe !important; color: #2563eb !important; }
        .rekap-icon-box.alpha { background-color: #ffe4e6 !important; color: #e11d48 !important; }
        .rekap-icon-box.wfo { background-color: #e0e7ff !important; color: #4338ca !important; }
        .rekap-icon-box.wfh { background-color: #f3e8ff !important; color: #7e22ce !important; }
        .rekap-icon-box.upacara { background-color: #fef08a !important; color: #854d0e !important; }
    </style>

    <!-- Ringkasan Cards (Sinkron dengan isi tabel) -->
    <div class="rekap-cards-grid">
        <!-- Hadir -->
        <div class="rekap-card-item">
            <div class="rekap-icon-box hadir">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div style="min-width: 0; flex: 1;">
                <p style="font-size: 10px; font-weight: 700; color: #6b7280; text-transform: uppercase; letter-spacing: 0.05em; margin: 0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">Hadir</p>
                <h4 style="font-size: 18px; font-weight: 900; color: #047857; line-height: 1.2; margin: 2px 0 0 0;">{{ $ringkasan['hadir'] }}</h4>
            </div>
        </div>

        <!-- Terlambat -->
        <div class="rekap-card-item">
            <div class="rekap-icon-box terlambat">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div style="min-width: 0; flex: 1;">
                <p style="font-size: 10px; font-weight: 700; color: #6b7280; text-transform: uppercase; letter-spacing: 0.05em; margin: 0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">Terlambat</p>
                <div class="flex items-baseline gap-1.5 flex-wrap">
                    <h4 style="font-size: 18px; font-weight: 900; color: #d97706; line-height: 1.2; margin: 2px 0 0 0;">{{ $ringkasan['terlambat'] }}</h4>
                    @if($ringkasan['menit_telat'] > 0)
                        <span style="font-size: 10px; font-weight: 600; color: #dc2626;" title="Total akumulasi keterlambatan">({{ $ringkasan['menit_telat'] }} mnt)</span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Izin / Cuti -->
        <div class="rekap-card-item">
            <div class="rekap-icon-box izin">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
            <div style="min-width: 0; flex: 1;">
                <p style="font-size: 10px; font-weight: 700; color: #6b7280; text-transform: uppercase; letter-spacing: 0.05em; margin: 0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">Izin / Cuti</p>
                <h4 style="font-size: 18px; font-weight: 900; color: #2563eb; line-height: 1.2; margin: 2px 0 0 0;">{{ $ringkasan['izin'] }}</h4>
            </div>
        </div>

        <!-- Alpha -->
        <div class="rekap-card-item">
            <div class="rekap-icon-box alpha">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div style="min-width: 0; flex: 1;">
                <p style="font-size: 10px; font-weight: 700; color: #6b7280; text-transform: uppercase; letter-spacing: 0.05em; margin: 0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">Alpha</p>
                <h4 style="font-size: 18px; font-weight: 900; color: #e11d48; line-height: 1.2; margin: 2px 0 0 0;">{{ $ringkasan['alpha'] }}</h4>
            </div>
        </div>

        <!-- WFO -->
        <div class="rekap-card-item">
            <div class="rekap-icon-box wfo">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
            </div>
            <div style="min-width: 0; flex: 1;">
                <p style="font-size: 10px; font-weight: 700; color: #6b7280; text-transform: uppercase; letter-spacing: 0.05em; margin: 0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">WFO</p>
                <h4 style="font-size: 18px; font-weight: 900; color: #4338ca; line-height: 1.2; margin: 2px 0 0 0;">{{ $ringkasan['wfo'] }}</h4>
            </div>
        </div>

        <!-- WFH -->
        <div class="rekap-card-item">
            <div class="rekap-icon-box wfh">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
            </div>
            <div style="min-width: 0; flex: 1;">
                <p style="font-size: 10px; font-weight: 700; color: #6b7280; text-transform: uppercase; letter-spacing: 0.05em; margin: 0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">WFH</p>
                <h4 style="font-size: 18px; font-weight: 900; color: #7e22ce; line-height: 1.2; margin: 2px 0 0 0;">{{ $ringkasan['wfh'] }}</h4>
            </div>
        </div>

        <!-- Upacara -->
        @if(($ringkasan['total_upacara'] ?? 0) > 0)
        <div class="rekap-card-item">
            <div class="rekap-icon-box upacara">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21h18M5 21V5a2 2 0 012-2h10a2 2 0 012 2v16M9 7h6m-6 4h6m-6 4h6"></path>
                </svg>
            </div>
            <div style="min-width: 0; flex: 1;">
                <p style="font-size: 10px; font-weight: 700; color: #6b7280; text-transform: uppercase; letter-spacing: 0.05em; margin: 0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">Upacara</p>
                <h4 style="font-size: 18px; font-weight: 900; color: #854d0e; line-height: 1.2; margin: 2px 0 0 0;">{{ $ringkasan['upacara_hadir'] }}/{{ $ringkasan['total_upacara'] }}</h4>
            </div>
        </div>
        @endif
    </div>

    <!-- White Card Container -->
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-4 sm:p-6">
        <!-- Filter Bar & Export -->
        <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 mb-6">
            <form method="GET" class="flex flex-wrap items-end gap-2.5 sm:gap-3">
                <div class="flex-1 sm:flex-none">
                    <label for="rekap-pribadi-bulan" class="block text-xs font-semibold text-gray-700 mb-1.5 whitespace-nowrap">Bulan</label>
                    <select id="rekap-pribadi-bulan" name="bulan" aria-label="Pilih Bulan" class="w-full sm:w-auto block py-2.5 pl-3.5 pr-8 text-sm rounded-xl border border-gray-300 bg-white focus:ring-2 focus:ring-[#0b602b]/20 focus:border-[#0b602b] transition shadow-sm text-gray-700 cursor-pointer">
                        @foreach (['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'] as $i => $namaBulan)
                            <option value="{{ $i + 1 }}" {{ $bulan == $i + 1 ? 'selected' : '' }}>{{ $namaBulan }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="flex-1 sm:flex-none">
                    <label for="rekap-pribadi-tahun" class="block text-xs font-semibold text-gray-700 mb-1.5 whitespace-nowrap">Tahun</label>
                    <select id="rekap-pribadi-tahun" name="tahun" aria-label="Pilih Tahun" class="w-full sm:w-auto block py-2.5 pl-3.5 pr-8 text-sm rounded-xl border border-gray-300 bg-white focus:ring-2 focus:ring-[#0b602b]/20 focus:border-[#0b602b] transition shadow-sm text-gray-700 cursor-pointer">
                        @for ($y = now()->year; $y >= now()->year - 2; $y--)
                            <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endfor
                    </select>
                </div>

                <button type="submit" class="inline-flex items-center justify-center gap-1.5 rounded-xl bg-[#0b602b] hover:bg-[#084d22] px-4 py-2.5 text-sm font-semibold text-white transition shadow-sm cursor-pointer whitespace-nowrap">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <span>Cari</span>
                </button>
            </form>

            <div class="shrink-0">
                <a href="{{ route('kehadiran.download-pribadi', ['bulan' => $bulan, 'tahun' => $tahun]) }}" target="_blank" style="background-color: #BA1A1A;" class="inline-flex items-center justify-center gap-2 bg-[#BA1A1A] hover:bg-[#961313] text-white px-4 py-2.5 rounded-xl text-sm font-semibold shadow-sm hover:shadow transition whitespace-nowrap w-full sm:w-auto">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    <span>Download PDF</span>
                </a>
            </div>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto rounded-xl border border-gray-200">
            <table class="w-full text-sm text-left border-collapse">
                <thead class="bg-[#0b602b]">
                    <tr>
                        <th scope="col" class="px-6 py-4 text-xs font-semibold uppercase tracking-wider text-white whitespace-nowrap text-center">Tanggal</th>
                        <th scope="col" class="px-6 py-4 text-xs font-semibold uppercase tracking-wider text-white whitespace-nowrap text-center">Jam Masuk</th>
                        <th scope="col" class="px-6 py-4 text-xs font-semibold uppercase tracking-wider text-white whitespace-nowrap text-center">Jam Keluar</th>
                        <th scope="col" class="px-6 py-4 text-xs font-semibold uppercase tracking-wider text-white whitespace-nowrap text-center">Upacara</th>
                        <th scope="col" class="px-6 py-4 text-xs font-semibold uppercase tracking-wider text-white whitespace-nowrap text-center">Jenis</th>
                        <th scope="col" class="px-6 py-4 text-xs font-semibold uppercase tracking-wider text-white whitespace-nowrap text-center">STATUS</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($data as $item)
                        <tr class="hover:bg-gray-50/50 transition {{ $item->is_libur ? 'bg-gray-50/40' : '' }}">
                            <td class="px-6 py-4 text-sm font-medium text-gray-700 whitespace-nowrap text-center">
                                {{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d F Y') }}
                            </td>
                            
                            {{-- Jam Masuk --}}
                            <td class="px-6 py-4 text-sm font-semibold whitespace-nowrap text-center {{ $item->is_libur ? 'text-gray-500' : (($item->menit_telat > 0 || $item->status === 'terlambat') ? 'text-red-600 font-bold' : ($item->is_izin ? 'text-blue-700' : 'text-gray-800')) }}">
                                {{ $item->jam_masuk ?? '-' }}
                            </td>

                            {{-- Jam Keluar --}}
                            <td class="px-6 py-4 text-sm font-semibold whitespace-nowrap text-center {{ $item->is_libur ? 'text-gray-500' : ($item->menit_pulang_awal > 0 ? 'text-red-600 font-bold' : ($item->is_izin ? 'text-blue-700' : 'text-gray-800')) }}">
                                {{ $item->jam_keluar ?? '-' }}
                            </td>

                            {{-- Upacara --}}
                            <td class="px-6 py-4 text-sm whitespace-nowrap text-center">
                                @if($item->upacara)
                                    <div class="flex flex-col items-center justify-center gap-0.5">
                                        <span class="inline-block px-2.5 py-1 text-[10px] font-extrabold rounded-full uppercase tracking-wider
                                            {{ $item->upacara->status === 'hadir' ? 'bg-green-100 text-green-800 border border-green-200' : ($item->upacara->status === 'izin' ? 'bg-blue-100 text-blue-800 border border-blue-200' : 'bg-red-100 text-red-800 border border-red-200') }}">
                                            {{ $item->upacara->status === 'hadir' ? 'HADIR UPACARA' : ($item->upacara->status === 'izin' ? 'IZIN UPACARA' : 'TIDAK HADIR') }}
                                        </span>
                                        <span class="text-[10px] text-gray-500 font-medium max-w-[150px] truncate" title="{{ $item->upacara->nama_upacara }}">
                                            {{ $item->upacara->nama_upacara }}
                                        </span>
                                    </div>
                                @else
                                    <span class="text-gray-400 text-xs">-</span>
                                @endif
                            </td>

                            {{-- Jenis --}}
                            <td class="px-6 py-4 text-sm whitespace-nowrap text-center">
                                <span class="inline-block px-2.5 py-1 text-[10px] font-extrabold rounded-full uppercase tracking-wider
                                    @if($item->is_libur) bg-gray-100 text-gray-600 border border-gray-200
                                    @elseif($item->is_izin) bg-blue-50 text-blue-700 border border-blue-200
                                    @elseif($item->jenis === 'wfh') bg-purple-50 text-purple-700 border border-purple-200
                                    @elseif($item->jenis === 'wfo') bg-emerald-50 text-emerald-700 border border-emerald-200
                                    @else bg-gray-100 text-gray-500 border border-gray-200
                                    @endif">
                                    {{ strtoupper($item->jenis) }}
                                </span>
                            </td>

                            {{-- Status Badge dengan Keterangan Menit --}}
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <div class="flex flex-col items-center justify-center gap-1 py-0.5">
                                    @if($item->is_libur)
                                        <div class="inline-flex flex-col items-center justify-center px-2.5 py-1 rounded-full bg-gray-100 text-gray-700 border border-gray-200 leading-tight" title="{{ $item->keterangan }}">
                                            <span class="text-[10px] font-extrabold uppercase tracking-wider">LIBUR</span>
                                            @if($item->keterangan && $item->keterangan !== 'Libur Akhir Pekan')
                                                <span class="text-[9px] text-emerald-800 font-semibold max-w-[140px] truncate uppercase">{{ $item->keterangan }}</span>
                                            @endif
                                        </div>
                                    @elseif($item->is_izin)
                                        <span class="inline-flex items-center justify-center px-2.5 py-1 rounded-full text-[10px] font-extrabold bg-blue-50 text-blue-700 border border-blue-200 uppercase tracking-wider">
                                            {{ strtoupper($item->keterangan) }}
                                        </span>
                                    @elseif($item->is_kehadiran && ($item->status === 'terlambat' || ($item->menit_telat ?? 0) > 0))
                                        <span class="inline-flex items-center justify-center px-2.5 py-1 rounded-full text-[10px] font-extrabold bg-[#fef9c3] text-[#854d0e] border border-[#fef08a] uppercase tracking-wider whitespace-nowrap">
                                            TERLAMBAT @if($item->menit_telat > 0) ({{ (int) round($item->menit_telat) }} MENIT) @endif
                                        </span>
                                    @elseif($item->is_kehadiran && $item->status === 'hadir')
                                        <span class="inline-flex items-center justify-center px-2.5 py-1 rounded-full text-[10px] font-extrabold bg-[#dcfce7] text-[#0b602b] border border-green-200 uppercase tracking-wider">
                                            HADIR
                                        </span>
                                    @elseif($item->status === 'alpha')
                                        <span class="inline-flex items-center justify-center px-2.5 py-1 rounded-full text-[10px] font-extrabold bg-rose-50 text-rose-700 border border-rose-200 uppercase tracking-wider" title="{{ $item->keterangan ?? 'Alpha' }}">
                                            ALPHA
                                        </span>
                                    @else
                                        <span class="text-gray-400 text-xs">-</span>
                                    @endif

                                    @if($item->menit_pulang_awal > 0)
                                        <span class="inline-flex items-center justify-center px-2.5 py-1 rounded-full text-[10px] font-extrabold bg-[#fff1f2] text-[#9f1239] border border-[#fecdd3] uppercase tracking-wider whitespace-nowrap">
                                            LEBIH AWAL ({{ (int) round($item->menit_pulang_awal) }} MENIT)
                                        </span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-400">Belum ada data kehadiran bulan ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</x-dashboard-layout>