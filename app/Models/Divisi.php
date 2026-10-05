<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Divisi extends Model
{
    protected $table = 'divisi';
    protected $fillable = ['nama_divisi', 'ketua_divisi'];

    public function pegawai()
    {
        return $this->hasMany(Pegawai::class);
    }
}