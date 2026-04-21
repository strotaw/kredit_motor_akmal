<?php

namespace App\Http\Controllers;

use App\Models\Asuransi;
use App\Models\Angsuran;
use App\Models\JenisCicilan;
use App\Models\Kredit;
use App\Models\Motor;
use App\Models\PengajuanDokumen;
use App\Models\PengajuanKredit;
use App\Models\PengajuanStatusLog;
use App\Models\UserProfile;
use App\Services\CreditSimulationService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UserPortalController extends Controller
{
    public function dashboard(Request $request)
    {
        $user = $request->user();
        $currentCredit = Kredit::query()
            ->with([
                'angsurans' => fn (Builder $query) => $query->latest('angsuran_ke'),
                'pengajuanKredit.motor',
                'pengajuanKredit.jenisCicilan',
                'metodeBayar',
                'pengiriman',
            ])
            ->whereHas('pengajuanKredit', fn (Builder $query) => $query->where('user_id', $user->id))
            ->whereIn('status_kredit', ['cicil', 'macet', 'lunas'])
            ->latest('tgl_mulai_kredit')
            ->first();

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
            'paymentSummary' => $this->buildPaymentSummary($currentCredit),
            'currentCredit' => $currentCredit,
        ]);
    }

    public function profile(Request $request)
    {
        return view('user.profile', [
            'user' => $request->user()->loadMissing('profile'),
        ]);
    }

    public function profileUpdate(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'password' => ['nullable', 'confirmed', Password::defaults()],
            'no_telp' => ['nullable', 'string', 'max:20'],
            'nik' => ['nullable', 'string', 'max:25'],
            'tempat_lahir' => ['nullable', 'string', 'max:100'],
            'tanggal_lahir' => ['nullable', 'date'],
            'jenis_kelamin' => ['nullable', Rule::in(['laki-laki', 'perempuan'])],
            'pekerjaan' => ['nullable', 'string', 'max:100'],
            'nama_perusahaan' => ['nullable', 'string', 'max:150'],
            'lama_bekerja_bulan' => ['nullable', 'integer', 'min:0'],
            'penghasilan_bulanan' => ['nullable', 'numeric', 'min:0'],
            'status_pernikahan' => ['nullable', 'string', 'max:50'],
            'alamat_ktp' => ['nullable', 'string', 'max:255'],
            'kota_ktp' => ['nullable', 'string', 'max:100'],
            'provinsi_ktp' => ['nullable', 'string', 'max:100'],
            'kodepos_ktp' => ['nullable', 'string', 'max:10'],
            'alamat_domisili' => ['nullable', 'string', 'max:255'],
            'kota_domisili' => ['nullable', 'string', 'max:100'],
            'provinsi_domisili' => ['nullable', 'string', 'max:100'],
            'kodepos_domisili' => ['nullable', 'string', 'max:10'],
            'nama_kontak_darurat' => ['nullable', 'string', 'max:100'],
            'hubungan_kontak_darurat' => ['nullable', 'string', 'max:100'],
            'no_telp_kontak_darurat' => ['nullable', 'string', 'max:20'],
            'foto_profil' => ['nullable', 'file', 'mimes:jpg,jpeg,png', 'max:4096'],
        ]);

        DB::transaction(function () use ($user, $validated, $request): void {
            $user->forceFill([
                'name' => $validated['name'],
                'email' => $validated['email'],
            ]);

            if (! empty($validated['password'])) {
                $user->password = $validated['password'];
            }

            $user->save();

            $profileData = collect($validated)
                ->except(['name', 'email', 'password', 'password_confirmation'])
                ->all();

            if ($request->hasFile('foto_profil')) {
                $profileData['foto_profil'] = $request->file('foto_profil')->store('profil-user', 'public');
            }

            UserProfile::query()->updateOrCreate(
                ['user_id' => $user->id],
                $profileData
            );
        });

        return redirect()
            ->route('user.profile')
            ->with('status', 'Profil berhasil diperbarui.');
    }

    public function pengajuanCreate(Request $request, CreditSimulationService $simulationService)
    {
        $motors = Motor::query()->with('jenisMotor')->where('status_aktif', true)->orderBy('nama_motor')->get();
        $tenors = JenisCicilan::query()->orderBy('lama_cicilan')->get();
        $insurances = Asuransi::query()->orderBy('nama_asuransi')->get();
        $selectedMotor = $motors->firstWhere('id', (int) $request->input('motor')) ?? $motors->first();
        $selectedTenor = $tenors->firstWhere('id', (int) $request->input('tenor')) ?? $tenors->first();
        $selectedInsurance = $insurances->firstWhere('id', (int) $request->input('asuransi')) ?? $insurances->first();
        $downPayment = $selectedMotor
            ? (float) ($request->input('dp') ?: $simulationService->minimumDownPayment($selectedMotor))
            : 0;

        return view('user.pengajuan.create', [
            'user' => $request->user()->loadMissing('profile'),
            'motors' => $motors,
            'tenors' => $tenors,
            'insurances' => $insurances,
            'selectedMotor' => $selectedMotor,
            'selectedTenor' => $selectedTenor,
            'selectedInsurance' => $selectedInsurance,
            'downPayment' => $downPayment,
            'simulation' => $selectedMotor && $selectedTenor && $selectedInsurance
                ? $simulationService->calculate($selectedMotor, $selectedTenor, $selectedInsurance, $downPayment)
                : null,
        ]);
    }

    public function pengajuanStore(Request $request, CreditSimulationService $simulationService)
    {
        $validated = $request->validate([
            'id_motor' => ['required', 'exists:motor,id'],
            'dp' => ['required', 'numeric', 'min:0'],
            'id_jenis_cicilan' => ['required', 'exists:jenis_cicilan,id'],
            'id_asuransi' => ['required', 'exists:asuransi,id'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($request->user()->id)],
            'no_telp' => ['required', 'string', 'max:20'],
            'nik' => ['required', 'string', 'max:25'],
            'tempat_lahir' => ['nullable', 'string', 'max:100'],
            'tanggal_lahir' => ['nullable', 'date'],
            'jenis_kelamin' => ['nullable', Rule::in(['laki-laki', 'perempuan'])],
            'pekerjaan' => ['required', 'string', 'max:100'],
            'nama_perusahaan' => ['nullable', 'string', 'max:150'],
            'lama_bekerja_bulan' => ['nullable', 'integer', 'min:0'],
            'penghasilan_bulanan' => ['required', 'numeric', 'min:0'],
            'status_pernikahan' => ['nullable', 'string', 'max:50'],
            'alamat_ktp' => ['required', 'string', 'max:255'],
            'kota_ktp' => ['required', 'string', 'max:100'],
            'provinsi_ktp' => ['required', 'string', 'max:100'],
            'kodepos_ktp' => ['required', 'string', 'max:10'],
            'alamat_domisili' => ['required', 'string', 'max:255'],
            'kota_domisili' => ['required', 'string', 'max:100'],
            'provinsi_domisili' => ['required', 'string', 'max:100'],
            'kodepos_domisili' => ['required', 'string', 'max:10'],
            'nama_kontak_darurat' => ['nullable', 'string', 'max:100'],
            'hubungan_kontak_darurat' => ['nullable', 'string', 'max:100'],
            'no_telp_kontak_darurat' => ['nullable', 'string', 'max:20'],
            'url_ktp' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:4096'],
            'url_kk' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:4096'],
            'url_npwp' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:4096'],
            'url_slip_gaji' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:4096'],
            'url_foto' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:4096'],
            'agree_terms' => ['accepted'],
        ]);

        $motor = Motor::query()->findOrFail($validated['id_motor']);
        $jenisCicilan = JenisCicilan::query()->findOrFail($validated['id_jenis_cicilan']);
        $asuransi = Asuransi::query()->findOrFail($validated['id_asuransi']);
        $simulation = $simulationService->calculate($motor, $jenisCicilan, $asuransi, (float) $validated['dp']);

        if ((float) $validated['dp'] < $simulation['minimum_dp']) {
            return back()
                ->withErrors(['dp' => 'DP minimal untuk motor ini adalah Rp'.number_format($simulation['minimum_dp'], 0, ',', '.').'.'])
                ->withInput();
        }

        $user = $request->user();

        $pengajuan = DB::transaction(function () use ($request, $validated, $user, $simulation, $motor, $jenisCicilan, $asuransi) {
            $user->forceFill([
                'name' => $validated['name'],
                'email' => $validated['email'],
            ])->save();

            UserProfile::query()->updateOrCreate(
                ['user_id' => $user->id],
                collect($validated)
                    ->only([
                        'nik',
                        'no_telp',
                        'tempat_lahir',
                        'tanggal_lahir',
                        'jenis_kelamin',
                        'pekerjaan',
                        'nama_perusahaan',
                        'lama_bekerja_bulan',
                        'penghasilan_bulanan',
                        'status_pernikahan',
                        'alamat_ktp',
                        'kota_ktp',
                        'provinsi_ktp',
                        'kodepos_ktp',
                        'alamat_domisili',
                        'kota_domisili',
                        'provinsi_domisili',
                        'kodepos_domisili',
                        'nama_kontak_darurat',
                        'hubungan_kontak_darurat',
                        'no_telp_kontak_darurat',
                    ])
                    ->all()
            );

            $pengajuan = PengajuanKredit::query()->create([
                'kode_pengajuan' => 'PGJ-TMP-'.Str::upper(Str::random(8)),
                'tgl_pengajuan_kredit' => now()->toDateString(),
                'user_id' => $user->id,
                'id_motor' => $motor->id,
                'harga_cash' => $simulation['harga_cash'],
                'dp' => $simulation['dp'],
                'id_jenis_cicilan' => $jenisCicilan->id,
                'harga_kredit' => $simulation['harga_kredit'],
                'id_asuransi' => $asuransi->id,
                'biaya_asuransi_perbulan' => $simulation['biaya_asuransi_perbulan'],
                'cicilan_perbulan' => $simulation['cicilan_perbulan'],
                'status_pengajuan' => 'menunggu',
                'keterangan_status_pengajuan' => 'Pengajuan diterima dan menunggu verifikasi admin.',
                'url_kk' => $this->storeUserUpload($request, 'url_kk', 'kk'),
                'url_ktp' => $this->storeUserUpload($request, 'url_ktp', 'ktp'),
                'url_npwp' => $request->hasFile('url_npwp') ? $this->storeUserUpload($request, 'url_npwp', 'npwp') : null,
                'url_slip_gaji' => $this->storeUserUpload($request, 'url_slip_gaji', 'slip-gaji'),
                'url_foto' => $this->storeUserUpload($request, 'url_foto', 'foto-diri'),
            ]);

            $pengajuan->forceFill([
                'kode_pengajuan' => sprintf('PGJ-%06d', $pengajuan->id),
            ])->save();

            foreach ([
                'ktp' => $pengajuan->url_ktp,
                'kk' => $pengajuan->url_kk,
                'npwp' => $pengajuan->url_npwp,
                'slip_gaji' => $pengajuan->url_slip_gaji,
                'foto_diri' => $pengajuan->url_foto,
            ] as $jenis => $path) {
                if (! $path) {
                    continue;
                }

                PengajuanDokumen::query()->create([
                    'pengajuan_kredit_id' => $pengajuan->id,
                    'jenis_dokumen' => $jenis,
                    'file_path' => $path,
                    'status_verifikasi' => 'menunggu',
                ]);
            }

            PengajuanStatusLog::query()->create([
                'pengajuan_kredit_id' => $pengajuan->id,
                'status_lama' => null,
                'status_baru' => 'menunggu',
                'catatan' => 'Pengajuan dibuat oleh user.',
                'changed_by' => $user->id,
                'created_at' => now(),
            ]);

            return $pengajuan;
        });

        return redirect()
            ->route('user.pengajuan.show', $pengajuan)
            ->with('status', 'Pengajuan kredit berhasil dikirim.');
    }

    public function pengajuanShow(Request $request, PengajuanKredit $pengajuan)
    {
        abort_unless($pengajuan->user_id === $request->user()->id, 403);

        return view('user.pengajuan.show', [
            'pengajuan' => $pengajuan->load([
                'motor.jenisMotor',
                'jenisCicilan',
                'asuransi',
                'documents',
                'statusLogs.changedBy',
                'kredit.pengiriman',
            ]),
        ]);
    }

    public function pengajuanCancel(Request $request, PengajuanKredit $pengajuan)
    {
        abort_unless($pengajuan->user_id === $request->user()->id, 403);

        if (! in_array($pengajuan->status_pengajuan, ['menunggu', 'diproses'], true)) {
            return back()->withErrors(['pengajuan' => 'Pengajuan ini tidak bisa dibatalkan lagi.']);
        }

        $statusLama = $pengajuan->status_pengajuan;
        $pengajuan->forceFill([
            'status_pengajuan' => 'dibatalkan_pembeli',
            'keterangan_status_pengajuan' => 'Dibatalkan oleh user.',
        ])->save();

        PengajuanStatusLog::query()->create([
            'pengajuan_kredit_id' => $pengajuan->id,
            'status_lama' => $statusLama,
            'status_baru' => 'dibatalkan_pembeli',
            'catatan' => 'Pengajuan dibatalkan oleh user.',
            'changed_by' => $request->user()->id,
            'created_at' => now(),
        ]);

        return redirect()
            ->route('user.pengajuan.show', $pengajuan)
            ->with('status', 'Pengajuan berhasil dibatalkan.');
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
                ->withCount('angsurans')
                ->with(['pengajuanKredit.motor', 'pengajuanKredit.jenisCicilan', 'metodeBayar', 'pengiriman'])
                ->whereHas('pengajuanKredit', fn ($query) => $query->where('user_id', $request->user()->id))
                ->latest('tgl_mulai_kredit')
                ->paginate(10),
        ]);
    }

    public function kreditShow(Request $request, Kredit $kredit)
    {
        $kredit->load([
            'angsurans' => fn (Builder $query) => $query->latest('angsuran_ke'),
            'pengajuanKredit.motor.jenisMotor',
            'pengajuanKredit.jenisCicilan',
            'metodeBayar',
            'pengiriman',
        ]);

        abort_unless($kredit->pengajuanKredit?->user_id === $request->user()->id, 403);

        return view('user.kredit.show', [
            'credit' => $kredit,
            'paymentSummary' => $this->buildPaymentSummary($kredit),
        ]);
    }

    public function paymentsIndex(Request $request)
    {
        $credits = Kredit::query()
            ->with([
                'angsurans' => fn (Builder $query) => $query->latest('angsuran_ke'),
                'pengajuanKredit.motor',
                'pengajuanKredit.jenisCicilan',
                'metodeBayar',
            ])
            ->whereHas('pengajuanKredit', fn (Builder $query) => $query->where('user_id', $request->user()->id))
            ->whereIn('status_kredit', ['cicil', 'macet', 'lunas'])
            ->latest('tgl_mulai_kredit')
            ->get();

        return view('user.payments.index', [
            'paymentCards' => $credits->map(fn (Kredit $credit) => [
                'credit' => $credit,
                'summary' => $this->buildPaymentSummary($credit),
            ]),
        ]);
    }

    private function storeUserUpload(Request $request, string $field, string $folder): string
    {
        return $request->file($field)->store('pengajuan-dokumen/'.$folder, 'public');
    }

    private function buildPaymentSummary(?Kredit $credit): ?array
    {
        if (! $credit) {
            return null;
        }

        $totalInstallments = max(1, (int) ($credit->pengajuanKredit?->jenisCicilan?->lama_cicilan ?? 1));
        $paidInstallments = $credit->angsurans->count();
        $isPaidOff = $credit->status_kredit === 'lunas' || $paidInstallments >= $totalInstallments;
        $nextInstallment = $isPaidOff ? $totalInstallments : min($paidInstallments + 1, $totalInstallments);
        $dueDate = $isPaidOff
            ? $credit->tgl_selesai_kredit
            : $credit->tgl_mulai_kredit?->copy()->addMonths($nextInstallment);
        $amount = (float) ($credit->pengajuanKredit?->cicilan_perbulan ?: $credit->angsurans->first()?->total_bayar ?: 0);

        return [
            'method' => config('services.midtrans.qris_label', 'QRIS Dummy Midtrans Sandbox'),
            'merchant' => config('branding.name'),
            'qr_image' => asset('images/payment/qris-midtrans-dummy.svg'),
            'order_id' => sprintf('MID-SBX-%06d-%02d', $credit->id, $nextInstallment),
            'reference' => sprintf('QRIS-%06d-%02d', $credit->id, $nextInstallment),
            'amount' => $amount,
            'status' => $isPaidOff ? 'lunas' : $credit->status_kredit,
            'status_label' => $isPaidOff ? 'LUNAS' : str_replace('_', ' ', strtoupper($credit->status_kredit)),
            'due_date' => $dueDate,
            'next_installment' => $nextInstallment,
            'paid_installments' => $paidInstallments,
            'total_installments' => $totalInstallments,
            'instructions' => [
                'Buka aplikasi e-wallet atau mobile banking yang mendukung QRIS.',
                'Scan kode QR dummy Midtrans sandbox dari dashboard pembayaran.',
                'Simpan referensi pembayaran untuk pencocokan data admin.',
            ],
            'is_dummy' => true,
        ];
    }
}
