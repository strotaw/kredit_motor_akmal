@extends('layouts.app')

@section('content')
    <section class="km-stack">
        <div class="km-card">
            <span class="km-chip">Admin • Pengiriman</span>
            <h1 style="font-size: 2rem; margin-top: 1rem;">Monitor unit yang sedang dikirim</h1>
        </div>

        <div class="km-card km-table-wrap">
            <table class="km-table">
                <thead>
                    <tr>
                        <th>Invoice</th>
                        <th>User</th>
                        <th>Motor</th>
                        <th>Kurir</th>
                        <th>Tanggal kirim</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($deliveries as $delivery)
                        <tr>
                            <td>{{ $delivery->no_invoice }}</td>
                            <td>{{ $delivery->kredit?->pengajuanKredit?->user?->name ?: 'Belum terhubung' }}</td>
                            <td>{{ $delivery->kredit?->pengajuanKredit?->motor?->nama_motor }}</td>
                            <td>{{ $delivery->nama_kurir }}</td>
                            <td>{{ optional($delivery->tgl_kirim)->format('d M Y H:i') }}</td>
                            <td><span class="km-status status-{{ $delivery->status_kirim }}">{{ strtoupper($delivery->status_kirim) }}</span></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6"><div class="km-empty">Belum ada data pengiriman.</div></td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="km-card">
            {{ $deliveries->links() }}
        </div>
    </section>
@endsection
