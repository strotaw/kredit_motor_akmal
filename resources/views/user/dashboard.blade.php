@extends('layouts.app')

@section('content')
    <section class="km-stack">
        <div class="km-hero">
            <span class="km-chip">Dashboard User</span>
            <h1 style="font-size: 2.5rem; margin-top: 1rem;">Pantau pengajuan, kredit aktif, dan langkah selanjutnya.</h1>
            <p class="km-subtle" style="margin-top: 0.6rem;">Halaman ini nanti jadi pusat status pengajuan, timeline approval, dan akses cepat ke form kredit.</p>
        </div>

        <div class="km-grid km-grid-3">
            <div class="km-card">
                <p class="km-subtle">Total pengajuan</p>
                <div class="km-card-value">{{ number_format($pengajuanCount) }}</div>
            </div>
            <div class="km-card">
                <p class="km-subtle">Kredit aktif</p>
                <div class="km-card-value">{{ number_format($activeCredits) }}</div>
            </div>
            <div class="km-card">
                <p class="km-subtle">Angsuran terakhir</p>
                <div class="km-card-value" style="font-size: 1.2rem;">
                    {{ $latestInstallment ? 'Rp'.number_format((float) $latestInstallment->total_bayar, 0, ',', '.') : 'Belum ada' }}
                </div>
            </div>
        </div>

        <div class="km-grid km-grid-2">
            <div class="km-card">
                <h3>Status pengajuan terakhir</h3>
                @if ($latestPengajuan)
                    <div class="km-stack" style="margin-top: 1rem;">
                        <div>
                            <strong>{{ $latestPengajuan->kode_pengajuan ?: 'Pengajuan #'.$latestPengajuan->id }}</strong>
                            <p class="km-subtle">{{ $latestPengajuan->motor?->nama_motor }} • {{ $latestPengajuan->jenisCicilan?->lama_cicilan }} bulan</p>
                        </div>
                        <div>
                            <span class="km-status status-{{ $latestPengajuan->status_pengajuan }}">{{ str_replace('_', ' ', strtoupper($latestPengajuan->status_pengajuan)) }}</span>
                        </div>
                        <p class="km-subtle">{{ $latestPengajuan->keterangan_status_pengajuan ?: 'Belum ada catatan tambahan dari admin.' }}</p>
                    </div>
                @else
                    <div class="km-empty" style="margin-top: 1rem;">Belum ada pengajuan. Tahap berikutnya adalah membuat wizard pengajuan user.</div>
                @endif
            </div>

            <div class="km-card">
                <h3>Aksi cepat</h3>
                <div class="km-stack" style="margin-top: 1rem;">
                    <a href="{{ route('motors.index') }}" class="km-button-secondary">Lihat katalog & simulasi</a>
                    <a href="{{ route('user.pengajuan.index') }}" class="km-button-secondary">Buka daftar pengajuan</a>
                    <a href="{{ route('user.kredit.index') }}" class="km-button-secondary">Lihat kredit aktif</a>
                    <a href="{{ route('user.profile') }}" class="km-button">Lengkapi profil</a>
                </div>
            </div>
        </div>
    </section>
@endsection
