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
        Schema::create('motor', function (Blueprint $table) {
            $table->id();
            $table->string('nama_motor', 100);
            $table->foreignId('id_jenis_motor')->references('id')->on('jenis_motor')->onUpdate('cascade')->onDelete('cascade');
            $table->decimal('harga_jual', 11);
            $table->text('deskripsi_motor');
            $table->string('warna', 50);
            $table->string('kapasitas_mesin', 10);
            $table->string('tahun', 4);
            $table->string('foto1');
            $table->string('foto2');
            $table->string('foto3');
            $table->decimal('stok', 11);
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
