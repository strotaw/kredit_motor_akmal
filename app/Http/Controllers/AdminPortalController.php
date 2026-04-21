<?php

namespace App\Http\Controllers;

use App\Models\Angsuran;
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

    public function angsuranIndex(Request $request)
    {
        return view('admin.angsuran.index', [
            'stats' => [
                'menunggu' => Angsuran::query()->where('status_verifikasi', 'menunggu')->count(),
                'valid' => Angsuran::query()->where('status_verifikasi', 'valid')->count(),
                'ditolak' => Angsuran::query()->where('status_verifikasi', 'ditolak')->count(),
            ],
            'credits' => Kredit::query()
                ->with(['pengajuanKredit.user', 'pengajuanKredit.motor', 'metodeBayar'])
                ->whereIn('status_kredit', ['cicil', 'macet'])
                ->orderBy('nomor_kontrak')
                ->get(),
            'installments' => Angsuran::query()
                ->with(['kredit.pengajuanKredit.user', 'kredit.pengajuanKredit.motor'])
                ->when($request->filled('status'), fn ($query) => $query->where('status_verifikasi', $request->string('status')->toString()))
                ->latest('tgl_bayar')
                ->paginate(12)
                ->withQueryString(),
        ]);
    }

    public function angsuranStore(Request $request)
    {
        $validated = $request->validate([
            'id_kredit' => ['required', 'exists:kredit,id'],
            'tgl_bayar' => ['required', 'date'],
            'angsuran_ke' => ['required', 'integer', 'min:1'],
            'total_bayar' => ['required', 'numeric', 'min:0'],
            'status_verifikasi' => ['required', 'in:menunggu,valid,ditolak'],
            'status_kredit' => ['nullable', 'in:cicil,lunas,macet'],
            'keterangan' => ['nullable', 'string', 'max:500'],
        ]);

        $credit = Kredit::query()->with('metodeBayar', 'pengajuanKredit')->findOrFail($validated['id_kredit']);

        $angsuran = Angsuran::query()->create([
            'id_kredit' => $credit->id,
            'tgl_bayar' => $validated['tgl_bayar'],
            'angsuran_ke' => $validated['angsuran_ke'],
            'total_bayar' => $validated['total_bayar'],
            'metode_bayar_snapshot' => $credit->metodeBayar?->metode_bayar ?: 'Manual admin',
            'status_verifikasi' => $validated['status_verifikasi'],
            'verified_by' => $validated['status_verifikasi'] === 'valid' ? $request->user()->id : null,
            'verified_at' => $validated['status_verifikasi'] === 'valid' ? now() : null,
            'keterangan' => $validated['keterangan'] ?? null,
        ]);

        if (! empty($validated['status_kredit'])) {
            $credit->forceFill(['status_kredit' => $validated['status_kredit']])->save();
        }

        $this->syncCreditBalance($credit->fresh(['pengajuanKredit']));

        return redirect()
            ->route('admin.angsuran.index')
            ->with('status', 'Data angsuran berhasil ditambahkan.');
    }

    public function angsuranVerify(Request $request, Angsuran $angsuran)
    {
        $validated = $request->validate([
            'status_verifikasi' => ['required', 'in:menunggu,valid,ditolak'],
            'status_kredit' => ['nullable', 'in:cicil,lunas,macet'],
            'keterangan' => ['nullable', 'string', 'max:500'],
        ]);

        $angsuran->forceFill([
            'status_verifikasi' => $validated['status_verifikasi'],
            'verified_by' => $validated['status_verifikasi'] === 'valid' ? $request->user()->id : null,
            'verified_at' => $validated['status_verifikasi'] === 'valid' ? now() : null,
            'keterangan' => $validated['keterangan'] ?? $angsuran->keterangan,
        ])->save();

        $credit = $angsuran->kredit()->with('pengajuanKredit')->firstOrFail();

        if (! empty($validated['status_kredit'])) {
            $credit->forceFill(['status_kredit' => $validated['status_kredit']])->save();
        }

        $this->syncCreditBalance($credit);

        return redirect()
            ->route('admin.angsuran.index')
            ->with('status', 'Status angsuran berhasil diperbarui.');
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

    private function syncCreditBalance(Kredit $credit): void
    {
        $credit->loadMissing('pengajuanKredit');

        $totalValidPayments = Angsuran::query()
            ->where('id_kredit', $credit->id)
            ->where('status_verifikasi', 'valid')
            ->sum('total_bayar');

        $sisaKredit = max(((float) $credit->pengajuanKredit?->harga_kredit) - $totalValidPayments, 0);
        $statusKredit = $sisaKredit <= 0 ? 'lunas' : $credit->status_kredit;

        $credit->forceFill([
            'sisa_kredit' => $sisaKredit,
            'status_kredit' => $statusKredit,
        ])->save();
    }
}
