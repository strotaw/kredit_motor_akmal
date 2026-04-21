@extends('layouts.backoffice')

@section('title', 'Manajemen Angsuran')
@section('page-description', 'Input pembayaran manual, verifikasi bukti bayar, dan pantau status cicilan user.')

@section('content')
    <section class="content-stack">
        <div class="metric-grid">
            @foreach ($stats as $label => $value)
                <article class="metric-card">
                    <div class="metric-card__head">
                        <div>
                            <p class="eyebrow">{{ str_replace('_', ' ', strtoupper($label)) }}</p>
                            <div class="metric-card__value">{{ number_format($value) }}</div>
                        </div>
                        <span class="metric-card__icon">
                            <x-app-icon name="payment" class="icon-lg" />
                        </span>
                    </div>
                </article>
            @endforeach
        </div>

        <div class="grid-2">
            <article class="profile-card">
                <div class="eyebrow">Input pembayaran manual</div>
                <form action="{{ route('admin.angsuran.store') }}" method="POST" class="content-stack" style="margin-top: 16px;">
                    @csrf
                    <div class="form-grid">
                        <div class="field">
                            <label for="id_kredit">Kontrak</label>
                            <select id="id_kredit" name="id_kredit" required>
                                @foreach ($credits as $credit)
                                    <option value="{{ $credit->id }}">
                                        {{ $credit->nomor_kontrak }} - {{ $credit->pengajuanKredit?->user?->name }} - {{ $credit->pengajuanKredit?->motor?->nama_motor }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="field">
                            <label for="tgl_bayar">Tanggal bayar</label>
                            <input id="tgl_bayar" type="date" name="tgl_bayar" value="{{ now()->toDateString() }}" required>
                        </div>
                        <div class="field">
                            <label for="angsuran_ke">Angsuran ke</label>
                            <input id="angsuran_ke" type="number" min="1" name="angsuran_ke" required>
                        </div>
                        <div class="field">
                            <label for="total_bayar">Total bayar</label>
                            <input id="total_bayar" type="number" min="0" name="total_bayar" required>
                        </div>
                        <div class="field">
                            <label for="status_verifikasi">Status verifikasi</label>
                            <select id="status_verifikasi" name="status_verifikasi" required>
                                @foreach (['menunggu' => 'Menunggu', 'valid' => 'Valid', 'ditolak' => 'Ditolak'] as $value => $label)
                                    <option value="{{ $value }}">{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="field">
                            <label for="status_kredit">Status kredit</label>
                            <select id="status_kredit" name="status_kredit">
                                <option value="">Ikuti status saat ini</option>
                                @foreach (['cicil' => 'Cicil', 'macet' => 'Macet', 'lunas' => 'Lunas'] as $value => $label)
                                    <option value="{{ $value }}">{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="field" style="grid-column: 1 / -1;">
                            <label for="keterangan">Keterangan</label>
                            <textarea id="keterangan" name="keterangan"></textarea>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">Simpan Pembayaran</button>
                </form>
            </article>

            <article class="profile-card">
                <div class="eyebrow">Filter</div>
                <form method="GET" class="content-stack" style="margin-top: 16px;">
                    <div class="field">
                        <label for="status">Status verifikasi</label>
                        <select id="status" name="status">
                            <option value="">Semua status</option>
                            @foreach (['menunggu' => 'Menunggu', 'valid' => 'Valid', 'ditolak' => 'Ditolak'] as $value => $label)
                                <option value="{{ $value }}" @selected(request('status') === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-secondary">Terapkan Filter</button>
                </form>
            </article>
        </div>

        <section class="table-card">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Kontrak</th>
                        <th>User</th>
                        <th>Motor</th>
                        <th>Ke</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($installments as $angsuran)
                        <tr>
                            <td>{{ optional($angsuran->tgl_bayar)->format('d M Y') }}</td>
                            <td>{{ $angsuran->kredit?->nomor_kontrak }}</td>
                            <td>{{ $angsuran->kredit?->pengajuanKredit?->user?->name }}</td>
                            <td>{{ $angsuran->kredit?->pengajuanKredit?->motor?->nama_motor }}</td>
                            <td>{{ $angsuran->angsuran_ke }}</td>
                            <td>Rp{{ number_format((float) $angsuran->total_bayar, 0, ',', '.') }}</td>
                            <td><x-status-badge :value="$angsuran->status_verifikasi" /></td>
                            <td>
                                <form action="{{ route('admin.angsuran.verify', $angsuran) }}" method="POST" class="table-actions">
                                    @csrf
                                    <select name="status_verifikasi">
                                        @foreach (['menunggu' => 'Menunggu', 'valid' => 'Valid', 'ditolak' => 'Ditolak'] as $value => $label)
                                            <option value="{{ $value }}" @selected($angsuran->status_verifikasi === $value)>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                    <select name="status_kredit">
                                        <option value="">Status kredit</option>
                                        @foreach (['cicil' => 'Cicil', 'macet' => 'Macet', 'lunas' => 'Lunas'] as $value => $label)
                                            <option value="{{ $value }}">{{ $label }}</option>
                                        @endforeach
                                    </select>
                                    <input type="text" name="keterangan" value="{{ $angsuran->keterangan }}" placeholder="Catatan">
                                    <button type="submit" class="btn btn-secondary">Simpan</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8"><div class="empty-state">Belum ada data angsuran.</div></td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </section>

        <div class="panel">
            {{ $installments->links() }}
        </div>
    </section>
@endsection
