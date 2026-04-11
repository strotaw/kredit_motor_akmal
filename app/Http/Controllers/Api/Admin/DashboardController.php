<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kredit;
use App\Models\PengajuanKredit;
use App\Models\Pengiriman;

class DashboardController extends Controller
{
    public function __invoke()
    {
        return response()->json([
            'stats' => [
                'menunggu' => PengajuanKredit::query()->where('status_pengajuan', 'menunggu')->count(),
                'diproses' => PengajuanKredit::query()->where('status_pengajuan', 'diproses')->count(),
                'bermasalah' => PengajuanKredit::query()->where('status_pengajuan', 'bermasalah')->count(),
                'kredit_aktif' => Kredit::query()->where('status_kredit', 'cicil')->count(),
                'kredit_macet' => Kredit::query()->where('status_kredit', 'macet')->count(),
                'pengiriman' => Pengiriman::query()->where('status_kirim', 'dikirim')->count(),
            ],
        ]);
    }
}
