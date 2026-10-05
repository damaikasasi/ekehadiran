<x-dashboard-layout :title="'Detail Agenda Upacara'">

    <!-- Breadcrumb Header -->
    <div class="mb-4 text-sm flex items-center gap-2">
        <a href="{{ route('upacara.index') }}" class="text-gray-500 hover:text-[#0b602b] transition-colors font-medium">
            Presensi Upacara
        </a>
        <span class="text-gray-300">/</span>
        <span class="font-bold text-[#0b602b]">Detail Agenda</span>
    </div>

    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-900 tracking-tight">{{ $upacara->nama_upacara }}</h2>
            <p class="mt-1 text-sm text-gray-500">
                Dilaksanakan pada {{ \Carbon\Carbon::parse($upacara->tanggal)->translatedFormat('l, d F Y') }}
                @if($upacara->waktu_mulai)
                    &bull; Pukul {{ substr($upacara->waktu_mulai, 0, 5) }} WIB
                @endif
                @if($upacara->keterangan)
                    &bull; {{ $upacara->keterangan }}
                @endif
            </p>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 rounded-xl bg-green-50 border border-green-200 text-green-800 text-sm flex items-center gap-3">
            <svg class="w-5 h-5 text-green-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <!-- Mini Stat Cards in One Row (1 Baris) -->
    <div class="flex flex-nowrap gap-4 mb-6 overflow-x-auto pb-1">
        <div class="flex-1 min-w-[140px] bg-white p-4 rounded-2xl border border-gray-200 shadow-sm text-center">
            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Total Peserta</p>
            <h3 class="text-2xl font-bold text-gray-900 mt-1">{{ $stats['total'] }}</h3>
        </div>
        <div class="flex-1 min-w-[140px] bg-white p-4 rounded-2xl border border-gray-200 shadow-sm text-center">
            <p class="text-xs font-semibold text-green-600 uppercase tracking-wider">Hadir</p>
            <h3 class="text-2xl font-bold text-green-700 mt-1">{{ $stats['hadir'] }}</h3>
        </div>
        <div class="flex-1 min-w-[140px] bg-white p-4 rounded-2xl border border-gray-200 shadow-sm text-center">
            <p class="text-xs font-semibold text-red-600 uppercase tracking-wider">Tidak Hadir</p>
            <h3 class="text-2xl font-bold text-red-700 mt-1">{{ $stats['tidak_hadir'] }}</h3>
        </div>
        <div class="flex-1 min-w-[140px] bg-white p-4 rounded-2xl border border-gray-200 shadow-sm text-center">
            <p class="text-xs font-semibold text-blue-600 uppercase tracking-wider">Izin / Sakit</p>
            <h3 class="text-2xl font-bold text-blue-700 mt-1">{{ $stats['izin'] }}</h3>
        </div>
    </div>

    <!-- Main Table Card -->
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
        <!-- Filter Bar (1 Baris) -->
        <form method="GET" action="{{ route('upacara.show', $upacara) }}" class="flex flex-row items-end gap-3 mb-6 w-full">
            <div class="flex-1 min-w-[180px]">
                <label for="search" class="block text-xs font-semibold text-gray-600 mb-1.5 whitespace-nowrap">Cari Pegawai</label>
                <div class="relative">
                    <svg class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11a6 6 0 11-12 0 6 6 0 0112 0z"/></svg>
                    <input type="text" id="search" name="search" value="{{ request('search') }}"
                           placeholder="Nama atau NIP..."
                           class="block w-full pl-9 pr-3.5 py-2.5 text-sm rounded-xl border border-gray-300 focus:ring-2 focus:ring-[#0b602b]/20 focus:border-[#0b602b] transition shadow-sm">
                </div>
            </div>

            <div class="w-48 shrink-0">
                <label for="divisi_id" class="block text-xs font-semibold text-gray-600 mb-1.5 whitespace-nowrap">Divisi</label>
                <select id="divisi_id" name="divisi_id"
                        class="block w-full py-2.5 pl-3.5 pr-8 text-sm rounded-xl border border-gray-300 bg-white focus:ring-2 focus:ring-[#0b602b]/20 focus:border-[#0b602b] transition shadow-sm text-gray-700">
                    <option value="">Semua Divisi</option>
                    @foreach($divisis as $d)
                        <option value="{{ $d->id }}" {{ request('divisi_id') == $d->id ? 'selected' : '' }}>
                            {{ $d->nama_divisi }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="w-40 shrink-0">
                <label for="status" class="block text-xs font-semibold text-gray-600 mb-1.5 whitespace-nowrap">Status</label>
                <select id="status" name="status"
                        class="block w-full py-2.5 pl-3.5 pr-8 text-sm rounded-xl border border-gray-300 bg-white focus:ring-2 focus:ring-[#0b602b]/20 focus:border-[#0b602b] transition shadow-sm text-gray-700">
                    <option value="">Semua Status</option>
                    <option value="hadir" {{ request('status') === 'hadir' ? 'selected' : '' }}>Hadir</option>
                    <option value="tidak_hadir" {{ request('status') === 'tidak_hadir' ? 'selected' : '' }}>Tidak Hadir</option>
                    <option value="izin" {{ request('status') === 'izin' ? 'selected' : '' }}>Izin</option>
                </select>
            </div>

            <button type="submit"
                    class="px-5 py-2.5 bg-[#0e622b] hover:bg-[#0b4d22] text-white text-sm font-semibold rounded-xl shadow-sm transition shrink-0">
                Filter
            </button>

            @if(request()->hasAny(['search', 'divisi_id', 'status']))
                <a href="{{ route('upacara.show', $upacara) }}"
                   class="px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-semibold rounded-xl transition shrink-0">
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
                        <th class="py-3.5 px-4 font-semibold">Nama Pegawai</th>
                        <th class="py-3.5 px-4 font-semibold">Divisi</th>
                        <th class="py-3.5 px-4 text-center font-semibold">Status Kehadiran Upacara</th>
                        <th class="py-3.5 px-4 text-center font-semibold">Keterangan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-gray-800">
                    @forelse($pesertaList as $index => $item)
                        <tr class="hover:bg-gray-50/80 transition">
                            <td class="py-3.5 px-4 text-center font-medium text-gray-500">
                                {{ $pesertaList->firstItem() + $index }}
                            </td>
                            <td class="py-3.5 px-4">
                                <div class="font-bold text-gray-900">{{ $item->pegawai?->nama ?? '-' }}</div>
                                <div class="text-xs text-gray-500">NIP: {{ $item->pegawai?->user?->nip ?: '-' }}</div>
                            </td>
                            <td class="py-3.5 px-4 text-gray-600">
                                {{ $item->pegawai?->divisi?->nama_divisi ?: '-' }}
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                @if($item->status === 'hadir')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800 border border-green-200">
                                        Hadir Upacara
                                    </span>
                                @elseif($item->status === 'izin')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800 border border-blue-200">
                                        Izin
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800 border border-red-200">
                                        Tidak Hadir
                                    </span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-center text-xs text-gray-500">
                                {{ $item->keterangan ?: '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-10 text-center text-gray-400">
                                Tidak ada data peserta yang cocok dengan filter pencarian.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $pesertaList->links() }}
        </div>
    </div>

</x-dashboard-layout>
