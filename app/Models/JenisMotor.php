class JenisMotor extends Model
{
    protected $table = 'jenis_motor';

    protected $fillable = [
        'merk',
        'tipe',
        'deskripsi_jenis',
        'image_url'
    ];

    public function motors()
    {
        return $this->hasMany(Motor::class, 'id_jenis_motor');
    }
}