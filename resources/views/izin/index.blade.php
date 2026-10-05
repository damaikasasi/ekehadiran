<x-dashboard-layout :title="'Permohonan Izin'">

    <!-- Header Title & Subtitle -->
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-900 tracking-tight">Permohonan Izin</h2>
        <p class="mt-1 text-sm text-gray-500">Kelola dan proses pengajuan izin meninggalkan tugas pegawai.</p>
    </div>

    @if (session('success'))
        <div class="mb-5 p-4 bg-emerald-50 text-emerald-800 rounded-xl border border-emerald-200 flex items-center justify-between text-sm shadow-sm">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span>{{ session('success') }}</span>
            </div>
            <button type="button" onclick="this.parentElement.remove()" aria-label="Tutup notifikasi" class="text-emerald-500 hover:text-emerald-700">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
    @endif

    <!-- Top 3 Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        @if (auth()->user()->role === 'user')
            <!-- Card 1: Sedang Diverifikasi Atasan -->
            <div class="bg-white rounded-2xl border border-gray-200 p-5 flex items-center gap-4 shadow-sm hover:shadow-md transition">
                <div class="w-12 h-12 rounded-xl flex items-center justify-center shrink-0" style="background-color: #fef3c7; color: #b45309;">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <p class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Sedang Diverifikasi Atasan</p>
                    <h4 class="text-2xl font-black text-gray-900 mt-0.5">{{ $sedangDiverifikasiAtasan }} Permohonan</h4>
                </div>
            </div>

            <!-- Card 2: Sedang Diverifikasi Admin -->
            <div class="bg-white rounded-2xl border border-gray-200 p-5 flex items-center gap-4 shadow-sm hover:shadow-md transition">
                <div class="w-12 h-12 rounded-xl flex items-center justify-center shrink-0" style="background-color: #dbeafe; color: #1d4ed8;">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                </div>
                <div>
                    <p class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Sedang Diverifikasi Admin</p>
                    <h4 class="text-2xl font-black text-gray-900 mt-0.5">{{ $sedangDiverifikasiAdmin }} Permohonan</h4>
                </div>
            </div>

            <!-- Card 3: Izin Disetujui -->
            <div class="bg-white rounded-2xl border border-gray-200 p-5 flex items-center gap-4 shadow-sm hover:shadow-md transition">
                <div class="w-12 h-12 rounded-xl flex items-center justify-center shrink-0" style="background-color: #dcfce7; color: #047857;">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <p class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Izin Disetujui</p>
                    <h4 class="text-2xl font-black text-gray-900 mt-0.5">{{ $izinDisetujui }} Permohonan</h4>
                </div>
            </div>
        @else
            <!-- Card 1: Sedang Diverifikasi Admin -->
            <div class="bg-white rounded-2xl border border-gray-200 p-5 flex items-center gap-4 shadow-sm hover:shadow-md transition">
                <div class="w-12 h-12 rounded-xl flex items-center justify-center shrink-0" style="background-color: #fef3c7; color: #b45309;">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                <div>
                    <p class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Sedang Diverifikasi Admin</p>
                    <h4 class="text-2xl font-black text-gray-900 mt-0.5">{{ $sedangDiverifikasiAdmin }} Permohonan</h4>
                </div>
            </div>

            <!-- Card 2: Sedang Diverifikasi Atasan -->
            <div class="bg-white rounded-2xl border border-gray-200 p-5 flex items-center gap-4 shadow-sm hover:shadow-md transition">
                <div class="w-12 h-12 rounded-xl flex items-center justify-center shrink-0" style="background-color: #dbeafe; color: #1d4ed8;">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                    </svg>
                </div>
                <div>
                    <p class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Sedang Diverifikasi Atasan</p>
                    <h4 class="text-2xl font-black text-gray-900 mt-0.5">{{ $sedangDiverifikasiAtasan }} Permohonan</h4>
                </div>
            </div>

            <!-- Card 3: Pegawai Sedang Izin -->
            <div class="bg-white rounded-2xl border border-gray-200 p-5 flex items-center gap-4 shadow-sm hover:shadow-md transition">
                <div class="w-12 h-12 rounded-xl flex items-center justify-center shrink-0" style="background-color: #dcfce7; color: #047857;">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
                <div>
                    <p class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Pegawai Sedang Izin</p>
                    <h4 class="text-2xl font-black text-gray-900 mt-0.5">{{ $pegawaiSedangIzin }} Orang</h4>
                </div>
            </div>
        @endif
    </div>

    <!-- Main Content Card -->
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">

        <!-- Filter & Action Bar -->
        <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-4 mb-6">
            <form method="GET" action="{{ route('izin.index') }}" class="flex flex-wrap items-end gap-2.5 w-full lg:w-auto">
                @if(request('per_page'))
                    <input type="hidden" name="per_page" value="{{ request('per_page') }}">
                @endif

                <!-- Search Input -->
                <div class="w-full sm:w-56 md:w-64">
                    <label for="search-izin" class="block text-xs font-semibold text-gray-700 mb-1.5 whitespace-nowrap">{{ auth()->user()->role === 'user' ? 'Cari Izin' : 'Cari Pegawai' }}</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                        <input
                            type="text"
                            id="search-izin"
                            name="search"
                            value="{{ request('search') }}"
                            placeholder="{{ auth()->user()->role === 'user' ? 'Cari jenis atau keterangan...' : 'Nama, NIP atau Divisi...' }}"
                            class="block w-full pl-9 pr-3 py-2.5 text-sm rounded-xl border border-gray-300 focus:ring-2 focus:ring-[#0b602b]/20 focus:border-[#0b602b] transition shadow-sm text-gray-700">
                    </div>
                </div>

                <!-- Filter Jenis Izin -->
                <div class="w-full sm:w-auto flex-1 sm:flex-none">
                    <label for="filter-jenis" class="block text-xs font-semibold text-gray-700 mb-1.5 whitespace-nowrap">Jenis Izin</label>
                    <select id="filter-jenis" name="jenis" aria-label="Filter Jenis Izin" class="w-full sm:w-auto block pl-3.5 pr-9 py-2.5 text-sm rounded-xl border border-gray-300 bg-white focus:ring-2 focus:ring-[#0b602b]/20 focus:border-[#0b602b] transition shadow-sm text-gray-700 cursor-pointer">
                        <option value="">Semua Jenis</option>
                        <option value="sakit" {{ request('jenis') === 'sakit' ? 'selected' : '' }}>Sakit</option>
                        <option value="dinas" {{ request('jenis') === 'dinas' ? 'selected' : '' }}>Dinas Luar</option>
                        <option value="lainnya" {{ request('jenis') === 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                    </select>
                </div>

                <!-- Filter Status -->
                <div class="w-full sm:w-auto flex-1 sm:flex-none">
                    <label for="filter-status" class="block text-xs font-semibold text-gray-700 mb-1.5 whitespace-nowrap">Status</label>
                    <select id="filter-status" name="status" aria-label="Filter Status Izin" class="w-full sm:w-auto block pl-3.5 pr-9 py-2.5 text-sm rounded-xl border border-gray-300 bg-white focus:ring-2 focus:ring-[#0b602b]/20 focus:border-[#0b602b] transition shadow-sm text-gray-700 cursor-pointer">
                        <option value="">Semua Status</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending (Atasan)</option>
                        <option value="disetujui_atasan" {{ request('status') === 'disetujui_atasan' ? 'selected' : '' }}>Verifikasi Admin</option>
                        <option value="disetujui" {{ request('status') === 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                        <option value="ditolak" {{ request('status') === 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                    </select>
                </div>

                <div class="flex items-center gap-2">
                    <button
                        type="submit"
                        class="inline-flex items-center justify-center gap-1.5 rounded-xl bg-[#0b602b] hover:bg-[#084d22] px-4 py-2.5 text-sm font-semibold text-white transition shadow-sm cursor-pointer whitespace-nowrap">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        <span>Cari</span>
                    </button>

                    @if(request('search') || request('jenis') || request('status') || request('bulan') || request('tahun'))
                        <a
                            href="{{ route('izin.index') }}"
                            aria-label="Reset Pencarian & Filter"
                            title="Reset Pencarian & Filter"
                            class="inline-flex items-center justify-center p-2.5 rounded-xl border border-gray-300 bg-gray-50 hover:bg-gray-100 text-gray-600 transition shadow-sm shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </a>
                    @endif
                </div>
            </form>

            <div class="flex flex-wrap sm:flex-nowrap items-center gap-2 w-full lg:w-auto shrink-0 justify-start lg:justify-end">
                @if (auth()->user()->role === 'user')
                    <a href="{{ route('izin.create') }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-[#0b602b] hover:bg-[#084d22] text-white text-sm font-semibold rounded-full shadow-sm hover:shadow transition whitespace-nowrap">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                        </svg>
                        <span>Ajukan Izin</span>
                    </a>
                @endif

                <a href="{{ route('izin.export', request()->query()) }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-[#0b602b] hover:bg-[#084d22] text-white text-sm font-semibold rounded-full shadow-sm hover:shadow transition whitespace-nowrap">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                    </svg>
                    <span>Ekspor Data</span>
                </a>
            </div>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto rounded-xl border border-gray-200">
            <table class="w-full text-sm text-center border-collapse">
                <thead class="bg-[#0b602b] text-white uppercase">
                    <tr>
                        @if (auth()->user()->role === 'user')
                            <th scope="col" class="px-4 py-3.5 text-xs font-semibold uppercase tracking-wider whitespace-nowrap text-center w-12">No</th>
                            <th scope="col" class="px-5 py-3.5 text-xs font-semibold uppercase tracking-wider whitespace-nowrap text-center">Jenis Izin</th>
                            <th scope="col" class="px-5 py-3.5 text-xs font-semibold uppercase tracking-wider whitespace-nowrap text-center">Periode Tanggal</th>
                            <th scope="col" class="px-5 py-3.5 text-xs font-semibold uppercase tracking-wider whitespace-nowrap text-center">Durasi</th>
                            <th scope="col" class="px-5 py-3.5 text-xs font-semibold uppercase tracking-wider whitespace-nowrap text-center">Keterangan</th>
                            <th scope="col" class="px-5 py-3.5 text-xs font-semibold uppercase tracking-wider whitespace-nowrap text-center">Lampiran</th>
                            <th scope="col" class="px-5 py-3.5 text-xs font-semibold uppercase tracking-wider whitespace-nowrap text-center">STATUS</th>
                            <th scope="col" class="px-5 py-3.5 text-xs font-semibold uppercase tracking-wider whitespace-nowrap text-center">Aksi</th>
                        @else
                            <th scope="col" class="px-5 py-3.5 text-xs font-semibold uppercase tracking-wider whitespace-nowrap text-center">Nama Pegawai</th>
                            <th scope="col" class="px-5 py-3.5 text-xs font-semibold uppercase tracking-wider whitespace-nowrap text-center">Jenis Izin</th>
                            <th scope="col" class="px-5 py-3.5 text-xs font-semibold uppercase tracking-wider whitespace-nowrap text-center">Periode Tanggal</th>
                            <th scope="col" class="px-5 py-3.5 text-xs font-semibold uppercase tracking-wider whitespace-nowrap text-center">Durasi</th>
                            <th scope="col" class="px-5 py-3.5 text-xs font-semibold uppercase tracking-wider whitespace-nowrap text-center">Keterangan</th>
                            <th scope="col" class="px-5 py-3.5 text-xs font-semibold uppercase tracking-wider whitespace-nowrap text-center">Lampiran</th>
                            <th scope="col" class="px-5 py-3.5 text-xs font-semibold uppercase tracking-wider whitespace-nowrap text-center">STATUS</th>
                            <th scope="col" class="px-5 py-3.5 text-xs font-semibold uppercase tracking-wider whitespace-nowrap text-center">Aksi</th>
                        @endif
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-gray-700">
                    @forelse ($izin as $item)
                        @php
                            $durasiHari = \Carbon\Carbon::parse($item->tanggal_mulai)->diffInDays(\Carbon\Carbon::parse($item->tanggal_selesai)) + 1;
                        @endphp
                        <tr class="hover:bg-gray-50/75 transition duration-150">
                            @if (auth()->user()->role === 'user')
                                <!-- No -->
                                <td class="px-4 py-4 text-center font-medium text-gray-500 whitespace-nowrap">
                                    {{ $izin->firstItem() + $loop->index }}
                                </td>

                                <!-- Jenis Izin -->
                                <td class="px-5 py-4 text-center whitespace-nowrap">
                                    <span class="font-semibold text-gray-900">{{ ucfirst(str_replace('_', ' ', $item->jenis)) }}</span>
                                    @if ($item->jenis === 'sakit')
                                        <span class="text-xs font-normal text-gray-500 block">
                                            {{ $item->ada_surat_dokter ? 'Dengan surat dokter' : 'Tanpa surat dokter' }}
                                        </span>
                                    @endif
                                </td>

                                <!-- Periode Tanggal -->
                                <td class="px-5 py-4 text-gray-600 text-center whitespace-nowrap">
                                    {{ \Carbon\Carbon::parse($item->tanggal_mulai)->translatedFormat('d M Y') }}
                                    <span class="text-gray-400">s/d</span>
                                    {{ \Carbon\Carbon::parse($item->tanggal_selesai)->translatedFormat('d M Y') }}
                                </td>

                                <!-- Durasi -->
                                <td class="px-5 py-4 text-center whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-100 whitespace-nowrap">
                                        {{ $durasiHari }} Hari
                                    </span>
                                </td>

                                <!-- Keterangan -->
                                <td class="px-5 py-4 text-gray-600 text-center max-w-xs truncate mx-auto" title="{{ $item->keterangan }}">
                                    {{ $item->keterangan ?: '-' }}
                                </td>

                                <!-- Lampiran -->
                                <td class="px-5 py-4 text-center whitespace-nowrap">
                                    @if ($item->file_lampiran)
                                        <a href="{{ route('izin.preview', $item->id) }}" target="_blank" aria-label="Lihat Lampiran Izin {{ $item->pegawai->nama ?? '' }}" class="p-2 rounded-lg text-[#0b602b] hover:bg-emerald-50 transition inline-block" title="Lihat Lampiran">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
                                            </svg>
                                        </a>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>

                                <!-- Status -->
                                <td class="px-5 py-4 text-center whitespace-nowrap">
                                    @if($item->status === 'disetujui')
                                        <span class="inline-block px-2.5 py-1 text-[10px] font-extrabold rounded-full bg-[#dcfce7] text-[#0b602b] uppercase tracking-wider">
                                            DISETUJUI
                                        </span>
                                    @elseif($item->status === 'disetujui_atasan')
                                        <span class="inline-block px-2.5 py-1 text-[10px] font-extrabold rounded-full bg-[#e0f2fe] text-[#0369a1] uppercase tracking-wider">
                                            DISETUJUI ATASAN
                                        </span>
                                    @elseif($item->status === 'ditolak')
                                        <span class="inline-block px-2.5 py-1 text-[10px] font-extrabold rounded-full bg-[#BA1A1A] text-white uppercase tracking-wider shadow-sm">
                                            DITOLAK
                                        </span>
                                    @else
                                        <span class="inline-block px-2.5 py-1 text-[10px] font-extrabold rounded-full bg-[#fef3c7] text-[#b45309] uppercase tracking-wider">
                                            PENDING
                                        </span>
                                    @endif
                                </td>

                                <!-- Aksi (User) -->
                                <td class="px-5 py-4 text-center whitespace-nowrap">
                                    <a href="{{ route('izin.show', $item->id) }}"
                                        aria-label="Lihat Detail Permohonan Izin"
                                        class="p-2 rounded-lg text-emerald-700 hover:bg-emerald-50 transition inline-block"
                                        title="Detail Permohonan Izin">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </a>
                                </td>
                            @else
                                <!-- Nama Pegawai (Admin / Atasan) -->
                                <td class="px-5 py-4 font-semibold text-gray-900 text-center whitespace-nowrap">
                                    {{ $item->pegawai->nama ?? '-' }}
                                    @if(!empty($item->pegawai->divisi->nama_divisi))
                                        <span class="block text-xs font-normal text-gray-500">{{ $item->pegawai->divisi->nama_divisi }}</span>
                                    @endif
                                </td>

                                <!-- Jenis Izin -->
                                <td class="px-5 py-4 text-center whitespace-nowrap">
                                    <span class="font-medium text-gray-800">{{ ucfirst(str_replace('_', ' ', $item->jenis)) }}</span>
                                    @if ($item->jenis === 'sakit')
                                        <span class="text-xs font-normal text-gray-500 block">
                                            {{ $item->ada_surat_dokter ? 'Dengan surat dokter' : 'Tanpa surat dokter' }}
                                        </span>
                                    @endif
                                </td>

                                <!-- Tanggal -->
                                <td class="px-5 py-4 text-gray-600 text-center whitespace-nowrap">
                                    {{ \Carbon\Carbon::parse($item->tanggal_mulai)->translatedFormat('d M Y') }}
                                    <span class="text-gray-400">s/d</span>
                                    {{ \Carbon\Carbon::parse($item->tanggal_selesai)->translatedFormat('d M Y') }}
                                </td>

                                <!-- Durasi -->
                                <td class="px-5 py-4 text-center whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-100 whitespace-nowrap">
                                        {{ $durasiHari }} Hari
                                    </span>
                                </td>

                                <!-- Keterangan -->
                                <td class="px-5 py-4 text-gray-600 text-center max-w-xs truncate mx-auto" title="{{ $item->keterangan }}">
                                    {{ $item->keterangan ?: '-' }}
                                </td>

                                <!-- Lampiran -->
                                <td class="px-5 py-4 text-center whitespace-nowrap">
                                    @if ($item->file_lampiran)
                                        <a href="{{ route('izin.preview', $item->id) }}" target="_blank" aria-label="Lihat Lampiran Izin {{ $item->pegawai->nama ?? '' }}" class="p-2 rounded-lg text-[#0b602b] hover:bg-emerald-50 transition inline-block" title="Lihat Lampiran">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
                                            </svg>
                                        </a>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>

                                <!-- Status -->
                                <td class="px-5 py-4 text-center whitespace-nowrap">
                                    @if($item->status === 'disetujui')
                                        <span class="inline-block px-2.5 py-1 text-[10px] font-extrabold rounded-full bg-[#dcfce7] text-[#0b602b] uppercase tracking-wider">
                                            DISETUJUI
                                        </span>
                                    @elseif($item->status === 'disetujui_atasan')
                                        <span class="inline-block px-2.5 py-1 text-[10px] font-extrabold rounded-full bg-[#e0f2fe] text-[#0369a1] uppercase tracking-wider">
                                            DISETUJUI ATASAN
                                        </span>
                                    @elseif($item->status === 'ditolak')
                                        <span class="inline-block px-2.5 py-1 text-[10px] font-extrabold rounded-full bg-[#BA1A1A] text-white uppercase tracking-wider shadow-sm">
                                            DITOLAK
                                        </span>
                                    @else
                                        <span class="inline-block px-2.5 py-1 text-[10px] font-extrabold rounded-full bg-[#fef3c7] text-[#b45309] uppercase tracking-wider">
                                            PENDING
                                        </span>
                                    @endif
                                </td>

                                <!-- Aksi (Atasan / Admin) -->
                                <td class="px-5 py-4 text-center whitespace-nowrap">
                                    @php
                                        $role = auth()->user()->role;
                                    @endphp

                                    <div class="inline-flex items-center justify-center gap-1.5">
                                        <!-- Tombol Icon Mata / Lihat Detail Halaman Izin -->
                                        <a href="{{ route('izin.show', $item->id) }}"
                                            aria-label="Lihat Detail Permohonan Izin {{ $item->pegawai->nama ?? '' }}"
                                            class="p-1.5 text-emerald-700 hover:bg-emerald-50 rounded-lg transition inline-block"
                                            title="Lihat Detail Permohonan Izin">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                        </a>

                                        @if ($role === 'atasan' && $item->status === 'pending')
                                            <form action="{{ route('izin.approve-atasan', $item->id) }}" method="POST" class="confirm-form" data-title="Setujui Izin" data-text="Apakah Anda yakin ingin menyetujui permohonan izin dari {{ $item->pegawai->nama ?? 'pegawai ini' }}?" data-icon="question" data-confirm-text="Setujui" data-confirm-color="#0b602b">
                                                @csrf @method('PATCH')
                                                <button type="submit" aria-label="Setujui Izin {{ $item->pegawai->nama ?? '' }}" class="p-1.5 text-emerald-600 hover:bg-emerald-50 rounded-lg transition" title="Setujui Atasan">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.3" d="M5 13l4 4L19 7"/></svg>
                                                </button>
                                            </form>
                                            <button type="button" aria-label="Tolak Izin {{ $item->pegawai->nama ?? '' }}" onclick="openRejectIzinModal({ url: '{{ route('izin.reject-atasan', $item->id) }}', nama: '{{ addslashes($item->pegawai->nama ?? 'pegawai ini') }}' })" class="p-1.5 text-[#BA1A1A] hover:bg-red-50 rounded-lg transition cursor-pointer" title="Tolak">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.3" d="M6 18L18 6M6 6l12 12"/></svg>
                                            </button>
                                        @elseif ($role === 'admin' && $item->status === 'disetujui_atasan')
                                            <form action="{{ route('izin.verifikasi', $item->id) }}" method="POST" class="confirm-form" data-title="Verifikasi Izin" data-text="Apakah Anda yakin ingin memverifikasi permohonan izin dari {{ $item->pegawai->nama ?? 'pegawai ini' }}?" data-icon="question" data-confirm-text="Ya, Verifikasi" data-confirm-color="#0b602b">
                                                @csrf @method('PATCH')
                                                <button type="submit" aria-label="Verifikasi Final Izin {{ $item->pegawai->nama ?? '' }}" class="p-1.5 text-emerald-600 hover:bg-emerald-50 rounded-lg transition" title="Verifikasi Final Admin">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.3" d="M5 13l4 4L19 7"/></svg>
                                                </button>
                                            </form>
                                            <button type="button" aria-label="Tolak Izin {{ $item->pegawai->nama ?? '' }}" onclick="openRejectIzinModal({ url: '{{ route('izin.reject-admin', $item->id) }}', nama: '{{ addslashes($item->pegawai->nama ?? 'pegawai ini') }}' })" class="p-1.5 text-[#BA1A1A] hover:bg-red-50 rounded-lg transition cursor-pointer" title="Tolak">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.3" d="M6 18L18 6M6 6l12 12"/></svg>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            @endif
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center text-gray-500">
                                <div class="flex flex-col items-center justify-center">
                                    <svg class="w-10 h-10 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    <p class="text-sm font-medium">Belum ada data permohonan izin ditemukan.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination Footer -->
        @if ($izin->hasPages())
            <div class="px-2 py-4 mt-2 border-t border-gray-100 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-gray-500">
                <div class="order-2 sm:order-1 text-center sm:text-left">
                    Menampilkan <span class="font-semibold text-gray-700">{{ $izin->firstItem() ?? 0 }}</span> - <span class="font-semibold text-gray-700">{{ $izin->lastItem() ?? 0 }}</span> dari <span class="font-semibold text-gray-700">{{ $izin->total() }}</span> data
                </div>
                <div class="order-1 sm:order-2 w-full sm:w-auto flex justify-center sm:justify-end">
                    {{ $izin->links() }}
                </div>
            </div>
        @endif

    </div>

    <!-- MODAL DETAIL PERMOHONAN IZIN -->
    <div id="detailIzinModal" class="fixed inset-0 z-50 hidden overflow-y-auto bg-gray-900/60 backdrop-blur-sm flex items-center justify-center p-4 transition-all duration-300" onclick="handleDetailModalBackdrop(event)">
        <div class="relative w-full max-w-2xl bg-white rounded-2xl shadow-2xl border border-gray-100 overflow-hidden transform transition-all" onclick="event.stopPropagation()">

            <!-- Header Modal -->
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between bg-gradient-to-r from-emerald-50/80 via-white to-white">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-[#0b602b]/10 text-[#0b602b] flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-gray-900">Detail Permohonan Izin</h3>
                        <p class="text-xs text-gray-500" id="modalTanggalDiajukan">Diajukan pada -</p>
                    </div>
                </div>
                <button type="button" onclick="closeDetailIzinModal()" aria-label="Tutup Detail Izin" class="w-8 h-8 rounded-lg flex items-center justify-center text-gray-500 hover:text-gray-700 hover:bg-gray-100 transition cursor-pointer">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <!-- Body Modal -->
            <div class="p-6 space-y-5 max-h-[75vh] overflow-y-auto">

                <!-- Profil Pegawai & Status -->
                <div class="bg-gray-50/80 rounded-2xl p-4 border border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div class="flex items-center gap-3.5">
                        <div id="modalPegawaiAvatarWrapper" class="shrink-0">
                            <div id="modalPegawaiAvatarPlaceholder" class="w-12 h-12 rounded-full bg-emerald-100 text-[#0b602b] font-bold flex items-center justify-center text-sm shadow-inner">
                                -
                            </div>
                            <img id="modalPegawaiAvatarImg" src="" width="48" height="48" loading="lazy" decoding="async" class="w-12 h-12 rounded-full object-cover shadow hidden" alt="Foto Pegawai">
                        </div>
                        <div>
                            <h4 id="modalPegawaiNama" class="text-base font-bold text-gray-900">-</h4>
                            <div class="flex items-center gap-2 text-xs text-gray-500 mt-0.5 flex-wrap">
                                <span id="modalPegawaiNip" class="font-medium text-gray-600">NIP: -</span>
                                <span class="text-gray-300">•</span>
                                <span id="modalPegawaiDivisi">-</span>
                                <span class="text-gray-300">•</span>
                                <span id="modalPegawaiJabatan">-</span>
                            </div>
                        </div>
                    </div>
                    <div id="modalStatusBadge" class="shrink-0 self-start sm:self-center">
                        <!-- Badge status diisi via JS -->
                    </div>
                </div>

                <!-- Grid Detail Informasi Izin -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Jenis Izin -->
                    <div class="p-4 bg-gray-50/70 rounded-xl border border-gray-100">
                        <span class="block text-[11px] font-bold text-gray-600 uppercase tracking-wider mb-1">Jenis Izin</span>
                        <p id="modalJenisIzin" class="text-sm font-bold text-gray-800">-</p>
                        <p id="modalSuratDokterInfo" class="text-xs font-semibold mt-1 hidden"></p>
                    </div>

                    <!-- Periode & Durasi -->
                    <div class="p-4 bg-gray-50/70 rounded-xl border border-gray-100">
                        <span class="block text-[11px] font-bold text-gray-600 uppercase tracking-wider mb-1">Periode & Durasi</span>
                        <p id="modalPeriode" class="text-sm font-bold text-gray-800">-</p>
                        <p id="modalDurasi" class="text-xs font-semibold text-emerald-700 mt-1">- Hari</p>
                    </div>
                </div>

                <!-- Keterangan / Alasan -->
                <div class="p-4 bg-gray-50/70 rounded-xl border border-gray-100">
                    <span class="block text-[11px] font-bold text-gray-600 uppercase tracking-wider mb-1.5">Keterangan / Alasan</span>
                    <p id="modalKeterangan" class="text-sm text-gray-700 leading-relaxed whitespace-pre-line">-</p>
                </div>

                <!-- Dokumen Lampiran -->
                <div id="modalLampiranSection" class="p-4 bg-gray-50/70 rounded-xl border border-gray-100">
                    <span class="block text-[11px] font-bold text-gray-600 uppercase tracking-wider mb-2">Dokumen Lampiran</span>
                    <div id="modalLampiranContent">
                        <!-- Preview / link dokumen diisi via JS -->
                    </div>
                </div>

                <!-- Catatan / Alasan Penolakan (Jika status ditolak) -->
                <div id="modalCatatanPenolakanSection" class="p-4 bg-red-50/80 rounded-xl border border-red-200 hidden">
                    <div class="flex items-center gap-1.5 text-xs font-bold text-red-800 uppercase tracking-wider mb-1.5">
                        <svg class="w-4 h-4 text-red-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        <span>Catatan / Alasan Penolakan</span>
                    </div>
                    <p id="modalCatatanPenolakan" class="text-sm text-red-700 leading-relaxed whitespace-pre-line ml-5.5">-</p>
                </div>

            </div>

            <!-- Footer Modal -->
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex items-center justify-between flex-wrap gap-2">
                <button type="button" onclick="closeDetailIzinModal()" class="px-5 py-2 bg-[#BA1A1A] hover:bg-[#93000a] text-white text-xs font-semibold rounded-xl transition shadow-xs cursor-pointer">
                    Tutup
                </button>
                <div id="modalActionButtons" class="flex items-center gap-2">
                    <!-- Action buttons (Verifikasi / Setujui / Tolak) diisi via JS -->
                </div>
            </div>

        </div>
    </div>

    <!-- Script Handler Modal Detail Izin -->
    <script>
        function openDetailIzinModal(data) {
            // Tanggal diajukan
            document.getElementById('modalTanggalDiajukan').textContent = 'Diajukan pada: ' + (data.created_at || '-');

            // Pegawai info
            document.getElementById('modalPegawaiNama').textContent = data.nama || '-';
            document.getElementById('modalPegawaiNip').textContent = 'NIP: ' + (data.nip || '-');
            document.getElementById('modalPegawaiDivisi').textContent = data.divisi || '-';
            document.getElementById('modalPegawaiJabatan').textContent = data.jabatan || '-';

            // Avatar
            const avatarImg = document.getElementById('modalPegawaiAvatarImg');
            const avatarPlaceholder = document.getElementById('modalPegawaiAvatarPlaceholder');
            if (data.foto) {
                avatarImg.src = data.foto;
                avatarImg.classList.remove('hidden');
                avatarPlaceholder.classList.add('hidden');
            } else {
                avatarPlaceholder.textContent = data.initial || 'P';
                avatarPlaceholder.classList.remove('hidden');
                avatarImg.classList.add('hidden');
            }

            // Status Badge
            const statusContainer = document.getElementById('modalStatusBadge');
            let badgeHtml = '';
            if (data.status === 'disetujui') {
                badgeHtml = '<span class="inline-block px-2.5 py-1 text-[10px] font-extrabold rounded-full bg-[#dcfce7] text-[#0b602b] uppercase tracking-wider">DISETUJUI</span>';
            } else if (data.status === 'disetujui_atasan') {
                badgeHtml = '<span class="inline-block px-2.5 py-1 text-[10px] font-extrabold rounded-full bg-[#e0f2fe] text-[#0369a1] uppercase tracking-wider">DISETUJUI ATASAN</span>';
            } else if (data.status === 'ditolak') {
                badgeHtml = '<span class="inline-block px-2.5 py-1 text-[10px] font-extrabold rounded-full bg-[#dc2626] text-white uppercase tracking-wider shadow-sm">DITOLAK</span>';
            } else {
                badgeHtml = '<span class="inline-block px-2.5 py-1 text-[10px] font-extrabold rounded-full bg-[#fef3c7] text-[#b45309] uppercase tracking-wider">PENDING</span>';
            }
            statusContainer.innerHTML = badgeHtml;

            // Jenis Izin & Surat Dokter
            document.getElementById('modalJenisIzin').textContent = data.jenis || '-';
            const suratDokterElem = document.getElementById('modalSuratDokterInfo');
            if (data.jenis_raw === 'sakit') {
                suratDokterElem.classList.remove('hidden');
                if (data.ada_surat_dokter) {
                    suratDokterElem.textContent = '✓ Dilengkapi surat dokter';
                    suratDokterElem.className = 'text-xs font-semibold mt-1 text-emerald-600';
                } else {
                    suratDokterElem.textContent = '⚠ Tanpa surat dokter';
                    suratDokterElem.className = 'text-xs font-semibold mt-1 text-amber-600';
                }
            } else {
                suratDokterElem.classList.add('hidden');
            }

            // Periode & Durasi
            document.getElementById('modalPeriode').textContent = data.tanggal_mulai + ' - ' + data.tanggal_selesai;
            document.getElementById('modalDurasi').textContent = (data.durasi || 1) + ' Hari Kerja';

            // Keterangan
            document.getElementById('modalKeterangan').textContent = data.keterangan || '-';

            // Catatan Penolakan
            const catatanPenolakanSection = document.getElementById('modalCatatanPenolakanSection');
            const catatanPenolakanText = document.getElementById('modalCatatanPenolakan');
            if (data.status === 'ditolak' && data.catatan_penolakan) {
                catatanPenolakanText.textContent = data.catatan_penolakan;
                catatanPenolakanSection.classList.remove('hidden');
            } else {
                catatanPenolakanSection.classList.add('hidden');
            }

            // Lampiran
            const lampiranContainer = document.getElementById('modalLampiranContent');
            if (data.file_lampiran) {
                if (data.file_is_image) {
                    lampiranContainer.innerHTML = `
                        <div class="space-y-2">
                            <div class="relative group max-w-sm rounded-xl overflow-hidden border border-gray-200 bg-black/5">
                                <img src="${data.file_lampiran}" alt="Lampiran" class="w-full max-h-56 object-contain">
                            </div>
                            <a href="${data.file_lampiran}" target="_blank" class="inline-flex items-center gap-1.5 text-xs font-semibold text-emerald-700 hover:text-emerald-900 bg-emerald-50 hover:bg-emerald-100 px-3 py-1.5 rounded-lg transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                <span>Buka Gambar Penuh</span>
                            </a>
                        </div>
                    `;
                } else {
                    lampiranContainer.innerHTML = `
                        <div class="flex items-center justify-between p-3 bg-white rounded-xl border border-gray-200">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-lg bg-red-100 text-red-600 flex items-center justify-center shrink-0">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                                </div>
                                <div>
                                    <p class="text-xs font-bold text-gray-800">Berkas Lampiran Dokumen</p>
                                    <p class="text-[11px] text-gray-400">PDF / Dokumen Pendukung</p>
                                </div>
                            </div>
                            <a href="${data.file_lampiran}" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 text-xs font-semibold rounded-lg transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                <span>Buka Tab Penuh</span>
                            </a>
                        </div>
                        <div class="mt-3 rounded-xl overflow-hidden border border-gray-200 bg-white">
                            <iframe src="${data.file_lampiran}" class="w-full h-80 border-0" title="Pratinjau Dokumen"></iframe>
                        </div>
                    `;
                }
            } else {
                lampiranContainer.innerHTML = '<p class="text-xs text-gray-400 italic">Tidak ada dokumen lampiran yang diunggah.</p>';
            }

            // Action Buttons in Modal
            const actionButtonsContainer = document.getElementById('modalActionButtons');
            let actionHtml = '';
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}';

            if (data.can_approve_admin) {
                actionHtml = `
                    <form action="${data.verifikasi_admin_url}" method="POST" class="confirm-form inline-block" data-title="Verifikasi Izin" data-text="Apakah Anda yakin ingin memverifikasi permohonan izin dari ${data.nama}?" data-icon="question" data-confirm-text="Ya, Verifikasi" data-confirm-color="#0b602b">
                        <input type="hidden" name="_token" value="${csrfToken}">
                        <input type="hidden" name="_method" value="PATCH">
                        <button type="submit" class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-[#0b602b] hover:bg-[#084d22] text-white text-xs font-semibold rounded-xl transition shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.3" d="M5 13l4 4L19 7"/></svg>
                            <span>Verifikasi Final</span>
                        </button>
                    </form>
                    <button type="button" onclick="closeDetailIzinModal(); openRejectIzinModal({ url: '${data.reject_admin_url}', nama: '${data.nama}' })" class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-[#BA1A1A] hover:bg-[#93000a] text-white text-xs font-semibold rounded-xl transition shadow-sm cursor-pointer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.3" d="M6 18L18 6M6 6l12 12"/></svg>
                        <span>Tolak</span>
                    </button>
                `;
            } else if (data.can_approve_atasan) {
                actionHtml = `
                    <form action="${data.approve_atasan_url}" method="POST" class="confirm-form inline-block" data-title="Setujui Izin" data-text="Apakah Anda yakin ingin menyetujui permohonan izin dari ${data.nama}?" data-icon="question" data-confirm-text="Ya, Setujui" data-confirm-color="#0b602b">
                        <input type="hidden" name="_token" value="${csrfToken}">
                        <input type="hidden" name="_method" value="PATCH">
                        <button type="submit" class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-[#0b602b] hover:bg-[#084d22] text-white text-xs font-semibold rounded-xl transition shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.3" d="M5 13l4 4L19 7"/></svg>
                            <span>Setujui</span>
                        </button>
                    </form>
                    <button type="button" onclick="closeDetailIzinModal(); openRejectIzinModal({ url: '${data.reject_atasan_url}', nama: '${data.nama}' })" class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-[#BA1A1A] hover:bg-[#93000a] text-white text-xs font-semibold rounded-xl transition shadow-sm cursor-pointer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.3" d="M6 18L18 6M6 6l12 12"/></svg>
                        <span>Tolak</span>
                    </button>
                `;
            }
            actionButtonsContainer.innerHTML = actionHtml;

            // Pasang event listener SweetAlert pada form dinamis di modal jika Swal tersedia
            actionButtonsContainer.querySelectorAll('.confirm-form').forEach(form => {
                form.addEventListener('submit', function(e) {
                    if (typeof Swal !== 'undefined') {
                        e.preventDefault();
                        Swal.fire({
                            title: this.dataset.title || 'Konfirmasi',
                            text: this.dataset.text || 'Apakah Anda yakin?',
                            icon: this.dataset.icon || 'warning',
                            showCancelButton: true,
                            confirmButtonColor: this.dataset.confirmColor || '#0b602b',
                            cancelButtonColor: '#2563eb',
                            confirmButtonText: this.dataset.confirmText || 'Ya',
                            cancelButtonText: 'Batal',
                            reverseButtons: true
                        }).then((result) => {
                            if (result.isConfirmed) {
                                form.submit();
                            }
                        });
                    }
                });
            });

            document.getElementById('detailIzinModal').classList.remove('hidden');
        }

        function closeDetailIzinModal() {
            document.getElementById('detailIzinModal').classList.add('hidden');
        }

        function handleDetailModalBackdrop(e) {
            if (e.target.id === 'detailIzinModal') {
                closeDetailIzinModal();
            }
        }

        function openRejectIzinModal(options) {
            const modal = document.getElementById('modalTolakIzin');
            const form = document.getElementById('formTolakIzin');
            const subtitle = document.getElementById('modalRejectSubtitle');
            const textarea = document.getElementById('reject_catatan_penolakan');

            form.action = options.url;
            subtitle.innerHTML = `Pegawai: <strong class="text-gray-800">${options.nama || 'Pegawai'}</strong>`;
            textarea.value = '';
            modal.classList.remove('hidden');
            setTimeout(() => textarea.focus(), 100);
        }

        function closeRejectIzinModal() {
            document.getElementById('modalTolakIzin').classList.add('hidden');
        }

        function handleRejectModalBackdrop(e) {
            if (e.target.id === 'modalTolakIzin') {
                closeRejectIzinModal();
            }
        }

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeDetailIzinModal();
                closeRejectIzinModal();
            }
        });
    </script>

    <!-- Modal Form Tolak Izin -->
    <div id="modalTolakIzin" class="fixed inset-0 z-50 overflow-y-auto bg-black/50 backdrop-blur-xs flex items-center justify-center p-4 hidden" onclick="handleRejectModalBackdrop(event)">
        <div class="bg-white rounded-2xl shadow-xl border border-gray-200 w-full max-w-lg overflow-hidden transform transition-all" onclick="event.stopPropagation()">
            <!-- Header -->
            <div class="px-6 py-4 bg-gray-50 border-b border-gray-200 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-red-100 text-[#BA1A1A] flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-gray-900">Tolak Permohonan Izin</h3>
                        <p class="text-xs text-gray-500" id="modalRejectSubtitle">Berikan alasan penolakan</p>
                    </div>
                </div>
                <button type="button" onclick="closeRejectIzinModal()" aria-label="Tutup Form Penolakan Izin" class="w-8 h-8 rounded-lg flex items-center justify-center text-gray-500 hover:text-gray-700 hover:bg-gray-100 transition cursor-pointer">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <!-- Form Body -->
            <form id="formTolakIzin" method="POST" action="">
                @csrf
                @method('PATCH')
                <div class="p-6 space-y-4">
                    <div class="p-3 bg-red-50 border border-red-100 rounded-xl text-xs text-red-700 leading-relaxed">
                        Alasan penolakan ini wajib diisi dan akan disampaikan kepada pegawai yang bersangkutan agar mengetahui penyebab permohonan ditolak
                    </div>

                    <div>
                        <label for="reject_catatan_penolakan" class="block text-sm font-bold text-gray-800 mb-1.5">
                            Alasan / Keterangan Penolakan <span class="text-rose-600">*</span>
                        </label>
                        <textarea id="reject_catatan_penolakan" name="catatan_penolakan" rows="4" required
                            class="block w-full border-gray-300 focus:border-[#0b602b] focus:ring-[#0b602b] rounded-xl shadow-xs text-sm placeholder-gray-400 leading-relaxed"
                            placeholder="Tuliskan alasan penolakan izin secara jelas..."></textarea>
                    </div>
                </div>

                <!-- Footer -->
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex items-center justify-end gap-3">
                    <button type="button" onclick="closeRejectIzinModal()" class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-semibold rounded-xl transition shadow-xs cursor-pointer">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2.5 bg-[#BA1A1A] hover:bg-[#93000a] text-white text-sm font-semibold rounded-xl shadow-xs transition cursor-pointer">
                        Tolak
                    </button>
                </div>
            </form>
        </div>
    </div>

</x-dashboard-layout>