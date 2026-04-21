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
        $role = $currentUser?->role;
        $backofficeLinks = match ($role) {
            'admin' => [
                ['label' => 'Dashboard', 'route' => 'admin.dashboard', 'pattern' => 'admin.dashboard', 'icon' => 'dashboard'],
                ['label' => 'Pengajuan', 'route' => 'admin.pengajuan.index', 'pattern' => 'admin.pengajuan.*', 'icon' => 'pengajuan'],
                ['label' => 'Kredit', 'route' => 'admin.kredit.index', 'pattern' => 'admin.kredit.*', 'icon' => 'credit'],
                ['label' => 'Angsuran', 'route' => 'admin.angsuran.index', 'pattern' => 'admin.angsuran.*', 'icon' => 'payment'],
                ['label' => 'Pengiriman', 'route' => 'admin.pengiriman.index', 'pattern' => 'admin.pengiriman.*', 'icon' => 'truck'],
            ],
            'ceo' => [
                ['label' => 'Dashboard Executive', 'route' => 'ceo.dashboard', 'pattern' => 'ceo.dashboard', 'icon' => 'dashboard'],
                ['label' => 'Data User', 'route' => 'ceo.users.index', 'pattern' => 'ceo.users.*', 'icon' => 'users'],
                ['label' => 'Transaksi', 'route' => 'ceo.transaksi.index', 'pattern' => 'ceo.transaksi.*', 'icon' => 'report'],
                ['label' => 'Laporan', 'route' => 'ceo.laporan.index', 'pattern' => 'ceo.laporan.*', 'icon' => 'report'],
            ],
            default => [],
        };
        $workspaceLabel = $role === 'ceo' ? 'Executive workspace' : 'Operational workspace';
    @endphp
    <body class="theme-backoffice">
        <div class="bo-shell">
            <aside class="bo-sidebar">
                <div class="bo-sidebar__brand">
                    <x-brand-logo />
                    <div class="bo-sidebar__role">
                        <span class="pill pill-solid">{{ strtoupper((string) $role) }}</span>
                        <p>{{ $workspaceLabel }}</p>
                    </div>
                </div>

                <nav class="bo-sidebar__nav" aria-label="Navigasi backoffice">
                    @foreach ($backofficeLinks as $link)
                        <a href="{{ route($link['route']) }}" class="bo-nav-link {{ request()->routeIs($link['pattern']) ? 'is-active' : '' }}">
                            <x-app-icon :name="$link['icon']" class="icon-md" />
                            <span>{{ $link['label'] }}</span>
                        </a>
                    @endforeach
                </nav>

                <div class="bo-sidebar__card">
                    <div class="eyebrow">Template focus</div>
                    <h3>{{ $role === 'ceo' ? 'Ringkas dan data-first' : 'TailAdmin operational flow' }}</h3>
                    <p>{{ $role === 'ceo' ? 'CEO tetap fokus baca KPI, transaksi, dan performa bisnis tanpa gangguan operasional.' : 'Admin fokus memantau antrian, kontrak, dan pengiriman dengan layout dashboard yang lebih rapi.' }}</p>
                </div>

                <div class="bo-sidebar__footer">
                    <a href="{{ route('home') }}" class="bo-footer-link">
                        <x-app-icon name="home" class="icon-sm" />
                        <span>Kembali ke landing page</span>
                    </a>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="bo-footer-link bo-footer-link--danger">
                            <x-app-icon name="logout" class="icon-sm" />
                            <span>Logout</span>
                        </button>
                    </form>
                </div>
            </aside>

            <div class="bo-main">
                <header class="bo-topbar">
                    <div>
                        <div class="eyebrow">{{ $workspaceLabel }}</div>
                        <h1 class="page-title">{{ trim($__env->yieldContent('title')) ?: 'Dashboard' }}</h1>
                        @hasSection('page-description')
                            <p class="page-description">@yield('page-description')</p>
                        @endif
                    </div>

                    <div class="bo-topbar__aside">
                        <label class="search-shell">
                            <x-app-icon name="search" class="icon-sm" />
                            <input type="text" value="" placeholder="Pencarian menu" disabled>
                        </label>

                        <div class="profile-chip">
                            <span class="profile-chip__avatar">{{ strtoupper(substr((string) $currentUser?->name, 0, 1)) }}</span>
                            <div>
                                <strong>{{ $currentUser?->name }}</strong>
                                <span>{{ now()->format('d M Y') }}</span>
                            </div>
                        </div>
                    </div>
                </header>

                <main class="bo-content">
                    @include('layouts.partials.flash-messages')

                    @hasSection('page-actions')
                        <div class="page-toolbar">
                            @yield('page-actions')
                        </div>
                    @endif

                    @yield('content')
                </main>
            </div>
        </div>
    </body>
</html>
