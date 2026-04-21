<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ trim($__env->yieldContent('title')) ?: config('branding.name') }}</title>

        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @else
            <style>{!! file_get_contents(resource_path('css/theme.css')) !!}</style>
        @endif
    </head>
    @php
        $publicLinks = [
            ['label' => 'Beranda', 'route' => 'home', 'pattern' => 'home'],
            ['label' => 'Katalog Motor', 'route' => 'motors.index', 'pattern' => 'motors.*'],
            ['label' => 'Simulasi', 'route' => 'simulation', 'pattern' => 'simulation'],
        ];
    @endphp
    <body class="theme-public">
        <div class="page-shell">
            <header class="site-header">
                <div class="shell-container site-header__inner">
                    <x-brand-logo class="site-header__brand" />

                    <nav class="site-nav" aria-label="Navigasi utama">
                        @foreach ($publicLinks as $link)
                            <a href="{{ route($link['route']) }}" class="site-nav__link {{ request()->routeIs($link['pattern']) ? 'is-active' : '' }}">
                                {{ $link['label'] }}
                            </a>
                        @endforeach
                    </nav>

                    <div class="site-header__actions">
                        @auth
                            <span class="pill pill-soft">{{ strtoupper(auth()->user()->role) }}</span>
                            <a href="{{ route('dashboard') }}" class="btn btn-secondary">Buka Dashboard</a>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-primary">Logout</button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-ghost">Login</a>
                            <a href="{{ route('register') }}" class="btn btn-primary">Daftar</a>
                        @endauth
                    </div>
                </div>
            </header>

            <main class="site-main">
                <div class="shell-container section-stack">
                    @include('layouts.partials.flash-messages')
                    @yield('content')
                </div>
            </main>

            <footer class="site-footer">
                <div class="shell-container site-footer__inner">
                    <x-brand-logo compact />
                    <p class="site-footer__copy">Sistem kredit motor terpusat untuk landing page, portal user, dashboard admin, dan executive reporting.</p>
                </div>
            </footer>
        </div>
    </body>
</html>
