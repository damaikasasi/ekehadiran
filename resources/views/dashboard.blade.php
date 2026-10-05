<x-dashboard-layout :title="auth()->user()->role === 'admin' ? 'Dashboard Admin' : 'Ikhtisar Administrasi'">

    <style>
        /* ===== Custom dashboard styles (tidak bergantung Tailwind arbitrary-value JIT) ===== */
        .ek-title { font-size: 24px; font-weight: 600; color: #1f2937; margin: 0; }
        .ek-subtitle { font-size: 14px; font-weight: 400; color: #6b7280; margin-top: 4px; }
        .ek-section-title { font-size: 18px; font-weight: 600; color: #1f2937; margin: 0 0 14px 0; }
        .ek-accent { color: #0e622b; font-weight: 500; }

        /* Quick access cards */
        .ek-quick-grid { display: grid; grid-template-columns: repeat(1, minmax(0, 1fr)); gap: 16px; margin-bottom: 24px; }
        @media (min-width: 640px) { .ek-quick-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); } }
        @media (min-width: 1024px) { .ek-quick-grid { grid-template-columns: repeat(4, minmax(0, 1fr)); } }
        .ek-quick-card {
            background: #ffffff; border: 1px solid #e5e7eb; border-radius: 16px; padding: 20px;
            display: flex; align-items: center; justify-content: space-between; gap: 12px;
            text-decoration: none; transition: box-shadow .18s ease, transform .18s ease, border-color .18s ease;
        }
        .ek-quick-card:hover { box-shadow: 0 8px 20px rgba(16, 24, 40, 0.08); transform: translateY(-2px); border-color: #d1fae5; }
        .ek-quick-icon {
            width: 46px; height: 46px; min-width: 46px; border-radius: 9999px;
            display: flex; align-items: center; justify-content: center;
        }
        .ek-quick-icon svg { width: 22px; height: 22px; }
        .ek-quick-label { font-size: 18px; font-weight: 600; color: #1f2937; line-height: 1.2; }
        .ek-quick-desc { font-size: 14px; color: #4b5563; margin-top: 3px; line-height: 1.3; }
        .ek-quick-chevron { width: 16px; height: 16px; color: #d1d5db; flex-shrink: 0; transition: transform .18s ease, color .18s ease; }
        .ek-quick-card:hover .ek-quick-chevron { transform: translateX(3px); color: #0e622b; }

        /* Buttons & Select Inputs */
        .ek-btn-primary {
            background: #0e622b; color: #ffffff !important; padding: 9px 18px; border-radius: 12px;
            font-size: 13px; font-weight: 500; text-decoration: none; display: inline-flex; align-items: center;
            transition: background .15s ease; white-space: nowrap;
        }
        .ek-btn-primary:hover { background: #0b4d22; }
        .ek-btn-outline {
            background: #ffffff; color: #374151; border: 1px solid #e5e7eb; padding: 9px 18px; border-radius: 12px;
            font-size: 13px; font-weight: 500; text-decoration: none; display: inline-flex; align-items: center;
            transition: background .15s ease;
        }
        .ek-btn-outline:hover { background: #f9fafb; }
        .ek-select {
            background-color: #ffffff; color: #374151; border: 1px solid #e5e7eb; padding: 9px 32px 9px 14px; border-radius: 12px;
            font-size: 13px; font-weight: 500; font-family: inherit; display: inline-flex; align-items: center;
            transition: border-color .15s ease, box-shadow .15s ease, background-color .15s ease;
            appearance: none; -webkit-appearance: none; -moz-appearance: none;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
            background-position: right 10px center;
            background-repeat: no-repeat;
            background-size: 16px 16px;
            cursor: pointer; line-height: 1.4; outline: none; box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        }
        .ek-select:hover { border-color: #0e622b; background-color: #fdfdfd; }
        .ek-select:focus { border-color: #0e622b; box-shadow: 0 0 0 3px rgba(14, 98, 43, 0.12); }

        /* Cards (chart / announcements / tables) */
        .ek-card { background: #ffffff; border: 1px solid #e5e7eb; border-radius: 16px; padding: 24px; box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05); }

        /* Stat mini-cards */
        .ek-stat { border-radius: 16px; padding: 16px; border-top: 1px solid; border-right: 1px solid; border-bottom: 1px solid; }
        .ek-stat-label { font-size: 12px; color: #4b5563; margin: 0; text-transform: uppercase; font-weight: 700; letter-spacing: .04em; }
        .ek-stat-value { font-size: 18px; font-weight: 600; margin: 4px 0 0 0; }

        /* Legend dots */
        .ek-dot { width: 9px; height: 9px; border-radius: 9999px; display: inline-block; margin-right: 6px; }

        /* Announcement cards */
        .ek-announce { padding: 14px 16px; border-radius: 0 14px 14px 0; border-left: 4px solid; position: relative; transition: box-shadow .15s ease; margin-bottom: 16px; }
        .ek-announce:hover { box-shadow: 0 2px 8px rgba(16,24,40,0.06); }
        .ek-announce:last-child { margin-bottom: 0; }
        .ek-announce-title { font-size: 18px; font-weight: 600; color: #1f2937; margin: 0; padding-right: 70px; line-height: 1.3; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
        .ek-announce-time { font-size: 12px; color: #4b5563; position: absolute; top: 14px; right: 16px; }
        .ek-announce-body { font-size: 14px; color: #4b5563; margin-top: 6px; line-height: 1.5; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }

        /* Table badges */
        .ek-badge { display: inline-block; font-size: 11px; font-weight: 600; letter-spacing: .03em; padding: 3px 10px; border-radius: 8px; border: 1px solid; text-transform: uppercase; white-space: nowrap; }
        .ek-badge-pending { background: #fef3c7; color: #854d0e; border-color: #fde68a; }
        .ek-badge-approved { background: #dcfce7; color: #166534; border-color: #bbf7d0; }
        .ek-badge-rejected { background: #fee2e2; color: #991b1b; border-color: #fecaca; }
        .ek-status-dot-row { display: inline-flex; align-items: center; font-size: 12px; font-weight: 600; text-transform: uppercase; letter-spacing: .03em; white-space: nowrap; }

        /* Table */
        .ek-table { width: 100%; text-align: left; border-collapse: collapse; }
        .ek-table thead th { font-size: 12px; font-weight: 600; letter-spacing: .04em; color: #ffffff; padding: 13px 20px; background: #0b602b; text-transform: uppercase; white-space: nowrap; text-align: center; }
        .ek-table thead th:first-child { border-top-left-radius: 10px; }
        .ek-table thead th:last-child { border-top-right-radius: 10px; }
        .ek-table tbody tr { height: 60px; }
        .ek-table tbody td { padding: 12px 20px; font-size: 14px; color: #374151; border-bottom: 1px solid #f6f7f8; vertical-align: middle; }
        .ek-table tbody tr:hover { background: #fafbfc; }
        .ek-table tbody tr:last-child td { border-bottom: none; }
        .ek-table svg { width: 20px; height: 20px; min-width: 20px; min-height: 20px; display: inline-block; }

        /* Center kolom Status (badge) */
        .ek-table-status-center { text-align: center; text-transform: uppercase; }
    </style>

    @if (session('success'))
        <div class="mb-6 p-4 bg-emerald-50 border border-emerald-100 text-emerald-800 rounded-2xl flex items-center gap-3 shadow-sm transition-all duration-300">
            <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <span class="text-sm font-medium">{{ session('success') }}</span>
        </div>
    @endif

    @if(auth()->user()->role === 'admin')
        <!-- REDESIGNED ADMIN DASHBOARD -->
        <div class="space-y-8">
           <!-- HERO BANNER ADMIN -->
            <div class="relative overflow-hidden mb-5 sm:mb-6 rounded-2xl shadow-sm min-h-[110px] sm:min-h-[125px] md:min-h-[135px] flex flex-col justify-center px-6 sm:px-8 md:px-10 py-8 sm:py-5">

                <!-- Background Image -->
                <img 
                    src="{{ asset('images/brmp_meeting_photo.jpeg') }}"
                    alt="Background Gedung"
                    class="absolute inset-0 w-full h-full object-cover"
                    style="object-position: center;"
                >

                <!-- Green Overlay -->
                <div class="absolute inset-0"
                    style="background: linear-gradient(
                        90deg,
                        rgba(0, 105, 55, 0.96) 0%,
                        rgba(0, 105, 55, 0.88) 40%,
                        rgba(0, 105, 55, 0.65) 70%,
                        rgba(0, 105, 55, 0.45) 100%
                    );">
                </div>

                <!-- Content -->
                <div class="relative z-10">

                    <h2 class="font-black text-white leading-tight mb-1 sm:mb-1.5 text-xl sm:text-2xl md:text-[26px] lg:text-[28px]">
                        Selamat Datang
                        <span style="color: #FFD700;">
                            Admin
                        </span>
                    </h2>

                    <p class="text-white text-xs sm:text-sm font-medium max-w-2xl"
                    style="text-shadow: 0 1px 3px rgba(0,0,0,0.25);">
                        Pantau performa kehadiran dan kelola laporan pegawai hari ini
                    </p>

                </div>
            </div>
        </div>

            <!-- Akses Cepat (Quick Access) Section -->
            <div>
                <p class="ek-section-title">Akses Cepat</p>
                <div class="ek-quick-grid">
                    <!-- Tambah Pegawai -->
                    <a href="{{ route('pegawai.create') }}" class="ek-quick-card">
                        <div style="display: flex; align-items: center; gap: 14px; min-width: 0;">
                            <div class="ek-quick-icon" style="background-color: #e6f4ea; color: #137333;">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h8m9-8h-4m2-2v4"/></svg>
                            </div>
                            <div style="min-width: 0;">
                                <p class="ek-quick-label">Tambah Pegawai</p>
                                <p class="ek-quick-desc">Input data dan NIP pegawai</p>
                            </div>
                        </div>
                        <svg class="ek-quick-chevron" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path></svg>
                    </a>

                    <!-- Kelola Laporan -->
                    <a href="{{ route('laporan-mingguan.index') }}" class="ek-quick-card">
                        <div style="display: flex; align-items: center; gap: 14px; min-width: 0;">
                            <div class="ek-quick-icon" style="background-color: #fef7e0; color: #b06000;">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 002 2h2a2 2 0 002-2z"></path></svg>
                            </div>
                            <div style="min-width: 0;">
                                <p class="ek-quick-label">Kelola Laporan</p>
                                <p class="ek-quick-desc">Pantau & kelola laporan bulanan</p>
                            </div>
                        </div>
                        <svg class="ek-quick-chevron" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path></svg>
                    </a>

                    <!-- Import Kehadiran -->
                    <a href="{{ route('kehadiran.create') }}" class="ek-quick-card">
                        <div style="display: flex; align-items: center; gap: 14px; min-width: 0;">
                            <div class="ek-quick-icon" style="background-color: #e2f3eb; color: #1e8e5e;">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"></path></svg>
                            </div>
                            <div style="min-width: 0;">
                                <p class="ek-quick-label">Import Kehadiran</p>
                                <p class="ek-quick-desc">Unggah presensi dari fingerprint</p>
                            </div>
                        </div>
                        <svg class="ek-quick-chevron" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path></svg>
                    </a>

                    <!-- Presensi WFH -->
                    <a href="{{ route('kehadiran.wfh', ['bulan' => $selectedBulan, 'tahun' => $selectedTahun]) }}" class="ek-quick-card">
                        <div style="display: flex; align-items: center; gap: 14px; min-width: 0;">
                            <div class="ek-quick-icon" style="background-color: #fdf4e3; color: #b06000;">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </div>
                            <div style="min-width: 0;">
                                <p class="ek-quick-label">Presensi WFH</p>
                                <p class="ek-quick-desc">Atur dan aktifkan presensi WFH</p>
                            </div>
                        </div>
                        <svg class="ek-quick-chevron" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path></svg>
                    </a>
                </div>
            </div>

            <!-- Middle Row: Team Attendance Metrics + Recent Announcements -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-stretch">
                <!-- Left Column (lg:col-span-2): Ringkasan Kehadiran Mingguan -->
                <div class="lg:col-span-2 flex flex-col h-full">
                    <!-- Ringkasan Kehadiran Mingguan -->
                    <div class="ek-card flex flex-col justify-between flex-1 h-full">
                        <div>
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                <div>
                                    <h3 class="text-lg sm:text-xl font-bold text-gray-900 leading-snug">Ringkasan Kehadiran Mingguan</h3>
                                    <p class="text-xs sm:text-sm text-gray-500 mt-0.5">Perbandingan status kehadiran tim berdasarkan minggu</p>
                                </div>
                                <form method="GET" action="{{ route('dashboard') }}" id="filterBulanForm" class="flex items-center gap-2 shrink-0">
                                    <!-- Dropdown Bulan -->
                                    <select
                                        name="bulan"
                                        aria-label="Pilih Bulan"
                                        onchange="document.getElementById('filterBulanForm').submit()"
                                        class="ek-select min-w-[125px]">
                                        @foreach($monthsList ?? [] as $num => $name)
                                            <option value="{{ $num }}" {{ (int)($selectedBulan ?? now()->month) === (int)$num ? 'selected' : '' }}>{{ $name }}</option>
                                        @endforeach
                                    </select>

                                    <!-- Dropdown Tahun -->
                                    <select
                                        name="tahun"
                                        aria-label="Pilih Tahun"
                                        onchange="document.getElementById('filterBulanForm').submit()"
                                        class="ek-select min-w-[90px]">
                                        @foreach($yearsRange ?? [now()->year] as $year)
                                            <option value="{{ $year }}" {{ (int)($selectedTahun ?? now()->year) === (int)$year ? 'selected' : '' }}>{{ $year }}</option>
                                        @endforeach
                                    </select>
                                </form>
                            </div>

                            <!-- Stacked Bar Chart Canvas -->
                            <div class="mt-6 relative w-full" style="height: 330px;">
                                <canvas id="weeklyAttendanceChart"></canvas>
                            </div>

                            <!-- Chart Custom Legend -->
                            <div style="display: flex; align-items: center; justify-content: center; gap: 24px; margin-top: 24px; flex-wrap: wrap;">
                                <div style="display: flex; align-items: center; gap: 8px;">
                                    <span style="width: 10px; height: 10px; border-radius: 50%; background-color: #2e9e62; display: inline-block;"></span>
                                    <span style="font-size: 12.5px; font-weight: 600; color: #374151;">Hadir</span>
                                </div>
                                <div style="display: flex; align-items: center; gap: 8px;">
                                    <span style="width: 10px; height: 10px; border-radius: 50%; background-color: #eb5757; display: inline-block;"></span>
                                    <span style="font-size: 12.5px; font-weight: 600; color: #374151;">Terlambat</span>
                                </div>
                                <div style="display: flex; align-items: center; gap: 8px;">
                                    <span style="width: 10px; height: 10px; border-radius: 50%; background-color: #9b51e0; display: inline-block;"></span>
                                    <span style="font-size: 12.5px; font-weight: 600; color: #374151;">Izin</span>
                                </div>
                                <div style="display: flex; align-items: center; gap: 8px;">
                                    <span style="width: 10px; height: 10px; border-radius: 50%; background-color: #53aee8; display: inline-block;"></span>
                                    <span style="font-size: 12.5px; font-weight: 600; color: #374151;">WFH</span>
                                </div>
                            </div>
                        </div>

                        <!-- Insight Banner Card -->
                        <a href="{{ route('kehadiran.rekap', ['bulan' => $selectedBulan, 'tahun' => $selectedTahun]) }}" 
                           class="group text-decoration-none"
                           style="margin-top: 24px; padding: 16px 20px; background-color: #f0f7ff; border: 1px solid #d8ebfd; border-radius: 16px; display: flex; align-items: center; justify-content: space-between; gap: 16px; transition: background-color .15s ease;">
                            <div style="display: flex; align-items: center; gap: 14px; min-width: 0;">
                                <div style="width: 36px; height: 36px; border-radius: 12px; display: flex; align-items: center; justify-content: center; color: #1d70b8; flex-shrink: 0;">
                                    <svg style="width: 24px; height: 24px; color: #1d70b8;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                                    </svg>
                                </div>
                                <div style="min-width: 0;">
                                    @if($mingguanInsight['has_data'] ?? false)
                                        <p style="font-size: 14.5px; font-weight: 700; color: #1e293b; margin: 0; line-height: 1.3;">
                                            Minggu {{ $mingguanInsight['best_week_number'] }} memiliki tingkat kehadiran tertinggi.
                                        </p>
                                        <p style="font-size: 13px; color: #64748b; margin: 3px 0 0 0;">
                                            {{ $mingguanInsight['best_week_hadir'] }} hadir dari {{ $mingguanInsight['best_week_total'] }} total catatan kehadiran.
                                        </p>
                                    @else
                                        <p style="font-size: 14.5px; font-weight: 700; color: #1e293b; margin: 0; line-height: 1.3;">
                                            Belum ada data kehadiran pada bulan ini.
                                        </p>
                                        <p style="font-size: 13px; color: #64748b; margin: 3px 0 0 0;">
                                            Data kehadiran akan otomatis diperbarui setelah presensi tercatat.
                                        </p>
                                    @endif
                                </div>
                            </div>
                            <div style="color: #94a3b8; flex-shrink: 0;">
                                <svg style="width: 20px; height: 20px;" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                                </svg>
                            </div>
                        </a>
                    </div>
                </div>

                <!-- Right Column (lg:col-span-1): Recent Announcements -->
                <div class="lg:col-span-1 flex flex-col h-full">
                    <div class="ek-card flex flex-col justify-between flex-1 h-full">
                    <div>
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 18px;">
                            <p class="ek-section-title" style="margin: 0;">Pengumuman Terbaru</p>
                            <a href="{{ route('pengumuman.create') }}" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 bg-[#0b602b] hover:bg-[#084d22] text-white text-xs font-semibold rounded-full shadow-xs transition" title="Buat Pengumuman Baru">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                                <span>Buat</span>
                            </a>
                        </div>

                        <!-- Stack of Announcements -->
                        <div>
                            @forelse($pengumumanTerbaru as $index => $item)
                                @php
                                    $palette = [
                                        ['border' => '#137333', 'bg' => '#f4f9f4'],
                                        ['border' => '#a50e58', 'bg' => '#faf5f8'],
                                        ['border' => '#9ca3af', 'bg' => '#f9fafb'],
                                    ];
                                    $c = $palette[$index % count($palette)];
                                @endphp
                                <div class="ek-announce" style="background-color: {{ $c['bg'] }}; border-left-color: {{ $c['border'] }};">
                                    <div class="flex items-center justify-between gap-2">
                                        <a href="{{ route('pengumuman.show', $item->id) }}" class="ek-announce-title hover:text-[#0b602b] transition-colors" title="{{ $item->judul }}">{{ $item->judul }}</a>
                                        @if($item->file_lampiran)
                                            <a href="{{ Storage::url($item->file_lampiran) }}" target="_blank" class="text-emerald-700 hover:text-emerald-900 shrink-0 text-xs inline-flex items-center gap-0.5 font-medium" title="Buka Lampiran">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
                                                <span>Lampiran</span>
                                            </a>
                                        @endif
                                    </div>
                                    <span class="ek-announce-time">{{ $item->created_at->diffForHumans() }}</span>
                                    <p class="ek-announce-body">{{ $item->isi }}</p>
                                </div>
                            @empty
                                <div style="text-align: center; padding: 32px 0; color: #9ca3af; font-size: 12px;">
                                    Belum ada pengumuman.
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <a href="{{ route('pengumuman.index') }}" style="color: #0e622b; font-size: 13px; font-weight: 500; text-decoration: none; text-align: center; display: block; margin-top: 20px;">
                        Lihat Semua Pengumuman
                    </a>
                </div>
            </div>
        </div>

        <!-- Bottom Row: Recent Leaves + Recent Weekly Reports -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-6 items-stretch">
                <!-- Permohonan Izin Terbaru -->
                <div class="ek-card flex flex-col justify-between h-full">
                    <div>
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 14px;">
                            <p class="ek-section-title" style="margin: 0;">Permohonan Izin Terbaru</p>
                            <a href="{{ route('izin.index') }}" style="color: #0e622b; font-size: 13px; font-weight: 500; text-decoration: none;">Lihat Semua</a>
                        </div>

                        <div class="overflow-x-auto rounded-xl border border-gray-200">
                            <table class="w-full text-sm text-center border-collapse">
                                <thead class="bg-[#0b602b] text-white uppercase">
                                    <tr class="h-11">
                                        <th scope="col" class="px-4 py-3 text-xs font-semibold uppercase tracking-wider text-white text-center whitespace-nowrap">NAMA PEGAWAI</th>
                                        <th scope="col" class="px-4 py-3 text-xs font-semibold uppercase tracking-wider text-white text-center whitespace-nowrap">JENIS IZIN</th>
                                        <th scope="col" class="px-4 py-3 text-xs font-semibold uppercase tracking-wider text-white text-center whitespace-nowrap">DURASI</th>
                                        <th scope="col" class="px-4 py-3 text-xs font-semibold uppercase tracking-wider text-white text-center whitespace-nowrap">LAMPIRAN</th>
                                        <th scope="col" class="px-4 py-3 text-xs font-semibold uppercase tracking-wider text-white text-center whitespace-nowrap">STATUS</th>
                                        <th scope="col" class="px-4 py-3 text-xs font-semibold uppercase tracking-wider text-white text-center whitespace-nowrap">AKSI</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @forelse($izinTerbaru as $item)
                                        @php
                                            $durasiHari = \Carbon\Carbon::parse($item->tanggal_mulai)->diffInDays(\Carbon\Carbon::parse($item->tanggal_selesai)) + 1;
                                        @endphp
                                        <tr class="hover:bg-gray-50/50 transition-colors h-[64px]">
                                            <!-- Nama Pegawai -->
                                            <td class="py-2.5 px-3 font-bold text-gray-800 text-center text-xs sm:text-sm leading-snug align-middle whitespace-nowrap">
                                                {{ $item->pegawai->nama ?? '-' }}
                                            </td>

                                            <!-- Jenis Izin -->
                                            <td class="py-2.5 px-3 text-center whitespace-nowrap align-middle">
                                                <span class="font-medium text-gray-800 text-xs sm:text-sm">{{ ucfirst(str_replace('_', ' ', $item->jenis)) }}</span>
                                                @if ($item->jenis === 'sakit')
                                                    <span class="text-[11px] font-normal text-gray-400 block">
                                                        {{ $item->ada_surat_dokter ? 'Dengan surat dokter' : 'Tanpa surat dokter' }}
                                                    </span>
                                                @endif
                                            </td>

                                            <!-- Durasi -->
                                            <td class="py-2.5 px-3 text-xs sm:text-sm text-gray-600 whitespace-nowrap text-center align-middle">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-100 whitespace-nowrap">
                                                    {{ $durasiHari }} Hari
                                                </span>
                                            </td>

                                            <!-- Lampiran -->
                                            <td class="py-2.5 px-3 text-center whitespace-nowrap align-middle">
                                                @if ($item->file_lampiran)
                                                    <a href="{{ route('izin.preview', $item->id) }}" target="_blank" aria-label="Lihat Lampiran Izin {{ $item->pegawai->nama ?? '' }}" class="p-1.5 rounded-lg text-[#0b602b] hover:bg-emerald-50 transition inline-block" title="Lihat Lampiran">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
                                                        </svg>
                                                    </a>
                                                @else
                                                    <span class="text-gray-400 text-xs">-</span>
                                                @endif
                                            </td>

                                            <!-- Status -->
                                            <td class="py-2.5 px-3 text-center whitespace-nowrap align-middle">
                                                @if($item->status === 'disetujui')
                                                    <span class="inline-block px-2.5 py-1 text-[10px] font-extrabold rounded-full bg-[#dcfce7] text-[#0b602b] uppercase tracking-wider">
                                                        DISETUJUI
                                                    </span>
                                                @elseif($item->status === 'disetujui_atasan')
                                                    <span class="inline-block px-2.5 py-1 text-[10px] font-extrabold rounded-full bg-[#e0f2fe] text-[#0369a1] uppercase tracking-wider">
                                                        DISETUJUI ATASAN
                                                    </span>
                                                @elseif($item->status === 'ditolak')
                                                    <span class="inline-block px-2.5 py-1 text-[10px] font-extrabold rounded-full bg-[#BA1A1A] text-white uppercase tracking-wider shadow-sm">
                                                        DITOLAK
                                                    </span>
                                                @else
                                                    <span class="inline-block px-2.5 py-1 text-[10px] font-extrabold rounded-full bg-[#fef3c7] text-[#b45309] uppercase tracking-wider">
                                                        PENDING
                                                    </span>
                                                @endif
                                            </td>

                                            <!-- Aksi -->
                                            <td class="py-2.5 px-3 text-center whitespace-nowrap align-middle">
                                                <a href="{{ route('izin.show', $item->id) }}" aria-label="Lihat Detail Izin {{ $item->pegawai->nama ?? '' }}" class="text-[#0e622b] hover:text-[#0b4d22] p-1.5 rounded-lg hover:bg-emerald-50 transition inline-flex items-center justify-center" title="Lihat Detail Izin">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="py-8 text-center text-sm text-gray-400">Tidak ada permohonan izin terbaru.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Laporan Mingguan Terbaru -->
                <div class="ek-card flex flex-col justify-between h-full">
                    <div>
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 14px;">
                            <p class="ek-section-title" style="margin: 0;">Laporan Bulanan Terbaru</p>
                            <a href="{{ route('laporan-mingguan.index') }}" style="color: #0e622b; font-size: 13px; font-weight: 500; text-decoration: none;">Lihat Semua</a>
                        </div>

                        <div class="overflow-x-auto rounded-xl border border-gray-200">
                            <table class="w-full text-sm text-center border-collapse">
                                <thead class="bg-[#0b602b] text-white uppercase">
                                    <tr class="h-11">
                                        <th scope="col" class="px-4 py-3 text-xs font-semibold uppercase tracking-wider text-white text-center whitespace-nowrap">NAMA PEGAWAI</th>
                                        <th scope="col" class="px-4 py-3 text-xs font-semibold uppercase tracking-wider text-white text-center whitespace-nowrap">BULAN/TAHUN</th>
                                        <th scope="col" class="px-4 py-3 text-xs font-semibold uppercase tracking-wider text-white text-center whitespace-nowrap">LAPORAN</th>
                                        <th scope="col" class="px-4 py-3 text-xs font-semibold uppercase tracking-wider text-white text-center whitespace-nowrap">STATUS</th>
                                        <th scope="col" class="px-4 py-3 text-xs font-semibold uppercase tracking-wider text-white text-center whitespace-nowrap">AKSI</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @forelse($laporanTerbaru as $item)
                                        <tr class="hover:bg-gray-50/50 transition-colors h-[64px]">
                                            <!-- Nama Pegawai -->
                                            <td class="py-2.5 px-3 font-bold text-gray-800 text-center text-xs sm:text-sm leading-snug align-middle whitespace-nowrap">
                                                {{ $item->pegawai->nama ?? '-' }}
                                            </td>

                                            <!-- Bulan / Tahun -->
                                            <td class="py-2.5 px-3 text-xs sm:text-sm text-gray-600 text-center whitespace-nowrap align-middle">
                                                {{ \Carbon\Carbon::create()->month($item->bulan)->translatedFormat('F') }} {{ $item->tahun }}
                                            </td>

                                            <!-- Laporan (Preview Link) -->
                                            <td class="py-2.5 px-3 text-center whitespace-nowrap align-middle">
                                                <a href="{{ route('laporan-mingguan.preview', $item->id) }}" target="_blank" 
                                                   class="text-[#0e622b] hover:text-[#0b4d22] font-semibold inline-flex items-center justify-center gap-1.5 transition text-xs sm:text-sm hover:underline" 
                                                   title="laporan-kinerja-{{ Str::slug($item->pegawai->nama ?? 'pegawai') }}-{{ \Carbon\Carbon::create()->month($item->bulan)->translatedFormat('F') }}-{{ $item->tahun }}.pdf">
                                                    <svg class="w-4 h-4 shrink-0 text-[#0b602b]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg>
                                                    <span>Laporan Kinerja.pdf</span>
                                                </a>
                                            </td>

                                            <!-- Status -->
                                            <td class="py-2.5 px-3 text-center whitespace-nowrap align-middle">
                                                @if($item->status === 'menunggu')
                                                    <span class="inline-flex items-center whitespace-nowrap px-2.5 py-1 text-[10px] font-extrabold rounded-full bg-red-50 text-red-600 border border-red-200 uppercase tracking-wider">
                                                        BELUM DIREVIEW
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center whitespace-nowrap px-2.5 py-1 text-[10px] font-extrabold rounded-full bg-green-50 text-green-700 border border-green-200 uppercase tracking-wider">
                                                        SUDAH DIREVIEW
                                                    </span>
                                                @endif
                                            </td>

                                            <!-- Aksi -->
                                            <td class="py-2.5 px-3 text-center whitespace-nowrap align-middle">
                                                @if($item->nilai || $item->status !== 'menunggu')
                                                    <a href="{{ route('laporan-mingguan.show', $item->id) }}" 
                                                       class="text-[#0e622b] hover:text-[#0b4d22] p-1.5 rounded-lg hover:bg-emerald-50 transition inline-flex items-center justify-center"
                                                       title="Lihat Rincian Penilaian">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                                    </a>
                                                @else
                                                    <a href="{{ route('laporan-mingguan.nilai-form', $item->id) }}" 
                                                       class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg font-semibold text-xs text-white bg-[#0b602b] hover:bg-[#084d22] transition shadow-xs"
                                                       title="Beri Penilaian Laporan">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                                        <span>Nilai</span>
                                                    </a>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="py-8 text-center text-sm text-gray-400">Tidak ada laporan bulanan terbaru.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                </div>
            </div>
        </div>

        <!-- Render dynamic Chart.js for Weekly Attendance Breakdown -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.0/chart.umd.min.js" defer></script>
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const canvasEl = document.getElementById('weeklyAttendanceChart');
                if (!canvasEl) return;

                @php
                    $chartLabels = collect($mingguan ?? [])->map(function ($w) {
                        return [$w['label'], $w['sublabel']];
                    });
                    $hadirData = collect($mingguan ?? [])->pluck('hadir');
                    $terlambatData = collect($mingguan ?? [])->pluck('terlambat');
                    $izinData = collect($mingguan ?? [])->pluck('izin');
                    $wfhData = collect($mingguan ?? [])->pluck('wfh');
                    $maxWeeklyTotal = collect($mingguan ?? [])->max('total') ?? 0;
                    $suggestedMaxY = $maxWeeklyTotal > 0 ? (int) (ceil(($maxWeeklyTotal + 8) / 10) * 10) : 60;
                    if ($suggestedMaxY < 30) $suggestedMaxY = 30;
                @endphp

                const weeklyLabels = {!! json_encode($chartLabels) !!};
                const hadirData = {!! json_encode($hadirData) !!};
                const terlambatData = {!! json_encode($terlambatData) !!};
                const izinData = {!! json_encode($izinData) !!};
                const wfhData = {!! json_encode($wfhData) !!};

                // Custom plugin to draw numbers inside segments and total on top
                const stackedBarLabelsPlugin = {
                    id: 'stackedBarLabels',
                    afterDatasetsDraw(chart) {
                        const { ctx } = chart;
                        const datasets = chart.data.datasets;
                        if (!datasets.length) return;
                        const meta0 = chart.getDatasetMeta(0);
                        if (!meta0 || !meta0.data) return;
                        const barCount = meta0.data.length;

                        for (let i = 0; i < barCount; i++) {
                            let total = 0;
                            let topY = null;
                            let barX = null;

                            for (let d = 0; d < datasets.length; d++) {
                                const meta = chart.getDatasetMeta(d);
                                if (!meta || !meta.data[i]) continue;
                                const element = meta.data[i];
                                const val = Number(datasets[d].data[i]) || 0;

                                if (val > 0) {
                                    total += val;
                                    barX = element.x;
                                    if (topY === null || element.y < topY) {
                                        topY = element.y;
                                    }

                                    const segHeight = Math.abs(element.base - element.y);
                                    const segCenterY = (element.base + element.y) / 2;

                                    if (segHeight >= 14) {
                                        ctx.save();
                                        ctx.fillStyle = '#ffffff';
                                        ctx.font = '600 11px Inter, system-ui, sans-serif';
                                        ctx.textAlign = 'center';
                                        ctx.textBaseline = 'middle';
                                        ctx.fillText(val, element.x, segCenterY);
                                        ctx.restore();
                                    }
                                }
                            }

                            if (total > 0 && barX !== null && topY !== null) {
                                ctx.save();
                                ctx.fillStyle = '#1f2937';
                                ctx.font = 'bold 13px Inter, system-ui, sans-serif';
                                ctx.textAlign = 'center';
                                ctx.textBaseline = 'bottom';
                                ctx.fillText(total, barX, topY - 6);
                                ctx.restore();
                            }
                        }
                    }
                };

                new Chart(canvasEl.getContext('2d'), {
                    type: 'bar',
                    data: {
                        labels: weeklyLabels,
                        datasets: [
                            {
                                label: 'Hadir',
                                data: hadirData,
                                backgroundColor: '#2e9e62',
                                hoverBackgroundColor: '#278853',
                                stack: 'kehadiran',
                                borderRadius: 3,
                            },
                            {
                                label: 'Terlambat',
                                data: terlambatData,
                                backgroundColor: '#eb5757',
                                hoverBackgroundColor: '#d44646',
                                stack: 'kehadiran',
                                borderRadius: 3,
                            },
                            {
                                label: 'Izin',
                                data: izinData,
                                backgroundColor: '#9b51e0',
                                hoverBackgroundColor: '#873ec9',
                                stack: 'kehadiran',
                                borderRadius: 3,
                            },
                            {
                                label: 'WFH',
                                data: wfhData,
                                backgroundColor: '#53aee8',
                                hoverBackgroundColor: '#3b9ad6',
                                stack: 'kehadiran',
                                borderRadius: 3,
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        categoryPercentage: 0.55,
                        barPercentage: 0.85,
                        interaction: {
                            mode: 'index',
                            intersect: false,
                        },
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                enabled: true,
                                backgroundColor: '#1f2937',
                                titleFont: { size: 12, family: 'Inter', weight: 'bold' },
                                bodyFont: { size: 11, family: 'Inter', weight: 'normal' },
                                padding: 10,
                                cornerRadius: 8,
                                callbacks: {
                                    label: function(context) {
                                        return ' ' + context.dataset.label + ': ' + context.parsed.y + ' orang';
                                    }
                                }
                            }
                        },
                        scales: {
                            x: {
                                stacked: true,
                                grid: { display: false, drawBorder: false },
                                ticks: {
                                    color: '#374151',
                                    font: { size: 11, family: 'Inter', weight: '500' },
                                    maxRotation: 0,
                                    minRotation: 0,
                                }
                            },
                            y: {
                                stacked: true,
                                beginAtZero: true,
                                suggestedMax: {{ $suggestedMaxY }},
                                grid: {
                                    display: true,
                                    color: '#f3f4f6',
                                    drawBorder: false
                                },
                                ticks: {
                                    display: true,
                                    color: '#9ca3af',
                                    font: { size: 11, family: 'Inter', weight: '500' },
                                    stepSize: 10,
                                    callback: function(value) {
                                        if (Number.isInteger(value)) {
                                            return value;
                                        }
                                    }
                                }
                            }
                        }
                    },
                    plugins: [stackedBarLabelsPlugin]
                });
            });
        </script>
    @elseif(auth()->user()->role === 'atasan')
        <!-- REDESIGNED ATASAN DASHBOARD -->
        <div class="space-y-8">
            <!-- HERO BANNER ATASAN -->
            <div class="relative overflow-hidden mb-5 sm:mb-6 rounded-2xl shadow-sm min-h-[110px] sm:min-h-[125px] md:min-h-[135px] flex flex-col justify-center px-6 sm:px-8 md:px-10 py-8 sm:py-5">

                <!-- Background Image -->
                <img 
                    src="{{ asset('images/brmp_meeting_photo.jpeg') }}"
                    alt="Background Gedung"
                    fetchpriority="high"
                    decoding="async"
                    width="1200"
                    height="300"
                    class="absolute inset-0 w-full h-full object-cover"
                    style="object-position: center;"
                >

                <!-- Green Overlay -->
                <div class="absolute inset-0"
                    style="background: linear-gradient(
                        90deg,
                        rgba(0, 105, 55, 0.96) 0%,
                        rgba(0, 105, 55, 0.88) 40%,
                        rgba(0, 105, 55, 0.65) 70%,
                        rgba(0, 105, 55, 0.45) 100%
                    );">
                </div>

                <!-- Content -->
                <div class="relative z-10">

                    <h2 class="font-black text-white leading-tight mb-1 sm:mb-1.5 text-xl sm:text-2xl md:text-[26px] lg:text-[28px]">
                        Selamat Datang,
                        <span style="color: #FFD700;">
                            {{ explode(' ', auth()->user()->name)[0] }}!
                        </span>
                    </h2>

                    <p class="text-white text-xs sm:text-sm font-medium max-w-2xl"
                    style="text-shadow: 0 1px 3px rgba(0,0,0,0.25);">
                        Pantau kehadiran dan kelola laporan tim Divisi {{ $divisiName }} hari ini
                    </p>

                </div>
            </div>
        </div>

            <!-- STATS CARDS -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <!-- Card 1: Izin Tertunda -->
                <div class="bg-white rounded-2xl border border-gray-200 p-5 flex items-center gap-4 shadow-sm hover:shadow-md transition">
                    <div class="w-12 h-12 rounded-xl flex items-center justify-center shrink-0" style="background-color: #fef2f2; color: #BA1A1A;">
                        <svg class="w-6 h-6" style="color: #BA1A1A;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Izin Tertunda</p>
                        <h4 class="text-2xl font-black text-gray-900 mt-0.5">{{ $izinTertunda }} Permohonan</h4>
                    </div>
                </div>

                <!-- Card 2: Laporan Perlu Direview -->
                <div class="bg-white rounded-2xl border border-gray-200 p-5 flex items-center gap-4 shadow-sm hover:shadow-md transition">
                    <div class="w-12 h-12 rounded-xl flex items-center justify-center shrink-0" style="background-color: #dbeafe; color: #1d4ed8;">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Laporan Perlu Direview</p>
                        <h4 class="text-2xl font-black text-gray-900 mt-0.5">{{ $laporanPerluReview }} Permohonan</h4>
                    </div>
                </div>

                <!-- Card 3: Jumlah Pegawai -->
                <div class="bg-white rounded-2xl border border-gray-200 p-5 flex items-center gap-4 shadow-sm hover:shadow-md transition">
                    <div class="w-12 h-12 rounded-xl flex items-center justify-center shrink-0" style="background-color: #dcfce7; color: #047857;">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Jumlah Pegawai</p>
                        <h4 class="text-2xl font-black text-gray-900 mt-0.5">{{ $jumlahPegawai }} Orang</h4>
                    </div>
                </div>
            </div>

            <!-- TWO COLUMN TABLES -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-8 items-stretch">
                <!-- Permintaan Izin Terbaru -->
                <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 flex flex-col justify-between h-full">
                    <div>
                        <div class="flex justify-between items-center mb-6">
                            <h4 class="text-base font-bold text-gray-800">Permintaan Izin Terbaru</h4>
                            <a href="{{ route('izin.index') }}" class="text-xs font-bold text-[#0e622b] hover:underline">Lihat Semua</a>
                        </div>
                        <div class="overflow-x-auto rounded-xl border border-gray-200">
                            <table class="w-full text-sm text-center border-collapse">
                                <thead class="bg-[#0b602b] text-white uppercase">
                                    <tr class="h-11">
                                        <th scope="col" class="px-4 py-3 text-xs font-semibold uppercase tracking-wider text-white text-center whitespace-nowrap">NAMA PEGAWAI</th>
                                        <th scope="col" class="px-4 py-3 text-xs font-semibold uppercase tracking-wider text-white text-center whitespace-nowrap">JENIS IZIN</th>
                                        <th scope="col" class="px-4 py-3 text-xs font-semibold uppercase tracking-wider text-white text-center whitespace-nowrap">DURASI</th>
                                        <th scope="col" class="px-4 py-3 text-xs font-semibold uppercase tracking-wider text-white text-center whitespace-nowrap">LAMPIRAN</th>
                                        <th scope="col" class="px-4 py-3 text-xs font-semibold uppercase tracking-wider text-white text-center whitespace-nowrap">STATUS</th>
                                        <th scope="col" class="px-4 py-3 text-xs font-semibold uppercase tracking-wider text-white text-center whitespace-nowrap">AKSI</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @forelse ($izinTerbaruAtasan as $item)
                                        @php
                                            $durasiHari = \Carbon\Carbon::parse($item->tanggal_mulai)->diffInDays(\Carbon\Carbon::parse($item->tanggal_selesai)) + 1;
                                        @endphp
                                        <tr class="hover:bg-gray-50/50 transition-colors h-[64px]">
                                            <!-- Nama Pegawai -->
                                            <td class="py-2.5 px-3 font-bold text-gray-800 text-center text-xs sm:text-sm leading-snug align-middle whitespace-nowrap">
                                                {{ $item->pegawai->nama ?? '-' }}
                                            </td>

                                            <!-- Jenis Izin -->
                                            <td class="py-2.5 px-3 text-center whitespace-nowrap align-middle">
                                                <span class="font-medium text-gray-800 text-xs sm:text-sm">{{ ucfirst(str_replace('_', ' ', $item->jenis)) }}</span>
                                                @if ($item->jenis === 'sakit')
                                                    <span class="text-[11px] font-normal text-gray-400 block">
                                                        {{ $item->ada_surat_dokter ? 'Dengan surat dokter' : 'Tanpa surat dokter' }}
                                                    </span>
                                                @endif
                                            </td>

                                            <!-- Durasi -->
                                            <td class="py-2.5 px-3 text-xs sm:text-sm text-gray-600 whitespace-nowrap text-center align-middle">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-100 whitespace-nowrap">
                                                    {{ $durasiHari }} Hari
                                                </span>
                                            </td>

                                            <!-- Lampiran -->
                                            <td class="py-2.5 px-3 text-center whitespace-nowrap align-middle">
                                                @if ($item->file_lampiran)
                                                    <a href="{{ route('izin.preview', $item->id) }}" target="_blank" aria-label="Lihat Lampiran Izin {{ $item->pegawai->nama ?? '' }}" class="p-1.5 rounded-lg text-[#0b602b] hover:bg-emerald-50 transition inline-block" title="Lihat Lampiran">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
                                                        </svg>
                                                    </a>
                                                @else
                                                    <span class="text-gray-400 text-xs">-</span>
                                                @endif
                                            </td>

                                            <!-- Status -->
                                            <td class="py-2.5 px-3 text-center whitespace-nowrap align-middle">
                                                @if($item->status === 'disetujui')
                                                    <span class="inline-block px-2.5 py-1 text-[10px] font-extrabold rounded-full bg-[#dcfce7] text-[#0b602b] uppercase tracking-wider">
                                                        DISETUJUI
                                                    </span>
                                                @elseif($item->status === 'disetujui_atasan')
                                                    <span class="inline-block px-2.5 py-1 text-[10px] font-extrabold rounded-full bg-[#e0f2fe] text-[#0369a1] uppercase tracking-wider">
                                                        DISETUJUI ATASAN
                                                    </span>
                                                @elseif($item->status === 'ditolak')
                                                    <span class="inline-block px-2.5 py-1 text-[10px] font-extrabold rounded-full bg-[#BA1A1A] text-white uppercase tracking-wider shadow-sm">
                                                        DITOLAK
                                                    </span>
                                                @else
                                                    <span class="inline-block px-2.5 py-1 text-[10px] font-extrabold rounded-full bg-[#fef3c7] text-[#b45309] uppercase tracking-wider">
                                                        PENDING
                                                    </span>
                                                @endif
                                            </td>

                                            <!-- Aksi -->
                                            <td class="py-2.5 px-3 text-center whitespace-nowrap align-middle">
                                                <a href="{{ route('izin.show', $item->id) }}" aria-label="Lihat Detail Izin {{ $item->pegawai->nama ?? '' }}" class="text-[#0e622b] hover:text-[#0b4d22] p-1.5 rounded-lg hover:bg-emerald-50 transition inline-flex items-center justify-center" title="Lihat Detail Izin">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="py-8 text-center text-sm text-gray-400">Belum ada permintaan izin terbaru.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Aktivitas Laporan Terbaru -->
                <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 flex flex-col justify-between h-full">
                    <div>
                        <div class="flex justify-between items-center mb-6">
                            <h4 class="text-base font-bold text-gray-800">Aktivitas Laporan Terbaru</h4>
                            <a href="{{ route('laporan-mingguan.index') }}" class="text-xs font-bold text-[#0e622b] hover:underline">Lihat Semua</a>
                        </div>
                        <div class="overflow-x-auto rounded-xl border border-gray-200">
                            <table class="w-full text-sm text-center border-collapse">
                                <thead class="bg-[#0b602b] text-white uppercase">
                                    <tr class="h-11">
                                        <th scope="col" class="px-4 py-3 text-xs font-semibold uppercase tracking-wider text-white text-center whitespace-nowrap">NAMA PEGAWAI</th>
                                        <th scope="col" class="px-4 py-3 text-xs font-semibold uppercase tracking-wider text-white text-center whitespace-nowrap">BULAN/TAHUN</th>
                                        <th scope="col" class="px-4 py-3 text-xs font-semibold uppercase tracking-wider text-white text-center whitespace-nowrap">LAPORAN</th>
                                        <th scope="col" class="px-4 py-3 text-xs font-semibold uppercase tracking-wider text-white text-center whitespace-nowrap">STATUS</th>
                                        <th scope="col" class="px-4 py-3 text-xs font-semibold uppercase tracking-wider text-white text-center whitespace-nowrap">AKSI</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @forelse ($laporanTerbaruAtasan as $item)
                                        <tr class="hover:bg-gray-50/50 transition-colors h-[64px]">
                                            <!-- Nama Pegawai -->
                                            <td class="py-2.5 px-3 font-bold text-gray-800 text-center text-xs sm:text-sm leading-snug align-middle whitespace-nowrap">
                                                {{ $item->pegawai->nama ?? '-' }}
                                            </td>

                                            <!-- Bulan / Tahun -->
                                            <td class="py-2.5 px-3 text-xs sm:text-sm text-gray-600 text-center whitespace-nowrap align-middle">
                                                {{ \Carbon\Carbon::create()->month($item->bulan)->translatedFormat('F') }} {{ $item->tahun }}
                                            </td>

                                            <!-- Laporan (Preview Link) -->
                                            <td class="py-2.5 px-3 text-center whitespace-nowrap align-middle">
                                                <a href="{{ route('laporan-mingguan.preview', $item->id) }}" target="_blank" 
                                                   class="text-[#0e622b] hover:text-[#0b4d22] font-semibold inline-flex items-center justify-center gap-1.5 transition text-xs sm:text-sm hover:underline" 
                                                   title="laporan-kinerja-{{ Str::slug($item->pegawai->nama ?? 'pegawai') }}-{{ \Carbon\Carbon::create()->month($item->bulan)->translatedFormat('F') }}-{{ $item->tahun }}.pdf">
                                                    <svg class="w-4 h-4 shrink-0 text-[#0b602b]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg>
                                                    <span>Laporan Kinerja.pdf</span>
                                                </a>
                                            </td>

                                            <!-- Status -->
                                            <td class="py-2.5 px-3 text-center whitespace-nowrap align-middle">
                                                @if($item->status === 'menunggu')
                                                    <span class="inline-flex items-center whitespace-nowrap px-2.5 py-1 text-[10px] font-extrabold rounded-full bg-red-50 text-red-600 border border-red-200 uppercase tracking-wider">
                                                        BELUM DIREVIEW
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center whitespace-nowrap px-2.5 py-1 text-[10px] font-extrabold rounded-full bg-green-50 text-green-700 border border-green-200 uppercase tracking-wider">
                                                        SUDAH DIREVIEW
                                                    </span>
                                                @endif
                                            </td>

                                            <!-- Aksi -->
                                            <td class="py-2.5 px-3 text-center whitespace-nowrap align-middle">
                                                @if($item->nilai || $item->status !== 'menunggu')
                                                    <a href="{{ route('laporan-mingguan.show', $item->id) }}" 
                                                       class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg font-semibold text-xs text-[#0b602b] bg-emerald-50 hover:bg-emerald-100 border border-emerald-200 transition shadow-xs"
                                                       title="Lihat Rincian Penilaian">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                                        <span>Lihat Penilaian</span>
                                                    </a>
                                                @else
                                                    <a href="{{ route('laporan-mingguan.nilai-form', $item->id) }}" 
                                                       class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg font-semibold text-xs text-white bg-[#0b602b] hover:bg-[#084d22] transition shadow-xs"
                                                       title="Beri Penilaian Laporan">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                                        <span>Nilai</span>
                                                    </a>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="py-8 text-center text-sm text-gray-400">Belum ada aktivitas laporan terbaru.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <!-- STANDARD USER/SUPERVISOR DASHBOARD -->
        <!-- HERO BANNER USER -->
            <div class="relative overflow-hidden mb-5 sm:mb-6 rounded-2xl shadow-sm min-h-[110px] sm:min-h-[125px] md:min-h-[135px] flex flex-col justify-center px-6 sm:px-8 md:px-10 py-8 sm:py-5">

            <!-- Background Image -->
            <img 
                src="{{ asset('images/brmp_meeting_photo.jpeg') }}"
                alt="Background Gedung"
                fetchpriority="high"
                decoding="async"
                width="1200"
                height="300"
                class="absolute inset-0 w-full h-full object-cover"
                style="object-position: center;"
            >

            <!-- Green Overlay -->
            <div class="absolute inset-0"
                style="background: linear-gradient(
                    90deg,
                    rgba(0, 105, 55, 0.96) 0%,
                    rgba(0, 105, 55, 0.88) 40%,
                    rgba(0, 105, 55, 0.65) 70%,
                    rgba(0, 105, 55, 0.45) 100%
                );">
            </div>

            <!-- Content -->
            <div class="relative z-10">

                <!-- Welcome Text -->
                <h2 class="font-black text-white leading-tight mb-1 sm:mb-1.5 text-xl sm:text-2xl md:text-[26px] lg:text-[28px]">
                    Selamat Datang,
                    <span style="color: #FFD700;">
                        {{ explode(' ', auth()->user()->name)[0] }}!
                    </span>
                </h2>

                <!-- Description -->
                <p class="text-white text-xs sm:text-sm font-medium max-w-2xl"
                    style="text-shadow: 0 1px 3px rgba(0,0,0,0.25);">
                    Hari ini adalah {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}.
                    Semoga aktivitas kerja Anda berjalan lancar
                </p>

            </div>
        </div>

        <!-- DASHBOARD PEGAWAI CONTENT -->
        <div x-data="pegawaiDashboard({!! htmlspecialchars(json_encode($attendanceData), ENT_QUOTES, 'UTF-8') !!}, {{ $currentMonth }}, {{ $currentYear }})"
             x-init="initChart()"
             class="flex flex-col gap-6">
            
            <!-- 1. Akses Cepat Cards (Grid 2 Kolom di Bagian Atas) -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <!-- Form Presensi WFH -->
                <a href="{{ route('absen-wfh.create') }}" class="bg-white border border-gray-200 hover:border-emerald-300 rounded-2xl p-4 sm:p-5 flex items-center justify-between gap-4 transition-all duration-200 hover:shadow-md hover:-translate-y-0.5 group shadow-xs">
                    <div class="flex items-center gap-3.5 min-w-0">
                        <div class="w-12 h-12 rounded-xl bg-[#f2f7f2] border border-[#e2ede2] flex items-center justify-center text-[#137333] shrink-0 group-hover:scale-105 transition-transform">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                        </div>
                        <div class="min-w-0">
                            <h3 class="text-[15px] font-bold text-gray-800 leading-snug group-hover:text-[#0b602b] transition-colors">Form Presensi WFH</h3>
                            <p class="text-xs text-gray-500 mt-0.5 leading-tight">Laporan aktivitas WFH hari ini</p>
                        </div>
                    </div>
                    <svg class="w-4 h-4 text-gray-400 group-hover:text-[#0b602b] group-hover:translate-x-0.5 transition-all shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>

                <!-- Unggah Laporan -->
                <a href="{{ route('laporan-mingguan.index') }}" class="bg-white border border-gray-200 hover:border-emerald-300 rounded-2xl p-4 sm:p-5 flex items-center justify-between gap-4 transition-all duration-200 hover:shadow-md hover:-translate-y-0.5 group shadow-xs">
                    <div class="flex items-center gap-3.5 min-w-0">
                        <div class="w-12 h-12 rounded-xl bg-[#f2f7f2] border border-[#e2ede2] flex items-center justify-center text-[#137333] shrink-0 group-hover:scale-105 transition-transform">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <div class="min-w-0">
                            <h3 class="text-[15px] font-bold text-gray-800 leading-snug group-hover:text-[#0b602b] transition-colors">Unggah Laporan</h3>
                            <p class="text-xs text-gray-500 mt-0.5 leading-tight">Dokumentasi hasil bulanan</p>
                        </div>
                    </div>
                    <svg class="w-4 h-4 text-gray-400 group-hover:text-[#0b602b] group-hover:translate-x-0.5 transition-all shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>

            <!-- 2. PENGUMUMAN & REKAP KEHADIRAN BERSAMPINGAN (GRID 2 KOLOM) -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-start">
                
                <!-- KOLOM KIRI: Pengumuman Penting Card -->
                <div class="flex flex-col h-full">
                    <div class="relative overflow-hidden rounded-2xl sm:rounded-3xl p-4 sm:p-6 border border-[#d2e4cf] shadow-xs flex flex-col justify-between h-full"
                         style="background: linear-gradient(135deg, #dcebda 0%, #edf5e7 50%, #e2eed5 100%);">
                        
                        <div>
                            <!-- Header badge with megaphone icon -->
                            <div class="flex items-center justify-between gap-2 mb-3 sm:mb-4">
                                <div class="flex items-center gap-2 sm:gap-2.5 text-[#1b4324] min-w-0">
                                    <svg class="w-5 h-5 sm:w-6 sm:h-6 text-[#137333] shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>
                                    </svg>
                                    <h3 class="text-lg sm:text-xl font-bold text-[#1b4324] tracking-tight truncate">Pengumuman Terbaru</h3>
                                </div>
                            </div>

                            <!-- Announcement List (Ukuran kompak 5 pengumuman setara card sebelah) -->
                            <div class="space-y-2 mb-4 sm:mb-3.5 max-h-[520px] overflow-y-auto pr-0.5">
                                @forelse(($pengumumanTerbaru ?? collect())->take(5) as $item)
                                    <div class="p-2.5 sm:p-3 rounded-xl bg-white border border-gray-200/90 shadow-2xs hover:shadow-xs hover:border-emerald-300 transition-all">
                                        <div class="flex items-center justify-between gap-2 mb-1">
                                            <div class="flex items-center flex-wrap gap-1.5">
                                                @if($item->kategori)
                                                    <span class="inline-flex items-center px-2 py-0.2 rounded-full text-[10px] font-bold bg-emerald-100 text-[#0b602b] border border-emerald-200">
                                                        {{ $item->kategori }}
                                                    </span>
                                                @endif
                                                <span class="text-[11px] text-gray-500 font-medium">
                                                    {{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d F Y') }}
                                                </span>
                                            </div>

                                            @if($item->file_lampiran)
                                                <span class="inline-flex items-center gap-1 text-[10px] font-semibold text-[#0b602b] bg-emerald-50 px-1.5 py-0.2 rounded border border-emerald-200">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
                                                    <span>Lampiran</span>
                                                </span>
                                            @endif
                                        </div>

                                        <a href="{{ route('pengumuman.show', $item->id) }}" class="text-sm font-bold text-gray-900 hover:text-[#0b602b] transition-colors block leading-snug truncate">
                                            {{ $item->judul }}
                                        </a>

                                        <p class="text-gray-600 text-xs leading-relaxed mt-1 line-clamp-2">
                                            {{ $item->isi }}
                                        </p>
                                        <div class="mt-1">
                                            <a href="{{ route('pengumuman.show', $item->id) }}" class="text-[#0b602b] hover:text-[#084d22] font-bold hover:underline whitespace-nowrap text-xs inline-block">Baca Selengkapnya...</a>
                                        </div>
                                    </div>
                                @empty
                                    <div class="p-5 rounded-xl bg-white border border-gray-200 text-center text-gray-500 text-xs">
                                        Belum ada pengumuman terbaru.
                                    </div>
                                @endforelse
                            </div>
                        </div>

                        <!-- Action Button -->
                        <div class="pt-3 sm:pt-1 mt-1 sm:mt-0 flex justify-end">
                            <a href="{{ route('pengumuman.index') }}" class="inline-flex items-center justify-center px-3.5 py-1.5 rounded-full bg-[#0b602b] hover:bg-[#084a21] text-white font-medium text-xs transition-all duration-200 shadow-xs hover:shadow">
                                Lihat Semua Pengumuman
                            </a>
                        </div>
                    </div>
                </div>

                <!-- KOLOM KANAN: Rekap Kehadiran & Detail List -->
                <div class="flex flex-col gap-4">
                
                <!-- 1. Rekap Kehadiran Card (Solid Green) -->
                <div class="rounded-2xl sm:rounded-3xl p-6 sm:p-7 text-white shadow-sm flex flex-col justify-between"
                     style="background: #0b602b;">
                    
                    <!-- Header with Title, Month Filter & Year Filter Dropdowns -->
                    <div class="flex flex-wrap items-center justify-between gap-2.5 mb-2">
                        <h3 class="text-xl font-bold text-white tracking-tight shrink-0">Rekap Kehadiran</h3>

                        <!-- Filter Bulan & Tahun Dropdowns Side-by-Side -->
                        <div class="flex items-center gap-2">
                            <!-- Month Selector Dropdown -->
                            <select x-model.number="selectedMonth"
                                    aria-label="Pilih Bulan Rekap"
                                    @change="onFilterChange()"
                                    class="bg-white text-gray-800 hover:text-gray-900 text-xs font-bold py-1.5 pl-3 pr-8 rounded-xl border border-white shadow-sm outline-none cursor-pointer transition-all">
                                @foreach($monthsList as $mNum => $mName)
                                    <option value="{{ $mNum }}" class="text-gray-900 bg-white font-semibold">
                                        {{ $mName }}
                                    </option>
                                @endforeach
                            </select>

                            <!-- Year Selector Dropdown -->
                            <select x-model.number="selectedYear"
                                    aria-label="Pilih Tahun Rekap"
                                    @change="onFilterChange()"
                                    class="bg-white text-gray-800 hover:text-gray-900 text-xs font-bold py-1.5 pl-3 pr-8 rounded-xl border border-white shadow-sm outline-none cursor-pointer transition-all">
                                @foreach($availableYears as $y)
                                    <option value="{{ $y }}" class="text-gray-900 bg-white font-semibold">
                                        {{ $y }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Donut Chart Canvas Container (Fixed 180px square to prevent collapsing on mobile) -->
                    <div class="my-2 flex items-center justify-center w-full" style="height: 180px;">
                        <div style="position: relative; width: 180px; height: 180px; margin: 0 auto;">
                            <canvas id="rekapDonutChart" width="180" height="180"></canvas>
                            
                            <!-- Center Donut Content -->
                            <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none">
                                <div class="w-20 h-20 bg-white rounded-full flex flex-col items-center justify-center shadow-inner text-center px-1 transition-all duration-300">
                                    <span class="text-2xl font-extrabold text-gray-900 leading-none" x-text="centerNumber">
                                        0
                                    </span>
                                    <span class="text-[10px] font-bold text-gray-700 leading-tight mt-0.5 truncate max-w-[65px] uppercase" x-text="centerLabel">
                                        HARI
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Legend Items (Fixed Colors & Clickable to filter table tab) -->
                    <div class="flex items-center justify-center flex-wrap gap-2.5 sm:gap-4 mt-3 pt-2 text-xs font-medium text-white uppercase tracking-wider">
                        <button type="button" 
                                @click="setTab('hadir')" 
                                class="flex items-center gap-1.5 px-2.5 py-1 rounded-lg transition-all cursor-pointer font-bold"
                                :class="activeTab === 'hadir' ? 'bg-white/20 font-bold shadow-xs scale-105' : 'opacity-80 hover:opacity-100 hover:bg-white/10'">
                            <span class="w-3.5 h-3.5 rounded-xs border border-white/30 shrink-0 shadow-xs" style="background: #4ade80;"></span>
                            <span>HADIR TEPAT WAKTU (<span x-text="currentMonthData.hadir_tepat_waktu || 0"></span>)</span>
                        </button>
                        <button type="button" 
                                @click="setTab('terlambat')" 
                                class="flex items-center gap-1.5 px-2.5 py-1 rounded-lg transition-all cursor-pointer font-bold"
                                :class="activeTab === 'terlambat' ? 'bg-white/20 font-bold shadow-xs scale-105' : 'opacity-80 hover:opacity-100 hover:bg-white/10'">
                            <span class="w-3.5 h-3.5 rounded-xs border border-white/30 shrink-0 shadow-xs" style="background: #ffd700;"></span>
                            <span>TERLAMBAT (<span x-text="currentMonthData.terlambat || 0"></span>)</span>
                        </button>
                        <button type="button" 
                                @click="setTab('tidak_hadir')" 
                                class="flex items-center gap-1.5 px-2.5 py-1 rounded-lg transition-all cursor-pointer font-bold"
                                :class="activeTab === 'tidak_hadir' ? 'bg-white/20 font-bold shadow-xs scale-105' : 'opacity-80 hover:opacity-100 hover:bg-white/10'">
                            <span class="w-3.5 h-3.5 rounded-xs border border-white/30 shrink-0 shadow-xs" style="background: #f87171;"></span>
                            <span>TIDAK HADIR (<span x-text="currentMonthData.tidak_hadir || 0"></span>)</span>
                        </button>
                    </div>
                </div>

                <!-- 2. Attendance Details Tabs & Box -->
                <div class="flex flex-col">
                    <!-- Tabs Navigation -->
                    <div class="flex items-center gap-5 sm:gap-6 border-b border-gray-200 px-2 uppercase tracking-wider">
                        <button @click="setTab('hadir')"
                                type="button"
                                class="pb-2 text-sm font-bold transition-all relative cursor-pointer flex items-center gap-1.5"
                                :class="activeTab === 'hadir' ? 'text-[#0b602b] border-b-2 border-[#0b602b]' : 'text-gray-500 hover:text-gray-800'">
                            <span>HADIR TEPAT WAKTU</span>
                            <span class="text-[11px] px-1.5 py-0.2 rounded-full font-semibold"
                                  :class="activeTab === 'hadir' ? 'bg-emerald-100 text-[#0b602b]' : 'bg-gray-100 text-gray-500'"
                                  x-text="currentMonthData.hadir_tepat_waktu || 0"></span>
                        </button>
                        <button @click="setTab('terlambat')"
                                type="button"
                                class="pb-2 text-sm font-bold transition-all relative cursor-pointer flex items-center gap-1.5"
                                :class="activeTab === 'terlambat' ? 'text-[#0b602b] border-b-2 border-[#0b602b]' : 'text-gray-500 hover:text-gray-800'">
                            <span>TERLAMBAT</span>
                            <span class="text-[11px] px-1.5 py-0.2 rounded-full font-semibold"
                                  :class="activeTab === 'terlambat' ? 'bg-amber-100 text-amber-800' : 'bg-gray-100 text-gray-500'"
                                  x-text="currentMonthData.terlambat || 0"></span>
                        </button>
                        <button @click="setTab('tidak_hadir')"
                                type="button"
                                class="pb-2 text-sm font-bold transition-all relative cursor-pointer flex items-center gap-1.5"
                                :class="activeTab === 'tidak_hadir' ? 'text-[#0b602b] border-b-2 border-[#0b602b]' : 'text-gray-500 hover:text-gray-800'">
                            <span>TIDAK HADIR</span>
                            <span class="text-[11px] px-1.5 py-0.2 rounded-full font-semibold"
                                  :class="activeTab === 'tidak_hadir' ? 'bg-red-100 text-red-700' : 'bg-gray-100 text-gray-500'"
                                  x-text="currentMonthData.tidak_hadir || 0"></span>
                        </button>
                    </div>

                    <!-- White Card Details Box -->
                    <div class="bg-white rounded-2xl border border-gray-200 p-4 sm:p-5 shadow-xs mt-3 min-h-[160px] flex flex-col justify-between">
                        
                        <div>
                            <!-- TAB: HADIR -->
                            <div x-show="activeTab === 'hadir'" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" style="display: none;">
                                <template x-if="currentMonthData.list_hadir && currentMonthData.list_hadir.length > 0">
                                    <div class="space-y-2.5">
                                        <template x-for="(item, idx) in currentMonthData.list_hadir.slice(0, 5)" :key="'hadir-' + idx">
                                            <div class="flex items-center justify-between text-xs p-2.5 rounded-xl bg-gray-50 hover:bg-gray-100/80 transition">
                                                <div class="flex items-center gap-2 min-w-0">
                                                    <span class="w-2 h-2 rounded-full shrink-0 bg-emerald-600"></span>
                                                    <span class="font-semibold text-gray-800 truncate" x-text="item.hari + ', ' + item.tanggal"></span>
                                                    <span class="text-[10px] text-emerald-700 bg-emerald-100 px-2 py-0.5 rounded-md font-bold uppercase tracking-wider">TEPAT WAKTU</span>
                                                </div>
                                                <div class="shrink-0 ml-2 text-right">
                                                    <span class="text-gray-500 font-medium" x-text="item.jam_masuk + ' (' + item.jenis.toUpperCase() + ')'"></span>
                                                </div>
                                            </div>
                                        </template>
                                    </div>
                                </template>
                                <template x-if="!currentMonthData.list_hadir || currentMonthData.list_hadir.length === 0">
                                    <div class="flex flex-col items-center justify-center py-6 text-center text-gray-400">
                                        <svg class="w-8 h-8 text-gray-300 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                        <p class="text-xs">Belum ada riwayat kehadiran tepat waktu pada bulan <span class="font-semibold" x-text="currentMonthData.bulan_nama + ' ' + selectedYear"></span>.</p>
                                    </div>
                                </template>
                            </div>

                            <!-- TAB: TERLAMBAT -->
                            <div x-show="activeTab === 'terlambat'" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0">
                                <template x-if="currentMonthData.list_terlambat && currentMonthData.list_terlambat.length > 0">
                                    <div class="space-y-2.5">
                                        <template x-for="(item, idx) in currentMonthData.list_terlambat.slice(0, 5)" :key="'terlambat-' + idx">
                                            <div class="flex items-center justify-between text-xs p-2.5 rounded-xl bg-amber-50/60 hover:bg-amber-50 transition border border-amber-100">
                                                <div class="flex items-center gap-2 min-w-0">
                                                    <span class="w-2 h-2 rounded-full bg-amber-500 shrink-0"></span>
                                                    <span class="font-semibold text-gray-800 truncate" x-text="item.hari + ', ' + item.tanggal"></span>
                                                    <span class="text-[10px] text-amber-700 bg-amber-100 px-2 py-0.5 rounded-md font-bold uppercase tracking-wider">TERLAMBAT</span>
                                                </div>
                                                <div class="text-right shrink-0 ml-2">
                                                    <span class="text-gray-700 font-medium" x-text="item.jam_masuk"></span>
                                                    <template x-if="item.menit_telat">
                                                        <span class="text-[10px] text-amber-700 font-bold ml-1" x-text="'(+' + item.menit_telat + 'm)'"></span>
                                                    </template>
                                                </div>
                                            </div>
                                        </template>
                                    </div>
                                </template>
                                <template x-if="!currentMonthData.list_terlambat || currentMonthData.list_terlambat.length === 0">
                                    <div class="flex flex-col items-center justify-center py-6 text-center text-gray-400">
                                        <svg class="w-8 h-8 text-gray-300 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        <p class="text-xs">Tidak ada keterlambatan tercatat pada bulan <span class="font-semibold" x-text="currentMonthData.bulan_nama + ' ' + selectedYear"></span>.</p>
                                    </div>
                                </template>
                            </div>

                            <!-- TAB: TIDAK HADIR -->
                            <div x-show="activeTab === 'tidak_hadir'" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" style="display: none;">
                                <template x-if="currentMonthData.list_tidak_hadir && currentMonthData.list_tidak_hadir.length > 0">
                                    <div class="space-y-2.5">
                                        <template x-for="(item, idx) in currentMonthData.list_tidak_hadir.slice(0, 5)" :key="'tidakhadir-' + idx">
                                            <div class="flex items-center justify-between text-xs p-2.5 rounded-xl bg-red-50/60 hover:bg-red-50 transition border border-red-100">
                                                <div class="flex items-center gap-2 min-w-0">
                                                    <span class="w-2 h-2 rounded-full bg-red-500 shrink-0"></span>
                                                    <span class="font-semibold text-gray-800 truncate" x-text="item.hari + ', ' + item.tanggal"></span>
                                                </div>
                                                <span class="text-red-700 font-bold uppercase tracking-wider shrink-0 ml-2 text-[11px]" x-text="item.keterangan"></span>
                                            </div>
                                        </template>
                                    </div>
                                </template>
                                <template x-if="!currentMonthData.list_tidak_hadir || currentMonthData.list_tidak_hadir.length === 0">
                                    <div class="flex flex-col items-center justify-center py-6 text-center text-gray-400">
                                        <svg class="w-8 h-8 text-gray-300 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5"/></svg>
                                        <p class="text-xs">Tidak ada izin atau ketidakhadiran pada bulan <span class="font-semibold" x-text="currentMonthData.bulan_nama + ' ' + selectedYear"></span>.</p>
                                    </div>
                                </template>
                            </div>
                        </div>

                        <!-- Footer Link: Direct ke Rekap Kehadiran Saya -->
                        <div class="mt-4 pt-3 border-t border-gray-100 flex items-center justify-between">
                            <span class="text-[11px] text-gray-400 font-medium">
                                Menampilkan 5 data teratas
                            </span>
                            <a :href="'{{ route('kehadiran.rekap-pribadi') }}?bulan=' + selectedMonth + '&tahun=' + selectedYear"
                                class="inline-flex items-center gap-1 text-xs font-bold text-[#0b602b] hover:text-[#084a21] hover:underline transition-colors group">
                                <span>Lihat Rekap Kehadiran Saya</span>
                                <svg class="w-3.5 h-3.5 group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>
                        </div>

                    </div>
                </div>

            </div>

        </div>

        <!-- Render Donut Chart for Pegawai & Script Logic -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.0/chart.umd.min.js" defer></script>
        <script>
            function pegawaiDashboard(attendanceData, initialMonth, initialYear) {
                return {
                    attendanceData: attendanceData,
                    selectedMonth: initialMonth,
                    selectedYear: initialYear,
                    activeTab: 'hadir',
                    chartInstance: null,

                    get currentMonthData() {
                        if (this.attendanceData[this.selectedYear] && this.attendanceData[this.selectedYear][this.selectedMonth]) {
                            return this.attendanceData[this.selectedYear][this.selectedMonth];
                        }
                        return {
                            hadir: 0,
                            hadir_tepat_waktu: 0,
                            terlambat: 0,
                            tidak_hadir: 0,
                            total_hari: 0,
                            bulan_nama: '',
                            tahun: this.selectedYear,
                            list_hadir: [],
                            list_terlambat: [],
                            list_tidak_hadir: []
                        };
                    },

                    get centerNumber() {
                        if (this.activeTab === 'hadir') return this.currentMonthData.hadir_tepat_waktu || 0;
                        if (this.activeTab === 'terlambat') return this.currentMonthData.terlambat || 0;
                        if (this.activeTab === 'tidak_hadir') return this.currentMonthData.tidak_hadir || 0;
                        return this.currentMonthData.total_hari || 0;
                    },

                    get centerLabel() {
                        if (this.activeTab === 'hadir') return 'TEPAT WAKTU';
                        if (this.activeTab === 'terlambat') return 'TERLAMBAT';
                        if (this.activeTab === 'tidak_hadir') return 'TIDAK HADIR';
                        return 'HARI';
                    },

                    initChart() {
                        const tryInit = () => {
                            if (typeof Chart === 'undefined') {
                                setTimeout(tryInit, 50);
                                return;
                            }
                            const canvas = document.getElementById('rekapDonutChart');
                            if (!canvas) return;

                            const ctx = canvas.getContext('2d');
                            const dataCounts = this.getChartData();
                            const bgColors = this.getColors();
                            const labels = this.getChartLabels();

                            this.chartInstance = new Chart(ctx, {
                                type: 'doughnut',
                                data: {
                                    labels: labels,
                                    datasets: [{
                                        data: dataCounts,
                                        backgroundColor: bgColors,
                                        borderWidth: 0,
                                        cutout: '68%'
                                    }]
                                },
                                options: {
                                    responsive: true,
                                    maintainAspectRatio: false,
                                    animation: {
                                        duration: 400,
                                        easing: 'easeOutQuart'
                                    },
                                    plugins: {
                                        legend: { display: false },
                                        tooltip: {
                                            backgroundColor: '#1f2937',
                                            titleFont: { size: 11, family: 'Inter' },
                                            bodyFont: { size: 11, family: 'Inter' },
                                            padding: 8,
                                            cornerRadius: 8,
                                            callbacks: {
                                                label: (context) => {
                                                    const total = (this.currentMonthData.hadir_tepat_waktu || 0) + 
                                                                  (this.currentMonthData.terlambat || 0) + 
                                                                  (this.currentMonthData.tidak_hadir || 0);
                                                    if (total === 0) {
                                                        return ' Belum ada data presensi';
                                                    }
                                                    return ' ' + context.label + ': ' + context.parsed + ' hari';
                                                }
                                            }
                                        }
                                    }
                                }
                            });
                        };
                        this.$nextTick(tryInit);
                    },

                    getChartData() {
                        const h = this.currentMonthData.hadir_tepat_waktu || 0;
                        const t = this.currentMonthData.terlambat || 0;
                        const th = this.currentMonthData.tidak_hadir || 0;

                        if (h === 0 && t === 0 && th === 0) {
                            return [1];
                        }
                        return [h, t, th];
                    },

                    getColors() {
                        const h = this.currentMonthData.hadir_tepat_waktu || 0;
                        const t = this.currentMonthData.terlambat || 0;
                        const th = this.currentMonthData.tidak_hadir || 0;

                        if (h === 0 && t === 0 && th === 0) {
                            return ['rgba(255, 255, 255, 0.35)'];
                        }

                        // Fixed intuitive colors: Green (Tepat Waktu), Yellow (Terlambat), Red (Tidak Hadir)
                        return ['#4ade80', '#ffd700', '#f87171'];
                    },

                    getChartLabels() {
                        const h = this.currentMonthData.hadir_tepat_waktu || 0;
                        const t = this.currentMonthData.terlambat || 0;
                        const th = this.currentMonthData.tidak_hadir || 0;

                        if (h === 0 && t === 0 && th === 0) {
                            return ['BELUM ADA DATA'];
                        }
                        return ['HADIR TEPAT WAKTU', 'TERLAMBAT', 'TIDAK HADIR'];
                    },

                    onFilterChange() {
                        this.updateChart();
                    },

                    setTab(tab) {
                        this.activeTab = tab;
                    },

                    updateChart() {
                        if (this.chartInstance) {
                            this.chartInstance.destroy();
                            this.chartInstance = null;
                        }
                        this.initChart();
                    }
                };
            }
        </script>
    @endif

</x-dashboard-layout>