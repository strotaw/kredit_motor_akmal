@extends('layouts.user-portal')

@section('title', 'Pengajuan Saya')
@section('eyebrow', 'Pipeline pribadi')
@section('page-description', 'Daftar seluruh pengajuan kredit milik user beserta status terbarunya.')

@section('content')
    <section class="km-stack">
        <div class="km-card">
            <div style="display: flex; justify-content: space-between; gap: 1rem; flex-wrap: wrap;">
                <div>
                    <span class="km-chip">Pengajuan Saya</span>
                    <h1 style="font-size: 2rem; margin-top: 1rem;">Daftar pengajuan kredit user</h1>
                    <p class="km-subtle" style="margin-top: 0.5rem;">Pantau status terbaru, cicilan, dan detail proses untuk setiap pengajuan.</p>
                </div>
                <a href="{{ route('user.pengajuan.create') }}" class="km-button">Buat Pengajuan Baru</a>
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
                        <th>Aksi</th>
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
                            <td><a href="{{ route('user.pengajuan.show', $pengajuan) }}" class="km-button-secondary">Detail</a></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">
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
