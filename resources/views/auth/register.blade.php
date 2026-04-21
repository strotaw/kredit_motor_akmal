@extends('layouts.public')

@section('title', 'Registrasi User')

@section('content')
    <div class="auth-card">
        <span class="pill pill-soft">Registrasi</span>
        <h1 style="font-size: 2rem; margin: 18px 0 10px;">Buat akun baru</h1>
        <p class="muted-text" style="margin: 0;">Registrasi publik selalu membuat akun role `user`. Akun demo admin dan ceo tetap disiapkan lewat seeder.</p>

        <form action="{{ route('register.store') }}" method="POST" class="content-stack" style="margin-top: 24px;">
            @csrf
            <div class="form-grid">
                <div class="field">
                    <label for="name">Nama lengkap</label>
                    <input id="name" type="text" name="name" value="{{ old('name') }}" required>
                </div>

                <div class="field">
                    <label for="email">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required>
                </div>

                <div class="field">
                    <label for="password">Password</label>
                    <input id="password" type="password" name="password" required>
                </div>

                <div class="field">
                    <label for="password_confirmation">Konfirmasi password</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" required>
                </div>

            </div>

            <button type="submit" class="btn btn-primary">Buat akun</button>
        </form>
    </div>
@endsection
