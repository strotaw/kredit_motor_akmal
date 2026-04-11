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
            $table->string('kode_pengajuan')->unique();
            $table->date('tgl_pengajuan_kredit');
            $table->foreignId('user_id')->constrained('users')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('id_motor')->references('id')->on('motor')->onUpdate('cascade')->onDelete('cascade');
            $table->decimal('harga_cash', 11, 2);
            $table->decimal('dp', 11, 2);
            $table->foreignId('id_jenis_cicilan')->references('id')->on('jenis_cicilan')->onUpdate('cascade')->onDelete('cascade');
            $table->double('harga_kredit');
            $table->foreignId('id_asuransi')->references('id')->on('asuransi')->onUpdate('cascade')->onDelete('cascade');
            $table->double('biaya_asuransi_perbulan');
            $table->double('cicilan_perbulan');
            $table->string('url_kk')->nullable();
            $table->string('url_ktp')->nullable();
            $table->string('url_npwp')->nullable();
            $table->string('url_slip_gaji')->nullable();
            $table->string('url_foto')->nullable();
            $table->enum('status_pengajuan', ['menunggu', 'diproses', 'dibatalkan_pembeli', 'dibatalkan_penjual', 'bermasalah', 'diterima'])->default('menunggu');
            $table->string('keterangan_status_pengajuan')->nullable();
            $table->foreignId('assigned_admin_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('approved_at')->nullable();
            $table->foreignId('rejected_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('rejected_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengajuan_kredit');
    }
};
