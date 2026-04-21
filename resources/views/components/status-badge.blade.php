@props([
    'value' => 'unknown',
    'label' => null,
])

@php
    $statusValue = strtolower(str_replace([' ', '/'], '_', (string) $value));
    $statusLabel = $label ?? str_replace('_', ' ', strtoupper((string) $value));
@endphp

<span {{ $attributes->class(['status-pill', 'status-'.$statusValue]) }}>
    {{ $statusLabel }}
</span>
