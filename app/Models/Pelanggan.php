class Pelanggan extends Model
{
    protected $table = 'pelanggan';

    protected $fillable = [
        'nama_pelanggan',
        'email',
        'password',
        'no_telp',
        'alamat1',
        'kota1',
        'provinsi1',
        'kodepos1',
        'alamat2',
        'kota2',
        'provinsi2',
        'kodepos2',
        'alamat3',
        'kota3',
        'provinsi3',
        'kodepos3',
        'foto',
    ];

    protected $hidden = ['password'];

    public function pengajuanKredits()
    {
        return $this->hasMany(PengajuanKredit::class, 'id_pelanggan');
    }
}