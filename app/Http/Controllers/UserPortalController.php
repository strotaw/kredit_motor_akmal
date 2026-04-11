<?php

namespace App\Http\Controllers;

use App\Models\Angsuran;
use App\Models\Kredit;
use App\Models\PengajuanKredit;
use Illuminate\Http\Request;

class UserPortalController extends Controller
{
    public function dashboard(Request $request)
    {
        $user = $request->user();

        return view('user.dashboard', [
            'latestPengajuan' => PengajuanKredit::query()
                ->with(['motor', 'jenisCicilan'])
                ->where('user_id', $user->id)
                ->latest('tgl_pengajuan_kredit')
                ->first(),
            'pengajuanCount' => PengajuanKredit::query()->where('user_id', $user->id)->count(),
            'activeCredits' => Kredit::query()
                ->whereHas('pengajuanKredit', fn ($query) => $query->where('user_id', $user->id))
                ->whereIn('status_kredit', ['cicil', 'macet'])
                ->count(),
            'latestInstallment' => Angsuran::query()
                ->whereHas('kredit.pengajuanKredit', fn ($query) => $query->where('user_id', $user->id))
                ->latest('tgl_bayar')
                ->first(),
        ]);
    }

    public function profile(Request $request)
    {
        return view('user.profile', [
            'user' => $request->user()->loadMissing('profile'),
        ]);
    }

    public function pengajuanIndex(Request $request)
    {
        return view('user.pengajuan.index', [
            'pengajuanList' => PengajuanKredit::query()
                ->with(['motor', 'jenisCicilan'])
                ->where('user_id', $request->user()->id)
                ->latest('tgl_pengajuan_kredit')
                ->paginate(10),
        ]);
    }

    public function kreditIndex(Request $request)
    {
        return view('user.kredit.index', [
            'credits' => Kredit::query()
                ->with(['pengajuanKredit.motor', 'metodeBayar', 'pengiriman'])
                ->whereHas('pengajuanKredit', fn ($query) => $query->where('user_id', $request->user()->id))
                ->latest('tgl_mulai_kredit')
                ->paginate(10),
        ]);
    }
}
