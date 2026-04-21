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
        $currentUser = auth()->user();
        $portalLinks = [
            ['label' => 'Dashboard', 'route' => 'user.dashboard', 'pattern' => 'user.dashboard', 'icon' => 'dashboard'],
            ['label' => 'Pengajuan', 'route' => 'user.pengajuan.index', 'pattern' => 'user.pengajuan.*', 'icon' => 'pengajuan'],
            ['label' => 'Kredit', 'route' => 'user.kredit.index', 'pattern' => 'user.kredit.*', 'icon' => 'credit'],
            ['label' => 'Pembayaran', 'route' => 'user.payments.index', 'pattern' => 'user.payments.*', 'icon' => 'payment'],
            ['label' => 'Profil', 'route' => 'user.profile', 'pattern' => 'user.profile', 'icon' => 'profile'],
        ];
    @endphp
    <body class="theme-user">
        <div class="page-shell">
            <header class="user-topbar">
                <div class="shell-container user-topbar__inner">
                    <x-brand-logo />

                    <nav class="user-topbar__nav" aria-label="Navigasi portal user">
                        <a href="{{ route('motors.index') }}" class="user-topbar__link {{ request()->routeIs('motors.*') ? 'is-active' : '' }}">Katalog</a>
                        <a href="{{ route('simulation') }}" class="user-topbar__link {{ request()->routeIs('simulation') ? 'is-active' : '' }}">Simulasi</a>
                        <a href="{{ route('user.pengajuan.index') }}" class="user-topbar__link {{ request()->routeIs('user.pengajuan.*') ? 'is-active' : '' }}">Pengajuan Saya</a>
                        <a href="{{ route('user.payments.index') }}" class="user-topbar__link {{ request()->routeIs('user.payments.*') ? 'is-active' : '' }}">Bayar Cicilan</a>
                    </nav>

                    <div class="user-topbar__actions">
                        <span class="pill pill-soft">Halo, {{ $currentUser?->name }}</span>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-ghost">Logout</button>
                        </form>
                    </div>
                </div>
            </header>

            <div class="shell-container user-layout">
                <aside class="user-sidebar">
                    <div class="user-sidebar__profile">
                        <span class="user-sidebar__avatar">{{ strtoupper(substr((string) $currentUser?->name, 0, 1)) }}</span>
                        <div>
                            <p class="eyebrow">Portal User</p>
                            <h2>{{ $currentUser?->name }}</h2>
                            <p>{{ $currentUser?->email }}</p>
                        </div>
                    </div>

                    <nav class="user-sidebar__nav" aria-label="Menu user">
                        @foreach ($portalLinks as $link)
                            <a href="{{ route($link['route']) }}" class="user-nav-link {{ request()->routeIs($link['pattern']) ? 'is-active' : '' }}">
                                <x-app-icon :name="$link['icon']" class="icon-md" />
                                <span>{{ $link['label'] }}</span>
                            </a>
                        @endforeach
                    </nav>

                    <div class="user-sidebar__card">
                        <div class="eyebrow">Pembayaran</div>
                        <h3>QRIS Dummy Midtrans</h3>
                        <p>Pembayaran cicilan memakai QRIS dummy Midtrans sandbox untuk kebutuhan demo alur user.</p>
                    </div>
                </aside>

                <main class="user-main">
                    <div class="user-page-head">
                        <div>
                            <div class="eyebrow">@yield('eyebrow', 'Customer dashboard')</div>
                            <h1 class="page-title">{{ trim($__env->yieldContent('title')) ?: 'Portal User' }}</h1>
                            @hasSection('page-description')
                                <p class="page-description">@yield('page-description')</p>
                            @endif
                        </div>

                        @hasSection('page-actions')
                            <div class="page-actions">
                                @yield('page-actions')
                            </div>
                        @endif
                    </div>

                    @include('layouts.partials.flash-messages')
                    @yield('content')
                </main>
            </div>

            <nav class="user-bottom-nav" aria-label="Quick navigation">
                @foreach ($portalLinks as $link)
                    <a href="{{ route($link['route']) }}" class="user-bottom-nav__link {{ request()->routeIs($link['pattern']) ? 'is-active' : '' }}">
                        <x-app-icon :name="$link['icon']" class="icon-sm" />
                        <span>{{ $link['label'] }}</span>
                    </a>
                @endforeach
            </nav>
        </div>
    </body>
</html>
