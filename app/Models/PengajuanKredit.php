<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengajuanKredit extends Model
{
    use HasFactory;

    protected $table = 'pengajuan_kredit';

    protected $fillable = [
        'kode_pengajuan',
        'tgl_pengajuan_kredit',
        'user_id',
        'id_motor',
        'harga_cash',
        'dp',
        'id_jenis_cicilan',
        'harga_kredit',
        'id_asuransi',
        'biaya_asuransi_perbulan',
        'cicilan_perbulan',
        'url_kk',
        'url_ktp',
        'url_npwp',
        'url_slip_gaji',
        'url_foto',
        'status_pengajuan',
        'keterangan_status_pengajuan',
        'assigned_admin_id',
        'approved_by',
        'approved_at',
        'rejected_by',
        'rejected_at',
    ];

    protected function casts(): array
    {
        return [
            'tgl_pengajuan_kredit' => 'date',
            'harga_cash' => 'float',
            'dp' => 'float',
            'harga_kredit' => 'float',
            'biaya_asuransi_perbulan' => 'float',
            'cicilan_perbulan' => 'float',
            'approved_at' => 'datetime',
            'rejected_at' => 'datetime',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function motor()
    {
        return $this->belongsTo(Motor::class, 'id_motor');
    }

    public function jenisCicilan()
    {
        return $this->belongsTo(JenisCicilan::class, 'id_jenis_cicilan');
    }

    public function asuransi()
    {
        return $this->belongsTo(Asuransi::class, 'id_asuransi');
    }

    public function kredit()
    {
        return $this->hasOne(Kredit::class, 'id_pengajuan_kredit');
    }

    public function documents()
    {
        return $this->hasMany(PengajuanDokumen::class);
    }

    public function statusLogs()
    {
        return $this->hasMany(PengajuanStatusLog::class);
    }

    public function assignedAdmin()
    {
        return $this->belongsTo(User::class, 'assigned_admin_id');
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function rejectedBy()
    {
        return $this->belongsTo(User::class, 'rejected_by');
    }
}
