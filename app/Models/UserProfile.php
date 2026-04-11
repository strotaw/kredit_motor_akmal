<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
    use HasFactory;

    protected $table = 'user_profiles';

    protected $fillable = [
        'user_id',
        'nik',
        'no_telp',
        'tempat_lahir',
        'tanggal_lahir',
        'jenis_kelamin',
        'pekerjaan',
        'nama_perusahaan',
        'lama_bekerja_bulan',
        'penghasilan_bulanan',
        'status_pernikahan',
        'alamat_ktp',
        'kota_ktp',
        'provinsi_ktp',
        'kodepos_ktp',
        'alamat_domisili',
        'kota_domisili',
        'provinsi_domisili',
        'kodepos_domisili',
        'nama_kontak_darurat',
        'hubungan_kontak_darurat',
        'no_telp_kontak_darurat',
        'foto_profil',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_lahir' => 'date',
            'penghasilan_bulanan' => 'float',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
