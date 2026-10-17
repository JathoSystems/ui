@props([
    'color' => 'secondary',
    'size' => 'md',
    'href' => null,
    'type' => 'button',
    'navigate' => false,
])

@php
    $colorClasses = match ($color) {
        'primary' => 'bg-blue-500 hover:bg-blue-600 text-white border-blue-400',
        'danger' => 'bg-red-600 hover:bg-red-700 text-white border-red-700',
        'warning' => 'bg-yellow-500 hover:bg-yellow-600 text-white border-yellow-600',
        'success' => 'bg-green-600 hover:bg-green-700 text-white border-green-700',
        'neutral' => 'bg-gray-500 hover:bg-gray-600 text-white border-gray-600',
        default => 'bg-gray-100 dark:bg-zinc-900 hover:bg-gray-200 dark:hover:bg-zinc-950',
    };

    $sizeClasses = match ($size) {
        'sm' => 'px-2 py-1 text-xs',
        'lg' => 'px-4 py-3 text-base',
        default => '',
    };

    $classes = "btn-base {$colorClasses} {$sizeClasses} disabled:opacity-75 disabled:cursor-not-allowed data-loading:opacity-75 data-loading:pointer-events-none";
@endphp

@if ($href)
    <a href="{{ $href }}" @if ($navigate) wire:navigate @endif {{ $attributes->class($classes) }}>
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->class($classes) }}>
        {{ $slot }}
    </button>
@endif
