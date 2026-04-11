<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengajuanDokumen extends Model
{
    use HasFactory;

    protected $table = 'pengajuan_dokumen';

    protected $fillable = [
        'pengajuan_kredit_id',
        'jenis_dokumen',
        'file_path',
        'status_verifikasi',
        'catatan_verifikasi',
    ];

    public function pengajuanKredit()
    {
        return $this->belongsTo(PengajuanKredit::class);
    }
}
