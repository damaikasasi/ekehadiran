<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LaporanKegiatan extends Model
{
    protected $table = 'laporan_kegiatan';
    protected $fillable = ['laporan_mingguan_id', 'kegiatan', 'foto'];

    public function laporan()
    {
        return $this->belongsTo(LaporanMingguan::class, 'laporan_mingguan_id');
    }

    /**
     * Mengembalikan daftar path foto baik disimpan sebagai string tunggal atau JSON array.
     */
    public function getFotoListAttribute(): array
    {
        if (empty($this->foto)) {
            return [];
        }

        $decoded = json_decode($this->foto, true);
        if (is_array($decoded)) {
            return $decoded;
        }

        return [$this->foto];
    }
}