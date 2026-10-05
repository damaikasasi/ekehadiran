<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE kehadiran MODIFY status ENUM('hadir', 'terlambat', 'alpha', 'sakit', 'izin', 'menunggu_verifikasi') NOT NULL DEFAULT 'hadir'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE kehadiran MODIFY status ENUM('hadir', 'terlambat', 'alpha', 'sakit', 'izin') NOT NULL DEFAULT 'hadir'");
    }
};