<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ $title ?? 'Kredit Motor Akmal' }}</title>
        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @else
            <style>
                body { font-family: Arial, sans-serif; margin: 0; padding: 0; }
                .km-container { width: min(1180px, calc(100% - 2rem)); margin: 0 auto; }
                .km-topbar { padding: 1rem 0; border-bottom: 1px solid #ddd; background: #fff; }
                .km-topbar-inner, .km-topnav, .km-userbar { display: flex; gap: 0.75rem; flex-wrap: wrap; align-items: center; justify-content: space-between; }
                .km-frame { display: grid; grid-template-columns: 280px 1fr; gap: 1.5rem; padding: 1.5rem 0; }
                .km-sidebar, .km-card, .km-hero { background: #fff; border: 1px solid #ddd; border-radius: 16px; padding: 1.25rem; }
                .km-stack, .km-sidebar nav { display: grid; gap: 1rem; }
                .km-grid { display: grid; gap: 1rem; }
                .km-grid-2 { grid-template-columns: repeat(2, minmax(0, 1fr)); }
                .km-grid-3 { grid-template-columns: repeat(3, minmax(0, 1fr)); }
                .km-button, .km-button-secondary, .km-chip, .km-nav-link, .km-side-link { display: inline-flex; align-items: center; gap: .5rem; border-radius: 999px; padding: .7rem 1rem; text-decoration: none; color: inherit; }
                .km-button { background: #bb4d00; color: #fff; border: none; }
                .km-button-secondary, .km-chip, .km-nav-link, .km-side-link { background: #f4f4f4; }
                .km-table { width: 100%; border-collapse: collapse; }
                .km-table th, .km-table td { text-align: left; padding: .8rem; border-bottom: 1px solid #eee; }
                .km-form-grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 1rem; }
                .km-field { display: grid; gap: .45rem; }
                .km-field input, .km-field select { padding: .8rem; border: 1px solid #ddd; border-radius: 12px; }
                .km-status { display: inline-flex; border-radius: 999px; padding: .35rem .75rem; background: #f4f4f4; }
                .km-empty, .km-alert { padding: 1rem; border: 1px dashed #ddd; border-radius: 12px; }
                @media (max-width: 1024px) { .km-frame, .km-grid-2, .km-grid-3, .km-form-grid { grid-template-columns: 1fr; } }
            </style>
        @endif
    </head>
    @php
        $currentUser = auth()->user();
        $roleLinks = match ($currentUser?->role) {
            'user' => [
                ['label' => 'Dashboard', 'route' => 'user.dashboard'],
                ['label' => 'Profil', 'route' => 'user.profile'],
                ['label' => 'Pengajuan', 'route' => 'user.pengajuan.index'],
                ['label' => 'Kredit', 'route' => 'user.kredit.index'],
            ],
            'admin' => [
                ['label' => 'Dashboard', 'route' => 'admin.dashboard'],
                ['label' => 'Pengajuan', 'route' => 'admin.pengajuan.index'],
                ['label' => 'Kredit', 'route' => 'admin.kredit.index'],
                ['label' => 'Pengiriman', 'route' => 'admin.pengiriman.index'],
            ],
            'ceo' => [
                ['label' => 'Dashboard', 'route' => 'ceo.dashboard'],
                ['label' => 'Data User', 'route' => 'ceo.users.index'],
                ['label' => 'Transaksi', 'route' => 'ceo.transaksi.index'],
            ],
            default => [],
        };
    @endphp
    <body>
        <div class="km-shell">
            <header class="km-topbar">
                <div class="km-container km-topbar-inner">
                    <a href="{{ route('home') }}" class="km-brand-lockup">
                        <div class="km-brand-mark">KM</div>
                        <div class="km-brand-text">
                            <strong>Kredit Motor Akmal</strong>
                            <span>Laravel monolith untuk user, admin, dan CEO</span>
                        </div>
                    </a>

                    <nav class="km-topnav">
                        <a href="{{ route('home') }}" class="km-nav-link {{ request()->routeIs('home') ? 'is-active' : '' }}">Beranda</a>
                        <a href="{{ route('motors.index') }}" class="km-nav-link {{ request()->routeIs('motors.*') ? 'is-active' : '' }}">Katalog Motor</a>
                        @guest
                            <a href="{{ route('login') }}" class="km-nav-link {{ request()->routeIs('login') ? 'is-active' : '' }}">Login</a>
                            <a href="{{ route('register') }}" class="km-button">Daftar</a>
                        @endguest
                    </nav>

                    @auth
                        <div class="km-userbar">
                            <span class="km-chip">{{ strtoupper(auth()->user()->role) }}</span>
                            <a href="{{ route('dashboard') }}" class="km-button-secondary">Buka Dashboard</a>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="km-button">Logout</button>
                            </form>
                        </div>
                    @endauth
                </div>
            </header>

            <div class="km-container km-frame {{ auth()->check() ? '' : 'km-frame-public' }}">
                @auth
                    <aside class="km-sidebar">
                        <h2>Navigasi {{ strtoupper(auth()->user()->role) }}</h2>
                        <nav>
                            @foreach ($roleLinks as $link)
                                <a href="{{ route($link['route']) }}" class="km-side-link {{ request()->routeIs($link['route']) ? 'is-active' : '' }}">
                                    <span>{{ $link['label'] }}</span>
                                    <span>&rarr;</span>
                                </a>
                            @endforeach
                        </nav>

                        <div class="km-card" style="margin-top: 1rem;">
                            <h3>API cepat</h3>
                            <p class="km-subtle" style="margin-top: 0.45rem;">Endpoint publik ada di `/api/motors`, dan dashboard per role ada di `/api/{role}/dashboard`.</p>
                        </div>
                    </aside>
                @endauth

                <main class="km-main">
                    @if (session('status'))
                        <div class="km-alert">{{ session('status') }}</div>
                    @endif

                    @if ($errors->any())
                        <div class="km-alert">
                            <strong style="display: block; margin-bottom: 0.45rem;">Perlu diperbaiki:</strong>
                            <ul style="display: grid; gap: 0.3rem;">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @yield('content')
                </main>
            </div>
        </div>
    </body>
</html>
