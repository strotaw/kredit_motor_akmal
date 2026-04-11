@extends('layouts.app')

@section('content')
    <section class="km-stack">
        <div class="km-card">
            <span class="km-chip">CEO • Data User</span>
            <h1 style="font-size: 2rem; margin-top: 1rem;">Monitoring user customer</h1>
        </div>

        <div class="km-card km-table-wrap">
            <table class="km-table">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Total pengajuan</th>
                        <th>Status akun</th>
                        <th>Tanggal daftar</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $user)
                        <tr>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ number_format($user->pengajuan_kredits_count) }}</td>
                            <td>{{ $user->is_active ? 'Aktif' : 'Nonaktif' }}</td>
                            <td>{{ optional($user->created_at)->format('d M Y') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5"><div class="km-empty">Belum ada user.</div></td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="km-card">
            {{ $users->links() }}
        </div>
    </section>
@endsection
