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
        Schema::create('user_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained('users')->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('nik', 25)->nullable();
            $table->string('no_telp', 20)->nullable();
            $table->string('tempat_lahir')->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->enum('jenis_kelamin', ['laki-laki', 'perempuan'])->nullable();
            $table->string('pekerjaan')->nullable();
            $table->string('nama_perusahaan')->nullable();
            $table->integer('lama_bekerja_bulan')->nullable();
            $table->decimal('penghasilan_bulanan', 14, 2)->nullable();
            $table->string('status_pernikahan')->nullable();
            $table->string('alamat_ktp')->nullable();
            $table->string('kota_ktp')->nullable();
            $table->string('provinsi_ktp')->nullable();
            $table->string('kodepos_ktp')->nullable();
            $table->string('alamat_domisili')->nullable();
            $table->string('kota_domisili')->nullable();
            $table->string('provinsi_domisili')->nullable();
            $table->string('kodepos_domisili')->nullable();
            $table->string('nama_kontak_darurat')->nullable();
            $table->string('hubungan_kontak_darurat')->nullable();
            $table->string('no_telp_kontak_darurat', 20)->nullable();
            $table->string('foto_profil')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_profiles');
    }
};
