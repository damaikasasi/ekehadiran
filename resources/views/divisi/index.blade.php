<x-dashboard-layout :title="'Data Divisi'">

    <style>
        .dv-table th { font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: .04em; color: #ffffff; background: #0b602b; padding: 14px 20px; text-align: center; }
        .dv-table td { padding: 14px 20px; font-size: 14px; border-bottom: 1px solid #f1f2f4; vertical-align: middle; text-align: center; }
        .dv-table tbody tr:last-child td { border-bottom: none; }
        .dv-table tbody tr:hover { background: #fafbfc; }
        .dv-action-icon { width: 20px; height: 20px; }
        .dv-badge-count { display: inline-flex; align-items: center; gap: 4px; font-size: 12px; font-weight: 600; padding: 4px 12px; border-radius: 9999px; background: #dcfce7; color: #15803d; }
    </style>

    {{-- Header Section --}}
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-900 tracking-tight">Daftar Divisi</h2>
        <p class="mt-1 text-sm text-gray-500">Kelola dan organisasikan divisi instansi.</p>
    </div>

    @if (session('success'))
        @php
            $isDelete = str_contains(strtolower(session('success')), 'hapus');
        @endphp
        <div class="mb-6 p-4 {{ $isDelete ? 'bg-red-50 border-red-200 text-red-800' : 'bg-emerald-50 border-emerald-200 text-emerald-800' }} border rounded-2xl text-sm flex items-center justify-between gap-3 shadow-xs transition-all">
            <div class="flex items-center gap-2.5">
                @if($isDelete)
                    <svg class="w-5 h-5 text-red-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                @else
                    <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                @endif
                <span>{{ session('success') }}</span>
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="{{ $isDelete ? 'text-red-500 hover:text-red-700 hover:bg-red-100' : 'text-emerald-500 hover:text-emerald-700 hover:bg-emerald-100' }} p-1 rounded-lg transition cursor-pointer" title="Tutup">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const showPopup = () => {
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: '{{ $isDelete ? "error" : "success" }}',
                            iconColor: '{{ $isDelete ? "#BA1A1A" : "#0b602b" }}',
                            title: '{{ $isDelete ? "Berhasil Dihapus!" : "Berhasil!" }}',
                            text: {!! json_encode(session('success')) !!},
                            showCloseButton: true,
                            confirmButtonColor: '{{ $isDelete ? "#BA1A1A" : "#0b602b" }}',
                            confirmButtonText: 'Selesai',
                            customClass: {
                                popup: 'rounded-2xl shadow-xl',
                                confirmButton: 'rounded-full px-6 py-2.5 font-bold text-sm cursor-pointer',
                                closeButton: 'focus:outline-none'
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
        <!-- Search & Action Bar -->
        <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 mb-6">
            <form method="GET" action="{{ route('divisi.index') }}" class="flex items-end gap-2.5 w-full sm:w-auto">
                <div class="flex-1 sm:w-80">
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5 whitespace-nowrap">Cari Divisi</label>
                    <div class="relative">
                        <svg class="w-4 h-4 text-gray-400 absolute left-3.5 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11a6 6 0 11-12 0 6 6 0 0112 0z"/></svg>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama divisi..."
                            class="w-full pl-10 pr-4 py-2.5 text-sm rounded-xl border border-gray-300 focus:ring-2 focus:ring-[#0b602b]/20 focus:border-[#0b602b] transition shadow-sm text-gray-700">
                    </div>
                </div>

                <button type="submit" class="inline-flex items-center justify-center gap-1.5 rounded-xl bg-[#0b602b] hover:bg-[#084920] px-4 py-2.5 text-sm font-semibold text-white transition shadow-sm cursor-pointer whitespace-nowrap">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <span>Cari</span>
                </button>

                @if(request('search'))
                    <a href="{{ route('divisi.index') }}" title="Reset" class="p-2.5 bg-gray-50 border border-gray-300 hover:bg-gray-100 text-gray-600 rounded-xl transition shadow-sm shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </a>
                @endif
            </form>

            <div class="w-full sm:w-auto shrink-0 flex justify-end">
                <a href="{{ route('divisi.create') }}" class="w-full sm:w-auto text-center inline-flex items-center justify-center gap-2 rounded-full bg-[#0b602b] hover:bg-[#084d22] px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:shadow transition whitespace-nowrap">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                    <span>Tambah Divisi</span>
                </a>
            </div>
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto rounded-xl border border-gray-200">
            <table class="w-full dv-table min-w-[500px]">
                <thead>
                    <tr>
                        <th style="width: 60px;">No</th>
                        <th class="text-center">Nama Divisi</th>
                        <th>Jumlah Pegawai</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($divisi as $item)
                        <tr>
                            <td class="text-gray-500">{{ $loop->iteration + ($divisi->currentPage() - 1) * $divisi->perPage() }}</td>
                            <td class="text-left">
                                <span class="text-gray-800 font-medium">{{ $item->nama_divisi }}</span>
                            </td>
                            <td>
                                <span class="dv-badge-count">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                    {{ $item->pegawai_count ?? 0 }} Pegawai
                                </span>
                            </td>
                            <td>
                                <div class="flex items-center justify-center gap-1">
                                    <a href="{{ route('divisi.edit', $item->id) }}" class="p-2 rounded-lg text-emerald-600 hover:bg-emerald-100 transition" title="Edit">
                                        <svg class="dv-action-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </a>
                                    <form action="{{ route('divisi.destroy', $item->id) }}" method="POST" class="confirm-form" 
                                          data-title="Hapus Divisi" 
                                          data-text="Apakah Anda yakin ingin menghapus divisi ini?"
                                          data-icon="warning"
                                          data-icon-color="#BA1A1A"
                                          data-confirm-color="#BA1A1A"
                                          data-confirm-text="Ya, Hapus">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 rounded-lg text-red-600 hover:bg-red-100 transition cursor-pointer" title="Hapus">
                                            <svg class="dv-action-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-12 text-gray-400">
                                <div class="flex flex-col items-center gap-2">
                                    <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                                    <span>Belum ada data divisi.</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($divisi->hasPages())
            <div class="flex flex-col sm:flex-row items-center justify-between gap-3 px-2 sm:px-4 py-4 border-t border-gray-100 mt-4">
                <p class="text-xs sm:text-sm text-gray-500 order-2 sm:order-1 text-center sm:text-left">
                    Menampilkan <span class="font-semibold text-gray-700">{{ $divisi->firstItem() ?? 0 }}</span> - <span class="font-semibold text-gray-700">{{ $divisi->lastItem() ?? 0 }}</span> dari <span class="font-semibold text-gray-700">{{ $divisi->total() }}</span> divisi
                </p>
                <div class="order-1 sm:order-2 w-full sm:w-auto flex justify-center sm:justify-end">
                    {{ $divisi->appends(request()->query())->links() }}
                </div>
            </div>
        @endif
    </div>

</x-dashboard-layout>