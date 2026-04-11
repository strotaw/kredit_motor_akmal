@extends('layouts.app')

@section('content')
    <section class="km-stack">
        <div class="km-hero">
            <span class="km-chip">Satu folder, semua jalan</span>
            <h1 style="font-size: clamp(2rem, 5vw, 4rem); max-width: 14ch; margin-top: 1rem;">Pengajuan kredit motor yang rapi dari user sampai approval admin.</h1>
            <p class="km-subtle" style="max-width: 56rem; margin-top: 1rem; font-size: 1.05rem;">
                Project ini sudah disiapkan untuk alur public catalog, dashboard role-based, dan API internal dalam satu Laravel. Tinggal lanjut ke modul form pengajuan, verifikasi admin, dan transaksi cicilan.
            </p>
            <div style="display: flex; gap: 0.85rem; flex-wrap: wrap; margin-top: 1.5rem;">
                <a href="{{ route('motors.index') }}" class="km-button">Lihat Katalog</a>
                @guest
                    <a href="{{ route('register') }}" class="km-button-secondary">Mulai sebagai User</a>
                @else
                    <a href="{{ route('dashboard') }}" class="km-button-secondary">Masuk Dashboard</a>
                @endguest
            </div>
        </div>

        <div class="km-grid km-grid-3">
            <div class="km-card">
                <p class="km-subtle">Total motor</p>
                <div class="km-card-value">{{ number_format($stats['motor']) }}</div>
            </div>
            <div class="km-card">
                <p class="km-subtle">Total pengajuan</p>
                <div class="km-card-value">{{ number_format($stats['pengajuan']) }}</div>
            </div>
            <div class="km-card">
                <p class="km-subtle">Total user customer</p>
                <div class="km-card-value">{{ number_format($stats['user']) }}</div>
            </div>
        </div>

        <div class="km-grid km-grid-2">
            <div class="km-card">
                <h3>Flow yang sudah disiapkan</h3>
                <div class="km-stack" style="margin-top: 1rem;">
                    <div>
                        <strong>1. User</strong>
                        <p class="km-subtle">Lihat motor, simulasi, login, dan pantau dashboard pengajuan.</p>
                    </div>
                    <div>
                        <strong>2. Admin</strong>
                        <p class="km-subtle">Lihat antrian pengajuan, status, kredit aktif, dan pengiriman.</p>
                    </div>
                    <div>
                        <strong>3. CEO</strong>
                        <p class="km-subtle">Pantau data user, transaksi, approval, dan performa motor.</p>
                    </div>
                </div>
            </div>

            <div class="km-card">
                <h3>Status implementasi saat ini</h3>
                <div class="km-stack" style="margin-top: 1rem;">
                    <p class="km-subtle">Auth session, middleware role, layout dashboard, model relasi, migration perapihan schema, serta API dasar sudah dipasang.</p>
                    <p class="km-subtle">Tahap berikutnya paling pas adalah wizard pengajuan user, upload dokumen, approval admin, dan transaksi angsuran.</p>
                </div>
            </div>
        </div>

        <div class="km-card">
            <div style="display: flex; justify-content: space-between; gap: 1rem; flex-wrap: wrap; margin-bottom: 1rem;">
                <div>
                    <h3>Motor unggulan</h3>
                    <p class="km-subtle">Data ini diambil langsung dari tabel `motor`.</p>
                </div>
                <a href="{{ route('motors.index') }}" class="km-button-secondary">Buka semua motor</a>
            </div>

            <div class="km-grid km-grid-3">
                @forelse ($featuredMotors as $motor)
                    <a href="{{ route('motors.show', $motor) }}" class="km-card" style="padding: 1rem; background: white;">
                        <p class="km-chip" style="width: fit-content;">{{ $motor->jenisMotor?->merk ?? 'Motor' }}</p>
                        <h3 style="margin-top: 0.8rem;">{{ $motor->nama_motor }}</h3>
                        <p class="km-subtle" style="margin-top: 0.35rem;">{{ $motor->warna }} • {{ $motor->tahun }}</p>
                        <div class="km-card-value" style="font-size: 1.45rem;">Rp{{ number_format((float) $motor->harga_jual, 0, ',', '.') }}</div>
                    </a>
                @empty
                    <div class="km-empty" style="grid-column: 1 / -1;">
                        Belum ada data motor. Setelah migration dan seeding jalan, daftar motor akan muncul di sini.
                    </div>
                @endforelse
            </div>
        </div>
    </section>
@endsection
