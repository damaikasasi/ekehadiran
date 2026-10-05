<x-dashboard-layout :title="'Presensi Work From Home'">

    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-900 tracking-tight">Presensi Work From Home (WFH)</h2>
        <p class="mt-1 text-sm text-gray-500">Lakukan pencatatan presensi masuk dan pulang saat bertugas dari rumah.</p>
    </div>

    @if (session('success'))
        <div class="mb-5 p-4 bg-emerald-50 text-emerald-800 rounded-xl border border-emerald-200 flex items-center justify-between text-sm shadow-sm">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span>{{ session('success') }}</span>
            </div>
            <button type="button" onclick="this.parentElement.remove()" aria-label="Tutup notifikasi" class="text-emerald-500 hover:text-emerald-700">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-5 p-4 bg-rose-50 text-rose-800 rounded-xl border border-rose-200 text-sm shadow-sm">
            <div class="flex items-center gap-2 mb-2 font-bold">
                <svg class="w-5 h-5 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                <span>Gagal Mengirimkan Presensi</span>
            </div>
            <ul class="list-disc pl-5 space-y-1 text-xs">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Stepper Indicator -->
    <div class="w-full mb-6 bg-white p-4 sm:p-5 rounded-2xl border border-gray-200 shadow-sm">
        <div class="flex items-center justify-between text-xs sm:text-sm font-bold">
            <!-- Step 1: Masuk -->
            <div class="flex items-center gap-2 {{ $tahap !== 'masuk' ? 'text-[#0b602b]' : 'text-gray-900' }}">
                <div class="w-7 h-7 sm:w-8 sm:h-8 rounded-full flex items-center justify-center font-black {{ $tahap !== 'masuk' ? 'bg-[#0b602b] text-white shadow-xs' : 'bg-[#0b602b] text-white ring-4 ring-[#0b602b]/15' }}">
                    @if($tahap !== 'masuk')
                        <svg class="w-4 h-4 sm:w-4.5 sm:h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                    @else
                        1
                    @endif
                </div>
                <span>Presensi Masuk</span>
            </div>

            <!-- Line -->
            <div class="flex-1 h-0.5 mx-3 sm:mx-6 {{ $tahap !== 'masuk' ? 'bg-[#0b602b]' : 'bg-gray-200' }}"></div>

            <!-- Step 2: Pulang -->
            <div class="flex items-center gap-2 {{ $tahap === 'selesai' ? 'text-[#0b602b]' : ($tahap === 'pulang' ? 'text-[#0b602b]' : 'text-gray-500') }}">
                <div class="w-7 h-7 sm:w-8 sm:h-8 rounded-full flex items-center justify-center font-black {{ $tahap === 'selesai' ? 'bg-[#0b602b] text-white shadow-xs' : ($tahap === 'pulang' ? 'bg-[#0b602b] text-white ring-4 ring-[#0b602b]/15' : 'bg-gray-100 text-gray-500') }}">
                    @if($tahap === 'selesai')
                        <svg class="w-4 h-4 sm:w-4.5 sm:h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                    @else
                        2
                    @endif
                </div>
                <span>Presensi Pulang</span>
            </div>
        </div>
    </div>

    @if ($isFormDisabled)
        <!-- Form Disabled by Admin -->
        <div class="w-full bg-white rounded-2xl border border-gray-200 shadow-sm p-8 text-center">
            <div class="w-14 h-14 mx-auto bg-amber-50 text-amber-600 rounded-full flex items-center justify-center mb-3">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
            <h3 class="text-lg font-bold text-gray-800 mb-1.5">Form Presensi WFH Dinonaktifkan</h3>
            <p class="text-gray-600 text-sm">Pengisian presensi WFH saat ini sedang dinonaktifkan oleh Admin.</p>
        </div>

    @elseif ($tahap === 'selesai')
        <!-- Both In and Out Complete -->
        <div class="w-full bg-white rounded-2xl border border-[#0b602b]/20 shadow-sm p-6 sm:p-8">
            <div class="w-14 h-14 mx-auto bg-[#0b602b] text-white rounded-full flex items-center justify-center mb-4 shadow-sm">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                </svg>
            </div>
            <div class="text-center mb-6">
                <h3 class="text-xl font-black text-gray-900 mb-1">Presensi WFH Hari Ini Lengkap</h3>
                <p class="text-gray-500 text-xs">Seluruh sesi presensi masuk dan pulang Anda telah berhasil tercatat.</p>
            </div>

            <!-- Detail Box -->
            <div class="grid grid-cols-2 gap-3 p-4 bg-gray-50 rounded-xl border border-gray-100 text-xs">
                <div class="p-3 bg-white rounded-lg border border-gray-100">
                    <span class="text-gray-600 block mb-1">Jam Masuk</span>
                    <strong class="text-base text-gray-900 font-black">
                        {{ substr($kehadiranHariIni->jam_masuk, 0, 5) }} WIB
                    </strong>
                    <div class="mt-1">
                        @if($kehadiranHariIni->status === 'terlambat')
                            <span class="text-[10px] font-bold text-amber-700 bg-amber-50 px-2 py-0.5 rounded">Terlambat {{ $kehadiranHariIni->menit_telat }}m</span>
                        @else
                            <span class="text-[10px] font-bold text-[#0b602b] bg-[#e6f4ea] px-2 py-0.5 rounded">Tepat Waktu</span>
                        @endif
                    </div>
                </div>

                <div class="p-3 bg-white rounded-lg border border-gray-100">
                    <span class="text-gray-600 block mb-1">Jam Pulang</span>
                    <strong class="text-base text-gray-900 font-black">
                        {{ substr($kehadiranHariIni->jam_keluar, 0, 5) }} WIB
                    </strong>
                    <div class="mt-1">
                        <span class="text-[10px] font-bold text-[#0b602b] bg-[#e6f4ea] px-2 py-0.5 rounded">Tercatat</span>
                    </div>
                </div>
            </div>

            <div class="mt-6 text-center">
                <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-[#0b602b] hover:bg-[#084d22] text-white text-xs font-bold transition shadow-sm">
                    Kembali ke Dashboard
                </a>
            </div>
        </div>

    @elseif ($tahap === 'pulang')
        <!-- Step 2: Form Absen Pulang -->
        <div class="w-full bg-white rounded-2xl border border-gray-200 shadow-sm p-6 sm:p-8">
            
            <!-- Info Jam Masuk Hari Ini -->
            <div class="mb-5 p-4 rounded-xl bg-[#e6f4ea]/70 border border-[#0b602b]/20 flex items-center justify-between text-xs">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg bg-[#0b602b] text-white flex items-center justify-center shrink-0 shadow-2xs">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    </div>
                    <div>
                        <p class="font-bold text-[#0b602b]">Presensi Masuk Tercatat</p>
                        <p class="text-gray-600 text-[11px]">Pukul {{ substr($kehadiranHariIni->jam_masuk, 0, 5) }} WIB ({{ $kehadiranHariIni->status === 'terlambat' ? 'Terlambat' : 'Tepat Waktu' }})</p>
                    </div>
                </div>
                <span class="px-2.5 py-1 bg-[#0b602b] text-white rounded-md font-bold text-[10px]">Langkah 1 Selesai</span>
            </div>

            <div class="flex items-center justify-between pb-4 mb-4 border-b border-gray-100">
                <div>
                    <h3 class="text-lg font-bold text-gray-900">Presensi Pulang WFH</h3>
                    <p class="text-xs text-gray-500">Jam Pulang Standar: 16.00 WIB</p>
                </div>
                <span class="px-3 py-1 text-xs font-bold rounded-full bg-blue-50 text-blue-700 border border-blue-100">
                    Sesi Pulang
                </span>
            </div>

            <form action="{{ route('absen-wfh.pulang') }}" method="POST" enctype="multipart/form-data" id="wfh-pulang-form">
                @csrf

                <div class="mb-5">
                    <x-input-label for="foto" value="Ambil Swafoto Saat Pulang" class="text-sm font-semibold text-gray-800" />
                    <p class="text-xs text-gray-500 mb-2">Unggah swafoto terbaru sebagai bukti selesai jam kerja WFH.</p>
                    <input type="file" name="foto" id="foto" accept="image/*" capture="user" required
                           aria-label="Ambil Swafoto Saat Pulang"
                           class="block mt-1 w-full text-sm border border-gray-300 rounded-xl p-2.5 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    
                    <!-- Preview Container -->
                    <div id="foto-preview-container" class="hidden mt-3 p-3 bg-blue-50/70 border border-blue-200 rounded-xl flex items-center justify-between gap-3">
                        <div class="flex items-center gap-3 min-w-0 flex-1">
                            <img id="foto-preview-img" src="" alt="Preview Swafoto" width="56" height="56" class="w-14 h-14 object-cover rounded-lg border border-blue-200 shadow-2xs">
                            <div class="min-w-0 flex-1 text-xs">
                                <p class="font-bold text-blue-900" id="foto-status-text">✓ Foto siap diunggah</p>
                                <p class="text-blue-600 text-[11px] mt-0.5" id="foto-size-text"></p>
                            </div>
                        </div>
                        <button type="button" onclick="cancelPhotoUpload()" class="p-1.5 text-gray-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition shrink-0 cursor-pointer" title="Batalkan foto yang diunggah" aria-label="Batalkan foto">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>
                    <x-input-error :messages="$errors->get('foto')" class="mt-2" />
                </div>

                <input type="hidden" name="latitude" id="latitude">
                <input type="hidden" name="longitude" id="longitude">

                <div class="mb-6 p-4 rounded-xl bg-gray-50 border border-gray-200 text-xs text-gray-600 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <div class="flex items-center gap-2.5">
                        <div id="lokasi-icon-wrapper" class="w-7 h-7 rounded-lg bg-[#e6f4ea] text-[#0b602b] flex items-center justify-center shrink-0">
                            <svg id="lokasi-icon" class="w-4 h-4 text-[#0b602b] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <div>
                            <p id="lokasi-status" class="font-medium text-gray-700">Mendeteksi lokasi GPS Anda...</p>
                            <p id="lokasi-substatus" class="text-[11px] text-gray-500 mt-0.5">Pastikan GPS aktif & browser diizinkan mengakses lokasi</p>
                        </div>
                    </div>
                    <button type="button" id="retry-location-btn" onclick="detectLocation()" class="inline-flex items-center justify-center gap-1.5 px-3 py-1.5 bg-white hover:bg-gray-100 text-gray-700 text-xs font-semibold rounded-lg border border-gray-200 transition shadow-2xs shrink-0 cursor-pointer">
                        <svg class="w-3.5 h-3.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                        <span>Coba Lagi</span>
                    </button>
                </div>

                <div class="flex items-center gap-4">
                    <button type="submit" id="submit-btn" disabled class="w-full inline-flex items-center justify-center gap-2 px-5 py-3 rounded-xl bg-[#0b602b] text-white text-sm font-semibold hover:bg-[#084d22] transition disabled:opacity-50 disabled:cursor-not-allowed shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                        <span>Kirim Presensi Pulang</span>
                    </button>
                </div>
            </form>
        </div>

    @else
        <!-- Step 1: Form Absen Masuk -->
        <div class="w-full bg-white rounded-2xl border border-gray-200 shadow-sm p-6 sm:p-8">
            <div class="flex items-center justify-between pb-4 mb-4 border-b border-gray-100">
                <div>
                    <h3 class="text-lg font-bold text-gray-900">Presensi Masuk WFH</h3>
                    <p class="text-xs text-gray-500">Jam Masuk Standar: 07.30 WIB</p>
                </div>
                <span class="px-3 py-1 text-xs font-bold rounded-full bg-[#e6f4ea] text-[#0b602b] border border-[#0b602b]/20">
                    Sesi Masuk
                </span>
            </div>

            <!-- Late warning info -->
            @php
                $isLate = now()->greaterThan(now()->copy()->setTime(7, 30, 0));
            @endphp
            @if($isLate)
                <div class="mb-5 p-3.5 rounded-xl bg-amber-50 border border-amber-200 text-xs text-amber-800 flex items-center gap-2">
                    <svg class="w-4 h-4 text-amber-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>Waktu saat ini melewati <strong>07.30 WIB</strong>. Presensi masuk akan tercatat <strong>Terlambat</strong>.</span>
                </div>
            @endif

            <form action="{{ route('absen-wfh.store') }}" method="POST" enctype="multipart/form-data" id="wfh-masuk-form">
                @csrf

                <div class="mb-5">
                    <x-input-label for="foto" value="Ambil Swafoto Saat Masuk" class="text-sm font-semibold text-gray-800" />
                    <p class="text-xs text-gray-500 mb-2">Unggah swafoto wajah Anda sebagai bukti kehadiran masuk WFH.</p>
                    <input type="file" name="foto" id="foto" accept="image/*" capture="user" required
                           aria-label="Ambil Swafoto Saat Masuk"
                           class="block mt-1 w-full text-sm border border-gray-300 rounded-xl p-2.5 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-[#e6f4ea] file:text-[#0b602b] hover:file:bg-[#dcfce7]">
                    
                    <!-- Preview Container -->
                    <div id="foto-preview-container" class="hidden mt-3 p-3 bg-[#e6f4ea]/70 border border-[#0b602b]/20 rounded-xl flex items-center justify-between gap-3">
                        <div class="flex items-center gap-3 min-w-0 flex-1">
                            <img id="foto-preview-img" src="" alt="Preview Swafoto" width="56" height="56" class="w-14 h-14 object-cover rounded-lg border border-[#0b602b]/20 shadow-2xs">
                            <div class="min-w-0 flex-1 text-xs">
                                <p class="font-bold text-[#0b602b]" id="foto-status-text">✓ Foto siap diunggah</p>
                                <p class="text-[#0b602b]/80 text-[11px] mt-0.5" id="foto-size-text"></p>
                            </div>
                        </div>
                        <button type="button" onclick="cancelPhotoUpload()" class="p-1.5 text-gray-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition shrink-0 cursor-pointer" title="Batalkan foto yang diunggah" aria-label="Batalkan foto">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>
                    <x-input-error :messages="$errors->get('foto')" class="mt-2" />
                </div>

                <input type="hidden" name="latitude" id="latitude">
                <input type="hidden" name="longitude" id="longitude">

                <div class="mb-6 p-4 rounded-xl bg-gray-50 border border-gray-200 text-xs text-gray-600 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <div class="flex items-center gap-2.5">
                        <div id="lokasi-icon-wrapper" class="w-7 h-7 rounded-lg bg-[#e6f4ea] text-[#0b602b] flex items-center justify-center shrink-0">
                            <svg id="lokasi-icon" class="w-4 h-4 text-[#0b602b] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <div>
                            <p id="lokasi-status" class="font-medium text-gray-700">Mendeteksi lokasi GPS Anda...</p>
                            <p id="lokasi-substatus" class="text-[11px] text-gray-500 mt-0.5">Pastikan GPS aktif & browser diizinkan mengakses lokasi</p>
                        </div>
                    </div>
                    <button type="button" id="retry-location-btn" onclick="detectLocation()" class="inline-flex items-center justify-center gap-1.5 px-3 py-1.5 bg-white hover:bg-gray-100 text-gray-700 text-xs font-semibold rounded-lg border border-gray-200 transition shadow-2xs shrink-0 cursor-pointer">
                        <svg class="w-3.5 h-3.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                        <span>Coba Lagi</span>
                    </button>
                </div>

                <div class="flex items-center gap-4">
                    <button type="submit" id="submit-btn" disabled class="w-full inline-flex items-center justify-center gap-2 px-5 py-3 rounded-xl bg-[#0b602b] text-white text-sm font-semibold hover:bg-[#084d22] transition disabled:opacity-50 disabled:cursor-not-allowed shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
                        <span>Kirim Presensi Masuk</span>
                    </button>
                </div>
            </form>
        </div>
    @endif

    <script>
        function cancelPhotoUpload() {
            const fotoInput = document.getElementById('foto');
            const previewContainer = document.getElementById('foto-preview-container');
            const previewImg = document.getElementById('foto-preview-img');
            const statusText = document.getElementById('foto-status-text');
            const sizeText = document.getElementById('foto-size-text');

            if (fotoInput) {
                fotoInput.value = '';
                try {
                    fotoInput.files = (new DataTransfer()).files;
                } catch (e) {}
            }
            if (previewImg) {
                previewImg.src = '';
            }
            if (statusText) {
                statusText.textContent = '';
            }
            if (sizeText) {
                sizeText.textContent = '';
            }
            if (previewContainer) {
                previewContainer.classList.add('hidden');
            }
        }

        function compressAndPreviewImage(inputElement) {
            const file = inputElement.files ? inputElement.files[0] : null;
            if (!file) {
                cancelPhotoUpload();
                return;
            }

            const previewContainer = document.getElementById('foto-preview-container');
            const previewImg = document.getElementById('foto-preview-img');
            const statusText = document.getElementById('foto-status-text');
            const sizeText = document.getElementById('foto-size-text');

            if (previewContainer) {
                previewContainer.classList.remove('hidden');
                if (statusText) statusText.innerHTML = '<span class="animate-pulse">Mengoptimalkan & mengompres foto...</span>';
                if (sizeText) sizeText.textContent = 'Ukuran asli: ' + (file.size / 1024 / 1024).toFixed(2) + ' MB';
            }

            const reader = new FileReader();
            reader.onload = function(e) {
                const img = new Image();
                img.onload = function() {
                    const maxDim = 1080;
                    let width = img.width;
                    let height = img.height;

                    if (width > maxDim || height > maxDim) {
                        if (width > height) {
                            height = Math.round((height * maxDim) / width);
                            width = maxDim;
                        } else {
                            width = Math.round((width * maxDim) / height);
                            height = maxDim;
                        }
                    }

                    const canvas = document.createElement('canvas');
                    canvas.width = width;
                    canvas.height = height;
                    const ctx = canvas.getContext('2d');
                    ctx.drawImage(img, 0, 0, width, height);

                    // Function to compress with target max 500KB (480KB safety limit)
                    function getCompressedBlob(quality) {
                        return new Promise((resolve) => {
                            canvas.toBlob((blob) => {
                                if (blob && blob.size > 480 * 1024 && quality > 0.4) {
                                    // Step down quality if exceeding 480KB
                                    resolve(getCompressedBlob(quality - 0.1));
                                } else {
                                    resolve({ blob, quality });
                                }
                            }, 'image/jpeg', quality);
                        });
                    }

                    getCompressedBlob(0.80).then(({ blob, quality }) => {
                        // Check if user already cancelled upload while compressing
                        if (!inputElement.value && (!inputElement.files || inputElement.files.length === 0)) {
                            return;
                        }

                        if (!blob) {
                            if (previewImg) previewImg.src = e.target.result;
                            return;
                        }

                        // Create new compressed File object
                        const compressedFile = new File([blob], (file.name ? file.name.replace(/\.[^/.]+$/, "") : "swafoto") + ".jpg", {
                            type: 'image/jpeg',
                            lastModified: Date.now()
                        });

                        // Replace input files with compressed file
                        try {
                            const dt = new DataTransfer();
                            dt.items.add(compressedFile);
                            inputElement.files = dt.files;
                        } catch (err) {
                            console.warn('DataTransfer not supported, proceeding with original file', err);
                        }

                        // Update preview UI
                        if (previewImg) previewImg.src = canvas.toDataURL('image/jpeg', quality);
                        if (statusText) statusText.textContent = '✓ Foto siap diunggah (Maks. 500 KB)';
                        if (sizeText) {
                            const originalMB = (file.size / 1024 / 1024).toFixed(2);
                            const compressedKB = Math.round(compressedFile.size / 1024);
                            sizeText.textContent = `Asli: ${originalMB} MB → Terkompresi: ${compressedKB} KB`;
                        }
                    });
                };
                img.src = e.target.result;
            };
            reader.readAsDataURL(file);
        }

        function updateLocationUI(status, message, isSuccess, isWarning = false) {
            const statusEl = document.getElementById('lokasi-status');
            const subStatusEl = document.getElementById('lokasi-substatus');
            const iconWrapper = document.getElementById('lokasi-icon-wrapper');
            const btn = document.getElementById('submit-btn');

            if (statusEl) statusEl.innerHTML = status;
            if (subStatusEl) subStatusEl.textContent = message;

            if (iconWrapper) {
                if (isSuccess) {
                    iconWrapper.className = 'w-7 h-7 rounded-lg bg-[#e6f4ea] text-[#0b602b] flex items-center justify-center shrink-0';
                } else if (isWarning) {
                    iconWrapper.className = 'w-7 h-7 rounded-lg bg-amber-100 text-amber-700 flex items-center justify-center shrink-0';
                } else {
                    iconWrapper.className = 'w-7 h-7 rounded-lg bg-rose-100 text-rose-700 flex items-center justify-center shrink-0';
                }
            }

            if (btn) {
                if (isSuccess) {
                    btn.removeAttribute('disabled');
                } else {
                    btn.setAttribute('disabled', 'disabled');
                }
            }
        }

        function handleLocationSuccess(position) {
            const latInput = document.getElementById('latitude');
            const lngInput = document.getElementById('longitude');
            if (latInput && lngInput) {
                latInput.value = position.coords.latitude;
                lngInput.value = position.coords.longitude;
            }

            const latFormatted = position.coords.latitude.toFixed(5);
            const lngFormatted = position.coords.longitude.toFixed(5);
            const acc = position.coords.accuracy ? Math.round(position.coords.accuracy) + 'm' : '';

            updateLocationUI(
                'Lokasi GPS terdeteksi: <strong class="text-gray-900">' + latFormatted + ', ' + lngFormatted + '</strong>',
                'Akurasi: ~' + acc + ' • Siap mengirim presensi',
                true
            );
        }

        function detectLocation() {
            // Check Secure Context (HTTPS or localhost)
            const isLocal = ['localhost', '127.0.0.1'].includes(window.location.hostname);
            if (!window.isSecureContext && !isLocal) {
                updateLocationUI(
                    '<span class="text-rose-600 font-bold">Koneksi Tidak Aman (HTTP)</span>',
                    'Browser HP memblokir GPS pada HTTP. Gunakan HTTPS atau akses melalui URL domain aman.',
                    false
                );
                return;
            }

            if (!navigator.geolocation) {
                updateLocationUI(
                    '<span class="text-rose-600 font-bold">Fitur Tidak Didukung</span>',
                    'Browser HP Anda tidak mendukung geolokasi GPS.',
                    false
                );
                return;
            }

            updateLocationUI(
                '<span class="text-gray-700 font-semibold animate-pulse">Mencari titik koordinat GPS...</span>',
                'Mohon tunggu sebentar...',
                false,
                true
            );

            // 1st attempt: High Accuracy GPS
            navigator.geolocation.getCurrentPosition(
                function(position) {
                    handleLocationSuccess(position);
                },
                function(error) {
                    // Fallback to low accuracy (WiFi / Cell positioning)
                    navigator.geolocation.getCurrentPosition(
                        function(position) {
                            handleLocationSuccess(position);
                        },
                        function(err) {
                            let errMsg = 'Gagal mendeteksi lokasi.';
                            let subMsg = 'Pastikan GPS HP aktif dan izin lokasi diizinkan pada browser.';

                            if (err.code === err.PERMISSION_DENIED) {
                                errMsg = '<span class="text-rose-600 font-bold">Akses Lokasi Ditolak</span>';
                                subMsg = 'Klik ikon gembok/pengaturan pada URL browser HP Anda dan aktifkan izin "Lokasi", lalu klik "Coba Lagi".';
                            } else if (err.code === err.POSITION_UNAVAILABLE) {
                                errMsg = '<span class="text-rose-600 font-bold">Sinyal GPS Tidak Tersedia</span>';
                                subMsg = 'Aktifkan GPS/Lokasi perangkat Anda dan pastikan berada di area dengan sinyal baik.';
                            } else if (err.code === err.TIMEOUT) {
                                errMsg = '<span class="text-rose-600 font-bold">Waktu Pencarian GPS Habis</span>';
                                subMsg = 'Koneksi GPS lambat. Silakan klik "Coba Lagi".';
                            }

                            updateLocationUI(errMsg, subMsg, false);
                        },
                        {
                            enableHighAccuracy: false,
                            timeout: 15000,
                            maximumAge: 300000
                        }
                    );
                },
                {
                    enableHighAccuracy: true,
                    timeout: 10000,
                    maximumAge: 30000
                }
            );
        }

        document.addEventListener('DOMContentLoaded', () => {
            detectLocation();

            const fotoInput = document.getElementById('foto');
            if (fotoInput) {
                fotoInput.addEventListener('change', function() {
                    compressAndPreviewImage(this);
                });
            }

            const forms = document.querySelectorAll('form');
            forms.forEach(f => {
                f.addEventListener('submit', function(e) {
                    const btn = document.getElementById('submit-btn');
                    if (btn) {
                        btn.setAttribute('disabled', 'disabled');
                        btn.innerHTML = `
                            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                            </svg>
                            <span>Mengirimkan Presensi...</span>
                        `;
                    }
                });
            });
        });
    </script>

</x-dashboard-layout>