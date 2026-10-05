<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    
    public function up(): void
    {
        Schema::table('pegawai', function (Blueprint $table) {
            $table->text('struktur_fungsi')->nullable()->after('jabatan');
            $table->text('capaian_kerja')->nullable()->after('struktur_fungsi');
        });
    }

    public function down(): void
    {
    Schema::table('pegawai', function (Blueprint $table) {
        $table->dropColumn(['struktur_fungsi', 'capaian_kerja']);
    });
    }
};