<x-dashboard-layout :title="'Data Pegawai'">

    <style>
        .pg-badge { display: inline-block; font-size: 11px; font-weight: 700; padding: 4px 12px; border-radius: 9999px; text-transform: uppercase; letter-spacing: .02em; }
        .pg-badge-staf { background: #f3f4f6; color: #374151; }
        .pg-badge-atasan { background: #fce7f3; color: #9d174d; }
        .pg-badge-admin { background: #dbeafe; color: #1e40af; }
        .pg-badge-aktif { background: #dcfce7; color: #166534; }
        .pg-badge-tidak-aktif { background: #f3f4f6; color: #374151; }
        .pg-dot { width: 6px; height: 6px; border-radius: 9999px; display: inline-block; margin-right: 5px; }
        .pg-table th { font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: .04em; color: #ffffff; background: #0b602b; padding: 14px 20px; text-align: center; }
        .pg-table td { padding: 14px 20px; font-size: 14px; border-bottom: 1px solid #f1f2f4; vertical-align: middle; text-align: center; }
        .pg-table tbody tr:last-child td { border-bottom: none; }
        .pg-table tbody tr:hover { background: #fafbfc; }
        .pg-avatar { width: 40px; height: 40px; border-radius: 9999px; object-fit: cover; background: #e5e7eb; }
        .pg-action-icon { width: 20px; height: 20px; }
    </style>

    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-900 tracking-tight">Manajemen Pegawai</h2>
        <p class="mt-1 text-sm text-gray-600">Kelola dan pantau catatan data personil organisasi.</p>
    </div>

    @if (session('success'))
        <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl text-sm">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-4 sm:p-6">
        <!-- Filter & Action Bar -->
        <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-4 mb-6">
            <form method="GET" action="{{ route('pegawai.index') }}" class="flex flex-wrap items-end gap-3 w-full lg:w-auto">
                <!-- 1. Cari Karyawan -->
                <div class="w-full sm:w-56 md:w-64">
                    <label for="search-pegawai" class="block text-xs font-semibold text-gray-700 mb-1.5 whitespace-nowrap">Cari Karyawan</label>
                    <div class="relative">
                        <svg class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11a6 6 0 11-12 0 6 6 0 0112 0z"/></svg>
                        <input id="search-pegawai" type="text" name="search" value="{{ request('search') }}" placeholder="Nama atau NIP..."
                            class="w-full pl-9 pr-3 py-2.5 text-sm rounded-xl border border-gray-300 focus:ring-2 focus:ring-[#0b602b]/20 focus:border-[#0b602b] transition shadow-sm text-gray-700">
                    </div>
                </div>

                @if(auth()->user()->role !== 'atasan')
                    <!-- 2. Divisi -->
                    <div class="w-full sm:w-44 md:w-48">
                        <label for="filter-divisi" class="block text-xs font-semibold text-gray-700 mb-1.5 whitespace-nowrap">Divisi</label>
                        <select id="filter-divisi" name="divisi_id" class="w-full pl-3.5 pr-8 py-2.5 text-sm rounded-xl border border-gray-300 bg-white focus:ring-2 focus:ring-[#0b602b]/20 focus:border-[#0b602b] transition shadow-sm text-gray-700">
                            <option value="">Semua Divisi</option>
                            @foreach ($divisiList ?? [] as $divisi)
                                <option value="{{ $divisi->id }}" @selected(request('divisi_id') == $divisi->id)>{{ $divisi->nama_divisi }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif

                <!-- 3. Peran -->
                <div class="w-full sm:w-36 md:w-40">
                    <label for="filter-role" class="block text-xs font-semibold text-gray-700 mb-1.5 whitespace-nowrap">Peran</label>
                    <select id="filter-role" name="role" class="w-full pl-3.5 pr-8 py-2.5 text-sm rounded-xl border border-gray-300 bg-white focus:ring-2 focus:ring-[#0b602b]/20 focus:border-[#0b602b] transition shadow-sm text-gray-700">
                        <option value="">Semua Peran</option>
                        <option value="user" @selected(request('role') === 'user')>Staf</option>
                        <option value="atasan" @selected(request('role') === 'atasan')>Atasan</option>
                        <option value="admin" @selected(request('role') === 'admin')>Admin</option>
                    </select>
                </div>

                <!-- 4. Buttons (Terapkan Filter) -->
                <div class="flex items-center gap-2 w-full sm:w-auto mt-2 sm:mt-0">
                    <button type="submit" class="flex-1 sm:flex-none inline-flex items-center justify-center gap-1.5 rounded-xl bg-[#0b602b] hover:bg-[#084920] px-4 py-2.5 text-sm font-semibold text-white transition shadow-sm cursor-pointer whitespace-nowrap">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4h18M6 8h12M10 12h4"/></svg>
                        <span>Terapkan Filter</span>
                    </button>

                    @if(request()->anyFilled(['search', 'divisi_id', 'role']))
                        <a href="{{ route('pegawai.index') }}" aria-label="Reset Filter Pencarian" title="Reset" class="p-2.5 bg-gray-50 border border-gray-300 hover:bg-gray-100 text-gray-600 rounded-xl transition shadow-sm shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </a>
                    @endif
                </div>
            </form>

            @if(auth()->user()->role === 'admin')
                <div class="w-full lg:w-auto shrink-0 flex justify-end">
                    <a href="{{ route('pegawai.create') }}" class="w-full sm:w-auto text-center inline-flex items-center justify-center gap-2 rounded-full bg-[#0b602b] hover:bg-[#084d22] px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:shadow transition whitespace-nowrap">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                        <span>Tambah Pegawai</span>
                    </a>
                </div>
            @endif
        </div>

        <!-- Table -->
        <div class="overflow-x-auto rounded-xl border border-gray-200">
            <table class="w-full pg-table min-w-[750px]">
                <thead>
                    <tr>
                        <th scope="col" class="whitespace-nowrap">Profile</th>
                        <th scope="col" class="whitespace-nowrap">NIP</th>
                        <th scope="col" class="whitespace-nowrap">Nama Lengkap</th>
                        <th scope="col" class="whitespace-nowrap">Divisi</th>
                        <th scope="col" class="whitespace-nowrap">Jabatan</th>
                        <th scope="col" class="whitespace-nowrap">Peran</th>
                        <th scope="col" class="whitespace-nowrap">Status</th>
                        @if(auth()->user()->role === 'admin')
                            <th scope="col" class="whitespace-nowrap">Aksi</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @forelse ($pegawai as $item)
                        <tr>
                            <td>
                                @if ($item->foto && Storage::disk('public')->exists($item->foto))
                                    <img src="{{ asset('storage/'.$item->foto) }}" width="40" height="40" loading="lazy" decoding="async" class="pg-avatar inline-block" alt="{{ $item->nama }}">
                                @else
                                    <div class="pg-avatar inline-flex items-center justify-center text-xs font-semibold text-gray-700 bg-gray-200">
                                        {{ substr($item->nama, 0, 2) }}
                                    </div>
                                @endif
                            </td>
                            <td class="text-gray-700 font-mono text-xs sm:text-sm whitespace-nowrap">{{ $item->user->nip ?? '-' }}</td>
                            <td class="whitespace-nowrap">
                                <span class="text-green-800 font-semibold">{{ $item->nama }}</span>
                            </td>
                            <td class="text-gray-700 whitespace-nowrap">{{ $item->divisi->nama_divisi ?? '-' }}</td>
                            <td class="text-gray-700 whitespace-nowrap">{{ $item->jabatan ?? '-' }}</td>
                            <td class="whitespace-nowrap">
                                @php
                                    $roleLabel = match($item->user->role ?? null) {
                                        'admin' => 'ADMIN',
                                        'atasan' => 'ATASAN',
                                        default => 'STAF',
                                    };
                                    $roleClass = match($item->user->role ?? null) {
                                        'admin' => 'pg-badge-admin',
                                        'atasan' => 'pg-badge-atasan',
                                        default => 'pg-badge-staf',
                                    };
                                @endphp
                                <span class="pg-badge {{ $roleClass }}">{{ $roleLabel }}</span>
                            </td>
                            <td class="whitespace-nowrap">
                                @if ($item->status_aktif === 'aktif')
                                    <span class="pg-badge pg-badge-aktif">Aktif</span>
                                @else
                                    <span class="pg-badge pg-badge-tidak-aktif">Tidak Aktif</span>
                                @endif
                            </td>
                            @if(auth()->user()->role === 'admin')
                                <td class="whitespace-nowrap">
                                    <div class="flex items-center justify-center gap-1">
                                        <a href="{{ route('pegawai.show', $item->id) }}" aria-label="Lihat Detail {{ $item->nama }}" class="p-2 rounded-lg text-emerald-700 hover:bg-emerald-50 transition" title="Lihat">
                                            <svg class="pg-action-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        </a>
                                        <a href="{{ route('pegawai.edit', $item->id) }}" aria-label="Edit Pegawai {{ $item->nama }}" class="p-2 rounded-lg text-emerald-700 hover:bg-emerald-100 transition" title="Edit">
                                            <svg class="pg-action-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        </a>
                                        <form action="{{ route('pegawai.destroy', $item->id) }}" method="POST" class="confirm-form" 
                                              data-title="Hapus Pegawai" 
                                              data-text="Apakah Anda yakin ingin menghapus pegawai ini?"
                                              data-icon="warning"
                                              data-icon-color="#BA1A1A"
                                              data-confirm-color="#BA1A1A"
                                              data-confirm-text="Ya, Hapus">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" aria-label="Hapus Pegawai {{ $item->nama }}" class="p-2 rounded-lg text-red-700 hover:bg-red-100 transition cursor-pointer" title="Hapus">
                                                <svg class="pg-action-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            @endif
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ auth()->user()->role === 'admin' ? 8 : 7 }}" class="text-center py-12 text-gray-500">Belum ada data pegawai.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($pegawai->hasPages())
            <div class="flex flex-col sm:flex-row items-center justify-between gap-3 px-2 sm:px-4 py-4 border-t border-gray-100 mt-4">
                <p class="text-xs sm:text-sm text-gray-600 order-2 sm:order-1 text-center sm:text-left">
                    Menampilkan <span class="font-semibold text-gray-700">{{ $pegawai->firstItem() ?? 0 }}</span> - <span class="font-semibold text-gray-700">{{ $pegawai->lastItem() ?? 0 }}</span> dari <span class="font-semibold text-gray-700">{{ $pegawai->total() }}</span> pegawai
                </p>
                <div class="order-1 sm:order-2 w-full sm:w-auto flex justify-center sm:justify-end">
                    {{ $pegawai->appends(request()->query())->links() }}
                </div>
            </div>
        @endif
    </div>

</x-dashboard-layout>