<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ImportLog extends Model
{
    protected $table = 'import_log';
    protected $fillable = [
        'nama_file', 'diupload_oleh', 'jumlah_berhasil',
        'jumlah_gagal', 'status'
    ];

    public function uploader()
    {
        return $this->belongsTo(\App\Models\User::class, 'diupload_oleh');
    }
}