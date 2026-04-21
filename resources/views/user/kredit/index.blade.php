@extends('layouts.user-portal')

@section('title', 'Kredit Saya')
@section('eyebrow', 'Kontrak aktif')
@section('page-description', 'Daftar kontrak kredit aktif, metode pembayaran, dan status pengiriman unit.')

@section('page-actions')
    <a href="{{ route('user.payments.index') }}" class="btn btn-primary">Pembayaran QRIS</a>
@endsection

@section('content')
    <section class="km-stack">
        <div class="km-card">
            <span class="km-chip">Kredit Aktif</span>
            <h1 style="font-size: 2rem; margin-top: 1rem;">Ringkasan kontrak dan pengiriman milik user</h1>
        </div>

        <div class="km-grid km-grid-2">
            @forelse ($credits as $credit)
                <article class="km-card">
                    <div style="display: flex; justify-content: space-between; gap: 1rem; flex-wrap: wrap;">
                        <div>
                            <h3>{{ $credit->nomor_kontrak ?: 'KRD-'.$credit->id }}</h3>
                            <p class="km-subtle">{{ $credit->pengajuanKredit?->motor?->nama_motor }}</p>
                        </div>
                        <span class="km-status status-{{ $credit->status_kredit }}">{{ strtoupper($credit->status_kredit) }}</span>
                    </div>
                    <div class="km-stack" style="margin-top: 1rem;">
                        <div><strong>Sisa kredit</strong><p class="km-subtle">Rp{{ number_format((float) $credit->sisa_kredit, 0, ',', '.') }}</p></div>
                        <div><strong>Metode bayar</strong><p class="km-subtle">{{ $credit->metodeBayar?->metode_bayar ?: '-' }}</p></div>
                        <div><strong>Pengiriman</strong><p class="km-subtle">{{ $credit->pengiriman?->status_kirim ?: 'Belum dibuat' }}</p></div>
                        <div><strong>Angsuran tercatat</strong><p class="km-subtle">{{ $credit->angsurans_count }} pembayaran</p></div>
                    </div>
                    <div class="table-actions" style="margin-top: 1rem;">
                        <a href="{{ route('user.kredit.show', $credit) }}" class="km-button-secondary">Detail Kredit</a>
                        <a href="{{ route('user.payments.index') }}" class="km-button">Pembayaran</a>
                    </div>
                </article>
            @empty
                <div class="km-empty" style="grid-column: 1 / -1;">Belum ada kredit aktif untuk user ini.</div>
            @endforelse
        </div>

        <div class="km-card">
            {{ $credits->links() }}
        </div>
    </section>
@endsection
