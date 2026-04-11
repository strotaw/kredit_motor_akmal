<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Angsuran extends Model
{
    use HasFactory;

    protected $table = 'angsuran';

    protected $fillable = [
        'id_kredit',
        'tgl_bayar',
        'angsuran_ke',
        'total_bayar',
        'metode_bayar_snapshot',
        'bukti_bayar',
        'status_verifikasi',
        'verified_by',
        'verified_at',
        'keterangan',
    ];

    protected function casts(): array
    {
        return [
            'tgl_bayar' => 'date',
            'verified_at' => 'datetime',
            'total_bayar' => 'float',
        ];
    }

    public function kredit()
    {
        return $this->belongsTo(Kredit::class, 'id_kredit');
    }

    public function verifiedBy()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }
}
