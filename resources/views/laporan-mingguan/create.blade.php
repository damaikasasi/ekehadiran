<x-dashboard-layout :title="'Buat Laporan Bulanan'">

    <!-- BREADCRUMB -->
    <div class="flex items-center gap-2 text-sm mb-6">
        <a href="{{ route('laporan-mingguan.index') }}" class="text-gray-500 hover:text-green-700 transition">Laporan</a>
        <span class="text-gray-300">/</span>
        <span class="text-[#0e622b] font-bold">Buat Laporan baru</span>
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
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 md:p-8 mb-8" x-data="{
        files: [],
        maxFiles: 5,
        totalSizeBytes: 0,
        maxSizeBytes: 20 * 1024 * 1024,
        isOverLimit: false,
        limitMessage: '',
        compressing: false,
        isSubmitting: false,
        async compressImage(file) {
            return new Promise((resolve) => {
                if (!file.type.startsWith('image/')) return resolve(file);
                const reader = new FileReader();
                reader.readAsDataURL(file);
                reader.onload = (e) => {
                    const img = new Image();
                    img.src = e.target.result;
                    img.onload = () => {
                        const canvas = document.createElement('canvas');
                        let width = img.width;
                        let height = img.height;
                        const maxDim = 1280;

                        if (width > maxDim || height > maxDim) {
                            if (width > height) {
                                height = Math.round((height * maxDim) / width);
                                width = maxDim;
                            } else {
                                width = Math.round((width * maxDim) / height);
                                height = maxDim;
                            }
                        }

                        canvas.width = width;
                        canvas.height = height;
                        const ctx = canvas.getContext('2d');
                        ctx.drawImage(img, 0, 0, width, height);

                        canvas.toBlob((blob) => {
                            if (!blob) {
                                return resolve(file);
                            }
                            const compressedFile = new File([blob], file.name.replace(/\.[^/.]+$/, '') + '.jpg', {
                                type: 'image/jpeg',
                                lastModified: Date.now()
                            });
                            resolve(compressedFile);
                        }, 'image/jpeg', 0.82);
                    };
                    img.onerror = () => resolve(file);
                };
                reader.onerror = () => resolve(file);
            });
        },
        async handleFileChange(event) {
            const input = event.target;
            if (!input.files || input.files.length === 0) return;
            await this.addFiles(Array.from(input.files));
            input.value = '';
        },
        async handleDrop(event) {
            event.preventDefault();
            if (event.dataTransfer.files && event.dataTransfer.files.length > 0) {
                await this.addFiles(Array.from(event.dataTransfer.files));
            }
        },
        async addFiles(fileList) {
            this.limitMessage = '';
            const validImages = fileList.filter(f => f.type.startsWith('image/'));
            
            if (this.files.length >= this.maxFiles) {
                this.limitMessage = 'Batas maksimal 5 foto telah tercapai. Hapus foto yang ada jika ingin mengganti.';
                return;
            }

            this.compressing = true;
            let addedCount = 0;

            for (const file of validImages) {
                if (this.files.length >= this.maxFiles) {
                    this.limitMessage = 'Hanya 5 foto dokumentasi yang diperbolehkan. Foto selebihnya tidak dimasukkan.';
                    break;
                }

                const compressed = await this.compressImage(file);
                const exists = this.files.some(existing => existing.name === compressed.name && Math.abs(existing.size - compressed.size) < 100);
                if (!exists) {
                    this.files.push({
                        file: compressed,
                        name: compressed.name,
                        size: compressed.size,
                        sizeFormatted: (compressed.size / (1024 * 1024) >= 1) ? (compressed.size / (1024 * 1024)).toFixed(2) + ' MB' : (compressed.size / 1024).toFixed(0) + ' KB',
                        previewUrl: URL.createObjectURL(compressed)
                    });
                    addedCount++;
                }
            }
            this.compressing = false;
            this.calculateTotal();
            this.syncInput();
        },
        removeFile(index) {
            this.files.splice(index, 1);
            this.limitMessage = '';
            this.calculateTotal();
            this.syncInput();
        },
        calculateTotal() {
            this.totalSizeBytes = this.files.reduce((acc, f) => acc + f.size, 0);
            this.isOverLimit = this.files.length > this.maxFiles || this.totalSizeBytes > this.maxSizeBytes;
        },
        syncInput() {
            try {
                const dt = new DataTransfer();
                this.files.forEach(item => dt.items.add(item.file));
                const input = document.getElementById('dokumentasi');
                if (input) {
                    input.files = dt.files;
                }
            } catch (e) {
                console.error('Error sync input:', e);
            }
        },
        getTotalSizeFormatted() {
            return (this.totalSizeBytes / (1024 * 1024)).toFixed(2) + ' MB';
        }
    }">
        <h2 class="text-xl md:text-2xl font-bold text-[#0e622b] mb-1">Formulir Laporan Bulanan</h2>
        <p class="text-sm text-gray-600 mb-6">Silahkan lengkapi formulir di bawah ini untuk membuat laporan kinerja bulanan baru</p>

        <form action="{{ route('laporan-mingguan.store') }}" method="POST" enctype="multipart/form-data" @submit="isSubmitting = true; syncInput()">
            @csrf

            <!-- BULAN & TAHUN -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label for="bulan" class="block text-sm font-medium text-gray-700 mb-2">Bulan <span class="text-red-500">*</span></label>
                    <select id="bulan" name="bulan" required class="block w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm text-gray-800 focus:border-[#0e622b] focus:ring-1 focus:ring-[#0e622b] transition cursor-pointer">
                        <option value="" disabled {{ old('bulan') ? '' : 'selected' }}>Pilih Bulan</option>
                        @foreach (['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'] as $i => $namaBulan)
                            <option value="{{ $i + 1 }}" {{ old('bulan', now()->month) == ($i + 1) ? 'selected' : '' }}>{{ $namaBulan }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="tahun" class="block text-sm font-medium text-gray-700 mb-2">Tahun <span class="text-red-500">*</span></label>
                    <select id="tahun" name="tahun" required class="block w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm text-gray-800 focus:border-[#0e622b] focus:ring-1 focus:ring-[#0e622b] transition cursor-pointer">
                        <option value="" disabled {{ old('tahun') ? '' : 'selected' }}>Pilih Tahun</option>
                        @for ($y = now()->year + 1; $y >= 2022; $y--)
                            <option value="{{ $y }}" {{ old('tahun', now()->year) == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endfor
                    </select>
                </div>
            </div>

            <!-- PENDAHULUAN & TUJUAN -->
            <div class="mb-6">
                <label for="pendahuluan" class="block text-sm font-medium text-gray-700 mb-2">Pendahuluan & Tujuan <span class="text-red-500">*</span></label>
                <textarea id="pendahuluan" name="pendahuluan" rows="3" required class="block w-full rounded-xl border border-gray-300 p-3.5 text-sm text-gray-800 placeholder-gray-400 focus:border-[#0e622b] focus:ring-1 focus:ring-[#0e622b] transition resize-y" placeholder="Masukkan deskripsi...">{{ old('pendahuluan') }}</textarea>
            </div>

            <!-- KEGIATAN YANG TELAH DILAKSANAKAN -->
            <div class="mb-6">
                <label for="kegiatan_dilaksanakan" class="block text-sm font-medium text-gray-700 mb-2">Kegiatan yang telah dilaksanakan <span class="text-red-500">*</span></label>
                <textarea id="kegiatan_dilaksanakan" name="kegiatan_dilaksanakan" rows="3" required class="block w-full rounded-xl border border-gray-300 p-3.5 text-sm text-gray-800 placeholder-gray-400 focus:border-[#0e622b] focus:ring-1 focus:ring-[#0e622b] transition resize-y" placeholder="Masukkan deskripsi...">{{ old('kegiatan_dilaksanakan') }}</textarea>
            </div>

            <!-- KENDALA YANG DIHADAPI -->
            <div class="mb-6">
                <label for="kendala" class="block text-sm font-medium text-gray-700 mb-2">Kendala yang dihadapi <span class="text-red-500">*</span></label>
                <textarea id="kendala" name="kendala" rows="3" required class="block w-full rounded-xl border border-gray-300 p-3.5 text-sm text-gray-800 placeholder-gray-400 focus:border-[#0e622b] focus:ring-1 focus:ring-[#0e622b] transition resize-y" placeholder="Masukkan deskripsi...">{{ old('kendala') }}</textarea>
            </div>

            <!-- RENCANA TINDAK LANJUT -->
            <div class="mb-6">
                <label for="rencana" class="block text-sm font-medium text-gray-700 mb-2">Rencana tindak lanjut <span class="text-red-500">*</span></label>
                <textarea id="rencana" name="rencana" rows="3" required class="block w-full rounded-xl border border-gray-300 p-3.5 text-sm text-gray-800 placeholder-gray-400 focus:border-[#0e622b] focus:ring-1 focus:ring-[#0e622b] transition resize-y" placeholder="Masukkan deskripsi...">{{ old('rencana') }}</textarea>
            </div>

            <!-- PENUTUP -->
            <div class="mb-6">
                <label for="penutup" class="block text-sm font-medium text-gray-700 mb-2">Penutup <span class="text-red-500">*</span></label>
                <textarea id="penutup" name="penutup" rows="3" required class="block w-full rounded-xl border border-gray-300 p-3.5 text-sm text-gray-800 placeholder-gray-400 focus:border-[#0e622b] focus:ring-1 focus:ring-[#0e622b] transition resize-y" placeholder="Masukkan deskripsi...">{{ old('penutup') }}</textarea>
            </div>

            <!-- DOKUMENTASI -->
            <div class="mb-8">
                <div class="flex items-center justify-between mb-2">
                    <label class="block text-sm font-medium text-gray-700">Dokumentasi Kegiatan <span class="text-xs text-gray-400 font-normal">(Opsional)</span></label>
                    <span class="text-xs font-semibold px-2.5 py-1 rounded-full" :class="isOverLimit ? 'bg-red-100 text-red-700' : 'bg-emerald-100 text-[#0e622b]'">
                        Maksimal: 5 Foto &bull; Total Maks. 2 MB
                    </span>
                </div>

                <div class="border-2 border-dashed rounded-2xl p-6 sm:p-8 flex flex-col items-center justify-center text-center transition"
                     :class="isOverLimit ? 'border-red-300 bg-red-50/50' : 'border-[#bbf7d0] hover:border-[#86efac] bg-[#fafdfa]'"
                     @dragover.prevent
                     @drop.prevent="handleDrop($event)">
                    
                    <div class="w-14 h-14 rounded-full flex items-center justify-center mb-3 transition"
                         :class="isOverLimit ? 'bg-red-100 text-red-600' : 'bg-[#dcfce7] text-[#0e622b]'">
                         <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                        </svg>
                    </div>
                    
                    <span class="font-bold text-gray-800 text-base mb-1">Pilih atau Tarik Foto Dokumentasi</span>
                    <span class="text-xs text-gray-500 mb-4 max-w-md">
                        Maksimal dapat memilih hingga <strong>5 foto</strong> dengan total ukuran maksimal <strong>2 MB</strong> (format .png, .jpg, .jpeg, .webp). Foto otomatis dioptimalkan sebelum diunggah ke Google Drive.
                    </span>

                    <input type="file" id="dokumentasi" name="dokumentasi[]" multiple accept="image/png, image/jpeg, image/jpg, image/webp" class="hidden" @change="handleFileChange($event)" :disabled="files.length >= 5">
                    
                    <template x-if="files.length < 5">
                        <label for="dokumentasi" class="cursor-pointer inline-flex items-center justify-center gap-2 px-6 py-2.5 bg-[#0e622b] hover:bg-[#0b4d22] text-white text-xs font-semibold rounded-full shadow-sm transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                            <span x-text="files.length > 0 ? 'Tambah Foto (' + (5 - files.length) + ' tersisa)' : 'Pilih Foto (Maks. 5)'"></span>
                        </label>
                    </template>

                    <template x-if="files.length >= 5">
                        <span class="inline-flex items-center gap-1.5 px-4 py-2 bg-emerald-50 border border-emerald-200 text-[#0e622b] text-xs font-semibold rounded-full">
                            <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            Sudah mencapai kuota maksimal (5 foto)
                        </span>
                    </template>

                    <!-- COMPRESSING LOADER -->
                    <template x-if="compressing">
                        <div class="mt-4 flex items-center justify-center gap-2 text-xs font-semibold text-emerald-700 bg-emerald-50 px-4 py-2 rounded-xl border border-emerald-200">
                            <svg class="animate-spin h-4 w-4 text-emerald-600" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                            </svg>
                            <span>Mengoptimalkan ukuran gambar...</span>
                        </div>
                    </template>

                    <!-- LIMIT WARNING NOTICE -->
                    <template x-if="limitMessage">
                        <div class="mt-4 w-full p-3 bg-amber-50 border border-amber-300 rounded-xl text-amber-800 text-xs font-semibold flex items-center justify-center gap-2">
                            <svg class="w-4 h-4 shrink-0 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                            <span x-text="limitMessage"></span>
                        </div>
                    </template>

                    <!-- WARNING OVER LIMIT -->
                    <template x-if="isOverLimit">
                        <div class="mt-4 w-full p-3 bg-red-100 border border-red-300 rounded-xl text-red-700 text-xs font-semibold flex items-center justify-center gap-2">
                            <svg class="w-4 h-4 shrink-0 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <span x-text="files.length > maxFiles ? 'Total foto yang dipilih melebihi batas maksimal 5 foto!' : 'Total ukuran foto yang dipilih (' + getTotalSizeFormatted() + ') melebihi batas maksimal 2 MB! Hapus beberapa foto untuk melanjutkan.'"></span>
                        </div>
                    </template>

                    <!-- SELECTED FILES LIST & PREVIEWS -->
                    <template x-if="files.length > 0">
                        <div class="mt-6 w-full text-left">
                            <div class="flex items-center justify-between text-xs font-bold text-gray-700 mb-3 px-1">
                                <span>Foto Terpilih (<span x-text="files.length"></span>/5 foto)</span>
                                <span :class="isOverLimit ? 'text-red-600 font-bold' : 'text-gray-500'">
                                    Total Ukuran: <span x-text="getTotalSizeFormatted()"></span> / 2.00 MB
                                </span>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3">
                                <template x-for="(item, idx) in files" :key="idx">
                                    <div class="relative flex items-center gap-3 p-2.5 bg-white rounded-xl border border-gray-200 shadow-xs group">
                                        <div class="w-12 h-12 rounded-lg overflow-hidden bg-gray-100 shrink-0 border border-gray-100">
                                            <img :src="item.previewUrl" :alt="item.name" class="w-full h-full object-cover">
                                        </div>
                                        <div class="overflow-hidden flex-1 min-w-0 pr-6">
                                            <p class="text-xs font-semibold text-gray-800 truncate" x-text="item.name"></p>
                                            <p class="text-[11px] text-gray-400 font-medium mt-0.5" x-text="item.sizeFormatted"></p>
                                        </div>
                                        <button type="button" @click="removeFile(idx)" class="absolute top-2 right-2 w-6 h-6 rounded-full bg-gray-100 hover:bg-red-50 text-gray-400 hover:text-red-600 flex items-center justify-center transition" title="Hapus foto ini">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                                        </button>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </template>

                </div>
            </div>

            <!-- ACTION BUTTONS -->
            <div class="flex items-center justify-end gap-4 pt-2">
                <a href="{{ route('laporan-mingguan.index') }}" class="px-8 py-2.5 bg-white border border-[#0e622b] text-[#0e622b] hover:bg-emerald-50 font-bold text-sm rounded-full transition min-w-[110px] text-center">
                    Batal
                </a>
                <button type="submit" :disabled="isOverLimit || compressing || isSubmitting" :class="isOverLimit || compressing || isSubmitting ? 'opacity-50 cursor-not-allowed bg-gray-400' : 'bg-[#0e622b] hover:bg-[#0b4d22] text-white'" class="inline-flex items-center justify-center gap-2 px-8 py-2.5 font-bold text-sm rounded-full shadow-sm transition min-w-[140px] text-center">
                    <svg x-show="isSubmitting" class="animate-spin -ml-1 mr-1.5 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                    </svg>
                    <span x-text="isSubmitting ? 'Menyimpan...' : 'Buat'"></span>
                </button>
            </div>
        </form>
    </div>

</x-dashboard-layout>