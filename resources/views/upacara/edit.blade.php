<x-dashboard-layout :title="'Edit Agenda Upacara'">

    <!-- Breadcrumb Header -->
    <div class="mb-4 text-sm flex items-center gap-2">
        <a href="{{ route('upacara.index') }}" class="text-gray-500 hover:text-[#0b602b] transition-colors font-medium">
            Presensi Upacara
        </a>
        <span class="text-gray-300">/</span>
        <a href="{{ route('upacara.show', $upacara) }}" class="text-gray-500 hover:text-[#0b602b] transition-colors font-medium">
            {{ $upacara->nama_upacara }}
        </a>
        <span class="text-gray-300">/</span>
        <span class="font-bold text-[#0b602b]">Edit Agenda</span>
    </div>

    @if($errors->any())
        <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200 text-red-700 text-sm">
            <div class="font-bold mb-1">Terjadi kesalahan:</div>
            <ul class="list-disc pl-5 space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('upacara.update', $upacara) }}" method="POST" enctype="multipart/form-data" class="w-full">
        @csrf
        @method('PUT')

        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 sm:p-8 space-y-6">

            <!-- Title & Subtitle -->
            <div class="border-b border-gray-100 pb-5">
                <h2 class="text-2xl font-bold text-gray-900 tracking-tight">Edit Agenda Upacara Hari Besar</h2>
                <p class="mt-1 text-sm text-gray-500">
                    Ubah informasi agenda upacara dan perbarui daftar hadir via upload file jika diperlukan
                </p>
            </div>

            <!-- Bagian 1: Informasi Upacara -->
            <div>
                <h3 class="text-base font-bold text-gray-900 border-b border-gray-100 pb-3 mb-4">
                    1. Informasi Upacara Hari Besar
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="md:col-span-2">
                        <label for="nama_upacara" class="block text-xs font-semibold text-gray-700 mb-1.5">
                            Nama Upacara <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="nama_upacara" id="nama_upacara" required
                               value="{{ old('nama_upacara', $upacara->nama_upacara) }}"
                               class="w-full px-4 py-2.5 text-sm rounded-xl border border-gray-300 focus:ring-2 focus:ring-[#0b602b]/20 focus:border-[#0b602b] transition shadow-sm">
                    </div>

                    <div>
                        <label for="tanggal" class="block text-xs font-semibold text-gray-700 mb-1.5">
                            Tanggal Pelaksanaan <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="tanggal" id="tanggal" required
                               value="{{ old('tanggal', \Carbon\Carbon::parse($upacara->tanggal)->format('Y-m-d')) }}"
                               class="w-full px-4 py-2.5 text-sm rounded-xl border border-gray-300 focus:ring-2 focus:ring-[#0b602b]/20 focus:border-[#0b602b] transition shadow-sm">
                    </div>

                    <div>
                        <label for="waktu_mulai" class="block text-xs font-semibold text-gray-700 mb-1.5">
                            Waktu Mulai Upacara
                        </label>
                        <input type="time" name="waktu_mulai" id="waktu_mulai"
                               value="{{ old('waktu_mulai', $upacara->waktu_mulai ? substr($upacara->waktu_mulai, 0, 5) : '07:30') }}"
                               class="w-full px-4 py-2.5 text-sm rounded-xl border border-gray-300 focus:ring-2 focus:ring-[#0b602b]/20 focus:border-[#0b602b] transition shadow-sm">
                    </div>

                    <div class="md:col-span-2">
                        <label for="keterangan" class="block text-xs font-semibold text-gray-700 mb-1.5">
                            Keterangan / Lokasi (Opsional)
                        </label>
                        <textarea name="keterangan" id="keterangan" rows="2"
                                  placeholder="Contoh: Halaman Kantor Utama"
                                  class="w-full px-4 py-2.5 text-sm rounded-xl border border-gray-300 focus:ring-2 focus:ring-[#0b602b]/20 focus:border-[#0b602b] transition shadow-sm">{{ old('keterangan', $upacara->keterangan) }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Bagian 2: Upload File Absensi Baru (Opsional) -->
            <div>
                <h3 class="text-base font-bold text-gray-900 border-b border-gray-100 pb-3 mb-4 flex items-center justify-between">
                    <span>2. Perbarui File Spreadsheet Absensi (Opsional)</span>
                    <a href="{{ route('upacara.template') }}" class="text-xs font-bold text-[#0e622b] hover:underline flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                        </svg>
                        Download Template CSV
                    </a>
                </h3>

                <div class="bg-gray-50 border-2 border-dashed border-gray-300 hover:border-[#0e622b] rounded-2xl p-6 text-center transition">
                    <svg class="w-12 h-12 mx-auto text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                    </svg>

                    <label for="file" class="cursor-pointer">
                        <span class="inline-flex items-center px-4 py-2 bg-white text-[#0e622b] font-bold text-sm rounded-xl border border-gray-300 hover:bg-gray-50 shadow-sm transition">
                            Pilih File Baru (CSV / Excel)
                        </span>
                        <input type="file" name="file" id="file" accept=".csv,.xlsx,.xls,.txt"
                               class="sr-only" onchange="document.getElementById('editFileName').innerText = this.files[0]?.name || ''">
                    </label>

                    <p id="editFileName" class="text-sm font-semibold text-emerald-800 mt-2.5"></p>
                    <p class="text-xs text-gray-500 mt-1">Biarkan kosong jika tidak ingin mengubah data absensi peserta yang sudah ada.</p>
                </div>

                <div class="mt-3 p-3 bg-blue-50/70 border border-blue-100 rounded-xl text-xs text-blue-800 space-y-1">
                    <p class="font-bold text-blue-900">Petunjuk Format File:</p>
                    <p>&bull; Susunan Kolom: <code>No</code>, <code>NIP</code>, <code>Nama</code>, <code>Tanggal</code>, <code>Keterangan</code></p>
                    <p>&bull; Kolom <strong>Keterangan</strong> diisi: <strong>Hadir</strong> / <strong>Tidak Hadir</strong> / <strong>Izin</strong>.</p>
                    <p>&bull; Anda dapat mengunduh format yang sudah terisi otomatis seluruh data pegawai melalui tombol <strong>Download Template CSV</strong> di atas.</p>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="pt-4 border-t border-gray-100 flex items-center justify-end gap-3">
                <a href="{{ route('upacara.show', $upacara) }}"
                   class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-semibold rounded-full transition">
                    Batal
                </a>
                <button type="submit"
                        class="inline-flex items-center gap-2 px-6 py-2.5 bg-[#0e622b] hover:bg-[#0b4d22] text-white text-sm font-semibold rounded-full shadow-sm transition">
                    Simpan Perubahan
                </button>
            </div>

        </div>
    </form>

</x-dashboard-layout>
