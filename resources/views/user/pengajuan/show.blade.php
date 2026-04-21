@extends('layouts.user-portal')

@section('title', $pengajuan->kode_pengajuan ?: 'Detail Pengajuan')
@section('eyebrow', 'Status pengajuan')
@section('page-description', 'Pantau status, dokumen, dan riwayat proses pengajuan kredit.')

@section('page-actions')
    @if (in_array($pengajuan->status_pengajuan, ['menunggu', 'diproses'], true))
        <form action="{{ route('user.pengajuan.cancel', $pengajuan) }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-secondary">Batalkan Pengajuan</button>
        </form>
    @endif
@endsection

@section('content')
    <section class="content-stack">
        <div class="grid-2">
            <article class="profile-card">
                <div class="card-head">
                    <div>
                        <div class="eyebrow">Pengajuan</div>
                        <h2 style="margin: 10px 0 6px;">{{ $pengajuan->kode_pengajuan }}</h2>
                        <p class="muted-text" style="margin: 0;">{{ $pengajuan->motor?->nama_motor }} • {{ optional($pengajuan->tgl_pengajuan_kredit)->format('d M Y') }}</p>
                    </div>
                    <x-status-badge :value="$pengajuan->status_pengajuan" />
                </div>

                <div class="summary-list" style="margin-top: 18px;">
                    <div class="summary-list__item">
                        <span>DP</span>
                        <strong>Rp{{ number_format((float) $pengajuan->dp, 0, ',', '.') }}</strong>
                    </div>
                    <div class="summary-list__item">
                        <span>Tenor</span>
                        <strong>{{ $pengajuan->jenisCicilan?->lama_cicilan }} bulan</strong>
                    </div>
                    <div class="summary-list__item">
                        <span>Cicilan per bulan</span>
                        <strong>Rp{{ number_format((float) $pengajuan->cicilan_perbulan, 0, ',', '.') }}</strong>
                    </div>
                    <div class="summary-list__item">
                        <span>Catatan</span>
                        <strong>{{ $pengajuan->keterangan_status_pengajuan ?: '-' }}</strong>
                    </div>
                </div>
            </article>

            <article class="profile-card">
                <div class="eyebrow">Dokumen</div>
                <div class="summary-list" style="margin-top: 16px;">
                    @forelse ($pengajuan->documents as $document)
                        <div class="summary-list__item">
                            <span>{{ str_replace('_', ' ', strtoupper($document->jenis_dokumen)) }}</span>
                            <x-status-badge :value="$document->status_verifikasi" />
                        </div>
                    @empty
                        <div class="empty-state">Dokumen belum tersedia.</div>
                    @endforelse
                </div>
            </article>
        </div>

        <div class="grid-2">
            <article class="profile-card">
                <div class="eyebrow">Timeline proses</div>
                <div class="summary-list" style="margin-top: 16px;">
                    @forelse ($pengajuan->statusLogs->sortByDesc('created_at') as $log)
                        <div class="summary-list__item">
                            <div>
                                <strong>{{ str_replace('_', ' ', strtoupper($log->status_baru)) }}</strong>
                                <p class="list-meta">{{ $log->catatan ?: 'Status diperbarui.' }}</p>
                            </div>
                            <span class="muted-text">{{ optional($log->created_at)->format('d M Y H:i') }}</span>
                        </div>
                    @empty
                        <div class="empty-state">Belum ada riwayat status.</div>
                    @endforelse
                </div>
            </article>

            <article class="profile-card">
                <div class="eyebrow">Status lanjutan</div>
                @if ($pengajuan->kredit)
                    <div class="summary-list" style="margin-top: 16px;">
                        <div class="summary-list__item">
                            <span>Nomor kontrak</span>
                            <strong>{{ $pengajuan->kredit->nomor_kontrak }}</strong>
                        </div>
                        <div class="summary-list__item">
                            <span>Status kredit</span>
                            <x-status-badge :value="$pengajuan->kredit->status_kredit" />
                        </div>
                        <div class="summary-list__item">
                            <span>Pengiriman</span>
                            <strong>{{ $pengajuan->kredit->pengiriman?->status_kirim ?: '-' }}</strong>
                        </div>
                    </div>
                @else
                    <div class="empty-state" style="margin-top: 16px;">Kontrak kredit belum dibuat.</div>
                @endif
            </article>
        </div>
    </section>
@endsection
