<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('pengajuan_kredit') && Schema::hasColumn('pengajuan_kredit', 'id_pelanggan')) {
            DB::statement('ALTER TABLE pengajuan_kredit MODIFY COLUMN id_pelanggan BIGINT UNSIGNED NULL');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
