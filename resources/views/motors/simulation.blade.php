@extends('layouts.public')

@section('title', 'Simulasi Kredit')

@section('content')
    <section class="content-stack">
        <article class="hero-card hero-card--public">
            <span class="pill pill-soft">Simulasi Kredit</span>
            <h1 class="hero-card__title" style="max-width: 14ch;">Hitung skema cicilan sebelum mengajukan kredit.</h1>
            <p class="hero-card__copy">Pilih motor, tenor, asuransi, dan DP untuk melihat estimasi cicilan per bulan.</p>
        </article>

        <div class="grid-2">
            <section class="panel">
                <form method="GET" class="content-stack">
                    <div class="form-grid">
                        <div class="field">
                            <label for="motor">Motor</label>
                            <select id="motor" name="motor">
                                @foreach ($motors as $motor)
                                    <option value="{{ $motor->id }}" @selected($selectedMotor?->id === $motor->id)>{{ $motor->nama_motor }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="field">
                            <label for="tenor">Tenor</label>
                            <select id="tenor" name="tenor">
                                @foreach ($tenors as $tenor)
                                    <option value="{{ $tenor->id }}" @selected($selectedTenor?->id === $tenor->id)>{{ $tenor->lama_cicilan }} bulan</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="field">
                            <label for="asuransi">Asuransi</label>
                            <select id="asuransi" name="asuransi">
                                @foreach ($insurances as $insurance)
                                    <option value="{{ $insurance->id }}" @selected($selectedInsurance?->id === $insurance->id)>{{ $insurance->nama_asuransi }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="field">
                            <label for="dp">DP</label>
                            <input id="dp" type="number" min="0" name="dp" value="{{ $downPayment }}">
                        </div>
                    </div>

                    <div class="table-actions">
                        <button type="submit" class="btn btn-primary">Hitung Simulasi</button>
                        <a href="{{ route('motors.index') }}" class="btn btn-secondary">Kembali ke Katalog</a>
                    </div>
                </form>
            </section>

            <section class="info-card">
                @if ($selectedMotor && $simulation)
                    <div class="badge-row">
                        <span class="pill pill-soft">{{ $selectedMotor->jenisMotor?->merk ?? 'Motor' }}</span>
                        <span class="pill pill-soft">{{ $selectedTenor?->lama_cicilan }} bulan</span>
                    </div>
                    <h2 style="margin: 16px 0 8px;">{{ $selectedMotor->nama_motor }}</h2>
                    <div class="summary-list">
                        <div class="summary-list__item">
                            <span>Harga cash</span>
                            <strong>Rp{{ number_format($simulation['harga_cash'], 0, ',', '.') }}</strong>
                        </div>
                        <div class="summary-list__item">
                            <span>DP minimal</span>
                            <strong>Rp{{ number_format($simulation['minimum_dp'], 0, ',', '.') }}</strong>
                        </div>
                        <div class="summary-list__item">
                            <span>Harga kredit</span>
                            <strong>Rp{{ number_format($simulation['harga_kredit'], 0, ',', '.') }}</strong>
                        </div>
                        <div class="summary-list__item">
                            <span>Asuransi per bulan</span>
                            <strong>Rp{{ number_format($simulation['biaya_asuransi_perbulan'], 0, ',', '.') }}</strong>
                        </div>
                        <div class="summary-list__item">
                            <span>Cicilan per bulan</span>
                            <strong>Rp{{ number_format($simulation['cicilan_perbulan'], 0, ',', '.') }}</strong>
                        </div>
                        <div class="summary-list__item">
                            <span>Total kewajiban</span>
                            <strong>Rp{{ number_format($simulation['total_kewajiban'], 0, ',', '.') }}</strong>
                        </div>
                    </div>

                    <div class="hero-actions">
                        @auth
                            <a href="{{ route('user.pengajuan.create', ['motor' => $selectedMotor->id, 'tenor' => $selectedTenor?->id, 'asuransi' => $selectedInsurance?->id, 'dp' => $simulation['dp']]) }}" class="btn btn-primary">Lanjut ke Pengajuan</a>
                        @else
                            <a href="{{ route('register') }}" class="btn btn-primary">Daftar untuk Mengajukan</a>
                        @endauth
                    </div>
                @endif
            </section>
        </div>
    </section>
@endsection
