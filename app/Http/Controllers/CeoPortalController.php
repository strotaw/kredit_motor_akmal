<?php

namespace App\Http\Controllers;

use App\Models\Angsuran;
use App\Models\Kredit;
use App\Models\Motor;
use App\Models\PengajuanKredit;
use App\Models\User;
use Illuminate\Http\Request;

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

    public function laporanIndex(Request $request)
    {
        $from = $request->date('from', now()->startOfMonth())->toDateString();
        $to = $request->date('to', now())->toDateString();

        if ($request->filled('export')) {
            return $this->exportReportCsv($request->string('export')->toString(), $from, $to);
        }

        $pengajuanQuery = PengajuanKredit::query()->whereBetween('tgl_pengajuan_kredit', [$from, $to]);
        $angsuranQuery = Angsuran::query()->whereBetween('tgl_bayar', [$from, $to]);

        return view('ceo.reports.index', [
            'from' => $from,
            'to' => $to,
            'reportMetrics' => [
                'total_pengajuan' => (clone $pengajuanQuery)->count(),
                'pengajuan_diterima' => (clone $pengajuanQuery)->where('status_pengajuan', 'diterima')->count(),
                'kredit_aktif' => Kredit::query()->where('status_kredit', 'cicil')->count(),
                'tunggakan' => Kredit::query()->where('status_kredit', 'macet')->count(),
                'pendapatan' => (clone $angsuranQuery)->where('status_verifikasi', 'valid')->sum('total_bayar'),
            ],
            'statusSummary' => (clone $pengajuanQuery)
                ->selectRaw('status_pengajuan, COUNT(*) as total')
                ->groupBy('status_pengajuan')
                ->orderByDesc('total')
                ->get(),
            'creditSummary' => Kredit::query()
                ->selectRaw('status_kredit, COUNT(*) as total')
                ->groupBy('status_kredit')
                ->orderByDesc('total')
                ->get(),
            'motorSummary' => Motor::query()
                ->withCount(['pengajuanKredits' => fn ($query) => $query->whereBetween('tgl_pengajuan_kredit', [$from, $to])])
                ->orderByDesc('pengajuan_kredits_count')
                ->take(8)
                ->get(),
            'installmentSummary' => (clone $angsuranQuery)
                ->selectRaw('status_verifikasi, COUNT(*) as total, SUM(total_bayar) as nominal')
                ->groupBy('status_verifikasi')
                ->orderByDesc('total')
                ->get(),
        ]);
    }

    private function exportReportCsv(string $type, string $from, string $to)
    {
        $fileName = sprintf('laporan-%s-%s-sampai-%s.csv', $type, $from, $to);

        return response()->streamDownload(function () use ($type, $from, $to): void {
            $output = fopen('php://output', 'w');

            match ($type) {
                'pengajuan' => $this->writePengajuanCsv($output, $from, $to),
                'kredit' => $this->writeKreditCsv($output),
                default => $this->writeAngsuranCsv($output, $from, $to),
            };

            fclose($output);
        }, $fileName, [
            'Content-Type' => 'text/csv',
        ]);
    }

    private function writePengajuanCsv($output, string $from, string $to): void
    {
        fputcsv($output, ['Kode Pengajuan', 'Tanggal', 'User', 'Motor', 'Status', 'Nominal Kredit']);

        PengajuanKredit::query()
            ->with(['user', 'motor'])
            ->whereBetween('tgl_pengajuan_kredit', [$from, $to])
            ->orderByDesc('tgl_pengajuan_kredit')
            ->each(function (PengajuanKredit $pengajuan) use ($output): void {
                fputcsv($output, [
                    $pengajuan->kode_pengajuan,
                    optional($pengajuan->tgl_pengajuan_kredit)->format('Y-m-d'),
                    $pengajuan->user?->name,
                    $pengajuan->motor?->nama_motor,
                    $pengajuan->status_pengajuan,
                    (float) $pengajuan->harga_kredit,
                ]);
            });
    }

    private function writeKreditCsv($output): void
    {
        fputcsv($output, ['Nomor Kontrak', 'User', 'Motor', 'Status Kredit', 'Sisa Kredit']);

        Kredit::query()
            ->with(['pengajuanKredit.user', 'pengajuanKredit.motor'])
            ->orderByDesc('tgl_mulai_kredit')
            ->each(function (Kredit $credit) use ($output): void {
                fputcsv($output, [
                    $credit->nomor_kontrak,
                    $credit->pengajuanKredit?->user?->name,
                    $credit->pengajuanKredit?->motor?->nama_motor,
                    $credit->status_kredit,
                    (float) $credit->sisa_kredit,
                ]);
            });
    }

    private function writeAngsuranCsv($output, string $from, string $to): void
    {
        fputcsv($output, ['Tanggal Bayar', 'Kontrak', 'Angsuran Ke', 'Total Bayar', 'Status Verifikasi']);

        Angsuran::query()
            ->with('kredit')
            ->whereBetween('tgl_bayar', [$from, $to])
            ->orderByDesc('tgl_bayar')
            ->each(function (Angsuran $angsuran) use ($output): void {
                fputcsv($output, [
                    optional($angsuran->tgl_bayar)->format('Y-m-d'),
                    $angsuran->kredit?->nomor_kontrak,
                    $angsuran->angsuran_ke,
                    (float) $angsuran->total_bayar,
                    $angsuran->status_verifikasi,
                ]);
            });
    }
}
