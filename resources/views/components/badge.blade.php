@props([
    'color' => 'gray',
    'icon' => null,
])

@php
    $colorClass = match ($color) {
        'blue', 'green', 'yellow', 'red' => "badge-{$color}",
        default => 'badge-gray',
    };
@endphp

<span {{ $attributes->class(['badge gap-1', $colorClass]) }}>
    @if ($icon)
        <x-dynamic-component :component="'lucide-'.$icon" class="size-3" />
    @endif
    {{ $slot }}
</span>
