<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengajuanStatusLog extends Model
{
    use HasFactory;

    protected $table = 'pengajuan_status_logs';

    public $timestamps = false;

    protected $fillable = [
        'pengajuan_kredit_id',
        'status_lama',
        'status_baru',
        'catatan',
        'changed_by',
        'created_at',
    ];

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
        ];
    }

    public function pengajuanKredit()
    {
        return $this->belongsTo(PengajuanKredit::class);
    }

    public function changedBy()
    {
        return $this->belongsTo(User::class, 'changed_by');
    }
}
