class Pengiriman extends Model
{
    protected $table = 'pengiriman';

    protected $fillable = [
        'no_invoice',
        'tgl_kirim',
        'tgl_tiba',
        'status_kirim',
        'nama_kurir',
        'telpon_kurir',
        'bukti_foto',
        'keterangan',
        'id_kredit',
    ];

    public function kredit()
    {
        return $this->belongsTo(Kredit::class, 'id_kredit');
    }
}