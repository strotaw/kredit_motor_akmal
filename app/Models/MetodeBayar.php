class MetodeBayar extends Model
{
    protected $table = 'metode_bayar';

    protected $fillable = [
        'metode_bayar',
        'tempat_bayar',
        'no_rekening',
        'url_logo'
    ];

    public function kredits()
    {
        return $this->hasMany(Kredit::class, 'id_metode_bayar');
    }
}