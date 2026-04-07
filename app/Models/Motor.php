class Motor extends Model
{
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
        'stok'
    ];

    public function jenisMotor()
    {
        return $this->belongsTo(JenisMotor::class, 'id_jenis_motor');
    }

    public function pengajuanKredits()
    {
        return $this->hasMany(PengajuanKredit::class, 'id_motor');
    }
}