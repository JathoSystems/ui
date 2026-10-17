@props(['size' => 'md'])

@php
    $iconSize = match ($size) {
        'sm' => 'size-4',
        'lg' => 'size-12',
        default => 'size-8',
    };
@endphp

<div {{ $attributes->class('flex items-center justify-center h-64') }} role="status" aria-label="{{ __('ui::ui.loading') }}">
    <x-lucide-loader-circle @class(['animate-spin', $iconSize]) />
</div>
