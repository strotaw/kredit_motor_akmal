<?php

namespace App\Http\Controllers;

use App\Models\Angsuran;
use App\Models\Kredit;
use App\Models\Motor;
use App\Models\PengajuanKredit;
use App\Models\User;

class CeoPortalController extends Controller
{
    public function dashboard()
    {
        return view('ceo.dashboard', [
            'metrics' => [
                'users' => User::query()->where('role', 'user')->count(),
                'pengajuan' => PengajuanKredit::query()->count(),
                'approved' => PengajuanKredit::query()->where('status_pengajuan', 'diterima')->count(),
                'kredit_aktif' => Kredit::query()->where('status_kredit', 'cicil')->count(),
                'kredit_macet' => Kredit::query()->where('status_kredit', 'macet')->count(),
                'pendapatan' => Angsuran::query()->sum('total_bayar'),
            ],
            'topMotors' => Motor::query()
                ->with('jenisMotor')
                ->withCount('pengajuanKredits')
                ->orderByDesc('pengajuan_kredits_count')
                ->take(5)
                ->get(),
        ]);
    }

    public function usersIndex()
    {
        return view('ceo.users.index', [
            'users' => User::query()
                ->withCount('pengajuanKredits')
                ->where('role', 'user')
                ->latest()
                ->paginate(12),
        ]);
    }

    public function transaksiIndex()
    {
        return view('ceo.transaksi.index', [
            'transactions' => PengajuanKredit::query()
                ->with(['user', 'motor', 'kredit'])
                ->latest('tgl_pengajuan_kredit')
                ->paginate(12),
        ]);
    }
}
