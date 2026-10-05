<x-dashboard-layout :title="'Profil Saya'">

    @php
        $pegawai = auth()->user()->pegawai;
    @endphp

    <div class="max-w-4xl mx-auto">
        @if (session('status') === 'profile-updated' || session('status') === 'password-updated')
            <div class="mb-6 p-4 bg-emerald-50 border border-emerald-100 text-emerald-800 rounded-2xl flex items-center gap-3 shadow-sm transition-all duration-300">
                <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span class="text-sm font-medium">
                    {{ session('status') === 'profile-updated' ? 'Profil berhasil diperbarui.' : 'Kata sandi berhasil diperbarui.' }}
                </span>
            </div>
        @endif

        <div class="relative bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden mb-6">
            <!-- Cover Banner -->
            <div class="h-28 w-full bg-[#0b602b] relative overflow-hidden" style="background-color: #0b602b;">
                <div class="absolute -right-10 -top-10 w-40 h-40 bg-white/5 rounded-full blur-2xl"></div>
                <div class="absolute -left-10 -bottom-10 w-40 h-40 bg-white/5 rounded-full blur-2xl"></div>
            </div>

            <!-- Profile Header Content -->
            <div class="px-6 sm:px-8 py-6 relative flex flex-col md:flex-row md:items-center justify-between gap-6 border-b border-gray-100 bg-white">
                <div class="flex flex-col md:flex-row items-center md:items-center gap-5 text-center md:text-left">
                    <!-- Avatar (Perfect Circle with Aspect 1:1 on all devices) -->
                    <div class="w-24 h-24 shrink-0 rounded-full overflow-hidden border-2 border-emerald-100 shadow-sm bg-white aspect-square relative flex items-center justify-center">
                        @if($pegawai && $pegawai->foto && \Storage::disk('public')->exists($pegawai->foto))
                            <img src="{{ asset('storage/' . $pegawai->foto) }}"
                                 alt="{{ auth()->user()->name }}"
                                 width="96"
                                 height="96"
                                 class="w-full h-full object-cover object-center block aspect-square rounded-full">
                        @else
                            <div class="w-full h-full bg-[#0b602b] text-white flex items-center justify-center text-3xl font-bold rounded-full">
                                {{ substr(auth()->user()->name, 0, 2) }}
                            </div>
                        @endif
                    </div>
                    
                    <!-- Text Info (Aligned with avatar center) -->
                    <div class="flex flex-col items-center md:items-start justify-center">
                        <h3 class="font-extrabold text-gray-900 text-xl sm:text-2xl mb-1 leading-snug">{{ auth()->user()->name }}</h3>
                        
                        <div class="text-xs sm:text-sm font-semibold text-gray-600 uppercase tracking-wide mb-2 text-center md:text-left">
                            {{ $pegawai?->jabatan ?? '-' }}
                        </div>

                        <div>
                            @if($pegawai?->status_aktif === 'aktif')
                                <span class="inline-flex items-center gap-1.5 px-3 py-0.5 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200 text-xs font-semibold">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                    Aktif
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-3 py-0.5 rounded-full bg-rose-50 text-rose-700 border border-rose-200 text-xs font-semibold">
                                    <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                    Tidak Aktif
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="flex flex-wrap gap-2.5 justify-center md:justify-end shrink-0 pt-2 md:pt-0">
                    <button onclick="document.getElementById('modal-edit-profil').classList.remove('hidden')"
                            class="inline-flex items-center gap-2 rounded-xl bg-[#0b602b] hover:bg-[#084d22] px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:shadow transition whitespace-nowrap cursor-pointer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                        <span>Edit Profil</span>
                    </button>
                    @if(auth()->user()->role === 'admin')
                    <button onclick="document.getElementById('modal-ganti-password').classList.remove('hidden')"
                            class="px-4 py-2.5 bg-white border border-gray-200 hover:bg-gray-50 text-gray-700 text-sm font-semibold rounded-xl shadow-sm transition duration-150 flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                        Ganti Kata Sandi
                    </button>
                    @endif
                </div>
            </div>

            <!-- Detail Data (Single Card Grid Layout) -->
            <div class="p-8 bg-gray-50/50">
                <h4 class="font-bold text-gray-850 text-sm mb-6 flex items-center gap-2">
                    <svg class="w-4 h-4 text-green-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    Informasi detail pegawai
                </h4>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

    <!-- NIP -->
    <div class="bg-white rounded-xl border border-gray-200 p-3">
        <p class="text-[11px] text-gray-500 font-medium mb-1">
            Nomor Induk Pegawai
        </p>
        <p class="text-lg font-semibold text-gray-800">
            {{ auth()->user()->nip }}
        </p>
    </div>

    <!-- Jabatan -->
    <div class="bg-white rounded-xl border border-gray-200 p-3">
        <p class="text-[11px] text-gray-500 font-medium mb-1">
            Jabatan
        </p>
        <p class="text-lg font-semibold text-gray-800">
            {{ $pegawai?->jabatan ?? '-' }}
        </p>
    </div>

    <!-- Divisi -->
    <div class="bg-white rounded-xl border border-gray-200 p-3">
        <p class="text-[11px] text-gray-500 font-medium mb-1">
            Divisi / Unit Kerja
        </p>
        <p class="text-lg font-semibold text-gray-800">
            {{ $pegawai?->divisi?->nama_divisi ?? '-' }}
        </p>
    </div>

    <!-- Role -->
    <div class="bg-white rounded-xl border border-gray-200 p-3">
        <p class="text-[11px] text-gray-500 font-medium mb-1">
            Role Akun
        </p>
        <p class="text-lg font-semibold text-gray-800 capitalize">
            {{ auth()->user()->role }}
        </p>
    </div>

    <!-- Capaian Kerja -->
    <div class="bg-white rounded-xl border border-gray-200 p-4 md:col-span-2">
        <p class="text-[11px] text-gray-500 font-medium mb-2 uppercase tracking-wide">
            Capaian Kerja
        </p>
        <p class="text-sm text-gray-800 whitespace-pre-wrap leading-relaxed">
            {{ $pegawai?->capaian_kerja ?? 'Belum ada data capaian kerja' }}
        </p>
    </div>
</div>
            </div>
        </div>
    </div>

    <!-- MODAL EDIT PROFIL -->
    <div id="modal-edit-profil" class="hidden fixed inset-0 bg-gray-900/60 backdrop-blur-sm flex items-center justify-center z-50 p-4 transition-all duration-300">
        <div class="bg-white rounded-2xl border border-gray-100 shadow-xl w-full max-w-md overflow-hidden transform scale-100 transition-all">
            <div class="p-5 border-b border-gray-50 flex justify-between items-center bg-gray-50/50">
                <h4 class="font-bold text-gray-800 text-lg">Edit Profil</h4>
                <button onclick="document.getElementById('modal-edit-profil').classList.add('hidden')" class="text-gray-400 hover:text-gray-600 transition">
                    <svg class="w-5.5 h-5.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="p-6">
                @csrf @method('PATCH')
                <input type="hidden" name="email" value="{{ auth()->user()->email }}">
                <div class="mb-4">
                    <x-input-label for="name" value="Nama Lengkap" class="text-gray-700 font-medium mb-1" />
                    <input type="text" id="name" name="name" value="{{ old('name', auth()->user()->name) }}" required
                           pattern="^(?=.*[a-zA-ZÀ-ÿ])[\s.a-zA-ZÀ-ÿ',-]+$"
                           title="Nama harus mengandung huruf dan tidak boleh mengandung angka atau simbol khusus."
                           oninput="this.value = this.value.replace(/[0-9]/g, '')"
                           class="block w-full border-gray-300 focus:border-green-500 focus:ring-green-500 rounded-xl shadow-sm text-sm" />
                    <x-input-error :messages="$errors->get('name')" class="mt-2 text-xs" />
                </div>
                <div class="mb-6">
                    <x-input-label for="foto" value="Foto Profil Baru" class="text-gray-700 font-medium mb-1" />
                    <input type="file" id="foto" name="foto" accept="image/*" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-green-50 file:text-green-700 hover:file:bg-green-100 border border-gray-250 rounded-xl p-2 bg-white shadow-sm" />
                    <x-input-error :messages="$errors->get('foto')" class="mt-2 text-xs" />
                </div>
                @if(auth()->user()->role === 'admin')
                <div class="mb-6">
                    <x-input-label for="capaian_kerja" value="Capaian Kerja" class="text-gray-700 font-medium mb-1" />
                    <textarea id="capaian_kerja" name="capaian_kerja" rows="3" class="block w-full border-gray-250 focus:border-green-500 focus:ring-green-500 rounded-xl shadow-sm placeholder-gray-400 text-sm" placeholder="Tuliskan capaian atau prestasi kerja pegawai...">{{ old('capaian_kerja', $pegawai?->capaian_kerja) }}</textarea>
                    <x-input-error :messages="$errors->get('capaian_kerja')" class="mt-2 text-xs" />
                </div>
                @endif
                <div class="flex gap-2 justify-end">
                    <button type="button" onclick="document.getElementById('modal-edit-profil').classList.add('hidden')" class="px-4 py-2 border border-gray-200 rounded-xl text-sm font-semibold text-gray-700 hover:bg-gray-50 transition cursor-pointer">
                        Batal
                    </button>
                    <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-[#0b602b] hover:bg-[#084d22] px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:shadow transition whitespace-nowrap cursor-pointer">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL GANTI PASSWORD -->
    @if(auth()->user()->role === 'admin')
    <div id="modal-ganti-password" class="hidden fixed inset-0 bg-gray-900/60 backdrop-blur-sm flex items-center justify-center z-50 p-4 transition-all duration-300">
        <div class="bg-white rounded-2xl border border-gray-100 shadow-xl w-full max-w-md overflow-hidden transform scale-100 transition-all">
            <div class="p-5 border-b border-gray-50 flex justify-between items-center bg-gray-50/50">
                <h4 class="font-bold text-gray-800 text-lg">Ganti Kata Sandi</h4>
                <button onclick="document.getElementById('modal-ganti-password').classList.add('hidden')" class="text-gray-400 hover:text-gray-600 transition">
                    <svg class="w-5.5 h-5.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            <form method="POST" action="{{ route('password.update') }}" class="p-6">
                @csrf @method('PUT')
                <div class="mb-4">
                    <x-input-label for="current_password" value="Kata Sandi Saat Ini" class="text-gray-700 font-medium mb-1" />
                    <x-text-input id="current_password" name="current_password" type="password" class="block w-full border-gray-250 focus:border-green-500 focus:ring-green-500 rounded-xl shadow-sm" required />
                    <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2 text-xs" />
                </div>
                <div class="mb-4">
                    <x-input-label for="password" value="Kata Sandi Baru" class="text-gray-700 font-medium mb-1" />
                    <x-text-input id="password" name="password" type="password" class="block w-full border-gray-250 focus:border-green-500 focus:ring-green-500 rounded-xl shadow-sm" required />
                    <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2 text-xs" />
                </div>
                <div class="mb-6">
                    <x-input-label for="password_confirmation" value="Konfirmasi Kata Sandi Baru" class="text-gray-700 font-medium mb-1" />
                    <x-text-input id="password_confirmation" name="password_confirmation" type="password" class="block w-full border-gray-250 focus:border-green-500 focus:ring-green-500 rounded-xl shadow-sm" required />
                </div>
                <div class="flex gap-2 justify-end">
                    <button type="button" onclick="document.getElementById('modal-ganti-password').classList.add('hidden')" class="px-4 py-2 border border-gray-200 rounded-xl text-sm font-semibold text-gray-700 hover:bg-gray-50 transition cursor-pointer">
                        Batal
                    </button>
                    <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-[#0b602b] hover:bg-[#084d22] px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:shadow transition whitespace-nowrap cursor-pointer">
                        Perbarui Password
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- AUTO-OPEN MODAL IF ERRORS EXIST -->
    @if ($errors->updatePassword->any())
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                document.getElementById('modal-ganti-password').classList.remove('hidden');
            });
        </script>
    @endif
    @endif

    @if ($errors->any() && !$errors->updatePassword->any())
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                document.getElementById('modal-edit-profil').classList.remove('hidden');
            });
        </script>
    @endif

</x-dashboard-layout>
