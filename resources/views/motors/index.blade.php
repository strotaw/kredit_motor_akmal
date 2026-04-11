@extends('layouts.app')

@section('content')
    <section class="km-stack">
        <div class="km-hero">
            <span class="km-chip">Katalog Motor</span>
            <h1 style="font-size: 2.6rem; margin-top: 1rem;">Pilih unit yang siap masuk simulasi kredit.</h1>
            <p class="km-subtle" style="margin-top: 0.6rem;">Halaman ini sudah siap jadi entry point public untuk calon user sebelum membuat pengajuan.</p>
        </div>

        <div class="km-card">
            <form method="GET" class="km-form-grid">
                <div class="km-field">
                    <label for="search">Cari motor</label>
                    <input id="search" name="search" value="{{ $search }}" placeholder="Contoh: Vario, NMAX, CB150R">
                </div>
                <div class="km-field">
                    <label for="merk">Filter merk</label>
                    <input id="merk" name="merk" value="{{ $selectedMerk }}" placeholder="Contoh: Honda">
                </div>
            </form>
        </div>

        <div class="km-grid km-grid-3">
            @forelse ($motors as $motor)
                <article class="km-card">
                    <p class="km-chip" style="width: fit-content;">{{ $motor->jenisMotor?->merk ?? 'Motor' }}</p>
                    <h3 style="margin-top: 0.8rem;">{{ $motor->nama_motor }}</h3>
                    <p class="km-subtle" style="margin-top: 0.35rem;">{{ $motor->warna }} • {{ $motor->kapasitas_mesin }} • {{ $motor->tahun }}</p>
                    <div class="km-card-value" style="font-size: 1.5rem;">Rp{{ number_format((float) $motor->harga_jual, 0, ',', '.') }}</div>
                    <div style="display: flex; gap: 0.75rem; flex-wrap: wrap; margin-top: 1rem;">
                        <a href="{{ route('motors.show', $motor) }}" class="km-button-secondary">Detail</a>
                        @auth
                            <a href="{{ route('user.pengajuan.index') }}" class="km-button">Ajukan</a>
                        @else
                            <a href="{{ route('register') }}" class="km-button">Daftar & Ajukan</a>
                        @endauth
                    </div>
                </article>
            @empty
                <div class="km-empty" style="grid-column: 1 / -1;">
                    Belum ada motor yang cocok dengan filter ini.
                </div>
            @endforelse
        </div>

        <div class="km-card">
            {{ $motors->links() }}
        </div>
    </section>
@endsection
