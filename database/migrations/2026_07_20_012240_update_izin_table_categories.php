<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('izin', function (Blueprint $table) {
            $table->boolean('ada_surat_dokter')->nullable()->after('jenis');
        });

        DB::statement("ALTER TABLE izin MODIFY jenis ENUM('cuti_tahunan', 'sakit', 'dinas', 'lainnya') NOT NULL");
    }

    public function down(): void
    {
        Schema::table('izin', function (Blueprint $table) {
            $table->dropColumn('ada_surat_dokter');
        });

        DB::statement("ALTER TABLE izin MODIFY jenis ENUM('cuti_tahunan', 'sakit', 'duka_cita', 'izin_lainnya') NOT NULL");
    }
};