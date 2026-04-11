<?php

namespace Database\Seeders;

use App\Models\Angsuran;
use App\Models\Asuransi;
use App\Models\JenisCicilan;
use App\Models\JenisMotor;
use App\Models\Kredit;
use App\Models\MetodeBayar;
use App\Models\Motor;
use App\Models\PengajuanKredit;
use App\Models\Pengiriman;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $user = User::query()->updateOrCreate(
            ['email' => 'user@kreditmotor.test'],
            [
                'name' => 'Demo User',
                'password' => Hash::make('password'),
                'role' => 'user',
                'is_active' => true,
                'last_login_at' => now(),
                'email_verified_at' => now(),
            ]
        );

        $admin = User::query()->updateOrCreate(
            ['email' => 'admin@kreditmotor.test'],
            [
                'name' => 'Demo Admin',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'is_active' => true,
                'last_login_at' => now(),
                'email_verified_at' => now(),
            ]
        );

        User::query()->updateOrCreate(
            ['email' => 'ceo@kreditmotor.test'],
            [
                'name' => 'Demo CEO',
                'password' => Hash::make('password'),
                'role' => 'ceo',
                'is_active' => true,
                'last_login_at' => now(),
                'email_verified_at' => now(),
            ]
        );

        UserProfile::query()->updateOrCreate(
            ['user_id' => $user->id],
            [
                'nik' => '3174010101900001',
                'no_telp' => '081234567890',
                'tempat_lahir' => 'Jakarta',
                'tanggal_lahir' => '1990-01-01',
                'jenis_kelamin' => 'laki-laki',
                'pekerjaan' => 'Karyawan Swasta',
                'nama_perusahaan' => 'PT Demo Finance',
                'lama_bekerja_bulan' => 48,
                'penghasilan_bulanan' => 8500000,
                'status_pernikahan' => 'menikah',
                'alamat_ktp' => 'Jl. Mawar No. 10',
                'kota_ktp' => 'Jakarta Selatan',
                'provinsi_ktp' => 'DKI Jakarta',
                'kodepos_ktp' => '12345',
                'alamat_domisili' => 'Jl. Melati No. 21',
                'kota_domisili' => 'Jakarta Selatan',
                'provinsi_domisili' => 'DKI Jakarta',
                'kodepos_domisili' => '12345',
                'nama_kontak_darurat' => 'Siti Demo',
                'hubungan_kontak_darurat' => 'Istri',
                'no_telp_kontak_darurat' => '081298765432',
            ]
        );

        $scooter = JenisMotor::query()->updateOrCreate(
            ['merk' => 'Honda', 'tipe' => 'skuter'],
            [
                'deskripsi_jenis' => 'Skuter harian yang ringan dan nyaman untuk mobilitas kota.',
                'image_url' => 'jenis-motor/honda-skuter.jpg',
            ]
        );

        $sport = JenisMotor::query()->updateOrCreate(
            ['merk' => 'Yamaha', 'tipe' => 'sport_bike'],
            [
                'deskripsi_jenis' => 'Sport bike untuk user yang butuh gaya dan performa.',
                'image_url' => 'jenis-motor/yamaha-sport.jpg',
            ]
        );

        $vario = Motor::query()->updateOrCreate(
            ['nama_motor' => 'Honda Vario 160 CBS'],
            [
                'id_jenis_motor' => $scooter->id,
                'harga_jual' => 28500000,
                'deskripsi_motor' => 'Skuter premium untuk penggunaan harian dan touring ringan.',
                'warna' => 'Hitam Glossy',
                'kapasitas_mesin' => '160cc',
                'tahun' => '2026',
                'foto1' => 'motor/vario-160-1.jpg',
                'foto2' => 'motor/vario-160-2.jpg',
                'foto3' => 'motor/vario-160-3.jpg',
                'stok' => 12,
                'status_aktif' => true,
            ]
        );

        Motor::query()->updateOrCreate(
            ['nama_motor' => 'Honda Scoopy Prestige'],
            [
                'id_jenis_motor' => $scooter->id,
                'harga_jual' => 24100000,
                'deskripsi_motor' => 'Skuter retro modern untuk user yang suka desain stylish.',
                'warna' => 'Putih Coklat',
                'kapasitas_mesin' => '110cc',
                'tahun' => '2026',
                'foto1' => 'motor/scoopy-1.jpg',
                'foto2' => 'motor/scoopy-2.jpg',
                'foto3' => 'motor/scoopy-3.jpg',
                'stok' => 8,
                'status_aktif' => true,
            ]
        );

        Motor::query()->updateOrCreate(
            ['nama_motor' => 'Yamaha R15 Connected'],
            [
                'id_jenis_motor' => $sport->id,
                'harga_jual' => 39200000,
                'deskripsi_motor' => 'Motor sport untuk user yang ingin tampilan agresif.',
                'warna' => 'Blue Racing',
                'kapasitas_mesin' => '155cc',
                'tahun' => '2026',
                'foto1' => 'motor/r15-1.jpg',
                'foto2' => 'motor/r15-2.jpg',
                'foto3' => 'motor/r15-3.jpg',
                'stok' => 5,
                'status_aktif' => true,
            ]
        );

        $tenor24 = JenisCicilan::query()->updateOrCreate(
            ['lama_cicilan' => 24],
            ['margin_kredit' => 8.5]
        );

        JenisCicilan::query()->updateOrCreate(
            ['lama_cicilan' => 35],
            ['margin_kredit' => 10.0]
        );

        $asuransi = Asuransi::query()->updateOrCreate(
            ['nama_perusahaan_asuransi' => 'Asuransi Aman Sentosa', 'nama_asuransi' => 'All Risk Basic'],
            [
                'margin_asuransi' => 2.25,
                'no_rekening' => '1234567890',
                'url_logo' => 'asuransi/aman-sentosa.png',
            ]
        );

        $metodeBayar = MetodeBayar::query()->updateOrCreate(
            ['metode_bayar' => 'Transfer Bank', 'tempat_bayar' => 'BCA Virtual Account'],
            [
                'no_rekening' => '8800123456',
                'url_logo' => 'metode-bayar/bca.png',
            ]
        );

        $pengajuan = PengajuanKredit::query()->updateOrCreate(
            ['kode_pengajuan' => 'PGJ-000001'],
            [
                'tgl_pengajuan_kredit' => now()->toDateString(),
                'user_id' => $user->id,
                'id_motor' => $vario->id,
                'harga_cash' => 28500000,
                'dp' => 7000000,
                'id_jenis_cicilan' => $tenor24->id,
                'harga_kredit' => 24000000,
                'id_asuransi' => $asuransi->id,
                'biaya_asuransi_perbulan' => 150000,
                'cicilan_perbulan' => 1100000,
                'status_pengajuan' => 'diterima',
                'keterangan_status_pengajuan' => 'Dokumen lengkap dan pengajuan layak diproses.',
                'assigned_admin_id' => $admin->id,
                'approved_by' => $admin->id,
                'approved_at' => now(),
                'url_kk' => 'dokumen/kk-demo.jpg',
                'url_ktp' => 'dokumen/ktp-demo.jpg',
                'url_npwp' => 'dokumen/npwp-demo.jpg',
                'url_slip_gaji' => 'dokumen/slip-gaji-demo.jpg',
                'url_foto' => 'dokumen/foto-diri-demo.jpg',
            ]
        );

        $kredit = Kredit::query()->updateOrCreate(
            ['nomor_kontrak' => 'KRD-000001'],
            [
                'id_pengajuan_kredit' => $pengajuan->id,
                'id_metode_bayar' => $metodeBayar->id,
                'tgl_mulai_kredit' => now()->toDateString(),
                'tgl_selesai_kredit' => now()->addMonths(24)->toDateString(),
                'sisa_kredit' => 21800000,
                'status_kredit' => 'cicil',
                'keterangan_status_kredit' => 'Kontrak aktif.',
                'created_by' => $admin->id,
            ]
        );

        Pengiriman::query()->updateOrCreate(
            ['no_invoice' => 'INV-000001'],
            [
                'tgl_kirim' => now()->addDays(2),
                'tgl_tiba' => now()->addDays(4),
                'status_kirim' => 'dikirim',
                'nama_kurir' => 'Rizal Kurir',
                'telpon_kurir' => '081322211199',
                'bukti_foto' => 'pengiriman/invoice-000001.jpg',
                'keterangan' => 'Unit sedang dalam perjalanan ke alamat user.',
                'id_kredit' => $kredit->id,
            ]
        );

        Angsuran::query()->updateOrCreate(
            [
                'id_kredit' => $kredit->id,
                'angsuran_ke' => 1,
            ],
            [
                'tgl_bayar' => now()->subDays(5)->toDateString(),
                'total_bayar' => 1100000,
                'metode_bayar_snapshot' => 'Transfer Bank',
                'status_verifikasi' => 'valid',
                'verified_by' => $admin->id,
                'verified_at' => now()->subDays(4),
                'keterangan' => 'Pembayaran angsuran pertama berhasil diverifikasi.',
            ]
        );
    }
}
