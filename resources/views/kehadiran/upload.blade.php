<x-dashboard-layout :title="'Unggah Fingerprint'">

    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-900 tracking-tight">Unggah Data Fingerprint</h2>
            <p class="mt-1 text-sm text-gray-500">Unggah file presensi mesin fingerprint (.xlsx, .xls, .csv) ke sistem</p>
        </div>
        <a href="{{ route('kehadiran.template') }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-[#0e622b] hover:bg-[#0b4d22] text-white font-semibold text-xs rounded-xl shadow-xs transition">
            <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
            <span>Unduh Contoh Format File</span>
        </a>
    </div>

    @include('kehadiran._tabs')

    @if (session('success'))
        <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-xl border border-green-200">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-xl border border-red-200">
            {{ session('error') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-xl border border-red-200">
            <ul class="list-disc pl-5 text-sm space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Upload Area --}}
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 mb-6">
        <form id="importForm" action="{{ route('kehadiran.import') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div id="dropZone" class="border-2 border-dashed border-green-300 rounded-xl p-8 md:p-12 text-center cursor-pointer transition-colors duration-200 hover:border-green-500 hover:bg-green-50/30">

                {{-- Cloud Upload Icon --}}
                <div class="mx-auto w-14 h-14 bg-green-100 rounded-xl flex items-center justify-center mb-4">
                    <svg class="w-7 h-7 text-green-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                    </svg>
                </div>

                <h2 class="text-lg font-bold text-gray-800 mb-1">
                    Lepaskan File Excel / CSV Di Sini
                </h2>

                <p class="text-xs text-gray-500 mb-5">
                    Mendukung upload banyak file sekaligus (.xlsx, .xls, .csv)
                </p>

                <input
                    type="file"
                    id="fileInput"
                    name="files[]"
                    multiple
                    accept=".xlsx,.xls,.csv"
                    required
                    class="hidden">

                <button type="button" id="pickFileBtn" class="inline-flex items-center gap-2 rounded-xl bg-[#0e622b] hover:bg-[#0b4d22] px-6 py-2.5 text-sm font-semibold text-white transition shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    <span id="pickFileBtnText">Pilih File Excel / CSV</span>
                </button>

                {{-- Selected Files List --}}
                <div id="fileListContainer" class="mt-6 text-left hidden">
                    <div class="flex items-center justify-between text-xs font-bold text-gray-700 mb-3 px-1">
                        <span>Daftar File Terpilih (<span id="fileCountBadge">0</span> file)</span>
                        <button type="button" id="clearAllFilesBtn" class="text-red-600 hover:text-red-800 font-semibold text-xs">
                            Hapus Semua
                        </button>
                    </div>
                    <div id="fileBadgesGrid" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3">
                        {{-- Injected dynamically --}}
                    </div>
                </div>

            </div>
        </form>
    </div>

    {{-- Preview Section (Pratinjau Impor) --}}
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden mb-8">
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
            <div class="flex items-center gap-2.5">
                <h3 class="text-base font-bold text-gray-800">Pratinjau Impor</h3>
                <span id="previewCountBadge" class="hidden inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200"></span>
            </div>
            <span id="previewStatus" class="text-sm text-gray-400 italic">Menunggu unggahan berkas...</span>
        </div>

        {{-- Empty State --}}
        <div id="previewEmpty" class="px-6 py-12 text-center">
            <div class="mx-auto w-12 h-12 text-gray-300 mb-3 flex items-center justify-center">
                <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
            </div>
            <p class="text-sm text-gray-400 max-w-sm mx-auto">
                Setelah Anda mengunggah file, data absensi yang akan diimpor akan ditampilkan di tabel ini untuk verifikasi.
            </p>
        </div>

        {{-- Table Content --}}
        <div id="previewContent" class="hidden">
            <div class="overflow-x-auto max-h-96">
                <table class="w-full text-xs text-left text-gray-700">
                    <thead class="bg-gray-50 border-b border-gray-200 font-bold text-gray-800 uppercase text-[11px] sticky top-0 z-10">
                        <tr>
                            <th class="px-4 py-3 border-r border-gray-200 w-14 text-center">No.</th>
                            <th class="px-4 py-3 border-r border-gray-200 text-center">NIP</th>
                            <th class="px-4 py-3 border-r border-gray-200 text-center">Nama</th>
                            <th class="px-4 py-3 border-r border-gray-200 text-center">Waktu</th>
                            <th class="px-4 py-3 border-r border-gray-200 text-center">Tipe</th>
                            <th class="px-4 py-3 text-center">File Sumber</th>
                        </tr>
                    </thead>
                    <tbody id="previewTableBody" class="divide-y divide-gray-200 font-mono text-[11px] bg-white">
                        {{-- Data rows inserted here via JS --}}
                    </tbody>
                </table>
            </div>

            <div class="px-6 py-4 bg-gray-50/70 border-t border-gray-200 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs">
                <span id="previewSummaryText" class="text-gray-500 font-medium"></span>
                <button type="button" id="submitImportBtn" class="inline-flex items-center gap-2 rounded-xl bg-[#0e622b] hover:bg-[#0b4d22] px-6 py-2.5 text-xs font-bold text-white transition shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                    </svg>
                    <span>Upload Sekarang</span>
                </button>
            </div>
        </div>
    </div>

    {{-- SheetJS Library --}}
    <script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>

    {{-- Preview Script --}}
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const dropZone = document.getElementById('dropZone');
            const fileInput = document.getElementById('fileInput');
            const pickFileBtn = document.getElementById('pickFileBtn');
            const pickFileBtnText = document.getElementById('pickFileBtnText');
            const fileListContainer = document.getElementById('fileListContainer');
            const fileBadgesGrid = document.getElementById('fileBadgesGrid');
            const fileCountBadge = document.getElementById('fileCountBadge');
            const clearAllFilesBtn = document.getElementById('clearAllFilesBtn');
            
            const previewEmpty = document.getElementById('previewEmpty');
            const previewContent = document.getElementById('previewContent');
            const previewStatus = document.getElementById('previewStatus');
            const previewCountBadge = document.getElementById('previewCountBadge');
            const previewTableBody = document.getElementById('previewTableBody');
            const previewSummaryText = document.getElementById('previewSummaryText');
            const submitImportBtn = document.getElementById('submitImportBtn');
            const importForm = document.getElementById('importForm');

            let selectedFilesArray = [];

            // Click to pick file
            pickFileBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                fileInput.click();
            });

            // Drag and drop
            dropZone.addEventListener('dragover', (e) => {
                e.preventDefault();
                dropZone.classList.add('border-green-500', 'bg-green-50/50');
            });

            dropZone.addEventListener('dragleave', () => {
                dropZone.classList.remove('border-green-500', 'bg-green-50/50');
            });

            dropZone.addEventListener('drop', (e) => {
                e.preventDefault();
                dropZone.classList.remove('border-green-500', 'bg-green-50/50');
                if (e.dataTransfer.files && e.dataTransfer.files.length > 0) {
                    addFiles(Array.from(e.dataTransfer.files));
                }
            });

            // File input change
            fileInput.addEventListener('change', () => {
                if (fileInput.files && fileInput.files.length > 0) {
                    addFiles(Array.from(fileInput.files));
                }
            });

            // Clear all files
            clearAllFilesBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                selectedFilesArray = [];
                syncInputAndRender();
            });

            // Submit import
            submitImportBtn.addEventListener('click', () => {
                importForm.submit();
            });

            function addFiles(newFiles) {
                newFiles.forEach(file => {
                    const ext = file.name.split('.').pop().toLowerCase();
                    if (['xlsx', 'xls', 'csv'].includes(ext)) {
                        const exists = selectedFilesArray.some(f => f.name === file.name && f.size === file.size);
                        if (!exists) {
                            selectedFilesArray.push(file);
                        }
                    }
                });
                syncInputAndRender();
            }

            function removeFile(index) {
                selectedFilesArray.splice(index, 1);
                syncInputAndRender();
            }

            function syncInputAndRender() {
                // Sync to file input via DataTransfer
                try {
                    const dt = new DataTransfer();
                    selectedFilesArray.forEach(file => dt.items.add(file));
                    fileInput.files = dt.files;
                } catch (err) {
                    console.error('DataTransfer error:', err);
                }

                // Render File Badges
                if (selectedFilesArray.length === 0) {
                    fileListContainer.classList.add('hidden');
                    pickFileBtnText.textContent = 'Pilih File Excel / CSV';
                    previewEmpty.classList.remove('hidden');
                    previewContent.classList.add('hidden');
                    previewCountBadge.classList.add('hidden');
                    previewStatus.textContent = 'Menunggu unggahan berkas...';
                    previewStatus.className = 'text-sm text-gray-400 italic';
                    previewTableBody.innerHTML = '';
                    return;
                }

                fileListContainer.classList.remove('hidden');
                pickFileBtnText.textContent = 'Tambah File Lainnya';
                fileCountBadge.textContent = selectedFilesArray.length;
                fileBadgesGrid.innerHTML = '';

                selectedFilesArray.forEach((file, idx) => {
                    const ext = file.name.split('.').pop().toUpperCase();
                    const sizeStr = (file.size / (1024 * 1024) >= 1) 
                        ? (file.size / (1024 * 1024)).toFixed(2) + ' MB' 
                        : (file.size / 1024).toFixed(0) + ' KB';

                    const card = document.createElement('div');
                    card.className = 'relative flex items-center gap-3 p-3 bg-white rounded-xl border border-green-200 shadow-xs';
                    card.innerHTML = `
                        <div class="w-9 h-9 rounded-lg bg-green-50 text-green-700 flex items-center justify-center font-bold text-xs shrink-0 border border-green-100 uppercase">
                            ${ext}
                        </div>
                        <div class="overflow-hidden flex-1 min-w-0 pr-6">
                            <p class="text-xs font-bold text-gray-800 truncate" title="${file.name}">${file.name}</p>
                            <p class="text-[11px] text-gray-400 font-medium mt-0.5">${sizeStr}</p>
                        </div>
                        <button type="button" class="btn-remove-file absolute top-2.5 right-2.5 w-6 h-6 rounded-full bg-gray-100 hover:bg-red-50 text-gray-400 hover:text-red-600 flex items-center justify-center transition" title="Hapus file ini">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    `;

                    card.querySelector('.btn-remove-file').addEventListener('click', (e) => {
                        e.stopPropagation();
                        removeFile(idx);
                    });

                    fileBadgesGrid.appendChild(card);
                });

                // Parse and render Preview Data Table
                parseAndRenderPreview();
            }

            function parseAndRenderPreview() {
                previewStatus.textContent = 'Membaca data file...';
                previewStatus.className = 'text-sm text-emerald-700 font-semibold animate-pulse';

                let allRows = [];
                let completed = 0;

                selectedFilesArray.forEach(file => {
                    const isCsv = file.name.toLowerCase().endsWith('.csv');
                    const reader = new FileReader();

                    reader.onload = (e) => {
                        try {
                            if (isCsv) {
                                const rows = parseCsv(e.target.result, file.name);
                                allRows = allRows.concat(rows);
                            } else if (typeof XLSX !== 'undefined') {
                                const data = new Uint8Array(e.target.result);
                                const workbook = XLSX.read(data, { type: 'array', cellDates: true });
                                const firstSheet = workbook.Sheets[workbook.SheetNames[0]];
                                const json = XLSX.utils.sheet_to_json(firstSheet, { header: 1, raw: false });
                                const rows = parseExcelJson(json, file.name);
                                allRows = allRows.concat(rows);
                            }
                        } catch (err) {
                            console.error('Error parsing preview:', err);
                        }

                        completed++;
                        if (completed === selectedFilesArray.length) {
                            renderTableRows(allRows);
                        }
                    };

                    if (isCsv) {
                        reader.readAsText(file);
                    } else {
                        reader.readAsArrayBuffer(file);
                    }
                });
            }

            function parseExcelJson(json, fileName) {
                if (!json || json.length < 2) return [];

                let headerIdx = 0;
                for (let i = 0; i < Math.min(5, json.length); i++) {
                    const rowStr = (json[i] || []).map(c => String(c).toLowerCase()).join(' ');
                    if (rowStr.includes('nip') || rowStr.includes('waktu') || rowStr.includes('nama')) {
                        headerIdx = i;
                        break;
                    }
                }

                const headers = (json[headerIdx] || []).map(h => String(h || '').trim().toLowerCase());
                const nipIdx = headers.findIndex(h => h.includes('nip'));
                const namaIdx = headers.findIndex(h => h.includes('nama'));
                const waktuIdx = headers.findIndex(h => h.includes('waktu') || h.includes('tanggal') || h.includes('jam'));
                const tipeIdx = headers.findIndex(h => h.includes('tipe') || h.includes('status') || h.includes('keterangan'));

                const rows = [];
                for (let i = headerIdx + 1; i < json.length; i++) {
                    const row = json[i];
                    if (!row || row.length === 0) continue;

                    const nip = nipIdx !== -1 ? String(row[nipIdx] || '').trim() : '';
                    const nama = namaIdx !== -1 ? String(row[namaIdx] || '').trim() : '';
                    const waktu = waktuIdx !== -1 ? String(row[waktuIdx] || '').trim() : '';
                    const tipe = tipeIdx !== -1 ? String(row[tipeIdx] || '').trim() : '';

                    if (nip || nama || waktu) {
                        rows.push({
                            nip: nip || '-',
                            nama: nama || '-',
                            waktu: waktu || '-',
                            tipe: tipe || (waktu ? 'Absen' : '-'),
                            fileName: fileName
                        });
                    }
                }
                return rows;
            }

            function parseCsv(text, fileName) {
                const lines = text.split(/\r?\n/).filter(line => line.trim() !== '');
                if (lines.length < 2) return [];

                const delimiter = lines[0].includes(';') ? ';' : (lines[0].includes('\t') ? '\t' : ',');
                const splitLine = (l) => l.split(delimiter).map(s => s.replace(/^['\"]|['\"]$/g, '').trim());

                const headers = splitLine(lines[0]).map(h => h.toLowerCase());
                const nipIdx = headers.findIndex(h => h.includes('nip'));
                const namaIdx = headers.findIndex(h => h.includes('nama'));
                const waktuIdx = headers.findIndex(h => h.includes('waktu') || h.includes('tanggal') || h.includes('jam'));
                const tipeIdx = headers.findIndex(h => h.includes('tipe') || h.includes('status') || h.includes('keterangan'));

                const rows = [];
                for (let i = 1; i < lines.length; i++) {
                    const row = splitLine(lines[i]);
                    if (row.length === 0 || row.every(c => c === '')) continue;

                    const nip = nipIdx !== -1 ? (row[nipIdx] || '') : '';
                    const nama = namaIdx !== -1 ? (row[namaIdx] || '') : '';
                    const waktu = waktuIdx !== -1 ? (row[waktuIdx] || '') : '';
                    const tipe = tipeIdx !== -1 ? (row[tipeIdx] || '') : '';

                    if (nip || nama || waktu) {
                        rows.push({
                            nip: nip || '-',
                            nama: nama || '-',
                            waktu: waktu || '-',
                            tipe: tipe || (waktu ? 'Absen' : '-'),
                            fileName: fileName
                        });
                    }
                }
                return rows;
            }

            function renderTableRows(rows) {
                if (rows.length === 0) {
                    previewEmpty.classList.remove('hidden');
                    previewContent.classList.add('hidden');
                    previewCountBadge.classList.add('hidden');
                    previewStatus.textContent = 'Tidak ada baris data yang terdeteksi.';
                    previewStatus.className = 'text-sm text-amber-600 font-semibold';
                    return;
                }

                previewEmpty.classList.add('hidden');
                previewContent.classList.remove('hidden');
                previewCountBadge.classList.remove('hidden');

                const maxDisplay = 50;
                const displayRows = rows.slice(0, maxDisplay);

                previewCountBadge.textContent = `${displayRows.length} dari ${rows.length} Baris`;
                previewStatus.textContent = 'Data Siap Diimpor';
                previewStatus.className = 'text-sm text-green-700 font-semibold';
                previewSummaryText.textContent = `Menampilkan ${displayRows.length} baris data pertama dari total ${rows.length} baris data (${selectedFilesArray.length} file).`;

                previewTableBody.innerHTML = displayRows.map((r, i) => {
                    const isMasuk = r.tipe.toLowerCase().includes('masuk') || r.tipe.toLowerCase().includes('in');
                    const badgeClass = isMasuk 
                        ? 'text-emerald-700 bg-emerald-50 border border-emerald-200' 
                        : 'text-blue-700 bg-blue-50 border border-blue-200';

                    return `
                        <tr class="hover:bg-emerald-50/40 transition">
                            <td class="px-4 py-2.5 border-r border-gray-200">${i + 1}</td>
                            <td class="px-4 py-2.5 border-r border-gray-200 font-semibold text-gray-900">${escapeHtml(r.nip)}</td>
                            <td class="px-4 py-2.5 border-r border-gray-200 font-sans font-medium text-gray-800">${escapeHtml(r.nama)}</td>
                            <td class="px-4 py-2.5 border-r border-gray-200 text-gray-700">${escapeHtml(r.waktu)}</td>
                            <td class="px-4 py-2.5 border-r border-gray-200 font-sans font-semibold">
                                <span class="px-2 py-0.5 rounded text-[11px] ${badgeClass}">${escapeHtml(r.tipe)}</span>
                            </td>
                            <td class="px-4 py-2.5 font-sans text-gray-400 truncate max-w-[160px]" title="${escapeHtml(r.fileName)}">${escapeHtml(r.fileName)}</td>
                        </tr>
                    `;
                }).join('');
            }

            function escapeHtml(text) {
                const div = document.createElement('div');
                div.textContent = text || '';
                return div.innerHTML;
            }
        });
    </script>

</x-dashboard-layout>