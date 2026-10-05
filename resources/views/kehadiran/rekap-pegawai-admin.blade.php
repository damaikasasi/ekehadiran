<x-dashboard-layout :title="'Presensi Pegawai'">

    <div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-900 tracking-tight">
                Rekap Kehadiran Per Pegawai
            </h2>
            <p class="mt-1 text-sm text-gray-500">
                Pilih pegawai, bulan, dan tahun untuk melihat rekap kehadiran
            </p>
        </div>
    </div>

    @include('kehadiran._tabs')

    {{-- Filter Form --}}
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-5 mb-6">
        <form method="GET"
              action="{{ route('kehadiran.rekap-pegawai') }}"
              class="flex flex-wrap items-end gap-4">

            {{-- Pegawai --}}
            <div class="flex-1 min-w-[200px]">
                <label for="pegawai-id-select" class="block text-xs font-semibold text-gray-700 mb-1.5 whitespace-nowrap">
                    Pegawai
                </label>

                <select id="pegawai-id-select"
                        name="pegawai_id"
                        aria-label="Pilih Pegawai"
                        class="block w-full pl-3.5 pr-8 py-2.5 border-gray-300 rounded-xl text-sm focus:ring-green-500 focus:border-green-500 shadow-sm"
                        required>

                    <option value="">-- Pilih Pegawai --</option>

                    @foreach ($pegawaiList as $p)
                        <option value="{{ $p->id }}"
                            {{ $pegawaiId == $p->id ? 'selected' : '' }}>
                            {{ $p->nama }} ({{ $p->divisi->nama_divisi ?? '-' }})
                        </option>
                    @endforeach

                </select>
            </div>

            {{-- Bulan --}}
            <div>
                <label for="bulan-select" class="block text-xs font-semibold text-gray-700 mb-1.5 whitespace-nowrap">
                    Bulan
                </label>

                <select id="bulan-select"
                        name="bulan"
                        aria-label="Pilih Bulan"
                        class="block pl-3.5 pr-8 py-2.5 border-gray-300 rounded-xl text-sm focus:ring-green-500 focus:border-green-500 shadow-sm">

                    @foreach ([
                        'Januari',
                        'Februari',
                        'Maret',
                        'April',
                        'Mei',
                        'Juni',
                        'Juli',
                        'Agustus',
                        'September',
                        'Oktober',
                        'November',
                        'Desember'
                    ] as $i => $nb)

                        <option value="{{ $i + 1 }}"
                            {{ $bulan == $i + 1 ? 'selected' : '' }}>
                            {{ $nb }}
                        </option>

                    @endforeach

                </select>
            </div>

            {{-- Tahun --}}
            <div>
                <label for="tahun-select" class="block text-xs font-semibold text-gray-700 mb-1.5 whitespace-nowrap">
                    Tahun
                </label>

                <select id="tahun-select"
                        name="tahun"
                        aria-label="Pilih Tahun"
                        class="block pl-3.5 pr-8 py-2.5 border-gray-300 rounded-xl text-sm focus:ring-green-500 focus:border-green-500 shadow-sm">

                    @for ($y = now()->year; $y >= now()->year - 3; $y--)

                        <option value="{{ $y }}"
                            {{ $tahun == $y ? 'selected' : '' }}>
                            {{ $y }}
                        </option>

                    @endfor

                </select>
            </div>

            {{-- Tombol Tampilkan --}}
            <button type="submit"
                    class="inline-flex items-center gap-2 rounded-xl bg-[#0b602b] px-5 py-2.5 text-sm font-semibold text-white hover:bg-[#084d22] transition shadow-sm">

                <svg class="w-4 h-4"
                     fill="none"
                     stroke="currentColor"
                     viewBox="0 0 24 24">

                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="M21 21l-4.35-4.35M17 11A6 6 0 115 11a6 6 0 0112 0z"/>

                </svg>

                Cari
            </button>

            {{-- Download PDF --}}
            @if ($pegawai)

                <a href="{{ route('kehadiran.rekap-pegawai.download', [
                    'pegawai_id' => $pegawai->id,
                    'bulan' => $bulan,
                    'tahun' => $tahun
                ]) }}"
                   target="_blank"
                   class="inline-flex items-center gap-2 rounded-xl bg-[#BA1A1A] hover:bg-[#961313] px-5 py-2.5 text-sm font-semibold text-white transition shadow-sm hover:shadow whitespace-nowrap">

                    <svg class="w-4 h-4"
                         fill="none"
                         stroke="currentColor"
                         viewBox="0 0 24 24">

                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>

                    </svg>

                    <span>Download PDF</span>
                </a>

            @endif

        </form>
    </div>


    @if ($pegawai)

        {{-- Info Pegawai --}}
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-5 mb-6 flex items-center gap-4">

            <div class="w-12 h-12 rounded-xl overflow-hidden bg-green-50 border border-green-100 flex items-center justify-center shrink-0">

                @if ($pegawai->foto)

                    <img src="{{ asset('storage/' . $pegawai->foto) }}"
                         class="w-full h-full object-cover"
                         alt="">

                @else

                    <span class="text-green-700 font-extrabold text-lg">
                        {{ strtoupper(substr($pegawai->nama, 0, 1)) }}
                    </span>

                @endif

            </div>

            <div>

                <p class="font-bold text-gray-800 text-base">
                    {{ $pegawai->nama }}
                </p>

                <p class="text-sm text-gray-500">
                    {{ $pegawai->jabatan ?? '-' }}
                    &bull;
                    {{ $pegawai->divisi->nama_divisi ?? '-' }}
                </p>

                <p class="text-xs text-gray-400 mt-0.5">
                    NIP: {{ $pegawai->user->nip ?? '-' }}
                </p>

            </div>

            <div class="ml-auto text-right">

                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">
                    Periode
                </p>

                <p class="font-bold text-gray-700">
                    {{ \Carbon\Carbon::create(null, $bulan)->translatedFormat('F') }}
                    {{ $tahun }}
                </p>

            </div>

        </div>


        <style>
            .rekap-cards-grid {
                display: grid;
                grid-template-columns: 1fr;
                gap: 10px;
                margin-bottom: 24px;
            }
            @media (min-width: 640px) {
                .rekap-cards-grid {
                    display: flex !important;
                    flex-wrap: nowrap !important;
                    gap: 10px !important;
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
                flex: 1;
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
            .rekap-icon-box.wfo { background-color: #dbeafe !important; color: #1d4ed8 !important; }
            .rekap-icon-box.wfh { background-color: #f3e8ff !important; color: #7e22ce !important; }
        </style>

        {{-- Summary Cards --}}
        @if ($ringkasan)

            <div class="rekap-cards-grid">

                {{-- HADIR --}}
                <div class="rekap-card-item">
                    <div class="rekap-icon-box hadir" style="background-color: #dcfce7; color: #047857;">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div style="min-width: 0; flex: 1;">
                        <p style="font-size: 10px; font-weight: 700; color: #6b7280; text-transform: uppercase; letter-spacing: 0.05em; margin: 0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">Hadir</p>
                        <h4 style="font-size: 18px; font-weight: 900; color: #047857; line-height: 1.2; margin: 2px 0 0 0;">{{ $ringkasan['hadir'] }}</h4>
                    </div>
                </div>

                {{-- TERLAMBAT --}}
                <div class="rekap-card-item">
                    <div class="rekap-icon-box terlambat" style="background-color: #fef3c7; color: #d97706;">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div style="min-width: 0; flex: 1;">
                        <p style="font-size: 10px; font-weight: 700; color: #6b7280; text-transform: uppercase; letter-spacing: 0.05em; margin: 0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">Terlambat</p>
                        <h4 style="font-size: 18px; font-weight: 900; color: #d97706; line-height: 1.2; margin: 2px 0 0 0;">{{ $ringkasan['terlambat'] }}</h4>
                    </div>
                </div>

                {{-- AKUMULASI TELAT --}}
                <div class="rekap-card-item">
                    <div class="rekap-icon-box telat" style="background-color: #fee2e2; color: #dc2626;">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>
                    <div style="min-width: 0; flex: 1;">
                        <p style="font-size: 10px; font-weight: 700; color: #6b7280; text-transform: uppercase; letter-spacing: 0.05em; margin: 0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">Akumulasi Telat</p>
                        <h4 style="font-size: 18px; font-weight: 900; color: #dc2626; line-height: 1.2; margin: 2px 0 0 0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $ringkasan['menit_telat'] }} <span style="font-size: 11px; font-weight: 500; color: #9ca3af;">mnt</span></h4>
                    </div>
                </div>

                {{-- WFO --}}
                <div class="rekap-card-item">
                    <div class="rekap-icon-box wfo" style="background-color: #dbeafe; color: #1d4ed8;">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                    <div style="min-width: 0; flex: 1;">
                        <p style="font-size: 10px; font-weight: 700; color: #6b7280; text-transform: uppercase; letter-spacing: 0.05em; margin: 0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">WFO</p>
                        <h4 style="font-size: 18px; font-weight: 900; color: #111827; line-height: 1.2; margin: 2px 0 0 0;">{{ $ringkasan['wfo'] }}</h4>
                    </div>
                </div>

                {{-- WFH --}}
                <div class="rekap-card-item">
                    <div class="rekap-icon-box wfh" style="background-color: #f3e8ff; color: #7e22ce;">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                    </div>
                    <div style="min-width: 0; flex: 1;">
                        <p style="font-size: 10px; font-weight: 700; color: #6b7280; text-transform: uppercase; letter-spacing: 0.05em; margin: 0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">WFH</p>
                        <h4 style="font-size: 18px; font-weight: 900; color: #111827; line-height: 1.2; margin: 2px 0 0 0;">{{ $ringkasan['wfh'] }}</h4>
                    </div>
                </div>

                {{-- UPACARA --}}
                @if(($ringkasan['total_upacara'] ?? 0) > 0)
                <div class="rekap-card-item">
                    <div class="rekap-icon-box" style="background-color: #fef08a; color: #854d0e;">
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

        @endif


        {{-- Tabel Detail --}}
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">

            <div class="overflow-x-auto">

                <table class="w-full text-sm text-left">

                    <thead class="bg-[#0b602b]">

                        <tr>

                            <th scope="col" class="px-5 py-4 text-xs font-semibold uppercase tracking-wider text-white text-center">
                                Tanggal
                            </th>

                            <th scope="col" class="px-5 py-4 text-xs font-semibold uppercase tracking-wider text-white text-center">
                                Jam Masuk
                            </th>

                            <th scope="col" class="px-5 py-4 text-xs font-semibold uppercase tracking-wider text-white text-center">
                                Jam Keluar
                            </th>

                            <th scope="col" class="px-5 py-4 text-xs font-semibold uppercase tracking-wider text-white text-center">
                                Upacara
                            </th>

                            <th scope="col" class="px-5 py-4 text-xs font-semibold uppercase tracking-wider text-white text-center">
                                Jenis
                            </th>

                            <th scope="col" class="px-5 py-4 text-xs font-semibold uppercase tracking-wider text-white text-center">
                                STATUS
                            </th>

                            <th scope="col" class="px-5 py-4 text-xs font-semibold uppercase tracking-wider text-white text-center">
                                FOTO
                            </th>

                        </tr>

                    </thead>

                    <tbody class="divide-y divide-gray-100">

                        @forelse ($data as $item)

                            <tr class="hover:bg-gray-50/50 {{ $item->is_libur ? 'bg-gray-50/40' : '' }}">

                                <td class="px-5 py-3.5 font-medium text-gray-800 whitespace-nowrap text-center">
                                    {{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d F Y') }}
                                </td>

                                {{-- Jam Masuk --}}
                                <td class="px-5 py-3.5 font-semibold whitespace-nowrap text-center {{ $item->is_libur ? 'text-gray-500' : (($item->menit_telat > 0 || $item->status === 'terlambat') ? 'text-red-600 font-bold' : ($item->is_izin ? 'text-blue-700' : 'text-gray-800')) }}">
                                    {{ $item->jam_masuk ?? '-' }}
                                </td>

                                {{-- Jam Keluar --}}
                                <td class="px-5 py-3.5 font-semibold whitespace-nowrap text-center {{ $item->is_libur ? 'text-gray-500' : ($item->menit_pulang_awal > 0 ? 'text-red-600 font-bold' : ($item->is_izin ? 'text-blue-700' : 'text-gray-800')) }}">
                                    {{ $item->jam_keluar ?? '-' }}
                                </td>

                                {{-- Upacara --}}
                                <td class="px-5 py-3.5 text-sm whitespace-nowrap text-center">
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
                                <td class="px-5 py-3.5 text-center whitespace-nowrap">
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
                                <td class="px-5 py-3.5 whitespace-nowrap text-center">
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
                                        @elseif($item->status === 'terlambat' || $item->menit_telat > 0)
                                            <span class="inline-flex items-center justify-center px-2.5 py-1 rounded-full text-[10px] font-extrabold bg-[#fef9c3] text-[#854d0e] border border-[#fef08a] uppercase tracking-wider whitespace-nowrap">
                                                TERLAMBAT @if($item->menit_telat > 0) ({{ (int) round($item->menit_telat) }} MENIT) @endif
                                            </span>
                                        @elseif($item->status === 'hadir')
                                            <span class="inline-flex items-center justify-center px-2.5 py-1 rounded-full text-[10px] font-extrabold bg-[#dcfce7] text-[#0b602b] border border-green-200 uppercase tracking-wider">
                                                HADIR
                                            </span>
                                        @elseif($item->status === 'alpha')
                                            <span class="inline-flex items-center justify-center px-2.5 py-1 rounded-full text-[10px] font-extrabold bg-rose-50 text-rose-700 border border-rose-200 uppercase tracking-wider">
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

                                {{-- Foto WFH --}}
                                <td class="px-5 py-3.5 text-center whitespace-nowrap">
                                    @if ($item->foto || $item->foto_keluar)
                                        <div class="flex items-center justify-center gap-1.5">
                                            @if ($item->foto)
                                                <a href="{{ url('/storage-file/' . $item->foto) }}" target="_blank" class="inline-block group relative" title="Foto Masuk">
                                                    <img src="{{ url('/storage-file/' . $item->foto) }}" class="w-8 h-8 rounded-lg object-cover border border-emerald-300 hover:scale-110 transition shadow-sm" alt="Masuk">
                                                    <span class="absolute -top-1 -right-1 bg-emerald-600 text-white text-[9px] font-bold px-1 rounded">IN</span>
                                                </a>
                                            @endif
                                            @if ($item->foto_keluar)
                                                <a href="{{ url('/storage-file/' . $item->foto_keluar) }}" target="_blank" class="inline-block group relative" title="Foto Pulang">
                                                    <img src="{{ url('/storage-file/' . $item->foto_keluar) }}" class="w-8 h-8 rounded-lg object-cover border border-rose-300 hover:scale-110 transition shadow-sm" alt="Pulang">
                                                    <span class="absolute -top-1 -right-1 bg-rose-600 text-white text-[9px] font-bold px-1 rounded">OUT</span>
                                                </a>
                                            @endif
                                        </div>
                                    @else
                                        <span class="text-gray-400 text-xs">-</span>
                                    @endif
                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="7" class="px-5 py-12 text-center text-gray-400">
                                    Belum ada data presensi pada periode ini.
                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>


    @else

        {{-- Empty State --}}
        <div class="bg-white rounded-2xl border border-dashed border-gray-300 p-16 text-center text-gray-400">

            <svg class="w-12 h-12 mx-auto mb-3 text-gray-300"
                 fill="none"
                 stroke="currentColor"
                 viewBox="0 0 24 24">

                <path stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="1.5"
                      d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>

            </svg>

            <p class="font-medium">
                Pilih pegawai untuk melihat rekap kehadiran
            </p>

        </div>

    @endif

</x-dashboard-layout>