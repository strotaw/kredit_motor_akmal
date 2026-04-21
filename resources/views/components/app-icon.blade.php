@props([
    'name',
    'class' => '',
])

@php
    $iconClass = trim('app-icon '.$class);
@endphp

@switch($name)
    @case('dashboard')
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="{{ $iconClass }}" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M4.75 12.75h6.5v6.5h-6.5zm8-7.5h6.5v5h-6.5zm0 8h6.5v6.5h-6.5zm-8-8h6.5v5h-6.5z" />
        </svg>
        @break

    @case('pengajuan')
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="{{ $iconClass }}" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M8.5 4.75h7l4 4v10.5a1 1 0 0 1-1 1h-13a1 1 0 0 1-1-1V5.75a1 1 0 0 1 1-1z" />
            <path stroke-linecap="round" stroke-linejoin="round" d="M8.5 4.75v4h4m-4 4h7m-7 3h4.5" />
        </svg>
        @break

    @case('credit')
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="{{ $iconClass }}" aria-hidden="true">
            <rect x="3.75" y="6.25" width="16.5" height="11.5" rx="2.25" />
            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 10.75h16.5M7.5 15.25h3.25" />
        </svg>
        @break

    @case('payment')
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="{{ $iconClass }}" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 4.75h10.5a2 2 0 0 1 2 2v10.5a2 2 0 0 1-2 2H6.75a2 2 0 0 1-2-2V6.75a2 2 0 0 1 2-2z" />
            <path stroke-linecap="round" stroke-linejoin="round" d="M8.75 8.75h2.5v2.5h-2.5zm0 4h2.5v2.5h-2.5zm4-4h2.5v2.5h-2.5zm4 4h-2.5v2.5h2.5m-4 0h-2.5v2.5h2.5" />
        </svg>
        @break

    @case('truck')
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="{{ $iconClass }}" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h10.5v8.5H3.75zm10.5 2h3l2 2.5v4h-5zm-7.5 7.5a1.75 1.75 0 1 1 0 3.5a1.75 1.75 0 0 1 0-3.5zm10 0a1.75 1.75 0 1 1 0 3.5a1.75 1.75 0 0 1 0-3.5z" />
        </svg>
        @break

    @case('users')
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="{{ $iconClass }}" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 12.25a3.25 3.25 0 1 0 0-6.5a3.25 3.25 0 0 0 0 6.5zm-6.5 6a5.75 5.75 0 0 1 13 0m3.25-6.5a2.5 2.5 0 1 0-2.5-2.5m1.75 8.5a4.6 4.6 0 0 0-1.75-3.62" />
        </svg>
        @break

    @case('report')
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="{{ $iconClass }}" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 18.25V11.5m5.25 6.75V6.5m5.25 11.75v-4.5M4.75 19.25h14.5" />
        </svg>
        @break

    @case('catalog')
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="{{ $iconClass }}" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M5.75 5.25h12.5a1 1 0 0 1 1 1v11.5a1 1 0 0 1-1 1H5.75a1 1 0 0 1-1-1V6.25a1 1 0 0 1 1-1z" />
            <path stroke-linecap="round" stroke-linejoin="round" d="M8 8.5h8m-8 3h8m-8 3h4.5" />
        </svg>
        @break

    @case('profile')
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="{{ $iconClass }}" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 12a3.5 3.5 0 1 0-3.5-3.5A3.5 3.5 0 0 0 12 12zm-6.5 6.25a6.5 6.5 0 0 1 13 0" />
        </svg>
        @break

    @case('home')
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="{{ $iconClass }}" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="m4.75 10.5 7.25-5.75 7.25 5.75v8a1 1 0 0 1-1 1h-4.5v-5h-3.5v5h-4.5a1 1 0 0 1-1-1z" />
        </svg>
        @break

    @case('search')
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="{{ $iconClass }}" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M17.5 17.5 20 20m-3.75-9a5.25 5.25 0 1 1-10.5 0a5.25 5.25 0 0 1 10.5 0z" />
        </svg>
        @break

    @case('logout')
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="{{ $iconClass }}" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M10 6.25H7a1.5 1.5 0 0 0-1.5 1.5v8.5A1.5 1.5 0 0 0 7 17.75h3" />
            <path stroke-linecap="round" stroke-linejoin="round" d="m13.5 15.5 3.75-3.5-3.75-3.5M9.75 12h7.5" />
        </svg>
        @break

    @case('spark')
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="{{ $iconClass }}" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="m12 4.75 1.85 4.4 4.4 1.85-4.4 1.85L12 17.25l-1.85-4.4-4.4-1.85 4.4-1.85zm5.25 10.5.85 2 .9-2 .9-.85-2-.9-.65-1.9-.85 1.9-2 .9z" />
        </svg>
        @break

    @case('clock')
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="{{ $iconClass }}" aria-hidden="true">
            <circle cx="12" cy="12" r="7.25" />
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8.5v4l2.75 1.75" />
        </svg>
        @break

    @default
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="{{ $iconClass }}" aria-hidden="true">
            <circle cx="12" cy="12" r="7.25" />
        </svg>
@endswitch
