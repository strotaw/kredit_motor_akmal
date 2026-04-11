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
        Schema::create('pengajuan_dokumen', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengajuan_kredit_id')->constrained('pengajuan_kredit')->cascadeOnUpdate()->cascadeOnDelete();
            $table->enum('jenis_dokumen', ['ktp', 'kk', 'npwp', 'slip_gaji', 'foto_diri']);
            $table->string('file_path');
            $table->enum('status_verifikasi', ['menunggu', 'valid', 'revisi'])->default('menunggu');
            $table->text('catatan_verifikasi')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengajuan_dokumen');
    }
};
