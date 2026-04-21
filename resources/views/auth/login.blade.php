@extends('layouts.public')

@section('title', 'Login')

@section('content')
    <div class="auth-card auth-card--narrow">
        <span class="pill pill-soft">Login</span>
        <h1 style="font-size: 2rem; margin: 18px 0 10px;">Masuk ke sistem kredit motor</h1>
        <p class="muted-text" style="margin: 0;">Gunakan akun `user`, `admin`, atau `ceo` untuk melihat dashboard sesuai role.</p>

        <form action="{{ route('login.store') }}" method="POST" class="content-stack" style="margin-top: 24px;">
            @csrf
            <div class="field">
                <label for="email">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus>
            </div>

            <div class="field">
                <label for="password">Password</label>
                <input id="password" type="password" name="password" required>
            </div>

            <label class="checkbox-line">
                <input type="checkbox" name="remember" value="1">
                <span class="muted-text">Ingat sesi login saya</span>
            </label>

            <button type="submit" class="btn btn-primary">Login</button>
        </form>

        <p class="muted-text" style="margin-top: 18px;">Belum punya akun? <a href="{{ route('register') }}" style="font-weight: 700; color: var(--primary-strong);">Daftar di sini</a>.</p>
    </div>
@endsection
