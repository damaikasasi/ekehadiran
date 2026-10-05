<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kehadiran', function (Blueprint $table) {
            $table->string('foto_keluar')->nullable()->after('foto');
            $table->string('lokasi_keluar')->nullable()->after('lokasi');
        });
    }

    public function down(): void
    {
        Schema::table('kehadiran', function (Blueprint $table) {
            $table->dropColumn(['foto_keluar', 'lokasi_keluar']);
        });
    }
};
