<x-dashboard-layout :title="'Ajukan Izin'">

    <!-- BREADCRUMB -->
    <div class="flex items-center gap-2 text-sm font-medium mb-6">
        <a href="{{ route('izin.index') }}" class="text-gray-600 hover:text-green-700 transition flex items-center gap-1">
            <svg class="w-4 h-4 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            <span>Permohonan Izin</span>
        </a>
        <span class="text-gray-300">/</span>
        <span class="text-[#0e622b] font-bold">Ajukan Izin</span>
    </div>

    @if ($errors->any())
        <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-700 rounded-2xl">
            <div class="flex items-center gap-2 font-bold mb-1">
                <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span>Terjadi Kesalahan</span>
            </div>
            <ul class="list-disc list-inside text-xs space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- MAIN FORM CARD -->
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 md:p-8 mb-8 w-full">
        <h2 class="text-xl md:text-2xl font-bold text-[#0e622b] mb-1">Formulir Pengajuan Izin</h2>
        <p class="text-sm text-gray-600 mb-6">Silahkan lengkapi formulir di bawah ini untuk mengajukan permohonan izin baru</p>

        <form action="{{ route('izin.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- Jenis Izin -->
                <div class="md:col-span-2">
                    <label for="jenis" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">
                        Jenis Izin <span class="text-red-500">*</span>
                    </label>
                    <select name="jenis" id="jenis" onchange="toggleSakitOptions()" class="block w-full border-gray-250 focus:border-green-600 focus:ring-green-600 rounded-xl shadow-xs text-sm py-2.5 px-3 bg-white" required>
                        <option value="">-- Pilih Jenis Izin --</option>
                        <option value="sakit" {{ old('jenis') === 'sakit' ? 'selected' : '' }}>Sakit</option>
                        <option value="dinas" {{ old('jenis') === 'dinas' ? 'selected' : '' }}>Dinas</option>
                        <option value="lainnya" {{ old('jenis') === 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                    </select>
                    <x-input-error :messages="$errors->get('jenis')" class="mt-1.5 text-xs" />
                </div>

                <!-- Opsi Surat Dokter (Kondisional Sakit) -->
                <div id="sakit-options" class="md:col-span-2 {{ old('jenis') === 'sakit' ? '' : 'hidden' }} p-4 rounded-xl bg-amber-50/60 border border-amber-200 transition-all">
                    <label class="block text-xs font-bold text-amber-900 uppercase tracking-wider mb-2">
                        Ada Surat Keterangan Dokter? <span class="text-red-500">*</span>
                    </label>
                    <div class="flex flex-wrap gap-4 mt-1">
                        <label class="flex items-center gap-2 cursor-pointer bg-white px-4 py-2 rounded-xl border border-amber-200 text-sm font-medium text-gray-700 hover:bg-amber-50 transition">
                            <input type="radio" name="ada_surat_dokter" value="1" onchange="toggleUploadWajib()" class="text-[#0b602b] focus:ring-green-600" {{ old('ada_surat_dokter') == '1' ? 'checked' : '' }}>
                            <span>Ya, ada surat dokter</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer bg-white px-4 py-2 rounded-xl border border-amber-200 text-sm font-medium text-gray-700 hover:bg-amber-50 transition">
                            <input type="radio" name="ada_surat_dokter" value="0" onchange="toggleUploadWajib()" class="text-[#0b602b] focus:ring-green-600" {{ old('ada_surat_dokter') == '0' ? 'checked' : '' }}>
                            <span>Tidak ada surat dokter</span>
                        </label>
                    </div>
                </div>

                <!-- Tanggal Mulai -->
                <div>
                    <label for="tanggal_mulai" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">
                        Tanggal Mulai <span class="text-red-500">*</span>
                    </label>
                    <input type="date" id="tanggal_mulai" name="tanggal_mulai" value="{{ old('tanggal_mulai') }}" class="block w-full border-gray-250 focus:border-green-600 focus:ring-green-600 rounded-xl shadow-xs text-sm py-2.5 px-3 bg-white" required />
                    <x-input-error :messages="$errors->get('tanggal_mulai')" class="mt-1.5 text-xs" />
                </div>

                <!-- Tanggal Selesai -->
                <div>
                    <label for="tanggal_selesai" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">
                        Tanggal Selesai <span class="text-red-500">*</span>
                    </label>
                    <input type="date" id="tanggal_selesai" name="tanggal_selesai" value="{{ old('tanggal_selesai') }}" class="block w-full border-gray-250 focus:border-green-600 focus:ring-green-600 rounded-xl shadow-xs text-sm py-2.5 px-3 bg-white" required />
                    <x-input-error :messages="$errors->get('tanggal_selesai')" class="mt-1.5 text-xs" />
                </div>

                <!-- Keterangan -->
                <div class="md:col-span-2">
                    <label for="keterangan" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">
                        Keterangan / Alasan Izin
                    </label>
                    <textarea name="keterangan" id="keterangan" rows="3" class="block w-full border-gray-250 focus:border-green-600 focus:ring-green-600 rounded-xl shadow-xs text-sm p-3 placeholder-gray-400" placeholder="Jelaskan alasan izin (wajib diisi jika memilih jenis 'Lainnya')...">{{ old('keterangan') }}</textarea>
                    <x-input-error :messages="$errors->get('keterangan')" class="mt-1.5 text-xs" />
                </div>

                <!-- Upload Lampiran -->
                <div class="md:col-span-2">
                    <label for="file_lampiran" id="label-upload" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">
                        Upload Lampiran (Surat Dokter / Dokumen Pendukung)
                    </label>
                    <input type="file" name="file_lampiran" id="file_lampiran" accept=".pdf,.jpg,.jpeg,.png" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-green-50 file:text-[#0b602b] hover:file:bg-green-100 border border-gray-250 rounded-xl p-2 bg-white shadow-xs" />
                    <p class="text-[11px] text-gray-500 mt-1.5">Format file yang diperbolehkan: PDF, JPG, JPEG, PNG (Maks. 2MB)</p>
                    <x-input-error :messages="$errors->get('file_lampiran')" class="mt-1.5 text-xs" />
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
                <a href="{{ route('izin.index') }}" class="px-5 py-2.5 border border-gray-200 rounded-full text-sm font-semibold text-gray-700 hover:bg-gray-50 transition">
                    Batal
                </a>
                <button type="submit" class="inline-flex items-center gap-2 rounded-full bg-[#0b602b] hover:bg-[#084d22] px-6 py-2.5 text-sm font-semibold text-white shadow-sm hover:shadow transition whitespace-nowrap cursor-pointer">
                    <span>Ajukan Izin</span>
                </button>
            </div>
        </form>
    </div>

    <script>
        function toggleSakitOptions() {
            const jenis = document.getElementById('jenis').value;
            const sakitOptions = document.getElementById('sakit-options');
            if (jenis === 'sakit') {
                sakitOptions.classList.remove('hidden');
            } else {
                sakitOptions.classList.add('hidden');
            }
        }

        function toggleUploadWajib() {
            const checkedRadio = document.querySelector('input[name="ada_surat_dokter"]:checked');
            const fileInput = document.getElementById('file_lampiran');
            const labelUpload = document.getElementById('label-upload');
            if (checkedRadio && checkedRadio.value === '1') {
                fileInput.setAttribute('required', 'required');
                labelUpload.innerHTML = 'Upload Lampiran (Surat Dokter) <span class="text-red-500">*</span>';
            } else {
                fileInput.removeAttribute('required');
                labelUpload.innerHTML = 'Upload Lampiran (Surat Dokter / Dokumen Pendukung)';
            }
        }
    </script>

</x-dashboard-layout>