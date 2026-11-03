@props([
    'columns' => 3,
    'gap' => 'md',
])

{{--
    <x-ui::media-grid columns="3" gap="md">...</x-ui::media-grid>

    columns: 2 | 3 | 4   gap: none | sm | md | lg
--}}

@php
    $columnClasses = match ((int) $columns) {
        2 => 'grid-cols-1 sm:grid-cols-2',
        4 => 'grid-cols-2 md:grid-cols-3 lg:grid-cols-4',
        default => 'grid-cols-1 sm:grid-cols-2 lg:grid-cols-3',
    };

    $gapClasses = match ($gap) {
        'none' => 'gap-0',
        'sm' => 'gap-2',
        'lg' => 'gap-8',
        default => 'gap-4',
    };
@endphp

<div {{ $attributes->class(['grid', $columnClasses, $gapClasses]) }}>
    {{ $slot }}
</div>
