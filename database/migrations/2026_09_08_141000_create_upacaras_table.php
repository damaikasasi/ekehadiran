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
        Schema::create('upacara', function (Blueprint $table) {
            $table->id();
            $table->string('nama_upacara');
            $table->date('tanggal');
            $table->time('waktu_mulai')->nullable();
            $table->text('keterangan')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('upacara_peserta', function (Blueprint $table) {
            $table->id();
            $table->foreignId('upacara_id')->constrained('upacara')->cascadeOnDelete();
            $table->foreignId('pegawai_id')->constrained('pegawai')->cascadeOnDelete();
            $table->enum('status', ['hadir', 'tidak_hadir', 'izin'])->default('hadir');
            $table->string('keterangan')->nullable();
            $table->timestamps();

            $table->unique(['upacara_id', 'pegawai_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('upacara_peserta');
        Schema::dropIfExists('upacara');
    }
};
