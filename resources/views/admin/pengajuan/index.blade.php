@extends('layouts.backoffice')

@section('title', 'Pengajuan Kredit')
@section('page-description', 'Daftar seluruh pengajuan kredit yang sedang ditangani tim admin.')

@section('content')
    <section class="km-stack">
        <div class="km-card">
            <span class="km-chip">Admin • Pengajuan</span>
            <h1 style="font-size: 2rem; margin-top: 1rem;">List pengajuan kredit</h1>
        </div>

        <div class="km-card km-table-wrap">
            <table class="km-table">
                <thead>
                    <tr>
                        <th>Kode</th>
                        <th>Tanggal</th>
                        <th>User</th>
                        <th>Motor</th>
                        <th>Tenor</th>
                        <th>Cicilan</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($pengajuanList as $pengajuan)
                        <tr>
                            <td>{{ $pengajuan->kode_pengajuan ?: 'PGJ-'.$pengajuan->id }}</td>
                            <td>{{ optional($pengajuan->tgl_pengajuan_kredit)->format('d M Y') }}</td>
                            <td>{{ $pengajuan->user?->name ?: 'Belum terhubung' }}</td>
                            <td>{{ $pengajuan->motor?->nama_motor }}</td>
                            <td>{{ $pengajuan->jenisCicilan?->lama_cicilan }} bulan</td>
                            <td>Rp{{ number_format((float) $pengajuan->cicilan_perbulan, 0, ',', '.') }}</td>
                            <td><span class="km-status status-{{ $pengajuan->status_pengajuan }}">{{ str_replace('_', ' ', strtoupper($pengajuan->status_pengajuan)) }}</span></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7"><div class="km-empty">Belum ada pengajuan.</div></td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="km-card">
            {{ $pengajuanList->links() }}
        </div>
    </section>
@endsection
