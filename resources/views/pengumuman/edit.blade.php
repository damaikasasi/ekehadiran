<x-dashboard-layout :title="'Edit Pengumuman'">

    <!-- Breadcrumb -->
    <div class="mb-4 text-sm">
        <a href="{{ route('pengumuman.index') }}" class="text-gray-500 hover:text-[#0b602b] transition-colors">Pengumuman</a>
        <span class="text-gray-300 mx-2">/</span>
        <span class="font-bold text-[#0b602b]">Edit Pengumuman</span>
    </div>

    <form action="{{ route('pengumuman.update', $pengumuman->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 sm:p-8 w-full">

            <!-- Title & Subtitle -->
            <div class="mb-8">
                <h2 class="text-2xl font-bold text-[#0b602b] tracking-tight mb-1">Edit Pengumuman</h2>
                <p class="text-sm text-gray-500">Perbarui rincian pengumuman atau berkas lampiran.</p>
            </div>

            <!-- Judul -->
            <div class="mb-5">
                <label for="judul" class="block text-sm font-semibold text-gray-700 mb-1.5">
                    Judul Pengumuman <span class="text-red-500">*</span>
                </label>
                <input
                    id="judul"
                    name="judul"
                    type="text"
                    class="block w-full text-sm rounded-xl border border-gray-300 focus:ring-2 focus:ring-[#0b602b]/20 focus:border-[#0b602b] shadow-sm py-2.5 px-3.5"
                    value="{{ old('judul', $pengumuman->judul) }}"
                    required
                />
                <x-input-error :messages="$errors->get('judul')" class="mt-2" />
            </div>

            <!-- Kategori & Tanggal -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 mb-5">
                <div>
                    <label for="kategori" class="block text-sm font-semibold text-gray-700 mb-1.5">Kategori (Opsional)</label>
                    <input
                        id="kategori"
                        name="kategori"
                        type="text"
                        class="block w-full text-sm rounded-xl border border-gray-300 focus:ring-2 focus:ring-[#0b602b]/20 focus:border-[#0b602b] shadow-sm py-2.5 px-3.5"
                        value="{{ old('kategori', $pengumuman->kategori) }}"
                    />
                    <x-input-error :messages="$errors->get('kategori')" class="mt-2" />
                </div>

                <div>
                    <label for="tanggal" class="block text-sm font-semibold text-gray-700 mb-1.5">
                        Tanggal Publikasi <span class="text-red-500">*</span>
                    </label>
                    <input
                        id="tanggal"
                        name="tanggal"
                        type="date"
                        class="block w-full text-sm rounded-xl border border-gray-300 focus:ring-2 focus:ring-[#0b602b]/20 focus:border-[#0b602b] shadow-sm py-2.5 px-3.5"
                        value="{{ old('tanggal', \Carbon\Carbon::parse($pengumuman->tanggal)->format('Y-m-d')) }}"
                        required
                    />
                    <x-input-error :messages="$errors->get('tanggal')" class="mt-2" />
                </div>
            </div>

            <!-- Isi -->
            <div class="mb-5">
                <label for="isi" class="block text-sm font-semibold text-gray-700 mb-1.5">
                    Isi Pengumuman <span class="text-red-500">*</span>
                </label>
                <textarea
                    id="isi"
                    name="isi"
                    rows="6"
                    class="block w-full text-sm border-gray-300 rounded-xl shadow-sm focus:ring-2 focus:ring-[#0b602b]/20 focus:border-[#0b602b] py-2.5 px-3.5"
                    required>{{ old('isi', $pengumuman->isi) }}</textarea>
                <x-input-error :messages="$errors->get('isi')" class="mt-2" />
            </div>

            <!-- Berkas / Gambar Lampiran -->
            <div>
                <label for="file_lampiran" class="block text-sm font-semibold text-gray-700 mb-1.5">Unggah Berkas / Flyer Baru (opsional)</label>

                @if ($pengumuman->file_lampiran)
                    @php
                        $ext = strtolower(pathinfo($pengumuman->file_lampiran, PATHINFO_EXTENSION));
                        $isImage = in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'gif']);
                    @endphp
                    <div class="mt-2 mb-3 p-3.5 bg-gray-50 border border-gray-200 rounded-xl flex items-center justify-between gap-3">
                        <div class="flex items-center gap-3">
                            @if ($isImage)
                                <img src="{{ Storage::url($pengumuman->file_lampiran) }}" alt="Lampiran" class="w-12 h-12 object-cover rounded-lg border border-gray-200">
                            @else
                                <div class="w-10 h-10 rounded-lg bg-red-100 text-red-600 flex items-center justify-center">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                                </div>
                            @endif
                            <div>
                                <p class="text-xs font-bold text-gray-800">Berkas Lampiran Saat Ini</p>
                                <p class="text-[11px] text-gray-400">Unggah file baru di bawah untuk mengganti.</p>
                            </div>
                        </div>
                        <a href="{{ Storage::url($pengumuman->file_lampiran) }}" target="_blank" class="px-3.5 py-1.5 text-xs font-semibold text-emerald-700 bg-white hover:bg-emerald-50 border border-gray-200 rounded-lg shadow-sm transition">
                            Lihat File
                        </a>
                    </div>
                @endif

                <input
                    type="file"
                    id="file_lampiran"
                    name="file_lampiran"
                    accept=".pdf,.jpg,.jpeg,.png,.webp"
                    class="block w-full text-sm text-gray-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 border border-gray-200 rounded-xl cursor-pointer">
                <p class="mt-1.5 text-xs text-gray-400">Format yang didukung: PDF, JPG, JPEG, PNG, WEBP (Maks. 5 MB).</p>
                <x-input-error :messages="$errors->get('file_lampiran')" class="mt-2" />
            </div>

            <!-- Tombol Action (di dalam card) -->
            <div class="pt-6 border-t border-gray-100 mt-8 flex items-center justify-end gap-3">
                <a href="{{ route('pengumuman.index') }}"
                   class="px-5 py-2.5 rounded-full border border-gray-300 text-gray-600 hover:bg-gray-50 text-sm font-medium transition cursor-pointer">
                    Batal
                </a>
                <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 bg-[#0b602b] hover:bg-[#0e622b] text-white text-sm font-semibold rounded-full shadow-sm hover:shadow transition cursor-pointer">
                    <span>Simpan Perubahan</span>
                </button>
            </div>

        </div>
    </form>

</x-dashboard-layout>