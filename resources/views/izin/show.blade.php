<x-dashboard-layout :title="'Detail Permohonan Izin'">

    <!-- Breadcrumb -->
    <div class="mb-6 flex items-center gap-2 text-sm font-medium">
        <a href="{{ route('izin.index') }}" class="text-gray-600 hover:text-[#0b602b] transition flex items-center gap-1">
            <svg class="w-4 h-4 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            <span>Permohonan Izin</span>
        </a>
        <span class="text-gray-300">/</span>
        <span class="font-bold text-[#0b602b]">
            Detail Izin
        </span>
    </div>

    @if (session('success'))
        <div class="mb-6 p-4 bg-emerald-50 text-emerald-800 rounded-xl border border-emerald-200 flex items-center justify-between text-sm shadow-sm">
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
        <div class="mb-6 p-4 bg-red-50 text-red-800 rounded-xl border border-red-200 text-sm shadow-sm">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Main Card -->
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 sm:p-8">

        <!-- Header: Judul Permohonan, Pemohon, & Status Badge (Kiri - Kanan) -->
        <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-4 pb-6 border-b border-gray-200">
            <div>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 tracking-tight">
                    Permohonan Izin <span class="text-[#0b602b]">{{ ucfirst(str_replace('_', ' ', $izin->jenis)) }}</span>
                </h1>
                <p class="mt-1.5 text-sm text-gray-500">
                    Diajukan oleh <span class="font-bold text-gray-900">{{ $izin->pegawai->nama ?? '-' }}</span>
                    @if(!empty($izin->pegawai->divisi->nama_divisi))
                        <span class="text-gray-400">• {{ $izin->pegawai->divisi->nama_divisi }}</span>
                    @endif
                </p>
            </div>

            <!-- Status Badge -->
            <div class="shrink-0">
                @if($izin->status === 'disetujui')
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-[#dcfce7] text-[#0b602b] text-[10px] font-extrabold uppercase tracking-wider">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>DISETUJUI</span>
                    </span>
                @elseif($izin->status === 'disetujui_atasan')
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-[#e0f2fe] text-[#0369a1] text-[10px] font-extrabold uppercase tracking-wider">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>DISETUJUI ATASAN</span>
                    </span>
                @elseif($izin->status === 'ditolak')
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-[#BA1A1A] text-white text-[10px] font-extrabold uppercase tracking-wider shadow-sm">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>DITOLAK</span>
                    </span>
                @else
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-[#fef3c7] text-[#b45309] text-[10px] font-extrabold uppercase tracking-wider">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        <span>MENUNGGU PERSETUJUAN</span>
                    </span>
                @endif
            </div>
        </div>

        <!-- Section Catatan Penolakan (Jika ditolak) -->
        @if ($izin->status === 'ditolak' && $izin->catatan_penolakan)
            <div class="mt-6 p-4 rounded-xl bg-red-50 border border-red-200">
                <div class="flex items-center gap-2 mb-1.5 text-red-800 font-bold text-sm">
                    <svg class="w-4 h-4 text-red-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    <span>Catatan / Alasan Penolakan:</span>
                </div>
                <p class="text-sm text-red-700 leading-relaxed whitespace-pre-line ml-6">
                    {{ $izin->catatan_penolakan }}
                </p>
            </div>
        @endif

        <!-- Section 1: Informasi Izin -->
        <div class="pt-6 pb-2 space-y-6">
            <h3 class="text-base font-bold text-[#0b602b]">Informasi Izin</h3>

            <!-- Grid Tanggal -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <span class="block text-xs font-semibold text-gray-500 mb-1">Tanggal Mulai</span>
                    <p class="text-sm font-semibold text-gray-900">
                        {{ \Carbon\Carbon::parse($izin->tanggal_mulai)->translatedFormat('d F Y') }}
                    </p>
                </div>
                <div>
                    <span class="block text-xs font-semibold text-gray-500 mb-1">Tanggal Selesai</span>
                    <p class="text-sm font-semibold text-gray-900">
                        @php
                            $durasiHari = \Carbon\Carbon::parse($izin->tanggal_mulai)->diffInDays(\Carbon\Carbon::parse($izin->tanggal_selesai)) + 1;
                        @endphp
                        {{ \Carbon\Carbon::parse($izin->tanggal_selesai)->translatedFormat('d F Y') }} <span class="text-gray-500 font-normal">({{ $durasiHari }} Hari)</span>
                    </p>
                </div>
            </div>

            <!-- Grid Alasan & Surat Dokter -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <span class="block text-xs font-semibold text-gray-500 mb-1">Alasan Pengajuan</span>
                    <p class="text-sm text-gray-800 leading-relaxed whitespace-pre-line">{{ trim($izin->keterangan ?: '-') }}</p>
                </div>

                @if ($izin->jenis === 'sakit')
                    <div>
                        <span class="block text-xs font-semibold text-gray-500 mb-1">Surat Dokter</span>
                        <p class="text-sm font-semibold {{ $izin->ada_surat_dokter ? 'text-emerald-700' : 'text-amber-700' }}">
                            {{ $izin->ada_surat_dokter ? 'Dilengkapi dengan surat keterangan dokter' : 'Tanpa surat keterangan dokter' }}
                        </p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Section 2: Lampiran Pendukung -->
        <div class="pt-6">
            <h3 class="text-base font-bold text-[#0b602b] mb-4">Lampiran Pendukung</h3>

            @if ($izin->file_lampiran)
                @php
                    $previewUrl = route('izin.preview', $izin->id);
                    $downloadUrl = route('izin.download', $izin->id);
                    $fileName = basename($izin->file_lampiran);
                    $ext = strtoupper(pathinfo($izin->file_lampiran, PATHINFO_EXTENSION));
                    $isImage = in_array(strtolower($ext), ['jpg', 'jpeg', 'png', 'webp', 'gif']);
                    $isPdf = strtolower($ext) === 'pdf';
                    $fileSizeText = '';
                    if (Storage::disk('public')->exists($izin->file_lampiran)) {
                        $bytes = Storage::disk('public')->size($izin->file_lampiran);
                        if ($bytes >= 1048576) {
                            $fileSizeText = number_format($bytes / 1048576, 1) . ' MB';
                        } elseif ($bytes >= 1024) {
                            $fileSizeText = number_format($bytes / 1024, 0) . ' KB';
                        } else {
                            $fileSizeText = $bytes . ' B';
                        }
                    }
                @endphp

                <!-- Card Info Lampiran -->
                <div class="border border-gray-300 rounded-xl p-4 flex items-center justify-between gap-4 max-w-2xl bg-white hover:border-gray-400 transition shadow-2xs">
                    <a href="{{ $previewUrl }}" target="_blank" class="flex items-center gap-3.5 min-w-0 flex-1 group" title="Buka Pratinjau Dokumen">
                        <div class="w-10 h-10 rounded-lg {{ $isPdf ? 'bg-red-50 text-red-600 group-hover:bg-red-600' : 'bg-emerald-50 text-[#0b602b] group-hover:bg-[#0b602b]' }} flex items-center justify-center shrink-0 group-hover:text-white transition">
                            @if ($isPdf)
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 15h-2v-6h2v6zm4 0h-2v-4h2v4zm-2-8a1 1 0 110-2 1 1 0 010 2z"/>
                                </svg>
                            @else
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            @endif
                        </div>
                        <div class="min-w-0">
                            <p class="text-sm font-bold text-gray-900 truncate group-hover:text-[#0b602b] transition" title="{{ $fileName }}">
                                {{ $fileName }}
                            </p>
                            <p class="text-xs text-gray-500 mt-0.5">
                                {{ $ext }} {{ $fileSizeText ? '• ' . $fileSizeText : '' }}
                            </p>
                        </div>
                    </a>

                    <div class="flex items-center gap-1.5 shrink-0">
                        <a href="{{ $previewUrl }}" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-emerald-700 bg-emerald-50 hover:bg-emerald-100 rounded-lg transition" title="Buka Pratinjau di Tab Baru">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
                            </svg>
                            <span>Pratinjau</span>
                        </a>

                        <a href="{{ $downloadUrl }}" class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-semibold text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition" title="Unduh Dokumen Lampiran">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                            </svg>
                            <span>Unduh</span>
                        </a>
                    </div>
                </div>

                <!-- Pratinjau Langsung di Halaman -->
                @if ($isPdf)
                    <div class="mt-5 rounded-2xl overflow-hidden border border-gray-200 bg-white shadow-xs max-w-4xl">
                        <div class="px-4 py-3 bg-gray-50 border-b border-gray-200 flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <span class="inline-flex items-center justify-center px-2 py-0.5 rounded bg-red-100 text-red-700 text-[11px] font-bold">PDF</span>
                                <span class="text-xs font-bold text-gray-700 uppercase tracking-wider">Pratinjau Dokumen Lampiran</span>
                            </div>
                            <div class="flex items-center gap-3">
                                <a href="{{ $previewUrl }}" target="_blank" class="text-xs text-emerald-700 hover:text-emerald-900 font-semibold inline-flex items-center gap-1 hover:underline">
                                    <span>Buka Layar Penuh</span>
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                </a>
                            </div>
                        </div>
                        <iframe src="{{ $previewUrl }}" class="w-full h-[650px] border-0" title="Pratinjau Dokumen PDF"></iframe>
                    </div>
                @elseif ($isImage)
                    <div class="mt-5 max-w-2xl rounded-2xl overflow-hidden border border-gray-200 bg-white shadow-xs">
                        <div class="px-4 py-3 bg-gray-50 border-b border-gray-200 flex items-center justify-between">
                            <span class="text-xs font-bold text-gray-700 uppercase tracking-wider flex items-center gap-2">
                                <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                Pratinjau Gambar Lampiran
                            </span>
                            <a href="{{ $previewUrl }}" target="_blank" class="text-xs text-emerald-700 hover:text-emerald-900 font-semibold inline-flex items-center gap-1 hover:underline">
                                <span>Lihat Ukuran Penuh</span>
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                            </a>
                        </div>
                        <div class="p-4 flex justify-center bg-gray-50/50">
                            <img src="{{ $previewUrl }}" alt="Pratinjau Lampiran" class="max-h-[500px] w-auto object-contain rounded-xl shadow-xs border border-gray-200">
                        </div>
                    </div>
                @endif
            @else
                <p class="text-sm text-gray-400 italic">Tidak ada lampiran pendukung yang diunggah</p>
            @endif
        </div>

        @php $role = auth()->user()->role; @endphp

        <!-- Tombol Aksi Persetujuan (Di atas garis) -->
        @if (($role === 'admin' && $izin->status === 'disetujui_atasan') || ($role === 'atasan' && $izin->status === 'pending'))
            <div class="mt-8 flex items-center justify-end gap-3 flex-wrap">
                @if ($role === 'admin' && $izin->status === 'disetujui_atasan')
                    <button type="button" onclick="openRejectIzinModal({ url: '{{ route('izin.reject-admin', $izin->id) }}', nama: '{{ addslashes($izin->pegawai->nama ?? 'pegawai ini') }}' })"
                        class="inline-flex items-center gap-2 px-6 py-2.5 rounded-full bg-[#BA1A1A] hover:bg-[#93000a] text-white font-bold text-sm transition shadow-sm cursor-pointer">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>Tolak Izin</span>
                    </button>

                    <form action="{{ route('izin.verifikasi', $izin->id) }}" method="POST" class="confirm-form" data-title="Verifikasi Izin" data-text="Apakah Anda yakin ingin memverifikasi permohonan izin dari {{ $izin->pegawai->nama ?? 'pegawai ini' }}?" data-icon="question" data-confirm-text="Ya, Verifikasi" data-confirm-color="#0b602b">
                        @csrf @method('PATCH')
                        <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 rounded-full bg-[#0b602b] hover:bg-[#084d22] text-white font-bold text-sm transition shadow-sm cursor-pointer">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span>Setujui Izin</span>
                        </button>
                    </form>
                @elseif ($role === 'atasan' && $izin->status === 'pending')
                    <button type="button" onclick="openRejectIzinModal({ url: '{{ route('izin.reject-atasan', $izin->id) }}', nama: '{{ addslashes($izin->pegawai->nama ?? 'pegawai ini') }}' })"
                        class="inline-flex items-center gap-2 px-6 py-2.5 rounded-full bg-[#BA1A1A] hover:bg-[#93000a] text-white font-bold text-sm transition shadow-sm cursor-pointer">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>Tolak Izin</span>
                    </button>

                    <form action="{{ route('izin.approve-atasan', $izin->id) }}" method="POST" class="confirm-form" data-title="Setujui Izin" data-text="Apakah Anda yakin ingin menyetujui permohonan izin dari {{ $izin->pegawai->nama ?? 'pegawai ini' }}?" data-icon="question" data-confirm-text="Ya, Setujui" data-confirm-color="#0b602b">
                        @csrf @method('PATCH')
                        <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 rounded-full bg-[#0b602b] hover:bg-[#084d22] text-white font-bold text-sm transition shadow-sm cursor-pointer">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span>Setujui Izin</span>
                        </button>
                    </form>
                @endif
            </div>
        @endif

        <!-- Garis Pemisah & Tombol Kembali (Di bawah garis) -->
        <div class="pt-6 mt-6 border-t border-gray-100 flex items-center justify-end">
            <a href="{{ route('izin.index') }}" class="inline-flex items-center gap-2 rounded-full bg-[#0b602b] hover:bg-[#084d22] px-6 py-2.5 text-sm font-semibold text-white transition shadow-sm hover:shadow">
                <span>Kembali ke Daftar Izin</span>
            </a>
        </div>

    </div>

    <!-- Modal Form Tolak Izin -->
    <div id="modalTolakIzin" class="fixed inset-0 z-50 overflow-y-auto bg-black/50 backdrop-blur-xs flex items-center justify-center p-4 hidden" onclick="handleRejectModalBackdrop(event)">
        <div class="bg-white rounded-2xl shadow-xl border border-gray-200 w-full max-w-lg overflow-hidden transform transition-all" onclick="event.stopPropagation()">
            <!-- Header -->
            <div class="px-6 py-4 bg-gray-50 border-b border-gray-200 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-red-100 text-[#BA1A1A] flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-gray-900">Tolak Permohonan Izin</h3>
                        <p class="text-xs text-gray-500" id="modalRejectSubtitle">Berikan alasan penolakan</p>
                    </div>
                </div>
                <button type="button" onclick="closeRejectIzinModal()" aria-label="Tutup Form Penolakan Izin" class="w-8 h-8 rounded-lg flex items-center justify-center text-gray-500 hover:text-gray-700 hover:bg-gray-100 transition cursor-pointer">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <!-- Form Body -->
            <form id="formTolakIzin" method="POST" action="">
                @csrf
                @method('PATCH')
                <div class="p-6 space-y-4">
                    <div class="p-3 bg-red-50 border border-red-100 rounded-xl text-xs text-red-700 leading-relaxed">
                        Alasan penolakan ini wajib diisi dan akan disampaikan kepada pegawai yang bersangkutan agar mengetahui penyebab permohonan ditolak.
                    </div>

                    <div>
                        <label for="reject_catatan_penolakan" class="block text-sm font-bold text-gray-800 mb-1.5">
                            Alasan / Keterangan Penolakan <span class="text-rose-600">*</span>
                        </label>
                        <textarea id="reject_catatan_penolakan" name="catatan_penolakan" rows="4" required
                            class="block w-full border-gray-300 focus:border-[#0b602b] focus:ring-[#0b602b] rounded-xl shadow-xs text-sm placeholder-gray-400 leading-relaxed"
                            placeholder="Tuliskan alasan penolakan izin secara jelas..."></textarea>
                    </div>
                </div>

                <!-- Footer -->
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex items-center justify-end gap-3">
                    <button type="button" onclick="closeRejectIzinModal()" class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-semibold rounded-full transition shadow-xs cursor-pointer">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2.5 bg-[#BA1A1A] hover:bg-[#93000a] text-white text-sm font-semibold rounded-full shadow-xs transition cursor-pointer">
                        Ya, Tolak
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openRejectIzinModal(options) {
            const modal = document.getElementById('modalTolakIzin');
            const form = document.getElementById('formTolakIzin');
            const subtitle = document.getElementById('modalRejectSubtitle');
            const textarea = document.getElementById('reject_catatan_penolakan');

            form.action = options.url;
            subtitle.innerHTML = `Pegawai: <strong class="text-gray-800">${options.nama || 'Pegawai'}</strong>`;
            textarea.value = '';
            modal.classList.remove('hidden');
            setTimeout(() => textarea.focus(), 100);
        }

        function closeRejectIzinModal() {
            document.getElementById('modalTolakIzin').classList.add('hidden');
        }

        function handleRejectModalBackdrop(e) {
            if (e.target.id === 'modalTolakIzin') {
                closeRejectIzinModal();
            }
        }

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeRejectIzinModal();
            }
        });
    </script>

</x-dashboard-layout>
