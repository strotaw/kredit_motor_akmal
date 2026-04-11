<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Models\Kredit;
use App\Models\PengajuanKredit;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        $user = $request->user();

        return response()->json([
            'stats' => [
                'pengajuan' => PengajuanKredit::query()->where('user_id', $user->id)->count(),
                'kredit_aktif' => Kredit::query()
                    ->whereHas('pengajuanKredit', fn ($query) => $query->where('user_id', $user->id))
                    ->whereIn('status_kredit', ['cicil', 'macet'])
                    ->count(),
            ],
            'latest_pengajuan' => PengajuanKredit::query()
                ->with(['motor', 'jenisCicilan'])
                ->where('user_id', $user->id)
                ->latest('tgl_pengajuan_kredit')
                ->first(),
        ]);
    }
}
