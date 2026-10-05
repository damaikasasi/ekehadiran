<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kehadiran extends Model
{
    protected $table = 'kehadiran';
    protected $fillable = [
        'pegawai_id', 'tanggal', 'jam_masuk', 'jam_keluar',
        'jenis', 'sumber', 'status', 'menit_telat', 'lokasi', 'foto',
        'foto_keluar', 'lokasi_keluar', 'keterangan'
    ];

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class);
    }

    public function getMenitPulangAwalAttribute()
    {
        if (empty($this->jam_keluar)) {
            return 0;
        }

        try {
            $jamKeluar = \Carbon\Carbon::parse($this->jam_keluar);
            $jamStandarPulang = \Carbon\Carbon::parse('16:00:00');

            if ($jamKeluar->lt($jamStandarPulang)) {
                return (int) round($jamKeluar->diffInMinutes($jamStandarPulang));
            }
        } catch (\Exception $e) {
            return 0;
        }

        return 0;
    }

    public function getIsPulangAwalAttribute()
    {
        return $this->menit_pulang_awal > 0;
    }
}