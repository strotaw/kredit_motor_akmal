@extends('layouts.app')

@section('content')
    <section class="km-stack">
        <div class="km-hero">
            <span class="km-chip">Dashboard CEO</span>
            <h1 style="font-size: 2.5rem; margin-top: 1rem;">Pantau user, transaksi, dan performa bisnis kredit motor.</h1>
        </div>

        <div class="km-grid km-grid-3">
            @foreach ($metrics as $label => $value)
                <div class="km-card">
                    <p class="km-subtle">{{ str_replace('_', ' ', ucwords($label, '_')) }}</p>
                    <div class="km-card-value">
                        {{ $label === 'pendapatan' ? 'Rp'.number_format((float) $value, 0, ',', '.') : number_format($value) }}
                    </div>
                </div>
            @endforeach
        </div>

        <div class="km-card km-table-wrap">
            <h3 style="margin-bottom: 1rem;">Motor dengan pengajuan terbanyak</h3>
            <table class="km-table">
                <thead>
                    <tr>
                        <th>Motor</th>
                        <th>Merk</th>
                        <th>Harga</th>
                        <th>Total pengajuan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($topMotors as $motor)
                        <tr>
                            <td>{{ $motor->nama_motor }}</td>
                            <td>{{ $motor->jenisMotor?->merk ?? '-' }}</td>
                            <td>Rp{{ number_format((float) $motor->harga_jual, 0, ',', '.') }}</td>
                            <td>{{ number_format($motor->pengajuan_kredits_count) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4"><div class="km-empty">Belum ada data motor.</div></td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
@endsection
