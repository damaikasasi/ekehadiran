<?php

namespace App\Imports;

use App\Models\Pegawai;
use App\Models\Upacara;
use App\Models\UpacaraPeserta;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Throwable;

class UpacaraPesertaImport implements ToModel, WithHeadingRow, SkipsOnError
{
    use SkipsErrors;

    public int $berhasil = 0;
    public int $gagal = 0;
    protected Upacara $upacara;

    public function __construct(Upacara $upacara)
    {
        $this->upacara = $upacara;
    }

    public function model(array $row)
    {
        // 1. Ambil & Cari Pegawai via NIP atau Nama
        $rawNip = $row['nip'] ?? $row['nomor_induk_pegawai'] ?? null;
        $nip = null;

        if ($rawNip !== null) {
            if (is_numeric($rawNip)) {
                $nip = number_format($rawNip, 0, '', '');
            } else {
                $nip = trim((string) $rawNip);
            }
        }

        $pegawai = null;
        if (!empty($nip)) {
            $pegawai = Pegawai::whereHas('user', function ($q) use ($nip) {
                $q->where('nip', $nip);
            })->first();
        }

        if (!$pegawai && !empty($row['nama'])) {
            $nama = trim((string) $row['nama']);
            $pegawai = Pegawai::where('nama', 'like', "%{$nama}%")->first();
        }

        if (!$pegawai) {
            $this->gagal++;
            return null;
        }

        // 2. Tentukan status dari kolom Keterangan / Status
        $rawKeterangan = strtolower(trim((string) ($row['keterangan'] ?? $row['status'] ?? 'hadir')));

        if (
            str_contains($rawKeterangan, 'tidak') ||
            str_contains($rawKeterangan, 'alpha') ||
            str_contains($rawKeterangan, 'alpa') ||
            str_contains($rawKeterangan, 'absen') ||
            $rawKeterangan === 'th' ||
            $rawKeterangan === '0' ||
            $rawKeterangan === 'false'
        ) {
            $status = 'tidak_hadir';
            $keterangan = 'Tidak Hadir';
        } elseif (
            str_contains($rawKeterangan, 'izin') ||
            str_contains($rawKeterangan, 'ijin') ||
            str_contains($rawKeterangan, 'sakit') ||
            str_contains($rawKeterangan, 'cuti') ||
            $rawKeterangan === 'i' ||
            $rawKeterangan === 's'
        ) {
            $status = 'izin';
            $keterangan = trim((string) ($row['keterangan'] ?? 'Izin'));
        } else {
            // Default hadir
            $status = 'hadir';
            $keterangan = 'Hadir Upacara';
        }


        // Jika pegawai tidak hadir upacara tapi memiliki izin/cuti/sakit/dinas yang disetujui pada tanggal upacara
        if ($status !== 'hadir') {
            $tanggalUpacara = \Carbon\Carbon::parse($this->upacara->tanggal)->format('Y-m-d');
            $izin = \App\Models\Izin::where('pegawai_id', $pegawai->id)
                ->where(function ($q) {
                    $q->where('status', 'disetujui')
                      ->orWhere('status', 'disetujui_atasan');
                })
                ->where('tanggal_mulai', '<=', $tanggalUpacara)
                ->where('tanggal_selesai', '>=', $tanggalUpacara)
                ->first();

            if ($izin) {
                $status = 'izin';
                $labelIzin = match (strtolower($izin->jenis)) {
                    'sakit'   => 'Sakit',
                    'cuti'    => 'Cuti',
                    'dinas'   => 'Dinas Luar',
                    default   => ucwords(str_replace('_', ' ', $izin->jenis))
                };
                $keterangan = "Izin ({$labelIzin})";
            }
        }

        UpacaraPeserta::updateOrCreate(
            [
                'upacara_id' => $this->upacara->id,
                'pegawai_id' => $pegawai->id,
            ],
            [
                'status'     => $status,
                'keterangan' => $keterangan,
            ]
        );

        $this->berhasil++;
        return null;
    }
}
