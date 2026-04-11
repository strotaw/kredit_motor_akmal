@extends('layouts.app')

@section('content')
    <section class="km-stack">
        <div class="km-grid km-grid-2">
            <div class="km-card">
                <span class="km-chip">{{ $motor->jenisMotor?->merk ?? 'Motor' }}</span>
                <h1 style="font-size: 2.3rem; margin-top: 1rem;">{{ $motor->nama_motor }}</h1>
                <p class="km-subtle" style="margin-top: 0.7rem;">{{ $motor->deskripsi_motor }}</p>
                <div class="km-grid km-grid-2" style="margin-top: 1.4rem;">
                    <div class="km-card" style="background: white;">
                        <p class="km-subtle">Harga cash</p>
                        <div class="km-card-value" style="font-size: 1.4rem;">Rp{{ number_format((float) $motor->harga_jual, 0, ',', '.') }}</div>
                    </div>
                    <div class="km-card" style="background: white;">
                        <p class="km-subtle">Stok</p>
                        <div class="km-card-value" style="font-size: 1.4rem;">{{ number_format((float) $motor->stok, 0, ',', '.') }}</div>
                    </div>
                </div>
                <div style="display: flex; gap: 0.75rem; flex-wrap: wrap; margin-top: 1.5rem;">
                    @auth
                        <a href="{{ route('user.pengajuan.index') }}" class="km-button">Lanjut ke Pengajuan</a>
                    @else
                        <a href="{{ route('register') }}" class="km-button">Daftar untuk Mengajukan</a>
                    @endauth
                    <a href="{{ route('motors.index') }}" class="km-button-secondary">Kembali ke katalog</a>
                </div>
            </div>

            <div class="km-card">
                <h3>Spesifikasi ringkas</h3>
                <div class="km-stack" style="margin-top: 1rem;">
                    <div>
                        <strong>Warna</strong>
                        <p class="km-subtle">{{ $motor->warna }}</p>
                    </div>
                    <div>
                        <strong>Kapasitas mesin</strong>
                        <p class="km-subtle">{{ $motor->kapasitas_mesin }}</p>
                    </div>
                    <div>
                        <strong>Tahun</strong>
                        <p class="km-subtle">{{ $motor->tahun }}</p>
                    </div>
                    <div>
                        <strong>Foto utama</strong>
                        <p class="km-subtle">{{ $motor->primary_image ?: 'Belum ada path foto utama.' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="km-card">
            <h3>Motor serupa</h3>
            <div class="km-grid km-grid-3" style="margin-top: 1rem;">
                @forelse ($relatedMotors as $relatedMotor)
                    <a href="{{ route('motors.show', $relatedMotor) }}" class="km-card" style="background: white;">
                        <h3>{{ $relatedMotor->nama_motor }}</h3>
                        <p class="km-subtle" style="margin-top: 0.35rem;">{{ $relatedMotor->warna }} • {{ $relatedMotor->tahun }}</p>
                        <div class="km-card-value" style="font-size: 1.35rem;">Rp{{ number_format((float) $relatedMotor->harga_jual, 0, ',', '.') }}</div>
                    </a>
                @empty
                    <div class="km-empty" style="grid-column: 1 / -1;">Belum ada motor lain pada jenis yang sama.</div>
                @endforelse
            </div>
        </div>
    </section>
@endsection
