class Asuransi extends Model
{
    protected $table = 'asuransi';

    protected $fillable = [
        'nama_perusahaan_asuransi',
        'nama_asuransi',
        'margin_asuransi',
        'no_rekening',
        'url_logo'
    ];

    public function pengajuanKredits()
    {
        return $this->hasMany(PengajuanKredit::class, 'id_asuransi');
    }
}