@extends('layouts.user-portal')

@section('title', $credit->nomor_kontrak ?: 'Detail Kredit')
@section('eyebrow', 'Detail kontrak')
@section('page-description', 'Lihat ringkasan kontrak, histori angsuran, pembayaran, dan status pengiriman.')

@section('page-actions')
    <a href="{{ route('user.payments.index') }}" class="btn btn-primary">Bayar Cicilan</a>
@endsection

@section('content')
    <section class="content-stack">
        <div class="grid-2">
            <article class="profile-card">
                <div class="card-head">
                    <div>
                        <div class="eyebrow">Kontrak kredit</div>
                        <h2 style="margin: 10px 0 6px;">{{ $credit->nomor_kontrak }}</h2>
                        <p class="muted-text" style="margin: 0;">{{ $credit->pengajuanKredit?->motor?->nama_motor }}</p>
                    </div>
                    <x-status-badge :value="$credit->status_kredit" />
                </div>

                <div class="summary-list" style="margin-top: 18px;">
                    <div class="summary-list__item">
                        <span>Tanggal mulai</span>
                        <strong>{{ optional($credit->tgl_mulai_kredit)->format('d M Y') ?: '-' }}</strong>
                    </div>
                    <div class="summary-list__item">
                        <span>Tanggal selesai</span>
                        <strong>{{ optional($credit->tgl_selesai_kredit)->format('d M Y') ?: '-' }}</strong>
                    </div>
                    <div class="summary-list__item">
                        <span>Sisa kredit</span>
                        <strong>Rp{{ number_format((float) $credit->sisa_kredit, 0, ',', '.') }}</strong>
                    </div>
                    <div class="summary-list__item">
                        <span>Metode bayar</span>
                        <strong>{{ $credit->metodeBayar?->metode_bayar ?: '-' }}</strong>
                    </div>
                </div>
            </article>

            @if ($paymentSummary)
                <article class="qr-card">
                    <div class="qris-preview">
                        <div class="qris-preview__image">
                            <img src="{{ $paymentSummary['qr_image'] }}" alt="QRIS dummy Midtrans">
                        </div>
                        <div class="card-stack">
                            <div>
                                <div class="eyebrow">Pembayaran berikutnya</div>
                                <h3 style="margin: 10px 0 8px;">{{ $paymentSummary['method'] }}</h3>
                            </div>
                            <div class="summary-list">
                                <div class="summary-list__item">
                                    <span>Nominal</span>
                                    <strong>Rp{{ number_format($paymentSummary['amount'], 0, ',', '.') }}</strong>
                                </div>
                                <div class="summary-list__item">
                                    <span>Jatuh tempo</span>
                                    <strong>{{ optional($paymentSummary['due_date'])->format('d M Y') ?: '-' }}</strong>
                                </div>
                                <div class="summary-list__item">
                                    <span>Referensi</span>
                                    <strong>{{ $paymentSummary['reference'] }}</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                </article>
            @endif
        </div>

        <div class="grid-2">
            <article class="profile-card">
                <div class="eyebrow">Histori angsuran</div>
                <div class="km-table-wrap" style="margin-top: 16px;">
                    <table class="km-table">
                        <thead>
                            <tr>
                                <th>Ke</th>
                                <th>Tanggal</th>
                                <th>Total</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($credit->angsurans as $angsuran)
                                <tr>
                                    <td>{{ $angsuran->angsuran_ke }}</td>
                                    <td>{{ optional($angsuran->tgl_bayar)->format('d M Y') }}</td>
                                    <td>Rp{{ number_format((float) $angsuran->total_bayar, 0, ',', '.') }}</td>
                                    <td><x-status-badge :value="$angsuran->status_verifikasi" /></td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4"><div class="empty-state">Belum ada histori angsuran.</div></td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </article>

            <article class="profile-card">
                <div class="eyebrow">Pengiriman unit</div>
                @if ($credit->pengiriman)
                    <div class="summary-list" style="margin-top: 16px;">
                        <div class="summary-list__item">
                            <span>No invoice</span>
                            <strong>{{ $credit->pengiriman->no_invoice }}</strong>
                        </div>
                        <div class="summary-list__item">
                            <span>Status</span>
                            <x-status-badge :value="$credit->pengiriman->status_kirim" />
                        </div>
                        <div class="summary-list__item">
                            <span>Tanggal kirim</span>
                            <strong>{{ optional($credit->pengiriman->tgl_kirim)->format('d M Y H:i') ?: '-' }}</strong>
                        </div>
                        <div class="summary-list__item">
                            <span>Estimasi tiba</span>
                            <strong>{{ optional($credit->pengiriman->tgl_tiba)->format('d M Y H:i') ?: '-' }}</strong>
                        </div>
                        <div class="summary-list__item">
                            <span>Kurir</span>
                            <strong>{{ $credit->pengiriman->nama_kurir }}</strong>
                        </div>
                        <div class="summary-list__item">
                            <span>Telepon kurir</span>
                            <strong>{{ $credit->pengiriman->telpon_kurir }}</strong>
                        </div>
                    </div>
                @else
                    <div class="empty-state" style="margin-top: 16px;">Data pengiriman belum tersedia.</div>
                @endif
            </article>
        </div>
    </section>
@endsection
