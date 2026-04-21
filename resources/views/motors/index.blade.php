@extends('layouts.public')

@section('title', 'Katalog Motor')

@section('content')
    <section class="content-stack">
        <article class="hero-card hero-card--public">
            <span class="pill pill-soft">Katalog Motor</span>
            <h1 class="hero-card__title" style="max-width: 16ch;">Pilih unit yang siap masuk simulasi kredit.</h1>
            <p class="hero-card__copy">Gunakan filter merk, tipe, harga, dan stok untuk menemukan motor yang paling cocok sebelum menghitung cicilan.</p>
        </article>

        <section class="panel">
            <form method="GET" class="form-grid">
                <div class="field">
                    <label for="search">Cari motor</label>
                    <input id="search" name="search" value="{{ $search }}" placeholder="Contoh: Vario, NMAX, CB150R">
                </div>
                <div class="field">
                    <label for="merk">Filter merk</label>
                    <input id="merk" name="merk" value="{{ $selectedMerk }}" placeholder="Contoh: Honda">
                </div>
                <div class="field">
                    <label for="tipe">Filter tipe</label>
                    <select id="tipe" name="tipe">
                        <option value="">Semua tipe</option>
                        @foreach ($motorTypes as $type)
                            <option value="{{ $type->tipe }}" @selected($selectedType === $type->tipe)>{{ ucfirst(str_replace('_', ' ', $type->tipe)) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="field">
                    <label for="stock">Filter stok</label>
                    <select id="stock" name="stock">
                        <option value="">Semua stok</option>
                        <option value="ready" @selected($selectedStock === 'ready')>Ready stock</option>
                        <option value="low" @selected($selectedStock === 'low')>Stok terbatas</option>
                        <option value="empty" @selected($selectedStock === 'empty')>Stok habis</option>
                    </select>
                </div>
                <div class="field">
                    <label for="min_price">Harga minimum</label>
                    <input id="min_price" name="min_price" type="number" min="0" value="{{ $selectedMinPrice }}" placeholder="10000000">
                </div>
                <div class="field">
                    <label for="max_price">Harga maksimum</label>
                    <input id="max_price" name="max_price" type="number" min="0" value="{{ $selectedMaxPrice }}" placeholder="50000000">
                </div>
                <div class="field">
                    <label for="sort">Urutkan</label>
                    <select id="sort" name="sort">
                        <option value="">Terbaru</option>
                        <option value="harga_termurah" @selected($selectedSort === 'harga_termurah')>Harga termurah</option>
                        <option value="harga_termahal" @selected($selectedSort === 'harga_termahal')>Harga termahal</option>
                        <option value="stok_terbanyak" @selected($selectedSort === 'stok_terbanyak')>Stok terbanyak</option>
                        <option value="terlama" @selected($selectedSort === 'terlama')>Data terlama</option>
                    </select>
                </div>
                <div class="table-actions" style="grid-column: 1 / -1;">
                    <button type="submit" class="btn btn-primary">Terapkan Filter</button>
                    <a href="{{ route('motors.index') }}" class="btn btn-secondary">Reset</a>
                </div>
            </form>
        </section>

        @if ($featuredSimulation)
            <section class="panel">
                <div class="card-head">
                    <div>
                        <div class="eyebrow">Simulasi cepat</div>
                        <h2 style="margin: 12px 0 6px;">Estimasi kredit dari hasil pencarian saat ini</h2>
                        <p class="muted-text" style="margin: 0;">DP minimal dan cicilan berikut dihitung dari motor pertama yang tampil pada daftar ini.</p>
                    </div>
                    <a href="{{ route('simulation') }}" class="btn btn-secondary">Buka kalkulator lengkap</a>
                </div>
                <div class="summary-list" style="margin-top: 16px;">
                    <div class="summary-list__item">
                        <span>DP minimal</span>
                        <strong>Rp{{ number_format($featuredSimulation['minimum_dp'], 0, ',', '.') }}</strong>
                    </div>
                    <div class="summary-list__item">
                        <span>Cicilan per bulan</span>
                        <strong>Rp{{ number_format($featuredSimulation['cicilan_perbulan'], 0, ',', '.') }}</strong>
                    </div>
                    <div class="summary-list__item">
                        <span>Total kewajiban</span>
                        <strong>Rp{{ number_format($featuredSimulation['total_kewajiban'], 0, ',', '.') }}</strong>
                    </div>
                </div>
            </section>
        @endif

        <section class="grid-3">
            @forelse ($motors as $motor)
                <article class="data-card">
                    <div class="badge-row">
                        <span class="pill pill-soft">{{ $motor->jenisMotor?->merk ?? 'Motor' }}</span>
                        <span class="pill pill-soft">{{ ucfirst(str_replace('_', ' ', $motor->jenisMotor?->tipe ?? 'umum')) }}</span>
                        <span class="pill pill-soft">{{ $motor->tahun }}</span>
                    </div>
                    <h3 style="margin: 16px 0 8px;">{{ $motor->nama_motor }}</h3>
                    <p class="km-subtle" style="margin-top: 0.35rem;">{{ $motor->warna }} | {{ $motor->kapasitas_mesin }} | {{ $motor->tahun }}</p>
                    <div class="km-card-value" style="font-size: 1.5rem;">Rp{{ number_format((float) $motor->harga_jual, 0, ',', '.') }}</div>
                    <p class="muted-text" style="margin: 12px 0 0;">Stok: {{ number_format((float) $motor->stok, 0, ',', '.') }} unit</p>
                    <div style="display: flex; gap: 0.75rem; flex-wrap: wrap; margin-top: 1rem;">
                        <a href="{{ route('motors.show', $motor) }}" class="km-button-secondary">Detail</a>
                        <a href="{{ route('simulation', ['motor' => $motor->id]) }}" class="km-button-secondary">Simulasi</a>
                        @auth
                            <a href="{{ route('user.pengajuan.create', ['motor' => $motor->id]) }}" class="km-button">Ajukan</a>
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
        </section>

        <div class="km-card">
            {{ $motors->links() }}
        </div>
    </section>
@endsection
