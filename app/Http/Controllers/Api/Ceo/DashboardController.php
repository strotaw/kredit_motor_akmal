<?php

namespace App\Http\Controllers\Api\Ceo;

use App\Http\Controllers\Controller;
use App\Models\Angsuran;
use App\Models\Kredit;
use App\Models\PengajuanKredit;
use App\Models\User;

class DashboardController extends Controller
{
    public function __invoke()
    {
        return response()->json([
            'stats' => [
                'users' => User::query()->where('role', 'user')->count(),
                'pengajuan' => PengajuanKredit::query()->count(),
                'approved' => PengajuanKredit::query()->where('status_pengajuan', 'diterima')->count(),
                'kredit_aktif' => Kredit::query()->where('status_kredit', 'cicil')->count(),
                'kredit_macet' => Kredit::query()->where('status_kredit', 'macet')->count(),
                'pendapatan' => (float) Angsuran::query()->sum('total_bayar'),
            ],
        ]);
    }
}
