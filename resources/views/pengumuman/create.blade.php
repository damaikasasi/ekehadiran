<x-dashboard-layout :title="'Buat Pengumuman'">

    <!-- Breadcrumb -->
    <div class="mb-4 text-sm">
        <a href="{{ route('pengumuman.index') }}" class="text-gray-500 hover:text-[#0b602b] transition-colors">Pengumuman</a>
        <span class="text-gray-300 mx-2">/</span>
        <span class="font-bold text-[#0b602b]">Buat Pengumuman</span>
    </div>

    <form action="{{ route('pengumuman.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 sm:p-8 w-full">

            <!-- Title & Subtitle -->
            <div class="mb-8">
                <h2 class="text-2xl font-bold text-[#0b602b] tracking-tight mb-1">Buat Pengumuman Baru</h2>
                <p class="text-sm text-gray-500">Publikasikan informasi atau pengumuman penting untuk seluruh pegawai.</p>
            </div>

            <div class="mb-5">
                <label for="judul" class="block text-sm font-semibold text-gray-700 mb-1.5">
                    Judul Pengumuman <span class="text-red-500">*</span>
                </label>
                <input id="judul" name="judul" type="text" class="block w-full text-sm rounded-xl border border-gray-300 focus:ring-2 focus:ring-[#0b602b]/20 focus:border-[#0b602b] shadow-sm py-2.5 px-3.5" placeholder="Masukkan judul pengumuman..." value="{{ old('judul') }}" required />
                <x-input-error :messages="$errors->get('judul')" class="mt-2" />
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 mb-5">
                <div>
                    <label for="kategori" class="block text-sm font-semibold text-gray-700 mb-1.5">Kategori (opsional)</label>
                    <input id="kategori" name="kategori" type="text" class="block w-full text-sm rounded-xl border border-gray-300 focus:ring-2 focus:ring-[#0b602b]/20 focus:border-[#0b602b] shadow-sm py-2.5 px-3.5" placeholder="Contoh: Kebijakan, Umum, Libur" value="{{ old('kategori') }}" />
                    <x-input-error :messages="$errors->get('kategori')" class="mt-2" />
                </div>

                <div>
                    <label for="tanggal" class="block text-sm font-semibold text-gray-700 mb-1.5">
                        Tanggal Publikasi <span class="text-red-500">*</span>
                    </label>
                    <input id="tanggal" name="tanggal" type="date" min="{{ now()->format('Y-m-d') }}" class="block w-full text-sm rounded-xl border border-gray-300 focus:ring-2 focus:ring-[#0b602b]/20 focus:border-[#0b602b] shadow-sm py-2.5 px-3.5" value="{{ old('tanggal', now()->format('Y-m-d')) }}" required />
                    <x-input-error :messages="$errors->get('tanggal')" class="mt-2" />
                </div>
            </div>

            <div class="mb-5">
                <label for="isi" class="block text-sm font-semibold text-gray-700 mb-1.5">
                    Isi Pengumuman <span class="text-red-500">*</span>
                </label>
                <textarea id="isi" name="isi" rows="6" class="block w-full text-sm border-gray-300 rounded-xl shadow-sm focus:ring-2 focus:ring-[#0b602b]/20 focus:border-[#0b602b] py-2.5 px-3.5" placeholder="Tulis isi pengumuman secara lengkap di sini..." required>{{ old('isi') }}</textarea>
                <x-input-error :messages="$errors->get('isi')" class="mt-2" />
            </div>

            <div>
                <label for="file_lampiran" class="block text-sm font-semibold text-gray-700 mb-1.5">Unggah Berkas / Flyer / Gambar (opsional)</label>
                <input
                    type="file"
                    id="file_lampiran"
                    name="file_lampiran"
                    accept=".pdf,.jpg,.jpeg,.png,.webp"
                    class="block w-full text-sm text-gray-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 border border-gray-200 rounded-xl cursor-pointer">
                <p class="mt-1.5 text-xs text-gray-400">Format yang didukung: PDF, JPG, JPEG, PNG, WEBP (Maks. 5 MB).</p>
                <x-input-error :messages="$errors->get('file_lampiran')" class="mt-2" />
            </div>

            <!-- Action Buttons (di dalam card) -->
            <div class="pt-6 border-t border-gray-100 mt-8 flex items-center justify-end gap-3">
                <a href="{{ route('pengumuman.index') }}" class="px-5 py-2.5 rounded-full border border-gray-300 text-gray-600 hover:bg-gray-50 text-sm font-medium transition cursor-pointer">
                    Batal
                </a>
                <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 bg-[#0b602b] hover:bg-[#0e622b] text-white text-sm font-semibold rounded-full shadow-sm hover:shadow transition cursor-pointer">
                    <span>Publikasikan Pengumuman</span>
                </button>
            </div>

        </div>
    </form>

</x-dashboard-layout>