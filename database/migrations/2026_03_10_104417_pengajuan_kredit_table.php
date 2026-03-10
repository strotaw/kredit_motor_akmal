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
        Schema::create('pengajuan_kredit', function (Blueprint $table) {
            $table->id();
            $table->date('tgl_pengajuan_kredit');
            $table->foreignId('id_pelanggan')->references('id')->on('pelanggan')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('id_motor')->references('id')->on('motor')->onUpdate('cascade')->onDelete('cascade');
            $table->decimal('harga_cash', 11);
            $table->decimal('dp', 11);
            $table->foreignId('id_jenis_cicilan')->references('id')->on('jenis_cicilan')->onUpdate('cascade')->onDelete('cascade');
            $table->double('harga_kredit');
            $table->foreignId('id_asuransi')->references('id')->on('asuransi')->onUpdate('cascade')->onDelete('cascade');
            $table->double('biaya_asuransi_perbulan');
            $table->double('cicilan_perbulan');
            $table->string('url_kk');
            $table->string('url_ktp');
            $table->string('url_npwp');
            $table->string('url_slip_gaji');
            $table->string('url_foto');
            $table->enum('status_pengajuan', ['menunggu', 'diproses','dibatalkan_pembeli','dibatalkan_penjual', 'bermasalah', 'diterima'])->default('menunggu');
            $table->string('keterangan_status_pengajuan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
