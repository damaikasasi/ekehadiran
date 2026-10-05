<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Upacara extends Model
{
    protected $table = 'upacara';

    protected $fillable = [
        'nama_upacara',
        'tanggal',
        'waktu_mulai',
        'keterangan',
        'created_by',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function peserta()
    {
        return $this->hasMany(UpacaraPeserta::class, 'upacara_id');
    }

    public function getJumlahHadirAttribute()
    {
        return $this->peserta()->where('status', 'hadir')->count();
    }

    public function getJumlahTidakHadirAttribute()
    {
        return $this->peserta()->where('status', 'tidak_hadir')->count();
    }

    public function getJumlahIzinAttribute()
    {
        return $this->peserta()->where('status', 'izin')->count();
    }

    public function syncPeserta()
    {
        $tanggalUpacara = \Carbon\Carbon::parse($this->tanggal)->format('Y-m-d');
        $activePegawais = Pegawai::where('status_aktif', 'aktif')->get();

        foreach ($activePegawais as $pegawai) {

            $peserta = UpacaraPeserta::where('upacara_id', $this->id)
                ->where('pegawai_id', $pegawai->id)
                ->first();

            $izin = Izin::where('pegawai_id', $pegawai->id)
                ->where(function ($q) {
                    $q->where('status', 'disetujui')
                      ->orWhere('status', 'disetujui_atasan');
                })
                ->where('tanggal_mulai', '<=', $tanggalUpacara)
                ->where('tanggal_selesai', '>=', $tanggalUpacara)
                ->first();

            if ($izin) {
                $labelIzin = match (strtolower($izin->jenis)) {
                    'sakit'   => 'Sakit',
                    'cuti'    => 'Cuti',
                    'dinas'   => 'Dinas Luar',
                    default   => ucwords(str_replace('_', ' ', $izin->jenis))
                };
                $ketIzin = "Izin ({$labelIzin})";

                if (!$peserta) {
                    UpacaraPeserta::create([
                        'upacara_id' => $this->id,
                        'pegawai_id' => $pegawai->id,
                        'status'     => 'izin',
                        'keterangan' => $ketIzin,
                    ]);
                } elseif ($peserta->status !== 'hadir') {
                    $peserta->update([
                        'status'     => 'izin',
                        'keterangan' => $ketIzin,
                    ]);
                }
            } else {
                if (!$peserta) {
                    UpacaraPeserta::create([
                        'upacara_id' => $this->id,
                        'pegawai_id' => $pegawai->id,
                        'status'     => 'tidak_hadir',
                        'keterangan' => 'Tidak Hadir',
                    ]);
                } elseif ($peserta->status === 'izin' && (str_starts_with($peserta->keterangan ?? '', 'Izin (') || $peserta->keterangan === 'Izin')) {
                    $peserta->update([
                        'status'     => 'tidak_hadir',
                        'keterangan' => 'Tidak Hadir',
                    ]);
                }
            }
        }
    }
}

