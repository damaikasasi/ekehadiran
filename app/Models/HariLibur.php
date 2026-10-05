<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HariLibur extends Model
{
    protected $table = 'hari_liburs';

    protected $fillable = [
        'tanggal',
        'nama_libur',
        'jenis',
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

    public function getJenisLabelAttribute(): string
    {
        return match ($this->jenis) {
            'cuti_bersama' => 'Cuti Bersama',
            'khusus'       => 'Libur Khusus/Instansi',
            default        => 'Libur Nasional',
        };
    }

    public function getJenisBadgeClassAttribute(): string
    {
        return match ($this->jenis) {
            'cuti_bersama' => 'bg-amber-100 text-amber-800 border-amber-200',
            'khusus'       => 'bg-purple-100 text-purple-800 border-purple-200',
            default        => 'bg-red-100 text-red-800 border-red-200',
        };
    }
}
