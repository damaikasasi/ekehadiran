<x-dashboard-layout :title="'Presensi Work From Home'">

    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-900 tracking-tight">Presensi Work From Home (WFH)</h2>
        <p class="mt-1 text-sm text-gray-500">Pantau dan verifikasi kehadiran pegawai yang bekerja dari rumah</p>
    </div>

    @include('kehadiran._tabs')

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

    <!-- Top Stats Cards & Form Toggle -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">

        <!-- Card 1: Total WFH Hari Ini -->
        <div class="bg-white rounded-2xl border border-gray-200 p-5 flex items-center gap-4 shadow-sm hover:shadow-md transition">
            <div class="w-12 h-12 rounded-xl bg-[#dcfce7] text-[#047857] flex items-center justify-center shrink-0" style="background-color: #dcfce7; color: #047857;">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
            </div>
            <div>
                <p class="text-xs font-medium text-gray-500">Total WFH Hari Ini</p>
                <h4 class="text-2xl font-black text-gray-900 mt-0.5">{{ $totalWfhHariIni }}</h4>
            </div>
        </div>

        <!-- Card 2: Form Presensi WFH Toggle -->
        <div class="bg-white rounded-2xl border border-gray-200 p-5 flex flex-col justify-center shadow-sm">
            <p class="text-xs font-semibold text-gray-600 mb-2">Form Presensi WFH</p>
            <form action="{{ route('kehadiran.wfh.toggle') }}" method="POST" class="flex items-center gap-3">
                @csrf
                <button type="submit" class="relative inline-flex h-6 w-11 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none {{ $wfhFormStatus === 'open' ? 'bg-[#0b602b]' : 'bg-gray-300' }}" role="switch" aria-checked="{{ $wfhFormStatus === 'open' ? 'true' : 'false' }}">
                    <span class="sr-only">Toggle Form WFH</span>
                    <span aria-hidden="true" class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out {{ $wfhFormStatus === 'open' ? 'translate-x-5' : 'translate-x-0' }}"></span>
                </button>
                <span class="text-sm font-bold {{ $wfhFormStatus === 'open' ? 'text-[#0b602b]' : 'text-gray-500' }}">
                    {{ $wfhFormStatus === 'open' ? 'Aktif' : 'Nonaktif' }}
                </span>
            </form>
        </div>

    </div>

    <!-- Main Table Container -->
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">

        <!-- Search, Filter & Action Bar (1 Baris: Filter di Kiri, Download di Pojok Kanan) -->
        <div class="flex items-end justify-between gap-3 mb-6 flex-wrap md:flex-nowrap">
            <form method="GET" action="{{ route('kehadiran.wfh') }}" class="flex items-end gap-2.5 flex-wrap sm:flex-nowrap">
                @if(request('sort'))
                    <input type="hidden" name="sort" value="{{ request('sort') }}">
                @endif
                @if(request('direction'))
                    <input type="hidden" name="direction" value="{{ request('direction') }}">
                @endif
                @if(request('per_page'))
                    <input type="hidden" name="per_page" value="{{ request('per_page') }}">
                @endif

                <!-- Search Input -->
                <div class="w-full sm:w-52 md:w-60">
                    <label for="search-wfh" class="block text-xs font-semibold text-gray-700 mb-1.5 whitespace-nowrap">Cari Pegawai</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                        <input
                            type="text"
                            id="search-wfh"
                            name="search"
                            value="{{ request('search') }}"
                            placeholder="Nama, NIP atau Divisi..."
                            class="block w-full pl-9 pr-3.5 py-2.5 text-sm rounded-xl border border-gray-300 focus:ring-2 focus:ring-[#0b602b]/20 focus:border-[#0b602b] transition shadow-sm text-gray-700">
                    </div>
                </div>

                <!-- Filter Bulan -->
                <div class="w-32 sm:w-36 shrink-0">
                    <label for="filter-bulan" class="block text-xs font-semibold text-gray-700 mb-1.5 whitespace-nowrap">Bulan</label>
                    <select
                        id="filter-bulan"
                        name="bulan"
                        aria-label="Pilih Bulan Presensi WFH"
                        class="block w-full py-2.5 pl-3 pr-7 text-sm rounded-xl border border-gray-300 bg-white focus:ring-2 focus:ring-[#0b602b]/20 focus:border-[#0b602b] transition shadow-sm text-gray-700 font-medium">
                        <option value="semua" {{ (string)$bulan === 'semua' ? 'selected' : '' }}>Semua Bulan</option>
                        @foreach($monthsList as $num => $name)
                            <option value="{{ $num }}" {{ (string)$bulan === (string)$num ? 'selected' : '' }}>{{ $name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Filter Tahun -->
                <div class="w-24 sm:w-28 shrink-0">
                    <label for="filter-tahun" class="block text-xs font-semibold text-gray-700 mb-1.5 whitespace-nowrap">Tahun</label>
                    <select
                        id="filter-tahun"
                        name="tahun"
                        aria-label="Pilih Tahun Presensi WFH"
                        class="block w-full py-2.5 pl-3 pr-7 text-sm rounded-xl border border-gray-300 bg-white focus:ring-2 focus:ring-[#0b602b]/20 focus:border-[#0b602b] transition shadow-sm text-gray-700 font-medium">
                        <option value="semua" {{ (string)$tahun === 'semua' ? 'selected' : '' }}>Semua Tahun</option>
                        @foreach($yearsRange as $year)
                            <option value="{{ $year }}" {{ (string)$tahun === (string)$year ? 'selected' : '' }}>{{ $year }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Tombol Cari & Reset -->
                <div class="flex items-center gap-1.5 shrink-0">
                    <button
                        type="submit"
                        class="inline-flex items-center justify-center gap-1.5 rounded-xl bg-[#0b602b] hover:bg-[#084d22] px-4 py-2.5 text-sm font-semibold text-white transition shadow-sm cursor-pointer whitespace-nowrap">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        <span>Cari</span>
                    </button>

                    @if(request('search') || (request('bulan') && request('bulan') !== (string)now()->month) || (request('tahun') && request('tahun') !== (string)now()->year))
                        <a
                            href="{{ route('kehadiran.wfh') }}"
                            aria-label="Reset Filter"
                            title="Reset Filter"
                            class="inline-flex items-center justify-center p-2.5 rounded-xl border border-gray-300 bg-gray-50 hover:bg-gray-100 text-gray-600 transition shadow-sm shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </a>
                    @endif
                </div>
            </form>

            <!-- Tombol Download Excel di Pojok Kanan -->
            <div class="shrink-0 self-end ml-auto">
                <a href="{{ route('kehadiran.wfh.export', ['bulan' => $bulan, 'tahun' => $tahun, 'search' => request('search')]) }}" class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-[#0b602b] hover:bg-[#084d22] text-white text-sm font-semibold rounded-full shadow-sm hover:shadow transition whitespace-nowrap" title="Download Excel Presensi WFH">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                    </svg>
                    <span>Download Excel</span>
                </a>
            </div>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto rounded-xl border border-gray-200">
            <table class="w-full text-left border-collapse">
                <thead class="bg-[#0b602b] text-white uppercase">
                    <tr>
                        <th scope="col" class="px-5 py-3.5 text-xs font-semibold uppercase tracking-wider whitespace-nowrap text-center">
                            <a href="{{ route('kehadiran.wfh', array_merge(request()->query(), ['sort' => 'nama', 'direction' => (request('sort') === 'nama' && request('direction') === 'asc') ? 'desc' : 'asc'])) }}"
                                aria-label="Urutkan berdasarkan Nama Pegawai"
                                class="inline-flex items-center justify-center gap-1.5 text-white hover:text-emerald-200 transition group cursor-pointer"
                                title="Urutkan berdasarkan Nama Pegawai">
                                <span>Nama Pegawai</span>
                                @if(request('sort') === 'nama')
                                    @if(request('direction') === 'asc')
                                        <svg class="w-3.5 h-3.5 text-emerald-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 15l7-7 7 7"/></svg>
                                    @else
                                        <svg class="w-3.5 h-3.5 text-emerald-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                                    @endif
                                @else
                                    <svg class="w-3.5 h-3.5 opacity-75 group-hover:opacity-100 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/></svg>
                                @endif
                            </a>
                        </th>
                        <th scope="col" class="px-5 py-3.5 text-xs font-semibold uppercase tracking-wider whitespace-nowrap text-center">
                            <a href="{{ route('kehadiran.wfh', array_merge(request()->query(), ['sort' => 'tanggal', 'direction' => (request('sort', 'tanggal') === 'tanggal' && request('direction', 'desc') === 'desc') ? 'asc' : 'desc'])) }}"
                                aria-label="Urutkan berdasarkan Tanggal"
                                class="inline-flex items-center justify-center gap-1.5 text-white hover:text-emerald-200 transition group cursor-pointer"
                                title="Urutkan berdasarkan Tanggal">
                                <span>Tanggal</span>
                                @if(request('sort', 'tanggal') === 'tanggal')
                                    @if(request('direction') === 'asc')
                                        <svg class="w-3.5 h-3.5 text-emerald-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 15l7-7 7 7"/></svg>
                                    @else
                                        <svg class="w-3.5 h-3.5 text-emerald-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                                    @endif
                                @else
                                    <svg class="w-3.5 h-3.5 opacity-75 group-hover:opacity-100 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/></svg>
                                @endif
                            </a>
                        </th>
                        <th scope="col" class="px-5 py-3.5 text-xs font-semibold uppercase tracking-wider whitespace-nowrap text-center">Jam Masuk</th>
                        <th scope="col" class="px-5 py-3.5 text-xs font-semibold uppercase tracking-wider whitespace-nowrap text-center">Jam Pulang</th>
                        <th scope="col" class="px-5 py-3.5 text-xs font-semibold uppercase tracking-wider whitespace-nowrap text-center">Bukti Swafoto</th>
                        <th scope="col" class="px-5 py-3.5 text-xs font-semibold uppercase tracking-wider whitespace-nowrap text-center">Status</th>
                        <th scope="col" class="px-5 py-3.5 text-xs font-semibold uppercase tracking-wider whitespace-nowrap text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm text-gray-700">
                    @forelse ($kehadiran as $item)
                        <tr class="hover:bg-gray-50/75 transition duration-150">
                            <!-- Nama Pegawai -->
                            <td class="px-5 py-4 font-semibold text-gray-800 whitespace-nowrap">
                                {{ $item->pegawai->nama ?? '-' }}
                            </td>

                            <!-- Tanggal -->
                            <td class="px-5 py-4 text-gray-600 whitespace-nowrap">
                                {{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d F Y') }}
                            </td>

                            <!-- Jam Masuk -->
                            <td class="px-5 py-4 whitespace-nowrap {{ ($item->menit_telat > 0 || $item->status === 'terlambat') ? 'text-red-600 font-bold' : 'text-gray-700' }}">
                                {{ $item->jam_masuk ? str_replace(':', '.', substr($item->jam_masuk, 0, 5)) : '-' }}
                            </td>

                            <!-- Jam Pulang -->
                            <td class="px-5 py-4 whitespace-nowrap">
                                @if($item->jam_keluar)
                                    <span class="{{ $item->menit_pulang_awal > 0 ? 'text-red-600 font-bold' : 'text-gray-800 font-medium' }}">
                                        {{ str_replace(':', '.', substr($item->jam_keluar, 0, 5)) }}
                                    </span>
                                @else
                                    <span class="inline-block px-2.5 py-0.5 text-[11px] font-semibold rounded-full bg-amber-50 text-amber-700 border border-amber-200">
                                        Belum Pulang
                                    </span>
                                @endif
                            </td>

                            <!-- Bukti Swafoto -->
                            <td class="px-5 py-3.5 text-center whitespace-nowrap">
                                <div class="inline-flex items-center justify-center gap-2.5">
                                    @if ($item->foto)
                                        <button
                                            type="button"
                                            onclick="showPhotoModal('{{ \App\Helpers\StorageHelper::url($item->foto) }}', 'Swafoto Masuk (IN): {{ $item->pegawai->nama ?? 'Pegawai' }} ({{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d M Y') }})')"
                                            aria-label="Lihat Swafoto Masuk {{ $item->pegawai->nama ?? '' }}"
                                            class="relative inline-block group focus:outline-none cursor-pointer"
                                            title="Swafoto Masuk (IN)">
                                            <img
                                                src="{{ \App\Helpers\StorageHelper::url($item->foto) }}"
                                                alt="Swafoto Masuk"
                                                width="40"
                                                height="40"
                                                loading="lazy"
                                                decoding="async"
                                                onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"
                                                class="w-10 h-10 object-cover rounded-xl border border-emerald-500 shadow-sm group-hover:scale-105 transition-transform duration-150"
                                                style="display: block;">
                                            <div style="display: none;" class="w-10 h-10 rounded-xl border border-dashed border-emerald-300 bg-emerald-50/80 items-center justify-center text-[10px] font-bold text-emerald-700" title="Foto tidak tersedia di server">
                                                N/A
                                            </div>
                                            <span
                                                style="position: absolute; bottom: -4px; right: -4px; background-color: #059669; color: #ffffff; font-weight: 900; font-size: 9px; padding: 1px 4px; border-radius: 4px; line-height: 1.2; box-shadow: 0 1px 3px rgba(0,0,0,0.3); border: 1.5px solid #ffffff; letter-spacing: 0.5px;">
                                                IN
                                            </span>
                                        </button>
                                    @endif

                                    @if ($item->foto_keluar)
                                        <button
                                            type="button"
                                            onclick="showPhotoModal('{{ \App\Helpers\StorageHelper::url($item->foto_keluar) }}', 'Swafoto Pulang (OUT): {{ $item->pegawai->nama ?? 'Pegawai' }} ({{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d M Y') }})')"
                                            aria-label="Lihat Swafoto Pulang {{ $item->pegawai->nama ?? '' }}"
                                            class="relative inline-block group focus:outline-none cursor-pointer"
                                            title="Swafoto Pulang (OUT)">
                                            <img
                                                src="{{ \App\Helpers\StorageHelper::url($item->foto_keluar) }}"
                                                alt="Swafoto Pulang"
                                                width="40"
                                                height="40"
                                                loading="lazy"
                                                decoding="async"
                                                onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"
                                                class="w-10 h-10 object-cover rounded-xl border border-rose-500 shadow-sm group-hover:scale-105 transition-transform duration-150"
                                                style="display: block;">
                                            <div style="display: none;" class="w-10 h-10 rounded-xl border border-dashed border-rose-300 bg-rose-50/80 items-center justify-center text-[10px] font-bold text-rose-700" title="Foto tidak tersedia di server">
                                                N/A
                                            </div>
                                            <span
                                                style="position: absolute; bottom: -4px; right: -4px; background-color: #dc2626; color: #ffffff; font-weight: 900; font-size: 9px; padding: 1px 4px; border-radius: 4px; line-height: 1.2; box-shadow: 0 1px 3px rgba(0,0,0,0.3); border: 1.5px solid #ffffff; letter-spacing: 0.5px;">
                                                OUT
                                            </span>
                                        </button>
                                    @endif

                                    @if (!$item->foto && !$item->foto_keluar)
                                        <span class="text-gray-400 font-medium text-sm">-</span>
                                    @endif
                                </div>
                            </td>

                            <!-- Status -->
                            <td class="px-5 py-4 text-center whitespace-nowrap">
                                <div class="inline-flex flex-col items-center gap-1">
                                    @if($item->status === 'hadir')
                                        @if($item->menit_pulang_awal <= 0)
                                            <span class="inline-block px-2.5 py-1 text-[10px] font-extrabold rounded-full bg-[#dcfce7] text-[#0b602b] border border-green-200 whitespace-nowrap uppercase tracking-wider">
                                                HADIR
                                            </span>
                                        @endif
                                    @elseif($item->status === 'terlambat')
                                        <span class="inline-block px-2.5 py-1 text-[10px] font-extrabold rounded-full bg-[#fef3c7] text-[#b45309] border border-[#fef08a] whitespace-nowrap uppercase tracking-wider">
                                            TERLAMBAT @if($item->menit_telat > 0) ({{ (int) round($item->menit_telat) }} MENIT) @endif
                                        </span>
                                    @elseif($item->status === 'alpha')
                                        <span class="inline-block px-2.5 py-1 text-[10px] font-extrabold rounded-full bg-red-50 text-red-600 border border-red-100 whitespace-nowrap uppercase tracking-wider">
                                            ALPHA
                                        </span>
                                    @elseif($item->status === 'menunggu_verifikasi')
                                        <span class="inline-block px-2.5 py-1 text-[10px] font-extrabold rounded-full bg-[#fef3c7] text-[#b45309] border border-[#fef08a] whitespace-nowrap uppercase tracking-wider">
                                            MENUNGGU
                                        </span>
                                    @else
                                        <span class="inline-block px-2.5 py-1 text-[10px] font-extrabold rounded-full bg-gray-100 text-gray-700 border border-gray-200 whitespace-nowrap uppercase tracking-wider">
                                            {{ strtoupper($item->status) }}
                                        </span>
                                    @endif

                                    @if($item->menit_pulang_awal > 0)
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-extrabold bg-[#fff1f2] text-[#9f1239] border border-[#fecdd3] whitespace-nowrap uppercase tracking-wider">
                                            LEBIH AWAL ({{ (int) round($item->menit_pulang_awal) }} MENIT)
                                        </span>
                                    @endif
                                </div>
                            </td>

                            <!-- Aksi -->
                            <td class="px-5 py-4 text-center whitespace-nowrap">
                                <div class="inline-flex items-center justify-center" x-data="{ openModal: false }">
                                    <button
                                        @click="openModal = true"
                                        type="button"
                                        aria-label="Lihat Rincian Bukti Presensi {{ $item->pegawai->nama ?? '' }}"
                                        class="p-2 text-[#0b602b] hover:text-[#0e622b] hover:bg-emerald-50 rounded-lg transition cursor-pointer"
                                        title="Lihat Rincian Bukti Presensi">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </button>

                                    <!-- Pop-up Modal Tindakan -->
                                    <template x-teleport="body">
                                        <div x-show="openModal"
                                             x-transition:enter="transition ease-out duration-200"
                                             x-transition:enter-start="opacity-0"
                                             x-transition:enter-end="opacity-100"
                                             x-transition:leave="transition ease-in duration-150"
                                             x-transition:leave-start="opacity-100"
                                             x-transition:leave-end="opacity-0"
                                             class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-xs"
                                             style="display: none;"
                                             @keydown.escape.window="openModal = false">

                                            <div @click.outside="openModal = false"
                                                 x-show="openModal"
                                                 x-transition:enter="transition ease-out duration-200"
                                                 x-transition:enter-start="opacity-0 scale-95"
                                                 x-transition:enter-end="opacity-100 scale-100"
                                                 x-transition:leave="transition ease-in duration-150"
                                                 x-transition:leave-start="opacity-100 scale-100"
                                                 x-transition:leave-end="opacity-0 scale-95"
                                                 class="bg-white rounded-2xl shadow-2xl border border-gray-200 w-full max-w-lg overflow-hidden text-left transform transition-all">

                                                <!-- Modal Header -->
                                                <div class="px-6 py-4 bg-gray-50 border-b border-gray-200 flex items-start justify-between gap-3">
                                                    <div class="flex items-center gap-3.5 min-w-0">
                                                        <div class="w-10 h-10 rounded-xl bg-[#0b602b] text-white flex items-center justify-center shrink-0 shadow-xs">
                                                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                                            </svg>
                                                        </div>
                                                        <div class="min-w-0">
                                                            <h3 class="text-base font-bold text-gray-900 leading-snug">Bukti & Rincian Presensi WFH</h3>
                                                            <div class="flex items-center gap-2 mt-1 flex-wrap text-xs">
                                                                <span class="font-bold text-gray-800">{{ $item->pegawai->nama ?? '-' }}</span>
                                                                <span class="text-gray-300">•</span>
                                                                <span class="inline-flex items-center gap-1 font-semibold text-white bg-[#0b602b] px-2 py-0.5 rounded-md text-[11px] shadow-2xs">
                                                                    <svg class="w-3 h-3 text-white shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                                                    {{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d F Y') }}
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <button type="button" @click="openModal = false" aria-label="Tutup Rincian Presensi" class="w-8 h-8 rounded-lg flex items-center justify-center text-gray-500 hover:text-gray-700 hover:bg-gray-100 transition cursor-pointer shrink-0 mt-0.5">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                                    </button>
                                                </div>

                                                <!-- Modal Body -->
                                                <div class="p-5 space-y-4">
                                                    <!-- Info Ringkasan Jam & Status -->
                                                    <div class="grid grid-cols-2 gap-3 p-3.5 bg-gray-50/80 rounded-xl border border-gray-200 text-xs">
                                                        <div>
                                                            <span class="text-gray-500 block">Jam Masuk</span>
                                                            <span class="font-bold text-gray-900 text-sm font-mono">{{ $item->jam_masuk ?? '-' }}</span>
                                                        </div>
                                                        <div>
                                                            <span class="text-gray-500 block">Jam Pulang</span>
                                                            <span class="font-bold text-gray-900 text-sm font-mono">{{ $item->jam_keluar ?? 'Belum Pulang' }}</span>
                                                        </div>
                                                    </div>

                                                    <!-- Section: Bukti GPS & Swafoto -->
                                                    @if($item->lokasi || $item->lokasi_keluar || $item->foto || $item->foto_keluar)
                                                        <div class="space-y-2">
                                                            <span class="block text-[11px] font-bold text-gray-600 uppercase tracking-wider">Bukti GPS & Swafoto</span>
                                                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                                                                @if($item->lokasi)
                                                                    <a href="https://www.google.com/maps?q={{ $item->lokasi }}" target="_blank" class="flex items-center gap-2.5 p-2.5 rounded-xl border border-blue-100 bg-blue-50/60 hover:bg-blue-100/80 text-blue-900 transition">
                                                                        <div class="w-7 h-7 rounded-lg bg-blue-600 text-white flex items-center justify-center shrink-0 shadow-xs">
                                                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                                                        </div>
                                                                        <div class="min-w-0">
                                                                            <span class="block text-xs font-bold truncate">GPS Masuk</span>
                                                                            <span class="block text-[10px] text-blue-600 truncate">Google Maps</span>
                                                                        </div>
                                                                    </a>
                                                                @endif

                                                                @if($item->lokasi_keluar)
                                                                    <a href="https://www.google.com/maps?q={{ $item->lokasi_keluar }}" target="_blank" class="flex items-center gap-2.5 p-2.5 rounded-xl border border-indigo-100 bg-indigo-50/60 hover:bg-indigo-100/80 text-indigo-900 transition">
                                                                        <div class="w-7 h-7 rounded-lg bg-indigo-600 text-white flex items-center justify-center shrink-0 shadow-xs">
                                                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                                                        </div>
                                                                        <div class="min-w-0">
                                                                            <span class="block text-xs font-bold truncate">GPS Pulang</span>
                                                                            <span class="block text-[10px] text-indigo-600 truncate">Google Maps</span>
                                                                        </div>
                                                                    </a>
                                                                @endif

                                                                @if($item->foto)
                                                                    <button type="button" @click="openModal = false; showPhotoModal('{{ \App\Helpers\StorageHelper::url($item->foto) }}', 'Bukti Swafoto Masuk - {{ addslashes($item->pegawai->nama ?? '') }}')" class="flex items-center gap-2.5 p-2.5 rounded-xl border border-gray-200 bg-gray-50/80 hover:bg-gray-100 text-gray-800 transition cursor-pointer text-left">
                                                                        <div class="w-7 h-7 rounded-lg bg-gray-700 text-white flex items-center justify-center shrink-0 shadow-xs">
                                                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                                                        </div>
                                                                        <div class="min-w-0">
                                                                            <span class="block text-xs font-bold truncate">Foto Masuk</span>
                                                                            <span class="block text-[10px] text-gray-500 truncate">Lihat Swafoto</span>
                                                                        </div>
                                                                    </button>
                                                                @endif

                                                                @if($item->foto_keluar)
                                                                    <button type="button" @click="openModal = false; showPhotoModal('{{ \App\Helpers\StorageHelper::url($item->foto_keluar) }}', 'Bukti Swafoto Pulang - {{ addslashes($item->pegawai->nama ?? '') }}')" class="flex items-center gap-2.5 p-2.5 rounded-xl border border-gray-200 bg-gray-50/80 hover:bg-gray-100 text-gray-800 transition cursor-pointer text-left">
                                                                        <div class="w-7 h-7 rounded-lg bg-gray-700 text-white flex items-center justify-center shrink-0 shadow-xs">
                                                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                                                        </div>
                                                                        <div class="min-w-0">
                                                                            <span class="block text-xs font-bold truncate">Foto Pulang</span>
                                                                            <span class="block text-[10px] text-gray-500 truncate">Lihat Swafoto</span>
                                                                        </div>
                                                                    </button>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    @else
                                                        <div class="py-6 text-center text-gray-500 text-xs italic">
                                                            Tidak ada rekaman bukti foto atau lokasi GPS untuk data ini.
                                                        </div>
                                                    @endif
                                                </div>

                                                <!-- Modal Footer -->
                                                <div class="px-6 py-3.5 bg-gray-50 border-t border-gray-100 flex items-center justify-end">
                                                    <button type="button" @click="openModal = false" class="px-5 py-2 bg-[#BA1A1A] hover:bg-[#93000a] text-white text-xs font-semibold rounded-xl transition shadow-xs cursor-pointer">
                                                        Tutup
                                                    </button>
                                                </div>

                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                <div class="flex flex-col items-center justify-center">
                                    <svg class="w-10 h-10 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                                    </svg>
                                    <p class="text-sm font-medium">Belum ada data presensi WFH ditemukan.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination Footer -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mt-6 text-sm text-gray-600">
            <!-- Rows Per Page -->
            <div class="flex items-center gap-2 text-xs">
                <span>Rows per page</span>
                <form method="GET" action="{{ route('kehadiran.wfh') }}" class="inline">
                    @if(request('search'))
                        <input type="hidden" name="search" value="{{ request('search') }}">
                    @endif
                    @if(request('bulan'))
                        <input type="hidden" name="bulan" value="{{ request('bulan') }}">
                    @endif
                    @if(request('tahun'))
                        <input type="hidden" name="tahun" value="{{ request('tahun') }}">
                    @endif
                    <select
                        name="per_page"
                        id="per_page"
                        aria-label="Jumlah data per halaman"
                        onchange="this.form.submit()"
                        class="border-gray-300 rounded-lg text-xs py-1 pl-2.5 pr-7 bg-white shadow-sm focus:ring-[#0b602b] focus:border-[#0b602b]">
                        <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10</option>
                        <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                        <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                        <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                    </select>
                </form>
                <span>of {{ $kehadiran->total() }} rows</span>
            </div>

            <!-- Pagination Numbers -->
            @if ($kehadiran->hasPages())
                <div class="flex items-center gap-1.5 text-xs">
                    {{-- Previous Page Link --}}
                    @if ($kehadiran->onFirstPage())
                        <span class="w-8 h-8 flex items-center justify-center rounded-lg border border-gray-200 text-gray-300 cursor-not-allowed">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                        </span>
                    @else
                        <a href="{{ $kehadiran->previousPageUrl() }}" aria-label="Halaman Sebelumnya" class="w-8 h-8 flex items-center justify-center rounded-lg border border-gray-200 text-gray-600 hover:bg-gray-100 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                        </a>
                    @endif

                    {{-- Pagination Elements --}}
                    @foreach ($kehadiran->getUrlRange(max(1, $kehadiran->currentPage() - 2), min($kehadiran->lastPage(), $kehadiran->currentPage() + 2)) as $page => $url)
                        @if ($page == $kehadiran->currentPage())
                            <span class="w-8 h-8 flex items-center justify-center rounded-full bg-[#0b602b] text-white font-bold shadow-sm" aria-current="page">
                                {{ $page }}
                            </span>
                        @else
                            <a href="{{ $url }}" aria-label="Halaman {{ $page }}" class="w-8 h-8 flex items-center justify-center rounded-lg text-gray-600 hover:bg-gray-100 transition">
                                {{ $page }}
                            </a>
                        @endif
                    @endforeach

                    {{-- Next Page Link --}}
                    @if ($kehadiran->hasMorePages())
                        <a href="{{ $kehadiran->nextPageUrl() }}" aria-label="Halaman Selanjutnya" class="w-8 h-8 flex items-center justify-center rounded-lg border border-gray-200 text-gray-600 hover:bg-gray-100 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </a>
                    @else
                        <span class="w-8 h-8 flex items-center justify-center rounded-lg border border-gray-200 text-gray-300 cursor-not-allowed">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </span>
                    @endif
                </div>
            @endif
        </div>

    </div>

    <!-- Photo Preview Modal -->
    <div id="photoModal" class="fixed inset-0 z-50 bg-black/75 hidden flex items-center justify-center p-4 backdrop-blur-sm" onclick="closePhotoModal(event)">
        <div class="bg-white rounded-2xl overflow-hidden max-w-lg w-full shadow-2xl relative" onclick="event.stopPropagation()">
            <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between bg-gray-50">
                <h4 id="photoModalTitle" class="text-sm font-bold text-gray-800">Bukti Swafoto WFH</h4>
                <button type="button" onclick="closePhotoModal()" aria-label="Tutup Pratinjau Foto" class="text-gray-500 hover:text-gray-700">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <div class="p-4 flex items-center justify-center bg-gray-900/5 min-h-[160px]">
                <img id="photoModalImg" src="" alt="Swafoto" class="max-h-[70vh] w-auto rounded-xl object-contain shadow">
                <div id="photoModalError" class="hidden text-center p-6 text-gray-500 text-sm">
                    <svg class="w-10 h-10 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    <span>Berkas foto tidak ditemukan di server.</span>
                </div>
            </div>
            <div class="p-3 bg-gray-50 border-t border-gray-100 flex justify-end">
                <button type="button" onclick="closePhotoModal()" class="px-5 py-2 bg-[#BA1A1A] hover:bg-[#93000a] text-white text-xs font-semibold rounded-xl transition shadow-xs cursor-pointer">
                    Tutup
                </button>
            </div>
        </div>
    </div>

    <script>
        function showPhotoModal(src, title) {
            const img = document.getElementById('photoModalImg');
            const errorBox = document.getElementById('photoModalError');
            img.style.display = 'block';
            if (errorBox) errorBox.classList.add('hidden');

            img.onerror = function() {
                this.style.display = 'none';
                if (errorBox) errorBox.classList.remove('hidden');
            };

            img.src = src;
            document.getElementById('photoModalTitle').textContent = title || 'Bukti Swafoto WFH';
            document.getElementById('photoModal').classList.remove('hidden');
        }

        function closePhotoModal() {
            document.getElementById('photoModal').classList.add('hidden');
        }
    </script>

</x-dashboard-layout>