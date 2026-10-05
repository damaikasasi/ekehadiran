<?php

namespace App\Http\Controllers;

use App\Models\Upacara;
use App\Models\UpacaraPeserta;
use App\Models\Pegawai;
use App\Models\Divisi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UpacaraController extends Controller
{
    public function index(Request $request)
    {
        $query = Upacara::withCount([
            'peserta as total_peserta',
            'peserta as total_hadir' => function ($q) {
                $q->where('status', 'hadir');
            },
            'peserta as total_tidak_hadir' => function ($q) {
                $q->where('status', 'tidak_hadir');
            },
            'peserta as total_izin' => function ($q) {
                $q->where('status', 'izin');
            },
        ])->latest('tanggal');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('nama_upacara', 'like', "%{$search}%");
        }

        if ($request->filled('bulan')) {
            $query->whereMonth('tanggal', $request->bulan);
        }

        if ($request->filled('tahun')) {
            $query->whereYear('tanggal', $request->tahun);
        }

        $upacaraList = $query->paginate(10)->withQueryString();
        $totalUpacara = Upacara::count();
        $totalUpacaraTahunIni = Upacara::whereYear('tanggal', now()->year)->count();

        return view('upacara.index', compact('upacaraList', 'totalUpacara', 'totalUpacaraTahunIni'));
    }

    public function create()
    {
        return view('upacara.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_upacara' => 'required|string|max:255',
            'tanggal'      => 'required|date',
            'waktu_mulai'  => 'nullable|string',
            'keterangan'   => 'nullable|string',
            'file'         => 'required|file|mimes:csv,xlsx,xls,txt|max:5120',
        ], [
            'file.required' => 'Silakan unggah file CSV atau Excel daftar absensi upacara.',
        ]);

        $upacara = DB::transaction(function () use ($request) {
            $upacara = Upacara::create([
                'nama_upacara' => $request->nama_upacara,
                'tanggal'      => $request->tanggal,
                'waktu_mulai'  => $request->waktu_mulai,
                'keterangan'   => $request->keterangan,
                'created_by'   => auth()->id(),
            ]);

            $file = $request->file('file');
            $import = new \App\Imports\UpacaraPesertaImport($upacara);
            \Maatwebsite\Excel\Facades\Excel::import($import, $file);

            $upacara->syncPeserta();

            return $upacara;
        });

        return redirect()->route('upacara.show', $upacara)
            ->with('success', 'Agenda upacara dan data absensi dari file berhasil disimpan.');
    }

    public function show(Upacara $upacara, Request $request)
    {
        $upacara->syncPeserta();
        $upacara->load(['creator']);

        $query = $upacara->peserta()->with('pegawai.divisi', 'pegawai.user');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('pegawai', function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhereHas('user', fn($u) => $u->where('nip', 'like', "%{$search}%"));
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('divisi_id')) {
            $query->whereHas('pegawai', fn($q) => $q->where('divisi_id', $request->divisi_id));
        }

        $pesertaList = $query->paginate(20)->withQueryString();
        $divisis = Divisi::orderBy('nama_divisi')->get();

        $stats = [
            'total'       => $upacara->peserta()->count(),
            'hadir'       => $upacara->peserta()->where('status', 'hadir')->count(),
            'tidak_hadir' => $upacara->peserta()->where('status', 'tidak_hadir')->count(),
            'izin'        => $upacara->peserta()->where('status', 'izin')->count(),
        ];

        return view('upacara.show', compact('upacara', 'pesertaList', 'divisis', 'stats'));
    }

    public function edit(Upacara $upacara)
    {
        return view('upacara.edit', compact('upacara'));
    }

    public function update(Request $request, Upacara $upacara)
    {
        $request->validate([
            'nama_upacara' => 'required|string|max:255',
            'tanggal'      => 'required|date',
            'waktu_mulai'  => 'nullable|string',
            'keterangan'   => 'nullable|string',
            'file'         => 'nullable|file|mimes:csv,xlsx,xls,txt|max:5120',
        ]);

        DB::transaction(function () use ($request, $upacara) {
            $upacara->update([
                'nama_upacara' => $request->nama_upacara,
                'tanggal'      => $request->tanggal,
                'waktu_mulai'  => $request->waktu_mulai,
                'keterangan'   => $request->keterangan,
            ]);

            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $import = new \App\Imports\UpacaraPesertaImport($upacara);
                \Maatwebsite\Excel\Facades\Excel::import($import, $file);
            }

            $upacara->syncPeserta();
        });

        return redirect()->route('upacara.show', $upacara)
            ->with('success', 'Agenda upacara dan data absensi berhasil diperbarui.');
    }

    public function destroy(Upacara $upacara)
    {
        $upacara->delete();

        return redirect()->route('upacara.index')
            ->with('success', 'Data agenda upacara berhasil dihapus.');
    }

    public function updateStatusPeserta(Request $request, Upacara $upacara, Pegawai $pegawai)
    {
        $request->validate([
            'status' => 'required|in:hadir,tidak_hadir,izin',
        ]);

        UpacaraPeserta::updateOrCreate(
            [
                'upacara_id' => $upacara->id,
                'pegawai_id' => $pegawai->id,
            ],
            [
                'status' => $request->status,
                'keterangan' => $request->status === 'hadir' ? 'Hadir Upacara' : ($request->keterangan ?? null),
            ]
        );

        return back()->with('success', "Status presensi {$pegawai->nama} berhasil diubah menjadi {$request->status}.");
    }

    public function downloadTemplate()
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="template_absensi_upacara.csv"',
        ];

        $pegawaiList = Pegawai::with('user')->where('status_aktif', 'aktif')->orderBy('nama')->get();
        $tanggalHariIni = now()->format('Y-m-d');

        $callback = function () use ($pegawaiList, $tanggalHariIni) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['No', 'NIP', 'Nama', 'Tanggal', 'Keterangan']);

            foreach ($pegawaiList as $index => $p) {
                fputcsv($handle, [
                    $index + 1,
                    $p->user?->nip ?? '',
                    $p->nama,
                    $tanggalHariIni,
                    'Hadir'
                ]);
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function import(Request $request, Upacara $upacara)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,xlsx,xls,txt|max:5120',
        ]);

        $file = $request->file('file');
        $import = new \App\Imports\UpacaraPesertaImport($upacara);

        try {
            \Maatwebsite\Excel\Facades\Excel::import($import, $file);
            $upacara->syncPeserta();
            return back()->with('success', "Impor berhasil! {$import->berhasil} data kehadiran peserta upacara diperbarui.");
        } catch (\Exception $e) {
            return back()->withErrors(['file' => 'Gagal mengimpor file: ' . $e->getMessage()]);
        }
    }
}

