@extends('layouts.app')

@section('content')
    <section class="km-stack">
        <div class="km-card">
            <div style="display: flex; justify-content: space-between; gap: 1rem; flex-wrap: wrap;">
                <div>
                    <span class="km-chip">Pengajuan Saya</span>
                    <h1 style="font-size: 2rem; margin-top: 1rem;">Daftar pengajuan kredit user</h1>
                    <p class="km-subtle" style="margin-top: 0.5rem;">Tahap ini sudah siap untuk menjadi list page dan nanti akan disambungkan ke wizard pengajuan.</p>
                </div>
                <a href="{{ route('motors.index') }}" class="km-button">Pilih Motor Baru</a>
            </div>
        </div>

        <div class="km-card km-table-wrap">
            <table class="km-table">
                <thead>
                    <tr>
                        <th>Kode</th>
                        <th>Tanggal</th>
                        <th>Motor</th>
                        <th>Tenor</th>
                        <th>Cicilan / bulan</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($pengajuanList as $pengajuan)
                        <tr>
                            <td>{{ $pengajuan->kode_pengajuan ?: 'PGJ-'.$pengajuan->id }}</td>
                            <td>{{ optional($pengajuan->tgl_pengajuan_kredit)->format('d M Y') }}</td>
                            <td>{{ $pengajuan->motor?->nama_motor }}</td>
                            <td>{{ $pengajuan->jenisCicilan?->lama_cicilan }} bulan</td>
                            <td>Rp{{ number_format((float) $pengajuan->cicilan_perbulan, 0, ',', '.') }}</td>
                            <td><span class="km-status status-{{ $pengajuan->status_pengajuan }}">{{ str_replace('_', ' ', strtoupper($pengajuan->status_pengajuan)) }}</span></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">
                                <div class="km-empty">Belum ada pengajuan.</div>
                            </td>
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
