<?php

namespace App\Http\Controllers;

use App\Models\LaporanMingguan;
use App\Models\LaporanKegiatan;
use App\Models\Setting;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Carbon\Carbon;

class LaporanMingguanController extends Controller
{
    public function index(Request $request)
    {
        $role = auth()->user()->role;

        $query = LaporanMingguan::with([
            'pegawai.divisi',
            'pegawai.user',
            'kegiatanList'
        ]);

        $extraData = [];

        if ($role === 'user') {
            $pegawai = auth()->user()->pegawai;

            $query->where('pegawai_id', $pegawai?->id);

        } elseif ($role === 'atasan') {

            $atasanPegawai = auth()->user()->pegawai;

            if ($atasanPegawai) {

                $divisiId = $atasanPegawai->divisi_id;

                $query->whereHas('pegawai', function ($q) use ($divisiId, $atasanPegawai) {
                    $q->where('divisi_id', $divisiId)
                      ->where('id', '!=', $atasanPegawai->id);
                });

                $extraData['pegawaiList'] = \App\Models\Pegawai::with('divisi')
                    ->where('status_aktif', 'aktif')
                    ->where('divisi_id', $divisiId)
                    ->where('id', '!=', $atasanPegawai->id)
                    ->orderBy('nama')
                    ->get();

                // Nama divisi
                $extraData['divisiName'] =
                    $atasanPegawai->divisi?->nama_divisi ?? '-';
            }
        } elseif ($role === 'admin') {
            $extraData['pegawaiList'] = \App\Models\Pegawai::with('divisi')
                ->where('status_aktif', 'aktif')
                ->orderBy('nama')
                ->get();
        }

        // =========================
        // SEARCH
        // =========================

        if ($request->filled('search')) {

            $search = $request->search;

            if ($role === 'user') {

                $query->where(function ($q) use ($search) {

                    $q->where(
                        'catatan_atasan',
                        'like',
                        "%{$search}%"
                    )

                    ->orWhereHas('kegiatanList', function ($sub) use ($search) {
                        $sub->where(
                            'kegiatan',
                            'like',
                            "%{$search}%"
                        );
                    });
                });

            } else {

                $query->where(function ($q) use ($search) {

                    $q->whereHas('pegawai', function ($sub) use ($search) {

                        $sub->where(
                            'nama',
                            'like',
                            "%{$search}%"
                        )

                        ->orWhereHas('user', function ($u) use ($search) {
                            $u->where(
                                'nip',
                                'like',
                                "%{$search}%"
                            );
                        });

                    })

                    ->orWhere(
                        'catatan_atasan',
                        'like',
                        "%{$search}%"
                    )

                    ->orWhereHas('kegiatanList', function ($sub) use ($search) {
                        $sub->where(
                            'kegiatan',
                            'like',
                            "%{$search}%"
                        );
                    });
                });
            }
        }

        // =========================
        // FILTER STATUS
        // =========================

        if (
            $request->filled('status') &&
            $request->status !== 'semua'
        ) {

            $statusFilter = $request->status;

            if ($statusFilter === 'belum_direview' || $statusFilter === 'menunggu') {
                $query->where('status', 'menunggu');
            } elseif ($statusFilter === 'sudah_direview') {
                $query->where(function ($q) {
                    $q->whereIn('status', ['terverifikasi', 'ditolak'])
                      ->orWhereNotNull('nilai');
                });
            } else {
                $query->where('status', $statusFilter);
            }
        }

        // =========================
        // FILTER BULAN
        // =========================

        if (
            $request->filled('bulan') &&
            $request->bulan !== 'semua'
        ) {

            $query->where(
                'bulan',
                (int) $request->bulan
            );
        }

        // =========================
        // FILTER TAHUN
        // =========================

        if (
            $request->filled('tahun') &&
            $request->tahun !== 'semua'
        ) {

            $query->where(
                'tahun',
                (int) $request->tahun
            );
        }

        // =========================
        // FILTER PEGAWAI
        // =========================

        if (
            $request->filled('pegawai_id') &&
            $request->pegawai_id !== 'semua'
        ) {
            $query->where('pegawai_id', $request->pegawai_id);
        }

        $laporan = $query
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view(
            'laporan-mingguan.index',
            array_merge(
                [
                    'laporan' => $laporan,
                    'laporanUploadStatus' => Setting::get('laporan_upload_status', 'open'),
                ],
                $extraData
            )
        );
    }

    // =========================================================
    // SHOW
    // =========================================================

    public function show(LaporanMingguan $laporan)
    {
        // Cek divisi untuk atasan
        if (auth()->user()->role === 'atasan') {

            $atasanPegawai = auth()->user()->pegawai;

            if (
                $atasanPegawai &&
                $laporan->pegawai?->divisi_id !==
                $atasanPegawai->divisi_id
            ) {
                abort(
                    403,
                    'Anda tidak memiliki akses ke laporan divisi lain.'
                );
            }
        }

        // Cek kepemilikan laporan untuk user
        if (auth()->user()->role === 'user') {

            $pegawai = auth()->user()->pegawai;

            if (
                $laporan->pegawai_id !==
                $pegawai?->id
            ) {
                abort(
                    403,
                    'Anda tidak memiliki akses ke laporan ini.'
                );
            }
        }

        $laporan->load(
            'pegawai.user',
            'pegawai.divisi',
            'kegiatanList'
        );

        return view(
            'laporan-mingguan.show',
            compact('laporan')
        );
    }

    // =========================================================
    // FORM NILAI
    // =========================================================

    public function showNilaiForm(LaporanMingguan $laporan)
    {
        if (auth()->user()->role === 'atasan') {

            $atasanPegawai = auth()->user()->pegawai;

            if (
                $atasanPegawai &&
                $laporan->pegawai?->divisi_id !==
                $atasanPegawai->divisi_id
            ) {
                abort(
                    403,
                    'Anda tidak memiliki akses ke laporan divisi lain.'
                );
            }
        }

        $laporan->load(
            'pegawai.user',
            'pegawai.divisi'
        );

        return view(
            'laporan-mingguan.nilai',
            compact('laporan')
        );
    }

    // =========================================================
    // CREATE
    // =========================================================

    public function create()
    {
        if (auth()->user()->role === 'user' && Setting::get('laporan_upload_status', 'open') !== 'open') {
            return redirect()->route('laporan-mingguan.index')
                ->with('error', 'Pengunggahan laporan saat ini sedang ditutup oleh admin.');
        }

        $pegawai = auth()->user()->pegawai;

        return view(
            'laporan-mingguan.create',
            compact('pegawai')
        );
    }

    // =========================================================
    // STORE
    // =========================================================

    public function store(Request $request)
    {
        if (auth()->user()->role === 'user' && Setting::get('laporan_upload_status', 'open') !== 'open') {
            return redirect()->route('laporan-mingguan.index')
                ->with('error', 'Pengunggahan laporan saat ini sedang ditutup oleh admin.');
        }

        @set_time_limit(180);
        $pegawai = auth()->user()->pegawai;

        if (!$pegawai) {

            return back()
                ->withErrors([
                    'error' => 'Data pegawai tidak ditemukan.'
                ])
                ->withInput();
        }

        // =====================================================
        // FORM TERSTRUKTUR
        // =====================================================

        if (
            $request->has('kegiatan_dilaksanakan') ||
            $request->has('pendahuluan')
        ) {

            $request->validate([

                'bulan' => 'required|integer|min:1|max:12',

                'tahun' => 'required|integer|min:2020',

                'pendahuluan' => 'required|string',

                'kegiatan_dilaksanakan' => 'required|string',

                'kendala' => 'required|string',

                'rencana' => 'required|string',

                'penutup' => 'required|string',

                'dokumentasi' => 'nullable|array|max:5',

                'dokumentasi.*' =>
                    'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',

            ], [

                'bulan.required' =>
                    'Silakan pilih bulan laporan.',

                'tahun.required' =>
                    'Silakan pilih tahun laporan.',

                'pendahuluan.required' =>
                    'Pendahuluan & tujuan wajib diisi.',

                'kegiatan_dilaksanakan.required' =>
                    'Kegiatan yang telah dilaksanakan wajib diisi.',

                'kendala.required' =>
                    'Kendala yang dihadapi wajib diisi.',

                'rencana.required' =>
                    'Rencana tindak lanjut wajib diisi.',

                'penutup.required' =>
                    'Penutup wajib diisi.',

                'dokumentasi.max' =>
                    'Maksimal foto dokumentasi yang dapat diunggah adalah 5 foto.',

                'dokumentasi.*.image' =>
                    'File dokumentasi harus berupa gambar (.png, .jpg, .jpeg, .webp).',

                'dokumentasi.*.max' =>
                    'Ukuran setiap gambar tidak boleh lebih dari 2MB.',
            ]);

            // =================================================
            // TOTAL SIZE DOKUMENTASI MAKSIMAL 2 MB
            // =================================================

            if ($request->hasFile('dokumentasi')) {

                $totalSize = 0;

                $files = is_array(
                    $request->file('dokumentasi')
                )
                    ? $request->file('dokumentasi')
                    : [$request->file('dokumentasi')];

                foreach ($files as $file) {

                    if ($file) {
                        $totalSize += $file->getSize();
                    }
                }

                if ($totalSize > 2 * 1024 * 1024) {

                    $totalMb = round(
                        $totalSize / (1024 * 1024),
                        2
                    );

                    return back()
                        ->withErrors([
                            'dokumentasi' =>
                                "Total ukuran seluruh file dokumentasi ({$totalMb} MB) melebihi batas maksimal 2 MB."
                        ])
                        ->withInput();
                }
            }

            // =================================================
            // CEK DUPLIKAT
            // =================================================

            $sudahAda =
                LaporanMingguan::where(
                    'pegawai_id',
                    $pegawai->id
                )
                ->where(
                    'bulan',
                    $request->bulan
                )
                ->where(
                    'tahun',
                    $request->tahun
                )
                ->exists();

            if ($sudahAda) {

                return back()
                    ->withErrors([
                        'bulan' =>
                            'Laporan untuk bulan dan tahun ini sudah pernah dibuat.'
                    ])
                    ->withInput();
            }

            // =================================================
            // SIMPAN FOTO KE STORAGE (GOOGLE DRIVE / PUBLIC)
            // =================================================

            $fotoPaths = [];

            if ($request->hasFile('dokumentasi')) {

                $files = is_array(
                    $request->file('dokumentasi')
                )
                    ? $request->file('dokumentasi')
                    : [$request->file('dokumentasi')];

                try {
                    foreach ($files as $file) {

                        if (
                            $file &&
                            $file->isValid()
                        ) {

                            $fotoPaths[] =
                                \App\Helpers\StorageHelper::storeCompressedImage(
                                    $file,
                                    'laporan-kegiatan'
                                );
                        }
                    }
                } catch (\Throwable $e) {
                    foreach ($fotoPaths as $fp) {
                        try {
                            \Illuminate\Support\Facades\Storage::disk(\App\Helpers\StorageHelper::disk())->delete($fp);
                        } catch (\Throwable $delEx) {}
                    }

                    return back()
                        ->withErrors([
                            'dokumentasi' => 'Gagal mengunggah dokumentasi ke cloud storage: ' . $e->getMessage()
                        ])
                        ->withInput();
                }
            }

            $fotoValue =
                !empty($fotoPaths)
                    ? json_encode($fotoPaths)
                    : null;

            // =================================================
            // TRANSACTION
            // =================================================

            $createdLaporan = null;

            try {
                DB::transaction(function () use (
                    $request,
                    $pegawai,
                    $fotoValue,
                    &$createdLaporan
                ) {

                    $laporan = LaporanMingguan::create([

                        'pegawai_id' =>
                            $pegawai->id,

                        'minggu_ke' =>
                            $request->get('minggu_ke', 1),

                        'bulan' =>
                            $request->bulan,

                        'tahun' =>
                            $request->tahun,

                        'status' =>
                            'menunggu',
                    ]);

                    $createdLaporan = $laporan;

                    // =============================================
                    // PENDAHULUAN
                    // =============================================

                    if ($request->filled('pendahuluan')) {

                        LaporanKegiatan::create([

                            'laporan_mingguan_id' =>
                                $laporan->id,

                            'kegiatan' =>
                                "Pendahuluan & Tujuan\n" .
                                $request->pendahuluan,

                            'foto' =>
                                null,
                        ]);
                    }

                    // =============================================
                    // KEGIATAN
                    // =============================================

                    LaporanKegiatan::create([

                        'laporan_mingguan_id' =>
                            $laporan->id,

                        'kegiatan' =>
                            "Kegiatan yang Telah Dilaksanakan\n" .
                            $request->kegiatan_dilaksanakan,

                        'foto' =>
                            $fotoValue,
                    ]);

                    // =============================================
                    // KENDALA
                    // =============================================

                    if ($request->filled('kendala')) {

                        LaporanKegiatan::create([

                            'laporan_mingguan_id' =>
                                $laporan->id,

                            'kegiatan' =>
                                "Kendala yang Dihadapi\n" .
                                $request->kendala,

                            'foto' =>
                                null,
                        ]);
                    }

                    // =============================================
                    // RENCANA
                    // =============================================

                    if ($request->filled('rencana')) {

                        LaporanKegiatan::create([

                            'laporan_mingguan_id' =>
                                $laporan->id,

                            'kegiatan' =>
                                "Rencana Tindak Lanjut\n" .
                                $request->rencana,

                            'foto' =>
                                null,
                        ]);
                    }

                    // =============================================
                    // PENUTUP
                    // =============================================

                    if ($request->filled('penutup')) {

                        LaporanKegiatan::create([

                            'laporan_mingguan_id' =>
                                $laporan->id,

                            'kegiatan' =>
                                "Penutup\n" .
                                $request->penutup,

                            'foto' =>
                                null,
                        ]);
                    }
                });
            } catch (\Throwable $e) {
                foreach ($fotoPaths as $fp) {
                    try {
                        \Illuminate\Support\Facades\Storage::disk(\App\Helpers\StorageHelper::disk())->delete($fp);
                    } catch (\Throwable $delEx) {}
                }

                return back()
                    ->withErrors([
                        'error' => 'Gagal menyimpan laporan ke database: ' . $e->getMessage()
                    ])
                    ->withInput();
            }

            if ($createdLaporan) {
                $this->generateAndSavePdf($createdLaporan);
            }

            return redirect()
                ->route('laporan-mingguan.index')
                ->with(
                    'success',
                    'Laporan kinerja bulanan dan file PDF berhasil disimpan ke Google Drive.'
                );
        }

        // =====================================================
        // FORM LAMA / DINAMIS
        // =====================================================

        $request->validate([

            'bulan' =>
                'required|integer|min:1|max:12',

            'tahun' =>
                'required|integer|min:2020',

            'kegiatan' =>
                'required|array|min:1',

            'kegiatan.*' =>
                'required|string',

            'foto.*' =>
                'nullable|image|max:2048',
        ]);

        // =====================================================
        // CEK DUPLIKAT
        // =====================================================

        $sudahAda =
            LaporanMingguan::where(
                'pegawai_id',
                $pegawai->id
            )
            ->where(
                'bulan',
                $request->bulan
            )
            ->where(
                'tahun',
                $request->tahun
            )
            ->exists();

        if ($sudahAda) {

            return back()
                ->withErrors([
                    'bulan' =>
                        'Laporan untuk bulan dan tahun ini sudah pernah dibuat.'
                ])
                ->withInput();
        }

        // =====================================================
        // SIMPAN
        // =====================================================

        $createdLaporan = null;

        DB::transaction(function () use (
            $request,
            $pegawai,
            &$createdLaporan
        ) {

            $laporan = LaporanMingguan::create([

                'pegawai_id' =>
                    $pegawai->id,

                'minggu_ke' =>
                    $request->get('minggu_ke', 1),

                'bulan' =>
                    $request->bulan,

                'tahun' =>
                    $request->tahun,

                'status' =>
                    'menunggu',
            ]);

            $createdLaporan = $laporan;

            foreach (
                $request->kegiatan as
                $index => $isiKegiatan
            ) {

                $fotoPath = null;

                if (
                    $request->hasFile(
                        "foto.$index"
                    )
                ) {

                    $fotoPath =
                        \App\Helpers\StorageHelper::storeCompressedImage(
                            $request->file("foto.$index"),
                            'laporan-kegiatan'
                        );
                }

                LaporanKegiatan::create([

                    'laporan_mingguan_id' =>
                        $laporan->id,

                    'kegiatan' =>
                        $isiKegiatan,

                    'foto' =>
                        $fotoPath,
                ]);
            }
        });

        if ($createdLaporan) {
            $this->generateAndSavePdf($createdLaporan);
        }

        return redirect()
            ->route('laporan-mingguan.index')
            ->with(
                'success',
                'Laporan kinerja bulanan dan file PDF berhasil disimpan ke Google Drive.'
            );
    }

    // =========================================================
    // EDIT
    // =========================================================

    public function edit(LaporanMingguan $laporan)
    {
        $user = auth()->user();
        $pegawai = $user->pegawai;

        if ($user->role === 'user') {
            if (!$pegawai || $laporan->pegawai_id !== $pegawai->id) {
                abort(403, 'Anda tidak memiliki akses untuk mengedit laporan ini.');
            }
            if ($laporan->nilai !== 'dibawah_ekspektasi' && $laporan->status !== 'ditolak') {
                return redirect()->route('laporan-mingguan.show', $laporan->id)
                    ->with('error', 'Laporan yang telah dikirim hanya dapat diedit setelah mendapat penilaian di bawah ekspektasi untuk diperbaiki.');
            }
        } elseif ($user->role === 'atasan') {
            if ($pegawai && $laporan->pegawai?->divisi_id !== $pegawai->divisi_id) {
                abort(403, 'Anda tidak memiliki akses ke laporan divisi lain.');
            }
        }

        $laporan->load('pegawai.user', 'pegawai.divisi', 'kegiatanList');

        $pendahuluan = '';
        $kegiatan_dilaksanakan = '';
        $kendala = '';
        $rencana = '';
        $penutup = '';
        $existingPhotos = [];

        foreach ($laporan->kegiatanList as $kegiatanItem) {
            $text = $kegiatanItem->kegiatan ?? '';
            if (str_starts_with($text, "Pendahuluan & Tujuan\n")) {
                $pendahuluan = substr($text, strlen("Pendahuluan & Tujuan\n"));
            } elseif (str_starts_with($text, "Kegiatan yang Telah Dilaksanakan\n")) {
                $kegiatan_dilaksanakan = substr($text, strlen("Kegiatan yang Telah Dilaksanakan\n"));
            } elseif (str_starts_with($text, "Kendala yang Dihadapi\n")) {
                $kendala = substr($text, strlen("Kendala yang Dihadapi\n"));
            } elseif (str_starts_with($text, "Rencana Tindak Lanjut\n")) {
                $rencana = substr($text, strlen("Rencana Tindak Lanjut\n"));
            } elseif (str_starts_with($text, "Penutup\n")) {
                $penutup = substr($text, strlen("Penutup\n"));
            } else {
                if (empty($kegiatan_dilaksanakan)) {
                    $kegiatan_dilaksanakan = $text;
                } else {
                    $kegiatan_dilaksanakan .= "\n\n" . $text;
                }
            }

            if (!empty($kegiatanItem->foto_list)) {
                foreach ($kegiatanItem->foto_list as $foto) {
                    if (!in_array($foto, $existingPhotos)) {
                        $existingPhotos[] = $foto;
                    }
                }
            }
        }

        return view('laporan-mingguan.edit', compact(
            'laporan',
            'pegawai',
            'pendahuluan',
            'kegiatan_dilaksanakan',
            'kendala',
            'rencana',
            'penutup',
            'existingPhotos'
        ));
    }

    // =========================================================
    // UPDATE
    // =========================================================

    public function update(Request $request, LaporanMingguan $laporan)
    {
        $user = auth()->user();
        $pegawai = $user->pegawai;

        if ($user->role === 'user') {
            if (!$pegawai || $laporan->pegawai_id !== $pegawai->id) {
                abort(403, 'Anda tidak memiliki akses untuk mengubah laporan ini.');
            }
            if ($laporan->nilai !== 'dibawah_ekspektasi' && $laporan->status !== 'ditolak') {
                return redirect()->route('laporan-mingguan.show', $laporan->id)
                    ->with('error', 'Laporan yang telah dikirim hanya dapat diubah setelah mendapat penilaian di bawah ekspektasi untuk diperbaiki.');
            }
        } elseif ($user->role === 'atasan') {
            if ($pegawai && $laporan->pegawai?->divisi_id !== $pegawai->divisi_id) {
                abort(403, 'Anda tidak memiliki akses ke laporan divisi lain.');
            }
        }

        @set_time_limit(180);
        $request->validate([
            'bulan' => 'required|integer|min:1|max:12',
            'tahun' => 'required|integer|min:2020',
            'pendahuluan' => 'required|string',
            'kegiatan_dilaksanakan' => 'required|string',
            'kendala' => 'required|string',
            'rencana' => 'required|string',
            'penutup' => 'required|string',
            'keep_photos' => 'nullable|array',
            'keep_photos.*' => 'string',
            'dokumentasi' => 'nullable|array|max:5',
            'dokumentasi.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ], [
            'bulan.required' => 'Silakan pilih bulan laporan.',
            'tahun.required' => 'Silakan pilih tahun laporan.',
            'pendahuluan.required' => 'Pendahuluan & tujuan wajib diisi.',
            'kegiatan_dilaksanakan.required' => 'Kegiatan yang telah dilaksanakan wajib diisi.',
            'kendala.required' => 'Kendala yang dihadapi wajib diisi.',
            'rencana.required' => 'Rencana tindak lanjut wajib diisi.',
            'penutup.required' => 'Penutup wajib diisi.',
            'dokumentasi.max' => 'Maksimal foto dokumentasi baru yang dapat diunggah adalah 5 foto.',
            'dokumentasi.*.image' => 'File dokumentasi harus berupa gambar (.png, .jpg, .jpeg, .webp).',
            'dokumentasi.*.max' => 'Ukuran setiap gambar tidak boleh lebih dari 2MB.',
        ]);

        $keepCount = is_array($request->keep_photos) ? count($request->keep_photos) : 0;
        $files = is_array($request->file('dokumentasi'))
            ? $request->file('dokumentasi')
            : ($request->hasFile('dokumentasi') ? [$request->file('dokumentasi')] : []);
        $newCount = count($files);

        if (($keepCount + $newCount) > 5) {
            return back()
                ->withErrors([
                    'dokumentasi' => 'Total foto dokumentasi (foto lama + baru) tidak boleh lebih dari 5 foto.'
                ])
                ->withInput();
        }

        // Cek total ukuran dokumentasi baru maksimal 2 MB
        if ($request->hasFile('dokumentasi')) {
            $totalSize = 0;
            foreach ($files as $file) {
                if ($file) {
                    $totalSize += $file->getSize();
                }
            }

            if ($totalSize > 2 * 1024 * 1024) {
                $totalMb = round($totalSize / (1024 * 1024), 2);
                return back()
                    ->withErrors([
                        'dokumentasi' => "Total ukuran seluruh file dokumentasi baru ({$totalMb} MB) melebihi batas maksimal 2 MB."
                    ])
                    ->withInput();
            }
        }

        // Cek duplikat jika bulan / tahun diubah
        if ($request->bulan != $laporan->bulan || $request->tahun != $laporan->tahun) {
            $sudahAda = LaporanMingguan::where('pegawai_id', $laporan->pegawai_id)
                ->where('bulan', $request->bulan)
                ->where('tahun', $request->tahun)
                ->where('id', '!=', $laporan->id)
                ->exists();

            if ($sudahAda) {
                return back()
                    ->withErrors(['bulan' => 'Laporan untuk bulan dan tahun ini sudah pernah dibuat.'])
                    ->withInput();
            }
        }

        $finalPhotos = [];

        // Foto yang dipertahankan
        if ($request->filled('keep_photos') && is_array($request->keep_photos)) {
            foreach ($request->keep_photos as $keptPhoto) {
                if (is_string($keptPhoto) && !empty($keptPhoto)) {
                    $finalPhotos[] = $keptPhoto;
                }
            }
        }

        $newUploadedPaths = [];

        // Simpan foto baru jika ada
        if ($request->hasFile('dokumentasi')) {
            $files = is_array($request->file('dokumentasi'))
                ? $request->file('dokumentasi')
                : [$request->file('dokumentasi')];

            try {
                foreach ($files as $file) {
                    if ($file && $file->isValid()) {
                        $storedPath = \App\Helpers\StorageHelper::storeCompressedImage($file, 'laporan-kegiatan');
                        $finalPhotos[] = $storedPath;
                        $newUploadedPaths[] = $storedPath;
                    }
                }
            } catch (\Throwable $e) {
                foreach ($newUploadedPaths as $np) {
                    try {
                        \Illuminate\Support\Facades\Storage::disk(\App\Helpers\StorageHelper::disk())->delete($np);
                    } catch (\Throwable $delEx) {}
                }

                return back()
                    ->withErrors([
                        'dokumentasi' => 'Gagal mengunggah dokumentasi ke cloud storage: ' . $e->getMessage()
                    ])
                    ->withInput();
            }
        }

        $fotoValue = !empty($finalPhotos) ? json_encode(array_values($finalPhotos)) : null;

        try {
            DB::transaction(function () use ($request, $laporan, $fotoValue) {
                // Reset status menjadi 'menunggu' dan reset penilaian agar dinilai ulang oleh atasan
                $laporan->update([
                    'bulan' => $request->bulan,
                    'tahun' => $request->tahun,
                    'status' => 'menunggu',
                    'nilai' => null,
                    'catatan_atasan' => null,
                ]);

                // Hapus rincian lama dan ganti dengan yang baru
                $laporan->kegiatanList()->delete();

                if ($request->filled('pendahuluan')) {
                    LaporanKegiatan::create([
                        'laporan_mingguan_id' => $laporan->id,
                        'kegiatan' => "Pendahuluan & Tujuan\n" . $request->pendahuluan,
                        'foto' => null,
                    ]);
                }

                LaporanKegiatan::create([
                    'laporan_mingguan_id' => $laporan->id,
                    'kegiatan' => "Kegiatan yang Telah Dilaksanakan\n" . $request->kegiatan_dilaksanakan,
                    'foto' => $fotoValue,
                ]);

                if ($request->filled('kendala')) {
                    LaporanKegiatan::create([
                        'laporan_mingguan_id' => $laporan->id,
                        'kegiatan' => "Kendala yang Dihadapi\n" . $request->kendala,
                        'foto' => null,
                    ]);
                }

                if ($request->filled('rencana')) {
                    LaporanKegiatan::create([
                        'laporan_mingguan_id' => $laporan->id,
                        'kegiatan' => "Rencana Tindak Lanjut\n" . $request->rencana,
                        'foto' => null,
                    ]);
                }

                if ($request->filled('penutup')) {
                    LaporanKegiatan::create([
                        'laporan_mingguan_id' => $laporan->id,
                        'kegiatan' => "Penutup\n" . $request->penutup,
                        'foto' => null,
                    ]);
                }
            });
        } catch (\Throwable $e) {
            foreach ($newUploadedPaths as $np) {
                try {
                    \Illuminate\Support\Facades\Storage::disk(\App\Helpers\StorageHelper::disk())->delete($np);
                } catch (\Throwable $delEx) {}
            }

            return back()
                ->withErrors([
                    'error' => 'Gagal memperbarui laporan: ' . $e->getMessage()
                ])
                ->withInput();
        }

        $this->generateAndSavePdf($laporan);

        return redirect()
            ->route('laporan-mingguan.show', $laporan->id)
            ->with('success', 'Laporan bulanan berhasil diperbaiki dan dikirim ulang. Status saat ini "Menunggu" penilaian kembali dari atasan.');
    }

    // =========================================================
    // GENERATE & SAVE PDF TO CLOUD STORAGE (GOOGLE DRIVE)
    // =========================================================

    public function generateAndSavePdf(LaporanMingguan $laporan): ?string
    {
        try {
            $laporan->load(
                'pegawai.user',
                'pegawai.divisi',
                'kegiatanList'
            );

            $this->preparePdfImages(
                $laporan->kegiatanList
            );

            $pdf = Pdf::loadView(
                'laporan-mingguan.pdf-template',
                compact('laporan')
            );

            $pdf->setPaper('A4', 'portrait');

            $bulanNama = Carbon::create()
                ->month((int) $laporan->bulan)
                ->translatedFormat('F');

            $slugPegawai = Str::slug($laporan->pegawai?->nama ?? 'pegawai');
            $filename = 'laporan-pdf/laporan-kinerja-' . $slugPegawai . '-' . strtolower($bulanNama) . '-' . $laporan->tahun . '.pdf';

            $disk = \App\Helpers\StorageHelper::disk();
            \Illuminate\Support\Facades\Storage::disk($disk)->put($filename, $pdf->output());

            $laporan->update([
                'file_laporan' => $filename,
            ]);

            return $filename;
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning('Gagal menyimpan PDF laporan ke storage: ' . $e->getMessage());
            return null;
        }
    }

    // =========================================================
    // DOWNLOAD PDF BULANAN
    // =========================================================

    public function download(LaporanMingguan $laporan)
    {
        $laporan->load(
            'pegawai.user',
            'pegawai.divisi',
            'kegiatanList'
        );

        /*
        |--------------------------------------------------------------------------
        | OPTIMASI GAMBAR
        |--------------------------------------------------------------------------
        |
        | Gambar asli dari upload bisa berukuran besar.
        | Sebelum dikirim ke DomPDF, kita resize dan kompres.
        |
        */

        $this->preparePdfImages(
            $laporan->kegiatanList
        );

        $pdf = Pdf::loadView(
            'laporan-mingguan.pdf-template',
            compact('laporan')
        );

        /*
        |--------------------------------------------------------------------------
        | Pengaturan DomPDF
        |--------------------------------------------------------------------------
        */

        $pdf->setPaper('A4', 'portrait');

        $bulanNama =
            Carbon::create()
                ->month((int) $laporan->bulan)
                ->translatedFormat('F');

        $filename =
            'laporan-kinerja-' .
            Str::slug($laporan->pegawai->nama) .
            '-' .
            strtolower($bulanNama) .
            '-' .
            $laporan->tahun .
            '.pdf';

        return $pdf->download($filename);
    }

    // =========================================================
    // PREVIEW PDF BULANAN
    // =========================================================

    public function preview(LaporanMingguan $laporan)
    {
        $laporan->load(
            'pegawai.user',
            'pegawai.divisi',
            'kegiatanList'
        );

        // Optimasi gambar sebelum DomPDF
        $this->preparePdfImages(
            $laporan->kegiatanList
        );

        $pdf = Pdf::loadView(
            'laporan-mingguan.pdf-template',
            compact('laporan')
        );

        $pdf->setPaper('A4', 'portrait');

        $bulanNama =
            Carbon::create()
                ->month((int) $laporan->bulan)
                ->translatedFormat('F');

        $filename =
            'laporan-kinerja-' .
            Str::slug($laporan->pegawai->nama) .
            '-' .
            strtolower($bulanNama) .
            '-' .
            $laporan->tahun .
            '.pdf';

        return $pdf->stream($filename);
    }

    // =========================================================
    // NILAI LAPORAN
    // =========================================================

    public function nilai(
        Request $request,
        LaporanMingguan $laporan
    ) {
        if (auth()->user()->role === 'atasan') {
            $atasanPegawai = auth()->user()->pegawai;
            if (
                $atasanPegawai &&
                $laporan->pegawai?->divisi_id !==
                $atasanPegawai->divisi_id
            ) {
                abort(
                    403,
                    'Anda tidak memiliki akses ke laporan divisi lain.'
                );
            }
        } elseif (auth()->user()->role !== 'admin') {
            abort(403, 'Hanya atasan atau admin yang dapat menilai laporan.');
        }

        $request->validate([
            'catatan_atasan' => 'nullable|string',
            'nilai' => 'required|in:dibawah_ekspektasi,sesuai_ekspektasi,diatas_ekspektasi',
            'keputusan' => 'nullable|in:terverifikasi,ditolak',
        ], [
            'nilai.required' => 'Skala nilai wajib dipilih sebelum menyimpan penilaian.',
            'nilai.in' => 'Pilihan skala nilai tidak valid.',
        ]);

        $status = $request->input('keputusan', 'terverifikasi');
        if ($request->nilai === 'dibawah_ekspektasi') {
            $status = $request->input('keputusan') ?: 'ditolak';
        }

        $laporan->update([
            'status' => $status,
            'nilai' => $request->nilai,
            'catatan_atasan' => $request->catatan_atasan,
        ]);

        $this->generateAndSavePdf($laporan);

        return redirect()
            ->route('laporan-mingguan.index')
            ->with(
                'success',
                'Penilaian berhasil diperbarui'
            );
    }

    // =========================================================
    // DOWNLOAD PDF TRIWULAN
    // =========================================================

    public function downloadTriwulan(
        Request $request
    ) {
        $role = auth()->user()->role;

        $rules = [
            'kuartal' => 'required|integer|min:1|max:4',
            'tahun' => 'required|integer|min:2020',
        ];

        if ($role === 'admin' || $role === 'atasan') {
            $rules['pegawai_id'] = 'required|exists:pegawai,id';
        }

        $request->validate($rules);

        if ($role === 'admin' || $role === 'atasan') {
            $pegawai = \App\Models\Pegawai::findOrFail($request->pegawai_id);

            if ($role === 'atasan') {
                $atasanPegawai = auth()->user()->pegawai;
                if ($atasanPegawai && $pegawai->divisi_id !== $atasanPegawai->divisi_id) {
                    abort(403, 'Anda tidak memiliki akses ke laporan pegawai divisi lain.');
                }
            }
        } else {
            $pegawai = auth()->user()->pegawai;

            if (!$pegawai) {
                abort(403, 'Anda bukan pegawai.');
            }
        }

        $pegawai->load(
            'user',
            'divisi'
        );

        // =====================================================
        // TENTUKAN BULAN KUARTAL
        // =====================================================

        $kuartal =
            (int) $request->kuartal;

        $bulanMulai =
            ($kuartal - 1) * 3 + 1;

        $bulanAkhir =
            $bulanMulai + 2;

        // =====================================================
        // AMBIL LAPORAN
        // =====================================================

        $laporanList =
            LaporanMingguan::with(
                'kegiatanList'
            )
            ->where(
                'pegawai_id',
                $pegawai->id
            )
            ->whereBetween(
                'bulan',
                [
                    $bulanMulai,
                    $bulanAkhir
                ]
            )
            ->where(
                'tahun',
                $request->tahun
            )
            ->orderBy(
                'bulan',
                'asc'
            )
            ->get();

        if ($laporanList->isEmpty()) {

            return back()
                ->with(
                    'error',
                    'Tidak ada laporan pada kuartal dan tahun yang dipilih.'
                );
        }

        // =====================================================
        // OPTIMASI SEMUA GAMBAR
        // =====================================================

        foreach ($laporanList as $laporanItem) {

            $this->preparePdfImages(
                $laporanItem->kegiatanList
            );
        }

        // =====================================================
        // LABEL
        // =====================================================

        $labelKuartal =
            'Kuartal ' .
            $kuartal .
            ' ' .
            $request->tahun;

        $bulanMulaiNama =
            Carbon::create()
                ->month($bulanMulai)
                ->translatedFormat('F');

        $bulanAkhirNama =
            Carbon::create()
                ->month($bulanAkhir)
                ->translatedFormat('F');

        $periodeLabel =
            $bulanMulaiNama .
            ' – ' .
            $bulanAkhirNama .
            ' ' .
            $request->tahun;

        // =====================================================
        // GENERATE PDF
        // =====================================================

        $pdf = Pdf::loadView(
            'laporan-mingguan.pdf-bulanan-template',
            compact(
                'laporanList',
                'pegawai',
                'labelKuartal',
                'periodeLabel',
                'request'
            )
        );

        $pdf->setPaper(
            'A4',
            'portrait'
        );

        // =====================================================
        // NAMA FILE
        // =====================================================

        $filename =
            'laporan-kinerja-' .
            Str::slug($pegawai->nama) .
            '-kuartal-' .
            $kuartal .
            '-' .
            $request->tahun .
            '.pdf';

        return $pdf->download(
            $filename
        );
    }

    // =========================================================
    // DOWNLOAD PDF REKAP TAHUNAN
    // =========================================================

    public function downloadTahunan(Request $request)
    {
        $role = auth()->user()->role;

        $rules = [
            'tahun' => 'required|integer|min:2020',
        ];

        if ($role === 'admin' || $role === 'atasan') {
            $rules['pegawai_id'] = 'required|exists:pegawai,id';
        }

        $request->validate($rules);

        if ($role === 'admin' || $role === 'atasan') {
            $pegawai = \App\Models\Pegawai::findOrFail($request->pegawai_id);

            if ($role === 'atasan') {
                $atasanPegawai = auth()->user()->pegawai;
                if ($atasanPegawai && $pegawai->divisi_id !== $atasanPegawai->divisi_id) {
                    abort(403, 'Anda tidak memiliki akses ke laporan pegawai divisi lain.');
                }
            }
        } else {
            $pegawai = auth()->user()->pegawai;

            if (!$pegawai) {
                abort(403, 'Anda bukan pegawai.');
            }
        }

        $pegawai->load('user', 'divisi');

        // =====================================================
        // AMBIL SEMUA LAPORAN PADA TAHUN TERSEBUT
        // =====================================================
        $laporanList = LaporanMingguan::with('kegiatanList')
            ->where('pegawai_id', $pegawai->id)
            ->where('tahun', $request->tahun)
            ->orderBy('bulan', 'asc')
            ->get();

        if ($laporanList->isEmpty()) {
            return back()->with('error', 'Tidak ada laporan pada tahun ' . $request->tahun . ' yang dipilih.');
        }

        // =====================================================
        // OPTIMASI SEMUA GAMBAR
        // =====================================================
        foreach ($laporanList as $laporanItem) {
            $this->preparePdfImages($laporanItem->kegiatanList);
        }

        $isTahunan = true;
        $labelKuartal = 'Tahun ' . $request->tahun;
        $periodeLabel = 'Januari – Desember ' . $request->tahun;

        $pdf = Pdf::loadView(
            'laporan-mingguan.pdf-bulanan-template',
            compact(
                'laporanList',
                'pegawai',
                'labelKuartal',
                'periodeLabel',
                'request',
                'isTahunan'
            )
        );

        $pdf->setPaper('A4', 'portrait');

        $filename = 'laporan-kinerja-tahunan-' . Str::slug($pegawai->nama) . '-' . $request->tahun . '.pdf';

        return $pdf->download($filename);
    }

    // =========================================================
    // OPTIMASI GAMBAR UNTUK PDF
    // =========================================================

    private function preparePdfImages($kegiatanList)
    {
        foreach ($kegiatanList as $item) {

            $optimizedImages = [];

            /*
            |--------------------------------------------------------------------------
            | Ambil foto_list dari accessor/model
            |--------------------------------------------------------------------------
            */

            $fotoList =
                $item->foto_list ?? [];

            if (!is_array($fotoList)) {
                $fotoList = [];
            }

            foreach ($fotoList as $fotoPath) {

                if (
                    !is_string($fotoPath) ||
                    trim($fotoPath) === ''
                ) {
                    continue;
                }

                /*
                |--------------------------------------------------------------------------
                | Cari file asli
                |--------------------------------------------------------------------------
                */

                $relativePath =
                    ltrim(
                        $fotoPath,
                        '/\\'
                    );

                $sourcePath =
                    storage_path(
                        'app/public/' .
                        $relativePath
                    );

                /*
                |--------------------------------------------------------------------------
                | Jika tidak ditemukan, coba public/storage
                |--------------------------------------------------------------------------
                */

                if (!is_file($sourcePath)) {

                    $sourcePath =
                        public_path(
                            'storage/' .
                            $relativePath
                        );
                }

                if (!is_file($sourcePath)) {
                    $tempDir = storage_path('app/temp-pdf-images');
                    $cachedTempPath = $tempDir . '/' . md5($relativePath) . '.' . (pathinfo($relativePath, PATHINFO_EXTENSION) ?: 'jpg');
                    if (is_file($cachedTempPath)) {
                        $sourcePath = $cachedTempPath;
                    } else {
                        $fileContent = \App\Helpers\StorageHelper::get($relativePath);
                        if ($fileContent) {
                            if (!is_dir($tempDir)) {
                                @mkdir($tempDir, 0755, true);
                            }
                            file_put_contents($cachedTempPath, $fileContent);
                            $sourcePath = $cachedTempPath;
                        }
                    }
                }

                if (!is_file($sourcePath)) {
                    continue;
                }

                /*
                |--------------------------------------------------------------------------
                | Optimasi gambar
                |--------------------------------------------------------------------------
                */

                $optimizedPath =
                    $this->optimizeImageForPdf(
                        $sourcePath
                    );

                if (
                    $optimizedPath &&
                    is_file($optimizedPath)
                ) {

                    $optimizedImages[] =
                        $optimizedPath;
                }
            }

            /*
            |--------------------------------------------------------------------------
            | Simpan hasil optimasi sebagai attribute sementara
            |--------------------------------------------------------------------------
            */

            $item->setAttribute(
                'pdf_foto_list',
                $optimizedImages
            );
        }
    }

    // =========================================================
    // RESIZE + COMPRESS IMAGE
    // =========================================================

    private function optimizeImageForPdf(
        string $sourcePath
    ): ?string {

        if (!is_file($sourcePath)) {
            return null;
        }

        /*
        |--------------------------------------------------------------------------
        | Cek GD
        |--------------------------------------------------------------------------
        */

        if (!function_exists('imagecreatefromjpeg')) {

            // Jika GD tidak tersedia,
            // gunakan gambar asli.
            return $sourcePath;
        }

        /*
        |--------------------------------------------------------------------------
        | Ambil informasi gambar
        |--------------------------------------------------------------------------
        */

        $imageInfo =
            @getimagesize($sourcePath);

        if (!$imageInfo) {
            return $sourcePath;
        }

        $width =
            $imageInfo[0];

        $height =
            $imageInfo[1];

        $mime =
            $imageInfo['mime'] ?? '';

        /*
        |--------------------------------------------------------------------------
        | Kalau gambar sudah kecil, tidak perlu diproses lagi.
        |--------------------------------------------------------------------------
        */

        $fileSize =
            @filesize($sourcePath);

        if (
            $fileSize !== false &&
            $fileSize <= 500 * 1024 &&
            $width <= 1200 &&
            $height <= 900
        ) {
            return $sourcePath;
        }

        /*
        |--------------------------------------------------------------------------
        | Folder cache PDF
        |--------------------------------------------------------------------------
        */

        $cacheDirectory =
            storage_path(
                'app/public/pdf-images'
            );

        if (!File::exists($cacheDirectory)) {

            File::makeDirectory(
                $cacheDirectory,
                0755,
                true
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Nama file berdasarkan file asli
        |--------------------------------------------------------------------------
        |
        | Kalau file asli berubah, hash juga berubah sehingga cache baru
        | otomatis dibuat.
        |
        */

        $cacheKey =
            md5(
                $sourcePath .
                '|' .
                (@filemtime($sourcePath) ?: '') .
                '|' .
                ($fileSize ?: '')
            );

        $destinationPath =
            $cacheDirectory .
            DIRECTORY_SEPARATOR .
            $cacheKey .
            '.jpg';

        /*
        |--------------------------------------------------------------------------
        | Gunakan cache jika sudah pernah dibuat
        |--------------------------------------------------------------------------
        */

        if (is_file($destinationPath)) {
            return $destinationPath;
        }

        /*
        |--------------------------------------------------------------------------
        | Buat image resource
        |--------------------------------------------------------------------------
        */

        $sourceImage = null;

        switch ($mime) {

            case 'image/jpeg':
                $sourceImage =
                    @imagecreatefromjpeg(
                        $sourcePath
                    );
                break;

            case 'image/png':
                $sourceImage =
                    @imagecreatefrompng(
                        $sourcePath
                    );
                break;

            case 'image/webp':
                if (function_exists('imagecreatefromwebp')) {

                    $sourceImage =
                        @imagecreatefromwebp(
                            $sourcePath
                        );
                }
                break;
        }

        if (!$sourceImage) {
            return $sourcePath;
        }

        /*
        |--------------------------------------------------------------------------
        | Tentukan ukuran maksimum
        |--------------------------------------------------------------------------
        |
        | Untuk PDF A4, gambar dokumentasi tidak perlu ukuran kamera asli.
        |
        */

        $maxWidth = 1000;
        $maxHeight = 700;

        $scale =
            min(
                $maxWidth / $width,
                $maxHeight / $height,
                1
            );

        $newWidth =
            max(
                1,
                (int) round($width * $scale)
            );

        $newHeight =
            max(
                1,
                (int) round($height * $scale)
            );

        /*
        |--------------------------------------------------------------------------
        | Buat canvas baru
        |--------------------------------------------------------------------------
        */

        $newImage =
            imagecreatetruecolor(
                $newWidth,
                $newHeight
            );

        /*
        |--------------------------------------------------------------------------
        | Background putih
        |--------------------------------------------------------------------------
        |
        | Penting untuk PNG transparan supaya tidak menjadi hitam di PDF.
        |
        */

        $white =
            imagecolorallocate(
                $newImage,
                255,
                255,
                255
            );

        imagefill(
            $newImage,
            0,
            0,
            $white
        );

        /*
        |--------------------------------------------------------------------------
        | Resize
        |--------------------------------------------------------------------------
        */

        imagecopyresampled(
            $newImage,
            $sourceImage,
            0,
            0,
            0,
            0,
            $newWidth,
            $newHeight,
            $width,
            $height
        );

        /*
        |--------------------------------------------------------------------------
        | Simpan sebagai JPEG kualitas 75
        |--------------------------------------------------------------------------
        */

        $saved =
            @imagejpeg(
                $newImage,
                $destinationPath,
                75
            );

        /*
        |--------------------------------------------------------------------------
        | Bersihkan memory
        |--------------------------------------------------------------------------
        */

        imagedestroy(
            $sourceImage
        );

        imagedestroy(
            $newImage
        );

        if (
            $saved &&
            is_file($destinationPath)
        ) {

            return $destinationPath;
        }

        return $sourcePath;
    }

    // =========================================================
    // TOGGLE UPLOAD LAPORAN (Admin Only)
    // =========================================================

    public function toggleUpload(Request $request)
    {
        $currentStatus = Setting::get('laporan_upload_status', 'open');
        $newStatus = $currentStatus === 'open' ? 'closed' : 'open';

        Setting::set('laporan_upload_status', $newStatus);

        $message = $newStatus === 'open'
            ? 'Pengiriman laporan berhasil dibuka. Pegawai sekarang dapat mengunggah laporan.'
            : 'Pengiriman laporan berhasil ditutup. Pegawai tidak dapat mengunggah laporan sementara waktu.';

        return back()->with('success', $message);
    }
}