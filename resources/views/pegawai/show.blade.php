<x-dashboard-layout :title="'Detail Pegawai'">

    <!-- Breadcrumb -->
    <div class="mb-6 text-sm font-medium flex items-center gap-2">
        <a href="{{ route('pegawai.index') }}" class="text-gray-600 hover:text-[#0b602b] transition-colors flex items-center gap-1">
            <svg class="w-4 h-4 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            <span>Pegawai</span>
        </a>
        <span class="text-gray-300">/</span>
        <span class="font-bold text-[#0b602b]">Detail Pegawai</span>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 items-stretch w-full">

        <!-- ============ LEFT: Profile Card ============ -->
        <div class="lg:col-span-1 min-w-0 bg-white rounded-2xl border border-gray-200 shadow-sm p-8 flex flex-col items-center text-center h-full">

            <!-- Avatar -->
            <div class="relative mb-4">
                <div class="w-28 h-28 rounded-full overflow-hidden bg-gray-100 flex items-center justify-center ring-4 ring-gray-100">
                    @if ($pegawai->foto && Storage::disk('public')->exists($pegawai->foto))
                        <img src="{{ asset('storage/'.$pegawai->foto) }}" class="w-full h-full object-cover" alt="{{ $pegawai->nama }}">
                    @else
                        <svg class="w-16 h-16 text-gray-300" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    @endif
                </div>
                <div class="absolute bottom-0.5 right-0.5 w-8 h-8 bg-[#0b602b] rounded-full flex items-center justify-center shadow-md ring-2 ring-white">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
            </div>

            <!-- Name -->
            <h2 class="text-lg font-bold text-gray-900 mb-2 leading-tight">{{ $pegawai->nama }}</h2>

            <!-- Role Badge -->
            <span class="inline-block px-4 py-1 rounded-full text-xs font-bold bg-[#0b602b] text-white mb-6">
                @if($pegawai->user)
                    {{ $pegawai->user->role === 'user' ? 'Pegawai Tetap' : ucfirst($pegawai->user->role) }}
                @else
                    Pegawai
                @endif
            </span>

            <div class="w-full border-t border-gray-100 mb-5"></div>

            <!-- Edit Button -->
            <a href="{{ route('pegawai.edit', $pegawai) }}"
               class="w-full flex items-center justify-center gap-2 py-2.5 rounded-xl border border-gray-200 text-gray-700 text-sm font-medium hover:bg-gray-50 transition mb-3">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                Edit Profil
            </a>

            <!-- Delete -->
            <form action="{{ route('pegawai.destroy', $pegawai) }}" method="POST"
                  class="confirm-form w-full"
                  data-title="Hapus Akun Pegawai"
                  data-text="Tindakan ini akan menghapus akun pegawai ini secara permanen. Lanjutkan?"
                  data-icon="warning"
                  data-icon-color="#BA1A1A"
                  data-confirm-color="#BA1A1A"
                  data-confirm-text="Ya, Hapus">
                @csrf
                @method('DELETE')
                <button type="submit"
                        class="w-full text-red-500 text-sm font-medium hover:text-red-700 transition py-1 cursor-pointer">
                    Hapus Akun
                </button>
            </form>

        </div>

        <!-- ============ RIGHT: Info Card ============ -->
        <div class="lg:col-span-3 min-w-0 overflow-hidden bg-white rounded-2xl border border-gray-200 shadow-sm p-8">

            <div class="mb-6 pb-5 border-b border-gray-100">
                <h3 class="text-base font-bold text-gray-900">Informasi Pribadi</h3>
                <p class="text-sm text-gray-400 mt-0.5">Lengkapi data diri Anda sesuai dokumen identitas resmi.</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">

                <!-- Nama Lengkap -->
                <div class="sm:col-span-2">
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">Nama Lengkap</label>
                    <div class="border border-gray-200 rounded-xl px-4 py-3 text-gray-800 text-sm bg-gray-50">
                        {{ $pegawai->nama ?? '-' }}
                    </div>
                </div>

                <!-- NIP -->
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">NIP</label>
                    <div class="border border-gray-200 rounded-xl px-4 py-3 text-gray-800 text-sm bg-gray-50">
                        {{ $pegawai->user->nip ?? '-' }}
                    </div>
                </div>

                <!-- Divisi -->
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">Divisi</label>
                    <div class="border border-gray-200 rounded-xl px-4 py-3 text-gray-800 text-sm bg-gray-50">
                        {{ $pegawai->divisi->nama_divisi ?? '-' }}
                    </div>
                </div>

                <!-- Jabatan -->
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">Jabatan</label>
                    <div class="border border-gray-200 rounded-xl px-4 py-3 text-gray-800 text-sm bg-gray-50">
                        {{ $pegawai->jabatan ?? '-' }}
                    </div>
                </div>

                <!-- Role Akun -->
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">Role Akun</label>
                    <div class="border border-gray-200 rounded-xl px-4 py-3 text-gray-800 text-sm bg-gray-50">
                        {{ $pegawai->user ? ucfirst($pegawai->user->role) : '-' }}
                    </div>
                </div>

                <!-- No. HP -->
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">No. HP</label>
                    <div class="border border-gray-200 rounded-xl px-4 py-3 text-gray-800 text-sm bg-gray-50">
                        {{ $pegawai->no_hp ?? '-' }}
                    </div>
                </div>

                <!-- Email -->
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">Email</label>
                    <div class="border border-gray-200 rounded-xl px-4 py-3 text-gray-800 text-sm bg-gray-50">
                        {{ $pegawai->email ?? '-' }}
                    </div>
                </div>

                <!-- Status -->
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">Status Kepegawaian</label>
                    <div class="border border-gray-200 rounded-xl px-4 py-3 text-sm bg-gray-50">
                        @if($pegawai->status_aktif === 'aktif')
                            <span class="inline-flex items-center text-green-700 font-semibold">
                                Aktif
                            </span>
                        @else
                            <span class="inline-flex items-center text-gray-500 font-semibold">
                                Tidak Aktif
                            </span>
                        @endif
                    </div>
                </div>

            </div>
        </div>

    </div>

</x-dashboard-layout>