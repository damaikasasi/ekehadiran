<x-dashboard-layout :title="'Presensi Upacara Hari Besar'">

    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-900 tracking-tight">Presensi Upacara Hari Besar</h2>
            <p class="mt-1 text-sm text-gray-500">
                Pencatatan dan pengelolaan absensi kehadiran pegawai pada upacara peringatan hari-hari besar
            </p>
        </div>
        <div class="w-full sm:w-auto shrink-0 flex justify-end">
            <a href="{{ route('upacara.create') }}"
               class="w-full sm:w-auto text-center inline-flex items-center justify-center gap-2 rounded-full bg-[#0b602b] hover:bg-[#084d22] px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:shadow transition whitespace-nowrap">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                </svg>
                <span>Tambah Agenda Upacara</span>
            </a>
        </div>
    </div>

    @include('kehadiran._tabs')

    @if(session('success'))
        <div class="mb-6 p-4 rounded-xl bg-green-50 border border-green-200 text-green-800 text-sm flex items-center gap-3">
            <svg class="w-5 h-5 text-green-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <!-- Mini Stat Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
        <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-green-100 text-[#0e622b] flex items-center justify-center shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
            </div>
            <div>
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Total Agenda Upacara</p>
                <h3 class="text-2xl font-bold text-gray-900 mt-0.5">{{ $totalUpacara }}</h3>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-blue-100 text-blue-700 flex items-center justify-center shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div>
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Upacara Tahun Ini ({{ now()->year }})</p>
                <h3 class="text-2xl font-bold text-gray-900 mt-0.5">{{ $totalUpacaraTahunIni }}</h3>
            </div>
        </div>
    </div>

    <!-- Main Table Card -->
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
        <!-- Filter Bar -->
        <form method="GET" action="{{ route('upacara.index') }}" class="flex flex-wrap items-end gap-3 mb-6">
            <div class="flex-1 min-w-[200px]">
                <label for="search" class="block text-xs font-semibold text-gray-600 mb-1.5 whitespace-nowrap">Cari Upacara</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <input type="text" id="search" name="search" value="{{ request('search') }}"
                           placeholder="Nama upacara..."
                           class="block w-full pl-10 pr-3 py-2.5 text-sm rounded-xl border border-gray-300 focus:ring-2 focus:ring-[#0b602b]/20 focus:border-[#0b602b] transition shadow-sm">
                </div>
            </div>

            <div class="w-40 shrink-0">
                <label for="bulan" class="block text-xs font-semibold text-gray-600 mb-1.5 whitespace-nowrap">Bulan</label>
                <select id="bulan" name="bulan"
                        class="block w-full py-2.5 pl-3.5 pr-8 text-sm rounded-xl border border-gray-300 bg-white focus:ring-2 focus:ring-[#0b602b]/20 focus:border-[#0b602b] transition shadow-sm text-gray-700 font-medium">
                    <option value="">Semua Bulan</option>
                    @for($m = 1; $m <= 12; $m++)
                        <option value="{{ $m }}" {{ request('bulan') == $m ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::create(null, $m)->translatedFormat('F') }}
                        </option>
                    @endfor
                </select>
            </div>

            <div class="w-32 shrink-0">
                <label for="tahun" class="block text-xs font-semibold text-gray-600 mb-1.5 whitespace-nowrap">Tahun</label>
                <select id="tahun" name="tahun"
                        class="block w-full py-2.5 pl-3.5 pr-8 text-sm rounded-xl border border-gray-300 bg-white focus:ring-2 focus:ring-[#0b602b]/20 focus:border-[#0b602b] transition shadow-sm text-gray-700 font-medium">
                    <option value="">Semua</option>
                    @for($y = now()->year + 1; $y >= now()->year - 4; $y--)
                        <option value="{{ $y }}" {{ request('tahun') == $y ? 'selected' : '' }}>
                            {{ $y }}
                        </option>
                    @endfor
                </select>
            </div>

            <button type="submit"
                    class="shrink-0 inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-[#0e622b] hover:bg-[#0b4d22] text-white text-sm font-semibold rounded-xl shadow-sm transition cursor-pointer whitespace-nowrap">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <span>Cari</span>
            </button>

            @if(request()->hasAny(['search', 'bulan', 'tahun']))
                <a href="{{ route('upacara.index') }}"
                   title="Reset Filter"
                   class="shrink-0 inline-flex items-center justify-center px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-semibold rounded-xl transition shadow-sm whitespace-nowrap">
                    Reset
                </a>
            @endif
        </form>

        <!-- Table -->
        <div class="overflow-x-auto rounded-xl border border-gray-200">
            <table class="w-full text-left border-collapse text-sm">
                <thead class="bg-[#0b602b] text-white uppercase text-xs">
                    <tr>
                        <th class="py-3.5 px-4 text-center w-12 font-semibold">No</th>
                        <th class="py-3.5 px-4 font-semibold">Nama Agenda Upacara</th>
                        <th class="py-3.5 px-4 font-semibold">Tanggal & Waktu</th>
                        <th class="py-3.5 px-4 text-center font-semibold">Kehadiran Peserta</th>
                        <th class="py-3.5 px-4 font-semibold">Keterangan</th>
                        <th class="py-3.5 px-4 text-center w-36 font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-gray-800">
                    @forelse($upacaraList as $index => $u)
                        <tr class="hover:bg-gray-50/80 transition">
                            <td class="py-3.5 px-4 text-center font-medium text-gray-500">
                                {{ $upacaraList->firstItem() + $index }}
                            </td>
                            <td class="py-3.5 px-4">
                                <a href="{{ route('upacara.show', $u) }}" class="font-bold text-gray-900 hover:text-[#0e622b] transition">
                                    {{ $u->nama_upacara }}
                                </a>
                            </td>
                            <td class="py-3.5 px-4">
                                <div class="font-medium text-gray-900">
                                    {{ \Carbon\Carbon::parse($u->tanggal)->translatedFormat('d F Y') }}
                                </div>
                                <div class="text-xs text-gray-500">
                                    {{ $u->waktu_mulai ? substr($u->waktu_mulai, 0, 5) . ' WIB' : 'Pukul standar upacara' }}
                                </div>
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-green-50 text-green-700 border border-green-200">
                                    {{ $u->total_hadir }} / {{ $u->total_peserta }} Hadir
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-gray-600">
                                {{ $u->keterangan ?: '-' }}
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <div class="inline-flex items-center gap-1.5">
                                    <a href="{{ route('upacara.show', $u) }}"
                                       title="Lihat & Kelola Absensi Peserta"
                                       class="p-2 rounded-lg text-gray-500 hover:bg-gray-100 transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </a>

                                    <a href="{{ route('upacara.edit', $u) }}"
                                       title="Edit Upacara"
                                       class="p-2 rounded-lg text-emerald-600 hover:bg-emerald-100 transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </a>

                                    <form action="{{ route('upacara.destroy', $u) }}" method="POST"
                                          class="confirm-form inline-block"
                                          data-title="Hapus Agenda Upacara"
                                          data-text="Apakah Anda yakin ingin menghapus agenda upacara ini? Data absensi peserta juga akan terhapus."
                                          data-icon="warning"
                                          data-icon-color="#BA1A1A"
                                          data-confirm-text="Ya, Hapus"
                                          data-confirm-color="#BA1A1A">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" title="Hapus Upacara"
                                                class="p-2 rounded-lg text-red-600 hover:bg-red-100 transition cursor-pointer">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-10 text-center text-gray-400">
                                <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                Belum ada data agenda upacara hari besar yang dicatat.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $upacaraList->links() }}
        </div>
    </div>

</x-dashboard-layout>
