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
        Schema::create('angsuran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_kredit')->references('id')->on('kredit')->onUpdate('cascade')->onDelete('cascade');
            $table->date('tgl_bayar');
            $table->integer('angsuran_ke');
            $table->double('total_bayar');
            $table->string('metode_bayar_snapshot')->nullable();
            $table->string('bukti_bayar')->nullable();
            $table->enum('status_verifikasi', ['menunggu', 'valid', 'ditolak'])->default('valid');
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('verified_at')->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('angsuran');
    }
};
