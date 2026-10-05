<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pegawai extends Model
{
    protected $table = 'pegawai';
    protected $fillable = [
        'divisi_id', 'nama', 'jabatan', 'struktur_fungsi', 'capaian_kerja', 'foto',
        'no_hp', 'email', 'tanggal_masuk', 'status_aktif'
    ];

    public function divisi()
    {
        return $this->belongsTo(Divisi::class);
    }

    public function kehadiran()
    {
        return $this->hasMany(Kehadiran::class);
    }

    public function izin()
    {
        return $this->hasMany(Izin::class);
    }

    public function laporanMingguan()
    {
        return $this->hasMany(LaporanMingguan::class);
    }

    public function user()
    {
        return $this->hasOne(\App\Models\User::class);
    }


    public function upacaraPeserta()
    {
        return $this->hasMany(UpacaraPeserta::class, 'pegawai_id');
    }

    public function isSatpam(): bool
    {
        $divisiName = strtolower($this->divisi->nama_divisi ?? '');
        $jabatan = strtolower($this->jabatan ?? '');
        return str_contains($divisiName, 'satpam') || str_contains($divisiName, 'keamanan') || str_contains($jabatan, 'satpam') || str_contains($jabatan, 'keamanan');
    }
}