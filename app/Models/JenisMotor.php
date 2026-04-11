<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisMotor extends Model
{
    use HasFactory;

    protected $table = 'jenis_motor';

    protected $fillable = [
        'merk',
        'tipe',
        'deskripsi_jenis',
        'image_url',
    ];

    public function motors()
    {
        return $this->hasMany(Motor::class, 'id_jenis_motor');
    }
}
