@extends('layouts.app')

@section('content')
    <div class="km-card" style="max-width: 40rem; margin: 0 auto;">
        <span class="km-chip">Registrasi</span>
        <h1 style="font-size: 2rem; margin-top: 1rem;">Buat akun baru</h1>
        <p class="km-subtle" style="margin-top: 0.5rem;">Registrasi publik selalu membuat akun role `user`. Akun demo admin dan ceo disiapkan lewat seeder.</p>

        <form action="{{ route('register.store') }}" method="POST" class="km-stack" style="margin-top: 1.5rem;">
            @csrf
            <div class="km-form-grid">
                <div class="km-field">
                    <label for="name">Nama lengkap</label>
                    <input id="name" type="text" name="name" value="{{ old('name') }}" required>
                </div>

                <div class="km-field">
                    <label for="email">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required>
                </div>

                <div class="km-field">
                    <label for="password">Password</label>
                    <input id="password" type="password" name="password" required>
                </div>

                <div class="km-field">
                    <label for="password_confirmation">Konfirmasi password</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" required>
                </div>

            </div>

            <button type="submit" class="km-button">Buat akun</button>
        </form>
    </div>
@endsection
