@extends('layouts.public')

@section('title', $motor->nama_motor)

@section('content')
    @php
        $photos = collect([$motor->foto1, $motor->foto2, $motor->foto3])->filter();
    @endphp

    <section class="content-stack">
        <div class="grid-2">
            <article class="hero-card hero-card--public">
                <div class="badge-row">
                    <span class="pill pill-soft">{{ $motor->jenisMotor?->merk ?? 'Motor' }}</span>
                    <span class="pill pill-soft">{{ ucfirst(str_replace('_', ' ', $motor->jenisMotor?->tipe ?? 'umum')) }}</span>
                    <span class="pill pill-soft">{{ $motor->tahun }}</span>
                </div>
                <h1 class="hero-card__title" style="max-width: 14ch;">{{ $motor->nama_motor }}</h1>
                <p class="hero-card__copy">{{ $motor->deskripsi_motor }}</p>

                <div class="grid-2" style="margin-top: 18px;">
                    <div class="data-card">
                        <div class="eyebrow">Harga cash</div>
                        <div class="metric-card__value" style="margin-top: 10px;">Rp{{ number_format((float) $motor->harga_jual, 0, ',', '.') }}</div>
                    </div>
                    <div class="data-card">
                        <div class="eyebrow">Estimasi mulai dari</div>
                        <div class="metric-card__value" style="margin-top: 10px;">Rp{{ number_format((float) ($defaultSimulation['cicilan_perbulan'] ?? 0), 0, ',', '.') }}</div>
                    </div>
                </div>

                <div class="hero-actions">
                    <a href="{{ route('simulation', ['motor' => $motor->id]) }}" class="btn btn-secondary">Simulasi Kredit</a>
                    @auth
                        <a href="{{ route('user.pengajuan.create', ['motor' => $motor->id]) }}" class="btn btn-primary">Lanjut ke Pengajuan</a>
                    @else
                        <a href="{{ route('register') }}" class="btn btn-primary">Daftar untuk Mengajukan</a>
                    @endauth
                </div>
            </article>

            <article class="panel">
                <div class="eyebrow">Spesifikasi ringkas</div>
                <div class="summary-list" style="margin-top: 16px;">
                    <div class="summary-list__item">
                        <span>Warna</span>
                        <strong>{{ $motor->warna }}</strong>
                    </div>
                    <div class="summary-list__item">
                        <span>Kapasitas mesin</span>
                        <strong>{{ $motor->kapasitas_mesin }}</strong>
                    </div>
                    <div class="summary-list__item">
                        <span>Stok</span>
                        <strong>{{ number_format((float) $motor->stok, 0, ',', '.') }} unit</strong>
                    </div>
                    <div class="summary-list__item">
                        <span>Tenor tersedia</span>
                        <strong>{{ $tenors->pluck('lama_cicilan')->implode(', ') }} bulan</strong>
                    </div>
                    <div class="summary-list__item">
                        <span>Asuransi</span>
                        <strong>{{ $insurances->pluck('nama_asuransi')->implode(', ') }}</strong>
                    </div>
                </div>
            </article>
        </div>

        <section class="panel">
            <div class="card-head">
                <div>
                    <div class="eyebrow">Galeri motor</div>
                    <h2 style="margin: 12px 0 6px;">Foto unit</h2>
                </div>
                <a href="{{ route('motors.index') }}" class="btn btn-secondary">Kembali ke katalog</a>
            </div>

            <div class="grid-3" style="margin-top: 18px;">
                @forelse ($photos as $index => $photo)
                    @php
                        $isAbsolute = \Illuminate\Support\Str::startsWith($photo, ['http://', 'https://', '/']);
                        $photoUrl = $isAbsolute
                            ? $photo
                            : (file_exists(public_path($photo)) ? asset($photo) : asset('storage/'.$photo));
                    @endphp
                    <article class="data-card" style="padding: 14px;">
                        <div style="aspect-ratio: 4 / 3; overflow: hidden; border-radius: 18px; background: linear-gradient(135deg, rgba(23, 53, 96, 0.08), rgba(240, 107, 47, 0.12));">
                            <img src="{{ $photoUrl }}" alt="Foto {{ $index + 1 }} {{ $motor->nama_motor }}" style="width: 100%; height: 100%; object-fit: cover;">
                        </div>
                        <p class="muted-text" style="margin: 12px 0 0;">Foto {{ $index + 1 }}</p>
                    </article>
                @empty
                    <div class="empty-state" style="grid-column: 1 / -1;">Foto unit belum tersedia.</div>
                @endforelse
            </div>
        </section>

        @if ($defaultSimulation)
            <section class="grid-2">
                <article class="info-card">
                    <div class="eyebrow">Simulasi awal</div>
                    <h2 style="margin: 12px 0 8px;">Perkiraan paket kredit</h2>
                    <div class="summary-list">
                        <div class="summary-list__item">
                            <span>DP minimal</span>
                            <strong>Rp{{ number_format((float) $defaultSimulation['minimum_dp'], 0, ',', '.') }}</strong>
                        </div>
                        <div class="summary-list__item">
                            <span>Harga kredit</span>
                            <strong>Rp{{ number_format((float) $defaultSimulation['harga_kredit'], 0, ',', '.') }}</strong>
                        </div>
                        <div class="summary-list__item">
                            <span>Asuransi per bulan</span>
                            <strong>Rp{{ number_format((float) $defaultSimulation['biaya_asuransi_perbulan'], 0, ',', '.') }}</strong>
                        </div>
                        <div class="summary-list__item">
                            <span>Cicilan per bulan</span>
                            <strong>Rp{{ number_format((float) $defaultSimulation['cicilan_perbulan'], 0, ',', '.') }}</strong>
                        </div>
                    </div>
                </article>

                <article class="info-card">
                    <div class="eyebrow">Aksi</div>
                    <h2 style="margin: 12px 0 8px;">Lanjutkan proses</h2>
                    <p class="muted-text" style="margin: 0;">Sesuaikan DP, tenor, dan asuransi dari halaman simulasi lalu lanjutkan ke form pengajuan kredit.</p>
                    <div class="card-stack" style="margin-top: 18px;">
                        <a href="{{ route('simulation', ['motor' => $motor->id, 'tenor' => $tenors->first()?->id, 'asuransi' => $insurances->first()?->id, 'dp' => $defaultSimulation['minimum_dp']]) }}" class="quick-link">Atur simulasi lengkap</a>
                        @auth
                            <a href="{{ route('user.pengajuan.create', ['motor' => $motor->id, 'tenor' => $tenors->first()?->id, 'asuransi' => $insurances->first()?->id, 'dp' => $defaultSimulation['minimum_dp']]) }}" class="quick-link">Buka form pengajuan</a>
                        @endauth
                    </div>
                </article>
            </section>
        @endif

        <section class="panel">
            <div class="card-head">
                <div>
                    <div class="eyebrow">Motor serupa</div>
                    <h2 style="margin: 12px 0 6px;">Pilihan lain dalam kategori yang sama</h2>
                </div>
            </div>
            <div class="grid-3" style="margin-top: 18px;">
                @forelse ($relatedMotors as $relatedMotor)
                    <a href="{{ route('motors.show', $relatedMotor) }}" class="data-card">
                        <div class="badge-row">
                            <span class="pill pill-soft">{{ $relatedMotor->jenisMotor?->merk ?? 'Motor' }}</span>
                            <span class="pill pill-soft">{{ $relatedMotor->tahun }}</span>
                        </div>
                        <h3 style="margin: 16px 0 8px;">{{ $relatedMotor->nama_motor }}</h3>
                        <p class="muted-text" style="margin: 0;">{{ $relatedMotor->warna }} | {{ $relatedMotor->kapasitas_mesin }}</p>
                        <div class="metric-card__value" style="margin-top: 18px;">Rp{{ number_format((float) $relatedMotor->harga_jual, 0, ',', '.') }}</div>
                    </a>
                @empty
                    <div class="empty-state" style="grid-column: 1 / -1;">Belum ada motor lain pada jenis yang sama.</div>
                @endforelse
            </div>
        </section>
    </section>
@endsection
