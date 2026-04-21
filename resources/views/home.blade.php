@extends('layouts.public')

@section('title', 'Beranda Kredit Motor Akmal')

@section('content')
    <section class="content-stack">
        <article class="hero-card hero-card--public">
            <span class="pill pill-soft">Satu aplikasi, tiga dashboard</span>
            <h1 class="hero-card__title">Pengajuan kredit motor yang rapi dari user sampai executive view.</h1>
            <p class="hero-card__copy">
                Cari motor, hitung simulasi, kirim pengajuan, lalu pantau kredit aktif dan pembayaran dari satu alur yang sama.
            </p>
            <div class="hero-actions">
                <a href="{{ route('motors.index') }}" class="btn btn-primary">Lihat Katalog Motor</a>
                <a href="{{ route('simulation') }}" class="btn btn-secondary">Coba Simulasi</a>
                @guest
                    <a href="{{ route('register') }}" class="btn btn-secondary">Daftar Sekarang</a>
                @else
                    <a href="{{ route('dashboard') }}" class="btn btn-secondary">Masuk ke Dashboard</a>
                @endguest
            </div>
        </article>

        <div class="metric-grid">
            <article class="metric-card">
                <div class="metric-card__head">
                    <div>
                        <p class="eyebrow">Inventory</p>
                        <div class="metric-card__value">{{ number_format($stats['motor']) }}</div>
                    </div>
                    <span class="metric-card__icon">
                        <x-app-icon name="catalog" class="icon-lg" />
                    </span>
                </div>
                <p class="metric-card__meta">Total motor aktif yang siap tampil di katalog.</p>
            </article>

            <article class="metric-card">
                <div class="metric-card__head">
                    <div>
                        <p class="eyebrow">Pipeline</p>
                        <div class="metric-card__value">{{ number_format($stats['pengajuan']) }}</div>
                    </div>
                    <span class="metric-card__icon">
                        <x-app-icon name="pengajuan" class="icon-lg" />
                    </span>
                </div>
                <p class="metric-card__meta">Total pengajuan yang sudah masuk ke sistem.</p>
            </article>

            <article class="metric-card">
                <div class="metric-card__head">
                    <div>
                        <p class="eyebrow">Customers</p>
                        <div class="metric-card__value">{{ number_format($stats['user']) }}</div>
                    </div>
                    <span class="metric-card__icon">
                        <x-app-icon name="users" class="icon-lg" />
                    </span>
                </div>
                <p class="metric-card__meta">Jumlah akun user customer yang sudah terdaftar.</p>
            </article>
        </div>

        <div class="grid-2">
            <div class="panel">
                <div class="eyebrow">Flow aplikasi</div>
                <div class="summary-list" style="margin-top: 12px;">
                    <div class="summary-list__item">
                        <div>
                            <strong>User Portal</strong>
                            <p class="list-meta">Katalog, pengajuan, kontrak, dan pembayaran QRIS dummy Midtrans.</p>
                        </div>
                    </div>
                    <div class="summary-list__item">
                        <div>
                            <strong>Admin Dashboard</strong>
                            <p class="list-meta">Pantau antrian, kredit aktif, angsuran, dan pengiriman.</p>
                        </div>
                    </div>
                    <div class="summary-list__item">
                        <div>
                            <strong>CEO Dashboard</strong>
                            <p class="list-meta">Baca KPI, user, transaksi, dan laporan bisnis dalam satu tempat.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="panel">
                <div class="eyebrow">Akses cepat</div>
                <h3 style="margin: 12px 0 10px;">Mulai dari katalog, simulasi, atau login sesuai peran.</h3>
                <div class="summary-list" style="margin-top: 12px;">
                    <div class="summary-list__item">
                        <span>Calon pembeli</span>
                        <a href="{{ route('motors.index') }}" class="quick-link">Lihat motor</a>
                    </div>
                    <div class="summary-list__item">
                        <span>Perhitungan cicilan</span>
                        <a href="{{ route('simulation') }}" class="quick-link">Buka simulasi</a>
                    </div>
                    <div class="summary-list__item">
                        <span>Akun terdaftar</span>
                        <a href="{{ route('login') }}" class="quick-link">Masuk sistem</a>
                    </div>
                </div>
            </div>
        </div>

        <section class="grid-3">
            <article class="data-card">
                <div class="eyebrow">Langkah 1</div>
                <h3 style="margin: 12px 0 8px;">Pilih motor</h3>
                <p class="muted-text" style="margin: 0;">Bandingkan unit yang tersedia, cek spesifikasi, dan lihat harga cash sebelum mengajukan.</p>
            </article>
            <article class="data-card">
                <div class="eyebrow">Langkah 2</div>
                <h3 style="margin: 12px 0 8px;">Hitung cicilan</h3>
                <p class="muted-text" style="margin: 0;">Atur DP, tenor, dan asuransi untuk melihat estimasi cicilan serta total kewajiban.</p>
            </article>
            <article class="data-card">
                <div class="eyebrow">Langkah 3</div>
                <h3 style="margin: 12px 0 8px;">Pantau proses</h3>
                <p class="muted-text" style="margin: 0;">User memantau status pengajuan dan kredit, admin mengelola operasional, CEO membaca laporan bisnis.</p>
            </article>
        </section>

        <section class="panel">
            <div class="card-head" style="margin-bottom: 18px;">
                <div>
                    <div class="eyebrow">Motor unggulan</div>
                    <h2 style="margin: 12px 0 6px;">Daftar unit yang siap masuk simulasi kredit</h2>
                    <p class="muted-text" style="margin: 0;">Data tetap diambil langsung dari tabel `motor` yang ada di project ini.</p>
                </div>
                <a href="{{ route('motors.index') }}" class="btn btn-secondary">Buka semua motor</a>
            </div>

            <div class="grid-3">
                @forelse ($featuredMotors as $motor)
                    <a href="{{ route('motors.show', $motor) }}" class="data-card">
                        <div class="badge-row">
                            <span class="pill pill-soft">{{ $motor->jenisMotor?->merk ?? 'Motor' }}</span>
                            <span class="pill pill-soft">{{ $motor->tahun }}</span>
                        </div>
                        <h3 style="margin: 16px 0 8px;">{{ $motor->nama_motor }}</h3>
                        <p class="muted-text" style="margin: 0;">{{ $motor->warna }} | {{ $motor->kapasitas_mesin }}</p>
                        <div class="metric-card__value" style="margin-top: 18px;">Rp{{ number_format((float) $motor->harga_jual, 0, ',', '.') }}</div>
                    </a>
                @empty
                    <div class="empty-state" style="grid-column: 1 / -1;">
                        Belum ada data motor. Setelah proses seeding selesai, daftar motor akan muncul di sini.
                    </div>
                @endforelse
            </div>
        </section>

        <section class="grid-3">
            <article class="data-card">
                <div class="eyebrow">Keunggulan</div>
                <h3 style="margin: 12px 0 8px;">Satu login, tiga role</h3>
                <p class="muted-text" style="margin: 0;">Semua akses memakai sistem akun yang sama dengan dashboard sesuai kebutuhan tiap role.</p>
            </article>
            <article class="data-card">
                <div class="eyebrow">Pembayaran</div>
                <h3 style="margin: 12px 0 8px;">QRIS Midtrans Sandbox</h3>
                <p class="muted-text" style="margin: 0;">Alur pembayaran user sudah memakai kartu QRIS sandbox untuk kebutuhan demo dan pengujian.</p>
            </article>
            <article class="data-card">
                <div class="eyebrow">Monitoring</div>
                <h3 style="margin: 12px 0 8px;">Laporan executive</h3>
                <p class="muted-text" style="margin: 0;">CEO bisa memantau ringkasan pengajuan, kredit aktif, angsuran, dan performa motor terlaris.</p>
            </article>
        </section>

        <section class="grid-3">
            <article class="data-card">
                <div class="eyebrow">Testimoni</div>
                <h3 style="margin: 12px 0 8px;">Pengajuan lebih jelas</h3>
                <p class="muted-text" style="margin: 0;">User bisa melihat posisi pengajuan, cicilan, dan pengiriman tanpa harus bertanya manual ke admin.</p>
            </article>
            <article class="data-card">
                <div class="eyebrow">Testimoni</div>
                <h3 style="margin: 12px 0 8px;">Operasional lebih rapi</h3>
                <p class="muted-text" style="margin: 0;">Admin punya tampilan terpusat untuk mengelola pengajuan, angsuran, dan distribusi unit.</p>
            </article>
            <article class="data-card">
                <div class="eyebrow">Testimoni</div>
                <h3 style="margin: 12px 0 8px;">Laporan cepat dibaca</h3>
                <p class="muted-text" style="margin: 0;">CEO cukup membuka dashboard dan halaman laporan untuk melihat gambaran bisnis harian.</p>
            </article>
        </section>
    </section>
@endsection
