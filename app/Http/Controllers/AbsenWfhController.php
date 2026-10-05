<?php

namespace App\Http\Controllers;

use App\Models\Kehadiran;
use App\Models\Setting;
use Illuminate\Http\Request;

class AbsenWfhController extends Controller
{
    public function create()
    {
        $pegawai = auth()->user()->pegawai;

        $kehadiranHariIni = Kehadiran::where('pegawai_id', $pegawai->id)
            ->where('tanggal', now()->format('Y-m-d'))
            ->where('jenis', 'wfh')
            ->first();

        $isFormDisabled = Setting::get('wfh_form_status', 'open') === 'closed';

        if (!$kehadiranHariIni) {
            $tahap = 'masuk';
        } elseif (empty($kehadiranHariIni->jam_keluar)) {
            $tahap = 'pulang';
        } else {
            $tahap = 'selesai';
        }

        return view('absen-wfh.create', compact('kehadiranHariIni', 'tahap', 'isFormDisabled'));
    }

    public function store(Request $request)
    {
        if (Setting::get('wfh_form_status', 'open') === 'closed') {
            return back()->withErrors(['foto' => 'Pengisian absensi WFH saat ini sedang dinonaktifkan oleh Admin.']);
        }

        $request->validate([
            'foto' => 'required|image|max:512',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ], [
            'foto.required' => 'Swafoto wajib diunggah.',
            'foto.image' => 'Berkas yang diunggah harus berupa gambar (JPG, PNG, JPEG, WEBP).',
            'foto.max' => 'Ukuran foto maksimal 500 KB.',
            'foto.uploaded' => 'Ukuran foto terlalu besar untuk diunggah. Silakan ambil ulang swafoto.',
            'latitude.required' => 'Koordinat lokasi GPS belum terdeteksi. Pastikan GPS aktif.',
            'longitude.required' => 'Koordinat lokasi GPS belum terdeteksi. Pastikan GPS aktif.',
        ]);

        $pegawai = auth()->user()->pegawai;

        $kehadiranHariIni = Kehadiran::where('pegawai_id', $pegawai->id)
            ->where('tanggal', now()->format('Y-m-d'))
            ->where('jenis', 'wfh')
            ->first();

        if ($kehadiranHariIni) {
            return back()->withErrors(['foto' => 'Kamu sudah melakukan absen masuk WFH hari ini.']);
        }

        $now = now();
        $batasMasuk = now()->copy()->setTime(7, 30, 0);

        $status = 'hadir';
        $menitTelat = 0;

        // Jika absen masuk setelah pukul 07:30, dihitung terlambat beserta menit keterlambatan
        if ($now->greaterThan($batasMasuk)) {
            $status = 'terlambat';
            $menitTelat = (int) $batasMasuk->diffInMinutes($now);
        }

        $disk = \App\Helpers\StorageHelper::disk();
        try {
            $fotoPath = $request->file('foto')->store('absen-wfh', $disk);
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning("Gagal upload WFH ke {$disk}, fallback ke public: " . $e->getMessage());
            $fotoPath = $request->file('foto')->store('absen-wfh', 'public');
        }

        Kehadiran::create([
            'pegawai_id' => $pegawai->id,
            'tanggal' => $now->format('Y-m-d'),
            'jam_masuk' => $now->format('H:i:s'),
            'jam_keluar' => null,
            'jenis' => 'wfh',
            'sumber' => 'manual',
            'status' => $status,
            'menit_telat' => $menitTelat,
            'foto' => $fotoPath,
            'lokasi' => $request->latitude . ',' . $request->longitude,
        ]);

        $pesan = $status === 'terlambat'
            ? "Absen masuk WFH berhasil dikirim (Tercatat Terlambat {$menitTelat} menit). Silakan lakukan absen pulang saat jam kerja selesai."
            : "Absen masuk WFH berhasil dikirim tepat waktu. Silakan lakukan absen pulang saat jam kerja selesai.";

        return redirect()->route('absen-wfh.create')->with('success', $pesan);
    }

    public function storePulang(Request $request)
    {
        if (Setting::get('wfh_form_status', 'open') === 'closed') {
            return back()->withErrors(['foto' => 'Pengisian absensi WFH saat ini sedang dinonaktifkan oleh Admin.']);
        }

        $request->validate([
            'foto' => 'required|image|max:512',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ], [
            'foto.required' => 'Swafoto wajib diunggah.',
            'foto.image' => 'Berkas yang diunggah harus berupa gambar (JPG, PNG, JPEG, WEBP).',
            'foto.max' => 'Ukuran foto maksimal 500 KB.',
            'foto.uploaded' => 'Ukuran foto terlalu besar untuk diunggah. Silakan ambil ulang swafoto.',
            'latitude.required' => 'Koordinat lokasi GPS belum terdeteksi. Pastikan GPS aktif.',
            'longitude.required' => 'Koordinat lokasi GPS belum terdeteksi. Pastikan GPS aktif.',
        ]);

        $pegawai = auth()->user()->pegawai;

        $kehadiranHariIni = Kehadiran::where('pegawai_id', $pegawai->id)
            ->where('tanggal', now()->format('Y-m-d'))
            ->where('jenis', 'wfh')
            ->first();

        if (!$kehadiranHariIni) {
            return back()->withErrors(['foto' => 'Kamu belum melakukan absen masuk WFH hari ini.']);
        }

        if (!empty($kehadiranHariIni->jam_keluar)) {
            return back()->withErrors(['foto' => 'Kamu sudah melakukan absen pulang WFH hari ini.']);
        }

        $now = now();
        $disk = \App\Helpers\StorageHelper::disk();
        try {
            $fotoPath = $request->file('foto')->store('absen-wfh', $disk);
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning("Gagal upload pulang WFH ke {$disk}, fallback ke public: " . $e->getMessage());
            $fotoPath = $request->file('foto')->store('absen-wfh', 'public');
        }

        $kehadiranHariIni->update([
            'jam_keluar' => $now->format('H:i:s'),
            'foto_keluar' => $fotoPath,
            'lokasi_keluar' => $request->latitude . ',' . $request->longitude,
        ]);

        return redirect()->route('absen-wfh.create')->with('success', 'Absen pulang WFH berhasil dicatat. Terima kasih atas kerja keras Anda hari ini!');
    }
}