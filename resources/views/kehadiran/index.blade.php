<x-dashboard-layout :title="'Presensi Pegawai'">

    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-900 tracking-tight">Daftar Kehadiran Pegawai</h2>
        <p class="mt-1 text-sm text-gray-500">
            Rekap kehadiran harian pegawai dari data fingerprint dan manual
        </p>
    </div>

    @include('kehadiran._tabs')

    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">

        <!-- Filter Bar -->
        <form method="GET" action="{{ route('kehadiran.index') }}" class="flex flex-wrap items-end gap-3 mb-6">
            <!-- 1. Cari Karyawan -->
            <div class="w-full sm:w-56 md:w-60">
                <label for="filterSearch" class="block text-xs font-semibold text-gray-600 mb-1.5 whitespace-nowrap">Cari Karyawan</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <input
                        type="text"
                        id="filterSearch"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Nama atau NIP..."
                        class="block w-full pl-9 pr-3 py-2.5 text-sm rounded-xl border border-gray-300 focus:ring-2 focus:ring-[#0b602b]/20 focus:border-[#0b602b] transition shadow-sm">
                </div>
            </div>

            <!-- 2. Divisi -->
            <div class="w-full sm:w-44 md:w-48">
                <label for="filterDivisi" class="block text-xs font-semibold text-gray-600 mb-1.5 whitespace-nowrap">Divisi</label>
                <select
                    id="filterDivisi"
                    name="divisi_id"
                    class="block w-full py-2.5 pl-3.5 pr-8 text-sm rounded-xl border border-gray-300 bg-white focus:ring-2 focus:ring-[#0b602b]/20 focus:border-[#0b602b] transition shadow-sm text-gray-700">
                    <option value="">Semua Divisi</option>
                    @foreach($divisis as $d)
                        <option value="{{ $d->id }}" {{ request('divisi_id') == $d->id ? 'selected' : '' }}>
                            {{ $d->nama_divisi }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- 3. Status -->
            <div class="w-full sm:w-36 md:w-40">
                <label for="filterStatus" class="block text-xs font-semibold text-gray-600 mb-1.5 whitespace-nowrap">Status</label>
                <select
                    id="filterStatus"
                    name="status"
                    class="block w-full py-2.5 pl-3.5 pr-8 text-sm rounded-xl border border-gray-300 bg-white focus:ring-2 focus:ring-[#0b602b]/20 focus:border-[#0b602b] transition shadow-sm text-gray-700">
                    <option value="">Semua Status</option>
                    <option value="hadir" {{ request('status') === 'hadir' ? 'selected' : '' }}>Hadir</option>
                    <option value="terlambat" {{ request('status') === 'terlambat' ? 'selected' : '' }}>Terlambat</option>
                    <option value="izin" {{ request('status') === 'izin' ? 'selected' : '' }}>Izin</option>
                    <option value="sakit" {{ request('status') === 'sakit' ? 'selected' : '' }}>Sakit</option>
                    <option value="alpha" {{ request('status') === 'alpha' ? 'selected' : '' }}>Alpha</option>
                </select>
            </div>

            <!-- 4. Jenis (WFO / WFH) -->
            <div class="w-full sm:w-36 md:w-40">
                <label for="filterJenis" class="block text-xs font-semibold text-gray-600 mb-1.5 whitespace-nowrap">Jenis Presensi</label>
                <select
                    id="filterJenis"
                    name="jenis"
                    class="block w-full py-2.5 pl-3.5 pr-8 text-sm rounded-xl border border-gray-300 bg-white focus:ring-2 focus:ring-[#0b602b]/20 focus:border-[#0b602b] transition shadow-sm text-gray-700">
                    <option value="">Semua</option>
                    <option value="wfo" {{ request('jenis') === 'wfo' ? 'selected' : '' }}>WFO</option>
                    <option value="wfh" {{ request('jenis') === 'wfh' ? 'selected' : '' }}>WFH</option>
                </select>
            </div>

            <!-- 5. Tanggal -->
            <div class="w-full sm:w-40">
                <label for="filterTanggal" class="block text-xs font-semibold text-gray-600 mb-1.5 whitespace-nowrap">Tanggal</label>
                <input
                    type="date"
                    id="filterTanggal"
                    name="tanggal"
                    value="{{ request('tanggal') }}"
                    class="block w-full py-2.5 px-3 text-sm rounded-xl border border-gray-300 bg-white focus:ring-2 focus:ring-[#0b602b]/20 focus:border-[#0b602b] transition shadow-sm text-gray-700">
            </div>

            <!-- 6. Tombol Aksi (Cari & Reset) -->
            <div class="flex items-center gap-1.5 w-full sm:w-auto mt-2 sm:mt-0">
                <button
                    type="submit"
                    class="flex-1 sm:flex-none inline-flex items-center justify-center gap-1.5 rounded-xl bg-[#0b602b] hover:bg-[#084d22] px-4 py-2.5 text-sm font-semibold text-white transition shadow-sm cursor-pointer whitespace-nowrap">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <span>Cari</span>
                </button>

                @if(request()->anyFilled(['search', 'divisi_id', 'status', 'jenis', 'tanggal']))
                    <a
                        href="{{ route('kehadiran.index') }}"
                        title="Reset Filter"
                        class="inline-flex items-center justify-center p-2.5 rounded-xl border border-gray-300 bg-gray-50 hover:bg-gray-100 text-gray-600 transition shadow-sm shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </a>
                @endif
            </div>
        </form>

        <div class="overflow-x-auto rounded-xl border border-gray-200">

            <table class="w-full text-sm text-center border-collapse">

                <thead class="bg-[#0b602b]">
                    <tr>
                        <th class="px-6 py-4 text-xs font-semibold uppercase tracking-wider text-white text-center whitespace-nowrap">NIP</th>
                        <th class="px-6 py-4 text-xs font-semibold uppercase tracking-wider text-white text-center whitespace-nowrap">Nama</th>
                        <th class="px-6 py-4 text-xs font-semibold uppercase tracking-wider text-white text-center whitespace-nowrap">Tanggal</th>
                        <th class="px-6 py-4 text-xs font-semibold uppercase tracking-wider text-white text-center whitespace-nowrap">Masuk</th>
                        <th class="px-6 py-4 text-xs font-semibold uppercase tracking-wider text-white text-center whitespace-nowrap">Keluar</th>
                        <th class="px-6 py-4 text-xs font-semibold uppercase tracking-wider text-white text-center whitespace-nowrap">Jenis</th>
                        <th class="px-6 py-4 text-xs font-semibold uppercase tracking-wider text-white text-center whitespace-nowrap">Sumber</th>
                        <th class="px-6 py-4 text-xs font-semibold uppercase tracking-wider text-white text-center whitespace-nowrap">STATUS</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100">

                    @forelse ($kehadiran as $item)

                        <tr class="hover:bg-gray-50/50 transition-colors">

                            <td class="px-6 py-4 text-sm text-gray-700 whitespace-nowrap text-center align-middle">
                                {{ $item->pegawai->user->nip ?? '-' }}
                            </td>

                            <td class="px-6 py-4 text-sm text-gray-800 font-medium whitespace-nowrap text-center align-middle">
                                {{ $item->pegawai->nama }}
                            </td>

                            <td class="px-6 py-4 text-sm text-gray-600 whitespace-nowrap text-center align-middle">
                                {{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d F Y') }}
                            </td>

                            <td class="px-6 py-4 text-sm font-medium whitespace-nowrap text-center align-middle {{ ($item->menit_telat > 0 || $item->status === 'terlambat') ? 'text-red-600 font-bold' : 'text-gray-700' }}">
                                {{ $item->jam_masuk ?? '-' }}
                            </td>

                            <td class="px-6 py-4 text-sm font-medium whitespace-nowrap text-center align-middle {{ $item->menit_pulang_awal > 0 ? 'text-red-600 font-bold' : 'text-gray-700' }}">
                                {{ $item->jam_keluar ?? '-' }}
                            </td>

                            <td class="px-6 py-4 text-sm text-gray-700 whitespace-nowrap text-center align-middle">
                                {{ strtoupper($item->jenis) }}
                            </td>

                            <td class="px-6 py-4 text-sm text-gray-700 whitespace-nowrap text-center align-middle">
                                {{ ucfirst($item->sumber) }}
                            </td>

                            <td class="px-6 py-4 text-sm text-gray-700 whitespace-nowrap text-center align-middle">
                                <div class="flex flex-col items-center justify-center gap-1">
                                    @if($item->status == 'hadir')
                                        @if($item->menit_pulang_awal <= 0)
                                            <span class="inline-block px-2.5 py-1 text-[10px] font-extrabold rounded-full bg-[#dcfce7] text-[#0b602b] border border-green-200 whitespace-nowrap uppercase tracking-wider">
                                                HADIR
                                            </span>
                                        @endif
                                    @elseif($item->status == 'terlambat')
                                        <span class="inline-block px-2.5 py-1 text-[10px] font-extrabold rounded-full bg-[#fef3c7] text-[#b45309] border border-[#fef08a] whitespace-nowrap uppercase tracking-wider">
                                            TERLAMBAT @if($item->menit_telat > 0) ({{ $item->menit_telat }} MENIT) @endif
                                        </span>
                                    @elseif($item->status == 'izin')
                                        <span class="inline-block px-2.5 py-1 text-[10px] font-extrabold rounded-full bg-blue-50 text-blue-700 border border-blue-200 whitespace-nowrap uppercase tracking-wider">
                                            IZIN
                                        </span>
                                    @elseif($item->status == 'sakit')
                                        <span class="inline-block px-2.5 py-1 text-[10px] font-extrabold rounded-full bg-indigo-50 text-indigo-700 border border-indigo-200 whitespace-nowrap uppercase tracking-wider">
                                            SAKIT
                                        </span>
                                    @else
                                        <span class="inline-block px-2.5 py-1 text-[10px] font-extrabold rounded-full bg-red-50 text-red-600 border border-red-100 whitespace-nowrap uppercase tracking-wider">
                                            ALPHA
                                        </span>
                                    @endif

                                    @if($item->menit_pulang_awal > 0)
                                        <span class="inline-block px-2.5 py-1 text-[10px] font-extrabold rounded-full bg-[#fff1f2] text-[#9f1239] border border-[#fecdd3] whitespace-nowrap uppercase tracking-wider">
                                            LEBIH AWAL ({{ (int) round($item->menit_pulang_awal) }} MENIT)
                                        </span>
                                    @endif
                                </div>
                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="8" class="text-center py-12 text-gray-500">

                                Belum ada data kehadiran.

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

        @if($kehadiran->hasPages())
            <div class="flex flex-col sm:flex-row items-center justify-between gap-3 px-2 sm:px-4 py-4 border-t border-gray-100 mt-4">
                <p class="text-xs sm:text-sm text-gray-500 order-2 sm:order-1 text-center sm:text-left">
                    Menampilkan <span class="font-semibold text-gray-700">{{ $kehadiran->firstItem() ?? 0 }}</span> - <span class="font-semibold text-gray-700">{{ $kehadiran->lastItem() ?? 0 }}</span> dari <span class="font-semibold text-gray-700">{{ $kehadiran->total() }}</span> data
                </p>
                <div class="order-1 sm:order-2 w-full sm:w-auto flex justify-center sm:justify-end">
                    {{ $kehadiran->appends(request()->query())->links() }}
                </div>
            </div>
        @endif

    </div>

</x-dashboard-layout>