<x-dashboard-layout :title="'Penilaian Laporan'">

    <!-- BREADCRUMB & STATUS BADGE -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
        <div class="text-sm font-medium text-gray-500">
            <a href="{{ route('laporan-mingguan.index') }}" class="hover:text-green-700 transition">Dashboard</a>
            <span class="mx-2 text-gray-300">/</span>
            <a href="{{ route('laporan-mingguan.index') }}" class="hover:text-green-700 transition">Laporan Bulanan</a>
            <span class="mx-2 text-gray-300">/</span>
            <span class="text-[#0e622b] font-bold">Penilaian Laporan</span>
        </div>
        <div>
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

    <!-- PAGE TITLE -->
    <h3 class="text-2xl font-extrabold text-gray-800 mb-8">Formulir Penilaian Laporan</h3>

    @if ($errors->any())
        <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-800 rounded-2xl text-sm flex items-center gap-2.5">
            <svg class="w-5 h-5 text-red-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span>{{ $errors->first('nilai') ?: 'Harap periksa kembali isian formulir penilaian.' }}</span>
        </div>
    @endif

    <form action="{{ route('laporan-mingguan.nilai', $laporan->id) }}" method="POST" id="penilaian-form">
        @csrf @method('PATCH')
        <input type="hidden" name="keputusan" value="terverifikasi">
        <input type="hidden" name="nilai" id="nilai-value" value="{{ old('nilai', $laporan->nilai ?? '') }}" required>

        <!-- TWO COLUMN LAYOUT -->
        <div class="mb-8" style="display: flex; gap: 32px; align-items: start; flex-wrap: wrap;">
            
            <!-- LEFT COLUMN: IDENTITAS LAPORAN (w-1/3) -->
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 space-y-5" style="flex: 1; min-width: 280px;">
                <h4 class="font-extrabold text-base text-gray-800 tracking-tight">Identitas Laporan</h4>
                <hr class="border-gray-150">

                <div class="space-y-4">
                    <div>
                        <span class="block text-[10px] font-bold uppercase tracking-wider text-gray-400">PELAPOR</span>
                        <span class="block text-sm font-bold text-gray-800 mt-0.5">{{ $laporan->pegawai->nama ?? '-' }}</span>
                    </div>

                    <div>
                        <span class="block text-[10px] font-bold uppercase tracking-wider text-gray-400">PERIODE</span>
                        <span class="block text-sm font-semibold text-gray-700 mt-0.5">
                            {{ \Carbon\Carbon::create()->month($laporan->bulan)->translatedFormat('F') }} {{ $laporan->tahun }}
                        </span>
                    </div>

                    @if($laporan->file_laporan)
                        <div>
                            <span class="block text-[10px] font-bold uppercase tracking-wider text-gray-400 mb-2">DOKUMEN LAMPIRAN</span>
                            <a href="{{ route('laporan-mingguan.preview', $laporan->id) }}" target="_blank" class="inline-flex items-center gap-2 text-sm font-medium text-green-700 hover:text-green-800 transition">
                                <svg style="width: 18px; height: 18px;" class="text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                                <span class="underline">laporan.pdf</span>
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- RIGHT COLUMN: PENILAIAN & UMPAN BALIK (w-2/3) -->
            <div class="space-y-8" style="flex: 2; min-width: 480px;">
                <!-- PEMBERIAN NILAI NUMERIK -->
                <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 space-y-6">
                    <h4 class="font-extrabold text-base text-gray-800 tracking-tight">Pemberian Nilai</h4>
                    <hr class="border-gray-150">

                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <label class="block text-sm font-bold text-gray-800">
                                Skala Nilai Keseluruhan <span class="text-red-500">*</span>
                            </label>
                            <span class="text-xs text-red-500 font-semibold" id="nilai-required-badge" style="display: none;">Wajib dipilih</span>
                        </div>
                        <p class="text-xs text-gray-400 font-medium">Berikan penilaian objektif berdasarkan kelengkapan dan akurasi data yang dilaporkan</p>
                        
                        <!-- SEGMENTED BUTTON CONTROL -->
                        <div id="grade-container" style="display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); border: 1px solid #e5e7eb; border-radius: 12px; overflow: hidden; box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05); transition: all 0.2s ease;">
                            <button type="button" onclick="selectGrade(this, 'dibawah_ekspektasi')" class="grade-btn py-3 text-xs font-bold text-gray-700 bg-white border-r border-gray-200 hover:bg-gray-50 transition cursor-pointer" style="border-right: 1px solid #e5e7eb;">
                                Di Bawah<br>Ekspektasi
                            </button>
                            <button type="button" onclick="selectGrade(this, 'sesuai_ekspektasi')" class="grade-btn py-3 text-xs font-bold text-gray-700 bg-white border-r border-gray-200 hover:bg-gray-50 transition cursor-pointer" style="border-right: 1px solid #e5e7eb;">
                                Sesuai<br>Ekspektasi
                            </button>
                            <button type="button" onclick="selectGrade(this, 'diatas_ekspektasi')" class="grade-btn py-3 text-xs font-bold text-gray-700 bg-white hover:bg-gray-50 transition cursor-pointer">
                                Di Atas<br>Ekspektasi
                            </button>
                        </div>

                        <!-- PESAN ERROR INLINE -->
                        <div id="nilai-error-text" class="p-3 bg-red-50 border border-red-200 text-red-700 rounded-xl text-xs font-semibold flex items-center gap-2 mt-2" style="display: none;">
                            <svg class="w-4 h-4 text-red-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span>Skala nilai wajib dipilih sebelum menyimpan penilaian!</span>
                        </div>
                        @error('nilai')
                            <div class="p-3 bg-red-50 border border-red-200 text-red-700 rounded-xl text-xs font-semibold flex items-center gap-2 mt-2">
                                <svg class="w-4 h-4 text-red-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <span>{{ $message }}</span>
                            </div>
                        @enderror
                    </div>
                </div>

                <!-- UMPAN BALIK -->
                <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 space-y-6">
                    <h4 class="font-extrabold text-base text-gray-800 tracking-tight">Umpan Balik (opsional)</h4>
                    <hr class="border-gray-150">

                    <div class="space-y-4">
                        <label for="catatan_atasan" class="block text-sm font-bold text-gray-800">Komentar & Arahan Hasil Laporan</label>
                        <textarea id="catatan_atasan" name="catatan_atasan" rows="5" class="block w-full border-gray-250 focus:border-green-500 focus:ring-green-500 rounded-xl shadow-sm placeholder-gray-400 text-sm" placeholder="Tuliskan catatan Anda mengenai laporan ini. Sertakan poin-poin yang memerlukan perbaikan atau apresiasi terhadap pencapaian tertentu...">{{ old('catatan_atasan', $laporan->catatan_atasan ?? '') }}</textarea>
                    </div>

                    <!-- ALERT BOX -->
                    <div class="bg-gray-50 border-l-4 border-[#0e622b] p-4 text-xs text-gray-500 rounded-r-xl flex items-start gap-2.5">
                        <svg style="width: 16px; height: 16px;" class="text-green-700 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span>Feedback ini akan dipublikasikan langsung ke profil bawahan setelah diverifikasi.</span>
                    </div>

                    <!-- FOOTER ACTIONS -->
                    <div class="flex gap-4 justify-end pt-4 border-t border-gray-100">
                        <a href="{{ route('laporan-mingguan.show', $laporan->id) }}" class="rounded-full transition" style="display: inline-block; padding: 12px 28px !important; border: 1.5px solid #0b602b !important; color: #0b602b !important; background-color: #ffffff !important; text-align: center; text-decoration: none; font-size: 14px; font-weight: 700; border-radius: 9999px; line-height: 1.25;">
                            Batal
                        </a>
                        <button type="submit" class="text-white rounded-full shadow-sm transition" style="display: inline-block; padding: 12px 28px !important; background-color: #0b602b !important; color: #ffffff !important; border: 1.5px solid #0b602b !important; text-align: center; font-size: 14px; font-weight: 700; border-radius: 9999px; line-height: 1.25; cursor: pointer;">
                            Simpan Penilaian
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </form>

    <script>
        function selectGrade(element, gradeVal) {
            // Reset all buttons
            document.querySelectorAll('.grade-btn').forEach(btn => {
                btn.style.backgroundColor = '#ffffff';
                btn.style.color = '#374151';
                btn.style.borderColor = '#e5e7eb';
            });
            // Highlight selected button
            let activeColor = '#2563eb'; // Blue for sesuai_ekspektasi
            if (gradeVal === 'diatas_ekspektasi') activeColor = '#4338ca'; // Indigo
            else if (gradeVal === 'dibawah_ekspektasi') activeColor = '#d97706'; // Amber

            element.style.backgroundColor = activeColor;
            element.style.color = '#ffffff';
            element.style.borderColor = activeColor;
            // Set hidden field value
            document.getElementById('nilai-value').value = gradeVal;

            // Reset error warning
            const container = document.getElementById('grade-container');
            if (container) {
                container.style.borderColor = '#e5e7eb';
                container.style.boxShadow = '0 1px 2px 0 rgba(0, 0, 0, 0.05)';
            }
            const errText = document.getElementById('nilai-error-text');
            if (errText) errText.style.display = 'none';
            const badge = document.getElementById('nilai-required-badge');
            if (badge) badge.style.display = 'none';
        }

        document.addEventListener('DOMContentLoaded', () => {
            const currentVal = document.getElementById('nilai-value').value;
            if (currentVal) {
                const btn = document.querySelector(`button[onclick*="'${currentVal}'"]`);
                if (btn) selectGrade(btn, currentVal);
            }

            const form = document.getElementById('penilaian-form');
            if (form) {
                form.addEventListener('submit', function(e) {
                    const val = document.getElementById('nilai-value').value.trim();
                    if (!val) {
                        e.preventDefault();
                        e.stopPropagation();

                        // Highlight error on UI
                        const container = document.getElementById('grade-container');
                        if (container) {
                            container.style.borderColor = '#BA1A1A';
                            container.style.boxShadow = '0 0 0 3px rgba(186, 26, 26, 0.15)';
                            container.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        }
                        const errText = document.getElementById('nilai-error-text');
                        if (errText) errText.style.display = 'flex';
                        const badge = document.getElementById('nilai-required-badge');
                        if (badge) badge.style.display = 'inline-block';

                        // Show SweetAlert warning
                        if (typeof Swal !== 'undefined') {
                            Swal.fire({
                                icon: 'warning',
                                iconColor: '#BA1A1A',
                                title: 'Nilai Belum Dipilih!',
                                text: 'Mohon pilih salah satu skala nilai terlebih dahulu sebelum menyimpan penilaian',
                                confirmButtonColor: '#0b602b',
                                confirmButtonText: 'Mengerti'
                            });
                        } else {
                            alert('Mohon pilih salah satu skala nilai terlebih dahulu sebelum menyimpan penilaian.');
                        }

                        return false;
                    }
                });
            }
        });
    </script>

</x-dashboard-layout>
