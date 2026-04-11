<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengiriman extends Model
{
    use HasFactory;

    protected $table = 'pengiriman';

    protected $fillable = [
        'no_invoice',
        'tgl_kirim',
        'tgl_tiba',
        'status_kirim',
        'nama_kurir',
        'telpon_kurir',
        'bukti_foto',
        'keterangan',
        'id_kredit',
    ];

    protected function casts(): array
    {
        return [
            'tgl_kirim' => 'datetime',
            'tgl_tiba' => 'datetime',
        ];
    }

    public function kredit()
    {
        return $this->belongsTo(Kredit::class, 'id_kredit');
    }
}
