@extends('layouts.backoffice')

@section('title', 'Laporan CEO')
@section('page-description', 'Ringkasan laporan pengajuan, kredit, angsuran, dan performa motor untuk kebutuhan monitoring manajemen.')

@section('content')
    <section class="content-stack">
        <article class="panel">
            <form method="GET" class="form-grid">
                <div class="field">
                    <label for="from">Dari tanggal</label>
                    <input id="from" type="date" name="from" value="{{ $from }}">
                </div>
                <div class="field">
                    <label for="to">Sampai tanggal</label>
                    <input id="to" type="date" name="to" value="{{ $to }}">
                </div>
                <div class="field" style="align-self: end;">
                    <button type="submit" class="btn btn-primary">Terapkan</button>
                </div>
            </form>

            <div class="table-actions" style="margin-top: 18px;">
                <a href="{{ route('ceo.laporan.index', ['from' => $from, 'to' => $to, 'export' => 'pengajuan']) }}" class="btn btn-secondary">Export Pengajuan CSV</a>
                <a href="{{ route('ceo.laporan.index', ['from' => $from, 'to' => $to, 'export' => 'kredit']) }}" class="btn btn-secondary">Export Kredit CSV</a>
                <a href="{{ route('ceo.laporan.index', ['from' => $from, 'to' => $to, 'export' => 'angsuran']) }}" class="btn btn-secondary">Export Angsuran CSV</a>
            </div>
        </article>

        <div class="metric-grid">
            @foreach ($reportMetrics as $label => $value)
                <article class="metric-card">
                    <div class="metric-card__head">
                        <div>
                            <p class="eyebrow">{{ str_replace('_', ' ', strtoupper($label)) }}</p>
                            <div class="metric-card__value">
                                {{ str_contains($label, 'pendapatan') ? 'Rp'.number_format((float) $value, 0, ',', '.') : number_format($value) }}
                            </div>
                        </div>
                        <span class="metric-card__icon">
                            <x-app-icon name="report" class="icon-lg" />
                        </span>
                    </div>
                </article>
            @endforeach
        </div>

        <div class="grid-2">
            <article class="table-card">
                <h3 style="margin: 0 0 16px;">Ringkasan status pengajuan</h3>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Status</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($statusSummary as $row)
                            <tr>
                                <td><x-status-badge :value="$row->status_pengajuan" /></td>
                                <td>{{ number_format($row->total) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2"><div class="empty-state">Belum ada data.</div></td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </article>

            <article class="table-card">
                <h3 style="margin: 0 0 16px;">Ringkasan kredit</h3>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Status</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($creditSummary as $row)
                            <tr>
                                <td><x-status-badge :value="$row->status_kredit" /></td>
                                <td>{{ number_format($row->total) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2"><div class="empty-state">Belum ada data.</div></td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </article>
        </div>

        <div class="grid-2">
            <article class="table-card">
                <h3 style="margin: 0 0 16px;">Ringkasan angsuran</h3>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Status</th>
                            <th>Total data</th>
                            <th>Nominal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($installmentSummary as $row)
                            <tr>
                                <td><x-status-badge :value="$row->status_verifikasi" /></td>
                                <td>{{ number_format($row->total) }}</td>
                                <td>Rp{{ number_format((float) $row->nominal, 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3"><div class="empty-state">Belum ada data.</div></td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </article>

            <article class="table-card">
                <h3 style="margin: 0 0 16px;">Motor paling banyak diajukan</h3>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Motor</th>
                            <th>Total pengajuan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($motorSummary as $motor)
                            <tr>
                                <td>{{ $motor->nama_motor }}</td>
                                <td>{{ number_format($motor->pengajuan_kredits_count) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2"><div class="empty-state">Belum ada data.</div></td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </article>
        </div>
    </section>
@endsection
