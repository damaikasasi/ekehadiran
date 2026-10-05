<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Izin extends Model
{
    protected $table = 'izin';
    protected $fillable = [
        'pegawai_id', 'jenis', 'ada_surat_dokter', 'tanggal_mulai',
        'tanggal_selesai', 'keterangan', 'catatan_penolakan', 'file_lampiran', 'status'
    ];

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class);
    }
}