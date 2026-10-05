<?php

namespace App\Http\Controllers;

use App\Models\Izin;
use App\Exports\IzinExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class IzinController extends Controller
{
    public function index(Request $request)
    {
        $role = auth()->user()->role;

        $query = Izin::with(['pegawai.divisi', 'pegawai.user']);

        if ($role === 'user') {
            // User cuma lihat izin miliknya sendiri
            $pegawai = auth()->user()->pegawai;
            $query->where('pegawai_id', $pegawai?->id);
        } elseif ($role === 'atasan') {
            $atasanPegawai = auth()->user()->pegawai;
            if ($atasanPegawai) {
                $query->whereHas('pegawai', function ($q) use ($atasanPegawai) {
                    $q->where('divisi_id', $atasanPegawai->divisi_id);
                });
            }
        }

        if ($request->filled('search')) {
            $search = $request->search;
            if ($role === 'user') {
                $query->where(function ($q) use ($search) {
                    $q->where('keterangan', 'like', "%{$search}%")
                      ->orWhere('jenis', 'like', "%{$search}%");
                });
            } else {
                $query->where(function ($q) use ($search) {
                    $q->whereHas('pegawai', function ($sub) use ($search) {
                        $sub->where('nama', 'like', "%{$search}%")
                            ->orWhereHas('user', fn($u) => $u->where('nip', 'like', "%{$search}%"))
                            ->orWhereHas('divisi', fn($d) => $d->where('nama_divisi', 'like', "%{$search}%"));
                    })->orWhere('keterangan', 'like', "%{$search}%")
                      ->orWhere('jenis', 'like', "%{$search}%");
                });
            }
        }

        if ($request->filled('jenis') && $request->jenis !== 'semua') {
            $query->where('jenis', $request->jenis);
        }

        if ($request->filled('status') && $request->status !== 'semua') {
            $query->where('status', $request->status);
        }

        if ($request->filled('bulan') && $request->bulan !== 'semua') {
            $query->whereMonth('tanggal_mulai', $request->bulan);
        }

        if ($request->filled('tahun') && $request->tahun !== 'semua') {
            $query->whereYear('tanggal_mulai', $request->tahun);
        }

        $perPage = (int) $request->get('per_page', 10);
        $izin = $query->latest()->paginate($perPage)->withQueryString();

        // Calculate statistics
        $statsQuery = Izin::query();
        if ($role === 'user') {
            $statsQuery->where('pegawai_id', auth()->user()->pegawai?->id);
        } elseif ($role === 'atasan') {
            $atasanPegawai = auth()->user()->pegawai;
            if ($atasanPegawai) {
                $statsQuery->whereHas('pegawai', fn($q) => $q->where('divisi_id', $atasanPegawai->divisi_id));
            }
        }

        $sedangDiverifikasiAdmin = (clone $statsQuery)->where('status', 'disetujui_atasan')->count();
        $sedangDiverifikasiAtasan = (clone $statsQuery)->where('status', 'pending')->count();
        $izinDisetujui = (clone $statsQuery)->where('status', 'disetujui')->count();
        $izinDitolak = (clone $statsQuery)->where('status', 'ditolak')->count();
        $totalPermohonan = (clone $statsQuery)->count();

        $pegawaiSedangIzin = (clone $statsQuery)->where('status', 'disetujui')
            ->whereDate('tanggal_mulai', '<=', today())
            ->whereDate('tanggal_selesai', '>=', today())
            ->distinct('pegawai_id')
            ->count('pegawai_id');

        return view('izin.index', compact(
            'izin',
            'sedangDiverifikasiAdmin',
            'sedangDiverifikasiAtasan',
            'pegawaiSedangIzin',
            'izinDisetujui',
            'izinDitolak',
            'totalPermohonan'
        ));
    }

    public function export(Request $request)
    {
        $search = $request->query('search');
        $jenis = $request->query('jenis');
        $status = $request->query('status');
        $bulan = $request->query('bulan');
        $tahun = $request->query('tahun');

        $namaFile = 'rekap-permohonan-izin-' . now()->format('d-m-Y') . '.xlsx';

        return Excel::download(new IzinExport(
            $search,
            auth()->user(),
            $jenis,
            $status,
            $bulan,
            $tahun
        ), $namaFile);
    }

    public function show(Izin $izin)
    {
        $role = auth()->user()->role;
        $userPegawai = auth()->user()->pegawai;

        // Authorization check
        if ($role === 'user' && $izin->pegawai_id !== $userPegawai?->id) {
            abort(403, 'Anda tidak memiliki akses untuk melihat permohonan izin ini.');
        } elseif ($role === 'atasan') {
            if ($userPegawai && $izin->pegawai && $izin->pegawai->divisi_id !== $userPegawai->divisi_id) {
                abort(403, 'Anda tidak memiliki akses untuk melihat permohonan izin divisi lain.');
            }
        }

        $izin->load(['pegawai.divisi', 'pegawai.user']);

        return view('izin.show', compact('izin'));
    }

    public function downloadLampiran(Izin $izin)
    {
        $role = auth()->user()->role;
        $userPegawai = auth()->user()->pegawai;

        // Authorization check
        if ($role === 'user' && $izin->pegawai_id !== $userPegawai?->id) {
            abort(403, 'Anda tidak memiliki akses untuk mengunduh lampiran izin ini.');
        } elseif ($role === 'atasan') {
            if ($userPegawai && $izin->pegawai && $izin->pegawai->divisi_id !== $userPegawai->divisi_id) {
                abort(403, 'Anda tidak memiliki akses untuk mengunduh lampiran izin divisi lain.');
            }
        }

        $fileContent = \App\Helpers\StorageHelper::get($izin->file_lampiran ?? '');
        if (!$fileContent) {
            return back()->with('error', 'Berkas lampiran tidak ditemukan di penyimpanan server.');
        }

        $extension = pathinfo($izin->file_lampiran, PATHINFO_EXTENSION);
        $namaPegawai = $izin->pegawai->nama ?? 'Pegawai';
        $namaFile = 'Lampiran-Izin-' . Str::slug($namaPegawai) . '-' . \Carbon\Carbon::parse($izin->tanggal_mulai)->format('d-m-Y') . ($extension ? '.' . $extension : '');

        $mime = match (strtolower($extension)) {
            'pdf' => 'application/pdf',
            'png' => 'image/png',
            'jpg', 'jpeg' => 'image/jpeg',
            default => 'application/octet-stream',
        };

        return response($fileContent, 200, [
            'Content-Type' => $mime,
            'Content-Disposition' => 'attachment; filename="' . $namaFile . '"',
        ]);
    }

    public function previewLampiran(Izin $izin)
    {
        $role = auth()->user()->role;
        $userPegawai = auth()->user()->pegawai;

        // Authorization check
        if ($role === 'user' && $izin->pegawai_id !== $userPegawai?->id) {
            abort(403, 'Anda tidak memiliki akses untuk melihat lampiran izin ini.');
        } elseif ($role === 'atasan') {
            if ($userPegawai && $izin->pegawai && $izin->pegawai->divisi_id !== $userPegawai->divisi_id) {
                abort(403, 'Anda tidak memiliki akses untuk melihat lampiran izin divisi lain.');
            }
        }

        $fileContent = \App\Helpers\StorageHelper::get($izin->file_lampiran ?? '');
        if (!$fileContent) {
            abort(404, 'Berkas lampiran tidak ditemukan di penyimpanan server.');
        }

        $extension = strtolower(pathinfo($izin->file_lampiran, PATHINFO_EXTENSION));
        $namaPegawai = $izin->pegawai->nama ?? 'Pegawai';
        $namaFile = 'Lampiran-Izin-' . Str::slug($namaPegawai) . '-' . \Carbon\Carbon::parse($izin->tanggal_mulai)->format('d-m-Y') . ($extension ? '.' . $extension : '');

        $mime = match ($extension) {
            'pdf' => 'application/pdf',
            'png' => 'image/png',
            'jpg', 'jpeg' => 'image/jpeg',
            'webp' => 'image/webp',
            'gif' => 'image/gif',
            default => 'application/octet-stream',
        };

        return response($fileContent, 200, [
            'Content-Type' => $mime,
            'Content-Disposition' => 'inline; filename="' . $namaFile . '"',
            'Cache-Control' => 'public, max-age=86400',
        ]);
    }

    public function create()
    {
        if (auth()->user()->role === 'admin') {
            abort(403, 'Admin tidak dapat mengajukan permohonan izin.');
        }

        $pegawai = auth()->user()->pegawai;
        return view('izin.create', compact('pegawai'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'jenis' => 'required|in:cuti_tahunan,sakit,dinas,lainnya',
            'ada_surat_dokter' => 'nullable|boolean',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'keterangan' => 'nullable|string',
            'file_lampiran' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        if ($request->jenis === 'sakit' && $request->ada_surat_dokter && !$request->hasFile('file_lampiran')) {
            return back()->withErrors(['file_lampiran' => 'Surat keterangan dokter wajib diupload.'])->withInput();
        }

        $path = null;
        if ($request->hasFile('file_lampiran')) {
            $disk = \App\Helpers\StorageHelper::disk();
            $path = $request->file('file_lampiran')->store('izin', $disk);
        }

        $pegawai = auth()->user()->pegawai;

        Izin::create([
            'pegawai_id' => $pegawai->id,
            'jenis' => $request->jenis,
            'ada_surat_dokter' => $request->jenis === 'sakit' ? $request->boolean('ada_surat_dokter') : null,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'keterangan' => $request->keterangan,
            'file_lampiran' => $path,
            'status' => 'pending',
        ]);

        return redirect()->route('izin.index')->with('success', 'Pengajuan izin berhasil dikirim.');
    }

    // Atasan: approve tahap 1
    public function approveAtasan(Izin $izin)
    {
        $izin->update(['status' => 'disetujui_atasan']);
        $this->syncUpacaraForIzin($izin);
        return back()->with('success', 'Izin disetujui, menunggu verifikasi admin.');
    }

    public function rejectAtasan(Request $request, Izin $izin)
    {
        $request->validate([
            'catatan_penolakan' => 'required|string|max:1000',
        ], [
            'catatan_penolakan.required' => 'Keterangan atau alasan penolakan izin wajib diisi.',
        ]);

        $izin->update([
            'status' => 'ditolak',
            'catatan_penolakan' => $request->catatan_penolakan,
        ]);

        $this->syncUpacaraForIzin($izin);

        return back()->with('success', 'Permohonan izin telah ditolak dengan catatan alasan.');
    }

    // Admin: verifikasi final
    public function verifikasiAdmin(Izin $izin)
    {
        $izin->update(['status' => 'disetujui']);
        $this->syncUpacaraForIzin($izin);
        return back()->with('success', 'Izin terverifikasi dan disetujui final.');
    }

    public function rejectAdmin(Request $request, Izin $izin)
    {
        $request->validate([
            'catatan_penolakan' => 'required|string|max:1000',
        ], [
            'catatan_penolakan.required' => 'Keterangan atau alasan penolakan izin wajib diisi.',
        ]);

        $izin->update([
            'status' => 'ditolak',
            'catatan_penolakan' => $request->catatan_penolakan,
        ]);

        $this->syncUpacaraForIzin($izin);

        return back()->with('success', 'Permohonan izin telah ditolak oleh admin dengan catatan alasan.');
    }

    protected function syncUpacaraForIzin(Izin $izin)
    {
        $upacaras = \App\Models\Upacara::whereBetween('tanggal', [
            \Carbon\Carbon::parse($izin->tanggal_mulai)->format('Y-m-d'),
            \Carbon\Carbon::parse($izin->tanggal_selesai)->format('Y-m-d'),
        ])->get();

        foreach ($upacaras as $upacara) {
            $upacara->syncPeserta();
        }
    }
}