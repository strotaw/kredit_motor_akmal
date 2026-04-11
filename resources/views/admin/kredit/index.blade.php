@extends('layouts.app')

@section('content')
    <section class="km-stack">
        <div class="km-card">
            <span class="km-chip">Admin • Kredit</span>
            <h1 style="font-size: 2rem; margin-top: 1rem;">Daftar kredit aktif dan histori kontrak</h1>
        </div>

        <div class="km-card km-table-wrap">
            <table class="km-table">
                <thead>
                    <tr>
                        <th>Kontrak</th>
                        <th>User</th>
                        <th>Motor</th>
                        <th>Mulai</th>
                        <th>Sisa</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($credits as $credit)
                        <tr>
                            <td>{{ $credit->nomor_kontrak ?: 'KRD-'.$credit->id }}</td>
                            <td>{{ $credit->pengajuanKredit?->user?->name ?: 'Belum terhubung' }}</td>
                            <td>{{ $credit->pengajuanKredit?->motor?->nama_motor }}</td>
                            <td>{{ optional($credit->tgl_mulai_kredit)->format('d M Y') }}</td>
                            <td>Rp{{ number_format((float) $credit->sisa_kredit, 0, ',', '.') }}</td>
                            <td><span class="km-status status-{{ $credit->status_kredit }}">{{ strtoupper($credit->status_kredit) }}</span></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6"><div class="km-empty">Belum ada data kredit.</div></td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="km-card">
            {{ $credits->links() }}
        </div>
    </section>
@endsection
