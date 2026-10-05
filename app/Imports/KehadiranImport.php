<?php

namespace App\Imports;

use App\Models\Kehadiran;
use App\Models\Pegawai;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class KehadiranImport implements ToModel, WithHeadingRow, SkipsOnFailure
{
    use SkipsFailures;

    public int $berhasil = 0;
    public int $gagal = 0;

    const JAM_MASUK_STANDAR = '07:30:00';
    const JAM_MASUK_SATPAM_PAGI = '07:00:00';
    const JAM_MASUK_SATPAM_MALAM = '19:00:00';

    public function model(array $row)
    {
        // 1. Ambil & Cari Pegawai
        $rawNip = $row['nip'] ?? null;
        $nip = null;

        if ($rawNip !== null) {
            if (is_numeric($rawNip)) {
                $nip = number_format($rawNip, 0, '', '');
            } else {
                $nip = trim((string)$rawNip);
            }
        }

        $pegawai = null;
        if (!empty($nip)) {
            $pegawai = Pegawai::with('divisi')->whereHas('user', function ($q) use ($nip) {
                $q->where('nip', $nip);
            })->first();
        }

        // Fallback pencarian nama jika tidak ditemukan via NIP
        if (!$pegawai && !empty($row['nama'])) {
            $nama = trim((string)$row['nama']);
            $pegawai = Pegawai::with('divisi')->where('nama', $nama)->first();
        }

        if (!$pegawai) {
            $this->gagal++;
            return null;
        }

        $isSatpam = $pegawai->isSatpam();

        // 2. Format baru (Kolom: No, NIP, Nama, Waktu, Tipe)
        if (isset($row['waktu'])) {
            $dt = $this->parseDateTime($row['waktu']);

            if (!$dt) {
                $this->gagal++;
                return null;
            }

            $tanggal = $dt->format('Y-m-d');
            $jam = $dt->format('H:i:s');
            $hour = $dt->hour;

            $rawTipe = strtolower(trim((string)($row['tipe'] ?? '')));
            $isMasuk = str_contains($rawTipe, 'masuk') || str_contains($rawTipe, 'in');
            $isPulang = str_contains($rawTipe, 'pulang') || str_contains($rawTipe, 'keluar') || str_contains($rawTipe, 'out');

            // ─── KHUSUS SATPAM: Deteksi Shift Otomatis & Lintas Hari ───────────
            if ($isSatpam) {

                // KASUS 1: Scan Pagi (05:00 - 11:59)
                if ($hour >= 5 && $hour < 12) {
                    // Cek apakah kemarin malam (H-1) satpam ini masuk Shift 2 (jam_masuk >= 17:00)
                    $kemarinTgl = $dt->copy()->subDay()->format('Y-m-d');
                    $kemarin = Kehadiran::where('pegawai_id', $pegawai->id)
                        ->where('tanggal', $kemarinTgl)
                        ->where('jam_masuk', '>=', '17:00:00')
                        ->first();

                    // Jika kemarin masuk malam dan scan pagi ini di bawah jam 09:00 atau bertipe pulang
                    if ($kemarin && (empty($kemarin->jam_keluar) || $isPulang || $hour <= 9)) {
                        if (empty($kemarin->jam_keluar) || $jam > $kemarin->jam_keluar) {
                            $kemarin->jam_keluar = $jam;
                            $kemarin->save();
                        }
                        $this->berhasil++;
                        return null;
                    }

                    // Jika bukan kelanjutan shift malam kemarin, ini adalah Shift 1 (Pagi: 07:00 - 19:00) hari ini
                    $kehadiran = Kehadiran::firstOrNew([
                        'pegawai_id' => $pegawai->id,
                        'tanggal'    => $tanggal,
                    ]);

                    if (!$kehadiran->exists) {
                        $kehadiran->jenis = 'wfo';
                        $kehadiran->sumber = 'fingerprint';
                        $kehadiran->status = 'hadir';
                        $kehadiran->menit_telat = 0;
                    }

                    if (empty($kehadiran->jam_masuk) || $jam < $kehadiran->jam_masuk) {
                        $kehadiran->jam_masuk = $jam;
                        $jamStandar = self::JAM_MASUK_SATPAM_PAGI;
                        $standar = Carbon::parse($jamStandar);
                        $aktual = Carbon::parse($jam);

                        if ($aktual->gt($standar)) {
                            $kehadiran->menit_telat = $standar->diffInMinutes($aktual);
                            $kehadiran->status = 'terlambat';
                        } else {
                            $kehadiran->menit_telat = 0;
                            $kehadiran->status = 'hadir';
                        }
                    }

                    $kehadiran->sumber = 'fingerprint';
                    $kehadiran->jenis = $kehadiran->jenis ?? 'wfo';
                    $kehadiran->save();
                    $this->berhasil++;
                    return null;
                }

                // KASUS 2: Scan Sore / Malam (>= 16:00)
                if ($hour >= 16) {
                    $kehadiran = Kehadiran::firstOrNew([
                        'pegawai_id' => $pegawai->id,
                        'tanggal'    => $tanggal,
                    ]);

                    // Jika satpam sudah punya jam masuk pagi hari ini (< 12:00), scan sore ini adalah jam pulang Shift 1
                    if ($kehadiran->exists && !empty($kehadiran->jam_masuk) && $kehadiran->jam_masuk < '12:00:00') {
                        if (empty($kehadiran->jam_keluar) || $jam > $kehadiran->jam_keluar) {
                            $kehadiran->jam_keluar = $jam;
                            $kehadiran->save();
                        }
                        $this->berhasil++;
                        return null;
                    }

                    // Jika belum ada jam masuk pagi, ini adalah JAM MASUK Shift 2 (Malam: 19:00 - 07:00) hari ini
                    if (!$kehadiran->exists) {
                        $kehadiran->jenis = 'wfo';
                        $kehadiran->sumber = 'fingerprint';
                        $kehadiran->status = 'hadir';
                        $kehadiran->menit_telat = 0;
                    }

                    if (empty($kehadiran->jam_masuk) || $jam < $kehadiran->jam_masuk) {
                        $kehadiran->jam_masuk = $jam;
                        $jamStandar = self::JAM_MASUK_SATPAM_MALAM;
                        $standar = Carbon::parse($jamStandar);
                        $aktual = Carbon::parse($jam);

                        if ($aktual->gt($standar)) {
                            $kehadiran->menit_telat = $standar->diffInMinutes($aktual);
                            $kehadiran->status = 'terlambat';
                        } else {
                            $kehadiran->menit_telat = 0;
                            $kehadiran->status = 'hadir';
                        }
                    }

                    $kehadiran->sumber = 'fingerprint';
                    $kehadiran->jenis = $kehadiran->jenis ?? 'wfo';
                    $kehadiran->save();
                    $this->berhasil++;
                    return null;
                }

                // KASUS 3: Scan Siang (12:00 - 15:59)
                $kehadiran = Kehadiran::firstOrNew([
                    'pegawai_id' => $pegawai->id,
                    'tanggal'    => $tanggal,
                ]);
                if (!$kehadiran->exists) {
                    $kehadiran->jenis = 'wfo';
                    $kehadiran->sumber = 'fingerprint';
                    $kehadiran->status = 'hadir';
                    $kehadiran->jam_masuk = $jam;
                    $kehadiran->menit_telat = 0;
                } else {
                    $kehadiran->jam_keluar = $jam;
                }
                $kehadiran->save();
                $this->berhasil++;
                return null;
            }

            // ─── PEGAWAI REGULER ────────────────────────────────────────────────
            if (!$isMasuk && !$isPulang) {
                if ($dt->hour < 12) {
                    $isMasuk = true;
                } else {
                    $isPulang = true;
                }
            }

            $kehadiran = Kehadiran::firstOrNew([
                'pegawai_id' => $pegawai->id,
                'tanggal'    => $tanggal,
            ]);

            if (!$kehadiran->exists) {
                $kehadiran->jenis = 'wfo';
                $kehadiran->sumber = 'fingerprint';
                $kehadiran->status = 'hadir';
                $kehadiran->menit_telat = 0;
            }

            if ($isMasuk) {
                // Simpan jam masuk jika belum ada atau jika scan ini lebih awal
                if (empty($kehadiran->jam_masuk) || $jam < $kehadiran->jam_masuk) {
                    $kehadiran->jam_masuk = $jam;

                    $jamMasukStandar = self::JAM_MASUK_STANDAR;
                    $standar = Carbon::parse($jamMasukStandar);
                    $aktual = Carbon::parse($jam);

                    if ($aktual->gt($standar)) {
                        $kehadiran->menit_telat = $standar->diffInMinutes($aktual);
                        $kehadiran->status = 'terlambat';
                    } else {
                        $kehadiran->menit_telat = 0;
                        $kehadiran->status = 'hadir';
                    }
                }
            }

            if ($isPulang) {
                // Simpan jam keluar jika belum ada atau jika scan ini lebih akhir
                if (empty($kehadiran->jam_keluar) || $jam > $kehadiran->jam_keluar) {
                    $kehadiran->jam_keluar = $jam;
                }
            }

            $kehadiran->sumber = 'fingerprint';
            $kehadiran->jenis = $kehadiran->jenis ?? 'wfo';
            $kehadiran->save();

            $this->berhasil++;
            return null;
        }

        // 3. Fallback: Format lama (Kolom: NIP, Tanggal, Jam Masuk, Jam Keluar)
        if (isset($row['tanggal'])) {
            if (is_numeric($row['tanggal'])) {
                $tanggal = Date::excelToDateTimeObject($row['tanggal'])->format('Y-m-d');
            } else {
                $tanggal = Carbon::parse($row['tanggal'])->format('Y-m-d');
            }

            $jamMasuk = null;
            if (!empty($row['jam_masuk'])) {
                if (is_numeric($row['jam_masuk'])) {
                    $jamMasuk = Date::excelToDateTimeObject($row['jam_masuk'])->format('H:i:s');
                } else {
                    $jamMasuk = Carbon::parse($row['jam_masuk'])->format('H:i:s');
                }
            }

            $jamKeluar = null;
            if (!empty($row['jam_keluar'])) {
                if (is_numeric($row['jam_keluar'])) {
                    $jamKeluar = Date::excelToDateTimeObject($row['jam_keluar'])->format('H:i:s');
                } else {
                    $jamKeluar = Carbon::parse($row['jam_keluar'])->format('H:i:s');
                }
            }

            if ($isSatpam) {
                if ($jamMasuk && $jamMasuk >= '16:00:00') {
                    $jamMasukStandar = self::JAM_MASUK_SATPAM_MALAM;
                } else {
                    $jamMasukStandar = self::JAM_MASUK_SATPAM_PAGI;
                }
            } else {
                $jamMasukStandar = self::JAM_MASUK_STANDAR;
            }

            $menitTelat = 0;
            $status = 'hadir';

            if ($jamMasuk) {
                $standar = Carbon::parse($jamMasukStandar);
                $aktual = Carbon::parse($jamMasuk);

                if ($aktual->gt($standar)) {
                    $menitTelat = $standar->diffInMinutes($aktual);
                    $status = 'terlambat';
                }
            }

            Kehadiran::updateOrCreate(
                [
                    'pegawai_id' => $pegawai->id,
                    'tanggal'    => $tanggal,
                ],
                [
                    'jam_masuk'   => $jamMasuk,
                    'jam_keluar'  => $jamKeluar,
                    'jenis'       => 'wfo',
                    'sumber'      => 'fingerprint',
                    'status'      => $status,
                    'menit_telat' => $menitTelat,
                ]
            );

            $this->berhasil++;
            return null;
        }

        $this->gagal++;
        return null;
    }

    private function parseDateTime($raw): ?Carbon
    {
        if (empty($raw)) {
            return null;
        }

        if (is_numeric($raw)) {
            try {
                return Carbon::instance(Date::excelToDateTimeObject($raw));
            } catch (\Exception $e) {
                return null;
            }
        }

        $val = trim((string)$raw);
        $formats = [
            'd/m/Y H:i:s',
            'd/m/Y H:i',
            'd-m-Y H:i:s',
            'd-m-Y H:i',
            'Y-m-d H:i:s',
            'Y-m-d H:i',
            'm/d/Y H:i:s',
            'm/d/Y H:i',
        ];

        foreach ($formats as $fmt) {
            try {
                $dt = Carbon::createFromFormat($fmt, $val);
                if ($dt !== false) {
                    return $dt;
                }
            } catch (\Exception $e) {
                // Continue to next format
            }
        }

        try {
            return Carbon::parse($val);
        } catch (\Exception $e) {
            return null;
        }
    }
}