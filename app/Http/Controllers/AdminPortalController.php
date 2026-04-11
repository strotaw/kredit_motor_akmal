<?php

namespace App\Http\Controllers;

use App\Models\Kredit;
use App\Models\PengajuanKredit;
use App\Models\Pengiriman;
use Illuminate\Http\Request;

class AdminPortalController extends Controller
{
    public function dashboard()
    {
        return view('admin.dashboard', [
            'metrics' => [
                'menunggu' => PengajuanKredit::query()->where('status_pengajuan', 'menunggu')->count(),
                'diproses' => PengajuanKredit::query()->where('status_pengajuan', 'diproses')->count(),
                'bermasalah' => PengajuanKredit::query()->where('status_pengajuan', 'bermasalah')->count(),
                'kredit_aktif' => Kredit::query()->where('status_kredit', 'cicil')->count(),
                'kredit_macet' => Kredit::query()->where('status_kredit', 'macet')->count(),
                'pengiriman' => Pengiriman::query()->where('status_kirim', 'dikirim')->count(),
            ],
            'latestPengajuan' => PengajuanKredit::query()
                ->with(['user', 'motor'])
                ->latest('tgl_pengajuan_kredit')
                ->take(8)
                ->get(),
        ]);
    }

    public function pengajuanIndex()
    {
        return view('admin.pengajuan.index', [
            'pengajuanList' => PengajuanKredit::query()
                ->with(['user', 'motor', 'jenisCicilan'])
                ->latest('tgl_pengajuan_kredit')
                ->paginate(12),
        ]);
    }

    public function kreditIndex()
    {
        return view('admin.kredit.index', [
            'credits' => Kredit::query()
                ->with(['pengajuanKredit.user', 'pengajuanKredit.motor', 'metodeBayar'])
                ->latest('tgl_mulai_kredit')
                ->paginate(12),
        ]);
    }

    public function pengirimanIndex()
    {
        return view('admin.pengiriman.index', [
            'deliveries' => Pengiriman::query()
                ->with(['kredit.pengajuanKredit.user', 'kredit.pengajuanKredit.motor'])
                ->latest('tgl_kirim')
                ->paginate(12),
        ]);
    }
}
