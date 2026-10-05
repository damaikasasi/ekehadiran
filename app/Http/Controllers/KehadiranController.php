<?php

namespace App\Http\Controllers;

use App\Models\Kehadiran;
use App\Models\ImportLog;
use App\Imports\KehadiranImport;
use App\Exports\RekapitulasiExport;
use App\Exports\WfhExport;
use App\Models\Setting;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class KehadiranController extends Controller
{
    public function index(Request $request)
    {
        $query = Kehadiran::with(['pegawai.divisi', 'pegawai.user'])->latest('tanggal');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('pegawai', function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhereHas('user', fn($u) => $u->where('nip', 'like', "%{$search}%"));
            });
        }

        if ($request->filled('divisi_id')) {
            $query->whereHas('pegawai', function ($q) use ($request) {
                $q->where('divisi_id', $request->divisi_id);
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('jenis')) {
            $query->where('jenis', $request->jenis);
        }

        if ($request->filled('tanggal')) {
            $query->whereDate('tanggal', $request->tanggal);
        }

        $kehadiran = $query->paginate(15)->withQueryString();
        $divisis = \App\Models\Divisi::orderBy('nama_divisi')->get();

        return view('kehadiran.index', compact('kehadiran', 'divisis'));
    }

    public function create()
    {
        return view('kehadiran.upload');
    }

    public function downloadTemplate()
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="template_absensi_fingerprint.csv"',
        ];

        $callback = function () {
            $handle = fopen('php://output', 'w');
            // Header
            fputcsv($handle, ['No.', 'NIP', 'Nama', 'Waktu', 'Tipe']);
            // Contoh baris
            fputcsv($handle, [1, '198512072025211074', 'Jaelani', '03/08/2026 05:20', 'Absen Masuk']);
            fputcsv($handle, [2, '199012072025211078', 'Deky', '03/08/2026 05:31', 'Absen Masuk']);
            fputcsv($handle, [3, '198512072025211074', 'Jaelani', '03/08/2026 16:01', 'Absen Pulang']);
            fputcsv($handle, [4, '199012072025211078', 'Deky', '03/08/2026 18:45', 'Absen Pulang']);
            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function import(Request $request)
    {
        $files = $request->file('files') ?? $request->file('file');

        if (!$files) {
            return redirect()->back()->withErrors(['files' => 'Silakan pilih minimal satu file Excel/CSV untuk diimpor.']);
        }

        if (!is_array($files)) {
            $files = [$files];
        }

        $totalBerhasil = 0;
        $totalGagal = 0;
        $fileNames = [];
        $errors = [];

        foreach ($files as $file) {
            if (!$file || !$file->isValid()) {
                continue;
            }

            $extension = strtolower($file->getClientOriginalExtension());
            if (!in_array($extension, ['xlsx', 'xls', 'csv'])) {
                $errors[] = "File '{$file->getClientOriginalName()}' dilewati karena format tidak didukung (.xlsx, .xls, .csv).";
                continue;
            }

            $import = new KehadiranImport();

            try {
                Excel::import($import, $file);

                $totalBerhasil += $import->berhasil;
                $totalGagal += $import->gagal;
                $fileNames[] = $file->getClientOriginalName();

                ImportLog::create([
                    'nama_file' => $file->getClientOriginalName(),
                    'diupload_oleh' => auth()->id(),
                    'jumlah_berhasil' => $import->berhasil,
                    'jumlah_gagal' => $import->gagal,
                    'status' => 'selesai',
                ]);
            } catch (\Exception $e) {
                $totalGagal += $import->gagal;
                $errors[] = "File '{$file->getClientOriginalName()}' gagal: " . $e->getMessage();

                ImportLog::create([
                    'nama_file' => $file->getClientOriginalName(),
                    'diupload_oleh' => auth()->id(),
                    'jumlah_berhasil' => 0,
                    'jumlah_gagal' => 0,
                    'status' => 'gagal',
                ]);
            }
        }

        $totalFiles = count($fileNames);
        $message = "Impor {$totalFiles} file selesai. Total data berhasil: {$totalBerhasil}, Gagal: {$totalGagal}.";

        if (!empty($errors)) {
            return redirect()->route('kehadiran.create')
                ->with('success', $message)
                ->withErrors($errors);
        }

        return redirect()->route('kehadiran.create')
            ->with('success', $message);
    }

    public function wfh(Request $request)
    {
        $bulan = $request->get('bulan', (string) now()->month);
        $tahun = $request->get('tahun', (string) now()->year);

        $query = Kehadiran::with(['pegawai.divisi', 'pegawai.user'])
            ->where('jenis', 'wfh');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('pegawai', function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhereHas('user', fn($u) => $u->where('nip', 'like', "%{$search}%"))
                  ->orWhereHas('divisi', fn($d) => $d->where('nama_divisi', 'like', "%{$search}%"));
            });
        }

        if ($bulan && $bulan !== 'semua') {
            $query->whereMonth('tanggal', (int) $bulan);
        }

        if ($tahun && $tahun !== 'semua') {
            $query->whereYear('tanggal', (int) $tahun);
        }

        $sort = $request->get('sort', 'tanggal');
        $direction = strtolower($request->get('direction', 'desc')) === 'asc' ? 'asc' : 'desc';

        if ($sort === 'nama') {
            $query->join('pegawai', 'kehadiran.pegawai_id', '=', 'pegawai.id')
                  ->orderBy('pegawai.nama', $direction)
                  ->select('kehadiran.*');
        } elseif ($sort === 'status') {
            $query->orderBy('status', $direction);
        } else {
            $query->orderBy('tanggal', $direction)->orderBy('jam_masuk', $direction);
        }

        $perPage = (int) $request->get('per_page', 10);
        $kehadiran = $query->paginate($perPage)->withQueryString();

        $wfhFormStatus = Setting::get('wfh_form_status', 'open');

        $totalWfhHariIni = Kehadiran::where('jenis', 'wfh')->whereDate('tanggal', today())->count();
        $menungguVerifikasi = Kehadiran::where('jenis', 'wfh')->where('status', 'menunggu_verifikasi')->count();
        $sudahTerverifikasi = Kehadiran::where('jenis', 'wfh')->whereIn('status', ['hadir', 'terlambat'])->count();

        $monthsList = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];
        $currentYear = now()->year;
        $yearsRange = range($currentYear - 2, $currentYear + 1);

        return view('kehadiran.wfh', compact(
            'kehadiran',
            'wfhFormStatus',
            'totalWfhHariIni',
            'menungguVerifikasi',
            'sudahTerverifikasi',
            'bulan',
            'tahun',
            'monthsList',
            'yearsRange'
        ));
    }

    public function exportWfh(Request $request)
    {
        $bulan = $request->get('bulan', (string) now()->month);
        $tahun = $request->get('tahun', (string) now()->year);
        $search = $request->get('search');
        $status = $request->get('status');

        $namaBulan = ($bulan && $bulan !== 'semua')
            ? \Carbon\Carbon::create()->month((int)$bulan)->translatedFormat('F')
            : 'Semua-Bulan';

        $namaTahun = ($tahun && $tahun !== 'semua') ? $tahun : 'Semua-Tahun';

        return Excel::download(
            new WfhExport($bulan, $tahun, $search, $status),
            "presensi-wfh-{$namaBulan}-{$namaTahun}.xlsx"
        );
    }

    public function toggleWfhForm(Request $request)
    {
        $currentStatus = Setting::get('wfh_form_status', 'open');
        $newStatus = $currentStatus === 'open' ? 'closed' : 'open';

        Setting::set('wfh_form_status', $newStatus);

        $message = $newStatus === 'open'
            ? 'Form absensi WFH berhasil diaktifkan.'
            : 'Form absensi WFH berhasil dinonaktifkan.';

        return back()->with('success', $message);
    }

    public function rekap()
    {
        $bulan = request('bulan', 'semua');
        $tahun = request('tahun', 'semua');

        $query = Kehadiran::selectRaw('pegawai_id, status, jenis, count(*) as total, sum(menit_telat) as total_menit_telat');

        if ($bulan && $bulan !== 'semua') {
            $query->whereMonth('tanggal', (int) $bulan);
        }

        if ($tahun && $tahun !== 'semua') {
            $query->whereYear('tanggal', (int) $tahun);
        }

        $rekap = $query
            ->groupBy('pegawai_id', 'status', 'jenis')
            ->with('pegawai')
            ->get()
            ->groupBy('pegawai_id');

        $hasSearched = true;

        return view('kehadiran.rekap', compact('rekap', 'bulan', 'tahun', 'hasSearched'));
    }

    public function exportRekap()
    {
        $bulan = request('bulan', 'semua');
        $tahun = request('tahun', 'semua');

        $namaBulan = ($bulan && $bulan !== 'semua')
            ? \Carbon\Carbon::create()->month((int)$bulan)->translatedFormat('F')
            : 'Semua-Bulan';

        $namaTahun = ($tahun && $tahun !== 'semua') ? $tahun : 'Semua-Tahun';

        return Excel::download(
            new RekapitulasiExport($bulan, $tahun),
            "rekapitulasi-kehadiran-{$namaBulan}-{$namaTahun}.xlsx"
        );
    }

    public function verifikasiWfh(Kehadiran $kehadiran)
    {
        $kehadiran->update(['status' => 'hadir']);
        return back()->with('success', 'Absen WFH terverifikasi.');
    }

    public function tolakWfh(Kehadiran $kehadiran)
    {
        $kehadiran->update(['status' => 'alpha']);
        return back()->with('success', 'Absen WFH ditolak.');
    }

    public function rekapPribadi()
    {
        $pegawai = auth()->user()->pegawai;
        $bulan = (int) request('bulan', now()->month);
        $tahun = (int) request('tahun', now()->year);

        $data = $this->getRekapBulananLengkap($pegawai, $bulan, $tahun);
        $ringkasan = $this->hitungRingkasanRekap($data);
        $kehadiran = $data;

        return view('kehadiran.rekap-pribadi', compact('data', 'kehadiran', 'ringkasan', 'bulan', 'tahun'));
    }

    public function downloadPribadi()
    {
        $pegawai = auth()->user()->pegawai;
        $bulan = (int) request('bulan', now()->month);
        $tahun = (int) request('tahun', now()->year);

        $data = $this->getRekapBulananLengkap($pegawai, $bulan, $tahun);
        $ringkasan = $this->hitungRingkasanRekap($data);

        // Collect WFH photos and convert to base64 for PDF embedding
        $fotoWfh = $data->filter(function ($item) {
            return $item->jenis === 'wfh' && ($item->foto || $item->foto_keluar);
        })->map(function ($item) {
            return (object) [
                'tanggal' => $item->tanggal,
                'jam_masuk' => $item->jam_masuk,
                'foto_base64' => $item->foto ? \App\Helpers\StorageHelper::getBase64($item->foto) : null,
                'foto_keluar_base64' => $item->foto_keluar ? \App\Helpers\StorageHelper::getBase64($item->foto_keluar) : null,
            ];
        })->filter(function ($item) {
            return $item->foto_base64 !== null || $item->foto_keluar_base64 !== null;
        })->values();

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('kehadiran.pdf-pribadi', compact('data', 'ringkasan', 'bulan', 'tahun', 'pegawai', 'fotoWfh'));
        
        $namaBulan = \Carbon\Carbon::create(null, $bulan)->translatedFormat('F');
        $filename = 'Rekap_Kehadiran_' . str_replace(' ', '_', $pegawai->nama) . '_' . $namaBulan . '_' . $tahun . '.pdf';

        return $pdf->download($filename);
    }

    // ─── ADMIN: Rekap kehadiran per pegawai ────────────────────────────────────

    public function rekapPegawaiAdmin(Request $request)
    {
        $bulan = (int) $request->get('bulan', now()->month);
        $tahun = (int) $request->get('tahun', now()->year);
        $pegawaiId = $request->get('pegawai_id');

        $pegawaiList = \App\Models\Pegawai::with('divisi', 'user')
            ->where('status_aktif', 'aktif')
            ->orderBy('nama')
            ->get();

        $data      = collect();
        $kehadiran = collect();
        $ringkasan = null;
        $pegawai   = null;

        if ($pegawaiId) {
            $pegawai = \App\Models\Pegawai::with('divisi', 'user')->findOrFail($pegawaiId);
            $data = $this->getRekapBulananLengkap($pegawai, $bulan, $tahun);
            $ringkasan = $this->hitungRingkasanRekap($data);
            $kehadiran = $data;
        }

        return view('kehadiran.rekap-pegawai-admin', compact(
            'pegawaiList', 'pegawai', 'data', 'kehadiran', 'ringkasan', 'bulan', 'tahun', 'pegawaiId'
        ));
    }

    public function downloadPegawaiAdmin(Request $request)
    {
        $request->validate([
            'pegawai_id' => 'required|exists:pegawai,id',
            'bulan'      => 'required|integer|min:1|max:12',
            'tahun'      => 'required|integer|min:2020',
        ]);

        $bulan   = (int) $request->bulan;
        $tahun   = (int) $request->tahun;
        $pegawai = \App\Models\Pegawai::with('divisi', 'user')->findOrFail($request->pegawai_id);

        $data = $this->getRekapBulananLengkap($pegawai, $bulan, $tahun);
        $ringkasan = $this->hitungRingkasanRekap($data);

        // Encode foto WFH ke base64
        $fotoWfh = $data->filter(fn($i) => $i->jenis === 'wfh' && ($i->foto || $i->foto_keluar))
            ->map(function ($item) {
                return (object) [
                    'tanggal'            => $item->tanggal,
                    'jam_masuk'          => $item->jam_masuk,
                    'foto_base64'        => $item->foto ? \App\Helpers\StorageHelper::getBase64($item->foto) : null,
                    'foto_keluar_base64' => $item->foto_keluar ? \App\Helpers\StorageHelper::getBase64($item->foto_keluar) : null,
                ];
            })->filter(fn($i) => $i->foto_base64 !== null || $i->foto_keluar_base64 !== null)->values();

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView(
            'kehadiran.pdf-pribadi',
            compact('data', 'ringkasan', 'bulan', 'tahun', 'pegawai', 'fotoWfh')
        );

        $namaBulan = \Carbon\Carbon::create(null, $bulan)->translatedFormat('F');
        $filename  = 'Rekap_Kehadiran_' . str_replace(' ', '_', $pegawai->nama) . '_' . $namaBulan . '_' . $tahun . '.pdf';

        return $pdf->download($filename);
    }

    /**
     * Membangun daftar lengkap seluruh tanggal dalam 1 bulan (termasuk Libur, Cuti/Izin, WFO/WFH)
     */
    protected function getRekapBulananLengkap($pegawai, int $bulan, int $tahun)
    {
        $daysInMonth = \Carbon\Carbon::createFromDate($tahun, $bulan, 1)->daysInMonth;
        $startDate   = \Carbon\Carbon::createFromDate($tahun, $bulan, 1)->startOfDay();
        $endDate     = \Carbon\Carbon::createFromDate($tahun, $bulan, $daysInMonth)->endOfDay();

        // 1. Ambil data presensi aktual
        $kehadiranList = Kehadiran::where('pegawai_id', $pegawai->id)
            ->whereBetween('tanggal', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
            ->get()
            ->keyBy(fn($item) => \Carbon\Carbon::parse($item->tanggal)->format('Y-m-d'));

        // 2. Ambil data cuti / izin yang disetujui
        $izinList = \App\Models\Izin::where('pegawai_id', $pegawai->id)
            ->where(function ($q) {
                $q->where('status', 'disetujui')
                  ->orWhere('status', 'disetujui_atasan');
            })
            ->where('tanggal_mulai', '<=', $endDate->format('Y-m-d'))
            ->where('tanggal_selesai', '>=', $startDate->format('Y-m-d'))
            ->get();

        // 3. Ambil data upacara hari besar
        $upacaraList = \App\Models\Upacara::with(['peserta' => function ($q) use ($pegawai) {
                $q->where('pegawai_id', $pegawai->id);
            }])
            ->whereBetween('tanggal', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
            ->get()
            ->keyBy(fn($item) => \Carbon\Carbon::parse($item->tanggal)->format('Y-m-d'));

        $rekapData = collect();

        for ($day = 1; $day <= $daysInMonth; $day++) {
            $currDate  = \Carbon\Carbon::createFromDate($tahun, $bulan, $day);
            $dateKey   = $currDate->format('Y-m-d');
            $isWeekend = $currDate->isWeekend();
            $liburNasional = \App\Helpers\HariLiburHelper::getLiburNasional($dateKey);

            $kehadiran = $kehadiranList->get($dateKey);
            $izin = $izinList->first(function ($iz) use ($dateKey) {
                return $dateKey >= \Carbon\Carbon::parse($iz->tanggal_mulai)->format('Y-m-d')
                    && $dateKey <= \Carbon\Carbon::parse($iz->tanggal_selesai)->format('Y-m-d');
            });

            $isSatpam = $pegawai->isSatpam();

            $upacara = $upacaraList->get($dateKey);
            $upacaraInfo = null;
            if ($upacara) {
                $pesertaInfo = $upacara->peserta->first();
                $upacaraStatus = $pesertaInfo?->status;

                // Jika pegawai memiliki izin/cuti/sakit/dinas pada tanggal upacara dan tidak hadir secara fisik:
                if ($izin && $upacaraStatus !== 'hadir') {
                    $upacaraStatus = 'izin';
                } elseif (!$upacaraStatus) {
                    $upacaraStatus = 'tidak_hadir';
                }

                $upacaraInfo = (object) [
                    'id'           => $upacara->id,
                    'nama_upacara' => $upacara->nama_upacara,
                    'status'       => $upacaraStatus,
                    'waktu_mulai'  => $upacara->waktu_mulai,
                    'keterangan'   => $upacara->keterangan,
                ];
            }

            if ($izin) {
                $labelIzin = match (strtolower($izin->jenis)) {
                    'sakit'   => 'Sakit',
                    'cuti'    => 'Cuti Tahunan',
                    'dinas'   => 'Dinas Luar',
                    default   => ucwords(str_replace('_', ' ', $izin->jenis))
                };

                $rekapData->push((object) [
                    'id'                => null,
                    'tanggal'           => $dateKey,
                    'jam_masuk'         => $labelIzin,
                    'jam_keluar'        => $labelIzin,
                    'jenis'             => 'izin',
                    'status'            => strtolower($izin->jenis),
                    'menit_telat'       => 0,
                    'menit_pulang_awal' => 0,
                    'foto'              => null,
                    'foto_keluar'       => null,
                    'is_libur'          => false,
                    'is_izin'           => true,
                    'is_kehadiran'      => false,
                    'keterangan'        => $labelIzin,
                    'upacara'           => $upacaraInfo,
                ]);
            } elseif ($kehadiran) {
                // Jika hadir (termasuk satpam/pegawai yang bertugas di hari libur/weekend)
                $keteranganTambahan = null;
                if ($liburNasional) {
                    $keteranganTambahan = "Bertugas di Hari Libur ({$liburNasional})";
                } elseif ($isWeekend) {
                    $keteranganTambahan = 'Bertugas di Akhir Pekan';
                }

                $isAlpha = ($kehadiran->status === 'alpha');

                $rekapData->push((object) [
                    'id'                => $kehadiran->id,
                    'tanggal'           => $dateKey,
                    'jam_masuk'         => $isAlpha ? '-' : $kehadiran->jam_masuk,
                    'jam_keluar'        => $isAlpha ? '-' : $kehadiran->jam_keluar,
                    'jenis'             => $isAlpha ? '-' : $kehadiran->jenis,
                    'status'            => $kehadiran->status,
                    'menit_telat'       => $isAlpha ? 0 : ($kehadiran->menit_telat ?? 0),
                    'menit_pulang_awal' => $isAlpha ? 0 : ($kehadiran->menit_pulang_awal ?? 0),
                    'foto'              => $isAlpha ? null : $kehadiran->foto,
                    'foto_keluar'       => $isAlpha ? null : $kehadiran->foto_keluar,
                    'is_libur'          => false,
                    'is_izin'           => false,
                    'is_kehadiran'      => !$isAlpha,
                    'keterangan'        => $isAlpha ? 'Absen Ditolak (Alpha)' : $keteranganTambahan,
                    'upacara'           => $upacaraInfo,
                ]);
            } elseif ($upacaraInfo !== null && !$isSatpam) {
                // Hari besar / tanggal merah upacara (untuk pegawai reguler tanpa presensi)
                $rekapData->push((object) [
                    'id'                => null,
                    'tanggal'           => $dateKey,
                    'jam_masuk'         => 'LIBUR',
                    'jam_keluar'        => 'LIBUR',
                    'jenis'             => 'libur',
                    'status'            => 'libur',
                    'menit_telat'       => 0,
                    'menit_pulang_awal' => 0,
                    'foto'              => null,
                    'foto_keluar'       => null,
                    'is_libur'          => true,
                    'is_izin'           => false,
                    'is_kehadiran'      => false,
                    'keterangan'        => $liburNasional ? $liburNasional : ('Hari Libur Upacara (' . $upacaraInfo->nama_upacara . ')'),
                    'upacara'           => $upacaraInfo,
                ]);
            } elseif ($liburNasional !== null && !$isSatpam) {
                // Hari libur nasional (untuk pegawai reguler tanpa presensi)
                $rekapData->push((object) [
                    'id'                => null,
                    'tanggal'           => $dateKey,
                    'jam_masuk'         => 'LIBUR',
                    'jam_keluar'        => 'LIBUR',
                    'jenis'             => 'libur',
                    'status'            => 'libur',
                    'menit_telat'       => 0,
                    'menit_pulang_awal' => 0,
                    'foto'              => null,
                    'foto_keluar'       => null,
                    'is_libur'          => true,
                    'is_izin'           => false,
                    'is_kehadiran'      => false,
                    'keterangan'        => $liburNasional,
                    'upacara'           => null,
                ]);
            } elseif ($isWeekend && !$isSatpam) {
                // Akhir pekan (untuk pegawai reguler)
                $rekapData->push((object) [
                    'id'                => null,
                    'tanggal'           => $dateKey,
                    'jam_masuk'         => 'LIBUR',
                    'jam_keluar'        => 'LIBUR',
                    'jenis'             => 'libur',
                    'status'            => 'libur',
                    'menit_telat'       => 0,
                    'menit_pulang_awal' => 0,
                    'foto'              => null,
                    'foto_keluar'       => null,
                    'is_libur'          => true,
                    'is_izin'           => false,
                    'is_kehadiran'      => false,
                    'keterangan'        => 'Libur Akhir Pekan',
                    'upacara'           => null,
                ]);
            } elseif ($isSatpam) {
                // Khusus Satpam tanpa scan pada tanggal ini: Dihitung sebagai Libur Shift / Lepas Piket
                $rekapData->push((object) [
                    'id'                => null,
                    'tanggal'           => $dateKey,
                    'jam_masuk'         => 'OFF',
                    'jam_keluar'        => 'OFF',
                    'jenis'             => 'libur',
                    'status'            => 'libur',
                    'menit_telat'       => 0,
                    'menit_pulang_awal' => 0,
                    'foto'              => null,
                    'foto_keluar'       => null,
                    'is_libur'          => true,
                    'is_izin'           => false,
                    'is_kehadiran'      => false,
                    'keterangan'        => 'Libur Shift / Lepas Piket',
                    'upacara'           => $upacaraInfo,
                ]);
            } else {
                $isPast = $currDate->isPast() && !$currDate->isToday();
                $rekapData->push((object) [
                    'id'                => null,
                    'tanggal'           => $dateKey,
                    'jam_masuk'         => '-',
                    'jam_keluar'        => '-',
                    'jenis'             => '-',
                    'status'            => $isPast ? 'alpha' : '-',
                    'menit_telat'       => 0,
                    'menit_pulang_awal' => 0,
                    'foto'              => null,
                    'foto_keluar'       => null,
                    'is_libur'          => false,
                    'is_izin'           => false,
                    'is_kehadiran'      => false,
                    'keterangan'        => $isPast ? 'Tidak Hadir' : null,
                    'upacara'           => null,
                ]);
            }
        }

        return $rekapData;
    }

    /**
     * Hitung ringkasan statistik kehadiran bulanan (sinkron persis dengan badge di tabel)
     */
    protected function hitungRingkasanRekap($data)
    {
        $totalUpacara = $data->filter(fn($d) => $d->upacara !== null)->count();
        $upacaraHadir = $data->filter(fn($d) => $d->upacara !== null && $d->upacara->status === 'hadir')->count();

        // 1. Total Hadir (dihitung dari total masuk kerja, baik tepat waktu maupun terlambat)
        $totalHadir = $data->filter(fn($d) => $d->is_kehadiran)->count();
        $hadirTepatWaktu = $data->filter(fn($d) => $d->is_kehadiran && $d->status === 'hadir' && ($d->menit_telat ?? 0) <= 0)->count();

        // 2. Terlambat (Badge Kuning "Terlambat")
        $terlambat = $data->filter(fn($d) => $d->is_kehadiran && ($d->status === 'terlambat' || ($d->menit_telat ?? 0) > 0))->count();

        // 3. Izin (Badge Biru "Izin / Sakit / Cuti / Dinas")
        $izin = $data->filter(fn($d) => $d->is_izin)->count();

        // 4. Alpha (Badge Merah "Alpha")
        $alpha = $data->filter(fn($d) => !$d->is_libur && !$d->is_izin && $d->status === 'alpha')->count();

        // 5. Libur (Badge Abu-abu "Libur")
        $libur = $data->filter(fn($d) => $d->is_libur)->count();

        // 6. WFO & WFH
        $wfo = $data->filter(fn($d) => $d->is_kehadiran && $d->jenis === 'wfo')->count();
        $wfh = $data->filter(fn($d) => $d->is_kehadiran && $d->jenis === 'wfh')->count();

        // 7. Akumulasi Menit Telat (khusus presensi kehadiran)
        $menitTelat = $data->filter(fn($d) => $d->is_kehadiran)->sum('menit_telat');

        return [
            'hadir'             => $totalHadir, // Total presensi masuk kerja (mau terlambat mau enggak)
            'total_hadir'       => $totalHadir,
            'hadir_tepat_waktu' => $hadirTepatWaktu,
            'terlambat'         => $terlambat,
            'menit_telat'       => $menitTelat,
            'izin'              => $izin,
            'alpha'             => $alpha,
            'libur'             => $libur,
            'wfo'               => $wfo,
            'wfh'               => $wfh,
            'total_upacara'     => $totalUpacara,
            'upacara_hadir'     => $upacaraHadir,
        ];
    }
}