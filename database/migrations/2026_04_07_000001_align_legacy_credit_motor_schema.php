<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $isMySql = DB::connection()->getDriverName() === 'mysql';

        if (Schema::hasTable('users')) {
            if ($isMySql && Schema::hasColumn('users', 'role')) {
                DB::statement("UPDATE users SET role = 'admin' WHERE role = 'marketing'");
                DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('user', 'admin', 'ceo') NOT NULL DEFAULT 'user'");
            }

            Schema::table('users', function (Blueprint $table): void {
                if (! Schema::hasColumn('users', 'is_active')) {
                    $table->boolean('is_active')->default(true)->after('role');
                }

                if (! Schema::hasColumn('users', 'last_login_at')) {
                    $table->timestamp('last_login_at')->nullable()->after('is_active');
                }
            });
        }

        if (! Schema::hasTable('user_profiles')) {
            Schema::create('user_profiles', function (Blueprint $table): void {
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

        if ($isMySql && Schema::hasTable('pelanggan') && Schema::hasTable('users') && Schema::hasTable('user_profiles')) {
            DB::statement("
                INSERT INTO user_profiles (
                    user_id, no_telp, alamat_ktp, kota_ktp, provinsi_ktp, kodepos_ktp,
                    alamat_domisili, kota_domisili, provinsi_domisili, kodepos_domisili,
                    foto_profil, created_at, updated_at
                )
                SELECT
                    u.id,
                    p.no_telp,
                    p.alamat1,
                    p.kota1,
                    p.provinsi1,
                    p.kodepos1,
                    p.alamat2,
                    p.kota2,
                    p.provinsi2,
                    p.kodepos2,
                    p.foto,
                    COALESCE(p.created_at, NOW()),
                    COALESCE(p.updated_at, NOW())
                FROM pelanggan p
                INNER JOIN users u ON u.email = p.email
                LEFT JOIN user_profiles up ON up.user_id = u.id
                WHERE up.id IS NULL
            ");
        }

        if (Schema::hasTable('motor')) {
            Schema::table('motor', function (Blueprint $table): void {
                if (! Schema::hasColumn('motor', 'status_aktif')) {
                    $table->boolean('status_aktif')->default(true)->after('stok');
                }
            });
        }

        if (Schema::hasTable('pengajuan_kredit')) {
            Schema::table('pengajuan_kredit', function (Blueprint $table): void {
                if (! Schema::hasColumn('pengajuan_kredit', 'kode_pengajuan')) {
                    $table->string('kode_pengajuan')->nullable()->after('id');
                }

                if (! Schema::hasColumn('pengajuan_kredit', 'user_id')) {
                    $table->unsignedBigInteger('user_id')->nullable()->after('tgl_pengajuan_kredit');
                    $table->index('user_id');
                }

                if (! Schema::hasColumn('pengajuan_kredit', 'assigned_admin_id')) {
                    $table->unsignedBigInteger('assigned_admin_id')->nullable()->after('keterangan_status_pengajuan');
                }

                if (! Schema::hasColumn('pengajuan_kredit', 'approved_by')) {
                    $table->unsignedBigInteger('approved_by')->nullable()->after('assigned_admin_id');
                }

                if (! Schema::hasColumn('pengajuan_kredit', 'approved_at')) {
                    $table->timestamp('approved_at')->nullable()->after('approved_by');
                }

                if (! Schema::hasColumn('pengajuan_kredit', 'rejected_by')) {
                    $table->unsignedBigInteger('rejected_by')->nullable()->after('approved_at');
                }

                if (! Schema::hasColumn('pengajuan_kredit', 'rejected_at')) {
                    $table->timestamp('rejected_at')->nullable()->after('rejected_by');
                }
            });

            if ($isMySql && Schema::hasTable('pelanggan') && Schema::hasColumn('pengajuan_kredit', 'id_pelanggan') && Schema::hasColumn('pengajuan_kredit', 'user_id')) {
                DB::statement("
                    UPDATE pengajuan_kredit pk
                    INNER JOIN pelanggan p ON pk.id_pelanggan = p.id
                    INNER JOIN users u ON u.email = p.email
                    SET pk.user_id = u.id
                    WHERE pk.user_id IS NULL
                ");
            }

            if ($isMySql) {
                DB::statement("
                    UPDATE pengajuan_kredit
                    SET kode_pengajuan = CONCAT('PGJ-', LPAD(id, 6, '0'))
                    WHERE kode_pengajuan IS NULL OR kode_pengajuan = ''
                ");

                DB::statement("
                    ALTER TABLE pengajuan_kredit
                    MODIFY COLUMN keterangan_status_pengajuan VARCHAR(255) NULL
                ");
            }
        }

        if (Schema::hasTable('kredit')) {
            Schema::table('kredit', function (Blueprint $table): void {
                if (! Schema::hasColumn('kredit', 'nomor_kontrak')) {
                    $table->string('nomor_kontrak')->nullable()->after('id');
                }

                if (! Schema::hasColumn('kredit', 'created_by')) {
                    $table->unsignedBigInteger('created_by')->nullable()->after('keterangan_status_kredit');
                }
            });

            if ($isMySql) {
                DB::statement("
                    UPDATE kredit
                    SET nomor_kontrak = CONCAT('KRD-', LPAD(id, 6, '0'))
                    WHERE nomor_kontrak IS NULL OR nomor_kontrak = ''
                ");
            }
        }

        if (Schema::hasTable('angsuran')) {
            Schema::table('angsuran', function (Blueprint $table): void {
                if (! Schema::hasColumn('angsuran', 'metode_bayar_snapshot')) {
                    $table->string('metode_bayar_snapshot')->nullable()->after('total_bayar');
                }

                if (! Schema::hasColumn('angsuran', 'bukti_bayar')) {
                    $table->string('bukti_bayar')->nullable()->after('metode_bayar_snapshot');
                }

                if (! Schema::hasColumn('angsuran', 'status_verifikasi')) {
                    $table->enum('status_verifikasi', ['menunggu', 'valid', 'ditolak'])->default('valid')->after('bukti_bayar');
                }

                if (! Schema::hasColumn('angsuran', 'verified_by')) {
                    $table->unsignedBigInteger('verified_by')->nullable()->after('status_verifikasi');
                }

                if (! Schema::hasColumn('angsuran', 'verified_at')) {
                    $table->timestamp('verified_at')->nullable()->after('verified_by');
                }
            });
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
