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
        Schema::create('pengiriman', function (Blueprint $table) {
            $table->id();
            $table->string('no_invoice');
            $table->datetime('tgl_kirim');
            $table->datetime('tgl_tiba');
            $table->enum('status_kirim', ['dikirim', 'diterima'])->default('dikirim');
            $table->string('nama_kurir');
            $table->string('telpon_kurir');
            $table->string('bukti_foto');
            $table->text('keterangan')->nullable();
            $table->foreignId('id_kredit')->references('id')->on('kredit')->onUpdate('cascade')->onDelete('cascade');
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
