@extends('layouts.app')

@section('content')
    <section class="km-stack">
        <div class="km-card">
            <span class="km-chip">Profil User</span>
            <h1 style="font-size: 2rem; margin-top: 1rem;">Ringkasan data akun dan profil</h1>
            <p class="km-subtle" style="margin-top: 0.5rem;">Form edit penuh belum saya bangun di tahap ini, tapi struktur data dan view dasarnya sudah disiapkan.</p>
        </div>

        <div class="km-grid km-grid-2">
            <div class="km-card">
                <h3>Data akun</h3>
                <div class="km-stack" style="margin-top: 1rem;">
                    <div><strong>Nama</strong><p class="km-subtle">{{ $user->name }}</p></div>
                    <div><strong>Email</strong><p class="km-subtle">{{ $user->email }}</p></div>
                    <div><strong>Role</strong><p class="km-subtle">{{ strtoupper($user->role) }}</p></div>
                    <div><strong>Login terakhir</strong><p class="km-subtle">{{ optional($user->last_login_at)->format('d M Y H:i') ?: 'Belum ada data' }}</p></div>
                </div>
            </div>

            <div class="km-card">
                <h3>Data profil</h3>
                @if ($user->profile)
                    <div class="km-stack" style="margin-top: 1rem;">
                        <div><strong>NIK</strong><p class="km-subtle">{{ $user->profile->nik ?: '-' }}</p></div>
                        <div><strong>No telp</strong><p class="km-subtle">{{ $user->profile->no_telp ?: '-' }}</p></div>
                        <div><strong>Pekerjaan</strong><p class="km-subtle">{{ $user->profile->pekerjaan ?: '-' }}</p></div>
                        <div><strong>Penghasilan bulanan</strong><p class="km-subtle">{{ $user->profile->penghasilan_bulanan ? 'Rp'.number_format((float) $user->profile->penghasilan_bulanan, 0, ',', '.') : '-' }}</p></div>
                    </div>
                @else
                    <div class="km-empty" style="margin-top: 1rem;">Belum ada data profil detail. Tabel `user_profiles` sudah siap untuk modul form profil.</div>
                @endif
            </div>
        </div>
    </section>
@endsection
