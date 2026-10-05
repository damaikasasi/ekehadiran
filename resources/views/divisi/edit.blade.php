<x-dashboard-layout :title="'Edit Divisi'">

    <!-- Breadcrumb -->
    <div class="mb-4 text-sm">
        <a href="{{ route('divisi.index') }}" class="text-gray-500 hover:text-[#0b602b] transition-colors">Divisi</a>
        <span class="text-gray-300 mx-2">/</span>
        <span class="font-bold text-[#0b602b]">Edit Divisi</span>
    </div>

    <div class="bg-white rounded-2xl border border-gray-200 p-8 shadow-sm w-full">

        <!-- Title & Subtitle -->
        <div class="mb-8">
            <h2 class="text-2xl font-bold text-[#0b602b] mb-1">Edit Divisi</h2>
            <p class="text-gray-500 text-sm">Perbarui rincian informasi divisi.</p>
        </div>

        <form action="{{ route('divisi.update', $divisi->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-6">
                <!-- Nama Divisi -->
                <div>
                    <label for="nama_divisi" class="block text-sm font-semibold text-gray-700 mb-1.5">
                        Nama Divisi <span class="text-red-500">*</span>
                    </label>
                    <input
                        id="nama_divisi"
                        name="nama_divisi"
                        type="text"
                        class="block w-full text-sm rounded-xl border {{ $errors->has('nama_divisi') ? 'border-red-400 focus:ring-2 focus:ring-red-100 focus:border-red-500' : 'border-gray-300 focus:ring-2 focus:ring-[#0b602b]/20 focus:border-[#0b602b]' }} shadow-sm py-2.5 px-3.5"
                        value="{{ old('nama_divisi', $divisi->nama_divisi) }}"
                        required
                        autofocus
                        placeholder="Contoh: Divisi Keuangan & Akuntansi"
                    />
                    <x-input-error :messages="$errors->get('nama_divisi')" class="mt-2" />
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 pt-5 border-t border-gray-100">
                <a href="{{ route('divisi.index') }}" class="px-5 py-2.5 rounded-full border border-gray-300 text-gray-600 hover:bg-gray-50 text-sm font-medium transition cursor-pointer">
                    Batal
                </a>
                <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 bg-[#0b602b] hover:bg-[#0e622b] text-white text-sm font-semibold rounded-full shadow-sm hover:shadow transition cursor-pointer">
                    <span>Simpan Perubahan</span>
                </button>
            </div>
        </form>
    </div>

</x-dashboard-layout>