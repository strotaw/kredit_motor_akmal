<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asuransi extends Model
{
    use HasFactory;

    protected $table = 'asuransi';

    protected $fillable = [
        'nama_perusahaan_asuransi',
        'nama_asuransi',
        'margin_asuransi',
        'no_rekening',
        'url_logo',
    ];

    protected function casts(): array
    {
        return [
            'margin_asuransi' => 'decimal:2',
        ];
    }

    public function pengajuanKredits()
    {
        return $this->hasMany(PengajuanKredit::class, 'id_asuransi');
    }
}
