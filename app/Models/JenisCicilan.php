class JenisCicilan extends Model
{
    protected $table = 'jenis_cicilan';

    protected $fillable = [
        'lama_cicilan',
        'margin_kredit'
    ];

    public function pengajuanKredits()
    {
        return $this->hasMany(PengajuanKredit::class, 'id_jenis_cicilan');
    }
}