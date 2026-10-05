<x-dashboard-layout :title="'Laporan Kinerja'">

    @if(auth()->user()->role === 'atasan')
        <!-- REDESIGNED ATASAN REVIEW LAYOUT -->
        <div class="space-y-8">
            <!-- HEADER -->
            <div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h2 class="text-3xl font-extrabold text-[#0e622b] tracking-tight">Nilai Laporan Bulanan Pegawai</h2>
                    <p class="mt-2 text-sm text-gray-500">Review dan nilai laporan bulanan {{ strtoupper($divisiName ?? 'divisi') }}</p>
                </div>
                <div class="flex flex-wrap items-center gap-2">
                    <button type="button" onclick="openDownloadModal('triwulan')" class="inline-flex items-center justify-center gap-1.5 rounded-full bg-[#BA1A1A] hover:bg-[#961313] px-4 py-2.5 text-xs sm:text-sm font-semibold text-white shadow-xs hover:shadow transition cursor-pointer whitespace-nowrap">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                        <span>Download Triwulan</span>
                    </button>
                    <button type="button" onclick="openDownloadModal('tahunan')" class="inline-flex items-center justify-center gap-1.5 rounded-full bg-[#0e622b] hover:bg-[#0b4d22] px-4 py-2.5 text-xs sm:text-sm font-semibold text-white shadow-xs hover:shadow transition cursor-pointer whitespace-nowrap">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                        <span>Download Rekap Tahunan</span>
                    </button>
                </div>
            </div>
            @if (session('success'))
                <script>
                    document.addEventListener('DOMContentLoaded', () => {
                        const showPopup = () => {
                            if (typeof Swal !== 'undefined') {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil!',
                                    text: {!! json_encode(session('success')) !!},
                                    confirmButtonColor: '#0b602b',
                                    confirmButtonText: 'Selesai',
                                    customClass: {
                                        popup: 'rounded-2xl shadow-xl',
                                        confirmButton: 'rounded-full px-6 py-2.5 font-bold text-sm'
                                    }
                                });
                            } else {
                                setTimeout(showPopup, 50);
                            }
                        };
                        showPopup();
                    });
                </script>
            @endif

            <!-- CARD TABLE DENGAN TAB FILTER -->
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-4 sm:p-6">
                <!-- FILTER & TAB HEADER (Satu Baris) -->
                <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 mb-6">
                    <!-- Tab Filters (Status Laporan) -->
                    <div class="flex items-center gap-1.5 sm:gap-2 shrink-0">
                        @php
                            $activeStatus = request('status');
                        @endphp
                        <a href="{{ route('laporan-mingguan.index', array_filter(request()->except('status', 'page'))) }}" 
                           class="{{ !$activeStatus ? 'px-3.5 py-2 rounded-xl text-xs sm:text-sm font-semibold shadow-xs' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100/70 px-3.5 py-2 text-xs sm:text-sm font-medium rounded-xl transition' }}"
                           style="{{ !$activeStatus ? 'background-color: #0e622b !important; color: #ffffff !important;' : '' }}">
                            Semua
                        </a>
                        <a href="{{ route('laporan-mingguan.index', array_merge(request()->except('page'), ['status' => 'belum_direview'])) }}" 
                           class="{{ in_array($activeStatus, ['belum_direview', 'menunggu']) ? 'px-3.5 py-2 rounded-xl text-xs sm:text-sm font-semibold shadow-xs' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100/70 px-3.5 py-2 text-xs sm:text-sm font-medium rounded-xl transition' }}"
                           style="{{ in_array($activeStatus, ['belum_direview', 'menunggu']) ? 'background-color: #0e622b !important; color: #ffffff !important;' : '' }}">
                            Belum Direview
                        </a>
                        <a href="{{ route('laporan-mingguan.index', array_merge(request()->except('page'), ['status' => 'sudah_direview'])) }}" 
                           class="{{ in_array($activeStatus, ['sudah_direview', 'terverifikasi', 'ditolak']) ? 'px-3.5 py-2 rounded-xl text-xs sm:text-sm font-semibold shadow-xs' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100/70 px-3.5 py-2 text-xs sm:text-sm font-medium rounded-xl transition' }}"
                           style="{{ in_array($activeStatus, ['sudah_direview', 'terverifikasi', 'ditolak']) ? 'background-color: #0e622b !important; color: #ffffff !important;' : '' }}">
                            Sudah Direview
                        </a>
                    </div>

                    <!-- Search & Filter Bar (Satu Baris Sejajar) -->
                    <form method="GET" action="{{ route('laporan-mingguan.index') }}" class="flex items-center gap-2 w-full lg:w-auto flex-wrap sm:flex-nowrap">
                        @if(request('status'))
                            <input type="hidden" name="status" value="{{ request('status') }}">
                        @endif

                        <!-- Input Cari Pegawai / NIP -->
                        <div class="relative flex-1 sm:w-56 md:w-64 min-w-[160px]">
                            <svg class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11a6 6 0 11-12 0 6 6 0 0112 0z"/>
                            </svg>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari pegawai / NIP..." aria-label="Cari Pegawai atau NIP"
                                class="w-full pl-9 pr-3 py-2 text-xs sm:text-sm rounded-xl border border-gray-300 focus:ring-2 focus:ring-[#0b602b]/20 focus:border-[#0b602b] transition shadow-xs text-gray-700 placeholder-gray-400 bg-white">
                        </div>

                        <!-- Dropdown Filter Bulan -->
                        <div class="w-36 sm:w-40 shrink-0">
                            <select name="bulan" aria-label="Filter Bulan" class="w-full pl-3 pr-8 py-2 text-xs sm:text-sm rounded-xl border border-gray-300 bg-white focus:ring-2 focus:ring-[#0b602b]/20 focus:border-[#0b602b] transition shadow-xs text-gray-700 cursor-pointer">
                                <option value="">Semua Bulan</option>
                                @foreach([1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'] as $num => $namaBulan)
                                    <option value="{{ $num }}" {{ request('bulan') == $num ? 'selected' : '' }}>{{ $namaBulan }}</option>
                                @endforeach
                            </select>
                        </div>

                        @if(isset($pegawaiList) && $pegawaiList->count() > 0)
                            <!-- Dropdown Filter Pegawai -->
                            <div class="w-40 sm:w-44 shrink-0">
                                <select name="pegawai_id" aria-label="Filter Pegawai" class="w-full pl-3 pr-8 py-2 text-xs sm:text-sm rounded-xl border border-gray-300 bg-white focus:ring-2 focus:ring-[#0b602b]/20 focus:border-[#0b602b] transition shadow-xs text-gray-700 cursor-pointer">
                                    <option value="">Semua Pegawai</option>
                                    @foreach ($pegawaiList as $p)
                                        @if(auth()->check() && auth()->user()->pegawai && $p->id === auth()->user()->pegawai->id)
                                            @continue
                                        @endif
                                        <option value="{{ $p->id }}" {{ request('pegawai_id') == $p->id ? 'selected' : '' }}>{{ $p->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @endif

                        <!-- Tombol Cari & Reset (Sejajar dalam baris yang sama) -->
                        <div class="flex items-center gap-1.5 shrink-0">
                            <button type="submit" class="inline-flex items-center justify-center gap-1.5 rounded-xl bg-[#0b602b] hover:bg-[#084920] px-4 py-2 text-xs sm:text-sm font-semibold text-white transition shadow-xs cursor-pointer whitespace-nowrap">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                <span>Cari</span>
                            </button>

                            @if(request()->anyFilled(['search', 'bulan', 'pegawai_id', 'tahun']))
                                <a href="{{ route('laporan-mingguan.index', request('status') ? ['status' => request('status')] : []) }}" aria-label="Reset Filter" title="Reset Filter" class="p-2 bg-gray-50 border border-gray-300 hover:bg-gray-100 text-gray-600 rounded-xl transition shadow-xs shrink-0 flex items-center justify-center">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                </a>
                            @endif
                        </div>
                    </form>
                </div>

                <!-- TABLE WRAPPER (Sama seperti daftar pegawai, tidak mentok ke samping) -->
                <div class="overflow-x-auto rounded-xl border border-gray-200">
                    <table class="w-full text-sm text-center border-collapse min-w-[750px]">
                        <thead class="bg-[#0b602b] text-white uppercase">
                            <tr>
                                <th scope="col" class="px-4 py-3.5 text-xs font-semibold uppercase tracking-wider text-white text-center whitespace-nowrap">NAMA PEGAWAI</th>
                                <th scope="col" class="px-3.5 py-3.5 text-xs font-semibold uppercase tracking-wider text-white text-center whitespace-nowrap">BULAN/TAHUN</th>
                                <th scope="col" class="px-3.5 py-3.5 text-xs font-semibold uppercase tracking-wider text-white text-center whitespace-nowrap">TANGGAL</th>
                                <th scope="col" class="px-4 py-3.5 text-xs font-semibold uppercase tracking-wider text-white text-center whitespace-nowrap">LAPORAN</th>
                                <th scope="col" class="px-3.5 py-3.5 text-xs font-semibold uppercase tracking-wider text-white text-center whitespace-nowrap">STATUS</th>
                                <th scope="col" class="px-4 py-3.5 text-xs font-semibold uppercase tracking-wider text-white text-center whitespace-nowrap">NILAI & CATATAN</th>
                                <th scope="col" class="px-4 py-3.5 text-xs font-semibold uppercase tracking-wider text-white text-center whitespace-nowrap">AKSI</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse ($laporan as $item)
                                <tr class="hover:bg-gray-50/30 transition">
                                    <td class="py-3.5 px-4 font-bold text-gray-800 text-center whitespace-nowrap">{{ $item->pegawai->nama ?? '-' }}</td>
                                    <td class="py-3.5 px-3 text-gray-500 text-xs sm:text-sm text-center whitespace-nowrap">{{ \Carbon\Carbon::create()->month($item->bulan)->translatedFormat('F') }} {{ $item->tahun }}</td>
                                    <td class="py-3.5 px-3 text-gray-500 text-xs sm:text-sm text-center whitespace-nowrap">
                                        {{ $item->created_at ? $item->created_at->translatedFormat('d M Y') : '-' }}
                                    </td>
                                    <td class="py-3.5 px-4 text-center whitespace-nowrap align-middle">
                                        <a href="{{ route('laporan-mingguan.preview', $item->id) }}" target="_blank" 
                                           class="text-[#0e622b] hover:text-[#0b4d22] font-semibold inline-flex items-center justify-center gap-1.5 transition text-xs sm:text-sm hover:underline" 
                                           title="laporan-kinerja-{{ Str::slug($item->pegawai->nama ?? 'pegawai') }}-{{ \Carbon\Carbon::create()->month($item->bulan)->translatedFormat('F') }}-{{ $item->tahun }}.pdf">
                                            <svg class="w-4 h-4 shrink-0 text-[#0b602b]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg>
                                            <span>Laporan Kinerja.pdf</span>
                                        </a>
                                    </td>
                                    <td class="py-3.5 px-3 text-center whitespace-nowrap">
                                        @if($item->status === 'menunggu')
                                            <span class="inline-flex items-center whitespace-nowrap bg-red-50 text-red-600 border border-red-200 px-2.5 py-1 rounded-full text-[10px] font-extrabold uppercase tracking-wider">
                                                BELUM DIREVIEW
                                            </span>
                                        @else
                                            <span class="inline-flex items-center whitespace-nowrap bg-green-50 text-green-700 border border-green-200 px-2.5 py-1 rounded-full text-[10px] font-extrabold uppercase tracking-wider">
                                                SUDAH DIREVIEW
                                            </span>
                                        @endif
                                    </td>
                                    <td class="py-3 px-3 text-center">
                                        <div class="flex flex-col items-center justify-center gap-1 max-w-[200px] mx-auto">
                                            @if($item->nilai === 'diatas_ekspektasi')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-indigo-50 text-indigo-700 border border-indigo-200 whitespace-nowrap">
                                                    Di Atas Ekspektasi
                                                </span>
                                            @elseif($item->nilai === 'sesuai_ekspektasi')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-blue-50 text-blue-700 border border-blue-200 whitespace-nowrap">
                                                    Sesuai Ekspektasi
                                                </span>
                                            @elseif($item->nilai === 'dibawah_ekspektasi')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200 whitespace-nowrap">
                                                    Di Bawah Ekspektasi
                                                </span>
                                            @elseif($item->nilai)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-gray-50 text-gray-700 border border-gray-200 whitespace-nowrap">
                                                    {{ ucwords(str_replace('_', ' ', $item->nilai)) }}
                                                </span>
                                            @else
                                                <span class="text-xs text-gray-500 font-medium italic">Belum Dinilai</span>
                                            @endif

                                            @if($item->catatan_atasan)
                                                <p class="text-xs text-gray-600 font-normal line-clamp-1 text-center whitespace-normal leading-relaxed" title="{{ $item->catatan_atasan }}">
                                                    "{{ $item->catatan_atasan }}"
                                                </p>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="py-3.5 px-4 text-center whitespace-nowrap">
                                        @if($item->nilai || $item->status !== 'menunggu')
                                            <a href="{{ route('laporan-mingguan.show', $item->id) }}" 
                                               class="text-[#0e622b] hover:text-[#0b4d22] p-1.5 rounded-lg hover:bg-emerald-50 transition inline-flex items-center justify-center"
                                               title="Lihat Rincian Penilaian">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                            </a>
                                        @else
                                            <a href="{{ route('laporan-mingguan.nilai-form', $item->id) }}" 
                                               class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-lg font-semibold text-xs text-white bg-[#0b602b] hover:bg-[#084d22] transition shadow-sm"
                                               title="Beri Penilaian Laporan">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                                <span>Nilai</span>
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="py-12 text-center text-gray-500">Belum ada laporan kinerja.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- PAGINATION (Sama seperti daftar pegawai) -->
                @if ($laporan->hasPages())
                    <div class="flex flex-col sm:flex-row items-center justify-between gap-3 px-2 sm:px-4 py-4 border-t border-gray-100 mt-4">
                        <p class="text-xs sm:text-sm text-gray-600 order-2 sm:order-1 text-center sm:text-left">
                            Menampilkan <span class="font-semibold text-gray-700">{{ $laporan->firstItem() ?? 0 }}</span> - <span class="font-semibold text-gray-700">{{ $laporan->lastItem() ?? 0 }}</span> dari <span class="font-semibold text-gray-700">{{ $laporan->total() }}</span> laporan
                        </p>
                        <div class="order-1 sm:order-2 w-full sm:w-auto flex justify-center sm:justify-end">
                            {{ $laporan->appends(request()->query())->links() }}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    @else
        <!-- STANDARD USER/ADMIN LAPORAN KINERJA VIEW -->
        <div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 tracking-tight">Daftar Laporan Kinerja</h2>
                <p class="mt-1 text-sm text-gray-500">Riwayat laporan kinerja bulanan pegawai.</p>
            </div>

            <div class="flex flex-wrap items-center gap-2.5">
                @if (auth()->user()->role === 'admin')
                    {{-- Toggle Upload Form Switch --}}
                    <div class="inline-flex items-center gap-2.5 px-3.5 py-2 bg-white border border-gray-200 rounded-xl shadow-xs">
                        <span class="text-xs font-semibold text-gray-700">Form Laporan:</span>
                        <form action="{{ route('laporan-mingguan.toggle-upload') }}" method="POST" class="flex items-center gap-2 m-0">
                            @csrf
                            <button type="submit" class="relative inline-flex h-5 w-10 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none {{ $laporanUploadStatus === 'open' ? 'bg-[#0b602b]' : 'bg-gray-400' }}" role="switch" aria-checked="{{ $laporanUploadStatus === 'open' ? 'true' : 'false' }}" aria-label="Toggle Form Laporan" title="Klik untuk {{ $laporanUploadStatus === 'open' ? 'menonaktifkan' : 'mengaktifkan' }} pengiriman laporan">
                                <span class="sr-only">Toggle Form Laporan</span>
                                <span aria-hidden="true" class="pointer-events-none inline-block h-4 w-4 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out {{ $laporanUploadStatus === 'open' ? 'translate-x-5' : 'translate-x-0' }}"></span>
                            </button>
                            <span class="text-xs font-bold {{ $laporanUploadStatus === 'open' ? 'text-[#0b602b]' : 'text-gray-600' }}">
                                {{ $laporanUploadStatus === 'open' ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </form>
                    </div>
                @endif

                @if(auth()->user()->role === 'user')
                    @if($laporanUploadStatus === 'open')
                        <a href="{{ route('laporan-mingguan.create') }}"
                            class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full text-white text-xs sm:text-sm font-bold shadow-xs hover:shadow transition bg-[#0b602b] hover:bg-[#084d22]">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                            <span>Tambah Laporan</span>
                        </a>
                    @else
                        <span class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full text-xs sm:text-sm font-bold bg-gray-100 text-gray-500 cursor-not-allowed" title="Pengiriman laporan sementara ditutup oleh admin">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                            <span>Upload Ditutup</span>
                        </span>
                    @endif
                @endif

                @if (auth()->user()->role === 'admin' || auth()->user()->role === 'user')
                    <button type="button" onclick="openDownloadModal('triwulan')" class="inline-flex items-center justify-center gap-1.5 rounded-full bg-[#BA1A1A] hover:bg-[#961313] px-4 py-2.5 text-xs sm:text-sm font-semibold text-white shadow-xs hover:shadow transition cursor-pointer whitespace-nowrap">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                        <span>Download Triwulan</span>
                    </button>
                    <button type="button" onclick="openDownloadModal('tahunan')" class="inline-flex items-center justify-center gap-1.5 rounded-full bg-[#0e622b] hover:bg-[#0b4d22] px-4 py-2.5 text-xs sm:text-sm font-semibold text-white shadow-xs hover:shadow transition cursor-pointer whitespace-nowrap">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                        <span>Download Rekap Tahunan</span>
                    </button>
                @endif
            </div>
        </div>

        @if (session('success'))
            <script>
                document.addEventListener('DOMContentLoaded', () => {
                    const showPopup = () => {
                        if (typeof Swal !== 'undefined') {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: {!! json_encode(session('success')) !!},
                                confirmButtonColor: '#0b602b',
                                confirmButtonText: 'Selesai',
                                customClass: {
                                    popup: 'rounded-2xl shadow-xl',
                                    confirmButton: 'rounded-full px-6 py-2.5 font-bold text-sm'
                                }
                            });
                        } else {
                            setTimeout(showPopup, 50);
                        }
                    };
                    showPopup();
                });
            </script>
        @endif
        @if (session('error'))
            <script>
                document.addEventListener('DOMContentLoaded', () => {
                    const showPopup = () => {
                        if (typeof Swal !== 'undefined') {
                            Swal.fire({
                                icon: 'error',
                                title: 'Perhatian!',
                                text: {!! json_encode(session('error')) !!},
                                confirmButtonColor: '#BA1A1A',
                                confirmButtonText: 'Tutup',
                                customClass: {
                                    popup: 'rounded-2xl shadow-xl',
                                    confirmButton: 'rounded-full px-6 py-2.5 font-bold text-sm'
                                }
                            });
                        } else {
                            setTimeout(showPopup, 50);
                        }
                    };
                    showPopup();
                });
            </script>
        @endif

        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-4 sm:p-6">
            <!-- Filter Bar -->
            <div class="mb-5 pb-4 border-b border-gray-100">
                <form method="GET" action="{{ route('laporan-mingguan.index') }}" class="flex flex-wrap items-end gap-3">
                    @if(request('per_page'))
                        <input type="hidden" name="per_page" value="{{ request('per_page') }}">
                    @endif

                    <!-- Search Input -->
                    <div class="w-full sm:w-56 md:w-64">
                        <label for="search_laporan" class="block text-xs font-semibold text-gray-700 mb-1.5 whitespace-nowrap">{{ auth()->user()->role === 'user' ? 'Cari Kegiatan' : 'Cari Pegawai' }}</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                            </div>
                            <input
                                type="text"
                                id="search_laporan"
                                name="search"
                                value="{{ request('search') }}"
                                placeholder="{{ auth()->user()->role === 'user' ? 'Cari isi kegiatan...' : 'Nama pegawai...' }}"
                                aria-label="{{ auth()->user()->role === 'user' ? 'Cari isi kegiatan' : 'Cari nama pegawai' }}"
                                class="block w-full pl-9 pr-3 py-2.5 text-sm rounded-xl border border-gray-300 focus:ring-2 focus:ring-[#0b602b]/20 focus:border-[#0b602b] transition shadow-sm text-gray-700 bg-white placeholder-gray-500">
                        </div>
                    </div>

                    <!-- Filter Bulan -->
                    <div class="w-full sm:w-auto flex-1 sm:flex-none">
                        <label for="filter_bulan" class="block text-xs font-semibold text-gray-700 mb-1.5 whitespace-nowrap">Bulan</label>
                        <select id="filter_bulan" name="bulan" aria-label="Filter Bulan" class="w-full sm:w-auto block pl-3.5 pr-9 py-2.5 text-sm rounded-xl border border-gray-300 bg-white focus:ring-2 focus:ring-[#0b602b]/20 focus:border-[#0b602b] transition shadow-sm text-gray-700 cursor-pointer">
                            <option value="">Semua Bulan</option>
                            @foreach([1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'] as $num => $namaBulan)
                                <option value="{{ $num }}" {{ request('bulan') == $num ? 'selected' : '' }}>{{ $namaBulan }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Filter Status -->
                    <div class="w-full sm:w-auto flex-1 sm:flex-none">
                        <label for="filter_status" class="block text-xs font-semibold text-gray-700 mb-1.5 whitespace-nowrap">Status</label>
                        <select id="filter_status" name="status" aria-label="Filter Status" class="w-full sm:w-auto block pl-3.5 pr-9 py-2.5 text-sm rounded-xl border border-gray-300 bg-white focus:ring-2 focus:ring-[#0b602b]/20 focus:border-[#0b602b] transition shadow-sm text-gray-700 cursor-pointer">
                            <option value="">Semua Status</option>
                            <option value="belum_direview" {{ in_array(request('status'), ['belum_direview', 'menunggu']) ? 'selected' : '' }}>Belum Direview</option>
                            <option value="sudah_direview" {{ in_array(request('status'), ['sudah_direview', 'terverifikasi', 'ditolak']) ? 'selected' : '' }}>Sudah Direview</option>
                        </select>
                    </div>

                    <!-- Tombol Cari & Reset -->
                    <div class="flex items-center gap-2">
                        <button
                            type="submit"
                            aria-label="Cari Data"
                            class="inline-flex items-center justify-center gap-1.5 rounded-xl bg-[#0b602b] hover:bg-[#084d22] px-4 py-2.5 text-sm font-semibold text-white transition shadow-sm cursor-pointer whitespace-nowrap">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            <span>Cari</span>
                        </button>

                        @if(request('search') || request('bulan') || request('status'))
                            <a
                                href="{{ route('laporan-mingguan.index') }}"
                                title="Reset Pencarian & Filter"
                                aria-label="Reset Pencarian dan Filter"
                                class="inline-flex items-center justify-center p-2.5 rounded-xl border border-gray-300 bg-gray-50 hover:bg-gray-100 text-gray-600 transition shadow-sm shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            <div class="overflow-x-auto rounded-xl border border-gray-200">
                <table class="w-full text-sm text-left border-collapse">
                    <thead class="bg-[#0b602b] text-white uppercase">
                        <tr>
                            @if (auth()->user()->role === 'user')
                                <th scope="col" class="px-4 py-3.5 text-xs font-semibold uppercase tracking-wider text-white text-center w-12 whitespace-nowrap">No</th>
                                <th scope="col" class="px-5 py-3.5 text-xs font-semibold uppercase tracking-wider text-white text-center whitespace-nowrap">Bulan / Tahun</th>
                                <th scope="col" class="px-5 py-3.5 text-xs font-semibold uppercase tracking-wider text-white text-center whitespace-nowrap">STATUS</th>
                                <th scope="col" class="px-5 py-3.5 text-xs font-semibold uppercase tracking-wider text-white text-center">Catatan Atasan & Nilai</th>
                                <th scope="col" class="px-5 py-3.5 text-xs font-semibold uppercase tracking-wider text-white text-center whitespace-nowrap">Lihat File</th>
                                <th scope="col" class="px-5 py-3.5 text-xs font-semibold uppercase tracking-wider text-white text-center whitespace-nowrap">Unduh File</th>
                                <th scope="col" class="px-5 py-3.5 text-xs font-semibold uppercase tracking-wider text-white text-center whitespace-nowrap">Aksi</th>
                            @else
                                <th scope="col" class="px-5 py-3.5 text-xs font-semibold uppercase tracking-wider text-white text-left whitespace-nowrap">Nama Pegawai</th>
                                <th scope="col" class="px-5 py-3.5 text-xs font-semibold uppercase tracking-wider text-white text-center whitespace-nowrap">Bulan / Tahun</th>
                                <th scope="col" class="px-5 py-3.5 text-xs font-semibold uppercase tracking-wider text-white text-center whitespace-nowrap">STATUS</th>
                                <th scope="col" class="px-5 py-3.5 text-xs font-semibold uppercase tracking-wider text-white text-center">Catatan Atasan</th>
                                <th scope="col" class="px-5 py-3.5 text-xs font-semibold uppercase tracking-wider text-white text-center whitespace-nowrap">Lihat File</th>
                                <th scope="col" class="px-5 py-3.5 text-xs font-semibold uppercase tracking-wider text-white text-center whitespace-nowrap">Unduh File</th>
                                <th scope="col" class="px-5 py-3.5 text-xs font-semibold uppercase tracking-wider text-white text-center whitespace-nowrap">Aksi</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-gray-700">
                        @forelse ($laporan as $item)
                            <tr class="hover:bg-gray-50/75 transition duration-150">
                                @if (auth()->user()->role === 'user')
                                    <!-- No -->
                                    <td class="px-4 py-4 text-center font-medium text-gray-500 whitespace-nowrap">
                                        {{ $laporan->firstItem() + $loop->index }}
                                    </td>
                                @else
                                    <!-- Nama Pegawai -->
                                    <td class="px-5 py-4 font-semibold text-gray-900 whitespace-nowrap">
                                        {{ $item->pegawai->nama ?? '-' }}
                                        @if(!empty($item->pegawai->divisi->nama_divisi))
                                            <span class="block text-xs font-normal text-gray-500">{{ $item->pegawai->divisi->nama_divisi }}</span>
                                        @endif
                                    </td>
                                @endif

                                <!-- Bulan / Tahun -->
                                <td class="px-5 py-4 text-gray-600 whitespace-nowrap text-center font-medium">
                                    {{ \Carbon\Carbon::create()->month($item->bulan)->translatedFormat('F') }} {{ $item->tahun }}
                                </td>

                                <!-- Status -->
                                <td class="px-5 py-4 text-center whitespace-nowrap">
                                    @if($item->status === 'menunggu')
                                        <span class="inline-flex items-center whitespace-nowrap bg-red-50 text-red-600 border border-red-200 px-2.5 py-1 rounded-full text-[10px] font-extrabold uppercase tracking-wider">
                                            BELUM DIREVIEW
                                        </span>
                                    @else
                                        <span class="inline-flex items-center whitespace-nowrap bg-green-50 text-green-700 border border-green-200 px-2.5 py-1 rounded-full text-[10px] font-extrabold uppercase tracking-wider">
                                            SUDAH DIREVIEW
                                        </span>
                                    @endif
                                </td>

                                <!-- Catatan Atasan & Nilai -->
                                <td class="px-5 py-4 text-center">
                                    @if ($item->nilai)
                                        <div class="mb-1">
                                            <span class="inline-block px-2.5 py-0.5 text-xs font-semibold rounded-full border
                                                @if($item->nilai === 'diatas_ekspektasi') bg-indigo-50 text-indigo-700 border-indigo-200
                                                @elseif($item->nilai === 'sesuai_ekspektasi') bg-blue-50 text-blue-700 border-blue-200
                                                @elseif($item->nilai === 'dibawah_ekspektasi') bg-amber-50 text-amber-700 border-amber-200
                                                @endif">
                                                {{ $item->nilai === 'diatas_ekspektasi' ? 'Di Atas Ekspektasi' : ($item->nilai === 'sesuai_ekspektasi' ? 'Sesuai Ekspektasi' : ($item->nilai === 'dibawah_ekspektasi' ? 'Di Bawah Ekspektasi' : ucwords(str_replace('_', ' ', $item->nilai)))) }}
                                            </span>
                                        </div>
                                    @endif
                                    <p class="text-xs text-gray-500 line-clamp-1" title="{{ $item->catatan_atasan }}">{{ $item->catatan_atasan ?? '-' }}</p>
                                </td>

                                <!-- Lihat File -->
                                <td class="px-5 py-4 text-center whitespace-nowrap">
                                    @if ($item->file_laporan)
                                        <a href="{{ route('laporan-mingguan.preview', $item->id) }}" target="_blank" class="p-2 rounded-lg text-[#0b602b] hover:bg-emerald-50 transition inline-block" title="Lihat Dokumen Laporan" aria-label="Lihat Dokumen Laporan {{ $item->pegawai->nama ?? 'Pegawai' }}">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
                                            </svg>
                                        </a>
                                    @else
                                        <span class="text-gray-500">-</span>
                                    @endif
                                </td>

                                <!-- Unduh File -->
                                <td class="px-5 py-4 text-center whitespace-nowrap">
                                    <a href="{{ route('laporan-mingguan.download', $item->id) }}" class="p-2 rounded-lg text-[#BA1A1A] hover:bg-red-50 transition inline-block" title="Download PDF" aria-label="Download PDF Laporan {{ $item->pegawai->nama ?? 'Pegawai' }}">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                    </a>
                                </td>

                                <!-- Aksi / Detail -->
                                <td class="px-5 py-4 text-center whitespace-nowrap">
                                    <div class="inline-flex items-center justify-center gap-1">
                                        @if(auth()->user()->role === 'admin' || auth()->user()->role === 'atasan')
                                            @if($item->nilai || $item->status !== 'menunggu')
                                                <a href="{{ route('laporan-mingguan.show', $item->id) }}" class="text-[#0e622b] hover:text-[#0b4d22] p-1.5 rounded-lg hover:bg-emerald-50 transition inline-flex items-center justify-center" title="Detail Rincian Laporan" aria-label="Detail Rincian Laporan {{ $item->pegawai->nama ?? 'Pegawai' }}">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                    </svg>
                                                </a>
                                            @else
                                                <a href="{{ route('laporan-mingguan.nilai-form', $item->id) }}" 
                                                   class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg font-semibold text-xs text-white bg-[#0b602b] hover:bg-[#084d22] transition shadow-xs"
                                                   title="Beri Penilaian Laporan">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                                    <span>Nilai</span>
                                                </a>
                                            @endif
                                        @else
                                            <a href="{{ route('laporan-mingguan.show', $item->id) }}" class="text-[#0e622b] hover:text-[#0b4d22] p-1.5 rounded-lg hover:bg-emerald-50 transition inline-flex items-center justify-center" title="Detail Rincian Laporan" aria-label="Detail Rincian Laporan {{ $item->pegawai->nama ?? 'Pegawai' }}">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                </svg>
                                            </a>

                                            @if(auth()->user()->role === 'user' && ($item->nilai === 'dibawah_ekspektasi' || $item->status === 'ditolak'))
                                                <a href="{{ route('laporan-mingguan.edit', $item->id) }}" class="p-2 rounded-lg text-amber-600 hover:bg-amber-50 transition inline-block" title="Perbaiki Laporan" aria-label="Perbaiki Laporan">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                    </svg>
                                                </a>
                                            @endif
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ auth()->user()->role === 'user' ? 7 : 7 }}" class="px-5 py-12 text-center text-gray-500">Belum ada data laporan kinerja ditemukan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($laporan->hasPages())
                <div class="px-5 py-4 border-t border-gray-100 bg-white">{{ $laporan->links() }}</div>
            @endif
        </div>
    @endif

    <!-- Modal Download Bulanan (Triwulan & Tahunan) -->
    <div id="modalDownloadBulanan" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true" x-data="{ rekapType: 'triwulan' }">
        <div class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <!-- Background overlay -->
            <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" aria-hidden="true" onclick="document.getElementById('modalDownloadBulanan').classList.add('hidden')"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <!-- Modal panel -->
            <div class="inline-block px-4 pt-5 pb-4 overflow-hidden text-left align-bottom transition-all transform bg-white rounded-2xl shadow-xl sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
                <div class="sm:flex sm:items-start">
                    <div class="flex items-center justify-center flex-shrink-0 w-12 h-12 mx-auto bg-red-100 rounded-2xl sm:mx-0 sm:h-11 sm:w-11">
                        <svg class="w-6 h-6 text-[#BA1A1A]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                        <h3 class="text-lg font-bold leading-6 text-gray-900" id="modal-title">
                            Download Rekap Laporan Bulanan
                        </h3>
                        <p class="text-xs text-gray-500 mt-1">
                            Pilih tipe rekapitulasi (Triwulan atau Tahunan) untuk mengunduh gabungan laporan kinerja bulanan.
                        </p>

                        <!-- TAB SELECTOR -->
                        <div class="flex p-1 mt-4 bg-gray-100 rounded-xl">
                            <button type="button" @click="rekapType = 'triwulan'" class="w-1/2 py-2 text-xs sm:text-sm font-bold rounded-lg transition" :class="rekapType === 'triwulan' ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-600 hover:text-gray-900'">
                                Rekap Triwulan (3 Bulan)
                            </button>
                            <button type="button" @click="rekapType = 'tahunan'" class="w-1/2 py-2 text-xs sm:text-sm font-bold rounded-lg transition" :class="rekapType === 'tahunan' ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-600 hover:text-gray-900'">
                                Rekap Tahunan (1 Tahun)
                            </button>
                        </div>

                        <!-- FORM REKAP TRIWULAN -->
                        <div x-show="rekapType === 'triwulan'">
                            <div class="mt-3 p-3 bg-gray-50 rounded-xl border border-gray-200 text-xs text-gray-500 space-y-1">
                                <p><span class="font-semibold text-gray-700">Triwulan 1</span> — Januari, Februari, Maret</p>
                                <p><span class="font-semibold text-gray-700">Triwulan 2</span> — April, Mei, Juni</p>
                                <p><span class="font-semibold text-gray-700">Triwulan 3</span> — Juli, Agustus, September</p>
                                <p><span class="font-semibold text-gray-700">Triwulan 4</span> — Oktober, November, Desember</p>
                            </div>

                            <form action="{{ route('laporan-mingguan.download-triwulan') }}" method="GET" class="mt-4 space-y-4">
                                @if(auth()->user()->role === 'admin' || auth()->user()->role === 'atasan')
                                    <div>
                                        <label for="pegawai_id_triwulan" class="block text-xs font-medium text-gray-700 mb-1">Pilih Pegawai</label>
                                        <select id="pegawai_id_triwulan" name="pegawai_id" required class="block w-full border-gray-300 rounded-xl shadow-xs focus:ring-green-500 focus:border-green-500 text-xs sm:text-sm py-2 px-3">
                                            <option value="">-- Pilih Pegawai --</option>
                                            @foreach($pegawaiList ?? [] as $p)
                                                <option value="{{ $p->id }}">{{ $p->nama }} {{ !empty($p->divisi->nama_divisi) ? '('.$p->divisi->nama_divisi.')' : '' }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                @endif

                                <div>
                                    <label for="kuartal" class="block text-xs font-medium text-gray-700 mb-1">Triwulan</label>
                                    <select id="kuartal" name="kuartal" class="block w-full border-gray-300 rounded-xl shadow-xs focus:ring-green-500 focus:border-green-500 text-xs sm:text-sm py-2 px-3">
                                        @php
                                             $bulanSekarang = date('n');
                                             $kuartalSekarang = ceil($bulanSekarang / 3);
                                        @endphp
                                        <option value="1" {{ $kuartalSekarang == 1 ? 'selected' : '' }}>Triwulan 1 (Jan – Mar)</option>
                                        <option value="2" {{ $kuartalSekarang == 2 ? 'selected' : '' }}>Triwulan 2 (Apr – Jun)</option>
                                        <option value="3" {{ $kuartalSekarang == 3 ? 'selected' : '' }}>Triwulan 3 (Jul – Sep)</option>
                                        <option value="4" {{ $kuartalSekarang == 4 ? 'selected' : '' }}>Triwulan 4 (Okt – Des)</option>
                                    </select>
                                </div>

                                <div>
                                    <label for="tahun_triwulan" class="block text-xs font-medium text-gray-700 mb-1">Tahun</label>
                                    <select id="tahun_triwulan" name="tahun" class="block w-full border-gray-300 rounded-xl shadow-xs focus:ring-green-500 focus:border-green-500 text-xs sm:text-sm py-2 px-3">
                                        @foreach(range(date('Y') - 2, date('Y')) as $y)
                                            <option value="{{ $y }}" {{ date('Y') == $y ? 'selected' : '' }}>{{ $y }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mt-5 sm:mt-6 sm:flex sm:flex-row-reverse gap-3">
                                    <button type="submit" class="inline-flex justify-center items-center gap-1.5 w-full sm:w-auto px-5 py-2.5 text-xs sm:text-sm font-bold text-white bg-[#BA1A1A] hover:bg-[#961313] rounded-xl shadow-sm transition cursor-pointer">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                        <span>Download PDF Triwulan</span>
                                    </button>
                                    <button type="button" onclick="document.getElementById('modalDownloadBulanan').classList.add('hidden')" class="inline-flex justify-center w-full sm:w-auto px-5 py-2.5 mt-2 sm:mt-0 text-xs sm:text-sm font-semibold text-gray-700 bg-white border border-gray-300 rounded-xl shadow-xs hover:bg-gray-50 transition cursor-pointer">
                                        Batal
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- FORM REKAP TAHUNAN -->
                        <div x-show="rekapType === 'tahunan'" style="display: none;">
                            <div class="mt-3 p-3 bg-emerald-50 rounded-xl border border-emerald-200 text-xs text-emerald-800 space-y-1">
                                <p class="font-medium">
                                    <span class="font-bold">Rekap Tahunan</span> menggabungkan seluruh laporan kinerja bulanan (Januari s.d. Desember) dalam 1 tahun penuh ke dalam satu dokumen PDF resmi lengkap dengan halaman sampul dan lampiran dokumentasi.
                                </p>
                            </div>

                            <form action="{{ route('laporan-mingguan.download-tahunan') }}" method="GET" class="mt-4 space-y-4">
                                @if(auth()->user()->role === 'admin' || auth()->user()->role === 'atasan')
                                    <div>
                                        <label for="pegawai_id_tahunan" class="block text-xs font-medium text-gray-700 mb-1">Pilih Pegawai</label>
                                        <select id="pegawai_id_tahunan" name="pegawai_id" required class="block w-full border-gray-300 rounded-xl shadow-xs focus:ring-green-500 focus:border-green-500 text-xs sm:text-sm py-2 px-3">
                                            <option value="">-- Pilih Pegawai --</option>
                                            @foreach($pegawaiList ?? [] as $p)
                                                <option value="{{ $p->id }}">{{ $p->nama }} {{ !empty($p->divisi->nama_divisi) ? '('.$p->divisi->nama_divisi.')' : '' }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                @endif

                                <div>
                                    <label for="tahun_tahunan" class="block text-xs font-medium text-gray-700 mb-1">Tahun Laporan</label>
                                    <select id="tahun_tahunan" name="tahun" class="block w-full border-gray-300 rounded-xl shadow-xs focus:ring-green-500 focus:border-green-500 text-xs sm:text-sm py-2 px-3">
                                        @foreach(range(date('Y') - 2, date('Y')) as $y)
                                            <option value="{{ $y }}" {{ date('Y') == $y ? 'selected' : '' }}>{{ $y }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mt-5 sm:mt-6 sm:flex sm:flex-row-reverse gap-3">
                                    <button type="submit" class="inline-flex justify-center items-center gap-1.5 w-full sm:w-auto px-5 py-2.5 text-xs sm:text-sm font-bold text-white bg-[#0e622b] hover:bg-[#0b4d22] rounded-xl shadow-sm transition cursor-pointer">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                        <span>Download PDF Rekap Tahunan</span>
                                    </button>
                                    <button type="button" onclick="document.getElementById('modalDownloadBulanan').classList.add('hidden')" class="inline-flex justify-center w-full sm:w-auto px-5 py-2.5 mt-2 sm:mt-0 text-xs sm:text-sm font-semibold text-gray-700 bg-white border border-gray-300 rounded-xl shadow-xs hover:bg-gray-50 transition cursor-pointer">
                                        Batal
                                    </button>
                                </div>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function openDownloadModal(type) {
            const modal = document.getElementById('modalDownloadBulanan');
            if (modal) {
                modal.classList.remove('hidden');
                // Set Alpine.js rekapType data if available
                if (window.Alpine) {
                    const alpineData = Alpine.$data(modal);
                    if (alpineData) {
                        alpineData.rekapType = type || 'triwulan';
                    }
                }
            }
        }
    </script>

    {{-- Modal Toggle Upload Laporan --}}
    @if(auth()->user()->role === 'admin')
    <div id="modalToggleUpload" class="fixed inset-0 z-50 overflow-y-auto bg-black/50 backdrop-blur-sm flex items-center justify-center p-4 hidden" onclick="handleToggleUploadBackdrop(event)">
        <div class="bg-white rounded-2xl shadow-xl border border-gray-200 w-full max-w-md overflow-hidden transform transition-all" onclick="event.stopPropagation()">
            <!-- Header -->
            <div class="px-6 py-4 bg-gray-50 border-b border-gray-200 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div id="toggleModalIcon" class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0"></div>
                    <div>
                        <h3 id="toggleModalTitle" class="text-base font-bold text-gray-900"></h3>
                        <p id="toggleModalSubtitle" class="text-xs text-gray-500"></p>
                    </div>
                </div>
                <button type="button" onclick="closeToggleUploadModal()" class="w-8 h-8 rounded-lg flex items-center justify-center text-gray-400 hover:text-gray-600 hover:bg-gray-100 transition" aria-label="Tutup dialog">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <!-- Body -->
            <div class="p-6">
                <div id="toggleModalAlert" class="p-3 rounded-xl text-xs leading-relaxed mb-0"></div>
            </div>

            <!-- Footer -->
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex items-center justify-end gap-3">
                <button type="button" onclick="closeToggleUploadModal()" class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-semibold rounded-xl transition cursor-pointer">
                    Batal
                </button>
                <button type="button" id="toggleModalConfirmBtn" onclick="submitToggleUpload()" class="px-5 py-2.5 text-white text-sm font-semibold rounded-xl shadow-xs transition cursor-pointer">
                </button>
            </div>
        </div>
    </div>

    <script>
        function openToggleUploadModal(action) {
            const modal = document.getElementById('modalToggleUpload');
            const icon = document.getElementById('toggleModalIcon');
            const title = document.getElementById('toggleModalTitle');
            const subtitle = document.getElementById('toggleModalSubtitle');
            const alert = document.getElementById('toggleModalAlert');
            const confirmBtn = document.getElementById('toggleModalConfirmBtn');

            if (action === 'close') {
                icon.style.background = '#fef2f2';
                icon.style.color = '#BA1A1A';
                icon.innerHTML = `<svg style="width:20px;height:20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>`;
                title.textContent = 'Tutup Pengiriman Laporan';
                subtitle.textContent = 'Pegawai tidak dapat mengunggah laporan';
                alert.style.background = '#fef2f2';
                alert.style.border = '1px solid #fecaca';
                alert.style.color = '#991b1b';
                alert.textContent = 'Setelah ditutup, seluruh pegawai tidak akan dapat mengirimkan laporan bulanan baru. Anda dapat membukanya kembali kapan saja.';
                confirmBtn.textContent = 'Ya, Tutup Upload';
                confirmBtn.style.background = '#BA1A1A';
                confirmBtn.onmouseover = function() { this.style.background = '#961313'; };
                confirmBtn.onmouseout = function() { this.style.background = '#BA1A1A'; };
            } else {
                icon.style.background = '#e6f4ea';
                icon.style.color = '#0b602b';
                icon.innerHTML = `<svg style="width:20px;height:20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z"/></svg>`;
                title.textContent = 'Buka Pengiriman Laporan';
                subtitle.textContent = 'Pegawai dapat kembali mengunggah laporan';
                alert.style.background = '#e6f4ea';
                alert.style.border = '1px solid #b7dfbc';
                alert.style.color = '#0b602b';
                alert.textContent = 'Setelah dibuka, seluruh pegawai dapat kembali mengirimkan laporan bulanan mereka.';
                confirmBtn.textContent = 'Ya, Buka Upload';
                confirmBtn.style.background = '#0b602b';
                confirmBtn.onmouseover = function() { this.style.background = '#084d22'; };
                confirmBtn.onmouseout = function() { this.style.background = '#0b602b'; };
            }

            modal.classList.remove('hidden');
        }

        function closeToggleUploadModal() {
            document.getElementById('modalToggleUpload').classList.add('hidden');
        }

        function handleToggleUploadBackdrop(e) {
            if (e.target.id === 'modalToggleUpload') closeToggleUploadModal();
        }

        function submitToggleUpload() {
            document.getElementById('formToggleUpload').submit();
        }

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') closeToggleUploadModal();
        });
    </script>
    @endif

</x-dashboard-layout>
