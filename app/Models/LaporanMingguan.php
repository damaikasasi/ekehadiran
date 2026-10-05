<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LaporanMingguan extends Model
{
    protected $table = 'laporan_mingguan';
    protected $fillable = [
        'pegawai_id', 'minggu_ke', 'bulan', 'tahun',
        'kegiatan', 'kendala', 'file_laporan', 'status', 'nilai', 'catatan_atasan'
    ];

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class);
    }

    public function kegiatanList()
    {
        return $this->hasMany(LaporanKegiatan::class, 'laporan_mingguan_id');
    }
}