<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Motor extends Model
{
    use HasFactory;

    protected $table = 'motor';

    protected $fillable = [
        'nama_motor',
        'id_jenis_motor',
        'harga_jual',
        'deskripsi_motor',
        'warna',
        'kapasitas_mesin',
        'tahun',
        'foto1',
        'foto2',
        'foto3',
        'stok',
        'status_aktif',
    ];

    protected function casts(): array
    {
        return [
            'harga_jual' => 'float',
            'stok' => 'float',
            'status_aktif' => 'boolean',
        ];
    }

    public function jenisMotor()
    {
        return $this->belongsTo(JenisMotor::class, 'id_jenis_motor');
    }

    public function pengajuanKredits()
    {
        return $this->hasMany(PengajuanKredit::class, 'id_motor');
    }

    public function getPrimaryImageAttribute(): ?string
    {
        return $this->foto1 ?: $this->foto2 ?: $this->foto3;
    }
}
