@aware(['gap' => 'md'])

@php
    $spacing = match ($gap) {
        'none' => 'mb-0',
        'sm' => 'mb-2',
        'lg' => 'mb-8',
        default => 'mb-4',
    };
@endphp

<div {{ $attributes->class(['break-inside-avoid', $spacing]) }}>
    {{ $slot }}
</div>
