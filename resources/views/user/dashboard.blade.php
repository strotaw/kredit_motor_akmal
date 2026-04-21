@extends('layouts.user-portal')

@section('title', 'Dashboard User')
@section('eyebrow', 'Portal kredit')
@section('page-description', 'Ringkasan pengajuan, kontrak aktif, dan akses cepat ke pembayaran QRIS dummy.')

@section('page-actions')
    <a href="{{ route('user.payments.index') }}" class="btn btn-primary">Bayar Cicilan</a>
    <a href="{{ route('user.pengajuan.create') }}" class="btn btn-secondary">Ajukan Kredit Baru</a>
@endsection

@section('content')
    <section class="km-stack">
        <div class="km-hero">
            <span class="km-chip">Dashboard User</span>
            <h1 style="font-size: 2.5rem; margin-top: 1rem;">Pantau pengajuan, kredit aktif, dan langkah selanjutnya.</h1>
            <p class="km-subtle" style="margin-top: 0.6rem;">Semua ringkasan utama akun, pembayaran, dan progres pengiriman tersedia dari halaman ini.</p>
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

        @if ($paymentSummary)
            <div class="grid-2">
                <article class="data-card">
                    <div class="card-head">
                        <div>
                            <div class="eyebrow">Pembayaran berikutnya</div>
                            <h3 style="margin: 10px 0 6px;">{{ $paymentSummary['method'] }}</h3>
                            <p class="muted-text" style="margin: 0;">{{ $currentCredit?->nomor_kontrak ?: 'Kredit aktif' }} | {{ $currentCredit?->pengajuanKredit?->motor?->nama_motor }}</p>
                        </div>
                        <x-status-badge :value="$paymentSummary['status']" :label="$paymentSummary['status_label']" />
                    </div>

                    <div class="summary-list" style="margin-top: 16px;">
                        <div class="summary-list__item">
                            <span>Nominal cicilan</span>
                            <strong>Rp{{ number_format($paymentSummary['amount'], 0, ',', '.') }}</strong>
                        </div>
                        <div class="summary-list__item">
                            <span>Jatuh tempo</span>
                            <strong>{{ optional($paymentSummary['due_date'])->format('d M Y') ?: '-' }}</strong>
                        </div>
                        <div class="summary-list__item">
                            <span>Order sandbox</span>
                            <strong>{{ $paymentSummary['order_id'] }}</strong>
                        </div>
                    </div>
                </article>

                <article class="qr-card">
                    <div class="qris-preview">
                        <div class="qris-preview__image">
                            <img src="{{ $paymentSummary['qr_image'] }}" alt="QRIS dummy Midtrans sandbox">
                        </div>
                        <div class="card-stack">
                            <div>
                                <div class="eyebrow">Pembayaran</div>
                                <h3 style="margin: 10px 0 8px;">QRIS Midtrans Sandbox</h3>
                                <p class="muted-text" style="margin: 0;">Buka halaman pembayaran untuk melihat kode QR, referensi transaksi, dan nominal angsuran berikutnya.</p>
                            </div>
                            <div class="stack-actions">
                                <a href="{{ route('user.payments.index') }}" class="quick-link">Buka Halaman Pembayaran</a>
                                @if ($currentCredit)
                                    <a href="{{ route('user.kredit.show', $currentCredit) }}" class="quick-link">Lihat Detail Kredit</a>
                                @else
                                    <a href="{{ route('user.kredit.index') }}" class="quick-link">Lihat Kontrak</a>
                                @endif
                            </div>
                        </div>
                    </div>
                </article>
            </div>
        @endif

        <div class="km-grid km-grid-2">
            <div class="km-card">
                <h3>Status pengajuan terakhir</h3>
                @if ($latestPengajuan)
                    <div class="km-stack" style="margin-top: 1rem;">
                        <div>
                            <strong>{{ $latestPengajuan->kode_pengajuan ?: 'Pengajuan #'.$latestPengajuan->id }}</strong>
                            <p class="km-subtle">{{ $latestPengajuan->motor?->nama_motor }} | {{ $latestPengajuan->jenisCicilan?->lama_cicilan }} bulan</p>
                        </div>
                        <div>
                            <span class="km-status status-{{ $latestPengajuan->status_pengajuan }}">{{ str_replace('_', ' ', strtoupper($latestPengajuan->status_pengajuan)) }}</span>
                        </div>
                        <p class="km-subtle">{{ $latestPengajuan->keterangan_status_pengajuan ?: 'Belum ada catatan tambahan dari admin.' }}</p>
                        <a href="{{ route('user.pengajuan.show', $latestPengajuan) }}" class="km-button-secondary">Lihat Detail Pengajuan</a>
                    </div>
                @else
                    <div class="km-empty" style="margin-top: 1rem;">Belum ada pengajuan. Anda bisa mulai dari katalog atau form pengajuan baru.</div>
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

        <div class="km-card">
            <h3>Status pengiriman</h3>
            @if ($currentCredit?->pengiriman)
                <div class="km-grid km-grid-3" style="margin-top: 1rem;">
                    <div>
                        <strong>No invoice</strong>
                        <p class="km-subtle">{{ $currentCredit->pengiriman->no_invoice }}</p>
                    </div>
                    <div>
                        <strong>Status</strong>
                        <p class="km-subtle">{{ strtoupper($currentCredit->pengiriman->status_kirim) }}</p>
                    </div>
                    <div>
                        <strong>Estimasi tiba</strong>
                        <p class="km-subtle">{{ optional($currentCredit->pengiriman->tgl_tiba)->format('d M Y H:i') ?: '-' }}</p>
                    </div>
                </div>
            @else
                <div class="km-empty" style="margin-top: 1rem;">Status pengiriman akan tampil setelah kontrak aktif dan unit dijadwalkan dikirim.</div>
            @endif
        </div>
    </section>
@endsection
