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
        Schema::create('jenis_motor', function (Blueprint $table) {
            $table->id();
            $table->string('merk', 50);
            $table->enum('tipe', ['bebek', 'skuter', 'dual_sport', 'naked_sport', 'sport_bike', 'retro', 'cruiser', 'sport_touring', 'dirt_bike', 'motocross', 'scrambler', 'atv', 'motor_adventure', 'lainnya']);
            $table->string('deskripsi_jenis');
            $table->string('image_url');
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
