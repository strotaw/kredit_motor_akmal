@extends('layouts.app')

@section('content')
    <div class="km-card" style="max-width: 34rem; margin: 0 auto;">
        <span class="km-chip">Login</span>
        <h1 style="font-size: 2rem; margin-top: 1rem;">Masuk ke sistem kredit motor</h1>
        <p class="km-subtle" style="margin-top: 0.5rem;">Gunakan akun `user`, `admin`, atau `ceo` untuk melihat dashboard sesuai role.</p>

        <form action="{{ route('login.store') }}" method="POST" class="km-stack" style="margin-top: 1.5rem;">
            @csrf
            <div class="km-field">
                <label for="email">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus>
            </div>

            <div class="km-field">
                <label for="password">Password</label>
                <input id="password" type="password" name="password" required>
            </div>

            <label style="display: inline-flex; gap: 0.65rem; align-items: center;">
                <input type="checkbox" name="remember" value="1">
                <span class="km-subtle">Ingat sesi login saya</span>
            </label>

            <button type="submit" class="km-button">Login</button>
        </form>

        <p class="km-subtle" style="margin-top: 1rem;">Belum punya akun? <a href="{{ route('register') }}" style="color: var(--km-brand); font-weight: 700;">Daftar di sini</a>.</p>
    </div>
@endsection
