@props([
    'columns' => 3,
    'gap' => 'md',
])

{{--
    Masonry layout using CSS columns. Wrap each child in <x-ui::masonry.item>.

    <x-ui::masonry columns="3" gap="md">
        <x-ui::masonry.item><x-ui::media ... /></x-ui::masonry.item>
    </x-ui::masonry>

    columns: 2 | 3 | 4   gap: none | sm | md | lg
--}}

@php
    $columnClasses = match ((int) $columns) {
        2 => 'columns-1 sm:columns-2',
        4 => 'columns-2 md:columns-3 lg:columns-4',
        default => 'columns-1 sm:columns-2 lg:columns-3',
    };

    $gapClasses = match ($gap) {
        'none' => 'gap-0',
        'sm' => 'gap-2',
        'lg' => 'gap-8',
        default => 'gap-4',
    };
@endphp

<div {{ $attributes->class([$columnClasses, $gapClasses]) }}>
    {{ $slot }}
</div>
