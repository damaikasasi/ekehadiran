<x-dashboard-layout :title="'Detail Laporan Kinerja'">

    <!-- BREADCRUMB -->
    <div class="flex items-center gap-2 text-sm font-medium text-gray-500 mb-6">
        <a href="{{ route('laporan-mingguan.index') }}" class="hover:text-green-700 transition flex items-center gap-1 text-gray-600">
            <svg class="w-4 h-4 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            <span>Laporan Bulanan</span>
        </a>
        <span class="text-gray-300">/</span>
        <span class="text-[#0e622b] font-bold">Detail Laporan</span>
    </div>

    @if (session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const showPopup = () => {
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: {!! json_encode(session('success')) !!},
                            confirmButtonColor: '#0b602b',
                            confirmButtonText: 'Selesai',
                            customClass: {
                                popup: 'rounded-2xl shadow-xl',
                                confirmButton: 'rounded-full px-6 py-2.5 font-bold text-sm'
                            }
                        });
                    } else {
                        setTimeout(showPopup, 50);
                    }
                };
                showPopup();
            });
        </script>
    @endif

    <!-- NOTIFIKASI PERBAIKAN UNTUK USER JIKA NILAI DI BAWAH EKSPEKTASI / DITOLAK -->
    @if(auth()->user()->role === 'user' && auth()->user()->pegawai_id === $laporan->pegawai_id && ($laporan->nilai === 'dibawah_ekspektasi' || $laporan->status === 'ditolak'))
        <div class="mb-6 p-5 bg-white border border-gray-200 rounded-2xl text-amber-900 shadow-sm flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div class="flex items-start gap-3">
                <div class="w-10 h-10 rounded-xl bg-amber-50 flex items-center justify-center text-amber-600 shrink-0 mt-0.5 border border-amber-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                </div>
                <div>
                    <h4 class="font-bold text-base text-amber-900">Laporan Memerlukan Perbaikan</h4>
                    <p class="text-xs text-amber-800 mt-0.5 font-medium leading-relaxed">
                        Atasan telah memberikan penilaian dan catatan pada laporan Anda. Anda dapat mengedit laporan untuk memperbaiki rincian kegiatan dan bukti dokumentasi.
                    </p>
                </div>
            </div>
            <a href="{{ route('laporan-mingguan.edit', $laporan->id) }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-amber-600 hover:bg-amber-700 text-white font-bold text-xs sm:text-sm rounded-full transition shadow-sm shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                <span>Perbaiki Laporan Sekarang</span>
            </a>
        </div>
    @endif

    <!-- PROFILE HEADER CARD -->
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 mb-8 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
        <div class="flex items-center gap-5">
            <!-- Profile Photo -->
            <div class="w-16 h-16 rounded-2xl overflow-hidden bg-gray-100 border border-gray-200 shrink-0 flex items-center justify-center">
                @if($laporan->pegawai->foto)
                    <img src="{{ asset('storage/' . $laporan->pegawai->foto) }}" width="64" height="64" loading="lazy" decoding="async" alt="Foto Pegawai" class="w-full h-full object-cover">
                @else
                    <div class="w-full h-full flex items-center justify-center bg-green-50 text-green-700 font-extrabold text-2xl">
                        {{ strtoupper(substr($laporan->pegawai->nama ?? 'P', 0, 1)) }}
                    </div>
                @endif
            </div>
            
            <div class="space-y-1.5">
                <h3 class="text-xl font-bold text-gray-900 leading-snug">{{ $laporan->pegawai->nama ?? '-' }}</h3>
                <p class="text-sm font-medium text-gray-500">NIP: {{ $laporan->pegawai->user?->nip ?? '-' }}</p>
                <div class="flex flex-wrap gap-2.5 items-center pt-1">
                    <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-gray-600 bg-gray-50 border border-gray-200 px-3 py-1 rounded-full">
                        <svg class="w-3.5 h-3.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        {{ \Carbon\Carbon::create()->month($laporan->bulan)->translatedFormat('F') }} {{ $laporan->tahun }}
                    </span>
                    @if($laporan->status === 'menunggu')
                        <span class="inline-flex items-center whitespace-nowrap bg-red-50 text-red-600 border border-red-200 px-2.5 py-1 rounded-full text-[10px] font-extrabold uppercase tracking-wider">
                            BELUM DIREVIEW
                        </span>
                    @else
                        <span class="inline-flex items-center whitespace-nowrap bg-green-50 text-green-700 border border-green-200 px-2.5 py-1 rounded-full text-[10px] font-extrabold uppercase tracking-wider">
                            SUDAH DIREVIEW
                        </span>
                    @endif
                </div>
            </div>
        </div>

        <div class="text-left md:text-right border-t md:border-t-0 border-gray-100 pt-4 md:pt-0 w-full md:w-auto space-y-1">
            <span class="text-[10px] font-bold uppercase tracking-wider text-gray-400 block">TANGGAL PENGIRIMAN</span>
            <span class="text-base font-bold text-gray-800 block">
                {{ $laporan->created_at ? $laporan->created_at->translatedFormat('d M Y') : '-' }}
            </span>
            <span class="text-xs text-gray-500 block">
                Pukul {{ $laporan->created_at ? $laporan->created_at->translatedFormat('H:i') : '-' }} WIB
            </span>
        </div>
    </div>

    <!-- MAIN DETAILS -->
    <div class="space-y-8 mb-8">
        
        <!-- CAPAIAN KERJA -->
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="px-6 py-4 bg-gray-50/50 border-b border-gray-200 flex items-center gap-3">
                <div class="rounded-lg flex items-center justify-center shrink-0" style="width: 32px; height: 32px; background-color: #f0fdf4; color: #0e622b;">
                    <svg style="width: 20px; height: 20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <h4 class="font-bold text-gray-800 text-base">Capaian Kerja</h4>
            </div>
            <div class="p-6">
                <ul class="space-y-6 relative ml-2.5" style="border-left: 2px solid #e5e7eb; padding-left: 20px;">
                    @forelse($laporan->kegiatanList as $item)
                        @php
                            $parts = explode("\n", trim($item->kegiatan), 2);
                            $title = $parts[0];
                            $desc = $parts[1] ?? '';
                        @endphp
                        <li class="relative">
                            <!-- Bullet Dot -->
                            <div class="absolute top-1.5 rounded-full bg-[#0e622b] border-2 border-white" style="left: -27px; width: 12px; height: 12px; box-shadow: 0 0 0 4px #f0fdf4;"></div>
                            
                            <div>
                                <h5 class="text-sm font-bold text-gray-800 leading-snug">{{ $title }}</h5>
                                @if($desc)
                                    <p class="text-sm text-gray-500 mt-1 whitespace-pre-line leading-relaxed">{{ $desc }}</p>
                                @endif
                            </div>
                        </li>
                    @empty
                        <div class="text-center py-4 text-gray-400 text-sm">Tidak ada rincian kegiatan capaian kerja.</div>
                    @endforelse
                </ul>
            </div>
        </div>

        <!-- LAMPIRAN FILE -->
        @php
            $totalPhotosCount = 0;
            foreach($laporan->kegiatanList as $kItem) {
                $totalPhotosCount += count($kItem->foto_list);
            }
            $attachedFilesCount = ($laporan->file_laporan ? 1 : 0) + $totalPhotosCount;
            $photoCounter = 1;
        @endphp
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="px-6 py-4 bg-gray-50/50 border-b border-gray-200 flex items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <div class="rounded-lg flex items-center justify-center shrink-0" style="width: 32px; height: 32px; background-color: #f0fdf4; color: #0e622b;">
                        <svg style="width: 20px; height: 20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg>
                    </div>
                    <h4 class="font-bold text-gray-800 text-base">Lampiran File</h4>
                </div>
                <span class="text-xs font-semibold text-gray-500">{{ $attachedFilesCount }} File Terlampir</span>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Main PDF Attachment Card -->
                    @if($laporan->file_laporan)
                        <div class="flex justify-between items-center p-4 border border-gray-200 hover:border-green-200 rounded-2xl bg-white transition group">
                            <div class="flex items-center gap-3 overflow-hidden">
                                <div class="rounded-xl flex items-center justify-center shrink-0 font-bold text-xs uppercase" style="width: 40px; height: 40px; background-color: #fef2f2; color: #dc2626;">
                                    PDF
                                </div>
                                <div class="overflow-hidden">
                                    <span class="block text-sm font-bold text-gray-800 truncate" title="laporan-kinerja-{{ Str::slug($laporan->pegawai->nama) }}-{{ \Carbon\Carbon::create()->month($laporan->bulan)->translatedFormat('F') }}-{{ $laporan->tahun }}.pdf">
                                        laporan-kinerja-{{ Str::slug($laporan->pegawai->nama) }}-{{ \Carbon\Carbon::create()->month($laporan->bulan)->translatedFormat('F') }}-{{ $laporan->tahun }}.pdf
                                    </span>
                                    <span class="block text-[11px] text-gray-400 font-medium">Dokumen Utama</span>
                                </div>
                            </div>
                            <div class="flex gap-2 shrink-0">
                                <a href="{{ route('laporan-mingguan.preview', $laporan->id) }}" target="_blank" class="p-2 text-gray-400 hover:text-green-700 hover:bg-green-50 rounded-xl transition" title="Lihat PDF">
                                    <svg style="width: 20px; height: 20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                </a>
                                <a href="{{ route('laporan-mingguan.download', $laporan->id) }}" class="p-2 text-[#BA1A1A] hover:text-[#961313] hover:bg-red-50 rounded-xl transition" title="Unduh PDF">
                                    <svg style="width: 20px; height: 20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                </a>
                            </div>
                        </div>
                    @endif

                    <!-- Photo Attachments from KegiatanList -->
                    @foreach($laporan->kegiatanList as $kegiatanItem)
                        @foreach($kegiatanItem->foto_list as $fotoPath)
                            <div class="flex justify-between items-center p-4 border border-gray-200 hover:border-green-200 rounded-2xl bg-white transition group">
                                <div class="flex items-center gap-3 overflow-hidden">
                                    <div class="rounded-xl flex items-center justify-center shrink-0 font-bold text-xs uppercase overflow-hidden" style="width: 40px; height: 40px;">
                                        <img src="{{ url('/storage-file/' . $fotoPath) }}" alt="Foto Lampiran" class="w-full h-full object-cover">
                                    </div>
                                    <div class="overflow-hidden">
                                        <span class="block text-sm font-bold text-gray-800 truncate" title="Foto Dokumentasi {{ $photoCounter }}">
                                            Foto Dokumentasi {{ $photoCounter }}
                                        </span>
                                        <span class="block text-[11px] text-gray-400 font-medium">Foto Lampiran</span>
                                    </div>
                                </div>
                                <div class="flex gap-2 shrink-0">
                                    <a href="{{ url('/storage-file/' . $fotoPath) }}" target="_blank" class="p-2 text-gray-400 hover:text-green-700 hover:bg-green-50 rounded-xl transition" title="Lihat Foto">
                                        <svg style="width: 20px; height: 20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                    </a>
                                </div>
                            </div>
                            @php $photoCounter++; @endphp
                        @endforeach
                    @endforeach
                </div>

                @if($laporan->status === 'menunggu')
                    <!-- Footer Actions Inside Card -->
                    <div class="pt-6 mt-6 border-t border-gray-100 flex items-center justify-end gap-3 flex-wrap">
                        <a href="{{ route('laporan-mingguan.index') }}" class="inline-flex items-center gap-2 rounded-full bg-[#0b602b] hover:bg-[#084d22] px-6 py-2.5 text-sm font-semibold text-white transition shadow-sm hover:shadow">
                            <span>Kembali ke Daftar Laporan</span>
                        </a>

                        @if(auth()->user()->role === 'atasan')
                            <a href="{{ route('laporan-mingguan.nilai-form', $laporan->id) }}" class="inline-flex items-center gap-2 px-6 py-2.5 rounded-full text-white font-semibold text-sm bg-[#0b602b] hover:bg-[#084d22] shadow-sm hover:shadow transition">
                                <span>Lanjut ke Penilaian</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                            </a>
                        @endif
                    </div>
                @endif
            </div>
        </div>

        <!-- HASIL PENILAIAN LAPORAN -->
        @if($laporan->status !== 'menunggu')
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                <div class="px-6 py-4 bg-gray-50/50 border-b border-gray-200 flex items-center gap-3">
                    <div class="rounded-lg flex items-center justify-center shrink-0" style="width: 32px; height: 32px; background-color: #f0fdf4; color: #0e622b;">
                        <svg style="width: 20px; height: 20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                    </div>
                    <h4 class="font-bold text-gray-800 text-base">Hasil Penilaian Laporan</h4>
                </div>
                <div class="p-6 space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <span class="text-xs font-bold uppercase tracking-wider text-gray-400 block mb-2">KATEGORI PENILAIAN</span>
                            <div>
                                @if($laporan->nilai === 'diatas_ekspektasi')
                                    <span class="inline-flex items-center px-3.5 py-1.5 rounded-full text-xs font-bold bg-indigo-50 text-indigo-700 border border-indigo-200">
                                        Di Atas Ekspektasi
                                    </span>
                                @elseif($laporan->nilai === 'sesuai_ekspektasi')
                                    <span class="inline-flex items-center px-3.5 py-1.5 rounded-full text-xs font-bold bg-blue-50 text-blue-700 border border-blue-200">
                                        Sesuai Ekspektasi
                                    </span>
                                @elseif($laporan->nilai === 'dibawah_ekspektasi')
                                    <span class="inline-flex items-center px-3.5 py-1.5 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                        Di Bawah Ekspektasi
                                    </span>
                                @elseif($laporan->nilai)
                                    <span class="inline-flex items-center px-3.5 py-1.5 rounded-full text-xs font-bold bg-gray-50 text-gray-700 border border-gray-200">
                                        {{ ucwords(str_replace('_', ' ', $laporan->nilai)) }}
                                    </span>
                                @else
                                    <span class="text-xs text-gray-400 font-medium italic">Belum Dinilai</span>
                                @endif
                            </div>
                        </div>
                        <div>
                            <span class="text-xs font-bold uppercase tracking-wider text-gray-400 block mb-2">STATUS KEPUTUSAN</span>
                            <div>
                                @if($laporan->status === 'menunggu')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-extrabold bg-red-50 text-red-600 border border-red-200 uppercase tracking-wider">
                                        <svg class="w-3.5 h-3.5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        BELUM DIREVIEW
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-extrabold bg-green-50 text-green-700 border border-green-200 uppercase tracking-wider">
                                        <svg class="w-3.5 h-3.5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                                        SUDAH DIREVIEW
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div>
                        <span class="text-xs font-bold uppercase tracking-wider text-gray-400 block mb-1.5">CATATAN / FEEDBACK</span>
                        <div class="text-sm font-medium text-gray-700 bg-gray-50 px-4 py-3.5 rounded-xl border border-gray-200 min-h-[90px] whitespace-pre-line leading-relaxed">
                            {{ $laporan->catatan_atasan ?? 'Tidak ada catatan atau feedback yang diberikan.' }}
                        </div>
                    </div>

                    <!-- Footer Actions Inside Card -->
                    <div class="pt-6 border-t border-gray-100 flex items-center justify-end gap-3 flex-wrap">
                        <a href="{{ route('laporan-mingguan.index') }}" class="inline-flex items-center gap-2 rounded-full bg-[#0b602b] hover:bg-[#084d22] px-6 py-2.5 text-sm font-semibold text-white transition shadow-sm hover:shadow">
                            <span>Kembali ke Daftar Laporan</span>
                        </a>

                        @if(auth()->user()->role === 'user' && auth()->user()->pegawai_id === $laporan->pegawai_id)
                            @if($laporan->nilai === 'dibawah_ekspektasi' || $laporan->status === 'ditolak')
                                <a href="{{ route('laporan-mingguan.edit', $laporan->id) }}" class="inline-flex items-center gap-2 px-6 py-2.5 rounded-full text-white font-semibold text-sm bg-[#0e622b] hover:bg-[#0b4d22] shadow-sm hover:shadow transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    <span>Perbaiki Laporan</span>
                                </a>
                            @endif
                        @endif

                        @if(auth()->user()->role === 'atasan')
                            <a href="{{ route('laporan-mingguan.nilai-form', $laporan->id) }}" class="inline-flex items-center gap-2 px-6 py-2.5 rounded-full text-white font-semibold text-sm bg-[#0b602b] hover:bg-[#084d22] shadow-sm hover:shadow transition">
                                <span>Ubah Penilaian</span>
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        @endif

    </div>

</x-dashboard-layout>
