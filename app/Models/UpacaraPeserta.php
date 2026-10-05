<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UpacaraPeserta extends Model
{
    protected $table = 'upacara_peserta';

    protected $fillable = [
        'upacara_id',
        'pegawai_id',
        'status',
        'keterangan',
    ];

    public function upacara()
    {
        return $this->belongsTo(Upacara::class, 'upacara_id');
    }

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'pegawai_id');
    }
}
