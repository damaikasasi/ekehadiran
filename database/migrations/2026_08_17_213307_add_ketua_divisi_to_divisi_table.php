<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('divisi', function (Blueprint $table) {
            $table->string('ketua_divisi')->nullable()->after('nama_divisi');
        });
    }

    public function down(): void
    {
        Schema::table('divisi', function (Blueprint $table) {
            $table->dropColumn('ketua_divisi');
        });
    }
};
