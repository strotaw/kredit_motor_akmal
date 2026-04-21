@extends('layouts.user-portal')

@section('title', 'Pembayaran Cicilan')
@section('eyebrow', 'QRIS sandbox')
@section('page-description', 'Daftar pembayaran angsuran dengan QRIS Midtrans Sandbox untuk kebutuhan demo dan pengujian alur.')

@section('page-actions')
    <a href="{{ route('user.kredit.index') }}" class="btn btn-secondary">Lihat Kontrak</a>
    <a href="{{ route('user.pengajuan.create') }}" class="btn btn-primary">Ajukan Kredit Baru</a>
@endsection

@section('content')
    <section class="content-stack">
        <div class="panel">
            <div class="badge-row">
                <span class="pill pill-soft">Midtrans Sandbox</span>
                <span class="pill pill-soft">QRIS Dummy</span>
            </div>
            <h2 style="margin: 14px 0 8px;">Pembayaran cicilan per kontrak</h2>
            <p class="muted-text" style="margin: 0;">Setiap kartu menampilkan nominal angsuran, jatuh tempo, referensi transaksi, dan kode QRIS untuk alur pembayaran sandbox.</p>
        </div>

        @forelse ($paymentCards as $item)
            @php
                $credit = $item['credit'];
                $summary = $item['summary'];
            @endphp
            <article class="grid-2">
                <div class="data-card">
                    <div class="card-head">
                        <div>
                            <div class="eyebrow">Kontrak</div>
                            <h3 style="margin: 10px 0 6px;">{{ $credit->nomor_kontrak ?: 'KRD-'.$credit->id }}</h3>
                            <p class="muted-text" style="margin: 0;">{{ $credit->pengajuanKredit?->motor?->nama_motor ?: 'Motor belum terhubung' }}</p>
                        </div>
                        <x-status-badge :value="$summary['status']" :label="$summary['status_label']" />
                    </div>

                    <div class="split-list" style="margin-top: 18px;">
                        <div class="split-list__item">
                            <span>Angsuran berikutnya</span>
                            <strong>#{{ $summary['next_installment'] }} / {{ $summary['total_installments'] }}</strong>
                        </div>
                        <div class="split-list__item">
                            <span>Nominal bayar</span>
                            <strong>Rp{{ number_format($summary['amount'], 0, ',', '.') }}</strong>
                        </div>
                        <div class="split-list__item">
                            <span>Jatuh tempo</span>
                            <strong>{{ optional($summary['due_date'])->format('d M Y') ?: '-' }}</strong>
                        </div>
                        <div class="split-list__item">
                            <span>Metode</span>
                            <strong>{{ $summary['method'] }}</strong>
                        </div>
                        <div class="split-list__item">
                            <span>Order sandbox</span>
                            <strong>{{ $summary['order_id'] }}</strong>
                        </div>
                        <div class="split-list__item">
                            <span>Ref pembayaran</span>
                            <strong>{{ $summary['reference'] }}</strong>
                        </div>
                    </div>
                </div>

                <div class="qr-card">
                    <div class="qris-preview">
                        <div class="qris-preview__image">
                            <img src="{{ $summary['qr_image'] }}" alt="QRIS dummy Midtrans sandbox untuk {{ $credit->nomor_kontrak ?: 'kontrak '.$credit->id }}">
                        </div>

                        <div class="card-stack">
                            <div>
                                <div class="eyebrow">Instruksi bayar</div>
                                <h3 style="margin: 10px 0 8px;">Scan QRIS dummy via Midtrans sandbox</h3>
                                <ol class="qris-preview__steps" style="margin: 0; padding-left: 18px;">
                                    @foreach ($summary['instructions'] as $instruction)
                                        <li>{{ $instruction }}</li>
                                    @endforeach
                                </ol>
                            </div>

                            <div class="summary-list">
                                <div class="summary-list__item">
                                    <span>Merchant</span>
                                    <strong>{{ $summary['merchant'] }}</strong>
                                </div>
                                <div class="summary-list__item">
                                    <span>Mode pembayaran</span>
                                    <strong>{{ $summary['is_dummy'] ? 'Sandbox' : 'Live' }}</strong>
                                </div>
                            </div>

                            <div class="stack-actions">
                                <a href="{{ route('user.dashboard') }}" class="quick-link">Kembali ke Dashboard</a>
                                <a href="{{ route('user.kredit.show', $credit) }}" class="quick-link">Lihat Detail Kredit</a>
                            </div>
                        </div>
                    </div>
                </div>
            </article>
        @empty
            <div class="empty-state">
                Belum ada kontrak kredit yang bisa dibayar. Kartu QRIS akan muncul setelah kredit aktif tersedia.
            </div>
        @endforelse
    </section>
@endsection
