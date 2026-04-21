@props([
    'compact' => false,
])

@php
    $brandName = config('branding.name');
    $brandTagline = config('branding.tagline');
    $brandLogo = asset(config('branding.logo_path'));
    $brandAlt = config('branding.logo_alt');
@endphp

<a href="{{ route('home') }}" {{ $attributes->class(['brand-lockup', 'brand-lockup--compact' => $compact]) }}>
    <span class="brand-lockup__media">
        <img src="{{ $brandLogo }}" alt="{{ $brandAlt }}" class="brand-lockup__image">
    </span>

    @unless ($compact)
        <span class="brand-lockup__copy">
            <strong>{{ $brandName }}</strong>
            <span>{{ $brandTagline }}</span>
        </span>
    @endunless
</a>
