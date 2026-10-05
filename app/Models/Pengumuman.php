<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengumuman extends Model
{
    protected $table = 'pengumuman';
    protected $fillable = [
        'judul', 'isi', 'file_lampiran', 'kategori', 'tanggal', 'dibuat_oleh'
    ];

    public function pembuat()
    {
        return $this->belongsTo(\App\Models\User::class, 'dibuat_oleh');
    }
}