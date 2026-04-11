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
        Schema::create('kredit', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_kontrak')->unique();
            $table->foreignId('id_pengajuan_kredit')->references('id')->on('pengajuan_kredit')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('id_metode_bayar')->references('id')->on('metode_bayar')->onUpdate('cascade')->onDelete('cascade');
            $table->date('tgl_mulai_kredit');
            $table->date('tgl_selesai_kredit');
            $table->double('sisa_kredit');
            $table->enum('status_kredit', ['cicil', 'macet', 'lunas'])->default('cicil');
            $table->string('keterangan_status_kredit')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kredit');
    }
};
