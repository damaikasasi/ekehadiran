<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('email')->nullable()->change();
        });

        // Hapus email auto-generate (@ekehadiran.local) yang sudah terlanjur dibuat di users & pegawai
        \Illuminate\Support\Facades\DB::table('users')
            ->where('email', 'like', '%@ekehadiran.local')
            ->update(['email' => null]);

        \Illuminate\Support\Facades\DB::table('pegawai')
            ->where('email', 'like', '%@ekehadiran.local')
            ->update(['email' => null]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('email')->nullable(false)->change();
        });
    }
};
