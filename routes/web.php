<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\DivisiController;
use App\Http\Controllers\KehadiranController;
use App\Http\Controllers\IzinController;
use App\Http\Controllers\LaporanMingguanController;
use App\Http\Controllers\PengumumanController;
use App\Http\Controllers\AbsenWfhController;
use App\Http\Controllers\UpacaraController;
use App\Models\Pegawai;
use App\Models\Divisi;
use App\Models\Kehadiran;
use App\Models\Izin;
use App\Models\Setting;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LengkapiProfilController;

Route::get('/', function () {
    return auth()->check() ? redirect('/dashboard') : redirect()->route('login');
});

Route::get('/buku-panduan', function () {
    $pdfPath = public_path('docs/buku-panduan.pdf');
    if (file_exists($pdfPath)) {
        return response()->file($pdfPath);
    }
    return view('panduan');
})->name('buku-panduan');

Route::get('/dashboard', function () {
    $user = auth()->user();
    $data = [
        'totalPegawai' => Pegawai::count(),
        'totalDivisi' => Divisi::count(),
        'kehadiranHariIni' => Kehadiran::whereDate('tanggal', today())->count(),
    ];

    if ($user->role === 'admin') {
        $data['rekapIzin'] = [
            'pending' => Izin::where('status', 'pending')->count(),
            'disetujui_atasan' => Izin::where('status', 'disetujui_atasan')->count(),
            'disetujui' => Izin::where('status', 'disetujui')->count(),
            'ditolak' => Izin::where('status', 'ditolak')->count(),
        ];
        $data['izinTerbaru'] = Izin::with('pegawai')
            ->latest()
            ->limit(5)
            ->get();

        $data['laporanTerbaru'] = \App\Models\LaporanMingguan::with('pegawai')
            ->latest()
            ->limit(5)
            ->get();

        $data['pengumumanTerbaru'] = \App\Models\Pengumuman::whereDate('tanggal', '<=', today())
            ->orderBy('tanggal', 'desc')
            ->orderBy('id', 'desc')
            ->limit(5)
            ->get();

        $data['laporanUploadStatus'] = Setting::get('laporan_upload_status', 'open');
        $data['wfhFormStatus'] = Setting::get('wfh_form_status', 'open');

        // Monthly attendance summary & weekly breakdown
        $selectedBulan = (int) request('bulan', now()->month);
        $selectedTahun = (int) request('tahun', now()->year);

        $daysInMonth = \Carbon\Carbon::createFromDate($selectedTahun, $selectedBulan, 1)->daysInMonth;
        $startDate = \Carbon\Carbon::createFromDate($selectedTahun, $selectedBulan, 1)->startOfDay();
        $endDate = \Carbon\Carbon::createFromDate($selectedTahun, $selectedBulan, $daysInMonth)->endOfDay();
        $shortMonthName = $startDate->translatedFormat('M');

        $allKehadiranBulan = Kehadiran::whereBetween('tanggal', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])->get();
        $izinsBulan = Izin::where(function ($q) use ($startDate, $endDate) {
            $q->whereBetween('tanggal_mulai', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
              ->orWhereBetween('tanggal_selesai', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')]);
        })->get();

        $mingguan = [];
        $currentDay = 1;
        $weekNumber = 1;

        while ($currentDay <= $daysInMonth) {
            $weekStartDate = \Carbon\Carbon::createFromDate($selectedTahun, $selectedBulan, $currentDay)->startOfDay();
            $daysToSunday = (7 - $weekStartDate->dayOfWeekIso) % 7;
            $endDay = min($currentDay + $daysToSunday, $daysInMonth);
            $weekEndDate = \Carbon\Carbon::createFromDate($selectedTahun, $selectedBulan, $endDay)->endOfDay();

            $weekKehadiran = $allKehadiranBulan->filter(function ($item) use ($weekStartDate, $weekEndDate) {
                $tgl = \Carbon\Carbon::parse($item->tanggal);
                return $tgl->betweenIncluded($weekStartDate, $weekEndDate);
            });

            $weekIzinCount = $izinsBulan->filter(function ($item) use ($weekStartDate, $weekEndDate) {
                $mulai = \Carbon\Carbon::parse($item->tanggal_mulai)->startOfDay();
                $selesai = \Carbon\Carbon::parse($item->tanggal_selesai ?? $item->tanggal_mulai)->endOfDay();
                return $mulai->lte($weekEndDate) && $selesai->gte($weekStartDate);
            })->count();

            $wfh = $weekKehadiran->where('jenis', 'wfh')->count();
            $terlambat = $weekKehadiran->where('jenis', '!=', 'wfh')->where('status', 'terlambat')->count();
            $izin = $weekKehadiran->where('jenis', '!=', 'wfh')->whereIn('status', ['izin', 'sakit'])->count() + $weekIzinCount;
            $hadir = $weekKehadiran->where('jenis', '!=', 'wfh')->where('status', 'hadir')->count();
            $total = $hadir + $terlambat + $izin + $wfh;

            $mingguan[] = [
                'week_number' => $weekNumber,
                'label' => "Minggu {$weekNumber}",
                'sublabel' => "({$currentDay} – {$endDay} {$shortMonthName})",
                'hadir' => $hadir,
                'terlambat' => $terlambat,
                'izin' => $izin,
                'wfh' => $wfh,
                'total' => $total,
            ];

            $currentDay = $endDay + 1;
            $weekNumber++;
        }

        // Summary cards totals
        $totalHadir = collect($mingguan)->sum('hadir');
        $totalTerlambat = collect($mingguan)->sum('terlambat');
        $totalIzin = collect($mingguan)->sum('izin');
        $totalWfh = collect($mingguan)->sum('wfh');
        $totalSemua = $totalHadir + $totalTerlambat + $totalIzin + $totalWfh;

        $data['summaryKehadiran'] = [
            'hadir' => $totalHadir,
            'terlambat' => $totalTerlambat,
            'izin' => $totalIzin,
            'wfh' => $totalWfh,
            'total' => $totalSemua,
            'persen_hadir' => $totalSemua > 0 ? number_format(($totalHadir / $totalSemua) * 100, 1, ',', '.') : '0,0',
            'persen_terlambat' => $totalSemua > 0 ? number_format(($totalTerlambat / $totalSemua) * 100, 1, ',', '.') : '0,0',
            'persen_izin' => $totalSemua > 0 ? number_format(($totalIzin / $totalSemua) * 100, 1, ',', '.') : '0,0',
            'persen_wfh' => $totalSemua > 0 ? number_format(($totalWfh / $totalSemua) * 100, 1, ',', '.') : '0,0',
        ];

        // Best week insight
        $bestWeek = collect($mingguan)->sortByDesc('hadir')->first();
        $data['mingguanInsight'] = [
            'has_data' => $totalSemua > 0 && ($bestWeek['hadir'] ?? 0) > 0,
            'best_week_number' => $bestWeek['week_number'] ?? 1,
            'best_week_hadir' => $bestWeek['hadir'] ?? 0,
            'best_week_total' => $bestWeek['total'] ?? 0,
        ];

        $data['mingguan'] = $mingguan;

        // Legacy compatibility variables
        $data['hadirHariIni'] = $totalHadir;
        $data['terlambatHariIni'] = $totalTerlambat;
        $data['izinHariIni'] = $totalIzin;
        $data['wfhHariIni'] = $totalWfh;

        $data['selectedBulan'] = $selectedBulan;
        $data['selectedTahun'] = $selectedTahun;
        $data['monthsList'] = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];
        $currentYear = now()->year;
        $data['yearsRange'] = range($currentYear - 2, $currentYear + 1);
        $data['selectedMonthName'] = $data['monthsList'][$selectedBulan] ?? '';
    } elseif ($user->role === 'atasan') {
        $divisiId = $user->pegawai?->divisi_id;
        $data['divisiName'] = $user->pegawai?->divisi?->nama_divisi ?? '-';
        $data['izinTertunda'] = Izin::where('status', 'pending')
            ->whereHas('pegawai', function ($q) use ($divisiId) {
                $q->where('divisi_id', $divisiId);
            })
            ->count();
        $data['laporanPerluReview'] = \App\Models\LaporanMingguan::where('status', 'menunggu')
            ->whereHas('pegawai', function ($q) use ($divisiId) {
                $q->where('divisi_id', $divisiId);
            })
            ->count();
        $data['jumlahPegawai'] = Pegawai::where('divisi_id', $divisiId)
            ->where('status_aktif', 'aktif')
            ->count();
        $data['laporanTerbaruAtasan'] = \App\Models\LaporanMingguan::with('pegawai')
            ->whereHas('pegawai', function ($q) use ($divisiId) {
                $q->where('divisi_id', $divisiId);
            })
            ->latest()
            ->limit(5)
            ->get();
        $data['izinTerbaruAtasan'] = Izin::with('pegawai')
            ->whereHas('pegawai', function ($q) use ($divisiId) {
                $q->where('divisi_id', $divisiId);
            })
            ->latest()
            ->limit(5)
            ->get();
        $data['pengumumanTerbaru'] = \App\Models\Pengumuman::whereDate('tanggal', '<=', today())
            ->orderBy('tanggal', 'desc')
            ->orderBy('id', 'desc')
            ->limit(5)
            ->get();
    } else {
        // Role: User (Pegawai)
        $pegawai = $user->pegawai;
        $currentYear = now()->year;
        $currentMonth = now()->month;
        $data['pengumumanTerbaru'] = \App\Models\Pengumuman::whereDate('tanggal', '<=', today())
            ->orderBy('tanggal', 'desc')
            ->orderBy('id', 'desc')
            ->limit(5)
            ->get();
        $data['pengumumanUtama'] = $data['pengumumanTerbaru']->first();

        $allKehadiran = collect();
        $allIzin = collect();

        if ($pegawai) {
            $allKehadiran = Kehadiran::where('pegawai_id', $pegawai->id)
                ->orderBy('tanggal', 'desc')
                ->get();

            $allIzin = Izin::where('pegawai_id', $pegawai->id)
                ->orderBy('tanggal_mulai', 'desc')
                ->get();
        }

        $yearsRange = range($currentYear - 2, $currentYear + 1);
        $kehadiranYears = $allKehadiran->pluck('tanggal')->map(fn($t) => (int) \Carbon\Carbon::parse($t)->year)->unique()->toArray();
        $allYears = array_unique(array_merge($yearsRange, $kehadiranYears));
        sort($allYears);

        $monthsList = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        $attendanceData = [];

        foreach ($allYears as $y) {
            $attendanceData[$y] = [];
            for ($m = 1; $m <= 12; $m++) {
                $kehadiranBulan = $allKehadiran->filter(function ($item) use ($y, $m) {
                    $dt = \Carbon\Carbon::parse($item->tanggal);
                    return $dt->year === $y && $dt->month === $m;
                });

                $izinBulan = $allIzin->filter(function ($item) use ($y, $m) {
                    $dt = \Carbon\Carbon::parse($item->tanggal_mulai);
                    return $dt->year === $y && $dt->month === $m;
                });

                $hadirTepatWaktuList = $kehadiranBulan->where('status', 'hadir')->map(function ($item) {
                    return [
                        'tanggal' => \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d M Y'),
                        'hari' => \Carbon\Carbon::parse($item->tanggal)->translatedFormat('l'),
                        'jam_masuk' => $item->jam_masuk ? substr($item->jam_masuk, 0, 5) : '-',
                        'jenis' => strtoupper($item->jenis ?? 'WFO'),
                        'status' => 'hadir',
                        'menit_telat' => 0,
                    ];
                })->values();

                $terlambatList = $kehadiranBulan->where('status', 'terlambat')->map(function ($item) {
                    return [
                        'tanggal' => \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d M Y'),
                        'hari' => \Carbon\Carbon::parse($item->tanggal)->translatedFormat('l'),
                        'jam_masuk' => $item->jam_masuk ? substr($item->jam_masuk, 0, 5) : '-',
                        'menit_telat' => $item->menit_telat ?? 0,
                        'jenis' => strtoupper($item->jenis ?? 'WFO'),
                        'status' => 'terlambat',
                    ];
                })->values();

                $tidakHadirList = collect();
                foreach ($izinBulan as $iz) {
                    $tidakHadirList->push([
                        'tanggal' => \Carbon\Carbon::parse($iz->tanggal_mulai)->translatedFormat('d M Y'),
                        'hari' => \Carbon\Carbon::parse($iz->tanggal_mulai)->translatedFormat('l'),
                        'keterangan' => ucfirst(str_replace('_', ' ', $iz->jenis)),
                        'status' => $iz->jenis,
                        'is_izin' => true,
                    ]);
                }
                foreach ($kehadiranBulan->whereIn('status', ['izin', 'sakit', 'alpha', 'cuti']) as $item) {
                    $tidakHadirList->push([
                        'tanggal' => \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d M Y'),
                        'hari' => \Carbon\Carbon::parse($item->tanggal)->translatedFormat('l'),
                        'keterangan' => strtoupper($item->status),
                        'status' => $item->status,
                        'is_izin' => false,
                    ]);
                }

                $hadirTotalCount = $kehadiranBulan->whereIn('status', ['hadir', 'terlambat'])->count();
                $hadirMurniCount = $hadirTepatWaktuList->count();
                $terlambatCount = $terlambatList->count();
                $tidakHadirCount = $tidakHadirList->count();

                $attendanceData[$y][$m] = [
                    'bulan_angka' => $m,
                    'bulan_nama' => $monthsList[$m],
                    'tahun' => $y,
                    'total_hari' => $hadirTotalCount,
                    'hadir' => $hadirTotalCount,
                    'hadir_tepat_waktu' => $hadirMurniCount,
                    'terlambat' => $terlambatCount,
                    'tidak_hadir' => $tidakHadirCount,
                    'list_hadir' => $hadirTepatWaktuList,
                    'list_terlambat' => $terlambatList,
                    'list_tidak_hadir' => $tidakHadirList->values(),
                ];
            }
        }

        $data['attendanceData'] = $attendanceData;
        $data['availableYears'] = $allYears;
        $data['monthsList'] = $monthsList;
        $data['currentMonth'] = $currentMonth;
        $data['currentYear'] = $currentYear;
    }

    $data['laporanUploadStatus'] = Setting::get('laporan_upload_status', 'open');

    return view('dashboard', $data);
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');


    Route::get('/izin', [IzinController::class, 'index'])->name('izin.index');
    Route::get('/izin/export', [IzinController::class, 'export'])->name('izin.export');
    Route::get('/izin/{izin}', [IzinController::class, 'show'])->name('izin.show')->where('izin', '[0-9]+');
    Route::get('/izin/{izin}/preview', [IzinController::class, 'previewLampiran'])->name('izin.preview')->where('izin', '[0-9]+');
    Route::get('/izin/{izin}/download', [IzinController::class, 'downloadLampiran'])->name('izin.download')->where('izin', '[0-9]+');
    Route::get('/laporan-mingguan', [LaporanMingguanController::class, 'index'])->name('laporan-mingguan.index');
    Route::get('/laporan-mingguan/download-triwulan', [LaporanMingguanController::class, 'downloadTriwulan'])->name('laporan-mingguan.download-triwulan');
    Route::get('/laporan-mingguan/download-tahunan', [LaporanMingguanController::class, 'downloadTahunan'])->name('laporan-mingguan.download-tahunan');
    Route::get('/laporan-mingguan/{laporan}', [LaporanMingguanController::class, 'show'])->name('laporan-mingguan.show')->where('laporan', '[0-9]+');
    Route::get('/laporan-mingguan/{laporan}/download', [LaporanMingguanController::class, 'download'])->name('laporan-mingguan.download')->where('laporan', '[0-9]+');
    Route::get('/laporan-mingguan/{laporan}/preview', [LaporanMingguanController::class, 'preview'])->name('laporan-mingguan.preview')->where('laporan', '[0-9]+');
    Route::get('/pengumuman', [PengumumanController::class, 'index'])->name('pengumuman.index');
    Route::get('/pengumuman/{pengumuman}', [PengumumanController::class, 'show'])->name('pengumuman.show')->where('pengumuman', '[0-9]+');

    Route::middleware('role:user')->group(function () {
        Route::get('/izin/create', [IzinController::class, 'create'])->name('izin.create');
        Route::post('/izin', [IzinController::class, 'store'])->name('izin.store');
        Route::get('/laporan-mingguan/create', [LaporanMingguanController::class, 'create'])->name('laporan-mingguan.create');
        Route::post('/laporan-mingguan', [LaporanMingguanController::class, 'store'])->name('laporan-mingguan.store');
        Route::get('/laporan-mingguan/{laporan}/edit', [LaporanMingguanController::class, 'edit'])->name('laporan-mingguan.edit')->where('laporan', '[0-9]+');
        Route::put('/laporan-mingguan/{laporan}', [LaporanMingguanController::class, 'update'])->name('laporan-mingguan.update')->where('laporan', '[0-9]+');

        Route::get('/absen-wfh', [AbsenWfhController::class, 'create'])->name('absen-wfh.create');
        Route::post('/absen-wfh', [AbsenWfhController::class, 'store'])->name('absen-wfh.store');
        Route::post('/absen-wfh/pulang', [AbsenWfhController::class, 'storePulang'])->name('absen-wfh.pulang');

        Route::get('/kehadiran-saya', [KehadiranController::class, 'rekapPribadi'])->name('kehadiran.rekap-pribadi');
        Route::get('/kehadiran-saya/download', [KehadiranController::class, 'downloadPribadi'])->name('kehadiran.download-pribadi');
    });

    // Admin dan Atasan
    Route::middleware('role:admin,atasan')->group(function () {
        Route::get('/pegawai', [PegawaiController::class, 'index'])->name('pegawai.index');
        Route::get('/laporan-mingguan/{laporan}/nilai', [LaporanMingguanController::class, 'showNilaiForm'])->name('laporan-mingguan.nilai-form')->where('laporan', '[0-9]+');
        Route::match(['post', 'patch'], '/laporan-mingguan/{laporan}/nilai', [LaporanMingguanController::class, 'nilai'])->name('laporan-mingguan.nilai')->where('laporan', '[0-9]+');
    });

    // Khusus Admin
    Route::middleware('role:admin')->group(function () {
        Route::resource('pegawai', PegawaiController::class)->except(['index']);
        Route::resource('divisi', DivisiController::class);

        Route::get('/kehadiran/upload', [KehadiranController::class, 'create'])->name('kehadiran.create');
        Route::get('/kehadiran/template', [KehadiranController::class, 'downloadTemplate'])->name('kehadiran.template');
        Route::post('/kehadiran/import', [KehadiranController::class, 'import'])->name('kehadiran.import');
        Route::get('/kehadiran', [KehadiranController::class, 'index'])->name('kehadiran.index');
        Route::get('/kehadiran/wfh', [KehadiranController::class, 'wfh'])->name('kehadiran.wfh');
        Route::get('/kehadiran/wfh/export', [KehadiranController::class, 'exportWfh'])->name('kehadiran.wfh.export');
        Route::post('/kehadiran/wfh/toggle', [KehadiranController::class, 'toggleWfhForm'])->name('kehadiran.wfh.toggle');
        Route::get('/kehadiran/rekap', [KehadiranController::class, 'rekap'])->name('kehadiran.rekap');
        Route::get('/kehadiran/rekap/export', [KehadiranController::class, 'exportRekap'])->name('kehadiran.rekap.export');
        Route::patch('/kehadiran/{kehadiran}/verifikasi-wfh', [KehadiranController::class, 'verifikasiWfh'])->name('kehadiran.verifikasi-wfh');
        Route::patch('/kehadiran/{kehadiran}/tolak-wfh', [KehadiranController::class, 'tolakWfh'])->name('kehadiran.tolak-wfh');

        Route::patch('/izin/{izin}/verifikasi', [IzinController::class, 'verifikasiAdmin'])->name('izin.verifikasi');
        Route::patch('/izin/{izin}/reject-admin', [IzinController::class, 'rejectAdmin'])->name('izin.reject-admin');


        Route::resource('pengumuman', PengumumanController::class)->except(['show', 'index']);

        // Rekap kehadiran per pegawai (admin)
        Route::get('/kehadiran/rekap-pegawai', [KehadiranController::class, 'rekapPegawaiAdmin'])->name('kehadiran.rekap-pegawai');
        Route::get('/kehadiran/rekap-pegawai/download', [KehadiranController::class, 'downloadPegawaiAdmin'])->name('kehadiran.rekap-pegawai.download');

        // Presensi Upacara Hari Besar
        Route::get('/upacara/template', [UpacaraController::class, 'downloadTemplate'])->name('upacara.template');
        Route::post('/upacara/{upacara}/import', [UpacaraController::class, 'import'])->name('upacara.import');
        Route::resource('upacara', UpacaraController::class);
        Route::patch('/upacara/{upacara}/peserta/{pegawai}', [UpacaraController::class, 'updateStatusPeserta'])->name('upacara.peserta.status');


        // Toggle upload laporan pegawai
        Route::post('/laporan-mingguan/toggle-upload', [LaporanMingguanController::class, 'toggleUpload'])->name('laporan-mingguan.toggle-upload');

        // Otorisasi Google Drive
        Route::get('/google-drive/connect', [\App\Http\Controllers\GoogleDriveAuthController::class, 'connect'])->name('google-drive.connect');
        Route::get('/google-drive/callback', [\App\Http\Controllers\GoogleDriveAuthController::class, 'callback'])->name('google-drive.callback');
    });

    // Khusus Atasan
    Route::middleware('role:atasan')->group(function () {
        Route::patch('/izin/{izin}/approve-atasan', [IzinController::class, 'approveAtasan'])->name('izin.approve-atasan');
        Route::patch('/izin/{izin}/reject-atasan', [IzinController::class, 'rejectAtasan'])->name('izin.reject-atasan');
    });
});

Route::get('/storage-file/{path}', function ($path) {
    $content = \App\Helpers\StorageHelper::get($path);
    if (!$content) {
        abort(404, 'Berkas tidak ditemukan.');
    }
    $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));
    $mime = match ($extension) {
        'png' => 'image/png',
        'gif' => 'image/gif',
        'webp' => 'image/webp',
        'svg' => 'image/svg+xml',
        'pdf' => 'application/pdf',
        default => 'image/jpeg',
    };
    return response($content, 200, [
        'Content-Type' => $mime,
        'Cache-Control' => 'public, max-age=86400',
    ]);
})->where('path', '.*')->name('storage.file');

require __DIR__.'/auth.php';