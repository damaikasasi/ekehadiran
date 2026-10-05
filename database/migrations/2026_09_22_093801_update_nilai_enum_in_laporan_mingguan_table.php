<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Ubah kolom menjadi VARCHAR sementara agar aman saat konversi data lama
        DB::statement("ALTER TABLE `laporan_mingguan` MODIFY `nilai` VARCHAR(50) NULL");

        // 2. Petakan data lama ke skala baru
        DB::table('laporan_mingguan')
            ->whereIn('nilai', ['sangat_kurang', 'kurang'])
            ->update(['nilai' => 'dibawah_ekspektasi']);

        DB::table('laporan_mingguan')
            ->whereIn('nilai', ['butuh_perbaikan', 'baik'])
            ->update(['nilai' => 'sesuai_ekspektasi']);

        DB::table('laporan_mingguan')
            ->where('nilai', 'sangat_baik')
            ->update(['nilai' => 'diatas_ekspektasi']);

        // 3. Ubah kolom menjadi ENUM baru yang didukung sistem
        DB::statement("ALTER TABLE `laporan_mingguan` MODIFY `nilai` ENUM('dibawah_ekspektasi', 'sesuai_ekspektasi', 'diatas_ekspektasi') NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Kembalikan ke format enum sebelumnya
        DB::statement("ALTER TABLE `laporan_mingguan` MODIFY `nilai` VARCHAR(50) NULL");

        DB::table('laporan_mingguan')
            ->where('nilai', 'dibawah_ekspektasi')
            ->update(['nilai' => 'kurang']);

        DB::table('laporan_mingguan')
            ->where('nilai', 'sesuai_ekspektasi')
            ->update(['nilai' => 'baik']);

        DB::table('laporan_mingguan')
            ->where('nilai', 'diatas_ekspektasi')
            ->update(['nilai' => 'sangat_baik']);

        DB::statement("ALTER TABLE `laporan_mingguan` MODIFY `nilai` ENUM('sangat_kurang', 'kurang', 'butuh_perbaikan', 'baik', 'sangat_baik') NULL");
    }
};
