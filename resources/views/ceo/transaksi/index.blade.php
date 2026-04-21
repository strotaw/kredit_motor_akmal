@extends('layouts.backoffice')

@section('title', 'Data Transaksi')
@section('page-description', 'Daftar pengajuan dan kredit untuk kebutuhan monitoring CEO.')

@section('content')
    <section class="km-stack">
        <div class="km-card">
            <span class="km-chip">CEO • Transaksi</span>
            <h1 style="font-size: 2rem; margin-top: 1rem;">Daftar transaksi pengajuan dan kredit</h1>
        </div>

        <div class="km-card km-table-wrap">
            <table class="km-table">
                <thead>
                    <tr>
                        <th>Kode pengajuan</th>
                        <th>User</th>
                        <th>Motor</th>
                        <th>Nominal kredit</th>
                        <th>Status pengajuan</th>
                        <th>Status kredit</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($transactions as $transaction)
                        <tr>
                            <td>{{ $transaction->kode_pengajuan ?: 'PGJ-'.$transaction->id }}</td>
                            <td>{{ $transaction->user?->name ?: 'Belum terhubung' }}</td>
                            <td>{{ $transaction->motor?->nama_motor }}</td>
                            <td>Rp{{ number_format((float) $transaction->harga_kredit, 0, ',', '.') }}</td>
                            <td><span class="km-status status-{{ $transaction->status_pengajuan }}">{{ str_replace('_', ' ', strtoupper($transaction->status_pengajuan)) }}</span></td>
                            <td>
                                @if ($transaction->kredit)
                                    <span class="km-status status-{{ $transaction->kredit->status_kredit }}">{{ strtoupper($transaction->kredit->status_kredit) }}</span>
                                @else
                                    <span class="km-subtle">Belum aktif</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6"><div class="km-empty">Belum ada transaksi.</div></td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="km-card">
            {{ $transactions->links() }}
        </div>
    </section>
@endsection
