@aware(['perView' => 1, 'gap' => 'md'])

@props(['thumbnail' => null])

@php
    $basis = match ((int) $perView) {
        2 => 'basis-full sm:basis-1/2',
        3 => 'basis-full sm:basis-1/2 lg:basis-1/3',
        default => 'basis-full',
    };

    $padding = match ($gap) {
        'none' => '',
        'sm' => 'px-1',
        'lg' => 'px-4',
        default => 'px-2',
    };
@endphp

<div
    data-carousel-slide
    @if ($thumbnail) data-thumbnail="{{ $thumbnail }}" @endif
    role="group"
    aria-roledescription="slide"
    {{ $attributes->class(['shrink-0 grow-0 snap-start', $basis, $padding]) }}
>
    {{ $slot }}
</div>
