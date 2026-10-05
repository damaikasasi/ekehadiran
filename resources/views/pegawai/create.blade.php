<x-dashboard-layout :title="'Tambah Pegawai'">
    <!-- Breadcrumb (Optional, matching image) -->
    <div class="mb-4 text-sm">
        <a href="{{ route('pegawai.index') }}" class="text-gray-500 hover:text-[#0b602b] transition-colors">Pegawai</a>
        <span class="text-gray-300 mx-2">/</span>
        <span class="font-bold text-[#0b602b]">Tambah Pegawai</span>
    </div>

    <div class="bg-white rounded-2xl border border-gray-200 p-8 shadow-sm w-full">

        <!-- Title & Subtitle -->
        <div class="mb-8">
            <h2 class="text-2xl font-bold text-[#0b602b] mb-1">Tambah Pegawai</h2>
            <p class="text-gray-500 text-sm">Masukkan informasi detail pegawai baru untuk membuat akun dan profil pada sistem</p>
        </div>

        <form action="{{ route('pegawai.store') }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <!-- Nama Lengkap (Full Width) -->
                <div class="md:col-span-2">
                    <label for="nama" class="block text-sm font-medium text-gray-700 mb-1">
                        Nama Lengkap <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="nama" name="nama" value="{{ old('nama') }}" required
                           pattern="^(?=.*[a-zA-ZÀ-ÿ])[\s.a-zA-ZÀ-ÿ\',-]+$"
                           title="Nama harus mengandung huruf dan tidak boleh mengandung angka atau simbol khusus."
                           oninput="this.value = this.value.replace(/[0-9]/g, '')"
                           class="block w-full border-gray-300 rounded-lg shadow-sm focus:border-green-600 focus:ring-green-600/20 py-2.5 px-3" 
                           placeholder="Masukkan nama lengkap">
                    <p class="text-[11px] text-gray-400 mt-1">Harus mengandung huruf dan tidak boleh mengandung angka</p>
                    <x-input-error :messages="$errors->get('nama')" class="mt-2" />
                </div>

                <!-- NIP -->
                <div>
                    <label for="nip" class="block text-sm font-medium text-gray-700 mb-1">
                        Nomor Induk Kepegawaian (NIP) <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="nip" name="nip" value="{{ old('nip') }}" required autofocus
                           maxlength="18" minlength="18" pattern="[0-9]{18}" inputmode="numeric"
                           oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 18)"
                           class="block w-full border-gray-300 rounded-lg shadow-sm focus:border-green-600 focus:ring-green-600/20 py-2.5 px-3" 
                           placeholder="Masukkan 18 digit NIP (angka)">
                    <p class="text-[11px] text-gray-400 mt-1">Harus terdiri dari 18 digit angka</p>
                    <x-input-error :messages="$errors->get('nip')" class="mt-2" />
                </div>

                <!-- Divisi -->
                <div>
                    <label for="divisi_id" class="block text-sm font-medium text-gray-700 mb-1">
                        Divisi <span class="text-red-500">*</span>
                    </label>
                    <select id="divisi_id" name="divisi_id" required
                            class="block w-full border-gray-300 rounded-lg shadow-sm focus:border-green-600 focus:ring-green-600/20 py-2.5 pl-3.5 pr-8">
                        <option value="">-- Pilih Divisi --</option>
                        @foreach ($divisi as $d)
                            <option value="{{ $d->id }}" {{ old('divisi_id') == $d->id ? 'selected' : '' }}>
                                {{ $d->nama_divisi }}
                            </option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('divisi_id')" class="mt-2" />
                </div>

                <!-- Jabatan -->
                <div>
                    <label for="jabatan" class="block text-sm font-medium text-gray-700 mb-1">Jabatan (opsional)</label>
                    <input type="text" id="jabatan" name="jabatan" value="{{ old('jabatan') }}"
                           class="block w-full border-gray-300 rounded-lg shadow-sm focus:border-green-600 focus:ring-green-600/20 py-2.5 px-3" 
                           placeholder="Masukkan Jabatan">
                    <x-input-error :messages="$errors->get('jabatan')" class="mt-2" />
                </div>

                <!-- Role Akun -->
                <div>
                    <label for="role" class="block text-sm font-medium text-gray-700 mb-1">
                        Role Akun <span class="text-red-500">*</span>
                    </label>
                    <select id="role" name="role" required
                            class="block w-full border-gray-300 rounded-lg shadow-sm focus:border-green-600 focus:ring-green-600/20 py-2.5 pl-3.5 pr-8">
                        <option value="">-- Pilih Role Akun --</option>
                        <option value="user" {{ old('role') == 'user' ? 'selected' : '' }}>User (Pegawai)</option>
                        <option value="atasan" {{ old('role') == 'atasan' ? 'selected' : '' }}>Atasan</option>
                        <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                    </select>
                    <p class="text-[11px] text-gray-400 mt-1">Jika role Atasan dipilih pada divisi yang sudah memiliki atasan, atasan lama akan digantikan otomatis</p>
                    <x-input-error :messages="$errors->get('role')" class="mt-2" />
                </div>

                <!-- No. HP -->
                <div>
                    <label for="no_hp" class="block text-sm font-medium text-gray-700 mb-1">
                        No. HP (opsional) <span class="text-xs text-gray-400 font-normal"></span>
                    </label>
                    <input type="text" id="no_hp" name="no_hp" value="{{ old('no_hp') }}"
                           inputmode="numeric" maxlength="20"
                           oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                           class="block w-full border-gray-300 rounded-lg shadow-sm focus:border-green-600 focus:ring-green-600/20 py-2.5 px-3" 
                           placeholder="Contoh: 08123456789 (opsional - hanya angka)">
                    <p class="text-[11px] text-gray-400 mt-1">Opsional, jika diisi hanya berupa angka (8-20 digit).</p>
                    <x-input-error :messages="$errors->get('no_hp')" class="mt-2" />
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email (opsional)</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}"
                           class="block w-full border-gray-300 rounded-lg shadow-sm focus:border-green-600 focus:ring-green-600/20 py-2.5 px-3" 
                           placeholder="Masukkan Email">
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <!-- Capaian Kerja (Full Width) -->
                <div class="md:col-span-2">
                    <label for="capaian_kerja" class="block text-sm font-medium text-gray-700 mb-1">
                        Capaian Kerja <span class="text-red-500">*</span>
                    </label>
                    <textarea id="capaian_kerja" name="capaian_kerja" rows="2" required
                              class="block w-full border-gray-300 rounded-lg shadow-sm focus:border-green-600 focus:ring-green-600/20 py-2.5 px-3" 
                              placeholder="Ringkasan capaian/prestasi kerja pegawai">{{ old('capaian_kerja') }}</textarea>
                    <x-input-error :messages="$errors->get('capaian_kerja')" class="mt-2" />
                </div>

            </div>

            <!-- Action Button -->
            <div class="mt-8 flex justify-end">
                <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 bg-[#0b602b] hover:bg-[#084920] text-white text-sm font-semibold rounded-full transition shadow-sm hover:shadow cursor-pointer">
                    Buat Akun Pegawai
                </button>
            </div>
        </form>

    </div>

</x-dashboard-layout>