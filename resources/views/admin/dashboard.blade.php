@extends('layouts.app')

@section('content')
    <section class="km-stack">
        <div class="km-hero">
            <span class="km-chip">Dashboard Admin</span>
            <h1 style="font-size: 2.5rem; margin-top: 1rem;">Kelola antrian pengajuan, kredit aktif, dan pengiriman.</h1>
        </div>

        <div class="km-grid km-grid-3">
            @foreach ($metrics as $label => $value)
                <div class="km-card">
                    <p class="km-subtle">{{ str_replace('_', ' ', ucwords($label, '_')) }}</p>
                    <div class="km-card-value">{{ number_format($value) }}</div>
                </div>
            @endforeach
        </div>

        <div class="km-card km-table-wrap">
            <h3 style="margin-bottom: 1rem;">Pengajuan terbaru</h3>
            <table class="km-table">
                <thead>
                    <tr>
                        <th>Kode</th>
                        <th>User</th>
                        <th>Motor</th>
                        <th>DP</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($latestPengajuan as $pengajuan)
                        <tr>
                            <td>{{ $pengajuan->kode_pengajuan ?: 'PGJ-'.$pengajuan->id }}</td>
                            <td>{{ $pengajuan->user?->name ?: 'Belum terhubung' }}</td>
                            <td>{{ $pengajuan->motor?->nama_motor }}</td>
                            <td>Rp{{ number_format((float) $pengajuan->dp, 0, ',', '.') }}</td>
                            <td><span class="km-status status-{{ $pengajuan->status_pengajuan }}">{{ str_replace('_', ' ', strtoupper($pengajuan->status_pengajuan)) }}</span></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5"><div class="km-empty">Belum ada data pengajuan.</div></td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
@endsection
